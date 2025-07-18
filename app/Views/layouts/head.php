<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>StacGateLMS</title>
    <?php
    if (!function_exists('getAppSetting')) {
        function getAppSetting($key, $default = '') {
            return \App\Helpers\Setting::get($key, $default);
        }
    }
    // HEADER
    switch (getAppSetting('header_bg_type', 'gradient')) {
        case 'solid':
            $header_bg = getAppSetting('header_bg_color', '#174b99');
            break;
        case 'gradient':
            $header_bg = getAppSetting('header_bg_gradient', 'linear-gradient(90deg, #174b99 0%, #25b461 100%)');
            break;
        case 'image':
            $img = getAppSetting('header_bg_image', '');
            $header_bg = $img ? "url($img)" : '#174b99';
            break;
        default:
            $header_bg = '#174b99';
    }
    // BODY
    switch (getAppSetting('body_bg_type', 'solid')) {
        case 'solid':
            $body_bg = getAppSetting('body_bg_color', '#e9f5ff');
            break;
        case 'gradient':
            $body_bg = getAppSetting('body_bg_gradient', '');
            break;
        case 'image':
            $img = getAppSetting('body_bg_image', '');
            $body_bg = $img ? "url($img)" : '#e9f5ff';
            break;
        default:
            $body_bg = '#e9f5ff';
    }
    // FOOTER
   switch (getAppSetting('footer_bg_type', 'solid')) {
    case 'solid':
        $footer_bg = getAppSetting('footer_bg_color', '#142436');
        break;
    case 'gradient':
        $footer_bg = getAppSetting('footer_bg_gradient', '');
            break;
        break;
    case 'image':
        $img = trim(getAppSetting('footer_bg_image', ''));
        $footer_bg = $img ? "url($img)" : '#e9f5ff';
        break;
    default:
        $footer_bg = '#142436';
}



    ?>
    <style id="dynamic-theme">
        :root {
            --header-bg: <?= $header_bg ?>;
            --header-font-color: <?= getAppSetting('header_font_color', '#fff') ?>;
            --header-font-size: <?= getAppSetting('header_font_size', '18px') ?>;
            --header-font-family: <?= getAppSetting('header_font_family', 'Montserrat, Arial, sans-serif') ?>;
            --body-bg: <?= $body_bg ?>;
            --body-font-color: <?= getAppSetting('body_font_color', '#142436') ?>;
            --body-font-size: <?= getAppSetting('body_font_size', '16px') ?>;
            --body-font-family: <?= getAppSetting('body_font_family', 'Montserrat, Arial, sans-serif') ?>;
            --footer-bg: <?= $footer_bg ?>;
            --footer-font-color: <?= getAppSetting('footer_font_color', '#fff') ?>;
            --footer-font-size: <?= getAppSetting('footer_font_size', '15px') ?>;
            --footer-font-family: <?= getAppSetting('footer_font_family', 'Montserrat, Arial, sans-serif') ?>;
        }
    </style>
    <link rel="stylesheet" href="/StacGateLMS/public/assets/css/theme.css">
    <link rel="stylesheet" href="/StacGateLMS/public/assets/css/main.css">
</head>
<body>
    <div class="menu-backdrop"></div>
    <?php require __DIR__ . '/header.php'; ?>
    <main>
