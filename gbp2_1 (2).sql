-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 13 avr. 2025 à 16:39
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gbp2.1`
--

-- --------------------------------------------------------

--
-- Structure de la table `abonnement`
--

CREATE TABLE `abonnement` (
  `id` int(11) NOT NULL,
  `Id_client` int(11) DEFAULT NULL,
  `Annee_abonnement` int(11) NOT NULL,
  `Montant` decimal(10,2) DEFAULT 20000.00,
  `MontantSc` decimal(10,2) NOT NULL,
  `Penalite` decimal(10,2) DEFAULT 0.00,
  `Status` enum('payé','impayé','exonorer') NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `abonnement`
--

INSERT INTO `abonnement` (`id`, `Id_client`, `Annee_abonnement`, `Montant`, `MontantSc`, `Penalite`, `Status`, `created_at`, `updated_at`, `updated_by`) VALUES
(3, 9, 2025, 20000.00, 0.00, 0.00, 'payé', '2025-02-27', '2025-02-27', 1),
(4, 11, 2025, 20000.00, 0.00, 3000.00, 'payé', '2025-02-27', '2025-03-16', 1),
(5, 11, 2026, 20000.00, 0.00, 3000.00, 'payé', '2025-04-06', '2025-04-06', 27),
(38, 49, 2025, 20000.00, 0.00, 0.00, 'payé', '2025-04-06', '2025-04-06', 27),
(41, 53, 2025, 20000.00, 0.00, 0.00, 'payé', '2025-04-06', '2025-04-06', 27),
(42, 54, 2025, 20000.00, 0.00, 0.00, 'payé', '2025-04-06', '2025-04-06', 27),
(43, 55, 2025, 20000.00, 0.00, 0.00, 'payé', '2025-04-06', '2025-04-06', 27),
(44, 11, 2026, 20000.00, 0.00, 0.00, 'impayé', '2025-04-06', '2025-04-06', 27),
(45, 57, 2025, 20000.00, 0.00, 0.00, 'payé', '2025-04-07', '2025-04-07', 27),
(46, 58, 2025, 20000.00, 0.00, 0.00, 'payé', '2025-04-08', '2025-04-08', 27),
(47, 59, 2025, 20000.00, 0.00, 0.00, 'payé', '2025-04-08', '2025-04-08', 27),
(48, 60, 2025, 25000.00, 0.00, 0.00, 'impayé', '2025-04-13', '2025-04-13', 27),
(49, 61, 2025, 25000.00, 0.00, 0.00, 'payé', '2025-04-13', '2025-04-13', 27),
(50, 62, 2025, 25000.00, 0.00, 0.00, 'impayé', '2025-04-13', '2025-04-13', 27),
(51, 63, 2025, 15000.00, 0.00, 0.00, 'impayé', '2025-04-13', '2025-04-13', 27),
(52, 64, 2025, 15000.00, 0.00, 0.00, 'payé', '2025-04-13', '2025-04-13', 27);

-- --------------------------------------------------------

--
-- Structure de la table `boit_postal`
--

CREATE TABLE `boit_postal` (
  `id` int(11) NOT NULL,
  `Numero` varchar(50) NOT NULL,
  `Type` enum('Grand','Moyen','Petite') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `boit_postal`
--

INSERT INTO `boit_postal` (`id`, `Numero`, `Type`) VALUES
(5, '1', 'Grand'),
(8, '2', 'Grand'),
(47, '3', 'Grand'),
(50, '4', 'Grand'),
(51, '5', 'Grand'),
(52, '6', 'Grand'),
(53, '7', 'Grand'),
(54, '8', 'Grand'),
(55, '9', 'Grand'),
(56, '10', 'Moyen');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `Nom` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Adresse` varchar(255) DEFAULT NULL,
  `TypeClient` enum('Entreprise','Particulier') NOT NULL,
  `Telephone` varchar(20) DEFAULT NULL,
  `Id_boite_postale` int(11) DEFAULT NULL,
  `Date_abonnement` date NOT NULL,
  `id_user` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `Nom`, `Email`, `Adresse`, `TypeClient`, `Telephone`, `Id_boite_postale`, `Date_abonnement`, `id_user`, `updated_by`) VALUES
