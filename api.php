<?php

// Inclure le fichier autoload

use App\Models\AbonnementModel;
use App\Models\AchatCleModel;
use App\Models\BoitPostaleModel;
use App\Models\ChangementModel;
use App\Models\ClientsModels;
use App\Models\CollectionModel;
use App\Models\ExonorerModel;
use App\Models\LvdModel;
use App\Models\PenaliterModels;
use App\Models\ResilierModel;
use App\Models\SousCouverteModel;
use App\Models\UserModel;

require_once __DIR__ . '/autoload.php';

// Définir les en-têtes de réponse
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Permettre les requêtes de toutes les origines
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Méthodes HTTP autorisées
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // En-têtes autorisés
header('Access-Control-Allow-Credentials: true'); // Permettre l'envoi de cookies et autres informations d'identification


// Gérer les requêtes préliminaires OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

// Récupérer la méthode passée dans l'URL
$method = $_GET['method'] ?? null;

$UserController = new UserModel();
$AbonnementController = new AbonnementModel();
$ClientsController = new ClientsModels();
$BoitPostaleController = new BoitPostaleModel();
$CollectionController = new CollectionModel();
$ExonoreController = new ExonorerModel();
$LvdController = new LvdModel();
$PenaliterController = new PenaliterModels();
$ResilierController = new ResilierModel();
$SousCouverteController = new SousCouverteModel();
$AchatCleController = new AchatCleModel();
$ChangementNameController = new ChangementModel();

// Gestion des requêtes GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  switch ($method) {
    case 'getAllUsers':
      $UserController->getAllUsers();
      break;
    case 'GetUsersById':
      $id = $_GET['id'] ?? null;
      $UserController->GetUsersById($id);
      break;
    case 'NoadminUsers':
      $UserController->NoadminUsers();
      break;
    case 'GetNextBoitePostal':
      $BoitPostaleController->GetNextBoitePostal();
      break;
    case 'GetAllClients':
      $ClientsController->GetAllClients();
      break;
    case 'GetAllClientsResilies':
      $ClientsController->GetAllClientsResilies();
      break;
    case 'GetAllClientsExonore':
      $ClientsController->GetAllClientsExonore();
      break;
    case 'GetAllClientCount':
      $ClientsController->GetAllClientCount();
      break;
    case 'GetAllClientsCountWithStatusPaye':
      $ClientsController->GetAllClientsCountWithStatusPaye();
      break;
    case 'GetAllClientsCountWithStatusNonPaye':
      $ClientsController->GetAllClientsCountWithStatusNonPaye();
      break;
    case 'GetAllClientsExonoreCount':
      $ClientsController->GetAllClientsExonoreCount();
      break;
    case 'GetCountOfClientsResilies':
      $ClientsController->GetCountOfClientsResilies();
      break;
    case 'GetCountOfBpGrandType':
      $BoitPostaleController->GetCountOfBpGrandType();
      break;
    case 'GetCountOfBpMoyenType':
      $BoitPostaleController->GetCountOfBpMoyenType();
      break;
    case 'GetCountOfBpPetiteType':
      $BoitPostaleController->GetCountOfBpPetiteType();
      break;
    case 'getLastReferenceChangerNom':
      $ChangementNameController->getLastReferenceChangerNom();
      break;
    case 'getLastReferenceOfkey':
      $AchatCleController->getLastReferenceOfkey();
      break;
    case 'getLastReferenceAjoutSousCouvette':
      $SousCouverteController->getLastReferenceAjoutSousCouvette();
      break;

    default:
      # code...
      break;
  }
}
// Gestion des requêtes POST
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

  switch ($method) {
    case 'AddClientsAbonnment':
      $id = $_GET['id'] ?? null;
      $files = $_FILES;
      $Data = $_POST;
      $ClientsController->AddClientsAbonnment($id, $Data, $files);
      break;

    case 'CreateAgentsByResponsable':
      $Data = file_get_contents("php://input");
      $UserController->CreateAgentsByResponsable($Data);
      break;

    case 'AddLvdClients':
      $id = $_GET['id'];
      $idClient = $_GET['idClient'];
      $Data = file_get_contents("php://input");
      $LvdController->AddLvdClients($idClient, $id, $Data);
      break;

    case 'AddCollectionClients':
      $id = $_GET['id'];
      $idClient = $_GET['idClient'];
      $Data = file_get_contents("php://input");
      $CollectionController->AddCollectionClients($idClient, $id, $Data);
      break;

    case 'AddSousCouverteClients':
      $id = $_GET['id'];
      $idClient = $_GET['idClient'];
      $Data = file_get_contents("php://input");
      $SousCouverteController->AddSousCouverteClients($idClient, $id, $Data);
      break;

    case 'AchatCleForClients':
      $id = $_GET['id'];
      $idClient = $_GET['idClient'];
      $Data = file_get_contents("php://input");
      $AchatCleController->AchatCleForClients($idClient, $id, $Data);
      break;

    case 'ChangeClientName':
      $id = $_GET['id'];
      $idClient = $_GET['idClient'];
      $Data = file_get_contents("php://input");
      $ChangementNameController->ChangeClientName($idClient, $id, $Data);
      break;

    default:
      # code...
      break;
  }
}
// Gestion des requêtes PUT
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
  switch ($method) {
    case 'UpdateAgentByResponsable':
      $id = $_GET['id'] ?? null;
      $Data = file_get_contents("php://input");
      $UserController->UpdateAgentByResponsable($id, $Data);
      break;
    default:
      # code...
      break;
  }
}
// Gestion des requêtes DELETE
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
  switch ($method) {
    case 'DeleteUser':
      $id = $_GET['id'] ?? null;
      $UserController->DeletedByResponsable($id);
      break;
    default:
      # code...
      break;
  }
} // Méthode invalide
else {
}
