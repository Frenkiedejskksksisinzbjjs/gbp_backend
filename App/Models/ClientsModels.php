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
                SELECT  DISTINCT 
                    c.*, 
                    a.Status AS abonnement_status, 
                    SUM(a.Penalite) AS abonnement_penalite, 
                    MAX(a.Annee_abonnement) AS annee_abonnement, 
                    b.Numero AS boite_postal_numero, 
                    (SELECT COUNT(*) FROM sous_couverte sc WHERE sc.Id_client = c.id) AS nombre_sous_couverte,
                    (SELECT COUNT(*) FROM lvdomcile L WHERE L.Id_clients = c.id) AS Adresse_Livraison,
                    (SELECT COUNT(*) FROM collections Cl WHERE Cl.Id_clients = c.id) AS Adresse_Collection
                FROM clients c
                LEFT JOIN abonnement a ON c.id = a.Id_client
                LEFT JOIN boit_postal b ON c.Id_boite_postale  = b.id
                 WHERE c.id not in (SELECT Id_client from resilier)
                GROUP BY c.id, b.Numero;
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier si des clients existent
            if (!$clients) {
                echo json_encode(['message' => 'Aucun client trouvé.']);
            }

            echo json_encode($clients);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
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
                echo json_encode(['message' => 'Aucun client résilié trouvé.']);
                return;
            }

            echo json_encode(['clients_resilies' => $clientsResilies]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
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
                echo json_encode(['message' => 'Aucun client exonéré trouvé.']);
                return;
            }

            echo json_encode($clientsExonores);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
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

    public function AddClientsAbonnment($idUser, $Data, $files)
    {
        try {
            $pdo = $this->db->getPdo();
            $pdo->beginTransaction(); // Démarrer une transaction

            // Vérification si la boîte postale existe déjà
            $verificationSql = "SELECT id FROM boit_postal WHERE Numero = :Numero";
            $Verfystmt = $pdo->prepare($verificationSql);
            $Verfystmt->execute([
                ':Numero' => $Data['BoitePostale']
            ]);

            // Récupération de l'ID si la boîte existe
            $idBoitePostale = $Verfystmt->fetchColumn();

            if ($idBoitePostale) {
                // Mise à jour du type si la boîte postale existe déjà
                $updateSql = "UPDATE boit_postal SET Type = :Type WHERE id = :id";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->execute([
                    ':Type' => $Data['TypeBp'],
                    ':id' => $idBoitePostale
                ]);
            } else {
                // La boîte postale n'existe pas, on l'insère
                $sqlBoitePostale = "INSERT INTO boit_postal (Numero, Type) VALUES (:Numero, :Type)";
                $stmt = $pdo->prepare($sqlBoitePostale);
                $stmt->execute([
                    ':Numero' => $Data['BoitePostale'],
                    ':Type' => $Data['TypeBp']
                ]);

                // Récupération de l'ID de la nouvelle boîte postale insérée
                $idBoitePostale = $pdo->lastInsertId();
            }



            // 1️⃣ Enregistrer le client dans la table clients
            $sqlClient = "INSERT INTO clients (Nom, Email, Adresse, TypeClient, Telephone, Id_boite_postale, Date_abonnement, id_user, updated_by)
                          VALUES (:Nom, :Email, :Adresse, :TypeClient, :Telephone, :Id_boite_postale, NOW(), :idUser, :updated_by)";
            $stmt = $pdo->prepare($sqlClient);
            $stmt->execute([
                ':Nom' => $Data['Nom'],
                ':Email' => $Data['Email'],
                ':Adresse' => $Data['Adresse'],
                ':TypeClient' => $Data['Role'],
                ':Telephone' => $Data['Telephone'],
                ':Id_boite_postale' => $idBoitePostale,
                ':idUser' => $idUser,
                ':updated_by' => $idUser
            ]);
            $idClient = $pdo->lastInsertId();

            // 2️⃣ Enregistrer l'abonnement du client
            $montant = ($Data['Role'] === 'particulier') ? 15000 : 25000;

            $sqlAbonnement = "INSERT INTO abonnement (Id_client, Annee_abonnement, Montant, Penalite, Status, created_at, updated_at, updated_by) 
                              VALUES (:Id_client, YEAR(NOW()), :Montant, 0, 'payé', NOW(), NOW(), :updated_by)";
            $stmt = $pdo->prepare($sqlAbonnement);
            $stmt->execute([
                ':Id_client' => $idClient,
                ':Montant' => $montant,
                ':updated_by' => $idUser
            ]);

            $idAbonnement = $pdo->lastInsertId();

            // 3️⃣ Enregistrer le paiement
            $sqlPaiement = "INSERT INTO paiement (Id_abonnement, Methode_paiement, Wallet, Numero_wallet, Numero_cheque, Nom_bank, reference, created_at, created_by) 
                            VALUES (:Id_abonnement, :Methode_paiement, :Wallet, :Numero_wallet, :Numero_cheque, :Nom_bank, :reference, NOW(), :created_by)";
            $stmt = $pdo->prepare($sqlPaiement);
            $stmt->execute([
                ':Id_abonnement' => $idAbonnement,
                ':Methode_paiement' => $Data['Methode_de_paiement'],
                ':Wallet' => $Data['wallet'] ?? null,
                ':Numero_wallet' => $Data['Numero_wallet'],
                ':Numero_cheque' => $Data['Numero_cheque'],
                ':Nom_bank' => $Data['Nom_Banque'],
                ':reference' => $Data['Reference_Rdv'],
                ':created_by' => $idUser
            ]);
            $idPaiement = $pdo->lastInsertId();

            // 4️⃣ Enregistrer les documents
            $chemins = [];
            foreach (['Abonnement', 'Identiter', 'patent_quitance'] as $doc) {
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
                ':Identite' => $chemins['Identiter'],
                ':Patent_Quitance' => $chemins['patent_quitance'] ?? 'null',
                ':created_by' => $idUser
            ]);

            // 5️⃣ Enregistrer la livraison à domicile si choisie
            if (!empty($Data['Adresse_Livraison_Domicile'])) {
                $sqlLivraison = "INSERT INTO lvdomcile (Id_clients, Adresse, Date, created_by) 
                                 VALUES (:Id_clients, :Adresse, NOW(), :created_by)";
                $stmt = $pdo->prepare($sqlLivraison);
                $stmt->execute([
                    ':Id_clients' => $idClient,
                    ':Adresse' => $Data['Adresse_Livraison_Domicile'],
                    ':created_by' => $idUser
                ]);

                $sqlDetailPaiement = "INSERT INTO details_paiements (Id_paiement, Categories,Montant, Methode_paiement, Wallet, Numero_wallet, Numero_cheque, Nom_bank, reference, created_at, created_by) 
                                      VALUES (:Id_paiement, 'livraison_a_domicil',:Montant, :Methode_paiement, :Wallet, :Numero_wallet, :Numero_cheque, :Nom_bank, :reference, NOW(), :created_by)";
                $stmt = $pdo->prepare($sqlDetailPaiement);
                $stmt->execute([
                    ':Id_paiement' => $idPaiement,
                    ':Methode_paiement' => $Data['Methode_de_paiement'],
                    ':Montant' => $Data['montantLd'],
                    ':Wallet' => $Data['wallet'] ?? null,
                    ':Numero_wallet' => $Data['Numero_wallet'],
                    ':Numero_cheque' => $Data['Numero_cheque'],
                    ':Nom_bank' => $Data['Nom_Banque'],
                    ':reference' => $Data['reference_Ld'],
                    ':created_by' => $idUser
                ]);
            }

            // 6️⃣ Enregistrer la collecte si choisie
            if (!empty($Data['Adresse_collection'])) {
                $sqlCollection = "INSERT INTO collections (Id_clients, Adresse, Date, created_by) 
                                  VALUES (:Id_clients, :Adresse, NOW(), :created_by)";
                $stmt = $pdo->prepare($sqlCollection);
                $stmt->execute([
                    ':Id_clients' => $idClient,
                    ':Adresse' => $Data['Adresse_collection'],
                    ':created_by' => $idUser
                ]);

                $sqlDetailPaiement = "INSERT INTO details_paiements (Id_paiement, Categories,Montant, Methode_paiement, Wallet, Numero_wallet, Numero_cheque, Nom_bank, reference, created_at, created_by) 
                VALUES (:Id_paiement, 'collections',:Montant, :Methode_paiement, :Wallet, :Numero_wallet, :Numero_cheque, :Nom_bank, :reference, NOW(), :created_by)";
                $stmt = $pdo->prepare($sqlDetailPaiement);
                $stmt->execute([
                    ':Id_paiement' => $idPaiement,
                    ':Methode_paiement' => $Data['Methode_de_paiement'],
                    ':Montant' => $Data['montantCll'],
                    ':Wallet' => $Data['wallet'] ?? null,
                    ':Numero_wallet' => $Data['Numero_wallet'],
                    ':Numero_cheque' => $Data['Numero_cheque'],
                    ':Nom_bank' => $Data['Nom_Banque'],
                    ':reference' => $Data['reference_collection'],
                    ':created_by' => $idUser
                ]);
            }

            // 7️⃣ Enregistrer sous-couverte si choisie (limite max 5)
            if (!empty($Data['sousCouvertures'])) {
                // Correction du format JSON (ajout de guillemets aux clés)
                // $jsonString = preg_replace('/([{,])(\s*)([a-zA-Z0-9_]+)(\s*):/', '$1"$3":', $Data['sousCouvertures']);
                // Décoder la chaîne JSON en tableau associatif
                $sousCouvertes = json_decode($Data['sousCouvertures'], true);
                // var_dump($sousCouvertes);
                // Vérifier que le décodage a réussi et que c'est bien un tableau
                if (is_array($sousCouvertes)) {
                    foreach ($sousCouvertes as $sousCouverte) {
                        // Vérifier que tous les champs requis sont remplis et ne sont pas vides
                        if (
                            !empty($sousCouverte['societe']) &&
                            !empty($sousCouverte['personne']) &&
                            !empty($sousCouverte['telephone']) &&
                            !empty($sousCouverte['adresse'])
                        ) {

                            // Vérifier le nombre actuel d'enregistrements
                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM sous_couverte WHERE Id_client = :Id_client");
                            $stmt->execute([':Id_client' => $idClient]);
                            $countSousCouverte = $stmt->fetchColumn();

                            if ($countSousCouverte < 5) {
                                // Insérer la sous-couverte
                                $sqlSousCouverte = "INSERT INTO sous_couverte (Nom_societe, Nom_personne, Telephone, Adresse, Id_client, Created_by, date) 
                                        VALUES (:Nom_societe, :Nom_personne, :Telephone, :Adresse, :Id_client, :Created_by, NOW())";
                                $stmt = $pdo->prepare($sqlSousCouverte);
                                $stmt->execute([
                                    ':Nom_societe' => $sousCouverte['societe'],
                                    ':Nom_personne' => $sousCouverte['personne'],
                                    ':Telephone' => $sousCouverte['telephone'],
                                    ':Adresse' => $sousCouverte['adresse'],
                                    ':Id_client' => $idClient,
                                    ':Created_by' => $idUser
                                ]);
                            }
                            $sqlDetailPaiement = "INSERT INTO details_paiements (Id_paiement, Categories,Montant, Methode_paiement, Wallet, Numero_wallet, Numero_cheque, Nom_bank, reference, created_at, created_by) 
                            VALUES (:Id_paiement, 'sous_couverte',:Montant, :Methode_paiement, :Wallet, :Numero_wallet, :Numero_cheque, :Nom_bank, :reference, NOW(), :created_by)";
                            $stmt = $pdo->prepare($sqlDetailPaiement);
                            $stmt->execute([
                                ':Id_paiement' => $idPaiement,
                                ':Methode_paiement' => $Data['Methode_de_paiement'],
                                ':Montant' => $Data['montantSC'],
                                ':Wallet' => $Data['wallet'] ?? null,
                                ':Numero_wallet' => $Data['Numero_wallet'],
                                ':Numero_cheque' => $Data['Numero_cheque'],
                                ':Nom_bank' => $Data['Nom_Banque'],
                                ':reference' => $Data['reference_Sc'],
                                ':created_by' => $idUser
                            ]);
                        }
                    }
                }
            }


            // 🔄 Commit la transaction
            $pdo->commit();
            echo json_encode(['success' => 'Client et abonnements enregistrés avec succès.']);
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        }
    }


    public function GetAllClientCount()
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête SQL pour compter le nombre total de clients
            $sql = "SELECT COUNT(*) AS total FROM clients";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            // Récupérer le résultat
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Retourner le nombre total de clients ou 0 si NULL
            echo json_encode(['count' => $result['total'] ?? 0]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage(), 'total_clients' => 0]);
        }
    }



    public function GetAllClientsCountWithStatusPaye()
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête SQL avec une jointure entre `clients` et `abonnement`
            $sql = "SELECT COUNT(DISTINCT c.id) AS total 
                    FROM clients c
                    INNER JOIN abonnement a ON c.id = a.Id_client
                    WHERE a.Status = 'paye'";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            // Récupérer le résultat
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Retourner le nombre total de clients abonnés avec statut "paye" ou 0 si NULL
            echo json_encode(['count' => $result['total'] ?? 0]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage(), 'total_clients_paye' => 0]);
        }
    }

    public function GetAllClientsCountWithStatusNonPaye()
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête SQL avec une jointure entre `clients` et `abonnement`
            $sql = "SELECT COUNT(DISTINCT c.id) AS total 
                FROM clients c
                INNER JOIN abonnement a ON c.id = a.id_client
                WHERE a.Status = 'impayé'";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            // Récupérer le résultat
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Retourner le nombre total de clients abonnés avec statut "non payé" ou 0 si NULL
            echo json_encode(['count' => $result['total'] ?? 0]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage(), 'total_clients_non_paye' => 0]);
        }
    }


    public function GetAllClientsExonoreCount()
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête pour compter le nombre total des clients exonérés
            $sql = "
            SELECT COUNT(*) AS total
            FROM clients c
            INNER JOIN exonore e ON c.id = e.Id_client
        ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Retourner le nombre total de clients exonérés ou 0 si aucun
            echo json_encode(['count' => $result['total'] ?? 0]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage(), 'total_clients_exonores' => 0]);
        }
    }

    public function GetCountOfClientsResilies()
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête SQL pour compter le nombre total de clients résiliés
            $sql = "
            SELECT COUNT(*) AS total
            FROM resilier r
            JOIN clients c ON r.Id_client = c.id
        ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Retourner le nombre total de clients résiliés ou 0 si aucun
            echo json_encode(['count' => $result['total'] ?? 0]);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données: ' . $e->getMessage(), 'total_clients_resilies' => 0]);
        }
    }

    public function AfficherDocument($id)
    {
        try {
            $pdo = $this->db->getPdo();

            // Requête SQL pour récupérer tous les documents du client
            $sql = "
            SELECT *
            FROM documents 
            WHERE Id_client = :id
        ";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
            } else {
                echo json_encode(['error' => 'Aucun document pour ce client.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur de la base de données : ' . $e->getMessage()]);
        }
    }
}
