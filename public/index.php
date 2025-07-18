<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Connexion PDO à ta base fraichement créée
$db = new \PDO(
    'mysql:host=localhost;dbname=stacgatelms_dev;charset=utf8mb4',
    'root',
    ''
);
// Initialise la classe Setting
\App\Helpers\Setting::init($db);

use App\Core\Router;
$router = new Router();

$router->get('/', function () {
    require __DIR__ . '/../app/Views/layouts/head.php';
    require __DIR__ . '/../app/Views/home/welcome.php';
    require __DIR__ . '/../app/Views/layouts/footer.php';
});

$router->get('/salut', function () {
    echo '<h1>Cette route est un point de test !</h1>';
    echo '<a href="/StacGateLMS/public/">Retour à l’accueil</a>';
});

$router->get('/admin/settings', function () {
    require __DIR__ . '/../app/Views/admin/settings.php';
});
$router->post('/admin/settings', function () {
    require __DIR__ . '/../app/Views/admin/settings.php';
});


$basePath = '/StacGateLMS/public';
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath)) ?: '/';
}

$router->dispatch($requestUri, $_SERVER['REQUEST_METHOD']);
