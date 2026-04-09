-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 09 avr. 2026 à 23:19
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
(43, 'basilic', '69d6b7b9c6386.jpg'),
(44, 'cacao', '69d7de5c0683f.jpg'),
(45, 'mascarpone', '69d7de5c09b59.jpg'),
(46, 'viande de boeuf', '69d7e0f21ca87.jpg'),
(47, 'champignon', '69d7e0f21eb56.jpg'),
(48, 'huile tournesol', '69d7e0f221661.jpg'),
(49, 'Vin rouge', '69d7e0f22376a.jpg'),
(50, 'Carotte', '69d7e0f2259da.jpg'),
(51, 'Lardons', '69d7e0f2279aa.jpg'),
(52, 'viande hachée', '69d7e35e7614f.jpg'),
(53, 'Feuilles chou', '69d7e35e78b0d.jpg'),
(54, 'Sauce soja', '69d7e35e7acf3.jpg'),
(55, 'Pâte à gyoza', '69d7e35e7ce15.jpg'),
(56, 'Fromage râpé', '69d7e4fc0df60.jpg'),
(57, 'Baguette de pain', '69d7e4fc0fcf0.jpg');

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
(15, 'Pizza', 'La pizza est un plat italien composé d’une base de pâte à pizza garnie de sauce tomate, de mozzarella et d’autres ingrédients au choix, puis cuite au four pour obtenir une texture croustillante et fondante.', '69d6b842a7b3c.jpg', '2026-04-08 20:16:57'),
(16, 'Tiramisu', 'Le tiramisu est un dessert italien crémeux à base de mascarpone, de café et de biscuits à la cuillère, le tout saupoudré de cacao. Il se prépare sans cuisson et doit reposer au frais pour développer ses saveurs.', '69d7de5bf3e91.jpg', '2026-04-09 17:14:04'),
(17, 'Bœuf bourguignon', 'Le bœuf bourguignon est un grand classique de la cuisine française. C’est un plat mijoté à base de bœuf, cuit lentement dans du vin rouge avec des légumes, pour une viande tendre et une sauce riche.', '69d7e0f21652a.jpg', '2026-04-09 17:25:06'),
(18, 'Gyozas', 'Les gyozas sont des raviolis japonais croustillants et fondants, garnis de viande ou de légumes, puis cuits à la poêle avec un peu d’eau pour une texture à la fois dorée et moelleuse.', '69d7e35e70fbe.jpg', '2026-04-09 17:35:26'),
(19, 'Soupe à l’oignon', 'La soupe à l’oignon est une spécialité française traditionnelle, à base d’oignon longuement caramélisés, de bouillon et gratinée avec du fromage sur du pain. C’est un plat chaud, simple et très réconfortant.', '69d7e4fc08e1b.jpg', '2026-04-09 17:42:20');

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
(11, 7, ''),
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
(15, 43, ''),
(16, 2, ''),
(16, 5, ''),
(16, 44, ''),
(16, 45, ''),
(17, 7, ''),
(17, 24, ''),
(17, 28, ''),
(17, 35, ''),
(17, 36, ''),
(17, 46, ''),
(17, 47, ''),
(17, 48, ''),
(17, 49, ''),
(17, 50, ''),
(17, 51, ''),
(18, 7, ''),
(18, 35, ''),
(18, 36, ''),
(18, 48, ''),
(18, 52, ''),
(18, 53, ''),
(18, 54, ''),
(18, 55, ''),
(19, 3, ''),
(19, 7, ''),
(19, 24, ''),
(19, 28, ''),
(19, 36, ''),
(19, 56, ''),
(19, 57, '');

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
(11, 2),
(11, 3),
(11, 18),
(11, 31),
(11, 32),
(11, 38),
(13, 26),
(13, 27),
(14, 2),
(14, 26),
(14, 28),
(14, 29),
(15, 29),
(15, 30),
(15, 31),
(16, 1),
(16, 3),
(16, 16),
(16, 22),
(16, 26),
(16, 30),
(16, 32),
(17, 27),
(17, 33),
(17, 34),
(17, 35),
(17, 36),
(18, 3),
(18, 18),
(18, 37),
(18, 38),
(19, 18),
(19, 27),
(19, 29),
(19, 33),
(19, 35);

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
(37, 'croustillant'),
(1, 'dessert'),
(31, 'facile'),
(33, 'familial'),
(30, 'gourmand'),
(32, 'leger'),
(26, 'onctueux'),
(3, 'rapide'),
(29, 'reconfortant'),
(34, 'riche'),
(38, 'Salé'),
(35, 'savoureux'),
(18, 'simple'),
(22, 'sucrée'),
(36, 'tendre'),
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT pour la table `recettes`
--
ALTER TABLE `recettes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
