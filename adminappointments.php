<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: AdminLog.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Appointments</title>

<style>
body {
    font-family: Arial;
    background: #f4f7fb;
}
.container {
    max-width: 1100px;
    margin: 40px auto;
}
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}
th, td {
    padding: 14px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}
th {
    background: #3aaed8;
    color: white;
}
.status {
    padding: 6px 12px;
    border-radius: 14px;
    color: white;
}
.pending { background: orange; }
.approved { background: green; }
.rejected { background: red; }
.action-btn {
    padding: 6px 12px;
    background: #3aaed8;
    color: white;
    border-radius: 6px;
    text-decoration: none;
}
</style>
</head>

<body>
<div class="container">

<h2>Manage Appointments</h2>

<table>
<tr>
    <th>Appointment ID</th>
    <th>Customer</th>
    <th>Date</th>
    <th>Time</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<tr>
    <td>1001</td>
    <td>LZY</td>
    <td>2026-02-01</td>
    <td>10:00 AM</td>
    <td><span class="status pending">Pending</span></td>
    <td>
        <a class="action-btn" href="#">View</a>
    </td>
</tr>

</table>

<p><a href="editproduct.php">← Back to Dashboard</a></p>
</div>
</body>
</html>
