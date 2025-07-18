<?php
namespace App\Core;

class Router {
    protected array $routes = [];

    public function get(string $uri, $action) {
        $this->routes['GET'][$uri] = $action;
    }

    // Ajout : support des routes POST
    public function post(string $uri, $action) {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch(string $requestUri, string $requestMethod) {
        $requestUri = strtok($requestUri, '?'); // ignore les paramètres de requête

        $action = $this->routes[$requestMethod][$requestUri] ?? null;
        if ($action) {
            if (is_callable($action)) {
                return $action();
            }
            // Format : [ControllerClass, method]
            if (is_array($action) && class_exists($action[0])) {
                $controller = new $action[0]();
                return call_user_func([$controller, $action[1]]);
            }
        }
        // Réponse personnalisée 404
        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
        exit;
    }
}
