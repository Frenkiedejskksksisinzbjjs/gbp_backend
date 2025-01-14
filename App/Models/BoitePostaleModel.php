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

//une fonction qui affecte une boîte postale à un client en mettant à jour les relations entre les deux. Elle vérifie d'abord si la boîte postale est déjà assignée à un client. Si ce n'est pas le cas, elle l'associe au client spécifié.
public function insertAndAssignBoitePostaleToClient($data)
{
    try {
        $this->db->getPdo()->beginTransaction();
        // Décodage des données JSON
        $decodedData = json_decode($data, true);

        // Vérification des champs obligatoires pour les clients
        if (!isset(
            $decodedData['nom'],
            $decodedData['adresse'],
            $decodedData['type_client'],
            $decodedData['email'],
            $decodedData['telephone'],
            $decodedData['numero_boite_postale'],
            $decodedData['id_user'],
            $decodedData['update_by'],
            $decodedData['date_abonnement'],
            $decodedData['montant_redevence'],
            $decodedData['methode_payment']
        )) {
            echo json_encode(["error" => "Tous les champs client sont obligatoires."]);
            return;
        }

        // Extraction des données
        $nom = $decodedData['nom'];
        $adresse = $decodedData['adresse'];
        $typeClient = $decodedData['type_client'];
        $email = $decodedData['email'];
        $telephone = $decodedData['telephone'];
        $numeroBoitePostale = $decodedData['numero_boite_postale'];
        $idUser = $decodedData['id_user'];
        $updateBy = $decodedData['update_by'];
        $nomSociete = isset($decodedData['nom_societe']) ? $decodedData['nom_societe'] : null;
        $dateAbonnement = isset($decodedData['date_abonnement']) ? $decodedData['date_abonnement'] : null;
        $montantRedevence = $decodedData['montant_redevence']; // Montant de la redevance
        $methodePayment = $decodedData['methode_payment']; // Méthode de paiement


        // Champs supplémentaires pour le sous-couvert
        $ouvrirSousCouvert = isset($decodedData['ouvrir_sous_couvert']) ? $decodedData['ouvrir_sous_couvert'] : false;
        $nomPersonneSousCouvert = isset($decodedData['nom_personne_sous_couvert']) ? $decodedData['nom_personne_sous_couvert'] : null;
        $montantSousCouverte = isset($decodedData['montant_sous_couverte']) ? $decodedData['montant_sous_couverte'] : null;
        $methodePaymentCouvette = isset($decodedData['methode_payment_couvette']) ? $decodedData['methode_payment_couvette'] : null;
        $typeWalletCouvette = isset($decodedData['type_wallet_couvette']) ? $decodedData['type_wallet_couvette'] : null;
        $numeroChequeSousCouvette = isset($decodedData['numero_cheque_sous_couvette']) ? $decodedData['numero_cheque_sous_couvette'] : null;
        $nomBanqueSousCouvette = isset($decodedData['nom_banque_sous_couvette']) ? $decodedData['nom_banque_sous_couvette'] : null;

         // Récupérer les données supplémentaires si le mode de paiement est un chèque
        $numero_cheque = isset($decodedData['numero_cheque']) ? $decodedData['numero_cheque'] : null;
        $nom_banque = isset($decodedData['nom_banque']) ? $decodedData['nom_banque'] : null;
        // Champs supplémentaires pour la livraison à domicile
$livraisonADomicile = isset($decodedData['livraison_a_domicile']) ? $decodedData['livraison_a_domicile'] : false;
$adresseLivraison = isset($decodedData['adresse_livraison']) ? $decodedData['adresse_livraison'] : null;
$montantLivraisonADomicile = isset($decodedData['montant_livraison_a_domicile']) ? $decodedData['montant_livraison_a_domicile'] : 0;
$methodePaiementADomicile = isset($decodedData['methode_paiement_a_domicile']) ? $decodedData['methode_paiement_a_domicile'] : null;
$typeWalletLivraisonADomicile = isset($decodedData['type_wallet_livraison_a_domicile']) ? $decodedData['type_wallet_livraison_a_domicile'] : null;
$numeroChequeLivraisonADomicile = isset($decodedData['numero_cheque_livraison_a_domicile']) ? $decodedData['numero_cheque_livraison_a_domicile'] : null;
$nomBanqueLivraisonADomicile = isset($decodedData['nom_banque_livraison_a_domicile']) ? $decodedData['nom_banque_livraison_a_domicile'] : null;


 // Champs supplémentaires pour la collection
 $collection = isset($decodedData['collection']) ? $decodedData['collection'] : false;
 $adresseCollection = isset($decodedData['adresseCollection']) ? $decodedData['adresseCollection'] : null;
 $montant_collection = isset($decodedData['montant_collection']) ? $decodedData['montant_collection'] : 0;
 $methode_paiement_collection = isset($decodedData['methode_paiement_collection']) ? $decodedData['methode_paiement_collection'] : null;
 $type_wallet_collection = isset($decodedData['type_wallet_collection']) ? $decodedData['type_wallet_collection'] : null;
 $numero_cheque_collection = isset($decodedData['numero_cheque_collection']) ? $decodedData['numero_cheque_collection'] : null;
 $nom_banque_collection = isset($decodedData['nom_banque_collection']) ? $decodedData['nom_banque_collection'] : null;

 //reference ajout sous-couvette
 $referenceAjoutSousCouvette = $decodedData['reference_ajout_sous_couvette'] ?? null;
 //reference ajout livraison a domicile 
 $referenceLivraison = $decodedData['reference_livraison_domicile'] ?? null;
 //reference ajout collection 
 $referenceAjoutCollection = $decodedData['reference_ajout_collection'];

 $numeroWalletAjoutSousCouvette = $decodedData['numero_wallet_ajout_sous_couvette'] ?? null;
 $numeroWalletLivraisonDomicile = $decodedData['numero_wallet_livraison_domicile'] ?? null;
 $numeroWalletCollection = $decodedData['numero_wallet_collection'];








// Gestion du type de wallet si la méthode de paiement est 'wallet'
if ($methodePaymentCouvette === 'wallet' && isset($decodedData['type_wallet_couvette'])) {
    $typeWalletCouvette = $decodedData['type_wallet_couvette'];
}

// Gestion des informations spécifiques au chèque si la méthode de paiement est 'cheque'
if ($methodePaymentCouvette === 'cheque') {
    // Récupération des données
    $numeroChequeSousCouvette = isset($decodedData['numero_cheque_sous_couvette']) ? $decodedData['numero_cheque_sous_couvette'] : null;
    $nomBanqueSousCouvette = isset($decodedData['nom_banque_sous_couvette']) ? $decodedData['nom_banque_sous_couvette'] : null;

    // Validation des champs obligatoires pour le chèque
    if (empty($numeroChequeSousCouvette) || empty($nomBanqueSousCouvette)) {
        throw new Exception("Les informations du chèque sont requises pour ce mode de paiement.");
    }
}

        // Récupérer le type de wallet si la méthode de paiement est 'wallet'
        $typeWallet = null;
        if ($methodePayment === 'wallet' && isset($decodedData['type_wallet'])) {
            $typeWallet = $decodedData['type_wallet'];
        }
       // Vérifiez si le mode de paiement est un chèque
if ($decodedData['methode_payment'] === 'cheque') {
    // Récupérer les informations spécifiques aux chèques
    $numero_cheque = isset($decodedData['numero_cheque']) ? $decodedData['numero_cheque'] : null;
    $nom_banque = isset($decodedData['nom_banque']) ? $decodedData['nom_banque'] : null;

    // Valider que les champs requis pour le chèque ne sont pas vides
    if (empty($numero_cheque) || empty($nom_banque)) {
        throw new Exception("Les informations du chèque sont requises pour ce mode de paiement.");
    }
} else {
    // Si ce n'est pas un chèque, les valeurs restent nulles
    $numero_cheque = null;
    $nom_banque = null;
}


        // Vérification des documents
        $patenteQuitance = isset($decodedData['patente_quitance']) ? $decodedData['patente_quitance'] : null;
        $identiteGerant = isset($decodedData['identite_gerant']) ? $decodedData['identite_gerant'] : null;
        $abonnementUnique = isset($decodedData['abonnement_unique']) ? $decodedData['abonnement_unique'] : null;

        if (empty($identiteGerant) || empty($abonnementUnique)) {
            echo json_encode(["error" => "Les documents 'identité du gérant' et 'abonnement unique' sont obligatoires."]);
            return;
        }

        if ($typeClient === 'société' && empty($patenteQuitance)) {
            echo json_encode(["error" => "Le document 'patente/quittance' est obligatoire pour un client de type 'société'."]);
            return;
        }

        // Vérification si le numéro de boîte postale existe
        $checkBoitePostaleQuery = "
            SELECT id 
            FROM boites_postales 
            WHERE numero = :numero_boite_postale
        ";
        $stmt = $this->db->getPdo()->prepare($checkBoitePostaleQuery);
        $stmt->bindParam(':numero_boite_postale', $numeroBoitePostale, PDO::PARAM_STR);
        $stmt->execute();

        $boitePostale = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$boitePostale) {
            echo json_encode(["error" => "Le numéro de boîte postale spécifié n'existe pas."]);
            return;
        }

        $idBoitePostale = $boitePostale['id'];

          // Vérification du nombre de sous-couverts associés à la boîte postale
          if ($ouvrirSousCouvert) {
            $checkSousCouverteQuery = "
                SELECT COUNT(*) AS total_sous_couvert 
                FROM sous_couvete 
                WHERE id_boite_postale = :id_boite_postale
            ";
            $stmt = $this->db->getPdo()->prepare($checkSousCouverteQuery);
            $stmt->bindParam(':id_boite_postale', $idBoitePostale, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result['total_sous_couvert'] >= 5) {
                echo json_encode(["error" => "La boîte postale a déjà 5 sous-couverts assignés."]);
                return;
            }
        }

        // Vérification si la boîte postale est déjà assignée
        $checkClientQuery = "
            SELECT id 
            FROM clients 
            WHERE id_boite_postale = :id_boite_postale
        ";
        $stmt = $this->db->getPdo()->prepare($checkClientQuery);
        $stmt->bindParam(':id_boite_postale', $idBoitePostale, PDO::PARAM_INT);
        $stmt->execute();

        $clientAssigned = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($clientAssigned) {
            echo json_encode(["error" => "Cette boîte postale est déjà assignée à un autre client."]);
            return;
        }

        if ($typeClient === 'société' && empty($nomSociete)) {
            echo json_encode(["error" => "Le nom de la société est obligatoire pour un client de type 'société'."]);
            return;
        }

        // Insertion du client
        $insertQuery = "
            INSERT INTO clients (nom, adresse, type_client, email, telephone, id_boite_postale, id_user, update_by, nom_societe, date_abonnement)
            VALUES (:nom, :adresse, :type_client, :email, :telephone, :id_boite_postale, :id_user, :update_by, :nom_societe, :date_abonnement)
        ";
        $stmt = $this->db->getPdo()->prepare($insertQuery);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $stmt->bindParam(':type_client', $typeClient, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
        $stmt->bindParam(':id_boite_postale', $idBoitePostale, PDO::PARAM_INT);
        $stmt->bindParam(':id_user', $idUser, PDO::PARAM_INT);
        $stmt->bindParam(':update_by', $updateBy, PDO::PARAM_INT);
        $stmt->bindParam(':date_abonnement', $dateAbonnement, PDO::PARAM_STR);

        if ($typeClient === 'société') {
            $stmt->bindParam(':nom_societe', $nomSociete, PDO::PARAM_STR);
        } else {
            $stmt->bindValue(':nom_societe', null, PDO::PARAM_NULL);
        }

        $stmt->execute();
        $idClient = $this->db->getPdo()->lastInsertId();

        // Insertion des documents
        $insertDocumentQuery = "
            INSERT INTO documents (type, patente_quitance, identite_gerant, abonnement_unique, id_client)
            VALUES (:type, :patente_quitance, :identite_gerant, :abonnement_unique, :id_client)
        ";
        $stmt = $this->db->getPdo()->prepare($insertDocumentQuery);
        $stmt->bindParam(':type', $typeClient, PDO::PARAM_STR);
        $stmt->bindParam(':patente_quitance', $patenteQuitance, PDO::PARAM_LOB);
        $stmt->bindParam(':identite_gerant', $identiteGerant, PDO::PARAM_LOB);
        $stmt->bindParam(':abonnement_unique', $abonnementUnique, PDO::PARAM_LOB);
        $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);
        $stmt->execute();

        // Insertion du paiement pour le nouveau client
        $insertPaymentQuery = "
            INSERT INTO paiements (type, montant_redevence, methode_payment,  numero_cheque, nom_banque,type_wallet, id_client)
            VALUES ('mis_a_jour', :montant_redevence, :methode_payment,:numero_cheque,:nom_banque, :type_wallet, :id_client)
        ";
        $stmt = $this->db->getPdo()->prepare($insertPaymentQuery);
        $stmt->bindParam(':montant_redevence', $montantRedevence, PDO::PARAM_STR);
        $stmt->bindParam(':methode_payment', $methodePayment, PDO::PARAM_STR);
        $stmt->bindParam(':numero_cheque', $numero_cheque, PDO::PARAM_STR);
        $stmt->bindParam(':nom_banque', $nom_banque     , PDO::PARAM_STR);
        $stmt->bindParam(':type_wallet', $typeWallet, PDO::PARAM_STR);
        $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);

        // Si la méthode de paiement est 'wallet', on insère le type_wallet
        if ($methodePayment !== 'wallet') {
            $stmt->bindValue(':type_wallet', null, PDO::PARAM_NULL);
        }

        $stmt->execute(); $idPayment = $this->db->getPdo()->lastInsertId();

        // Création de l'abonnement pour l'année en cours
        $anneeAbonnement = date('Y');
        $insertAbonnementQuery = "
            INSERT INTO abonnement (id_boite_postale, annee_abonnement, id_payments)
            VALUES (:id_boite_postale, :annee_abonnement, :id_payments)
        ";
        $stmt = $this->db->getPdo()->prepare($insertAbonnementQuery);
        $stmt->bindParam(':id_boite_postale', $idBoitePostale, PDO::PARAM_INT);
        $stmt->bindParam(':annee_abonnement', $anneeAbonnement, PDO::PARAM_INT);
        $stmt->bindParam(':id_payments', $idPayment, PDO::PARAM_INT);
        $stmt->execute();
          // Insertion d'un sous-couvert si demandé
          if ($ouvrirSousCouvert) {
            $insertSousCouverteQuery = "
                INSERT INTO sous_couvete (nom_societe, nom_personne, telephone, adresse, id_boite_postale, id_user)
                VALUES (:nom_societe, :nom_personne, :telephone, :adresse, :id_boite_postale, :id_user)
            ";
            $stmt = $this->db->getPdo()->prepare($insertSousCouverteQuery);
            $stmt->bindParam(':nom_societe', $nomSociete, PDO::PARAM_STR);
            $stmt->bindParam(':nom_personne', $nomPersonneSousCouvert, PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
            $stmt->bindParam(':id_boite_postale', $idBoitePostale, PDO::PARAM_INT);
            $stmt->bindParam(':id_user', $idUser, PDO::PARAM_INT);
            $stmt->execute();

            // Mise à jour du paiement pour inclure le montant du sous-couvert
            $updatePaiementQuery = "
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
            $stmt = $this->db->getPdo()->prepare($updatePaiementQuery);
            $stmt->bindParam(':montant_sous_couvete', $montantSousCouverte, PDO::PARAM_STR);
            $stmt->bindParam(':methode_payment_couvette', $methodePaymentCouvette, PDO::PARAM_STR);
            $stmt->bindParam(':type_wallet_couvette', $typeWalletCouvette, PDO::PARAM_STR);
            $stmt->bindParam(':numero_cheque_sous_couvette', $numeroChequeSousCouvette, PDO::PARAM_STR);
            $stmt->bindParam(':nom_banque_sous_couvette', $nomBanqueSousCouvette, PDO::PARAM_STR);
            $stmt->bindParam(':reference_ajout_sous_couvette', $referenceAjoutSousCouvette, PDO::PARAM_STR);
            $stmt->bindParam(':numero_wallet_ajout_sous_couvette', $numeroWalletAjoutSousCouvette, PDO::PARAM_STR);  // Ajout du paramètre pour numero_wallet_ajout_sous_couvette
            $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);
            
            $stmt->execute();

          }

