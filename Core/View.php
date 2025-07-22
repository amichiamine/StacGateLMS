<?php
namespace StacGate\Core;

final class View
{
    private string $layout = 'layouts/main';

    public function setLayout(string $layout): self
    {
        $this->layout = $layout;
        return $this;
    }

    public function render(string $view, array $data = []): void
    {
        $viewFile   = $this->path("$view.php");
        $layoutFile = $this->path("$this->layout.php");

        if (!is_file($viewFile)) {
            throw new \RuntimeException("Vue introuvable : $viewFile");
        }
        if (!is_file($layoutFile)) {
            throw new \RuntimeException("Layout introuvable : $layoutFile");
        }

        extract($data, EXTR_OVERWRITE);

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require $layoutFile;
    }

    private function path(string $relative): string
    {
        return dirname(__DIR__) . '/templates/' . str_replace('/', DIRECTORY_SEPARATOR, $relative);
    }
}
