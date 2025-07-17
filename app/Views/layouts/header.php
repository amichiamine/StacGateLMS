<header class="main-header">
    <div class="logo-and-info">
        <div class="logo">
            <img src="<?= \App\Helpers\Setting::get('homepage_logo_url',
            '/StacGateLMS/public/assets/images/logo.png') ?>" alt="Logo" style="height:60px;">

        </div>
        <div class="site-info">
            <?= \App\Helpers\Setting::get(
            'homepage_site_info',
            '<h1>StacGateLMS</h1><p>Votre plateforme d’apprentissage</p>'
            ) ?>
        </div>


    </div>
    <button class="hamburger" aria-label="Menu" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <nav class="main-nav">
    <?php
    $menu = json_decode(\App\Helpers\Setting::get('main_menu_json', '[]'), true);
    if (!empty($menu)) {
        foreach ($menu as $item) {
            echo '<a href="' . htmlspecialchars($item['url']) . '" class="nav-link">' . 
                  htmlspecialchars($item['label']) . '</a>';
        }
    }
    ?>
</nav>

</header>
