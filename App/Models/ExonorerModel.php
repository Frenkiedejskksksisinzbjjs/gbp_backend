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
            $sql = "SELECT c.*,d.*, e.Date AS date_exonoration, u.Nom As agents
                    FROM clients c
                    INNER JOIN exonore e ON c.id = e.Id_client
                    INNER JOIN users u ON u.id = e.created_by
                    INNER JOIN documents d ON d.id_client = c.id
                    ORDER BY e.Date DESC";

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
