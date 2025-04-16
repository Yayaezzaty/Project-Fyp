<?php
session_start();
include 'db_connection.php'; // Ensure this file exists

if (!isset($_SESSION['user_id'])) {
    echo "Please login first.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Check current password
    $result = $conn->query("SELECT password FROM users WHERE user_id='$user_id'");
    $user = $result->fetch_assoc();

    if (!password_verify($current_password, $user['password'])) {
        echo "<script>alert('Incorrect current password!');</script>";
    } else {
        // Update password
        $conn->query("UPDATE users SET password='$new_password' WHERE user_id='$user_id'");
        echo "<script>alert('Password updated successfully!'); window.location.href='user_account.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        .btn-orange { background: #e67e22; color: white; border: none; }
        .btn-orange:hover { background: #d35400; }
    </style>
</head>
<body>

<div class="container">
    <h3 class="text-center text-orange">Change Password</h3>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Current Password:</label>
            <input type="password" name="current_password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">New Password:</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-orange w-100">Change Password</button>
        <a href="user_account.php" class="btn btn-secondary w-100 mt-2">Back to My Account</a>
    </form>
</div>

</body>
</html>
