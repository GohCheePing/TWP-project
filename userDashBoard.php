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
   2. DATA FETCHING (Stats)
========================= */

// Auto-detect columns to prevent SQL errors
$res1 = $conn->query("SHOW COLUMNS FROM services WHERE Field LIKE '%name%'");
$col_name = ($res1 && $res1->num_rows > 0) ? $res1->fetch_assoc()['Field'] : 's_name';

$res2 = $conn->query("SHOW COLUMNS FROM services WHERE Field LIKE '%price%'");
$col_price = ($res2 && $res2->num_rows > 0) ? $res2->fetch_assoc()['Field'] : 's_price';

// Total Appointment Count
$count_stmt = $conn->prepare("SELECT COUNT(*) AS total FROM appointments WHERE cus_id = ?");
$count_stmt->bind_param("i", $cus_id);
$count_stmt->execute();
$total_apps = $count_stmt->get_result()->fetch_assoc()['total'];
$count_stmt->close();

// Total Spend Calculation (JOIN logic for accuracy)
$spend_sql = "
    SELECT SUM(s.$col_price) AS total_spend 
    FROM appointments a
    INNER JOIN services s ON TRIM(a.service_type) = TRIM(s.$col_name)
    WHERE a.cus_id = ?
";
$spend_stmt = $conn->prepare($spend_sql);
$spend_stmt->bind_param("i", $cus_id);
$spend_stmt->execute();
$total_spend = $spend_stmt->get_result()->fetch_assoc()['total_spend'] ?? 0.00;
$spend_stmt->close();

/* =========================
   3. LATEST APPOINTMENT
========================= */
$latest_sql = "
    SELECT a.service_type, a.app_date, a.app_time, a.status, s.$col_price AS display_price
    FROM appointments a
    LEFT JOIN services s ON TRIM(a.service_type) = TRIM(s.$col_name)
    WHERE a.cus_id = ?
    ORDER BY a.app_date DESC, a.app_time DESC
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Meow Meow Dental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap');

        :root {
            --main-blue: #6cc4ff;
            --dark-blue: #3aaed8;
            --bg-color: #f8fafc;
            --sidebar-width: 280px;
        }

        body { background-color: var(--bg-color); font-family: 'Inter', sans-serif; margin: 0; }

        .dashboard-container { display: flex; min-height: 100vh; }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid #edf2f7;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            padding: 20px;
        }

        .sidebar-brand {
            padding: 20px 0;
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar-brand img {
            width: 80px;
            margin-bottom: 10px;
        }

        .sidebar-brand h4 {
            color: var(--dark-blue);
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: -0.5px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #718096;
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 8px;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .menu-item i { font-size: 1.2rem; margin-right: 15px; }

        .menu-item:hover, .menu-item.active {
            background-color: #f0f9ff;
            color: var(--dark-blue);
        }

        /* Main Content Styling */
        .main-content { flex: 1; padding: 0; overflow-x: hidden; }

        .top-nav {
            padding: 20px 40px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .welcome-section {
            background: var(--main-blue);
            color: white;
            padding: 60px 40px;
            margin: 0 40px 30px 40px;
            border-radius: 24px;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 24px;
            padding: 30px;
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.02);
            height: 100%;
        }

        .icon-square {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 20px;
        }

        .blue-icon { background: #e0f2fe; color: #0ea5e9; }
        .orange-icon { background: #fff7ed; color: #f97316; }

        .badge-confirmed {
            background: #dcfce7;
            color: #15803d;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 100px;
        }

        .logout-btn {
            margin-top: auto;
            border: 1px solid #feb2b2;
            color: #f56565;
            padding: 10px;
            border-radius: 12px;
            text-align: center;
            text-decoration: none;
            font-weight: 600;
        }
        .logout-btn:hover { background: #fff5f5; }
    </style>
</head>
<body>

<div class="dashboard-container">
    <nav class="sidebar">
        <div class="sidebar-brand">
            <img src="images/Logo.png" alt="Meow Meow Dental Logo">
            <h4>Meow Dental</h4>
        </div>
        
        <div class="nav flex-column">
            <a href="userDashBoard.php" class="menu-item active"><i class="bi bi-grid-fill"></i> Dashboard</a>
            <a href="service_catalogue.php" class="menu-item"><i class="bi bi-calendar-plus"></i> New Booking</a>
            <a href="appointment_records.php" class="menu-item"><i class="bi bi-file-earmark-text"></i> My Records</a>
            <a href="aboutUS.php" class="menu-item"><i class="bi bi-info-circle"></i> About Us</a>
        </div>

        <a href="logout.php" class="logout-btn mt-5"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
    </nav>

    <div class="main-content">
        <div class="top-nav">
            <span class="text-muted"><i class="bi bi-person-circle me-2"></i>Logged in as: <strong><?= htmlspecialchars($cus_name) ?></strong></span>
        </div>

        <div class="welcome-section">
            <h1 class="fw-bold mb-2">Welcome Back, <?= htmlspecialchars($cus_name) ?>!</h1>
            <p class="mb-0 opacity-75">Track your dental appointments and history in one place.</p>
        </div>

        <div class="container-fluid px-5">
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="icon-square blue-icon"><i class="bi bi-calendar-check"></i></div>
                        <p class="text-muted small fw-bold mb-1">TOTAL APPOINTMENTS</p>
                        <h2 class="fw-bold mb-0"><?= $total_apps ?></h2>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="icon-square orange-icon"><i class="bi bi-wallet2"></i></div>
                        <p class="text-muted small fw-bold mb-1">TOTAL SPEND</p>
                        <h2 class="fw-bold mb-0">RM <?= number_format($total_spend, 2) ?></h2>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card d-flex flex-column justify-content-center">
                        <a href="service_catalogue.php" class="btn btn-primary mb-2 py-3 fw-bold rounded-4 shadow-sm" style="background: #2563eb; border: none;">New Booking</a>
                        <a href="appointment_records.php" class="btn btn-light py-2 fw-bold text-muted rounded-4">History</a>
                    </div>
                </div>
            </div>

            <div class="card stat-card border-0 mb-5">
                <h5 class="fw-bold mb-4">Latest Booking Details</h5>
                <?php if ($latest_app): ?>
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <p class="text-muted small mb-1">SERVICE</p>
                            <h6 class="fw-bold text-primary mb-0"><?= htmlspecialchars($latest_app['service_type']) ?></h6>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">DATE & TIME</p>
                            <h6 class="fw-bold mb-0"><?= date("d M Y", strtotime($latest_app['app_date'])) ?> | <?= date("h:i A", strtotime($latest_app['app_time'])) ?></h6>
                        </div>
                        <div class="col-md-2">
                            <p class="text-muted small mb-1">PRICE</p>
                            <h6 class="fw-bold text-danger mb-0">RM <?= number_format($latest_app['display_price'] ?? 0, 2) ?></h6>
                        </div>
                        <div class="col-md-3 text-end">
                            <span class="badge-confirmed"><?= htmlspecialchars($latest_app['status']) ?></span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-3">
                        <p class="text-muted mb-0">No booking records found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>