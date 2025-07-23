<?php
namespace StacGate\Core;

use StacGate\Core\View;

class BaseController
{
    /**
     * Affiche une vue dans le layout voulu.
     */
    protected function render(string $view, array $data = [], string $layout = 'layouts/main'): void
    {
        (new View())
            ->setLayout($layout)
            ->render($view, $data);
    }

    /**
     * Connexion PDO partagée.
     */
    protected function db(): \PDO
    {
        return \StacGate\Core\BaseModel::db();
    }
}
