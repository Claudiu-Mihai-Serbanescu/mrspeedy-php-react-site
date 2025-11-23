<footer class="site-footer" role="contentinfo" aria-labelledby="footer-title" itemscope
    itemtype="https://schema.org/Locksmith">
    <h2 id="footer-title" class="visually-hidden">Mr Speedy Locksmith — footer</h2>

    <div class="container">
        <div class="sf-top">
            <!-- Brand & NAP -->
            <div class="sf-col sf-brand" aria-label="Company details">
                <a href="/" class="sf-logo" itemprop="url">
                    <img src="<?= $BASE ?>/assets/img/navbar-logo2.svg" width="168" height="40"
                        alt="Mr Speedy Locksmith" itemprop="logo">
                </a>

                <p class="sf-name" itemprop="name">Mr. Speedy Locksmith</p>
                <address class="sf-address" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                    <span itemprop="streetAddress">123 High Street</span><br>
                    <span itemprop="addressLocality">Birmingham</span>,
                    <span itemprop="addressRegion">West Midlands</span>
                    <span itemprop="postalCode">B1</span>,
                    <span itemprop="addressCountry">UK</span>
                </address>

                <p class="sf-contact">
                    <a href="tel:+447846716954" class="sf-tel" itemprop="telephone" rel="nofollow">07846 716 954</a><br>
                    <a href="mailto:hello@mrspeedy.co.uk" itemprop="email" class="sf-mail">hello@mrspeedy.co.uk</a>
                </p>

                <ul class="sf-hours" aria-label="Opening hours" itemprop="openingHoursSpecification" itemscope
                    itemtype="https://schema.org/OpeningHoursSpecification">
                    <li>
                        <meta itemprop="dayOfWeek" content="Monday"><span>Mon–Sun</span> <time itemprop="opens"
                            datetime="00:00">24/7</time>
                    </li>
                </ul>
            </div>

            <!-- Quick links -->
            <nav class="sf-col" aria-label="Quick links">
                <h3 class="sf-head">Quick links</h3>
                <ul class="sf-list">
                    <li><a href="/">Home</a></li>
                    <li><a href="/locksmith-birmingham/index.php">Services</a></li>
                    <li><a href="/locksmith-emergency/locksmith-service.php">24/7 Emergency locksmith</a></li>
                    <li><a href="/local-locksmith/index.php">Areas</a></li>
                    <li><a href="/locksmith-locks/index.php">Locks</a></li>
                    <li><a href="/about-locksmith/index.php">About</a></li>
                    <li><a href="/contact-locksmith/index.php">Contact</a></li>
                </ul>
            </nav>

            <!-- Popular services (deep links help SEO) -->
            <nav class="sf-col" aria-label="Popular services">
                <h3 class="sf-head">Popular services</h3>
                <ul class="sf-list">
                    <li><a href="/locksmith-birmingham/Non-Destructive-Entry-%26-Snapped-Key-Extraction.php">Non-destructive
                            entry</a></li>
                    <li><a href="/locksmith-birmingham/lock-replacement-and-installation.php">Lock replacement &amp;
                            installation</a></li>
                    <li><a href="/locksmith-birmingham/burglary-repairs-property-boarding.php">Burglary repairs &amp;
                            boarding</a></li>
                    <li><a href="/locksmith-birmingham/upvc-repairs.php">uPVC door &amp; window repairs</a></li>
                    <li><a href="/locksmith-birmingham/Fire-Doors-Roller-Shutters-Gates-Garage-Doors.php">Fire/garage
                            doors &amp; shutters</a></li>
                </ul>
            </nav>

            <!-- Social & trust -->
            <section class="sf-col sf-social" aria-label="Social and trust">
                <h3 class="sf-head">Connect</h3>
                <p class="sf-social-row">
                    <a class="btn btn-dark btn-social" href="https://www.facebook.com/mrspeedylocksmithbirmingham"
                        target="_blank" rel="me noopener" aria-label="Facebook">
                        <i class="fab fa-facebook-f" aria-hidden="true"></i>
                    </a>
                    <a class="btn btn-dark btn-social" href="https://wa.me/447846716954" target="_blank" rel="noopener"
                        aria-label="WhatsApp">
                        <i class="fab fa-whatsapp" aria-hidden="true"></i>
                    </a>
                </p>
                <p class="sf-meta"><span itemprop="priceRange">££</span> • <span>DBS checked</span> • <span>Fully
                        insured</span></p>
            </section>
        </div>

        <!-- CTA bar -->
        <div class="sf-cta" aria-label="Emergency contact">
            <a href="tel:+447846716954" class="btn btn-warning sf-call">Call 07846 716 954 (24/7)</a>
            <a href="/contact-locksmith/index.php" class="btn btn-outline-light">Get a quote</a>
        </div>

        <!-- Legal row -->
        <div class="sf-bottom">
            <p class="sf-copy">© <span itemprop="name">Mr. Speedy Locksmith</span> 2024</p>
            <nav class="sf-legal" aria-label="Legal">
                <a href="/privacy-policy.php">Privacy Policy</a>
                <a href="/terms.php">Terms of Use</a>
                <a href="/cookie-policy.php">Cookie Policy</a>
                <a href="/sitemap.xml">Sitemap</a>
                <a href="/accessibility-statement.php">Accessibility</a>
            </nav>
        </div>
    </div>

    <!-- JSON-LD (LocalBusiness → Locksmith) -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Locksmith",
            "name": "Mr. Speedy Locksmith",
            "url": "https://mrspeedy.co.uk/",
            "logo": "https://mrspeedy.co.uk/assets/img/navbar-logo2.svg",
            "telephone": "+44 7846 716954",
            "email": "hello@mrspeedy.co.uk",
            "priceRange": "££",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "123 High Street",
                "addressLocality": "Birmingham",
                "addressRegion": "West Midlands",
                "postalCode": "B1",
                "addressCountry": "GB"
            },
            "areaServed": ["Birmingham", "Solihull", "Sutton Coldfield", "Walsall", "Dudley"],
            "openingHoursSpecification": [{
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
                "opens": "00:00",
                "closes": "23:59"
            }],
            "sameAs": [
                "https://www.facebook.com/mrspeedylocksmithbirmingham"
            ]
        }
    </script>
