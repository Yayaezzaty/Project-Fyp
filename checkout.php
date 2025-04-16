<?php
session_start();
require_once("db_connection.php");

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cartItems = [];
$totalPrice = 0;

// Fetch cart items
$query = "SELECT p.product_id, p.name, p.price, p.image, c.quantity, (p.price * c.quantity) AS total
          FROM cart c
          JOIN products p ON c.product_id = p.product_id
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
    $totalPrice += $row['total'];
}

if (empty($cartItems)) {
    header("Location: cart.php");
    exit();
}

// Handle checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
    $payment_method = trim($_POST['payment_method']);
    
    // Validate payment method
    $allowed_methods = ['Bank Transfer', 'Touch \'n Go eWallet', 'Shopee Pay', 'Cash On Delivery'];
    if (empty($payment_method) || !in_array($payment_method, $allowed_methods)) {
        $_SESSION['error'] = "Please select a valid payment method.";
        header("Location: checkout.php");
        exit();
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Create order
        $order_date = date('Y-m-d H:i:s');
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, order_date, payment_method, status) 
                              VALUES (?, ?, ?, ?, 'pending')");
        $stmt->bind_param("idss", $user_id, $totalPrice, $order_date, $payment_method);
        $stmt->execute();
        $order_id = $conn->insert_id;

        // Add order items
        foreach ($cartItems as $item) {
            $stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) 
                                  VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
            $stmt->execute();
            
            // Update stock
            $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE product_id = ?");
            $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
            $stmt->execute();
        }

        // Clear cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $conn->commit();
        header("Location: order_success.php?order_id=$order_id");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error processing your order: " . $e->getMessage();
        header("Location: checkout.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Jenny Ride Care Center</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #e74c3c;
            --accent: #3498db;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --success: #27ae60;
            --danger: #e74c3c;
            --border-radius: 8px;
            --box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        
        .checkout-container {
            max-width: 1000px;
            margin: 2rem auto;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }
        
        .checkout-header {
            padding: 1.5rem;
            background: var(--primary);
            color: white;
            text-align: center;
        }
        
        .checkout-header h2 {
            margin: 0;
            font-size: 1.8rem;
        }
        
        .checkout-body {
            display: flex;
            padding: 0;
        }
        
        .order-summary, .payment-section {
            flex: 1;
            padding: 1.5rem;
        }
        
        .order-summary {
            background: var(--light);
            border-right: 1px solid #ddd;
        }
        
        h3 {
            color: var(--primary);
            margin-bottom: 1.5rem;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .cart-item {
            display: flex;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #ddd;
        }
        
        .cart-item:last-child {
            border-bottom: none;
        }
        
        .cart-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: var(--border-radius);
            margin-right: 1rem;
        }
        
        .cart-item-details {
            flex: 1;
        }
        
        .cart-item-name {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .cart-item-price {
            color: var(--secondary);
            font-weight: 600;
        }
        
        .cart-item-quantity {
            color: #666;
            font-size: 0.9rem;
        }
        
        .summary-total {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--primary);
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
        }
        
        .total-label {
            font-weight: 600;
        }
        
        .total-amount {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .payment-method-option {
            margin: 1rem 0;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
        }
        
        .payment-method-option:hover {
            border-color: var(--accent);
            box-shadow: var(--box-shadow);
        }
        
        .payment-method-option input[type="radio"] {
            display: none;
        }
        
        .payment-method-option input[type="radio"]:checked + .payment-content {
            border-left: 4px solid var(--accent);
            padding-left: 0.8rem;
        }
        
        .payment-content {
            display: flex;
            align-items: center;
            transition: var(--transition);
        }
        
        .payment-icon {
            font-size: 1.8rem;
            margin-right: 1rem;
            color: var(--primary);
            width: 40px;
            text-align: center;
        }
        
        .payment-title {
            font-weight: 600;
            margin-bottom: 0.3rem;
            color: var(--primary);
        }
        
        .payment-description {
            color: #666;
            font-size: 0.9rem;
        }
        
        .checkout-btn {
            width: 100%;
            padding: 1rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1.5rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .checkout-btn:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }
        
        .error-message {
            color: var(--danger);
            padding: 1rem;
            margin: 0 1.5rem 1.5rem;
            background: rgba(231, 76, 60, 0.1);
            border-radius: var(--border-radius);
            display: <?php echo isset($_SESSION['error']) ? 'flex' : 'none'; ?>;
            align-items: center;
            gap: 0.5rem;
        }
        
        .divider {
            height: 1px;
            background: #ddd;
            margin: 1.5rem 0;
        }
        
        @media (max-width: 768px) {
            .checkout-body {
                flex-direction: column;
            }
            
            .order-summary {
                border-right: none;
                border-bottom: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
    <?php include 'navigation.php'; ?>

    <div class="checkout-container">
        <div class="checkout-header">
            <h2><i class="fas fa-shopping-cart"></i> Checkout</h2>
        </div>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> 
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>
        
        <div class="checkout-body">
            <div class="order-summary">
                <h3><i class="fas fa-list"></i> Order Summary</h3>
                
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item">
                        <img src="images/products/<?= htmlspecialchars($item['image']) ?>" 
                             alt="<?= htmlspecialchars($item['name']) ?>" 
                             class="cart-item-image">
                        <div class="cart-item-details">
                            <div class="cart-item-name"><?= htmlspecialchars($item['name']) ?></div>
                            <div class="cart-item-price">RM<?= number_format($item['price'], 2) ?></div>
                            <div class="cart-item-quantity">Quantity: <?= $item['quantity'] ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="divider"></div>
                
                <div class="summary-total">
                    <div class="total-row">
                        <span class="total-label">Subtotal:</span>
                        <span>RM<?= number_format($totalPrice, 2) ?></span>
                    </div>
                    <div class="total-row">
                        <span class="total-label">Shipping:</span>
                        <span>FREE</span>
                    </div>
                    <div class="total-row total-amount-row">
                        <span class="total-label">Total:</span>
                        <span class="total-amount">RM<?= number_format($totalPrice, 2) ?></span>
                    </div>
                </div>
            </div>
            
            <div class="payment-section">
                <h3><i class="fas fa-credit-card"></i> Payment Method</h3>
                
                <form method="post">
                    <label class="payment-method-option">
                        <input type="radio" name="payment_method" value="Bank Transfer" required> 
                        <div class="payment-content">
                            <div class="payment-icon"><i class="fas fa-university"></i></div>
                            <div class="payment-details">
                                <div class="payment-title">Bank Transfer</div>
                                <div class="payment-description">Transfer to our Maybank account: 1234-5678-9012</div>
                            </div>
                        </div>
                    </label>
                    
                    <label class="payment-method-option">
                        <input type="radio" name="payment_method" value="Touch 'n Go eWallet"> 
                        <div class="payment-content">
                            <div class="payment-icon"><i class="fas fa-mobile-alt"></i></div>
                            <div class="payment-details">
                                <div class="payment-title">Touch 'n Go eWallet</div>
                                <div class="payment-description">Send payment to: 012-3456789</div>
                            </div>
                        </div>
                    </label>
                    
                    <label class="payment-method-option">
                        <input type="radio" name="payment_method" value="Shopee Pay"> 
                        <div class="payment-content">
                            <div class="payment-icon"><i class="fas fa-wallet"></i></div>
                            <div class="payment-details">
                                <div class="payment-title">Shopee Pay</div>
                                <div class="payment-description">Send payment to: shopee@jennyride.com</div>
                            </div>
                        </div>
                    </label>
                    
                    <label class="payment-method-option">
                        <input type="radio" name="payment_method" value="Cash On Delivery"> 
                        <div class="payment-content">
                            <div class="payment-icon"><i class="fas fa-money-bill-wave"></i></div>
                            <div class="payment-details">
                                <div class="payment-title">Cash On Delivery</div>
                                <div class="payment-description">Pay when you receive your products</div>
                            </div>
                        </div>
                    </label>

                    <button type="submit" class="checkout-btn">
                        <i class="fas fa-lock"></i> Complete Order
                    </button>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>