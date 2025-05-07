<?php
include 'connection.php';

// Fetch dynamic data
$products_result = $conn->query("SELECT * FROM products");
$navbar_result = $conn->query("SELECT * FROM navigation");

// Fetch site settings

$result = $conn->query("SELECT * FROM site_settings WHERE id = 1");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  <!-- bootstrap core css -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <!-- nice select  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" />
  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
 

  <style>
    .navbar-nav .nav-link {
  color:rgb(255, 255, 255) !important;
  font-weight: 500;
  padding: 10px 15px;
  text-transform: uppercase;
  transition: all 0.3s ease;
  border-radius: 5px;
}

.navbar-nav .nav-link:hover {
  color: #ffbe33 !important;
  background-color: rgba(255, 255, 255, 0.1);
}

.navbar-nav .nav-item {
  margin-right: 10px;
}

.navbar-brand span {
  font-size: 32px;
  font-weight: bold;
  font-family: 'Dancing Script', cursive;
  color:rgb(250, 250, 250);
}
.header_section{
  background-color: black;
}
.btn-animated {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn-animated:active {
  transform: scale(0.95);
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

  </style>
  
</head>
<div>

<!-- START: Same Navbar as index.php but dynamic -->
<div class="hero_area">
    <div class="bg-box">
    <img src="https://images8.alphacoders.com/106/1060258.jpg" alt="">
    </div>
    <!-- header section strats -->
    <header class="header_section">
      <div class="container">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="index.php">
            <span>
              Hungerz Den..
            </span>
          </a>

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">

            <span class=""> </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto">
              <?php while ($nav = $navbar_result->fetch_assoc()) { ?>
                <li class="nav-item">
                  <a class="nav-link" href="<?php echo $nav['link']; ?>"><?php echo $nav['title']; ?></a>
                </li>
              <?php } ?>
            </ul>
            <div class="user_option">
              <a href="" class="user_link">
                <i class="fa fa-user" aria-hidden="true"></i>
              </a>
              <a class="cart_link" href="#">
                <!-- SVG Cart Icon -->
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                  <g>
                    <g>
                      <path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248
                   c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z" />
                    </g>
                  </g>
                  <g>
                    <g>
                      <path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48
                   C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064
                   c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4
                   C457.728,97.71,450.56,86.958,439.296,84.91z" />
                    </g>
                  </g>
                  <g>
                    <g>
                      <path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296
                   c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z" />
                    </g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                  <g>
                  </g>
                </svg>
              </a>
              <form class="form-inline">
                <button class="btn  my-2 my-sm-0 nav_search-btn" type="submit">
                  <i class="fa fa-search" aria-hidden="true"></i>
                </button>
              </form>
              <a href="menu.php" class="order_online">
                Order Online
              </a>
            </div>
          </div>
        </nav>
      </div>
    </header>
<!-- END: Navbar -->

<!-- Navbar Management -->
<!-- Manage Navbar Section -->
<div class="card mb-5">
    <div class="card-header bg-dark text-white">Manage Navbar</div>
    <div class="card<div">
      <form action="insert_navbar.php" method="POST" class="row g-2 mb-4">
        <div class="col-md-5">
          <input type="text" name="title" class="form-control" placeholder="Navbar Title" required>
        </div>
        <div class="col-md-5">
          <input type="text" name="link" class="form-control" placeholder="Navbar Link (e.g., about.php)" required>
        </div>
        <div class="col-md-2">
          <button type="submit" name="submit" class="btn btn-primary w-100">Add</button>
        </div>
      </form>

      <table class="table table-bordered">
        <thead class="table-dark">
          <tr>
            <th>Title</th>
            <th>Link</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $navbar_result = $conn->query("SELECT * FROM navigation");

          if (!$navbar_result) {
              die("Error in query: " . $conn->error);
          }
          
           while ($row = $navbar_result->fetch_assoc()) { ?>
            <tr>
              <td><?= $row['title'] ?></td>
              <td><?= $row['link'] ?></td>
              <td>
              <a href="#" data-href="edit_navbar.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm btn-animated edit-btn">Edit</a>
<a href="#" data-href="delete_navbar.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm btn-animated delete-btn">Del</a>

                  </div>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Manage Product Section -->
  <div class="card">
    <div class="card-header bg-dark text-white">Manage Products</div>
    <div class="card<div">
      <form action="insert_product.php" method="POST" class="row g-2 mb-4">
        <div class="col-md-4">
          <input type="text" name="name" class="form-control" placeholder="Product Name" required>
        </div>
        <div class="col-md-4">
          <input type="number" name="price" class="form-control" placeholder="Price" required>
        </div>
        <div class="col-md-4">
          <input type="text" name="description" class="form-control" placeholder="Description" required>
        </div>
        <div class="col-md-12 text-end">
          <button type="submit" name="submit" class="btn btn-success">Add Product</button>
        </div>
      </form>

      <?php
include 'connection.php';
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<section class="food_section layout_padding">
  <div class="container">
    <div class="heading_container heading_center">
    
    </div>

    <div class="filters-content">
      <div class="row grid">
        <?php while ($row = $result->fetch_assoc()) { ?>
          <div class="col-sm-6 col-lg-4 all">
            <div class="box">
              <div>
                <div class="detail-box">
                  <h5><?php echo $row['name']; ?></h5>
                  <p><?php echo $row['description']; ?></p>
                  <div class="options">
                    <h6>₹<?php echo $row['price']; ?></h6>
                    <a href="#" data-href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm btn-animated edit-btn">Edit</a>
<a href="#" data-href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm btn-animated delete-btn">Del</a>

                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>

  <div class="container mt-5">
    <h3>Change Site Logo</h3>
    <form action="update_logo.php" method="POST" enctype="multipart/form-data">
        
        <div class="mb-3">
            <label>Upload New Logo</label>
            <input type="file" name="logo" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Logo</button>
    </form>
<div>

        <!-- No need for <audio> tag if you're using Audio() in JS -->

        <script>
  const sound = new Audio('click.mp3');
  const animatedButtons = document.querySelectorAll('.btn-animated');

  animatedButtons.forEach(button => {
    button.addEventListener('click', (e) => {
      e.preventDefault(); // Stop default link click
      
      const link = button.getAttribute('data-href');
      sound.currentTime = 0;
      sound.play().then(() => {
        // Wait a little before redirect
        setTimeout(() => {
          window.location.href = link;
        }, 300); // adjust delay as needed
      }).catch(err => {
        console.error("Sound error:", err);
        window.location.href = link; // fallback if sound fails
      });
    });
  });

  function updateTimer(){
    const timers = document.querySelectorAll('.timer');
    timers.forEach(timer=>{
      const ordertime = parseInt(timer.getAttribute('data-time'))* 1000;
      const now = new Date().getTime();
      const diff = math.floor((now - ordertime)/1000);

      const minutes = Math.floor(diff/60);
      const seconds = diff % 60;

      timer.innerHTML = `${minutes}m ${seconds}s ago`;
    });
  }
  setInterval(updateTimer, 1000); // Update every second
  updateTimer(); // Initial call to set the timer immediately
</script>


        <!-- Bootstrap JS & dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



<?php 
include 'connection.php';

$result = $conn->query("SELECT * FROM orders where status = 'pending' ORDER  BY created_at ASC");
?>

<div class="container mt-5">

<h2>Pending orders</h2>
<table class="table table-border">

<thead class="table-dark">
<t>
  <th>OrderId</th>
  <th>Item</th>
  <th>QTY</th>
  <th>price</th>
  <th>Total</th>
  <th>Order Date</th>
  <th>Action</th>

</tr>
</thead>
<tbody>
  <?php while ($row = $result ->fetch_assoc()): ?>
<tr>
  <td><?=$row['id']?></td>
  <td><?=$row['item_name']?></td>
  <td><?=$row['item_qty']?></td>
  <td><?=$row['item_price']?></td>
  <td><?=$row['order_total']?></td>
  <td><?=$row['created_at']?></td>
  <td><span class="timer" data-time="<?=strtotime($row['created_at'])?>"></span></td>
  <td>
    <form action="mark_prepare.php" method="post" >
      <input type="hidden" name="order_id" value="<?=$row['id']?>">
      <button type="submit" class="btn btn-success">Mark as Prepared</button>
    </form>
  </td>
</tr>
<?php endwhile; ?>
</tbody>

</table>
</div>


<<div>
</html>



  