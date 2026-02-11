<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EchoBay | Home</title>
    <link rel="icon" href="../images/Logo/Logo.png" type="image/x-icon"> 
    <link rel="stylesheet" href="../CSS/Style.css"> 
</head> 

<body>
    <!--Navigation bar-->
    <header>
        <div>
            <img src="../images/Logo/Logo.png" alt="">
        </div>
        <div class="navigation">
            <div class="navigation-items">
                <a href="index.php" class="active">Home</a>
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
    <section class="welcome">
        <img class="image-slide" src="../images/wallpaperflare.com_wallpaper (5).jpg" alt="">
        <img class="image-slide" src="../images/wallpaperflare.com_wallpaper (3).jpg" alt="">
        <img class="image-slide" src="../images/wallpaperflare.com_wallpaper (4).jpg" alt="">
        <img src="../images/wallpaperflare.com_wallpaper (6).jpg" alt="" class="image-slide">
        <img src="../images/wallpaperflare.com_wallpaper (7).jpg" alt="" class="image-slide">
        <img src="../images/wallpaperflare.com_wallpaper (8).jpg" alt="" class="image-slide">
        <div class="content">
            <h1>Welcome to <br><span>Echo Bay Lodge</span></h1>
            <p>A tranquil retreat nestled by the serene waters of Echo Bay. Our hotel combines comfort and natural beauty, offering guests a peaceful escape with cozy rooms, scenic lake views, and exceptional service. Whether you're here to unwind by the water, explore nature trails, or enjoy nearby attractions, Echo Bay Lodge promises a memorable and rejuvenating stay. Book your escape today and experience the perfect blend of relaxation and adventure.</p>
            <a href="Learn_more.php"><button class="learnmore">Learn more >></button></a>
        </div>
    </section>

    <!-- Modal -->
    <?php if (isset($_SESSION['just_logged_in']) && $_SESSION['just_logged_in'] === true): ?>
    <div id="welcomeModal" class="modal">
        <div class="modal-content">
            <h2>Congratulations!</h2>
            <p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?></strong>!</p>
            <button class="close-btn" onclick="closeModal()">Continue</button>
        </div>
    </div>
    <?php unset($_SESSION['just_logged_in']); endif; ?>

    <script src="../js/homeslide.js"></script>
    <script>
        window.onload = function() {
            var modal = document.getElementById("welcomeModal");
            if (modal) {
                modal.style.display = "block";
            }
        };

        function closeModal() {
            document.getElementById("welcomeModal").style.display = "none";
        }
    </script>
</body> 
</html>
