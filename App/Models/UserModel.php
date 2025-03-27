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


    public function getAllUsers()
    {
        try {
            // Préparer la requête SQL pour récupérer tous les utilisateurs
            $sql = "SELECT * FROM users";
            $stmt = $this->db->getPdo()->prepare($sql);

            // Exécution de la requête
            $stmt->execute();


            if ($stmt->rowCount() > 0) {
                // Récupérer tous les résultats sous forme de tableau associatif
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // Retourner les utilisateurs sous forme de JSON
                echo json_encode($users);
            } else {
                echo json_encode(['error' => 'il ya acun utilisateur']);
            }
        } catch (PDOException $e) {
            // Gestion des erreurs : si une exception PDO est lancée, retourner le message d'erreur
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }

    public function GetUsersById($id)
    {
        try {
            // Préparer la requête pour récupérer un utilisateur par son ID
            $stmt = $this->db->getPdo()->prepare("SELECT * FROM users WHERE id = :id");

            // Lier le paramètre :id à l'ID de l'utilisateur
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Exécuter la requête
            $stmt->execute();

            // Vérifier si un utilisateur a été trouvé
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Retourner les données de l'utilisateur en JSON
                echo json_encode($user);
            } else {
                // Si aucun utilisateur n'est trouvé avec cet ID
                echo json_encode(['error' => 'Aucun utilisateur trouvé avec cet ID.']);
            }
        } catch (PDOException $e) {
            // Gérer les erreurs PDO
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }


    public function NoadminUsers()
    {
        try {
            // Préparer la requête SQL pour récupérer tous les utilisateurs
            $sql = "SELECT * FROM users WHERE Role NOT IN ('responsable', 'superviseur', 'Admin');";
            $stmt = $this->db->getPdo()->prepare($sql);

            // Exécution de la requête
            $stmt->execute();


            if ($stmt->rowCount() > 0) {
                // Récupérer tous les résultats sous forme de tableau associatif
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // Retourner les utilisateurs sous forme de JSON
                echo json_encode($users);
            } else {
                echo json_encode(['error' => 'il ya aucune utilisateur']);
            }
        } catch (PDOException $e) {
            // Gestion des erreurs : si une exception PDO est lancée, retourner le message d'erreur
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }


    public function CreateAgentsByResponsable($data)
    {
        try {
            $Data = json_decode($data, true);

            // Vérifier si toutes les données nécessaires sont présentes
            if (!isset($Data['Nom'], $Data['Adresse'], $Data['Telephone'], $Data['Email'], $Data['Password'], $Data['role'])) {
                echo json_encode(['error' => 'Données manquantes']);
                return;
            }

            // Vérifier si le rôle est 'admin' ou 'Responsable'
            if ($Data['role'] === 'admin' || $Data['role'] === 'responsable') {
                echo json_encode(['error' => 'Désolé, vous n\'avez pas l\'autorisation de créer cet utilisateur']);
                return;
            }

            // Hasher le mot de passe avant l'insertion
            $hashedPassword = password_hash($Data['Password'], PASSWORD_BCRYPT);

            // Préparer la requête SQL d'insertion
            $sql = "INSERT INTO users (Nom, Adresse, Telephone, Email, password, Role) VALUES (:Nom, :Adresse, :Telephone, :Email, :password, :Role)";
            $stmt = $this->db->getPdo()->prepare($sql);

            // Exécuter la requête avec les valeurs sécurisées
            $stmt->execute([
                ':Nom' => $Data['Nom'],
                ':Adresse' => $Data['Adresse'],
                ':Telephone' => $Data['Telephone'],
                ':Email' => $Data['Email'],
                ':password' => $hashedPassword,
                ':Role' => $Data['role']
            ]);

            // Retourner une réponse JSON de succès
            echo json_encode(['success' => 'Agent ajouté avec succès']);
        } catch (PDOException $e) {
            // Gérer les erreurs PDO
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }


    public function UpdateAgentByResponsable($id, $data)
    {
        try {
            $Data = json_decode($data, true);

            // Vérifier si toutes les données nécessaires sont présentes
            if (!isset($Data['Nom'], $Data['Adresse'], $Data['Telephone'], $Data['Email'], $Data['Role'])) {
                echo json_encode(['error' => 'Données manquantes']);
                return;
            }

            // Vérifier si l'utilisateur qu'on veut mettre à jour a un rôle interdit
            $stmtCheck = $this->db->getPdo()->prepare("SELECT Role FROM users WHERE id = :id");
            $stmtCheck->execute([':id' => $id]);
            $user = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                echo json_encode(['error' => 'Utilisateur non trouvé']);
                return;
            }

            if ($Data['Role'] === 'admin' || $Data['Role'] === 'Responsable') {
                echo json_encode(['error' => 'Désolé, vous n\'avez pas l\'autorisation de modifier cet utilisateur']);
                return;
            }

            // Vérifier si l'email existe déjà pour un autre utilisateur
            $stmtEmail = $this->db->getPdo()->prepare("SELECT id FROM users WHERE Email = :Email AND id != :id");
            $stmtEmail->execute([':Email' => $Data['Email'], ':id' => $id]);

            if ($stmtEmail->fetch()) {
                echo json_encode(['error' => 'Cet email est déjà utilisé par un autre utilisateur']);
                return;
            }

            // Hasher le mot de passe si un nouveau est fourni
            $passwordClause = "";
            $params = [
                ':Nom' => $Data['Nom'],
                ':Adresse' => $Data['Adresse'],
                ':Telephone' => $Data['Telephone'],
                ':Email' => $Data['Email'],
                ':Role' => $Data['Role'],
                ':id' => $id
            ];

            if (!empty($Data['password'])) {
                $passwordClause = ", password = :password";
                $params[':password'] = password_hash($Data['password'], PASSWORD_BCRYPT);
            }

            // Préparer la requête SQL de mise à jour
            $sql = "UPDATE users SET Nom = :Nom, Adresse = :Adresse, Telephone = :Telephone, Email = :Email, Role = :Role" . $passwordClause . " WHERE id = :id";
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute($params);

            // Retourner une réponse JSON de succès
            echo json_encode(['success' => 'Utilisateur mis à jour avec succès']);
        } catch (PDOException $e) {
            // Gérer les erreurs PDO
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }

    public function DeletedByResponsable($id)
    {
        try {
            // Préparer la requête DELETE pour supprimer l'utilisateur par ID
            $stmt = $this->db->getPdo()->prepare("DELETE FROM users WHERE id = :id");

            // Lier le paramètre :id à l'ID de l'utilisateur à supprimer
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Exécuter la requête
            $stmt->execute();

            // Vérifier si l'utilisateur a été supprimé
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => 'Utilisateur supprimé avec succès.']);
            } else {
                echo json_encode(['error' => 'Aucun utilisateur trouvé avec cet ID.']);
            }
        } catch (PDOException $e) {
            // Gérer les erreurs PDO
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }
}
