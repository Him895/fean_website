<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
  $id = $_POST['order_id'];
  $stmt = $conn->prepare("UPDATE orders SET status = 'prepared' WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
}

header("Location: admin.php");
