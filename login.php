<?php
session_start();
include "db_connection.php"; // Ensure database connection is included

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // First, check in the admin table
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $role = "admin";
    } else {
        // If not found in admin, check users table
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $role = "user";
        } else {
            echo "<script>alert('User not found!'); window.location.href='login.php';</script>";
            exit();
        }
    }

    // Verify hashed password
    if (password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["name"] = $user["name"];
        $_SESSION["role"] = $role;

        // Redirect based on role
        if ($role === "admin") {
            header("Location: admin.php");
        } else {
            header("Location: homepage.php");
        }
        exit();
    } else {
        echo "<script>alert('Invalid password!'); window.location.href='login.php';</script>";
    }
}
?>
