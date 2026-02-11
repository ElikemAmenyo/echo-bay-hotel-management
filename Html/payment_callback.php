<?php
require_once '../config.php';

$paystack_secret = "sk_test_3729ce72888df3fefa5fe49bcf3bb8d27de1ec06";

if (!isset($_GET['reference'])) {
    die("No transaction reference supplied.");
}

$reference = $_GET['reference'];

// Verify transaction
$ch = curl_init("https://api.paystack.co/transaction/verify/" . $reference);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $paystack_secret"
]);
$result = curl_exec($ch);
curl_close($ch);

$response = json_decode($result, true);

if ($response && isset($response['data']['status']) && $response['data']['status'] === 'success') {
    $metadata = $response['data']['metadata'];
    $booking_id = $metadata['booking_id'] ?? null;
    $customer_name = $metadata['customer_name'] ?? 'Guest';
    $room_type = $metadata['room_type'] ?? 'Room';
    $amount = $response['data']['amount'] / 100; // Convert from kobo to GHS

    if ($booking_id) {
        // Mark booking as paid
        $stmt = $conn->prepare("UPDATE bookings SET payment_status = 'Paid', reference = ? WHERE id = ?");
        $stmt->execute([$reference, $booking_id]);
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Payment Successful - Echo Bay Lodge</title>
        <link rel="stylesheet" href="../CSS/payment.css">
    </head>
    <body>
        <div class="payment-result-container">
            <div class="payment-success">
                <div class="success-icon">✓</div>
                <h1>Payment Successful!</h1>
                <p>Thank you, <strong><?= htmlspecialchars($customer_name) ?></strong>.</p>
                <p>Your booking has been confirmed and payment received.</p>
                
                <div class="booking-confirmation">
                    <h3>Booking Details</h3>
                    <div class="confirmation-item">
                        <span class="confirmation-label">Booking Reference:</span>
                        <span class="confirmation-value">#<?= $booking_id ?></span>
                    </div>
                    <div class="confirmation-item">
                        <span class="confirmation-label">Room Type:</span>
                        <span class="confirmation-value"><?= htmlspecialchars($room_type) ?></span>
                    </div>
                    <div class="confirmation-item">
                        <span class="confirmation-label">Amount Paid:</span>
                        <span class="confirmation-value">₵<?= number_format($amount, 2) ?></span>
                    </div>
                    <div class="confirmation-item">
                        <span class="confirmation-label">Transaction ID:</span>
                        <span class="confirmation-value"><?= $reference ?></span>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="user_dashboard.php" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt"></i> Go to Dashboard
                    </a>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                </div>

                <div class="additional-info">
                    <p>A confirmation email has been sent to your registered email address.</p>
                    <p>For any questions, contact: info@echobaylg.com</p>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Payment Failed - Echo Bay Lodge</title>
        <link rel="stylesheet" href="../CSS/payment.css">
    </head>
    <body>
        <div class="payment-result-container">
            <div class="payment-error">
                <div class="error-icon">⚠️</div>
                <h1>Payment Failed!</h1>
                <p>We encountered an issue processing your payment. Please try again.</p>
                
                <?php if (isset($response['message'])): ?>
                    <p>Error: <?= htmlspecialchars($response['message']) ?></p>
                <?php endif; ?>

                <div class="action-buttons">
                    <a href="booking.php" class="btn btn-danger">
                        <i class="fas fa-redo"></i> Try Again
                    </a>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                </div>

                <div class="additional-info">
                    <p>If this problem persists, please contact our support team.</p>
                    <p>Phone: +233 (0) 504600091 | Email: support@echobaylg.com</p>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>