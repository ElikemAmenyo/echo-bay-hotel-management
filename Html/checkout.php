<?php
require_once '../config.php';

// Check if booking_id is passed
if (!isset($_GET['booking_id'])) {
    die("Invalid booking request.");
}

$booking_id = $_GET['booking_id'];

// Fetch booking info
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("Booking not found.");
}

// Fetch room info for price
$stmt = $conn->prepare("SELECT * FROM rooms WHERE room_type = ?");
$stmt->execute([$booking['room_type']]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

// Calculate total nights and price
$checkin = new DateTime($booking['checkin']);
$checkout = new DateTime($booking['checkout']);
$nights = $checkin->diff($checkout)->days;
$total_price = $room['price'] * $nights;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .checkout-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            width: 500px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 {
            color: #222;
        }
        .summary {
            text-align: left;
            margin-top: 20px;
        }
        .summary p {
            font-size: 16px;
            line-height: 1.6;
        }
        .btn {
            background: #1b73e8;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            transition: 0.3s;
        }
        .btn:hover {
            background: #155bc4;
        }
        /* Loading overlay */
        #loadingOverlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            flex-direction: column;
        }
        .spinner {
            border: 5px solid #ddd;
            border-top: 5px solid #1b73e8;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% {transform: rotate(0deg);}
            100% {transform: rotate(360deg);}
        }
        #loadingOverlay p {
            margin-top: 20px;
            font-size: 18px;
            color: #333;
        }
    </style>
</head>
<body>

<div class="checkout-box">
    <h1>Checkout</h1>

    <div class="summary">
        <p><strong>Guest:</strong> <?= htmlspecialchars($booking['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($booking['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($booking['phone']) ?></p>
        <p><strong>Room Type:</strong> <?= htmlspecialchars(ucfirst($booking['room_type'])) ?></p>
        <p><strong>Check-in:</strong> <?= htmlspecialchars($booking['checkin']) ?></p>
        <p><strong>Check-out:</strong> <?= htmlspecialchars($booking['checkout']) ?></p>
        <p><strong>Nights:</strong> <?= $nights ?></p>
        <p><strong>Total Price:</strong> ₵<?= number_format($total_price, 2) ?></p>
    </div>

    <button class="btn" id="proceedBtn">💳 Proceed to Payment</button>
</div>

<!-- Loading animation -->
<div id="loadingOverlay">
    <div class="spinner"></div>
    <p>Redirecting to payment gateway...</p>
</div>

<script>
    const proceedBtn = document.getElementById("proceedBtn");
    const overlay = document.getElementById("loadingOverlay");

    proceedBtn.addEventListener("click", () => {
        overlay.style.display = "flex";
        proceedBtn.disabled = true;
        setTimeout(() => {
            window.location.href = "payment.php?booking_id=<?= $booking_id ?>&amount=<?= $total_price ?>";
        }, 3000); // wait 3 seconds before redirect
    });
</script>

</body>
</html>
