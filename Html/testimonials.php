<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EchoBay | Reviews</title>
  <link rel="icon" href="../images/Logo/Logo.png">
  <link rel="stylesheet" href="../CSS/base.css">
  <link rel="stylesheet" href="../CSS/testimonial.css">
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
  <a href="./SpecialOffers.php">Special offers</a>
  <a href="testimonials.php" class="active">Reviews</a>
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

<div class="main">
  <div class="testimonials-container">
    <h1>What Our Customers Say</h1>
    <div class="testimonials">

      <!-- Testimonial 1 -->
      <div class="testimonial">
        <p class="review">“I had an amazing stay at Echo Bay! The staff were friendly and helpful, the room was clean and comfortable, and the breakfast buffet was incredible. I’ll definitely be back!” </p>
        <p class="customer">- John Doe</p>
      </div>

      <!-- Testimonial 2 -->
      <div class="testimonial">
        <p class="review">"“From check-in to check-out, our stay at Echo Bay was flawless. The hotel’s attention to detail and commitment to excellence truly impressed us. We can’t wait to return!” </p>
        <p class="customer">- Jane Smith</p>
      </div>

      <!-- Testimonial 3 -->
      <div class="testimonial">
        <p class="review">“The Echo Bay team went above and beyond to make our anniversary celebration special. The room was beautifully decorated, and the champagne and strawberries were a lovely touch. Thank you for an unforgettable stay!” </p>
        <p class="customer">- Alex Johnson</p>
      </div>

       <!-- Testimonial 4 -->
      <div class="testimonial">
        <p class="review">“The fitness center was well-equipped, and the pool was a great place to relax. We also appreciated the free Wi-Fi.”  </p>
        <p class="customer">– Christine L</p>
      </div>

       <!-- Testimonial 5 -->
      <div class="testimonial">
        <p class="review">“The hotel’s location was perfect – close to public transportation and within walking distance to many attractions.” </p>
        <p class="customer">– Michael T</p>
      </div>

    </div>
  </div>
</div>
</body>
</html>