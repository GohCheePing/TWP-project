<?php
session_start();
include 'database.php';

if (!isset($_SESSION['admin'])) {
    header("Location: AdminLog.php");
    exit;
}

$sql = "
    SELECT a.app_id,a.app_date,a.app_time,a.status,
           c.Cus_Name,s.service_name,s.price
    FROM appointments a
    JOIN customer c ON a.cus_id = c.Cus_ID
    JOIN services s ON a.service_type = s.service_name
    ORDER BY a.app_date DESC, a.app_time DESC
";
$result = $conn->query($sql);
if (!$result) die("Query failed: ".$conn->error);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - View Appointments</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2>View Appointments</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-striped mt-3">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Price (RM)</th>
                </tr>
            </thead>
            <tbody>
            <?php if($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['app_id'] ?></td>
                        <td><?= htmlspecialchars($row['Cus_Name']) ?></td>
                        <td><?= htmlspecialchars($row['service_name']) ?></td>
                        <td><?= date('d-m-Y', strtotime($row['app_date'])) ?></td>
                        <td><?= date('H:i', strtotime($row['app_time'])) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= number_format($row['price'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">No appointments found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <a href="adminDashBoard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>
</body>
</html>