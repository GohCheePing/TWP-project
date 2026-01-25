<?php
// header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Website</title>
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
            padding: 12px 40px;
        }

        .nav-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Logo */
        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 45px;
            margin-right: 10px;
        }

        .logo span {
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }

        /* Navigation */
        nav ul {
            list-style: none;
            display: flex;
            gap: 30px;
        }

        nav ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        nav ul li a:hover {
            color: #c59d5f;
        }

        /* Auth buttons */
        .auth-links a {
            margin-left: 20px;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 14px;
        }

        .login {
            color: #333;
            border: 1px solid #333;
        }

        .login:hover {
            background-color: #333;
            color: #fff;
        }

        .register {
            background-color: #c59d5f;
            color: #fff;
        }

        .register:hover {
            background-color: #a8824a;
        }
    </style>
</head>
<body>

<header>
    <div class="nav-container">

        <!-- Logo -->
        <div class="logo">
            <img src= "images/MeowLogo.png">
            <span>MyWebsite</span>
        </div>

        <!-- Navigation -->
        <nav>
            <ul>
                <li><a href="about.php">About Us</a></li>
            </ul>
        </nav>

        <!-- Login / Register -->
        <div class="auth-links">
            <a href="login.php" class="login">Login</a>
            <a href="register.php" class="register">Register</a>
        </div>

    </div>
</header>