</footer>
<!-- Butoane flotante (un singur container părinte) -->
<div class="floating-actions">
    <a href="https://wa.me/+447846716954" class="float" target="_blank" style="text-decoration: none">
        <i class="fa-brands fa-whatsapp my-float m-2" style="font-size: 1rem;"></i>
        <span class="text-label">Text Now</span>
    </a>
    <a href="tel:+447846716954" class="float2" target="_blank" style="text-decoration: none">
        <i class="fa-solid fa-phone my-float m-2"></i>
        <span class="text-label">Call Now</span>
    </a>
</div>

<!-- Vendor JS (CDN, doar ce-ți trebuie) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

<!-- Vite entry (dev) — va injecta și SCSS-ul importat în /src/main.js -->
<?php vite('src/main.js'); ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const nav = document.getElementById("navbar");
        const toggle = document.querySelector(".mobile-nav-toggle");

        if (!nav || !toggle) return;

        // ia iconul din interior (fa-bars / fa-times)
        const icon = toggle.querySelector("i");

        toggle.addEventListener("click", function(e) {
            e.preventDefault();
            nav.classList.toggle("navbar-mobile");

            if (icon) {
                icon.classList.toggle("fa-bars");
                icon.classList.toggle("fa-times");
            }
        });

        // activează dropdownurile doar pe mobil
        nav.querySelectorAll(".dropdown > a").forEach((link) => {
            link.addEventListener("click", function(e) {
                if (nav.classList.contains("navbar-mobile")) {
                    e.preventDefault();
                    this.nextElementSibling?.classList.toggle("dropdown-active");
                }
            });
        });
    });
</script>
<style>
    @media (max-width: 768px) {

        .float,
        .float2 {
            position: fixed;
            width: 100%;
            height: 33px;
            left: 0;
            border-radius: 0;
            font-size: 18px;
            text-align: center;
            line-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .float {
            bottom: 33px;
            background: #25d366
        }

        .float2 {
            bottom: 0;
            background: #2555d3
        }

        .text-label {
            display: inline-block;
            font-size: 16px;
            font-weight: 700;
            margin-left: 10px
        }

        .float i,
        .float2 i {
            font-size: 16px
        }
    }
</style>