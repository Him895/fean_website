<?php
session_start();

// DB connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "student2";

$conn = new mysqli($host, $user, $pass, $db);

// Place order logic
if (isset($_POST['place_order'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    $order_total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $order_total += $item['price'] * $item['qty'];
    }

    // Insert each item with status
    foreach ($_SESSION['cart'] as $item) {
        $status = 'pending';
        $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, customer_phone, customer_address, item_name, item_qty, item_price, order_total, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssidds", $user_id, $name, $phone, $address, $item['name'], $item['qty'], $item['price'], $order_total, $status);

        $stmt->execute();
    }

    // Clear cart
    $_SESSION['cart'] = [];
    header("Location: checkout.php?order_success=1");
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
    <a href="index.php" class="btn btn-secondary mb-3">🏠 Home</a>

    <?php if (isset($_GET['order_success']) && $_GET['order_success'] == 1): ?>
        <div class="alert alert-success">Order placed successfully!</div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['cart'])): ?>
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
                        <span>₹<?= $subtotal ?></span>
                    </li>
                <?php endforeach; ?>
                <li class="list-group-item d-flex justify-content-between fw-bold">
                    Total: <span>₹<?= $total ?></span>
                </li>
            </ul>

            <button type="submit" name="place_order" class="btn btn-primary">Place Order</button>
        </form>
    <?php else: ?>
        <div class="alert alert-warning">Your cart is empty.</div>
    <?php endif; ?>
</div>
</body>
</html>
