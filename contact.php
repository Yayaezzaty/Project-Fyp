<?php 
session_start();
include 'navigation.php';

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    exit();
}

$user_email = $_SESSION['email'];
$user_name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Jenny RideCare Center</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary-color: #e63946; /* Red color for motorcycle theme */
            --secondary-color: #f8f9fa;
            --text-color: #333;
            --light-gray: #e9ecef;
            --dark-color: #1d3557;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        
        
        header h1 {
            margin: 0;
            font-size: 2.5rem;
            text-transform: uppercase;
        }
        
        header p {
            font-size: 1.2rem;
            margin-top: 0.5rem;
        }
        
        .contact-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        
        .contact-form, .contact-info {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .contact-form h2, .contact-info h2 {
            color: var(--primary-color);
            margin-top: 0;
            font-size: 1.8rem;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 0.5rem;
            display: inline-block;
        }
        
        .contact-info p {
            margin-bottom: 1.5rem;
        }
        
        .contact-info i {
            color: var(--primary-color);
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        input, textarea, select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid var(--light-gray);
            border-radius: 4px;
            margin-bottom: 1rem;
            font-family: inherit;
        }
        
        textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        select {
            background-color: white;
        }
        
        button[type="submit"] {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
            text-transform: uppercase;
            font-weight: bold;
        }
        
        button[type="submit"]:hover {
            background-color: var(--dark-color);
            transform: translateY(-2px);
        }
        
        .service-options {
            margin-bottom: 1.5rem;
        }
        
        .map-container {
            margin-top: 2rem;
            height: 300px;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        @media (max-width: 768px) {
            .contact-container {
                grid-template-columns: 1fr;
            }
            
            header {
                padding: 2rem 0;
            }
            
            header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Contact Jenny RideCare Center</h1>
        <p>We're here to help with all your motorcycle service needs</p>
    </header>
    
    <main class="contact-container">
        <section class="contact-form">
            <h2>Send Us a Message</h2>
            <p>Do you have a problem with the services? Fill out the form below and we'll get back to you within 24 hours.</p>
            
            <form action="process_contact.php" method="POST">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($user_email); ?>">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($user_name); ?>">
                
                
                <label for="message">Your Message</label>
                <textarea id="message" name="message" placeholder="Please describe your motorcycle issue or request in detail..." required></textarea>
                
                <button type="submit">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
            </form>
        </section>
        
        <section class="contact-info">
            <h2>Workshop Information</h2>
            <p><i class="fas fa-envelope"></i> <strong>Email:</strong> JennyWorkshop93@gmail.com</p>
            <p><i class="fas fa-phone"></i> <strong>Phone:</strong> 010-8446445</p>
            <p><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> Felda Air Tawar 5, 81900 Kota Tinggi, Johor</p>
            <p><i class="fas fa-clock"></i> <strong>Workshop Hours:</strong><br>
            Monday-saturday: 9:00am-6:00pm<br>
            Sunday: 9:00am-2:00pmpm<br>
            </p>
            
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127482.7283250448!2d103.8994147!3d1.7380414!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da3dc871835a6b%3A0x8a4b4c1e0f8b3b1a!2sFelda%20Air%20Tawar%205%2C%2081900%20Kota%20Tinggi%2C%20Johor!5e0!3m2!1sen!2smy!4v1712480000000!5m2!1sen!2smy" allowfullscreen="" loading="lazy"></iframe>
            </div>
            
            <div class="social-links" style="margin-top: 2rem;">
                <h3>Connect With Us</h3>
                <a href="https://facebook.com/jennyridecare" style="color: var(--primary-color); margin-right: 1rem; font-size: 1.5rem;"><i class="fab fa-facebook"></i></a>
                <a href="https://instagram.com/jennyridecare" style="color: var(--primary-color); margin-right: 1rem; font-size: 1.5rem;"><i class="fab fa-instagram"></i></a>
                <a href="https://wa.me/60123456789" style="color: var(--primary-color); margin-right: 1rem; font-size: 1.5rem;"><i class="fab fa-whatsapp"></i></a>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>