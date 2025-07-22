<?php
require 'vendor/autoload.php';

echo "✅ Autoloader chargé\n";

try {
    $router = new \StacGate\Core\Router();
    echo "✅ Router instancié\n";
    
    $controller = new \StacGate\Controllers\HomeController();
    echo "✅ HomeController instancié\n";
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}
