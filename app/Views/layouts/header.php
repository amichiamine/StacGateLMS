<header class="main-header">
    <div class="logo-and-info">
        <div class="logo">
            <img src="<?= \App\Helpers\Setting::get('homepage_logo_url',
            '/StacGateLMS/public/assets/images/logo.png') ?>" alt="Logo" style="height:60px;">

        </div>
        <div class="site-info">
    <?php
    echo htmlspecialchars_decode(\App\Helpers\Setting::get(
        'homepage_site_info',
        '<h1>StacGate LMS</h1><p>Par MagSteam E-Learning Platform</p>'
    ));
    ?>
</div>

    </div>
    <button class="hamburger" aria-label="Menu" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <nav class="main-nav">
        <a href="/" class="nav-link">Accueil</a>
        <a href="/about" class="nav-link">À propos</a>
        <a href="/contact" class="nav-link">Contact</a>
    </nav>
</header>
