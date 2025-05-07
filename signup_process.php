<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $user_name = $_POST['user_name'];
  $password = $_POST['password'];

  // Check if email already exists
  $check = $conn->prepare("SELECT id FROM user WHERE email = ?");
  $check->bind_param("s", $email);
  $check->execute();
  $check->store_result();

  if ($check->num_rows > 0) {
    $_SESSION['signup_error'] = "Email already registered.";
    header("Location: signup.php");
    exit;
  }

  // Insert new user
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $conn->prepare("INSERT INTO user (email,user_name, password) VALUES (?,?,?)");
  $stmt->bind_param("sss", $email,$user_name,$hashed_password);

  if ($stmt->execute()) {
    $_SESSION['signup_success'] = "Signup successful. Please login.";
    header("Location: signup.php");
  } else {
    $_SESSION['signup_error'] = "Something went wrong. Try again.";
    header("Location: signup.php");
  }
}
?>
