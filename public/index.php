<?php
/**
 * FRONT-CONTROLLER – point d’entrée unique
 */

declare(strict_types=1);

use StacGate\Core\Router;

/* Autoload + helper */
require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/core/helpers.php';

/* DEBUG */
define('DEBUG', true);
error_reporting(E_ALL);
ini_set('display_errors', '1');

/* BasePath dynamique pour le Router (ex.  /stacgatelms   ou   '') */
$basePath = preg_replace('#/public/?$#', '', dirname($_SERVER['SCRIPT_NAME']));
$router   = new Router($basePath);

/* -------------------------------------------------
 * Enregistrement des routes
 * ------------------------------------------------- */
$router
    ->get('/',              'HomeController', 'index')
    ->get('/hello/{name}',  'HomeController', 'hello');

/* Lancement */
$router->handleRequest();
