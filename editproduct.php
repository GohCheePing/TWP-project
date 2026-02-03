<?php
session_start();

// Admin access control
if (!isset($_SESSION["admin"])) {
    header("Location: AdminLog.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            background: #f4f7fb;
        }

        header {
            background: linear-gradient(to right, #6cc4ff, #3aaed8);
            padding: 20px 40px;
            color: white;
        }

        header h2 {
            margin: 0;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-6px);
        }

        .card h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .card p {
            color: #555;
            font-size: 14px;
            line-height: 1.6;
        }

        .card a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: white;
            background: #3aaed8;
            padding: 10px 16px;
            border-radius: 20px;
            font-size: 14px;
        }

        .logout {
            margin-top: 40px;
            text-align: center;
        }

        .logout a {
            text-decoration: none;
            color: #3aaed8;
            font-weight: bold;
        }

        @media (max-width: 900px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<header>
    <h2>Admin Dashboard</h2>
    <p>Meow Meow Dental Appointment System</p>
</header>

<div class="container">

    <div class="dashboard-grid">

    <div class="card">
        <h3>Manage Services</h3>
        <p>
            View, add, edit, or remove dental services offered by the clinic.
        </p>
        <a href="adminservices.php">Go</a>
    </div>

    <div class="card">
        <h3>Manage Appointments</h3>
        <p>
            View customer appointments and manage scheduling.
        </p>
        <a href="adminappointments.php">Go</a>
    </div>

    <div class="card">
        <h3>Manage Customers</h3>
        <p>
            View registered customer information and account details.
        </p>
        <a href="admincustomers.php">Go</a>
    </div>

</div>

</body>
</html>
