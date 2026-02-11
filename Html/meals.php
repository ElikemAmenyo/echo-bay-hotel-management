<?php
session_start();
require_once "../config.php";

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Handle order submission
if (isset($_POST['order'])) {
    $meal_id = $_POST['meal_id'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO orders (user_id, meal_id, quantity) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $meal_id, $quantity]);

    $success = "Your order has been placed successfully!";
}

// Fetch available meals
$stmt = $conn->query("SELECT * FROM meals WHERE availability = 1 ORDER BY category");
$meals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Meals</title>
    <link rel="stylesheet" href="../CSS/base.css">
    <link rel="stylesheet" href="../CSS/meals.css">
</head>
<body>
     <!--Navigation bar-->
    <header>
        <div>
            <img src="../images/Logo/Logo.png" alt="" class="logo">
        </div>
        <div class="navigation">
            <div class="navigation-items">
                <a href="index.php">Home</a>
                <a href="reservation.php" target="_blank">Booking</a>
                <a href="About_us.php">About Us</a>
                <a href="Gallery.php">Gallery</a>
                <a href="Contact_us.php">Contact us</a>
                <a href="SpecialOffers.php">Special offers</a>
                <a href="testimonials.php">Reviews</a>
                <a href="user_dashboard.php">Dashboard</a>

                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="../login.php" class="login-btn">Login</a>
                <?php else: ?>
                    <a href="../logout.php" class="logout-btn">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <h1 style="text-align:center;">🍴 Available Meals</h1>

    <?php if (!empty($success)): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>

    <div class="meal-container">
        <?php foreach ($meals as $meal): ?>
            <div class="meal-card">
                <h3><?= htmlspecialchars($meal['name']) ?></h3>
                <p><b>Category:</b> <?= htmlspecialchars($meal['category']) ?></p>
                <p><?= htmlspecialchars($meal['description']) ?></p>
                <p><b>Price:</b> $<?= number_format($meal['price'], 2) ?></p>
                <form method="POST">
                    <input type="hidden" name="meal_id" value="<?= $meal['id'] ?>">
                    <input type="number" name="quantity" value="1" min="1" required>
                    <button type="submit" name="order" class="btn-order">Order</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
