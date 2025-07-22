-- ================================================================
-- 000_create_stacgatelms_mysqldb.sql
-- Création de la base de données StacGateLMS
-- ================================================================

-- Sécurité : interrompt l’exécution en cas d’erreur
SET sql_mode = 'STRICT_ALL_TABLES';

-- Crée la base si elle n’existe pas déjà
CREATE DATABASE IF NOT EXISTS stacgatelms
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- Sélectionne la base fraîchement créée (facultatif pour les scripts suivants)
USE stacgatelms;

-- Fin du script
