<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Motorcycle Repairs | Jenny Ride Care Center</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="stylesAc.css">
    <style>
        :root {
            --primary: #e63946;
            --secondary: #1d3557;
            --accent: #a8dadc;
            --light: #f1faee;
            --dark: #457b9d;
        }
        
        .service-details {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 2rem;
            display: flex;
            flex-wrap: wrap;
            gap: 3rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .service-video {
            flex: 1;
            min-width: 300px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .service-video video {
            width: 100%;
            display: block;
        }
        
        .service-info {
            flex: 1;
            min-width: 300px;
        }
        
        .service-info h1 {
            color: var(--secondary);
            font-size: 2.5rem;
            margin-top: 0;
            position: relative;
            padding-bottom: 1rem;
        }
        
        .service-info h1:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: var(--primary);
        }
        
        .service-info p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 2rem;
        }
        
        .service-info h2 {
            color: var(--dark);
            margin: 2rem 0 1rem;
        }
        
        .benefits-list {
            list-style: none;
            padding: 0;
            margin: 0 0 2rem 0;
        }
        
        .benefits-list li {
            padding: 0.8rem 0;
            padding-left: 2.5rem;
            position: relative;
            font-size: 1.1rem;
        }
        
        .benefits-list li:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--primary);
            font-weight: bold;
            font-size: 1.3rem;
        }
        
        .btn {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(230, 57, 70, 0.3);
        }
        
        .btn:hover {
            background: var(--secondary);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(29, 53, 87, 0.3);
        }
        
        .cta-section {
            margin-top: 2.5rem;
        }
        
        .cta-note {
            margin-top: 1rem;
            font-style: italic;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .service-details {
                flex-direction: column;
                padding: 1.5rem;
                margin: 1.5rem;
            }
            
            .service-info h1 {
                font-size: 2rem;
            }
            
            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <?php include 'navigation.php'; ?>

    <!-- Main Content Section -->
    <main class="main-content">
        <section class="service-details">
            <div class="service-video">
                <video controls poster="images/repair-poster.jpg">
                    <source src="accessories.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="service-info">
                <h1>Premium Motorcycle Repairs</h1>
                <p>At Jenny Ride Care Center, we combine cutting-edge technology with decades of expertise to deliver unparalleled motorcycle repair services. Our certified technicians are passionate about restoring your bike to peak performance.</p>
                
                <h2>Why Riders Choose Us</h2>
                <ul class="benefits-list">
                    <li>ASE-Certified Master Technicians with 10+ years experience</li>
                    <li>Genuine OEM and premium aftermarket parts</li>
                    <li>90% of repairs completed within 24 hours</li>
                    <li>Transparent pricing with no hidden fees</li>
                    <li>24-month warranty on all repairs</li>
                    <li>Free diagnostic check with every service</li>
                    <li>Complimentary bike wash with major repairs</li>
                </ul>
                
                <div class="cta-section">
                    <a href="booking.php" class="btn">
                        <i class="fas fa-calendar-alt"></i> Book Your Repair Now
                    </a>
                    <p class="cta-note">Or call us directly at <strong>010-8446445</strong></p>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Animation for benefit items
            const benefits = document.querySelectorAll('.benefits-list li');
            benefits.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';
                item.style.transition = `all 0.3s ease ${index * 0.1}s`;
                
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, 100);
            });
            
            // Video hover effect
            const video = document.querySelector('.service-video video');
            if(video) {
                video.addEventListener('mouseover', () => {
                    video.controls = true;
                });
                
                video.addEventListener('mouseout', () => {
                    if(!video.paused) return;
                    video.controls = false;
                });
            }
        });
    </script>
</body>
</html>