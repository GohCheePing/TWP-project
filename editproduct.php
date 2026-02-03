<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: adminLog.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product</title>
</head>
<body>

  <h2>Edit Product Page</h2>
  <p>Welcome Admin. You can manage products here.</p>

</body>

</html>
