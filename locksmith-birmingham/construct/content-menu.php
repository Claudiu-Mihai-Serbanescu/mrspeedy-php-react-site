<?php
// locksmith-birmingham/construct/content-menu.php
if (!isset($conn)) require_once __DIR__ . '/../../conectare.php';

$slug = basename($_SERVER['PHP_SELF'], '.php');

$toc = null;
$sql = "SELECT ss.data
        FROM service_sections ss
        JOIN services s ON s.id = ss.service_id
        WHERE s.slug = ? AND ss.type = 'toc' AND ss.is_active = 1
        ORDER BY ss.position ASC
        LIMIT 1";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('s', $slug);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) $toc = json_decode($row['data'], true);
    $stmt->close();
}

if (!$toc || empty($toc['items']) || !is_array($toc['items'])) return;

function render_toc_items(array $items): string
{
    if (!$items) return '';
    $html = "<ul>\n";
    foreach ($items as $it) {
        $text = htmlspecialchars($it['text'] ?? '', ENT_QUOTES);
        $href = htmlspecialchars($it['href'] ?? '#', ENT_QUOTES);
        if ($href && !preg_match('~^(#|/|https?://)~i', $href)) $href = '#';
        $html .= '<li><a href="' . $href . '">' . $text . '</a>';
        if (!empty($it['children']) && is_array($it['children'])) {
            $html .= render_toc_items($it['children']);
        }
        $html .= "</li>\n";
    }
    $html .= "</ul>\n";
    return $html;
}

// ID-uri unice pentru acest TOC (evită conflicte dacă ai mai multe)
$uid = 'toc_' . bin2hex(random_bytes(4));
$btnId = $uid . '_btn';
$cntId = $uid . '_content';
?>
<div class="toc-section container">
    <p id="<?= $uid; ?>_toggle" style="cursor:pointer;font-weight:bold;">
        Table of Contents
        <button id="<?= $btnId; ?>" class="btn btn-secondary" style="border:1px solid;" aria-expanded="false" type="button">
            Show
        </button>
    </p>

    <!-- ASCUNS by default -->
    <div id="<?= $cntId; ?>" style="display:none;font-weight:bold;">
        <?= render_toc_items($toc['items']); ?>
    </div>
</div>

<script>
    (function() {
        const btn = document.getElementById('<?= $btnId; ?>');
        const box = document.getElementById('<?= $cntId; ?>');
        if (!btn || !box) return;

        const setState = (open) => {
            box.style.display = open ? 'block' : 'none';
            btn.textContent = open ? 'Hide' : 'Show';
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        };

        // Start: ascuns
        setState(false);

        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const isOpen = box.style.display !== 'none';
            setState(!isOpen);
        });
    })();
</script>