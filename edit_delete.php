<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $customer = $result->fetch_assoc();
    } else {
        echo "<script>alert('Customer not found!'); window.location.href='admin_customer.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href='admin_customer.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $update_query = "UPDATE users SET name=?, email=?, phone=?, address=? WHERE user_id=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Customer updated successfully!'); window.location.href='admin_customer.php';</script>";
    } else {
        echo "<script>alert('Failed to update customer!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-save {
            background-color: #28a745;
            color: white;
        }
        .btn-save:hover {
            background-color: #218838;
        }
        .btn-back {
            background-color: #007bff;
            color: white;
            text-align: center;
            display: block;
            margin-top: 10px;
            text-decoration: none;
            padding: 10px;
            border-radius: 5px;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Edit Customer</h2>
        <form method="POST">
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($customer['name']); ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($customer['email']); ?>" required>

            <label>Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($customer['phone']); ?>" required>

            <label>Address</label>
            <input type="text" name="address" value="<?= htmlspecialchars($customer['address']); ?>" required>

            <button type="submit" class="btn btn-save">Save Changes</button>
        </form>
        <a href="admin_customer.php" class="btn-back">⬅ Back to Customers</a>
    </div>

</body>
</html>
