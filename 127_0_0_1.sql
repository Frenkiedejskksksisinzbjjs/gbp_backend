-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 14 jan. 2025 à 13:16
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
CREATE DATABASE IF NOT EXISTS `gbp` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `gbp`;

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
(1, 5, '2024', 3),
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
(35, 51, '2025', 57);

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
  `id_boite_postale` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `collection`
--

INSERT INTO `collection` (`id`, `adresse`, `created_at`, `updated_at`, `id_boite_postale`) VALUES
(1, '123 rue exemple', '2024-12-24 06:44:06', '2024-12-24 06:44:06', 2),
(2, 'Avenue Centrale', '2024-12-24 06:46:38', '2024-12-24 06:46:38', 18),
(3, '123 Rue de Paris', '2024-12-31 06:03:18', '2024-12-31 06:03:18', 37),
(4, '123 Rue de Paris', '2025-01-02 05:58:47', '2025-01-02 05:58:47', 38),
(5, '123 Rue de Paris', '2025-01-02 06:10:19', '2025-01-02 06:10:19', 40),
(6, '123 Rue de Paris', '2025-01-02 06:14:02', '2025-01-02 06:14:02', 41),
(8, '123 Rue de Paris', '2025-01-05 08:25:46', '2025-01-05 08:25:46', 42),
(9, '123 Rue de Paris', '2025-01-05 08:27:49', '2025-01-05 08:27:49', 43),
(10, '123 Rue de Paris', '2025-01-05 08:30:00', '2025-01-05 08:30:00', 44),
(11, '123 Rue Principale', '2025-01-07 08:08:44', '2025-01-07 08:08:44', 26),
(12, '123 Rue Principale', '2025-01-07 08:10:07', '2025-01-07 08:10:07', 26),
(13, '123 Rue Principale', '2025-01-07 08:14:05', '2025-01-07 08:14:05', 26),
(14, '123 Rue Principale', '2025-01-07 08:14:27', '2025-01-07 08:14:27', 26),
(15, '123 Rue Principale', '2025-01-07 08:15:40', '2025-01-07 08:15:40', 26),
(16, '123 Rue Principale', '2025-01-07 08:21:07', '2025-01-07 08:21:07', 26),
(17, '123 Rue Principale', '2025-01-07 08:25:47', '2025-01-07 08:25:47', 26),
(18, '123 Rue Principale', '2025-01-07 08:26:01', '2025-01-07 08:26:01', 26),
(19, '123 Rue Principale', '2025-01-07 08:26:42', '2025-01-07 08:26:42', 26),
(20, '123 Rue Principale', '2025-01-07 08:27:48', '2025-01-07 08:27:48', 26),
(21, '123 Rue Principale', '2025-01-07 08:28:08', '2025-01-07 08:28:08', 26),
(22, '123 Rue Principale', '2025-01-07 08:28:34', '2025-01-07 08:28:34', 26),
(23, '123 Rue Principale', '2025-01-07 08:28:41', '2025-01-07 08:28:41', 26),
(24, '123 Rue Principale', '2025-01-07 08:28:43', '2025-01-07 08:28:43', 26),
(25, '123 Rue Principale', '2025-01-07 08:28:46', '2025-01-07 08:28:46', 26),
(26, '123 Rue Principale', '2025-01-07 08:33:22', '2025-01-07 08:33:22', 26),
(27, '123 Rue Principale', '2025-01-07 08:33:41', '2025-01-07 08:33:41', 26),
(28, '123 Rue de Exemple, Ville, Pays', '2025-01-12 08:35:20', '2025-01-12 08:35:20', 42),
(29, '123 Rue de Exemple, Ville, Pays', '2025-01-12 09:15:18', '2025-01-12 09:15:18', 43),
(30, '789 Boulevard Exemple, Ville, Pays', '2025-01-14 05:41:54', '2025-01-14 05:41:54', 46),
(31, '789 Boulevard Exemple, Ville, Pays', '2025-01-14 05:52:39', '2025-01-14 05:52:39', 47),
(32, '789 Boulevard Exemple, Ville, Pays', '2025-01-14 06:00:27', '2025-01-14 06:00:27', 48),
(33, '789 Boulevard Exemple, Ville, Pays', '2025-01-14 06:07:31', '2025-01-14 06:07:31', 49),
(34, '789 Boulevard Exemple, Ville, Pays', '2025-01-14 08:36:19', '2025-01-14 08:36:19', 50),
(35, '789 Boulevard Exemple, Ville, Pays', '2025-01-14 08:41:41', '2025-01-14 08:41:41', 51);

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `type` enum('particulier','société') NOT NULL,
  `patente_quitance` longblob DEFAULT NULL,
  `identite_gerant` longblob NOT NULL,
  `abonnement_unique` longblob NOT NULL,
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
-- Structure de la table `livraison_a_domicile`
--

