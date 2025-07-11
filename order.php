<?php
session_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect guest to login page
    exit;
}

$user_id = $_SESSION['user_id'];

// Initialize cart session for logged-in user
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart
if (isset($_POST['add_to_cart'])) {
    $item_name = $_POST['item_name'];
    $item_price = floatval($_POST['item_price']);
    $item_qty = intval($_POST['item_qty']);

    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] === $item_name) {
            $item['qty'] += $item_qty;
            $found = true;
            break;
        }
    }
    unset($item);

    if (!$found) {
        $_SESSION['cart'][] = [
            'name' => $item_name,
            'price' => $item_price,
            'qty' => $item_qty,
        ];
    }

    header("Location: order.php");
    exit;
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $removeIndex = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$removeIndex])) {
        unset($_SESSION['cart'][$removeIndex]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">🧾 Order Summary</h2>

    <?php if (!empty($_SESSION['cart'])): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Qty</th>
                    <th>Price (₹)</th>
                    <th>Subtotal (₹)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grand_total = 0;
                foreach ($_SESSION['cart'] as $index => $item):
                    $subtotal = $item['price'] * $item['qty'];
                    $grand_total += $subtotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= $item['qty'] ?></td>
                        <td>₹<?= number_format($item['price'], 2) ?></td>
                        <td>₹<?= number_format($subtotal, 2) ?></td>
                        <td>
                            <a href="?remove=<?= $index ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remove this item?')">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <th colspan="3" class="text-end">Total:</th>
                    <th>₹<?= number_format($grand_total, 2) ?></th>
                    <th></th>
                </tr>
            </tbody>
        </table>

        <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
    <?php else: ?>
        <div class="alert alert-info">Your cart is empty.</div>
    <?php endif; ?>

    <a href="index.php" class="btn btn-secondary mt-3">🏠 Back to Home</a>
</div>
</body>
</html>
