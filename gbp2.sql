-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 16 fév. 2025 à 06:44
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
-- Base de données : `gbp2`
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
(42, 3, '2025', 8),
(43, 7, '2025', 9);

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
(73, 'halima abdillahi hassan', 'Natasha@gmail.com', 'centre ville q1', NULL, 'particulier', '77101010', 3, 2, NULL, '2025-02-08'),
(74, 'fatouma sadik abdallah', 'testuser@gmail.com', 'cite nasib', NULL, 'particulier', '77777778', 7, 2, NULL, '2025-02-12');

-- --------------------------------------------------------

--
-- Structure de la table `collection`
--

CREATE TABLE `collection` (
  `id` int(11) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_client` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `collection`
--

INSERT INTO `collection` (`id`, `adresse`, `created_at`, `updated_at`, `id_client`) VALUES
(0, 'hoddan ,hayabley ', '2025-02-08 14:54:38', '2025-02-08 16:54:38', 73);

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
  `type_wallet` enum('waafi','cac_pay','d_money','sabapay','dahabplaces') DEFAULT NULL,
  `numero_wallet` varchar(255) DEFAULT NULL,
  `numero_cheque` varchar(50) DEFAULT NULL,
  `nom_banque` varchar(100) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `created_by_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `details_paiements`
--

INSERT INTO `details_paiements` (`id`, `paiement_id`, `categorie`, `montant`, `methode_payment`, `type_wallet`, `numero_wallet`, `numero_cheque`, `nom_banque`, `reference`, `created_by_user`) VALUES
(25, 8, 'redevence', 40000.00, 'wallet', 'waafi', '77101012', '', '', 'RNBP/00001/2025-02-08', 2),
(26, 8, 'sous_couvette', 0.00, 'wallet', 'waafi', '77101012', '', '', 'AJSC/00001/2025-02-08', 2),
(27, 8, 'livraison_domicile', 3000.00, 'wallet', 'waafi', '77101012', '', '', 'AJLV/00001/2025-02-08', 2),
(28, 8, 'collection', 4000.00, 'wallet', 'waafi', '77101012', '', '', 'AJCll/00001/2025-02-08', 2),
(29, 8, 'livraison_domicile', 5000.00, 'cheque', NULL, '', '132564', 'EAB', 'CGNM/00001/2025-02-11', 2),
(30, 8, 'redevence', 40000.00, 'cash', NULL, '', '', '', 'RNBP/00002/2025-02-11', 2),
(31, 8, 'redevence', 40000.00, 'wallet', 'waafi', '77101012', '', '', 'RNBP/00003/2025-02-11', 2),
(32, 8, 'redevence', 40000.00, 'cash', NULL, '', '', '', 'RNBP/00004/2025-02-11', 2),
(33, 8, 'redevence', 40000.00, 'cash', NULL, '', '', '', 'RNBP/00005/2025-02-11', 2),
(34, 8, 'redevence', 40000.00, 'cash', NULL, '', '', '', 'RNBP/00005/2025-02-11', 2),
(35, 8, 'redevence', 40000.00, 'cash', NULL, '', '', '', 'RNBP/00006/2025-02-11', 2),
(36, 8, 'redevence', 40000.00, 'cash', NULL, '', '', '', 'RNBP/00006/2025-02-11', 2),
(37, 8, 'redevence', 40000.00, 'cash', NULL, '', '', '', 'RNBP/00007/2025-02-11', 2),
(38, 9, 'redevence', 40000.00, 'cash', '', '', '', '', 'RNBP/00008/2025-02-12', 2),
(39, 9, 'sous_couvette', 0.00, 'cash', '', '', '', '', '', 2),
(40, 9, 'livraison_domicile', 3000.00, 'cash', '', '', '', '', 'AJLV/00002/2025-02-12', 2);

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
(43, 'particulier', NULL, 0x75706c6f61642f646f63756d656e74732f506f7274666f6c696f205765622054656d706c6174652028436f6d6d756e697479292e706466, 0x75706c6f61642f646f63756d656e74732f506f7274666f6c696f2044657643686170746572202831292e706466, '2025-02-12 06:08:02', 74);

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

--
-- Déchargement des données de la table `livraison_a_domicile`
--

INSERT INTO `livraison_a_domicile` (`id`, `adresse`, `id_client`, `created_at`, `updated_at`) VALUES
(0, 'hodan 4', 73, '2025-02-08 14:54:38', '2025-02-08 16:54:38'),
(0, 'cite hodan 4', 74, '2025-02-12 04:08:02', '2025-02-12 06:08:02');

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
(8, 73, 'mis_a_jour', 0.00, 40000.00, 'wallet', 'RNBP/00001/2025-02-08', '2025-02-08 17:54:38'),
(9, 74, 'mis_a_jour', 0.00, 40000.00, 'cash', 'RNBP/00008/2025-02-12', '2025-02-16 07:08:02');

-- --------------------------------------------------------

--
-- Structure de la table `resilies`
--

CREATE TABLE `resilies` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_client` int(11) DEFAULT NULL,
  `date_resiliation` date NOT NULL,
  `Lettre_recommandation` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `resilies`
--

INSERT INTO `resilies` (`id`, `id_user`, `id_client`, `date_resiliation`, `Lettre_recommandation`) VALUES
(3, 6, 73, '2025-02-13', 'upload/documents/1739439266_67adbca2d3ec5.PNG');

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

--
-- Déchargement des données de la table `sous_couvete`
--

INSERT INTO `sous_couvete` (`id`, `nom_societe`, `nom_personne`, `telephone`, `adresse`, `id_client`, `id_user`) VALUES
(0, '', '', '', '', 73, 2),
(0, '', '', '', '', 74, 2);

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
(1, 'test', 'test@gmail.com', '$2a$12$GDWZWWmZGak79vkBzlLxsOM3ucHKPdjwgAJTw/mESoHsYBkvnj8jS', 'supersiveur'),
(2, 'sadik', 'sadik@gmail.com', '$2a$12$GDWZWWmZGak79vkBzlLxsOM3ucHKPdjwgAJTw/mESoHsYBkvnj8jS', 'agent_guichets'),
(4, 'John Doe', 'johndoe@example.com', '$2a$12$GDWZWWmZGak79vkBzlLxsOM3ucHKPdjwgAJTw/mESoHsYBkvnj8jS', 'responsable'),
(6, 'layla mohamed hassan', 'layla@gmail.com', '$2a$12$GDWZWWmZGak79vkBzlLxsOM3ucHKPdjwgAJTw/mESoHsYBkvnj8jS', 'agent_commerciale');

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
  ADD KEY `details_paiements_ibfk_1` (`paiement_id`),
  ADD KEY `fk_user_id_track` (`created_by_user`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `boites_postales`
--
ALTER TABLE `boites_postales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT pour la table `depot`
--
ALTER TABLE `depot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `details_paiements`
--
ALTER TABLE `details_paiements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `exaunore`
--
ALTER TABLE `exaunore`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `paiements`
--
ALTER TABLE `paiements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `resilies`
--
ALTER TABLE `resilies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  ADD CONSTRAINT `details_paiements_ibfk_1` FOREIGN KEY (`paiement_id`) REFERENCES `paiements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_id_track` FOREIGN KEY (`created_by_user`) REFERENCES `users` (`id`);

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
