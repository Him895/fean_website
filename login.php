<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url('https://images.unsplash.com/photo-1606787366850-de6330128bfc?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center center/cover;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      backdrop-filter: blur(5px);
    }

    .card {
      width: 100%;
      max-width: 400px;
      padding: 30px;
      border-radius: 1rem;
      background: rgba(255, 255, 255, 0.15);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.18);
      color: #fff;
    }

    .form-title {
      font-size: 24px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
      color: black;
      font-family: monospace;
    }

    .form-control {
      border-radius: 0.5rem;
      background-color: rgba(255, 255, 255, 0.2);
      color: white;
      border: none;

    }

    .form-control::placeholder {
      color: #eee;
    }

    .btn-primary {
      width: 100%;
      border-radius: 0.5rem;
    }

    .btn-link {
      display: block;
      text-align: center;
      margin-top: 10px;
      color: #fff;
    }

    .alert {
      background-color: rgba(255, 0, 0, 0.2);
      border: none;
      color: white;
    }
    .form-label{
        color: black;
    }
    .btn-link {
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="form-title">Welcome Back</div>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="login_process.php" method="POST">
      <div class="mb-3">
        <label class="form-label">Email:</label>
        <input type="email" name="email" class="form-control" placeholder="Enter email" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Username:</label>
        <input type="text" name="user_name" class="form-control" placeholder="Enter username" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Password:</label>
        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
      </div>

      <button type="submit" class="btn btn-primary">Login</button>
      <a href="signup.php" class="btn btn-link">Don't have an account? Sign up</a>
    </form>
  </div>
</body>
</html>
