<?php
// footer.php
?>
<style>
    footer {
        background: linear-gradient(to right, #6cc4ff, #3aaed8);
        color: #ffffff;
        padding: 30px 40px;
        margin-top: 50px;
    }

    .footer-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .footer-section {
        flex: 1;
        min-width: 200px;
        margin-bottom: 20px;
    }

    .footer-section h4 {
        margin-bottom: 12px;
        font-size: 18px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.4);
        display: inline-block;
        padding-bottom: 5px;
    }

    .footer-section p,
    .footer-section a {
        font-size: 14px;
        line-height: 1.8;
        color: #ffffff;
        text-decoration: none;
    }

    .footer-section a:hover {
        text-decoration: underline;
    }

    .footer-bottom {
        text-align: center;
        margin-top: 20px;
        font-size: 13px;
        border-top: 1px solid rgba(255, 255, 255, 0.4);
        padding-top: 15px;
    }
</style>

<footer>
    <div class="footer-container">

        <div class="footer-section">
            <h4>Meow Meow Dental</h4>
            <p>
                Providing gentle and professional dental care for your beloved feline companions.
            </p>
        </div>

        <div class="footer-section">
            <h4>Quick Links</h4>
            <p><a href="homepage.php">Home</a></p>
            <p><a href="about.php">About Us</a></p>
            <p><a href="userLog.php">Login</a></p>
            <p><a href="userReg.php">Register</a></p>
            <p><a href="AdminLog.php">Admin Login</a></p>
        </div>

        <div class="footer-section">
            <h4>Visit</h4>
            <h5>Business Hours:</h5>
            <p>Mon-Fri...10AM-6PM</p>
            <p>Sat-Sun...11AM-5PM</p>
            <br>
            <h5>Location:</h5>
            <p>Jalan Ayer Keroh Lama,75450 Bukit Beruang,Melaka, Malaysia</p>
        </div>

        <div class="footer-section">
            <h4>Contact</h4>
            <h5>Email:</h5>
            <p>meowmewodental@gmail.com</p>
            <br>
            <h5>Phone:</h5>
            <p>+60 12-345 6789</p>
            <br>
            <h5>Webiste</h5>
            <p>www.meowmeowdental.com</p>
        </div>



    </div>

    <div class="footer-bottom">
        © <?php echo date("Y"); ?> Meow Meow Dental. All rights reserved.
    </div>
</footer>
