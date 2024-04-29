-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 29 avr. 2024 à 02:51
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecom1_project`
--

-- --------------------------------------------------------

--
-- Structure de la table `address`
--

DROP TABLE IF EXISTS `address`;
CREATE TABLE IF NOT EXISTS `address` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `street_name` varchar(255) NOT NULL,
  `street_nb` int NOT NULL,
  `city` varchar(40) NOT NULL,
  `province` varchar(40) NOT NULL,
  `zip_code` varchar(6) NOT NULL,
  `country` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `address`
--

INSERT INTO `address` (`id`, `street_name`, `street_nb`, `city`, `province`, `zip_code`, `country`) VALUES
(43, 'rosedalle', 34, 'montreal', 'quebec', 'h43', 'Canada'),
(41, 'ha', 12, 'sale', 'ma', 'e445', 'maroc'),
(42, 'rosedalle', 12, 'montreal', 'quebec', 'h43', 'Canada'),
(39, 'rosedalle', 12, 'montreal', 'quebec', 'h43', 'Canada'),
(40, 'walk', 13, 'montreal', 'quebec', 'f5v', 'canada'),
(44, 'rosedalle', 12, 'montreal', 'quebec', 'h43', 'Canada'),
(45, 'rosedalle', 34, 'montreal', 'quebec', 'h43', 'Canada'),
(46, 'rosedalle', 12, 'montreal', 'quebec', 'h43', 'Canada'),
(47, 'rosedalle', 12, 'montreal', 'quebec', 'h43', 'Canada'),
(48, 'rosedalle', 34, 'montreal', 'quebec', 'h43', 'Canada'),
(49, 'rosedalle', 12, 'montreal', 'quebec', 'h43', 'Canada'),
(50, 'rosedalle', 34, 'montreal', 'quebec', 'h43', 'Canada'),
(51, 'rosedalle', 12, 'montreal', 'quebec', 'h43', 'Canada'),
(52, 'rosedalle', 34, 'montreal', 'quebec', 'h43', 'Canada');

-- --------------------------------------------------------

--
-- Structure de la table `order_has_product`
--

DROP TABLE IF EXISTS `order_has_product`;
CREATE TABLE IF NOT EXISTS `order_has_product` (
  `quantity` int NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `order_id` bigint NOT NULL,
  `product_id` bigint NOT NULL,
  PRIMARY KEY (`product_id`,`order_id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `order_has_product`
--

INSERT INTO `order_has_product` (`quantity`, `price`, `order_id`, `product_id`) VALUES
(1, '3.00', 113, 32),
(67878, '3.00', 111, 32),
(11, '3.00', 116, 26),
(10, '777.00', 116, 10),
(1909, '3.00', 119, 26),
(15, '20.00', 121, 29),
(12, '14.00', 122, 28),
(1, '3.00', 129, 26),
(1, '0.00', 131, 10);

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `img_url` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`id`, `name`, `quantity`, `price`, `img_url`, `description`) VALUES
(26, 'CAHIER', 4, '3.00', '65864e7fa0a1bCAHIER.jpg', 'POUR LES ETUDIANTS '),
(10, 'rr', 3, '0.00', 'BIMO.jpg', '44'),
(28, 'VIANDE HACHEE', 45, '14.00', '65864ef527faaVIANDE.jpg', 'PAS DES DETAILS POUR L\'INSTANT'),
(27, 'poulet', 4, '2.00', '65864ec7cfa14POULET.jpg', 'POULET DU MAROC'),
(29, 'PIZZA', 23, '20.00', '65864f13d4f88PIZZA.jpg', 'PIZZA PIZZA'),
(30, 'FROMAGE ', 75, '7.00', '65864f412091ePIZZAjpg.jpg', 'FROMAGEE'),
(31, 'tonik', 4, '2.00', '65864f5b54030BIMO.jpg', 'bimo'),
(32, 'golden', 34, '3.00', '65864f7473f0bGOLDEN.jpg', 'bimo');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id`, `name`, `description`) VALUES
(1, 'SuperAdmin', 'role super administrateur'),
(3, 'client', 'role client');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `billing_address_id` bigint NOT NULL,
  `shipping_address_id` bigint NOT NULL,
  `token` varchar(255) NOT NULL,
  `role_id` bigint NOT NULL,
  `user_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`user_name`),
  KEY `role_id` (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `pwd`, `fname`, `lname`, `billing_address_id`, `shipping_address_id`, `token`, `role_id`, `user_name`) VALUES
