<?php
session_start();
include 'database.php';
if (!isset($_SESSION['admin'])) header("Location: AdminLog.php");

$id = intval($_GET['id']);
$cus = $conn->query("SELECT * FROM customer WHERE Cus_ID=$id")->fetch_assoc();
if (!$cus) die("Customer not found.");

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $ic    = trim($_POST['ic']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = $cus['Cus_Password']; // keep the original password

    // Check IC uniqueness
    $stmt = $conn->prepare("SELECT Cus_ID FROM customer WHERE Cus_IC=? AND Cus_ID<>?");
    $stmt->bind_param("si", $ic, $id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) $errors[] = "IC already exists";

    // Check Phone uniqueness
    $stmt = $conn->prepare("SELECT Cus_ID FROM customer WHERE Cus_Phone=? AND Cus_ID<>?");
    $stmt->bind_param("si", $phone, $id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) $errors[] = "Phone already exists";

    // Check Email uniqueness
    $stmt = $conn->prepare("SELECT Cus_ID FROM customer WHERE Cus_Email=? AND Cus_ID<>?");
    $stmt->bind_param("si", $email, $id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) $errors[] = "Email already exists";

    // Update if no errors
    if (empty($errors)) {
        $update = $conn->prepare("
            UPDATE customer SET Cus_Name=?, Cus_IC=?, Cus_Phone=?, Cus_Email=?, Cus_Password=? 
            WHERE Cus_ID=?
        ");
        $update->bind_param("sssssi", $name, $ic, $phone, $email, $password, $id);
        $update->execute();
        header("Location: admincustomers.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Customer</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color: #f4f7fb; font-family: Arial, sans-serif; }
.container { margin-top: 60px; }
.card { max-width: 500px; margin: 0 auto; padding: 30px; border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); background-color: #fff; }
.card h2 { margin-bottom: 25px; color: #3aaed8; text-align: center; }
.btn-success { width: 100%; padding: 10px; font-size: 1.1rem; margin-top: 10px; }
input[readonly] { background-color: #e9ecef; }
.password-input { font-family: monospace; font-size: 0.9rem; word-break: break-all; }
.alert-error { color: #fff; background-color: #dc3545; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; }
</style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2>Edit Customer</h2>

        <?php if(!empty($errors)): ?>
            <div class="alert-error">
                <?php foreach($errors as $e) echo htmlspecialchars($e) . "<br>"; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($cus['Cus_Name']) ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">IC</label>
                <input type="text" name="ic" value="<?= htmlspecialchars($cus['Cus_IC']) ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($cus['Cus_Phone']) ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($cus['Cus_Email']) ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password (Cannot edit)</label>
                <input type="text" value="<?= htmlspecialchars($cus['Cus_Password']) ?>" class="form-control password-input" readonly>
            </div>

            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</div>

</body>
</html>