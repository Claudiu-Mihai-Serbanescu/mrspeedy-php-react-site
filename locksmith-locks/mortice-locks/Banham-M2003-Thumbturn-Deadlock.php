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
  <h1 class="text-center">Banham M2002 Cylinder Mortice Deadlock</h1>

  <!-- Two Columns Layout -->
  <div class="row align-items-center">
    <!-- Product Details -->
    <div class="col-md-7 order-2 order-md-1">
      <p>The <strong>Banham M2002</strong> is a premium-grade mortice deadlock, fully certified to <strong>British Standard BS3621</strong> and approved by leading insurance providers. Designed for serious home security, it combines Banham’s advanced engineering with patent-protected key control and robust anti-attack features.</p>

      <p>Fitted with a <strong>hook-style steel bolt</strong> and reinforced 10” striker plate, the M2002 offers superior resistance against door spreading and brute force attacks. The <strong>6-pin cylinder</strong> is both <strong>drill- and snap-resistant</strong>, having passed independent testing to <strong>TS007 Clause 5.2</strong>. This lock is ideal for high-value properties and homeowners who demand the highest level of physical security and key protection.</p>

      <p>It can be keyed alike with other Banham products including the L2000, M2003, EL4000, collapsible gates, and padlocks — offering seamless integration across your property. Supplied with 2 keys and Banham’s patented <strong>key registration system</strong> to prevent unauthorised duplication.</p>

      <ul class="list-unstyled">
        <li><strong>Finishes Available:</strong>
          <ul>
            <li>Polished Chrome</li>
            <li>Satin Chrome</li>
            <li>Polished Brass</li>
            <li>Satin Brass</li>
            <li>Dark Bronze <strong>(+£99 surcharge)</strong></li>
            <li>Satin Black <strong>(+£99 surcharge)</strong></li>
          </ul>
        </li>

        <li><strong>Security Features:</strong>
          <ul>
            <li>Certified to <strong>BS3621</strong> – insurance and police approved</li>
            <li>Hook bolt prevents spreading of the door</li>
            <li>6-pin <strong>drill- and anti-snap-resistant</strong> cylinder</li>
            <li>Successfully tested to <strong>TS007 Clause 5.2</strong></li>
            <li>Saw-resistant steel bolt</li>
            <li>Secure 10” striker plate</li>
            <li>Security escutcheon plates to reinforce the door</li>
          </ul>
        </li>

        <li><strong>Key System:</strong>
          <ul>
            <li>Patented and copyrighted key profile</li>
            <li>Banham key registration prevents unauthorised copies</li>
            <li>Supplied with 2 keys (extra keys £35 each)</li>
          </ul>
        </li>

        <li><strong>Compatibility:</strong>
          <ul>
            <li>Can be keyed alike with Banham M2003, L2000, EL4000, padlocks, and collapsible gates</li>
            <li>Maximum door thickness: 70mm</li>
          </ul>
        </li>

        <li><strong>Security Level:</strong> Maximum security</li>
        <li><strong>Installation Area:</strong> Available in Birmingham and surrounding areas</li>
      </ul>
    </div>

    <!-- Product Image -->
    <div class="col-md-5 order-1 order-md-2 text-center mb-4 mb-md-0">
      <img id="mainImage" src="https://mrspeedy.co.uk/assets/img/locks/Banham-M2002-Mortice-Deadlock.jpg" class="img-fluid mb-3" alt="Banham M2002 Mortice Deadlock" style="max-height: 350px; object-fit: contain;">

      <div class="d-flex justify-content-center gap-2 flex-wrap">
        <img src="https://mrspeedy.co.uk/assets/img/locks/Banham-M2002-Mortice-Deadlock.jpg" class="img-thumbnail gallery-thumb" alt="thumb 1" style="width: 70px; cursor: pointer;">
        <img src="https://mrspeedy.co.uk/assets/img/locks/Banham-M2002-Mortice-Deadlock1.jpg" class="img-thumbnail gallery-thumb" alt="thumb 2" style="width: 70px; cursor: pointer;">
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