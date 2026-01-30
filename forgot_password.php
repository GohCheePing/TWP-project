<?php
include 'database.php';

$message = "";
$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ic      = trim($_POST['ic'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $newPass = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // =====================
    // Null value check
    // =====================
    if (empty($ic) || empty($phone) || empty($email) || empty($newPass) || empty($confirm)) {
        $errors[] = "All fields are required.";
    }

    // =====================
    // New password match check
    // =====================
    if ($newPass !== $confirm) {
        $errors[] = "New password and confirm password do not match.";
    }

    // =====================
    // Password strength check
    // =====================
    if (!empty($newPass)) {

        if (strlen($newPass) < 8) {
            $errors[] = "Password must be at least 8 characters.";
        }
        if (!preg_match('/[A-Z]/', $newPass)) {
            $errors[] = "Password must contain at least one uppercase letter.";
        }
        if (!preg_match('/[a-z]/', $newPass)) {
            $errors[] = "Password must contain at least one lowercase letter.";
        }
        if (!preg_match('/[0-9]/', $newPass)) {
            $errors[] = "Password must contain at least one number.";
        }
        if (!preg_match('/[\W_]/', $newPass)) {
            $errors[] = "Password must contain at least one symbol.";
        }
    }

    // =====================
    // Write to DB with no errors
    // =====================
    if (empty($errors)) {

        $stmt = $conn->prepare(
            "SELECT Cus_ID FROM customer 
             WHERE Cus_IC = ? AND Cus_Phone = ? AND Cus_Email = ?"
        );
        $stmt->bind_param("sss", $ic, $phone, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {

            $hashed = password_hash($newPass, PASSWORD_DEFAULT);

            $update = $conn->prepare(
                "UPDATE customer SET Cus_Password = ? WHERE Cus_ID = ?"
            );
            $update->bind_param("si", $hashed, $row['Cus_ID']);
            $update->execute();

            $message = "Password reset successfully. You may login now.";
        } else {
            $errors[] = "Information does not match our records.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="userRegStyle.css">
</head>
<body>

<form method="POST">
    <div class="login-card">

        <h1 class="title">Reset Password</h1>

        <?php if (!empty($errors)): ?>
    <div class="error">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($message): ?>
    <div class="success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>


        <div id="inputBox">

            <input type="text" name="ic"
                   placeholder="IC Number"
                   inputmode="numeric"
                   maxlength="12"
                   pattern="\d{12}"
                   title="IC Number must be exactly 12 digits"
                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);"
                   value="<?= htmlspecialchars($ic ?? '') ?>"
                   required>

            <input type="text" name="phone"
                   placeholder="Phone Number"
                   inputmode="numeric"
                   pattern="01\d{8,9}"
                   title="Phone must start with 01 and be 10–11 digits"
                   maxlength="11"
                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);"
                   value="<?= htmlspecialchars($phone ?? '') ?>"
                   required>

            <input type="email" name="email"
                   placeholder="Email Address"
                   value="<?= htmlspecialchars($email ?? '') ?>"
                   required>

            <div class="password-wrapper">
    <input type="password"
           name="new_password"
           id="new_password"
           placeholder="New Password"
           required>

    <span class="toggle-password" onclick="togglePassword('new_password', this)">👁</span>
</div>

<div class="password-wrapper">
    <input type="password"
           name="confirm_password"
           id="confirm_password"
           placeholder="Confirm New Password"
           required>

    <span class="toggle-password" onclick="togglePassword('confirm_password', this)">👁</span>
</div>


        </div>

        <button type="submit">Reset Password</button>

        <p style="text-align:center;">
            <a href="userLog.php">Back to Login</a>
        </p>

    </div>
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