(9, 'Hibo Ahmed', 'Fatouma@gmail.com', 'cite nasib', 'Entreprise', '77101214', 5, '2025-02-27', 1, 1),
(11, 'Fatouma ali mohamed', 'RaliaMohamed@gmail.com', 'cite nasib', 'Entreprise', '77101214', 8, '2025-02-27', 1, 1),
(49, 'testuser', 'testuser@gmail.com', 'cite nasib', 'Particulier', '77101214', 47, '2025-03-19', 27, 27),
(53, 'halima abdillahi hassan', 'testusennr@gmail.com', 'cite nasib', 'Particulier', '77101012', 50, '2025-03-19', 27, 27),
(54, 'layla farah', 'testusenner@gmail.com', 'balbala', 'Particulier', '77252121', 51, '2025-03-19', 27, 27),
(55, 'abdirahman nouradine moussa', 'abdirahman@gmail.com', 'cite nasib', 'Particulier', '77186724', 52, '2025-03-24', 27, 27),
(57, 'halima abdillahi hassan', 'testus@gmail.com', 'cite hodan', 'Particulier', '77101214', 50, '2025-04-07', 27, 27),
(58, 'katrine moussa ali ', 'katrine@gmail.com', 'cite wa shifa', 'Particulier', '77101214', 51, '2025-04-08', 27, 27),
(59, 'salima ahmed ali', 'salima@gmail.com', 'cite wa shifa', 'Particulier', '77101214', 52, '2025-04-08', 27, 27),
(60, 'hamze mousa daher', 'moussa14@gmail.com', 'cite nasib', 'Particulier', '77101214', 5, '2025-04-13', 27, 27),
(61, 'fdgdffdhfh', 'dfgfdgfdg@gmail.com', 'yuyui', 'Particulier', '77777778', 53, '2025-04-13', 27, 27),
(62, 'hgfgfgfsfgsxdgg', 'dfcdfvdv@gmail.com', 'test Adresse', 'Particulier', '77186724', 54, '2025-04-13', 27, 27),
(63, 'salima sqsg qbssh', 'dsfd@gmail.com', 'cite hodan', 'Particulier', '77777778', 55, '2025-04-13', 27, 27),
(64, 'yasmine amine ali', 'yasmine@gmail.com', 'Q3', 'Particulier', '77011214', 56, '2025-04-13', 27, 27);

-- --------------------------------------------------------

--
-- Structure de la table `collections`
--

