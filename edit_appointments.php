<?php
session_start();
include 'database.php';
if (!isset($_SESSION['admin'])) header("Location: AdminLog.php");

$id = $_GET['id'];
$app = $conn->query("SELECT * FROM appointments WHERE app_id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $service = $_POST['service'];
    $date = $_POST['app_date'];
    $time = $_POST['app_time'];
    $status = $_POST['status'];
    $payment = $_POST['payment'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("UPDATE appointments SET service_type=?, app_date=?, app_time=?, status=?, payment_status=?, price=? WHERE app_id=?");
    $stmt->bind_param("ssssssi",$service,$date,$time,$status,$payment,$price,$id);
    $stmt->execute();
    header("Location: adminappointments.php");
    exit;
}

// 获取所有服务名称供选择
$services = $conn->query("SELECT service_name FROM services");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Appointments</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color: #f4f7fb; font-family: Arial, sans-serif; }
.container { margin-top: 60px; }
.card { max-width: 500px; margin: 0 auto; padding: 30px; border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); background-color: #fff; }
.card h2 { margin-bottom: 25px; color: #3aaed8; text-align: center; }
.btn-success { width: 100%; padding: 10px; font-size: 1.1rem; }
</style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2>Edit Appointments</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Service</label>
                <select name="service" class="form-control">
                    <?php while($s=$services->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($s['service_name']) ?>" <?= $s['service_name']==$app['service_type'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['service_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" name="app_date" value="<?= $app['app_date'] ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Time</label>
                <input type="time" name="app_time" value="<?= $app['app_time'] ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="Pending" <?= $app['status']=='Pending'?'selected':'' ?>>Pending</option>
                    <option value="Confirmed" <?= $app['status']=='Confirmed'?'selected':'' ?>>Confirmed</option>
                    <option value="Cancelled" <?= $app['status']=='Cancelled'?'selected':'' ?>>Cancelled</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Payment Status</label>
                <select name="payment" class="form-control">
                    <option value="Pending" <?= $app['payment_status']=='Pending'?'selected':'' ?>>Pending</option>
                    <option value="Paid" <?= $app['payment_status']=='Paid'?'selected':'' ?>>Paid</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Price (RM)</label>
                <input type="number" step="0.01" name="price" value="<?= $app['price'] ?>" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</div>

</body>
</html>