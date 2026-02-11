<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = (int)$_SESSION['user_id'];

// Fetch user's name from database
$stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$_SESSION['user_name'] = $user ? $user['first_name'] . ' ' . $user['last_name'] : 'Guest';

// Fetch active bookings
$stmt = $conn->prepare("SELECT b.*, r.price
                        FROM bookings b
                        LEFT JOIN rooms r ON r.room_type = b.room_type
                        WHERE b.user_id = ? AND b.status = 'active'
                        ORDER BY b.checkin ASC");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch booking history
$stmt = $conn->prepare("SELECT b.*, r.price
                        FROM bookings b
                        LEFT JOIN rooms r ON r.room_type = b.room_type
                        WHERE b.user_id = ? AND b.status = 'checked_out'
                        ORDER BY b.checkout DESC
                        LIMIT 50");
$stmt->execute([$user_id]);
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch meal orders
$stmt = $conn->prepare("SELECT o.*, m.name AS meal_name
                        FROM orders o
                        LEFT JOIN meals m ON o.meal_id = m.id
                        WHERE o.user_id = ?
                        ORDER BY o.order_date DESC
                        LIMIT 50");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Helper functions
function nightsBetween($start, $end) {
    $d1 = new DateTime($start);
    $d2 = new DateTime($end);
    return max(0, $d1->diff($d2)->days);
}

function daysRemaining($now, $end) {
    $n = new DateTime($now);
    $e = new DateTime($end);
    return (int)$n->diff($e)->format('%r%a');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>User Dashboard</title>
<link rel="stylesheet" href="../CSS/admin.css">
<link rel="stylesheet" href="../CSS/base.css">
<style>
body { background:#f7f8fc; font-family: 'Poppins', sans-serif; }
.container { max-width:1100px; margin:30px auto; }
.card { background:#fff; padding:20px; border-radius:12px; margin-bottom:20px; box-shadow:0 4px 15px rgba(0,0,0,0.08); }
h1 { color:#333; }
h2 { color:#555; margin-bottom:10px; }
.table { width:100%; border-collapse:collapse; margin-top:10px; }
.table th, .table td { padding:10px; border-bottom:1px solid #eee; text-align:left; }
.table th { background:#fafafa; }
.small { font-size:0.9rem; color:#666; }
.btn { padding:7px 12px; border:none; border-radius:8px; cursor:pointer; color:#fff; font-size:0.9rem; }
.btn.primary { background:#1b73e8; }
.btn.warn { background:#f59e0b; }
.btn.danger { background:#dc3545; }
.input-inline { width:70px; padding:5px; border:1px solid #ccc; border-radius:6px; }
.notice { background:#e6ffed; color:#116530; padding:10px; border-radius:8px; margin-bottom:12px; }
.empty { color:#777; font-style:italic; }
</style>
</head>
<body>
    <header>
        <div>
            <img src="../images/Logo/Logo.png" alt="" class="logo">
        </div>
        <div class="navigation">
            <div class="navigation-items">
                <a href="index.php" class="active">Home</a>
                <a href="reservation.php" target="_blank">Booking</a>
                <a href="About_us.php">About Us</a>
                <a href="Gallery.php">Gallery</a>
                <a href="Contact_us.php">Contact us</a>
                <a href="SpecialOffers.php">Special offers</a>
                <a href="testimonials.php">Reviews</a>
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
<div class="container">
  <h1>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></h1>

  <!-- Active Bookings -->
  <div class="card">
    <h2>Your Current Bookings</h2>
    <?php if (count($bookings) === 0): ?>
      <p class="empty">You have no active bookings.</p>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr><th>ID</th><th>Room</th><th>Check-in</th><th>Check-out</th><th>Nights</th><th>Remaining</th><th>Amount (₵)</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach ($bookings as $b): 
          $nights = nightsBetween($b['checkin'], $b['checkout']);
          $remaining = daysRemaining(date('Y-m-d'), $b['checkout']);
          $price = $b['price'] ?? 0;
          $total = $b['total_price'] ?? ($price * $nights);
        ?>
          <tr id="booking-<?= $b['id'] ?>">
            <td>#<?= $b['id'] ?></td>
            <td><?= htmlspecialchars(ucfirst($b['room_type'])) ?></td>
            <td><?= htmlspecialchars($b['checkin']) ?></td>
            <td><?= htmlspecialchars($b['checkout']) ?></td>
            <td><?= $nights ?></td>
            <td>
              <?php if ($remaining > 0): ?>
                <span class="small"><?= $remaining ?> days left</span>
              <?php elseif ($remaining === 0): ?>
                <span class="small">Last day</span>
              <?php else: ?>
                <span class="small" style="color:#c00;">Expired</span>
              <?php endif; ?>
            </td>
            <td>₵<?= number_format($total, 2) ?></td>
            <td>
              <button class="btn primary" onclick="bookingAction('checkin', <?= $b['id'] ?>)">Check-in</button>
              <button class="btn warn" onclick="bookingAction('checkout', <?= $b['id'] ?>)">Check-out</button><br><br>
              <input type="number" id="extend-days-<?= $b['id'] ?>" class="input-inline" min="1" placeholder="days">
              <button class="btn primary" onclick="extendBooking(<?= $b['id'] ?>)">Pay & Extend</button>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <!-- Booking History -->
  <div class="card">
    <h2>Booking History</h2>
    <?php if (count($history) === 0): ?>
      <p class="empty">No past bookings yet.</p>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr><th>ID</th><th>Room</th><th>Check-in</th><th>Check-out</th><th>Amount (₵)</th></tr>
        </thead>
        <tbody>
        <?php foreach ($history as $h): 
          $nights = nightsBetween($h['checkin'], $h['checkout']);
          $price = $h['price'] ?? 0;
          $total = $h['total_price'] ?? ($price * $nights);
        ?>
          <tr>
            <td>#<?= $h['id'] ?></td>
            <td><?= htmlspecialchars($h['room_type']) ?></td>
            <td><?= htmlspecialchars($h['checkin']) ?></td>
            <td><?= htmlspecialchars($h['checkout']) ?></td>
            <td>₵<?= number_format($total, 2) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <!-- Meal Orders -->
  <div class="card">
    <h2>Meal Order History</h2>
    <?php if (count($orders) === 0): ?>
      <p class="empty">You have no meal orders yet.</p>
    <?php else: ?>
      <table class="table">
        <thead><tr><th>ID</th><th>Meal</th><th>Quantity</th><th>Date</th></tr></thead>
        <tbody>
        <?php foreach ($orders as $o): ?>
          <tr>
            <td>#<?= $o['id'] ?></td>
            <td><?= htmlspecialchars($o['meal_name'] ?? 'Unknown') ?></td>
            <td><?= $o['quantity'] ?></td>
            <td><?= $o['order_date'] ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>

<script>
async function bookingAction(action, bookingId) {
    if (!confirm(`Are you sure you want to ${action} this booking?`)) return;

    const formData = new FormData();
    formData.append('action', action);
    formData.append('booking_id', bookingId);

    const res = await fetch('booking_action.php', { method: 'POST', body: formData });
    const text = await res.text();
    alert(text);
    if (res.ok) location.reload();
}

async function extendBooking(bookingId) {
    const daysInput = document.getElementById('extend-days-' + bookingId);
    const days = parseInt(daysInput.value);
    if (!days || days < 1) { alert('Enter a valid number of days'); return; }
    if (!confirm('Pay and extend booking by ' + days + ' days?')) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'extend_payment.php';

    const bId = document.createElement('input');
    bId.type = 'hidden';
    bId.name = 'booking_id';
    bId.value = bookingId;
    form.appendChild(bId);

    const d = document.createElement('input');
    d.type = 'hidden';
    d.name = 'days';
    d.value = days;
    form.appendChild(d);

    document.body.appendChild(form);
    form.submit();
}
</script>
</body>
</html>
