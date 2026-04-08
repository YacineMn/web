-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 08 avr. 2026 à 22:29
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
-- Base de données : `recettes_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `ingredients`
--

CREATE TABLE `ingredients` (
  `id` int(10) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'default_ingredient.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ingredients`
--

INSERT INTO `ingredients` (`id`, `nom`, `image`) VALUES
(2, 'Oeufs', '69d03b3fab807.jpg'),
(3, 'Beurre', '69d03b4da1377.jpg'),
(4, 'Lait', '69d03b5c4d84b.jpg'),
(5, 'Sucre', '69d03b32746e5.jpg'),
(6, 'Chocolat noir', '69d03b285a83b.jpg'),
(7, 'Sel', '69d03b1a64525.jpg'),
(8, 'Levure chimique', '69d6b8ca12fee.jpg'),
(10, 'amande', '69cfc5a0eb0f6.jpg'),
(12, 'El Mordjene', '69cf6f572ab77.jpg'),
(20, 'vanille', '69cfc6df03827.jpg'),
(22, 'courgette', '69cfc6fccf712.jpg'),
(23, 'tomate', '69d6b92d6079f.jpg'),
(24, 'Farine', '69d03ae6cfc3e.jpg'),
(26, 'semoule', '69d04bd80745e.jpg'),
(27, 'petit pois', '69d04bd8093d1.jpg'),
(28, 'oignons', '69d6b99b5685a.jpg'),
(29, 'miel', '69d04c5760e85.jpg'),
(30, 'poivrons', '69d0fafab3ff2.jpg'),
(31, 'huile d\'olive', '69d0fafab4527.jpg'),
(35, 'ail', '69d6b2d7b8615.jpg'),
(36, 'poivre noir', '69d6b2d7ba00c.jpg'),
(37, 'pommes de terres', '69d6b2d7bb55a.jpg'),
(38, 'Coquilles de pâtes', '69d6b67b52866.jpg'),
(39, 'epinards', '69d6b67b55501.jpg'),
(40, 'parmesan', '69d6b67b56922.jpg'),
(41, 'Sauce tomate', '69d6b67b57a67.jpg'),
(42, 'mozarella', '69d6b7b9c5227.jpg'),
(43, 'basilic', '69d6b7b9c6386.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `recettes`
--

CREATE TABLE `recettes` (
  `id` int(10) UNSIGNED NOT NULL,
  `titre` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(255) NOT NULL DEFAULT 'default_recette.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `recettes`
--

INSERT INTO `recettes` (`id`, `titre`, `description`, `photo`, `created_at`) VALUES
(1, 'Crêpes classiques', 'Des crêpes légères et dorées, parfaites pour le goûter ou le brunch. Servir avec de la confiture, du Nutella ou du sucre citron .', '69cfc43c53c77.jpg', '2026-03-27 10:17:45'),
(2, 'Fondant au chocolat', 'Un gâteau au chocolat au coeur coulant et fondant. Idéal servi tiède avec une boule de glace vanille.', '69cfc1932e549.jpg', '2026-03-27 10:17:45'),
(4, 'Dziriyat', 'De délicieuses pâtisseries algériennes en forme de petits paniers, garnies d’une farce fondante aux amandes parfumée à la fleur d’oranger. Parfaites pour accompagner un thé ou un café lors des fêtes ou moments gourmands.', '69d18f4e8afde.jpg', '2026-04-02 21:37:52'),
(10, 'Ameqful', 'Un plat traditionnel kabyle à base de semoule roulée à la main, préparé avec des légumes et des pois chiches dans un bouillon parfumé. Il est généralement servi sans viande, ce qui en fait un plat simple, végétarien et très authentique.', '69d04bd804549.jpg', '2026-04-03 23:23:04'),
(11, 'chlita', 'Un plat traditionnel kabyle à base de poivrons grillés, finement écrasés et mélangés avec de l’ail, de l’huile d’olive et des épices. Savoureux et légèrement relevé, il est souvent dégusté avec du pain, en accompagnement ou en plat simple et convivial.', '69d0fafab349c.jpg', '2026-04-04 11:50:18'),
(13, 'Aligot', 'L’aligot est une spécialité traditionnelle du sud de la France (Aubrac). Il s’agit d’une purée de pomme de terre mélangée à de la tome fraîche, avec de l’ail, de la crème et du beurre.\r\nLe résultat est une préparation très filante, riche et onctueuse.', '69d6b32e8b1ee.jpg', '2026-04-08 19:56:07'),
(14, 'Epinards coquilles ricotta', 'Un plat de pâtes gourmand et crémeux, combinant des épinards fondants avec de la ricotta, le tout garni dans des coquilles de pâtes (conchiglioni) et gratiné au four.', '69d6b85c98e3a.jpg', '2026-04-08 20:11:39'),
(15, 'Pizza', 'La pizza est un plat italien composé d’une base de pâte à pizza garnie de sauce tomate, de mozzarella et d’autres ingrédients au choix, puis cuite au four pour obtenir une texture croustillante et fondante.', '69d6b842a7b3c.jpg', '2026-04-08 20:16:57');

-- --------------------------------------------------------

--
-- Structure de la table `recette_ingredients`
--

CREATE TABLE `recette_ingredients` (
  `recette_id` int(10) UNSIGNED NOT NULL,
  `ingredient_id` int(10) UNSIGNED NOT NULL,
  `quantite` varchar(60) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `recette_ingredients`
--

INSERT INTO `recette_ingredients` (`recette_id`, `ingredient_id`, `quantite`) VALUES
(1, 2, ''),
(1, 3, ''),
(1, 8, ''),
(4, 5, ''),
(4, 7, ''),
(4, 10, ''),
(4, 24, ''),
(4, 29, ''),
(10, 7, ''),
(10, 26, ''),
(10, 27, ''),
(10, 28, ''),
(11, 23, ''),
(11, 30, ''),
(11, 31, ''),
(13, 3, ''),
(13, 7, ''),
(13, 35, ''),
(13, 36, ''),
(13, 37, ''),
(14, 7, ''),
(14, 31, ''),
(14, 35, ''),
(14, 36, ''),
(14, 38, ''),
(14, 39, ''),
(14, 40, ''),
(14, 41, ''),
(15, 7, ''),
(15, 24, ''),
(15, 31, ''),
(15, 41, ''),
(15, 42, ''),
(15, 43, '');

-- --------------------------------------------------------

--
-- Structure de la table `recette_tags`
--

CREATE TABLE `recette_tags` (
  `recette_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `recette_tags`
--

INSERT INTO `recette_tags` (`recette_id`, `tag_id`) VALUES
(2, 3),
(2, 4),
(4, 21),
(4, 22),
(11, 3),
(13, 26),
(13, 27),
(14, 2),
(14, 26),
(14, 28),
(14, 29),
(15, 29),
(15, 30),
(15, 31);

-- --------------------------------------------------------

--
-- Structure de la table `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `nom` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tags`
--

INSERT INTO `tags` (`id`, `nom`) VALUES
(4, 'au four'),
(16, 'au froid'),
(20, 'bon'),
(27, 'chaud'),
(28, 'cremeux'),
(1, 'dessert'),
(31, 'facile'),
(30, 'gourmand'),
(26, 'onctueux'),
(3, 'rapide'),
(29, 'reconfortant'),
(18, 'simple'),
(22, 'sucrée'),
(21, 'traditionnel'),
(19, 'varié'),
(2, 'végétarien');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Index pour la table `recettes`
--
ALTER TABLE `recettes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `recette_ingredients`
--
ALTER TABLE `recette_ingredients`
  ADD PRIMARY KEY (`recette_id`,`ingredient_id`),
  ADD KEY `fk_ri_ingredient` (`ingredient_id`);

--
-- Index pour la table `recette_tags`
--
ALTER TABLE `recette_tags`
  ADD PRIMARY KEY (`recette_id`,`tag_id`),
  ADD KEY `fk_rt_tag` (`tag_id`);

--
-- Index pour la table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `recettes`
--
ALTER TABLE `recettes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `recette_ingredients`
--
ALTER TABLE `recette_ingredients`
  ADD CONSTRAINT `fk_ri_ingredient` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ri_recette` FOREIGN KEY (`recette_id`) REFERENCES `recettes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `recette_tags`
--
ALTER TABLE `recette_tags`
  ADD CONSTRAINT `fk_rt_recette` FOREIGN KEY (`recette_id`) REFERENCES `recettes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rt_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
