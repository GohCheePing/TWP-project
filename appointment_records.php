<?php
session_start();
include 'database.php';

// 1. Security Check
if (!isset($_SESSION['cus_id'])) {
    header("Location: userLog.php");
    exit;
}

$cus_id = $_SESSION['cus_id'];
$cus_name = $_SESSION['cus_name'];

/* ============================================================
   2. DYNAMIC COLUMN DETECTION (To match Dashboard logic)
============================================================ */
$res1 = $conn->query("SHOW COLUMNS FROM services WHERE Field LIKE '%name%'");
$col_name = ($res1 && $res1->num_rows > 0) ? $res1->fetch_assoc()['Field'] : 's_name';

$res2 = $conn->query("SHOW COLUMNS FROM services WHERE Field LIKE '%price%'");
$col_price = ($res2 && $res2->num_rows > 0) ? $res2->fetch_assoc()['Field'] : 's_price';

/* ============================================================
   3. FETCH APPOINTMENTS WITH PRICE JOIN
============================================================ */
$query = "
    SELECT a.*, s.$col_price as actual_price 
    FROM appointments a
    LEFT JOIN services s ON TRIM(a.service_type) = TRIM(s.$col_name)
    WHERE a.cus_id = ? 
    ORDER BY a.app_date DESC, a.app_time DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $cus_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Records - Meow Meow Dental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { 
            --main-blue: #6cc4ff; 
            --dark-blue: #3aaed8;
        }
        
        body { 
            /* Background Image Update */
            background: linear-gradient(rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.8)), 
                        url('images/bgImage1.jpeg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Inter', sans-serif; 
            min-height: 100vh;
        }
        
        .record-container {
            max-width: 1100px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 24px;
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 100px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }
        .bg-pending { background-color: #fff7ed; color: #c2410c; } 
        .bg-confirmed { background-color: #dcfce7; color: #15803d; } 

        .service-icon {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 12px;
            margin-right: 15px;
            border: 1px solid #eee;
        }

        .table { border-collapse: separate; border-spacing: 0 10px; }
        .table thead th { 
            background: none; 
            color: #64748b; 
            border: none; 
            text-transform: uppercase; 
            font-size: 0.75rem; 
            letter-spacing: 1px;
            padding-bottom: 20px;
        }
        .table tbody tr { 
            background: white; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
            transition: transform 0.2s;
        }
        .table tbody tr:hover { transform: scale(1.01); background-color: #f8fafc; }
        .table td { border: none; padding: 20px 15px; }
        .table td:first-child { border-radius: 12px 0 0 12px; }
        .table td:last-child { border-radius: 0 12px 12px 0; }

        .back-btn { 
            color: var(--dark-blue); 
            text-decoration: none; 
            font-weight: 600; 
            display: flex;
            align-items: center;
            transition: 0.3s;
        }
        .back-btn:hover { color: var(--main-blue); transform: translateX(-5px); }
        
        .price-text { font-weight: 700; color: #ef4444; }
    </style>
</head>
<body>

<div class="container">
    <div class="record-container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold mb-1"><i class="bi bi-clock-history me-2 text-primary"></i>My Records</h2>
                <p class="text-muted mb-0">Review your past and upcoming dental visits</p>
            </div>
            <a href="userDashBoard.php" class="back-btn"><i class="bi bi-arrow-left me-2"></i> Dashboard</a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Service Type</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="images/<?= htmlspecialchars($row['service_type']) ?>.png" 
                                             class="service-icon" 
                                             onerror="this.src='images/Logo.png'">
                                        <span class="fw-bold text-dark"><?= htmlspecialchars($row['service_type']) ?></span>
                                    </div>
                                </td>
                                <td><span class="text-secondary"><?= date("d M Y", strtotime($row['app_date'])) ?></span></td>
                                <td><span class="fw-medium"><?= date("h:i A", strtotime($row['app_time'])) ?></span></td>
                                <td><span class="price-text">RM <?= number_format($row['actual_price'] ?? 0, 2) ?></span></td>
                                <td>
                                    <?php 
                                        $status = $row['status'];
                                        $badgeClass = ($status == 'Confirmed') ? 'bg-confirmed' : 'bg-pending';
                                        $icon = ($status == 'Confirmed') ? 'bi-check-circle-fill' : 'bi-hourglass-split';
                                    ?>
                                    <span class="status-badge <?= $badgeClass ?>">
                                        <i class="bi <?= $icon ?> me-2"></i> <?= $status ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <img src="images/Logo.png" style="width: 80px; opacity: 0.3;" class="mb-3">
                                <h5 class="text-muted">No records found</h5>
                                <a href="service_catalogue.php" class="btn btn-primary mt-3 rounded-pill px-4">Book Your First Visit</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>