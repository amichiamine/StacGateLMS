<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../autoload.php';


use App\Helpers\Setting;

// Récupération des valeurs
$headerType = Setting::get('header_background_type', 'gradient');
$headerValue = Setting::get('header_background_value', 'linear-gradient(90deg, #1767a7, #11d48d)');
$headerFontColor = Setting::get('header_font_color', '#fff');
$headerFontFamily = Setting::get('header_font_family', 'Roboto, Arial, sans-serif');
$headerFontSize = Setting::get('header_font_size', '18px');

// Affichage brut pour vérification
var_dump($headerType, $headerValue, $headerFontColor, $headerFontFamily, $headerFontSize);

// Fais pareil pour le body et le footer si tu veux tout vérifier
?>
