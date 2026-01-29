<?php
session_start();

// ❗ 未登录不能访问
if (!isset($_SESSION['cus_id'])) {
    header("Location: userLog.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
        }

        .sidebar .nav-link {
            color: #333;
        }

        .sidebar .nav-link:hover {
            background-color: #f1f1f1;
        }

        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="d-flex">

    <!-- Sidebar -->
    <div class="sidebar bg-light border-end p-3">
        <h4 class="text-center mb-4">User Panel</h4>

        <ul class="nav nav-pills flex-column gap-1">

            <li class="nav-item">
                <a href="profile.php" class="nav-link">Profile</a>
            </li>

            <li class="nav-item">
                <a href="service_catalogue.php" class="nav-link">Service Catalogue</a>
            </li>

            <li class="nav-item">
                <a href="service_details.php" class="nav-link">Services Detail</a>
            </li>

            <li class="nav-item">
                <a href="change_password.php" class="nav-link">Change Password</a>
            </li>

            <li class="nav-item">
                <a href="appointment_records.php" class="nav-link">View Appointment Record</a>
            </li>

            <li class="nav-item">
                <a href="make_appointment.php" class="nav-link">Make Appointment</a>
            </li>

            <li class="nav-item mt-3">
                <a href="homepage.php" class="nav-link text-danger">Logout</a>
            </li>

        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">

        <h2>Welcome, <?= htmlspecialchars($_SESSION['cus_name']) ?></h2>
        <p class="text-muted">You are logged in to your dashboard.</p>

        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Dashboard Overview</h5>
                <p class="card-text">
                    Use the sidebar to view your profile, browse services, manage appointments,
                    or update your account settings.
                </p>
            </div>
        </div>

    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
