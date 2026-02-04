<?php
session_start();
include 'database.php';

if (!isset($_SESSION['admin'])) {
    header("Location: AdminLog.php");
    exit;
}

$result = $conn->query("SELECT * FROM customer");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Customers</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background-color: #f4f7fb; font-family: Arial, sans-serif;
    background-image: url('images/bgImage1.jpeg');
    background-repeat: no-repeat;
    background-position: center center;
    background-size: 100% ; }
.container { margin-top: 50px; }
.table th, .table td { vertical-align: middle; }
.password-cell { max-width: 250px; word-break: break-all; font-family: monospace; font-size: 0.85rem; color: #666; }
.btn-delete { background-color: #dc3545; color: white; }
.btn-delete:hover { background-color: #c82333; color: white; }
.btn-back-container { margin-top: 10px; }
</style>
</head>

<body>
<div class="container py-4">
    <h2 class="mb-4">Manage Customers</h2>

    <form method="GET" action="edit_customer.php">
        <div class="table-responsive">
            <table class="table table-hover table-bordered bg-white">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center">Select</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>IC</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody>
                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="text-center">
                                <input type="radio" name="id" value="<?= $row['Cus_ID'] ?>" required>
                            </td>
                            <td><?= $row['Cus_ID'] ?></td>
                            <td><?= htmlspecialchars($row['Cus_Name']) ?></td>
                            <td><?= htmlspecialchars($row['Cus_IC']) ?></td>
                            <td><?= htmlspecialchars($row['Cus_Phone']) ?></td>
                            <td><?= htmlspecialchars($row['Cus_Email']) ?></td>
                            <td class="password-cell"><?= htmlspecialchars($row['Cus_Password']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No customers found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex align-items-center gap-3 mt-3">
            <button type="submit" class="btn btn-primary px-4">Edit Selected</button>
            <a href="#" class="btn btn-delete px-4 ms-auto" id="deleteBtn">Delete Selected</a>
        </div>

        <div class="btn-back-container">
            <a href="adminDashBoard.php" class="btn btn-secondary px-4">Back to Dashboard</a>
        </div>
    </form>
</div>

<script>
document.getElementById('deleteBtn').addEventListener('click', function(e){
    e.preventDefault();
    let selected = document.querySelector('input[name="id"]:checked');
    if (!selected) { alert("Please select a customer to delete."); return; }
    if (confirm("Are you sure you want to delete customer ID: " + selected.value + "?")) {
        window.location.href = "delete_customer.php?id=" + selected.value;
    }
});
</script>
</body>
</html>