// Vérifier si la livraison à domicile est demandée
if ($livraisonADomicile) {
    // Vérification si l'adresse de livraison est fournie
    if (empty($adresseLivraison)) {
        echo json_encode(["error" => "L'adresse de livraison à domicile est obligatoire."]);
        return;
    }

    // Insertion de la livraison à domicile dans la table 'livraison_a_domicile'
    $stmt = $this->db->getPdo()->prepare("
        INSERT INTO livraison_a_domicile (adresse, id_boite_postale, created_at, updated_at)
        VALUES (:adresse, :id_boite_postale, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP())
    ");
    $stmt->bindParam(':adresse', $adresseLivraison, PDO::PARAM_STR);
    $stmt->bindParam(':id_boite_postale', $idBoitePostale, PDO::PARAM_INT);
    $stmt->execute();


    // Mise à jour du paiement pour inclure le montant du sous-couvert
    $updatePaiementQuery = "
    UPDATE paiements
    SET montant_livraison_a_domicile  = :montant_livraison_a_domicile, 
        methode_paiement_a_domicile  = :methode_paiement_a_domicile, 
        type_wallet_livraison_a_domicile = :type_wallet_livraison_a_domicile,
        numero_cheque_livraison_a_domicile = :numero_cheque_livraison_a_domicile,
       nom_banque_livraison_a_domicile = :nom_banque_livraison_a_domicile,
       reference_livraison_domicile = :reference_livraison_domicile,
       numero_wallet_livraison_domicile = :numero_wallet_livraison_domicile

    WHERE id_client = :id_client
";
$stmt = $this->db->getPdo()->prepare($updatePaiementQuery);
$stmt->bindParam(':montant_livraison_a_domicile', $montantLivraisonADomicile, PDO::PARAM_STR);
$stmt->bindParam(':methode_paiement_a_domicile', $methodePaiementADomicile, PDO::PARAM_STR);
$stmt->bindParam(':type_wallet_livraison_a_domicile', $typeWalletLivraisonADomicile, PDO::PARAM_STR);
$stmt->bindParam(':numero_cheque_livraison_a_domicile', $numeroChequeLivraisonADomicile, PDO::PARAM_STR);
$stmt->bindParam(':nom_banque_livraison_a_domicile', $nomBanqueLivraisonADomicile, PDO::PARAM_STR);
$stmt->bindParam(':reference_livraison_domicile', $referenceLivraison, PDO::PARAM_STR);
$stmt->bindParam(':numero_wallet_livraison_domicile', $numeroWalletLivraisonDomicile, PDO::PARAM_STR);
$stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);

$stmt->execute();
}
// Vérifier si la livraison à domicile est demandée
if ($collection) {
    // Vérification si l'adresse de livraison est fournie
    if (empty($collection)) {
        echo json_encode(["error" => "L'adresse de livraison à domicile est obligatoire."]);
        return;
    }

    // Insertion de la livraison à domicile dans la table 'livraison_a_domicile'
    $stmt = $this->db->getPdo()->prepare("
        INSERT INTO collection (adresse, id_boite_postale, created_at, updated_at)
        VALUES (:adresseCollection, :id_boite_postale, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP())
    ");
    $stmt->bindParam(':adresseCollection', $adresseCollection , PDO::PARAM_STR);
    $stmt->bindParam(':id_boite_postale', $idBoitePostale, PDO::PARAM_INT);
    $stmt->execute();


    // Mise à jour du paiement pour inclure le montant du sous-couvert
    $updatePaiementQuery = "
    UPDATE paiements
    SET montant_collection  = :montant_collection, 
        methode_paiement_collection  = :methode_paiement_collection, 
        type_wallet_collection = :type_wallet_collection,
        numero_cheque_collection = :numero_cheque_collection,
       nom_banque_collection = :nom_banque_collection,
        reference_ajout_collection = :reference_ajout_collection,
       numero_wallet_collection = :numero_wallet_collection  -- Ajout de numero_wallet_collection

    WHERE id_client = :id_client
";
$stmt = $this->db->getPdo()->prepare($updatePaiementQuery);
$stmt->bindParam(':montant_collection', $montant_collection, PDO::PARAM_STR);
$stmt->bindParam(':methode_paiement_collection', $methode_paiement_collection, PDO::PARAM_STR);
$stmt->bindParam(':type_wallet_collection', $type_wallet_collection, PDO::PARAM_STR);
$stmt->bindParam(':numero_cheque_collection', $numero_cheque_collection, PDO::PARAM_STR);
$stmt->bindParam(':nom_banque_collection', $nom_banque_collection, PDO::PARAM_STR);
$stmt->bindParam(':reference_ajout_collection', $referenceAjoutCollection, PDO::PARAM_STR);
$stmt->bindParam(':numero_wallet_collection', $decodedData['numero_wallet_collection'], PDO::PARAM_STR);  // Ajout de numero_wallet_collection
$stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);

$stmt->execute();

}


