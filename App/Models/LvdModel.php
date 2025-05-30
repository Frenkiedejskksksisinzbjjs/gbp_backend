<?php

namespace App\Models;

use App\Db\Db;
use PDO;
use PDOException;

class LvdModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    public function AddLvdClients($idclient, $idUser, $data)
    {
        try {
            $Data = json_decode($data, true);

            // Vérification des IDs
            if (empty($idclient) || !is_numeric($idclient)) {
                echo json_encode(['error' => 'ID client invalide']);
                return;
            }

            if (empty($idUser) || !is_numeric($idUser)) {
                echo json_encode(['error' => 'ID utilisateur invalide']);
                return;
            }

            if (empty($Data['Adresse_Livraison_Domicile'])) {
                echo json_encode(['error' => 'Adresse requise']);
                return;
            }

            // Démarrer une transaction
            $pdo = $this->db->getPdo();
            $pdo->beginTransaction();

            // Vérifier si le client a un abonnement payé
            $anneeActuelle = date('Y');
            $sql = "SELECT id FROM abonnement WHERE Id_client = :idclient AND Annee_abonnement = :anneeActuelle And status = 'paye' LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':idclient', $idclient, PDO::PARAM_INT);
            $stmt->bindParam(':anneeActuelle', $anneeActuelle, PDO::PARAM_INT);
            $stmt->execute();
            $abonnement = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$abonnement) {
                $pdo->rollBack();
                echo json_encode(['error' => 'Le client doit régler son abonnement avant d\'ajouter une livraison.']);
                return;
            }

            // Récupérer l'ID de paiement correspondant à l'année actuelle
            $sql = "SELECT id FROM paiement WHERE id_abonnement = :id_abonnement LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_abonnement', $abonnement['id'], PDO::PARAM_INT);
            $stmt->execute();
            $paiement = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$paiement) {
                $pdo->rollBack();
                echo json_encode(['error' => 'Aucun paiement trouvé pour cet abonnement.']);
                return;
            }

            // Insérer dans la table lvdomcile
            $sql = "INSERT INTO lvdomcile (Id_clients, Adresse, Date, created_by) 
                VALUES (:idclient, :adresse, NOW(), :idUser)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':idclient', $idclient, PDO::PARAM_INT);
            $stmt->bindParam(':adresse', $Data['Adresse_Livraison_Domicile'], PDO::PARAM_STR);
            $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->execute();

            // Insérer dans la table detailts_paiement
            $sql = "INSERT INTO details_paiements (Id_paiement, Categories,Montant, Methode_paiement, Wallet, Numero_wallet, 
                    Numero_cheque, Nom_bank, reference, created_at, created_by) 
                VALUES (:id_paiement, 'livraison_a_domicile',:montant, :methode, :wallet, :numero_wallet, 
                    :numero_cheque, :nom_bank, :reference, NOW(), :idUser)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_paiement', $paiement['id'], PDO::PARAM_INT);
            $stmt->bindParam(':methode', $Data['Methode_de_paiement'], PDO::PARAM_STR);
            $stmt->bindParam(':montant', $Data['Montant'], PDO::PARAM_STR);
            $stmt->bindParam(':wallet', $Data['Wallet'], PDO::PARAM_STR);
            $stmt->bindParam(':numero_wallet', $Data['Numero_wallet'], PDO::PARAM_STR);
            $stmt->bindParam(':numero_cheque', $Data['Numero_cheque'], PDO::PARAM_STR);
            $stmt->bindParam(':nom_bank', $Data['Nom_bank'], PDO::PARAM_STR);
            $stmt->bindParam(':reference', $Data['ReferenceId'], PDO::PARAM_STR);
            $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->execute();

            // Valider la transaction
            $pdo->commit();

            echo json_encode(['success' => 'La livraison à domicile et son paiement ont été enregistrés avec succès.']);
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $pdo->rollBack();
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }

    public function getLastReferenceLvd()
    {
        try {
            $pdo = $this->db->getPdo();

            $sql = "SELECT reference 
                    FROM details_paiements 
                    WHERE Categories = 'livraison_a_domicil'
                    ORDER BY 
                        SUBSTRING_INDEX(reference, '/', -1) DESC, 
                        CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(reference, '/', 2), '/', -1) AS UNSIGNED) DESC
                    LIMIT 1";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode(["reference" => $result ? $result['reference'] : null]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }

    public function GetLDVInfo($id)
    {
        try {
            $pdo = $this->db->getPdo();
            $sql = "SELECT * from lvdomcile Where Id_clients =:id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
            } else {
                echo json_encode(['error' => 'Cette client N\'a pas des Livraison à domicile']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }

    public function GetToDayActivityLD()
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête pour récupérer tous les clients avec leurs informations supplémentaires
            $sql = "
                   SELECT DISTINCT 
                    c.*, 
                    l.Adresse as Adresse_livraison, 
                    a.Status AS abonnement_status, 
                    u.Nom AS Agent,
                    l.Date AS 'Date_creation',
                    SUM(a.Penalite) AS abonnement_penalite, 
                    MAX(a.Annee_abonnement) AS annee_abonnement
                    FROM clients c
                    JOIN lvdomcile l ON l.Id_clients = c.id
                    JOIN abonnement a ON a.Id_client = c.id
                    JOIN users u ON l.created_by = u.id
                    WHERE l.Date = CURRENT_DATE
                    GROUP BY c.id, a.Status, u.Nom, l.Adresse, l.Date;
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier si des clients existent
            if (!$clients) {
                echo json_encode(['message' => 'Aucun client trouvé.']);
            }

            echo json_encode($clients);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }
    
    public function GetToDayActivityLDById($id)
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête pour récupérer tous les clients avec leurs informations supplémentaires
            $sql = "
                 SELECT DISTINCT 
                        c.*, 
                        l.Adresse AS Adresse_livraison, 
                        a.Status AS abonnement_status, 
                        u.Nom AS Agent,
                        l.Date AS Date_creation,
                        SUM(a.Penalite) AS abonnement_penalite, 
                        MAX(a.Annee_abonnement) AS annee_abonnement,
                        b.Numero AS boite_postal_numero
                    FROM clients c
                    JOIN lvdomcile l ON l.Id_clients = c.id
                    JOIN abonnement a ON a.Id_client = c.id
                    JOIN users u ON l.created_by = u.id
                    LEFT JOIN boit_postal b ON c.Id_boite_postale = b.id
                    WHERE l.Date = CURRENT_DATE 
                      AND l.created_by = :id
                    GROUP BY 
                        c.id, 
                        l.Adresse, 
                        a.Status, 
                        u.Nom, 
                        l.Date, 
                        b.Numero;

            ";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier si des clients existent
            if (!$clients) {
                echo json_encode(['error' => 'Aucun client trouvé.']);
            }
            //   var_dump($clients);
            // echo json_encode($clients);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }
    public function GetAllActivityLD()
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête pour récupérer tous les clients avec leurs informations supplémentaires
            $sql = "
                 SELECT DISTINCT 
                        c.*, 
                        l.Adresse AS Adresse_livraison, 
                        a.Status AS abonnement_status, 
                        u.Nom AS Agent,
                        l.Date AS Date_creation,
                        SUM(a.Penalite) AS abonnement_penalite, 
                        MAX(a.Annee_abonnement) AS annee_abonnement,
                        b.Numero AS boite_postal_numero
                    FROM clients c
                    JOIN lvdomcile l ON l.Id_clients = c.id
                    JOIN abonnement a ON a.Id_client = c.id
                    JOIN users u ON l.created_by = u.id
                    LEFT JOIN boit_postal b ON c.Id_boite_postale = b.id
                    GROUP BY 
                        c.id, 
                        l.Adresse, 
                        a.Status, 
                        u.Nom, 
                        l.Date, 
                        b.Numero;

            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier si des clients existent
            if (!$clients) {
                echo json_encode(['error' => 'Aucun client trouvé.']);
            }
            //   var_dump($clients);
            echo json_encode($clients);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }
}
