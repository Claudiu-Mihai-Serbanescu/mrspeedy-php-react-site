<?php
// src/construct/locationsBox.php

// === DB connect din rădăcină ===
$root = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/');
$isLocal = strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false
    || strpos($_SERVER['HTTP_HOST'] ?? '', '127.0.0.1') !== false;
if ($isLocal) $root .= '/mrspeedy';
require_once $root . '/conectare.php';

// Prefix pentru link-uri (localhost vs producție)
$BASE = $isLocal ? '/mrspeedy' : '';

// Helper URL (identic cu tabelul)
$makeAreaUrl = function (string $slug, ?string $pc) use ($BASE): string {
    $slug = trim($slug);
    $pc   = strtoupper(preg_replace('~\s+~', '', (string)$pc));
    if ($slug === '') return '#';
    return $BASE . '/local-locksmith/' . rawurlencode($slug . ($pc ? "-$pc" : '')) . '.php';
};

// === Helper imagine ===
$IMG_DIR_WEB = $BASE . '/assets/img/areas';      // pentru <img src="...">
$fallbackImg = $IMG_DIR_WEB . '/default-location.jpg';

/** Normalizează un path relativ la URL web. */
$toWeb = function (string $path) use ($BASE): string {
    if (preg_match('~^https?://~i', $path)) return $path;         // deja absolut
    $path = '/' . ltrim($path, '/');                              // leading slash
    if ($BASE && strpos($path, $BASE . '/') !== 0) return $BASE . $path; // prefix local
    return $path;
};

/** Verifică existența unui path web pe filesystem (fără dublarea /mrspeedy). */
$existsWeb = function (string $webPath) use ($root, $BASE, $isLocal): bool {
    if (preg_match('~^https?://~i', $webPath)) return true;       // nu verificăm CDN/extern
    $path = parse_url($webPath, PHP_URL_PATH) ?: $webPath;
    if ($isLocal && $BASE && strpos($path, $BASE . '/') === 0) {
        $path = substr($path, strlen($BASE));                     // /assets/...
    }
    return file_exists($root . $path);
};

/** Alege imaginea corectă din DB / preferință / slug, apoi verifică existența. */
$resolveImage = function (?string $dbImage, string $slug, ?string $preferred) use ($toWeb, $existsWeb, $IMG_DIR_WEB, $fallbackImg): string {
    $candidates = [];

    // 0) preferință explicită (din lista ta)
    if ($preferred) {
        // dacă e doar nume de fișier, pune în /assets/img/areas/
        if (!preg_match('~^https?://~i', $preferred) && strpos($preferred, '/') === false) {
            $candidates[] = $IMG_DIR_WEB . '/' . $preferred;
        } else {
            $candidates[] = $toWeb($preferred);
        }
    }

    // 1) ce vine din DB
    $dbImage = trim((string)$dbImage);
    if ($dbImage !== '') {
        if (preg_match('~^https?://~i', $dbImage)) {
            $candidates[] = $dbImage;
        } else {
            if (strpos($dbImage, '/') === false) {
                $candidates[] = $IMG_DIR_WEB . '/' . $dbImage; // doar nume fișier
            } else {
                $candidates[] = $toWeb($dbImage);              // cale relativă
            }
        }
    }

    // 2) variante pe slug
    $slugClean = preg_replace('~[^A-Za-z0-9\-]~', '', $slug);
    $slugLower = strtolower($slugClean);
    $slugTitle = implode('-', array_map('ucfirst', explode('-', $slugLower)));

    foreach (['jpg', 'webp', 'png', 'jpeg'] as $ext) {
        $candidates[] = $IMG_DIR_WEB . '/' . $slugLower . '.' . $ext;
        $candidates[] = $IMG_DIR_WEB . '/' . $slugTitle . '.' . $ext;
        $candidates[] = $IMG_DIR_WEB . '/hero-' . $slugLower . '.' . $ext;
        $candidates[] = $IMG_DIR_WEB . '/hero-' . $slugTitle . '.' . $ext;
    }

    // 3) primul care există
    foreach ($candidates as $c) {
        $web = preg_match('~^https?://~i', $c) ? $c : $toWeb($c);
        if ($existsWeb($web)) return $web;
    }

    // fallback
    return $toWeb($fallbackImg);
};

