-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 13 mars 2025 à 12:07
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `abonnement`
--

INSERT INTO `abonnement` (`id`, `Id_client`, `Annee_abonnement`, `Montant`, `MontantSc`, `Penalite`, `Status`, `created_at`, `updated_at`, `updated_by`) VALUES
(3, 9, 2025, 20000.00, 0.00, 0.00, 'payé', '2025-02-27 08:09:28', '2025-02-27 08:09:28', 1),
(4, 11, 2025, 20000.00, 0.00, 0.00, 'payé', '2025-02-27 08:33:14', '2025-02-27 08:33:14', 1);

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
(8, '2', 'Grand');

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
(11, 'Fatouma ali mohamed', 'RaliaMohamed@gmail.com', 'cite nasib', 'Entreprise', '77101214', 8, '2025-02-27', 1, 1);

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `details_paiements`
--

INSERT INTO `details_paiements` (`id`, `Id_paiement`, `Categories`, `Montant`, `Methode_paiement`, `Wallet`, `Numero_wallet`, `Numero_cheque`, `Nom_bank`, `reference`, `created_at`, `created_by`) VALUES
(11, 3, 'Changement_Nom', 5000, 'cash', NULL, '', '', NULL, 'CGNM/00001/2025-03-13', '2025-03-13 10:30:34', 27),
(12, 3, 'Achat_cle', 2000, 'wallet', 'waafi', '77101010', '', NULL, 'CGCLE/00001/2025-03-13', '2025-03-13 10:45:18', 27),
(13, 3, 'sous_couverte', 30000, 'cheque', NULL, '', '132564', 'salam bank', 'AJSC/00001/2025-03-13', '2025-03-13 11:00:43', 27);

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
(4, 11, 'upload/documents/1740645194_Compte d\'attente.PNG', 'upload/documents/1740645194_compte d\'attende modifier.PNG', 'upload/documents/1740645194_Add Clients individuel.PNG', '2025-02-27 08:33:14', 1);

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
(4, 4, 'wallet', 'waafi', '77101214', '0', '0', 'NBGPB/0001/2025', '2025-02-27 08:33:14', 1);

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
(10, 'societe A', 'Mousa ali farah', '7710121410', 'quartier 5', 9, 27, '2025-03-13');

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
(25, 'abdirahman nouradine moussa', 'test Adresse', '77101214', 'youra@gmail.com', '$2y$10$AyVHYFOV.Bl040/tD9IZIOAE1RJb6GtXkawiLyDrrRI9KYRVubggu', 'agent_commercial'),
(27, 'halima abdillahi hassan', 'test Adresse', '77101214', 'denerise@gmail.com', '$2y$10$Z6VlYn60l2Tc68aILh2A6e1WyUcPWYWgqJf7O7IQi6AC6suKkhLGO', 'agent_guichet');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `boit_postal`
--
ALTER TABLE `boit_postal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `collections`
--
ALTER TABLE `collections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `details_paiements`
--
ALTER TABLE `details_paiements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `exonore`
--
ALTER TABLE `exonore`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `lvdomcile`
--
ALTER TABLE `lvdomcile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `penaliter`
--
ALTER TABLE `penaliter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `resilier`
--
ALTER TABLE `resilier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sous_couverte`
--
ALTER TABLE `sous_couverte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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
