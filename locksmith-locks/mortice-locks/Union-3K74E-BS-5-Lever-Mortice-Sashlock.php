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

  <?php include '../../constructmenu.php' ?>
  <!-- Product Section -->
  <div class="product-section" style="margin-top: 100px; margin-bottom: 100px;">
    <div class="container">
      <!-- Product Title -->
      <h1 class="text-center">Union 3K74E - BS 5 Lever BS3621:2007 Mortice Sashlock</h1>

      <!-- Two Columns Layout -->
      <div class="row align-items-center">
        <!-- Product Details -->
        <div class="col-md-7 order-2 order-md-1">
          <ul class="list-unstyled">
            <li><strong>Sizes:</strong>
              <ul>
                <li>40mm Backset</li>
                <li>53mm Backset</li>
              </ul>
            </li>

            <li><strong>Finishes:</strong>
              <ul>
                <li>Polished Brass</li>
                <li>Satin Chrome</li>
              </ul>
            </li>

            <li><strong>Features:</strong>
              <ul>
                <li>Handles not included</li>
                <li>Approved by insurance companies and the Police</li>
                <li>For timber doors hinged on the left or the right</li>
                <li>Keys are suitable for use on doors up to 50mm thick</li>
                <li>Deadbolt locked or unlocked by key from either side</li>
                <li>Extended 20mm deadbolt for secure positive deadlocking and resistance against attack</li>
                <li>Latch bolt withdrawn by lever handle from either side</li>
                <li>Force resistant, anti-drill, anti-pick and anti-saw security features</li>
                <li>Available with square forend and striking plate</li>
                <li>20mm bolt throw</li>
                <li>Can be supplied on patented RKS key section to prevent illicit copying of keys</li>
                <li>Available with square forend and locking plate</li>
                <li>Case Size: 67mm or 80mm</li>
                <li>Centres: 57mm</li>
                <li>Case: Silver powder coat</li>
                <li>Deadbolt: Brass, with hardened steel rollers</li>
                <li>Rebate Kit: B-3K74R (13mm and 25mm sizes)</li>
                <li>Keying Groups: Standard differ or to pass. Not suitable for master keying</li>
              </ul>
            </li>

            <li><strong>Keys Supplied:</strong> 3 (extra keys £6 each)</li>

            <li><strong>Standards:</strong>
              <ul>
                <li>Tested to BS 3621:2007</li>
                <li>EN 1634-1 assessed for use on 60 minutes timber fire doors</li>
              </ul>
            </li>
          </ul>
        </div>

        <!-- Product Image -->
        <div class="col-md-5 order-1 order-md-2 text-center mb-4 mb-md-0">
          <!-- Imaginea principală -->
          <img id="mainImage" src="https://mrspeedy.co.uk/assets/img/locks/Union-3K74E-BS-5-Lever-Mortice-Sashlock.jpg" class="img-fluid mb-3" alt="Union 3K74E Mortice Sashlock" style="max-height: 350px; object-fit: contain;">

          <!-- Galerie imagini mici -->
          <div class="d-flex justify-content-center gap-2 flex-wrap">
            <img src="https://mrspeedy.co.uk/assets/img/locks/Union-3K74E-BS-5-Lever-Mortice-Sashlock.jpg" class="img-thumbnail gallery-thumb" alt="thumb 1" style="width: 70px; cursor: pointer;">
            <img src="https://mrspeedy.co.uk/assets/img/locks/Union-3K74E-BS-5-Lever-Mortice-Sashlock1.jpg" class="img-thumbnail gallery-thumb" alt="thumb 2" style="width: 70px; cursor: pointer;">
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