<?php
session_start();
include 'database.php';

// --- SECURITY: Redirect to login if session is not active ---
if (!isset($_SESSION['cus_id'])) {
    header("Location: userLog.php");
    exit;
}

// --- DATA FETCHING: Retrieve all dental services from the database ---
$query = "SELECT * FROM services";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Services - Meow Meow Dental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Modern Card Styling */
        .service-card {
            transition: all 0.3s ease;
            height: 100%; /* Ensures all cards in a row have the same height */
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .service-card:hover {
            transform: translateY(-8px); /* Interactive lift effect */
        }

        .card-img-top {
            object-fit: cover; /* Prevents image distortion */
            height: 180px;
        }

        .btn-book {
            background: linear-gradient(to right, #6cc4ff, #3aaed8);
            font-weight: 600;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h2 style="color: #a86b32; font-weight: bold;">Our Dental Services</h2>
        <p class="text-muted">Comprehensive dental solutions tailored for your needs.</p>
    </div>

    <div class="row g-4">
        <?php 
        if ($result && $result->num_rows > 0):
            // Loop through each service in the database
            while($row = $result->fetch_assoc()): 
                $s_name = $row['service_name'];
        ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card service-card text-center">
                    <div class="img-container">
                        <img src="images/<?= htmlspecialchars($s_name) ?>.png" 
                             class="card-img-top" 
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
        <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>