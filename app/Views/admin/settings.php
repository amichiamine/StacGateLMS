<?php
// CHEMIN : app/Views/admin/settings.php

// Connexion PDO à la base
$db = new \PDO(
    'mysql:host=localhost;dbname=stacgatelms_dev;charset=utf8mb4',
    'root',
    ''
);
// Initialise la classe Setting pour le site
\App\Helpers\Setting::init($db);

// Chargement de la config centrale (si structure avancée)
$config = require dirname(__DIR__, 2) . '/../config/config.php';
$dbconf = $config['db'];
$db = new PDO(
    "mysql:host={$dbconf['host']};dbname={$dbconf['name']};charset={$dbconf['charset']}",
    $dbconf['user'],
    $dbconf['password']
);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//verification role
//session_start();
//if (empty($_SESSION['user_id'])) {
//   header('Location: /StacGateLMS/public/login');
//    exit;
//}

// Gestion du formulaire et upload image pour background "image"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['key'])) {
    $key = $_POST['key'];
    $value = $_POST['value'] ?? '';

    if (str_contains($key, '_bg_type') && isset($_FILES['image_file_'.$key]) && $_FILES['image_file_'.$key]['error'] === UPLOAD_ERR_OK) {
        $imgKey = str_replace('_bg_type', '_bg_image', $key);
        $ext = strtolower(pathinfo($_FILES['image_file_'.$key]['name'], PATHINFO_EXTENSION));
        $newName = uniqid('bgimg_') . '.' . $ext;
        $target = '/StacGateLMS/public/assets/images/' . $newName;
        move_uploaded_file($_FILES['image_file_'.$key]['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $target);
        $stmt = $db->prepare('UPDATE app_settings SET setting_value=?, updated_at=NOW() WHERE setting_key=?');
        $stmt->execute([$target, $imgKey]);
    }

    $stmt = $db->prepare('UPDATE app_settings SET setting_value = ?, updated_at = NOW() WHERE setting_key = ?');
    $stmt->execute([$value, $key]);
    $msg = "Paramètre '{$key}' mis à jour !";
}

// Liste des paramètres dynamiques importants
$keys = [
    // Thème
    'header_bg_type', 'header_bg_color', 'header_bg_gradient', 'header_bg_image',
    'header_font_color', 'header_font_size', 'header_font_family',
    'body_bg_type', 'body_bg_color', 'body_bg_gradient', 'body_bg_image',
    'body_font_color', 'body_font_size', 'body_font_family',
    'footer_bg_type', 'footer_bg_color', 'footer_bg_gradient', 'footer_bg_image',
    'footer_font_color', 'footer_font_size', 'footer_font_family',
    // Carrousel
    'carousel_enabled', 'carousel_height', 'carousel_width', 'carousel_slides_json',
    // Accueil
    'homepage_site_info', 'homepage_logo_url', 'main_menu_json', 'homepage_welcome_html'
];
$in = str_repeat('?,', count($keys) - 1) . '?';
$stmt = $db->prepare("SELECT setting_key, setting_value FROM app_settings WHERE setting_key IN ($in)");
$stmt->execute($keys);
$params = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

require __DIR__ . '/../layouts/head.php';
?>
<div style="max-width:1100px;margin:2.5rem auto; background:white; border-radius: 1.2rem; padding:2rem 2.2rem; box-shadow: 0 6px 22px #0001;">
    <h1>🛠️ Administration des paramètres dynamiques StacGateLMS</h1>
    <?php if (!empty($msg)) echo "<div style='background:#d1fae5; color:#02946e; padding:0.9em 1em; border-radius:.6em; margin-bottom:1.5em;'>" . htmlspecialchars($msg) . "</div>"; ?>

    <table style="width:100%; border-collapse:collapse; background:#fff;">
        <thead>
            <tr style="background: var(--header-bg); color: var(--header-font-color);">
                <th style="padding:0.7em 1em;">Clé</th>
                <th style="padding:0.7em 1em;">Valeur</th>
                <th style="padding:0.7em 1em;">Aperçu</th>
            </tr>
        </thead>
        <tbody>
<?php foreach ($keys as $key):
    $value = isset($params[$key]) ? $params[$key] : '';
    $isJsonOrHtml = (str_contains($key, 'json') || str_contains($key, 'html'));
    $isColor = (str_contains($key, 'color') && !str_contains($key, 'font-family'));
    $isGradient = str_contains($key, '_bg_gradient');
    $isBgType = str_contains($key, '_bg_type');
?>
<tr style="border-bottom:1px solid #e2e8f0;">
    <td style="padding:0.7em 1em; vertical-align: top; font-family: monospace; font-size: 0.96em;">
        <?= htmlspecialchars($key) ?>
    </td>
    <td style="padding:0.7em 1em;">
        <?php if ($isBgType): ?>
            <form method="POST" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>" enctype="multipart/form-data" style="margin:0;display:flex;align-items:center;gap:1em;">
                <input type="hidden" name="key" value="<?= htmlspecialchars($key) ?>">
                <select name="value" onchange="toggleImageUpload(this, '<?= $key ?>')"
                        style="padding:0.35em 0.8em; border-radius:3px; margin-right:1em;">
                    <?php foreach(['solid', 'gradient', 'image'] as $type): ?>
                        <option value="<?= $type ?>" <?= $value === $type ? 'selected' : '' ?>><?= ucfirst($type) ?></option>
                    <?php endforeach; ?>
                </select>
                <span id="img-upload-<?= $key ?>" style="display:<?= ($value==='image') ? 'inline-block' : 'none' ?>;">
                    <input type="file" name="image_file_<?= $key ?>" accept="image/*" style="margin-right:0.3em;">
                    <?php
                    $bgImageKey = str_replace('_bg_type', '_bg_image', $key);
                    $bgImageUrl = isset($params[$bgImageKey]) ? $params[$bgImageKey] : '';
                    if ($bgImageUrl):
                    ?>
                    <img src="<?= htmlspecialchars($bgImageUrl) ?>" alt="aperçu" style="height:36px;vertical-align:middle;margin-left:4px;">
                    <?php endif; ?>
                </span>
                <button type="submit" style="margin-left:1em;">Enregistrer</button>
            </form>
            <script>
            function toggleImageUpload(sel, key) {
                var val = sel.value;
                document.getElementById('img-upload-' + key).style.display = (val==='image') ? 'inline-block':'none';
            }
            </script>
        <?php elseif ($isGradient):
            $gradient_pattern = '/linear-gradient\(([^,]+),\s*(#[a-fA-F0-9]{3,6})\s*([0-9%]*),\s*(#[a-fA-F0-9]{3,6})\s*([0-9%]*)\)/';
            if (preg_match($gradient_pattern, $value, $m)) {
                list(, $angle, $col_start, $pct_start, $col_end, $pct_end) = $m;
            } else {
                $angle = '90deg'; $col_start = '#174b99'; $pct_start = '0%'; $col_end = '#25b461'; $pct_end = '100%';
            }
        ?>
            <form method="POST" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>" oninput="updateGradientField(this)" style="display:flex;align-items:center;gap:.6em;">
                <input type="hidden" name="key" value="<?= htmlspecialchars($key) ?>">
                <input type="hidden" name="value" value="">
                <input type="text" name="angle" value="<?= htmlspecialchars($angle) ?>" style="width:50px;" placeholder="ex : 90deg">
                <input type="color" name="color_start" value="<?= htmlspecialchars($col_start) ?>">
                <input type="text" name="pct_start" value="<?= htmlspecialchars($pct_start) ?>" style="width:45px;" placeholder="ex : 0%">
                <input type="color" name="color_end" value="<?= htmlspecialchars($col_end) ?>">
                <input type="text" name="pct_end" value="<?= htmlspecialchars($pct_end) ?>" style="width:45px;" placeholder="ex : 100%">
                <button type="submit" style="margin-left:1em;">Enregistrer</button>
            </form>
            <script>
            function updateGradientField(form) {
                var angle = form.angle.value;
                var c1 = form.color_start.value;
                var p1 = form.pct_start.value;
                var c2 = form.color_end.value;
                var p2 = form.pct_end.value;
                form.value.value = 'linear-gradient(' + angle + ', ' + c1 + ' ' + p1 + ', ' + c2 + ' ' + p2 + ')';
            }
            </script>
        <?php elseif ($isColor): 
            $inputColor = (preg_match('/^#([a-fA-F0-9]{3,6})$/', trim($value))) ? trim($value) : '#000000'; 
        ?>
            <form method="POST" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>" style="margin: 0; display: flex; align-items: center;gap:.5em;">
                <input type="hidden" name="key" value="<?= htmlspecialchars($key) ?>">
                <input type="color" name="value" value="<?= htmlspecialchars($inputColor) ?>" style="width:2.1em;height:2.1em;padding:0;border:none;cursor:pointer;">
                <span style="display:inline-block;width:1.6em;height:1.6em;vertical-align:middle;
                        background:<?= htmlspecialchars($inputColor ?: '#000000') ?>;border:1px solid #aaa;border-radius: 4px;box-shadow:0 1px 4px #0001;">
                </span>
                <input type="text" name="value_txt" value="<?= htmlspecialchars($value) ?>" 
                    style="width:90px; margin-left:0.7em;font-family:monospace;" 
                    oninput="this.form.elements['value'].value = this.value"
                    onfocus="this.select();"
                >
                <button type="submit" style="margin-left:1.2em;">Enregistrer</button>
                <script>
                document.addEventListener('DOMContentLoaded', function(){
                    var form = document.currentScript.parentElement;
                    var colorInput = form.querySelector('input[type=color]');
                    var txtInput = form.querySelector('input[name=value_txt]');
                    if (colorInput && txtInput) {
                        colorInput.addEventListener('input', function() {
                            txtInput.value = this.value;
                        });
                        txtInput.addEventListener('input', function() {
                            if(/^#[a-fA-F0-9]{3,6}$/.test(this.value)) colorInput.value = this.value;
                        });
                    }
                });
                </script>
            </form>
        <?php elseif ($isJsonOrHtml): ?>
            <form method="POST" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                <input type="hidden" name="key" value="<?= htmlspecialchars($key) ?>">
                <textarea name="value" rows="5" style="width:99%;font-family:monospace"><?= htmlspecialchars($value) ?></textarea>
                <button type="submit" style="margin-top:0.3em;padding:0.5em 1.1em; border-radius:.6em;">Enregistrer</button>
            </form>
        <?php else: ?>
            <form method="POST" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                <input type="hidden" name="key" value="<?= htmlspecialchars($key) ?>">
                <input type="text" name="value" value="<?= htmlspecialchars($value) ?>" style="width:98%;padding:0.5em;">
                <button type="submit" style="padding:0.5em 1.1em; border-radius:.6em;">Enregistrer</button>
            </form>
        <?php endif; ?>
    </td>
    <td style="padding:0.7em 1em; text-align:center;">
        <?php if ($key === 'homepage_logo_url' && !empty($value)): ?>
            <img src="<?= htmlspecialchars($value) ?>" alt="Logo" style="max-height:48px; max-width:120px;">
        <?php endif; ?>

        <?php if ($key === 'main_menu_json' && !empty($value)): ?>
            <?php
            $menuPreview = @json_decode($value, true);
            if (is_array($menuPreview)) {
                echo '<nav style="margin:6px 0;">';
                foreach ($menuPreview as $item) {
                    if (isset($item['label'], $item['url'])) {
                        echo '<a href="' . htmlspecialchars($item['url']) . '" '
                        . 'style="display:inline-block; background:#f6f7fa; border:1px solid #e7eaee;'
                        . 'padding:0.2em 0.8em; margin-right:5px; border-radius:4px; text-decoration:none; color:#2162ff;">'
                        . htmlspecialchars($item['label']) . '</a>';
                    }
                }
                echo '</nav>';
            } else {
                echo '<div style="color:#f33;font-size:0.92em">Format JSON invalide ou vide</div>';
            }
            ?>
        <?php endif; ?>


        <?php if ($key === 'carousel_slides_json' && !empty($value)): ?>
            <?php
            $slidesPreview = @json_decode($value, true);
            if (is_array($slidesPreview) && count($slidesPreview) > 0) {
                echo '<div style="display:flex;gap:6px;overflow-x:auto;max-width:430px;padding:3px 0">';
                foreach ($slidesPreview as $slide) {
                    echo '<div style="min-width:110px;max-width:120px;background:#f9fafb;border-radius:6px;border:1px solid #eee;padding:6px;box-shadow:0 1px 6px #0001;text-align:center;">';
                    if (!empty($slide['img'])) {
                        echo '<img src="' . htmlspecialchars($slide['img']) . '" alt="Slide" style="width:100%;height:54px;object-fit:cover;border-radius:4px 4px 0 0;margin-bottom:0.3em">';
                    }
                    if (!empty($slide['title'])) {
                        echo '<div style="font-size:0.95em;font-weight:600;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' . htmlspecialchars($slide['title']) . '</div>';
                    }
                    if (!empty($slide['desc'])) {
                        echo '<div style="font-size:0.93em; color:#666; margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' . htmlspecialchars($slide['desc']) . '</div>';
                    }
                    if (!empty($slide['btn'])) {
                        echo '<span style="display:inline-block;padding:0.13em 0.7em;font-size:0.92em;background:#2162ff;color:#fff; border-radius:3px;">' . htmlspecialchars($slide['btn']) . '</span>';
                    }
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo '<div style="color:#f33;font-size:0.92em">Format JSON invalide ou aucune slide</div>';
            }
            ?>
        <?php endif; ?>


    <?php if ($key === 'homepage_welcome_html' && !empty($value)): ?>
        <div style="border:1px dashed #89b6fc;background:#fafbff;margin:6px 0;padding:10px 16px; border-radius:6px;max-width:360px;overflow:auto;">
            <?= $value ?>
        </div>
    <?php endif; ?>


        <?php if ($key === 'homepage_welcome_html'): ?>
    <form method="POST" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>" id="form-welcome-html" style="margin:0;">
        <input type="hidden" name="key" value="<?= htmlspecialchars($key) ?>">
        <div id="welcome-html-view" style="margin-bottom:6px;">
            <button type="button" onclick="toggleWelcomeEdit(true)" style="margin-bottom:8px;">
                Modifier le contenu de bienvenue
            </button>
            <div style="border:1px dashed #aad6fa;background:#f4f8fd;padding:12px 16px;border-radius:6px;max-width:390px;overflow:auto;">
                <?= $value ?>
            </div>
        </div>
        <div id="welcome-html-edit" style="display:none;">
            <textarea name="value" rows="6" style="width:99%;font-family:monospace;padding:6px;"><?= htmlspecialchars($value) ?></textarea>
            <div style="margin:10px 0 0;">
                <button type="submit" style="padding:0.4em 1.2em;">Enregistrer</button>
                <button type="button" onclick="toggleWelcomeEdit(false)" style="margin-left:10px;">Annuler</button>
            </div>
            <div style="margin:6px 0 0 0;font-size:0.95em;color:#666;">
                <strong>Astuce&nbsp;:</strong> tu peux saisir des balises HTML (<code>&lt;h2&gt;</code>, <code>&lt;p&gt;</code>, <code>&lt;a&gt;</code>, etc.) ou juste du texte simple.
            </div>
        </div>
        <!-- Optionnel : Mode simple de saisie directe sans HTML -->
         <div id="welcome-html-simple" style="margin-top:5px;display:none;">
    <input type="text" id="welcome-simple-title" placeholder="Titre (ex : Bienvenue sur StacGateLMS)" style="width:96%;margin-bottom:0.5em;">
    <textarea id="welcome-simple-desc" rows="3" placeholder="Message d’accueil" style="width:96%;margin-bottom:0.5em;"></textarea>
    <input type="text" id="welcome-simple-btn-label" placeholder="Texte bouton (ex : Commencer)" style="width:96%;margin-bottom:0.4em;">
    <input type="text" id="welcome-simple-btn-url" placeholder="Lien du bouton (ex : /inscription)" style="width:96%;margin-bottom:0.7em;">
    <button type="button" onclick="insertWelcomeSimple()">Insérer dans le HTML</button>
</div>

    </form>
    <button type="button" onclick="toggleWelcomeSimple()" style="margin-top:5px;">Saisie facile (formulaire simplifié)</button>
    <script>
    function toggleWelcomeEdit(edit) {
        document.getElementById('welcome-html-view').style.display = edit ? 'none' : '';
        document.getElementById('welcome-html-edit').style.display = edit ? '' : 'none';
        document.getElementById('welcome-html-simple').style.display = 'none';
    }
    function toggleWelcomeSimple() {
        // Affiche ou cache le bloc simple et active aussi l'édition du texte HTML
        var block = document.getElementById('welcome-html-simple');
        if (block.style.display==='none') {
            toggleWelcomeEdit(true);
            block.style.display = '';
        } else {
            block.style.display = 'none';
        }
    }
 function insertWelcomeSimple() {
    toggleWelcomeEdit(true);
    var titre = document.getElementById('welcome-simple-title').value.trim();
    var desc = document.getElementById('welcome-simple-desc').value.trim();
    var btnLabel = document.getElementById('welcome-simple-btn-label').value.trim();
    var btnUrl = document.getElementById('welcome-simple-btn-url').value.trim();

    var html = "";
    if (titre) html += "<h2>" + titre + "</h2>\n";
    if (desc) html += "<p>" + desc + "</p>\n";
    if (btnLabel && btnUrl) {
        html += '<a href="' + btnUrl.replace(/"/g, '&quot;') + '" class="btn-primary">' + btnLabel + '</a>';
    }
    var textarea = document.querySelector('#welcome-html-edit textarea[name="value"]');
    if (textarea) {
        textarea.value = html;
    }
}

    </script>
<?php else: ?>
    <!-- ancien code pour autres clés -->
<?php endif; ?>





        <?php
        if ($isColor) {
            echo '<span style="display:inline-block;width:2.3em;height:2.3em;border-radius:3px;border:1px solid #ddd;background:' . htmlspecialchars($value) . ';"></span>';
        } elseif ($isGradient && isset($angle, $col_start, $pct_start, $col_end, $pct_end)) {
            echo '<span style="display:inline-block;width:3.2em;height:2.2em;border-radius:3px;border:1px solid #ddd;background:linear-gradient('
                . $angle . ', ' . $col_start . ' ' . $pct_start . ', ' . $col_end . ' ' . $pct_end . ');"></span>';
        } elseif ($isBgType) {
            $bgImageKey = str_replace('_bg_type', '_bg_image', $key);
            if (!empty($params[$bgImageKey])) {
                echo '<img src="' . htmlspecialchars($params[$bgImageKey]) . '" alt="fond" style="height:36px;border-radius:3px;">';
            }
        }
        ?>
    </td>
</tr>
<?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