CREATE TABLE `livraison_a_domicile` (
  `id` int(11) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `id_boite_postale` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `livraison_a_domicile`
--

INSERT INTO `livraison_a_domicile` (`id`, `adresse`, `id_boite_postale`, `created_at`, `updated_at`) VALUES
(1, '123 rue exemple', 10, '2024-12-22 08:44:33', '2024-12-22 08:44:33'),
(2, '123 rue exemple', 22, '2024-12-22 08:47:47', '2024-12-22 08:47:47'),
(3, '123 rue exemple', 20, '2024-12-22 08:52:27', '2024-12-22 08:52:27'),
(4, '123 rue exemple', 20, '2024-12-22 08:53:27', '2024-12-22 08:53:27'),
(5, '123 rue exemple', 2, '2024-12-22 11:57:56', '2024-12-22 11:57:56'),
(6, '456 Rue de la Poste', 26, '2024-12-29 12:09:35', '2024-12-29 12:09:35'),
(7, '123 Rue de Paris, 75001 Paris', 27, '2024-12-30 05:48:05', '2024-12-30 05:48:05'),
(8, '123 Rue de Paris, 75001 Paris', 28, '2024-12-30 05:52:26', '2024-12-30 05:52:26'),
(9, '123 Rue de Paris, 75001 Paris', 29, '2024-12-30 05:54:34', '2024-12-30 05:54:34'),
(10, '123 Rue de Paris, 75001 Paris', 30, '2024-12-30 05:58:14', '2024-12-30 05:58:14'),
(11, '123 Rue de Paris, 75001 Paris', 31, '2024-12-30 06:01:46', '2024-12-30 06:01:46'),
(12, '123 Rue de Paris, 75001 Paris', 32, '2024-12-30 06:03:36', '2024-12-30 06:03:36'),
(13, '123 Rue de Paris, 75001 Paris', 33, '2024-12-30 06:08:33', '2024-12-30 06:08:33'),
(14, '123 Rue de Paris, 75001 Paris', 34, '2024-12-30 06:34:51', '2024-12-30 06:34:51'),
(15, '123 Rue de Paris, 75001 Paris', 37, '2024-12-31 06:03:18', '2024-12-31 06:03:18'),
(16, '123 Rue de Paris, 75001 Paris', 38, '2025-01-02 05:58:47', '2025-01-02 05:58:47'),
(17, '123 Rue de Paris, 75001 Paris', 40, '2025-01-02 06:10:19', '2025-01-02 06:10:19'),
(18, '56 Place de la Gare', 26, '2025-01-07 08:17:51', '2025-01-07 08:17:51'),
(19, '56 Place de la Gare', 26, '2025-01-07 08:20:32', '2025-01-07 08:20:32'),
(24, '123 Rue Exemple', 42, '2025-01-07 08:45:24', '2025-01-07 08:45:24'),
(25, '123 Rue Exemple, Ville, Code Postal', 43, '2025-01-12 08:11:32', '2025-01-12 08:11:32'),
(28, '123 Rue Exemple, Ville, Code Postal', 42, '2025-01-12 08:16:35', '2025-01-12 08:16:35'),
(31, '123 Rue Exemple, Ville, Code Postal', 42, '2025-01-12 08:22:06', '2025-01-12 08:22:06'),
(32, '123 Rue Exemple, Ville, Code Postal', 42, '2025-01-12 08:24:34', '2025-01-12 08:24:34'),
(33, '456 Avenue Exemple, Ville, Pays', 46, '2025-01-14 05:41:54', '2025-01-14 05:41:54'),
(34, '456 Avenue Exemple, Ville, Pays', 47, '2025-01-14 05:52:39', '2025-01-14 05:52:39'),
(35, '456 Avenue Exemple, Ville, Pays', 48, '2025-01-14 06:00:27', '2025-01-14 06:00:27'),
(36, '456 Avenue Exemple, Ville, Pays', 49, '2025-01-14 06:07:31', '2025-01-14 06:07:31'),
(37, '456 Avenue Exemple, Ville, Pays', 50, '2025-01-14 08:36:19', '2025-01-14 08:36:19'),
(38, '456 Avenue Exemple, Ville, Pays', 51, '2025-01-14 08:41:41', '2025-01-14 08:41:41');

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

CREATE TABLE `paiements` (
  `id` int(11) NOT NULL,
  `id_client` int(11) DEFAULT NULL,
  `type` enum('mis_a_jour','non_mis_a_jour') NOT NULL,
  `penalites` decimal(10,2) DEFAULT 0.00,
  `montant_sous_couvete` decimal(10,2) DEFAULT 0.00,
  `montant_changement_nom` decimal(10,2) DEFAULT 0.00,
  `montant_achats_cle` decimal(10,2) DEFAULT 0.00,
  `montant_redevence` decimal(10,2) DEFAULT 0.00,
  `methode_payment` enum('wallet','cash','cheque','carte_credits') DEFAULT NULL,
  `domicile` text DEFAULT NULL,
  `type_wallet` enum('wafi','cac-pay','d-money','sab-pay') DEFAULT NULL,
  `methode_payment_nom` enum('wallet','cash','cheque','carte_credits') DEFAULT NULL,
  `methode_payment_cle` enum('wallet','cash','cheque','carte_credits') DEFAULT NULL,
  `methode_payment_couvette` enum('wallet','cash','cheque','carte_credits') DEFAULT NULL,
  `type_wallet_nom` enum('wafi','cac-pay','d-money','sab-pay') DEFAULT NULL,
  `type_wallet_cle` enum('wafi','cac-pay','d-money','sab-pay') DEFAULT NULL,
  `type_wallet_couvette` enum('wafi','cac-pay','d-money','sab-pay') DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `reference_ajout_sous_couvette` varchar(255) DEFAULT NULL,
  `reference_livraison_domicile` varchar(255) DEFAULT NULL,
  `reference_changer_nom` varchar(255) DEFAULT NULL,
  `reference_achat_cle` varchar(255) DEFAULT NULL,
  `reference_ajout_collection` varchar(255) DEFAULT NULL,
  `numero_cheque` varchar(50) DEFAULT NULL,
  `nom_banque` varchar(100) DEFAULT NULL,
  `montant_livraison_a_domicile` decimal(10,2) DEFAULT NULL,
  `montant_collection` decimal(10,2) NOT NULL,
  `methode_paiement_a_domicile` enum('wallet','cash','cheque','carte_credits') DEFAULT NULL,
  `methode_paiement_collection` enum('wallet','cash','cheque','carte_credits') DEFAULT NULL,
  `type_wallet_collection` enum('wafi','cac-pay','d-money','sab-pay') DEFAULT NULL,
  `numero_cheque_collection` varchar(50) DEFAULT NULL,
  `nom_banque_collection` varchar(255) DEFAULT NULL,
  `type_wallet_livraison_a_domicile` enum('wafi','cac-pay','d-money','sab-pay') DEFAULT NULL,
  `reference_livraison_a_domicile` varchar(255) DEFAULT NULL,
  `numero_cheque_livraison_a_domicile` varchar(50) DEFAULT NULL,
  `nom_banque_livraison_a_domicile` varchar(255) DEFAULT NULL,
  `numero_cheque_sous_couvette` varchar(255) DEFAULT NULL,
  `nom_banque_sous_couvette` varchar(255) DEFAULT NULL,
  `numero_cheque_achat_cle` varchar(255) DEFAULT NULL,
  `nom_banque_achat_cle` varchar(255) DEFAULT NULL,
  `numero_cheque_changment_nom` varchar(255) DEFAULT NULL,
  `nom_banque_changment_nom` varchar(255) DEFAULT NULL,
  `numero_wallet_achat_cle` varchar(255) DEFAULT NULL,
  `numero_wallet_changement_nom` varchar(255) DEFAULT NULL,
  `numero_wallet_ajout_sous_couvette` varchar(255) DEFAULT NULL,
  `numero_wallet_livraison_domicile` varchar(255) DEFAULT NULL,
  `numero_wallet_collection` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `paiements`
--

INSERT INTO `paiements` (`id`, `id_client`, `type`, `penalites`, `montant_sous_couvete`, `montant_changement_nom`, `montant_achats_cle`, `montant_redevence`, `methode_payment`, `domicile`, `type_wallet`, `methode_payment_nom`, `methode_payment_cle`, `methode_payment_couvette`, `type_wallet_nom`, `type_wallet_cle`, `type_wallet_couvette`, `reference`, `reference_ajout_sous_couvette`, `reference_livraison_domicile`, `reference_changer_nom`, `reference_achat_cle`, `reference_ajout_collection`, `numero_cheque`, `nom_banque`, `montant_livraison_a_domicile`, `montant_collection`, `methode_paiement_a_domicile`, `methode_paiement_collection`, `type_wallet_collection`, `numero_cheque_collection`, `nom_banque_collection`, `type_wallet_livraison_a_domicile`, `reference_livraison_a_domicile`, `numero_cheque_livraison_a_domicile`, `nom_banque_livraison_a_domicile`, `numero_cheque_sous_couvette`, `nom_banque_sous_couvette`, `numero_cheque_achat_cle`, `nom_banque_achat_cle`, `numero_cheque_changment_nom`, `nom_banque_changment_nom`, `numero_wallet_achat_cle`, `numero_wallet_changement_nom`, `numero_wallet_ajout_sous_couvette`, `numero_wallet_livraison_domicile`, `numero_wallet_collection`) VALUES
(3, 5, 'mis_a_jour', 0.00, 5000.00, 23000.00, 51000.00, 0.00, 'cash', NULL, NULL, 'cheque', 'cheque', 'wallet', NULL, NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 'wallet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CHQ123456', 'Banque Nationale', '123456789', 'Banque Nationale', NULL, NULL, NULL, NULL, NULL),
(13, 3, 'mis_a_jour', 0.00, 0.00, 8000.00, 15000.00, 5000.00, 'cash', NULL, NULL, 'wallet', 'cash', NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1500.00, 1500.00, 'cash', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 2, '', 0.00, 5000.00, 0.00, 0.00, 0.00, 'wallet', NULL, NULL, NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 'wallet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '58659', 'salam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 24, 'mis_a_jour', 0.00, 0.00, 0.00, 0.00, 1000.52, 'wallet', NULL, 'sab-pay', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 'wallet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 25, 'mis_a_jour', 0.00, 16000.02, 0.00, 0.00, 1000.52, 'wallet', NULL, 'sab-pay', NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15000.00, NULL, 'cheque', NULL, 'CHQ202312345', 'Banque Internationale', NULL, NULL, NULL, NULL, '7845666', 'wafinhos', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 26, 'mis_a_jour', 0.00, 5000.00, 0.00, 0.00, 1000.52, 'wallet', NULL, 'sab-pay', NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 'wallet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '58659', 'salam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 27, 'mis_a_jour', 0.00, 5000.00, 0.00, 0.00, 1000.52, 'wallet', NULL, 'sab-pay', NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1500.00, 0.00, 'cash', 'wallet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '58659', 'salam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 29, 'mis_a_jour', 0.00, 15000.00, 0.00, 0.00, 1000.52, 'cheque', NULL, NULL, NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cheque', 'cheque', NULL, 0.00, NULL, 'wallet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '58659', 'salam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 30, 'mis_a_jour', 0.00, 15000.00, 0.00, 0.00, 1000.52, 'cheque', NULL, NULL, NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '123456789', 'Société Générale', 1500.00, 0.00, 'cheque', 'wallet', NULL, NULL, NULL, NULL, NULL, '123456', 'Banque XYZ', '7845666', 'wafinhos', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 31, 'mis_a_jour', 0.00, 5000.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '58659', 'salam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 34, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 36, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, 'ahmed123', '987654321', 'Banque Centrale', 7500.00, 300.00, 'carte_credits', 'wallet', 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 37, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 38, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 40, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 41, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 42, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 43, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 44, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 45, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', 15.50, 0.00, 'wallet', NULL, NULL, NULL, NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 46, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 47, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 48, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', 15.50, 15.50, 'wallet', 'wallet', 'wafi', NULL, NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 50, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', 15.50, 15.50, 'wallet', 'wallet', 'wafi', NULL, NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 52, 'mis_a_jour', 0.00, 0.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ggggggggggg585588h', NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 53, 'mis_a_jour', 0.00, 0.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', 15.50, 15.50, 'wallet', 'wallet', 'wafi', NULL, NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 54, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 15.50, NULL, 'wallet', 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 56, 'mis_a_jour', 0.00, 15075.00, 4219.50, 7500.00, 150.00, 'cheque', NULL, NULL, 'wallet', 'wallet', 'cheque', 'wafi', 'wafi', NULL, NULL, 'REF12345', 'REF1234', 'REF12345', 'REF12345', 'REF123456', '987654321', 'Banque Centrale', 7902.00, 1016.00, 'wallet', 'wallet', 'wafi', NULL, NULL, 'wafi', NULL, NULL, NULL, 'CHQ12345', 'Nom Banque', NULL, NULL, NULL, NULL, '1234567890', 'WALLET12345', NULL, '1234567890', '1234567890'),
(47, 57, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, 'REF1234', NULL, 'moham123', 'REF123456', '987654321', 'Banque Centrale', NULL, 1016.00, 'wallet', 'wallet', 'wafi', NULL, NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1234567890'),
(52, 61, 'mis_a_jour', 0.00, 20.00, 0.00, 0.00, 50.00, 'wallet', NULL, '', NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10.00, 15.00, 'wallet', 'cheque', NULL, 'CH67890', 'Banque Collection', '', NULL, NULL, NULL, 'CH12345', 'Banque Exemple', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(53, 62, 'mis_a_jour', 0.00, 20.00, 0.00, 0.00, 50.00, 'wallet', NULL, '', NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, 'RF12345', NULL, NULL, NULL, NULL, NULL, NULL, 10.00, 15.00, 'wallet', 'cheque', NULL, 'CH67890', 'Banque Collection', '', NULL, NULL, NULL, 'CH12345', 'Banque Exemple', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(54, 63, 'mis_a_jour', 0.00, 20.00, 0.00, 0.00, 50.00, 'wallet', NULL, '', NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, 'RF12345', 'RF77777', NULL, NULL, NULL, NULL, NULL, 10.00, 15.00, 'wallet', 'cheque', NULL, 'CH67890', 'Banque Collection', '', NULL, NULL, NULL, 'CH12345', 'Banque Exemple', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(55, 64, 'mis_a_jour', 0.00, 20.00, 0.00, 0.00, 50.00, 'wallet', NULL, '', NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, 'RF12345', 'RF77777', NULL, NULL, 'RF77420616', NULL, NULL, 10.00, 15.00, 'wallet', 'cheque', NULL, 'CH67890', 'Banque Collection', '', NULL, NULL, NULL, 'CH12345', 'Banque Exemple', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(56, 65, 'mis_a_jour', 0.00, 20.00, 0.00, 0.00, 50.00, 'wallet', NULL, '', NULL, NULL, 'wallet', NULL, NULL, 'wafi', NULL, 'RF12345', 'RF77777', NULL, NULL, 'RF77420616', NULL, NULL, 10.00, 15.00, 'wallet', 'cheque', NULL, 'CH67890', 'Banque Collection', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '12345jiji', NULL, NULL),
(57, 66, 'mis_a_jour', 0.00, 20.00, 0.00, 0.00, 50.00, 'wallet', NULL, '', NULL, NULL, 'wallet', NULL, NULL, 'wafi', NULL, 'RF12345', 'RF77777', NULL, NULL, 'RF77420616', NULL, NULL, 10.00, 15.00, 'wallet', 'cheque', NULL, 'CH67890', 'Banque Collection', 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '12345jiji', '456897', NULL);

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
  `id_boite_postale` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `sous_couvete`
--

INSERT INTO `sous_couvete` (`id`, `nom_societe`, `nom_personne`, `telephone`, `adresse`, `id_boite_postale`, `id_user`) VALUES
(1, 'societé', 'jakwar', '7775865', 'test', 5, NULL),
(2, 'societé', 'test', '7775865', 'test', 5, NULL),
(3, 'societé', 'jakwar', '0123456789', '123 Rue Exemple', 6, 1),
(4, 'societé', 'frenkie', '0123456789', '123 Rue Exemple', 6, 1),
(6, 'societé', 'iniesta', '0123456789', '123 Rue Exemple', 6, 1),
(7, 'societé', 'xavi', '0123456789', '123 Rue Exemple', 6, 1),
(9, 'XYZ Ltd', 'Jane Doe', '987654321', '456 Elm St', 2, 2),
(10, 'XYZ Ltd', 'Jane Doe', '987654321', '456 Elm St', 2, 2),
(14, 'Entreprise XYZ', 'Alice Dupont', '123456789', '456 Avenue Exemple', 3, NULL),
(16, 'Entreprise XYZ', 'Alice Dupont', '123456789', '456 Avenue Exemple', 3, NULL),
(18, 'Entreprise XYZ', 'Alice Dupont', '123456789', '456 Avenue Exemple', 3, NULL),
(19, 'Entreprise XYZ', 'Alice Dupont', '123456789', '456 Avenue Exemple', 3, NULL),
(20, 'Entreprise XYZ', 'Alice Dupont', '123456789', '456 Avenue Exemple', 3, NULL),
(22, 'Entreprise XYZ', 'Alice Dupont', '123456789', '456 Avenue Exemple', 2, NULL),
(27, 'Ma Société', 'Jean Dupont', '123456789', '123 Rue Exemple', 2, 2),
(28, 'Ma Société', 'Jean Dupont', '123456789', '123 Rue Exemple', 2, 2),
(29, 'Ma Société', 'Jean Dupont', '123456789', '123 Rue Exemple', 5, 2),
(30, 'Ma Société', 'Jean Dupont', '123456789', '123 Rue Exemple', 5, 2),
(31, NULL, 'Marie Dupont', '0612345678', '123 Rue Principale', 20, 1),
(32, NULL, 'Marie Dupont', '0612345678', '123 Rue Principale', 21, 1),
(33, NULL, 'Marie Dupont', '0612345678', '123 Rue Principale', 22, 1),
(35, 'Exemple Société', 'Jean Dupont', '123456789', '123 Rue Exemple', 1, 1),
(36, 'Exemple Société', 'Jean Dupont', '123456789', '123 Rue Exemple', 1, 1),
(37, 'Exemple Société', 'Jean Dupont', '123456789', '123 Rue Exemple', 1, 1),
(38, 'Exemple Société', 'Jean Dupont', '123456789', '123 Rue Exemple', 1, 1),
(39, 'Exemple Société', 'Jean Dupont', '123456789', '123 Rue Exemple', 22, 1),
(40, 'Exemple Société', 'Jean Dupont', '123456789', '123 Rue Exemple', 22, 1),
(41, 'Exemple Société', 'Jean Dupont', '123456789', '123 Rue Exemple', 22, 1),
(42, 'Exemple Société', 'Jean Dupont', '123456789', '123 Rue Exemple', 22, 1),
(43, 'Exemple Société', 'Jean Dupont', '123456789', '123 Rue Exemple', 20, 1),
(44, 'Exemple Société', 'Jean Dupont', '123456789', '123 Rue Exemple', 20, 1),
(45, 'tir Société', 'Jeanenne Dupont', '123456789', '123 Rue Exemple', 20, 1),
(46, 'Societe XYZ', 'John Doe', '1234567890', '123 Rue Exemple, Ville, Pays', 23, 1),
(47, 'Societe XYZ', 'John Doe', '1234567890', '123 Rue Exemple, Ville, Pays', 23, 1),
(48, 'Societe XYZ', 'John Doe', '1234567890', '123 Rue Exemple, Ville, Pays', 23, 1),
(49, 'Societe XYZ', 'John Doe', '1234567890', '123 Rue Exemple, Ville, Pays', 23, 1),
(50, 'Societe XYZ', 'John Doe', '1234567890', '123 Rue Exemple, Ville, Pays', 23, 1),
(51, 'Societe XYZ', 'John Doe', '1234567890', '123 Rue Exemple, Ville, Pays', 14, 1),
(52, 'Societe XYZ', 'John Doe', '1234567890', '123 Rue Exemple, Ville, Pays', 20, 1),
(53, 'Societe XYZ', 'John Doe', '1234567890', '123 Rue Exemple, Ville, Pays', 19, 1),
(54, 'Societe XYZ', 'John Doe', '1234567890', '123 Rue Exemple, Ville, Pays', 1, 1),
(55, 'test', 'test', '7775865', 'test', 20, 2),
(56, 'Societe XYZ', 'John Doe', '1234567890', '123 Rue Exemple, Ville, Pays', 21, 1),
(57, 'Societe XYZ', 'John Doe', '1234567890', '123 Rue Exemple, Ville, Pays', 21, 1),
(58, 'Societe XYZ', 'John Doe', '1234567890', '123 Rue Exemple, Ville, Pays', 21, 1),
(59, 'Societe XYZ', 'John Doe', '1234567890', '123 Rue Exemple, Ville, Pays', 21, 1),
(60, NULL, 'Marie Curie', '0612345678', '123 Rue Principale', 24, 1),
(61, NULL, 'Marie Curie', '0612345678', '123 Rue Principale', 26, 1),
(62, NULL, 'Marie Curie', '0612345678', '123 Rue Principale', 27, 1),
(63, NULL, 'Marie Curie', '0612345678', '123 Rue Principale', 28, 1),
(64, NULL, 'Marie Curie', '0612345678', '123 Rue Principale', 29, 1),
(65, NULL, 'Marie Curie', '0612345678', '123 Rue Principale', 30, 1),
(66, NULL, 'Marie Curie', '0612345678', '123 Rue Principale', 31, 1),
(67, NULL, 'Marie Curie', '0612345678', '123 Rue Principale', 32, 1),
(68, NULL, 'Marie Curie', '0612345678', '123 Rue Principale', 33, 1),
(69, NULL, 'Marie Curie', '0612345678', '123 Rue Principale', 34, 1),
(70, NULL, 'Marie Curie', '0612345678', '123 Rue Principale', 35, 1),
(71, NULL, 'Marie Curie', '0612345678', '123 Rue Principale', 36, 1),
(72, NULL, 'Marie Curie', '0612345678', '123 Rue Principale', 37, 1),
(73, NULL, 'Marie Curie', '0612345678', '123 Rue Principale', 38, 1),
(74, NULL, 'Marie Curie', '0612345678', '123 Rue Principale', 41, 1),
(76, NULL, 'Marie Curie', '0612345678', '123 Rue Principale', 42, 1),
(77, NULL, 'Marie Curie', '0612345678', '123 Rue Principale', 43, 1),
(78, NULL, 'Marie Curie', '0612345678', '123 Rue Principale', 44, 1),
(79, 'Nom Société', 'Nom Personne', '123456789', 'Adresse complète', 42, 1),
(80, 'Nom Société', 'Nom Personne', '123456789', 'Adresse complète', 42, 1),
(81, 'Nom Société', 'Nom Personne', '123456789', 'Adresse complète', 42, 1),
(82, 'Nom Société', 'Nom Personne', '123456789', 'Adresse complète', 42, 1),
(83, NULL, 'Marie Dupont', '+123456789', '123 Rue Exemple, Ville, Pays', 46, 1),
(84, NULL, 'Marie Dupont', '+123456789', '123 Rue Exemple, Ville, Pays', 47, 1),
(85, NULL, 'Marie Dupont', '+123456789', '123 Rue Exemple, Ville, Pays', 48, 1),
(86, NULL, 'Marie Dupont', '+123456789', '123 Rue Exemple, Ville, Pays', 49, 1),
(87, NULL, 'Marie Dupont', '+123456789', '123 Rue Exemple, Ville, Pays', 50, 1),
(88, NULL, 'Marie Dupont', '+123456789', '123 Rue Exemple, Ville, Pays', 51, 1);

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
(2, 'sadik', 'sadik@gmail.com', '123456789', 'agent_commerciale');

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_boite_postale` (`id_boite_postale`);

--
-- Index pour la table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_client` (`id_client`);

--
-- Index pour la table `livraison_a_domicile`
--
ALTER TABLE `livraison_a_domicile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_boite_postale` (`id_boite_postale`);

--
-- Index pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_client` (`id_client`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_boite_postale` (`id_boite_postale`),
  ADD KEY `id_user` (`id_user`) USING BTREE;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

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
-- AUTO_INCREMENT pour la table `collection`
--
ALTER TABLE `collection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `livraison_a_domicile`
--
ALTER TABLE `livraison_a_domicile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `paiements`
--
ALTER TABLE `paiements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT pour la table `resilies`
--
ALTER TABLE `resilies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `sous_couvete`
--
ALTER TABLE `sous_couvete`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `abonnement`
--
ALTER TABLE `abonnement`
  ADD CONSTRAINT `abonnement_ibfk_1` FOREIGN KEY (`id_boite_postale`) REFERENCES `boites_postales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `abonnement_ibfk_2` FOREIGN KEY (`id_payments`) REFERENCES `paiements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `collection_ibfk_1` FOREIGN KEY (`id_boite_postale`) REFERENCES `boites_postales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `livraison_a_domicile`
--
ALTER TABLE `livraison_a_domicile`
  ADD CONSTRAINT `livraison_a_domicile_ibfk_1` FOREIGN KEY (`id_boite_postale`) REFERENCES `boites_postales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sous_couvete_ibfk_1` FOREIGN KEY (`id_boite_postale`) REFERENCES `boites_postales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
--
-- Base de données : `marie`
--
CREATE DATABASE IF NOT EXISTS `marie` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `marie`;

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `matches`
--

CREATE TABLE `matches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `match_date` date NOT NULL,
  `team_blue` varchar(255) NOT NULL,
  `team_red` varchar(255) NOT NULL,
  `score_blue` int(11) NOT NULL DEFAULT 0,
  `score_red` int(11) NOT NULL DEFAULT 0,
  `man_of_the_match` varchar(255) DEFAULT NULL,
  `stars` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `matches`
--

INSERT INTO `matches` (`id`, `match_date`, `team_blue`, `team_red`, `score_blue`, `score_red`, `man_of_the_match`, `stars`, `created_at`, `updated_at`) VALUES
(2, '2024-12-25', 'EQUIPE BLEU', 'EQUIPE ROUGE', 0, 1, 'MOHAMED GAHANEH', 3, '2025-01-05 03:09:03', '2025-01-05 03:09:03'),
(3, '2024-12-18', 'EQUIPE BLEU', 'EQUIPE ROUGE', 1, 0, 'BOBO', 4, '2025-01-05 03:10:47', '2025-01-05 03:10:47'),
(4, '2024-12-11', 'EXTERIEUR', 'LA POSTE', 0, 3, 'INCONNUE', 0, '2025-01-05 03:12:04', '2025-01-05 03:12:04'),
(5, '2024-11-20', 'EXTERIEUR', 'LA POSTE', 0, 2, 'BOBO', 3, '2025-01-05 03:13:46', '2025-01-05 03:13:46'),
(6, '2024-11-13', 'EQUIPE BLEU', 'EQUIPE ROUGE', 1, 0, 'ALI', 3, '2025-01-05 03:14:41', '2025-01-05 03:14:41'),
(7, '2024-12-11', 'EXTERIEUR', 'LA POSTE', 0, 3, 'INCONNUE', 0, '2025-01-05 03:12:04', '2025-01-05 03:12:04');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_02_065754_create_utilisateurs_table', 2),
(5, '2025_01_02_112328_create_matches_table', 3);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3SVtvADfKKwnSMfYzWZvaPmCLPHNV4MJTLVz5ZcW', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRUZJdzdNd0VKZFpkYU9ZS0gwaGt6Tk1iVlRaVG1CT0ZyQ0k5bWdEYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9nYW1lcyI7fX0=', 1736404181),
('cTjn0diQ1CDSmxkEtXTytDf6w05tPZz2Ovm61Yp0', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiczZINlBwcURjMkxQRHRUYk43VEJGZzhpZGMxM2ZSYTFVaVZhNHloZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1736253127),
('HuUIUMFT8qKHjfwwLBBFfJPcJbUiSiTfI2rVp4Hi', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQ0lwR3hOc3Nsc1RwYm9DNnNRRWMwVk1TNW9ybHpNMGYyYjhaWkdlRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjM6InVybCI7YTowOnt9fQ==', 1736242429),
('jPR8ThnfgXzDpznPF7gx6l75oIAa3R2iu3guVQKX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSzRPMG5Ra2xyVWlTRTdyaEpzZ2RmOVhPOWY5OUwwTG1CTTBueTU0dSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9nYW1lcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1736231790);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `name`, `address`, `phone`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'sadik', 'hayableh', '77420616', 'sadickmohamedfarah@gmail.com', '$2y$12$mgd/GE7j8FVoK3On86QHuehuKtSDLv2naktw3n9iWhmlDgiZSr63e', '2025-01-02 04:13:35', '2025-01-02 04:13:35'),
(2, 'sadik', 'hayableh', '77420616', 'sadickmohamedfarahe@gmail.com', '$2y$12$EBPi0sHkxunZi7MhN52I2.0MBFcfGhru9vm.oN8P3ou6KwW2u1uCG', '2025-01-07 06:16:23', '2025-01-07 06:16:23');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `utilisateurs_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Base de données : `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Structure de la table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Structure de la table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Structure de la table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Structure de la table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Structure de la table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Structure de la table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Structure de la table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Structure de la table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Structure de la table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Structure de la table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Déchargement des données de la table `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"gbp\",\"table\":\"paiements\"},{\"db\":\"gbp\",\"table\":\"boites_postales\"},{\"db\":\"gbp\",\"table\":\"livraison_a_domicile\"},{\"db\":\"super\",\"table\":\"users\"},{\"db\":\"gbp\",\"table\":\"clients\"},{\"db\":\"gbp\",\"table\":\"sous_couvete\"},{\"db\":\"marie\",\"table\":\"utilisateurs\"},{\"db\":\"gbp\",\"table\":\"collection\"},{\"db\":\"marie\",\"table\":\"matches\"},{\"db\":\"marie\",\"table\":\"users\"}]');

-- --------------------------------------------------------

--
-- Structure de la table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Structure de la table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Structure de la table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Structure de la table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Structure de la table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Structure de la table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Structure de la table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Déchargement des données de la table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2025-01-14 05:25:40', '{\"Console\\/Mode\":\"collapse\",\"lang\":\"fr\"}');

-- --------------------------------------------------------

--
-- Structure de la table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Structure de la table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Index pour la table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Index pour la table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Index pour la table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Index pour la table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Index pour la table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Index pour la table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Index pour la table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Index pour la table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Index pour la table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Index pour la table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Index pour la table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Index pour la table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Index pour la table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Index pour la table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Index pour la table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Index pour la table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Index pour la table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Base de données : `super`
--
CREATE DATABASE IF NOT EXISTS `super` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `super`;

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_09_081745_add_usertype_to_users_table', 2);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('XKGruzj47u52i7dBSsOV5QbfxDRftWWQLZXLojSZ', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWFdYME9jNVBFUkpiNVpFOXBMTVdhZjZWb3paV1BDMjFRYUJHaFpGVCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7fQ==', 1736780348);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `usertype` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `usertype`) VALUES
(1, 'sadik', 'sadickmohamedfarah@gmail.com', NULL, '$2y$12$23Kkp0Et7DtScBbKG2fZyOM/XrtsxT8dmsgKC58AALeqz5oIJUiTW', NULL, '2025-01-09 03:53:15', '2025-01-09 03:53:15', 'user'),
(2, 'mohamed', 'sadickmohamedfarahe@gmail.com', NULL, '$2y$12$kDKqQZS/gbhhUlJFVWkdV.IM/7mX895QHOgyfog9zF/hgoA0h5d26', NULL, '2025-01-09 09:05:11', '2025-01-09 09:05:11', 'admin'),
(3, 'gavi', 'gavi@gmail.com', NULL, '$2y$12$T5Mf7y6wnpWrq0iXuY2qi.6k7TwV72.o4H.G8q8t/kkcT7G85CRqi', NULL, '2025-01-13 11:48:15', '2025-01-13 11:48:15', 'admin'),
(4, 'casado', 'casado@gmail.com', NULL, '$2y$12$BPyNSSQvQhTZXi9wQFcgsewh6bdUKN2GRoDvnZfDZCOsr5pf7u4K.', NULL, '2025-01-13 11:50:15', '2025-01-13 11:50:15', 'client'),
(5, 'pedri', 'pedri@gmail.com', NULL, '$2y$12$K8rXWee2owA5meIRSstbveW2MFI682ISYiUmODiw6bf00YBdKDKpm', NULL, '2025-01-13 11:56:21', '2025-01-13 11:56:21', 'admin');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Base de données : `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
