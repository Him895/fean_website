<?php
include 'connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];

    $sql = "INSERT INTO products (name, description, price) VALUES ('$name', '$desc', '$price')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Product added successfully'); window.location.href='admin.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
