<header class="masthead">
    <img src="<?= htmlspecialchars($hero['hero_image']) ?>"
        alt="<?= htmlspecialchars($hero['hero_image_alt']) ?>"
        class="masthead-img">
    <div class="container">
        <div class="masthead-heading">
            <h1><?= $hero['h1_html'] /* HTML permis */ ?></h1>
        </div>
        <hr>
        <div class="masthead-sub-subheading">
            <?php if (!empty($hero['sub_h2'])): ?>
                <h2><?= htmlspecialchars($hero['sub_h2']) ?></h2>
            <?php endif; ?>
            <?php if (!empty($hero['sub_p_html'])): ?>
                <p><?= $hero['sub_p_html'] /* HTML permis */ ?></p>
            <?php endif; ?>
        </div>
        <a class="btn btn-warning btn-lg" href="<?= htmlspecialchars($hero['cta_href']) ?>">
            <?= htmlspecialchars($hero['cta_text']) ?>
        </a>
    </div>
</header>
<br>