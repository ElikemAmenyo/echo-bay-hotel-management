<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EchoBay | Gallery</title>
    <link rel="icon" href="../images/Logo/Logo.png">
    <link rel="stylesheet" href="../CSS/Gallery.css">
    <link rel="stylesheet" href="../CSS/base.css">
</head>
<body>
 <!--Navigation bar-->
    <header>
        <div>
            <img src="../images/Logo/Logo.png" alt="" class="logo">
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
    <section>
        <div class="sec">
        <h1>Gallery</h1>
        <p>Explore our Exquisite Environments </p>
        </div>
        </section>
        <section class="dropdown-content">
           
            <div class="grid" data-component="grid">
                <div class="img-box">
                    <img src="../images/wallpaperflare.com_wallpaper (5).jpg" alt="">
                </div>
                <div class="img-box">
                    <img src="../images/wallpaperflare.com_wallpaper (7).jpg" alt="">
                </div>
                <div class="img-box">
                    <img src="../images/wallpaperflare.com_wallpaper (4).jpg" alt="">
                </div>
                <div class="img-box">
                    <img src="../images/wallpaperflare.com_wallpaper (6).jpg" alt="">
                </div>
                <div class="img-box">
                    <img src="../images/wallpaperflare.com_wallpaper (8).jpg" alt="">
                </div>
                <div class="img-box">
                    <img src="../images/wallpaperflare.com_wallpaper (3).jpg" alt="">
                </div>
                <div class="img-box">
                    <img src="../images/Night hotel.jpg" alt="">
                </div>
                <div class="img-box">
                    <img src="../images/Pool.jpg" alt="" >
                </div>
                <div class="img-box">
                    <img src="../images/Pool side.jpg" alt="">
                </div>
                </div>

                <section class="event-section">
                    <h2>Upcoming Events</h2>
                    <div class="event-container">
                        <div class="event-item">
                            <img src="../images/Hotel_event1.jpg" alt="Wedding Reception">
                            <div class="event-caption">
                                <h3 class="event-title">Wedding Reception</h3>
                                <p>Elegant wedding celebrations in our grand ballroom</p>
                            </div>
                        </div>
                        <div class="event-item">
                            <img src="../images/Hotel_event2.jpg" alt="Corporate Conference">
                            <div class="event-caption">
                                <h3 class="event-title">Corporate Conference</h3>
                                <p>Professional meetings with premium amenities</p>
                            </div>
                        </div>
                        <div class="event-item">
                            <img src="../images/Hotel_event3.jpg" alt="Gala Dinner">
                            <div class="event-caption">
                                <h3 class="event-title">Gala Dinner</h3>
                                <p>Exclusive fine dining experiences</p>
                            </div>
                        </div>
                    </div>
                </section>
        </section>

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
                <p>&copy; 2024 Our Hotel. All rights reserved. | <a href="index.php">Home</a> | <a href="reservation.html">Reserve</a></p>
            </footer>
          
</body>
</html>