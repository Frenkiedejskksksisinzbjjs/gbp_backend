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

    public function insertAndAssignBoitePostaleToClient($iduser, $data)
    {
        try {
            $this->db->getPdo()->beginTransaction();
            $Data = json_decode($data, true);

            // Données principales du client
            $nom = $Data['Nom'] ?? '';
            $email = $Data['Email'] ?? '';
            $telephone = $Data['Telephone'] ?? '';
            $adresse = $Data['Adresse'] ?? '';
            $boitePostale = $Data['BoitePostale'] ?? '';
            $role = $Data['Role'] ?? '';
            $typeClient = $Data['TypeClient'] ? 1 : 0; // true = 1, false = 0

            // Paiements
            $montantLd = $Data['montantLd'] ?? 0;
            $montantCll = $Data['montantCll'] ?? 0;
            $montantRd = $Data['montantRd'] ?? 0;
            $montantSC = $Data['montantSC'] ?? 0;
            $methodePaiement = $Data['Methode_de_paiement'] ?? '';
            $numeroCheque = $Data['Numero_cheque'] ?? '';
            $nomBanque = $Data['Nom_Banque'] ?? '';
            $numeroWallet = $Data['Numero_wallet'] ?? '';
            $Wallet = $Data['wallet'] ?? '';

            // Références
            $referenceRdv = $Data['Reference_Rdv'] ?? '';
            $referenceLd = $Data['reference_Ld'] ?? '';
            $referenceCll = $Data['reference_collection'] ?? '';
            $referenceSc = $Data['reference_Sc'] ?? '';

            // Adresses spécifiques
            $adresseLivraisonDomicile = $Data['Adresse_Livraison_Domicile'] ?? '';
            $adresseCollection = $Data['Adresse_collection'] ?? '';

            // Fichiers joints
            $identiter = $Data['Identiter'] ?? null;
            $abonnement = $Data['Abonnement'] ?? null;
            $patent_quitance = $Data['patent_quitance'] ?? null;

            // Sous-couvertures
            $sousCouvertures = $Data['sousCouvertures'] ?? [];

            // Vérification des champs obligatoires
            if (empty($nom) || empty($email) || empty($telephone)) {
                echo json_encode(["error" => "Nom, Email, et Téléphone sont obligatoires."]);
                return;
            }

            // Avant enregistrer les adresses, récupérer l'id de la boîte postale
            $stmtbp = $this->db->getPdo()->prepare("SELECT id FROM boites_postales WHERE numero = :numero");
            $stmtbp->bindParam(':numero', $boitePostale, PDO::PARAM_STR);
            $stmtbp->execute();

            if ($stmtbp->rowCount() > 0) {
                $idBoitePostal = $stmtbp->fetch(PDO::FETCH_ASSOC)['id'];

                // Insertion dans la table client
                $stmt = $this->db->getPdo()->prepare("INSERT INTO clients (nom, email, telephone, adresse, id_boite_postale,type_client,id_user)
                VALUES (:nom, :email, :telephone, :adresse, :boite_postale, :type_client,:id_user)");
                $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
                $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
                $stmt->bindParam(':boite_postale', $idBoitePostal, PDO::PARAM_STR);
                $stmt->bindParam(':type_client', $role, PDO::PARAM_STR);
                $stmt->bindParam(':id_user', $iduser, PDO::PARAM_INT);
                $stmt->execute();

                // Récupération de l'ID du client créé
                $idClient = $this->db->getPdo()->lastInsertId();

                // Insertion des paiements
                $stmt = $this->db->getPdo()->prepare("INSERT INTO paiements (id_client, montant_livraison_a_domicile, montant_collection, montant_redevence, montant_sous_couvete,
                methode_payment, numero_cheque, nom_banque, numero_wallet_redevence, reference, reference_livraison_domicile, reference_ajout_collection, reference_ajout_sous_couvette, type_wallet)
                VALUES (:id_client, :montantLd, :montantCll, :montantRd, :montantSC, :methodePaiement, :numeroCheque, :nomBanque, :numeroWallet, :referenceRdv, :referenceLd, :referenceCll, :referenceSc, :type_wallet)");
                $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);
                $stmt->bindParam(':montantLd', $montantLd, PDO::PARAM_STR);
                $stmt->bindParam(':montantCll', $montantCll, PDO::PARAM_STR);
                $stmt->bindParam(':montantRd', $montantRd, PDO::PARAM_STR);
                $stmt->bindParam(':montantSC', $montantSC, PDO::PARAM_STR);
                $stmt->bindParam(':methodePaiement', $methodePaiement, PDO::PARAM_STR);
                $stmt->bindParam(':numeroCheque', $numeroCheque, PDO::PARAM_STR);
                $stmt->bindParam(':nomBanque', $nomBanque, PDO::PARAM_STR);
                $stmt->bindParam(':numeroWallet', $numeroWallet, PDO::PARAM_STR);
                $stmt->bindParam(':referenceRdv', $referenceRdv, PDO::PARAM_STR);
                $stmt->bindParam(':referenceLd', $referenceLd, PDO::PARAM_STR);
                $stmt->bindParam(':referenceCll', $referenceCll, PDO::PARAM_STR);
                $stmt->bindParam(':referenceSc', $referenceSc, PDO::PARAM_STR);
                $stmt->bindParam(':type_wallet', $Wallet, PDO::PARAM_STR);
                $stmt->execute();

                // Enregistrer les documents dans la table "documents" après avoir déplacé les fichiers
                if ($identiter || $abonnement || $patent_quitance) {
                    $filePath = "AllFiles/";

                    // Fonction pour déplacer le fichier et obtenir son chemin
                    function moveFile($file, $path)
                    {
                        $filename = basename($file['name']);
                        $targetPath = $path . $filename;

                        // Déplacer le fichier
                        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                            return $targetPath;
                        }
                        return null;
                    }

                    $identiterPath = $identiter ? moveFile($identiter, $filePath) : null;
                    $abonnementPath = $abonnement ? moveFile($abonnement, $filePath) : null;
                    $patentQuitancePath = $patent_quitance ? moveFile($patent_quitance, $filePath) : null;

                    // Insertion dans la table documents avec les chemins des fichiers
                    $stmt = $this->db->getPdo()->prepare("INSERT INTO documents (patente_quitance, identite_gerant, abonnement_unique, created_at, id_client) 
                    VALUES (:patenteQuitance, :identiter, :abonnement, CURRENT_TIMESTAMP(), :idClient)");
                    $stmt->bindParam(':patenteQuitance', $patentQuitancePath, PDO::PARAM_STR);
                    $stmt->bindParam(':identiter', $identiterPath, PDO::PARAM_STR);
                    $stmt->bindParam(':abonnement', $abonnementPath, PDO::PARAM_STR);
                    $stmt->bindParam(':idClient', $idClient, PDO::PARAM_INT);
                    $stmt->execute();
                }

                // Commit
                $this->db->getPdo()->commit();
                echo json_encode(['status' => 'Abonnement avec succès']);
            }
        } catch (\Exception $e) {
            $this->db->getPdo()->rollBack();
            echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
        }
    }























    // achat clé
    public function addMontantAchatsCle($id, $data)
    {
        try {
            // Décoder les données JSON
            $decodedData = json_decode($data, true);

            // Vérifier les champs obligatoires
            if (!isset($id, $decodedData['Methode_de_paiement'], $decodedData['Montant'])) {
                echo json_encode(["error" => "Les champs 'id_client', 'methode_payment_cle' et 'montant_achats_cle' sont obligatoires."]);
                return;
            }

            $idClient = $id;
            $methodePaymentCle = $decodedData['Methode_de_paiement'];
            $montantAchatsCle = $decodedData['Montant'];
            $typeWalletCle = isset($decodedData['Wallet']) ? $decodedData['Wallet'] : null;

            $referenceAchatCle = isset($decodedData['ReferenceId']) ? $decodedData['ReferenceId'] : null;
            $numeroWalletAchatCle = isset($decodedData['Numero_wallet']) ? $decodedData['Numero_wallet'] : null;



            // Champs supplémentaires pour les paiements par chèque
            $numeroChequeAchatCle = isset($decodedData['Numero_cheque']) ? $decodedData['Numero_cheque'] : null;
            $nomBanqueAchatCle = isset($decodedData['Nom_Banque']) ? $decodedData['Nom_Banque'] : null;

            // Vérifier la validité de methode_payment_cle
            if (!in_array($methodePaymentCle, ['wallet', 'cash', 'cheque', 'carte_credits'])) {
                echo json_encode(["error" => "La méthode de paiement est invalide."]);
                return;
            }

            // Si méthode de paiement est 'wallet', vérifier type_wallet_cle
            if ($methodePaymentCle === 'wallet') {
                if (!isset($typeWalletCle) || !in_array($typeWalletCle, ['waafi', 'cac_pay', 'd_money', 'sabpay', 'dahabplaces'])) {
                    echo json_encode(["error" => "Si la méthode de paiement est 'wallet', 'type_wallet_cle' est obligatoire et doit être valide."]);
                    return;
                }

                // Vérification de 'numero_wallet_achat_cle'
                if (!isset($numeroWalletAchatCle) || empty($numeroWalletAchatCle)) {
                    echo json_encode(["error" => "Si la méthode de paiement est 'wallet', 'numero_wallet_achat_cle' est obligatoire."]);
                    return;
                }
            }


            // Si méthode de paiement est 'cheque', vérifier les champs liés
            if ($methodePaymentCle === 'cheque') {
                if (empty($numeroChequeAchatCle) || empty($nomBanqueAchatCle)) {
                    echo json_encode(["error" => "Pour le paiement par chèque, 'numero_cheque_achat_cle' et 'nom_banque_achat_cle' sont obligatoires."]);
                    return;
                }
            }

            // Vérifier si une entrée existe déjà pour ce client
            $query = "SELECT montant_achats_cle FROM paiements WHERE id_client = :id_client";
            $stmt = $this->db->getPdo()->prepare($query);
            $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);
            $stmt->execute();

            $paiement = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($paiement) {
                // Mise à jour de montant_achats_cle en ajoutant le montant reçu
                $nouveauMontant = $paiement['montant_achats_cle'] + $montantAchatsCle;

                $updateQuery = "UPDATE paiements 
                            SET montant_achats_cle = :nouveau_montant, 
                                methode_payment_cle = :methode_payment_cle, 
                                type_wallet_cle = :type_wallet_cle, 
                                numero_cheque_achat_cle = :numero_cheque_achat_cle, 
                                nom_banque_achat_cle = :nom_banque_achat_cle,  
                                reference_achat_cle = :reference_achat_cle,
                                numero_wallet_achat_cle = :numero_wallet_achat_cle
                            WHERE id_client = :id_client";
                $stmt = $this->db->getPdo()->prepare($updateQuery);
                $stmt->bindParam(':nouveau_montant', $nouveauMontant, PDO::PARAM_STR);
                $stmt->bindParam(':methode_payment_cle', $methodePaymentCle, PDO::PARAM_STR);
                $stmt->bindParam(':type_wallet_cle', $typeWalletCle, PDO::PARAM_STR);
                $stmt->bindParam(':numero_cheque_achat_cle', $numeroChequeAchatCle, PDO::PARAM_STR);
                $stmt->bindParam(':nom_banque_achat_cle', $nomBanqueAchatCle, PDO::PARAM_STR);
                $stmt->bindParam(':reference_achat_cle', $decodedData['reference_achat_cle'], PDO::PARAM_STR);
                $stmt->bindParam(':numero_wallet_achat_cle', $numeroWalletAchatCle, PDO::PARAM_STR);  // Ajout du paramètre pour numero_wallet_achat_cle
                $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                // Insérer un nouveau paiement si aucun enregistrement n'existe
                $insertQuery = "INSERT INTO paiements (id_client, montant_achats_cle, type, methode_payment_cle, type_wallet_cle, numero_cheque_achat_cle, nom_banque_achat_cle)
                            VALUES (:id_client, :montant_achats_cle, 'mis_a_jour', :methode_payment_cle, :type_wallet_cle, :numero_cheque_achat_cle, :nom_banque_achat_cle)";
                $stmt = $this->db->getPdo()->prepare($insertQuery);
                $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);
                $stmt->bindParam(':montant_achats_cle', $montantAchatsCle, PDO::PARAM_STR);
                $stmt->bindParam(':methode_payment_cle', $methodePaymentCle, PDO::PARAM_STR);
                $stmt->bindParam(':type_wallet_cle', $typeWalletCle, PDO::PARAM_STR);
                $stmt->bindParam(':numero_cheque_achat_cle', $numeroChequeAchatCle, PDO::PARAM_STR);
                $stmt->bindParam(':nom_banque_achat_cle', $nomBanqueAchatCle, PDO::PARAM_STR);
                $stmt->execute();
            }

            echo json_encode(["success" => "Paiement ajouté ou mis à jour avec succès."]);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Erreur : " . $e->getMessage()]);
        }
    }


    public function enregistrerPaiement($idClient, $data)
    {
        try {
            // Vérifier si $idClient est présent et valide
            if (empty($idClient) || !is_numeric($idClient)) {
                echo json_encode(["error" => "L'ID du client est requis et doit être valide."]);
                return;
            }

            // Décoder les données JSON
            $decodedData = json_decode($data, true);

            // Vérifier les champs obligatoires
            if (!isset($idClient, $decodedData['Montant'], $decodedData['Methode_de_paiement'], $decodedData['ReferenceId'])) {
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

            // Insérer directement un paiement
            $insertQuery = "INSERT INTO paiements (id_client, montant_redevence, methode_payment, type_wallet, numero_wallet_redevence, numero_cheque, nom_banque)
                        VALUES (:id_client, :montant_redevence, :methode_payment, :type_wallet, :numero_wallet_redevence, :numero_cheque, :nom_banque)";
            $stmt = $this->db->getPdo()->prepare($insertQuery);
            $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);
            $stmt->bindParam(':montant_redevence', $montantRedevence, PDO::PARAM_STR);
            $stmt->bindParam(':methode_payment', $methodePayment, PDO::PARAM_STR);
            $stmt->bindParam(':type_wallet', $typeWallet, PDO::PARAM_STR);
            $stmt->bindParam(':numero_wallet_redevence', $numeroWalletRedevence, PDO::PARAM_STR);
            $stmt->bindParam(':numero_cheque', $numeroCheque, PDO::PARAM_STR);
            $stmt->bindParam(':nom_banque', $nomBanque, PDO::PARAM_STR);
            $stmt->execute();

            echo json_encode(["success" => "Paiement ajouté avec succès."]);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Erreur : " . $e->getMessage()]);
        }
    }




















    //une fonction qui permet de récupérer le nom du client à partir de son ID, de modifier le nom du client et d'enregistrer cette modification dans la base de données. La fonction met à jour le champ update_by dans la table clients et enregistre un paiement dans la table paiements avec un montant fixe de 5000.00 dans le champ montant_changement_nom.

    public function updateClientNameAndAddPayment($id, $data)
    {
        try {
            // Décodage des données JSON
            $decodedData = json_decode($data, true);

            // Vérification que tous les champs nécessaires sont présents dans le JSON
            if (!isset($id, $decodedData['Nom'], $decodedData['Methode_de_paiement'], $decodedData['Montant'])) {
                echo json_encode(["error" => "Tous les champs sont obligatoires."]);
                return;
            }

            $idClient = $id;
            $nouveauNom = $decodedData['Nom'];
            $methodePaymentNom = $decodedData['Methode_de_paiement'];
            $montantChangementNom = $decodedData['Montant'];
            $typeWalletNom = isset($decodedData['Wallet']) ? $decodedData['Wallet'] : null;
            $numeroCheque = isset($decodedData['Numero_cheque']) ? $decodedData['Numero_cheque'] : null;
            $nomBanque = isset($decodedData['Nom_Banque']) ? $decodedData['Nom_Banque'] : null;

            $referenceChangerNom = isset($decodedData['ReferenceId']) ? $decodedData['ReferenceId'] : null;
            $numeroWalletChangementNom = isset($decodedData['Numero_wallet']) ? $decodedData['Numero_wallet'] : null;


            // Vérification si la référence est fournie (facultative ou obligatoire selon le cas)
            if (empty($referenceChangerNom)) {
                echo json_encode(["error" => "Le champ 'reference_changer_nom' est obligatoire."]);
                return;
            }


            // Vérification de la validité de methode_payment_nom
            if (!in_array($methodePaymentNom, ['wallet', 'cash', 'cheque', 'carte_credits'])) {
                echo json_encode(["error" => "La méthode de paiement est invalide."]);
                return;
            }

            // Si la méthode de paiement est 'wallet', vérifier type_wallet_nom
            if ($methodePaymentNom === 'wallet') {
                if (!isset($typeWalletNom) || !in_array($typeWalletNom, ['waafi', 'cac-pay', 'd-money', 'sab-pay'])) {
                    echo json_encode(["error" => "Si la méthode de paiement est 'wallet', 'type_wallet_nom' est obligatoire et doit être valide."]);
                    return;
                }

                // Ajouter la vérification pour 'numero_wallet_changement_nom'
                if (!isset($numeroWalletChangementNom) || empty($numeroWalletChangementNom)) {
                    echo json_encode(["error" => "Si la méthode de paiement est 'wallet', 'numero_wallet_changement_nom' est obligatoire."]);
                    return;
                }
            }


            // Si la méthode de paiement est 'cheque', vérifier numero_cheque et nom_banque
            if ($methodePaymentNom === 'cheque') {
                if (empty($numeroCheque) || empty($nomBanque)) {
                    echo json_encode(["error" => "Si la méthode de paiement est 'cheque', 'numero_cheque_changment_nom' et 'nom_banque_changment_nom' sont obligatoires."]);
                    return;
                }
            }

            // Vérifier si le client possède un paiement avec le type "mis_a_jour"
            $paymentCheckQuery = "SELECT id, montant_changement_nom 
                              FROM paiements 
                              WHERE id_client = :id_client AND type = 'mis_a_jour'";
            $stmt = $this->db->getPdo()->prepare($paymentCheckQuery);
            $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);
            $stmt->execute();

            $payment = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$payment) {
                echo json_encode(["error" => "Le client n'a pas de paiement avec le type 'mis_a_jour'. Modification non autorisée."]);
                return;
            }

            // Mise à jour du nom du client
            $updateQuery = "UPDATE clients 
                        SET nom = :nouveau_nom 
                        WHERE id = :id_client";
            $stmt = $this->db->getPdo()->prepare($updateQuery);
            $stmt->bindParam(':nouveau_nom', $nouveauNom, PDO::PARAM_STR);
            $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);
            $stmt->execute();

            // Mettre à jour le montant_changement_nom et autres champs dans la table paiements
            $nouveauMontant = $payment['montant_changement_nom'] + $montantChangementNom;
            $updatePaymentQuery = "UPDATE paiements 
        SET montant_changement_nom = :nouveau_montant, 
            methode_payment_nom = :methode_payment_nom, 
            type_wallet_nom = :type_wallet_nom, 
            numero_cheque_changment_nom = :numero_cheque, 
            nom_banque_changment_nom = :nom_banque, 
            reference_changer_nom = :reference_changer_nom,
            numero_wallet_changement_nom = :numero_wallet_changement_nom  -- Ajout du champ
        WHERE id = :id_paiement";

            $stmt = $this->db->getPdo()->prepare($updatePaymentQuery);

            $stmt->bindParam(':nouveau_montant', $nouveauMontant, PDO::PARAM_STR);
            $stmt->bindParam(':methode_payment_nom', $methodePaymentNom, PDO::PARAM_STR);
            $stmt->bindParam(':type_wallet_nom', $typeWalletNom, PDO::PARAM_STR);
            $stmt->bindParam(':numero_cheque', $numeroCheque, PDO::PARAM_STR);
            $stmt->bindParam(':nom_banque', $nomBanque, PDO::PARAM_STR);
            $stmt->bindParam(':reference_changer_nom', $referenceChangerNom, PDO::PARAM_STR);
            $stmt->bindParam(':numero_wallet_changement_nom', $numeroWalletChangementNom, PDO::PARAM_STR);  // Ajout du paramètre pour numero_wallet_changement_nom
            $stmt->bindParam(':id_paiement', $payment['id'], PDO::PARAM_INT);

            $stmt->execute();


            // Réponse de succès
            echo json_encode(["success" => "Le nom du client a été mis à jour et les informations de paiement enregistrées avec succès."]);
        } catch (PDOException $e) {
            // Gestion des erreurs
            echo json_encode(["error" => "Erreur de base de données : " . $e->getMessage()]);
        }
    }



















    public function addSousCouvette($id, $data)
    {
        try {
            // Décodage des données JSON
            $decodedData = json_decode($data, true);
            if (!$decodedData) {
                echo json_encode(["error" => "Données JSON invalides."]);
                return;
            }

            // Validation des champs requis
            $requiredFields = ['NBp', 'Methode_de_paiement', 'totalMontant', 'sousCouvertures'];
            foreach ($requiredFields as $field) {
                if (!isset($decodedData[$field])) {
                    echo json_encode(["error" => "Le champ '$field' est manquant."]);
                    return;
                }
            }

            // Assignation des variables
            $numeroBoitePostale = $decodedData['NBp'];
            $IdUser = $decodedData['id_user'];
            $methodePayment = $decodedData['Methode_de_paiement'];
            $totalMontant = $decodedData['totalMontant'];
            $sousCouvertures = $decodedData['sousCouvertures'];
            $referenceAjoutSousCouvette = $decodedData['ReferenceId'] ?? null;
            $typeWallet = $decodedData['Wallet'] ?? null;
            $numeroCheque = $decodedData['Numero_cheque'] ?? null;
            $nomBanque = $decodedData['Nom_Banque'] ?? null;
            $numeroWalletAjoutSousCouvette = $decodedData['Numero_wallet'] ?? null;

            // Étape 1 : Récupération de l'ID de la boîte postale
            $queryBoitePostale = "SELECT id FROM boites_postales WHERE numero = :numero_boite_postale";
            $stmt = $this->db->getPdo()->prepare($queryBoitePostale);
            $stmt->bindParam(':numero_boite_postale', $numeroBoitePostale, PDO::PARAM_STR);
            $stmt->execute();
            $boitePostale = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$boitePostale) {
                echo json_encode(["error" => "Aucune boîte postale trouvée avec ce numéro."]);
                return;
            }
            $idBoitePostale = $boitePostale['id'];

            // Étape 2 : Vérification du nombre de sous-couvertures existantes
            $queryCountSousCouvette = "SELECT COUNT(*) AS total FROM sous_couvete WHERE id_boite_postale = :id_boite_postale";
            $stmt = $this->db->getPdo()->prepare($queryCountSousCouvette);
            $stmt->bindParam(':id_boite_postale', $idBoitePostale, PDO::PARAM_INT);
            $stmt->execute();
            $countResult = $stmt->fetch(PDO::FETCH_ASSOC);

            $currentSousCouvetteCount = $countResult['total'];
            $remainingSpots = 5 - $currentSousCouvetteCount;

            if ($remainingSpots <= 0) {
                echo json_encode(["error" => "Limite de sous-couvertures atteinte. Aucun ajout supplémentaire n'est possible."]);
                return;
            }

            // Validation si l'utilisateur tente d'ajouter plus que le nombre disponible
            if (count($sousCouvertures) > $remainingSpots) {
                echo json_encode([
                    "error" => "Il n'y a que $remainingSpots emplacement(s) disponible(s). Veuillez réduire le nombre de sous-couvertures à ajouter."
                ]);
                return;
            }

            // Étape 3 : Insertion des sous-couvertures
            foreach ($sousCouvertures as $sousCouverture) {
                $queryInsertSousCouvette = "
                    INSERT INTO sous_couvete (nom_societe, nom_personne, telephone, adresse, id_boite_postale, id_user)
                    VALUES (:nom_societe, :nom_personne, :telephone, :adresse, :id_boite_postale, :id_user)
                ";
                $stmt = $this->db->getPdo()->prepare($queryInsertSousCouvette);
                $stmt->bindParam(':nom_societe', $sousCouverture['societe'], PDO::PARAM_STR);
                $stmt->bindParam(':nom_personne', $sousCouverture['personne'], PDO::PARAM_STR);
                $stmt->bindParam(':telephone', $sousCouverture['telephone'], PDO::PARAM_STR);
                $stmt->bindParam(':adresse', $sousCouverture['adresse'], PDO::PARAM_STR);
                $stmt->bindParam(':id_boite_postale', $idBoitePostale, PDO::PARAM_INT);
                $stmt->bindParam(':id_user', $IdUser, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Étape 4 : Mise à jour du montant dans paiements
            $queryCurrentMontant = "SELECT montant_sous_couvete FROM paiements WHERE id_client = :id_client";
            $stmt = $this->db->getPdo()->prepare($queryCurrentMontant);
            $stmt->bindParam(':id_client', $id, PDO::PARAM_INT);
            $stmt->execute();
            $currentMontant = $stmt->fetchColumn();

            $newMontant = $currentMontant ? $currentMontant + $totalMontant : $totalMontant;

            $updatePaiements = "
                UPDATE paiements 
                SET montant_sous_couvete = :montant_sous_couvete,
                    methode_payment_couvette = :methode_payment_couvette,
                    type_wallet_couvette = :type_wallet_couvette,
                    numero_cheque_sous_couvette = :numero_cheque_sous_couvette,
                    nom_banque_sous_couvette = :nom_banque_sous_couvette,
                    reference_ajout_sous_couvette = :reference_ajout_sous_couvette,
                    numero_wallet_ajout_sous_couvette = :numero_wallet_ajout_sous_couvette
                WHERE id_client = :id_client
            ";

            $stmt = $this->db->getPdo()->prepare($updatePaiements);
            $stmt->bindParam(':montant_sous_couvete', $newMontant, PDO::PARAM_STR);
            $stmt->bindParam(':methode_payment_couvette', $methodePayment, PDO::PARAM_STR);
            $stmt->bindParam(':type_wallet_couvette', $typeWallet, PDO::PARAM_STR);
            $stmt->bindParam(':numero_cheque_sous_couvette', $numeroCheque, PDO::PARAM_STR);
            $stmt->bindParam(':nom_banque_sous_couvette', $nomBanque, PDO::PARAM_STR);
            $stmt->bindParam(':reference_ajout_sous_couvette', $referenceAjoutSousCouvette, PDO::PARAM_STR);
            $stmt->bindParam(':numero_wallet_ajout_sous_couvette', $numeroWalletAjoutSousCouvette, PDO::PARAM_STR);
            $stmt->bindParam(':id_client', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(["success" => "Sous-couvette ajoutée et paiement mis à jour avec succès."]);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Erreur de base de données : " . $e->getMessage()]);
        }
    }











    public function insererLivraisonEtMettreAJourPaiement($id, $data)
    {
        try {
            $decodedData = json_decode($data, true);
    
            // Validation des champs obligatoires
            if (!isset($id, $decodedData['Adresse_Livraison_Domicile'], $decodedData['Methode_de_paiement'], $decodedData['Montant'], $decodedData['NBp'], $decodedData['ReferenceId'], $decodedData['id_user'])) {
                echo json_encode(["error" => "Tous les champs sont obligatoires."]);
                return;
            }
    
            $methodePaiement = $decodedData['Methode_de_paiement'];
            $validPaymentMethods = ['wallet', 'cash', 'cheque', 'carte_credits'];
            if (!in_array($methodePaiement, $validPaymentMethods)) {
                echo json_encode(["error" => "Méthode de paiement invalide."]);
                return;
            }
    
            // Validation des données spécifiques à la méthode de paiement
            if ($methodePaiement === 'wallet') {
                $typeWallet = $decodedData['Wallet'] ?? null;
                $validWalletTypes = ['wafi', 'cac-pay', 'd-money', 'sab-pay'];
    
                if (!in_array($typeWallet, $validWalletTypes) || empty($decodedData['Numero_wallet'])) {
                    echo json_encode(["error" => "Données de wallet invalides."]);
                    return;
                }
            } elseif ($methodePaiement === 'cheque') {
                if (!isset($decodedData['Numero_cheque'], $decodedData['Nom_Banque'])) {
                    echo json_encode(["error" => "Données de chèque invalides."]);
                    return;
                }
            }
    
            // Vérifier si le client possède un paiement de type 'mis_a_jour'
            $paymentCheckQuery = "SELECT id, montant_redevence FROM paiements WHERE id_client = :id_client AND type = 'mis_a_jour'";
            $stmt = $this->db->getPdo()->prepare($paymentCheckQuery);
            $stmt->bindParam(':id_client', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            $payment = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$payment) {
                echo json_encode(["error" => "Le client n'a pas de paiement avec le type 'mis_a_jour'. Modification non autorisée."]);
                return;
            }
    
            // Récupérer l'id de la boîte postale
            $NBp = $decodedData['NBp'];
            $stmtidBp = $this->db->getPdo()->prepare("SELECT id FROM boites_postales WHERE numero = :Nbp");
            $stmtidBp->bindParam(':Nbp', $NBp, PDO::PARAM_INT);
            $stmtidBp->execute();
    
            if ($stmtidBp->rowCount() > 0) {
                $this->db->getPdo()->beginTransaction(); // Démarrage de la transaction
    
                try {
                    $idBpostal = $stmtidBp->fetch(PDO::FETCH_ASSOC)['id'];
    
                    // Insertion dans 'livraison_a_domicile', avec id_user ajouté à l'insertion
                    $stmt = $this->db->getPdo()->prepare("INSERT INTO livraison_a_domicile (adresse, id_client, id_user) VALUES (:adresse, :id_client, :id_user)");
                    $stmt->bindParam(':adresse', $decodedData['Adresse_Livraison_Domicile'], PDO::PARAM_STR);
                    $stmt->bindParam(':id_client', $id, PDO::PARAM_INT);  
                    $stmt->bindParam(':id_user', $decodedData['id_user'], PDO::PARAM_INT);  // Ajout de l'id_user dans l'insertion
                    $stmt->execute();
    
                    // Préparation des données pour les détails du paiement dans 'details_paiements'
                    $montantLivraison = $decodedData['Montant'];
                    $numeroWallet = $decodedData['Numero_wallet'] ?? null;
                    $numeroCheque = $decodedData['Numero_cheque'] ?? null;
                    $nomBanque = $decodedData['Nom_Banque'] ?? null;
                    $typeWallet = $decodedData['Wallet'] ?? null;
                    $ReferenceId = $decodedData['ReferenceId'];
    
                    // Insertion dans 'details_paiements'
                    $stmt = $this->db->getPdo()->prepare("INSERT INTO details_paiements (paiement_id, categorie, montant, methode_payment, type_wallet, numero_wallet, numero_cheque, nom_banque, reference) 
                    VALUES (:paiement_id, 'livraison_domicile', :montant, :methode_payment, :type_wallet, :numero_wallet, :numero_cheque, :nom_banque, :reference)");
                    $stmt->bindParam(':paiement_id', $payment['id'], PDO::PARAM_INT);
                    $stmt->bindParam(':montant', $montantLivraison, PDO::PARAM_STR);
                    $stmt->bindParam(':methode_payment', $methodePaiement, PDO::PARAM_STR);
                    $stmt->bindParam(':type_wallet', $typeWallet, PDO::PARAM_STR);
                    $stmt->bindParam(':numero_wallet', $numeroWallet, PDO::PARAM_STR);
                    $stmt->bindParam(':numero_cheque', $numeroCheque, PDO::PARAM_STR);
                    $stmt->bindParam(':nom_banque', $nomBanque, PDO::PARAM_STR);
                    $stmt->bindParam(':reference', $ReferenceId, PDO::PARAM_STR);
                    $stmt->execute();
    
                    // Mise à jour du montant du paiement
                    $nouveauMontant = $payment['montant_redevence'] + $montantLivraison;
                    $stmt = $this->db->getPdo()->prepare("UPDATE paiements SET montant_redevence = :montant WHERE id = :paiement_id");
                    $stmt->bindParam(':montant', $nouveauMontant, PDO::PARAM_STR);
                    $stmt->bindParam(':paiement_id', $payment['id'], PDO::PARAM_INT);
                    $stmt->execute();
    
                    $this->db->getPdo()->commit(); // Validation de la transaction
    
                    echo json_encode(["success" => "Livraison et paiement mis à jour avec succès."]);
                } catch (PDOException $e) {
                    $this->db->getPdo()->rollBack(); // Annulation de la transaction en cas d'erreur
                    echo json_encode(["error" => "Erreur pendant la transaction : " . $e->getMessage()]);
                }
            } else {
                echo json_encode(["error" => "Identifiant de la boîte postale introuvable."]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Erreur : " . $e->getMessage()]);
        }
    }
    







    public function insererCollectionEtMettreAJourPaiement($idClient, $data)
    {
        try {
            // Décodage des données JSON
            $decodedData = json_decode($data, true);
    
            if (!$decodedData) {
                return json_encode(["error" => "Format JSON invalide."]);
            }
    
            // Validation des champs obligatoires
            $requiredFields = ['Adresse_collection', 'NBp', 'Methode_de_paiement', 'Montant', 'ReferenceId', 'id_user'];
            foreach ($requiredFields as $field) {
                if (empty($decodedData[$field])) {
                    return json_encode(["error" => "Le champ '$field' est obligatoire."]);
                }
            }
    
            // Initialisation des valeurs
            $methodePaiement = $decodedData['Methode_de_paiement'];
            $montant = $decodedData['Montant'];
            $reference = $decodedData['ReferenceId'];
            $idUser = $decodedData['id_user']; // Ajout de l'id_user
            $typeWallet = $decodedData['Wallet'] ?? null;
            $numeroWallet = $decodedData['Numero_wallet'] ?? null;
    
            // Validation de la méthode de paiement
            $validPaymentMethods = ['wallet', 'cash', 'cheque', 'carte_credits'];
            if (!in_array($methodePaiement, $validPaymentMethods)) {
                return json_encode(["error" => "Méthode de paiement invalide."]);
            }
    
            // Vérification spécifique pour les wallets
            if ($methodePaiement === 'wallet') {
                $validWalletTypes = ['wafi', 'cac-pay', 'd-money', 'sab-pay'];
                if (!in_array($typeWallet, $validWalletTypes) || empty($numeroWallet)) {
                    return json_encode(["error" => "Type de wallet invalide ou numéro wallet manquant."]);
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
                $this->db->getPdo()->rollBack();
                return json_encode(["error" => "Aucun paiement mis à jour trouvé pour ce client."]);
            }
    
            $paiementId = $paymentResult['id'];
    
            // Vérification de l'existence de la boîte postale et récupération de son ID
            $stmt = $this->db->getPdo()->prepare("SELECT id FROM boites_postales WHERE numero = :numero");
            $stmt->bindParam(':numero', $decodedData['NBp'], PDO::PARAM_STR);
            $stmt->execute();
            $boitePostaleResult = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$boitePostaleResult) {
                $this->db->getPdo()->rollBack();
                return json_encode(["error" => "Aucune boîte postale trouvée avec ce numéro."]);
            }
    
            $idBoitePostale = $boitePostaleResult['id'];
    
            // Vérification si l'ID client possède bien cette boîte postale
            $stmt = $this->db->getPdo()->prepare("SELECT id FROM clients WHERE id = :id_client AND id_boite_postale = :id_boite_postale");
            $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);
            $stmt->bindParam(':id_boite_postale', $idBoitePostale, PDO::PARAM_INT);
            $stmt->execute();
            $clientResult = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$clientResult) {
                $this->db->getPdo()->rollBack();
                return json_encode(["error" => "Ce client ne possède pas cette boîte postale."]);
            }
    
            // Insertion de la collection avec l'ajout de l'id_user
            $stmt = $this->db->getPdo()->prepare("INSERT INTO collection (adresse, id_client, id_user, created_at, updated_at) VALUES (:adresse, :id_client, :id_user, NOW(), NOW())");
            $stmt->bindParam(':adresse', $decodedData['Adresse_collection'], PDO::PARAM_STR);
            $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);
            $stmt->bindParam(':id_user', $idUser, PDO::PARAM_INT); // Ajout du paramètre id_user
            $stmt->execute();
    
            // Insertion des détails du paiement
            $stmt = $this->db->getPdo()->prepare("INSERT INTO details_paiements (paiement_id, categorie, montant, methode_payment, type_wallet, numero_wallet, reference) VALUES (:paiement_id, 'collection', :montant, :methode_payment, :type_wallet, :numero_wallet, :reference)");
            $stmt->bindParam(':paiement_id', $paiementId, PDO::PARAM_INT);
            $stmt->bindParam(':montant', $montant, PDO::PARAM_STR);
            $stmt->bindParam(':methode_payment', $methodePaiement, PDO::PARAM_STR);
            $stmt->bindParam(':type_wallet', $typeWallet, PDO::PARAM_STR);
            $stmt->bindParam(':numero_wallet', $numeroWallet, PDO::PARAM_STR);
            $stmt->bindParam(':reference', $reference, PDO::PARAM_STR);
            $stmt->execute();
    
            // Validation de la transaction
            $this->db->getPdo()->commit();
    
            return json_encode(["success" => "La collection a été ajoutée et le paiement mis à jour avec succès."]);
        } catch (PDOException $e) {
            $this->db->getPdo()->rollBack();
            return json_encode(["error" => "Erreur de base de données : " . $e->getMessage()]);
        }
    }
    
    
    

    

    




















    // une fonction qui récupére les informations des clients
    public function GetAllClients()
{
    try {
        // Récupérer l'année en cours
        $currentYear = date("Y");

        // Requête SQL pour récupérer les informations des clients avec vérification de l'année d'abonnement
        $sql = "
        SELECT 
            DISTINCT
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

            (
                CASE 
                    WHEN a.annee_abonnement = :currentYear THEN 'mis_a_jour'
                    ELSE 'non_mis_a_jour'
                END
            ) AS Etat,
            (
                SELECT COUNT(*) 
                FROM sous_couvete sc 
                WHERE sc.id_boite_postale = bp.id
            ) AS sous_couvert,
            
            -- Champs de la table documents
            d.type AS Document_Type,
            d.patente_quitance AS Patente_Quitance,
            d.identite_gerant AS Identite_Gerant,
            d.abonnement_unique AS Abonnement_Unique,
            d.created_at AS Document_Created_At,

            -- Ajout du statut de résiliation
            CASE 
                WHEN r.id_client IS NOT NULL THEN true
                ELSE false
            END AS status_resilation,

            -- Champs de la table details_paiements
            dp.categorie AS Paiement_Categorie,
            dp.montant AS Paiement_Montant,
            dp.methode_payment AS Paiement_Methode,
            dp.type_wallet AS Type_Wallet,
            dp.numero_wallet AS Numero_Wallet,
            dp.numero_cheque AS Numero_Cheque,
            dp.nom_banque AS Nom_Banque,
            dp.reference AS Paiement_Reference
        FROM 
            clients c
        LEFT JOIN 
            boites_postales bp ON c.id_boite_postale = bp.id
        LEFT JOIN 
            abonnement a ON bp.id = a.id_boite_postale
        LEFT JOIN 
            collection col ON col.id_boite_postale = bp.id
        LEFT JOIN 
            livraison_a_domicile ld ON ld.id_boite_postale = bp.id
        LEFT JOIN 
            documents d ON c.id = d.id_client
        LEFT JOIN 
            paiements p ON c.id = p.id_client
        LEFT JOIN 
            resilies r ON c.id = r.id_client
        LEFT JOIN 
            details_paiements dp ON p.id = dp.paiement_id
        ";

        // Préparation et exécution de la requête
        $stmt = $this->db->getPdo()->prepare($sql);
        $stmt->bindParam(':currentYear', $currentYear, PDO::PARAM_INT);
        $stmt->execute();

        // Récupération des résultats
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            // Retourner les données au format JSON
            echo json_encode($results);
        } else {
            // Retourner une erreur si aucun client n'est trouvé
            echo json_encode(["error" => "No clients found"]);
        }
    } catch (PDOException $e) {
        // Gestion des erreurs de base de données
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
}

    
    

    public function getLastReferenceAchatCle()
    {
        try {
            // Préparer la requête pour récupérer la dernière insertion
            $queryLastInsert = "SELECT reference_achat_cle
                FROM paiements
                WHERE reference_achat_cle IS NOT NULL
                ORDER BY 
                    CAST(SUBSTRING(reference_achat_cle, 
                    LOCATE('/', reference_achat_cle) + 1, 
                    LOCATE('/', reference_achat_cle, LOCATE('/', reference_achat_cle) + 1) 
                    - LOCATE('/', reference_achat_cle) - 1) AS UNSIGNED) DESC
                LIMIT 1";
            $stmt = $this->db->getPdo()->prepare($queryLastInsert);
            $stmt->execute();


            // Récupérer le résultat
            $dernierPaiement = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($dernierPaiement && !empty($dernierPaiement['reference_achat_cle'])) {
                // Retourner la référence si elle existe
                echo json_encode([
                    "reference_achat_cle" => $dernierPaiement['reference_achat_cle']
                ]);
            } else {
                // Retourner une valeur par défaut si la table est vide ou la référence absente
                echo json_encode([
                    "reference_achat_cle" => null, // Ou une valeur par défaut si nécessaire
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

    public function getLastReferenceAjoutSousCouvette()
    {
        try {
            // Préparer la requête pour récupérer la dernière insertion pour reference_ajout_sous_couvette
            $queryLastInsert = "SELECT reference_ajout_sous_couvette
                FROM paiements
                WHERE reference_ajout_sous_couvette IS NOT NULL
                ORDER BY 
                    CAST(SUBSTRING(reference_ajout_sous_couvette, 
                    LOCATE('/', reference_ajout_sous_couvette) + 1, 
                    LOCATE('/', reference_ajout_sous_couvette, LOCATE('/', reference_ajout_sous_couvette) + 1) 
                    - LOCATE('/', reference_ajout_sous_couvette) - 1) AS UNSIGNED) DESC
                LIMIT 1";
            $stmt = $this->db->getPdo()->prepare($queryLastInsert);
            $stmt->execute();

            // Récupérer le résultat
            $dernierPaiement = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($dernierPaiement && !empty($dernierPaiement['reference_ajout_sous_couvette'])) {
                // Retourner la référence si elle existe
                echo json_encode([
                    "reference_ajout_sous_couvette" => $dernierPaiement['reference_ajout_sous_couvette']
                ]);
            } else {
                // Retourner une valeur par défaut si la table est vide ou la référence absente
                echo json_encode([
                    "reference_ajout_sous_couvette" => null, // Ou une valeur par défaut si nécessaire
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
            // Préparer la requête pour récupérer la dernière insertion pour reference_changer_nom
            $queryLastInsert = "SELECT reference_changer_nom
                FROM paiements
                WHERE reference_changer_nom IS NOT NULL
                ORDER BY 
                    CAST(SUBSTRING(reference_changer_nom, 
                    LOCATE('/', reference_changer_nom) + 1, 
                    LOCATE('/', reference_changer_nom, LOCATE('/', reference_changer_nom) + 1) 
                    - LOCATE('/', reference_changer_nom) - 1) AS UNSIGNED) DESC
                LIMIT 1";
            $stmt = $this->db->getPdo()->prepare($queryLastInsert);
            $stmt->execute();

            // Récupérer le résultat
            $dernierPaiement = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($dernierPaiement && !empty($dernierPaiement['reference_changer_nom'])) {
                // Retourner la référence si elle existe
                echo json_encode([
                    "reference_changer_nom" => $dernierPaiement['reference_changer_nom']
                ]);
            } else {
                // Retourner une valeur par défaut si la table est vide ou la référence absente
                echo json_encode([
                    "reference_changer_nom" => null, // Ou une valeur par défaut si nécessaire
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
            // Préparer la requête pour récupérer la dernière insertion pour reference_livraison_domicile
            $queryLastInsert = "SELECT reference_livraison_domicile
                FROM paiements
                WHERE reference_livraison_domicile IS NOT NULL
                ORDER BY 
                    CAST(SUBSTRING(reference_livraison_domicile, 
                    LOCATE('/', reference_livraison_domicile) + 1, 
                    LOCATE('/', reference_livraison_domicile, LOCATE('/', reference_livraison_domicile) + 1) 
                    - LOCATE('/', reference_livraison_domicile) - 1) AS UNSIGNED) DESC
                LIMIT 1";
            $stmt = $this->db->getPdo()->prepare($queryLastInsert);
            $stmt->execute();

            // Récupérer le résultat
            $dernierPaiement = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($dernierPaiement && !empty($dernierPaiement['reference_livraison_domicile'])) {
                // Retourner la référence si elle existe
                echo json_encode([
                    "reference_livraison_domicile" => $dernierPaiement['reference_livraison_domicile']
                ]);
            } else {
                // Retourner une valeur par défaut si la table est vide ou la référence absente
                echo json_encode([
                    "reference_livraison_domicile" => null, // Ou une valeur par défaut si nécessaire
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
            // Préparer la requête pour récupérer la valeur avec la plus grande partie numérique
            $query = "
                SELECT reference_ajout_collection
                FROM paiements
                WHERE reference_ajout_collection IS NOT NULL
                ORDER BY 
                    CAST(SUBSTRING(reference_ajout_collection, 
                    LOCATE('/', reference_ajout_collection) + 1, 
                    LOCATE('/', reference_ajout_collection, LOCATE('/', reference_ajout_collection) + 1) 
                    - LOCATE('/', reference_ajout_collection) - 1) AS UNSIGNED) DESC
                LIMIT 1
            ";
            $stmt = $this->db->getPdo()->prepare($query);
            $stmt->execute();

            // Récupérer le résultat
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && !empty($result['reference_ajout_collection'])) {
                // Retourner la ligne avec la plus grande valeur numérique
                return json_encode(["reference_ajout_collection" => $result['reference_ajout_collection']]);
            } else {
                // Retourner null si aucune donnée n'est trouvée
                return json_encode(["reference_ajout_collection" => null]);
            }
        } catch (PDOException $e) {
            // Gérer les erreurs en cas d'exception
            throw new \Exception("Erreur lors de la récupération de la référence : " . $e->getMessage());
        }
    }


    public function getLastReference()
    {
        try {
            // Préparer la requête pour récupérer la dernière insertion pour reference
            $queryLastInsert = "SELECT reference
                FROM paiements
                WHERE reference IS NOT NULL
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
