<?php
session_start();
include 'database.php';

// 1. Security: Ensure user is logged in
if (!isset($_SESSION['cus_id'])) {
    header("Location: userLog.php");
    exit;
}

$cus_id = $_SESSION['cus_id'];

// 2. Capture data from make_appointment.php (POST)
// Note: Ensure your booking form uses method="POST"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_type = $_POST['service_type'] ?? 'General Consultation';
    $app_date     = $_POST['app_date'] ?? '';
    $app_time     = $_POST['app_time'] ?? '';
    $price        = floatval($_POST['price'] ?? 0);

    // 3. Logic: Determine status based on payment
    // In a real system, you'd integrate a Payment Gateway API here
    $payment_status = ($price > 0) ? 'Paid' : 'Not Required';
    $app_status     = ($price > 0) ? 'Confirmed' : 'Pending';

    // 4. Insert into database
    $sql = "INSERT INTO appointments (cus_id, app_date, app_time, service_type, status, payment_status, price) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssd", $cus_id, $app_date, $app_time, $service_type, $app_status, $payment_status, $price);
    
    if ($stmt->execute()) {
        $success = true;
    } else {
        die("Error saving appointment: " . $conn->error);
    }
} else {
    // Redirect if page is accessed directly without POST data
    header("Location: make_appointment.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation - Meow Meow Dental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #fff8f0; padding-top: 50px; }
        .confirmation-card { max-width: 500px; margin: auto; background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 30px; }
        .success-icon { font-size: 4rem; color: #28a745; text-align: center; display: block; }
    </style>
</head>
<body>

<div class="container">
    <div class="confirmation-card">
        <i class="bi bi-check-circle-fill success-icon mb-3"></i>
        <h2 class="text-center mb-4">Booking Confirmed!</h2>
        
        <div class="alert alert-secondary">
            <div class="d-flex justify-content-between mb-2">
                <span><strong>Service:</strong></span>
                <span><?= htmlspecialchars($service_type) ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span><strong>Date:</strong></span>
                <span><?= date('d M Y', strtotime($app_date)) ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span><strong>Time:</strong></span>
                <span><?= date('h:i A', strtotime($app_time)) ?></span>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
                <span><strong>Total Paid:</strong></span>
                <span class="text-primary fw-bold">RM <?= number_format($price, 2) ?></span>
            </div>
        </div>

        <div class="mt-4 d-grid gap-2">
            <a href="appointment_records.php" class="btn btn-primary">View My Appointments</a>
            <a href="userDashBoard.php" class="btn btn-outline-secondary">Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>