<?php
session_start();
include 'database.php';

/* ============================================================
   1. SECURITY CHECK: Redirect to login if session is not active
   ============================================================ */
if (!isset($_SESSION['cus_id'])) {
    header("Location: userLog.php");
    exit;
}

$cus_id   = $_SESSION['cus_id'];
$cus_name = $_SESSION['cus_name'] ?? 'User';

/* ============================================================
   2. DATA FETCHING: Total appointment count
   ============================================================ */
$count_sql = "SELECT COUNT(*) AS total FROM appointments WHERE cus_id = ?";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param("i", $cus_id);
$count_stmt->execute();
$total_apps = $count_stmt->get_result()->fetch_assoc()['total'];
$count_stmt->close();

/* ============================================================
   3. DATA FETCHING: Get the latest appointment record
   ============================================================ */
$latest_sql = "
    SELECT app_id, service_type, app_date, app_time, status, payment_status, price
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
    <title>User Dashboard - Meow Meow Dental</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; min-height: 100vh; font-family: sans-serif; }
        .sidebar { width: 260px; position: fixed; height: 100vh; background: #fff; border-right: 1px solid #ddd; padding: 20px; }
        .main-content { margin-left: 260px; padding: 40px; }
        .nav-link { color: #333; margin: 10px 0; border-radius: 8px; }
        .nav-link.active { background-color: #6cc4ff; color: #fff; }
        .welcome-box { background: linear-gradient(135deg, #6cc4ff, #3aaed8); color: white; padding: 30px; border-radius: 15px; }
        .card { border: none; border-radius: 15px; }
        @media (max-width: 768px) { .sidebar { width: 100%; height: auto; position: relative; } .main-content { margin-left: 0; } }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column">
    <div class="text-center mb-4">
        <img src="images/Logo.png" width="80">
        <h5 class="mt-2" style="color:#a86b32;">Meow Meow Dental</h5>
    </div>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item"><a href="userDashBoard.php" class="nav-link active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
        <li><a href="service_catalogue.php" class="nav-link"><i class="bi bi-grid me-2"></i> Services</a></li>
        <li><a href="make_appointment.php" class="nav-link"><i class="bi bi-calendar-plus me-2"></i> Book Appointment</a></li>
        <li><a href="appointment_records.php" class="nav-link"><i class="bi bi-journal-text me-2"></i> My Records</a></li>
    </ul>
    <hr>
    <a href="logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<div class="main-content">
    <div class="welcome-box mb-4">
        <h2>Welcome back, <?= htmlspecialchars($cus_name) ?> 👋</h2>
        <p>Manage your feline's dental health appointments.</p>
        <span class="badge bg-light text-dark"><?= date('l, d F Y') ?></span>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card p-4 shadow-sm text-center">
                <h6 class="text-muted">Total Visits</h6>
                <h2 class="fw-bold"><?= $total_apps ?></h2>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card p-4 shadow-sm">
                <h5 class="fw-bold mb-3">Latest Appointment Status</h5>
                <?php if ($latest_app): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Service:</strong> <?= htmlspecialchars($latest_app['service_type']) ?></p>
                            <p><strong>Date:</strong> <?= date('d M Y', strtotime($latest_app['app_date'])) ?></p>
                            <p><strong>Time:</strong> <?= date('h:i A', strtotime($latest_app['app_time'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong> <span class="badge bg-info"><?= $latest_app['status'] ?></span></p>
                            <p><strong>Payment:</strong> <span class="badge bg-<?= ($latest_app['payment_status'] == 'Paid') ? 'success' : 'warning text-dark' ?>"><?= $latest_app['payment_status'] ?></span></p>
                            <p><strong>Amount:</strong> RM <?= number_format($latest_app['price'], 2) ?></p>
                        </div>
                    </div>
                    
                    <?php if ($latest_app['payment_status'] !== 'Paid'): ?>
                        <form action="checkout.php" method="POST" class="mt-3">
                            <input type="hidden" name="app_id" value="<?= $latest_app['app_id'] ?>">
                            <input type="hidden" name="service_type" value="<?= htmlspecialchars($latest_app['service_type']) ?>">
                            <input type="hidden" name="app_date" value="<?= $latest_app['app_date'] ?>">
                            <input type="hidden" name="app_time" value="<?= $latest_app['app_time'] ?>">
                            <input type="hidden" name="price" value="<?= $latest_app['price'] ?>">
                            <button type="submit" name="pay_existing" class="btn btn-danger w-100 fw-bold">PAY NOW</button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-muted">No records found.</p>
                    <a href="make_appointment.php" class="btn btn-primary">Book Now</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>