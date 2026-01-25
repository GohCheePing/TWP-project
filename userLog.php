<?php
session_start();
include 'database.php';

$message = ''; // 用于显示错误或成功信息

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ic = trim($_POST['ic'] ?? '');
    $password = $_POST['password'] ?? '';

    // =====================
    // 1️⃣ 空值检查
    // =====================
    if (empty($ic) || empty($password)) {
        $message = "Please enter both IC Number and Password.";
    } else {
        // =====================
        // 2️⃣ 从数据库获取用户
        // =====================
        $stmt = $conn->prepare("SELECT Cus_ID, Cus_Password, Cus_Name FROM customer WHERE Cus_IC = ?");
        $stmt->bind_param("s", $ic);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // =====================
            // 3️⃣ 验证密码
            // =====================
            if (password_verify($password, $row['Cus_Password'])) {
                // 登录成功，保存 session
                $_SESSION['cus_id'] = $row['Cus_ID'];
                $_SESSION['cus_name'] = $row['Cus_Name'];

                // 可选：跳转到首页或用户面板
                header("Location: dashboard.php");
                exit;
            } else {
                $message = "Incorrect IC Number or Password.";
            }
        } else {
            $message = "Incorrect IC Number or Password.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="userRegStyle.css">
</head>
<body>


<form method="POST">
    <h1 class= "title">Customer Login</h1>

    <?php if ($message): ?>
        <div class="error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div id="inputBox">
        <input type="text" name="ic" placeholder="IC Number" value="<?= htmlspecialchars($ic ?? '') ?>" required>

    <div class="password-wrapper">
    <input type="password" name="password" id="password" placeholder="Password" required>
    <span class="toggle-password" onclick="togglePassword()">👁</span>
</div>

    </div>

    <button type="submit">Login</button>
    <p style="text-align: center;">Don't have an account? <a href="userReg.php">Register here</a></p>
    <p style="text-align: center;"><a href="homepage.php">Go to home</a></p>
</form>

<script>
function togglePassword() {
    const pwd = document.getElementById("password");
    const icon = document.querySelector(".toggle-password");

    if (pwd.type === "password") {
        pwd.type = "text";
        icon.textContent = "🙈"; // 显示状态
    } else {
        pwd.type = "password";
        icon.textContent = "👁"; // 隐藏状态
    }
}
</script>


</body>
</html>
