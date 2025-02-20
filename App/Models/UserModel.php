<?php
namespace App\Models;

use App\Db\Db;
use PDO;
use PDOException;

class UserModel {

    private $db;

    public function __construct()
    {
      $this->db = new Db();
    }
   

    public function getAllUsers() {
        try {
            // Préparer la requête SQL pour récupérer tous les utilisateurs
            $sql = "SELECT * FROM users";
            $stmt = $this->db->getPdo()->prepare($sql);
    
            // Exécution de la requête
            $stmt->execute();
    
            
            if($stmt->rowCount() > 0){
                // Récupérer tous les résultats sous forme de tableau associatif
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                 // Retourner les utilisateurs sous forme de JSON
                echo json_encode($users);
            }else{
                echo json_encode(['error' => 'il ya acun utilisateur']);
            }
           
        } catch (PDOException $e) {
            // Gestion des erreurs : si une exception PDO est lancée, retourner le message d'erreur
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }
  
}

?>