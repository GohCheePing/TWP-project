<?php
session_start();
include 'database.php';

// 安全检查：必须登录才能查看
if (!isset($_SESSION['cus_id'])) {
    header("Location: userLog.php");
    exit;
}

// 获取所有服务
$query = "SELECT * FROM services";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - Meow Meow Dental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="homePgStyle.css">
    <style>
        body { background-color: #fff8f0; }
        
        .service-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%; 
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.12);
        }

        .img-container {
            height: 180px; 
            overflow: hidden;
            background-color: #f8f9fa;
        }

        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover; 
        }

        .btn-book {
            background: linear-gradient(to right, #6cc4ff, #3aaed8);
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px;
            border-radius: 8px;
        }

        .btn-book:hover {
            background: #3aaed8;
            color: white;
        }

        .price-tag {
            color: #3aaed8;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h2 style="color: #a86b32; font-weight: bold; font-size: 2.5rem;">Our Dental Services</h2>
        <div style="width: 80px; height: 4px; background: #3aaed8; margin: 10px auto;"></div>
        <p class="text-muted mt-3">Comprehensive dental solutions tailored for your needs.</p>
    </div>

    <div class="row g-4">
        <?php 
        if ($result && $result->num_rows > 0):
            while($row = $result->fetch_assoc()): 
                $s_name = $row['service_name'];
        ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card service-card text-center">
                    <div class="img-container">
                        <img src="images/<?= htmlspecialchars($s_name) ?>.png" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($s_name) ?>"
                             onerror="this.src='images/default.png';">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($s_name) ?></h5>
                        <p class="price-tag mb-3">RM <?= number_format($row['price'], 2) ?></p>
                        <a href="make_appointment.php?type=<?= urlencode($s_name) ?>" 
                           class="btn btn-book mt-auto">Book Now</a>
                    </div>
                </div>
            </div>
        <?php 
            endwhile; 
        else:
        ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">No services found in the database.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>