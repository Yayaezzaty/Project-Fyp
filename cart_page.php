<?php
session_start();
require 'db_connection.php';

header("Content-Type: application/json"); // Ensure JSON response

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

    if ($product_id > 0) {
        echo json_encode(["status" => "success", "message" => "Product ID: $product_id received"]);
        exit;
    }
}

echo json_encode(["status" => "error", "message" => "No product ID received"]);
exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link rel="stylesheet" href="stylesC.css"> <!-- Link to external styles -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/9.0.0/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/9.0.0/swiper-bundle.min.css">
</head>
<body>

<h2>Your Shopping Cart</h2>

<div class="swiper cart-slider">
    <div class="swiper-wrapper">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="swiper-slide">
                <div class="cart-item">
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <h3><?php echo $row['name']; ?></h3>
                    <p>Price: $<?php echo $row['price']; ?></p>
                    <button class="remove-btn" data-cartid="<?php echo $row['cart_id']; ?>">Remove</button>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Navigation Buttons -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

<button id="checkout-btn">Checkout</button>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let swiper = new Swiper(".cart-slider", {
        slidesPerView: 1,
        spaceBetween: 20,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        }
    });

    document.querySelectorAll(".remove-btn").forEach(button => {
        button.addEventListener("click", function() {
            let cartId = this.getAttribute("data-cartid");

            fetch("remove_from_cart.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ cart_id: cartId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Removed from cart!");
                    location.reload(); // Refresh the page
                }
            });
        });
    });

    document.getElementById("checkout-btn").addEventListener("click", function() {
        fetch("checkout.php", { method: "POST" })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Checkout complete!");
                window.location.href = "purchase_history.php";
            }
        });
    });
});
</script>

</body>
</html>
