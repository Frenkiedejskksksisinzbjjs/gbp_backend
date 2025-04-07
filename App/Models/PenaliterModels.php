<?php

namespace App\Models;

use App\Db\Db;
use PDO;
use PDOException;

class PenaliterModels
{

    private $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    public function GetAllPenaliter()
    {
        try {
            $pdo = $this->db->getPdo();

            // Sélectionner toutes les pénalités
            $sql = "SELECT * FROM penaliter";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $penalites = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier s'il y a des pénalités
            if (!$penalites) {
                echo json_encode(['message' => 'Aucune pénalité trouvée.']);
                return;
            }

            // Retourner les pénalités en JSON
            echo json_encode(['penalites' => $penalites]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }


    public function EnleverPenaliter($idclient)
    {
        try {
            $pdo = $this->db->getPdo();

            // Vérifier si l'abonné a une pénalité et un statut "impayé"
            $checkSql = "SELECT * FROM abonnement WHERE Id_client = :id_abonne AND penalite > 0 AND Status = 'impayé'";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute(['id_abonne' => $idclient]);

            if ($checkStmt->rowCount() > 0) {
                // Si condition respectée, mettre penalite à 0
                $updateSql = "UPDATE abonnement SET penalite = 0 WHERE Id_client = :id_abonne";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->execute(['id_abonne' => $idclient]);

                echo json_encode(['success' => 'Pénalité supprimée avec succès.']);
            } else {
                echo json_encode(['error' => 'Aucune pénalité à supprimer ou statut payé.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }
}
