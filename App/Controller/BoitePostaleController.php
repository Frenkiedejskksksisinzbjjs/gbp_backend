<?php

namespace App\Controller;

use App\Models\BoitePostaleModel;

class BoitePostaleController
{
    private $boitePostaleModel;

    public function __construct()
    {
        $this->boitePostaleModel = new BoitePostaleModel();
    }
       
    public function insertAndAssignBoitePostaleToClient($data)
    {
        return $this->boitePostaleModel->insertAndAssignBoitePostaleToClient($data) ;

        // A implémenter si nécessaire
    }

    public function insererCollectionEtMettreAJourPaiement($data)
    {
        return $this->boitePostaleModel->insererCollectionEtMettreAJourPaiement($data) ;

        // A implémenter si nécessaire
    }

    public function insererLivraisonEtMettreAJourPaiement($data)
    {
        return $this->boitePostaleModel->insererLivraisonEtMettreAJourPaiement($data) ;

        // A implémenter si nécessaire
    }




    public function addMontantAchatsCle($data)
    {
        return $this->boitePostaleModel->addMontantAchatsCle($data) ;

        // A implémenter si nécessaire
    }



    public function updateClientNameAndAddPayment($data)
    {
        return $this->boitePostaleModel->updateClientNameAndAddPayment($data) ;

        // A implémenter si nécessaire
    }






    public function addSousCouvette($data)
    {
        return $this->boitePostaleModel->addSousCouvette($data) ;

        // A implémenter si nécessaire
    }


    public function GetAllClients()
    {
        return $this->boitePostaleModel->GetAllClients() ;

        // A implémenter si nécessaire
    }

    public function getLastReferenceAchatCle()
    {
        return $this->boitePostaleModel->getLastReferenceAchatCle() ;

        // A implémenter si nécessaire
    }

    public function getLastReferenceAjoutSousCouvette()
    {
        return $this->boitePostaleModel->getLastReferenceAjoutSousCouvette() ;

        // A implémenter si nécessaire
    }

    public function getLastReferenceChangerNom()
    {
        return $this->boitePostaleModel->getLastReferenceChangerNom() ;

        // A implémenter si nécessaire
    }

    public function getLastReferenceLivraisonDomicile()
    {
        return $this->boitePostaleModel->getLastReferenceLivraisonDomicile() ;

        // A implémenter si nécessaire
    }

    public function getLastReferenceAjoutCollection()
    {
        return $this->boitePostaleModel->getLastReferenceAjoutCollection() ;

        // A implémenter si nécessaire
    }
    public function getLastReference()
    {
        return $this->boitePostaleModel->getLastReference() ;

        // A implémenter si nécessaire
    }

    public function GetAllResilies()
    {
        return $this->boitePostaleModel->GetAllResilies() ;

        // A implémenter si nécessaire
    }

    public function GetClientName()
    {
        return $this->boitePostaleModel->GetClientName() ;

        // A implémenter si nécessaire
    }
    public function EnregistrerResiliation()
    {
        return $this->boitePostaleModel->EnregistrerResiliation() ;

        // A implémenter si nécessaire
    }

    
    public function GetAllBoxDetails()
    {
        return $this->boitePostaleModel->GetAllBoxDetails() ;

        // A implémenter si nécessaire
    }

    public function GetDetailsByClientData()
    {
        return $this->boitePostaleModel->GetDetailsByClientData() ;

        // A implémenter si nécessaire
    }


    public function UpdateClientName($clientId, $jsonData  , $userId)
    {
        return $this->boitePostaleModel->UpdateClientName($clientId, $jsonData , $userId) ;

        // A implémenter si nécessaire
    }

    // Récupérer toutes les boîtes postales
    public function GetAllBoitesPostales()
    {
        return $this->boitePostaleModel->GetAllBoitesPostales();
    }
     // Récupérer toutes les boîtes postales
     public function GetBoitePostaleDetails()
     {
         return $this->boitePostaleModel->GetBoitePostaleDetails();
     }

    // Récupérer toutes les boîtes postales
    public function GetBoitesPostalesDetails()
    {
        return $this->boitePostaleModel->GetBoitesPostalesDetails();
    }


    // Récupérer toutes les boîtes postales
    public function GetClientEtatBoitePostale($jsonData)
{
    return $this->boitePostaleModel->GetClientEtatBoitePostale($jsonData);
}

    // Récupérer toutes les boîtes postales
    public function GetEtatBoitesPostales()
    {
        return $this->boitePostaleModel->GetEtatBoitesPostales();
    }

    // Récupérer une boîte postale par son ID
    public function getBoitePostaleById($id)
    {
        return $this->boitePostaleModel->getBoitePostaleById($id);
    }

    // Créer une nouvelle boîte postale
    public function createBoitePostale($jsonData)
    {
        return $this->boitePostaleModel->createBoitePostale($jsonData);
    }

    // Mettre à jour une boîte postale
    public function updateBoitePostale($id, $data)
    {
        return $this->boitePostaleModel->updateBoitePostale($id, $data);
    }

    // Supprimer une ou plusieurs boîtes postales
    public function deleteBoitePostale($jsonData)
    {
        return $this->boitePostaleModel->deleteBoitePostale($jsonData);
    }

    // Récupérer les boîtes postales par type
    public function getBoitesPostalesByType($type)
    {
        return $this->boitePostaleModel->getBoitesPostalesByType($type);
    }

    // Compter le nombre total de boîtes postales
    public function countBoitesPostales()
    {
        return $this->boitePostaleModel->countBoitesPostales();
    }
}

?>
