<?php
session_start();
include("database.php"); 

if (!isset($_SESSION["admin"])) {
    header("Location: AdminLog.php");
    exit;
}

// Fetch statistics
$query = "
    SELECT 
        (SELECT COUNT(*) FROM services) AS totalServices,
        (SELECT COUNT(*) FROM appointments) AS totalAppointments,
        (SELECT COUNT(*) FROM customer) AS totalCustomers
";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$totalServices = $row['totalServices'];
$totalAppointments = $row['totalAppointments'];
$totalCustomers = $row['totalCustomers'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - Meow Meow Dental</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/countup.js@2.6.0/dist/countUp.min.js"></script>
<style>
body { font-family: Arial, Helvetica, sans-serif; margin:0; }

/* Sidebar */
.sidebar { 
    width: 260px; 
    position: fixed; 
    height: 100vh; 
    background: #fff; 
    border-right: 1px solid #ddd; 
    padding: 20px; 
    display: flex; 
    flex-direction: column;
}
.sidebar img { display: block; margin: 0 auto; }
.sidebar h5 { color: #a86b32; text-align: center; margin-top: 10px; }

/* Sidebar Links */
.sidebar .nav-link {
    color: #333;
    margin: 10px 0;
    border-radius: 10px;
    padding: 10px 15px;
    display: flex;
    align-items: center;
    transition: all 0.2s ease;
    font-weight: 500;
    text-decoration: none;
}
.sidebar .nav-link i { margin-right: 10px; }

/* Hover effect for clickable links */
.sidebar .nav-link:not(.disabled):hover {
    background-color: #6cc4ff;
    color: white;
    transform: scale(1.03);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Dashboard always active */
.sidebar .nav-link.dashboard {
    background-color: #3aaed8;
    color: white;
    cursor: default;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Logout button */
.sidebar .logout-btn {
    margin-top: auto;
    border: 1px solid #feb2b2;
    color: #f56565;
    padding: 10px;
    border-radius: 12px;
    text-align: center;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s ease;
}
.sidebar .logout-btn:hover { background: #fff5f5; }

/* Main content */
.main-content { 
    margin-left: 260px; 
    padding: 40px; 
    min-height: 100vh;
    background-image: url('images/bgImage1.jpeg');
    background-size: cover;
    background-position: center;
}

/* Welcome Box */
.welcome-box { 
    background: rgba(108,196,255,0.85); 
    color: white; 
    padding: 30px; 
    border-radius: 15px; 
    margin-bottom: 30px; 
}

/* Stat Cards */
.card { 
    border: none; 
    border-radius: 15px; 
    box-shadow: 0 8px 20px rgba(0,0,0,0.05); 
    background: rgba(255,255,255,0.9);
    padding: 30px 20px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }

.stat-label { font-size: 0.9rem; color: #666; text-transform: uppercase; font-weight: 600; margin-bottom: 10px; }
.stat-number { font-size: 3rem; font-weight: 700; margin: 0; }

@media (max-width: 768px) {
    .sidebar { width: 100%; height: auto; position: relative; }
    .main-content { margin-left: 0; padding: 20px; }
}
</style>
</head>
<body>

<div class="sidebar d-flex flex-column">
    <div class="text-center mb-4">
        <img src="images/Logo.png" width="80" alt="Logo">
        <h5>Meow Meow Dental</h5>
    </div>

    <ul class="nav flex-column mb-auto">
        <li>
            <span class="nav-link dashboard"><i class="bi bi-speedometer2"></i> Dashboard</span>
        </li>
        <li>
            <a href="adminservices.php" class="nav-link"><i class="bi bi-grid"></i> Manage Services</a>
        </li>
        <li>
            <a href="adminappointments.php" class="nav-link"><i class="bi bi-calendar-plus"></i> Manage Appointments</a>
        </li>
        <li>
            <a href="admincustomers.php" class="nav-link"><i class="bi bi-people"></i> Manage Customers</a>
        </li>
    </ul>

    <hr>
    <a href="logout.php" class="logout-btn"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<div class="main-content">
    <div class="welcome-box">
        <h2>Welcome back, Admin 👋</h2>
        <p>Current overview of your clinic statistics.</p>
        <span class="badge bg-light text-dark"><?= date('l, d F Y') ?></span>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="stat-label">Total Services</div>
                <div class="stat-number text-primary" id="services"><?= $totalServices ?></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center">
                <div class="stat-label">Total Appointments</div>
                <div class="stat-number text-success" id="appointments"><?= $totalAppointments ?></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center">
                <div class="stat-label">Total Customers</div>
                <div class="stat-number text-warning" id="customers"><?= $totalCustomers ?></div>
            </div>
        </div>
    </div>
</div>

<script>
const services = new CountUp('services', <?= $totalServices ?>);
const appointments = new CountUp('appointments', <?= $totalAppointments ?>);
const customers = new CountUp('customers', <?= $totalCustomers ?>);

if (!services.error) services.start();
if (!appointments.error) appointments.start();
if (!customers.error) customers.start();

// Refresh every 10s
setInterval(() => {
    fetch('adminStats.php')
    .then(res => res.json())
    .then(data => {
        services.update(data.totalServices);
        appointments.update(data.totalAppointments);
        customers.update(data.totalCustomers);
    });
}, 10000);
</script>

</body>
</html>