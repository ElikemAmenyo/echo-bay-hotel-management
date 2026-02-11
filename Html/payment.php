<?php
require_once '../config.php';

// Paystack test secret key
$paystack_secret = "sk_test_3729ce72888df3fefa5fe49bcf3bb8d27de1ec06";

if (!isset($_GET['booking_id'])) {
    die("Invalid booking request.");
}

$booking_id = (int)$_GET['booking_id'];

// Fetch booking info
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("Booking not found.");
}

// Make sure your bookings table has a total_amount column.
// If not, compute it from room rate × number of nights.
if (isset($booking['total_amount']) && $booking['total_amount'] > 0) {
    $total_amount = (float)$booking['total_amount'];
} else {
    // Fallback: compute price (update this logic as needed)
    $room_stmt = $conn->prepare("SELECT price FROM rooms WHERE room_type = ?");
    $room_stmt->execute([$booking['room_type']]);
    $room = $room_stmt->fetch(PDO::FETCH_ASSOC);

    $price_per_night = $room ? (float)$room['price'] : 0;
    $checkin_date = new DateTime($booking['checkin']);
    $checkout_date = new DateTime($booking['checkout']);
    $nights = $checkin_date->diff($checkout_date)->days ?: 1;

    $total_amount = $price_per_night * $nights;
}

// Convert GHS to kobo (multiply by 100)
$amount = intval($total_amount * 100);

$email = $booking['email'];
$callback_url = "http://localhost/Online_Hotel_Booking/Html/payment_callback.php";

$data = [
    'email' => $email,
    'amount' => $amount,
    'reference' => 'HOTEL_' . uniqid(),
    'callback_url' => $callback_url,
    'metadata' => [
        'booking_id' => $booking_id,
        'customer_name' => $booking['name'],
        'room_type' => $booking['room_type'],
        'total_amount' => $total_amount
    ]
];

$ch = curl_init('https://api.paystack.co/transaction/initialize');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $paystack_secret",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$result = curl_exec($ch);
curl_close($ch);

$response = json_decode($result, true);

if ($response && isset($response['data']['authorization_url'])) {
    header("Location: " . $response['data']['authorization_url']);
    exit();
} else {
    echo "Error initializing Paystack payment.";
    if (isset($response['message'])) {
        echo "<br>Error: " . htmlspecialchars($response['message']);
    }
}
?>
