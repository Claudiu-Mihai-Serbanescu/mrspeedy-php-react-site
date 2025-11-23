<?php
// src/construct/areas-names.php

// === DB connect din rădăcină ===
$root = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/');
if (strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false) {
    // proiectul e în /mrspeedy local
    $root .= '/mrspeedy';
}
require_once $root . '/conectare.php';

// Prefix pentru link-uri (localhost vs producție)
$BASE = (strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false) ? '/mrspeedy' : '';

// Helper pentru URL de arie (aceeași logică ca în areas-table.php)
$makeAreaUrl = function (string $slug, ?string $pc) use ($BASE): string {
    $slug = trim($slug);
    $pc   = strtoupper(preg_replace('~\s+~', '', (string)$pc));
    if ($slug === '') return '#';
    return $BASE . '/local-locksmith/' . rawurlencode($slug . ($pc ? "-$pc" : '')) . '.php';
};

// Citește name + slug + postCode
$rows = [];
$sql  = "SELECT name, slug, postCode FROM areas ORDER BY name ASC";
if ($res = $conn->query($sql)) {
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    $res->close();
}
?>
<section class="section" style="background-color:#06344c">
    <div class="container">
        <div class="text-center" style="color:white">
            <h2>Find Your Local Locksmith Service Area</h2>
            <p>
                Use the search bar below to <strong>quickly locate</strong> locksmith services.
                Our comprehensive list covers all areas we service, ensuring you get the help
                you need in the <strong>right place</strong>, right on time.
            </p>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="locations-list text-center" style="color:white;font-size:1.2rem">
                    <?php
                    if (!$rows) {
                        echo '<em style="opacity:.8">No areas yet.</em>';
                    } else {
                        $out = [];
                        foreach ($rows as $r) {
                            $url  = $makeAreaUrl($r['slug'] ?? '', $r['postCode'] ?? '');
                            $name = htmlspecialchars($r['name'] ?? '', ENT_QUOTES);
                            $out[] = '<strong><a class="area-name" style="color:#ffc107; " href="' .
                                htmlspecialchars($url, ENT_QUOTES) .
                                '">' . $name . '</a></strong>';
                        }
                        // separatoare vizuale între linkuri
                        echo implode(' <span class="sep" style="opacity:.7;color:#ffffff">|</span> ', $out);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>