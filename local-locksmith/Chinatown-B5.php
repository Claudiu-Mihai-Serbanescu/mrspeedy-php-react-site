<?php
include '../conectare.php';

// Fetch area details
$id_area = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 6;
$sql = "SELECT * FROM areas WHERE id = $id_area";
$result = $conn->query($sql);


$sql2 = "SELECT * FROM areas WHERE id = $id_area";
$result2 = $conn->query($sql2);
$area = $result2->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en-GB">

<?php include 'construct/head.php'; ?>
<?php
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $locationName = $row["name"];
        $prefix = $row["prefix"];
        $postCode = $row["postCode"];
        $text1 = $row["text1"];
        $text2 = $row["text2"];
        $text3 = $row["text3"];
        $map = $row["map"];
        $image = $row["image"]; ?>

        <body>
            <?php include '../construct/menu.php'; ?>
            <main id="main" class="scrolled-offset">

                <?php include 'construct/breadcrumbs.php';
                include 'construct/whyUsVideo.php';
                include 'construct/clients.php';
                include 'construct/discount.php';
                include 'construct/serviceArea.php'; ?>
                <section id="areaNews" class="container">
                    <div class=" row">

                        <div class=" text-center">
                            <h2><strong>Birmingham Chinatown: </strong>A New Name and a <br>New Era for the Chinese Community</h2>
                        </div>
                        <hr class="m-4">

                        <div class="col-md-12 mt-3">

                            <h3><strong>A historic moment has marked the life of Birmingham's Chinese community:</strong></h3>
                            <p> The Chinese Quarter, previously known as the Chinese Quarter, has been officially renamed Birmingham Chinatown.</p>

                            <p>The change was celebrated with a moving ceremony, during which a commemorative plaque was unveiled in front of the Southside Building on Hurst Street. The event brought together important figures from the city, including Mayor Chaman Lal and Kin Bong Lam, chairman of the Birmingham Chinatown Business Association (BCBTA).
                            </p>
                            <p><strong> Dorian Chan,</strong> secretary general of the BCTBA, expressed the community's joy at this official recognition: "We feel we have been heard and respected in our desire for our community."</p>

                            <p>The new name, Birmingham Chinatown, is not just a symbolic change. It is expected to bring significant benefits, both culturally and economically.</p>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h3><strong>A Boost for Tourism and Economy</strong></h3>
                                    <p>The renaming of the neighborhood is seen as a catalyst for increased tourism in the area. The name "Chinatown" is internationally recognized and will certainly attract more visitors interested in discovering Chinese culture and traditions.</p>

                                    <p>In addition to attracting tourists, the new name will also contribute to the economic development of the area. The number of businesses is expected to increase, and existing ones will benefit from greater exposure.</p>
                                </div>
                                <div class="col-md-6">
                                    <a href="https://ichef.bbci.co.uk/news/1024/cpsprodpb/a7ae/live/34ebc610-16c5-11ef-89f4-cbe1e4ef5150.jpg.webp" title=" Chinese Quarter, previously known as the Chinese Quarter, has been officially renamed Birmingham Chinatown." class="glightbox preview-link">
                                        <img src="https://ichef.bbci.co.uk/news/1024/cpsprodpb/a7ae/live/34ebc610-16c5-11ef-89f4-cbe1e4ef5150.jpg.webp" class="img-fluid" alt=" Chinese Quarter, previously known as the Chinese Quarter, has been officially renamed Birmingham Chinatown." style="max-width: 600px;">
                                    </a>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h3><strong>A Tribute to Cultural Heritage</strong></h3>
                                    <p>The name change is also a tribute to the rich cultural heritage and history of the Chinese community in Birmingham. It recognizes the community's important contribution to the city's development and strengthens the cultural identity of the neighborhood.</p>

                                    <h3><strong>Ambitious Plans for the Future</strong></h3>
                                    <p>The BCBTA has ambitious plans for the future of Birmingham Chinatown. The association is collaborating with the Southside Business Improvement District to implement projects that will further improve the area's appearance and attractiveness.</p>
                                    <p>Long-term goals include creating streets with Chinese names and building a traditional Chinese arch at the entrance to the neighborhood.</p>
                                </div>
                                <div class="col-md-6">
                                    <h3><strong>A New Chapter for the Birmingham Community</strong></h3>
                                    <p>The renaming of the Chinese Quarter marks the beginning of a new chapter for the Chinese community in Birmingham. It is a moment of pride and optimism, promising a prosperous future full of achievements for this vibrant corner of the city.</p>


                                    <h3><strong>Team Fast Locksmith Birmingham, Proud Partner of the Chinatown Community</strong></h3>
                                    <p>Team Fast Locksmith Birmingham is honored to provide reliable locksmith services to residents and businesses in Chinatown. We are an integral part of this vibrant community and are committed to contributing to the safety and prosperity of the area.</p>

                                    <p>
                                        Take action now to safeguard your home and loved ones.<br>
                                        <strong><a href="https://fastlocksmith-birmingham.com/index.php#contact">Contact us</a> today for a free security assessment and quote.</strong>
                                    </p>
                                </div>
                            </div>

                        </div>

                    </div>
                    <a href="https://fastlocksmith-birmingham.com/blog.php" class="service-link d-flex justify-content-center align-items-center">
                        <div class="callUs">
                            <h3>See our Blog Page for more info</h3>
                        </div>
                    </a>

                </section>
                <?php
                include 'construct/blogSlider.php';
                include 'construct/faq.php';
                include 'construct/testimonials.php';
                include 'construct/callUs.php';
                include 'construct/contact.php'; ?>
        <?php
    }
}
        ?>
            </main><!-- End #main -->
            <?php include '../footer.php'; ?>
        </body>

</html>