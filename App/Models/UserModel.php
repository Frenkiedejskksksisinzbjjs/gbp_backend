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

    // Récupérer un utilisateur par son ID
    public function GetUser($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                echo json_encode(["error" => "Invalid user ID"]);
                return;
            }

            $sql = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["error" => "User not found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
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
    public function DeleteUser($jsonData)
    {
        $data = json_decode($jsonData, true);

        if (is_array($data) && isset($data['ids']) && !empty($data['ids'])) {
            $ids = implode(',', array_map('intval', $data['ids']));
            $sql = "DELETE FROM users WHERE id IN ($ids)";
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo json_encode(["success" => "Deleted " . count($data['ids']) . " user(s)"]);
            } else {
                echo json_encode(["error" => "An error occurred"]);
            }
        } else {
            echo json_encode(["error" => "Invalid JSON data or no ID provided"]);
        }
    }
}

?>
