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
    // Récupérer tous les utilisateurs qui ne sont pas admin et responsable
    public function GetAllUsersWithOutAdminProperty()
    {
        try {
            $sql = "SELECT * FROM users WHERE role NOT IN ('responsable', 'admin');";
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
    public function CreateUser()
    {
        try {
            // Récupérer les données JSON envoyées via la requête HTTP
            $jsonData = file_get_contents("php://input");

            $data = json_decode($jsonData, true);

            // Vérifier que les données sont valides et que le rôle n'est pas 'responsable'
            if (is_array($data) && isset($data['Nom']) && isset($data['Email']) && isset($data['Password']) && isset($data['role']) && isset($data['Telephone']) && isset($data['Adresse'])) {
                // Condition pour empêcher la création d'un utilisateur avec le rôle 'responsable'
                if ($data['role'] === 'responsable') {
                    echo json_encode(["error" => "Cannot create user with role 'responsable'"]);
                    return; // Arrêter l'exécution si le rôle est 'responsable'
                }

                // Hacher le mot de passe avant de l'enregistrer
                $hashedPassword = password_hash($data['Password'], PASSWORD_DEFAULT);

                // Préparer et exécuter la requête SQL pour insérer l'utilisateur
                $sql = "INSERT INTO users (nom, email, password, role,Telephone,Adresse) VALUES (:nom, :email, :password, :role,:Telephone,:Adresse)";
                $stmt = $this->db->getPdo()->prepare($sql);

                $stmt->bindParam(':nom', $data['Nom']);
                $stmt->bindParam(':email', $data['Email']);
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->bindParam(':role', $data['role']);
                $stmt->bindParam(':Telephone', $data['Telephone']);
                $stmt->bindParam(':Adresse', $data['Adresse']);
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
    public function UpdateUser($id)
    {
        try {
            // Vérifier si l'ID est valide
            if (!is_numeric($id) || $id <= 0) {
                echo json_encode(["error" => "Invalid user ID"]);
                return;
            }

            // Récupérer les données JSON envoyées via la requête HTTP
            $jsonData = file_get_contents("php://input");
            $data = json_decode($jsonData, true);

            // Vérifier que les données sont valides
            if (!is_array($data) || empty($data)) {
                echo json_encode(["error" => "Invalid input data"]);
                return;
            }

            $fields = [];
            $params = [':id' => $id];

            if (isset($data['Nom']) && !empty($data['Nom'])) {
                $fields[] = 'nom = :nom';
                $params[':nom'] = $data['Nom'];
            }

            if (isset($data['Email']) && !empty($data['Email'])) {
                $fields[] = 'email = :email';
                $params[':email'] = $data['Email'];
            }

            if (isset($data['Password']) && !empty($data['Password'])) {
                // Hacher le mot de passe avant de l'enregistrer
                $fields[] = 'password = :password';
                $params[':password'] = password_hash($data['Password'], PASSWORD_DEFAULT);
            }

            if (isset($data['role']) && !empty($data['role'])) {
                // Empêcher la mise à jour vers le rôle "responsable"
                if ($data['role'] === 'responsable') {
                    echo json_encode(["error" => "Cannot update user to role 'responsable'"]);
                    return;
                }
                $fields[] = 'role = :role';
                $params[':role'] = $data['role'];
            }

            if (isset($data['Telephone']) && !empty($data['Telephone'])) {
                $fields[] = 'Telephone = :Telephone';
                $params[':Telephone'] = $data['Telephone'];
            }

            if (isset($data['Adresse']) && !empty($data['Adresse'])) {
                $fields[] = 'Adresse = :Adresse';
                $params[':Adresse'] = $data['Adresse'];
            }

            // Vérifier s'il y a des champs à mettre à jour
            if (empty($fields)) {
                echo json_encode(["error" => "No valid fields to update"]);
                return;
            }

            // Construire la requête SQL
            $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->db->getPdo()->prepare($sql);

            // Liaison des paramètres
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }

            // Exécuter la requête
            $stmt->execute();

            // Vérifier si la mise à jour a été effectuée
            if ($stmt->rowCount() > 0) {
                echo json_encode(["success" => "User updated successfully"]);
            } else {
                echo json_encode(["error" => "No changes made or user not found"]);
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

    public function getPetitBoitesPostalesCount()
    {
        try {
            // Préparer la requête SQL pour compter les boîtes postales de type 'petit'
            $sql = "SELECT COUNT(*) AS total FROM boites_postales WHERE type = 'petit'";
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();

            // Récupérer le résultat
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result['total'])) {
                // Retourner le total au format JSON
                echo json_encode(["success" => true, "count" => (int) $result['total']]);
            } else {
                echo json_encode(["success" => false, "message" => "No boites postales of type 'petit' found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }



    public function getMoyenBoitesPostalesCount()
    {
        try {
            // Préparer la requête SQL pour compter les boîtes postales de type 'moyen'
            $sql = "SELECT COUNT(*) AS total FROM boites_postales WHERE type = 'moyen'";
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();

            // Récupérer le résultat
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result['total'])) {
                // Retourner le total au format JSON
                echo json_encode(["success" => true, "count" => (int) $result['total']]);
            } else {
                echo json_encode(["success" => false, "message" => "No boites postales of type 'moyen' found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }

    public function getGrandeBoitesPostalesCount()
    {
        try {
            // Préparer la requête SQL pour compter les boîtes postales de type 'grand'
            $sql = "SELECT COUNT(*) AS total FROM boites_postales WHERE type = 'grand'";
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();

            // Récupérer le résultat
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result['total'])) {
                // Retourner le total au format JSON
                echo json_encode(["success" => true, "count" => (int) $result['total']]);
            } else {
                echo json_encode(["success" => false, "message" => "No boites postales of type 'grand' found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }


    public function getClientCount()
    {
        try {
            // Préparer la requête SQL pour compter tous les clients
            $sql = "SELECT COUNT(*) AS total FROM clients";
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();

            // Récupérer le résultat
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result['total'])) {
                // Retourner le total au format JSON
                echo json_encode(["success" => true, "count" => (int) $result['total']]);
            } else {
                echo json_encode(["success" => false, "message" => "No clients found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }

    public function countClientsWithUpdatedPayments()
    {
        try {
            // Obtenir l'année en cours
            $currentYear = date('Y');

            // Requête SQL pour compter les clients avec les conditions spécifiées
            $sql = "
            SELECT COUNT(DISTINCT c.id) AS total_clients
            FROM clients c
            INNER JOIN abonnement a ON c.id_boite_postale = a.id_boite_postale
            INNER JOIN paiements p ON a.id_payments = p.id
            WHERE p.type = 'mis_a_jour' AND a.annee_abonnement = :currentYear
        ";

            // Préparer la requête
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->bindParam(':currentYear', $currentYear, PDO::PARAM_INT);
            $stmt->execute();

            // Récupérer le résultat
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Retourner le résultat au format JSON
            if ($result && isset($result['total_clients'])) {
                echo json_encode(["success" => true, "count" => (int) $result['total_clients']]);
            } else {
                echo json_encode(["success" => false, "message" => "No matching clients found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }


    public function countClientsWithoutPaymentsOrWithNonUpdatedPayments()
    {
        try {
            // Obtenir l'année en cours
            $currentYear = date('Y');

            // Requête SQL pour compter les clients qui n'ont pas de paiement,
            // ou qui ont un paiement de type 'non_mis_a_jour' et une année d'abonnement différente de l'année en cours
            $sql = "
            SELECT COUNT(DISTINCT c.id) AS total_clients
            FROM clients c
            LEFT JOIN abonnement a ON c.id_boite_postale = a.id_boite_postale
            LEFT JOIN paiements p ON a.id_payments = p.id
            WHERE (p.id IS NULL OR (p.type = 'non_mis_a_jour' AND a.annee_abonnement != :currentYear))
        ";

            // Préparer la requête
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->bindParam(':currentYear', $currentYear, PDO::PARAM_INT);
            $stmt->execute();

            // Récupérer le résultat
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Retourner le résultat au format JSON
            if ($result && isset($result['total_clients'])) {
                echo json_encode(["success" => true, "count" => (int) $result['total_clients']]);
            } else {
                echo json_encode(["success" => false, "message" => "No matching clients found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }

    public function CountResilations()
    {
        try {
            // Requête SQL pour compter le nombre total de résiliations
            $sql = "SELECT COUNT(*) AS total_resilies FROM resilies";

            // Préparer et exécuter la requête SQL
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();

            // Récupérer le résultat
            $resilation = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier si un résultat a été trouvé
            if ($resilation && isset($resilation['total_resilies'])) {
                echo json_encode(["success" => true, "count" => (int) $resilation['total_resilies']]);
            } else {
                echo json_encode(["success" => false, "message" => "No resiliations found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }

    public function getAllResilations()
    {
        try {
            // Requête SQL pour obtenir toutes les résiliations
            $sql = "SELECT r.id, r.id_user, r.id_client, r.date_resiliation, u.nom AS user_name, c.nom AS client_name
                FROM resilies r
                LEFT JOIN users u ON r.id_user = u.id
                LEFT JOIN clients c ON r.id_client = c.id
                ORDER BY r.date_resiliation DESC"; // Tri par date de résiliation (du plus récent au plus ancien)

            // Préparer et exécuter la requête SQL
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();

            // Récupérer les résultats sous forme de tableau associatif
            $resilations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier si des résultats ont été trouvés
            if ($resilations) {
                echo json_encode(["success" => true, "resilations" => $resilations]);
            } else {
                echo json_encode(["success" => false, "message" => "No resiliations found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }

    public function GetChangementCle()
    {
        try {
            // Requête SQL pour récupérer les clients ayant un paiement avec 'reference_changer_nom' non null
            $sql = "
            SELECT 
                c.id AS client_id,
                c.nom AS client_nom,
                c.email AS client_email,
                c.telephone AS client_telephone,
                c.adresse AS client_adresse,
                p.id AS paiement_id,
                p.montant_achats_cle,
                p.reference_achat_cle
            FROM 
                clients c
            INNER JOIN 
                paiements p 
            ON 
                c.id = p.id_client
            WHERE 
                p.reference_achat_cle IS NOT NULL
            ORDER BY 
                c.nom ASC
        ";

            // Préparation et exécution de la requête
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();

            // Récupérer les résultats sous forme de tableau associatif
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier s'il y a des résultats
            if ($clients) {
                return json_encode(["success" => true, "clients" => $clients]);
            } else {
                return json_encode(["success" => false, "message" => "No clients with payments found."]);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }
    public function GetChangementLivraison()
    {
        try {
            // Requête SQL pour récupérer les clients ayant un paiement avec 'reference_changer_nom' non null
            $sql = "
            SELECT 
                c.id AS client_id,
                c.nom AS client_nom,
                c.email AS client_email,
                c.telephone AS client_telephone,
                c.adresse AS client_adresse,
                p.id AS paiement_id,
                p.montant_livraison_a_domicile,
                p.reference_livraison_domicile
            FROM 
                clients c
            INNER JOIN 
                paiements p 
            ON 
                c.id = p.id_client
            WHERE 
                p.reference_livraison_domicile IS NOT NULL
            ORDER BY 
                c.nom ASC
        ";

            // Préparation et exécution de la requête
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();

            // Récupérer les résultats sous forme de tableau associatif
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier s'il y a des résultats
            if ($clients) {
                return json_encode(["success" => true, "clients" => $clients]);
            } else {
                return json_encode(["success" => false, "message" => "No clients with payments found."]);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }
    public function getClientsWithPayments()
    {
        try {
            // Requête SQL pour récupérer les clients ayant un paiement avec 'reference_changer_nom' non null
            $sql = "
            SELECT 
                c.id AS client_id,
                c.nom AS client_nom,
                c.email AS client_email,
                c.telephone AS client_telephone,
                c.adresse AS client_adresse,
                p.id AS paiement_id,
                p.montant_changement_nom,
                p.reference_changer_nom
            FROM 
                clients c
            INNER JOIN 
                paiements p 
            ON 
                c.id = p.id_client
            WHERE 
                p.reference_changer_nom IS NOT NULL
            ORDER BY 
                c.nom ASC
        ";

            // Préparation et exécution de la requête
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();

            // Récupérer les résultats sous forme de tableau associatif
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier s'il y a des résultats
            if ($clients) {
                return json_encode(["success" => true, "clients" => $clients]);
            } else {
                return json_encode(["success" => false, "message" => "No clients with payments found."]);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }

// la fonction inspiration pour enregistre des fichiers 
    public function insertionUploadImages($id, $file)
    {
        try {
            // Vérifier que l'ID est valide
            if (empty($id)) {
                return json_encode(["error" => "ID invalide"]);
            }

            // Vérifier que le fichier est présent
            if (isset($file['photo']) && $file['photo']['error'] === UPLOAD_ERR_OK) {
                // Définir le répertoire de destination
                $targetDir = "uploads/photos/";

                // Créer le répertoire si nécessaire
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                // Générer un nom unique pour la photo
                $fileName = uniqid('photo_', true) . '.' . pathinfo($file['photo']['name'], PATHINFO_EXTENSION);
                $targetFile = $targetDir . $fileName;

                // Déplacer le fichier téléchargé
                if (move_uploaded_file($file['photo']['tmp_name'], $targetFile)) {
                    // Préparer la requête pour mettre à jour la base de données
                    $sql = "UPDATE cartin SET photo = :photo WHERE id = :id";
                    $stmt = $this->db->getPdo()->prepare($sql);

                    // Lier les paramètres
                    $stmt->bindParam(':photo', $targetFile);
                    $stmt->bindParam(':id', $id);

                    // Exécuter la requête
                    $stmt->execute();

                    // Vérifier si la mise à jour a réussi
                    if ($stmt->rowCount() > 0) {
                        echo json_encode(["success" => "Photo ajoutée avec succès", "photo_path" => $targetFile]);
                    } else {
                        echo json_encode(["error" => "Aucune modification n'a été effectuée"]);
                    }
                } else {
                    echo json_encode(["error" => "Erreur lors du déplacement du fichier"]);
                }
            } else {
                echo json_encode(["error" => "Fichier non valide ou non reçu"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Erreur de base de données : " . $e->getMessage()]);
        }
    }
}
