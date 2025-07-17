<?php
namespace App\Core;

class Router {
    protected array $routes = [];

    public function get(string $uri, $action) {
        $this->routes['GET'][$uri] = $action;
    }

    public function dispatch(string $requestUri, string $requestMethod) {
        $requestUri = strtok($requestUri, '?'); // ignores query params

        $action = $this->routes[$requestMethod][$requestUri] ?? null;
        if ($action) {
            if (is_callable($action)) {
                return $action();
            }
            // Format: [ControllerClass, method]
            if (is_array($action) && class_exists($action[0])) {
                $controller = new $action[0]();
                return call_user_func([$controller, $action[1]]);
            }
        }
        // 404 custom
        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
        exit;
    }
}
