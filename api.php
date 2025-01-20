<?php




use App\Controller\UserController;
use App\Models\UserModel;
use App\Controller\BoitePostaleController;


// Inclure le fichier autoload
require_once __DIR__ . '/autoload.php';

// Définir les en-têtes de réponse
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Permettre les requêtes de toutes les origines
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Méthodes HTTP autorisées
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // En-têtes autorisés
header('Access-Control-Allow-Credentials: true'); // Permettre l'envoi de cookies et autres informations d'identification

// Initialisation des contrôleurs
$userModel = new UserModel();

$boitePostaleModel = new BoitePostaleController();

// Gérer les requêtes préliminaires OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Récupérer la méthode passée dans l'URL
$method = $_GET['method'] ?? null;

// Gestion des requêtes GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($method) {
        case 'getAllColisNoFound':
            echo $colisNotFound->getAllColisNoFound();
            break;
        case 'GetBoitePostaleDetails':
            echo $boitePostaleModel->GetBoitePostaleDetails();
            break;

        case 'GetAllBoxDetails':
            echo $boitePostaleModel->GetAllBoxDetails();
            break;

        case 'GetAllResilies':
            echo $boitePostaleModel->GetAllResilies();
            break;

        case 'GetAllClients':
            echo $boitePostaleModel->GetAllClients();
            break;

        case 'getLastReferenceAchatCle':
            echo $boitePostaleModel->getLastReferenceAchatCle();
            break;
        case 'getLastReferenceAjoutSousCouvette':
            echo $boitePostaleModel->getLastReferenceAjoutSousCouvette();
            break;
        case 'getLastReferenceChangerNom':
            echo $boitePostaleModel->getLastReferenceChangerNom();
            break;
        case 'getLastReferenceLivraisonDomicile':
            echo $boitePostaleModel->getLastReferenceLivraisonDomicile();
            break;
        case 'getLastReferenceAjoutCollection':
            echo $boitePostaleModel->getLastReferenceAjoutCollection();
            break;
        case 'getLastReference':
            echo $boitePostaleModel->getLastReference();
            break;







        case 'GetBoitesPostalesDetails':
            echo $boitePostaleModel->GetBoitesPostalesDetails();
            break;
        case 'GetEtatBoitesPostales':
            echo $boitePostaleModel->GetEtatBoitesPostales();
            break;
        case 'GetAllBoitesPostales':
            echo $boitePostaleModel->GetAllBoitesPostales();
            break;
        case 'GetAllUsers':
            echo $userModel->GetAllUsers();
            break;


        default:
            echo json_encode(['error' => 'Invalid method']);
    }
}

// Gestion des requêtes POST
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    $id=$_GET['id'];

    switch ($method) {
        case 'createColisNoFound':
            echo $colisNotFound->createColisNoFound($data);
            break;
        case 'GetClientEtatBoitePostale':
            echo $boitePostaleModel->GetClientEtatBoitePostale($data);
            break;

        case 'addSousCouvette':
            echo $boitePostaleModel->addSousCouvette($data);
            break;


        case 'addMontantAchatsCle':
            echo $boitePostaleModel->addMontantAchatsCle($id,$data);
            break;

                        case 'enregistrerPaiement':
                            if (isset($_GET['idClient']) && !empty($_GET['idClient'])) {
                                $idClient = (int)$_GET['idClient'];
                                $data = file_get_contents('php://input'); // Récupérer les données JSON
                                $boitePostaleModel->enregistrerPaiement($idClient, $data);
                            } else {
                                echo json_encode(["error" => "ID du client manquant."]);
                            }
                            break;



        case 'updateClientNameAndAddPayment':
            echo $boitePostaleModel->updateClientNameAndAddPayment($id,$data);
            break;

        case 'insertAndAssignBoitePostaleToClient':
            echo $boitePostaleModel->insertAndAssignBoitePostaleToClient($data);
            break;
        case 'insererLivraisonEtMettreAJourPaiement':
            echo $boitePostaleModel->insererLivraisonEtMettreAJourPaiement($id, $data);
            break;
        case 'insererCollectionEtMettreAJourPaiement':
            echo $boitePostaleModel->insererCollectionEtMettreAJourPaiement($data);
            break;






        case 'GetDetailsByClientData':
            echo $boitePostaleModel->GetDetailsByClientData();
            break;

        case 'EnregistrerResiliation':
            echo $boitePostaleModel->EnregistrerResiliation();
            break;

        case 'GetClientName':
            echo $boitePostaleModel->GetClientName();
            break;

        default:
            echo json_encode(['error' => 'Invalid method']);
    }
}

// Gestion des requêtes PUT
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = file_get_contents('php://input');
    $decodedData = json_decode($data, true);

    switch ($method) {


        case 'UpdateClientName':
            // Récupération de l'ID du client depuis les paramètres GET
            $clientId = $_GET['id'] ?? null;

            // Récupération de l'ID de l'utilisateur depuis les paramètres GET (ou via un mécanisme d'authentification)
            $userId = $_GET['user_id'] ?? null;

            // Vérification de la présence de l'ID du client et de l'ID de l'utilisateur
            if ($clientId && $userId) {
                // Récupération des données JSON depuis le corps de la requête
                $jsonData = file_get_contents('php://input');

                // Appel de la méthode dans le contrôleur
                echo $boitePostaleModel->UpdateClientName($clientId, $jsonData, $userId);
            } else {
                // Retourner une erreur si l'un des ID est manquant
                echo json_encode(['error' => 'Missing client ID or user ID']);
            }
            break;




        default:
            echo json_encode(['error' => 'Invalid method']);
    }
}

// Gestion des requêtes DELETE
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = file_get_contents('php://input');
    $decodedData = json_decode($data, true);

    switch ($method) {
        case 'deleteArulo':
            if (isset($decodedData['id']) && is_numeric($decodedData['id'])) {
                echo $arulos->deleteArulo(json_encode($decodedData));
            } else {
                echo json_encode(['error' => 'Invalid or missing ID for deleteArulo']);
            }
            break;
        case 'deleteTransfert':
            $data = file_get_contents('php://input');

            if (!empty($data)) {
                echo $transfert->deleteTransfert($data);
            } else {
                echo json_encode(['error' => 'No data provided for deletion']);
            }
            break;

        case 'deleteCasSensible':
            $id = $_GET['id'] ?? null;
            if ($id) {
                echo $adrCasSensible->deleteCasSensible(json_encode(['id' => (int)$id]));
            } else {
                echo json_encode(['error' => 'Missing id for deleteCasSensible']);
            }
            break;

        case 'deleteColisNoFound':
            if (isset($decodedData['id']) && !empty($decodedData['id'])) {
                echo $colisNotFound->deleteColisNoFound($data);
            } else {
                echo json_encode(['error' => 'Missing id for DeleteColisNotFound']);
            }
            break;

        default:
            echo json_encode(['error' => 'Invalid method']);
    }
}

// Méthode invalide
else {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(['error' => 'Invalid request method']);
}
