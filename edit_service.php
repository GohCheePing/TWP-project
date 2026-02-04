<?php
session_start();
include 'database.php';
if (!isset($_SESSION['admin'])) header("Location: AdminLog.php");

$id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $price = $_POST['price'];

    $stmt = $conn->prepare("UPDATE services SET price=? WHERE service_id=?");
    $stmt->bind_param("di",$price,$id);
    $stmt->execute();
    header("Location: adminservices.php");
    exit;
}

$service = $conn->query("SELECT * FROM services WHERE service_id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Service Price</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background-color: #f4f7fb;
    font-family: Arial, sans-serif;
    background-image: url('images/bgImage1.jpeg');
    background-repeat: no-repeat;
    background-position: center center;
    background-size: 100% ; 
}
.container {
    margin-top: 60px;
    
}
.card {
    max-width: 500px;
    margin: 0 auto;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    background-color: #fff;
    
}
.card h2 {
    margin-bottom: 25px;
    color: #3aaed8;
    text-align: center;
}
.btn-success {
    width: 100%;
    padding: 10px;
    font-size: 1.1rem;
}
input[disabled] {
    background-color: #e9ecef;
}
</style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2>Edit Service Price</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Service Name</label>
                <input type="text" value="<?= htmlspecialchars($service['service_name']) ?>" class="form-control" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Price (RM)</label>
                <input type="number" step="0.01" name="price" value="<?= $service['price'] ?>" required class="form-control">
            </div>

            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</div>

</body>
</html>
