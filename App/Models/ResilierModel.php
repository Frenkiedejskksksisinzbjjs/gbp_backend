<?php

namespace App\Models;

use App\Db\Db;
use PDO;
use PDOException;

class ResilierModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Db();
    }


    public function ResilierClients($idClient, $id_user, $files)
    {
        try {
            // Vérification si le client existe
            $sql_check = "SELECT id FROM clients WHERE id = :idClient";
            $stmt_check = $this->db->getPdo()->prepare($sql_check);
            $stmt_check->execute([':idClient' => $idClient]);

            if ($stmt_check->rowCount() == 0) {
                // Si le client n'existe pas
                echo json_encode(['error' => 'Client non trouvé']);
                return;
            }

            // Vérification des abonnements impayés du client
            $sql_check_abonnement = "SELECT Id, Status, Annee_abonnement FROM abonnement WHERE Id_client = :idClient";
            $stmt_check_abonnement = $this->db->getPdo()->prepare($sql_check_abonnement);
            $stmt_check_abonnement->execute([':idClient' => $idClient]);

            $abonnements = $stmt_check_abonnement->fetchAll(PDO::FETCH_ASSOC);

            // Si aucun abonnement n'est trouvé pour ce client
            if (!$abonnements) {
                echo json_encode(['error' => 'Aucun abonnement trouvé pour ce client']);
                return;
            }

            // Vérifier si l'un des abonnements est impayé
            $abonnementImpayé = array_filter($abonnements, function ($abonnement) {
                return $abonnement['Status'] === 'impayé';
            });

            if (count($abonnementImpayé) > 0) {
                // Si le client a des abonnements impayés, l'informer
                $anneesImpayees = array_map(function ($abonnement) {
                    return $abonnement['Annee_abonnement'];
                }, $abonnementImpayé);
                $anneesImpayeesStr = implode(', ', $anneesImpayees);
                echo json_encode(['error' => "Veuillez régler vos abonnements impayés pour les années : $anneesImpayeesStr avant de résilier votre compte."]);
                return;
            }

            // Vérification et gestion du fichier (lettre de recommandation)
            if ($files['lettre_recommandation']['error'] === UPLOAD_ERR_OK) {
                // Récupérer le nom du fichier et son extension
                $fileTmpPath = $files['lettre_recommandation']['tmp_name'];
                $fileName = $files['lettre_recommandation']['name'];
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

                // Définir un nom de fichier unique
                $newFileName = 'lettre_' . uniqid() . '.' . $fileExtension;

                // Définir le dossier de destination pour l'upload
                $uploadDir = 'upload/documents/lettre_recommandation_resilier/';
                if (!is_dir($uploadDir)) {
                    // Si le dossier n'existe pas, le créer
                    mkdir($uploadDir, 0777, true);
                }

                // Chemin complet pour le fichier
                $destPath = $uploadDir . $newFileName;

                // Déplacer le fichier téléchargé vers le dossier de destination
                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    // Le fichier est téléchargé avec succès, on continue à enregistrer la résiliation
                    $lettreRecommandationPath = $destPath;
                } else {
                    // Si l'upload échoue
                    echo json_encode(['error' => 'Erreur lors de l\'upload du fichier.']);
                    return;
                }
            } else {
                // Si aucune erreur de fichier, retourner une erreur
                echo json_encode(['error' => 'Aucun fichier téléchargé ou fichier invalide.']);
                return;
            }

            // Insérer les informations de résiliation dans la table resilier
            $sql_insert = "INSERT INTO resilier (Id_client, Lettre_recommandation, Date_resilier, Resilier_by)
                           VALUES (:idClient, :lettreRecommandation, NOW(), :resilierBy)";

            $stmt_insert = $this->db->getPdo()->prepare($sql_insert);

            // Exécuter la requête d'insertion
            $stmt_insert->execute([
                ':idClient' => $idClient,
                ':lettreRecommandation' => $lettreRecommandationPath,
                ':resilierBy' => $id_user
            ]);

            // Retourner un message de succès
            echo json_encode(['success' => 'Client résilié avec succès.']);
        } catch (PDOException $e) {
            // Gestion des erreurs : si une exception PDO est lancée, retourner le message d'erreur
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }


    public function TousLesClientsResiliers()
    {
        try {
            // Préparer la requête SQL pour récupérer tous les clients résiliés avec toutes les informations supplémentaires
            $sql = "SELECT  DISTINCT 
                    c.*,r.*, 
                    a.Status AS abonnement_status, 
                    SUM(a.Penalite) AS abonnement_penalite, 
                    MAX(a.Annee_abonnement) AS annee_abonnement, 
                    b.Numero AS boite_postal_numero, 
                    (SELECT COUNT(*) FROM sous_couverte sc WHERE sc.Id_client = c.id) AS nombre_sous_couverte,
                    (SELECT COUNT(*) FROM lvdomcile L WHERE L.Id_clients = c.id) AS Adresse_Livraison,
                    (SELECT COUNT(*) FROM collections Cl WHERE Cl.Id_clients = c.id) AS Adresse_Collection,
                    u.Nom as Agents
                FROM resilier r
                JOIN clients c ON r.Id_client = c.id
                JOIN users u ON r.Resilier_by = u.id
                LEFT JOIN abonnement a ON c.id = a.Id_client
                LEFT JOIN boit_postal b ON c.Id_boite_postale  = b.id
                GROUP BY c.id, b.Numero; ";

            $stmt = $this->db->getPdo()->prepare($sql);

            // Exécution de la requête
            $stmt->execute();

            // Vérifier si des résultats sont retournés
            if ($stmt->rowCount() > 0) {
                // Récupérer les résultats sous forme de tableau associatif
                $clients_resilies = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Retourner les résultats sous forme de JSON
                echo json_encode($clients_resilies);
            } else {
                // Si aucun client résilié, retourner un message d'erreur
                echo json_encode(['error' => 'Aucun client résilié trouvé']);
            }
        } catch (PDOException $e) {
            // Gestion des erreurs : si une exception PDO est lancée, retourner le message d'erreur
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }

    public function RecaputilationsResilier($id)
    {
        try {
            $pdo = $this->db->getPdo();
            $sql = "SELECT C.*, R.*,b.Numero
                    FROM resilier R
                    LEFT JOIN clients C ON C.id = R.Id_client
                    JOIN boit_postal b ON b.id = C.Id_boite_postale
                    WHERE R.Resilier_by = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($result)) {
                echo json_encode($result);
            } else {
                echo json_encode(['error' => 'Ce client n\'a pas de sous-couverture.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }
}
