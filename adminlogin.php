<?php
$fixedUsername = "admin";
$fixedPassword = "admin123";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $inputUsername = $_POST["adminUsername"];
  $inputPassword = $_POST["adminPassword"];

  if ($inputUsername === $fixedUsername && $inputPassword === $fixedPassword) {
    header("Location: editproduct.php");
    exit();
  } else {
    $error = "Invalid Admin Username or Password";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="admin login.css">
</head>
<body>

  <div class="login-box">
    <h2>Admin Login</h2>
    <p class="subtitle">Dental Appointment System</p>

    <form method="post">

      <div class="input-group">
        <label>Admin Username</label>
        <input type="text" name="adminUsername" required>
      </div>

      <div class="input-group">
        <label>Password</label>
        <input type="password" name="adminPassword" required>
      </div>

      <?php if ($error != "") { ?>
        <p class="error"><?php echo $error; ?></p>
      <?php } ?>

      <button type="submit" class="login-btn">LOGIN</button>
    </form>

  </div>

</body>

</html>
