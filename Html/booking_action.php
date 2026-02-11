<?php
session_start();
require_once '../config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "Access denied: Please log in.";
    exit();
}

$user_id = (int)$_SESSION['user_id'];

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $booking_id = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;

    if (!$booking_id || !$action) {
        http_response_code(400);
        echo "Invalid request.";
        exit();
    }

    // Verify booking belongs to this user
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->execute([$booking_id, $user_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        http_response_code(404);
        echo "Booking not found or not authorized.";
        exit();
    }

    // Process action
    if ($action === 'checkin') {
        if ($booking['status'] === 'checked_in') {
            echo "You have already checked in.";
            exit();
        }

        $stmt = $conn->prepare("UPDATE bookings SET status = 'active', checkin = NOW() WHERE id = ?");
        $stmt->execute([$booking_id]);

        echo "Check-in successful for booking #{$booking_id}.";

    } elseif ($action === 'checkout') {
        if ($booking['status'] === 'checked_out') {
            echo "You have already checked out.";
            exit();
        }

        // Update booking status
        $stmt = $conn->prepare("UPDATE bookings 
                                SET status = 'checked_out', checkout = NOW() 
                                WHERE id = ?");
        $stmt->execute([$booking_id]);

        // Restore room availability
        $stmt = $conn->prepare("UPDATE rooms 
                                SET available = available + 1 
                                WHERE room_type = ?");
        $stmt->execute([$booking['room_type']]);

        echo "🏁 Check-out successful! Your booking #{$booking_id} has been moved to history.";

    } else {
        http_response_code(400);
        echo "Invalid action.";
        exit();
    }

} else {
    http_response_code(405);
    echo "Method not allowed.";
}
?>