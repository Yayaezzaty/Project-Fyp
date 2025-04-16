<?php
session_start();
include 'db_connect.php'; // Your database connection

echo "<h2>Your Cart</h2>";

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<table border='1'>
            <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";
    
    $total_price = 0;
    foreach ($_SESSION['cart'] as $id => $item) {
        $product_id = $item['product_id'];
        $result = $conn->query("SELECT name FROM products WHERE product_id = $product_id");
        $product = $result->fetch_assoc();

        $subtotal = $item['price'] * $item['quantity'];
        $total_price += $subtotal;

        echo "<tr>
                <td>{$product['name']}</td>
                <td>\${$item['price']}</td>
                <td>{$item['quantity']}</td>
                <td>\$$subtotal</td>
            </tr>";
    }
    echo "</table>";
    echo "<p><strong>Total Price:</strong> \$$total_price</p>";
}

echo '<a href="checkout.php">Proceed to Checkout</a>';
?>
