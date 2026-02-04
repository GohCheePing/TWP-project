<?php
session_start();
include 'database.php';

if (!isset($_SESSION['admin'])) {
    header("Location: AdminLog.php");
    exit;
}

$result = $conn->query("
    SELECT appointments.*, customer.Cus_Name
    FROM appointments
    JOIN customer ON appointments.cus_id = customer.Cus_ID
    ORDER BY appointments.app_date DESC, appointments.app_time DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Manage Appointments</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2>Manage Appointments</h2>

    <form method="GET" action="edit_appointments.php">
        <table class="table table-bordered table-striped mt-3">
            <thead class="table-primary">
                <tr>
                    <th>Select</th>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Price (RM)</th>
                </tr>
            </thead>
            <tbody>
            <?php if($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><input type="radio" name="id" value="<?= $row['app_id'] ?>" required></td>
                        <td><?= $row['app_id'] ?></td>
                        <td><?= htmlspecialchars($row['Cus_Name']) ?></td>
                        <td><?= htmlspecialchars($row['service_type']) ?></td>
                        <td><?= $row['app_date'] ?></td>
                        <td><?= $row['app_time'] ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['payment_status']) ?></td>
                        <td><?= number_format($row['price'],2) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">No appointments found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Edit</button>
    </form>
</div>
</body>
</html>