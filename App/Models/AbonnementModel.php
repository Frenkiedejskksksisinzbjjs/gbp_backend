<?php

namespace App\Models;

use App\Db\Db;
use PDO;
use PDOException;

class AbonnementModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    public function PaidAbonnement($IdClient, $idUser, $data)
    {
        try {
            $Data = json_decode($data, true);
            $pdo = $this->db->getPdo();
            $pdo->beginTransaction();

            // Récupérer tous les abonnements impayés du client
            $sql = "SELECT id, Montant, Penalite FROM abonnement WHERE Id_client = :IdClient AND status = 'impayé'";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':IdClient', $IdClient, PDO::PARAM_INT);
            $stmt->execute();
            $abonnements = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$abonnements) {
                $pdo->rollBack();
                echo json_encode(['error' => 'Aucun abonnement impayé trouvé.']);
                return;
            }

            // Calcul du montant total à payer
            $totalAmount = 0;
            $abonnementIds = [];

            foreach ($abonnements as $abonnement) {
                $montantTotal = $abonnement['Montant'] + $abonnement['Penalite'];
                $totalAmount += $montantTotal;
                $abonnementIds[] = $abonnement['id'];
            }

            // Vérifier si le montant saisi correspond au montant total
            if ($Data['Montant'] != $totalAmount) {
                $pdo->rollBack();
                echo json_encode(['error' => 'Le montant payé ne correspond pas au montant total dû.']);
                return;
            }

            // Mise à jour du statut des abonnements en "payé"
            $sql = "UPDATE abonnement SET status = 'paye' WHERE id IN (" . implode(',', $abonnementIds) . ")";
            $pdo->exec($sql);

            // Mise à jour du statut des pénalités en "payé"
            $sql = "UPDATE penaliter SET status = 'paye' WHERE Abonnement_id IN (" . implode(',', $abonnementIds) . ")";
            $pdo->exec($sql);

            // Enregistrer le paiement
            $sql = "INSERT INTO paiement (Id_abonnement, Methode_paiement, Wallet, Numero_wallet, Numero_cheque, Nom_bank, reference, created_at, created_by) 
                    VALUES (:IdAbonnement, :Methode, :Wallet, :NumeroWallet, :NumeroCheque, :NomBank, :Reference, NOW(), :CreatedBy)";
            $stmt = $pdo->prepare($sql);
            foreach ($abonnementIds as $idAbonnement) {
                $stmt->bindParam(':IdAbonnement', $idAbonnement, PDO::PARAM_INT);
                $stmt->bindParam(':Methode', $Data['Methode_de_paiement'], PDO::PARAM_STR);
                $stmt->bindParam(':Wallet', $Data['Wallet'], PDO::PARAM_STR);
                $stmt->bindParam(':NumeroWallet', $Data['Numero_wallet'], PDO::PARAM_STR);
                $stmt->bindParam(':NumeroCheque', $Data['Numero_cheque'], PDO::PARAM_STR);
                $stmt->bindParam(':NomBank', $Data['Nom_bank'], PDO::PARAM_STR);
                $stmt->bindParam(':Reference', $Data['ReferenceId'], PDO::PARAM_STR);
                $stmt->bindParam(':CreatedBy', $idUser, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Valider la transaction
            $pdo->commit();

            echo json_encode(['success' => 'Le paiement des abonnements a été effectué avec succès.']);
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }

    public function SelectionsLesMontantsImaper($IdClient)
    {
        try {
            $pdo = $this->db->getPdo();

            // Récupérer tous les abonnements impayés du client
            $sql = "SELECT id, Montant, Penalite FROM abonnement WHERE Id_client  = :IdClient AND status = 'impayé'";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':IdClient', $IdClient, PDO::PARAM_INT);
            $stmt->execute();
            $abonnements = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$abonnements) {
                http_response_code(400);
                echo json_encode(['error' => 'Aucun abonnement impayé trouvé.']);
                return;
            }

            // Calcul du montant total à payer
            $totalAmount = 0;

            foreach ($abonnements as $abonnement) {
                $totalAmount += $abonnement['Montant'] + $abonnement['Penalite'];
            }

            echo json_encode(["MontantApayer" => $totalAmount]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }

    public function SelectUnpaiedPaiement($idClients)
    {
        try {
            $pdo = $this->db->getPdo();

            // Sélectionner les années d'abonnement impayées
            $sql = "SELECT Annee_abonnement FROM abonnement WHERE Id_client = :idClients AND Status = 'impaye'";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':idClients', $idClients, PDO::PARAM_INT);
            $stmt->execute();
            $abonnements = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérification s'il y a des abonnements impayés
            if (!$abonnements) {
                echo json_encode(['message' => 'Ce client n\'a pas d\'abonnement à payer.']);
                return;
            }

            // Construction du message avec les années impayées
            $annees = array_column($abonnements, 'Annee_abonnement');
            $message = 'Redevance de ' . implode(', ', $annees) . ' non payée.';

            echo json_encode(['message' => $message, 'annees_impayees' => $annees]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }

    public function getLastReferenceRdv()
    {
        try {
            $pdo = $this->db->getPdo();

            $sql = "SELECT reference 
                    FROM paiement 
                    -- WHERE Categories = 'livraison_a_domicil'
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

    public function GetFactureClient($id)
    {
        try {
            $pdo = $this->db->getPdo();
            $sql = "SELECT 
                        a.Annee_abonnement As Redevance, 
                        c.Nom AS Nom, 
                        p.reference AS Reference, 
                        p.Methode_paiement AS Methode_de_paiement_anne, 
                        a.Montant AS Montant_Redevance, 
                        p.Wallet AS Wallet_de_redevance, 
                        p.Numero_wallet AS Numero_Telephone, 
                        p.Nom_bank AS Banque, 
                        p.Numero_cheque AS Numero_banque, 
                        dp.Categories AS Categorie,
                        dp.Methode_paiement AS Methode_Paiement_Categorie, 
                        dp.Wallet AS Wallet_de_Categorie, 
                        dp.Numero_wallet AS Numero_Telephone_categorie, 
                        dp.Nom_bank AS Nom_banque_categorie, 
                        dp.Numero_cheque AS Numero_banque_categorie, 
                        dp.Montant AS Montant_categorie
                    FROM abonnement a
                    JOIN clients c ON a.Id_client = c.id
                    JOIN paiement p ON p.Id_abonnement = a.id
                    LEFT JOIN  details_paiements dp ON dp.Id_paiement = p.id
                    WHERE c.id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
            } else {
                echo json_encode(['error' => 'Aucune facture trouvée pour ce client']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }


    public function GetToDayActivity()
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête pour récupérer tous les clients avec leurs informations supplémentaires
            $sql = "
                SELECT  DISTINCT 
                    c.*, 
                    a.Status AS abonnement_status, 
                    u.Nom AS Agent, 
                    SUM(a.Penalite) AS abonnement_penalite, 
                    MAX(a.Annee_abonnement) AS annee_abonnement, 
                    b.Numero AS boite_postal_numero, 
                    (SELECT COUNT(*) FROM sous_couverte sc WHERE sc.Id_client = c.id) AS nombre_sous_couverte,
                    (SELECT COUNT(*) FROM lvdomcile L WHERE L.Id_clients = c.id) AS Adresse_Livraison,
                    (SELECT COUNT(*) FROM collections Cl WHERE Cl.Id_clients = c.id) AS Adresse_Collection
                FROM clients c
                LEFT JOIN abonnement a ON c.id = a.Id_client
                LEFT JOIN boit_postal b ON c.Id_boite_postale  = b.id
                LEFT JOIN users u ON a.updated_by = u.id
                 WHERE c.id not in (SELECT Id_client from resilier)
                AND a.updated_at = CURRENT_DATE
                GROUP BY c.id, b.Numero;
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
    public function GetToDayActivityById($id)
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête pour récupérer tous les clients avec leurs informations supplémentaires
            $sql = "
                    SELECT DISTINCT 
                    c.*, 
                    a.Status AS abonnement_status, 
                    u.Nom AS Agent, 
                    SUM(a.Penalite) AS abonnement_penalite, 
                    MAX(a.Annee_abonnement) AS annee_abonnement, 
                    b.Numero AS boite_postal_numero, 
                    (
                        SELECT COUNT(*) 
                        FROM sous_couverte sc 
                        WHERE sc.Id_client = c.id
                    ) AS nombre_sous_couverte,
                    (
                        SELECT COUNT(*) 
                        FROM lvdomcile l 
                        WHERE l.Id_clients = c.id
                    ) AS Adresse_Livraison,
                    (
                        SELECT COUNT(*) 
                        FROM collections cl 
                        WHERE cl.Id_clients = c.id
                    ) AS Adresse_Collection
                FROM clients c
                LEFT JOIN abonnement a ON c.id = a.Id_client
                LEFT JOIN boit_postal b ON c.Id_boite_postale = b.id
                LEFT JOIN users u ON a.updated_by = u.id
                WHERE c.id NOT IN (SELECT Id_client FROM resilier)
                AND a.updated_by = :id
                AND DATE(a.updated_at) = CURRENT_DATE
                GROUP BY 
                    c.id, a.Status, u.Nom, b.Numero;

            ";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
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
    public function GetAllActivityRdv()
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête pour récupérer tous les clients avec leurs informations supplémentaires
            $sql = "
                    SELECT DISTINCT 
                    c.*, 
                    a.Status AS abonnement_status, 
                    u.Nom AS Agent, 
                    SUM(a.Penalite) AS abonnement_penalite, 
                    MAX(a.Annee_abonnement) AS annee_abonnement, 
                    b.Numero AS boite_postal_numero, 
                    (
                        SELECT COUNT(*) 
                        FROM sous_couverte sc 
                        WHERE sc.Id_client = c.id
                    ) AS nombre_sous_couverte,
                    (
                        SELECT COUNT(*) 
                        FROM lvdomcile l 
                        WHERE l.Id_clients = c.id
                    ) AS Adresse_Livraison,
                    (
                        SELECT COUNT(*) 
                        FROM collections cl 
                        WHERE cl.Id_clients = c.id
                    ) AS Adresse_Collection
                FROM clients c
                LEFT JOIN abonnement a ON c.id = a.Id_client
                LEFT JOIN boit_postal b ON c.Id_boite_postale = b.id
                LEFT JOIN users u ON a.updated_by = u.id
                WHERE c.id NOT IN (SELECT Id_client FROM resilier)
                GROUP BY 
                    c.id, a.Status, u.Nom, b.Numero;

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
