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

            // Récupérer les numéros résiliés qui ne sont pas attribués à un autre client actif
            $sqlResilie = "
           SELECT bp.Numero 
            FROM resilier r
            JOIN clients c_resilie ON r.Id_client = c_resilie.id
            JOIN boit_postal bp ON c_resilie.Id_boite_postale = bp.id
            WHERE bp.id NOT IN (
                SELECT DISTINCT c_actif.Id_boite_postale 
                FROM clients c_actif 
                LEFT JOIN resilier r_actif ON c_actif.id = r_actif.Id_client
                WHERE r_actif.Id_client IS NULL -- Seuls les clients non résiliés
            )
            ORDER BY r.Date_resilier ASC;

        ";

            $stmtResilie = $pdo->prepare($sqlResilie);
            $stmtResilie->execute();
            $numerosResilies = $stmtResilie->fetchAll(PDO::FETCH_COLUMN); // Récupère un tableau des numéros résiliés disponibles

            // Ajouter le prochain numéro disponible dans la liste
            $numerosDisponibles = array_merge([$nouveauNumero], $numerosResilies);

            echo json_encode([
                'numeros_disponibles' => $numerosDisponibles, // Tableau des numéros disponibles
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
