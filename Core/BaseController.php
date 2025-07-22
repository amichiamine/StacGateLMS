<?php
/**
 * BASECONTROLLER — classe parente de tous les contrôleurs StacGateLMS.
 * Fournit :
 *   - méthode render() pour charger une vue
 *   - place-holder db() (connexion PDO, implémentée à l’étape 2.3)
 */
namespace StacGate\Core;

class BaseController
{
    /** Chemin racine des templates */
    protected string $viewsPath = __DIR__ . '/../templates/';

    /**
     * Affiche une vue.
     * @param string $view  ex. 'home/index' → templates/home/index.php
     * @param array  $data  variables disponibles dans la vue
     */
   use StacGate\Core\View;

protected function render(string $view, array $data = [], string $layout = 'layouts/main'): void
{
    (new View())
        ->setLayout($layout)
        ->render($view, $data);
}

    /**
     * Retournera la connexion PDO partagée (étape 2.3).
     * @throws \LogicException tant que la BDD n’est pas configurée.
     */
    protected function db(): \PDO
    {
       
        return \StacGate\Core\BaseModel::db();
       
    }
}
