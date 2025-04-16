<?php
include 'db_connection.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Error: No product ID received.'); window.location.href='admin_product.php';</script>";
    exit();
}

$product_id = intval($_GET['id']); // Convert to integer for security

// Prepare the delete query
$delete_query = "DELETE FROM products WHERE product_id = ?";
$stmt = mysqli_prepare($conn, $delete_query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            Swal.fire({
                title: 'Deleted!',
                text: 'Product deleted successfully!',
                icon: 'success'
            }).then(() => {
                window.location.href = 'admin_product.php';
            });
        </script>";

        // Fallback redirect in case JavaScript fails
        header("Refresh: 2; URL=admin_product.php");
        exit();
    } else {
        echo "<script>alert('Error deleting product.'); window.location.href='admin_product.php';</script>";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<script>alert('Database error.'); window.location.href='admin_product.php';</script>";
}

mysqli_close($conn);
?>
