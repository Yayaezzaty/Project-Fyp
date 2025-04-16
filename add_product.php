<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']); // Capture the category from the form

    // Handle file upload
    $imageName = $_FILES['image']['name'];
    $imageTmpName = $_FILES['image']['tmp_name'];
    $imageFolder = 'uploads/' . $imageName;

    if (move_uploaded_file($imageTmpName, $imageFolder)) {
        // Insert the product into the database
        $sql = "INSERT INTO products (name, price, quantity, description, category, image)
                VALUES ('$name', '$price', '$quantity', '$description', '$category', '$imageName')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('Product added successfully.');
                    window.location.href = 'admin_product.php'; // Redirect back to your product management page
                  </script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Failed to upload image.";
    }
}
?>
