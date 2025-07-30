-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2025 at 08:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `etat_civil`
--

-- --------------------------------------------------------

--
-- Table structure for table `acte`
--

CREATE TABLE `acte` (
  `id` int(11) NOT NULL,
  `demande_id` int(11) NOT NULL,
  `chemin_pdf` varchar(255) DEFAULT NULL,
  `date_generation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `acte`
--

INSERT INTO `acte` (`id`, `demande_id`, `chemin_pdf`, `date_generation`) VALUES
(1, 10, 'documents/acte_10.pdf', '2025-06-11 15:34:10'),
(2, 9, 'documents/acte_9.pdf', '2025-06-11 15:53:59'),
(3, 11, 'documents/acte_11.pdf', '2025-06-11 20:48:52');

-- --------------------------------------------------------

--
-- Table structure for table `demande`
--

CREATE TABLE `demande` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `type_acte_id` int(11) NOT NULL,
  `nombre_copies` int(11) DEFAULT 1,
  `motif` text DEFAULT NULL,
  `nom_enfant` varchar(100) DEFAULT NULL,
  `date_heure_naissance` datetime DEFAULT NULL,
  `nomCompletPere` varchar(150) DEFAULT NULL,
  `nomCompletMere` varchar(150) DEFAULT NULL,
  `numero_extrait` varchar(150) DEFAULT NULL,
  `nomComplet_mariee` varchar(150) DEFAULT NULL,
  `nomComplet_marie` varchar(150) DEFAULT NULL,
  `extrait_defunt` varchar(150) DEFAULT NULL,
  `moyen_paiement` varchar(50) DEFAULT NULL,
  `numero_depot` varchar(20) DEFAULT NULL,
  `reference_paiement` varchar(100) DEFAULT NULL,
  `montant_paye` int(11) DEFAULT NULL,
  `statut` enum('en attente','validée','refusée') DEFAULT 'en attente',
  `date_demande` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `demande`
--

INSERT INTO `demande` (`id`, `utilisateur_id`, `type_acte_id`, `nombre_copies`, `motif`, `nom_enfant`, `date_heure_naissance`, `nomCompletPere`, `nomCompletMere`, `numero_extrait`, `nomComplet_mariee`, `nomComplet_marie`, `extrait_defunt`, `moyen_paiement`, `numero_depot`, `reference_paiement`, `montant_paye`, `statut`, `date_demande`) VALUES
(1, 1, 4, 1, 'HHHH', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'FFRR22334', 'orange', '0700000000', 'HHH', 500, 'en attente', '2025-05-28 23:02:09'),
(2, 1, 1, 2, 'HJJJHJH', 'ASSI SEKA BERNARD', '2025-05-28 23:04:00', 'ASSI FIRMIN', 'AWA BERTRANT', NULL, NULL, NULL, NULL, 'orange', '0700000000', 'JJJJJJ', 1000, 'en attente', '2025-05-28 23:05:59'),
(3, 2, 1, 1, 'EEERR', 'ASSY', '2025-10-15 12:00:00', 'EERRR', 'EERR', NULL, NULL, NULL, NULL, 'wave', '0700000000', 'RRRR', 500, 'en attente', '2025-05-29 13:11:16'),
(4, 1, 3, 3, 'HGHHHGHHGH', NULL, NULL, NULL, NULL, NULL, '567 DU 20M EETT', '567 DU 20M EETT', NULL, 'moov', '0500000000', 'DDDDD455', 1500, 'en attente', '2025-05-29 19:20:01'),
(5, 1, 1, 1, 'déclaration de naissance ', 'ASSI Godo Firmin', '2000-09-12 12:00:00', 'ASSI Bernard', 'Kouassi Anne Rogert', NULL, NULL, NULL, NULL, 'wave', '0700000000', 'GHHHHGHG', 500, 'en attente', '2025-06-09 11:10:51'),
(6, 1, 3, 1, 'rrrr', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'wave', '0700000000', 'GHHHHGHG', 500, 'en attente', '2025-06-09 11:12:51'),
(7, 1, 3, 3, 'HHH', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'orange', '0700000000', 'JJJJJJJ', 1500, 'en attente', '2025-06-09 14:53:28'),
(8, 1, 3, 4, '', NULL, NULL, NULL, NULL, NULL, 'ASSI yapo Anne', 'APPOh Dakar', NULL, 'orange', '0700000000', 'GHHHHGHG', 2000, 'refusée', '2025-06-09 15:01:01'),
(9, 1, 1, 3, 'GSGGGS', 'Béda Edouard', '2222-09-12 23:34:00', 'Coulibaly Savané', 'ATSEBY BEDA EDOUARD', NULL, NULL, NULL, NULL, 'orange', '0700000000', 'yyeyyye', 1500, 'validée', '2025-06-10 12:12:13'),
(10, 1, 1, 1, 'Naissance de notre fille', 'COULIBALY Aminata Savané', '2025-06-11 03:42:00', 'COULIBALY Youssouf', 'COULIBALY Myriam', NULL, NULL, NULL, NULL, 'wave', '0700000000', 'RRRR', 500, 'validée', '2025-06-11 03:44:44'),
(11, 6, 1, 2, '', 'Coulibaly youssof', '2025-06-11 19:40:00', 'Coulibaly Koné', 'Coulibaly Ane', NULL, NULL, NULL, NULL, 'orange', '0700000000', 'UUUU', 1000, 'validée', '2025-06-11 20:47:55');

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `id` int(11) NOT NULL,
  `demande_id` int(11) NOT NULL,
  `nom_fichier` varchar(255) DEFAULT NULL,
  `type_fichier` varchar(50) DEFAULT NULL,
  `date_upload` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `historique`
--

CREATE TABLE `historique` (
  `id` int(11) NOT NULL,
  `demande_id` int(11) NOT NULL,
  `action` varchar(255) DEFAULT NULL,
  `acteur_id` int(11) DEFAULT NULL,
  `date_action` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `historique`
--

INSERT INTO `historique` (`id`, `demande_id`, `action`, `acteur_id`, `date_action`) VALUES
(1, 10, 'Validation de la demande', 5, '2025-06-11 15:34:10'),
(2, 9, 'Validation de la demande', 5, '2025-06-11 15:53:59'),
(3, 8, 'Refus de la demande', 5, '2025-06-11 15:59:36'),
(4, 11, 'Validation de la demande', 5, '2025-06-11 20:48:52');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `statut` enum('non lu','lu') DEFAULT 'non lu',
  `date_notification` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `type_acte`
--

CREATE TABLE `type_acte` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `type_acte`
--

INSERT INTO `type_acte` (`id`, `libelle`) VALUES
(1, 'nouvelle_naissance'),
(2, 'copie_naissance'),
(3, 'mariage'),
(4, 'deces');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `role` enum('citoyen','agent','admin') DEFAULT 'citoyen',
  `statut` enum('actif','inactif') DEFAULT 'actif',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `telephone`, `role`, `statut`, `date_creation`) VALUES
(1, 'ATSEBY', '', 'bedaedouard@gmail.com', '$2y$10$edrlYWMTSDsIONh/qbRYDOoGB5yZ35QjI0lpcp3M5As.u9grJkpbG', '0700000000', 'citoyen', 'actif', '2025-05-27 18:52:55'),
(2, 'Sangaré Kouamé', '', 'sangare@gmail.com', '$2y$10$CD1QjtMbpn1LiqLeRnGiT.FrXPAf8kd30VU2/8ViA3KESCoIWkq1q', '0788125701', 'citoyen', 'actif', '2025-05-28 11:18:47'),
(5, 'ATSEBY', '', 'atseby.agent123@e-acte.com', '$2y$10$CD1QjtMbpn1LiqLeRnGiT.FrXPAf8kd30VU2/8ViA3KESCoIWkq1q', NULL, 'agent', 'actif', '2025-06-08 10:29:17'),
(6, 'jjj', '', 'atsebyedouard@gmail.com', '$2y$10$5egKbUhpOn3Ax3dKVDZlZ.B/Q8O/5O4bnK/nCSmljjAE.lGL003pG', '07888', 'citoyen', 'actif', '2025-06-10 12:47:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acte`
--
ALTER TABLE `acte`
  ADD PRIMARY KEY (`id`),
  ADD KEY `demande_id` (`demande_id`);

--
-- Indexes for table `demande`
--
ALTER TABLE `demande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `type_acte_id` (`type_acte_id`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`),
  ADD KEY `demande_id` (`demande_id`);

