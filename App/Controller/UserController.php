<?php

namespace App\Controller;

use App\Models\UserModel;

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Fonction pour récupérer tous les utilisateurs
    public function GetAllUsers()
    {
        return $this->userModel->GetAllUsers();
    }

    public function GetAgentsGuichets()
    {
        return $this->userModel->GetAgentsGuichets();
    }

    public function GetBoitesPostales()
    {
        return $this->userModel->GetBoitesPostales();
    }

    // Fonction pour récupérer un utilisateur par son ID
    public function getUserById($id)
    {
        return $this->userModel->GetUser($id);
    }

    // Fonction pour créer un nouvel utilisateur
    public function createUser($jsonData)
    {
        return $this->userModel->createUser($jsonData);
    }

    // Fonction pour mettre à jour un utilisateur
    public function updateUser($id, $data)
    {
        return $this->userModel->updateUser($id, $data);
    }

    // Fonction pour supprimer un utilisateur
    public function deleteUser($id)
    {
        return $this->userModel->deleteUser($id);
    }
}

?>
