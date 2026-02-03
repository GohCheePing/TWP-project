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
<title>Admin Customers</title>

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
.action-btn {
    padding: 6px 12px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
}
.view { background: #3498db; }
.disable { background: #e67e22; }
</style>
</head>

<body>
<div class="container">

<h2>Manage Customers</h2>

<table>
<tr>
    <th>Customer ID</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Email</th>
    <th>Action</th>
</tr>

<tr>
    <td>1</td>
    <td>LZY</td>
    <td>01110336789</td>
    <td>lzy1@gmail.com</td>
    <td>
        <a class="action-btn view" href="#">View</a>
        <a class="action-btn disable" href="#">Deactivate</a>
    </td>
</tr>

</table>

<p><a href="editproduct.php">← Back to Dashboard</a></p>
</div>
</body>
</html>
