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
      <h1 class="text-center">Thumb Turn Mortice Lock – Keyless Egress Deadlock</h1>

      <!-- Two Columns Layout -->
      <div class="row align-items-center">
        <!-- Product Details -->
        <div class="col-md-7 order-2 order-md-1">
          <p>The <strong>Thumb Turn Mortice Lock</strong> – also known as a Keyless Egress Lock, Thumbturn Lock, or Euro Thumbturn Mortice Deadlock – is specifically designed for doors that form part of a shared or communal area, particularly where fire safety regulations apply. Unlike traditional deadlocks that require a key from both sides, this lock provides <strong>keyless escape from the inside</strong>, making it ideal for multi-occupancy buildings and HMO properties.</p>

          <p>Compliant with fire safety standards, this euro-profile deadlock is operated via a <strong>registered high-security key from the outside</strong>, while a <strong>thumbturn mechanism inside</strong> ensures easy and safe exit in an emergency. This design eliminates the risk of trapping visitors, tenants, or emergency personnel behind locked doors in shared entrances or hallways.</p>

          <p>The lock can be <strong>keyed alike to existing systems</strong>, including rim, euro, or oval profile cylinders, making it an excellent choice for retrofitting additional security while maintaining single-key convenience for access control.</p>

          <ul class="list-unstyled">
            <li><strong>Recommended For:</strong>
              <ul>
                <li>Communal doors in flats or apartment blocks</li>
                <li>Houses in multiple occupation (HMOs)</li>
                <li>Shared accommodation or fire-regulated access zones</li>
              </ul>
            </li>

            <li><strong>Key Features:</strong>
              <ul>
                <li>Key operation from the outside</li>
                <li>Thumbturn operation from the inside – keyless egress</li>
                <li>Drill- and pick-resistant high security euro cylinder</li>
                <li>Complies with fire escape regulations</li>
                <li>Can be keyed alike to most euro/rim/oval cylinder systems</li>
                <li>2 keys supplied (extra keys available in pairs – £16 per pair)</li>
              </ul>
            </li>

            <li><strong>Finishes Available:</strong>
              <ul>
                <li>Satin Brass</li>
                <li>Satin Chrome</li>
              </ul>
            </li>

            <li><strong>Optional Add-on:</strong> Rebate kit for rebated doors: <strong>+£39.00</strong></li>
            <li><strong>Installation Area:</strong> Available in Birmingham and surrounding areas</li>
          </ul>

          <p>If you’d like to confirm compatibility with your door, click the orange button labeled <strong>“Will this fit on my Door – Enquire Now”</strong> and send us a photo. Our team will assist you with everything you need.</p>
        </div>

        <!-- Product Image -->
        <div class="col-md-5 order-1 order-md-2 text-center mb-4 mb-md-0">
          <img id="mainImage" src="https://mrspeedy.co.uk/assets/img/locks/Thumb-Turn-Mortice-Lock.jpg" class="img-fluid mb-3" alt="Thumb Turn Mortice Lock" style="max-height: 350px; object-fit: contain;">

          <div class="d-flex justify-content-center gap-2 flex-wrap">
            <img src="https://mrspeedy.co.uk/assets/img/locks/Thumb-Turn-Mortice-Lock.jpg" class="img-thumbnail gallery-thumb" alt="thumb 1" style="width: 70px; cursor: pointer;">
            <img src="https://mrspeedy.co.uk/assets/img/locks/Thumb-Turn-Mortice-Lock1.jpg" class="img-thumbnail gallery-thumb" alt="thumb 2" style="width: 70px; cursor: pointer;">
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