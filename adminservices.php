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
<title>Admin Services</title>

<style>
body {
    font-family: Arial;
    background: #f4f7fb;
}
.container {
    max-width: 1100px;
    margin: 40px auto;
}
h2 {
    margin-bottom: 20px;
}
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
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
    font-size: 13px;
}
.edit { background: #4CAF50; }
.delete { background: #e74c3c; }
.top-bar {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
}
.add {
    background: #3aaed8;
    color: white;
    padding: 8px 14px;
    border-radius: 20px;
    text-decoration: none;
}
</style>
</head>

<body>
<div class="container">

<div class="top-bar">
    <h2>Manage Services</h2>
    <a class="add" href="#">+ Add Service</a>
</div>

<table>
<tr>
    <th>Service ID</th>
    <th>Service Name</th>
    <th>Description</th>
    <th>Action</th>
</tr>

<tr>
    <td>1</td>
    <td>Dental Braces</td>
    <td>Teeth alignment service</td>
    <td>
        <a class="action-btn edit" href="#">Edit</a>
        <a class="action-btn delete" href="#">Delete</a>
    </td>
</tr>

</table>

<p><a href="editproduct.php">← Back to Dashboard</a></p>
</div>
</body>
</html>