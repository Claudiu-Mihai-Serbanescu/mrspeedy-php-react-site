<?php
include '../conectare.php';

// Fetch area details
$id_area = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 142;
$sql = "SELECT * FROM areas WHERE id = $id_area";
$result = $conn->query($sql);

// Detalii despre zona curentă
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
                <?php include 'construct/breadcrumbs.php'; ?>
                <?php include 'construct/whyUsVideo.php'; ?>
                <?php include 'construct/clients.php'; ?>
                <?php include 'construct/discount.php'; ?>
                <?php include 'construct/serviceArea.php'; ?>
                <?php include 'construct/blogSlider.php'; ?>
                <?php include 'construct/faq.php'; ?>
                <?php include 'construct/testimonials.php'; ?>
                <?php include 'construct/callUs.php'; ?>
                <?php include 'construct/contact.php'; ?>
            </main><!-- End #main -->
        <?php
    }
}
?>
</body>
</html>
