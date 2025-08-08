<?php
include 'connection.php';
date_default_timezone_set('Asia/Kolkata');

// Handle order status update

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['action_column'])) {
    $order_id = $_POST['order_id'];
    $action_column = $_POST['action_column'];

    // Sanitize allowed columns only
    $allowed_columns = ['accepted_at', 'prepared_at', 'on_the_way_at', 'delivered_at'];
    if (in_array($action_column, $allowed_columns)) {
        $now = date('Y-m-d H:i:s');
        $status_map = [
  'accepted_at' => 'accepted',
  'prepared_at' => 'prepared',
  'on_the_way_at' => 'on_the_way',
  'delivered_at' => 'delivered'
];

if (in_array($action_column, $allowed_columns)) {
    $now = date('Y-m-d H:i:s');
    $new_status = $status_map[$action_column];

    $stmt = $conn->prepare("UPDATE orders SET $action_column = ?, order_status = ? WHERE order_id = ?");
    $stmt->bind_param("sss", $now, $new_status, $order_id);
    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Failed to update: " . $conn->error;
    }
    $stmt->close();
}

    }
}




// Fetch all orders
$orders_result = $conn->query("SELECT * FROM orders ORDER BY created_at ASC");
if (!$orders_result) die("Orders fetch failed: " . $conn->error);

// Fetch all products
$products_result = $conn->query("SELECT * FROM products");
if (!$products_result) die("Products fetch failed: " . $conn->error);

// Fetch navbar items
$navbar_items = [];
$navbar_result = $conn->query("SELECT * FROM navigation");
if (!$navbar_result) die("Navigation fetch failed: " . $conn->error);
while ($nav = $navbar_result->fetch_assoc()) {
  $navbar_items[] = $nav;
}

// Fetch site settings
$site_settings = $conn->query("SELECT * FROM site_settings WHERE id = 1");
$site_settings = $site_settings ? $site_settings->fetch_assoc() : null;

