<?php require __DIR__ . '/../components/carousel.php'; ?>
<div class="hero-row">
    

   <section id="welcome">
    <?= \App\Helpers\Setting::get(
        'homepage_welcome_html',
        '<h2>Bienvenue sur StacGateLMS</h2><p>Plateforme d’apprentissage virtuelle, flexible et évolutive.</p><p>Vous retrouverez bientôt vos formations, suivis et outils pédagogiques.</p><a href="/StacGateLMS/public/salut">Accéder à la zone de test</a>'
    ) ?>
    </section>

</div>
