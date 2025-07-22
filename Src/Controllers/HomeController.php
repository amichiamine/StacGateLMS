<?php
/**
 * CONTRÔLEUR DE DÉMONSTRATION
 * Montre le fonctionnement du Router avec deux actions simples.
 */

namespace stacgate\Controllers;

class HomeController
{
    /**
     * Méthode appelée pour la route GET /
     * Affiche simplement un message de bienvenue.
     */
    public function index(): void
    {
        echo '<h1>Bienvenue sur StacGateLMS !</h1>';
    }

    /**
     * Méthode appelée pour la route GET /hello/{name}
     * @param string $name Nom capturé depuis l’URL.
     */
    public function hello(string $name): void
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); // Protection XSS
        echo "<h1>Bonjour, {$safeName} !</h1>";
    }
}
