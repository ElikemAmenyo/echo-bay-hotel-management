<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$booking_id = (int)($_GET['booking_id'] ?? 0);
$days = (int)($_GET['days'] ?? 0);
$reference = $_GET['reference'] ?? '';

if (!$reference || $booking_id <= 0 || $days <= 0) {
    die("Invalid transaction");
}

// Verify payment with Paystack
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer sk_test_3729ce72888df3fefa5fe49bcf3bb8d27de1ec06",
        "Cache-Control: no-cache"
    ]
]);
$response = curl_exec($curl);
curl_close($curl);

$res = json_decode($response, true);
if (!$res['status'] || $res['data']['status'] !== 'success') {
    die("Payment verification failed");
}

// Fetch booking details again
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
$stmt->execute([$booking_id, $_SESSION['user_id']]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("Booking not found");
}

// Get room price
$stmt = $conn->prepare("SELECT price FROM rooms WHERE room_type = ?");
$stmt->execute([$booking['room_type']]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

$price_per_night = $room['price'] ?? 0;
$added_amount = $days * $price_per_night;
$new_total = $booking['total_price'] + $added_amount;
$new_checkout = date('Y-m-d', strtotime($booking['checkout'] . " +$days days"));

// Update booking
$stmt = $conn->prepare("UPDATE bookings SET checkout = ?, total_price = ? WHERE id = ?");
$stmt->execute([$new_checkout, $new_total, $booking_id]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Success</title>
<style>
body { font-family: Arial, sans-serif; background:#f5f5f5; text-align:center; padding-top:80px; }
.card { background:#fff; padding:30px; border-radius:12px; width:420px; margin:auto; box-shadow:0 8px 20px rgba(0,0,0,0.1); }
h1 { color:#16a34a; }
a { display:inline-block; margin-top:20px; text-decoration:none; color:white; background:#1b73e8; padding:10px 20px; border-radius:8px; }
</style>
</head>
<body>
  <div class="card">
    <h1>Payment Successful!</h1>
    <p>Your booking has been extended by <strong><?= $days ?></strong> days.</p>
    <p>New checkout date: <strong><?= htmlspecialchars($new_checkout) ?></strong></p>
    <a href="user_dashboard.php">Return to Dashboard</a>
  </div>
</body>
</html>
