-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 05 mai 2026 à 09:42
-- Version du serveur : 10.6.23-MariaDB-0ubuntu0.22.04.1
-- Version de PHP : 8.1.2-1ubuntu2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `publicomdb`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `ID` int(11) NOT NULL,
  `IDENTIFIANT` char(32) DEFAULT NULL,
  `MOTDEPASSE` char(72) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`ID`, `IDENTIFIANT`, `MOTDEPASSE`) VALUES
(1, 'admin', '$2a$12$Ugy04OEWQkzdHSGwGeUk3eu54uteXU8HqMqk3e53.XqdpfOAFkKhq');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `IDCATEGORIE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commune`
--

CREATE TABLE `commune` (
  `ID` int(11) NOT NULL,
  `NOM` char(32) DEFAULT NULL,
  `CODEPOSTAL` bigint(20) DEFAULT NULL,
  `IMAGE` longblob DEFAULT NULL,
  `DESCRIPTION` char(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commune`
--

INSERT INTO `commune` (`ID`, `NOM`, `CODEPOSTAL`, `IMAGE`, `DESCRIPTION`) VALUES
(1, 'Montfa', 81210, NULL, 'Commune de montfa'),
(2, 'Caussade', 82300, NULL, 'commune de Caussade');

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `ID` int(11) NOT NULL,
  `ID_COMMUNEMESSAGE` int(11) NOT NULL,
  `TITRE` varchar(60) DEFAULT NULL,
  `CONTENU` varchar(600) DEFAULT NULL,
  `POLICETITRE` varchar(40) DEFAULT NULL,
  `POLICECONTENU` varchar(40) DEFAULT NULL,
  `ALIGNEMENT` enum('gauche','centre','droite') DEFAULT NULL,
  `FOND` char(80) DEFAULT NULL,
  `TAILLECONTENU` int(11) DEFAULT NULL,
  `TAILLETITRE` int(11) DEFAULT NULL,
  `PUBLIE` tinyint(1) DEFAULT NULL,
  `ID_CATEGORIEMESSAGE` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `panneaux`
--

CREATE TABLE `panneaux` (
  `ID` int(11) NOT NULL,
  `ID_COMMUNEPANNEAUX` int(11) NOT NULL,
  `NUMERO` int(11) DEFAULT NULL,
  `LATITUDE` decimal(10,5) DEFAULT NULL,
  `LONGITUDE` decimal(10,5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `ID` int(11) NOT NULL,
  `ID_UTILISATEURCOMMUNE` int(11) NOT NULL,
  `PRENOM` char(32) DEFAULT NULL,
  `NOM` char(32) DEFAULT NULL,
  `IDENTIFIANT` char(32) DEFAULT NULL,
  `MOTDEPASSE` char(72) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`IDCATEGORIE`);

--
-- Index pour la table `commune`
--
ALTER TABLE `commune`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `I_FK_MESSAGE_COMMUNE` (`ID_COMMUNEMESSAGE`),
  ADD KEY `I_FK_MESSAGE_CATEGORIE` (`ID_CATEGORIEMESSAGE`);

--
-- Index pour la table `panneaux`
--
ALTER TABLE `panneaux`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `I_FK_PANNEAUX_COMMUNE` (`ID_COMMUNEPANNEAUX`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `I_FK_UTILISATEUR_COMMUNE` (`ID_UTILISATEURCOMMUNE`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `IDCATEGORIE` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commune`
--
ALTER TABLE `commune`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `panneaux`
--
ALTER TABLE `panneaux`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_MESSAGE_CATEGORIE` FOREIGN KEY (`ID_CATEGORIEMESSAGE`) REFERENCES `categorie` (`IDCATEGORIE`),
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`ID_COMMUNEMESSAGE`) REFERENCES `commune` (`ID`);

--
-- Contraintes pour la table `panneaux`
--
ALTER TABLE `panneaux`
  ADD CONSTRAINT `panneaux_ibfk_1` FOREIGN KEY (`ID_COMMUNEPANNEAUX`) REFERENCES `commune` (`ID`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`ID_UTILISATEURCOMMUNE`) REFERENCES `commune` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
