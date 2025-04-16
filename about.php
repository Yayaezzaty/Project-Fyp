<?php include 'navigation.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Jenny Ride Care Center</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }

        .hero {
            background: url('about-banner.jpg') no-repeat center center/cover;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.5);
        }
        .hero h1 {
            font-size: 50px;
            font-weight: bold;
            position: relative;
            z-index: 1;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            padding: 50px 20px;
            text-align: center;
        }
        .section {
            background: white;
            padding: 40px;
            margin-bottom: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
        .section:hover {
            transform: translateY(-5px);
        }
        .section h2 {
            color: #ff7700;
            font-size: 36px;
            margin-bottom: 10px;
        }
        .section p {
            font-size: 18px;
            line-height: 1.6;
        }

        .team {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .team-member {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: 0.3s;
            width: 250px;
        }
        .team-member:hover {
            transform: scale(1.05);
        }
        .team-member img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .team-member h3 {
            font-size: 20px;
            margin: 5px 0;
            color: #1e3a5f;
        }
        .team-member p {
            font-size: 16px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="hero">
        <h1>About Jenny Ride Care Center</h1>
    </div>

    <div class="container">
        <div class="section">
            <h2>Our Story</h2>
            <p>Jenny Ride Care Center started with a passion for motorcycles. Our mission is to provide top-notch motorcycle servicing and high-quality riding gear. With years of experience, we have built a reputation for excellence and customer satisfaction.</p>
        </div>

        <div class="section">
            <h2>Meet Our Team</h2>
            <div class="team">
                <div class="team-member">
                    <img src="team1.jpg" alt="Founder">
                    <h3>NoorAzni Bin Alimi</h3>
                    <p>Founder & CEO</p>
                </div>
                <div class="team-member">
                    <img src="team2.jpg" alt="Lead Mechanic">
                    <h3>Ashraf Bin NoorAzman</h3>
                    <p>Lead Mechanic</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Why Choose Us?</h2>
            <p>✔ Highly skilled and experienced mechanics</p>
            <p>✔ Top-quality motorcycle accessories and parts</p>
            <p>✔ Customer satisfaction is our priority</p>
            <p>✔ Convenient booking system for repairs and servicing</p>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
