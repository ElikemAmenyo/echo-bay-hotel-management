<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EchoBay | Special offers</title>
    <link rel="icon" href="../images/Logo/Logo.png">
    <link rel="stylesheet" href="../CSS/base.css">
    <link rel="stylesheet" href="../CSS/SpecialOffers.css">
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
                <a href="SpecialOffers.php" class="active">Special offers</a>
                <a href="testimonials.php">Reviews</a>
                <a href="meals.php">Order meal</a>
                <a href="user_dashboard.php">Dashboard</a>

                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="../login.php" class="login-btn">Login</a>
                <?php else: ?>
                    <a href="../logout.php" class="logout-btn">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <h1 class="section-title">Special Offers</h1>
    <div class="offers-container">
        <div class="offer-card">
            <img src="../images/Luxury rooms.jpg" alt="Luxury Room Offer" class="offer-image">
            <div class="offer-details">
                <h2 class="offer-title">Luxury Suite Package</h2>
                <p class="offer-description">Experience our premium luxury suite with complimentary breakfast and spa access.</p>
                <div>
                    <span class="original-price">$399</span>
                    <span class="offer-price">$299</span>
                    <span class="discount-badge">25% OFF</span>
                    <div class="countdown"></div>
                </div>
            </div>
        </div>

        <div class="offer-card">
            <img src="../images/Pool side.jpg" alt="Poolside Package" class="offer-image">
            <div class="offer-details">
                <h2 class="offer-title">Poolside Retreat</h2>
                <p class="offer-description">Enjoy direct pool access from your room with this exclusive package.</p>
                <div>
                    <span class="original-price">$249</span>
                    <span class="offer-price">$199</span>
                    <span class="discount-badge">20% OFF</span>
                    <div class="countdown"></div>
                </div>
            </div>
        </div>

        <div class="offer-card">
            <img src="../images/Breakfast.png" alt="Breakfast Package" class="offer-image">
            <div class="offer-details">
                <h2 class="offer-title">Breakfast Included</h2>
                <p class="offer-description">Start your day right with our gourmet breakfast buffet included in your stay.</p>
                <div>
                    <span class="original-price">$179</span>
                    <span class="offer-price">$149</span>
                    <span class="discount-badge">17% OFF</span>
                    <div class="countdown"></div>
                </div>
            </div>
        </div>

        <div class="offer-card">
            <img src="../images/Massaging.jpg" alt="Spa Package" class="offer-image">
            <div class="offer-details">
                <h2 class="offer-title">Spa Getaway</h2>
                <p class="offer-description">Relax and rejuvenate with our spa package including 2 massage sessions.</p>
                <div>
                    <span class="original-price">$329</span>
                    <span class="offer-price">$259</span>
                    <span class="discount-badge">21% OFF</span>
                    <div class="countdown"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/specialoffers.js"></script>
</body>
</html>
