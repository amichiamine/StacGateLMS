<?php
namespace StacGate\Controllers;

use StacGate\Core\BaseController;

class HomeController extends BaseController
{
    /* Page d’accueil */
    public function index(): void
    {
        echo '<h1>Bienvenue sur StacGateLMS !</h1>';
        echo '<p>Plateforme LMS modulaire et personnalisable développée en PHP 8.</p>';
        echo '<p><a href="' . base_url('hello/Amine') . '">Tester la route dynamique</a></p>';
    }

    /* Route /hello/{name} */
    public function hello(string $name): void
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

        echo "<h1>Bonjour, {$safeName} !</h1>";
        echo '<p>Cette page démontre le fonctionnement des paramètres dynamiques.</p>';
        echo '<p><a href="' . base_url() . '">Retour à l’accueil</a></p>';
    }
}
