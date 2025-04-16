<?php
session_start();
include 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT name, email, phone FROM user WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $phone);
$stmt->fetch();
$stmt->close();

// Fetch user bookings
$bookings = [];
$query = "SELECT service, booking_date, status FROM bookings WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}
$stmt->close();

// Fetch user orders with product names
$orders = [];
$query = "SELECT od.product_id, p.name, od.quantity, od.price, o.order_date
          FROM orders o
          JOIN order_details od ON o.order_id = od.order_id
          JOIN products p ON od.product_id = p.product_id
          WHERE o.customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .user-info {
            text-align: center;
            padding: 20px;
            background: #007bff;
            color: white;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }
        .btn {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .btn:hover {
            background: #0056b3;
        }
        .no-data {
            text-align: center;
            color: #777;
            padding: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="user-info">
        <h2>Welcome, <?php echo htmlspecialchars($name); ?></h2>
        <p>Email: <?php echo htmlspecialchars($email); ?></p>
        <p>Phone: <?php echo htmlspecialchars($phone); ?></p>
    </div>

    <div class="section">
        <h3>Your Bookings</h3>
        <?php if (!empty($bookings)): ?>
            <table>
                <tr>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['service']); ?></td>
                        <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                        <td><?php echo htmlspecialchars($booking['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p class="no-data">No bookings yet.</p>
        <?php endif; ?>
    </div>

    <div class="section">
        <h3>Your Orders</h3>
        <?php if (!empty($orders)): ?>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price (RM)</th>
                    <th>Order Date</th>
                </tr>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($order['price']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p class="no-data">No orders yet.</p>
        <?php endif; ?>
    </div>

    <div style="text-align: center;">
        <a href="logout.php" class="btn">Logout</a>
    </div>
</div>

</body>
</html>