// === Lista FIXĂ în ordinea dorită (slug, postCode, nume de afișat, imagine preferată)
$wanted = [
    ['slug' => 'Four-Oaks',              'postCode' => 'B74',        'display' => 'Four Oaks',           'prefImage' => 'fourOaks.jpg'],
    ['slug' => 'Coventry',               'postCode' => 'CV1-CV-7',   'display' => 'Coventry',            'prefImage' => 'hero-coventry.jpg'],
    ['slug' => 'Hamstead',               'postCode' => 'B43',        'display' => 'Hamstead',            'prefImage' => 'hero-hamstead.jpg'],
    ['slug' => 'Solihull',               'postCode' => 'B91',        'display' => 'Solihull',            'prefImage' => 'hero-solihull.jpg'],
    ['slug' => 'Knowle',                 'postCode' => 'B93',        'display' => 'Knowle',              'prefImage' => 'hero-Knowle.jpg'],
    ['slug' => 'Dorridge',               'postCode' => 'B93',        'display' => 'Dorridge',            'prefImage' => 'hero-Dorridge.jpg'],
    ['slug' => 'Shirley',                'postCode' => 'B90',        'display' => 'Shirley',             'prefImage' => 'hero-Shirley1.jpg'],
    ['slug' => 'Aldridge',               'postCode' => 'WS9',        'display' => 'Walsall Aldridge',    'prefImage' => 'Aldridge.jpg'],
    ['slug' => 'Dudley',                 'postCode' => 'DY1-DY3',    'display' => 'Dudley',              'prefImage' => 'hero-Dudley.jpg'],
    ['slug' => 'Brierley-Hill',          'postCode' => 'DY5',        'display' => 'Brierley Hill',       'prefImage' => 'hero-Brierley.jpg'],
    ['slug' => 'Stourbridge',            'postCode' => 'DY8',        'display' => 'Stourbridge',         'prefImage' => 'hero-Stourbridge.jpg'],
    ['slug' => 'Stratford-Upon-Avon',    'postCode' => 'CV37',       'display' => 'Stratford-Upon-Avon', 'prefImage' => 'stratford.jpg'],
    ['slug' => 'Alcester',               'postCode' => 'B49',        'display' => 'Alcester',            'prefImage' => 'Alcester_B49-locksmith.jpg'],
    ['slug' => 'Kenilworth',             'postCode' => 'CV8',        'display' => 'Kenilworth',          'prefImage' => 'hero-Kenilworth.jpg'],
    ['slug' => 'Lichfield',              'postCode' => 'WS13-WS14',  'display' => 'Lichfield',           'prefImage' => 'hero-Lichfield.jpg'],
    ['slug' => 'Cannock',                'postCode' => 'WS11-WS12',  'display' => 'Cannock',             'prefImage' => 'hero-Cannock.jpg'],
];

// === Citește din DB DOAR slug-urile dorite (dacă există) și mergem în ordinea $wanted
$slugs = array_column($wanted, 'slug');
$placeholders = implode(',', array_fill(0, count($slugs), '?'));
$map = []; // slug => row din DB

if ($stmt = $conn->prepare("SELECT name, slug, postCode, image FROM areas WHERE slug IN ($placeholders)")) {
    $types = str_repeat('s', count($slugs));
    $stmt->bind_param($types, ...$slugs);
    if ($stmt->execute() && ($res = $stmt->get_result())) {
        while ($r = $res->fetch_assoc()) {
            $map[$r['slug']] = $r;
        }
        $res->close();
    }
    $stmt->close();
}

?>
<section class="locationsBox  bg-light">
    <div class="text-center serviceAreasTitle">
        <h2>Extensive Locksmith Coverage Across Birmingham</h2>
        <p>
            Explore more areas we serve throughout Birmingham. From the bustling
            <span class="highlight">City Center</span>
            to neighbourhoods like
            <span class="highlight">Northfield</span>
            and
            <span class="highlight">Jewellery Quarter</span>,
            our team is ready to assist you wherever you are. Browse the locations below for reliable locksmith services
            <strong>near you</strong>.
        </p>
    </div>

    <div class="container">
        <div class="row">
            <?php foreach ($wanted as $w):
                $slug      = $w['slug'];
                $prefImage = $w['prefImage'] ?? '';
                // DB row dacă există
                $db   = $map[$slug] ?? [];
                $name = trim($db['name'] ?? $w['display']);
                $pc   = (string)($db['postCode'] ?? $w['postCode']);
                $url  = $makeAreaUrl($slug, $pc);
                $img  = $resolveImage($db['image'] ?? '', $slug, $prefImage);
                $alt  = $name ? "Locksmith $name" : "Locksmith Area";
            ?>
                <div class="col-md-3 col-6 mt-3">
                    <a href="<?= htmlspecialchars($url, ENT_QUOTES) ?>" class="location-item">
                        <div class="image-container">
                            <img
                                src="<?= htmlspecialchars($img, ENT_QUOTES) ?>"
                                alt="<?= htmlspecialchars($alt, ENT_QUOTES) ?>"
                                class="img-fluid"
                                loading="lazy"
                                decoding="async" />
                            <h3><?= htmlspecialchars($name, ENT_QUOTES) ?></h3>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>