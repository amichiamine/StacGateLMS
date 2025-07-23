<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= $title ?? 'StacGateLMS' ?></title>
    <link rel="stylesheet" href="<?= base_url('public/css/app.css') ?>">
</head>
<body>
<header>… barre de navigation …</header>

<main><?= $content ?></main>

<footer>© <?= date('Y') ?> StacGateLMS</footer>
</body>
</html>
