<?php
include 'db_connection.php';

if (isset($_GET['booking_id'])) {
    $booking_id = intval($_GET['booking_id']);

    $query = "SELECT * FROM bookings WHERE id = $booking_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $booking = mysqli_fetch_assoc($result);
    } else {
        echo "<p>Booking not found.</p>";
        exit;
    }
} else {
    echo "<p>Invalid request.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background: white;
            width: 50%;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        h2 {
            color: #333;
            text-align: center;
            font-size: 28px;
            margin-bottom: 15px;
        }
        p {
            font-size: 16px;
            color: #555;
            margin: 8px 0;
        }
        strong {
            color: #222;
        }
        .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }
        .pending { background-color: #fce205; color: #7a6500; }
        .canceled { background-color: #ff4d4d; color: white; }
        .confirmed { background-color: #4caf50; color: white; }
        .back-btn {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            transition: 0.3s;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Booking Details</h2>
        <p><strong>Customer Name:</strong> <?= htmlspecialchars($booking['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($booking['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($booking['phone']) ?></p>
        <p><strong>Service:</strong> <?= htmlspecialchars($booking['service']) ?></p>
        <p><strong>Booking Date:</strong> <?= htmlspecialchars($booking['booking_date']) ?></p>
        <p><strong>Booking Time:</strong> <?= htmlspecialchars($booking['booking_time']) ?></p>
        <p><strong>Status:</strong> 
            <span class="status <?= strtolower($booking['status']) ?>">
                <?= htmlspecialchars($booking['status']) ?>
            </span>
        </p>
        <a href="booking_admin.php" class="back-btn">⬅ Back to Bookings</a>
    </div>
</body>
</html>
