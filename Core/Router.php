<?php
/**
 * STACGATELMS - ROUTER PRINCIPAL
 * 
 * Ce fichier contient la classe Router qui gère :
 * - Le routage des URLs vers les contrôleurs appropriés
 * - La gestion des méthodes HTTP (GET, POST, PUT, DELETE)
 * - L'analyse et le parsing des paramètres d'URL
 * - La sécurité de base et la validation des routes
 * 
 * Architecture : Mini-MVC modulaire
 * Namespace : StacGate\Core (PSR-4) - CORRIGÉ POUR CORRESPONDRE À L'AUTOLOAD
 * 
 * @package StacGate\Core
 * @author Amine AMICHI
 * @version 1.0.0
 */

namespace StacGate\Core; // CORRECTION: Namespace avec majuscule pour correspondre à composer.json

/**
 * Classe Router - Gestionnaire principal du routage
 * 
 * Cette classe centralise toute la logique de routage de l'application :
 * - Enregistrement des routes avec leurs contrôleurs
 * - Matching des URLs entrantes avec les routes définies
 * - Extraction des paramètres depuis les URLs
 * - Appel sécurisé des méthodes de contrôleurs
 */
class Router
{
    /**
     * @var array $routes Tableau associatif stockant toutes les routes enregistrées
     * Structure : $routes[METHOD][PATTERN] = ['controller' => 'ClassName', 'method' => 'methodName']
     */
    private array $routes = [];
    
    /**
     * @var string $basePath Chemin de base de l'application (pour sous-dossiers)
     * Exemple : si l'app est dans /stacgatelms/, basePath = '/stacgatelms'
     */
    private string $basePath = '';
    
    /**
     * @var array $middlewares Tableau des middlewares à exécuter avant les contrôleurs
     * Les middlewares permettent d'ajouter des vérifications (auth, permissions, etc.)
     */
    private array $middlewares = [];

    /**
     * Constructeur du Router
     * 
     * Initialise le routeur avec le chemin de base optionnel
     * Le basePath est utile quand l'application n'est pas à la racine du serveur
     * 
     * @param string $basePath Chemin de base de l'application (optionnel)
     */
    public function __construct(string $basePath = '')
    {
        // Supprime les slashes en fin de basePath pour éviter les doublons
        $this->basePath = rtrim($basePath, '/');
    }

    /**
     * Enregistre une route GET
     * 
     * Les routes GET sont utilisées pour récupérer des données (pages, API read)
     * Exemple : $router->get('/formations', 'FormationController', 'list')
     * 
     * @param string $pattern Pattern de l'URL (peut contenir des paramètres {id})
     * @param string $controller Nom de la classe contrôleur (sans namespace)
     * @param string $method Nom de la méthode à appeler dans le contrôleur
     * @return self Retourne l'instance pour permettre le chaînage
     */
    public function get(string $pattern, string $controller, string $method): self
    {
        return $this->addRoute('GET', $pattern, $controller, $method);
    }

    /**
     * Enregistre une route POST
     * 
     * Les routes POST sont utilisées pour créer des données (formulaires, API create)
     * Exemple : $router->post('/formations/create', 'FormationController', 'create')
     * 
     * @param string $pattern Pattern de l'URL
     * @param string $controller Nom de la classe contrôleur
     * @param string $method Nom de la méthode à appeler
     * @return self Retourne l'instance pour le chaînage
     */
    public function post(string $pattern, string $controller, string $method): self
    {
        return $this->addRoute('POST', $pattern, $controller, $method);
    }

    /**
     * Enregistre une route PUT
     * 
     * Les routes PUT sont utilisées pour mettre à jour des données (API update)
     * Exemple : $router->put('/formations/{id}', 'FormationController', 'update')
     * 
     * @param string $pattern Pattern de l'URL
     * @param string $controller Nom de la classe contrôleur
     * @param string $method Nom de la méthode à appeler
     * @return self Retourne l'instance pour le chaînage
     */
    public function put(string $pattern, string $controller, string $method): self
    {
        return $this->addRoute('PUT', $pattern, $controller, $method);
    }

    /**
     * Enregistre une route DELETE
     * 
     * Les routes DELETE sont utilisées pour supprimer des données (API delete)
     * Exemple : $router->delete('/formations/{id}', 'FormationController', 'delete')
     * 
     * @param string $pattern Pattern de l'URL
     * @param string $controller Nom de la classe contrôleur
     * @param string $method Nom de la méthode à appeler
     * @return self Retourne l'instance pour le chaînage
     */
    public function delete(string $pattern, string $controller, string $method): self
    {
        return $this->addRoute('DELETE', $pattern, $controller, $method);
    }

    /**
     * Méthode privée pour ajouter une route au tableau $routes
     * 
     * Centralise la logique d'ajout de routes pour éviter la duplication
     * Nettoie le pattern et stocke les informations dans la structure interne
     * 
     * @param string $method Méthode HTTP (GET, POST, PUT, DELETE)
     * @param string $pattern Pattern de l'URL à matcher
     * @param string $controller Classe contrôleur cible
     * @param string $action Méthode à appeler dans le contrôleur
     * @return self Retourne l'instance pour le chaînage
     */
    private function addRoute(string $method, string $pattern, string $controller, string $action): self
    {
        // Nettoie le pattern : ajoute un slash au début s'il n'existe pas
        $cleanPattern = '/' . ltrim($pattern, '/');
        
        // Stocke la route dans le tableau avec la structure définie
        $this->routes[$method][$cleanPattern] = [
            'controller' => $controller,
            'method' => $action
        ];
        
        return $this;
    }

