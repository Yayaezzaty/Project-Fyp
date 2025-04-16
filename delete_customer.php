<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    $query = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Customer deleted successfully!'); window.location.href='admin_customer.php';</script>";
    } else {
        echo "<script>alert('Failed to delete customer!');</script>";
    }
    $stmt->close();
}
?>
