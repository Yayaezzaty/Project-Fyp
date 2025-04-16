<?php
// This MUST be the very first line with no whitespace before it
session_start();

// Then include your navigation
include 'navigation.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - Jenny Ride Care Center</title>
    <style>
        /* Base Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        /* Main Content Styles */
        .booking-container {
            max-width: 700px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        h2 {
            text-align: center;
            color: #1e3a5f;
            margin-bottom: 20px;
        }
        
        /* Form Steps */
        .steps {
            display: flex;
            justify-content: center;
            margin: 25px 0;
        }
        
        .step {
            width: 20px;
            height: 20px;
            background: #ddd;
            border-radius: 50%;
            margin: 0 10px;
            transition: all 0.3s ease;
        }
        
        .step.active {
            background: #ff9800;
        }
        
        /* Form Elements */
        .form-step {
            display: none;
        }
        
        .form-step.active {
            display: block;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }
        
        input:focus, select:focus {
            border-color: #ff9800;
            outline: none;
        }
        
        label {
            display: block;
            margin-top: 15px;
            color: #1e3a5f;
            font-weight: 500;
        }
        
        /* Buttons */
        .btn {
            background: #ff9800;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            margin-top: 15px;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background: #e67e22;
        }
        
        .btn.prev {
            background: #6c757d;
            margin-right: 10px;
        }
        
        /* Review Section */
        #reviewName, #reviewEmail, 
        #reviewPhone, #reviewService, 
        #reviewDateTime {
            font-weight: normal;
            color: #666;
        }
        
        /* Footer */
        footer {
            background: #1e3a5f;
            color: white;
            text-align: center;
            padding: 30px 0;
            margin-top: 50px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .booking-container {
                margin: 20px;
                padding: 20px;
            }
            
            nav ul {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }
        }
    </style>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Your HTML content here -->

    
    <div class="booking-container">
        <h2>Book Your Motorcycle Service</h2>
        <div class="steps">
            <div class="step active"></div>
            <div class="step"></div>
            <div class="step"></div>
        </div>
        <form id="bookingForm" action="submit_booking.php" method="POST">
            <div class="form-step active">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
                
                <button type="button" class="btn next">Next</button>
            </div>
            
            <div class="form-step">
                <label for="service">Service Type</label>
                <select id="service" name="service" required>
                    <option value="">-- Select a service --</option>
                    <option value="repair">Motorcycle Repair</option>
                    <option value="oil-change">Oil Change</option>
                    <option value="accessories">Accessories</option>
                </select>
                
                <label for="booking_date">Preferred Date</label>
                <input type="date" id="booking_date" name="booking_date" required>
                
                <label for="booking_time">Preferred Time</label>
                <input type="time" id="booking_time" name="booking_time" required>
                
                <div class="button-group">
                    <button type="button" class="btn prev">Previous</button>
                    <button type="button" class="btn next">Next</button>
                </div>
            </div>
            
            <div class="form-step">
                <h3>Confirm Your Booking</h3>
                <p><strong>Name:</strong> <span id="reviewName"></span></p>
                <p><strong>Email:</strong> <span id="reviewEmail"></span></p>
                <p><strong>Phone:</strong> <span id="reviewPhone"></span></p>
                <p><strong>Service:</strong> <span id="reviewService"></span></p>
                <p><strong>Date & Time:</strong> <span id="reviewDateTime"></span></p>
                
                <div class="button-group">
                    <button type="button" class="btn prev">Previous</button>
                    <button type="submit" class="btn">Confirm Booking</button>
                </div>
            </div>
        </form>
    </div>
    
    <?php include 'footer.php'; ?>

    <script>
        // Your JavaScript code here
        document.addEventListener("DOMContentLoaded", function() {
            const steps = document.querySelectorAll(".form-step");
            const stepIndicators = document.querySelectorAll(".step");
            let currentStep = 0;
            
            // Initialize by showing first step
            showStep(currentStep);
            
            // Next button functionality
            document.querySelectorAll(".next").forEach(button => {
                button.addEventListener("click", () => {
                    if (validateStep(currentStep)) {
                        if (currentStep === 1) {
                            updateReviewSection();
                        }
                        currentStep++;
                        showStep(currentStep);
                    }
                });
            });
            
            // Previous button functionality
            document.querySelectorAll(".prev").forEach(button => {
                button.addEventListener("click", () => {
                    currentStep--;
                    showStep(currentStep);
                });
            });
            
            function showStep(stepIndex) {
                // Hide all steps
                steps.forEach(step => step.classList.remove("active"));
                // Show current step
                steps[stepIndex].classList.add("active");
                
                // Update step indicators
                stepIndicators.forEach((indicator, index) => {
                    if (index <= stepIndex) {
                        indicator.classList.add("active");
                    } else {
                        indicator.classList.remove("active");
                    }
                });
            }
            
            function validateStep(stepIndex) {
                const inputs = steps[stepIndex].querySelectorAll("input, select");
                let isValid = true;
                
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        input.style.borderColor = "#ff0000";
                        isValid = false;
                    } else {
                        input.style.borderColor = "#ddd";
                    }
                });
                
                if (!isValid) {
                    alert("Please fill in all required fields.");
                }
                
                return isValid;
            }
            
            function updateReviewSection() {
                document.getElementById("reviewName").textContent = 
                    document.getElementById("name").value;
                document.getElementById("reviewEmail").textContent = 
                    document.getElementById("email").value;
                document.getElementById("reviewPhone").textContent = 
                    document.getElementById("phone").value;
                document.getElementById("reviewService").textContent = 
                    document.getElementById("service").options[document.getElementById("service").selectedIndex].text;
                document.getElementById("reviewDateTime").textContent = 
                    `${document.getElementById("booking_date").value} at ${document.getElementById("booking_time").value}`;
            }
        });
    </script>
</body>
</html>