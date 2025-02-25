<?php

namespace App\Models;

use App\Db\Db;
use PDO;
use PDOException;

class BoitPostaleModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    public function GetNextBoitePostal()
    {
        try {
            $pdo = $this->db->getPdo();

            // Récupérer le dernier numéro inséré
            $sql = "SELECT Numero FROM boit_postal ORDER BY Numero DESC LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $boite = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si la table est vide, commencer à 1, sinon incrémenter
            $nouveauNumero = $boite ? $boite['Numero'] + 1 : 1;

            // Retourner le nouveau numéro
            return json_encode(['nouveau_numero' => $nouveauNumero]);
        } catch (PDOException $e) {
            return json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }
}
