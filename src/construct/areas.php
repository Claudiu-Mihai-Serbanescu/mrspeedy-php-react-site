<?php
// src/construct/areas-table.php
$root = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/');
if (strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false) $root .= '/mrspeedy';
require_once $root . '/conectare.php';

$BASE = (strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false) ? '/mrspeedy' : '';

$makeAreaUrl = function (string $slug, ?string $pc) use ($BASE): string {
    $slug = trim($slug);
    $pc   = strtoupper(preg_replace('~\s+~', '', (string)$pc));
    if ($slug === '') return '#';
    return $BASE . '/local-locksmith/' . rawurlencode($slug . ($pc ? "-$pc" : '')) . '.php';
};

$rows = [];
$sql  = "SELECT name, slug, postCode FROM areas ORDER BY name ASC";
if ($res = $conn->query($sql)) {
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    $res->close();
}
?>
<section class="page-section-table bg-light" id="areas">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="text-left">
                            <h2 class="section-heading">Are You Looking For Locksmiths Near You?</h2>
                            <h3 class="section-subheading">Find our Locksmith service in your Birmingham district:</h3>
                        </div>

                        <input type="text" id="searchInput" class="form-control"
                            placeholder="Search location name or post code..." />

                        <div class="scrollable-table mt-3">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>PostCode</th>
                                        <th>Call</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <?php if (!$rows): ?>
                                    <tr>
                                        <td colspan="3"><em>No areas found.</em></td>
                                    </tr>
                                    <?php else: foreach ($rows as $r):
                                            $url = $makeAreaUrl($r['slug'], $r['postCode']);
                                        ?>
                                    <tr>
                                        <td><a
                                                href="<?= htmlspecialchars($url, ENT_QUOTES) ?>"><?= htmlspecialchars($r['name'], ENT_QUOTES) ?></a>
                                        </td>
                                        <td><?= htmlspecialchars(strtoupper(preg_replace('~\s+~', '', (string)$r['postCode'])), ENT_QUOTES) ?>
                                        </td>
                                        <td><a href="tel:+447846716954" class="btn btn-warning btn-sm">Call Now</a></td>
                                    </tr>
                                    <?php endforeach;
                                    endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mt-2">
                <!-- harta rămâne neschimbată -->
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d155455.61262017404!2d-1.8636314999999999!3d52.497349150000005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487080d43225d7fd%3A0x8dc86fdb3abbf3e7!2sWest%20Midlands!5e0!3m2!1sen!2suk!4v1716464495329!5m2!1sen!2suk"
                    width="100%" height="550" style="border:0" allowfullscreen loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
    <br />
</section>