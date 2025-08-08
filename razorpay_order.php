<?php
require('vendor/autoload.php');
use Razorpay\Api\Api;

session_start();
include 'connection.php';
include 'razorpay_config.php';

$api = new Api($keyId, $keySecret);

$amount = $_POST['amount'] ?? 0;
$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';

// Store these temporarily in session
$_SESSION['checkout_user'] = [
    'name' => $name,
    'phone' => $phone,
    'address' => $address,
];

$orderData = [
    'receipt' => 'rcptid_' . time(),
    'amount' => $amount,
    'currency' => 'INR',
    'payment_capture' => 1
];

$razorpayOrder = $api->order->create($orderData);
echo json_encode($razorpayOrder);
?>