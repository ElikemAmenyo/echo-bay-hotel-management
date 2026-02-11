<?php
session_start();
require_once '../config.php';

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Fetch room info from DB (total, available, occupied, price)
$stmt = $conn->query("SELECT id, room_type, total, available, (total - available) AS occupied, price FROM rooms");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
$roomData = [];
foreach ($rooms as $r) {
    $roomData[$r['room_type']] = $r;
}

// Handle booking form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $room_type = $_POST['room_type'];

    if (!empty($name) && !empty($email) && !empty($phone) && !empty($checkin) && !empty($checkout) && !empty($room_type)) {
        // Fetch room details
        $stmt = $conn->prepare("SELECT id, available, price FROM rooms WHERE room_type = ?");
        $stmt->execute([$room_type]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($room && $room['available'] > 0) {
            // Calculate number of nights
            $checkin_date = new DateTime($checkin);
            $checkout_date = new DateTime($checkout);
            $nights = $checkin_date->diff($checkout_date)->days;

            if ($nights <= 0) {
                $message = "Checkout date must be after check-in date.";
            } else {
                // Calculate total amount
                $total_amount = $room['price'] * $nights;

                // Insert booking with total
                $stmt = $conn->prepare("
                    INSERT INTO bookings (user_id, name, email, phone, room_type, checkin, checkout, total_price, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')
                ");
                $stmt->execute([$user_id, $name, $email, $phone, $room_type, $checkin, $checkout, $total_amount]);
                $booking_id = $conn->lastInsertId();

                // Update room availability
                $stmt = $conn->prepare("UPDATE rooms SET available = available - 1 WHERE id = ?");
                $stmt->execute([$room['id']]);

                $message = "Booking confirmed! A $room_type room has been reserved for you.<br>Redirecting to checkout...";

                echo "<script>
                        setTimeout(function(){
                            window.location.href = 'checkout.php?booking_id=$booking_id';
                        }, 3000);
                      </script>";
            }
        } else {
            $message = "Sorry, all $room_type rooms are fully booked.";
        }
    } else {
        $message = "Please fill in all required fields.";
    }

    // Refresh room info
    $stmt = $conn->query("SELECT id, room_type, total, available, (total - available) AS occupied, price FROM rooms");
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $roomData = [];
    foreach ($rooms as $r) {
        $roomData[$r['room_type']] = $r;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Page</title>
  <link rel="icon" href="../images/Logo/Logo.png">
  <link rel="stylesheet" href="../CSS/Reserveform-enhanced.css">
</head>  
<body>  
  <div>  
    <h1>Book Your Stay</h1>
  </div>

  <?php if ($message): ?>
    <div class="alert"><?= $message ?></div>
  <?php endif; ?>

  <div class="reserve-wrapper">
    <div>
      <!-- Room Selection -->
      <div class="room-selection">
        <h2>Select a Room</h2>
        <div class="room-options">
          <?php foreach ($rooms as $room): ?>
          <div class="room-card" data-room="<?= htmlspecialchars($room['room_type']) ?>">
            <h3><?= ucfirst($room['room_type']) ?> Room</h3>
            <p>
              ₵<?= number_format($room['price'], 2) ?> per night <br>
              Total: <?= $room['total'] ?> | 
              Occupied: <?= $room['occupied'] ?> | 
              Available: <?= $room['available'] ?>
            </p>
            <button type="button" class="select-room" <?= $room['available'] <= 0 ? 'disabled' : '' ?>>
              <?= $room['available'] > 0 ? 'Select' : 'Fully Booked' ?>
            </button>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="booking-container">
      <!-- Guest Information Form -->
      <div class="guest-form">
        <h2>Guest Information</h2>
        <form id="guest-info-form" method="POST">
          <label for="name">Full Name:</label>
          <input type="text" id="name" name="name" required>

          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required>

          <label for="phone">Phone Number:</label>
          <input type="tel" id="phone" name="phone" required>

          <label for="checkin">Check-in Date:</label>
          <input type="date" id="checkin" name="checkin" required>

          <label for="checkout">Check-out Date:</label>
          <input type="date" id="checkout" name="checkout" required>

          <input type="hidden" id="room_type" name="room_type">

          <button type="submit">Submit Booking</button>
        </form>
      </div>

      <!-- Booking Summary -->
      <div class="booking-summary">
        <h2>Booking Summary</h2>
        <div id="summary-content">
          <p>No room selected yet.</p> 
        </div> 
      </div> 
    </div> 
  </div> 

<script>
  const buttons = document.querySelectorAll(".select-room");
  const summary = document.getElementById("summary-content");
  const roomInput = document.getElementById("room_type");

  buttons.forEach(btn => {
    btn.addEventListener("click", () => {
      let card = btn.closest(".room-card");
      let room = card.getAttribute("data-room");
      let details = card.querySelector("p").innerHTML;

      roomInput.value = room;
      summary.innerHTML = `<p><strong>${room.toUpperCase()}</strong> selected.<br>${details}</p>`;
    });
  });
</script>
</body> 
</html>
