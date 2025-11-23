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
    <link href="https://mrspeedy.co.uk/css/main1.css" rel="stylesheet" />

</head>

<style>
    .product-section li {
        margin-top: 15px;
    }
</style>

<body id="page-top">

    <?php include '../../construct/menu.php' ?>
    <!-- All products display Section -->

    <!-- Product Categories Grid 
 ["name" => "Post Box Lock", "slug" => "post-box-lock"],
        ["name" => "GERDA Lock", "slug" => "gerda-lock"],
        ["name" => "Door Chains & Door Viewers", "slug" => "door-chains-viewers"],
        ["name" => "Banham Locks", "slug" => "banham-locks"],
        ["name" => "Key Safes", "slug" => "key-safes"],
        ["name" => "Garage Locks", "slug" => "garage-locks"],
        ["name" => "Gate Locks", "slug" => "gate-locks"],
        ["name" => "Ring Video Doorbell", "slug" => "ring-video-doorbell"] 
-->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Explore Our Locking Bolts</h2>
            <div class="row g-4">

                <?php
                $categories = [
                    ["name" => "ERA-Press-Bolt", "slug" => "ERA-Press-Bolt"],
                    ["name" => "Ingersoll-Patio-Door-Lock", "slug" => "Ingersoll-Patio-Door-Lock"],
                    ["name" => "Yale-8K116-Locking-Bolt", "slug" => "Yale-8K116-Locking-Bolt"],
                    ["name" => "Yale-PM444-Security-Bolt", "slug" => "Yale-PM444-Security-Bolt"],
                ];

                foreach ($categories as $cat):
                    $img = "https://mrspeedy.co.uk/assets/img/locks/" . $cat['slug'] . ".jpg";
                    $link = "https://mrspeedy.co.uk/locksmith-locks/window-locks/" . $cat['slug'] . ".php";
                ?>

                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="<?php echo $link; ?>" class="text-decoration-none text-dark">
                            <div class="card h-100 shadow-sm border-0">
                                <img src="<?php echo $img; ?>" class="card-img-top" alt="<?php echo $cat['name']; ?>" style="object-fit: cover; height: 180px;">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo $cat['name']; ?></h5>
                                </div>
                            </div>
                        </a>
                    </div>

                <?php endforeach; ?>

            </div>
        </div>
    </section>
     <?php include '../../construct/discount.php'; ?>
    <section class="locationsBox"> <!-- Location Boxes -->
        <div class="text-center serviceAreasTitle">
            <h2>Extensive Locksmith Coverage Across Birmingham</h2>
            <p>Explore more areas we serve throughout Birmingham. From the bustling <span class="highlight">City Center</span> to neighborhoods like <span class="highlight">Northfield</span> and <span class="highlight">Jewellery Quarter</span>, our team is ready to assist you wherever you are. Browse through the list of locations below for reliable locksmith services <strong>near you</strong>.</p>
        </div>
        <div class="container">

            <div class="row">
                <!-- Location Item 1 -->
                <div class="col-md-3 col-6 mt-3">
                    <a href="https://mrspeedy.co.uk/local-locksmith/Four-Oaks-B74.php" class="location-item">
                        <div class="image-container">
                            <img src="https://mrspeedy.co.uk/assets/img/areas/fourOaks.jpg" alt="Birmingham Four Oaks - B74" class="img-fluid">
                            <h3>Four Oaks</h3>
                        </div>
                    </a>
                </div>
                <!-- Location Item 2 -->
                <div class="col-md-3 col-6 mt-3">
                    <a href="https://mrspeedy.co.uk/local-locksmith/Coventry-CV1-CV-7.php" class="location-item">
                        <div class="image-container">
                            <img src="https://mrspeedy.co.uk/assets/img/areas/hero-coventry.jpg" alt="Coventry" class="img-fluid">
                            <h3>Coventry</h3>
                        </div>
                    </a>
                </div>
                <!-- Location Item 3 -->
                <div class="col-md-3 col-6 mt-3">
                    <a href="https://mrspeedy.co.uk/local-locksmith/Hamstead-B43.php" class="location-item">
                        <div class="image-container">
                            <img src="https://mrspeedy.co.uk/assets/img/areas/hero-hamstead.jpg" alt="Birmingham Hamstead Area" class="img-fluid">
                            <h3>Hamstead</h3>
                        </div>
                    </a>
                </div>
                <!-- Location Item 4 -->
                <div class="col-md-3 col-6 mt-3">
                    <a href="https://mrspeedy.co.uk/local-locksmith/Solihull-B91.php" class="location-item">
                        <div class="image-container">
                            <img src="https://mrspeedy.co.uk/assets/img/areas/hero-solihull.jpg" alt="Birmingham Solihull Area" class="img-fluid">
                            <h3>Solihull</h3>
                        </div>
                    </a>
                </div>

                <!-- Location Item 1 -->
                <div class="col-md-3 col-6 mt-3" id="Solihull">
                    <a href="https://mrspeedy.co.uk/local-locksmith/Knowle-B93.php" class="location-item">
                        <div class="image-container">
                            <img src="https://mrspeedy.co.uk/assets/img/areas/hero-Knowle.jpg" alt="Birmingham Knowle Area" class="img-fluid">
                            <h3>Knowle</h3>
                        </div>
                    </a>
                </div>
                <!-- Location Item 2 -->
                <div class="col-md-3 col-6 mt-3">
                    <a href="https://mrspeedy.co.uk/local-locksmith/Dorridge-B93.php" class="location-item">
                        <div class="image-container">
                            <img src="https://mrspeedy.co.uk/assets/img/areas/hero-Dorridge.jpg" alt="Birmingham Dorridge Area" class="img-fluid">
                            <h3>Dorridge</h3>
                        </div>
                    </a>
                </div>
                <!-- Location Item 3 -->
                <div class="col-md-3 col-6 mt-3">
                    <a href="https://mrspeedy.co.uk/local-locksmith/Shirley-B90.php" class="location-item">
                        <div class="image-container">
                            <img src="https://mrspeedy.co.uk/assets/img/areas/hero-Shirley1.jpg" alt="Birmingham Shirley Area" class="img-fluid">
                            <h3>Shirley</h3>
                        </div>
                    </a>
                </div>
                <!-- Location Item 4 -->
                <div class="col-md-3 col-6 mt-3">
                    <a href="https://mrspeedy.co.uk/local-locksmith/Aldridge-WS9.php" class="location-item">
                        <div class="image-container">
                            <img src="https://mrspeedy.co.uk/assets/img/areas/Aldridge.jpg" alt="Birmingham Aldridge Area" class="img-fluid">
                            <h3>Walsall Aldridge</h3>
                        </div>
                    </a>
                </div>

                <!-- Location Item 1 -->
                <div class="col-md-3 col-6 mt-3" id="Dudley">
                    <a href="https://mrspeedy.co.uk/local-locksmith/Dudley-DY1-DY3.php" class="location-item">
                        <div class="image-container">
                            <img src="https://mrspeedy.co.uk/assets/img/areas/hero-Dudley.jpg" alt="Birmingham Dudley " class="img-fluid">
                            <h3>Dudley </h3>
                        </div>
                    </a>
                </div>
                <!-- Location Item 2 -->
                <div class="col-md-3 col-6 mt-3">
                    <a href="https://mrspeedy.co.uk/local-locksmith/Brierley-Hill-DY5.php" class="location-item">
                        <div class="image-container">
                            <img src="https://mrspeedy.co.uk/assets/img/areas/hero-Brierley.jpg" alt="Brierley Hill" class="img-fluid">
                            <h3>Brierley Hill</h3>
                        </div>
                    </a>
                </div>
                <!-- Location Item 3 -->
                <div class="col-md-3 col-6 mt-3">
                    <a href="https://mrspeedy.co.uk/local-locksmith/Stourbridge-DY8.php" class="location-item">
                        <div class="image-container">
                            <img src="https://mrspeedy.co.uk/assets/img/areas/hero-Stourbridge.jpg" alt="Birmingham Stourbridge Area" class="img-fluid">
                            <h3>Stourbridge</h3>
                        </div>
                    </a>
                </div>

                <!-- Location Item 1 -->
                <div class="col-md-3 col-6 mt-3" id="Warwickshire">
                    <a href="https://mrspeedy.co.uk/local-locksmith/Stratford-Upon-Avon-CV37.php" class="location-item">
                        <div class="image-container">
                            <img src="https://mrspeedy.co.uk/assets/img/areas/stratford.jpg" alt="Birmingham Stratford-Upon-Avon" class="img-fluid">
                            <h3>Stratford-Upon-Avon</h3>
                        </div>
                    </a>
                </div>
                <!-- Location Item 2 -->
                <div class="col-md-3 col-6 mt-3">
                    <a href="https://mrspeedy.co.uk/local-locksmith/Alcester-B49.php" class="location-item">
                        <div class="image-container">
                            <img src="https://mrspeedy.co.uk/assets/img/areas/Alcester_B49-locksmith.jpg" alt="Birmingham Alcester" class="img-fluid">
                            <h3>Alcester</h3>
                        </div>
                    </a>
                </div>
                <!-- Location Item 3 -->
                <div class="col-md-3 col-6 mt-3">
                    <a href="https://mrspeedy.co.uk/local-locksmith/Kenilworth-CV8.php" class="location-item">
                        <div class="image-container">
                            <img src="https://mrspeedy.co.uk/assets/img/areas/hero-Kenilworth.jpg" alt="Birmingham Kenilworth Area" class="img-fluid">
                            <h3>Kenilworth</h3>
                        </div>
                    </a>
                </div>

                <!-- Location Item 1 -->
                <div class="col-md-3 col-6 mt-3" id="Staffordshire">
                    <a href="https://mrspeedy.co.uk/local-locksmith/Lichfield-WS13-WS14.php" class="location-item">
                        <div class="image-container">
                            <img src="https://mrspeedy.co.uk/assets/img/areas/hero-Lichfield.jpg" alt="Birmingham Lichfield" class="img-fluid">
                            <h3>Lichfield</h3>
                        </div>
                    </a>
                </div>
                <!-- Location Item 2 -->
                <div class="col-md-3 col-6 mt-3">
                    <a href="https://mrspeedy.co.uk/local-locksmith/Cannock-WS11-WS12.php" class="location-item">
                        <div class="image-container">
                            <img src="https://mrspeedy.co.uk/assets/img/areas/hero-Cannock.jpg" alt="Birmingham Cannock Area" class="img-fluid">
                            <h3>Cannock</h3>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>   
<br>
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