(98, 'superadmin@admin.ca', '$2y$10$pvYufmdKSn/3teuX0DWSzud7Z5kZpyKoGF1810Stjaw1kgIefh/06', '', '', 0, 0, '', 1, 'superadmin'),
(131, 'saadboumhdi9@gmail.com', '$2y$10$9vWWmD34GNVxvhpF2dBJGOMHkmFiO3H7Gs8xlZzEAiOthogeuHADi', 'SA', 'BA', 52, 51, '', 3, 'ca'),
(119, 'saadboumhdi9@gmail.com', '$2y$10$OKwvgYr4aUa8BTXV.f14Hu8ILiqJm5zosnr4hatfamKcUXvH0rj3i', 'boumahdi', 'amri', 0, 0, '', 2, 'ma'),
(120, 'saadboumhdi9@gmail.com', '$2y$10$WdChB9tzjr0tOcvBpHVUo.bPxug2IhD3fvKY9LctKXFRU/T/JVzgG', 'boumahdi', 'bo', 0, 0, '', 3, 'na'),
(121, 'saadboumhdi9@gmail.com', '$2y$10$ByxAAiSBiRDoLeYxEmiswelw6apKrE652bSdAUmG5ayHzWSucC1xS', 'boumahdi', 'bo', 0, 0, '', 3, 'no'),
(122, 'saadboumhdi9@gmail.com', '$2y$10$LxgeAIleu7.dAL4v4M7RIe48c/5JuRRTS66V2eZy1hbb0K3wjPeUu', 'valentine', 'bo', 0, 0, '', 3, 'val'),
(123, 'saadboumhdi9@gmail.com', '$2y$10$UUNS7u368qXuiYwjzWZrvufm5jv1XhkQ9q56G5A0Og6TLGK7YRBIC', 'valentine', 'bo', 42, 41, '', 3, 'ana'),
(124, 'messi@gamil.com', '$2y$10$9ozS/w9u1pekEF.xzkdeKOWDcY1VaoDkS1i38014vnCqzH18O6Jyi', 'mw', 'mw', 0, 0, '', 3, 'mw'),
(125, 'messi@gmail.com', '$2y$10$A88rt22w8UJd5nPVRj/8.eMOa8a51pt6L8qhm3jnse4XlIIl3hL0.', 'mw', 'mw', 0, 0, '', 3, 'messi'),
(126, 'sou@gmail.com', '$2y$10$Qtc8vGZnShkizB0wEtMNSOVTPjHEAHFP.SsiNRvvvqu.CoBO7b9Bu', 'nb', 'so', 0, 0, '-', 1, 'soufian'),
(127, 'soufianbom@gmail.com', '$2y$10$KG2P5MQus4ZVt3ou7yh66.1SJRXNXBsJO0FGAdujMGr8vCdkOJ0Z2', 'so', 'so', 0, 0, '', 3, 'so'),
(128, 'messii@gamil.com', '$2y$10$RDgZ4svE3dC31GmBZMfdwu4hEzdRQ87Yx5.HU44XD/Owm6XOm14Re', '', 'messi', 0, 0, '', 3, 'ss'),
(129, 'soufianbom@gmail.com', '$2y$10$MC1FQ5Qrawi24x/MuO8lM.bGZhEvc5iorSOvwc9V0rKUf93suBVhu', 'sa', '', 0, 0, '', 3, 'root');

-- --------------------------------------------------------

--
-- Structure de la table `user_order`
--

DROP TABLE IF EXISTS `user_order`;
CREATE TABLE IF NOT EXISTS `user_order` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `ref` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `user_id` bigint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user_order`
--

INSERT INTO `user_order` (`id`, `ref`, `date`, `total`, `user_id`) VALUES
(10, '65863fad92a85', '2023-12-23', '302.50', 105),
(11, '6588cc4dbfbf0', '2023-12-25', '6993.00', 116),
(12, '65ef76125b056', '2024-03-11', '777.00', 123),
(13, '662ece111ccd0', '2024-04-28', '3.00', 131),
(14, '662ece5b11200', '2024-04-28', '140.00', 131),
(15, '662ed05802e8c', '2024-04-28', '0.00', 131),
(16, '662ed0a1785d1', '2024-04-28', '3.00', 131),
(17, '662ee9af6d1bd', '2024-04-29', '0.00', 131);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
