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
            // Vérifier si l'ID du client est valide
            if (empty($idclient) || !is_numeric($idclient)) {
                echo json_encode(['error' => 'ID client invalide']);
                return;
            }

            // Vérifier si l'ID de l'utilisateur est valide
            if (empty($idUser) || !is_numeric($idUser)) {
                echo json_encode(['error' => 'ID utilisateur invalide']);
                return;
            }

            // Requête pour insérer l'exonération du client
            $sql = "INSERT INTO exonore (Id_client, Date, created_by) VALUES (:idclient, NOW(), :idUser)";

            // Préparation et exécution de la requête
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->bindParam(':idclient', $idclient, PDO::PARAM_INT);
            $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->execute();

            // Vérifier si l'insertion a été effectuée
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => 'Le client a été exonéré avec succès']);
            } else {
                echo json_encode(['error' => 'Aucune modification effectuée. Vérifiez si le client est déjà exonéré.']);
            }
        } catch (PDOException $e) {
            // Gestion des erreurs PDO
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
