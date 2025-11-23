<?php
header('Content-Type: application/json; charset=utf-8');

// === CONFIG ===
$apiKey  = 'AIzaSyCzC7fPEbZHkV3g8TrF7AV3xWkwCsCM5Mw';
$placeId = 'ChIJy3ZPTcalcEgRte4zbsAJ6cY';

// === CACHE (opțional, recomandat) ===
$cacheFile = __DIR__ . '/cache/google_reviews.json';
$cacheTTL  = 60 * 60 * 12; // 12 ore

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTTL)) {
    echo file_get_contents($cacheFile);
    exit;
}

// === CALL GOOGLE PLACES API v1 ===
$ch = curl_init("https://places.googleapis.com/v1/places/$placeId");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'X-Goog-Api-Key: ' . $apiKey,
        'X-Goog-FieldMask: rating,userRatingCount,reviews'
    ]
]);
$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($code !== 200 || !$response) {
    http_response_code(500);
    echo json_encode(['error' => 'Google API error', 'http' => $code, 'raw' => $response]);
    exit;
}

$data = json_decode($response, true);

// === FILTRARE: doar 4★ și 5★ ===
$reviews = [];
if (!empty($data['reviews'])) {
    foreach ($data['reviews'] as $r) {
        if (($r['rating'] ?? 0) >= 4) {
            $reviews[] = [
                'author' => $r['authorAttribution']['displayName'] ?? 'Client',
                'photo'  => $r['authorAttribution']['photoUri'] ?? null,
                'rating' => $r['rating'] ?? 0,
                'text'   => $r['text']['text'] ?? '',
                'time'   => $r['publishTime'] ?? '',
                'url'    => $r['authorAttribution']['uri'] ?? ''
            ];
        }
    }
}

// Sortează descendent după dată
usort($reviews, fn($a, $b) => strtotime($b['time']) <=> strtotime($a['time']));

$out = [
    'placeRating' => $data['rating'] ?? null,
    'reviewCount' => $data['userRatingCount'] ?? null,
    'reviews'     => $reviews
];

// SALVEAZĂ CACHE
if (!is_dir(dirname($cacheFile))) {
    mkdir(dirname($cacheFile), 0755, true);
}
file_put_contents($cacheFile, json_encode($out, JSON_UNESCAPED_UNICODE));

echo json_encode($out, JSON_UNESCAPED_UNICODE);