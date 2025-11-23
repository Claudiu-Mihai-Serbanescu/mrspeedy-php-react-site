<?php
// ==== bootstrap sigur ====
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../conectare.php';
require_once __DIR__ . '/../../vite.php'; // dacă îl folosești; nu e obligatoriu aici

// prefix root: "" în producție, "/mrspeedy" local
$BASE = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) ? '/mrspeedy' : '';

// helpers
$safe = fn($v, $fb = '') => htmlspecialchars($v ?? $fb, ENT_QUOTES);
$area = $area ?? [];
$locName   = $safe($area['name'] ?? 'Birmingham');
$postCode  = $safe($area['postCode'] ?? 'B1');
$link      = $safe($area['link'] ?? $_SERVER['REQUEST_URI']);
$updated   = !empty($area['updated_at']) ? date('c', strtotime($area['updated_at'])) : date('c');
$imageFile = $safe($area['image'] ?? 'default-location.jpg');
$imgAbs    = "https://mrspeedy.co.uk/assets/img/areas/{$imageFile}";
$street    = $safe($area['streetAddress'] ?? '898b Bristol Rd South');
$lat       = $safe($area['latitude'] ?? '52.4862');
$lng       = $safe($area['longitude'] ?? '-1.8904');

// SEO dinamic
$metaTitle = "Emergency Locksmith {$locName} {$postCode} – 24/7 Local Locksmith | Mr Speedy";
$metaDesc  = "Local emergency locksmith in {$locName} ({$postCode}). 24/7, typical 30–45 min response, no call-out fee, non-destructive entry, uPVC doors, lock repair & replacement.";
$canonAbs  = (strpos($link, 'http') === 0) ? $link : ("https://mrspeedy.co.uk" . $link);

// JSON-LD (pregătit jos)
$schema = [
    "@context" => "https://schema.org",
    "@graph" => [
        [
            "@type" => ["LocalBusiness", "Locksmith"],
            "@id" => "https://mrspeedy.co.uk/#business",
            "name" => "Mr Speedy Locksmith",
            "url" => "https://mrspeedy.co.uk/",
            "telephone" => "+447846716954",
            "priceRange" => "££",
            "logo" => "https://mrspeedy.co.uk/assets/img/logo_fastLocksmith.png",
            "image" => "https://mrspeedy.co.uk/assets/img/logo_fastLocksmith.png",
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => $street,
                "addressLocality" => $locName,
                "addressRegion" => "West Midlands",
                "postalCode" => $postCode,
                "addressCountry" => "GB"
            ],
            "geo" => ["@type" => "GeoCoordinates", "latitude" => $lat, "longitude" => $lng],
            "openingHoursSpecification" => [["@type" => "OpeningHoursSpecification", "dayOfWeek" => ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"], "opens" => "00:00", "closes" => "23:59"]],
            "sameAs" => ["https://www.facebook.com/mrspeedylocksmithbirmingham"],
            "areaServed" => [["@type" => "AdministrativeArea", "name" => "{$locName} ({$postCode})"]]
        ],
        [
            "@type" => "Service",
            "@id" => "{$canonAbs}#emergency",
            "name" => "Emergency Locksmith – {$locName} ({$postCode})",
            "serviceType" => "Emergency Locksmith",
            "provider" => ["@id" => "https://mrspeedy.co.uk/#business"],
            "areaServed" => "{$locName} {$postCode}",
            "offers" => [
                "@type" => "Offer",
                "availability" => "https://schema.org/InStock",
                "priceCurrency" => "GBP",
                "price" => "59",
                "url" => $canonAbs,
                "priceSpecification" => ["@type" => "UnitPriceSpecification", "price" => "59", "priceCurrency" => "GBP", "name" => "From"]
            ]
        ],
        [
            "@type" => "WebPage",
            "@id" => "{$canonAbs}#webpage",
            "url" => $canonAbs,
            "name" => "Locksmith {$locName} ({$postCode}) – 24/7 Emergency",
            "primaryImageOfPage" => $imgAbs,
            "inLanguage" => "en-GB",
            "dateModified" => $updated,
            "isPartOf" => ["@id" => "https://mrspeedy.co.uk/#website"]
        ],
        [
            "@type" => "WebSite",
            "@id" => "https://mrspeedy.co.uk/#website",
            "url" => "https://mrspeedy.co.uk/",
            "name" => "Mr Speedy Locksmith",
            "inLanguage" => "en-GB",
            "potentialAction" => ["@type" => "SearchAction", "target" => "https://mrspeedy.co.uk/?s={search_term_string}", "query-input" => "required name=search_term_string"]
        ],
        [
            "@type" => "BreadcrumbList",
            "@id" => "{$canonAbs}#breadcrumbs",
            "itemListElement" => [
                ["@type" => "ListItem", "position" => 1, "name" => "Home", "item" => "https://mrspeedy.co.uk/"],
                ["@type" => "ListItem", "position" => 2, "name" => "Locksmith {$locName} ({$postCode})", "item" => $canonAbs]
            ]
        ]
    ]
];
$schema_json = json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}';
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Core meta -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="robots" content="index,follow,max-image-preview:large" />
    <meta name="theme-color" content="#ff5821" />

    <!-- SEO -->
    <title><?= $metaTitle ?></title>
    <meta name="description" content="<?= $metaDesc ?>" />
    <link rel="canonical" href="<?= $canonAbs ?>" />

    <!-- OG / Twitter -->
    <meta property="og:locale" content="en_GB" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?= $metaTitle ?>" />
    <meta property="og:description" content="<?= $metaDesc ?>" />
    <meta property="og:url" content="<?= $canonAbs ?>" />
    <meta property="og:site_name" content="Mr Speedy Locksmith" />
    <meta property="article:modified_time" content="<?= $updated ?>" />
    <meta property="og:image" content="<?= $imgAbs ?>" />
    <meta property="og:image:alt" content="Emergency locksmith in <?= $locName ?> (<?= $postCode ?>)" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?= $metaTitle ?>" />
    <meta name="twitter:description" content="<?= $metaDesc ?>" />
    <meta name="twitter:image" content="<?= $imgAbs ?>" />

    <!-- Preload LCP -->
    <link rel="preload" as="image" href="<?= $imgAbs ?>"
        imagesrcset="<?= $imgAbs ?> 900w, <?= $imgAbs ?> 1920w" fetchpriority="high" />

    <!-- Favicons -->
    <link rel="icon" href="https://mrspeedy.co.uk/assets/img/favicon-mrspeedy.png" sizes="32x32" />
    <link rel="apple-touch-icon" href="https://mrspeedy.co.uk/assets/img/favicon-mrspeedy.png" />

    <!-- Fonts & Vendors (minim) -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600;700;900&display=swap" rel="stylesheet" />
    <link href="https://mrspeedy.co.uk/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://mrspeedy.co.uk/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v6.3.0/css/all.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700&display=swap" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- BUNDLE-UL TĂU (SCSS compilat) – cu prefix $BASE  -->
    <link rel="stylesheet" href="<?= $BASE ?>/assets/dist/main.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- JSON-LD -->
    <script type="application/ld+json">
        <?= $schema_json ?>
    </script>
</head>