<?php
/**
 * CONFIGURATION BASE DE DONNÉES - FICHIER EXEMPLE
 * 
 * Ce fichier sert de modèle pour la configuration de la base de données.
 * Copiez ce fichier vers database.php et modifiez les paramètres
 * selon votre environnement local/production.
 * 
 * Sécurité :
 * - Ce fichier .example.php est versionné dans Git
 * - Le fichier database.php réel est dans .gitignore
 * - Ne jamais committer de vrais mots de passe
 * 
 * @package StacGate\Config
 * @author Votre Équipe StacGate
 * @version 1.0.0
 */

return [
    // === CONNEXION MYSQL/MARIADB ===
    
    /**
     * Hôte de la base de données
     * En local : 'localhost' ou '127.0.0.1'
     * En production : IP ou nom du serveur MySQL
     */
    'host' => 'localhost',
    
    /**
     * Port de connexion MySQL
     * Standard : 3306
     * XAMPP/WAMP : généralement 3306
     */
    'port' => 3306,
    
    /**
     * Nom de la base de données StacGateLMS
     * Créez cette base via phpMyAdmin ou ligne de commande :
     * CREATE DATABASE stacgatelms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
     */
    'database' => 'stacgatelms',
    
    /**
     * Nom d'utilisateur MySQL
     * Local : souvent 'root'
     * Production : compte dédié avec droits limités
     */
    'username' => 'your_username',
    
    /**
     * Mot de passe utilisateur MySQL
     * IMPORTANT : Remplacez par votre vrai mot de passe !
     * Ne laissez jamais vide en production
     */
    'password' => 'your_password',
    
    /**
     * Jeu de caractères (charset)
     * utf8mb4 : Support complet Unicode, emojis, caractères spéciaux
     * Recommandé pour toutes les applications modernes
     */
    'charset' => 'utf8mb4',
    
    /**
     * Collation de base de données
     * unicode_ci : Insensible à la casse et aux accents
     * Idéal pour applications internationales
     */
    'collation' => 'utf8mb4_unicode_ci',
    
    // === OPTIONS PDO AVANCÉES ===
    
    /**
     * Options PDO pour sécurité et performance
     * Ces paramètres sont cruciaux pour la sécurité de l'application
     */
    'options' => [
        /**
         * Mode d'erreur : lancer des exceptions
         * Permet une gestion propre des erreurs SQL
         */
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        
        /**
         * Mode de récupération par défaut : tableau associatif
         * Plus pratique pour manipuler les résultats
         */
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        
        /**
         * Désactive l'émulation des requêtes préparées
         * Utilise les vraies prepared statements du serveur
         * Sécurité renforcée contre les injections SQL
         */
        PDO::ATTR_EMULATE_PREPARES => false,
        
        /**
         * Force la conversion des types MySQL vers PHP
         * Les entiers restent des entiers, pas des chaînes
         */
        PDO::ATTR_STRINGIFY_FETCHES => false,
        
        /**
         * Connexions persistantes (optionnel)
         * Améliore les performances mais consomme plus de mémoire
         * Décommentez si nécessaire en production
         */
        // PDO::ATTR_PERSISTENT => true,
    ],
    
    // === CONFIGURATION AVANCÉE ===
    
    /**
     * Préfixe des tables (optionnel)
     * Utile si plusieurs applications partagent la même BDD
     */
    'prefix' => 'sg_',
    
    /**
     * Timeout de connexion (secondes)
     * Évite les blocages si le serveur MySQL est lent
     */
    'timeout' => 10,
    
    /**
     * Configuration SSL (pour connexions sécurisées)
     * Nécessaire si MySQL nécessite SSL
     */
    'ssl' => [
        'enabled' => false,
        'ca_cert' => '/path/to/ca-cert.pem',
        'client_cert' => '/path/to/client-cert.pem',
        'client_key' => '/path/to/client-key.pem',
    ],
];
