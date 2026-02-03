<?php
session_start();
include 'database.php';

if (!isset($_SESSION['cus_id'])) {
    header("Location: userLog.php");
    exit;
}

$cus_id = $_SESSION['cus_id'];

/* ============================================================
   1. GET POST DATA
   ============================================================ */
$app_id       = $_POST['app_id'] ?? null;
$service_type = $_POST['service_type'] ?? '';
$app_date     = $_POST['app_date'] ?? '';
$app_time     = $_POST['app_time'] ?? '';
$price        = floatval($_POST['price'] ?? 0);

$payment_status = 'Paid';
$app_status     = 'Confirmed';

/* ============================================================
   2. DATABASE LOGIC (Update Existing OR Insert New)
   ============================================================ */
if (isset($_POST['pay_existing']) && !empty($app_id)) {
    // UPDATE existing record
    $sql = "UPDATE appointments SET payment_status = ?, status = ? WHERE app_id = ? AND cus_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $payment_status, $app_status, $app_id, $cus_id);
} else {
    // INSERT new record
    $sql = "INSERT INTO appointments (cus_id, service_type, app_date, app_time, status, payment_status, price) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssd", $cus_id, $service_type, $app_date, $app_time, $app_status, $payment_status, $price);
}

$success = $stmt->execute();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful - Meow Meow Dental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .receipt-card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); max-width: 500px; width: 100%; text-align: center; }
        .check-icon { font-size: 60px; color: #28a745; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="receipt-card">
    <div class="check-icon">✓</div>
    <h2 class="fw-bold">Payment Successful!</h2>
    <p class="text-muted">Thank you for your payment. Your appointment is now confirmed.</p>
    <hr>
    <div class="text-start mb-4">
        <p><strong>Service:</strong> <?= htmlspecialchars($service_type) ?></p>
        <p><strong>Date/Time:</strong> <?= $app_date ?> | <?= $app_time ?></p>
        <p><strong>Amount Paid:</strong> RM <?= number_format($price, 2) ?></p>
    </div>
    <div class="d-grid gap-2">
        <a href="userDashBoard.php" class="btn btn-primary">Back to Dashboard</a>
        <a href="appointment_records.php" class="btn btn-outline-secondary">View My Records</a>
    </div>
</div>

</body>
</html>