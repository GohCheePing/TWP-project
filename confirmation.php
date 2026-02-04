<?php
session_start();
include 'database.php';

/* ========== 1. LOGIN CHECK ========== */
if (!isset($_SESSION['cus_id'])) {
    header("Location: userLog.php");
    exit;
}

// Retrieve details from URL parameters
$service = $_GET['service'] ?? 'Dental Treatment';
$date = $_GET['date'] ?? '';
$time = $_GET['time'] ?? '';

// Formatting for display
$formattedDate = !empty($date) ? date('l, d F Y', strtotime($date)) : 'Not specified';
$formattedTime = !empty($time) ? date('h:i A', strtotime($time)) : 'Not specified';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Successful - Meow Meow Dental</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        /* General Body Styling */
        body {
            background: url('images/bgImage1.jpeg') no-repeat center center fixed;
            background-size: cover;
            
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            
            /* Enable color printing */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Glassmorphism Card Effect */
        .confirmation-card {
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            backdrop-filter: blur(15px); /* Blur effect for the background image behind card */
            -webkit-backdrop-filter: blur(15px);
            
            border-radius: 40px;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.3);
            max-width: 480px;
            width: 90%;
            overflow: hidden;
            text-align: center;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        /* Brand Header */
        .brand-header {
            background: linear-gradient(135deg, #6cc4ff, #3aaed8) !important;
            padding: 50px 20px 60px;
            color: white !important;
        }

        .brand-header h1 {
            font-family: 'Fredoka', sans-serif;
            font-weight: 600;
            margin: 0;
            font-size: 2.2rem;
            letter-spacing: 1px;
        }

        /* Logo Squircle */
        .logo-circle {
            width: 110px;
            height: 110px;
            background: #ffffff !important;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: -55px auto 20px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            position: relative;
            z-index: 10;
            border: 5px solid #fff;
        }

        /* Receipt Information Box */
        .details-box {
            background-color: rgba(255, 255, 255, 0.6) !important;
            border-radius: 25px;
            padding: 25px;
            margin: 20px 35px;
            text-align: left;
            border: 2px dashed #6cc4ff !important;
        }

        .detail-label {
            font-size: 0.75rem;
            color: #7a92a7 !important;
            text-transform: uppercase;
            font-weight: 700;
            display: block;
            letter-spacing: 1px;
        }

        .detail-value {
            font-size: 1.1rem;
            color: #333 !important;
            font-weight: 600;
            margin-bottom: 15px;
        }

        /* Primary Button */
        .btn-dashboard {
            background: linear-gradient(135deg, #6cc4ff, #3aaed8) !important;
            color: white !important;
            border: none;
            padding: 16px;
            border-radius: 20px;
            font-weight: 600;
            text-decoration: none;
            display: block;
            margin: 10px 35px 0;
            transition: 0.3s;
        }

        .btn-dashboard:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(108, 196, 255, 0.5);
            color: white;
        }

        /* Print Trigger */
        .print-link {
            display: inline-block;
            margin: 20px 0 40px;
            color: #666;
            text-decoration: none;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .print-link:hover { color: #3aaed8; }

        /* Print Media Settings */
        @media print {
            body { 
                background: url('images/bgImage1.jpeg') no-repeat center center !important; 
                background-size: cover !important;
            }
            .confirmation-card { 
                box-shadow: none !important; 
                margin: 0 auto !important;
                background: white !important; /* Solid background for paper */
            }
            .btn-dashboard, .print-link { display: none !important; }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>

<div class="confirmation-card animate__animated animate__backInUp">
    <div class="brand-header">
        <h1>Meow Meow Dental</h1>
        <p class="small mb-0 opacity-75">Quality Dental Care for Every Cat</p>
    </div>

    <div class="logo-circle animate__animated animate__bounceIn animate__delay-1s">
        <img src="images/Logo.png" alt="Logo" style="width: 80px; height: 80px; object-fit: contain;">
    </div>

    <div class="px-3">
        <h4 class="fw-bold text-dark mt-2">Booking Confirmed!</h4>
        <p class="text-muted small mb-0 px-4">Your visit has been successfully scheduled. We look forward to seeing you!</p>

        <div class="details-box">
            <span class="detail-label">Service Type</span>
            <div class="detail-value text-primary"><?= htmlspecialchars($service) ?></div>
            
            <div class="row">
                <div class="col-7">
                    <span class="detail-label">Appt Date</span>
                    <div class="detail-value"><?= $formattedDate ?></div>
                </div>
                <div class="col-5">
                    <span class="detail-label">Appt Time</span>
                    <div class="detail-value"><?= $formattedTime ?></div>
                </div>
            </div>

            <span class="detail-label">Visit Status</span>
            <div class="detail-value mb-0 text-success">
                <i class="bi bi-patch-check-fill me-1"></i> Verified
            </div>
        </div>

        <a href="userDashBoard.php" class="btn btn-dashboard">
            Back to Dashboard
        </a>

        <span onclick="window.print()" class="print-link">
            <i class="bi bi-printer me-1"></i> Print Receipt
        </span>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>