<?php
session_start();
include 'database.php';

// --- SECURITY: Redirect to login if session is not active ---
if (!isset($_SESSION['cus_id'])) {
    header("Location: userLog.php");
    exit;
}

/* ============================================================
   1. AUTO-DETECT COLUMN NAMES (To match your database)
============================================================ */
// Find Name column (usually 's_name' or 'service_name')
$res1 = $conn->query("SHOW COLUMNS FROM services WHERE Field LIKE '%name%'");
$col_name = ($res1 && $res1->num_rows > 0) ? $res1->fetch_assoc()['Field'] : 'service_name';

// Find Price column (usually 's_price' or 'price')
$res2 = $conn->query("SHOW COLUMNS FROM services WHERE Field LIKE '%price%'");
$col_price = ($res2 && $res2->num_rows > 0) ? $res2->fetch_assoc()['Field'] : 'price';

// --- DATA FETCHING ---
$query = "SELECT * FROM services";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - Meow Meow Dental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { 
            --main-blue: #6cc4ff; 
            --dark-blue: #3aaed8;
        }

        body { 
            /* Same background as your Records page */
            background: linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)), 
                        url('images/bgImage1.jpeg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        .service-container {
            max-width: 1200px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 24px;
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        }
        
        /* Modern Card Styling */
        .service-card {
            transition: all 0.3s ease;
            height: 100%;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.1);
        }

        .card-img-top {
            object-fit: cover;
            height: 180px;
            border-bottom: 1px solid #f0f0f0;
        }

        .price-tag {
            font-weight: 800;
            color: #ef4444;
            font-size: 1.2rem;
        }

        .btn-book {
            background: linear-gradient(135deg, var(--main-blue), var(--dark-blue));
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px;
            border-radius: 12px;
            transition: 0.3s;
        }
        
        .btn-book:hover {
            opacity: 0.9;
            color: white;
            transform: scale(1.02);
        }

        .back-btn { 
            color: var(--dark-blue); 
            text-decoration: none; 
            font-weight: 600; 
            display: inline-flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .back-btn:hover { color: #2563eb; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="service-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="userDashBoard.php" class="back-btn"><i class="bi bi-arrow-left me-2"></i> Back to Dashboard</a>
            <img src="images/Logo.png" alt="Logo" style="width: 50px;">
        </div>

        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color: var(--dark-blue);">Our Dental Services</h2>
            <p class="text-muted">Choose a service to book your appointment</p>
        </div>

        <div class="row g-4">
            <?php 
            if ($result && $result->num_rows > 0):
                while($row = $result->fetch_assoc()): 
                    $s_name = $row[$col_name];
                    $s_price = $row[$col_price];
            ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card service-card text-center">
                        <img src="images/<?= htmlspecialchars($s_name) ?>.png" 
                             class="card-img-top" 
                             onerror="this.src='images/Logo.png';">
                        
                        <div class="card-body d-flex flex-column">
                            <h6 class="fw-bold mb-2"><?= htmlspecialchars($s_name) ?></h6>
                            <p class="price-tag mb-3">RM <?= number_format($s_price, 2) ?></p>
                            
                            <a href="make_appointment.php?type=<?= urlencode($s_name) ?>" 
                               class="btn btn-book mt-auto">Book Now</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No services available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>