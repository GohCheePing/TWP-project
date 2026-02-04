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

$cus_id   = $_SESSION['cus_id'];
$cus_name = $_SESSION['cus_name'] ?? 'User';

/* =========================
   2. TOTAL APPOINTMENTS
========================= */
$count_sql = "SELECT COUNT(*) AS total FROM appointments WHERE cus_id = ?";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param("i", $cus_id);
$count_stmt->execute();
$total_apps = $count_stmt->get_result()->fetch_assoc()['total'];
$count_stmt->close();

/* =========================
   3. LATEST APPOINTMENT
========================= */
$latest_sql = "
    SELECT service_type, app_date, app_time, status
    FROM appointments
    WHERE cus_id = ?
    ORDER BY app_date DESC, app_time DESC
    LIMIT 1
";
$latest_stmt = $conn->prepare($latest_sql);
$latest_stmt->bind_param("i", $cus_id);
$latest_stmt->execute();
$latest_app = $latest_stmt->get_result()->fetch_assoc();
$latest_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Meow Meow Dental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --main-blue: #6cc4ff; --soft-bg: #f4f7f6; }
        body { background-color: var(--soft-bg); font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: 250px; position: fixed; height: 100vh; background: white; border-right: 1px solid #ddd; padding: 20px; }
        .main-content { margin-left: 250px; padding: 40px; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .welcome-banner { background: linear-gradient(135deg, #6cc4ff, #3aaed8); color: white; padding: 30px; border-radius: 15px; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="text-center mb-4">
        <img src="images/Logo.png" width="70" alt="Logo">
        <h6 class="mt-2 fw-bold">Meow Meow Dental</h6>
    </div>
    <nav class="nav flex-column">
        <a class="nav-link active" href="userDashBoard.php"><i class="bi bi-house-door me-2"></i> Dashboard</a>
        <a class="nav-link text-dark" href="service_catalogue.php"><i class="bi bi-grid me-2"></i> Services</a>
        <a class="nav-link text-dark" href="make_appointment.php"><i class="bi bi-calendar-plus me-2"></i> Book Now</a>
        <a class="nav-link text-dark" href="appointment_records.php"><i class="bi bi-clock-history me-2"></i> History</a>
        <hr>
        <a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
    </nav>
</div>

<div class="main-content">
    <div class="welcome-banner mb-4">
        <h2>Hello, <?= htmlspecialchars($cus_name) ?>!</h2>
        <p>Welcome to your dental health dashboard.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card p-4 text-center">
                <h6 class="text-muted">Total Appointments</h6>
                <h2 class="fw-bold"><?= $total_apps ?></h2>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card p-4">
                <h5 class="fw-bold mb-4">Most Recent Booking</h5>
                <?php if ($latest_app): ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="mb-1 text-muted">Service</p>
                            <p class="fw-bold"><?= htmlspecialchars($latest_app['service_type']) ?></p>
                            <p class="mb-1 text-muted">Date & Time</p>
                            <p class="fw-bold"><?= $latest_app['app_date'] ?> | <?= date("h:i A", strtotime($latest_app['app_time'])) ?></p>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <p class="mb-1 text-muted">Status</p>
                            <span class="badge bg-success p-2 px-3">Confirmed</span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-3">
                        <p class="text-muted">No appointments found.</p>
                        <a href="make_appointment.php" class="btn btn-primary btn-sm">Make Your First Booking</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>