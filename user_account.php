<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Fetch loyalty points
$points_stmt = $conn->prepare("SELECT total_points FROM loyalty_points WHERE user_id = ?");
$points_stmt->bind_param("i", $user_id);
$points_stmt->execute();
$points_result = $points_stmt->get_result();
$points_data = $points_result->fetch_assoc();
$total_points = $points_data ? $points_data['total_points'] : 0;

// Calculate loyalty level
$loyalty_level = floor($total_points / 100); // Each level requires 100 points
$next_level_points = 100 - ($total_points % 100);

// Fetch order history
$order_stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$orders = $order_stmt->get_result();

// Get user profile image
$profile_image = !empty($user['profile_image']) ? $user['profile_image'] : 'assets/default-profile.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Jenny Ride Care Center</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #1e3a5f;
            --primary-orange: #ff7f00;
            --dark-blue: #0d253f;
            --light-orange: #ff9e40;
            --light-gray: #f8f9fa;
        }
        
        body {
            background-color: var(--light-gray);
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .account-container {
            max-width: 1200px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        }
        
        .profile-section {
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            color: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .profile-img {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid var(--primary-orange);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }
        
        .profile-img:hover {
            transform: scale(1.05);
        }
        
        .loyalty-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-left: 5px solid var(--primary-orange);
            margin-bottom: 30px;
        }
        
        .progress {
            height: 15px;
            border-radius: 8px;
            background-color: #e9ecef;
        }
        
        .progress-bar {
            background: linear-gradient(90deg, var(--primary-orange), var(--light-orange));
            border-radius: 8px;
        }
        
        .badge-level {
            background: var(--primary-orange);
            font-size: 0.9rem;
            padding: 5px 10px;
            border-radius: 20px;
        }
        
        .order-card {
            background: white;
            border-radius: 10px;
            padding: 0;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 15px;
        }
        
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        
        .order-header {
            background: var(--primary-blue);
            color: white;
            padding: 12px 15px;
            border-radius: 10px 10px 0 0;
        }
        
        .btn-primary-custom {
            background: var(--primary-orange);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary-custom:hover {
            background: var(--dark-blue);
            transform: translateY(-2px);
        }
        
        .btn-outline-custom {
            border: 2px solid var(--primary-orange);
            color: var(--primary-orange);
            background: transparent;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-outline-custom:hover {
            background: var(--primary-orange);
            color: white;
        }
        
        .section-title {
            color: var(--primary-blue);
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 60px;
            height: 3px;
            background: var(--primary-orange);
        }
        
        .loyalty-benefits {
            margin-top: 20px;
        }
        
        .benefit-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .benefit-icon {
            color: var(--primary-orange);
            margin-right: 10px;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

<?php include 'navigation.php'; ?>

<div class="account-container">
    <h2 class="text-center mb-4 section-title">My Account Dashboard</h2>
    
    <!-- Profile Section -->
    <div class="profile-section">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                <img src="<?php echo $profile_image; ?>" class="profile-img" alt="Profile Image">
                <a href="edit_profile.php" class="btn btn-primary-custom mt-3 w-100">
                    <i class="fas fa-user-edit me-2"></i>Edit Profile
                </a>
            </div>
            <div class="col-md-9">
                <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                <p class="mb-1"><i class="fas fa-envelope me-2"></i> <?php echo htmlspecialchars($user['email']); ?></p>
                <p class="mb-1"><i class="fas fa-phone me-2"></i> <?php echo htmlspecialchars($user['phone']); ?></p>
                <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i> <?php echo htmlspecialchars($user['address']); ?></p>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <a href="change_password.php" class="btn btn-outline-custom">
                        <i class="fas fa-lock me-2"></i>Change Password
                    </a>
                    <button onclick="confirmLogout()" class="btn btn-outline-light">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Loyalty Program Section -->
    <div class="loyalty-card">
        <h4 class="section-title">Loyalty Program</h4>
        
        <div class="row">
            <div class="col-md-6">
                <div class="d-flex justify-content-between mb-2">
                    <span>Level <?php echo $loyalty_level; ?> Member</span>
                    <span class="badge badge-level"><?php echo $total_points; ?> Points</span>
                </div>
                
                <div class="progress mb-3">
                    <div class="progress-bar" role="progressbar" 
                         style="width: <?php echo ($total_points % 100); ?>%;" 
                         aria-valuenow="<?php echo ($total_points % 100); ?>" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                    </div>
                </div>
                
                <p class="text-muted">
                    <i class="fas fa-info-circle me-2"></i>
                    <?php echo $next_level_points; ?> more points to reach Level <?php echo ($loyalty_level + 1); ?>
                </p>
            </div>
            
            <div class="col-md-6 loyalty-benefits">
                <h5><i class="fas fa-award me-2"></i>Your Benefits:</h5>
                <div class="benefit-item">
                    <span class="benefit-icon"><i class="fas fa-check-circle"></i></span>
                    <span>5% discount on all services</span>
                </div>
                <div class="benefit-item">
                    <span class="benefit-icon"><i class="fas fa-check-circle"></i></span>
                    <span>Priority booking</span>
                </div>
                <?php if ($loyalty_level >= 1): ?>
                <div class="benefit-item">
                    <span class="benefit-icon"><i class="fas fa-check-circle"></i></span>
                    <span>Free basic inspection (Level 1+)</span>
                </div>
                <?php endif; ?>
                <?php if ($loyalty_level >= 2): ?>
                <div class="benefit-item">
                    <span class="benefit-icon"><i class="fas fa-check-circle"></i></span>
                    <span>10% discount (Level 2+)</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="loyalty_info.php" class="btn btn-outline-custom">
                <i class="fas fa-question-circle me-2"></i>How to earn more points?
            </a>
        </div>
    </div>
    
    <!-- Order History -->
    <h4 class="section-title mt-5">Order History</h4>
    
    <?php if ($orders->num_rows > 0): ?>
        <div class="row">
            <?php while ($order = $orders->fetch_assoc()): ?>
            <div class="col-md-6 mb-3">
                <div class="order-card">
                    <div class="order-header d-flex justify-content-between">
                        <span>Order #<?php echo $order['order_id']; ?></span>
                        <span><?php echo date("M d, Y", strtotime($order['order_date'])); ?></span>
                    </div>
                    <div class="p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Status:</strong>
                            <span class="badge bg-<?php 
                                echo $order['status'] == 'Completed' ? 'success' : 
                                     ($order['status'] == 'Processing' ? 'warning' : 'secondary'); 
                            ?>">
                                <?php echo $order['status']; ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Total:</strong>
                            <span>RM<?php echo number_format($order['total_amount'], 2); ?></span>
                        </div>
                        <a href="order_details.php?id=<?php echo $order['order_id']; ?>" class="btn btn-primary-custom btn-sm w-100">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> You haven't placed any orders yet.
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmLogout() {
    Swal.fire({
        title: 'Logout Confirmation',
        text: 'Are you sure you want to logout?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ff7f00',
        cancelButtonColor: '#1e3a5f',
        confirmButtonText: 'Yes, logout'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "logout.php";
        }
    });
}

// Add this to your loyalty points system (server-side)
/*
// When a user completes an order (in your order processing script):
$points_to_add = round($order_total); // 1 point per RM1 spent
$conn->query("INSERT INTO loyalty_points (user_id, points_earned, transaction_id) 
              VALUES ($user_id, $points_to_add, $order_id) 
              ON DUPLICATE KEY UPDATE total_points = total_points + $points_to_add");

// Create the loyalty_points table if it doesn't exist:
CREATE TABLE loyalty_points (
    user_id INT PRIMARY KEY,
    total_points INT DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

// For transaction history:
CREATE TABLE loyalty_transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    points_earned INT,
    order_id INT,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);
*/
</script>
</body>
</html>