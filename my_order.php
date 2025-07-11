<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db = "student2";

$conn = new mysqli($host, $user, $pass, $db);


session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch distinct orders
// Fetch grouped orders per user
$orders = $conn->query("SELECT order_id, SUM(order_total) as order_total, 
                        MAX(order_status) as order_status,
                        MAX(accepted_at) as accepted_at, 
                        MAX(prepared_at) as prepared_at, 
                        MAX(on_the_way_at) as on_the_way_at, 
                        MAX(delivered_at) as delivered_at 
                        FROM orders 
                        WHERE user_id = $user_id 
                        GROUP BY order_id 
                        ORDER BY MAX(id) DESC");


// Duration helper function
function getDuration($start, $end) {
    if ($start && $end) {
        $startTime = new DateTime($start);
        $endTime = new DateTime($end);
        $interval = $startTime->diff($endTime);
        return $interval->format('%Hh %Im');
    }
    return "-";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
    function updateTimer(orderId, startTime, status) {
        const now = new Date().getTime();
        const start = new Date(startTime).getTime();
        let diff = now - start;

        const timer = document.getElementById('timer_' + orderId);
        if (diff > 0 && timer) {
            let minutes = Math.floor(diff / 60000);
            let seconds = Math.floor((diff % 60000) / 1000);
            timer.innerHTML = `${status} time: ${minutes}m ${seconds}s`;
        }
    }
    </script>
</head>
<body>
<div class="container mt-5">
    <h2>📦 Your Order Status</h2>
    <a href="index.php" class="btn btn-secondary">← Back to Home</a>
    <?php if (isset($_GET['order_success'])): ?>
        <div class="alert alert-success">🎉 Order placed successfully! You can track its status below.</div>
    <?php endif; ?>

    <?php while ($order = $orders->fetch_assoc()): ?>
        <div class="card mt-4">
            <div class="card-header">
                <strong>Order ID:</strong> <?= $order['order_id'] ?> - 
                <strong>Status:</strong> <?= ucfirst($order['order_status']) ?>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $orderId = $order['order_id'];
                        $items = $conn->query("SELECT item_name, item_qty, item_price FROM orders WHERE order_id = '$orderId'");
                        while ($item = $items->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($item['item_name']) ?></td>
                            <td><?= $item['item_qty'] ?></td>
                            <td>₹<?= $item['item_price'] ?></td>
                            <td>₹<?= $item['item_qty'] * $item['item_price'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>

                <p><strong>Total Amount:</strong> ₹<?= $order['order_total'] ?></p>
                <p id="timer_<?= $orderId ?>">Loading timer...</p>

                <!-- ⏱ Duration Info -->
                <div class="mt-3">
                    <strong>Durations:</strong>
                    <ul>
                        <li>Accept → Prepare: <?= getDuration($order['accepted_at'], $order['prepared_at']) ?></li>
                        <li>Prepare → On The Way: <?= getDuration($order['prepared_at'], $order['on_the_way_at']) ?></li>
                        <li>On The Way → Delivered: <?= getDuration($order['on_the_way_at'], $order['delivered_at']) ?></li>
                    </ul>
                </div>
            </div>
        </div>

        <script>
        <?php if ($order['order_status'] == 'preparing' && $order['accepted_at']): ?>
            setInterval(() => updateTimer("<?= $orderId ?>", "<?= $order['accepted_at'] ?>", "Preparation"), 1000);
        <?php elseif ($order['order_status'] == 'on_the_way' && $order['on_the_way_at']): ?>
            setInterval(() => updateTimer("<?= $orderId ?>", "<?= $order['on_the_way_at'] ?>", "Delivery"), 1000);
        <?php else: ?>
            document.getElementById("timer_<?= $orderId ?>").innerText = "Waiting for next stage...";
        <?php endif; ?>
        </script>
    <?php endwhile; ?>
</div>
</body>
</html>
