<?php

namespace App\Models;

use App\Db\Db;
use PDO;
use PDOException;

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    // Récupérer tous les utilisateurs
    public function GetAllUsers()
    {
        try {
            $sql = "SELECT * FROM users";
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["error" => "No users found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }
    public function GetAgentsGuichets()
{
    try {
        $sql = "SELECT * FROM users WHERE role = :role";
        $stmt = $this->db->getPdo()->prepare($sql);
        $stmt->bindValue(':role', 'agent_guichets', PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode($result);
        } else {
            echo json_encode(["error" => "No users with role 'agent_guichets' found"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
}

public function GetBoitesPostales()
{
    try {
        // Requête SQL pour récupérer toutes les boîtes postales
        $sql = "SELECT * FROM boites_postales";
        
        // Préparation de la requête
        $stmt = $this->db->getPdo()->prepare($sql);
        
        // Exécution de la requête
        $stmt->execute();
        
        // Récupération des résultats
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Vérification si des résultats existent
        if ($result) {
            // Retourner les résultats sous forme de JSON
            echo json_encode($result);
        } else {
            // Aucun résultat trouvé
            echo json_encode(["error" => "No postal boxes found"]);
        }
    } catch (PDOException $e) {
        // Gestion des erreurs de base de données
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
}


    // Récupérer un utilisateur par son ID
    public function GetUser($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return json_encode(["error" => "Invalid user ID"]);
            }
    
            $sql = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($result) {
                return json_encode($result);
            } else {
                return json_encode(["error" => "User not found"]);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }
    

    // Créer un utilisateur
    public function CreateUser($jsonData)
    {
        try {
            $data = json_decode($jsonData, true);

            if (is_array($data) && isset($data['nom']) && isset($data['email']) && isset($data['password']) && isset($data['role'])) {
                // Hacher le mot de passe avant de l'enregistrer
                $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

                $sql = "INSERT INTO users (nom, email, password, role) VALUES (:nom, :email, :password, :role)";
                $stmt = $this->db->getPdo()->prepare($sql);

                $stmt->bindParam(':nom', $data['nom']);
                $stmt->bindParam(':email', $data['email']);
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->bindParam(':role', $data['role']);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    echo json_encode(["success" => "User added successfully"]);
                } else {
                    echo json_encode(["error" => "User not added"]);
                }
            } else {
                echo json_encode(["error" => "Invalid input format"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }

    // Mettre à jour un utilisateur
    public function UpdateUser($id, $data)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                echo json_encode(["error" => "Invalid user ID"]);
                return;
            }

            if (is_string($data)) {
                $data = json_decode($data, true);
            }

            if (!is_array($data) || empty($data)) {
                echo json_encode(["error" => "Invalid input data"]);
                return;
            }

            $fields = [];
            $params = [':id' => $id];

            if (isset($data['nom']) && !empty($data['nom'])) {
                $fields[] = 'nom = :nom';
                $params[':nom'] = $data['nom'];
            }

            if (isset($data['email']) && !empty($data['email'])) {
                $fields[] = 'email = :email';
                $params[':email'] = $data['email'];
            }

            if (isset($data['password']) && !empty($data['password'])) {
                // Hacher le mot de passe avant de l'enregistrer
                $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                $fields[] = 'password = :password';
            }

            if (isset($data['role']) && !empty($data['role'])) {
                $fields[] = 'role = :role';
                $params[':role'] = $data['role'];
            }

            if (empty($fields)) {
                echo json_encode(["error" => "No valid fields to update"]);
                return;
            }

            $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->db->getPdo()->prepare($sql);

            foreach ($params as $key => &$val) {
                $stmt->bindParam($key, $val);
            }

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo json_encode(["success" => "User updated successfully"]);
            } else {
                echo json_encode(["error" => "User not updated"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }

    // Supprimer un ou plusieurs utilisateurs
    public function DeleteUser($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                echo json_encode(["error" => "Invalid user ID"]);
                return;
            }
    
            // Si l'ID est un seul nombre
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                echo json_encode(["success" => "User with ID $id deleted successfully"]);
            } else {
                echo json_encode(["error" => "User not found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }
    
}

?>
