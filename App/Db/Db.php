<?php

namespace App\Db;

use PDO;
use PDOException;

class Db {
    private $host = 'localhost'; // Changez cela avec le nom d'hôte de votre base de données
    private $db   = 'gbp2'; // Changez cela avec le nom de votre base de données
    private $user = 'root'; // Changez cela avec votre nom d'utilisateur
    private $pass = ''; // Changez cela avec votre mot de passe
    private $charset = 'utf8mb4';
    private $pdo;
    private $error;

    public function __construct() {
        // DSN (Data Source Name)
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";

        // Options pour PDO
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        // Création d'une instance PDO
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo "Erreur de connexion : " . $this->error;
        }
    }
   public function getPdo(){
    return $this->pdo;
   }
}
