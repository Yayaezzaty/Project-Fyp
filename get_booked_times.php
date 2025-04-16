<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ride_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$date = $_GET["date"];
$sql = "SELECT booking_time FROM bookings WHERE booking_date = '$date'";
$result = $conn->query($sql);

$bookedTimes = [];
while ($row = $result->fetch_assoc()) {
    $bookedTimes[] = $row["booking_time"];
}

echo json_encode($bookedTimes);
$conn->close();
?>
