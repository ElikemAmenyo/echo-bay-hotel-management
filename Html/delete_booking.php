<?php
require_once '../config.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Get the room type before deleting
    $stmt = $conn->prepare("SELECT room_type FROM bookings WHERE id = ?");
    $stmt->execute([$id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($booking) {
        $room_type = $booking['room_type'];

        // Delete the booking
        $deleteStmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
        $deleteStmt->execute([$id]);

        // Update room availability
        $updateStmt = $conn->prepare("UPDATE rooms SET available = available + 1 WHERE room_type = ?");
        $updateStmt->execute([$room_type]);

        header("Location: admin_dashboard.php?msg=Booking+Deleted+and+Room+Updated");
        exit();
    } else {
        echo "Booking not found.";
    }
}
?>
