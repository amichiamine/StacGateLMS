<?php
/**
 * FRONT-CONTROLLER
 * Point d’entrée unique de l’application StacGateLMS.
 *
 * - Charge l’autoloader Composer.
 * - Définit le mode DEBUG (true en dev).
 * - Instancie le Router et enregistre les routes.
 * - Délègue la requête HTTP au Router.
 */

declare(strict_types=1);

use stacgate\Core\Router;
use stacgate\Controllers\HomeController;

require dirname(__DIR__) . '/vendor/autoload.php';   // Autoload PSR-4

// Active les messages d’erreur en dev
define('DEBUG', true);
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Chemin de base (laisser chaîne vide si le projet est à la racine du vhost)
$basePath = '/stacgatelms';

// --- Instanciation du Router ---
$router = new Router($basePath);

/**
 * -------------------------------------------------
 * ENREGISTREMENT DES ROUTES
 * -------------------------------------------------
 * Signature :
 *   $router->get($pattern, $controller, $method);
 *   $router->post(...);
 */
$router
    ->get('/',               'HomeController', 'index')   // Page d’accueil
    ->get('/hello/{name}',   'HomeController', 'hello');  // Route de test dynamique

// --- Lancement de la résolution ---
$router->handleRequest();
