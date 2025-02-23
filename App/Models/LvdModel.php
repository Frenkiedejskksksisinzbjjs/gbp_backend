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

            if (empty($Data['Adresse'])) {
                echo json_encode(['error' => 'Adresse requise']);
                return;
            }

            // Démarrer une transaction
            $pdo = $this->db->getPdo();
            $pdo->beginTransaction();

            // Vérifier si le client a un abonnement payé
            $anneeActuelle = date('Y');
            $sql = "SELECT id FROM abonnement WHERE Id_clients = :idclient AND Annee_abonnement = :anneeActuelle And status = 'paye' LIMIT 1";
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
            $stmt->bindParam(':adresse', $Data['Adresse'], PDO::PARAM_STR);
            $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->execute();

            // Insérer dans la table detailts_paiement
            $sql = "INSERT INTO detailts_paiement (Id_paiement, Categories, Methode_paiement, Wallet, Numero_wallet, 
                    Numero_cheque, Nom_bank, reference, created_at, created_by) 
                VALUES (:id_paiement, 'livraison_a_domicile', :methode, :wallet, :numero_wallet, 
                    :numero_cheque, :nom_bank, :reference, NOW(), :idUser)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_paiement', $paiement['id'], PDO::PARAM_INT);
            $stmt->bindParam(':methode', $Data['Methode_paiement'], PDO::PARAM_STR);
            $stmt->bindParam(':wallet', $Data['Wallet'], PDO::PARAM_STR);
            $stmt->bindParam(':numero_wallet', $Data['Numero_wallet'], PDO::PARAM_STR);
            $stmt->bindParam(':numero_cheque', $Data['Numero_cheque'], PDO::PARAM_STR);
            $stmt->bindParam(':nom_bank', $Data['Nom_bank'], PDO::PARAM_STR);
            $stmt->bindParam(':reference', $Data['reference'], PDO::PARAM_STR);
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

    
}
