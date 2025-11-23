<?php
function vite($entry = 'src/main.js')
{
    $manifestPath = __DIR__ . '/assets/dist/manifest.json';

    // === PROD ===
    if (is_file($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true);
        $e = $manifest[$entry] ?? null;
        if (!$e) return;

        if (!empty($e['css'])) {
            foreach ($e['css'] as $css) {
                echo '<link rel="stylesheet" href="/mrspeedy/assets/dist/' . $css . '">' . PHP_EOL;
            }
        }
        echo '<script type="module" src="/mrspeedy/assets/dist/' . $e['file'] . '"></script>' . PHP_EOL;
        return;
    }

    // === DEV: caută portul ===
    $candidates = [];
    if ($p = getenv('VITE_PORT')) $candidates[] = (int)$p;
    $candidates = array_unique(array_merge($candidates, [5173, 5174, 5175, 3000]));

    $origin = null;
    foreach ($candidates as $port) {
        $fp = @fsockopen('127.0.0.1', $port, $errno, $errstr, 0.1);
        if (!$fp) continue;
        stream_set_timeout($fp, 1);
        fwrite($fp, "GET /@vite/client HTTP/1.0\r\nHost: localhost\r\n\r\n");
        $resp = stream_get_contents($fp);
        fclose($fp);
        if (strpos($resp, 'vite') !== false || strpos($resp, 'import.meta') !== false) {
            $origin = "http://localhost:$port";
            break;
        }
    }
    if (!$origin) {
        echo "<!-- Vite dev server not found -->";
        return;
    }

    echo '<script type="module" src="' . $origin . '/@vite/client"></script>' . PHP_EOL;
    echo '<script type="module" src="' . $origin . '/' . $entry . '"></script>' . PHP_EOL;
}
