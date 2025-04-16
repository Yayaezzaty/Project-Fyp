<?php
session_start();
include("db_connection.php");

// Handle POST requests (add/update/remove items)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submissions (not AJAX)
    if (isset($_POST['update'])) {
        // Update quantity
        $product_id = (int)$_POST['id'];
        $quantity = (int)$_POST['quantity'];
        
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $quantity, $_SESSION['user_id'], $product_id);
        $stmt->execute();
    } 
    elseif (isset($_POST['remove'])) {
        // Remove item
        $product_id = (int)$_POST['id'];
        
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $_SESSION['user_id'], $product_id);
        $stmt->execute();
    }
    
    // Redirect to prevent form resubmission
    header("Location: cart.php");
    exit();
}

// Fetch cart items (only if user is logged in)
$cartItems = [];
$totalPrice = 0;

if (isset($_SESSION['user_id'])) {
    $query = "SELECT p.product_id, p.name, p.price, c.quantity, 
                     (p.price * c.quantity) as total
              FROM cart c
              JOIN products p ON c.product_id = p.product_id
              WHERE c.user_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
        $totalPrice += $row['total'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart - Jenny Ride Care Center</title>
    <link rel="stylesheet" href="stylesCart.css">
    <style>
        /* Your existing CSS styles */
    </style>
</head>
<body>
<?php include 'navigation.php'; ?>

<div class="cart-container">
    <h2>Your Shopping Cart</h2>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <p>Please <a href="login.php">login</a> to view your cart.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Product</th>
                <th>Price (RM)</th>
                <th>Quantity</th>
                <th>Total (RM)</th>
                <th>Action</th>
            </tr>
            <?php if (empty($cartItems)): ?>
                <tr><td colspan="5">Your cart is empty.</td></tr>
            <?php else: ?>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= number_format($item['price'], 2) ?></td>
                        <td>
                            <form method="post" style="display: flex; gap: 5px; justify-content: center;">
                                <input type="hidden" name="id" value="<?= $item['product_id'] ?>">
                                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1">
                                <button type="submit" name="update" class="update-btn">Update</button>
                            </form>
                        </td>
                        <td><?= number_format($item['total'], 2) ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="id" value="<?= $item['product_id'] ?>">
                                <button type="submit" name="remove" class="remove-btn">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>

        <?php if (!empty($cartItems)): ?>
            <div class="cart-actions">
                <h3>Total: RM<?= number_format($totalPrice, 2) ?></h3>
<form action="checkout.php" method="post">
    <input type="hidden" name="cart_data" value="<?php echo htmlspecialchars(json_encode($cartItems)); ?>">
    <button type="submit" class="checkout-btn">Proceed to Checkout</button>
</form>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
<script>
    // Your existing JavaScript
</script>
</body>
</html>