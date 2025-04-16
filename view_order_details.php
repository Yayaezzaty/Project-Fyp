<?php
include 'db_connection.php'; // Database connection

// Check if 'id' is set in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid Order ID");
}

$order_id = $_GET['id'];

// Fetch order details
$sql = "SELECT o.order_id, u.name AS customer_name, u.email, u.phone, 
               o.total_amount, o.payment_method, o.status, o.created_at
        FROM orders o
        JOIN users u ON o.user_id = u.user_id
        WHERE o.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

// Fetch order items
$sql_items = "SELECT p.name AS product_name, od.quantity, od.price 
              FROM order_details od
              JOIN products p ON od.product_id = p.product_id
              WHERE od.order_id = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items_result = $stmt_items->get_result();

$stmt->close();
$stmt_items->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            display: inline-block;
            text-align: left;
            width: 50%;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            display: block;
            text-align: center;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Order Details (ID: <?= $order['order_id']; ?>)</h2>
    <p><strong>Customer:</strong> <?= $order['customer_name']; ?></p>
    <p><strong>Email:</strong> <?= $order['email']; ?></p>
    <p><strong>Phone:</strong> <?= $order['phone']; ?></p>
    <p><strong>Order Date:</strong> <?= $order['created_at']; ?></p>
    <p><strong>Payment Method:</strong> <?= $order['payment_method']; ?></p>
    <p><strong>Status:</strong> <?= $order['status']; ?></p>

    <h3>Order Items</h3>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price (RM)</th>
        </tr>
        <?php while ($item = $items_result->fetch_assoc()): ?>
            <tr>
                <td><?= $item['product_name']; ?></td>
                <td><?= $item['quantity']; ?></td>
                <td>RM <?= number_format($item['price'], 2); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <p><strong>Total Amount:</strong> RM <?= number_format($order['total_amount'], 2); ?></p>

    <a href="admin_order.php" class="back-btn">Back to Orders</a>
</div>

</body>
</html>
