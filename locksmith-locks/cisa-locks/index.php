<?php
include '../../conectare.php';
// Interogarea bazei de date pentru a obține zonele
$sql = "SELECT * FROM areas ORDER BY name ASC";
$result = $conn->query($sql);

$zones = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $zones[] = $row;
    }
} else {
    echo "Eroare la interogarea bazei de date: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>MrSpeedy Locksmith | 24/7 emergency services with rapid response | Dial +44 07846 716954</title>
    <meta name="description" content="MrSpeedy Locksmith Birmingham offers 24/7 emergency services with rapid response. Local experts for all your needs. Call us anytime! +44 07846 716954" />
    <meta name="keywords" content="Locksmith Birmingham, Emergency Locksmith Birmingham, Local Locksmith, Locksmith Near Me, 24/7 Locksmith, Fast Locksmith Birmingham, Professional Locksmith Services">

    <link rel="canonical" href="https://mrspeedy.co.uk/" />
    <meta property="og:locale" content="en_GB" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Home" />
    <meta property="og:description" content="Fast Locksmith Birmingham offers 24/7 emergency services with rapid response. Local experts for all your needs. Call us anytime! +44 7549624024" />
    <meta property="og:url" content="https://mrspeedy.co.uk/" />
    <meta property="og:site_name" content="Dial A Locksmith" />
    <meta property="article:modified_time" content="2024-04-09T09:16:10+00:00" />
    <meta property="og:image" content="https://mrspeedy.co.uk/assets/img/favicon.png" />
    <meta property="og:image:width" content="300" />
    <meta property="og:image:height" content="300" />
    <meta property="og:image:type" content="image/png" />

    <!-- Additional meta tags for Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@YourTwitterHandle">
    <meta name="twitter:title" content="Mr MrSpeedy Locksmith Birmingham | Expert Local Locksmith">
    <meta name="twitter:description" content="Fast Locksmith Birmingham offers 24/7 emergency services with rapid response. Local experts for all your needs. Call us anytime! +44 7549624024">
    <meta name="twitter:image" content="https://mrspeedy.co.uk/assets/img/favicon.png">

    <!-- Favicons -->
    <link href="https://mrspeedy.co.uk/assets/img/favicon.png" rel="icon">
    <link href="https://mrspeedy.co.uk/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css">
    <link href="https://mrspeedy.co.uk/css/styles.css" rel="stylesheet" />
    <link href="https://mrspeedy.co.uk/css/styles2.css" rel="stylesheet" />
</head>

<style>
    .product-section li {
        margin-top: 15px;
    }
</style>

<body id="page-top">

    <?php include '../../construct/menu.php' ?>
    <!-- Product Section -->
    <div class="product-section" style="margin-top: 100px; margin-bottom: 100px;">
        <div class="container">
            <!-- Product Title -->
            <h1 class="text-center">CISA Lock 11610 Communal Door Lock</h1>

            <!-- Two Columns Layout -->
            <div class="row align-items-center">
                <!-- Product Details -->
                <div class="col-md-7 order-2 order-md-1">
                    <p>The <strong>CISA 11610 Communal Door Lock</strong> is a high-security electric lock specifically designed for entrance doors in communal residential buildings, flats, and shared access premises. It combines mechanical strength with electronic integration for enhanced access control and resident safety.</p>

                    <p>Compatible with most <strong>audio and video intercom systems</strong>, this lock allows remote release via intercom while ensuring automatic deadlocking upon door closure. It can be installed on both <strong>inward or outward opening doors</strong>, making it highly versatile across different building setups.</p>

                    <p>The internal lock mechanism features a durable <strong>grey/black steel finish</strong>, while the external high-security cylinder is available in a selection of polished or satin finishes. Each unit includes a <strong>10-pin high-security cylinder</strong> with 2 keys supplied as standard.</p>

                    <ul class="list-unstyled">
                        <li><strong>Size:</strong> 60mm Backset</li>
                        <li><strong>Finishes:</strong>
                            <ul>
                                <li><strong>Internal Lock:</strong> Grey/Black</li>
                                <li><strong>External Cylinder Options:</strong>
                                    <ul>
                                        <li>Polished Brass</li>
                                        <li>Polished Chrome</li>
                                        <li>Satin Chrome</li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li><strong>Features:</strong>
                            <ul>
                                <li>Compatible with most intercom systems – includes wiring integration</li>
                                <li>Electric strike: 12V AC, 1.6 Amps</li>
                                <li>Suitable for inward or outward opening doors</li>
                                <li>Automatic deadlocking when door is closed</li>
                                <li>10-pin CISA high-security external cylinder</li>
                                <li>Supplied with 2 high-security keys</li>
                                <li>Additional keys available at installation: £16 per pair</li>
                                <li>Optional CISA London Bar upgrade: £149 fitted</li>
                            </ul>
                        </li>

                        <li><strong>Best Use:</strong> Communal residential entrances, apartment blocks, and managed properties</li>
                        <li><strong>Service Area:</strong> Available in Birmingham and surrounding areas</li>
                    </ul>
                </div>

                <!-- Product Image -->
                <div class="col-md-5 order-1 order-md-2 text-center mb-4 mb-md-0">
                    <img id="mainImage" src="https://mrspeedy.co.uk/assets/img/locks/CISA-11610-Communal-Lock.jpg" class="img-fluid mb-3" alt="CISA 11610 Communal Door Lock" style="max-height: 350px; object-fit: contain;">

                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        <img src="https://mrspeedy.co.uk/assets/img/locks/CISA-11610-Communal-Lock.jpg" class="img-thumbnail gallery-thumb" alt="thumb 1" style="width: 70px; cursor: pointer;">
                        <img src="https://mrspeedy.co.uk/assets/img/locks/CISA-11610-Communal-Lock1.jpg" class="img-thumbnail gallery-thumb" alt="thumb 2" style="width: 70px; cursor: pointer;">
                    </div>
                </div>
            </div>
        </div>

    </div>


    <?php
    include '../../construct/contact.php';
    include '../../footer.php'; ?>
    <script>
        const thumbs = document.querySelectorAll('.gallery-thumb');
        const mainImage = document.getElementById('mainImage');

        thumbs.forEach(thumb => {
            thumb.addEventListener('click', () => {
                mainImage.src = thumb.src;
            });
        });
    </script>
</body>

</html>