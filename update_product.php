<?php
include 'db_connection.php';

// Step 1: Get Product ID from URL and Validate
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = intval($_GET['id']);
    
    $query = "SELECT * FROM products WHERE product_id = $product_id";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        echo "<script>alert('Product not found!'); window.location.href='manage_products.php';</script>";
        exit();
    }

    $product = mysqli_fetch_assoc($result);
} else {
    echo "<script>alert('Invalid product selection!'); window.location.href='manage_products.php';</script>";
    exit();
}

// Step 2: Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $updateQuery = "UPDATE products SET name='$name', price='$price', quantity='$quantity', description='$description' WHERE product_id=$product_id";
    
    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Product updated successfully!'); window.location.href='admin_product.php';</script>";
        exit();
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="stylesAdP.css">
</head>
<body>
    <?php include 'admin_navigation.php'; ?>

    <div class="admin-container">
        <h1>Edit Product</h1>
        <form method="POST">
            <label>Product Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

            <label>Price:</label>
            <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>

            <label>Quantity:</label>
            <input type="number" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>

            <label>Description:</label>
            <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

            <input type="submit" value="Update Product">
        </form>
    </div>
</body>
</html>
