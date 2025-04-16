<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jenny Ride Care Center</title>
    <link rel="stylesheet" href="styles.css">
    <style>
          body {
            background-color:#FFF;
            color:black;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        header {
            background-color:#1e3a5f;
            padding: 20px;
            text-align: center;
            font-family: monospace;
            font-size: 20px;
            color: #FFF
            
        }
        .logo {
            width: 200px;
        }
        nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 40px;
        }
        nav ul li {
            margin: 0 15px;
            display: inline;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 15px;
            transition: color 0.3s ease-in-out, border-bottom 0.3s ease-in-out;
        }
        nav ul li a:hover {
    color: #ff9800; /* Change text color on hover */
    border-bottom: 3px solid #ff9800; /* Add an underline effect */
}
.hero {
            position: relative;
            width: 100%;
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
         }
         #hero-video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensures the video fills the section */
    z-index: -1; /* Pushes video behind content */
}

        .hero-overlay {
            background: rgba(0, 0, 0, 0.5);
            color:white;
            padding: 40px;
            border-radius: 10px;
            animation: fadeIn 2s ease-in-out;
        }

        /* Typography for Heading */
        .hero h1 {
    font-size: 30px;
    font-weight: 600;
    letter-spacing: 2px;
    margin-bottom: 10px;
    color: #ffcc00;
}
.hero h2 {
    font-size: 80px;
    font-weight: bold;
    color: #ff7700;
    text-transform: uppercase;
    letter-spacing: 3px;
    margin-bottom: 10px;
}

.hero p {
    font-size: 22px;
    font-weight: 400;
    color: #ffffff;
    margin-bottom: 20px;
}

