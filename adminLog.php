<?php
session_start();

$error = "";

$adminUsername = "admin";
$adminPassword = "admin123";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $inputUsername = trim($_POST["username"] ?? "");
    $inputPassword = trim($_POST["password"] ?? "");

    if ($inputUsername === "" || $inputPassword === "") {
        $error = "Please enter Admin Username and Password.";
    } else {
        if ($inputUsername === $adminUsername && $inputPassword === $adminPassword) {
            $_SESSION["admin"] = true;
            header("Location: adminDashBoard.php"); // <-- 改成 adminDashBoard.php
            exit;
        } else {
            $error = "Invalid Admin Username or Password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="userRegStyle.css">
</head>
<body>

<form method="POST">
    <div class="login-card">

        <h1 class="title">Admin Login</h1>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <input type="text" name="username" placeholder="Admin Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>

        <p style="text-align:center;">
            <a href="homepage.php">Back to Home</a>
        </p>

    </div>
</form>

</body>
</html>