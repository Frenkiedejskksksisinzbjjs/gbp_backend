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

            // Récupérer le dernier numéro inséré dans boit_postal
            $sql = "SELECT Numero FROM boit_postal ORDER BY Numero DESC LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $boite = $stmt->fetch(PDO::FETCH_ASSOC);
            $nouveauNumero = $boite ? $boite['Numero'] + 1 : 1;

            // Récupérer tous les numéros résiliés disponibles
            $sqlResilie = "
            SELECT bp.Numero 
            FROM resilier r
            JOIN clients c ON r.Id_client = c.id
            JOIN boit_postal bp ON c.Id_boite_postale = bp.id
            ORDER BY r.Date_resilier ASC";

            $stmtResilie = $pdo->prepare($sqlResilie);
            $stmtResilie->execute();
            $numerosResilies = $stmtResilie->fetchAll(PDO::FETCH_COLUMN); // Récupère un tableau de numéros résiliés

            // Ajouter le prochain numéro disponible dans le tableau des numéros disponibles
            $numerosDisponibles = array_merge([$nouveauNumero], $numerosResilies);

            echo json_encode([
                'numeros_disponibles' => $numerosDisponibles // Un tableau avec le prochain numéro + les numéros résiliés
            ]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }


    public function GetCountOfBpGrandType()
    {
        try {
            $pdo = $this->db->getPdo();

            // Préparer la requête pour compter le nombre de boîtes postales de type "Grand"
            $sql = "SELECT COUNT(*) AS total FROM boit_postal WHERE Type = 'Grand'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            // Récupérer le résultat
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Retourner le nombre total
            echo json_encode(['count' => $result['total']]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }

    public function GetCountOfBpMoyenType()
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête SQL pour compter les boîtes postales de type "Moyen"
            $sql = "SELECT COUNT(*) AS total FROM boit_postal WHERE Type = 'Moyen'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            // Récupérer le résultat
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Retourner le nombre total
            echo json_encode(['count' => $result['total']]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }

    public function GetCountOfBpPetiteType()
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête SQL pour compter les boîtes postales de type "Petite"
            $sql = "SELECT COUNT(*) AS total FROM boit_postal WHERE Type = 'Petite'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            // Récupérer le résultat
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Retourner le nombre total
            echo json_encode(['count' => $result['total']]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }
}
