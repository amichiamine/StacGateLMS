<?php
/**
 * BaseController – contrôleur parent
 */
namespace StacGate\Core;

// 1) IMPORT de la classe View (en dehors de la classe)
use StacGate\Core\View;

class BaseController
{
    /** Chemin racine des templates */
    protected string $viewsPath = __DIR__ . '/../templates/';   // « templates » tout en minuscules

    /**
     * Rendu d’une vue avec layout
     */
    protected function render(
        string $view,
        array  $data   = [],
        string $layout = 'layouts/main'
    ): void {
        (new View())
            ->setLayout($layout)
            ->render($view, $data);
    }

    /**
     * Accès PDO partagé (implémenté dans BaseModel)
     */
    protected function db(): \PDO
    {
        return \StacGate\Core\BaseModel::db();
    }
}
