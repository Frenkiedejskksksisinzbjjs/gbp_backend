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

    public function PaidAbonnement($idUser, $IdClient, $data)
    {
        try {
            $Data = json_decode($data, true);
            $pdo = $this->db->getPdo();
            $pdo->beginTransaction();

            // Récupérer tous les abonnements impayés du client
            $sql = "SELECT id, Montant, Penalite FROM abonnement WHERE Id_clients = :IdClient AND status = 'impaye'";
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
            if ($Data['MontantPaye'] != $totalAmount) {
                $pdo->rollBack();
                echo json_encode(['error' => 'Le montant payé ne correspond pas au montant total dû.']);
                return;
            }

            // Mise à jour du statut des abonnements en "payé"
            $sql = "UPDATE abonnement SET status = 'paye' WHERE id IN (" . implode(',', $abonnementIds) . ")";
            $pdo->exec($sql);

            // Mise à jour du statut des pénalités en "payé"
            $sql = "UPDATE penalite SET status = 'paye' WHERE Id_abonnement IN (" . implode(',', $abonnementIds) . ")";
            $pdo->exec($sql);

            // Enregistrer le paiement
            $sql = "INSERT INTO paiement (Id_abonnement, Methode_paiement, Wallet, Numero_wallet, Numero_cheque, Nom_bank, reference, created_at, created_by) 
                    VALUES (:IdAbonnement, :Methode, :Wallet, :NumeroWallet, :NumeroCheque, :NomBank, :Reference, NOW(), :CreatedBy)";
            $stmt = $pdo->prepare($sql);
            foreach ($abonnementIds as $idAbonnement) {
                $stmt->bindParam(':IdAbonnement', $idAbonnement, PDO::PARAM_INT);
                $stmt->bindParam(':Methode', $Data['Methode_paiement'], PDO::PARAM_STR);
                $stmt->bindParam(':Wallet', $Data['Wallet'], PDO::PARAM_STR);
                $stmt->bindParam(':NumeroWallet', $Data['Numero_wallet'], PDO::PARAM_STR);
                $stmt->bindParam(':NumeroCheque', $Data['Numero_cheque'], PDO::PARAM_STR);
                $stmt->bindParam(':NomBank', $Data['Nom_bank'], PDO::PARAM_STR);
                $stmt->bindParam(':Reference', $Data['reference'], PDO::PARAM_STR);
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


    public function SelectUnpaiedPaiement($idClients)
    {
        try {
            //code...
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }
}
