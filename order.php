<?php
require('vendor/autoload.php');
use Razorpay\Api\Api;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $item = [
        'name' => $_POST['item_name'],
        'price' => $_POST['item_price'],
        'qty' => $_POST['item_qty']
    ];

    $_SESSION['cart'][] = $item;

    header("Location: order.php");
    exit;
}
if (isset($_GET['remove'])) {
    $index = $_GET['remove'];
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex
    header("Location: order.php");
    exit;
}

include 'connection.php';
include 'razorpay_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$api = new Api($keyId, $keySecret);

// If order placed via COD
if (isset($_POST['place_order'])) {
    $name = $_POST['customer_name'];
    $phone = $_POST['customer_phone'];
    $address = $_POST['customer_address'];
    
// Agar already nahi hai toh zaroori hai
// echo '<pre>';
// print_r($_SESSION['cart']);
// echo '</pre>';
// exit;


   $custom_order_id = 'ORD' . rand(10000, 99999);

// Insert into orders
$stmt = $conn->prepare("INSERT INTO orders (order_id, user_id, customer_name, customer_phone, customer_address, payment_method) VALUES (?, ?, ?, ?, ?, 'COD')");
$stmt->bind_param("sisss", $custom_order_id, $user_id, $name, $phone, $address);
$stmt->execute();

// Insert items using same custom_order_id
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, item_name, item_price, item_qty) VALUES (?, ?, ?, ?)");
        $itemStmt->bind_param("ssdi", $custom_order_id, $item['name'], $item['price'], $item['qty']);
        $itemStmt->execute();
    }
}

    $_SESSION['cart'] = [];
    header("Location: order_status.php?order_id=" . $custom_order_id);
    exit;
}

// Razorpay pre-check
$grand_total = 0;
foreach ($_SESSION['cart'] as $item) {
    $grand_total += $item['price'] * $item['qty'];
}

$razorpayOrderId = '';
if ($grand_total > 0) {
    $razorpayOrder = $api->order->create([
        'receipt' => 'rcptid_' . time(),
        'amount' => $grand_total * 100,
        'currency' => 'INR',
        'payment_capture' => 1
    ]);
    $razorpayOrderId = $razorpayOrder['id'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
         <a href="menu.php" class="btn btn-secondary">⬅️ Back to menu</a>

    <h2>Your Cart</h2>

    <?php if (!empty($_SESSION['cart'])): ?>
        <table class="table table-bordered">
            <thead>
                <tr><th>Item</th><th>Qty</th><th>Price</th><th>Subtotal</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php foreach ($_SESSION['cart'] as $index => $item): 
                $subtotal = $item['price'] * $item['qty']; ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= $item['qty'] ?></td>
                    <td>₹<?= $item['price'] ?></td>
                    <td>₹<?= $subtotal ?></td>
                    <td><a href="?remove=<?= $index ?>" class="btn btn-danger btn-sm">Remove</a></td>
                </tr>
            <?php endforeach; ?>
                <tr><td colspan="3" class="text-end"><strong>Total</strong></td><td>₹<?= $grand_total ?></td><td></td></tr>
            </tbody>
        </table>

        <form id="checkoutForm" method="POST">
            <div class="mb-3"><label>Name</label><input type="text" name="customer_name" class="form-control" required></div>
            <div class="mb-3"><label>Phone</label><input type="text" name="customer_phone" class="form-control" required></div>
            <div class="mb-3"><label>Address</label><textarea name="customer_address" class="form-control" required></textarea></div>

            <button type="button" id="payOnlineBtn" class="btn btn-success w-100 mt-2">Pay Online</button>
            <button type="submit" name="place_order" class="btn btn-warning w-100 mt-2">Place Order (COD)</button>
        </form>
    <?php else: ?>
        <div class="alert alert-info">Your cart is empty.</div>
    <?php endif; ?>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('payOnlineBtn')?.addEventListener('click', function () {
    const form = document.getElementById('checkoutForm');
    const name = form.customer_name.value.trim();
    const phone = form.customer_phone.value.trim();
    const address = form.customer_address.value.trim();

    if (!name || !phone || !address) {
        alert("Please fill all fields");
        return;
    }

    const options = {
        "key": "<?= $keyId ?>",
        "amount": <?= $grand_total * 100 ?>,
        "currency": "INR",
        "name": name,
        "description": "Online Order",
        "order_id": "<?= $razorpayOrderId ?>",
        "handler": function (response) {
            const paymentForm = document.createElement("form");
            paymentForm.method = "POST";
            paymentForm.action = "payment_process.php";

            paymentForm.innerHTML += `<input type="hidden" name="razorpay_payment_id" value="${response.razorpay_payment_id}">`;
            paymentForm.innerHTML += `<input type="hidden" name="razorpay_order_id" value="${response.razorpay_order_id}">`;
            paymentForm.innerHTML += `<input type="hidden" name="razorpay_signature" value="${response.razorpay_signature}">`;
            paymentForm.innerHTML += `<input type="hidden" name="customer_name" value="${name}">`;
            paymentForm.innerHTML += `<input type="hidden" name="customer_phone" value="${phone}">`;
            paymentForm.innerHTML += `<input type="hidden" name="customer_address" value="${address}">`;

            document.body.appendChild(paymentForm);
            paymentForm.submit();
        },
        "prefill": {
            "name": name,
            "contact": phone
        }
    };

    const rzp = new Razorpay(options);
    rzp.open();
});
</script>
</body>
</html>
