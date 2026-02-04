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
$service_query = "SELECT service_name FROM services";
$service_result = $conn->query($service_query);

/* ========== 3. PROCESS FORM SUBMISSION ========== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $app_date = $_POST['app_date'];
    $app_time = $_POST['app_time'];
    $service_type = $_POST['service_type'];

    // --- Validation: Check Business Hours ---
    // Day of week: 1(Mon) - 7(Sun)
    $day_of_week = date('N', strtotime($app_date));

    // Convert time to minutes for comparison
    $hour = (int)date('H', strtotime($app_time));
    $minute = (int)date('i', strtotime($app_time));
    $timeMin = $hour * 60 + $minute;

    // Standard business rules
    if ($day_of_week <= 5) {
        // Weekdays: 10:00 AM - 6:00 PM (Last booking 5:30 PM)
        $open = 10 * 60;
        $last = 17 * 60 + 30;
    } else {
        // Weekends: 11:00 AM - 5:00 PM (Last booking 4:30 PM)
        $open = 11 * 60;
        $last = 16 * 60 + 30;
    }

    if ($timeMin < $open || $timeMin > $last) {
        $message = "<div class='error'>Selected time is outside business hours.</div>";
    } else {
        // --- Database Insertion ---
        $sql = "INSERT INTO appointments (cus_id, service_type, app_date, app_time, status) VALUES (?, ?, ?, ?, 'Confirmed')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $cus_id, $service_type, $app_date, $app_time);

        if ($stmt->execute()) {
            // Redirect to confirmation page on success
            $price_query = $conn->prepare("SELECT price FROM services WHERE service_name = ?");
            $price_query->bind_param("s", $service_type);
            $price_query->execute();
            $price_res = $price_query->get_result()->fetch_assoc();
            $service_price = $price_res['price'] ?? '0.00';
            
            // add &price when turn to URL
            header("Location: confirmation.php?service=" . urlencode($service_type) . "&date=$app_date&time=$app_time&price=$service_price");
            exit;
        } 
        else {
            $message = "<div class='error'>Database error: Unable to save booking.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment - Meow Meow Dental</title>
    <link rel="stylesheet" href="userRegStyle.css">
    <style>
        .form-group { margin-bottom: 15px; text-align: left; }
        .form-group label { font-weight: bold; margin-bottom: 5px; display:block; color: #333; }
        
        select, input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid rgba(0,0,0,0.2);
            background: rgba(255, 255, 255, 0.9);
        }

        .open-hours-info {
            font-size: 12px;
            background: rgba(255, 243, 224, 0.8);
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
            color: #5d4037;
            border-left: 4px solid #ffb74d;
        }

        /* Center the Back to Dashboard link */
        .btn-home {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #357abd;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-home:hover {
            text-decoration: underline;
            color: #2600ff;
        }
    </style>
</head>

<body>

<form method="POST" id="appointmentForm">
    <div class="login-card">
        <h1 class="title">New Appointment</h1>

        <?= $message ?>

        <div class="form-group">
            <label>Select Dental Service</label>
            <select name="service_type" required>
                <option value="">-- Choose a treatment --</option>
                <?php
                if ($service_result && $service_result->num_rows > 0) {
                    while ($row = $service_result->fetch_assoc()) {
                        $name = $row['service_name'];
                        $selected = ($selected_type === $name) ? "selected" : "";
                        echo "<option value='$name' $selected>$name</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Preferred Date</label>
            <input type="date" name="app_date" id="app_date"
                   min="<?= date('Y-m-d') ?>" required onchange="validateTime()">
        </div>

        <div class="form-group">
            <label>Preferred Time</label>
            <input type="time" name="app_time" id="app_time"
                   required onchange="validateTime()">

            <div class="open-hours-info">
                <strong>🕒 Open Hours</strong><br>
                Mon–Fri: 10:00 AM – 06:00 PM<br>
                Sat–Sun: 11:00 AM – 05:00 PM<br>
                <em>Last appointment: 30 mins before closing</em>
            </div>
        </div>

        <button type="submit" id="submitBtn">Confirm Booking</button>
        
        <a href="userDashBoard.php" class="btn-home">Back to Dashboard</a>
    </div>
</form>

<script>
function toMinutes(time) {
    const [h, m] = time.split(":").map(Number);
    return h * 60 + m;
}

function validateTime() {
    const date = document.getElementById("app_date").value;
    const time = document.getElementById("app_time").value;
    const btn = document.getElementById("submitBtn");

    if (!date || !time) return;

    const day = new Date(date).getDay(); // 0 Sun - 6 Sat
    const t = toMinutes(time);

    let open, last, msg;

    // 1=Mon, 5=Fri, 6=Sat, 0=Sun
    if (day >= 1 && day <= 5) {
        open = toMinutes("10:00");
        last = toMinutes("17:30");
        msg = "Weekdays: 10:00 AM - 05:30 PM (Last Slot)";
    } else {
        open = toMinutes("11:00");
        last = toMinutes("16:30");
        msg = "Weekends: 11:00 AM - 04:30 PM (Last Slot)";
    }

    if (t < open || t > last) {
        alert("Sorry! " + msg);
        document.getElementById("app_time").value = "";
        btn.disabled = true;
        btn.style.opacity = "0.5";
    } else {
        btn.disabled = false;
        btn.style.opacity = "1";
    }
}
</script>
</body>
</html>