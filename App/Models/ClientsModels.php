<?php

namespace App\Models;

use App\Db\Db;
use PDO;
use PDOException;

class ClientsModels
{

    private $db;

    public function __construct()
    {
        $this->db = new Db();
    }


    // tous les clients
    public function GetAllClients()
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête pour récupérer tous les clients avec leurs informations supplémentaires
            $sql = "
                SELECT 
                    c.*, 
                    a.Status AS abonnement_status, 
                    a.Penalite AS abonnement_penalite, 
                    a.Annee_abonnement, 
                    b.Numero AS boite_postal_numero, 
                    (SELECT COUNT(*) FROM sous_couverte sc WHERE sc.Id_client = c.id) AS nombre_sous_couverte
                FROM clients c
                LEFT JOIN abonnement a ON c.id = a.Id_client
                LEFT JOIN boit_postal b ON c.Id_boite_postale  = b.id
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier si des clients existent
            if (!$clients) {
                return json_encode(['message' => 'Aucun client trouvé.']);
            }

            return json_encode($clients);
        } catch (PDOException $e) {
            return json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }

    // tous les clients resiliers
    public function GetAllClientsResilies()
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête pour récupérer les clients résiliés avec leurs informations
            $sql = "
                    SELECT 
                    c.*, 
                    r.Lettre_recommandation AS motif_resiliation, 
                    r.Date_resilier, 
                    a.Status AS abonnement_status, 
                    a.Penalite AS abonnement_penalite, 
                    b.Numero AS boite_postal_numero, 
                    (SELECT COUNT(*) FROM sous_couverte sc WHERE sc.Id_client = c.id) AS nombre_sous_couverte,
                    U.Nom AS resilier_par
                    FROM clients c
                    INNER JOIN resilier r ON c.id = r.Id_client
                    LEFT JOIN abonnement a ON c.id = a.Id_client
                    LEFT JOIN boit_postal b ON c.Id_boite_postale = b.id
                    LEFT JOIN users U ON r.Resilier_by = U.id;
             ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $clientsResilies = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier si des clients résiliés existent
            if (!$clientsResilies) {
                return json_encode(['message' => 'Aucun client résilié trouvé.']);
            }

            return json_encode(['clients_resilies' => $clientsResilies]);
        } catch (PDOException $e) {
            return json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }

    // tous les clients exonorer
    public function GetAllClientsExonore()
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête pour récupérer tous les clients exonérés avec leurs informations
            $sql = "
                SELECT 
                    c.*, 
                    e.Date, 
                    a.Status AS abonnement_status, 
                    a.Penalite AS abonnement_penalite, 
                    b.Numero AS boite_postal_numero, 
                    (SELECT COUNT(*) FROM sous_couverte sc WHERE sc.Id_client = c.id) AS nombre_sous_couverte,
                    u.Nom AS exonore_par
                FROM clients c
                INNER JOIN exonore e ON c.id = e.Id_client
                LEFT JOIN abonnement a ON c.id = a.Id_client
                LEFT JOIN boit_postal b ON c.Id_boite_postale = b.id
                LEFT JOIN users u ON e.created_by = u.id
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $clientsExonores = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier si des clients exonérés existent
            if (!$clientsExonores) {
                return json_encode(['message' => 'Aucun client exonéré trouvé.']);
            }

            return json_encode($clientsExonores);
        } catch (PDOException $e) {
            return json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }


    // pour le future 
    public function GetAllClientsEnlevePenaliter()
    {
        try {
            //code...
        } catch (PDOException $e) {
            return json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }

    public function AddClientsAbonnment($idUser, $Data, $files) {
        try {
            $pdo = $this->db->getPdo();
            $pdo->beginTransaction(); // Démarrer une transaction
    
            // 1️⃣ Enregistrer le client dans la table clients
            $sqlClient = "INSERT INTO clients (Nom, Email, Adresse, TypeClient, Telephone, Id_boite_postale, Date_abonnement, id_user, updated_by)
                          VALUES (:Nom, :Email, :Adresse, :TypeClient, :Telephone, :Id_boite_postale, NOW(), :idUser, :updated_by)";
            $stmt = $pdo->prepare($sqlClient);
            $stmt->execute([
                ':Nom' => $Data['Nom'],
                ':Email' => $Data['Email'],
                ':Adresse' => $Data['Adresse'],
                ':TypeClient' => $Data['TypeClient'],
                ':Telephone' => $Data['Telephone'],
                ':Id_boite_postale' => $Data['Id_boite_postale'],
                ':idUser' => $idUser,
                ':updated_by' => $idUser
            ]);
            $idClient = $pdo->lastInsertId();
    
            // 2️⃣ Enregistrer l'abonnement du client
            $sqlAbonnement = "INSERT INTO abonnement (Id_client, Annee_abonnement, Montant, Penalite, Status, created_at, updated_at, updated_by) 
                              VALUES (:Id_client, YEAR(NOW()), 20000, 0, 'payé', NOW(), NOW(), :updated_by)";
            $stmt = $pdo->prepare($sqlAbonnement);
            $stmt->execute([
                ':Id_client' => $idClient,
                ':updated_by' => $idUser
            ]);
            $idAbonnement = $pdo->lastInsertId();
    
            // 3️⃣ Enregistrer le paiement
            $sqlPaiement = "INSERT INTO paiement (Id_abonnement, Methode_paiement, Wallet, Numero_wallet, Numero_cheque, Nom_bank, reference, created_at, created_by) 
                            VALUES (:Id_abonnement, :Methode_paiement, :Wallet, :Numero_wallet, :Numero_cheque, :Nom_bank, :reference, NOW(), :created_by)";
            $stmt = $pdo->prepare($sqlPaiement);
            $stmt->execute([
                ':Id_abonnement' => $idAbonnement,
                ':Methode_paiement' => $Data['Methode_paiement'],
                ':Wallet' => $Data['Wallet'],
                ':Numero_wallet' => $Data['Numero_wallet'],
                ':Numero_cheque' => $Data['Numero_cheque'],
                ':Nom_bank' => $Data['Nom_bank'],
                ':reference' => $Data['reference'],
                ':created_by' => $idUser
            ]);
            $idPaiement = $pdo->lastInsertId();
    
            // 4️⃣ Enregistrer les documents
            $chemins = [];
            foreach (['Abonnement', 'Identite', 'Patent_Quitance'] as $doc) {
                if (isset($files[$doc]) && $files[$doc]['error'] === 0) {
                    $chemin = 'upload/documents/' . time() . '_' . basename($files[$doc]['name']);
                    move_uploaded_file($files[$doc]['tmp_name'], $chemin);
                    $chemins[$doc] = $chemin;
                } else {
                    $chemins[$doc] = null;
                }
            }
    
            $sqlDocuments = "INSERT INTO documents (Id_client, Abonnement, Identite, Patent_Quitance, created_at, created_by)
                             VALUES (:Id_client, :Abonnement, :Identite, :Patent_Quitance, NOW(), :created_by)";
            $stmt = $pdo->prepare($sqlDocuments);
            $stmt->execute([
                ':Id_client' => $idClient,
                ':Abonnement' => $chemins['Abonnement'],
                ':Identite' => $chemins['Identite'],
                ':Patent_Quitance' => $chemins['Patent_Quitance'],
                ':created_by' => $idUser
            ]);
    
            // 5️⃣ Enregistrer la livraison à domicile si choisie
            if (!empty($Data['Livraison'])) {
                $sqlLivraison = "INSERT INTO lvdomcile (Id_clients, Adresse, Date, created_by) 
                                 VALUES (:Id_clients, :Adresse, NOW(), :created_by)";
                $stmt = $pdo->prepare($sqlLivraison);
                $stmt->execute([
                    ':Id_clients' => $idClient,
                    ':Adresse' => $Data['Adresse'],
                    ':created_by' => $idUser
                ]);
    
                $sqlDetailPaiement = "INSERT INTO details_paiement (Id_paiement, Categories, Methode_paiement, Wallet, Numero_wallet, Numero_cheque, Nom_bank, reference, created_at, created_by) 
                                      VALUES (:Id_paiement, 'livraison_a_domicile', :Methode_paiement, :Wallet, :Numero_wallet, :Numero_cheque, :Nom_bank, :reference, NOW(), :created_by)";
                $stmt = $pdo->prepare($sqlDetailPaiement);
                $stmt->execute([
                    ':Id_paiement' => $idPaiement,
                    ':Methode_paiement' => $Data['Methode_paiement'],
                    ':Wallet' => $Data['Wallet'],
                    ':Numero_wallet' => $Data['Numero_wallet'],
                    ':Numero_cheque' => $Data['Numero_cheque'],
                    ':Nom_bank' => $Data['Nom_bank'],
                    ':reference' => $Data['reference'],
                    ':created_by' => $idUser
                ]);
            }
    
            // 6️⃣ Enregistrer la collecte si choisie
            if (!empty($Data['Collection'])) {
                $sqlCollection = "INSERT INTO collections (Id_clients, Adresse, Date, created_by) 
                                  VALUES (:Id_clients, :Adresse, NOW(), :created_by)";
                $stmt = $pdo->prepare($sqlCollection);
                $stmt->execute([
                    ':Id_clients' => $idClient,
                    ':Adresse' => $Data['Adresse'],
                    ':created_by' => $idUser
                ]);
            }
    
            // 7️⃣ Enregistrer sous-couverte si choisie (limite max 5)
            if (!empty($Data['SousCouverte'])) {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM sous_couverte WHERE Id_client = :Id_client");
                $stmt->execute([':Id_client' => $idClient]);
                $countSousCouverte = $stmt->fetchColumn();
    
                if ($countSousCouverte < 5) {
                    $sqlSousCouverte = "INSERT INTO sous_couverte (Nom_societe, Nom_personne, Telephone, Adresse, Id_client, Created_by, date) 
                                        VALUES (:Nom_societe, :Nom_personne, :Telephone, :Adresse, :Id_client, :Created_by, NOW())";
                    $stmt = $pdo->prepare($sqlSousCouverte);
                    $stmt->execute([
                        ':Nom_societe' => $Data['Nom_societe'],
                        ':Nom_personne' => $Data['Nom_personne'],
                        ':Telephone' => $Data['Telephone'],
                        ':Adresse' => $Data['Adresse'],
                        ':Id_client' => $idClient,
                        ':Created_by' => $idUser
                    ]);
                }
            }
    
            // 🔄 Commit la transaction
            $pdo->commit();
            return json_encode(['success' => 'Client et abonnements enregistrés avec succès.']);
    
        } catch (PDOException $e) {
            $pdo->rollBack();
            return json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }
    
}
