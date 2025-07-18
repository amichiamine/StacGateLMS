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
