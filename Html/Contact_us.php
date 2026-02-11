<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EchoBay | Contact us</title>
    <link rel="icon" href="../images/Logo/Logo.png">
    <link rel="stylesheet" href="../CSS/base.css">
    <link rel="stylesheet" href="../CSS/Contact.css">
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
                <a href="Contact_us.php" class="active">Contact us</a>
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

    <section>
        <div>
        <h2>Have Any Concerns?</h2>
        <p>We are ready to hear from you</p>
        </div>
    </section>
    <div class="gridhalf">
    <div class="container">
        <form action="">
            <div>
            <div>
            <h2> Send Us A Message</h2>
            </div>
            <div>
                <input type="text" placeholder="Name*" required>
            </div>
            <div>
                <input type="email" placeholder="Email* example:ask@gmail.com" required>
            </div>
            <div>
                <textarea name="" id="" cols="47" rows="10" placeholder="Message" required></textarea>
            </div>
            <div>
                <button>Send Message</button>
            </div>
            </div>
        </form>
    </div>
    <div class="righthalf">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15880.722823217719!2d-0.2042773241503891!3d5.687006535336342!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfdf9c540974acf7%3A0x447b5d25a869756d!2sNorth%20Legon!5e0!3m2!1sen!2sgh!4v1736761266134!5m2!1sen!2sgh" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    </div>

      <!--Info bar-->
    <section id="contact" aria-label="Hotel contact information">
        <div class="ingrid">
            <div class="hotel-branches">
                <h2>Our Hotel Branches</h2>
                <ul>
                    <li><a href="#" aria-label="Echo Bay Lodge North Legon location">Echo Bay Lodge North Legon</a></li>
                    <li><a href="#" aria-label="Echo Bay Lodge Dansoman location">Echo Bay Lodge Dansoman</a></li>
                </ul>
            </div>
            <div class="contact-info">
                <h2>Contact Us</h2>
                <div class="contact-item-group">
                <ul>
                    <li class="contact-item">
                        <img src="../images/icons/location.png" alt="Location icon" />
                        <p><a href="https://maps.app.goo.gl/yB3rtmoQhfJTxLvA7" target="_blank" rel="noopener noreferrer">North Legon - Accra</a></p>
                    </li>
                    <li class="contact-item">
                        <img src="../images/icons/mail.png" alt="Email icon" />
                        <p><a href="mailto:info@hotel.com">info@echobaylg.com</a></p>
                    </li>
                    <li class="contact-item">
                        <img src="../images/icons/Phone.png" alt="Phone icon" />
                        <p><a href="tel:+2330504600091">+233 (0) 504600091</a></p>
                    </li>
                    <li class="contact-item">
                        <img src="../images/icons/Phone.png" alt="Phone icon" />
                        <p><a href="tel:+2330208117611">+233 (0) 208117611</a></p>
                    </li>
                </ul>
            </div>
            <ul class="social-media">
                <li>
                    <a href="https://www.facebook.com" aria-label="Follow us on Facebook"><img src="../images/Facebook-64.webp" alt="Facebook icon" /></a>
                </li>
                <li>
                    <a href="https://web.telegram.org/a/" aria-label="Join our Telegram channel"><img src="../images/icons/6214709_air_airplane_logo_paper_plane_icon.png" alt="Telegram icon" /></a>
                </li>
                <li>
                    <a href="https://www.instagram.com/" aria-label="Follow us on Instagram"><img src="../images/icons/1161953_instagram_icon.png" alt="Instagram icon" /></a>
                </li>
                <li>
                    <a href="https://www.tiktok.com/explore" aria-label="Follow us on TikTok"><img src="../images/icons/4362958_tiktok_logo_social media_icon.png" alt="TikTok icon" /></a>
                </li>
            </ul>
            </div>
        </div>
    </section>
    <footer>
        <p>&copy; 2024 Our Hotel. All rights reserved. | <a href="index.php">Home</a> | <a href="./Reservation.php">Reserve</a></p>
    </footer>
</body>
</html>