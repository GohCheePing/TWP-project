<?php
session_start();
include 'database.php';

/* =========================
   1. SECURITY CHECK
========================= */
if (!isset($_SESSION['cus_id'])) {
    header("Location: userLog.php");
    exit;
}

$cus_id = $_SESSION['cus_id'];

/* =========================
   2. VALIDATE DATA FROM PREVIOUS PAGE
========================= */
if (!isset($_POST['service_type'], $_POST['app_date'], $_POST['app_time'], $_POST['price'])) {
    header("Location: make_appointment.php");
    exit;
}

$service_type = $_POST['service_type'];
$app_date     = $_POST['app_date'];
$app_time     = $_POST['app_time'];
$price        = floatval($_POST['price']);

/* =========================
   3. PAYMENT LOGIC
========================= */
$payment_required = $price > 0;
$payment_status   = $payment_required ? 'Paid' : 'Not Required';
$app_status       = $payment_required ? 'Confirmed' : 'Pending';

/* =========================
   4. SAVE APPOINTMENT
========================= */
$insert = "
    INSERT INTO appointments 
    (cus_id, service_type, app_date, app_time, status, payment_status) 
    VALUES (?, ?, ?, ?, ?, ?)
";

$stmt = $conn->prepare($insert);
$stmt->bind_param(
    "isssss",
    $cus_id,
    $service_type,
    $app_date,
    $app_time,
    $app_status,
    $payment_status
);
$stmt->execute();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout & Confirmation - Meow Meow Dental</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background-color: #f4f7f6;
}

.confirm-card {
    max-width: 600px;
    margin: 80px auto;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}
</style>
</head>

<body>

<div class="container">
    <div class="card confirm-card p-4 bg-white">

        <div class="text-center mb-4">
            <h3 class="text-success">✔ Appointment Confirmed</h3>
            <p class="text-muted">Thank you for choosing Meow Meow Dental</p>
        </div>

        <!-- Appointment Summary -->
        <ul class="list-group mb-4">
            <li class="list-group-item d-flex justify-content-between">
                <span>Service</span>
                <strong><?= htmlspecialchars($service_type) ?></strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Date</span>
                <strong><?= date('d M Y', strtotime($app_date)) ?></strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Time</span>
                <strong><?= date('h:i A', strtotime($app_time)) ?></strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Amount</span>
                <strong>RM <?= number_format($price, 2) ?></strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Payment Status</span>
                <strong><?= $payment_status ?></strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Appointment Status</span>
                <strong><?= $app_status ?></strong>
            </li>
        </ul>

        <div class="d-flex justify-content-between">
            <a href="userDashBoard.php" class="btn btn-outline-primary">
                Go to Dashboard
            </a>
            <a href="appointment_records.php" class="btn btn-primary">
                View My Appointments
            </a>
        </div>

    </div>
</div>

</body>
</html>
