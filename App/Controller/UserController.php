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

    // Fonction pour récupérer un utilisateur par son ID
    public function getUserById($id)
    {
        return $this->userModel->getUserById($id);
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

    // Fonction pour compter le nombre total d'utilisateurs
    public function countUsers()
    {
        return $this->userModel->countUsers();
    }

    // Fonction pour obtenir les utilisateurs par rôle
    public function getUsersByRole($role)
    {
        return $this->userModel->getUsersByRole($role);
    }

    // Fonction pour compter les utilisateurs par rôle
    public function countUsersByRole($role)
    {
        return $this->userModel->countUsersByRole($role);
    }
}

?>
