<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>What we offer</title>
    <link rel="icon" href="../images/Logo/Logo.png">
    <link rel="stylesheet" href="../CSS/learnmore.css">
    <link rel="stylesheet" href="../CSS/Style.css">
    <link rel="stylesheet" href="../CSS/swiper-bundle.min.css">
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
    <div class="container">

        <video autoplay loop muted plays-inline class="background-clip">
            <source src="../Videos/6467633-uhd_4096_2160_25fps.mp4" type="video/mp4">
        </video>

        <div class="content">
            <section class="wrapper">
                <div class="card_container">
                    <div class="card_content">
                        <div class="swiper">
                            <div class="swiper-wrapper">
                               
                                <div class="swiper-slide">
                                    <div class="cover">
                                        <img src="../images/Pool.jpg" alt="">
                                        <div class="text">
                                            <h3>Infinity Pool</h3>
                                        <p>
                                            Relax in our stunning infinity pool overlooking breathtaking ocean views, featuring a swim-up bar and comfortable lounge areas.
                                        </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="swiper-slide">
                                    <div class="cover">
                                        <img src="../images/Breakfast.png" alt="">
                                        <div class="text">
                                            <h3>Gourmet Dining</h3>
                                        <p>
                                            Experience world-class cuisine prepared by our award-winning chefs, featuring locally-sourced ingredients and international flavors.
                                        </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="cover">
                                        <img src="../images/Luxury rooms.jpg" alt="">
                                        <div class="text">
                                            <h3>Premium Suites</h3>
                                        <p>Unwind in our spacious suites featuring plush bedding, marble bathrooms, and private balconies with panoramic views.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="cover">
                                        <img src="../images/Massaging.jpg" alt="">
                                        <div class="text">
                                            <h3>Luxury Spa</h3>
                                            <p>
                                                Rejuvenate with our signature treatments using organic products in our tranquil, ocean-view spa sanctuary.
                                             </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="swiper-button-next swiper-button-white"></div>
                            <div class="swiper-button-prev swiper-button-white"></div>
                            <div class="swiper-pagination swiper-pagination-white"></div>
                        </div>
                       </div>
                    </div>
                </section>
            </div>
        </div>
        <script src="../js/swiper-bundle.min.js"></script>
        <script src="../js/learnmore.js"></script>
</body>
</html>