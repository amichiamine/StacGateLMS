<?php
$carousel_enabled = \App\Helpers\Setting::get('carousel_enabled', '1');
if ($carousel_enabled === '1') :
    $carousel_height = \App\Helpers\Setting::get('carousel_height', '280px');
    $carousel_width  = \App\Helpers\Setting::get('carousel_width', '95vw');
    $slides = json_decode(\App\Helpers\Setting::get('carousel_slides_json', '[]'), true);
    if (!is_array($slides) || count($slides) === 0) return;
?>
<div class="carousel-container"
     style="--carousel-height:<?= htmlspecialchars(trim($carousel_height, '\'" ')) ?>; --carousel-width:<?= htmlspecialchars(trim($carousel_width, '\'" ')) ?>;">

    <div class="carousel">
    <?php foreach ($slides as $idx => $slide): ?>
        <div class="slide<?= $idx === 0 ? ' active' : '' ?>">
            <img src="<?= htmlspecialchars($slide['img']) ?>" alt="<?= htmlspecialchars($slide['title'] ?? '') ?>">
            <div class="slide-text">
                <?php if (!empty($slide['title'])) { ?><h3><?= htmlspecialchars($slide['title']) ?></h3><?php } ?>
                <?php if (!empty($slide['desc'])) { ?><p><?= htmlspecialchars($slide['desc']) ?></p><?php } ?>
                <?php if (!empty($slide['btn'])) { ?>
                    <a href="<?= htmlspecialchars($slide['link'] ?? '#') ?>" class="btn-primary"><?= htmlspecialchars($slide['btn']) ?></a>
                <?php } ?>
            </div>
        </div>
    <?php endforeach; ?>
    <button class="carousel-arrow left">&#8249;</button>
    <button class="carousel-arrow right">&#8250;</button>
    <div class="carousel-dots"></div>
    </div>
</div>
<?php endif; ?>
