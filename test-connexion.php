<?php
/**
 * TEST DE CONNEXION PDO STACGATELMS
 */

require 'vendor/autoload.php';

try {
    echo "=== TEST DE CONNEXION BASE DE DONNÉES ===\n";
    
    // Test 1 : Chargement de l'autoloader
    echo "✅ Autoloader Composer chargé avec succès\n";
    
    // Test 2 : Instanciation du BaseModel
    $pdo = \StacGate\Core\BaseModel::db();
    echo "✅ Connexion PDO établie avec succès\n";
    
    // Test 3 : Test basique de requête
    $result = $pdo->query("SELECT 1 as test")->fetch();
    if ($result['test'] == 1) {
        echo "✅ Requête de test réussie\n";
    }
    
    echo "\n🎉 TOUS LES TESTS DE CONNEXION SONT PASSÉS !\n";
    echo "Votre configuration PDO est opérationnelle.\n";
    
} catch (Exception $e) {
    echo "❌ ERREUR DE CONNEXION :\n";
    echo "Message : " . $e->getMessage() . "\n";
    echo "Fichier : " . $e->getFile() . " ligne " . $e->getLine() . "\n";
    
    echo "\n🔧 VÉRIFICATIONS À EFFECTUER :\n";
    echo "1. MySQL/MariaDB est-il démarré dans XAMPP ?\n";
    echo "2. La base 'stacgatelms' existe-t-elle ?\n";
    echo "3. Les identifiants dans Config/database.php sont-ils corrects ?\n";
}
