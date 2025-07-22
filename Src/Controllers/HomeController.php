<?php
/**
 * CONTRÔLEUR DE DÉMONSTRATION STACGATELMS
 * 
 * Ce contrôleur montre le fonctionnement du Router avec deux actions simples :
 * - Page d'accueil de démonstration
 * - Page de salutation dynamique avec paramètre URL
 * 
 * Namespace : StacGate\Controllers (PSR-4 compliant)
 * 
 * @package StacGate\Controllers
 * @author Amine AMICHI
 * @version 1.0.0
 */

namespace StacGate\Controllers; // CORRECTION: Namespace cohérent avec composer.json

use StacGate\Core\BaseController;
/**
 * Classe HomeController - Contrôleur principal de démonstration
 * 
 * Ce contrôleur gère les routes de base de l'application :
 * - Route "/" : page d'accueil
 * - Route "/hello/{name}" : page de salutation personnalisée
 */
class HomeController extends BaseController
{
    /**
     * Méthode appelée pour la route GET "/"
     * 
     * Affiche la page d'accueil de StacGateLMS avec un message de bienvenue.
     * Cette méthode sera appelée automatiquement par le Router quand 
     * l'utilisateur accède à http://localhost/stacgatelms/
     * 
     * @return void Affiche directement le contenu HTML
     */
    public function index(): void
    {
        // Affichage simple de la page d'accueil
        // TODO: Plus tard, ceci sera remplacé par un système de templates
        echo '<h1>Bienvenue sur StacGateLMS !</h1>';
        echo '<p>Plateforme LMS modulaire et personnalisable développée en PHP 8</p>';
        echo '<p><a href="/stacgatelms/hello/Amine">Tester la route dynamique</a></p>';
    }

    /**
     * Méthode appelée pour la route GET "/hello/{name}"
     * 
     * Affiche une page de salutation personnalisée avec le nom capturé
     * depuis l'URL. Exemple : /hello/Alice affichera "Bonjour, Alice !"
     * 
     * @param string $name Nom capturé depuis l'URL par le Router
     *                     Ce paramètre est automatiquement injecté par le Router
     *                     grâce au pattern {name} dans la route
     * @return void Affiche directement le contenu HTML
     */
    public function hello(string $name): void
    {
        // Protection XSS : échappement des caractères HTML dangereux
        // Ceci empêche l'injection de code HTML/JS malveillant dans l'URL
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        
        // Affichage de la page de salutation sécurisée
        echo "<h1>Bonjour, {$safeName} !</h1>";
        echo '<p>Cette page démontre le fonctionnement des paramètres dynamiques dans les routes.</p>';
        echo '<p><a href="/stacgatelms/">Retour à l\'accueil</a></p>';
    }
}
