-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 05 fév. 2025 à 13:34
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
-- Base de données : `gbp`
--

-- --------------------------------------------------------

--
-- Structure de la table `abonnement`
--

CREATE TABLE `abonnement` (
  `id` int(11) NOT NULL,
  `id_boite_postale` int(11) NOT NULL,
  `annee_abonnement` year(4) NOT NULL,
  `id_payments` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `abonnement`
--

INSERT INTO `abonnement` (`id`, `id_boite_postale`, `annee_abonnement`, `id_payments`) VALUES
(1, 5, '2025', 3),
(2, 2, '2024', 13),
(3, 18, '2024', 21),
(4, 19, '2024', 22),
(5, 20, '2024', 23),
(6, 21, '2024', 24),
(7, 22, '2024', 25),
(8, 24, '2024', 28),
(9, 26, '2024', 29),
(10, 27, '2024', 30),
(11, 28, '2024', 31),
(12, 29, '2024', 32),
(13, 30, '2024', 33),
(14, 31, '2024', 34),
(15, 32, '2024', 35),
(16, 33, '2024', 36),
(17, 34, '2024', 37),
(18, 35, '2024', 38),
(19, 36, '2024', 39),
(20, 37, '2024', 40),
(21, 38, '2025', 41),
(22, 39, '2025', 42),
(23, 40, '2025', 43),
(24, 41, '2025', 44),
(26, 42, '2025', 46),
(27, 43, '2025', 47),
(30, 46, '2025', 52),
(31, 47, '2025', 53),
(32, 48, '2025', 54),
(33, 49, '2025', 55),
(34, 50, '2025', 56),
(35, 51, '2025', 57),
(36, 1, '2025', 2);

-- --------------------------------------------------------

--
-- Structure de la table `boites_postales`
--

CREATE TABLE `boites_postales` (
  `id` int(11) NOT NULL,
  `type` enum('petit','grand','moyen') NOT NULL,
  `numero` varchar(50) NOT NULL,
  `cle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `boites_postales`
--

INSERT INTO `boites_postales` (`id`, `type`, `numero`, `cle`) VALUES
(1, 'grand', '756224155', 'test'),
(2, 'petit', '75896542', 'test'),
(3, 'moyen', '7B_11', 'test'),
(4, 'grand', 'B_789', 'test'),
(5, 'moyen', 'B119', 'test'),
(6, 'grand', 'B20', 'test'),
(7, 'petit', 'B200', 'test'),
(8, 'moyen', 'B500', 'test'),
(9, 'grand', 'BP501', 'test'),
(10, 'moyen', 'B800', 'test'),
(11, 'petit', 'B801', 'test'),
(12, 'grand', 'B802', 'test'),
(13, 'grand', 'BP600', 'test'),
(14, 'petit', 'BP601', 'test'),
(15, 'moyen', 'B1000', 'test'),
(16, 'petit', 'B21', 'test'),
(17, 'moyen', 'B8', 'test'),
(18, 'petit', 'BP1', 'test'),
(19, 'grand', 'BP90', 'test'),
(20, 'petit', 'BP03', 'test'),
(21, 'moyen', 'BP55', 'test'),
(22, 'grand', 'BP56', 'test'),
(23, 'petit', 'B14', ''),
(24, 'petit', 'BP88', 'test'),
(26, 'grand', 'BP901', 'test'),
(27, 'moyen', 'BP12', 'test'),
(28, 'petit', 'BP11', 'test'),
(29, 'grand', 'BP10', 'test'),
(30, 'moyen', 'BP13', 'test'),
(31, 'moyen', 'BP14', 'test'),
(32, '', 'BP15', 'test'),
(33, 'moyen', 'BP16', 'test'),
(34, 'petit', 'BP17', 'test'),
(35, '', 'BP18', 'test'),
(36, 'grand', 'BP19', 'test'),
(37, 'grand', 'BP20', 'test'),
(38, 'petit', 'BP22', 'test'),
(39, 'grand', 'BP23', 'test'),
(40, 'grand', 'BP24', 'test'),
(41, 'petit', 'BP25', 'test'),
(42, 'grand', 'BP26', 'test'),
(43, 'petit', 'BP27', 'test'),
(44, 'grand', 'BP28', 'test'),
(46, 'grand', 'BP29', 'test'),
(47, 'moyen', 'BP30', 'test'),
(48, 'grand', 'BP31', 'test'),
(49, 'petit', 'BP32', 'test'),
(50, 'moyen', 'BP33', 'test'),
(51, 'petit', 'BP34', 'test');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `adresse` text NOT NULL,
  `nom_societe` varchar(255) DEFAULT NULL,
  `type_client` enum('particulier','societé') NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `id_boite_postale` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `date_abonnement` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `nom`, `email`, `adresse`, `nom_societe`, `type_client`, `telephone`, `id_boite_postale`, `id_user`, `update_by`, `date_abonnement`) VALUES
(2, 'Nouveau Nom Client', 'sadik@gmail.com', 'test', NULL, 'particulier', '7775865', 1, 1, 2, '2024-12-01'),
(3, 'Nouveau Njjjhhhgomggggg du Client', 'bouh@gmail.com', 'test', NULL, 'particulier', '885562', 2, 1, 2, '2024-12-01'),
(4, 'mohamed', 'kiki@gmail.com', 'test', NULL, 'particulier', '77895', 3, 2, NULL, '2024-12-03'),
(5, 'packson Doe', 'jakwar@gmail.com', 'test', 'jakwar society', 'societé', '777087058', 5, 2, 2, '2024-11-19'),
(6, 'kathir', 'kathir@gmail.com', 'test', 'jojo', 'societé', '77895', 6, 1, NULL, '2024-12-01'),
(7, 'Jean Dupont', 'jean.dupont@example.com', '123 Rue Principale', 'Dupont SARL', 'societé', '0612345678', 8, 2, 1, '0000-00-00'),
(12, 'Jean Dupont', 'jeanpont@exampffgle.com', '123 Rue Principale', 'Dupont SARL', 'societé', '0612345678', 9, 2, 1, '2024-12-01'),
(14, 'Jean Dupont', 'jeanpolomp@exampffgle.com', '123 Rue Principale', 'Dupont SARL', 'societé', '0612345678', 10, 2, 1, '2024-12-01'),
(15, 'Jean Dupont', 'jeanpolkkoppojuuuhomp@exampffgle.com', '123 Rue Principale', 'Dupont SARL', 'societé', '0612345678', 11, 2, 1, '2024-12-01'),
(16, 'Jean Dupont', 'jeankouuuhomp@exampffgle.com', '123 Rue Principale', 'Dupont SARL', 'societé', '0612345678', 12, 2, 1, '2024-12-01'),
(18, 'Jean Dupont', 'jean.duponjhgfdert@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 13, 1, 1, '2024-12-16'),
(20, 'Jean Dupont', 'jean@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 14, 2, 2, '2024-12-16'),
(21, 'Jean Dupont', 'BENjean@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 15, 1, 1, '2024-12-16'),
(22, 'Jean Duponte', 'tenBENjean@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 16, 1, 1, '2024-12-16'),
(24, 'Jean Duponte', 'tenenesenBENjean@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 17, 1, 1, '2024-12-16'),
(25, 'manchini', 'manchini@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 18, 2, 2, '2024-12-16'),
(26, 'manchini', 'BP90@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 19, 1, 1, '2024-12-16'),
(27, 'POSTMAN', 'BP03@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 20, 1, 1, '2024-12-16'),
(29, 'POSTMAN', 'BPPPPPPPP3@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 21, 1, 1, '2024-12-16'),
(30, 'POSTMAN', 'HOHO@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 22, 1, 1, '2024-12-16'),
(31, 'koko', 'mlopjgmail.com', 'noto', 'test', 'societé', '7775865', 23, 2, 2, '2024-12-24'),
(34, 'Jean Dupont', 'jean.GOGOGEFETH@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 24, 1, 1, '2024-12-22'),
(36, 'Jean Dupont', 'jean.tyuioerfdgh@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 26, 1, 1, '2024-12-22'),
(37, 'Jean Dupont', 'bashkahdouk.tyuioerfdgh@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 27, 1, 1, '2024-12-22'),
(38, 'Jean Dupont', 'GAKPOFLASHNULLE.tyuioerfdgh@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 28, 1, 1, '2024-12-22'),
(40, 'Jean Dupont', 'h.tyuioerfdgh@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 29, 1, 1, '2024-12-22'),
(41, 'Jean Dupont', 'hE.tyuioerfdgh@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 30, 1, 1, '2024-12-22'),
(42, 'Jean Dupont', 'hEdsd.tyuioerfdgh@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 31, 1, 1, '2024-12-22'),
(43, 'Jean Dupont', 'hEdsddfsccd.tyuioerfdgh@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 32, 1, 1, '2024-12-22'),
(44, 'Jean Dupont', 'hEdsddfghhdfsccd.tyuioerfdgh@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 33, 1, 1, '2024-12-22'),
(45, 'Jean Dupont', 'MESSINEY.tyuioerfdgh@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 34, 1, 1, '2024-12-22'),
(46, 'Jean Dupont', 'GT.tyuioerfdgh@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 35, 1, 1, '2024-12-22'),
(47, 'Jean Dupont', 'GT.john@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 36, 1, 1, '2024-12-22'),
(48, 'Jean Dupont', 'GT.hbnvfdr@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 37, 1, 1, '2024-12-22'),
(50, 'Jean Dupont', 'KOKO.hbnvfdr@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 38, 1, 1, '2024-12-22'),
(52, 'Jean Dupont', 'joj.hbnvfdr@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 39, 1, 1, '2024-12-22'),
(53, 'Jean Dupont', 'GOIU.hbnvfdr@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 40, 1, 1, '2024-12-22'),
(54, 'Jean Dupont', 'MPOLK.hbnvfdr@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 41, 1, 1, '2024-12-22'),
(56, 'Nouveau Nom', 'truhg.hbnvfdr@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 42, 1, 1, '2024-12-22'),
(57, 'Jean Dupont', 'tgyuio.hbnvfdr@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 43, 1, 1, '2024-12-22'),
(59, 'Jean Dupont', 'uiytrdfgg.hbnvfdr@example.com', '123 Rue Principale', NULL, 'particulier', '0612345678', 44, 1, 1, '2024-12-22'),
(61, 'Jean Dupont', 'gavi.dupont@example.com', '123 Rue Exemple, Ville, Pays', NULL, '', '+123456789', 46, 1, 1, '2025-01-01'),
(62, 'Jean Dupont', 'casdao.dupont@example.com', '123 Rue Exemple, Ville, Pays', NULL, '', '+123456789', 47, 1, 1, '2025-01-01'),
(63, 'Jean Dupont', 'JAGTD.dupont@example.com', '123 Rue Exemple, Ville, Pays', NULL, '', '+123456789', 48, 1, 1, '2025-01-01'),
(64, 'Jean Dupont', 'LOPTIGUI.dupont@example.com', '123 Rue Exemple, Ville, Pays', NULL, '', '+123456789', 49, 1, 1, '2025-01-01'),
(65, 'Jean Dupont', 'kdfrty.dupont@example.com', '123 Rue Exemple, Ville, Pays', NULL, '', '+123456789', 50, 1, 1, '2025-01-01'),
(66, 'Jean Dupont', 'katie.dupont@example.com', '123 Rue Exemple, Ville, Pays', NULL, '', '+123456789', 51, 1, 1, '2025-01-01');

-- --------------------------------------------------------

--
-- Structure de la table `collection`
--

CREATE TABLE `collection` (
  `id` int(11) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_client` int(11) NOT NULL,
  `id_client` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `depot`
--

CREATE TABLE `depot` (
  `id` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `date_depot` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `details_paiements`
--

CREATE TABLE `details_paiements` (
  `id` int(11) NOT NULL,
  `paiement_id` int(11) NOT NULL,
  `categorie` enum('sous_couvette','changement_nom','achats_cle','redevence','livraison_domicile','collection') NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `methode_payment` enum('wallet','cash','cheque','carte_credits') DEFAULT NULL,
  `type_wallet` enum('wafi','cac-pay','d-money','sab-pay') DEFAULT NULL,
  `numero_wallet` varchar(255) DEFAULT NULL,
  `numero_cheque` varchar(50) DEFAULT NULL,
  `nom_banque` varchar(100) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `details_paiements`
--

INSERT INTO `details_paiements` (`id`, `paiement_id`, `categorie`, `montant`, `methode_payment`, `type_wallet`, `numero_wallet`, `numero_cheque`, `nom_banque`, `reference`) VALUES
(1, 1, 'changement_nom', 20.25, 'cheque', 'wafi', '', '12345785', 'salam', 'ref12345'),
(2, 1, 'collection', 15000.00, 'wallet', 'wafi', '252123456', NULL, NULL, 'REF-20240205'),
(3, 1, 'collection', 15000.00, 'wallet', 'wafi', '252123456', NULL, NULL, 'REF-20240205'),
(4, 2, 'collection', 15000.00, 'wallet', 'wafi', '252123456', NULL, NULL, 'REF-20240205'),
(5, 2, 'collection', 15000.00, 'wallet', 'wafi', '252123456', NULL, NULL, 'REF-20240205'),
(6, 2, 'collection', 15000.00, 'wallet', 'wafi', '252123456', NULL, NULL, 'REF-20240205'),
(7, 2, 'collection', 15000.00, 'wallet', 'wafi', '252123456', NULL, NULL, 'REF-20240205');

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `type` enum('particulier','société') NOT NULL,
  `patente_quitance` longblob DEFAULT NULL,
  `identite_gerant` longblob DEFAULT NULL,
  `abonnement_unique` longblob DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_client` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `documents`
--

INSERT INTO `documents` (`id`, `type`, `patente_quitance`, `identite_gerant`, `abonnement_unique`, `created_at`, `id_client`) VALUES
(1, 'société', 0x2f75706c6f6164732f6964656e746974652d676572616e742d78797a2e706466, 0x2f75706c6f6164732f6964656e746974652d676572616e742d78797a2e706466, 0x2f75706c6f6164732f61626f6e6e656d656e742d756e697175652d78797a2e706466, '2024-12-11 07:26:29', 14),
(2, 'société', 0x2f75706c6f6164732f6964656e746974652d676572616e742d78797a2e706466, 0x2f54c3a96cc3a96368617267656d656e74732f466172646f757373615f4d6f68616d65645f46617261685f43562e646f6378, 0x2f75706c6f6164732f61626f6e6e656d656e742d756e697175652d78797a2e706466, '2024-12-11 07:29:19', 15),
(3, 'société', 0x2f75706c6f6164732f6964656e746974652d676572616e742d78797a2e706466, 0x2f54c3a96cc3a96368617267656d656e74732f466172646f757373615f4d6f68616d65645f46617261685f43562e646f6378, 0x2f75706c6f6164732f61626f6e6e656d656e742d756e697175652d78797a2e706466, '2024-12-11 07:34:42', 16),
(4, 'particulier', NULL, 0x6964656e746974652e706466, 0x636f6e747261745f61626f6e6e656d656e742e706466, '2024-12-16 06:31:04', 20),
(5, 'particulier', NULL, 0x6964656e746974652e706466, 0x636f6e747261745f61626f6e6e656d656e742e706466, '2024-12-16 06:36:10', 21),
(6, 'particulier', NULL, 0x6964656e746974652e706466, 0x636f6e747261745f61626f6e6e656d656e742e706466, '2024-12-16 06:44:02', 22),
(7, 'particulier', NULL, 0x6964656e746974652e706466, 0x636f6e747261745f61626f6e6e656d656e742e706466, '2024-12-16 07:05:20', 24),
(8, 'particulier', NULL, 0x6964656e746974652e706466, 0x636f6e747261745f61626f6e6e656d656e742e706466, '2024-12-16 11:37:56', 25),
(9, 'particulier', NULL, 0x6964656e746974652e706466, 0x636f6e747261745f61626f6e6e656d656e742e706466, '2024-12-17 11:53:35', 26),
(10, 'particulier', NULL, 0x6964656e746974652e706466, 0x636f6e747261745f61626f6e6e656d656e742e706466, '2024-12-17 11:57:20', 27),
(11, 'particulier', NULL, 0x6964656e746974652e706466, 0x636f6e747261745f61626f6e6e656d656e742e706466, '2024-12-18 06:31:00', 29),
(12, 'particulier', NULL, 0x6964656e746974652e706466, 0x636f6e747261745f61626f6e6e656d656e742e706466, '2024-12-18 06:37:17', 30),
(13, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2024-12-29 11:47:02', 34),
(14, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2024-12-29 12:09:35', 36),
(15, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2024-12-30 05:48:05', 37),
(16, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2024-12-30 05:52:26', 38),
(17, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2024-12-30 05:54:33', 40),
(18, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2024-12-30 05:58:14', 41),
(19, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2024-12-30 06:01:46', 42),
(20, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2024-12-30 06:03:36', 43),
(21, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2024-12-30 06:08:32', 44),
(22, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2024-12-30 06:34:51', 45),
(23, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2024-12-30 06:58:29', 46),
(24, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2024-12-30 12:28:11', 47),
(25, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2024-12-31 06:03:18', 48),
(26, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2025-01-02 05:58:47', 50),
(27, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2025-01-02 06:00:11', 52),
(28, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2025-01-02 06:10:19', 53),
(29, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2025-01-02 06:14:01', 54),
(31, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2025-01-05 08:25:46', 56),
(32, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2025-01-05 08:27:49', 57),
(34, 'particulier', NULL, 0x646f63756d656e745f676572616e742e706466, 0x646f63756d656e745f61626f6e6e656d656e742e706466, '2025-01-05 08:30:00', 59),
(35, '', 0x706174656e74653132332e706466, 0x6964656e746974653132332e706466, 0x61626f6e6e656d656e743132332e706466, '2025-01-14 05:41:54', 61),
(36, '', 0x706174656e74653132332e706466, 0x6964656e746974653132332e706466, 0x61626f6e6e656d656e743132332e706466, '2025-01-14 05:52:39', 62),
(37, '', 0x706174656e74653132332e706466, 0x6964656e746974653132332e706466, 0x61626f6e6e656d656e743132332e706466, '2025-01-14 06:00:27', 63),
(38, '', 0x706174656e74653132332e706466, 0x6964656e746974653132332e706466, 0x61626f6e6e656d656e743132332e706466, '2025-01-14 06:07:31', 64),
(39, '', 0x706174656e74653132332e706466, 0x6964656e746974653132332e706466, 0x61626f6e6e656d656e743132332e706466, '2025-01-14 08:36:19', 65),
(40, '', 0x706174656e74653132332e706466, 0x6964656e746974653132332e706466, 0x61626f6e6e656d656e743132332e706466, '2025-01-14 08:41:41', 66);

-- --------------------------------------------------------

--
-- Structure de la table `exaunore`
--

CREATE TABLE `exaunore` (
  `id` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `date_exaunoré` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `livraison_a_domicile`
--

CREATE TABLE `livraison_a_domicile` (
  `id` int(11) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `id_client` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

CREATE TABLE `paiements` (
  `id` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `type` enum('mis_a_jour','non_mis_a_jour') NOT NULL,
  `penalites` decimal(10,2) DEFAULT 0.00,
  `montant_redevence` decimal(10,2) NOT NULL,
  `methode_payment` enum('wallet','cash','cheque','carte_credits') DEFAULT NULL,
  `reference_general` varchar(255) DEFAULT NULL,
  `date_paiement` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `paiements`
--

INSERT INTO `paiements` (`id`, `id_client`, `type`, `penalites`, `montant_redevence`, `methode_payment`, `reference_general`, `date_paiement`) VALUES
(1, 2, 'mis_a_jour', 0.00, 0.00, 'wallet', 'REF123456', '2025-02-04 10:37:52'),
(2, 3, 'mis_a_jour', 0.00, 50.00, 'wallet', 'ref1245p', '2025-02-05 12:15:37');

-- --------------------------------------------------------

--
-- Structure de la table `resilies`
--

CREATE TABLE `resilies` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_client` int(11) DEFAULT NULL,
  `date_resiliation` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `resilies`
--

INSERT INTO `resilies` (`id`, `id_user`, `id_client`, `date_resiliation`) VALUES
(1, 1, 3, '2024-12-03');

-- --------------------------------------------------------

--
-- Structure de la table `sous_couvete`
--

CREATE TABLE `sous_couvete` (
  `id` int(11) NOT NULL,
  `nom_societe` varchar(255) DEFAULT NULL,
  `nom_personne` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `adresse` text NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('responsable','supersiveur','agent_commerciale','agent_guichets','agent_comptable','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `email`, `password`, `role`) VALUES
(1, 'test', 'test@gmail.com', '12345', 'responsable'),
(2, 'sadik', 'sadik@gmail.com', '123456789', 'agent_commerciale'),
(4, 'John Doe', 'johndoe@example.com', '$2y$10$JNmTlVsk3cBgjuWwcBBaLONQY5UGBUtl4oWxHZrj453uhFuVGlzMW', 'responsable');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `abonnement`
--
ALTER TABLE `abonnement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_boite_postale` (`id_boite_postale`),
  ADD KEY `id_payments` (`id_payments`);

--
-- Index pour la table `boites_postales`
--
ALTER TABLE `boites_postales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero` (`numero`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_boite_postale` (`id_boite_postale`),
  ADD KEY `update_by` (`update_by`);

--
-- Index pour la table `collection`
--
ALTER TABLE `collection`
  ADD KEY `collection_ibfk_1` (`id_client`);

--
-- Index pour la table `depot`
--
ALTER TABLE `depot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_client` (`id_client`);

--
-- Index pour la table `details_paiements`
--
ALTER TABLE `details_paiements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `details_paiements_ibfk_1` (`paiement_id`);

--
-- Index pour la table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_client` (`id_client`);

--
-- Index pour la table `exaunore`
--
ALTER TABLE `exaunore`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_client` (`id_client`);

--
-- Index pour la table `livraison_a_domicile`
--
ALTER TABLE `livraison_a_domicile`
  ADD KEY `livraison_a_domicile_ibfk_1` (`id_client`);

--
-- Index pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paiements_ibfk_1` (`id_client`);

--
-- Index pour la table `resilies`
--
ALTER TABLE `resilies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_client` (`id_client`);

--
-- Index pour la table `sous_couvete`
--
ALTER TABLE `sous_couvete`
  ADD KEY `sous_couvete_ibfk_1` (`id_client`),
  ADD KEY `sous_couvete_ibfk_2` (`id_user`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `abonnement`
--
ALTER TABLE `abonnement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `boites_postales`
--
ALTER TABLE `boites_postales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT pour la table `depot`
--
ALTER TABLE `depot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `details_paiements`
--
ALTER TABLE `details_paiements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `exaunore`
--
ALTER TABLE `exaunore`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `paiements`
--
ALTER TABLE `paiements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `resilies`
--
ALTER TABLE `resilies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `abonnement`
--
ALTER TABLE `abonnement`
  ADD CONSTRAINT `abonnement_ibfk_1` FOREIGN KEY (`id_boite_postale`) REFERENCES `boites_postales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `clients_ibfk_2` FOREIGN KEY (`id_boite_postale`) REFERENCES `boites_postales` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `clients_ibfk_3` FOREIGN KEY (`update_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `collection`
--
ALTER TABLE `collection`
  ADD CONSTRAINT `collection_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `depot`
--
ALTER TABLE `depot`
  ADD CONSTRAINT `depot_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `details_paiements`
--
ALTER TABLE `details_paiements`
  ADD CONSTRAINT `details_paiements_ibfk_1` FOREIGN KEY (`paiement_id`) REFERENCES `paiements` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `exaunore`
--
ALTER TABLE `exaunore`
  ADD CONSTRAINT `exaunore_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `livraison_a_domicile`
--
ALTER TABLE `livraison_a_domicile`
  ADD CONSTRAINT `livraison_a_domicile_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD CONSTRAINT `paiements_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `resilies`
--
ALTER TABLE `resilies`
  ADD CONSTRAINT `resilies_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `resilies_ibfk_2` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `sous_couvete`
--
ALTER TABLE `sous_couvete`
  ADD CONSTRAINT `sous_couvete_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sous_couvete_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
