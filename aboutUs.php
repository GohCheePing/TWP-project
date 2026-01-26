<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Team</title>

    <style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        margin: 0;
        background: linear-gradient(to bottom, #eef3f8, #f9fbfd);
    }

    .team-container {
        max-width: 1100px;
        margin: 60px auto;
        padding: 0 20px;
    }

    .team-title {
        text-align: center;
        margin-bottom: 50px;
    }

    .team-title h2 {
        font-size: 34px;
        margin-bottom: 10px;
        color: #2c3e50;
    }

    .team-title p {
        color: #666;
        font-size: 16px;
    }

    .team-card {
        display: flex;
        gap: 35px;
        background: #ffffff;
        padding: 30px;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        margin-bottom: 40px;
        align-items: center;
    }

    /* 第二张卡片反向排列 */
    .team-card.reverse {
        flex-direction: row-reverse;
    }

    .team-card img {
        width: 200px;
        height: 240px;
        object-fit: cover;
        border-radius: 14px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }


    .team-info h3 {
        margin: 0;
        font-size: 24px;
        color: #2c3e50;
    }

    .team-info h4 {
        margin: 8px 0 16px;
        color: #888;
        font-weight: normal;
    }

    .team-info p {
        line-height: 1.8;
        color: #444;
        font-size: 15.5px;
    }

    @media (max-width: 768px) {
        .team-card,
        .team-card.reverse {
            flex-direction: column;
            text-align: center;
        }

        .team-card img {
            width: 160px;
            height: 200px;
        }
    }
</style>

</head>

<body>

<div class="team-container">

    <div class="team-title">
        <h2>Meet Our Team</h2>
        <p>Dedicated professionals at Meow Meow Dental</p>
    </div>

    <div class="team-card">
        <img src="images/dentist1.jpeg" alt="Principal Dentist">
        <div class="team-info">
            <h3>Mr. Lim Zi Yang</h3>
            <h4>Principal Dentist</h4>
            <p>
                Dr. Lim Zi Yang graduated with a Bachelor of Dental Surgery from the National University of Singapore in 2010.
                With over a decade of experience, he is committed to providing exceptional dental care and staying updated
                with the latest advancements in dentistry.
            </p>
        </div>
    </div>

<div class="team-card reverse">
    <img src="images/dentist3.jpeg" alt="Dental Surgery Assistant">
    <div class="team-info">
        <h3>Mr. Wong Ying Lin</h3>
        <h4>Dental Surgery Assistant</h4>
        <p>
            Mr. Wong Ying Lin completed his Bachelor of Dental Surgery in 2018 and specializes in
            restorative and cosmetic dentistry. He focuses on personalized care and tailored treatment plans.
        </p>
    </div>
</div>


    <div class="team-card">
        <img src="images/dentist2.jpeg" alt="Receptionist">
        <div class="team-info">
            <h3>Mr. Goh Chee Ping</h3>
            <h4>Receptionist</h4>
            <p>
                Mr. Goh Chee Ping holds a Diploma in Business Administration from Temasek Polytechnic.
                With over 5 years of experience in customer service, he ensures a smooth and pleasant
                experience for all patients.
            </p>
        </div>
    </div>

</div>

</body>
</html>

<?php include 'footer.php'; ?>