function Duration($start, $end) {
  if ($start && $end) {
    $startTime = new DateTime($start);
    $endTime = new DateTime($end);
    $interval = $startTime->diff($endTime);
    $hours = $interval->h + ($interval->days * 24);
    $minutes = $interval->i;
    return "{$hours}h {$minutes}m";
  }
  return "-";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header class="bg-dark py-3">
  <div class="container d-flex justify-content-between align-items-center">
    <a class="navbar-brand text-white" href="index.php"><strong>Hungerz Den..</strong></a>
    <ul class="nav">
      <?php foreach ($navbar_items as $nav): ?>
        <li class="nav-item">
          <a class="nav-link text-white" href="<?= htmlspecialchars($nav['link']) ?>">
            <?= htmlspecialchars($nav['title']) ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</header>

<div class="container my-5">

  <!-- Navbar Management -->
  <div class="card mb-5">
    <div class="card-header bg-primary text-white">Manage Navbar</div>
    <div class="card-body">
      <form action="insert_navbar.php" method="POST" class="row g-2 mb-4">
        <div class="col-md-5"><input type="text" name="title" class="form-control" placeholder="Navbar Title" required></div>
        <div class="col-md-5"><input type="text" name="link" class="form-control" placeholder="Navbar Link" required></div>
        <div class="col-md-2"><button type="submit" name="submit" class="btn btn-success w-100">Add</button></div>
      </form>
      <table class="table table-bordered">
        <thead class="table-light"><tr><th>Title</th><th>Link</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($navbar_items as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['link']) ?></td>
            <td>
              <a href="edit_navbar.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="delete_navbar.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this navbar item?');">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Product Management -->
  <div class="card mb-5">
    <div class="card-header bg-primary text-white">Manage Products</div>
    <div class="card-body">
      <form action="insert_product.php" method="POST" class="row g-2 mb-4">
        <div class="col-md-4"><input type="text" name="name" class="form-control" placeholder="Product Name" required></div>
        <div class="col-md-4"><input type="number" name="price" class="form-control" placeholder="Price" required></div>
        <div class="col-md-4"><input type="text" name="description" class="form-control" placeholder="Description" required></div>
        <div class="col-md-12 text-end"><button type="submit" class="btn btn-success">Add Product</button></div>
      </form>
      <div class="row">
        <?php while ($row = $products_result->fetch_assoc()): ?>
        <div class="col-sm-6 col-lg-4 mb-4">
          <div class="card p-3">
            <h5><?= htmlspecialchars($row['name']) ?></h5>
            <p><?= htmlspecialchars($row['description']) ?></p>
            <div class="d-flex justify-content-between">
              <h6>₹<?= number_format($row['price'], 2) ?></h6>
              <div>
                <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?');">Delete</a>
              </div>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>

  <!-- Logo Upload -->
  <div class="card mb-5">
    <div class="card-header bg-primary text-white">Site Logo</div>
    <div class="card-body">
      <form action="update_logo.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Upload New Logo</label>
          <input type="file" name="logo" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Logo</button>
      </form>
    </div>
  </div>

  <!-- Order Tracking -->
  <div class="card">
    <div class="card-header bg-primary text-white">Order Status Tracking</div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Order ID</th><th>Items</th><th>Qty</th><th>Amount</th><th>Date</th><th>Status</th><th>Durations</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php
$orders_result = $conn->query("
  SELECT 
    o.*, 
    i.item_name, 
    i.item_qty, 
    i.item_price 
  FROM orders o 
  LEFT JOIN order_items i ON o.order_id = i.order_id 
  ORDER BY o.created_at DESC
");
        $orders_grouped = [];
        while ($row = $orders_result->fetch_assoc()) {
          $orders_grouped[$row['order_id']][] = $row;
        }

      foreach ($orders_grouped as $order_id => $items):
    $first = $items[0];
    $total_qty = 0;
    $item_list = "";
    $total_price = 0;

    foreach ($items as $item) {
        $qty = (int)$item['item_qty'];
        $price = (float)$item['item_price'];

        $total_qty += $qty;
        $total_price += $qty * $price;

        $item_list .= htmlspecialchars($item['item_name']) . " (" . $qty . " x ₹" . number_format($price, 2) . "), ";
    }

    $item_list = rtrim($item_list, ', ');
?>
<tr>
  <td><?= $order_id ?></td>
  <td><?= $item_list ?></td>
  <td><?= $total_qty ?></td>
  <td>₹<?= number_format($total_price, 2) ?></td>
  <td><?= $first['created_at'] ?></td>

            <?php
            if (!$first['accepted_at']) echo "Pending";
            elseif (!$first['prepared_at']) echo "Accepted";
            elseif (!$first['on_the_way_at']) echo "Prepared";
            elseif (!$first['delivered_at']) echo "On the Way";
            else echo "Delivered";
            ?>
          </td>
          <td>
            <small>
              <?= "Accept: " . Duration($first['created_at'], $first['accepted_at']) . "<br>" ?>
              <?= "Prepare: " . Duration($first['accepted_at'], $first['prepared_at']) . "<br>" ?>
              <?= "Delivery: " . Duration($first['prepared_at'], $first['on_the_way_at']) . "<br>" ?>
              <?= "To Delivered: " . Duration($first['on_the_way_at'], $first['delivered_at']) ?>
            </small>
          </td>
          <td>
            <?php if (!$first['accepted_at']): ?>
              <form method="POST" class="d-inline">
                <input type="hidden" name="order_id" value="<?= $order_id ?>">
                <input type="hidden" name="action_column" value="accepted_at">
                <button type="submit" class="btn btn-success btn-sm">Accept</button>
              </form>
            <?php elseif (!$first['prepared_at']): ?>
              <form method="POST" class="d-inline">
                <input type="hidden" name="order_id" value="<?= $order_id ?>">
                <input type="hidden" name="action_column" value="prepared_at">
                <button type="submit" class="btn btn-warning btn-sm">Prepared</button>
              </form>
            <?php elseif (!$first['on_the_way_at']): ?>
              <form method="POST" class="d-inline">
                <input type="hidden" name="order_id" value="<?= $order_id ?>">
                <input type="hidden" name="action_column" value="on_the_way_at">
                <button type="submit" class="btn btn-info btn-sm">On the Way</button>
              </form>
            <?php elseif (!$first['delivered_at']): ?>
              <form method="POST" class="d-inline">
                <input type="hidden" name="order_id" value="<?= $order_id ?>">
                <input type="hidden" name="action_column" value="delivered_at">
                <button type="submit" class="btn btn-primary btn-sm">Delivered</button>
              </form>
            <?php else: ?>
              <span class="badge bg-success">Completed</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

</body>
</html>
