<?php
session_start();
include 'database.php';
if (!isset($_SESSION['admin'])) {
    header("Location: AdminLog.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("No customer specified.");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM customer WHERE Cus_ID=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admincustomers.php");
exit;