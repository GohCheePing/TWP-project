<?php
session_start();
include 'database.php'; // Includes the database connection settings

// --- 1. SECURITY CHECK ---
// Check if the user ID exists in the session. 
// If not, it means the user is not logged in; redirect them to the login page.
if (!isset($_SESSION['cus_id'])) {
    header("Location: userLog.php");
    exit;
}

// Retrieve user info from the Session (stored during login)
$cus_id = $_SESSION['cus_id'];
$cus_name = $_SESSION['cus_name'];

// --- 2. DATA AGGREGATION (STATISTICS) ---
// SQL logic: Count the total number of appointments made by this specific user.
$count_query = "SELECT COUNT(*) as total FROM appointments WHERE cus_id = ?";
$c_stmt = $conn->prepare($count_query); // Prepared statement to prevent SQL injection
$c_stmt->bind_param("i", $cus_id);
$c_stmt->execute();
$total_apps = $c_stmt->get_result()->fetch_assoc()['total'];

// --- 3. RETRIEVE LATEST APPOINTMENT ---
// SQL logic: Sort by date and time in descending order (DESC) 
// and limit the result to 1 to get the most recent record.
$query = "SELECT * FROM appointments WHERE cus_id = ? ORDER BY app_date DESC, app_time DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $cus_id);
$stmt->execute();
$recent_app = $stmt->get_result()->fetch_assoc();
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
        :root {
            --sidebar-bg: #ffffff;
            --main-bg: #f4f7f6;
            --accent-color: #6cc4ff; 
        }
        body { background-color: var(--main-bg); min-height: 100vh; display: flex; }
        
        /* Sidebar: Fixed on the left side */
        .sidebar { width: 260px; background: var(--sidebar-bg); border-right: 1px solid #e0e0e0; position: fixed; height: 100vh; padding: 20px; }
        
        /* Main Content: Offset by 260px to clear the sidebar space */
        .main-content { margin-left: 260px; width: 100%; padding: 40px; }
        
        .nav-link { color: #333; margin: 10px 0; border-radius: 8px; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background-color: var(--accent-color); color: white; }
        
        /* Welcome Banner: Uses a gradient for a professional medical look */
        .welcome-section { background: linear-gradient(135deg, #6cc4ff, #3aaed8); color: white; border-radius: 15px; padding: 30px; margin-bottom: 30px; }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column">
    <div class="text-center mb-4">
        <img src="images/Logo.png" alt="Logo" style="width: 80px;">
        <h5 class="mt-2" style="color: #a86b32;">Meow Meow Dental</h5>
    </div>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="userDashBoard.php" class="nav-link active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
        </li>
        <li>
            <a href="service_catalogue.php" class="nav-link"><i class="bi bi-grid me-2"></i> Service Catalogue</a>
        </li>
        <li>
            <a href="make_appointment.php" class="nav-link"><i class="bi bi-calendar-plus me-2"></i> Book Appointment</a>
        </li>
        <li>
            <a href="appointment_records.php" class="nav-link"><i class="bi bi-journal-text me-2"></i> My Records</a>
        </li>
    </ul>
    <hr>
    <a href="logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<div class="main-content">
    <div class="welcome-section shadow-sm">
        <h1>Welcome back, <?= htmlspecialchars($cus_name) ?>!</h1>
        <p>Manage your dental health and upcoming appointments here.</p>
        <div class="badge bg-white text-dark p-2"><?= date('l, F j, Y') ?></div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card stat-card p-4 bg-white border-0 shadow-sm" style="border-radius: 15px;">
                <div class="d-flex align-items-center">
                    <div class="bg-light p-3 rounded-circle me-3">
                        <i class="bi bi-calendar-check text-primary fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Total Bookings</h6>
                        <h3 class="mb-0"><?= $total_apps ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card stat-card p-4 bg-white border-0 shadow-sm" style="border-radius: 15px;">
                <h5 class="mb-3">Latest Appointment Status</h5>
                
                <?php if ($recent_app): // If the user has at least one appointment record ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="text-muted mb-1">Service</p>
                            <p class="fw-bold"><?= htmlspecialchars($recent_app['service_type']) ?></p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted mb-1">Status</p>
                            <?php 
                                // Conditional styling: Green for 'Confirmed', Yellow/Orange for 'Pending'
                                $statusClass = ($recent_app['status'] == 'Confirmed') ? 'bg-success' : 'bg-warning text-dark';
                            ?>
                            <span class="badge <?= $statusClass ?>"><?= $recent_app['status'] ?></span>
                        </div>
                        <div class="col-sm-6 mt-2">
                            <p class="text-muted mb-1">Date & Time</p>
                            <p class="fw-bold"><?= $recent_app['app_date'] ?> at <?= $recent_app['app_time'] ?></p>
                        </div>
                        <div class="col-sm-6 mt-2 d-flex align-items-end">
                            <a href="appointment_records.php" class="btn btn-sm btn-outline-primary">View All Records</a>
                        </div>
                    </div>
                <?php else: // If the user has zero records ?>
                    <p class="text-muted">You haven't made any appointments yet.</p>
                    <a href="make_appointment.php" class="btn btn-primary btn-sm w-25">Book Now</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>