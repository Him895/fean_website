<?php
session_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect guest to login page
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if cart exists and is not empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: order.php"); // No cart, go back to order page
    exit;
}

// Place order logic

if (isset($_POST['place_order'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $order_id = 'ORD' .time(); // Generate a unique order ID

    $order_total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $order_total += $item['price'] * $item['qty'];
    }

    $stmt = $conn->prepare("INSERT INTO orders (user_id, order_id, customer_name, customer_phone, customer_address, item_name, item_qty, item_price, order_total, order_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");


    $status = 'pending';
    foreach ($_SESSION['cart'] as $item) {
       $stmt->bind_param("issssssidd", $user_id, $order_id, $name, $phone, $address, $item['name'], $item['qty'], $item['price'], $order_total, $status);

        $stmt->execute();
    }

    // Clear cart after placing order
    $_SESSION['cart'] = [];

   header("Location: order_status.php?order_success=1&order_id=$order_id");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">🧾 Checkout</h2>
    <a href="order.php" class="btn btn-secondary mb-3">← Back to Order Summary</a>

    <?php if (isset($_GET['order_success']) && $_GET['order_success'] == 1): ?>
        <div class="alert alert-success">Order placed successfully!</div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control" required></textarea>
        </div>

        <h5 class="mt-4">Order Summary</h5>
        <ul class="list-group mb-3">
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $item):
                $subtotal = $item['price'] * $item['qty'];
                $total += $subtotal;
            ?>
                <li class="list-group-item d-flex justify-content-between">
                    <?= htmlspecialchars($item['name']) ?> × <?= $item['qty'] ?>
                    <span>₹<?= number_format($subtotal, 2) ?></span>
                </li>
            <?php endforeach; ?>
            <li class="list-group-item d-flex justify-content-between fw-bold">
                Total: <span>₹<?= number_format($total, 2) ?></span>
            </li>
        </ul>

        <button type="submit" name="place_order" class="btn btn-primary">Place Order</button>
    </form>
</div>
</body>
</html>
