<?php
session_start();
require_once '../config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../admin_login.php');
    exit();
}

try {
    // 1. Total Users
    $stmt = $conn->query("SELECT COUNT(*) FROM users");
    $total_users = $stmt->fetchColumn();

    // 2. Total Bookings
    $stmt = $conn->query("SELECT COUNT(*) FROM bookings");
    $total_bookings = $stmt->fetchColumn();

    // 3. Room Stats
    $stmt = $conn->query("
        SELECT room_type, COUNT(*) as total, 
               SUM(available) as available,
               SUM(total - available) as occupied,
               MAX(price) as price
        FROM rooms
        GROUP BY room_type
    ");
    $room_types = [];
    $available_rooms = 0;
    $occupied_rooms = 0;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $room_types[$row['room_type']] = [
            'total'     => $row['total'],
            'available' => $row['available'],
            'occupied'  => $row['occupied'],
            'price'     => $row['price']
        ];
        $available_rooms += $row['available'];
        $occupied_rooms  += $row['occupied'];
    }

    // 4. Recent Bookings
$stmt = $conn->query("
    SELECT id, name, email, phone, room_type, checkin, checkout, created_at
    FROM bookings
    ORDER BY created_at DESC
    LIMIT 5
");
$recent_bookings = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $recent_bookings[] = [
        'id'         => $row['id'],
        'name'       => $row['name'],
        'email'      => $row['email'],
        'phone'      => $row['phone'],
        'room_type'  => ucfirst($row['room_type']),
        'checkin'    => $row['checkin'],
        'checkout'   => $row['checkout'],
        'created_at' => $row['created_at']
    ];
}


} catch (PDOException $e) {
    die("Error loading dashboard data: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Echo Bay Lodge</title>
    <link rel="stylesheet" href="../CSS/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <!-- Header -->
        <div class="admin-header">
            <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
            <p>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?>!</p>
        </div>

        <!-- Navigation -->
        <div class="admin-nav">
            <ul>
                <li><a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="./add_room.php"><i class="fas fa-bed"></i> Room Management</a></li>
                <li><a href="./manage_meals.php"><i class="fas fa-calendar-check"></i> Meals</a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="../logout.php" style="color: #dc3545;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Statistics Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="stat-number"><?php echo $total_users; ?></div>
                <p class="stat-label">Registered users</p>
            </div>
            
            <div class="stat-card">
                <h3>Total Bookings</h3>
                <div class="stat-number"><?php echo $total_bookings; ?></div>
                <p class="stat-label">All-time Reservations</p>
            </div>
            
            <div class="stat-card">
                <h3>Available Rooms</h3>
                <div class="stat-number"><?php echo $available_rooms; ?></div>
                <p class="stat-label">Ready for Booking</p>
            </div>
            
            <div class="stat-card">
                <h3>Occupied Rooms</h3>
                <div class="stat-number"><?php echo $occupied_rooms; ?></div>
                <p class="stat-label">Currently Occupied</p>
            </div>
        </div>

        <!-- Room Availability Section -->
        <div class="admin-section">
            <h2><i class="fas fa-bed"></i> Room Availability</h2>
            <div class="room-status-grid">
                <?php foreach ($room_types as $type => $details): ?>
                <div class="room-status-card">
                    <h3><?php echo ucfirst($type); ?> Rooms</h3>
                    <div class="room-details">
                        <div class="room-detail"><span>Total Rooms:</span><span><?php echo $details['total']; ?></span></div>
                        <div class="room-detail"><span>Available:</span><span class="room-available"><?php echo $details['available']; ?></span></div>
                        <div class="room-detail"><span>Occupied:</span><span class="room-occupied"><?php echo $details['occupied']; ?></span></div>
                        <div class="room-detail"><span>Price per Night:</span><span>₵<?php echo number_format($details['price']); ?></span></div>
                        <div class="room-detail"><span>Occupancy Rate:</span><span><?php echo round(($details['occupied'] / $details['total']) * 100); ?>%</span></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Recent Bookings Section -->
        <div class="admin-section">
            <h2><i class="fas fa-calendar-check"></i> Recent Bookings</h2>
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Guest Name</th>
                            <th>Room Type</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php if (count($recent_bookings) > 0): ?>
        <?php foreach ($recent_bookings as $booking): ?>
        <tr>
            <td><?php echo htmlspecialchars($booking['id']); ?></td>
            <td><?php echo htmlspecialchars($booking['name']); ?></td>
            <td><?php echo htmlspecialchars($booking['room_type']); ?></td>
            <td><?php echo htmlspecialchars($booking['checkin']); ?></td>
            <td><?php echo htmlspecialchars($booking['checkout']); ?></td>
            <td>-</td> <!-- Status not in table -->
            <td>-</td> <!-- Amount not in table -->
            <td>
                <a href="view_booking.php?id=<?php echo $booking['id']; ?>" class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
                <a href="edit_booking.php?id=<?php echo $booking['id']; ?>" class="btn btn-success"><i class="fas fa-edit"></i> Edit</a>
                <a href="./delete_booking.php?id=<?php echo $booking['id']; ?>" class="btn btn-primary">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="8">No recent bookings found.</td></tr>
    <?php endif; ?>
</tbody>

                </table>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="admin-section">
            <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="add_room.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Room</a>
                <a href="../Html/create_admin.php" class="btn btn-success"><i class="fas fa-user-plus"></i> Create Admin</a>
                <a href="../Html/manage_meals.php" class="btn btn-success"><i class="fas fa-user-plus"></i>Manage Meals</a>
            </div>
        </div>

        <!-- System Status Section -->
        <div class="admin-section">
            <h2><i class="fas fa-server"></i> System Status</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div><h4>Database</h4><p style="color: #28a745;"><i class="fas fa-check-circle"></i> Connected</p></div>
                <div><h4>Server</h4><p style="color: #28a745;"><i class="fas fa-check-circle"></i> Online</p></div>
                <div><h4>Last Backup</h4><p>2024-01-14 23:59</p></div>
                <div><h4>Uptime</h4><p>99.9%</p></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading animation to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(btn => {
                btn.addEventListener('click', function() {
                    this.classList.add('loading');
                    setTimeout(() => {
                        this.classList.remove('loading');
                    }, 1000);
                });
            });

            // Example auto-refresh (could be replaced with AJAX)
            setInterval(() => {
                console.log('Refreshing room availability data...');
            }, 30000);
        });
    </script>
</body>
</html>
