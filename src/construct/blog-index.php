<?php
// prefix (la fel ca în celelalte componente)
$BASE = $BASE ?? (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ? '/mrspeedy' : '');
?>
<section id="blog" class="page-section blog-section">
    <div class="container">
        <!-- Header SEO -->
        <div class="text-center mb-4 mb-md-5">
            <h2 class="section-title text-uppercase mb-2">
                Birmingham Locksmith Blog: Expert Tips, Security &amp; Door Hardware
            </h2>
            <p class="section-subheading text-muted m-0">
                Practical advice from a local locksmith — non-destructive entry, UPVC fixes, cylinders, and commercial compliance.
            </p>
        </div>

        <div class="swiper blogNou-slider">
            <div class="swiper-wrapper">

                <!-- Slide 1 -->
                <div class="swiper-slide">
                    <article class="card blog-card h-100">
                        <a href="#" class="stretched-link text-decoration-none text-reset">
                            <figure class="blog-media m-0">
                                <img
                                    src="<?= $BASE ?>/assets/img/services/repair-round.jpg"
                                    class="card-img-top"
                                    alt="When to Replace vs Repair a Lock"
                                    loading="lazy" decoding="async">
                                <span class="badge blog-badge">Locksmith Tips</span>
                            </figure>
                            <div class="card-body">
                                <h3 class="card-title h5">Replace vs Repair: Best Choice for Your Lock</h3>
                                <div class="blog-meta small text-muted mb-2">by MrSpeedy • Sep 2024 • 4 min read</div>
                                <p class="card-text line-clamp-3">
                                    Not every faulty cylinder needs replacing. Here’s how a pro decides between servicing, rekeying
                                    or full replacement — and how you can save money without risking security…
                                </p>
                                <span class="read-more-cta">Read More</span>
                            </div>
                        </a>
                    </article>
                </div>

                <!-- Slide 2 -->
                <div class="swiper-slide">
                    <article class="card blog-card h-100">
                        <a href="#" class="stretched-link text-decoration-none text-reset">
                            <figure class="blog-media m-0">
                                <img
                                    src="<?= $BASE ?>/assets/img/services/extraction-round.jpg"
                                    class="card-img-top"
                                    alt="Non-Destructive Entry Explained"
                                    loading="lazy" decoding="async">
                                <span class="badge blog-badge">Emergency</span>
                            </figure>
                            <div class="card-body">
                                <h3 class="card-title h5">Non-Destructive Entry: What It Is &amp; When It Works</h3>
                                <div class="blog-meta small text-muted mb-2">by MrSpeedy • Aug 2024 • 3 min read</div>
                                <p class="card-text line-clamp-3">
                                    Lockouts don’t always mean drilling. Pin manipulation, raking and decoding can open most domestic
                                    locks cleanly. Here’s what to expect during a professional unlock…
                                </p>
                                <span class="read-more-cta">Read More</span>
                            </div>
                        </a>
                    </article>
                </div>

                <!-- Slide 3 -->
                <div class="swiper-slide">
                    <article class="card blog-card h-100">
                        <a href="#" class="stretched-link text-decoration-none text-reset">
                            <figure class="blog-media m-0">
                                <img
                                    src="<?= $BASE ?>/assets/img/services/upvc-round.jpg"
                                    class="card-img-top"
                                    alt="UPVC Door Not Latching"
                                    loading="lazy" decoding="async">
                                <span class="badge blog-badge">UPVC</span>
                            </figure>
                            <div class="card-body">
                                <h3 class="card-title h5">UPVC Doors: Quick Fixes for Misalignment</h3>
                                <div class="blog-meta small text-muted mb-2">by MrSpeedy • Jul 2024 • 4 min read</div>
                                <p class="card-text line-clamp-3">
                                    Dropped handles, stiff multipoint strips, swollen frames — simple adjustments restore smooth
                                    operation and extend hardware life. A short checklist to try first…
                                </p>
                                <span class="read-more-cta">Read More</span>
                            </div>
                        </a>
                    </article>
                </div>

                <!-- Slide 4 -->
                <div class="swiper-slide">
                    <article class="card blog-card h-100">
                        <a href="#" class="stretched-link text-decoration-none text-reset">
                            <figure class="blog-media m-0">
                                <img
                                    src="<?= $BASE ?>/assets/img/services/fire-round.jpg"
                                    class="card-img-top"
                                    alt="Commercial Fire Door Compliance"
                                    loading="lazy" decoding="async">
                                <span class="badge blog-badge">Commercial</span>
                            </figure>
                            <div class="card-body">
                                <h3 class="card-title h5">Fire Doors: Common Compliance Fails We See</h3>
                                <div class="blog-meta small text-muted mb-2">by MrSpeedy • Jun 2024 • 5 min read</div>
                                <p class="card-text line-clamp-3">
                                    From missing intumescent strips to incorrect cylinders, here are the top issues that keep
                                    commercial doors from passing inspection — and how we fix them fast…
                                </p>
                                <span class="read-more-cta">Read More</span>
                            </div>
                        </a>
                    </article>
                </div>

                <!-- Slide 5 -->
                <div class="swiper-slide">
                    <article class="card blog-card h-100">
                        <a href="#" class="stretched-link text-decoration-none text-reset">
                            <figure class="blog-media m-0">
                                <img
                                    src="<?= $BASE ?>/assets/img/services/burglary-round.jpg"
                                    class="card-img-top"
                                    alt="After Burglary: Securing Your Home"
                                    loading="lazy" decoding="async">
                                <span class="badge blog-badge">Security</span>
                            </figure>
                            <div class="card-body">
                                <h3 class="card-title h5">After a Break-In: 7 Immediate Security Upgrades</h3>
                                <div class="blog-meta small text-muted mb-2">by MrSpeedy • May 2024 • 4 min read</div>
                                <p class="card-text line-clamp-3">
                                    From anti-snap cylinders to sash jammers and hinge bolts, these quick upgrades harden typical
                                    entry points without a full door replacement…
                                </p>
                                <span class="read-more-cta">Read More</span>
                            </div>
                        </a>
                    </article>
                </div>

                <!-- Slide 6 -->
                <div class="swiper-slide">
                    <article class="card blog-card h-100">
                        <a href="#" class="stretched-link text-decoration-none text-reset">
                            <figure class="blog-media m-0">
                                <img
                                    src="<?= $BASE ?>/assets/img/og/og-locksmith-1200x630.jpg"
                                    class="card-img-top"
                                    alt="Choosing the Right Cylinder"
                                    loading="lazy" decoding="async">
                                <span class="badge blog-badge">Hardware</span>
                            </figure>
                            <div class="card-body">
                                <h3 class="card-title h5">Choosing a Cylinder: Anti-Snap, Anti-Bump, TS007</h3>
                                <div class="blog-meta small text-muted mb-2">by MrSpeedy • Apr 2024 • 6 min read</div>
                                <p class="card-text line-clamp-3">
                                    What the ratings mean, when to rekey vs replace, and how to match thumb-turns to fire safety
                                    requirements in flats and HMOs…
                                </p>
                                <span class="read-more-cta">Read More</span>
                            </div>
                        </a>
                    </article>
                </div>

            </div>

            <!-- Controls (scoped în container) -->
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev" aria-label="Previous"></div>
            <div class="swiper-button-next" aria-label="Next"></div>
        </div>
    </div>
</section>