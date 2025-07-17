<?php
namespace App\Helpers;

class Setting {
    private static $db;

    public static function init(\PDO $db) {
        self::$db = $db;
    }

    public static function get(string $key, string $default = 'StacGate LMS par MagSteam E-Learning Platform') {
    if (!isset(self::$db)) {
        return $default;
    }
    $stmt = self::$db->prepare('SELECT setting_value FROM app_settings WHERE setting_key = ?');
    $stmt->execute([$key]);
    $result = $stmt->fetchColumn();

    // ===> CORRECTION ICI :
    // Si $result vaut NULL, false ou '', on retourne la valeur par défaut
    if ($result === false || trim((string)$result) === '') {
        return $default;
    }
    return $result;
}

}
