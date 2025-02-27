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

            if (empty($Data['SousCouverte']) || !is_array($Data['SousCouverte'])) {
                echo json_encode(['error' => 'Les sous-couvertes sont requises et doivent être sous forme de tableau.']);
                return;
            }

            $pdo = $this->db->getPdo();
            $pdo->beginTransaction();

            // Vérifier si le client a un abonnement payé
            $anneeActuelle = date('Y');
            $sql = "SELECT id FROM abonnement WHERE Id_clients = :idclient AND Annee_abonnement = :anneeActuelle AND status = 'paye' LIMIT 1";
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
            $nombreAjout = count($Data['SousCouverte']);
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

            foreach ($Data['SousCouverte'] as $sousCouverte) {
                $stmt->bindParam(':nom_societe', $sousCouverte['Nom_societe'], PDO::PARAM_STR);
                $stmt->bindParam(':nom_personne', $sousCouverte['Nom_personne'], PDO::PARAM_STR);
                $stmt->bindParam(':telephone', $sousCouverte['Telephone'], PDO::PARAM_STR);
                $stmt->bindParam(':adresse', $sousCouverte['Adresse'], PDO::PARAM_STR);
                $stmt->bindParam(':idclient', $idclient, PDO::PARAM_INT);
                $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Insérer dans la table details_paiement
            $sql = "INSERT INTO detailts_paiement (Id_paiement, Categories, Methode_paiement, Wallet, Numero_wallet, 
                Numero_cheque, Nom_bank, reference, created_at, created_by) 
            VALUES (:id_paiement, 'sous_couverte', :methode, :wallet, :numero_wallet, 
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

            echo json_encode(['success' => 'Les sous-couvertes et leur paiement ont été enregistrés avec succès.']);
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $pdo->rollBack();
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }

    
}
