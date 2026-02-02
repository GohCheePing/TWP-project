<?php
session_start();
include 'database.php';

// 1. 安全检查：未登录重定向
if (!isset($_SESSION['cus_id'])) {
    header("Location: userLog.php");
    exit;
}

$message = "";
$cus_id = $_SESSION['cus_id'];

// 2. 接收从 Service Catalogue 传来的参数
$selected_val = isset($_GET['type']) ? $_GET['type'] : "";

// 3. 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $app_date = $_POST['app_date'];
    $app_time = $_POST['app_time'];
    $service_type = $_POST['service_type']; // 这里拿到的是 <option> 的 value (即图片名)

    // 执行插入
    $sql = "INSERT INTO appointments (cus_id, app_date, app_time, service_type, status) VALUES (?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $cus_id, $app_date, $app_time, $service_type);

    if ($stmt->execute()) {
        $message = "<div class='success'>Booking successful! <a href='appointment_records.php' style='color:blue;'>Check Records</a></div>";
    } else {
        $message = "<div class='error'>Error: " . $conn->error . "</div>";
    }
}

// 4. 定义服务映射 (图片名 => 显示名)
// 这样你以后修改名字只需要改这里，不需要改整个表单
$services = [
    "clear aligner" => "✨ Clear Aligner",
    "night guard" => "🌙 Night Guard",
    "Wisdom Teeth Removal" => "🦷 (Wisdom Teeth Removal)",
    "Fissure Sealant" => "🛡️ (Fissure Sealant)",
    "Crown & Bridge" => "💎 (Crown & Bridge)",
    "Root Canal Treatment" => "🧪 (Root Canal)",
    "Denture" => "😁 (Denture)",
    "gum" => "🩸 (Gum Treatment)",
    "Full Mouth Rehabilitation" => "🏥 (Full Mouth Rehab)"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Make Appointment - Meow Meow Dental</title>
    <link rel="stylesheet" href="userRegStyle.css">
    <style>
        /* 针对预约页面的特定调整，确保卡片在背景图上清晰 */
        .login-card {
            background: rgba(255, 255, 255, 0.85);
            padding: 30px;
            border-radius: 15px;
            width: 100%;
            max-width: 400px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        label {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }
        select, input {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            background: white;
        }
        .btn-home {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
            color: #555;
            text-decoration: none;
        }
    </style>
</head>
<body>

<form method="POST">
    <div class="login-card">
        <h1 class="title">New Appointment</h1>

        <?php echo $message; ?>

        <div class="form-group">
            <label>Select Dental Service</label>
            <select name="service_type" required>
                <option value="">-- Choose a treatment --</option>
                <?php foreach ($services as $fileName => $displayName): ?>
                    <option value="<?= $fileName ?>" <?= ($selected_val == $fileName) ? 'selected' : '' ?>>
                        <?= $displayName ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Preferred Date</label>
            <input type="date" name="app_date" min="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="form-group">
            <label>Preferred Time</label>
            <input type="time" name="app_time" required>
        </div>

        <button type="submit">Confirm Booking</button>

        <a href="userDashBoard.php" class="btn-home">Back to Dashboard</a>
    </div>
</form>

</body>
</html>