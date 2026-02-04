<?php
session_start();
include 'database.php';

// 1. 安全检查：必须登录
if (!isset($_SESSION['cus_id'])) {
    header("Location: userLog.php");
    exit;
}

$cus_id = $_SESSION['cus_id'];
$cus_name = $_SESSION['cus_name'];

// 2. 获取该用户的所有预约记录（按日期从新到旧排序）
$query = "SELECT * FROM appointments WHERE cus_id = ? ORDER BY app_date DESC, app_time DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $cus_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Records - Meow Meow Dental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --main-blue: #6cc4ff; --soft-peach: #fff8f0; }
        body { background-color: var(--soft-peach); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        .record-container {
            max-width: 1000px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .bg-pending { background-color: #fff3cd; color: #856404; } /* 橙色等待 */
        .bg-confirmed { background-color: #d4edda; color: #155724; } /* 绿色通过 */

        .service-icon {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 10px;
        }

        .table thead { background-color: var(--main-blue); color: white; }
        .back-btn { color: var(--main-blue); text-decoration: none; font-weight: bold; }
        .back-btn:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <div class="record-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-journal-text me-2"></i>My Appointment Records</h2>
            <a href="userDashBoard.php" class="back-btn"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Appointment Date</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <img src="images/<?= htmlspecialchars($row['service_type']) ?>.png" 
                                         class="service-icon" 
                                         onerror="this.src='images/Logo.png'">
                                    
                                    <?= $service_names[$row['service_type']] ?? $row['service_type'] ?>
                                </td>
                                <td><i class="bi bi-calendar3 me-2"></i><?= date("d M Y", strtotime($row['app_date'])) ?></td>
                                <td><i class="bi bi-clock me-2"></i><?= date("h:i A", strtotime($row['app_time'])) ?></td>
                                <td>
                                    <?php 
                                        $status = $row['status'];
                                        $badgeClass = ($status == 'Confirmed') ? 'bg-confirmed' : 'bg-pending';
                                        $icon = ($status == 'Confirmed') ? 'bi-check-circle-fill' : 'bi-hourglass-split';
                                    ?>
                                    <span class="status-badge <?= $badgeClass ?>">
                                        <i class="bi <?= $icon ?> me-1"></i> <?= $status ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                                You have no appointment records yet. 
                                <br><a href="make_appointment.php" class="btn btn-sm btn-primary mt-2">Book Now</a>
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