    /**
     * Résout une route : trouve le contrôleur correspondant à l'URL courante
     * 
     * Cette méthode est le cœur du Router :
     * 1. Récupère l'URL et la méthode HTTP courantes
     * 2. Cherche une route correspondante
     * 3. Extrait les paramètres depuis l'URL
     * 4. Retourne les informations pour l'exécution
     * 
     * @return array|null Tableau avec controller, method et params, ou null si pas de match
     */
    public function resolve(): ?array
    {
        // Récupère la méthode HTTP de la requête courante
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        // Récupère l'URI et supprime les paramètres GET (?param=value)
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH); // Supprime les query strings
        
        // Supprime le basePath de l'URI si il existe
        if ($this->basePath && strpos($uri, $this->basePath) === 0) {
            $uri = substr($uri, strlen($this->basePath));
        }
        
        // Assure qu'on a au moins un slash
        $uri = '/' . ltrim($uri, '/');
        
        // Vérifie s'il existe des routes pour cette méthode HTTP
        if (!isset($this->routes[$method])) {
            return null; // Aucune route pour cette méthode
        }
        
        // Parcourt toutes les routes de cette méthode pour trouver un match
        foreach ($this->routes[$method] as $pattern => $route) {
            // Tente de matcher l'URI avec le pattern de route
            $params = $this->matchRoute($pattern, $uri);
            
            if ($params !== null) {
                // Route trouvée ! Retourne les infos complètes
                return [
                    'controller' => $route['controller'],
                    'method' => $route['method'],
                    'params' => $params
                ];
            }
        }
        
        // Aucune route ne correspond
        return null;
    }

    /**
     * Vérifie si un pattern de route correspond à une URI donnée
     * 
     * Gère les paramètres dynamiques dans les routes (ex: /user/{id})
     * Extrait automatiquement les valeurs des paramètres
     * 
     * @param string $pattern Pattern de la route (ex: '/formations/{id}/edit')
     * @param string $uri URI à tester (ex: '/formations/123/edit')
     * @return array|null Paramètres extraits ou null si pas de correspondance
     */
    private function matchRoute(string $pattern, string $uri): ?array
    {
        // Si le pattern est exactement identique à l'URI, match direct
        if ($pattern === $uri) {
            return []; // Pas de paramètres, mais c'est un match
        }
        
        // Convertit le pattern en regex pour gérer les paramètres {nom}
        // Remplace {parametre} par un groupe de capture nommé
        $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
        
        // Échappe les caractères spéciaux de regex
        $regex = str_replace('/', '\/', $regex);
        
        // Ajoute les délimiteurs de regex et l'ancrage complet
        $regex = '/^' . $regex . '$/';
        
        // Teste le match avec la regex
        if (preg_match($regex, $uri, $matches)) {
            // Filtre les matches pour ne garder que les paramètres nommés
            $params = [];
            foreach ($matches as $key => $value) {
                // Ne garde que les clés de type string (paramètres nommés)
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }
            return $params;
        }
        
        return null; // Pas de correspondance
    }

    /**
     * Exécute la route trouvée : instancie le contrôleur et appelle la méthode
     * 
     * Cette méthode finalise le processus de routage :
     * 1. Vérifie que le contrôleur existe
     * 2. L'instancie avec les bonnes dépendances
     * 3. Appelle la méthode demandée avec les paramètres
     * 4. Gère les erreurs proprement
     * 
     * @param array $routeInfo Informations de route retournées par resolve()
     * @return mixed Résultat de l'exécution du contrôleur
     * @throws \Exception Si le contrôleur ou la méthode n'existe pas
     */
    public function execute(array $routeInfo)
    {
        $controllerName = $routeInfo['controller'];
        $methodName = $routeInfo['method'];
        $params = $routeInfo['params'];
        
        // CORRECTION: Construit le nom complet de la classe avec le namespace corrigé
        $controllerClass = 'StacGate\\Controllers\\' . $controllerName;
        
        // Vérifie que la classe contrôleur existe
        if (!class_exists($controllerClass)) {
            throw new \Exception("Contrôleur non trouvé : {$controllerClass}");
        }
        
        // Instancie le contrôleur
        $controller = new $controllerClass();
        
        // Vérifie que la méthode existe dans le contrôleur
        if (!method_exists($controller, $methodName)) {
            throw new \Exception("Méthode {$methodName} non trouvée dans {$controllerClass}");
        }
        
        // Appelle la méthode avec les paramètres extraits de l'URL
        return call_user_func_array([$controller, $methodName], $params);
    }

    /**
     * Gère une requête complète : resolve + execute
     * 
     * Méthode de convenance qui combine la résolution et l'exécution
     * C'est le point d'entrée principal pour traiter une requête HTTP
     * 
     * @return void
     * @throws \Exception Si aucune route ne correspond
     */
    public function handleRequest(): void
    {
        // Tente de résoudre la route courante
        $routeInfo = $this->resolve();
        
        if ($routeInfo === null) {
            // Aucune route trouvée - erreur 404
            http_response_code(404);
            echo "Page non trouvée (404)";
            return;
        }
        
        try {
            // Exécute la route trouvée
            $this->execute($routeInfo);
        } catch (\Exception $e) {
            // Gère les erreurs d'exécution - erreur 500
            http_response_code(500);
            echo "Erreur serveur : " . $e->getMessage();
            
            // En environnement de développement, affiche la stack trace
            if (defined('DEBUG') && DEBUG === true) {
                echo "\n\nStack trace:\n" . $e->getTraceAsString();
            }
        }
    }
}
