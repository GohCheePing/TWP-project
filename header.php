<?php
// header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dental Appointment System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        header {
            background-color: #fff8f0;
            border-bottom: 1px solid #ddd;
            padding: 0 40px;
        }

        .nav-container {
            display: flex;
            align-items: center;
            height: 70px;
        }

        /* Logo */
        .logo img {
            height: 45px;
        }

        /* Menu area (占满 logo 右边空间) */
        .menu {
            flex: 1;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
        }

        .menu a {
            flex: 1;
            text-align: center;
            height: 70px;
            line-height: 70px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            color: #333;
            transition: all 0.3s ease;
        }

        /* Hover 效果 */
        .menu a:hover {
            background-color: #f1e6d6;
            color: #c59d5f;
            border-bottom: 3px solid #c59d5f;
        }
    </style>
</head>
<body>

<header>
    <div class="nav-container">

        <!-- Logo -->
        <div class="logo">
            <img src="images/Logo.png" alt="Logo">
        </div>

        <!-- Menu -->
        <div class="menu">
            <a href="homepage.php">Home</a>
            <a href="aboutUs.php">About Us</a>
            <a href="userLog.php">Login</a>
            <a href="userReg.php">Register</a>
        </div>

    </div>
</header>
