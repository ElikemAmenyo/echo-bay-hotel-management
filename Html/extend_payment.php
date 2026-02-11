<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request");
}

$booking_id = (int)$_POST['booking_id'];
$days = (int)$_POST['days'];

if ($days <= 0 || $booking_id <= 0) {
    die("Invalid booking or days");
}

// Fetch booking info
$stmt = $conn->prepare("SELECT b.*, r.price FROM bookings b 
                        LEFT JOIN rooms r ON r.room_type = b.room_type 
                        WHERE b.id = ? AND b.user_id = ?");
$stmt->execute([$booking_id, $_SESSION['user_id']]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("Booking not found");
}

$price_per_night = $booking['price'] ?? 0;
$amount = $days * $price_per_night;  // in GHS
$amount_kobo = $amount * 100; // Paystack uses kobo/pesewas

$email = $_SESSION['user_email'] ?? 'test@example.com';

// Prepare Paystack transaction
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'email' => $email,
        'amount' => $amount_kobo,
        'callback_url' => "http://localhost/Online_Hotel_Booking/Html/extend_success.php?booking_id=$booking_id&days=$days"
    ]),
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer sk_test_3729ce72888df3fefa5fe49bcf3bb8d27de1ec06",
        "Cache-Control: no-cache"
    ]
]);

$response = curl_exec($curl);
curl_close($curl);

$res = json_decode($response, true);

if (!$res['status']) {
    die("Payment initialization failed: " . $res['message']);
}

// Redirect user to Paystack checkout
header("Location: " . $res['data']['authorization_url']);
exit;
?>
