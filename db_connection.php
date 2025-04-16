<?php
$host = "localhost";
$username = "root";  // Change if needed
$password = "";  // Change if needed
$database = "ride_db"; // Change to your database name

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
