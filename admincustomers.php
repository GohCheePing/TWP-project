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
<title>Admin Customers</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background-color: #f4f7fb;
    font-family: Arial, sans-serif;
}
.container {
    margin-top: 50px;
}
.table th, .table td {
    vertical-align: middle;
}
.password-cell {
    max-width: 250px;
    word-break: break-all;   /* 长密码自动换行 */
    font-family: monospace;  /* 看起来像 hash */
    font-size: 0.85rem;
}
</style>
</head>

<body>
<div class="container py-4">
    <h2 class="mb-4">Manage Customers</h2>

    <form method="GET" action="edit_customer.php">
        <table class="table table-striped table-bordered">
            <thead class="table-primary">
                <tr>
                    <th>Select</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>IC</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Password</th>
                </tr>
            </thead>
            <tbody>
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
                    <td class="password-cell">
                        <?= htmlspecialchars($row['Cus_Password']) ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">
            Edit
        </button>
    </form>
</div>
</body>
</html>