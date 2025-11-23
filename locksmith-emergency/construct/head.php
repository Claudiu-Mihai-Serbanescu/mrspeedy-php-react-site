<?php

/** locksmith-birmingham/construct/head.php (SAFE) */
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../conectare.php';
require_once __DIR__ . '/../../vite.php';

mysqli_report(MYSQLI_REPORT_OFF); // nu arunca fatale din mysqli

$BASE = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) ? '/mrspeedy' : '';
$page_slug = basename($_SERVER['PHP_SELF'], '.php');

/* --- helper safe --- */
$val = fn($arr, $key, $fallback = '') => isset($arr[$key]) && $arr[$key] !== '' ? $arr[$key] : $fallback;

/* --- Zones (opțional) --- */
$zones = [];
if ($res = @$conn->query('SELECT name, slug, postcode FROM areas ORDER BY name ASC')) {
    while ($row = $res->fetch_assoc()) $zones[] = $row;
}

/* --- SEO din DB cu fallback --- */
$seo = null;
if ($stmt = @$conn->prepare('SELECT * FROM seo_meta WHERE page_slug = ? LIMIT 1')) {
    $stmt->bind_param('s', $page_slug);
    if (@$stmt->execute()) $seo = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
if (!$seo) {
    $seo = [
        'title'             => 'MrSpeedy Locksmith Birmingham – Professional Locksmith Services',
        'description'       => '24/7 emergency locksmith in Birmingham & West Midlands. Fast arrival, no hidden fees.',
        'canonical'         => 'https://mrspeedy.co.uk/',
        'og_title'          => 'MrSpeedy Locksmith Birmingham',
        'og_description'    => 'Emergency locksmith services 24/7.',
        'og_image'          => 'https://mrspeedy.co.uk/assets/img/og/og-locksmith-1200x630.jpg',
        'twitter_title'     => 'MrSpeedy Locksmith',
        'twitter_description' => '24/7 Emergency Locksmith',
        'twitter_image'     => 'https://mrspeedy.co.uk/assets/img/og/og-locksmith-1200x630.jpg',
        'schema_published'  => date('Y-m-d'),
        'last_modified'     => date('Y-m-d H:i:s'),
        'schema_rating_value' => 5,
        'schema_review_count' => 250,
        'schema_review_author' => 'Customer',
        'schema_review_body' => 'Great service.',
        'schema_breadcrumb'  => json_encode([
            ["position" => 1, "name" => "Home", "item" => "https://mrspeedy.co.uk/"],
            ["position" => 2, "name" => "Services", "item" => "https://mrspeedy.co.uk/locksmith-birmingham/"]
        ]),
    ];
}

/* --- NU logăm accesul până stabilim 500-ul --- */
/*
$page_name   = $_SERVER['REQUEST_URI'] ?? '';
$access_time = date('Y-m-d H:i:s');
$user_ip     = $_SERVER['REMOTE_ADDR'] ?? '';
$user_agent  = $_SERVER['HTTP_USER_AGENT'] ?? '';
$session_id  = session_id();
if ($ins = @$conn->prepare('INSERT INTO page_access_logs (page_accessed, access_time, user_ip, user_agent, session_id) VALUES (?, ?, ?, ?, ?)')) {
  $ins->bind_param('sssss', $page_name, $access_time, $user_ip, $user_agent, $session_id);
  @$ins->execute();
  $ins->close();
}
*/

/* --- Schema.org --- */
$breadcrumb_list = json_decode($val($seo, 'schema_breadcrumb', '[]'), true);
$schema_markup = [
    "@context" => "https://schema.org",
    "@graph" => [
        [
            "@type" => "WebPage",
            "@id"   => $val($seo, 'canonical'),
            "url"   => $val($seo, 'canonical'),
            "name"  => $val($seo, 'title'),
            "isPartOf" => ["@id" => "https://mrspeedy.co.uk/#website"],
            "image" => $val($seo, 'og_image'),
            "datePublished" => date("c", strtotime($val($seo, 'schema_published')) ?: time()),
            "dateModified"  => date("c", strtotime($val($seo, 'last_modified')) ?: time()),
            "description"   => $val($seo, 'description'),
            "breadcrumb" => ["@id" => $val($seo, 'canonical') . "#breadcrumb"],
            "inLanguage" => "en-GB"
        ],
        [
            "@type" => "BreadcrumbList",
            "@id"   => $val($seo, 'canonical') . "#breadcrumb",
            "itemListElement" => array_map(function ($item) {
                return [
                    "@type" => "ListItem",
                    "position" => (int)($item['position'] ?? 0),
                    "name" => $item['name'] ?? '',
                    "item" => $item['item'] ?? ''
                ];
            }, is_array($breadcrumb_list) ? $breadcrumb_list : [])
        ],
        [
            "@type" => "Service",
            "name" => "Emergency Locksmith & Property Boarding",
            "provider" => [
                "@type" => "LocalBusiness",
                "name" => "MrSpeedy Locksmith",
                "telephone" => "07846 716954",
                "address" => [
                    "@type" => "PostalAddress",
                    "streetAddress" => "172 Gravelly Hill",
                    "addressLocality" => "Birmingham",
                    "postalCode" => "B23 7PF",
                    "addressCountry" => "UK"
                ]
            ],
            "areaServed" => [
                "@type" => "GeoCircle",
                "geoMidpoint" => ["@type" => "GeoCoordinates", "latitude" => "52.4862", "longitude" => "-1.8904"],
                "geoRadius"   => "50"
            ],
            "serviceType" => "24/7 Emergency Property Boarding and Burglary Repairs",
            "aggregateRating" => [
                "@type" => "AggregateRating",
                "ratingValue" => (float)$val($seo, 'schema_rating_value', 5),
                "reviewCount" => (int)$val($seo, 'schema_review_count', 250)
            ],
            "review" => [
                "@type" => "Review",
                "author" => ["@type" => "Person", "name" => $val($seo, 'schema_review_author', 'Customer')],
                "reviewBody" => $val($seo, 'schema_review_body', 'Great service.'),
                "reviewRating" => ["@type" => "Rating", "ratingValue" => "5"]
            ]
        ]
    ]
];

/* --- JSON-LD safe (evită fatale pe UTF-8) --- */
$schema_json = json_encode($schema_markup, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
if ($schema_json === false) {
    $schema_json = '{}';
}

/* --- Hero din DB (sigur) --- */
$slug = basename($_SERVER['PHP_SELF'], '.php');
$hero = null;
if ($stmt = @$conn->prepare("SELECT ss.data
  FROM service_sections ss
  JOIN services s ON s.id = ss.service_id
  WHERE s.slug = ? AND ss.type = 'hero' AND ss.is_active = 1
  ORDER BY ss.position ASC
  LIMIT 1")) {
    $stmt->bind_param('s', $slug);
    if (@$stmt->execute()) {
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) $hero = json_decode($row['data'], true);
    }
    $stmt->close();
}
if (!$hero && ($st2 = @$conn->prepare("SELECT * FROM service_hero WHERE service_slug = ? LIMIT 1"))) {
    $st2->bind_param('s', $slug);
    if (@$st2->execute()) {
        $r2 = $st2->get_result()->fetch_assoc();
        if ($r2) {
            $hero = [
                'hero_image'     => $r2['hero_image'],
                'hero_image_alt' => $r2['hero_image_alt'],
                'h1_html'        => $r2['h1_html'],
                'sub_h2'         => $r2['sub_h2'],
                'sub_p_html'     => $r2['sub_p_html'],
                'cta_text'       => $r2['cta_text'],
                'cta_href'       => $r2['cta_href'],
            ];
        }
    }
    $st2->close();
}
if (!$hero) {
    $hero = [
        'hero_image'     => 'https://mrspeedy.co.uk/assets/img/services/default-hero.jpg',
        'hero_image_alt' => 'Locksmith Service',
        'h1_html'        => '<strong>Professional <span>Locksmith Services</span></strong>',
        'sub_h2'         => null,
        'sub_p_html'     => 'Fast, friendly and reliable locksmiths in Birmingham.',
        'cta_text'       => 'Call Now 07846 716954',
        'cta_href'       => 'tel:+447846716954',
    ];
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title><?= htmlspecialchars($val($seo, 'title'), ENT_QUOTES) ?></title>
    <meta name="description" content="<?= htmlspecialchars($val($seo, 'description'), ENT_QUOTES) ?>" />
    <meta name="robots" content="index, follow" />
    <meta http-equiv="last-modified" content="<?= gmdate('D, d M Y H:i:s', strtotime($val($seo, 'last_modified')) ?: time()) . ' GMT' ?>" />
    <link rel="canonical" href="<?= htmlspecialchars($val($seo, 'canonical'), ENT_QUOTES) ?>" />
    <meta name="theme-color" content="#0b1b1b" />

    <!-- Open Graph -->
    <meta property="og:locale" content="en_GB" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?= htmlspecialchars($val($seo, 'og_title', $val($seo, 'title')), ENT_QUOTES) ?>" />
    <meta property="og:description" content="<?= htmlspecialchars($val($seo, 'og_description', $val($seo, 'description')), ENT_QUOTES) ?>" />
    <meta property="og:url" content="<?= htmlspecialchars($val($seo, 'canonical'), ENT_QUOTES) ?>" />
    <meta property="og:site_name" content="MrSpeedy Locksmith" />
    <meta property="og:image" content="<?= htmlspecialchars($val($seo, 'og_image'), ENT_QUOTES) ?>" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:type" content="image/jpeg" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?= htmlspecialchars($val($seo, 'twitter_title', $val($seo, 'title')), ENT_QUOTES) ?>" />
    <meta name="twitter:description" content="<?= htmlspecialchars($val($seo, 'twitter_description', $val($seo, 'description')), ENT_QUOTES) ?>" />
    <meta name="twitter:image" content="<?= htmlspecialchars($val($seo, 'twitter_image', $val($seo, 'og_image')), ENT_QUOTES) ?>" />

    <!-- Favicons -->
    <link rel="icon" href="https://mrspeedy.co.uk/assets/img/favicon-mrspeedy.png" />
    <link rel="apple-touch-icon" href="https://mrspeedy.co.uk/assets/img/favicon-mrspeedy.png" />

    <!-- Fonts & Bootstrap -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700&display=swap" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Flag pt. styling specific -->
    <script>
        document.documentElement.classList.add('page-service');
    </script>
    <style>
        .page-service header.masthead .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .container {
            margin: 30px 0px;
        }

        .site-footer .container {
            margin-top: 0px;
            margin-bottom: 0px;
        }
    </style>

    <!-- JSON-LD -->
    <script type="application/ld+json">
        <?= $schema_json ?>
    </script>
</head>