/* Animated Button */
.btn-book {
    display: inline-block;
    background: linear-gradient(45deg, #FF5733, #FF8C00);
    padding: 12px 24px;
    color: white;
    font-size: 18px;
    text-decoration: none;
    border-radius: 25px;
    transition: 0.3s;
    animation: bounce 2s infinite alternate;
}
.btn-book:hover {
    background: linear-gradient(45deg, #FF8C00, #FF5733);
    transform: scale(1.05);
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes bounce {
    from {
        transform: translateY(0);
    }
    to {
        transform: translateY(-10px);
    }
}

/* Applying Animations */
.fade-in {
    animation: fadeIn 2s ease-in-out;
}

.slide-in {
    animation: slideIn 1.5s ease-in-out;
}

        .book-now-btn {
            background: linear-gradient(45deg, #FF5733, #FF8C00);
            color: white;
            padding: 12px 24px;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 25px;
            transition: 0.3s;
            display: inline-block;
            margin-top: 20px;
        }
        .book-now-btn:hover {
            background: linear-gradient(45deg, #FF8C00, #FF5733);
            transform: scale(1.1);
        }
      
        .btn {
            background-color: #FF6600;
            color: #ffffff;
            padding: 15px 30px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            border-radius: 50px;
            font-weight: bold;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.5);
        }
        .btn:hover {
            background-color: #e68900;
            transform: scale(1.1);
        }
        .services, .contact {
            text-align: center;
            padding: 50px 20px;
        }

        /* Dropdown Button */
.dropbtn {
    background-color: transparent;
    border: none;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    width: 50px;
    height: 50px;
}

/* User Icon */
.user-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

/* Dropdown Menu */
.dropdown {
    position: relative;
    display: inline-block;
}

/* Fix dropdown box */
.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background: white;
    min-width: 150px;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    transform: translateY(-10px);
    opacity: 0;
    transition: opacity 0.3s ease, transform 0.3s ease;
    padding: 8px 0;
    z-index: 100;
}

/* Dropdown Links */
.dropdown-content li {
    list-style: none;
    text-align: center;
}

.dropdown-content li a {
    display: block;
    padding: 10px 12px;
    color: #333;
    text-decoration: none;
    font-size: 14px;
    font-weight: bold;
    transition: background 0.3s ease;
}

/* Hover effect */
.dropdown-content li a:hover {
    background: #f4f4f4;
}

/* Fix dropdown size */
.dropdown.show .dropdown-content {
    display: block;
    opacity: 1;
    transform: translateY(0);
}


    
 .services {
    padding: 40px 0;
    background-color:white;
    text-align: center;
}

.service-container {
    display: flex;
    justify-content: center;
    align-items: stretch;  /* Ensures all boxes have equal height */
    gap: 20px;
    max-width: 1200px;
    margin: auto;
    flex-wrap: wrap; /* Ensures responsiveness */
}

.service-box {
    background-color: #FFF;
    border: 1px solid #1e3a5f; /* Border color */
    padding: 20px;
    width: 30%; /* Ensures three boxes fit in a row */
    text-align: center;
}

.service-box img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}



.contact-section {
    background: url('your-background-image.jpg') no-repeat center center/cover;
    padding: 80px 20px;
    text-align: center;
    color: white;
    position: relative;
}

.contact-overlay {
    background: rgba(0, 0, 0, 0.7);
    padding: 40px;
    border-radius: 10px;
    display: inline-block;
}

.contact-info {
    margin: 20px 0;
}

.contact-item {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
}

.contact-item i {
    font-size: 20px;
    margin-right: 10px;
    color: #FFA500;
}

.btn-book {
    display: inline-block;
    background: linear-gradient(45deg, #FF5733, #FF8C00);
    padding: 12px 24px;
    color: white;
    font-size: 18px;
    text-decoration: none;
    border-radius: 25px;
    transition: 0.3s;
}

.btn-book:hover {
    background: linear-gradient(45deg, #FF8C00, #FF5733);
    transform: scale(1.05);
}

/* Footer Styling */
.footer {
    background-color: #1e3a5f; /* Dark Brown */
    color:white;
    padding: 40px 20px;
    text-align: center;
   
}

/* Center Content */
.container {
    max-width: 1100px;
    margin: auto;
    display: flex;
    flex-direction: column;
    align-items: center; /* Center items */
}

/* Title Center */
.footer-title {
    font-size: 35px;
    margin-bottom: 20px;
    font-weight: bold;
    text-align: center;
    color:#FF7700;
    font-style: normal;
}

/* Flexbox Layout for Boxes */
.footer-content {
    display: flex;
    justify-content: center; /* Center horizontally */
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 30px; /* Add spacing */
    width: 100%;
}

/* Footer Boxes */
.footer-box {
    width: 30%;
    padding: 20px;
    border: 1px solid #FF7700;
    border-radius: 10px;
    text-align: left;
    min-width: 280px; /* Prevent boxes from being too small */
}

/* Social Button */
.social-btn {
    background: none;
    border: 1px solid #FF7700;
    padding: 10px;
    color: white;
    font-style: italic;
    font-size: 14px;
    cursor: pointer;
    margin-top: 10px;
    border-radius: 20px;
    width: 100%;
}

.social-btn:hover {
    background-color: rgba(255, 255, 255, 0.2);
}
#cartCount {
    background: red;
    color: white;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 50%;
    position: relative;
    top: -10px;
    left: -5px;
}
.service-container {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.service-box {
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    width: 300px;
    text-decoration: none;
    color: inherit;
    transition: 0.3s ease-in-out;
}

.service-box:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    transform: translateY(-5px);
}

.service-box img {
    width: 100%;
    height: auto;
    border-radius: 10px;
}
.welcome-message {
    font-size: 18px;
    font-style: italic;
    color:#FFD700;
    margin: 10px 0;
    font-weight: 400;
    text-shadow: 1px 1px 2px rgba (0,0,0,0.5);
    animation: fadeIn 1.5s ease-in-out;
}

    </style>
</head>
<body>
<?php include 'navigation.php'; ?>
  
<section class="hero">
    <div class="hero">
        <video autoplay muted loop id="hero-video" poster="fallback-image.jpg">
            <source src="vid1.mp4" type="video/mp4">
            <source src="vid1.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    <div class="hero-overlay">
        <h1><span class="fade-in">WELCOME TO</span></h1>
        <h2 class="slide-in">JENNY RIDE CARE CENTER</h2>
        <p class="fade-in">Services & Repairs</p>
        <?php if(isset($_SESSION['user_id'])): ?>
            <p class="welcome-message">Welcome back, <?php echo htmlspecialchars($_SESSION['name']); ?>!</p>
        <?php endif; ?>
        <a href="<?php echo isset($_SESSION['user_id']) ? 'booking.php' : 'login.html'; ?>" class="btn">Book Now</a>
    </div>
</section>
    
<section class="services">
    <div class="service-container">
        <a href="motorcycle.php" class="service-box">
            <img src="r4.jpg" alt="Motorcycle Repairs">
            <h3>Motorcycle Repairs</h3>
            <p>Click to learn more about our repair services.</p>
        </a>

        <a href="oil.php" class="service-box">
            <img src="r5.jpg" alt="Oil Change">
            <h3>Oil Change</h3>
            <p>Click to learn more about our oil change services.</p>
        </a>

        <a href="accessories.php" class="service-box">
            <img src="r3.jpg" alt="Accessories & Equipment">
            <h3>Accessories & Equipment</h3>
            <p>Click to explore our accessories and equipment.</p>
        </a>
    </div>
</section>
<?php include 'footer.php'; ?>
    
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Check if user is logged in from PHP session
        let isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
        let userAccountLink = document.getElementById("userAccount");
        let bookNowBtn = document.getElementById("bookNow");
        let serviceLink = document.getElementById("serviceLink");

        if (!isLoggedIn) {
            if(userAccountLink) userAccountLink.href = "signup.html";
            if(bookNowBtn) {
                bookNowBtn.addEventListener("click", function (e) {
                    e.preventDefault();
                    alert("Please sign up or log in to book a service.");
                    window.location.href = "signup.html";
                });
            }
            if(serviceLink) {
                serviceLink.addEventListener("click", function (e) {
                    e.preventDefault();
                    alert("Please sign up or log in to view services.");
                    window.location.href = "signup.html";
                });
            }
        } else {
            if(userAccountLink) userAccountLink.href = "account.php";
        }

        // Cart count functionality
        let cartCount = localStorage.getItem("cartCount") || 0;
        let cartCountElement = document.getElementById("cartCount");
        if(cartCountElement) cartCountElement.textContent = cartCount;
    });

    function toggleDropdown() {
        let dropdown = document.querySelector(".dropdown");
        dropdown.classList.toggle("show");
    }

    // Close when clicking outside
    window.onclick = function(event) {
        if (!event.target.closest(".dropdown")) {
            let dropdowns = document.querySelectorAll(".dropdown");
            dropdowns.forEach(function(dropdown) {
                dropdown.classList.remove("show");
            });
        }
    };
</script>
</body>
</html>