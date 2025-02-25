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

    

}
