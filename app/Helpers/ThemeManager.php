<?php
namespace App\Helpers;

use App\Helpers\Setting;

class ThemeManager
{
    /**
     * Construit le CSS à partir
     *  • des variables stockées dans app_settings (préfixe css_var_)
     *  • de valeurs par défaut si la clé n’est pas définie
     */
    public static function buildCss(): string
    {
        $vars = Setting::getCssVariables();

        // Valeurs de secours (si une clé est manquante en BDD)
        $fallback = [
            '--color-bg-gradient'   => 'linear-gradient(90deg, #174b99 0%, #25b461 100%)',
            '--color-primary'       => '#2162ff',
            '--color-accent'        => '#48ff00',
            '--color-header-bg'     => 'rgba(24,42,144,0.92)',
            '--color-content-dark'  => '#121820',
            '--color-content-light' => '#f5f7fa',
            '--color-border-radius' => '1.2rem',
            '--font-base'           => "'Inter','Segoe UI',Arial,sans-serif",
            '--menu-button-bg'      => 'rgba(33,98,255,0.75)',
            '--menu-button-bg-hover'=> '#5de4c7',
            '--welcome-bg'          => 'rgba(33,98,255,0.85)',
            '--welcome-box-shadow'  => '0 6px 12px rgba(0,0,0,0.25)',
            '--welcome-button-bg'   => 'var(--color-accent)',
            '--welcome-button-bg-hover'=>'var(--color-primary)',
            '--welcome-button-radius'=>'1.2rem',
            '--color-primary-shadow'=> 'rgba(33,98,255,0.2)',
            '--footer-bg'           => 'rgba(20,36,54,0.15)',
        ];

        // Fusion BDD + fallback
        $vars = $vars + $fallback;

        // 1) bloc :root
        $css  = ":root{\n";
        foreach ($vars as $k => $v) {
            $css .= "  {$k}: {$v};\n";
        }
        $css .= "}\n";

        // 2) règles de base (issues de votre theme.css actuel)
        $css .= <<<CSS
html,body{
  height:100%;
  background:var(--color-bg-gradient);
  font-family:var(--font-base);
  margin:0;
  padding:0;
  color:var(--color-content-dark);
}
a,.btn-primary{
  color:#fff;
  background:var(--color-primary);
  border-radius:var(--color-border-radius);
  padding:0.65em 1.5em;
  border:none;
  font-weight:bold;
  font-size:1rem;
  transition:background .2s;
  text-decoration:none;
}
a:hover,.btn-primary:hover{
  background:var(--color-accent);
}
CSS;

        return $css;
    }
}
