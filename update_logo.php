

<?php

include 'connection.php';

if (isset($_FILES['logo'])) {
    $filename = $_FILES['logo']['name'];
    $tempname = $_FILES['logo']['tmp_name'];
    $folder = "upload/" . $filename;

    if (move_uploaded_file($tempname, $folder)) {
        // Always update or insert for ID = 1
        $check = $conn->query("SELECT * FROM site_settings WHERE id = 1");

        if ($check->num_rows > 0) {
            $conn->query("UPDATE site_settings SET logo = '$filename' WHERE id = 1");
        } else {
            $conn->query("INSERT INTO site_settings (id, logo) VALUES (1, '$filename')");
        }

        echo "Logo uploaded successfully!";
    } else {
        echo "Failed to upload logo.";
    }
}
?>