<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db = "student2";

$conn = new mysqli($host, $user, $pass, $db);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY id Asc");
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>📋 My Order History</h2>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered mt-3">
      <thead>
        <tr>
          <th>Item</th>
          <th>Qty</th>
          <th>Price</th>
          <th>Total</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['item_name']) ?></td>
            <td><?= $row['item_qty'] ?></td>
            <td>₹<?= $row['item_price'] ?></td>
            <td>₹<?= $row['item_price'] * $row['item_qty'] ?></td>
            <td><?= $row['order_date'] ?? 'N/A' ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">No orders found.</div>
  <?php endif; ?>
</div>
</body>
</html>
