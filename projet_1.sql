-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 02 juin 2023 à 01:47
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet_1`
--

-- --------------------------------------------------------

--
-- Structure de la table `imprimante`
--

CREATE TABLE `imprimante` (
  `imprimante_id` int(11) NOT NULL,
  `imprimante_ref` text NOT NULL,
  `imprimante_numserie` varchar(25) NOT NULL,
  `imprimante_loc_mens_prix` float NOT NULL,
  `imprimante_nb_prix` float NOT NULL,
  `imprimante_couleur_prix` float NOT NULL,
  `imprimante_delete` int(11) NOT NULL DEFAULT 0,
  `imprimante_site` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `imprimante`
--

INSERT INTO `imprimante` (`imprimante_id`, `imprimante_ref`, `imprimante_numserie`, `imprimante_loc_mens_prix`, `imprimante_nb_prix`, `imprimante_couleur_prix`, `imprimante_delete`, `imprimante_site`) VALUES
(25, 'Ricoh P501 (43 ppm)', '50', 50, 1, 1, 0, 5),
(27, 'Ricoh IMC 3000 (30 ppm)', '15', 17.6, 2, 3, 0, 6),
(48, 'Ricoh test 35', '556', 0, 4, 1, 0, 5),
(49, 'Ricoh test 36', '78654', 0, 0.056, 0.069, 0, 5),
(50, 'Ricoh test 50', '9999', 100, 1, 2, 0, 8),
(51, 'test référence', '453', 50.9, 12, 13, 0, 5),
(52, 'Ricogh oe', '654', 0, 0.025, 0.028, 0, 5),
(53, 'Test ref', '56145', 29.6, 0.0555, 0.0666, 0, 5);

-- --------------------------------------------------------

--
-- Structure de la table `ip`
--

CREATE TABLE `ip` (
  `ip_id` int(11) NOT NULL,
  `ip_ip` text NOT NULL,
  `ip_vlan` text NOT NULL,
  `ip_site` int(11) NOT NULL,
  `ip_commentaire` text DEFAULT NULL,
  `ip_delete` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ip`
--

INSERT INTO `ip` (`ip_id`, `ip_ip`, `ip_vlan`, `ip_site`, `ip_commentaire`, `ip_delete`) VALUES
(1, '198.63.5', 'vlan ip', 5, 'aucun commentaire', 1),
(36, '12.3.56', 'tests', 9, 'sfghqdfhqdfgh', 0),
(37, '122', 'test', 6, '', 0),
(38, '1223', 'sdgf', 5, '', 0),
(39, '6636', 'dfhfdh', 5, '', 0);

-- --------------------------------------------------------

--
-- Structure de la table `options_site`
--

CREATE TABLE `options_site` (
  `site_id` int(11) NOT NULL,
  `site_nom` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `options_site`
--

INSERT INTO `options_site` (`site_id`, `site_nom`) VALUES
(5, 'Vesoul '),
(6, 'Hericourt Dentaire'),
(8, 'Lure Audio'),
(9, 'test deux'),
(10, 'Lure Optique');

-- --------------------------------------------------------

--
-- Structure de la table `ordinateur`
--

CREATE TABLE `ordinateur` (
  `pc_id` int(11) NOT NULL,
  `pc_nom` text NOT NULL,
  `pc_numserie` text NOT NULL,
  `pc_date` text NOT NULL,
  `pc_ad` int(11) NOT NULL,
  `pc_sophos` int(11) NOT NULL,
  `pc_compta` int(11) NOT NULL,
  `pc_glpi` int(11) NOT NULL,
  `pc_decheterie` int(11) NOT NULL,
  `pc_cse` int(11) NOT NULL,
  `pc_com` text NOT NULL,
  `delete_d` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ordinateur`
--

INSERT INTO `ordinateur` (`pc_id`, `pc_nom`, `pc_numserie`, `pc_date`, `pc_ad`, `pc_sophos`, `pc_compta`, `pc_glpi`, `pc_decheterie`, `pc_cse`, `pc_com`, `delete_d`) VALUES
(6, 'HER-PCBU-026', '44566', '26/06/2021', 1, 1, 1, 1, 0, 1, '', 0),
(7, 'BEM-PORT-012', '75683', '10/09/2022', 1, 1, 1, 1, 1, 0, '', 0),
(8, 'VES-PCBU-0255', '999', '25/02/2022', 1, 1, 1, 1, 1, 0, 'Pc en bon état', 1),
(12, 'LUR-PORT-036', '75463', '', 1, 0, 1, 0, 0, 0, 'Problème avec la barre espace', 1),
(20, 'VES-PCBU-0258', '568465', '', 0, 1, 1, 1, 0, 0, '', 1);

-- --------------------------------------------------------

--
-- Structure de la table `quantite_mensuel_impression`
--

CREATE TABLE `quantite_mensuel_impression` (
  `id` int(11) NOT NULL,
  `date_donnee` date NOT NULL,
  `id_imprimante` int(11) NOT NULL,
  `quantite_couleur` int(11) DEFAULT NULL,
  `quantite_nb` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `quantite_mensuel_impression`
--

INSERT INTO `quantite_mensuel_impression` (`id`, `date_donnee`, `id_imprimante`, `quantite_couleur`, `quantite_nb`) VALUES
(1, '2023-05-18', 25, 10, 25),
(2, '2020-01-03', 27, 55, 30),
(3, '2020-01-09', 49, 60, 70),
(4, '2020-01-09', 50, 60, 50);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `imprimante`
--
ALTER TABLE `imprimante`
  ADD PRIMARY KEY (`imprimante_id`),
  ADD KEY `FK_site_imprimante` (`imprimante_site`);

--
-- Index pour la table `ip`
--
ALTER TABLE `ip`
  ADD PRIMARY KEY (`ip_id`),
  ADD KEY `FK_site` (`ip_site`);

--
-- Index pour la table `options_site`
--
ALTER TABLE `options_site`
  ADD PRIMARY KEY (`site_id`);

--
-- Index pour la table `ordinateur`
--
ALTER TABLE `ordinateur`
  ADD PRIMARY KEY (`pc_id`);

--
-- Index pour la table `quantite_mensuel_impression`
--
ALTER TABLE `quantite_mensuel_impression`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_imprimante` (`id_imprimante`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `imprimante`
--
ALTER TABLE `imprimante`
  MODIFY `imprimante_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT pour la table `ip`
--
ALTER TABLE `ip`
  MODIFY `ip_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `options_site`
--
ALTER TABLE `options_site`
  MODIFY `site_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `ordinateur`
--
ALTER TABLE `ordinateur`
  MODIFY `pc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `quantite_mensuel_impression`
--
ALTER TABLE `quantite_mensuel_impression`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `imprimante`
--
ALTER TABLE `imprimante`
  ADD CONSTRAINT `FK_site_imprimante` FOREIGN KEY (`imprimante_site`) REFERENCES `options_site` (`site_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `ip`
--
ALTER TABLE `ip`
  ADD CONSTRAINT `FK_site` FOREIGN KEY (`ip_site`) REFERENCES `options_site` (`site_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `quantite_mensuel_impression`
--
ALTER TABLE `quantite_mensuel_impression`
  ADD CONSTRAINT `FK_imprimante` FOREIGN KEY (`id_imprimante`) REFERENCES `imprimante` (`imprimante_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
