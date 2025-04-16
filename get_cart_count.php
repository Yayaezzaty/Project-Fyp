<?php
session_start();
header('Content-Type: application/json');

include("db_connection.php");

echo json_encode(["success" => true, "cart_count" => (int)$row['cart_count']]);


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT COUNT(*) AS cart_count FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $response['cart_count'] = $row['cart_count'];
        $response['success'] = true;
    }

    $stmt->close();
}

echo json_encode($response);
?>
