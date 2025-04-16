<?php
session_start();
include 'db_connection.php'; // Ensure database connection is included

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
    exit;
}

if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    echo json_encode(["success" => false, "error" => "Missing parameters"]);
    exit;
}

$productId = $_POST['product_id'];
$newQuantity = (int)$_POST['quantity'];

// Debugging: Log session cart data
error_log("Session Cart Data: " . print_r($_SESSION['cart'], true));

if (!isset($_SESSION['cart'][$productId])) {
    echo json_encode(["success" => false, "error" => "Product not found in cart"]);
    exit;
}

// Update quantity in session
$_SESSION['cart'][$productId]['quantity'] = $newQuantity;

$price = $_SESSION['cart'][$productId]['price'];
$discount = $_SESSION['cart'][$productId]['discount'];
$discountedPrice = $price - ($price * ($discount / 100));
$newSubtotal = $discountedPrice * $newQuantity;

echo json_encode(["success" => true, "newSubtotal" => number_format($newSubtotal, 2)]);
?>
