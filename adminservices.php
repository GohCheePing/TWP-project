<?php
session_start();
include("database.php"); 

if (!isset($_SESSION["admin"])) {
    header("Location: AdminLog.php");
    exit;
}

$result = $conn->query("SELECT * FROM services ORDER BY service_id ASC");
if (!$result) die("Query failed: ".$conn->error);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Manage Services</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style> 
    body {
    background-image: url('images/bgImage1.jpeg');
    background-repeat: no-repeat;
    background-position: center center;
    background-size: 100% ; 
    }
</style>
</head>
<body>
<div class="container py-4">
    <h2>Manage Services</h2>

    <form method="GET" action="edit_service.php">
        <table class="table table-bordered table-striped mt-3">
            <thead class="table-primary">
                <tr>
                    <th>Select</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price (RM)</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><input type="radio" name="id" value="<?= $row['service_id'] ?>" required></td>
                        <td><?= $row['service_id'] ?></td>
                        <td><?= htmlspecialchars($row['service_name']) ?></td>
                        <td><?= number_format($row['price'],2) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">No services found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Edit</button>
        <div class="mt-2">
            <a href="adminDashBoard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </form>
</div>
</body>
</html>
