<?php

namespace App\Models;

use App\Db\Db;
use PDO;
use PDOException;

class SousCouverteModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    public function AddSousCouverteClients($idclient, $idUser, $data)
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

            if (empty($Data['sousCouvertures']) || !is_array($Data['sousCouvertures'])) {
                echo json_encode(['error' => 'Les sous-couvertes sont requises et doivent être sous forme de tableau.']);
                return;
            }

            $pdo = $this->db->getPdo();
            $pdo->beginTransaction();

            // Vérifier si le client a un abonnement payé
            $anneeActuelle = date('Y');
            $sql = "SELECT id FROM abonnement WHERE Id_client = :idclient AND Annee_abonnement = :anneeActuelle AND status = 'paye' LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':idclient', $idclient, PDO::PARAM_INT);
            $stmt->bindParam(':anneeActuelle', $anneeActuelle, PDO::PARAM_INT);
            $stmt->execute();
            $abonnement = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$abonnement) {
                $pdo->rollBack();
                echo json_encode(['error' => 'Le client doit régler son abonnement avant d\'ajouter une sous-couverte.']);
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

            // Vérifier combien de sous-couvertes le client a déjà
            $sql = "SELECT COUNT(*) as total FROM sous_couverte WHERE Id_client = :idclient";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':idclient', $idclient, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $nombreActuel = $result['total'] ?? 0;

            // Vérifier si l'ajout dépasse la limite
            $nombreAjout = count($Data['sousCouvertures']);
            $limiteMax = 5;
            $nouveauTotal = $nombreActuel + $nombreAjout;

            if ($nouveauTotal > $limiteMax) {
                $reste = $limiteMax - $nombreActuel;
                if ($reste <= 0) {
                    echo json_encode(['error' => 'Le client a atteint la limite de 5 sous-couvertes.']);
                } else {
                    echo json_encode(['error' => "Le client ne peut ajouter que $reste sous-couverte(s) supplémentaire(s)."]);
                }
                $pdo->rollBack();
                return;
            }

            // Insérer les sous-couvertes
            $sql = "INSERT INTO sous_couverte (Nom_societe, Nom_personne, Telephone, Adresse, Id_client, Created_by, date) 
                VALUES (:nom_societe, :nom_personne, :telephone, :adresse, :idclient, :idUser, NOW())";
            $stmt = $pdo->prepare($sql);

            foreach ($Data['sousCouvertures'] as $sousCouverte) {
                $stmt->bindParam(':nom_societe', $sousCouverte['societe'], PDO::PARAM_STR);
                $stmt->bindParam(':nom_personne', $sousCouverte['personne'], PDO::PARAM_STR);
                $stmt->bindParam(':telephone', $sousCouverte['telephone'], PDO::PARAM_STR);
                $stmt->bindParam(':adresse', $sousCouverte['adresse'], PDO::PARAM_STR);
                $stmt->bindParam(':idclient', $idclient, PDO::PARAM_INT);
                $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Insérer dans la table details_paiement
            $sql = "INSERT INTO details_paiements (Id_paiement, Categories, Montant, Methode_paiement, Wallet, Numero_wallet, 
                Numero_cheque, Nom_bank, reference, created_at, created_by) 
            VALUES (:id_paiement, 'sous_couverte', :montant, :methode, :wallet, :numero_wallet, 
                :numero_cheque, :nom_bank, :reference, NOW(), :idUser)";
            $stmt = $pdo->prepare($sql);

            $walletValue = isset($Data['Wallet']) ? $Data['Wallet'] : null;

            $stmt->bindParam(':id_paiement', $paiement['id'], PDO::PARAM_INT);
            $stmt->bindParam(':methode', $Data['Methode_de_paiement'], PDO::PARAM_STR);
            $stmt->bindParam(':montant', $Data['totalMontant'], PDO::PARAM_STR);
            $stmt->bindParam(':wallet', $walletValue, PDO::PARAM_STR);
            $stmt->bindParam(':numero_wallet', $Data['Numero_wallet'], PDO::PARAM_STR);
            $stmt->bindParam(':numero_cheque', $Data['Numero_cheque'], PDO::PARAM_STR);
            $stmt->bindParam(':nom_bank', $Data['Nom_Banque'], PDO::PARAM_STR);
            $stmt->bindParam(':reference', $Data['ReferenceId'], PDO::PARAM_STR);
            $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->execute();

            // Valider la transaction
            $pdo->commit();

            echo json_encode(['success' => 'Les sous-couvertes et leur paiement ont été enregistrés avec succès.']);
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $pdo->rollBack();
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }



    public function getLastReferenceAjoutSousCouvette()
    {
        try {
            $pdo = $this->db->getPdo();

            $sql = "SELECT reference 
                    FROM details_paiements 
                    WHERE Categories = 'sous_couverte'
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

    public function GetSousCouvertInfo($id)
    {
        try {
            $pdo = $this->db->getPdo();
            $sql = "SELECT * from sous_couverte Where Id_client =:id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
            } else {
                echo json_encode(['error' => 'Cette client N\'a pas des sousCouverte']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }
    public function GetToDayActivitySousCouverte()
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête pour récupérer tous les clients avec leurs informations supplémentaires
            $sql = "
                    SELECT DISTINCT 
                    c.*, 
                    S.Nom_societe, 
                    S.Nom_personne, 
                    S.Telephone, 
                    S.Adresse,
                    a.Status AS abonnement_status, 
                    u.Nom AS Agent, 
                    SUM(a.Penalite) AS abonnement_penalite, 
                    MAX(a.Annee_abonnement) AS annee_abonnement
                FROM clients c
                LEFT JOIN abonnement a ON c.id = a.Id_client
                LEFT JOIN sous_couverte S ON c.id = S.Id_client
                LEFT JOIN boit_postal b ON c.Id_boite_postale = b.id
                LEFT JOIN users u ON a.updated_by = u.id
                WHERE c.id NOT IN (SELECT Id_client FROM resilier)
                AND S.Date = CURRENT_DATE
                GROUP BY c.id, S.Nom_societe, S.Nom_personne, S.Telephone, S.Adresse, a.Status, u.Nom;
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
    public function GetToDayActivitySousCouverteById($id)
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête pour récupérer tous les clients avec leurs informations supplémentaires
            $sql = "
                    SELECT DISTINCT 
                    c.*, 
                    S.Nom_societe, 
                    S.Nom_personne, 
                    S.Telephone, 
                    S.Adresse,
                    a.Status AS abonnement_status, 
                    u.Nom AS Agent, 
                    SUM(a.Penalite) AS abonnement_penalite, 
                    MAX(a.Annee_abonnement) AS annee_abonnement,
                     b.Numero AS boite_postal_numero
                FROM clients c
                LEFT JOIN abonnement a ON c.id = a.Id_client
                LEFT JOIN sous_couverte S ON c.id = S.Id_client
                LEFT JOIN boit_postal b ON c.Id_boite_postale = b.id
                LEFT JOIN users u ON a.updated_by = u.id
                WHERE c.id NOT IN (SELECT Id_client FROM resilier)
                AND S.Date = CURRENT_DATE and a.updated_by =:id
                GROUP BY c.id, S.Nom_societe, S.Nom_personne, S.Telephone, S.Adresse, a.Status, u.Nom;
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier si des clients existent
            if (!$clients) {
                echo json_encode(['error' => 'Aucun client trouvé.']);
            }

            echo json_encode($clients);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }
    public function GetAllActivitySousCouverte()
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête pour récupérer tous les clients avec leurs informations supplémentaires
            $sql = "
                    SELECT DISTINCT 
                    c.*, 
                    S.Nom_societe, 
                    S.Nom_personne, 
                    S.Telephone, 
                    S.Adresse,
                    a.Status AS abonnement_status, 
                    u.Nom AS Agent, 
                    SUM(a.Penalite) AS abonnement_penalite, 
                    MAX(a.Annee_abonnement) AS annee_abonnement,
                     b.Numero AS boite_postal_numero
                FROM clients c
                LEFT JOIN abonnement a ON c.id = a.Id_client
                LEFT JOIN paiement p ON p.Id_abonnement = a.id
                LEFT JOIN details_paiements D ON D.Id_paiement  = p.id
                LEFT JOIN sous_couverte S ON c.id = S.Id_client
                LEFT JOIN boit_postal b ON c.Id_boite_postale = b.id
                LEFT JOIN users u ON a.updated_by = u.id
                WHERE c.id NOT IN (SELECT Id_client FROM resilier) and D.Categories = 'sous_couverte' 
                GROUP BY c.id, S.Nom_societe, S.Nom_personne, S.Telephone, S.Adresse, a.Status, u.Nom;
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier si des clients existent
            if (!$clients) {
                echo json_encode(['error' => 'Aucun client trouvé.']);
            }

            echo json_encode($clients);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }
}
