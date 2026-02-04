<?php
session_start();
include 'database.php';

/* ========== 1. LOGIN CHECK ========== */
if (!isset($_SESSION['cus_id'])) {
    header("Location: userLog.php");
    exit;
}

$message = "";
$cus_id = $_SESSION['cus_id'];
$selected_type = $_GET['type'] ?? "";

/* ========== 2. FETCH SERVICE LIST ========== */
$res1 = $conn->query("SHOW COLUMNS FROM services WHERE Field LIKE '%name%'");
$col_name = ($res1 && $res1->num_rows > 0) ? $res1->fetch_assoc()['Field'] : 'service_name';

$service_query = "SELECT $col_name FROM services";
$service_result = $conn->query($service_query);

/* ========== 3. PROCESS FORM SUBMISSION ========== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $app_date = $_POST['app_date'];
    $app_time = $_POST['app_time'];
    $service_type = $_POST['service_type'];

    // Server-side validation for business hours and past times
    $day_of_week = date('N', strtotime($app_date));
    $timeMin = (int)date('H', strtotime($app_time)) * 60 + (int)date('i', strtotime($app_time));
    $currentMin = (int)date('H') * 60 + (int)date('i');
    $isToday = ($app_date === date('Y-m-d'));

    if ($day_of_week <= 5) {
        $open = 10 * 60; $last = 17 * 60 + 30; 
    } else {
        $open = 11 * 60; $last = 16 * 60 + 30; 
    }

    if ($isToday && $timeMin <= $currentMin) {
        $message = "<div class='alert alert-danger text-center'>You cannot book a time in the past.</div>";
    } elseif ($timeMin < $open || $timeMin > $last) {
        $message = "<div class='alert alert-danger text-center'>Outside business hours.</div>";
    } else {
        $sql = "INSERT INTO appointments (cus_id, service_type, app_date, app_time, status) VALUES (?, ?, ?, ?, 'Confirmed')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $cus_id, $service_type, $app_date, $app_time);

        if ($stmt->execute()) {
            $price_res = $conn->query("SELECT price FROM services WHERE $col_name = '$service_type'");
            $row_p = $price_res->fetch_assoc();
            $service_price = $row_p['price'] ?? '0.00';
            header("Location: confirmation.php?service=" . urlencode($service_type) . "&date=$app_date&time=$app_time&price=$service_price");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Meow Meow Dental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --main-blue: #6cc4ff; --dark-blue: #3aaed8; }
        body { 
            background: linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)), 
                        url('images/bgImage1.jpeg') no-repeat center center fixed;
            background-size: cover; font-family: 'Inter', sans-serif; min-height: 100vh;
            display: flex; align-items: center;
        }
        .booking-container {
            max-width: 500px; margin: 40px auto; background: rgba(255, 255, 255, 0.95);
            padding: 40px; border-radius: 30px; backdrop-filter: blur(10px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        }
        .title { color: var(--dark-blue); font-weight: 800; text-align: center; }
        .form-group { margin-bottom: 25px; text-align: center; }
        .form-group label { font-weight: 600; color: #4a5568; margin-bottom: 8px; display: block; }
        select, input {
            width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0;
            background: #f8fafc; text-align: center;
        }
        select { text-align-last: center; -moz-text-align-last: center; appearance: none; }
        .open-hours-info {
            font-size: 13px; background: #f0f9ff; padding: 15px; border-radius: 15px;
            margin-bottom: 20px; color: #0c4a6e; border-left: 5px solid var(--main-blue); text-align: center;
        }
        #submitBtn {
            width: 100%; padding: 15px; border-radius: 15px; border: none;
            background: linear-gradient(135deg, var(--main-blue), var(--dark-blue));
            color: white; font-weight: 700; transition: 0.3s;
        }
        #submitBtn:disabled { opacity: 0.5; cursor: not-allowed; }
        .btn-home { display: block; text-align: center; margin-top: 20px; color: #718096; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>

<div class="container">
    <div class="booking-container">
        <div class="text-center mb-4">
            <img src="images/Logo.png" alt="Logo" style="width: 60px; margin-bottom: 10px;">
            <h2 class="title mb-0">Book Now</h2>
        </div>

        <?= $message ?>

        <form method="POST" id="appointmentForm">
            <div class="form-group">
                <label><i class="bi bi-gear-fill me-1"></i> Treatment Type</label>
                <select name="service_type" required>
                    <option value="">-- Select Service --</option>
                    <?php
                    if ($service_result && $service_result->num_rows > 0) {
                        while ($row = $service_result->fetch_assoc()) {
                            $name = $row[$col_name];
                            $selected = ($selected_type === $name) ? "selected" : "";
                            echo "<option value='$name' $selected>$name</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label><i class="bi bi-calendar-check me-1"></i> Preferred Date</label>
                <input type="date" name="app_date" id="app_date"
                       min="<?= date('Y-m-d') ?>" required onchange="validateTime()">
            </div>

            <div class="form-group">
                <label><i class="bi bi-clock me-1"></i> Preferred Time</label>
                <input type="time" name="app_time" id="app_time"
                       required onchange="validateTime()">
            </div>

            <div class="open-hours-info">
                <strong>Business Hours</strong><br>
                Mon–Fri: 10:00 AM – 05:30 PM | Sat–Sun: 11:00 AM – 04:30 PM
            </div>

            <button type="submit" id="submitBtn">Confirm Booking</button>
            <a href="userDashBoard.php" class="btn-home">Back to Dashboard</a>
        </form>
    </div>
</div>

<script>
function toMinutes(time) {
    const [h, m] = time.split(":").map(Number);
    return h * 60 + m;
}

function validateTime() {
    const dateInput = document.getElementById("app_date");
    const timeInput = document.getElementById("app_time");
    const btn = document.getElementById("submitBtn");

    if (!dateInput.value || !timeInput.value) return;

    const selectedDate = dateInput.value;
    const today = new Date().toISOString().split('T')[0];
    const now = new Date();
    const currentMinutes = now.getHours() * 60 + now.getMinutes();

    const day = new Date(selectedDate).getDay(); 
    const t = toMinutes(timeInput.value);

    let open, last, msg;

    // Business Hours Logic
    if (day >= 1 && day <= 5) {
        open = toMinutes("10:00"); last = toMinutes("17:30");
        msg = "Weekdays: 10:00 AM - 05:30 PM";
    } else {
        open = toMinutes("11:00"); last = toMinutes("16:30");
        msg = "Weekends: 11:00 AM - 04:30 PM";
    }

    // Check 1: Is it in the past (only if today)?
    if (selectedDate === today && t <= currentMinutes) {
        alert("You cannot choose a time that has already passed today!");
        timeInput.value = "";
        btn.disabled = true;
    } 
    // Check 2: Is it within business hours?
    else if (t < open || t > last) {
        alert("Selected time is outside business hours:\n" + msg);
        timeInput.value = "";
        btn.disabled = true;
    } else {
        btn.disabled = false;
    }
}
</script>
</body>
</html>