<?php
include 'database.php';

$errors = [];   // 用来存多条错误信息
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ic              = trim($_POST['ic']);
    $name            = trim($_POST['name']);
    $phone           = trim($_POST['phone']);
    $email           = trim($_POST['email']);
    $password        = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // =====================
    // 1️⃣ 空值检查
    // =====================
    if (empty($ic))      $errors[] = "IC Number is required.";
    if (empty($name))    $errors[] = "Name is required.";
    if (empty($phone))   $errors[] = "Phone number is required.";
    if (empty($email))   $errors[] = "Email is required.";
    if (empty($password)) $errors[] = "Password is required.";
    if (empty($confirmPassword)) $errors[] = "Confirm password is required.";

    // =====================
    // 2️⃣ Email 格式
    // =====================
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // =====================
    // 3️⃣ 密码规则（逐条）
    // =====================
    if (!empty($password)) {

        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter.";
        }
        if (!preg_match('/[\W_]/', $password)) {
            $errors[] = "Password must contain at least one symbol.";
        }
    }

    // =====================
    // 4️⃣ 确认密码
    // =====================
    if (!empty($password) && !empty($confirmPassword) && $password !== $confirmPassword) {
        $errors[] = "Password and Confirm Password do not match.";
    }

    // =====================
    // 5️⃣ 检查重复（只有前面没错才查）
    // =====================
    if (empty($errors)) {

        $checkStmt = $conn->prepare(
            "SELECT Cus_IC, Cus_Phone, Cus_Email 
             FROM customer 
             WHERE Cus_IC = ? OR Cus_Phone = ? OR Cus_Email = ?"
        );
        $checkStmt->bind_param("sss", $ic, $phone, $email);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($row = $result->fetch_assoc()) {

            if ($row['Cus_IC'] === $ic) {
                $errors[] = "IC Number already registered.";
            }
            if ($row['Cus_Phone'] === $phone) {
                $errors[] = "Phone Number already registered.";
            }
            if ($row['Cus_Email'] === $email) {
                $errors[] = "Email already registered.";
            }
        }

        $checkStmt->close();
    }

    // =====================
    // 6️⃣ 插入数据库
    // =====================
    if (empty($errors)) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO customer
            (Cus_Name, Cus_Password, Cus_IC, Cus_Phone, Cus_Email)
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param("sssss", $name, $hashedPassword, $ic, $phone, $email);

        if ($stmt->execute()) {
            $success = "Registration successful!";

             $ic = $name = $phone = $email = '';

            

        } else {
            $errors[] = "Registration failed. Please try again.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="userRegStyle.css">

</head>
<body>

<form method="POST">
    <h1 class= "title">Customer Register</h1>
  

    <!-- 错误逐条显示 -->
    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- 成功提示 -->
    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
<div id = "inputBox">
    <input type="text" name="ic" placeholder="IC Number" value="<?= htmlspecialchars($ic ?? '') ?>" required>
    <input type="text" name="name" placeholder="Full Name" value="<?= htmlspecialchars($name ?? '') ?>" required>
    <input type="text" name="phone" placeholder="Phone Number" value="<?= htmlspecialchars($phone ?? '') ?>" required>
    <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email ?? '') ?>" required>

    <div class="password-wrapper">
    <input type="password" name="password" id="password" placeholder="Password" required>
    <span class="toggle-password" onclick="togglePassword('password', this)">👁</span>
</div>

<div class="password-wrapper">
    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
    <span class="toggle-password" onclick="togglePassword('confirm_password', this)">👁</span>
</div>

</div>

    <button type="submit">Register</button>
    <p style="text-align: center;">Already have account? <a href="userLog.php">Login here</a></p>
    <p style="text-align: center;"><a href="homepage.php">Go to home</a></p>
</form>

<script>
function togglePassword(inputId, icon) {
    const input = document.getElementById(inputId);

    if (input.type === "password") {
        input.type = "text";
        icon.textContent = "🙈";
    } else {
        input.type = "password";
        icon.textContent = "👁";
    }
}
</script>


</body>
</html>
