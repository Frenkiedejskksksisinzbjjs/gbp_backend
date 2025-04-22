<?php

namespace App\Models;

use App\Db\Db;
use PDO;
use PDOException;

class ExonorerModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    public function ExonorerClients($idclient, $idUser)
    {
        try {
            // Vérifier si les IDs sont valides
            if (empty($idclient) || !is_numeric($idclient)) {
                echo json_encode(['error' => 'ID client invalide']);
                return;
            }

            if (empty($idUser) || !is_numeric($idUser)) {
                echo json_encode(['error' => 'ID utilisateur invalide']);
                return;
            }

            // Démarrer la transaction
            $pdo = $this->db->getPdo();
            $pdo->beginTransaction();

            // Mettre à jour le statut de l'abonnement
            $sql1 = 'UPDATE abonnement SET Status = "exonorer" WHERE Id_client = :id';
            $stmt1 = $pdo->prepare($sql1);
            $stmt1->bindParam(':id', $idclient, PDO::PARAM_INT);
            $stmt1->execute();

            // Insérer l'exonération dans la table "exonore"
            $sql2 = "INSERT INTO exonore (Id_client, Date, created_by) VALUES (:idclient, NOW(), :idUser)";
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->bindParam(':idclient', $idclient, PDO::PARAM_INT);
            $stmt2->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmt2->execute();

            // Vérifier les deux exécutions avant de valider
            if ($stmt1->rowCount() > 0 && $stmt2->rowCount() > 0) {
                $pdo->commit();
                echo json_encode(['success' => 'Le client a été exonéré avec succès']);
            } else {
                // Une des deux opérations n'a rien modifié => rollback
                $pdo->rollBack();
                echo json_encode(['error' => 'Échec de l\'exonération. Le client est peut-être déjà exonéré.']);
            }
        } catch (PDOException $e) {
            // En cas d'erreur, rollback et affichage du message
            if (isset($pdo)) {
                $pdo->rollBack();
            }
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }



    public function AllClientExonorer()
    {
        try {
            // Requête pour récupérer tous les clients exonérés avec les détails
            $sql = "SELECT  DISTINCT 
                    c.*,e.*, 
                    a.Status AS abonnement_status, 
                    SUM(a.Penalite) AS abonnement_penalite, 
                    MAX(a.Annee_abonnement) AS annee_abonnement, 
                    b.Numero AS boite_postal_numero, 
                    (SELECT COUNT(*) FROM sous_couverte sc WHERE sc.Id_client = c.id) AS nombre_sous_couverte,
                    (SELECT COUNT(*) FROM lvdomcile L WHERE L.Id_clients = c.id) AS Adresse_Livraison,
                    (SELECT COUNT(*) FROM collections Cl WHERE Cl.Id_clients = c.id) AS Adresse_Collection,
                    u.Nom as Agents
                FROM exonore e
                JOIN clients c ON e.Id_client = c.id
                JOIN users u ON e.created_by = u.id
                LEFT JOIN abonnement a ON c.id = a.Id_client
                LEFT JOIN boit_postal b ON c.Id_boite_postale  = b.id
                GROUP BY c.id, b.Numero;                    ";

            // Préparer et exécuter la requête
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();

            // Récupérer tous les résultats sous forme de tableau associatif
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier si des résultats existent
            if (!empty($clients)) {
                echo json_encode($clients);
            } else {
                echo json_encode(['error' => 'Aucun client exonéré trouvé']);
            }
        } catch (PDOException $e) {
            // Gestion des erreurs PDO
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }
}
