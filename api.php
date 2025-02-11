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
        case 'getPetitBoitesPostalesCount':
            echo $userModel->getPetitBoitesPostalesCount();
            break;
        case 'getMoyenBoitesPostalesCount':
            echo $userModel->getMoyenBoitesPostalesCount();
            break;
        case 'getGrandeBoitesPostalesCount':
            echo $userModel->getGrandeBoitesPostalesCount();
            break;
        case 'getClientCount':
            echo $userModel->getClientCount();
            break;
        case 'countClientsWithUpdatedPayments':
            echo $userModel->countClientsWithUpdatedPayments();
            break;
        case 'countClientsWithoutPaymentsOrWithNonUpdatedPayments':
            echo $userModel->countClientsWithoutPaymentsOrWithNonUpdatedPayments();
            break;

        case 'getAllResilations':
            echo $userModel->getAllResilations();
            break;
        case 'getClientsWithPayments':
            echo $userModel->getClientsWithPayments();
            break;
        case 'getClientsWithcouvette':
            echo $userModel->getClientsWithcouvette();
            break;
        case 'getClientsWithPaymentsachatcle':
            echo $userModel->getClientsWithPaymentsachatcle();
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
        case 'GetAllUsersWithOutAdminProperty':
            echo $userModel->GetAllUsersWithOutAdminProperty();
            break;

        case 'GetAgentsGuichets':
            echo $userModel->GetAgentsGuichets();
            break;
        case 'GetChangementCle':
            echo $userModel->GetChangementCle();
            break;
        case 'GetChangementLivraison':
            echo $userModel->GetChangementLivraison();
            break;

        case 'GetBoitesPostales':
            echo $userModel->GetBoitesPostales();
            break;
        case 'CountResilations':
            echo $userModel->CountResilations();
            break;
        case 'GetUser':
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = (int) $_GET['id']; // On s'assure que l'ID est un entier
                // Appeler la méthode GetUser du modèle et l'afficher
                echo $userModel->GetUser($id);
            } else {
                echo json_encode(["error" => "User ID is required"]);
            }
            break;



        default:
            echo json_encode(['error' => 'Invalid method']);
    }
}

// Gestion des requêtes POST
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    switch ($method) {
        case 'createColisNoFound':
            echo $colisNotFound->createColisNoFound($data);
            break;
        case 'GetClientEtatBoitePostale':
            echo $boitePostaleModel->GetClientEtatBoitePostale($data);
            break;

        case 'addSousCouvette':
            if (isset($id)) {
                $idClient = (int) $id;
                $data = file_get_contents('php://input'); // Récupérer les données JSON
                $boitePostaleModel->addSousCouvette($idClient, $data);
            } else {
                echo json_encode(["error" => "ID du client manquant."]);
            }
            break;


        case 'addMontantAchatsCle':
            echo $boitePostaleModel->addMontantAchatsCle($id, $data);
            break;

        case 'enregistrerPaiement':
            if (isset($id)) {
                $idClient = (int) $id;
                $data = file_get_contents('php://input'); // Récupérer les données JSON
                $boitePostaleModel->enregistrerPaiement($idClient, $data);
            } else {
                echo json_encode(["error" => "ID du client manquant."]);
            }
            break;



        case 'updateClientNameAndAddPayment':
            echo $boitePostaleModel->updateClientNameAndAddPayment($id, $data);
            break;

        case 'CreateUser':
            echo $userModel->CreateUser();
            break;

        case 'insertAndAssignBoitePostaleToClient':
            echo $boitePostaleModel->insertAndAssignBoitePostaleToClient($id, $data);
            break;
        case 'insererLivraisonEtMettreAJourPaiement':
            echo $boitePostaleModel->insererLivraisonEtMettreAJourPaiement($id, $data);
            break;
            case 'insererCollectionEtMettreAJourPaiement':
                // Vérifiez si l'ID client est passé correctement
                $idClient = $_GET['id_client'] ?? $_POST['id_client'] ?? null;
            
                // Vérification que l'ID client est présent
                if (!$idClient) {
                    echo json_encode(["error" => "idClient est manquant."]);
                    exit;
                }
            
                // Récupération des données envoyées en POST (JSON)
                $data = file_get_contents("php://input");
            
                // Vérification que les données sont bien reçues
                if (empty($data)) {
                    echo json_encode(["error" => "Les données sont vides."]);
                    exit;
                }
            
                // Appel de la fonction dans le modèle
                $result = $boitePostaleModel->insererCollectionEtMettreAJourPaiement($idClient, $data);
                echo $result;
                break;

                case 'insertExaunoration':
                    // Vérifiez si l'ID client est passé correctement
                    $idClient = $_GET['id_client'] ?? $_POST['id_client'] ?? null;
                
                    // Vérification que l'ID client est présent
                    if (!$idClient) {
                        echo json_encode(["error" => "idClient est manquant."]);
                        exit;
                    }
                
                    // Récupération des données envoyées en POST (JSON)
                    $data = file_get_contents("php://input");
                
                    // Vérification que les données sont bien reçues
                    if (empty($data)) {
                        echo json_encode(["error" => "Les données sont vides."]);
                        exit;
                    }
                
                    // Appel de la fonction dans le modèle
                    $result = $userModel->insertExaunoration($idClient, $data);
                    echo $result;
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

        case 'UpdateUser':
            // Récupération de l'ID de l'utilisateur depuis les paramètres GET
            $userId = $_GET['id'] ?? null;

            // Vérification de la présence de l'ID de l'utilisateur
            if ($userId) {
                // Récupération des données JSON depuis le corps de la requête
                $jsonData = file_get_contents('php://input');

                // Appel de la méthode dans le contrôleur pour mettre à jour l'utilisateur
                echo $userModel->updateUser($userId, $jsonData);
            } else {
                // Retourner une erreur si l'ID de l'utilisateur est manquant
                echo json_encode(['error' => 'Missing user ID']);
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
                echo $adrCasSensible->deleteCasSensible(json_encode(['id' => (int) $id]));
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

        case 'DeleteUser':
            // Récupération de l'ID depuis les paramètres GET
            $id = $_GET['id'] ?? null;

            // Vérification de la présence de l'ID
            if ($id) {
                // Appel de la méthode DeleteUser avec l'ID
                echo $userModel->DeleteUser($id);
            } else {
                // Retourner une erreur si l'ID est manquant
                echo json_encode(['error' => 'User ID is required']);
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
