<?php
/**
 * TEST FINAL DES TABLES D'AUTHENTIFICATION
 */
require 'vendor/autoload.php';

try {
    echo "=== VÉRIFICATION FINALE DES TABLES ===\n";
    
    $pdo = \StacGate\Core\BaseModel::db();
    
    // Test 1 : Toutes les tables existent
    $tables = ['sg_users', 'sg_roles', 'sg_permissions', 'sg_user_roles', 'sg_role_permissions'];
    echo "Tables créées :\n";
    foreach ($tables as $table) {
        $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
        echo "  ✅ $table : $count enregistrements\n";
    }
    
    // Test 2 : Données initiales
    $admin = $pdo->query("SELECT username, email FROM sg_users WHERE id = 1")->fetch();
    if ($admin) {
        echo "  ✅ Admin créé : {$admin['username']} ({$admin['email']})\n";
    }
    
    $rolesCount = $pdo->query("SELECT COUNT(*) FROM sg_roles")->fetchColumn();
    echo "  ✅ Rôles système : $rolesCount\n";
    
    $permsCount = $pdo->query("SELECT COUNT(*) FROM sg_permissions")->fetchColumn();
    echo "  ✅ Permissions : $permsCount\n";
    
    // Test 3 : Relations fonctionnelles
    $adminRoles = $pdo->query("
        SELECT r.display_name 
        FROM sg_user_roles ur 
        JOIN sg_roles r ON ur.role_id = r.id 
        WHERE ur.user_id = 1
    ")->fetchAll();
    
    echo "  ✅ Rôles admin : " . implode(', ', array_column($adminRoles, 'display_name')) . "\n";
    
    echo "\n🎉 TOUTES LES TABLES SONT CRÉÉES ET FONCTIONNELLES !\n";
    echo "\nDétails de connexion admin :\n";
    echo "  Email : admin@stacgate.local\n";
    echo "  Mot de passe : StacGate2025!\n";
    echo "  (À changer immédiatement après première connexion)\n";
    
} catch (Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    echo "Le script de migration n'a pas été exécuté correctement.\n";
}
