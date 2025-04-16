<?php
session_start();
include("db_connection.php");

// Debugging: Check if session is working
error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'not set'));

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'Please login first']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)($_POST['quantity'] ?? 1);

    // Check if product exists
    $check = $conn->prepare("SELECT product_id FROM products WHERE product_id = ?");
    $check->bind_param("i", $product_id);
    $check->execute();
    
    if (!$check->get_result()->num_rows) {
        die(json_encode(['success' => false, 'message' => 'Product not found']));
    }

    // Insert or update cart
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) 
                          VALUES (?, ?, ?) 
                          ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)");
    $stmt->bind_param("iii", $_SESSION['user_id'], $product_id, $quantity);
    
    if ($stmt->execute()) {
        error_log("Cart updated for user: " . $_SESSION['user_id'] . ", product: $product_id");
        echo json_encode(['success' => true, 'message' => 'Added to cart']);
    } else {
        error_log("Database error: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>