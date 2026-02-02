<?php
session_start();
include 'database.php';

// 检查是否登录（可选，但指南建议 Dashboard 后才看目录）
if (!isset($_SESSION['cus_id'])) {
    header("Location: userLog.php");
    exit;
}

// 从数据库获取服务
$query = "SELECT * FROM services";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Catalogue - Meow Meow Dental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="homePgStyle.css"> <style>
        body { background-color: #fff8f0; }
        .service-card {
            background: white;
            border-radius: 18px;
            overflow: hidden;
            transition: 0.3s;
            height: 100%;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .service-card:hover { transform: translateY(-10px); }
        .card-img-top {
            height: 200px;
            object-fit: cover; /* 确保照片不变形 */
        }
        .btn-book {
            background: linear-gradient(to right, #6cc4ff, #3aaed8);
            border: none;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container py-5">
    <div class="welcome-box text-center mb-5">
        <h2 style="color: #a86b32;">Our Professional Services</h2>
        <p>Choose the best care for your smile and book an appointment today.</p>
    </div>

    <div class="row g-4">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="col-md-4 col-lg-3">
                <div class="card service-card">
                    <img src="images/<?= htmlspecialchars($row['service_name']) ?>.png" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($row['service_name']) ?>"
                         onerror="this.src='images/default.png';"> <div class="card-body text-center">
                        <h5 class="card-title"><?= htmlspecialchars($row['service_name']) ?></h5>
                        <p class="text-primary fw-bold">RM <?= number_format($row['price'], 2) ?></p>
                        <a href="make_appointment.php?type=<?= urlencode($row['service_name']) ?>" 
                           class="btn btn-book w-100">Book Now</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>