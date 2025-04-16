<?php
session_start();
require 'vendor/autoload.php';  // Ensure you installed Stripe via Composer
include("db_connection.php");

\Stripe\Stripe::setApiKey("sk_test_51R8EkVDhRU7D1IEvRP882VarQdVWZ4pv1hdD6IfFpxPxc7i3u1fuNq0MHxlXNuumTjJT6V5p6czzJxtus894jDfE003vOpBz6i"); // Replace with your secret key

// Get total price and user details
$user_id = $_SESSION['user_id'];
$totalPrice = 0;

$query = "SELECT SUM(p.price * c.quantity) as total FROM cart c 
          JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $totalPrice = $row['total'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['stripeToken'];
    
    try {
        $charge = \Stripe\Charge::create([
            'amount' => $totalPrice * 100, // Convert to cents
            'currency' => 'myr',
            'description' => 'Jenny Ride Care Center Order',
            'source' => $token,
        ]);

        // Insert into orders table
        $order_date = date('Y-m-d H:i:s');
        $payment_method = "Stripe";

        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, order_date, payment_method) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idss", $user_id, $totalPrice, $order_date, $payment_method);
        $stmt->execute();
        $order_id = $stmt->insert_id;

        // Move cart items to order details
        $cartQuery = "SELECT * FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($cartQuery);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $cartItems = $stmt->get_result();

        while ($item = $cartItems->fetch_assoc()) {
            $stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }

        // Clear cart after checkout
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        header("Location: order_success.php?order_id=$order_id");
        exit();
    } catch (\Stripe\Exception\CardException $e) {
        echo "Payment failed: " . $e->getMessage();
    }
}
?>
