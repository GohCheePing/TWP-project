<?php
include 'database.php';

$errors = [];   // Used to store multiple error messages
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ic              = trim($_POST['ic']);
    $name            = trim($_POST['name']);
    $phone           = trim($_POST['phone']);
    $email           = trim($_POST['email']);
    $password        = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // =====================
    // Null value check
    // =====================
    if (empty($ic))      $errors[] = "IC Number is required.";
    if (empty($name))    $errors[] = "Name is required.";
    if (empty($phone))   $errors[] = "Phone number is required.";
    if (empty($email))   $errors[] = "Email is required.";
    if (empty($password)) $errors[] = "Password is required.";
    if (empty($confirmPassword)) $errors[] = "Confirm password is required.";

    // =====================
    // IC & Phone format check
    // =====================

    // IC Number: exactly 12 digits
    if (!empty($ic) && !preg_match('/^\d{12}$/', $ic)) {
    $errors[] = "IC Number must contain exactly 12 digits.";
    }

   // Phone Number: start with 01, total 10–11 digits
   if (!empty($phone) && !preg_match('/^01\d{8,9}$/', $phone)) {
    $errors[] = "Phone number must start with 01 and contain 10 to 11 digits.";
   }


    // =====================
    // Email format check
    // =====================
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // =====================
    // Password strength check
    // =====================
    if (!empty($password)) {

        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters.At least one uppercase letter, one lowercase letter, one number, and one symbol are required.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter.";
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number.";
        }
        if (!preg_match('/[\W_]/', $password)) {
            $errors[] = "Password must contain at least one symbol.";
        }
    }

    // =====================
    // Password confirm check
    // =====================
    if (!empty($password) && !empty($confirmPassword) && $password !== $confirmPassword) {
        $errors[] = "Password and Confirm Password do not match.";
    }

    // =====================
    // Check for duplicates (only check if the preceding text is correct).）
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
    // 6️⃣ insert into database
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
  

    <!--Errors displayed one by one -->
    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Tips for success -->
    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
<div id = "inputBox">

    <input type="text" name="ic"
       placeholder="IC Number"
       inputmode="numeric"
       maxlength="12"
       pattern="\d{12}"
       title="IC Number must be exactly 12 digits"
       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);"
       value="<?= htmlspecialchars($ic ?? '') ?>"
       required>
       
       <input type="text" name="name" placeholder="Full Name" value="<?= htmlspecialchars($name ?? '') ?>" required>
       
       <input type="text" name="phone"
       placeholder="Phone Number"
       inputmode="numeric"
       pattern="01\d{8,9}"
       title="Phone must start with 01 and be 10–11 digits"
       maxlength="11"
       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);"
       value="<?= htmlspecialchars($phone ?? '') ?>"
       required>
       
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
