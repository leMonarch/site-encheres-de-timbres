-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 07 avr. 2022 à 17:23
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `lord-stampee`
--

-- --------------------------------------------------------

--
-- Structure de la table `certifications`
--

DROP TABLE IF EXISTS `certifications`;
CREATE TABLE IF NOT EXISTS `certifications` (
  `certification_id` int(11) NOT NULL AUTO_INCREMENT,
  `certification_nom` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`certification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `certifications`
--

INSERT INTO `certifications` (`certification_id`, `certification_nom`) VALUES
(1, 'Lord Stampee'),
(3, 'Best Seller');

-- --------------------------------------------------------

--
-- Structure de la table `conditions`
--

DROP TABLE IF EXISTS `conditions`;
CREATE TABLE IF NOT EXISTS `conditions` (
  `condition_id` int(11) NOT NULL AUTO_INCREMENT,
  `condition_nom` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`condition_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `conditions`
--

INSERT INTO `conditions` (`condition_id`, `condition_nom`) VALUES
(1, 'Menthe'),
(2, 'Utilisé'),
(3, 'Inutilisé');

-- --------------------------------------------------------

--
-- Structure de la table `encheres`
--

DROP TABLE IF EXISTS `encheres`;
CREATE TABLE IF NOT EXISTS `encheres` (
  `enchere_id` int(11) NOT NULL AUTO_INCREMENT,
  `enchere_prix_plancher` int(11) DEFAULT NULL,
  `enchere_prix_actuel` int(11) DEFAULT NULL,
  `gagnant_id` int(11) DEFAULT NULL,
  `gagnant_nom` varchar(255) DEFAULT NULL,
  `enchere_coup_de_coeur` varchar(255) DEFAULT NULL,
  `enchere_compte_rebour` datetime DEFAULT NULL,
  `enchere_date_debut` date DEFAULT NULL,
  `enchere_date_fin` date DEFAULT NULL,
  `enchere_categorie` varchar(255) DEFAULT NULL,
  `enchere_vendue` varchar(255) DEFAULT NULL,
  `Users_user_id` int(11) NOT NULL,
  `Users_user_firstname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`enchere_id`),
  KEY `Users_user_id_idx` (`Users_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `encheres`
--

INSERT INTO `encheres` (`enchere_id`, `enchere_prix_plancher`, `enchere_prix_actuel`, `gagnant_id`, `gagnant_nom`, `enchere_coup_de_coeur`, `enchere_compte_rebour`, `enchere_date_debut`, `enchere_date_fin`, `enchere_categorie`, `enchere_vendue`, `Users_user_id`, `Users_user_firstname`) VALUES
(12, 2300, 2335, NULL, 'Louis', NULL, NULL, '2022-03-22', '2022-03-25', 'Encheres', NULL, 3, 'Gilles'),
(13, 5000, 5575, NULL, 'Gilles', NULL, NULL, '2022-03-13', '2022-03-19', 'Encheres', NULL, 6, 'Jean'),
(15, 3022, 3022, NULL, NULL, NULL, NULL, '2022-03-17', '2022-03-19', 'Encheres', NULL, 5, 'Lise'),
(16, 575, 575, NULL, NULL, NULL, NULL, '2022-03-17', '2022-03-19', 'Encheres', NULL, 5, 'Lise'),
(17, 2125, 2125, NULL, NULL, NULL, NULL, '2022-03-17', '2022-03-19', 'Encheres', NULL, 6, 'Jean');

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `image_lien` varchar(255) DEFAULT NULL,
  `Timbres_timbre_id` int(11) NOT NULL,
  `Timbres_Pays_pays_id` int(11) NOT NULL,
  `Timbres_Conditions_condition_id` int(11) NOT NULL,
  `Timbres_Certifications_certification_id` int(11) NOT NULL,
  `Timbres_Encheres_enchere_id` int(11) NOT NULL,
  PRIMARY KEY (`image_id`,`Timbres_timbre_id`,`Timbres_Pays_pays_id`,`Timbres_Conditions_condition_id`,`Timbres_Certifications_certification_id`,`Timbres_Encheres_enchere_id`),
  KEY `fk_Images_Timbres1` (`Timbres_Pays_pays_id`,`Timbres_Conditions_condition_id`,`Timbres_Certifications_certification_id`,`Timbres_timbre_id`,`Timbres_Encheres_enchere_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `images`
--

INSERT INTO `images` (`image_id`, `image_lien`, `Timbres_timbre_id`, `Timbres_Pays_pays_id`, `Timbres_Conditions_condition_id`, `Timbres_Certifications_certification_id`, `Timbres_Encheres_enchere_id`) VALUES
(29, '/lord-stampee/public/img/timbre-29.jpg', 13, 9, 1, 3, 16),
(26, '/lord-stampee/public/img/timbre-26.jpg', 10, 10, 2, 1, 13),
(31, '/lord-stampee/public/img/timbre-31.jpg', 9, 6, 1, 3, 12),
(28, '/lord-stampee/public/img/timbre-28.jpg', 12, 10, 2, 3, 15),
(30, '/lord-stampee/public/img/timbre-30.jpg', 14, 7, 1, 1, 17);

-- --------------------------------------------------------

--
-- Structure de la table `offres`
--

DROP TABLE IF EXISTS `offres`;
CREATE TABLE IF NOT EXISTS `offres` (
  `Offres_user_id` int(11) NOT NULL,
  `Offres_enchere_id` int(11) NOT NULL,
  `offre_actuelle` int(11) NOT NULL,
  PRIMARY KEY (`Offres_user_id`,`Offres_enchere_id`),
  KEY `fk_User_has_Encheres_Encheres1_idx` (`Offres_enchere_id`),
  KEY `fk_User_has_Encheres_User_idx` (`Offres_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `pays`
--

DROP TABLE IF EXISTS `pays`;
CREATE TABLE IF NOT EXISTS `pays` (
  `pays_id` int(11) NOT NULL AUTO_INCREMENT,
  `pays_nom` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`pays_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pays`
--

INSERT INTO `pays` (`pays_id`, `pays_nom`) VALUES
(6, 'Canada'),
(7, 'USA'),
(8, 'Angleterre'),
(9, 'France'),
(10, 'Australie');

-- --------------------------------------------------------

--
-- Structure de la table `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_nom` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `status`
--

INSERT INTO `status` (`status_id`, `status_nom`) VALUES
(1, 'admin'),
(2, 'user'),
(3, 'suspend');

-- --------------------------------------------------------

--
-- Structure de la table `timbres`
--

DROP TABLE IF EXISTS `timbres`;
CREATE TABLE IF NOT EXISTS `timbres` (
  `timbre_id` int(11) NOT NULL AUTO_INCREMENT,
  `timbre_nom` varchar(255) DEFAULT NULL,
  `timbre_description` text,
  `timbre_annee` int(11) DEFAULT NULL,
  `timbre_type` varchar(255) DEFAULT NULL,
  `timbre_dimensions` varchar(255) DEFAULT NULL,
  `timbre_couleur` varchar(255) DEFAULT NULL,
  `Pays_pays_id` int(11) NOT NULL,
  `Conditions_condition_id` int(11) NOT NULL,
  `Certifications_certification_id` int(11) NOT NULL,
  `Encheres_enchere_id` int(11) NOT NULL,
  PRIMARY KEY (`timbre_id`,`Pays_pays_id`,`Conditions_condition_id`,`Certifications_certification_id`,`Encheres_enchere_id`),
  KEY `fk_Timbre_Pays1_idx` (`Pays_pays_id`),
  KEY `fk_Timbres_Conditions1_idx` (`Conditions_condition_id`),
  KEY `fk_Timbres_Certifications1_idx` (`Certifications_certification_id`),
  KEY `fk_Timbres_Encheres1_idx` (`Encheres_enchere_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `timbres`
--

INSERT INTO `timbres` (`timbre_id`, `timbre_nom`, `timbre_description`, `timbre_annee`, `timbre_type`, `timbre_dimensions`, `timbre_couleur`, `Pays_pays_id`, `Conditions_condition_id`, `Certifications_certification_id`, `Encheres_enchere_id`) VALUES
(9, 'La Bateaux', 'Rosevelt', 2022, 'Royal', '3 x 4', 'Rose', 6, 1, 3, 12),
(10, 'Les Lords', 'Les lords............', 1777, 'Politique', '4 x 5', 'Teinte', 10, 2, 1, 13),
(12, 'Les avions de voyages', 'Les avions de voyage figurent parmi la collection australienne de l\'histoire de l\'aviation............', 1877, 'Aviation', '2 x 3', 'Bleu gris', 10, 2, 3, 15),
(13, 'Le plongeur de guadeloupe', 'Le timbre récréatif........', 1985, 'Maritime', '3 x 2', 'Bleu', 9, 1, 3, 16),
(14, 'USA collection', 'La collection..........', 1800, 'Histoire', '7 x 10', 'Teinte', 7, 1, 1, 17);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_firstname` varchar(255) DEFAULT NULL,
  `user_lastname` varchar(255) DEFAULT NULL,
  `user_pseudo` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_status` int(11) DEFAULT NULL,
  `user_mdp` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_pseudo_UNIQUE` (`user_pseudo`),
  UNIQUE KEY `user_email_UNIQUE` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `user_firstname`, `user_lastname`, `user_pseudo`, `user_email`, `user_status`, `user_mdp`) VALUES
(3, 'Gilles', 'Lemire', NULL, 'gilles@gmail.com', 2, 'a52a99f32ab4146da016e205a6f67ebb20ff6102df53b4d0b39c9432f45d455bc6a43cbc93ee7b805d5315cdc9abbb0a6e15fdc4e1f7ea447a3dd2a0a47eee97'),
(4, 'Louis', 'Lemire', NULL, 'lo@gmail.com', 1, 'a52a99f32ab4146da016e205a6f67ebb20ff6102df53b4d0b39c9432f45d455bc6a43cbc93ee7b805d5315cdc9abbb0a6e15fdc4e1f7ea447a3dd2a0a47eee97'),
(5, 'Lise', 'Lesage', NULL, 'lise@gmail.com', 3, 'a52a99f32ab4146da016e205a6f67ebb20ff6102df53b4d0b39c9432f45d455bc6a43cbc93ee7b805d5315cdc9abbb0a6e15fdc4e1f7ea447a3dd2a0a47eee97'),
(6, 'Jean', 'Trembaly', NULL, 'jean@gmail.com', 2, 'a52a99f32ab4146da016e205a6f67ebb20ff6102df53b4d0b39c9432f45d455bc6a43cbc93ee7b805d5315cdc9abbb0a6e15fdc4e1f7ea447a3dd2a0a47eee97'),
(7, 'Pierre', 'Gagné', NULL, 'pierre@gmail.com', NULL, 'a52a99f32ab4146da016e205a6f67ebb20ff6102df53b4d0b39c9432f45d455bc6a43cbc93ee7b805d5315cdc9abbb0a6e15fdc4e1f7ea447a3dd2a0a47eee97');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `encheres`
--
ALTER TABLE `encheres`
  ADD CONSTRAINT `Users_user_id` FOREIGN KEY (`Users_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `offres`
--
ALTER TABLE `offres`
  ADD CONSTRAINT `fk_User_has_Encheres_Encheres1` FOREIGN KEY (`Offres_enchere_id`) REFERENCES `encheres` (`enchere_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_User_has_Encheres_User` FOREIGN KEY (`Offres_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `timbres`
--
ALTER TABLE `timbres`
  ADD CONSTRAINT `fk_Timbre_Pays1` FOREIGN KEY (`Pays_pays_id`) REFERENCES `pays` (`pays_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Timbres_Certifications1` FOREIGN KEY (`Certifications_certification_id`) REFERENCES `certifications` (`certification_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Timbres_Conditions1` FOREIGN KEY (`Conditions_condition_id`) REFERENCES `conditions` (`condition_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Timbres_Encheres1` FOREIGN KEY (`Encheres_enchere_id`) REFERENCES `encheres` (`enchere_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
