<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>User Signup</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url('https://media.istockphoto.com/id/1137312508/photo/assortment-of-products-with-high-sugar-level.jpg?s=1024x1024&w=is&k=20&c=31JIGszsx0mvGauCTxUED2epIbocfo4HIj3v9m_fjSQ=') no-repeat center center/cover;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .glass-card {
      width: 100%;
      max-width: 450px;
      padding: 30px;
      border-radius: 1rem;
      background: rgba(255, 255, 255, 0.1);
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.18);
      color: #fff;
    }

    .form-title {
      font-size: 24px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
      color: #fff;
    }

    .form-control {
      border-radius: 0.5rem;
      background-color: rgba(255, 255, 255, 0.8);
    }

    .btn-success {
      width: 100%;
      border-radius: 0.5rem;
    }

    .btn-link {
      display: block;
      text-align: center;
      margin-top: 10px;
      text-decoration: none;
      color: #f1f1f1;
    }

    label {
      font-weight: 500;
    }

    .alert {
      font-size: 14px;
      padding: 8px;
    }
    
  </style>
</head>
<body>
  <div class="glass-card">
    <div class="form-title">Create an Account</div>

    <?php if (isset($_SESSION['signup_error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['signup_error']; unset($_SESSION['signup_error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['signup_success'])): ?>
      <div class="alert alert-success"><?= $_SESSION['signup_success']; unset($_SESSION['signup_success']); ?></div>
    <?php endif; ?>

    <form action="signup_process.php" method="POST">
      <div class="mb-3">
        <label class="form-label">Username:</label>
        <input type="text" name="user_name" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Email:</label>
        <input type="email" name="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Password:</label>
        <input type="password" name="password" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-success">Sign Up</button>
      <a href="login.php" class="btn btn-link">Already have an account? Login</a>
    </form>
  </div>
</body>
</html>
