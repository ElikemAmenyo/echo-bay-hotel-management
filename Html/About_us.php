<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EchoBay | About Us</title>
    <link rel="icon" href="../images/Logo/Logo.png">
    <link rel="stylesheet" href="../CSS/about.css">
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
                <a href="index.php">Home</a>
                <a href="reservation.php" target="_blank">Booking</a>
                <a href="About_us.php" class="active">About Us</a>
                <a href="Gallery.php">Gallery</a>
                <a href="Contact_us.php">Contact us</a>
                <a href="SpecialOffers.php">Special offers</a>
                <a href="testimonials.php">Reviews</a>
                <a href="meals.php">Order meal</a>

                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="../login.php" class="login-btn">Login</a>
                <?php else: ?>
                    <a href="../logout.php" class="logout-btn">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div>
        <h1>About Echo Bay Lodge</h1>
        </div>
        <div>
    <section id="about-us">
        <h2>Our Mission</h2><br>
        <p>We are dedicated to providing our guests with an unforgettable experience marked by comfort, luxury, and exceptional service. Our goal is to create a memorable stay that leaves a lasting impression on each of our guests, whether they are here for business, pleasure, or a bit of both.</p>
    </section>
    </div>

    <div class="team">
        <h2>Meet the Team</h2><br>
    </div>

    <div class="team-container">
    <section id="team">
        <div class="team-box">
            <img src="../images/young-happy-professional-african-american-260nw-2319588109.webp" alt="">
            <h3>Jane Otis</h3>
            <p>CEO & Founder</p>
            <p>As CEO and founder, Jane Otis spearheads strategic direction and operational excellence, fostering a culture of innovation and customer-centric service in hospitality</p>
            <button>Contact</button>
        </div>

        <div class="team-box">
            <img src="../images/Manager.jpg" alt="">
            <h3>Steven Jones</h3>
            <p>Hotel Manager</p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iste facere blanditiis ipsa neque modi porro rerum sunt nulla debitis cum temporibus voluptate, distinctio maxime repellendus voluptates iusto quos quod fuga.</p>
            <button>Contact</button>
        </div>

         <div class="team-box">
            <img src="../images/Headchef.jpg" alt="">
            <h3>Esther Smith</h3>
            <p>Head Chef</p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iste facere blanditiis ipsa neque modi porro rerum sunt nulla debitis cum temporibus voluptate, distinctio maxime repellendus voluptates iusto quos quod fuga.</p>
            <button>Contact</button>
        </div>
    </section>
</div>

    <section id="history">
        <div class="history">
            <h1>Our History</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi excepturi aut quidem, exercitationem rerum, fugiat, repellendus non ex mollitia dignissimos magni necessitatibus vero cumque quia recusandae porro nulla a tempora.
            Magni velit quasi reiciendis dicta soluta nostrum perspiciatis error quod repudiandae modi ea similique, delectus necessitatibus mollitia placeat perferendis at, eum sed rerum provident. Libero eius alias deserunt modi ut!
            Molestias quis incidunt facere recusandae beatae minus, quos fugit saepe deleniti eum soluta! Aliquid, accusamus iusto tenetur totam cum rerum nam dicta placeat libero? Rem temporibus dicta saepe at dolore.
            Nemo porro ullam id, quia deserunt culpa officia distinctio illum assumenda deleniti aliquid quam, esse, tempore magni est facere doloremque? Eveniet totam, necessitatibus dicta doloremque beatae sed perspiciatis quidem facilis!
            Eveniet harum quo impedit totam. Dolore natus excepturi ex, atque earum ea nesciunt praesentium molestiae quos aperiam pariatur veritatis, sapiente sunt omnis saepe, eaque fugiat? Consequatur qui asperiores cumque dolorum.
            Dolores magnam nesciunt alias doloribus a consequatur. Voluptatibus consequuntur cum iste! Molestiae at quos dolorem quis, ratione laboriosam perspiciatis incidunt iure ducimus tempore, autem consequatur quidem necessitatibus expedita omnis fugit?
            Possimus autem, dolore dolorum saepe libero blanditiis sed assumenda aperiam amet quisquam. Modi harum reiciendis adipisci obcaecati! Saepe rem repudiandae nobis illum et exercitationem cumque sequi eum molestias, ex ad?
            Incidunt, accusamus exercitationem? Ratione non nulla repellendus eaque ab minus et perspiciatis sapiente dicta dolores? Maiores eum, sunt cum laborum aspernatur omnis deserunt quos qui vel, iure, doloremque nesciunt incidunt.
            Debitis ea neque eveniet aliquid ipsa voluptates eius, vitae optio error? Ipsa et laudantium nobis provident repudiandae deserunt assumenda at placeat, veniam vero est minus distinctio doloribus tempore inventore esse.
            Animi repellendus perferendis hic quis ullam, quo laboriosam ducimus commodi consectetur beatae deleniti exercitationem inventore amet, modi rem laborum ipsam assumenda voluptate, corrupti consequatur alias sint pariatur eos quidem. Excepturi!</p>
        </div>
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
