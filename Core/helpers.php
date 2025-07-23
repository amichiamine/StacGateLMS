<?php
/**
 * Helper global : base_url()
 * Retourne l’URL absolue de l’application, quel que soit
 * le domaine OU le sous-dossier où elle est installée.
 */
if (!function_exists('base_url')) {
    function base_url(string $uri = ''): string
    {
        /* 1) protocole */
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

        /* 2) hôte (localhost ou domaine) */
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        /* 3) dossier racine du projet = dossier parent de /public */
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));     // ex.  /stacgatelms/public
        $scriptDir = preg_replace('#/public/?$#', '', $scriptDir);                // enlève "/public"
        $scriptDir = rtrim($scriptDir, '/');                                      // supprime slash final

        /* 4) URL finale */
        $base = $scheme . '://' . $host . $scriptDir;

        return $uri ? $base . '/' . ltrim($uri, '/') : $base;
    }
}
