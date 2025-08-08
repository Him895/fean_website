<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <a href="menu.php">Back to menu</a>
    <h2>📦 My Orders</h2>

<?php
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<div class='alert alert-info'>You haven't placed any orders yet.</div>";
} else {
    while ($order = $result->fetch_assoc()) {
        $order_id = $order['order_id'];
        $payment_method = $order['payment_method'];
        $created_at = $order['created_at'];
        $order_status = $order['order_status'];
        $created_at_iso = date("c", strtotime($created_at)); // ISO format

        // Normalize order status to lowercase for consistent comparison
        $normalized_status = strtolower($order_status);

        echo "<div class='card mb-4 shadow'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>Order ID: $order_id</h5>";
        echo "<p><strong>Payment Method:</strong> " . ($payment_method === 'COD' ? 'Cash on Delivery (COD)' : 'Online Payment') . "</p>";
        echo "<p><strong>Status:</strong> $order_status</p>";

        // Show timer only if order is not delivered or cancelled
        if ($normalized_status !== 'delivered' && $normalized_status !== 'cancelled') {
            echo "<p><strong>Estimated Delivery:</strong> <span id='timer_$order_id'>⏳ Loading...</span></p>";
        }

        echo "<p><strong>Name:</strong> {$order['customer_name']}<br><strong>Phone:</strong> {$order['customer_phone']}<br><strong>Address:</strong> {$order['customer_address']}</p>";
        echo "<table class='table table-bordered'><tr><th>Item</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>";

        $itemStmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $itemStmt->bind_param("s", $order_id);
        $itemStmt->execute();
        $items = $itemStmt->get_result();

        $total = 0;
        while ($item = $items->fetch_assoc()) {
            $price = (float)$item['item_price'];
            $qty = (int)$item['item_qty'];
            $subtotal = $price * $qty;
            $total += $subtotal;

            echo "<tr>
                <td>{$item['item_name']}</td>
                <td>{$qty}</td>
                <td>₹" . number_format($price, 2) . "</td>
                <td>₹" . number_format($subtotal, 2) . "</td>
            </tr>";
        }

        echo "</table>";
        echo "<p class='fw-bold'>Total: ₹" . number_format($total, 2) . "</p>";
        echo "</div></div>";

        // Timer script only for non-delivered or non-cancelled orders
        if ($normalized_status !== 'delivered' && $normalized_status !== 'cancelled') {
            echo "<script>
            window.addEventListener('load', function() {
                startCountdown('$order_id', '$created_at_iso');
            });
            </script>";
        }
    }
}
?>
</div>

<script>
function startCountdown(orderId, createdAt) {
    const timerElement = document.getElementById('timer_' + orderId);
    const startTime = new Date(createdAt).getTime();
    const endTime = startTime + (20 * 60 * 1000); // 20 minutes

    const interval = setInterval(() => {
        const now = new Date().getTime();
        const diff = endTime - now;

        if (diff <= 0) {
            clearInterval(interval);
            timerElement.innerHTML = "⏱️ Time Over";
            timerElement.style.color = "red";
        } else {
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
            timerElement.innerHTML = `${minutes}m ${seconds}s`;
        }
    }, 1000);
}
</script>

</body>
</html>
