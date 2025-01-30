-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 30 jan. 2025 à 09:06
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
(35, '789 Boulevard Exemple, Ville, Pays', '2025-01-14 08:41:41', '2025-01-14 08:41:41', 51),
(37, '123 Rue Exemple', '2025-01-20 12:46:50', '2025-01-20 12:46:50', 3),
(38, '123 Rue Exemple', '2025-01-20 12:48:44', '2025-01-20 12:48:44', 5),
(39, '123 Rue Exemple', '2025-01-21 05:56:41', '2025-01-21 05:56:41', 5),
(40, '123 Rue Exemple', '2025-01-21 06:00:23', '2025-01-21 06:00:23', 5),
(41, '123 Rue Exemple', '2025-01-21 06:02:00', '2025-01-21 06:02:00', 5);

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
(38, '456 Avenue Exemple, Ville, Pays', 51, '2025-01-14 08:41:41', '2025-01-14 08:41:41'),
(39, '456 Avenue Test', 3, '2025-01-20 12:14:18', '2025-01-20 12:14:18');

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
  `numero_wallet_redevence` varchar(255) DEFAULT NULL,
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

INSERT INTO `paiements` (`id`, `id_client`, `type`, `penalites`, `montant_sous_couvete`, `montant_changement_nom`, `montant_achats_cle`, `montant_redevence`, `methode_payment`, `domicile`, `type_wallet`, `numero_wallet_redevence`, `methode_payment_nom`, `methode_payment_cle`, `methode_payment_couvette`, `type_wallet_nom`, `type_wallet_cle`, `type_wallet_couvette`, `reference`, `reference_ajout_sous_couvette`, `reference_livraison_domicile`, `reference_changer_nom`, `reference_achat_cle`, `reference_ajout_collection`, `numero_cheque`, `nom_banque`, `montant_livraison_a_domicile`, `montant_collection`, `methode_paiement_a_domicile`, `methode_paiement_collection`, `type_wallet_collection`, `numero_cheque_collection`, `nom_banque_collection`, `type_wallet_livraison_a_domicile`, `reference_livraison_a_domicile`, `numero_cheque_livraison_a_domicile`, `nom_banque_livraison_a_domicile`, `numero_cheque_sous_couvette`, `nom_banque_sous_couvette`, `numero_cheque_achat_cle`, `nom_banque_achat_cle`, `numero_cheque_changment_nom`, `nom_banque_changment_nom`, `numero_wallet_achat_cle`, `numero_wallet_changement_nom`, `numero_wallet_ajout_sous_couvette`, `numero_wallet_livraison_domicile`, `numero_wallet_collection`) VALUES
(3, 5, 'non_mis_a_jour', 0.00, 5000.00, 23000.00, 51000.00, 0.00, 'cash', NULL, NULL, NULL, 'cheque', 'cheque', 'wallet', NULL, NULL, 'wafi', NULL, NULL, NULL, 'rf123456789123456', NULL, 'REF54321', NULL, NULL, NULL, 75.50, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CHQ123456', 'Banque Nationale', '123456789', 'Banque Nationale', NULL, NULL, NULL, NULL, ''),
(13, 3, 'mis_a_jour', 0.00, 0.00, 8000.00, 15000.00, 5000.00, 'cash', NULL, NULL, NULL, 'wallet', 'cash', NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1500.00, 1500.00, 'cash', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 2, '', 0.00, 5000.00, 0.00, 0.00, 0.00, 'wallet', NULL, NULL, NULL, NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 'wallet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '58659', 'salam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 24, 'mis_a_jour', 0.00, 0.00, 0.00, 0.00, 1000.52, 'wallet', NULL, 'sab-pay', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 'wallet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 25, 'mis_a_jour', 0.00, 16000.02, 0.00, 0.00, 1000.52, 'wallet', NULL, 'sab-pay', NULL, NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15000.00, NULL, 'cheque', NULL, 'CHQ202312345', 'Banque Internationale', NULL, NULL, NULL, NULL, '7845666', 'wafinhos', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 26, 'mis_a_jour', 0.00, 5000.00, 0.00, 0.00, 1000.52, 'wallet', NULL, 'sab-pay', NULL, NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 'wallet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '58659', 'salam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 27, 'mis_a_jour', 0.00, 5000.00, 0.00, 0.00, 1000.52, 'wallet', NULL, 'sab-pay', NULL, NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1500.00, 0.00, 'cash', 'wallet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '58659', 'salam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 29, 'mis_a_jour', 0.00, 15000.00, 0.00, 0.00, 1000.52, 'cheque', NULL, NULL, NULL, NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cheque', 'cheque', NULL, 0.00, NULL, 'wallet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '58659', 'salam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 30, 'mis_a_jour', 0.00, 15000.00, 0.00, 0.00, 1000.52, 'cheque', NULL, NULL, NULL, NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '123456789', 'Société Générale', 1500.00, 0.00, 'cheque', 'wallet', NULL, NULL, NULL, NULL, NULL, '123456', 'Banque XYZ', '7845666', 'wafinhos', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 31, 'mis_a_jour', 0.00, 5000.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '58659', 'salam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 34, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 36, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, 'ahmed123', '987654321', 'Banque Centrale', 7500.00, 300.00, 'carte_credits', 'wallet', 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 37, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 38, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 40, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 41, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 42, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 43, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 44, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 45, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', 15.50, 0.00, 'wallet', NULL, NULL, NULL, NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 46, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 47, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 48, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', 15.50, 15.50, 'wallet', 'wallet', 'wafi', NULL, NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 50, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', 15.50, 15.50, 'wallet', 'wallet', 'wafi', NULL, NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 52, 'mis_a_jour', 0.00, 0.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ggggggggggg585588h', NULL, '987654321', 'Banque Centrale', NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 53, 'mis_a_jour', 0.00, 0.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', 15.50, 15.50, 'wallet', 'wallet', 'wafi', NULL, NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 54, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '987654321', 'Banque Centrale', NULL, 15.50, NULL, 'wallet', 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 56, 'mis_a_jour', 0.00, 15075.00, 4219.50, 7500.00, 150.00, 'cheque', NULL, NULL, NULL, 'wallet', 'wallet', 'cheque', 'wafi', 'wafi', NULL, NULL, 'REF12345', 'REF1234', 'REF12345', 'REF12345', 'REF123456', '987654321', 'Banque Centrale', 7902.00, 1016.00, 'wallet', 'wallet', 'wafi', NULL, NULL, 'wafi', NULL, NULL, NULL, 'CHQ12345', 'Nom Banque', NULL, NULL, NULL, NULL, '1234567890', 'WALLET12345', NULL, '1234567890', '1234567890'),
(47, 57, 'mis_a_jour', 0.00, 75.00, 0.00, 0.00, 150.00, 'cheque', NULL, NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, '', NULL, NULL, 'REF1234', NULL, 'moham123', 'REF123456', '987654321', 'Banque Centrale', NULL, 1016.00, 'wallet', 'wallet', 'wafi', NULL, NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1234567890'),
(52, 61, 'mis_a_jour', 0.00, 20.00, 0.00, 0.00, 50.00, 'wallet', NULL, '', NULL, NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10.00, 15.00, 'wallet', 'cheque', NULL, 'CH67890', 'Banque Collection', '', NULL, NULL, NULL, 'CH12345', 'Banque Exemple', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(53, 62, 'mis_a_jour', 0.00, 20.00, 0.00, 0.00, 50.00, 'wallet', NULL, '', NULL, NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, 'RF12345', NULL, NULL, NULL, NULL, NULL, NULL, 10.00, 15.00, 'wallet', 'cheque', NULL, 'CH67890', 'Banque Collection', '', NULL, NULL, NULL, 'CH12345', 'Banque Exemple', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(54, 63, 'mis_a_jour', 0.00, 20.00, 0.00, 0.00, 50.00, 'wallet', NULL, '', NULL, NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, 'RF12345', '1234', NULL, NULL, 'REF54321', NULL, NULL, 40.00, 75.50, 'cash', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CH12345', 'Banque Exemple', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ''),
(55, 64, 'mis_a_jour', 0.00, 20.00, 0.00, 0.00, 50.00, 'wallet', NULL, '', NULL, NULL, NULL, 'cheque', NULL, NULL, NULL, NULL, 'RF12345', 'RF77777', NULL, NULL, 'RF77420616', NULL, NULL, 10.00, 15.00, 'wallet', 'cheque', NULL, 'CH67890', 'Banque Collection', '', NULL, NULL, NULL, 'CH12345', 'Banque Exemple', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(56, 65, 'mis_a_jour', 0.00, 20.00, 0.00, 0.00, 50.00, 'wallet', NULL, '', NULL, NULL, NULL, 'wallet', NULL, NULL, 'wafi', NULL, 'RF12345', 'RF77777', NULL, NULL, 'RF77420616', NULL, NULL, 10.00, 15.00, 'wallet', 'cheque', NULL, 'CH67890', 'Banque Collection', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '12345jiji', NULL, NULL),
(57, 66, 'mis_a_jour', 0.00, 20.00, 0.00, 0.00, 250.00, 'cash', NULL, NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, 'wafi', NULL, 'RF12345', 'RF77777', NULL, NULL, 'RF77420616', NULL, NULL, 10.00, 15.00, 'wallet', 'cheque', NULL, 'CH67890', 'Banque Collection', 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '12345jiji', '456897', NULL),
(58, 66, 'mis_a_jour', 0.00, 0.00, 0.00, 50.35, 250.00, 'cash', NULL, NULL, NULL, NULL, 'wallet', NULL, NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, 'RF12345678', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '123456', NULL, NULL, NULL, NULL),
(59, 66, '', 0.00, 0.00, 58.37, 0.00, 250.00, 'cash', NULL, NULL, NULL, 'wallet', NULL, NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, 'RF77400616', NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '77087058', NULL, NULL, NULL),
(61, 66, 'mis_a_jour', 0.00, 0.00, 0.00, 0.00, 200.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(62, 66, 'mis_a_jour', 0.00, 0.00, 0.00, 0.00, 200.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ref123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(63, NULL, 'mis_a_jour', 0.00, 0.00, 0.00, 0.00, 200.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(64, NULL, 'mis_a_jour', 0.00, 0.00, 0.00, 0.00, 200.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(65, NULL, 'mis_a_jour', 0.00, 0.00, 0.00, 0.00, 100.50, 'wallet', NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(66, NULL, 'mis_a_jour', 0.00, 0.00, 0.00, 0.00, 100.50, 'wallet', NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(67, 66, 'mis_a_jour', 0.00, 0.00, 0.00, 0.00, 100.50, 'wallet', NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 63, 'mis_a_jour', 0.00, 0.00, 0.00, 0.00, 100.50, 'wallet', NULL, 'wafi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1234', NULL, NULL, NULL, NULL, NULL, 40.00, 0.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(69, 63, 'mis_a_jour', 0.00, 0.00, 0.00, 0.00, 100.50, 'wallet', NULL, 'wafi', '987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1234', NULL, NULL, NULL, NULL, NULL, 40.00, 0.00, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_boite_postale` (`id_boite_postale`);

--
-- Index pour la table `depot`
--
ALTER TABLE `depot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_client` (`id_client`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `depot`
--
ALTER TABLE `depot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `exaunore`
--
ALTER TABLE `exaunore`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `livraison_a_domicile`
--
ALTER TABLE `livraison_a_domicile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `paiements`
--
ALTER TABLE `paiements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- Contraintes pour la table `depot`
--
ALTER TABLE `depot`
  ADD CONSTRAINT `depot_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
