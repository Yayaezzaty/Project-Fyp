<?php
session_start();

// Sample cart data (You can replace this with your own system)
$_SESSION['cart'] = [
    1 => ['quantity' => 2],
    2 => ['quantity' => 1]
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>RideCare - Checkout</title>
</head>
<body>
    <h1>Your Cart</h1>
    <p>Total Price: $50</p> <!-- Replace this with your actual total calculation -->
    <form action="place_order.php" method="POST">
        <button type="submit">Checkout with Stripe</button>
    </form>
</body>
</html>
