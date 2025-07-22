<?php
namespace StacGate\Core;
/**
 * BASEMODEL — classe parente de tous les modèles StacGateLMS.
 *
 * Rôle :
 *  • centraliser l'accès PDO partagé (singleton) ;
 *  • exposer des helpers CRUD simples (find, all, create, update, delete) ;
 *  • permettre la surcharge dans chaque modèle métier.
 *
 * Namespace : StacGate\Core  — conforme à l'autoload PSR-4.
 */


use PDO;
use PDOException;
use RuntimeException;

class BaseModel
{
    /** @var PDO|null Connexion PDO partagée entre tous les modèles */
    private static ?PDO $pdo = null;

    /**
     * Initialise la connexion PDO si nécessaire et la retourne.
     * Les paramètres proviennent de Config/database.php.
     *
     * CORRECTION: Méthode PUBLIC pour permettre les tests externes
     *
     * @throws RuntimeException si le fichier de config est absent ou invalide
     */
    public static function db(): PDO  // ← CHANGEMENT ICI : protected → public
    {
        // Connexion déjà établie ? On la renvoie directement.
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        // Charge la configuration
        $configPath = dirname(__DIR__) . '/Config/database.php';
        if (!file_exists($configPath)) {
            throw new RuntimeException('Fichier de configuration base de données manquant.');
        }
        $cfg = require $configPath;

        // Construit le DSN MySQL
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $cfg['host'],
            $cfg['port'],
            $cfg['database'],
            $cfg['charset']
        );

        try {
            self::$pdo = new PDO($dsn, $cfg['username'], $cfg['password'], $cfg['options']);
        } catch (PDOException $e) {
            throw new RuntimeException('Connexion PDO impossible : ' . $e->getMessage());
        }

        return self::$pdo;
    }

    /* === Helpers CRUD simples (exemples) ================================ */

    /**
     * Récupère un enregistrement par clé primaire.
     */
    public static function find(int $id, string $table, string $pk = 'id'): array|false
    {
        $sql = "SELECT * FROM {$table} WHERE {$pk} = :id LIMIT 1";
        $stmt = self::db()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Retourne tous les enregistrements d'une table.
     */
    public static function all(string $table): array
    {
        return self::db()->query("SELECT * FROM {$table}")->fetchAll();
    }

    // create(), update(), delete() pourront être ajoutées plus tard.
}
