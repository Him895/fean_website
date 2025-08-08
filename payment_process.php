<?php
session_start();
require('vendor/autoload.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

include 'connection.php';
include 'razorpay_config.php';

$api = new Api($keyId, $keySecret);

// ✅ Store customer details in session (from hidden fields)
$_SESSION['checkout_user'] = [
    'name' => $_POST['customer_name'] ?? '',
    'phone' => $_POST['customer_phone'] ?? '',
    'address' => $_POST['customer_address'] ?? ''
];

// ✅ Then proceed as before
$razorpay_payment_id = $_POST['razorpay_payment_id'];
$razorpay_order_id = $_POST['razorpay_order_id'];
$razorpay_signature = $_POST['razorpay_signature'];
$payment_method = $_POST['payment_method'];
$total_amount = $_POST['total_amount'];

$name = $_SESSION['checkout_user']['name'] ?? '';
$phone = $_SESSION['checkout_user']['phone'] ?? '';
$address = $_SESSION['checkout_user']['address'] ?? '';

if (!$name || !$phone || !$address || !$razorpay_payment_id) {
    die('Missing data. Payment failed.');
}

// ✅ Verify Razorpay Signature
$success = false;
try {
    $attributes = [
        'razorpay_order_id' => $razorpay_order_id,
        'razorpay_payment_id' => $razorpay_payment_id,
        'razorpay_signature' => $razorpay_signature
    ];

    $api->utility->verifyPaymentSignature($attributes);
    $success = true;
} catch(SignatureVerificationError $e) {
    die('Payment verification failed: ' . $e->getMessage());
}

if ($success) {
    $user_id = $_SESSION['user_id'];
    $order_id = 'ORD' . time();
    $status = 'paid';

    // ✅ Insert main order (just once)
    $stmt = $conn->prepare("INSERT INTO orders (
        user_id, order_id, customer_name, customer_phone, customer_address,
        item_name, item_qty, item_price, order_total, order_status,
        payment_id, payment_method
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // We'll use the first item only for the `orders` table (optional logic)
    $firstItem = $_SESSION['cart'][0];
    $stmt->bind_param(
        "issssssiddss",
        $user_id,
        $order_id,
        $name,
        $phone,
        $address,
        $firstItem['name'],
        $firstItem['qty'],
        $firstItem['price'],
        $total_amount,
        $status,
        $razorpay_payment_id,
        $payment_method
    );
    $stmt->execute();

    // ✅ Now insert each item into `order_items` table
    $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, item_name, item_qty, item_price) VALUES (?, ?, ?, ?)");

    foreach ($_SESSION['cart'] as $item) {
        $itemStmt->bind_param(
            "sssd",
            $order_id,
            $item['name'],
            $item['qty'],
            $item['price']
        );
        $itemStmt->execute();
    }

    // ✅ Clear cart & session
    $_SESSION['cart'] = [];
    unset($_SESSION['checkout_user']);

    header("Location: order_status.php?order_success=1&order_id=$order_id");
    exit;
} else {
    echo "Payment verification failed.";
}
?>
