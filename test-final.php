<?php
require 'vendor/autoload.php';

echo "=== TEST FINAL STACGATELMS ===\n";

try {
    // Test 1: Autoloader
    echo "✅ Autoloader chargé\n";
    
    // Test 2: Router avec bon namespace
    $router = new \StacGate\Core\Router('/stacgatelms');
    echo "✅ Router instancié avec bon namespace\n";
    
    // Test 3: HomeController avec bon namespace  
    $controller = new \StacGate\Controllers\HomeController();
    echo "✅ HomeController instancié avec bon namespace\n";
    
    // Test 4: Simulation de route
    $router->get('/', 'HomeController', 'index');
    echo "✅ Route enregistrée\n";
    
    echo "\n🎉 TOUS LES TESTS PASSENT ! L'application devrait maintenant fonctionner.\n";
    
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    echo "Fichier : " . $e->getFile() . " ligne " . $e->getLine() . "\n";
}
