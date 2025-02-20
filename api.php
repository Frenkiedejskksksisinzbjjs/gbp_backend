<?php

// Inclure le fichier autoload

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

// Gestion des requêtes GET
if ($_SERVER['REQUEST_METHOD'] === 'GET'){
  switch ($method) {
    case 'getAllUsers':
        $UserController->getAllUsers();
        break;
    
    default:
        # code...
        break;
  }
}
// Gestion des requêtes POST
elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){

}
// Gestion des requêtes PUT
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT'){

}
// Gestion des requêtes DELETE
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE'){

}// Méthode invalide
else {

}
