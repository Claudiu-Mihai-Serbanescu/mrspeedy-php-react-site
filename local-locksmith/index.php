<?php
require dirname(__DIR__) . '/vite.php';
?>
<?php include '../conectare.php'; ?>
<!DOCTYPE html>
<html lang="en-GB">

<?php include 'construct/head.php'; ?>


<body id="page-top">
    <?php include '../src/construct/menu.php'; ?>

    <main id="main">
        <!-- ======= Breadcrumbs ======= -->
        <section id="breadcrumbs" class="breadcrumbs d-flex justify-content-center align-items-center"
            style="background-image: url(https://i.giphy.com/26ufhUSkulR8ZGO40.webp);">
            <div class="container text-center">
                <h1>Locksmith Service Areas in Birmingham</h1>
                <h2>Explore our coverage across Birmingham's neighborhoods.<br>
                    Search for your area below to find fast and reliable services near you.</h2>
            </div>
        </section>

        <div class="container-fluid d-flex align-items-center red-line">
            <div class="container d-flex justify-content-between align-items-center ">
                <a>Local</a>
                <a>Locksmith</a>
                <a>Near you</a>
            </div>
        </div>
        <!-- ======= End Breadcrumbs ======= -->

        <section class="locationsBox mt-4">
            <!-- ======= Main Boxes Location ======= -->
            <div class="container">
                <div class="text-center serviceAreasTitle" id="Birmingham">
                    <h2>Top Locksmith Service Areas in <span class="highlight">Birmingham</span></h2>
                    <p>Discover our <strong>most requested</strong> service areas in Birmingham, covering the <span
                            class="highlight">City Center</span>, <span class="highlight">Jewellery Quarter</span>,
                        <span class="highlight">Wolverhampton</span> and more. Find fast and reliable locksmith services
                        in these popular locations and get the assistance you need <strong>right where you are</strong>.
                    </p>
                </div>
                <div class="row mt-4">
                    <!-- Location Item 1 -->
                    <div class="col-md-4 mt-3">
                        <a href="/mrspeedy/local-locksmith/City-Centre-B1-B2-B3-B4.php"
                            class="location-item">
                            <div class="image-container">
                                <img src="https://mrspeedy.co.uk/assets/img/areas/cityCenter.jpg"
                                    alt="Birmingham City Center" class="img-fluid">
                                <h3>City Center</h3>
                            </div>
                        </a>
                    </div>
                    <!-- Location Item 2 -->
                    <div class="col-md-4 mt-3">
                        <a href="/mrspeedy/local-locksmith/Jewellery-Quarter-B1-B3-B18.php"
                            class="location-item">
                            <div class="image-container">
                                <img src="https://mrspeedy.co.uk/assets/img/areas/jewellery.jpg" alt="Jewellery Quarter"
                                    class="img-fluid">
                                <h3>Jewellery Quarter</h3>
                            </div>
                        </a>
                    </div>
                    <!-- Location Item 3 -->
                    <div class="col-md-4 mt-3">
                        <a href="/mrspeedy/local-locksmith/Wolverhampton-WV1-WV2.php"
                            class="location-item">
                            <div class="image-container">
                                <img src="https://mrspeedy.co.uk/assets/img/areas/hero-Wolverhampton.jpg"
                                    alt="Birmingham Wolverhampton Area" class="img-fluid">
                                <h3>Wolverhampton</h3>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <?php include '../src/construct/areas.php'; ?>
        <?php include '../src/construct/reviews.php'; ?>

        <?php include 'construct/discount.php'; ?>


        <?php
        include 'construct/faq.php';
        include 'construct/testimonials.php';
        include '../src/construct/contact.php';
        ?>

        <?php include '../src/construct/footer.php'; ?>

</body>

</html>