CREATE TABLE `collections` (
  `id` int(11) NOT NULL,
  `Id_clients` int(11) DEFAULT NULL,
  `Adresse` varchar(255) NOT NULL,
  `Date` date NOT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `collections`
--

INSERT INTO `collections` (`id`, `Id_clients`, `Adresse`, `Date`, `created_by`) VALUES
(7, 9, 'Q7,cite barwago', '2025-04-06', 27),
(27, 49, 'Q7,cite barwago', '2025-03-19', 27),
(28, 11, 'Q7,cite barwago', '2025-04-13', 27);

-- --------------------------------------------------------

--
-- Structure de la table `details_paiements`
--

CREATE TABLE `details_paiements` (
  `id` int(11) NOT NULL,
  `Id_paiement` int(11) NOT NULL,
  `Categories` enum('livraison_a_domicil','sous_couverte','collections','Achat_cle','Changement_Nom') NOT NULL,
  `Montant` float NOT NULL,
  `Methode_paiement` enum('cash','cheque','wallet') NOT NULL,
  `Wallet` enum('dahabplace','waafi','d_money','cac_pay','sabapay') DEFAULT NULL,
  `Numero_wallet` varchar(255) DEFAULT NULL,
  `Numero_cheque` varchar(255) DEFAULT NULL,
  `Nom_bank` varchar(255) DEFAULT NULL,
  `reference` varchar(255) NOT NULL,
  `created_at` date NOT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `details_paiements`
--

INSERT INTO `details_paiements` (`id`, `Id_paiement`, `Categories`, `Montant`, `Methode_paiement`, `Wallet`, `Numero_wallet`, `Numero_cheque`, `Nom_bank`, `reference`, `created_at`, `created_by`) VALUES
(11, 3, 'Changement_Nom', 5000, 'cash', NULL, '', '', NULL, 'CGNM/00001/2025-03-13', '2025-04-06', 27),
(12, 3, 'Achat_cle', 2000, 'wallet', 'waafi', '77101010', '', NULL, 'CGCLE/00001/2025-03-13', '2025-04-06', 27),
(13, 3, 'sous_couverte', 30000, 'cheque', NULL, '', '132564', 'salam bank', 'AJSC/00001/2025-03-13', '2025-03-13', 27),
(14, 3, 'livraison_a_domicil', 5000, 'wallet', 'd_money', '77101010', '', NULL, 'AJLV/00001/2025-03-16', '2025-03-16', 27),
(15, 3, 'collections', 5000, 'cheque', NULL, '', '741012', NULL, 'AJCll/00001/2025-03-16', '2025-03-16', 27),
(56, 39, 'livraison_a_domicil', 3000, 'cash', NULL, '', '', '', 'AJLV/00002/2025-03-19', '2025-03-19', 27),
(57, 39, 'collections', 4000, 'cash', NULL, '', '', '', 'AJCll/00002/2025-03-19', '2025-03-19', 27),
(58, 39, 'sous_couverte', 20000, 'cash', NULL, '', '', '', 'AJSC/00002/2025-03-19', '2025-04-02', 27),
(60, 47, 'Achat_cle', 2000, 'cash', NULL, '', '', NULL, 'CGCLE/00002/2025-04-08', '2025-04-08', 27),
(61, 47, 'livraison_a_domicil', 5000, 'wallet', 'cac_pay', '77101010', '', NULL, 'AJLV/00003/2025-04-08', '2025-04-08', 27),
(62, 39, 'Achat_cle', 1500, 'wallet', 'waafi', '77101010', '', NULL, 'CGCLE/00003/2025-04-13', '2025-04-13', 27),
(63, 5, 'collections', 60000, 'cash', NULL, '', '', NULL, 'AJCll/00003/2025-04-13', '2025-04-13', 27),
(64, 39, 'sous_couverte', 3500, 'cash', NULL, '', '', '', 'AJSC/00003/2025-04-13', '2025-04-13', 27),
(65, 48, 'sous_couverte', 3500, 'cheque', NULL, '', '132564', 'salam bank', 'AJSC/00004/2025-04-13', '2025-04-13', 27),
(66, 50, 'sous_couverte', 3500, 'cash', NULL, '', '', '', 'AJSC/00005/2025-04-13', '2025-04-13', 27),
(67, 51, 'sous_couverte', 3500, 'cash', NULL, '', '', '', 'AJSC/00006/2025-04-13', '2025-04-13', 27);

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `Id_client` int(11) DEFAULT NULL,
  `Abonnement` varchar(255) NOT NULL,
  `Identite` varchar(255) NOT NULL,
  `Patent_Quitance` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `documents`
--

INSERT INTO `documents` (`id`, `Id_client`, `Abonnement`, `Identite`, `Patent_Quitance`, `created_at`, `created_by`) VALUES
(3, 9, 'upload/documents/1740643768_Compte d\'attente.PNG', 'upload/documents/1740643768_compte d\'attende modifier.PNG', 'upload/documents/1740643768_Add Clients individuel.PNG', '2025-02-27 08:09:28', 1),
(4, 11, 'upload/documents/1740645194_Compte d\'attente.PNG', 'upload/documents/1740645194_compte d\'attende modifier.PNG', 'upload/documents/1740645194_Add Clients individuel.PNG', '2025-02-27 08:33:14', 1),
(28, 49, 'upload/documents/1742371828_Compte d\'attente.PNG', 'upload/documents/1742371828_compte d\'attende modifier.PNG', 'null', '2025-03-19 08:10:28', 27),
(31, 53, 'upload/documents/1742374544_compte d\'attende modifier.PNG', 'upload/documents/1742374544_Compte d\'attente.PNG', 'null', '2025-03-19 08:55:44', 27),
(32, 54, 'upload/documents/1742374612_compte d\'attende modifier.PNG', 'upload/documents/1742374612_Compte d\'attente.PNG', 'null', '2025-03-19 08:56:52', 27),
(33, 55, 'upload/documents/1742812964_2222.pdf', 'upload/documents/1742812964_Portfolio Web Template (Community).pdf', 'null', '2025-03-24 10:42:44', 27),
(34, 57, 'upload/documents/1744026484_2222.pdf', 'upload/documents/1744026484_oskar-yildiz-cOkpTiJMGzA-unsplash.jpg', 'null', '2025-04-07 11:48:04', 27),
(35, 58, 'upload/documents/1744096379_RDV docteur.pdf', 'upload/documents/1744096379_Portfolio DevChapter.pdf', 'null', '2025-04-08 07:12:59', 27),
(36, 59, 'upload/documents/1744097839_RDV docteur.pdf', 'upload/documents/1744097839_Portfolio DevChapter.pdf', 'null', '2025-04-08 07:37:19', 27),
(37, 60, 'upload/documents/1744545796_Gestion Boite Postale00000.pdf', 'upload/documents/1744545797_Portfolio Web Template (Community).pdf', 'null', '2025-04-13 12:03:17', 27),
(38, 61, 'upload/documents/1744546663_Portfolio Web Template (Community).pdf', 'upload/documents/1744546663_Gestion Boite Postale00000.pdf', 'null', '2025-04-13 12:17:43', 27),
(39, 62, 'upload/documents/1744546917_Gestion Boite Postale.pdf', 'upload/documents/1744546918_Portfolio DevChapter.pdf', 'null', '2025-04-13 12:21:58', 27),
(40, 63, 'upload/documents/1744547283_Portfolio Web Template (Community).pdf', 'upload/documents/1744547283_Portfolio DevChapter (1).pdf', 'null', '2025-04-13 12:28:03', 27),
(41, 64, 'upload/documents/1744552333_Gestion Boite Postale00000.pdf', 'upload/documents/1744552333_2222.pdf', 'null', '2025-04-13 13:52:13', 27);

-- --------------------------------------------------------

--
-- Structure de la table `exonore`
--

CREATE TABLE `exonore` (
  `id` int(11) NOT NULL,
  `Id_client` int(11) DEFAULT NULL,
  `Date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lvdomcile`
--

CREATE TABLE `lvdomcile` (
  `id` int(11) NOT NULL,
  `Id_clients` int(11) DEFAULT NULL,
  `Adresse` varchar(255) NOT NULL,
  `Date` date NOT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `lvdomcile`
--

INSERT INTO `lvdomcile` (`id`, `Id_clients`, `Adresse`, `Date`, `created_by`) VALUES
(8, 9, 'q3,q4,cite hodan', '2025-03-16', 27),
(26, 49, 'cite hodan 4', '2025-04-06', 27),
(27, 59, 'barwago', '2025-04-08', 27);

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `id` int(11) NOT NULL,
  `Id_abonnement` int(11) DEFAULT NULL,
  `Methode_paiement` enum('cash','cheque','wallet') NOT NULL,
  `Wallet` enum('dahabplace','waafi','d_money','cac_pay','sabapay') DEFAULT NULL,
  `Numero_wallet` varchar(255) DEFAULT NULL,
  `Numero_cheque` varchar(255) DEFAULT NULL,
  `Nom_bank` varchar(255) DEFAULT NULL,
  `reference` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `paiement`
--

INSERT INTO `paiement` (`id`, `Id_abonnement`, `Methode_paiement`, `Wallet`, `Numero_wallet`, `Numero_cheque`, `Nom_bank`, `reference`, `created_at`, `created_by`) VALUES
(3, 3, 'wallet', 'waafi', '77101214', '0', '0', 'NBGPB/0001/2025', '2025-02-27 08:09:28', 1),
(5, 4, 'cash', NULL, '', '', NULL, 'RNBP/00002/2025-03-16', '2025-03-16 09:25:28', 27),
(6, 5, 'cash', NULL, '', '', NULL, 'RNBP/00002/2025-03-16', '2025-03-16 09:25:28', 27),
(39, 38, 'cash', NULL, '', '', '', 'RNBP/00003/2025-03-19', '2025-03-19 08:10:28', 27),
(42, 41, 'cash', NULL, '', '', '', 'RNBP/00005/2025-03-19', '2025-03-19 08:55:44', 27),
(43, 42, 'cash', NULL, '', '', '', 'RNBP/00006/2025-03-19', '2025-03-19 08:56:52', 27),
(44, 43, 'cash', NULL, '', '', '', 'RNBP/00007/2025-03-24', '2025-03-24 10:42:44', 27),
(45, 45, 'cheque', NULL, '', '132564', 'EAB', 'RNBP/00008/2025-04-07', '2025-04-07 11:48:04', 27),
(46, 46, 'wallet', 'waafi', '77101010', '', '', 'RNBP/00009/2025-04-08', '2025-04-08 07:12:59', 27),
(47, 47, 'wallet', 'waafi', '77101010', '', '', 'RNBP/00010/2025-04-08', '2025-04-08 07:37:19', 27),
(48, 48, 'cheque', NULL, '', '132564', 'salam bank', 'RNBP/00011/2025-04-13', '2025-04-13 12:03:16', 27),
(49, 49, 'cash', NULL, '', '', '', 'RNBP/00012/2025-04-13', '2025-04-13 12:17:43', 27),
(50, 50, 'cash', NULL, '', '', '', 'RNBP/00013/2025-04-13', '2025-04-13 12:21:57', 27),
(51, 51, 'cash', NULL, '', '', '', 'RNBP/00014/2025-04-13', '2025-04-13 12:28:03', 27),
(52, 52, 'cash', NULL, '', '', '', 'RNBP/00015/2025-04-13', '2025-04-13 13:52:13', 27);

-- --------------------------------------------------------

--
-- Structure de la table `penaliter`
--

CREATE TABLE `penaliter` (
  `id` int(11) NOT NULL,
  `Abonnement_id` int(11) DEFAULT NULL,
  `Montant` decimal(10,2) NOT NULL,
  `Date_application` date NOT NULL,
  `Status` enum('payé','impayé') NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `resilier`
--

CREATE TABLE `resilier` (
  `id` int(11) NOT NULL,
  `Id_client` int(11) DEFAULT NULL,
  `Lettre_recommandation` text NOT NULL,
  `Date_resilier` date NOT NULL,
  `Resilier_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `resilier`
--

INSERT INTO `resilier` (`id`, `Id_client`, `Lettre_recommandation`, `Date_resilier`, `Resilier_by`) VALUES
(1, 9, 'test', '2025-03-19', 27),
(2, 55, 'upload/documents/lettre_recommandation_resilier/lettre_67e2873e429bc.PNG', '2025-03-25', 27),
(3, 54, 'upload/documents/lettre_recommandation_resilier/lettre_67e2878fbb195.pdf', '2025-03-25', 28),
(4, 53, 'upload/documents/lettre_recommandation_resilier/lettre_67e3d6690a22e.pdf', '2025-03-26', 28);

-- --------------------------------------------------------

--
-- Structure de la table `sous_couverte`
--

CREATE TABLE `sous_couverte` (
  `id` int(11) NOT NULL,
  `Nom_societe` varchar(255) DEFAULT NULL,
  `Nom_personne` varchar(255) DEFAULT NULL,
  `Telephone` varchar(20) DEFAULT NULL,
  `Adresse` varchar(255) DEFAULT NULL,
  `Id_client` int(11) DEFAULT NULL,
  `Created_by` int(11) DEFAULT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `sous_couverte`
--

INSERT INTO `sous_couverte` (`id`, `Nom_societe`, `Nom_personne`, `Telephone`, `Adresse`, `Id_client`, `Created_by`, `date`) VALUES
(10, 'societe A', 'Mousa ali farah', '7710121410', 'quartier 5', 9, 27, '2025-03-13'),
(15, 'Djibouti telecom', 'Hassan nouradine mousa', '7710121410', 'quartier 5', 49, 27, '2025-04-06'),
(17, 'Djibouti telecom', 'Mousa ali farah', '7710121410', 'quartier 5', 49, 27, '2025-04-13'),
(18, 'societe A', 'sdfsdfdsf', '7710121410', 'quartier 5', 60, 27, '2025-04-13'),
(19, 'societe A', 'Mousa ali farah', '7710121410', 'jghjhjg', 62, 27, '2025-04-13'),
(20, 'societe A', 'Mousa ali farah', '7710121410', 'quartier 5', 63, 27, '2025-04-13');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `Nom` varchar(100) NOT NULL,
  `Adresse` varchar(255) DEFAULT NULL,
  `Telephone` varchar(20) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `Role` enum('agent_commercial','agent_guichet','responsable','Admin','agent_comptable','superviseur') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `Nom`, `Adresse`, `Telephone`, `Email`, `password`, `Role`) VALUES
(1, 'moussa abdirahman ali', 'cite hodan', '77101214', 'moussa@gmail.com', '$2a$12$sAgTed5Ir6SzUzmOyUEtGe0dvSQyk5UwFQipPy/JCK1BWLLKIZgkW', 'responsable'),
(27, 'halima abdillahi hassan', 'test Adresse', '77101214', 'denerise@gmail.com', '$2y$10$Z6VlYn60l2Tc68aILh2A6e1WyUcPWYWgqJf7O7IQi6AC6suKkhLGO', 'agent_guichet'),
(28, 'abdirahman nouradine moussa', 'cite hodan', '77101214', 'abdi@gmail.com', '$2y$10$UPhoyRhW/e3Csn4D1f/zVOIAjemjvOoYr0uI4gf4X8nbmSz5OuXRO', 'agent_commercial'),
(29, 'fatouma omar abdillahi', 'cite hodan', '77101214', 'youra@gmail.com', '$2y$10$c4awG/9pqhcTSHXh3zPXCONKTR/WBY0BjyWdiaPMxBfOybaNDw8IS', 'superviseur'),
(30, 'layla ariel dave', 'Q3', '77101214', 'layla@gmail.com', '$2y$10$Om32uSPzkSPZbeZdPWt6ZePkLbMmtdpqA/7Cdyz1BluTrJs0DgZIy', 'superviseur');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `abonnement`
--
ALTER TABLE `abonnement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Id_client` (`Id_client`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Index pour la table `boit_postal`
--
ALTER TABLE `boit_postal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Numero` (`Numero`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `Id_boite_postale` (`Id_boite_postale`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Index pour la table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Id_clients` (`Id_clients`),
  ADD KEY `created_by` (`created_by`);

--
-- Index pour la table `details_paiements`
--
ALTER TABLE `details_paiements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Id_paiement` (`Id_paiement`),
  ADD KEY `created_by` (`created_by`);

--
-- Index pour la table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Id_client` (`Id_client`),
  ADD KEY `created_by` (`created_by`);

--
-- Index pour la table `exonore`
--
ALTER TABLE `exonore`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Id_client` (`Id_client`),
  ADD KEY `created_by` (`created_by`);

--
-- Index pour la table `lvdomcile`
--
ALTER TABLE `lvdomcile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Id_clients` (`Id_clients`),
  ADD KEY `created_by` (`created_by`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Id_abonnement` (`Id_abonnement`),
  ADD KEY `created_by` (`created_by`);

--
-- Index pour la table `penaliter`
--
ALTER TABLE `penaliter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Abonnement_id` (`Abonnement_id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Index pour la table `resilier`
--
ALTER TABLE `resilier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Id_client` (`Id_client`),
  ADD KEY `Resilier_by` (`Resilier_by`);

--
-- Index pour la table `sous_couverte`
--
ALTER TABLE `sous_couverte`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Id_client` (`Id_client`),
  ADD KEY `Created_by` (`Created_by`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `abonnement`
--
ALTER TABLE `abonnement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT pour la table `boit_postal`
--
ALTER TABLE `boit_postal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT pour la table `collections`
--
ALTER TABLE `collections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `details_paiements`
--
ALTER TABLE `details_paiements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `exonore`
--
ALTER TABLE `exonore`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `lvdomcile`
--
ALTER TABLE `lvdomcile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT pour la table `penaliter`
--
ALTER TABLE `penaliter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `resilier`
--
ALTER TABLE `resilier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `sous_couverte`
--
ALTER TABLE `sous_couverte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `abonnement`
--
ALTER TABLE `abonnement`
  ADD CONSTRAINT `abonnement_ibfk_1` FOREIGN KEY (`Id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `abonnement_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`Id_boite_postale`) REFERENCES `boit_postal` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `clients_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `clients_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `collections`
--
ALTER TABLE `collections`
  ADD CONSTRAINT `collections_ibfk_1` FOREIGN KEY (`Id_clients`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `collections_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `details_paiements`
--
ALTER TABLE `details_paiements`
  ADD CONSTRAINT `details_paiements_ibfk_1` FOREIGN KEY (`Id_paiement`) REFERENCES `paiement` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `details_paiements_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`Id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `exonore`
--
ALTER TABLE `exonore`
  ADD CONSTRAINT `exonore_ibfk_1` FOREIGN KEY (`Id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exonore_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `lvdomcile`
--
ALTER TABLE `lvdomcile`
  ADD CONSTRAINT `lvdomcile_ibfk_1` FOREIGN KEY (`Id_clients`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lvdomcile_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `paiement_ibfk_1` FOREIGN KEY (`Id_abonnement`) REFERENCES `abonnement` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `paiement_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `penaliter`
--
ALTER TABLE `penaliter`
  ADD CONSTRAINT `penaliter_ibfk_1` FOREIGN KEY (`Abonnement_id`) REFERENCES `abonnement` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penaliter_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `resilier`
--
ALTER TABLE `resilier`
  ADD CONSTRAINT `resilier_ibfk_1` FOREIGN KEY (`Id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `resilier_ibfk_2` FOREIGN KEY (`Resilier_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `sous_couverte`
--
ALTER TABLE `sous_couverte`
  ADD CONSTRAINT `sous_couverte_ibfk_1` FOREIGN KEY (`Id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sous_couverte_ibfk_2` FOREIGN KEY (`Created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
