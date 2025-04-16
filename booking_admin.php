<?php
include 'db_connection.php';
session_start();

// Fetch all bookings from the database
$sql = "SELECT id, name, email, phone, service, booking_date, booking_time, created_at, status FROM bookings ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Bookings - Jenny Ride Care</title>
    <link rel="stylesheet" href="AdBook.css?v=<?php echo time(); ?>"> <!-- Forces latest CSS -->
</head>
<body>

<!-- Admin Navigation -->
<?php include 'admin_navigation.php'; ?>

<div class="container">
    <h2>Bookings Management</h2>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Service</th>
                <th>Booking Date</th>
                <th>Booking Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row["id"]; ?></td>
                <td><?= htmlspecialchars($row["name"]); ?></td>
                <td><?= htmlspecialchars($row["email"]); ?></td>
                <td><?= htmlspecialchars($row["phone"]); ?></td>
                <td><?= htmlspecialchars($row["service"]); ?></td>
                <td><?= $row["booking_date"]; ?></td>
                <td><?= $row["booking_time"]; ?></td>
                <td>
                    <form action="update_booking.php" method="POST">
                        <input type="hidden" name="booking_id" value="<?= $row['id']; ?>">
                        <select name="status" onchange="this.form.submit()">
                            <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Confirmed" <?= $row['status'] == 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                            <option value="Completed" <?= $row['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="Canceled" <?= $row['status'] == 'Canceled' ? 'selected' : ''; ?>>Canceled</option>
                        </select>
                    </form>
                </td>
                <td><a class="details-btn" href="view_booking_details.php?booking_id=<?= $row['id']; ?>">View Details</a></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
