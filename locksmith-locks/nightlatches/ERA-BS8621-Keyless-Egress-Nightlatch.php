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

  <?php include '../../menu.php' ?>
  <!-- Product Section -->
  <div class="product-section" style="margin-top: 100px; margin-bottom: 100px;">
    <div class="container">
      <!-- Product Title -->
      <h1 class="text-center">ERA BS8621 Keyless Egress Nightlatch</h1>

      <!-- Two Columns Layout -->
      <div class="row align-items-center">
        <!-- Product Details -->
        <div class="col-md-7 order-2 order-md-1">
          <p>The <strong>ERA BS8621 Keyless Egress Nightlatch</strong> is a high-security solution specifically designed for entrance doors in multi-residential buildings, flats, and communal properties. Certified to <strong>BS 8621:2007</strong>, this nightlatch offers the same protection as BS3621 locks, but with <strong>keyless egress</strong> functionality in the event of an emergency, such as fire.</p>

          <p>Featuring <strong>automatic deadlocking</strong>, the bolt cannot be slipped or bypassed using credit cards or similar tools. From the inside, the lock includes a <strong>keyless lever</strong> to allow quick escape without needing a key—critical in shared accommodations or rented properties subject to fire safety regulations.</p>

          <p>This lock is a secure upgrade for most standard nightlatches and is recommended by insurance companies and police authorities under the Secured by Design initiative.</p>

          <ul class="list-unstyled">
            <li><strong>Sizes:</strong>
              <ul>
                <li>60mm Backset</li>
                <li>40mm Backset</li>
              </ul>
            </li>

            <li><strong>Finishes Available:</strong>
              <ul>
                <li>Polished Brass</li>
                <li>Polished Chrome</li>
                <li>Satin Chrome</li>
                <li>Black</li>
              </ul>
            </li>

            <li><strong>Features:</strong>
              <ul>
                <li>Automatic deadlocking prevents bolt retraction by card or tool</li>
                <li>Keyless lever inside – safe and fast fire escape</li>
                <li>Integral cylinder pull resists drilling and manipulation</li>
                <li>3 high-security keys supplied (extra keys £5 each)</li>
                <li>Direct replacement for most front door locks</li>
              </ul>
            </li>

            <li><strong>Standards & Endorsements:</strong>
              <ul>
                <li>Conforms to <strong>BS 8621:2007</strong> – secure and fire escape compliant</li>
                <li><strong>Police approved – Secured by Design</strong></li>
                <li><strong>Insurance approved</strong></li>
              </ul>
            </li>

            <li><strong>Security Level:</strong> Maximum security</li>
            <li><strong>Best Use:</strong> Flats, HMOs, shared properties, communal doors</li>
            <li><strong>Installation Area:</strong> Available in Birmingham and surrounding areas</li>
          </ul>
        </div>

        <!-- Product Image -->
        <div class="col-md-5 order-1 order-md-2 text-center mb-4 mb-md-0">
          <img id="mainImage" src="https://mrspeedy.co.uk/assets/img/locks/ERA-BS8621-Keyless-Egress-Nightlatch.jpg" class="img-fluid mb-3" alt="ERA BS8621 Keyless Nightlatch" style="max-height: 350px; object-fit: contain;">

          <div class="d-flex justify-content-center gap-2 flex-wrap">
            <img src="https://mrspeedy.co.uk/assets/img/locks/ERA-BS8621-Keyless-Egress-Nightlatch.jpg" class="img-thumbnail gallery-thumb" alt="thumb 1" style="width: 70px; cursor: pointer;">
            <img src="https://mrspeedy.co.uk/assets/img/locks/ERA-BS8621-Keyless-Egress-Nightlatch1.jpg" class="img-thumbnail gallery-thumb" alt="thumb 2" style="width: 70px; cursor: pointer;">
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