<?php
session_start();
include 'database.php';

// 1. 安全检查
if (!isset($_SESSION['cus_id'])) {
    header("Location: userLog.php");
    exit;
}

$message = "";
$cus_id = $_SESSION['cus_id'];
$selected_type = isset($_GET['type']) ? $_GET['type'] : "";

// 获取服务列表
$service_query = "SELECT service_name FROM services";
$service_result = $conn->query($service_query);

// 2. 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $app_date = $_POST['app_date'];
    $app_time = $_POST['app_time'];
    $service_type = $_POST['service_type']; 

    // 后端再次验证时间（防止绕过前端限制）
    $day_of_week = date('N', strtotime($app_date)); // 1 (Mon) to 7 (Sun)
    $hour = (int)date('H', strtotime($app_time));

    $is_valid = false;
    if ($day_of_week <= 5) { // 周一至周五: 10:00 - 18:00
        if ($hour >= 10 && $hour < 18) $is_valid = true;
    } else { // 周六至周日: 11:00 - 17:00
        if ($hour >= 11 && $hour < 17) $is_valid = true;
    }

    if (!$is_valid) {
        $message = "<div class='error'>Selected time is outside business hours!</div>";
    } else {
        $sql = "INSERT INTO appointments (cus_id, app_date, app_time, service_type, status) VALUES (?, ?, ?, ?, 'Pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $cus_id, $app_date, $app_time, $service_type);

        if ($stmt->execute()) {
            $message = "<div class='success'>Booking successful! <a href='appointment_records.php' style='color:#007bff; font-weight:bold;'>Check Records</a></div>";
        } else {
            $message = "<div class='error'>Error: " . $conn->error . "</div>";
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
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        select, input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        .open-hours-info {
            font-size: 12px;
            color: #a86b32;
            margin-top: 5px;
            background: #fff3e0;
            padding: 8px;
            border-radius: 5px;
            border-left: 3px solid #3aaed8;
        }
        .btn-home {
            display: block; text-align: center; margin-top: 15px; font-size: 14px; color: #666; text-decoration: none;
        }
    </style>
</head>
<body>

<form method="POST" id="appointmentForm">
    <div class="login-card">
        <h1 class="title">New Appointment</h1>

        <?php echo $message; ?>

        <div class="form-group">
            <label>Select Dental Service</label>
            <select name="service_type" required>
                <option value="">-- Choose a treatment --</option>
                <?php 
                if ($service_result->num_rows > 0) {
                    while($s_row = $service_result->fetch_assoc()) {
                        $name = $s_row['service_name'];
                        $selected = ($selected_type == $name) ? "selected" : "";
                        echo "<option value='$name' $selected>$name</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Preferred Date</label>
            <input type="date" name="app_date" id="app_date" min="<?= date('Y-m-d') ?>" required onchange="validateTime()">
        </div>

        <div class="form-group">
            <label>Preferred Time</label>
            <input type="time" name="app_time" id="app_time" required onchange="validateTime()">
            <div class="open-hours-info">
                <strong>🕒 Open Hours:</strong><br>
                Mon-Fri: 09:00 AM - 05:00 PM<br>
                Sat-Sun: 10:00 AM - 06:00 PM
            </div>
        </div>

        <button type="submit" id="submitBtn">Confirm Booking</button>
        <a href="userDashBoard.php" class="btn-home">Back to Dashboard</a>
    </div>
</form>

<script>
function validateTime() {
    const dateInput = document.getElementById('app_date');
    const timeInput = document.getElementById('app_time');
    const submitBtn = document.getElementById('submitBtn');

    if (!dateInput.value || !timeInput.value) return;

    const date = new Date(dateInput.value);
    const day = date.getDay(); // 0 是周日, 1-5 周一至五, 6 是周六
    const time = timeInput.value; // 格式 "HH:mm"
    
    let isValid = false;
    let message = "";

    // 检查周中还是周末
    if (day >= 1 && day <= 5) {
        // 周一至周五: 9:00 - 5:00
        if (time >= "9:00" && time <= "16:30") {
            isValid = true;
        }
        else if (time >= "16:31" && time <= "17:00") {
            message = "Last appointment: 30 mins before closing";
        }
        else {
            message = "Weekdays clinic hours: 10:00 AM - 06:00 PM";
        }
    } 
    
    else {
        // 周六周日: 10:00 - 6:00
        if (time >= "10:00" && time <= "17:30") {
            isValid = true;
        } 
        else if (time >= "17:31" && time <= "18:00") {
            message = "Last appointment: 30 mins before closing";
        }
        else {
            message = "Weekend clinic hours: 11:00 AM - 05:00 PM<br>Last appointment: 30 mins before closing";
        }
    }

    if (!isValid) {
        alert(message);
        timeInput.value = ""; // 清空非法时间
        submitBtn.disabled = true;
        submitBtn.style.opacity = "0.5";
    } else {
        submitBtn.disabled = false;
        submitBtn.style.opacity = "1";
    }
}
</script>

</body>
</html>