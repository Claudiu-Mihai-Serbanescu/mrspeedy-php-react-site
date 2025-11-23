<?php
include '../conectare.php';

// Fetch area details
$id_area = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 69;
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
                include 'construct/serviceArea.php';
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