$this->db->getPdo()->commit();



        echo json_encode(["success" => "Le client, ses documents, le paiement, l'abonnement et le sous-couvert ont été insérés avec succès."]);

    } catch (PDOException $e) {
        $this->db->getPdo()->rollBack();
        
        echo json_encode(["error" => $e->getMessage()]);
    }
}








    












// achat clé
public function addMontantAchatsCle($data)
{
    try {
        // Décoder les données JSON
        $decodedData = json_decode($data, true);

        // Vérifier les champs obligatoires
        if (!isset($decodedData['id_client'], $decodedData['methode_payment_cle'], $decodedData['montant_achats_cle'])) {
            echo json_encode(["error" => "Les champs 'id_client', 'methode_payment_cle' et 'montant_achats_cle' sont obligatoires."]);
            return;
        }

        $idClient = $decodedData['id_client'];
        $methodePaymentCle = $decodedData['methode_payment_cle'];
        $montantAchatsCle = $decodedData['montant_achats_cle'];
        $typeWalletCle = isset($decodedData['type_wallet_cle']) ? $decodedData['type_wallet_cle'] : null;

        $referenceAchatCle = isset($decodedData['reference_achat_cle']) ? $decodedData['reference_achat_cle'] : null;
        $numeroWalletAchatCle = isset($decodedData['numero_wallet_achat_cle']) ? $decodedData['numero_wallet_achat_cle'] : null;



        // Champs supplémentaires pour les paiements par chèque
        $numeroChequeAchatCle = isset($decodedData['numero_cheque_achat_cle']) ? $decodedData['numero_cheque_achat_cle'] : null;
        $nomBanqueAchatCle = isset($decodedData['nom_banque_achat_cle']) ? $decodedData['nom_banque_achat_cle'] : null;

        // Vérifier la validité de methode_payment_cle
        if (!in_array($methodePaymentCle, ['wallet', 'cash', 'cheque', 'carte_credits'])) {
            echo json_encode(["error" => "La méthode de paiement est invalide."]);
            return;
        }

        // Si méthode de paiement est 'wallet', vérifier type_wallet_cle
        if ($methodePaymentCle === 'wallet') {
            if (!isset($typeWalletCle) || !in_array($typeWalletCle, ['wafi', 'cac-pay', 'd-money', 'sab-pay'])) {
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
        }
        
        else {
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






    








//une fonction qui permet de récupérer le nom du client à partir de son ID, de modifier le nom du client et d'enregistrer cette modification dans la base de données. La fonction met à jour le champ update_by dans la table clients et enregistre un paiement dans la table paiements avec un montant fixe de 5000.00 dans le champ montant_changement_nom.
public function updateClientNameAndAddPayment($data)
{
    try {
        // Décodage des données JSON
        $decodedData = json_decode($data, true);

        // Vérification que tous les champs nécessaires sont présents dans le JSON
        if (!isset($decodedData['id_client'], $decodedData['nouveau_nom'], $decodedData['methode_payment_nom'], $decodedData['montant_changement_nom'])) {
            echo json_encode(["error" => "Tous les champs sont obligatoires."]);
            return;
        }

        $idClient = $decodedData['id_client'];
        $nouveauNom = $decodedData['nouveau_nom'];
        $methodePaymentNom = $decodedData['methode_payment_nom'];
        $montantChangementNom = $decodedData['montant_changement_nom'];
        $typeWalletNom = isset($decodedData['type_wallet_nom']) ? $decodedData['type_wallet_nom'] : null;
        $numeroCheque = isset($decodedData['numero_cheque_changment_nom']) ? $decodedData['numero_cheque_changment_nom'] : null;
        $nomBanque = isset($decodedData['nom_banque_changment_nom']) ? $decodedData['nom_banque_changment_nom'] : null;

        $referenceChangerNom = isset($decodedData['reference_changer_nom']) ? $decodedData['reference_changer_nom'] : null;
        $numeroWalletChangementNom = isset($decodedData['numero_wallet_changement_nom']) ? $decodedData['numero_wallet_changement_nom'] : null;


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
            if (!isset($typeWalletNom) || !in_array($typeWalletNom, ['wafi', 'cac-pay', 'd-money', 'sab-pay'])) {
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









    









    // ajouter une sous couvette avec une condition que 5 couve sont autorisé pour une boite boistale 
    public function addSousCouvette($data)
{
    try {
        // Décodage des données JSON
        $decodedData = json_decode($data, true);
        if (!$decodedData) {
            echo json_encode(["error" => "Données JSON invalides."]);
            return;
        }

        // Validation des champs requis
        $requiredFields = ['nom_societe', 'nom_personne', 'telephone', 'adresse', 'numero_boite_postale', 'id_user', 'methode_payment_couvette', 'montant_sous_couvette'];
        foreach ($requiredFields as $field) {
            if (!isset($decodedData[$field])) {
                echo json_encode(["error" => "Le champ '$field' est manquant."]);
                return;
            }
        }

        // Assignation des variables
        $nomSociete = $decodedData['nom_societe'];
        $nomPersonne = $decodedData['nom_personne'];
        $telephone = $decodedData['telephone'];
        $adresse = $decodedData['adresse'];
        $numeroBoitePostale = $decodedData['numero_boite_postale'];
        $idUser = $decodedData['id_user'];
        $methodePayment = $decodedData['methode_payment_couvette'];
        $montantSousCouvette = $decodedData['montant_sous_couvette'];
        $typeWallet = $decodedData['type_wallet_couvette'] ?? null;
        $numeroCheque = $decodedData['numero_cheque_sous_couvette'] ?? null;
        $nomBanque = $decodedData['nom_banque_sous_couvette'] ?? null;

        $referenceAjoutSousCouvette = $decodedData['reference_ajout_sous_couvette'] ?? null;
        $numeroWalletAjoutSousCouvette = $decodedData['numero_wallet_ajout_sous_couvette'] ?? null;


        // Validation des méthodes de paiement
        $validPaymentMethods = ['wallet', 'cash', 'cheque', 'carte_credits'];
        if (!in_array($methodePayment, $validPaymentMethods)) {
            echo json_encode(["error" => "Méthode de paiement invalide."]);
            return;
        }

       // Validation pour le type de wallet
if ($methodePayment === 'wallet') {
    $validWalletTypes = ['wafi', 'cac-pay', 'd-money', 'sab-pay'];
    if (!in_array($typeWallet, $validWalletTypes)) {
        echo json_encode(["error" => "Type de wallet invalide."]);
        return;
    }

    // Vérification que 'numero_wallet_ajout_sous_couvette' est fourni
    if (empty($numeroWalletAjoutSousCouvette)) {
        echo json_encode(["error" => "Le champ 'numero_wallet_ajout_sous_couvette' est obligatoire pour la méthode de paiement 'wallet'."]);
        return;
    }
}


        // Validation des données pour le chèque
        if ($methodePayment === 'cheque' && (empty($numeroCheque) || empty($nomBanque))) {
            echo json_encode(["error" => "Numéro de chèque et nom de la banque obligatoires pour un paiement par chèque."]);
            return;
        }

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

        // Étape 2 : Vérification du nombre de sous-couvette
        $queryCountSousCouvette = "SELECT COUNT(*) AS total FROM sous_couvete WHERE id_boite_postale = :id_boite_postale";
        $stmt = $this->db->getPdo()->prepare($queryCountSousCouvette);
        $stmt->bindParam(':id_boite_postale', $idBoitePostale, PDO::PARAM_INT);
        $stmt->execute();
        $countResult = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($countResult['total'] >= 5) {
            echo json_encode(["error" => "Limite de sous-couvette atteinte pour cette boîte postale."]);
            return;
        }

        // Étape 3 : Récupération de l'ID client
        $queryClient = "SELECT id FROM clients WHERE id_boite_postale = :id_boite_postale";
        $stmt = $this->db->getPdo()->prepare($queryClient);
        $stmt->bindParam(':id_boite_postale', $idBoitePostale, PDO::PARAM_INT);
        $stmt->execute();
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$client) {
            echo json_encode(["error" => "Aucun client trouvé pour cette boîte postale."]);
            return;
        }
        $idClient = $client['id'];

        // Étape 4 : Insertion dans sous_couvete
        $insertSousCouvette = "
            INSERT INTO sous_couvete (nom_societe, nom_personne, telephone, adresse, id_boite_postale, id_user)
            VALUES (:nom_societe, :nom_personne, :telephone, :adresse, :id_boite_postale, :id_user)";
        $stmt = $this->db->getPdo()->prepare($insertSousCouvette);
        $stmt->bindParam(':nom_societe', $nomSociete, PDO::PARAM_STR);
        $stmt->bindParam(':nom_personne', $nomPersonne, PDO::PARAM_STR);
        $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
        $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $stmt->bindParam(':id_boite_postale', $idBoitePostale, PDO::PARAM_INT);
        $stmt->bindParam(':id_user', $idUser, PDO::PARAM_INT);
        $stmt->execute();

        // Étape 5 : Récupération du montant actuel et mise à jour
        $queryCurrentMontant = "SELECT montant_sous_couvete FROM paiements WHERE id_client = :id_client";
        $stmt = $this->db->getPdo()->prepare($queryCurrentMontant);
        $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);
        $stmt->execute();
        $currentMontant = $stmt->fetchColumn();

        $newMontant = $currentMontant ? $currentMontant + $montantSousCouvette : $montantSousCouvette;

        $updatePaiements = "
        UPDATE paiements 
        SET montant_sous_couvete = :montant_sous_couvete,
            methode_payment_couvette = :methode_payment_couvette,
            type_wallet_couvette = :type_wallet_couvette,
            numero_cheque_sous_couvette = :numero_cheque_sous_couvette,
            nom_banque_sous_couvette = :nom_banque_sous_couvette,
            reference_ajout_sous_couvette = :reference_ajout_sous_couvette,
            numero_wallet_ajout_sous_couvette = :numero_wallet_ajout_sous_couvette
        WHERE id_client = :id_client";
    
    $stmt = $this->db->getPdo()->prepare($updatePaiements);
    $stmt->bindParam(':montant_sous_couvete', $newMontant, PDO::PARAM_STR);
    $stmt->bindParam(':methode_payment_couvette', $methodePayment, PDO::PARAM_STR);
    $stmt->bindParam(':type_wallet_couvette', $typeWallet, PDO::PARAM_STR);
    $stmt->bindParam(':numero_cheque_sous_couvette', $numeroCheque, PDO::PARAM_STR);
    $stmt->bindParam(':nom_banque_sous_couvette', $nomBanque, PDO::PARAM_STR);
    $stmt->bindParam(':reference_ajout_sous_couvette', $referenceAjoutSousCouvette, PDO::PARAM_STR);
    $stmt->bindParam(':numero_wallet_ajout_sous_couvette', $numeroWalletAjoutSousCouvette, PDO::PARAM_STR);  // Ajout du paramètre pour numero_wallet_ajout_sous_couvette
    $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);
    $stmt->execute();
    

        echo json_encode(["success" => "Sous-couvette ajoutée et paiement mis à jour avec succès."]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Erreur de base de données : " . $e->getMessage()]);
    }
}

    
    


    


    public function insererLivraisonEtMettreAJourPaiement($data)
    {
        try {
            // Décodage des données JSON
            $decodedData = json_decode($data, true);
    
            // Validation des champs obligatoires
            if (!isset($decodedData['adresse'], $decodedData['numero_boite_postale'], $decodedData['methode_paiement_a_domicile'], $decodedData['montant_livraison_a_domicile'])) {
                echo json_encode(["error" => "Tous les champs sont obligatoires."]);
                return;
            }
            $referenceLivraison = $decodedData['reference_livraison_domicile'] ?? null;
            $numeroWalletLivraisonDomicile = $decodedData['numero_wallet_livraison_domicile'] ?? null;


            $methodePaiement = $decodedData['methode_paiement_a_domicile'];
            $typeWallet = $decodedData['type_wallet_livraison_a_domicile'] ?? null;
    
            // Validation de la méthode de paiement
            $validPaymentMethods = ['wallet', 'cash', 'cheque', 'carte_credits'];
            if (!in_array($methodePaiement, $validPaymentMethods)) {
                echo json_encode(["error" => "Méthode de paiement invalide."]);
                return;
            }
    
            // Validation du type de wallet si 'wallet' est sélectionné
if ($methodePaiement === 'wallet') {
    $validWalletTypes = ['wafi', 'cac-pay', 'd-money', 'sab-pay'];
    if (!in_array($typeWallet, $validWalletTypes)) {
        echo json_encode(["error" => "Type de wallet invalide."]);
        return;
    }

    // Vérification que 'numero_wallet_livraison_domicile' est fourni
    if (empty($numeroWalletLivraisonDomicile)) {
        echo json_encode(["error" => "Le champ 'numero_wallet_livraison_domicile' est obligatoire pour la méthode de paiement 'wallet'."]);
        return;
    }
}

    
            // Recherche de l'ID de la boîte postale via son numéro
            $numeroBoitePostale = $decodedData['numero_boite_postale'];
            $getBoitePostaleIdQuery = "
                SELECT id
                FROM boites_postales
                WHERE numero = :numero_boite_postale
            ";
            $stmt = $this->db->getPdo()->prepare($getBoitePostaleIdQuery);
            $stmt->bindParam(':numero_boite_postale', $numeroBoitePostale, PDO::PARAM_STR);
            $stmt->execute();
    
            $boitePostaleResult = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$boitePostaleResult) {
                echo json_encode(["error" => "Aucune boîte postale trouvée pour ce numéro."]);
                return;
            }
    
            $idBoitePostale = $boitePostaleResult['id'];
    
            // Vérification du nombre de livraisons associées à la boîte postale
            $checkCountQuery = "
                SELECT COUNT(*) AS total 
                FROM livraison_a_domicile 
                WHERE id_boite_postale = :id_boite_postale
            ";
            $stmt = $this->db->getPdo()->prepare($checkCountQuery);
            $stmt->bindParam(':id_boite_postale', $idBoitePostale, PDO::PARAM_INT);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($result['total'] >= 5) {
                echo json_encode(["error" => "Vous avez dépassé le nombre de livraisons autorisé pour une boîte postale."]);
                return;
            }
    
            // Récupérer l'ID du client associé à la boîte postale
            $getClientQuery = "
                SELECT id
                FROM clients 
                WHERE id_boite_postale = :id_boite_postale
            ";
            $stmt = $this->db->getPdo()->prepare($getClientQuery);
            $stmt->bindParam(':id_boite_postale', $idBoitePostale, PDO::PARAM_INT);
            $stmt->execute();
    
            $clientResult = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$clientResult) {
                echo json_encode(["error" => "Aucun client trouvé pour cette boîte postale."]);
                return;
            }
    
            $idClient = $clientResult['id'];
    
            // Si la méthode de paiement est par chèque, on ajoute les informations du chèque
            if ($methodePaiement === 'cheque') {
                if (!isset($decodedData['numero_cheque_livraison_a_domicile'], $decodedData['nom_banque_livraison_a_domicile'])) {
                    echo json_encode(["error" => "Si le paiement est par chèque, le numéro de chèque et le nom de la banque sont requis."]);
                    return;
                }
            }
    
            // Insertion de la livraison à domicile
            $insertQuery = "
                INSERT INTO livraison_a_domicile (adresse, id_boite_postale)
                VALUES (:adresse, :id_boite_postale)
            ";
            $stmt = $this->db->getPdo()->prepare($insertQuery);
    
            $stmt->bindParam(':adresse', $decodedData['adresse'], PDO::PARAM_STR);
            $stmt->bindParam(':id_boite_postale', $idBoitePostale, PDO::PARAM_INT);
    
            $stmt->execute();
            $livraisonId = $this->db->getPdo()->lastInsertId(); // Récupérer l'ID de la livraison insérée
    
            // Récupérer le montant de la livraison depuis le JSON
            $montantLivraison = $decodedData['montant_livraison_a_domicile'];
    
            $updatePaymentQuery = "
            UPDATE paiements 
            SET montant_livraison_a_domicile = montant_livraison_a_domicile + :montant_livraison_a_domicile, 
                methode_paiement_a_domicile = :methode_paiement_a_domicile, 
                type_wallet_livraison_a_domicile = :type_wallet_livraison_a_domicile,
                numero_cheque_livraison_a_domicile = :numero_cheque_livraison_a_domicile,
                nom_banque_livraison_a_domicile = :nom_banque_livraison_a_domicile,
                reference_livraison_domicile = :reference_livraison_domicile,
                numero_wallet_livraison_domicile = :numero_wallet_livraison_domicile
            WHERE id_client = :id_client
        ";
        
        $stmt = $this->db->getPdo()->prepare($updatePaymentQuery);
        
        // Lier les paramètres
        $stmt->bindParam(':montant_livraison_a_domicile', $montantLivraison, PDO::PARAM_STR);
        $stmt->bindParam(':methode_paiement_a_domicile', $methodePaiement, PDO::PARAM_STR);
        $stmt->bindParam(':type_wallet_livraison_a_domicile', $typeWallet, PDO::PARAM_STR);
        $stmt->bindParam(':reference_livraison_domicile', $referenceLivraison, PDO::PARAM_STR);
        $stmt->bindParam(':numero_wallet_livraison_domicile', $numeroWalletLivraisonDomicile, PDO::PARAM_STR);
        $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);
        
        // Ajouter les informations de chèque si la méthode de paiement est par chèque
        if ($methodePaiement === 'cheque') {
            $stmt->bindParam(':numero_cheque_livraison_a_domicile', $decodedData['numero_cheque_livraison_a_domicile'], PDO::PARAM_STR);
            $stmt->bindParam(':nom_banque_livraison_a_domicile', $decodedData['nom_banque_livraison_a_domicile'], PDO::PARAM_STR);
        } else {
            // Si ce n'est pas par chèque, on met à null ces valeurs
            $stmt->bindValue(':numero_cheque_livraison_a_domicile', null, PDO::PARAM_NULL);
            $stmt->bindValue(':nom_banque_livraison_a_domicile', null, PDO::PARAM_NULL);
        }
        
        // Exécuter la requête une seule fois après avoir lié tous les paramètres
        $stmt->execute();
        
           
    
            // Réponse de succès
            echo json_encode(["success" => "La livraison a été ajoutée et le paiement mis à jour avec succès."]);
        } catch (PDOException $e) {
            // Gestion des erreurs
            echo json_encode(["error" => "Erreur de base de données : " . $e->getMessage()]);
        }
    }
    


    public function insererCollectionEtMettreAJourPaiement($data)
    {
        try {
            // Décodage des données JSON
            $decodedData = json_decode($data, true);
    
            // Validation des champs obligatoires
            if (!isset($decodedData['adresse'], $decodedData['numero_boite_postale'], $decodedData['methode_paiement_collection'], $decodedData['montant_collection'])) {
                echo json_encode(["error" => "Tous les champs obligatoires doivent être fournis."]);
                return;
            }
            if (!isset($decodedData['reference_ajout_collection'])) {
                echo json_encode(["error" => "La référence d'ajout de collection est requise."]);
                return;
            }
            
            $referenceAjoutCollection = $decodedData['reference_ajout_collection'];
            $numeroWalletCollection = $decodedData['numero_wallet_collection'];

            
    
            $methodePaiement = $decodedData['methode_paiement_collection'];
            $typeWallet = $decodedData['type_wallet_collection'] ?? null;
    
            // Validation de la méthode de paiement
            $validPaymentMethods = ['wallet', 'cash', 'cheque', 'carte_credits'];
            if (!in_array($methodePaiement, $validPaymentMethods)) {
                echo json_encode(["error" => "Méthode de paiement invalide."]);
                return;
            }
    
            // Validation du type de wallet si 'wallet' est sélectionné
if ($methodePaiement === 'wallet') {
    $validWalletTypes = ['wafi', 'cac-pay', 'd-money', 'sab-pay'];
    if (!in_array($typeWallet, $validWalletTypes)) {
        echo json_encode(["error" => "Type de wallet invalide."]);
        return;
    }

    // Vérification que 'numero_wallet_collection' est fourni pour 'wallet'
    if (empty($decodedData['numero_wallet_collection'])) {
        echo json_encode(["error" => "Le champ 'numero_wallet_collection' est obligatoire pour la méthode de paiement 'wallet'."]);
        return;
    }
}

    
            // Validation des informations de chèque si 'cheque' est sélectionné
            if ($methodePaiement === 'cheque') {
                if (!isset($decodedData['numero_cheque_collection'], $decodedData['nom_banque_collection'])) {
                    echo json_encode(["error" => "Si le paiement est par chèque, le numéro de chèque et le nom de la banque sont requis."]);
                    return;
                }
            }
    
            // Vérification de l'existence de la boîte postale
            $numeroBoitePostale = $decodedData['numero_boite_postale'];
            $checkBoxQuery = "
                SELECT id 
                FROM boites_postales 
                WHERE numero = :numero
            ";
            $stmt = $this->db->getPdo()->prepare($checkBoxQuery);
            $stmt->bindParam(':numero', $numeroBoitePostale, PDO::PARAM_STR);
            $stmt->execute();
    
            $boitePostaleResult = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$boitePostaleResult) {
                echo json_encode(["error" => "Aucune boîte postale trouvée avec ce numéro."]);
                return;
            }
    
            $idBoitePostale = $boitePostaleResult['id'];
    
            // Insertion de la collection
            $insertQuery = "
                INSERT INTO collection (adresse, id_boite_postale, created_at, updated_at)
                VALUES (:adresse, :id_boite_postale, NOW(), NOW())
            ";
            $stmt = $this->db->getPdo()->prepare($insertQuery);
            $stmt->bindParam(':adresse', $decodedData['adresse'], PDO::PARAM_STR);
            $stmt->bindParam(':id_boite_postale', $idBoitePostale, PDO::PARAM_INT);
            $stmt->execute();
    
            // Récupérer l'ID du client associé à la boîte postale
            $getClientQuery = "
                SELECT id 
                FROM clients 
                WHERE id_boite_postale = :id_boite_postale
            ";
            $stmt = $this->db->getPdo()->prepare($getClientQuery);
            $stmt->bindParam(':id_boite_postale', $idBoitePostale, PDO::PARAM_INT);
            $stmt->execute();
    
            $clientResult = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$clientResult) {
                echo json_encode(["error" => "Aucun client trouvé pour cette boîte postale."]);
                return;
            }
    
            $idClient = $clientResult['id'];
    
            // Mise à jour du paiement
            $updatePaymentQuery = "
            UPDATE paiements 
            SET montant_collection = montant_collection + :montant_collection,
                methode_paiement_collection = :methode_paiement_collection,
                type_wallet_collection = :type_wallet_collection,
                numero_cheque_collection = :numero_cheque_collection,
                nom_banque_collection = :nom_banque_collection,
                reference_ajout_collection = :reference_ajout_collection,
                numero_wallet_collection = :numero_wallet_collection  -- Ajout de numero_wallet_collection
            WHERE id_client = :id_client
        ";
        
        $stmt = $this->db->getPdo()->prepare($updatePaymentQuery);
        
        $stmt->bindParam(':montant_collection', $decodedData['montant_collection'], PDO::PARAM_STR);
        $stmt->bindParam(':methode_paiement_collection', $methodePaiement, PDO::PARAM_STR);
        $stmt->bindParam(':type_wallet_collection', $typeWallet, PDO::PARAM_STR);
        $stmt->bindParam(':reference_ajout_collection', $referenceAjoutCollection, PDO::PARAM_STR);
        $stmt->bindParam(':numero_wallet_collection', $decodedData['numero_wallet_collection'], PDO::PARAM_STR);  // Ajout de numero_wallet_collection
        $stmt->bindParam(':id_client', $idClient, PDO::PARAM_INT);
        
    
            // Ajout des informations de chèque si nécessaire
            if ($methodePaiement === 'cheque') {
                $stmt->bindParam(':numero_cheque_collection', $decodedData['numero_cheque_collection'], PDO::PARAM_STR);
                $stmt->bindParam(':nom_banque_collection', $decodedData['nom_banque_collection'], PDO::PARAM_STR);
            } else {
                $stmt->bindValue(':numero_cheque_collection', null, PDO::PARAM_NULL);
                $stmt->bindValue(':nom_banque_collection', null, PDO::PARAM_NULL);
            }
    
            $stmt->execute();
    
            // Réponse de succès
            echo json_encode(["success" => "La collection a été ajoutée et le paiement mis à jour avec succès."]);
        } catch (PDOException $e) {
            // Gestion des erreurs
            echo json_encode(["error" => "Erreur de base de données : " . $e->getMessage()]);
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
                c.nom AS nom_client,
                c.adresse AS adresse_client,
                c.type_client AS type_client,
                bp.numero AS numero_boite_postale,
                bp.type AS type_boite_postale,
                c.telephone AS telephone_client,
                a.annee_abonnement AS annee_abonnement,
                (
                    CASE 
                        WHEN a.annee_abonnement = :currentYear THEN 'mis_a_jour'
                        ELSE 'non_mis_a_jour'
                    END
                ) AS type_client_mis_a_jour,
                (
                    SELECT COUNT(*) 
                    FROM sous_couvete sc 
                    WHERE sc.id_boite_postale = bp.id
                ) AS nombre_sous_couvettes
            FROM 
                clients c
            LEFT JOIN 
                boites_postales bp ON c.id_boite_postale = bp.id
            LEFT JOIN 
                abonnement a ON bp.id = a.id_boite_postale
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
        $queryLastInsert = "SELECT reference_achat_cle FROM paiements ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->getPdo()->prepare($queryLastInsert);
        $stmt->execute();

        // Récupérer le résultat
        $dernierPaiement = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dernierPaiement && !empty($dernierPaiement['reference_achat_cle'])) {
            // Retourner la référence si elle existe
            echo json_encode([
                "success" => true,
                "reference_achat_cle" => $dernierPaiement['reference_achat_cle']
            ]);
        } else {
            // Retourner une valeur par défaut si la table est vide ou la référence absente
            echo json_encode([
                "success" => false,
                "reference_achat_cle" => null, // Ou une valeur par défaut si nécessaire
                "error" => "Aucune référence trouvée, initialisation nécessaire."
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
        $queryLastInsert = "SELECT reference_ajout_sous_couvette FROM paiements ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->getPdo()->prepare($queryLastInsert);
        $stmt->execute();

        // Récupérer le résultat
        $dernierPaiement = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dernierPaiement && !empty($dernierPaiement['reference_ajout_sous_couvette'])) {
            // Retourner la référence si elle existe
            echo json_encode([
                "success" => true,
                "reference_ajout_sous_couvette" => $dernierPaiement['reference_ajout_sous_couvette']
            ]);
        } else {
            // Retourner une valeur par défaut si la table est vide ou la référence absente
            echo json_encode([
                "success" => false,
                "reference_ajout_sous_couvette" => null, // Ou une valeur par défaut si nécessaire
                "error" => "Aucune référence ajoutée pour sous-couverture trouvée, initialisation nécessaire."
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
        $queryLastInsert = "SELECT reference_changer_nom FROM paiements ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->getPdo()->prepare($queryLastInsert);
        $stmt->execute();

        // Récupérer le résultat
        $dernierPaiement = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dernierPaiement && !empty($dernierPaiement['reference_changer_nom'])) {
            // Retourner la référence si elle existe
            echo json_encode([
                "success" => true,
                "reference_changer_nom" => $dernierPaiement['reference_changer_nom']
            ]);
        } else {
            // Retourner une valeur par défaut si la table est vide ou la référence absente
            echo json_encode([
                "success" => false,
                "reference_changer_nom" => null, // Ou une valeur par défaut si nécessaire
                "error" => "Aucune référence changée de nom trouvée, initialisation nécessaire."
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
        $queryLastInsert = "SELECT reference_livraison_domicile FROM paiements ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->getPdo()->prepare($queryLastInsert);
        $stmt->execute();

        // Récupérer le résultat
        $dernierPaiement = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dernierPaiement && !empty($dernierPaiement['reference_livraison_domicile'])) {
            // Retourner la référence si elle existe
            echo json_encode([
                "success" => true,
                "reference_livraison_domicile" => $dernierPaiement['reference_livraison_domicile']
            ]);
        } else {
            // Retourner une valeur par défaut si la table est vide ou la référence absente
            echo json_encode([
                "success" => false,
                "reference_livraison_domicile" => null, // Ou une valeur par défaut si nécessaire
                "error" => "Aucune référence de livraison domicile trouvée, initialisation nécessaire."
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
        // Préparer la requête pour récupérer la dernière insertion pour reference_ajout_collection
        $queryLastInsert = "SELECT reference_ajout_collection FROM paiements ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->getPdo()->prepare($queryLastInsert);
        $stmt->execute();

        // Récupérer le résultat
        $dernierPaiement = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dernierPaiement && !empty($dernierPaiement['reference_ajout_collection'])) {
            // Retourner la référence si elle existe
            echo json_encode([
                "success" => true,
                "reference_ajout_collection" => $dernierPaiement['reference_ajout_collection']
            ]);
        } else {
            // Retourner une valeur par défaut si la table est vide ou la référence absente
            echo json_encode([
                "success" => false,
                "reference_ajout_collection" => null, // Ou une valeur par défaut si nécessaire
                "error" => "Aucune référence ajout collection trouvée, initialisation nécessaire."
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
        // Préparer la requête pour récupérer la dernière insertion pour reference
        $queryLastInsert = "SELECT reference FROM paiements ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->getPdo()->prepare($queryLastInsert);
        $stmt->execute();

        // Récupérer le résultat
        $dernierPaiement = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dernierPaiement && !empty($dernierPaiement['reference'])) {
            // Retourner la référence si elle existe
            echo json_encode([
                "success" => true,
                "reference" => $dernierPaiement['reference']
            ]);
        } else {
            // Retourner une valeur par défaut si la table est vide ou la référence absente
            echo json_encode([
                "success" => false,
                "reference" => null, // Ou une valeur par défaut si nécessaire
                "error" => "Aucune référence trouvée, initialisation nécessaire."
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

?> 
