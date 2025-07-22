<?php
/**
 * CONFIGURATION GÉNÉRALE APPLICATION - FICHIER EXEMPLE
 * 
 * Paramètres globaux de l'application StacGateLMS
 * Copiez vers app_settings.php et personnalisez
 * 
 * @package StacGate\Config
 */

return [
    // === INFORMATIONS GÉNÉRALES ===
    'app_name' => 'StacGateLMS',
    'app_version' => '1.0.0',
    'app_description' => 'Plateforme LMS modulaire et personnalisable',
    
    // === ENVIRONNEMENT ===
    'environment' => 'development', // development, staging, production
    'debug' => true,
    
    // === SÉCURITÉ ===
    'session_lifetime' => 7200, // 2 heures en secondes
    'csrf_token_name' => '_token',
    'password_min_length' => 8,
    
    // === PATHS ET URLS ===
    'base_url' => 'http://localhost/stacgatelms',
    'upload_path' => 'storage/uploads',
    'max_upload_size' => '10M',
    
    // === PAGINATION ===
    'items_per_page' => 20,
    
    // === THÈME PAR DÉFAUT ===
    'default_theme' => 'stacgate',
    'primary_color' => '#3498db',
    'secondary_color' => '#2ecc71',
];
