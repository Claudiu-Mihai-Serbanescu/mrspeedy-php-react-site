<!-- Preload LCP image for this location -->
<link rel="preload"
    as="image"
    href="https://mrspeedy.co.uk/assets/img/areas/<?php echo $image; ?>"
    imagesrcset="
        https://mrspeedy.co.uk/assets/img/areas/<?php echo $image; ?> 900w,
        https://mrspeedy.co.uk/assets/img/areas/<?php echo $image; ?> 1920w"
    fetchpriority="high" />

<!-- HERO: Location — Emergency Locksmith -->
<div class="loc-hero" role="banner"
    aria-label="Emergency locksmith in <?php echo $locationName; ?> <?php echo $postCode; ?>">
    <div class="loc-hero__media">
        <picture>
            <source media="(max-width:640px)"
                srcset="https://mrspeedy.co.uk/assets/img/areas/<?php echo $image; ?>" />
            <source media="(max-width:1200px)"
                srcset="https://mrspeedy.co.uk/assets/img/areas/<?php echo $image; ?>" />
            <img class="loc-hero__img"
                src="https://mrspeedy.co.uk/assets/img/areas/<?php echo $image; ?>"
                alt="Local emergency locksmith in <?php echo $locationName; ?> (<?php echo $postCode; ?>)"
                width="1920" height="1080" fetchpriority="high" decoding="async" />
        </picture>
        <span class="loc-hero__badge">Local to <?php echo $locationName; ?></span>
    </div>

    <!-- Conversion panel -->
    <div class="loc-hero__panel" itemscope itemtype="https://schema.org/LocalBusiness">
        <meta itemprop="name" content="Mr Speedy Locksmith" />
        <meta itemprop="areaServed" content="<?php echo $locationName; ?> <?php echo $postCode; ?>" />
        <meta itemprop="serviceType" content="Emergency Locksmith" />

        <h1 class="loc-hero__title">
            Emergency Locksmith in <?php echo $locationName; ?> <span class="nowrap">(<?php echo $postCode; ?>)</span> — 24/7
        </h1>

        <p class="loc-hero__lead">
            Locked out or keys lost? Get a <strong>local emergency locksmith</strong> with
            <strong>30–45&nbsp;minute response</strong>, <strong>no call-out fee</strong>, and
            <strong>non-destructive entry</strong> wherever possible. uPVC doors, lock repair &amp; replacement, burglary repairs.
        </p>

        <ul class="loc-hero__chips" aria-label="Key benefits">
            <li><i class="fa-solid fa-clock"></i> Open 24/7</li>
            <li><i class="fa-solid fa-bolt"></i> 30–45 min response</li>
            <li><i class="fa-solid fa-check-circle"></i> No call-out fee</li>
            <li><i class="fa-solid fa-shield-halved"></i> DBS checked &amp; insured</li>
            <li><i class="fa-solid fa-screwdriver-wrench"></i> TS007/SS312 anti-snap</li>
        </ul>

        <div class="loc-hero__cta">
            <a href="tel:+447846716954" class="btn btn-warning fw-semibold">
                <i class="fa-solid fa-phone me-2"></i> Call 07846 716 954
            </a>
            <a href="https://wa.me/447846716954" class="btn btn-wa fw-semibold" style="border: 1px solid;" target="_blank" rel="noopener">
                <i class="fa-brands fa-whatsapp me-2"></i> WhatsApp
            </a>
            <a href="#quote" class="btn btn-ghost fw-semibold" style="border: 1px solid;">
                <i class="fa-regular fa-file-lines me-2"></i> Get a Quote
            </a>
        </div>

        <p class="loc-hero__areas">
            Covering <strong><?php echo $locationName; ?></strong> and nearby postcodes including <strong><?php echo $postCode; ?></strong>.
        </p>
    </div>
</div>

<!-- Quick links under hero (SEO + UX) -->
<nav class="loc-hero-tabs" role="navigation" aria-label="Local locksmith quick links">
    <div class="container tabs-scroller">
        <a class="tab"
            href="<?= $BASE ?? '' ?>/local-locksmith/"
            title="Local locksmith in <?= htmlspecialchars($area['name'] ?? $locationName ?? '') ?> <?= htmlspecialchars($area['postCode'] ?? $postCode ?? '') ?>">
            Local locksmith
        </a>

        <a class="tab"
            href="<?= $BASE ?? '' ?>/locksmith-emergency/locksmith-service.php"
            title="24/7 emergency locksmith in <?= htmlspecialchars($area['name'] ?? $locationName ?? '') ?>">
            Emergency locksmith
        </a>

        <a class="tab"
            href="<?= $BASE ?? '' ?>/contact-locksmith/index.php"
            title="Locksmith near you – contact Mr Speedy">
            Locksmith near you
        </a>
    </div>
</nav>