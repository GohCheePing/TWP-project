<?php
session_start();
include 'database.php';

/* ========== 1. LOGIN CHECK ========== */
if (!isset($_SESSION['cus_id'])) {
    header("Location: userLog.php");
    exit;
}

// Get URL parameters
$service = $_GET['service'] ?? 'Dental Treatment';
$price   = $_GET['price'] ?? '0.00'; 
$date    = $_GET['date'] ?? '';
$time    = $_GET['time'] ?? '';

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
    
    <style>
        :root {
            --main-blue: #6cc4ff;
            --dark-blue: #3aaed8;
            --soft-bg: #f0f9ff;
        }

        body {
            background: linear-gradient(135deg, #e0f2fe 0%, #f8fafc 100%);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .conf-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            max-width: 480px; /* Widen it slightly to facilitate horizontal arrangement */
            width: 100%;
            overflow: hidden;
            text-align: center;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .success-banner {
            background: var(--main-blue);
            padding: 40px 20px;
            color: white;
        }

        .check-icon {
            font-size: 60px;
            background: white;
            color: var(--main-blue);
            width: 100px;
            height: 100px;
            line-height: 100px;
            border-radius: 50%;
            display: inline-block;
            margin-bottom: 10px;
        }

        .details-box {
            background: var(--soft-bg);
            margin: 25px;
            padding: 20px;
            border-radius: 18px;
            text-align: left;
            border: 1px dashed var(--main-blue);
        }

        .detail-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #7f8c8d;
            font-weight: 600;
            letter-spacing: 1px;
            display: block;
        }

        .detail-value {
            font-size: 15px;
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .text-price {
            color: #e67e22; 
        }

        .btn-dashboard {
            background: var(--dark-blue);
            color: white;
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
            width: 80%;
        }

        .print-link {
            display: block;
            font-size: 13px;
            color: #95a5a6;
            cursor: pointer;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

<div class="conf-card">
    <div class="success-banner">
        <div class="check-icon">
            <i class="bi bi-calendar-check"></i>
        </div>
        <h3 class="fw-bold mb-0" style="font-family: 'Fredoka', sans-serif;">Success!</h3>
    </div>

    <div class="px-3">
        <h4 class="fw-bold text-dark mt-4">Booking Confirmed</h4>
        <p class="text-muted small mb-0 px-4">Your appointment has been successfully scheduled.</p>

        <div class="details-box">
            <div class="row">
                <div class="col-7">
                    <span class="detail-label">Service Type</span>
                    <div class="detail-value text-primary"><?= htmlspecialchars($service) ?></div>
                </div>
                <div class="col-5">
                    <span class="detail-label">Price</span>
                    <div class="detail-value text-price">RM <?= number_format($price, 2) ?></div>
                </div>
            </div>

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

</body>
</html>