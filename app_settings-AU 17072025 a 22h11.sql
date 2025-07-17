-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 17 juil. 2025 à 23:10
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
(5, 'main_menu_json', '[{\"url\":\"/\",\"label\":\"Accueil\"},{\"url\":\"/about\",\"label\":\"À propos\"},{\"url\":\"/contact\",\"label\":\"Contactez-nous\"},{\"url\":\"/blog\",\"label\":\"Blog\"}]', '2025-07-17 17:17:10'),
(6, 'homepage_welcome_html', '<h2>Bienvenue sur StacGateL MS</h2><p>By MagSteam E-Learning, Apprentissage virtuelle, flexible et évolutive.</p><p>Vous retrouverez bientôt vos formations, suivis et outils pédagogiques.</p><a href=\"/StacGateLMS/public/salut\">Accéder à la zone de test</a>', '2025-07-17 17:19:51'),
(7, 'carousel_enabled', '1', '2025-07-17 18:38:30'),
(8, 'carousel_height', '400px', '2025-07-17 18:38:10'),
(9, 'carousel_width', '1200px', '2025-07-17 18:37:57'),
(10, 'carousel_slides_json', '[{\"img\":\"/StacGateLMS/public/assets/images/carousel/slide1.jpg\",\"title\":\"Titre dynamique 1\",\"desc\":\"Description dynamique 1\",\"btn\":\"En savoir plus\",\"link\":\"#\"},{\"img\":\"/StacGateLMS/public/assets/images/carousel/slide2.jpg\",\"title\":\"Titre dynamique 2\",\"desc\":\"Description dynamique 2\",\"btn\":\"En savoir plus\",\"link\":\"#\"}]', '2025-07-17 18:38:57'),
(20, 'css_var_text_dark', '#212529', '2025-07-17 19:52:47'),
(21, 'css_var_radius', '18px', '2025-07-17 19:52:47'),
(22, 'css_var_spacing_md', '24px', '2025-07-17 19:52:47'),
(28, 'header_bg_value', '#1976d2', '2025-07-17 19:52:47'),
(29, 'content_bg_type', 'gradient', '2025-07-17 19:52:47'),
(30, 'content_bg_value', 'linear-gradient(120deg, #1cb5e0 0%, #000851 100%)', '2025-07-17 19:52:47'),
(31, 'footer_bg_type', 'solid', '2025-07-17 19:52:47'),
(32, 'footer_bg_value', '#1976d2', '2025-07-17 19:52:47'),
(33, 'theme_body_bg_type', 'gradient', '2025-07-17 20:33:28'),
(34, 'theme_body_bg_solid', '#ffffff', '2025-07-17 20:31:39'),
(35, 'theme_body_bg_gradient_start', '#2563eb', '2025-07-17 20:33:28'),
(36, 'theme_body_bg_gradient_end', '#22d3ee', '2025-07-17 20:33:28'),
(37, 'theme_body_bg_gradient_direction', 'to right', '2025-07-17 20:33:28'),
(38, 'theme_body_bg_image_url', '', '2025-07-17 20:31:39'),
(39, 'theme_body_bg_image_position', 'center center', '2025-07-17 20:31:39'),
(40, 'theme_body_bg_image_size', 'cover', '2025-07-17 20:31:39'),
(41, 'theme_body_bg_image_repeat', 'no-repeat', '2025-07-17 20:31:39'),
(42, 'theme_header_bg_type', 'gradient', '2025-07-17 20:33:38'),
(43, 'theme_header_bg_solid', '#1976d2', '2025-07-17 20:31:39'),
(44, 'theme_header_bg_gradient_start', '#2563eb', '2025-07-17 20:33:38'),
(45, 'theme_header_bg_gradient_end', '#34d399', '2025-07-17 20:33:38'),
(46, 'theme_header_bg_gradient_direction', 'to right', '2025-07-17 20:33:38'),
(47, 'theme_header_bg_image_url', '', '2025-07-17 20:31:39'),
(48, 'theme_header_bg_image_position', 'center center', '2025-07-17 20:31:39'),
(49, 'theme_header_bg_image_size', 'cover', '2025-07-17 20:31:39'),
(50, 'theme_header_bg_image_repeat', 'no-repeat', '2025-07-17 20:31:39'),
(51, 'theme_header_text_color', '#fff', '2025-07-17 20:31:39'),
(52, 'theme_header_border_color', 'none', '2025-07-17 20:31:39'),
(53, 'theme_footer_bg_type', 'solid', '2025-07-17 20:31:39'),
(54, 'theme_footer_bg_solid', '#1976d2', '2025-07-17 20:31:39'),
(55, 'theme_footer_bg_gradient_start', '#1976d2', '2025-07-17 20:31:39'),
(56, 'theme_footer_bg_gradient_end', '#1565c0', '2025-07-17 20:31:39'),
(57, 'theme_footer_bg_gradient_direction', 'to top', '2025-07-17 20:31:39'),
(58, 'theme_footer_bg_image_url', '', '2025-07-17 20:31:39'),
(59, 'theme_footer_bg_image_position', 'center center', '2025-07-17 20:31:39'),
(60, 'theme_footer_bg_image_size', 'cover', '2025-07-17 20:31:39'),
(61, 'theme_footer_bg_image_repeat', 'no-repeat', '2025-07-17 20:31:39'),
(62, 'theme_footer_text_color', '#fff', '2025-07-17 20:31:39'),
(63, 'theme_footer_link_color', '#fff', '2025-07-17 20:31:39'),
(64, 'theme_footer_link_hover', '#22d3ee', '2025-07-17 20:34:15'),
(65, 'theme_footer_border_color', 'none', '2025-07-17 20:31:39'),
(66, 'theme_card_bg', '#2563eb', '2025-07-17 20:34:07'),
(67, 'theme_card_text_color', '#fff', '2025-07-17 20:34:07');

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
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
