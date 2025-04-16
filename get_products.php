<?php
header('Content-Type: application/json');
$host = "localhost"; // Change if needed
$user = "root"; // Your database username
$pass = ""; // Your database password (leave empty if none)
$dbname = "ride_db"; // Your database name

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Correct SQL Query
$sql = "SELECT product_id AS id, name, price, description, category, discount FROM products";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode($products);
} else {
    echo json_encode([]);
}

$conn->close();
?>
