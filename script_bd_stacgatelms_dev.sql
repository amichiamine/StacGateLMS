-- 1. Création de la base de données
CREATE DATABASE IF NOT EXISTS stacgatelms_dev CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE stacgatelms_dev;

-- 2. Table des paramètres personnalisables
CREATE TABLE IF NOT EXISTS app_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(255) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 3. Paramètre par défaut pour la page d’accueil
INSERT INTO app_settings (setting_key, setting_value)
VALUES ('homepage_site_info', '<h1>StacGateLMS</h1><p>Votre plateforme d’apprentissage</p>')
ON DUPLICATE KEY UPDATE setting_value = setting_value;