--
-- Indexes for table `historique`
--
ALTER TABLE `historique`
  ADD PRIMARY KEY (`id`),
  ADD KEY `demande_id` (`demande_id`),
  ADD KEY `acteur_id` (`acteur_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Indexes for table `type_acte`
--
ALTER TABLE `type_acte`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acte`
--
ALTER TABLE `acte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `demande`
--
ALTER TABLE `demande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `historique`
--
ALTER TABLE `historique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `type_acte`
--
ALTER TABLE `type_acte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `acte`
--
ALTER TABLE `acte`
  ADD CONSTRAINT `acte_ibfk_1` FOREIGN KEY (`demande_id`) REFERENCES `demande` (`id`);

--
-- Constraints for table `demande`
--
ALTER TABLE `demande`
  ADD CONSTRAINT `demande_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `demande_ibfk_2` FOREIGN KEY (`type_acte_id`) REFERENCES `type_acte` (`id`);

--
-- Constraints for table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `document_ibfk_1` FOREIGN KEY (`demande_id`) REFERENCES `demande` (`id`);

--
-- Constraints for table `historique`
--
ALTER TABLE `historique`
  ADD CONSTRAINT `historique_ibfk_1` FOREIGN KEY (`demande_id`) REFERENCES `demande` (`id`),
  ADD CONSTRAINT `historique_ibfk_2` FOREIGN KEY (`acteur_id`) REFERENCES `utilisateur` (`id`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
