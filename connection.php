<?php
//connecting to the database//
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student2";

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
     "Connected successfully";
}
?>