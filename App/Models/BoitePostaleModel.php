<?php

namespace App\Models;

use App\Db\Db;
use PDO;
use PDOException;

class BoitePostaleModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    // une fonction qui affecte une boîte postale à un client en mettant à jour les relations entre les deux. Elle vérifie d'abord si la boîte postale est déjà assignée à un client. Si ce n'est pas le cas, elle l'associe au client spécifié.

    public function insertAndAssignBoitePostaleToClient($iduser, $data, $files)
    {
        try {
            $jsData = json_encode($data);
            // var_dump($files);
            $this->db->getPdo()->beginTransaction();
            $Data = json_decode($jsData, true);

            // var_dump($Data);
            $dateActuelle = date('Y-m-d H:i:s');
            $anneeActuelle = date('Y');

            // Récupérer les données du client
            $nom = $Data['Nom'] ?? '';
            $email = $Data['Email'] ?? '';
            $telephone = $Data['Telephone'] ?? '';
            $adresse = $Data['Adresse'] ?? '';
            $boitePostale = $Data['BoitePostale'] ?? '';
            $role = $Data['Role'] ?? '';

            // Vérification des champs obligatoires
            if (empty($nom) || empty($email) || empty($telephone) || empty($boitePostale)) {
                echo json_encode(["error" => "Nom, Email, Téléphone et Boîte Postale sont obligatoires."]);
                return;
            }

            // Récupération de l'ID de la boîte postale
            $stmtbp = $this->db->getPdo()->prepare("SELECT id FROM boites_postales WHERE numero = :numero");
            $stmtbp->bindParam(':numero', $boitePostale, PDO::PARAM_STR);
            $stmtbp->execute();

            if ($stmtbp->rowCount() == 0) {
                echo json_encode(["error" => "Boîte postale introuvable."]);
                return;
            }

            $idBoitePostal = $stmtbp->fetch(PDO::FETCH_ASSOC)['id'];

            // Insérer le client
            $stmt = $this->db->getPdo()->prepare("
                INSERT INTO clients (nom, email, telephone, adresse, id_boite_postale, type_client, id_user, date_abonnement)
                VALUES (:nom, :email, :telephone, :adresse, :boite_postale, :type_client, :id_user, :date_abonnement)");
            $stmt->execute([
                ':nom' => $nom,
                ':email' => $email,
                ':telephone' => $telephone,
                ':adresse' => $adresse,
                ':boite_postale' => $idBoitePostal,
                ':type_client' => $role,
                ':id_user' => $iduser,
                ':date_abonnement' => $dateActuelle
            ]);

            $idClient = $this->db->getPdo()->lastInsertId();

            // Insérer le paiement
            $stmt = $this->db->getPdo()->prepare("
                INSERT INTO paiements (id_client, reference_general, montant_redevence, type, methode_payment, date_paiement)
                VALUES (:id_client, :reference, :montantRd, 'mis_a_jour', :methodePaiement, :date_paiement)");
            $stmt->execute([
                ':id_client' => $idClient,
                ':reference' => $Data['Reference_Rdv'] ?? '',
                ':montantRd' => $Data['montantRd'] ?? 0,
                ':methodePaiement' => $Data['Methode_de_paiement'] ?? '',
                ':date_paiement' => $dateActuelle
            ]);

            $idPaiement = $this->db->getPdo()->lastInsertId();

            // Insérer l'abonnement
            $stmt = $this->db->getPdo()->prepare("
                INSERT INTO abonnement (id_boite_postale, annee_abonnement, id_payments)
                VALUES (:id_boite_postale, :annee_abonnement, :id_payments)");
            $stmt->execute([
                ':id_boite_postale' => $idBoitePostal,
                ':annee_abonnement' => $anneeActuelle,
                ':id_payments' => $idPaiement
            ]);

            // Insérer les détails des paiements
            $this->insertDetailsPaiements($idPaiement, $Data, $iduser, "redevence", $Data['montantRd'] ?? 0, $Data['Reference_Rdv'] ?? '');

            // Insérer les sous-couverts
            if (!empty($Data['sousCouvertures'])) {
                foreach (array($Data['sousCouvertures']) as $sousCouvert) {
                    $stmt = $this->db->getPdo()->prepare("
                        INSERT INTO sous_couvete (nom_societe, nom_personne, telephone, adresse, id_client, id_user)
                        VALUES (:nom_societe, :nom_personne, :telephone, :adresse, :id_client, :id_user)");
                    $stmt->execute([
                        ':nom_societe' => $sousCouvert['nom_societe'] ?? '',
                        ':nom_personne' => $sousCouvert['nom_personne'] ?? '',
                        ':telephone' => $sousCouvert['telephone'] ?? '',
                        ':adresse' => $sousCouvert['adresse'] ?? '',
                        ':id_client' => $idClient,
                        ':id_user' => $iduser
                    ]);
                }
                // Insérer les détails des paiements
                $this->insertDetailsPaiements($idPaiement, $Data, $iduser, "sous_couvette", $Data['montantSC'] ?? 0, $Data['reference_Sc'] ?? '');
            }

            // Insérer les adresses de livraison et de collection
            if (!empty($Data['Adresse_Livraison_Domicile'])) {
                $stmt = $this->db->getPdo()->prepare("
                    INSERT INTO livraison_a_domicile (adresse, id_client, created_at)
                    VALUES (:adresse, :id_client, :created_at)");
                $stmt->execute([
                    ':adresse' => $Data['Adresse_Livraison_Domicile'],
                    ':id_client' => $idClient,
                    ':created_at' => $dateActuelle
                ]);
                // Insérer les détails des paiements
                $this->insertDetailsPaiements($idPaiement, $Data, $iduser, "livraison_domicile", $Data['montantLd'] ?? 0, $Data['reference_Ld'] ?? '');
            }

            if (!empty($Data['Adresse_collection'])) {
                $stmt = $this->db->getPdo()->prepare("
                    INSERT INTO collection (adresse, id_client, created_at)
                    VALUES (:adresse, :id_client, :created_at)");
                $stmt->execute([
                    ':adresse' => $Data['Adresse_collection'],
                    ':id_client' => $idClient,
                    ':created_at' => $dateActuelle
                ]);
                // Insérer les détails des paiements
                $this->insertDetailsPaiements($idPaiement, $Data, $iduser, "collection", $Data['montantCll'] ?? 0, $Data['reference_collection'] ?? '');
            }

            // Gérer les documents
            $this->handleDocuments($idClient, $files, $Data['Role'], uploadPath: 'upload/documents');

            $this->db->getPdo()->commit();
            echo json_encode(['success' => 'Abonnement avec succès']);
        } catch (\Exception $e) {
            $this->db->getPdo()->rollBack();
            echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    private function insertDetailsPaiements($idPaiement, $Data, $iduser, $categories, $montant, $reference)
    {
        $methodePaiement = $Data['Methode_de_paiement'] ?? '';
        $typeWallet = $methodePaiement === 'wallet' ? ($Data['Wallet'] ?? '') : '';
        $numeroWallet = $methodePaiement === 'wallet' ? ($Data['Numero_wallet'] ?? '') : '';
        $numeroCheque = $methodePaiement === 'cheque' ? ($Data['Numero_cheque'] ?? '') : '';
        $nomBanque = $methodePaiement === 'cheque' ? ($Data['Nom_Banque'] ?? '') : '';

        $stmt = $this->db->getPdo()->prepare("
            INSERT INTO details_paiements (paiement_id, categorie,montant, methode_payment, type_wallet, numero_wallet, numero_cheque, nom_banque,reference, created_by_user)
            VALUES (:paiement_id, :categories,:montant, :methode_payment, :type_wallet, :numero_wallet, :numero_cheque, :nom_banque,:reference, :created_by_user)");
        $stmt->execute([
            ':paiement_id' => $idPaiement,
            ':methode_payment' => $methodePaiement,
            ':type_wallet' => $typeWallet,
            ':numero_wallet' => $numeroWallet,
            ':numero_cheque' => $numeroCheque,
            ':nom_banque' => $nomBanque,
            ':categories' => $categories,
            ':montant' => $montant,
            ':reference' => $reference,
            ':created_by_user' => $iduser
        ]);
    }

    private function handleDocuments($idClient, $Data, $Role, $uploadPath)
    {
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Liste des fichiers attendus avec leurs noms de colonnes corrects
        $filesMapping = [
            'patent_quitance1' => 'patente_quitance',
            'Identiter1' => 'identite_gerant',
            'Abonnement1' => 'abonnement_unique'
        ];

        // Construire l'insertion dynamique des fichiers
        $insertData = [];
        $params = [
            ':type' => $Role,
            ':idClient' => $idClient
        ];

        foreach ($filesMapping as $inputKey => $dbColumn) {
            if (!empty($Data[$inputKey]['tmp_name'])) {
                $filePath = $uploadPath . '/' . basename($Data[$inputKey]['name']);
                move_uploaded_file($Data[$inputKey]['tmp_name'], $filePath);

                $insertData[$dbColumn] = $filePath;
            }
        }

        if (!empty($insertData)) {
            $columns = implode(", ", array_keys($insertData));
            $placeholders = implode(", ", array_map(fn($col) => ":$col", array_keys($insertData)));

            $sql = "INSERT INTO documents (type, $columns, created_at, id_client) VALUES (:type, $placeholders, NOW(), :idClient)";

            $stmt = $this->db->getPdo()->prepare($sql);

            foreach ($insertData as $column => $filePath) {
                $params[":$column"] = $filePath;
            }

            $stmt->execute($params);
        }
    }

























    // achat clé
    public function addMontantAchatsCle($id, $data)
    {
        try {
            // Décoder les données JSON
            $decodedData = json_decode($data, true);

            if (!$decodedData) {
                echo json_encode(["error" => "Format JSON invalide."]);
                return;
            }

            // Vérifier les champs obligatoires
            if (!isset($id, $decodedData['Methode_de_paiement'], $decodedData['Montant'], $decodedData['id_user'])) {
                echo json_encode(["error" => "Les champs 'id_client', 'methode_payment_cle', 'montant_achats_cle' et 'id_user' sont obligatoires."]);
                return;
            }

            $methodePaymentCle = $decodedData['Methode_de_paiement'];
            $typeWalletCle = $decodedData['Wallet'] ?? null;
            $referenceAchatCle = $decodedData['ReferenceId'] ?? null;
            $numeroWalletAchatCle = $decodedData['Numero_wallet'] ?? null;
            $numeroChequeAchatCle = $decodedData['Numero_cheque'] ?? null;
            $nomBanqueAchatCle = $decodedData['Nom_Banque'] ?? null;
            $idUser = $decodedData['id_user'];

            // Vérifier la validité de methode_payment_cle
            $validPaymentMethods = ['wallet', 'cash', 'cheque'];
            if (!in_array($methodePaymentCle, $validPaymentMethods)) {
                echo json_encode(["error" => "La méthode de paiement est invalide."]);
                return;
            }

            // Validation spécifique aux wallets
            if ($methodePaymentCle === 'wallet') {
                $validWalletTypes = ['waafi', 'cac_pay', 'd_money', 'sabpay', 'dahabplaces'];
                if (!in_array($typeWalletCle, $validWalletTypes) || empty($numeroWalletAchatCle)) {
                    echo json_encode(["error" => "Type de wallet ou numéro wallet invalide."]);
                    return;
                }
            }

            // Validation spécifique aux chèques
            if ($methodePaymentCle === 'cheque' && (empty($numeroChequeAchatCle) || empty($nomBanqueAchatCle))) {
                echo json_encode(["error" => "Numéro de chèque et nom de la banque sont obligatoires pour un paiement par chèque."]);
                return;
            }

            // Démarrer une transaction
            $this->db->getPdo()->beginTransaction();

            // Vérifier si un paiement existe pour ce client
            $sql = "SELECT id FROM paiements WHERE id_client = :id_client";
            $stmt2 = $this->db->getPdo()->prepare($sql);
            $stmt2->execute([':id_client' => $id]);
            $paiement = $stmt2->fetch(PDO::FETCH_ASSOC);

            if ($paiement) {
                $idpaiement = $paiement['id'];
                $sql2 = "INSERT INTO details_paiements 
                         (paiement_id, categorie, montant, methode_payment, type_wallet, numero_wallet, numero_cheque, nom_banque, reference, created_by_user)
                         VALUES (:idpaiement, 'achats_cle', :montant, :methode, :type, :numWallet, :numCheque, :nomBank, :reference, :idUser)";
                $stmt3 = $this->db->getPdo()->prepare($sql2);
                $stmt3->execute([
                    ':idpaiement' => $idpaiement,
                    ':montant' => $decodedData['Montant'],
                    ':methode' => $methodePaymentCle,
                    ':type' => $typeWalletCle,
                    ':numWallet' => $numeroWalletAchatCle,
                    ':numCheque' => $numeroChequeAchatCle,
                    ':nomBank' => $nomBanqueAchatCle,
                    ':reference' => $referenceAchatCle,
                    ':idUser' => $idUser
                ]);
            } else {
                echo json_encode(["error" => "Aucun paiement trouvé pour ce client."]);
                $this->db->getPdo()->rollBack();
                return;
            }

            // Valider la transaction
            $this->db->getPdo()->commit();
            echo json_encode(["success" => "Achats clé ajoutés et paiement mis à jour avec succès."]);
        } catch (PDOException $e) {
            $this->db->getPdo()->rollBack();
            echo json_encode(["error" => "Erreur de base de données : " . $e->getMessage()]);
        }
    }















    public function insererLivraisonEtMettreAJourPaiement($id, $data)
    {
        try {
            $this->db->getPdo()->beginTransaction();
            $decodedData = json_decode($data, true);

            // Validation des champs obligatoires
            $requiredFields = ['Adresse_Livraison_Domicile', 'Methode_de_paiement', 'Montant', 'NBp', 'ReferenceId', 'id_user'];
            foreach ($requiredFields as $field) {
                if (!isset($decodedData[$field])) {
                    throw new \Exception("Le champ '$field' est manquant.");
                }
            }

            $methodePaiement = $decodedData['Methode_de_paiement'];
            $validPaymentMethods = ['wallet', 'cash', 'cheque', 'carte_credits'];
            if (!in_array($methodePaiement, $validPaymentMethods)) {
                throw new \Exception("Méthode de paiement invalide.");
            }

            // Validation des données spécifiques à la méthode de paiement
            $typeWallet = $decodedData['Wallet'] ?? null;
            $numeroWallet = $decodedData['Numero_wallet'] ?? null;
            $numeroCheque = $decodedData['Numero_cheque'] ?? null;
            $nomBanque = $decodedData['Nom_Banque'] ?? null;

            if ($methodePaiement === 'wallet' && (!in_array($typeWallet, ['waafi', 'cac_pay', 'd_money', 'sabpay']) || empty($numeroWallet))) {
                throw new \Exception("Données de wallet invalides.");
            }
            if ($methodePaiement === 'cheque' && (!$numeroCheque || !$nomBanque)) {
                throw new \Exception("Données de chèque invalides.");
            }

            // Vérifier si le client possède un paiement de type 'mis_a_jour'
            $stmt = $this->db->getPdo()->prepare("SELECT id FROM paiements WHERE id_client = :id_client AND type = 'mis_a_jour'");
            $stmt->execute([':id_client' => $id]);
            $payment = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$payment) {
                throw new \Exception("Le client n'a pas de paiement de type 'mis_a_jour'. Modification non autorisée.");
            }

            // Insertion de la livraison
            $stmt = $this->db->getPdo()->prepare("INSERT INTO livraison_a_domicile (adresse, id_client) VALUES (:adresse, :id_client)");
            $stmt->execute([
                ':adresse' => $decodedData['Adresse_Livraison_Domicile'],
                ':id_client' => $id
            ]);

            // Vérification et mise à jour du paiement
            $stmt = $this->db->getPdo()->prepare("SELECT id FROM paiements WHERE id_client = :id_client");
            $stmt->execute([':id_client' => $id]);
            $paiement = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($paiement) {
                $stmt = $this->db->getPdo()->prepare(
                    "INSERT INTO details_paiements (paiement_id, categorie, montant, methode_payment, type_wallet, numero_wallet, numero_cheque, nom_banque, reference, created_by_user)
           VALUES (:paiement_id, 'livraison_domicile', :montant, :methode, :type_wallet, :numero_wallet, :numero_cheque, :nom_banque, :reference, :id_user)"
                );
                $stmt->execute([
                    ':paiement_id' => $paiement['id'],
                    ':montant' => $decodedData['Montant'],
                    ':methode' => $methodePaiement,
                    ':type_wallet' => $typeWallet,
                    ':numero_wallet' => $numeroWallet,
                    ':numero_cheque' => $numeroCheque,
                    ':nom_banque' => $nomBanque,
                    ':reference' => $decodedData['ReferenceId'],
                    ':id_user' => $decodedData['id_user']
                ]);
            }

            $this->db->getPdo()->commit();
            echo json_encode(["success" => "Livraison insérée et paiement mis à jour avec succès."]);
        } catch (\Exception $e) {
            $this->db->getPdo()->rollBack();
            echo json_encode(["error" => $e->getMessage()]);
        }
    }









    public function insererCollectionEtMettreAJourPaiement($idClient, $data)
    {
        try {
            // Décodage des données JSON
            $decodedData = json_decode($data, true);
            if (!$decodedData) {
                throw new \Exception("Format JSON invalide.");
            }

            // Validation des champs obligatoires
            $requiredFields = ['Adresse_collection', 'NBp', 'Methode_de_paiement', 'Montant', 'ReferenceId', 'id_user'];
            foreach ($requiredFields as $field) {
                if (empty($decodedData[$field])) {
                    throw new \Exception("Le champ '$field' est obligatoire.");
                }
            }

            // Initialisation des valeurs
            $methodePaiement = $decodedData['Methode_de_paiement'];
            $montant = $decodedData['Montant'];
            $reference = $decodedData['ReferenceId'];
            $idUser = $decodedData['id_user'];
            $typeWallet = $decodedData['Wallet'] ?? null;
            $numeroWallet = $decodedData['Numero_wallet'] ?? null;

            // Validation de la méthode de paiement
            $validPaymentMethods = ['wallet', 'cash', 'cheque', 'carte_credits'];
            if (!in_array($methodePaiement, $validPaymentMethods)) {
                throw new \Exception("Méthode de paiement invalide.");
            }

            // Vérification spécifique pour les wallets
            if ($methodePaiement === 'wallet') {
                $validWalletTypes = ['wafi', 'cac-pay', 'd-money', 'sab-pay'];
                if (!in_array($typeWallet, $validWalletTypes) || empty($numeroWallet)) {
                    throw new \Exception("Type de wallet invalide ou numéro wallet manquant.");
                }
            }

            // Démarrage de la transaction
            $this->db->getPdo()->beginTransaction();

            // Vérification de l'existence du paiement actif
            $stmt = $this->db->getPdo()->prepare("SELECT id FROM paiements WHERE id_client = :id_client AND type = 'mis_a_jour'");
            $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);
            $stmt->execute();
            $paymentResult = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$paymentResult) {
                throw new \Exception("Aucun paiement mis à jour trouvé pour ce client.");
            }

            $paiementId = $paymentResult['id'];


            echo json_encode(["success" => "Paiement ajouté ou mis à jour avec succès."]);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Erreur : " . $e->getMessage()]);
        }
    }


    public function enregistrerPaiement($id, $data)
    {
        try {
            // Vérifier si $idClient est présent et valide
            if (empty($id) || !is_numeric($id)) {
                echo json_encode(["error" => "L'ID du client est requis et doit être valide."]);
                return;
            }

            // Décoder les données JSON
            $decodedData = json_decode($data, true);

            // Vérifier les champs obligatoires
            if (!isset($id, $decodedData['Montant'], $decodedData['Methode_de_paiement'], $decodedData['ReferenceId'])) {
                echo json_encode(["error" => "Les champs 'methode_payment' et 'montant_redevence' sont obligatoires."]);
                return;
            }

            $methodePayment = $decodedData['Methode_de_paiement'];
            $montantRedevence = $decodedData['Montant'];
            $typeWallet = isset($decodedData['Wallet']) ? $decodedData['Wallet'] : null;
            $numeroWalletRedevence = isset($decodedData['Numero_wallet']) ? $decodedData['Numero_wallet'] : null;

            // Champs supplémentaires pour les paiements par chèque
            $numeroCheque = isset($decodedData['Numero_cheque']) ? $decodedData['Numero_cheque'] : null;
            $nomBanque = isset($decodedData['Nom_Banque']) ? $decodedData['Nom_Banque'] : null;

            // Vérifier la validité de methode_payment
            if (!in_array($methodePayment, ['wallet', 'cash', 'cheque'])) {
                echo json_encode(["error" => "La méthode de paiement est invalide."]);
                return;
            }

            // Si méthode de paiement est 'wallet', vérifier type_wallet et numero_wallet_redevence
            if ($methodePayment === 'wallet') {
                if (!isset($typeWallet) || !in_array($typeWallet, ['waafi', 'cac_pay', 'd_money', 'sabpay', 'dahaplaces'])) {
                    echo json_encode(["error" => "Si la méthode de paiement est 'wallet', 'type_wallet' est obligatoire et doit être valide."]);
                    return;
                }
                if (empty($numeroWalletRedevence)) {
                    echo json_encode(["error" => "Pour le paiement par wallet, 'numero_wallet_redevence' est obligatoire."]);
                    return;
                }
            }

            // Si méthode de paiement est 'cheque', vérifier les champs liés
            if ($methodePayment === 'cheque') {
                if (empty($numeroCheque) || empty($nomBanque)) {
                    echo json_encode(["error" => "Pour le paiement par chèque, 'numero_cheque' et 'nom_banque' sont obligatoires."]);
                    return;
                }
            }

            

            // Vérification et mise à jour du paiement
            $stmt = $this->db->getPdo()->prepare("SELECT id FROM paiements WHERE id_client = :id_client");
            $stmt->execute([':id_client' => $id]);
            $paiement = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($paiement) {
                $stmt = $this->db->getPdo()->prepare(
                    "INSERT INTO details_paiements (paiement_id, categorie, montant, methode_payment, type_wallet, numero_wallet, numero_cheque, nom_banque, reference, created_by_user)
           VALUES (:paiement_id, 'redevence', :montant, :methode, :type_wallet, :numero_wallet, :numero_cheque, :nom_banque, :reference, :id_user)"
                );
                $stmt->execute([
                    ':paiement_id' => $paiement['id'],
                    ':montant' => $decodedData['Montant'],
                    ':methode' => $methodePayment,
                    ':type_wallet' => $typeWallet,
                    ':numero_wallet' => $numeroWalletRedevence,
                    ':numero_cheque' => $numeroCheque,
                    ':nom_banque' => $nomBanque,
                    ':reference' => $decodedData['ReferenceId'],
                    ':id_user' => $decodedData['id_user']
                ]);
            }



            echo json_encode(["success" => "Paiement ajouté avec succès."]);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Erreur : " . $e->getMessage()]);
        }
    }




















    //une fonction qui permet de récupérer le nom du client à partir de son ID, de modifier le nom du client et d'enregistrer cette modification dans la base de données. La fonction met à jour le champ update_by dans la table clients et enregistre un paiement dans la table paiements avec un montant fixe de 5000.00 dans le champ montant_changement_nom.

    public function updateClientNameAndAddPayment($id, $data)
    {
        try {
            // Démarrer une transaction
            $this->db->getPdo()->beginTransaction();

            // Décodage des données JSON
            $decodedData = json_decode($data, true);

            // Vérification que tous les champs nécessaires sont présents
            if (!isset($id, $decodedData['Nom'], $decodedData['Methode_de_paiement'], $decodedData['Montant'], $decodedData['id_user'])) {
                throw new \Exception("Tous les champs sont obligatoires.");
            }

            $idClient = $id;
            $nouveauNom = $decodedData['Nom'];
            $methodePaymentNom = $decodedData['Methode_de_paiement'];
            $montantChangementNom = $decodedData['Montant'];
            $typeWalletNom = $decodedData['Wallet'] ?? null;
            $numeroCheque = $decodedData['Numero_cheque'] ?? null;
            $nomBanque = $decodedData['Nom_Banque'] ?? null;
            $Iduser = $decodedData['id_user'];
            $referenceChangerNom = $decodedData['ReferenceId'] ?? null;
            $numeroWalletChangementNom = $decodedData['Numero_wallet'] ?? null;

            // Vérifications des valeurs fournies
            if (empty($referenceChangerNom)) {
                throw new \Exception("Le champ 'reference_changer_nom' est obligatoire.");
            }

            if (!in_array($methodePaymentNom, ['wallet', 'cash', 'cheque', 'carte_credits'])) {
                throw new \Exception("La méthode de paiement est invalide.");
            }

            if ($methodePaymentNom === 'wallet') {
                if (!isset($typeWalletNom) || !in_array($typeWalletNom, ['waafi', 'cac_pay', 'd_money', 'sabpay'])) {
                    throw new \Exception("Si la méthode de paiement est 'wallet', 'type_wallet_nom' est obligatoire et doit être valide.");
                }
                if (empty($numeroWalletChangementNom)) {
                    throw new \Exception("Si la méthode de paiement est 'wallet', 'numero_wallet_changement_nom' est obligatoire.");
                }
            }

            if ($methodePaymentNom === 'cheque' && (empty($numeroCheque) || empty($nomBanque))) {
                throw new \Exception("Si la méthode de paiement est 'cheque', 'numero_cheque' et 'nom_banque' sont obligatoires.");
            }

            // Vérifier si le client possède un paiement avec le type "mis_a_jour"
            $paymentCheckQuery = "SELECT id FROM paiements WHERE id_client = :id_client AND type = 'mis_a_jour'";
            $stmt = $this->db->getPdo()->prepare($paymentCheckQuery);
            $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);
            $stmt->execute();
            $payment = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$payment) {
                throw new \Exception("Le client n'a pas de paiement avec le type 'mis_a_jour'. Modification non autorisée.");
            }

            // Mise à jour du nom du client
            $updateQuery = "UPDATE clients SET nom = :nouveau_nom WHERE id = :id_client";
            $stmt = $this->db->getPdo()->prepare($updateQuery);
            $stmt->bindParam(':nouveau_nom', $nouveauNom, PDO::PARAM_STR);
            $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);
            $stmt->execute();

            // Insérer les détails de paiement
            $insertPaymentQuery = "INSERT INTO details_paiements (paiement_id, categorie, montant, methode_payment, type_wallet, numero_wallet, numero_cheque, nom_banque, reference, created_by_user)
                VALUES (:paiement_id, 'livraison_domicile', :montant, :methode, :type_wallet, :numero_wallet, :numero_cheque, :nom_banque, :reference, :id_user)";
            $stmt = $this->db->getPdo()->prepare($insertPaymentQuery);
            $stmt->execute([
                ':paiement_id' => $payment['id'],
                ':montant' => $montantChangementNom,
                ':methode' => $methodePaymentNom,
                ':type_wallet' => $typeWalletNom,
                ':numero_wallet' => $numeroWalletChangementNom,
                ':numero_cheque' => $numeroCheque,
                ':nom_banque' => $nomBanque,
                ':reference' => $referenceChangerNom,
                ':id_user' => $Iduser
            ]);

            // Valider la transaction
            $this->db->getPdo()->commit();

            echo json_encode(["success" => "Le nom du client a été mis à jour et les informations de paiement enregistrées avec succès."]);
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $this->db->getPdo()->rollBack();
            echo json_encode(["error" => $e->getMessage()]);
        }
    }




















    public function addSousCouvette($id, $data)
    {
        try {
            // Démarrer une transaction
            $this->db->getPdo()->beginTransaction();

            // Décodage des données JSON
            $decodedData = json_decode($data, true);
            if (!$decodedData) {
                echo json_encode(["error" => "Données JSON invalides."]);
                return;
            }

            // Validation des champs requis
            $requiredFields = ['Methode_de_paiement', 'totalMontant', 'sousCouvertures'];
            foreach ($requiredFields as $field) {
                if (!isset($decodedData[$field])) {
                    echo json_encode(["error" => "Le champ '$field' est manquant."]);
                    return;
                }
            }

            // Assignation des variables
            $IdUser = $decodedData['id_user'];
            $methodePayment = $decodedData['Methode_de_paiement'];
            $totalMontant = $decodedData['totalMontant'];
            $sousCouvertures = $decodedData['sousCouvertures'];
            $referenceAjoutSousCouvette = $decodedData['ReferenceId'] ?? null;
            $typeWallet = $decodedData['Wallet'] ?? null;
            $numeroCheque = $decodedData['Numero_cheque'] ?? null;
            $nomBanque = $decodedData['Nom_Banque'] ?? null;
            $numeroWalletAjoutSousCouvette = $decodedData['Numero_wallet'] ?? null;

            // Vérification du nombre de sous-couvertures existantes
            $queryCountSousCouvette = "SELECT COUNT(*) AS total FROM sous_couvete WHERE id_client = :id_clients";
            $stmt = $this->db->getPdo()->prepare($queryCountSousCouvette);
            $stmt->bindParam(':id_clients', $id, PDO::PARAM_INT);
            $stmt->execute();
            $currentSousCouvetteCount = $stmt->fetchColumn();
            $remainingSpots = 5 - $currentSousCouvetteCount;

            if ($remainingSpots <= 0) {
                echo json_encode(["error" => "Limite de sous-couvertures atteinte. Aucun ajout supplémentaire n'est possible."]);
                return;
            }

            if (count($sousCouvertures) > $remainingSpots) {
                echo json_encode(["error" => "Il n'y a que $remainingSpots emplacement(s) disponible(s). Veuillez réduire le nombre de sous-couvertures à ajouter."]);
                return;
            }

            // Insertion des sous-couvertures
            $queryInsertSousCouvette = "
                INSERT INTO sous_couvete (nom_societe, nom_personne, telephone, adresse, id_client, id_user)
                VALUES (:nom_societe, :nom_personne, :telephone, :adresse, :id_client, :id_user)
            ";
            $stmt = $this->db->getPdo()->prepare($queryInsertSousCouvette);
            foreach ($sousCouvertures as $sousCouverture) {
                $stmt->execute([
                    ':nom_societe' => $sousCouverture['societe'],
                    ':nom_personne' => $sousCouverture['personne'],
                    ':telephone' => $sousCouverture['telephone'],
                    ':adresse' => $sousCouverture['adresse'],
                    ':id_client' => $id,
                    ':id_user' => $IdUser
                ]);
            }

            // Vérification du paiement existant
            $sql = "SELECT id FROM paiements WHERE id_client = :id_client";
            $stmt2 = $this->db->getPdo()->prepare($sql);
            $stmt2->execute([':id_client' => $id]);
            $paiement = $stmt2->fetch(PDO::FETCH_ASSOC);

            if ($paiement) {
                $idpaiement = $paiement['id'];
                $sql2 = "INSERT INTO details_paiements 
                         (paiement_id, categorie, montant, methode_payment, type_wallet, numero_wallet, numero_cheque, nom_banque, reference, created_by_user)
                         VALUES (:idpaiement, 'sous_couvette', :montant, :methode, :type, :numWallet, :numCheque, :nomBank, :reference, :idUser)";
                $stmt3 = $this->db->getPdo()->prepare($sql2);
                $stmt3->execute([
                    ':idpaiement' => $idpaiement,
                    ':montant' => $totalMontant,
                    ':methode' => $methodePayment,
                    ':type' => $typeWallet,
                    ':numWallet' => $numeroWalletAjoutSousCouvette,
                    ':numCheque' => $numeroCheque,
                    ':nomBank' => $nomBanque,
                    ':reference' => $referenceAjoutSousCouvette,
                    ':idUser' => $IdUser
                ]);
            }

            // Valider la transaction
            $this->db->getPdo()->commit();
            echo json_encode(["success" => "Sous-couvette ajoutée et paiement mis à jour avec succès."]);
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $this->db->getPdo()->rollBack();
            echo json_encode(["error" => "Erreur de base de données : " . $e->getMessage()]);
        }
    }







    // une fonction qui récupére les informations des clients
    public function GetAllClients()
    {
        try {
            // Requête SQL pour récupérer les informations des clients
            $sql = "
            SELECT DISTINCT
                c.id AS id,
                c.nom AS Nom,
                c.adresse AS Adresse,
                c.type_client AS TypeClient,
                bp.numero AS NBp,
                bp.type AS Type_boite_postale,
                c.telephone AS Telephone,
                a.annee_abonnement AS annee_abonnement,
                p.type AS Paiement_Type,
                p.penalites AS Penalites,
                p.montant_redevence AS Montant_Redevance,
                p.methode_payment AS Methode_Paiement,
                p.reference_general AS Reference_General,
                p.date_paiement AS Date_Paiement,

                -- Détermination de l'état de l'abonnement
                CASE 
                    WHEN a.annee_abonnement = YEAR(CURDATE()) THEN 'mis_a_jour'
                    ELSE 'non_mis_a_jour'
                END AS Etat,

                -- Nombre de sous-couvertes associées
                (SELECT COUNT(*) FROM sous_couvete sc WHERE sc.id_client = c.id) AS sous_couvert,

                -- Champs de la table documents
                d.type AS Document_Type,
                d.patente_quitance AS Patente_Quitance,
                d.identite_gerant AS Identite_Gerant,
                d.abonnement_unique AS Abonnement_Unique,
                d.created_at AS Document_Created_At,

                -- Concaténation des paiements sur une seule ligne
                GROUP_CONCAT(DISTINCT dp.categorie ORDER BY dp.categorie ASC SEPARATOR ', ') AS Paiement_Categories,
                GROUP_CONCAT(DISTINCT dp.montant ORDER BY dp.montant ASC SEPARATOR ', ') AS Paiement_Montants,
                GROUP_CONCAT(DISTINCT dp.methode_payment ORDER BY dp.methode_payment ASC SEPARATOR ', ') AS Paiement_Methodes,
                GROUP_CONCAT(DISTINCT dp.type_wallet ORDER BY dp.type_wallet ASC SEPARATOR ', ') AS Type_Wallets,
                GROUP_CONCAT(DISTINCT dp.numero_wallet ORDER BY dp.numero_wallet ASC SEPARATOR ', ') AS Numero_Wallets,
                GROUP_CONCAT(DISTINCT dp.numero_cheque ORDER BY dp.numero_cheque ASC SEPARATOR ', ') AS Numero_Cheques,
                GROUP_CONCAT(DISTINCT dp.nom_banque ORDER BY dp.nom_banque ASC SEPARATOR ', ') AS Nom_Banques,
                GROUP_CONCAT(DISTINCT dp.reference ORDER BY dp.reference ASC SEPARATOR ', ') AS Paiement_References

            FROM clients c
            LEFT JOIN boites_postales bp ON c.id_boite_postale = bp.id
            LEFT JOIN abonnement a ON bp.id = a.id_boite_postale
            LEFT JOIN collection col ON col.id_client = c.id
            LEFT JOIN livraison_a_domicile ld ON ld.id_client = c.id
            LEFT JOIN documents d ON c.id = d.id_client
            LEFT JOIN paiements p ON c.id = p.id_client
            LEFT JOIN details_paiements dp ON p.id = dp.paiement_id

            GROUP BY c.id, bp.numero, bp.type, a.annee_abonnement, p.id, d.id
        ";

            // Exécution de la requête
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();

            // Récupération des résultats
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($results)) {
                // Retourner les données au format JSON
                echo json_encode($results, JSON_PRETTY_PRINT);
            } else {
                // Aucun client trouvé
                http_response_code(404);
                echo json_encode(["error" => "Aucun client trouvé"]);
            }
        } catch (PDOException $e) {
            // Gestion des erreurs SQL
            http_response_code(500);
            echo json_encode(["error" => "Erreur SQL: " . $e->getMessage()]);
        }
    }




    public function getLastReferenceAchatCle()
    {
        try {
            // Préparer la requête pour récupérer la dernière référence pour la catégorie 'sous_couvette'
            $queryLastInsert = "SELECT reference
            FROM details_paiements
            WHERE categorie = 'achats_cle' AND reference IS NOT NULL
            ORDER BY 
                CAST(SUBSTRING(reference, 
                LOCATE('/', reference) + 1, 
                LOCATE('/', reference, LOCATE('/', reference) + 1) 
                - LOCATE('/', reference) - 1) AS UNSIGNED) DESC
            LIMIT 1";

            $stmt = $this->db->getPdo()->prepare($queryLastInsert);
            $stmt->execute();

            // Récupérer le résultat
            $dernierPaiement = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($dernierPaiement && !empty($dernierPaiement['reference'])) {
                // Retourner la référence si elle existe
                echo json_encode([
                    "reference" => $dernierPaiement['reference']
                ]);
            } else {
                // Retourner une valeur par défaut si la table est vide ou la référence absente
                echo json_encode([
                    "reference" => null, // Ou une valeur par défaut si nécessaire
                ]);
            }
        } catch (PDOException $e) {
            // Retourner une erreur en cas d'exception
            echo json_encode([
                "success" => false,
                "error" => "Erreur : " . $e->getMessage()
            ]);
        }
    }

    public function getLastReferenceSousCouvette()
    {
        try {
            // Préparer la requête pour récupérer la dernière référence pour la catégorie 'sous_couvette'
            $queryLastInsert = "SELECT reference
            FROM details_paiements
            WHERE categorie = 'sous_couvette' AND reference IS NOT NULL
            ORDER BY 
                CAST(SUBSTRING(reference, 
                LOCATE('/', reference) + 1, 
                LOCATE('/', reference, LOCATE('/', reference) + 1) 
                - LOCATE('/', reference) - 1) AS UNSIGNED) DESC
            LIMIT 1";

            $stmt = $this->db->getPdo()->prepare($queryLastInsert);
            $stmt->execute();

            // Récupérer le résultat
            $dernierPaiement = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($dernierPaiement && !empty($dernierPaiement['reference'])) {
                // Retourner la référence si elle existe
                echo json_encode([
                    "reference" => $dernierPaiement['reference']
                ]);
            } else {
                // Retourner une valeur par défaut si la table est vide ou la référence absente
                echo json_encode([
                    "reference" => null, // Ou une valeur par défaut si nécessaire
                ]);
            }
        } catch (PDOException $e) {
            // Retourner une erreur en cas d'exception
            echo json_encode([
                "success" => false,
                "error" => "Erreur : " . $e->getMessage()
            ]);
        }
    }


    public function getLastReferenceChangerNom()
    {
        try {
            // Préparer la requête pour récupérer la dernière référence pour la catégorie 'sous_couvette'
            $queryLastInsert = "SELECT reference
            FROM details_paiements
            WHERE categorie = 'changement_nom' AND reference IS NOT NULL
            ORDER BY 
                CAST(SUBSTRING(reference, 
                LOCATE('/', reference) + 1, 
                LOCATE('/', reference, LOCATE('/', reference) + 1) 
                - LOCATE('/', reference) - 1) AS UNSIGNED) DESC
            LIMIT 1";

            $stmt = $this->db->getPdo()->prepare($queryLastInsert);
            $stmt->execute();

            // Récupérer le résultat
            $dernierPaiement = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($dernierPaiement && !empty($dernierPaiement['reference'])) {
                // Retourner la référence si elle existe
                echo json_encode([
                    "reference" => $dernierPaiement['reference']
                ]);
            } else {
                // Retourner une valeur par défaut si la table est vide ou la référence absente
                echo json_encode([
                    "reference" => null, // Ou une valeur par défaut si nécessaire
                ]);
            }
        } catch (PDOException $e) {
            // Retourner une erreur en cas d'exception
            echo json_encode([
                "success" => false,
                "error" => "Erreur : " . $e->getMessage()
            ]);
        }
    }


    public function getLastReferenceLivraisonDomicile()
    {
        try {
            // Préparer la requête pour récupérer la dernière référence pour la catégorie 'sous_couvette'
            $queryLastInsert = "SELECT reference
            FROM details_paiements
            WHERE categorie = 'livraison_domicile' AND reference IS NOT NULL
            ORDER BY 
                CAST(SUBSTRING(reference, 
                LOCATE('/', reference) + 1, 
                LOCATE('/', reference, LOCATE('/', reference) + 1) 
                - LOCATE('/', reference) - 1) AS UNSIGNED) DESC
            LIMIT 1";

            $stmt = $this->db->getPdo()->prepare($queryLastInsert);
            $stmt->execute();

            // Récupérer le résultat
            $dernierPaiement = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($dernierPaiement && !empty($dernierPaiement['reference'])) {
                // Retourner la référence si elle existe
                echo json_encode([
                    "reference" => $dernierPaiement['reference']
                ]);
            } else {
                // Retourner une valeur par défaut si la table est vide ou la référence absente
                echo json_encode([
                    "reference" => null, // Ou une valeur par défaut si nécessaire
                ]);
            }
        } catch (PDOException $e) {
            // Retourner une erreur en cas d'exception
            echo json_encode([
                "success" => false,
                "error" => "Erreur : " . $e->getMessage()
            ]);
        }
    }

    public function getLastReferenceAjoutCollection()
    {
        try {
            // Préparer la requête pour récupérer la dernière référence pour la catégorie 'sous_couvette'
            $queryLastInsert = "SELECT reference
            FROM details_paiements
            WHERE categorie = 'collection' AND reference IS NOT NULL
            ORDER BY 
                CAST(SUBSTRING(reference, 
                LOCATE('/', reference) + 1, 
                LOCATE('/', reference, LOCATE('/', reference) + 1) 
                - LOCATE('/', reference) - 1) AS UNSIGNED) DESC
            LIMIT 1";

            $stmt = $this->db->getPdo()->prepare($queryLastInsert);
            $stmt->execute();

            // Récupérer le résultat
            $dernierPaiement = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($dernierPaiement && !empty($dernierPaiement['reference'])) {
                // Retourner la référence si elle existe
                echo json_encode([
                    "reference" => $dernierPaiement['reference']
                ]);
            } else {
                // Retourner une valeur par défaut si la table est vide ou la référence absente
                echo json_encode([
                    "reference" => null, // Ou une valeur par défaut si nécessaire
                ]);
            }
        } catch (PDOException $e) {
            // Retourner une erreur en cas d'exception
            echo json_encode([
                "success" => false,
                "error" => "Erreur : " . $e->getMessage()
            ]);
        }
    }


    public function getLastReference()
    {
        try {
            // Préparer la requête pour récupérer la dernière référence pour la catégorie 'sous_couvette'
            $queryLastInsert = "SELECT reference
            FROM details_paiements
            WHERE categorie = 'redevence' AND reference IS NOT NULL
            ORDER BY 
                CAST(SUBSTRING(reference, 
                LOCATE('/', reference) + 1, 
                LOCATE('/', reference, LOCATE('/', reference) + 1) 
                - LOCATE('/', reference) - 1) AS UNSIGNED) DESC
            LIMIT 1";

            $stmt = $this->db->getPdo()->prepare($queryLastInsert);
            $stmt->execute();

            // Récupérer le résultat
            $dernierPaiement = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($dernierPaiement && !empty($dernierPaiement['reference'])) {
                // Retourner la référence si elle existe
                echo json_encode([
                    "reference" => $dernierPaiement['reference']
                ]);
            } else {
                // Retourner une valeur par défaut si la table est vide ou la référence absente
                echo json_encode([
                    "reference" => null, // Ou une valeur par défaut si nécessaire
                ]);
            }
        } catch (PDOException $e) {
            // Retourner une erreur en cas d'exception
            echo json_encode([
                "success" => false,
                "error" => "Erreur : " . $e->getMessage()
            ]);
        }
    }










    // enregistrer une résiliation dans la table resilies. Cette fonction prend l'ID du client (id_client), l'ID de l'utilisateur (id_user) et utilise la date actuelle (CURRENT_DATE) pour la résiliation.(Resilation)
    public function EnregistrerResiliation()
    {
        try {
            // Récupérer les données JSON envoyées dans le corps de la requête
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            // Vérifier que les données JSON contiennent l'ID du client et l'ID de l'utilisateur
            if (!isset($data['clientId']) || !is_numeric($data['clientId']) || $data['clientId'] <= 0) {
                echo json_encode(["error" => "Invalid or missing clientId"]);
                return;
            }

            if (!isset($data['userId']) || !is_numeric($data['userId']) || $data['userId'] <= 0) {
                echo json_encode(["error" => "Invalid or missing userId"]);
                return;
            }

            $clientId = $data['clientId'];
            $userId = $data['userId'];
            $dateResiliation = date('Y-m-d'); // Date actuelle (format YYYY-MM-DD)

            // Requête SQL pour insérer une nouvelle résiliation dans la table `resilies`
            $sql = "
            INSERT INTO resilies (id_user, id_client, date_resiliation)
            VALUES (:userId, :clientId, :dateResiliation)
        ";

            // Préparation et exécution de la requête
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':clientId', $clientId, PDO::PARAM_INT);
            $stmt->bindParam(':dateResiliation', $dateResiliation, PDO::PARAM_STR);

            // Exécution de la requête
            $stmt->execute();

            // Vérifier si l'insertion a réussi
            if ($stmt->rowCount() > 0) {
                // Retourner une réponse JSON confirmant la réussite de l'enregistrement
                echo json_encode(["success" => "Resiliation enregistrée avec succès"]);
            } else {
                // Retourner une erreur si l'insertion a échoué
                echo json_encode(["error" => "Failed to register resiliation"]);
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }

    public function AddResiliation($idClient, $idUser)
    {
        try {
            // Vérifier que les IDs sont valides
            if (!is_numeric($idClient) || $idClient <= 0 || !is_numeric($idUser) || $idUser <= 0) {
                echo json_encode(["error" => "Invalid client ID or user ID"]);
                return;
            }

            // Requête SQL pour insérer la résiliation
            $sql = "
              INSERT INTO resilies (id_client, id_user, date_resiliation)
              VALUES (:idClient, :idUser, CURRENT_DATE)
          ";

            // Préparation et exécution de la requête
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->bindParam(':idClient', $idClient, PDO::PARAM_INT);
            $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->execute();

            // Vérifier si l'insertion a réussi
            if ($stmt->rowCount() > 0) {
                echo json_encode(["success" => "Resiliation added successfully"]);
            } else {
                echo json_encode(["error" => "Failed to add resiliation"]);
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }






    // une fonction qui récupère et affiche le numéro de boîte postale, le type de boîte postale, l'état de la boîte postale et le nom du client.   (Resilation)
    public function GetAllBoxDetails()
    {
        try {
            // Requête SQL pour récupérer les informations
            $sql = "
                SELECT 
                    bp.numero AS numero_boite_postale,
                    bp.type AS type_boite_postale,
                    IFNULL(p.type, 'impayé') AS etat_boite_postale,
                    c.nom AS nom_client
                FROM 
                    boites_postales bp
                LEFT JOIN 
                    clients c ON c.id_boite_postale = bp.id
                LEFT JOIN 
                    paiements p ON c.id = p.id_client
            ";

            // Préparation et exécution de la requête
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();

            // Récupération des résultats
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($results)) {
                // Retourner les données au format JSON
                echo json_encode($results);
            } else {
                // Retourner une erreur si aucun résultat n'est trouvé
                echo json_encode(["error" => "No data found"]);
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }






    // fonction pour recuperes le numero-boite postale , l'etat boite postale et le nom du clients a partir du l'id du clients  (resilation)
    public function GetDetailsByClientData()
    {
        try {
            // Récupérer les données JSON envoyées dans le corps de la requête
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            // Vérifier si les données JSON contiennent un `clientId`
            if (!isset($data['clientId']) || !is_numeric($data['clientId']) || $data['clientId'] <= 0) {
                echo json_encode(["error" => "Invalid or missing clientId"]);
                return;
            }

            $clientId = $data['clientId'];

            // Requête SQL pour récupérer les informations
            $sql = "
            SELECT 
                bp.numero AS numero_boite_postale,
                c.nom AS nom_client,
                IFNULL(p.type, 'impayé') AS etat
            FROM 
                clients c
            LEFT JOIN 
                boites_postales bp ON c.id_boite_postale = bp.id
            LEFT JOIN 
                paiements p ON c.id = p.id_client
            WHERE 
                c.id = :clientId
        ";

            // Préparation et exécution de la requête
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->bindParam(':clientId', $clientId, PDO::PARAM_INT);
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Retourner les données au format JSON
                echo json_encode($result);
            } else {
                // Retourner une erreur si aucun client n'est trouvé
                echo json_encode(["error" => "Client not found"]);
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }



    public function GetClientEtatBoitePostale($jsonData)
    {
        try {
            // Décoder les données JSON
            $data = json_decode($jsonData, true);

            // Vérifier si l'ID du client est présent et valide
            if (!isset($data['clientId']) || !is_numeric($data['clientId']) || $data['clientId'] <= 0) {
                echo json_encode(["error" => "Invalid or missing client ID"]);
                return;
            }

            $clientId = $data['clientId'];

            // Requête pour récupérer les informations du client et de la boîte postale
            $sqlClientBoite = "
            SELECT 
                c.nom AS client_nom,
                c.id_boite_postale AS boite_postale_id,
                bp.type AS boite_postale_type
            FROM 
                clients c
            LEFT JOIN 
                boites_postales bp ON c.id_boite_postale = bp.id
            WHERE 
                c.id = :clientId
        ";

            $stmtClientBoite = $this->db->getPdo()->prepare($sqlClientBoite);
            $stmtClientBoite->bindParam(':clientId', $clientId, PDO::PARAM_INT);
            $stmtClientBoite->execute();
            $clientData = $stmtClientBoite->fetch(PDO::FETCH_ASSOC);

            if (!$clientData) {
                echo json_encode(["error" => "Client or boîte postale not found"]);
                return;
            }

            // Requête pour vérifier le statut de paiement
            $sqlPaiement = "
            SELECT 
                type 
            FROM 
                paiements
            WHERE 
                id_client = :clientId
            ORDER BY 
                id DESC 
            LIMIT 1
        ";

            $stmtPaiement = $this->db->getPdo()->prepare($sqlPaiement);
            $stmtPaiement->bindParam(':clientId', $clientId, PDO::PARAM_INT);
            $stmtPaiement->execute();
            $paiementData = $stmtPaiement->fetch(PDO::FETCH_ASSOC);

            // Déterminer le statut de paiement
            $etatPaiement = $paiementData ? $paiementData['type'] : 'impayé';

            // Résultat combiné
            $result = [
                "client_nom" => $clientData['client_nom'],
                "boite_postale_id" => $clientData['boite_postale_id'],
                "boite_postale_type" => $clientData['boite_postale_type'],
                "etat_paiement" => $etatPaiement
            ];

            echo json_encode($result);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }

    public function UpdateClientName($clientId, $jsonData, $userId)
    {
        try {
            // Vérifier si l'ID du client est valide
            if (!is_numeric($clientId) || $clientId <= 0) {
                echo json_encode(["error" => "Invalid client ID"]);
                return;
            }

            // Vérifier si l'ID de l'utilisateur est valide
            if (!is_numeric($userId) || $userId <= 0) {
                echo json_encode(["error" => "Invalid user ID"]);
                return;
            }

            // Décoder les données JSON
            $data = json_decode($jsonData, true);

            // Vérifier si le nouveau nom est présent et valide
            if (!isset($data['newName']) || empty(trim($data['newName']))) {
                echo json_encode(["error" => "Invalid or missing new name"]);
                return;
            }

            $newName = trim($data['newName']);

            // Vérifier si l'utilisateur existe dans la table `users`
            $sqlCheckUser = "
            SELECT id 
            FROM users
            WHERE id = :userId
        ";
            $stmtCheckUser = $this->db->getPdo()->prepare($sqlCheckUser);
            $stmtCheckUser->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmtCheckUser->execute();

            if ($stmtCheckUser->rowCount() === 0) {
                echo json_encode(["error" => "User not found"]);
                return;
            }

            // Requête SQL pour mettre à jour le nom du client
            $sqlUpdate = "
            UPDATE clients
            SET nom = :newName, id_user = :userId
            WHERE id = :clientId
        ";

            $stmtUpdate = $this->db->getPdo()->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':newName', $newName, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':clientId', $clientId, PDO::PARAM_INT);
            $stmtUpdate->execute();

            // Vérifier si une ligne a été mise à jour
            if ($stmtUpdate->rowCount() > 0) {
                echo json_encode(["success" => "Client name updated successfully"]);
            } else {
                echo json_encode(["error" => "No client found with the provided ID"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }



    //une fonction PHP qui permet d’afficher toutes les résiliations enregistrées dans la table resilies.
    public function GetAllResilies()
    {
        try {
            // Requête SQL pour récupérer toutes les résiliations
            $sql = "
            SELECT 
                r.id AS resiliation_id,
                r.id_user AS utilisateur_id,
                u.nom AS nom_utilisateur,
                r.id_client AS client_id,
                c.nom AS nom_client,
                bp.numero AS numero_boite_postale,
                r.date_resiliation AS date_resiliation
            FROM 
                resilies r
            LEFT JOIN 
                users u ON r.id_user = u.id
            LEFT JOIN 
                clients c ON r.id_client = c.id
            LEFT JOIN
                boites_postales bp ON c.id_boite_postale = bp.id
        ";

            // Préparation et exécution de la requête
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();

            // Récupération des résultats
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                // Retourner les données au format JSON
                echo json_encode($results);
            } else {
                // Retourner une erreur si aucune résiliation n'est trouvée
                echo json_encode(["error" => "No resiliation records found"]);
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }








    // une fonction PHP qui prend un paramètre JSON contenant l'id du client et retourne uniquement le nom du client.
    public function GetClientName()
    {
        try {
            // Récupérer les données JSON envoyées dans le corps de la requête
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            // Vérifier que l'ID du client est présent et valide
            if (!isset($data['clientId']) || !is_numeric($data['clientId']) || $data['clientId'] <= 0) {
                echo json_encode(["error" => "Invalid or missing clientId"]);
                return;
            }

            $clientId = $data['clientId'];

            // Requête SQL pour récupérer le nom du client
            $sql = "
            SELECT 
                nom AS nom_client
            FROM 
                clients
            WHERE 
                id = :clientId
        ";

            // Préparation et exécution de la requête
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->bindParam(':clientId', $clientId, PDO::PARAM_INT);
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Retourner uniquement le nom du client au format JSON
                echo json_encode(["nom_client" => $result['nom_client']]);
            } else {
                // Retourner une erreur si aucun client n'est trouvé
                echo json_encode(["error" => "Client not found"]);
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }






    // Récupérer les détails des boîtes postales avec le nom du client et l'état (payé/impayé)
    public function GetBoitePostaleDetails()
    {
        try {
            $sql = "
            SELECT 
                bp.numero AS boite_postale_numero,
                c.nom AS client_nom,
                CASE 
                    WHEN p.type = 'payé' THEN 'payé'
                    ELSE 'impayé'
                END AS etat
            FROM 
                boites_postales bp
            LEFT JOIN 
                clients c ON c.id_boite_postale = bp.id
            LEFT JOIN 
                paiements p ON p.id_client = c.id
            ";

            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["error" => "No data found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }




    public function GetBoitesPostalesDetails()
    {
        try {
            $sql = "
                SELECT 
                    c.nom AS nom_client,
                    c.date_abonnement,
                    b.numero AS numero_boite_postale,
                    b.type AS type_boite_postale,
                    p.redevence,
                    p.sous_couvete,
                    p.domicile,
                    CASE 
                        WHEN p.type = 'payé' THEN 'payé'
                        ELSE 'impayé'
                    END AS etat_boite_postale
                FROM 
                    boites_postales b
                LEFT JOIN 
                    clients c ON b.id = c.id_boite_postale
                LEFT JOIN 
                    paiements p ON c.id = p.id_client
                ORDER BY 
                    c.nom ASC
            ";

            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["error" => "No details found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }





    // Fonction pour récupérer le numéro, le type et l'état des boîtes postales
    public function GetEtatBoitesPostales()
    {
        try {
            $sql = "
            SELECT 
                bp.numero AS numero_boite_postale,
                bp.type AS type_boite_postale,
                CASE
                    WHEN p.type = 'payé' THEN 'payé'
                    ELSE 'impayé'
                END AS etat_boite_postale
            FROM 
                boites_postales bp
            LEFT JOIN 
                clients c ON bp.id = c.id_boite_postale
            LEFT JOIN 
                paiements p ON c.id = p.id_client
            GROUP BY 
                bp.id, p.type
        ";

            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["error" => "No data found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }




    // Récupérer une boîte postale par son ID
    public function GetBoitePostale($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                echo json_encode(["error" => "Invalid boîte postale ID"]);
                return;
            }

            $sql = "SELECT * FROM boites_postales WHERE id = :id";
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["error" => "Boîte postale not found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }

    // Créer une nouvelle boîte postale
    public function CreateBoitePostale($jsonData)
    {
        try {
            $data = json_decode($jsonData, true);

            if (is_array($data) && isset($data['type']) && isset($data['numero']) && isset($data['cle'])) {
                $sql = "INSERT INTO boites_postales (type, numero, cle) VALUES (:type, :numero, :cle)";
                $stmt = $this->db->getPdo()->prepare($sql);

                $stmt->bindParam(':type', $data['type']);
                $stmt->bindParam(':numero', $data['numero']);
                $stmt->bindParam(':cle', $data['cle']);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    echo json_encode(["success" => "Boîte postale added successfully"]);
                } else {
                    echo json_encode(["error" => "Boîte postale not added"]);
                }
            } else {
                echo json_encode(["error" => "Invalid input format"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }

    // Mettre à jour une boîte postale
    public function UpdateBoitePostale($id, $data)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                echo json_encode(["error" => "Invalid boîte postale ID"]);
                return;
            }

            if (is_string($data)) {
                $data = json_decode($data, true);
            }

            if (!is_array($data) || empty($data)) {
                echo json_encode(["error" => "Invalid input data"]);
                return;
            }

            $fields = [];
            $params = [':id' => $id];

            if (isset($data['type']) && !empty($data['type'])) {
                $fields[] = 'type = :type';
                $params[':type'] = $data['type'];
            }

            if (isset($data['numero']) && !empty($data['numero'])) {
                $fields[] = 'numero = :numero';
                $params[':numero'] = $data['numero'];
            }

            if (isset($data['cle']) && !empty($data['cle'])) {
                $fields[] = 'cle = :cle';
                $params[':cle'] = $data['cle'];
            }

            if (empty($fields)) {
                echo json_encode(["error" => "No valid fields to update"]);
                return;
            }

            $sql = "UPDATE boites_postales SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->db->getPdo()->prepare($sql);

            foreach ($params as $key => &$val) {
                $stmt->bindParam($key, $val);
            }

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo json_encode(["success" => "Boîte postale updated successfully"]);
            } else {
                echo json_encode(["error" => "Boîte postale not updated"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        }
    }

    // Supprimer une ou plusieurs boîtes postales
    public function DeleteBoitePostale($jsonData)
    {
        $data = json_decode($jsonData, true);

        if (is_array($data) && isset($data['ids']) && !empty($data['ids'])) {
            $ids = implode(',', array_map('intval', $data['ids']));
            $sql = "DELETE FROM boites_postales WHERE id IN ($ids)";
            $stmt = $this->db->getPdo()->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo json_encode(["success" => "Deleted " . count($data['ids']) . " boîte(s) postale(s)"]);
            } else {
                echo json_encode(["error" => "An error occurred"]);
            }
        } else {
            echo json_encode(["error" => "Invalid JSON data or no ID provided"]);
        }
    }
}
