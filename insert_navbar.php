<?php
include 'connection.php';

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $link = $_POST['link'];

    $stmt = $conn->prepare("INSERT INTO navigation (title, link) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $link);
    $stmt->execute();
    $stmt->close();

    header("Location: admin.php"); // or wherever your dashboard is
    exit();
}
?>
