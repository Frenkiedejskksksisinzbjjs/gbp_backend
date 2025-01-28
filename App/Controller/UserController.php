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
    public function GetUser($id)
    {
        return $this->userModel->GetUser($id);
    }

    // Fonction pour créer un nouvel utilisateur
    public function CreateUser()
    {
        return $this->userModel-> CreateUser();
    }

    // Fonction pour mettre à jour un utilisateur
    public function UpdateUser($id, $data)
    {
        return $this->userModel->UpdateUser($id, $data);
    }

    // Fonction pour supprimer un utilisateur
    public function DeleteUser($id)
    {
        return $this->userModel->DeleteUser($id);
    }
    public function getPetitBoitesPostales()
    {
        return $this->userModel->getPetitBoitesPostales();
    }
    public function getMoyenBoitesPostales()
    {
        return $this->userModel->getMoyenBoitesPostales();
    }

    public function getGrandeBoitesPostalesCount()
    {
        return $this->userModel->getGrandeBoitesPostalesCount();
    }

    public function getClientCount()
    {
        return $this->userModel->getClientCount();
    }

    public function countClientsWithUpdatedPayments()
    {
        return $this->userModel->countClientsWithUpdatedPayments();
    }

    public function countClientsWithoutPaymentsOrWithNonUpdatedPayments()
    {
        return $this->userModel->countClientsWithoutPaymentsOrWithNonUpdatedPayments();
    }


}

?>
