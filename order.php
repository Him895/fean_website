<?php
session_start();

// Add item to session/cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $item = [
        'name' => $_POST['item_name'],
        'price' => $_POST['item_price'],
        'qty' => $_POST['item_qty']
    ];

    $_SESSION['cart'][] = $item;
}

// Show order summary
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
                </tr>
            </thead>
            <tbody>
                <?php
                $grand_total = 0;
                foreach ($_SESSION['cart'] as $item):
                    $subtotal = $item['price'] * $item['qty'];
                    $grand_total += $subtotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= $item['qty'] ?></td>
                        <td><?= $item['price'] ?></td>
                        <td><?= $subtotal ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <th colspan="3" class="text-end">Total:</th>
                    <th>₹<?= $grand_total ?></th>
                </tr>
            </tbody>
        </table>
        <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
    <?php else: ?>
        <div class="alert alert-warning">No items in order.</div>
    <?php endif; ?>
</div>
</body>
</html>
