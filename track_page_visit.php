<?php
include 'conectare.php';

// Verificăm dacă datele au fost trimise corect
if (isset($_POST['page_name']) && isset($_POST['device_info']) && isset($_POST['visit_timestamp'])) {
    $page_name = $conn->real_escape_string($_POST['page_name']);
    $device_info = $conn->real_escape_string($_POST['device_info']);
    $visit_timestamp = $conn->real_escape_string($_POST['visit_timestamp']);

    // Verificăm dacă există deja o înregistrare pentru această pagină și dispozitiv la acest timestamp
    $sql_check = "SELECT * FROM page_statistics WHERE page_name = '$page_name' AND device_info = '$device_info' AND click_timestamp = '$visit_timestamp'";
    $result_check = $conn->query($sql_check);
    
    if ($result_check->num_rows == 0) {
        // Dacă nu există, inserăm o nouă înregistrare
        $sql = "INSERT INTO page_statistics (page_name, device_info, visit_count, click_timestamp) 
                VALUES ('$page_name', '$device_info', 1, '$visit_timestamp')";
        if ($conn->query($sql) === TRUE) {
            echo "Page visit saved successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        // Dacă există deja, actualizăm vizitele pentru această pagină și dispozitiv
        $sql_update = "UPDATE page_statistics 
                       SET visit_count = visit_count + 1, click_timestamp = '$visit_timestamp' 
                       WHERE page_name = '$page_name' AND device_info = '$device_info' AND click_timestamp = '$visit_timestamp'";
        if ($conn->query($sql_update) === TRUE) {
            echo "Page visit updated successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
    }
} else {
    echo "Invalid data.";
}
?>
