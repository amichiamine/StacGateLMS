-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 18 juil. 2025 à 23:49
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
-- Base de données : `stacgatelms_dev`
--
CREATE DATABASE IF NOT EXISTS `stacgatelms_dev` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `stacgatelms_dev`;

-- --------------------------------------------------------

--
-- Structure de la table `app_settings`
--

DROP TABLE IF EXISTS `app_settings`;
CREATE TABLE `app_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(255) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `app_settings`
--

INSERT INTO `app_settings` (`id`, `setting_key`, `setting_value`, `updated_at`) VALUES
(1, 'homepage_site_info', '<h1>StacGate LMS</h1><p>By Amine Amichi - ELearning Platform</p>', '2025-07-17 17:13:01'),
(2, 'homepage_logo_url', '/StacGateLMS/public/assets/images/logo2.png', '2025-07-17 00:44:13'),
(5, 'main_menu_json', '[{\"url\":\"http://localhost/StacGateLMS/public/\",\"label\":\"Accueil\"},{\"url\":\"/about\",\"label\":\"À propos\"},{\"url\":\"/contact\",\"label\":\"Contactez-nous\"},{\"url\":\"/blog\",\"label\":\"Blog\"},\r\n{\"url\":\"http://localhost/StacGateLMS/public/admin/settings\",\"label\":\"Settings\"}]', '2025-07-18 20:57:22'),
(6, 'homepage_welcome_html', '<h2>Bienvenue sur StacGate LMS</h2>\r\n<p>By MagSteam E-Learning, Apprentissage virtuelle, flexible et évolutive. Vous retrouverez bientôt vos formations, suivis et outils pédagogiques. HIIIII</p>\r\n<a href=\"http://localhost/StacGateLMS/public/admin/settings\" class=\"btn-primary\">Teste</a>', '2025-07-18 20:54:25'),
(7, 'carousel_enabled', '1', '2025-07-18 18:21:10'),
(8, 'carousel_height', '400px', '2025-07-17 18:38:10'),
(9, 'carousel_width', '1200px', '2025-07-18 18:27:13'),
(10, 'carousel_slides_json', '[{\"img\":\"/StacGateLMS/public/assets/images/carousel/slide1.jpg\",\"title\":\"Titre dynamique 1\",\"desc\":\"Description dynamique 1\",\"btn\":\"En savoir plus\",\"link\":\"#\"},{\"img\":\"/StacGateLMS/public/assets/images/carousel/slide2.jpg\",\"title\":\"Titre dynamique 2\",\"desc\":\"Description dynamique 2\",\"btn\":\"En savoir plus\",\"link\":\"#\"}]', '2025-07-17 18:38:57'),
(110, 'header_bg_type', 'gradient', '2025-07-18 21:16:13'),
(111, 'header_bg_color', '#174b99', '2025-07-18 16:11:32'),
(112, 'header_bg_gradient', 'linear-gradient(90deg, #174b99 0%, #25b461 100%)', '2025-07-18 16:11:32'),
(113, 'header_bg_image', '/StacGateLMS/public/assets/images/bgimg_687ab9942656c.jpg', '2025-07-18 21:16:04'),
(114, 'header_font_color', '#ffffff', '2025-07-18 16:11:32'),
(115, 'header_font_size', '25px', '2025-07-18 18:28:07'),
(116, 'header_font_family', 'Montserrat, Arial, sans-serif', '2025-07-18 16:11:32'),
(117, 'body_bg_type', 'gradient', '2025-07-18 21:16:45'),
(118, 'body_bg_color', '#e9f5ff', '2025-07-18 16:11:32'),
(119, 'body_bg_gradient', 'linear-gradient(90deg, #f2ff42 0%, #124427 100%)', '2025-07-18 21:32:20'),
(120, 'body_bg_image', '/StacGateLMS/public/assets/images/bgimg_687ab9ae83538.jpg', '2025-07-18 21:16:30'),
(121, 'body_font_color', '#142436', '2025-07-18 16:11:32'),
(122, 'body_font_size', '16px', '2025-07-18 16:11:32'),
(123, 'body_font_family', 'Montserrat, Arial, sans-serif', '2025-07-18 16:11:32'),
(124, 'footer_bg_type', 'gradient', '2025-07-18 20:27:45'),
(125, 'footer_bg_color', '#142436', '2025-07-18 19:33:51'),
(126, 'footer_bg_gradient', 'linear-gradient(90deg, #dd2a1d 0%, #25b461 100%)', '2025-07-18 19:33:32'),
(127, 'footer_bg_image', '/StacGateLMS/public/assets/images/bgimg_687aa196efc0a.jpg', '2025-07-18 19:33:42'),
(128, 'footer_font_color', '#ffffff', '2025-07-18 16:11:32'),
(129, 'footer_font_size', '35px', '2025-07-18 18:30:13'),
(130, 'footer_font_family', 'Montserrat, Arial, sans-serif', '2025-07-18 16:11:32');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(80) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` varchar(15) DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `role`) VALUES
(1, 'admin', '$2y$10$SmQpCRu.HEETF1WSnqFSPezm2Mowc6CRiyJZAEbutnGD4nsi7VZ/2', 'admin');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
