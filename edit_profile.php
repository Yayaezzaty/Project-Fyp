<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo "Please login first.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$result = $conn->query("SELECT * FROM users WHERE user_id = '$user_id'");
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $target_file = $user['profile_image']; // Default to current profile image

    // Handle profile image upload
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = "uploads/";

        // Check if uploads folder exists, if not create it
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $image_name = time() . "_" . basename($_FILES['profile_image']['name']);
        $target_file = $target_dir . $image_name;

        if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            echo "<script>alert('Error uploading image!');</script>";
            $target_file = $user['profile_image']; // Keep old image if upload fails
        }
    }

    // **Fix SQL Error: Use Prepared Statement**
    $sql = "UPDATE users SET name=?, email=?, phone=?, address=?, profile_image=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $name, $email, $phone, $address, $target_file, $user_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='user_account.php';</script>";
    } else {
        echo "<script>alert('Error updating profile!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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
        .profile-img { width: 100px; height: 100px; object-fit: cover; border-radius: 50%; }
    </style>
</head>
<body>

<div class="container">
    <h3 class="text-center text-orange">Edit Profile</h3>

    <form method="POST" enctype="multipart/form-data">
        <div class="text-center">
            <img src="<?php echo $user['profile_image'] ?: 'profile-placeholder.png'; ?>" class="profile-img" alt="Profile Image">
            <input type="file" name="profile_image" class="form-control mt-2">
        </div>

        <div class="mb-3">
            <label class="form-label">Name:</label>
            <input type="text" name="name" class="form-control" value="<?php echo $user['name']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone:</label>
            <input type="text" name="phone" class="form-control" value="<?php echo $user['phone']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Address:</label>
            <textarea name="address" class="form-control" required><?php echo $user['address']; ?></textarea>
        </div>

        <button type="submit" class="btn btn-orange w-100">Update Profile</button>
        <a href="user_account.php" class="btn btn-secondary w-100 mt-2">Back to My Account</a>
    </form>
</div>

</body>
</html>