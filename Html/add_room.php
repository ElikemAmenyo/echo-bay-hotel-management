<?php
session_start();
require_once '../config.php';

// ✅ Remove debug exit
// var_dump($_SESSION); exit;

// ✅ Only check session, don’t overwrite it here
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) { 
    header('Location: ../admin_login.php'); 
    exit(); 
}

$error = "";
$success = "";

// Add new room
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_room'])) { 
    $room_type = $_POST['room_type']; 
    $total = (int)$_POST['total']; 
    $available = (int)$_POST['available'];
    $price = (float)$_POST['price'];

    try {
        $stmt = $conn->prepare("INSERT INTO rooms (room_type, total, available, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$room_type, $total, $available, $price]);
        $success = "Room added successfully!";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Delete room
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    try {
        $stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
        $stmt->execute([$delete_id]);
        $success = "Room deleted successfully!";
    } catch (PDOException $e) {
        $error = "Error deleting room: " . $e->getMessage();
    }
}

// Fetch all rooms
$stmt = $conn->query("SELECT * FROM rooms ORDER BY created_at DESC");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Rooms - Echo Bay Lodge</title>
    <link rel="stylesheet" href="../CSS/room_management.css">
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

    <div class="room-management-container">
        <!-- Header -->
        <div class="room-management-header">
            <h1><i class="fas fa-bed"></i> Room Management</h1>
            <p>Manage room availability and pricing</p>
        </div>

        <!-- Navigation -->
        <div class="room-management-nav">
            <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="add_room.php" class="active"><i class="fas fa-bed"></i> Room Management</a></li>
                <li><a href="manage_meals.php"><i class="fas fa-calendar-check"></i> Meals</a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="../logout.php" style="color: #dc3545;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Add Room Form -->
        <div class="room-form-section">
            <h2><i class="fas fa-plus-circle"></i> Add New Room</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="post" class="room-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="room_type">Room Type</label>
                        <select id="room_type" name="room_type" required>
                            <option value="standard">Standard</option>
                            <option value="deluxe">Deluxe</option>
                            <option value="suite">Suite</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="total">Total Rooms</label>
                        <input type="number" id="total" name="total" required min="1">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="available">Available Rooms</label>
                        <input type="number" id="available" name="available" required min="0">
                    </div>

                    <div class="form-group price-input">
                        <label for="price">Price per Night (₵)</label>
                        <input type="number" id="price" name="price" step="0.01" required min="0">
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" name="add_room" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Room
                    </button>
                </div>
            </form>
        </div>

        <!-- Existing Rooms Table -->
        <div class="rooms-table-section">
            <h2><i class="fas fa-list"></i> Existing Rooms</h2>
            
            <div class="table-container">
                <table class="rooms-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Room Type</th>
                            <th>Total</th>
                            <th>Available</th>
                            <th>Price (₵)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($rooms) > 0): ?>
                            <?php foreach ($rooms as $room): ?>
                            <tr>
                                <td><?php echo $room['id']; ?></td>
                                <td><?php echo ucfirst($room['room_type']); ?></td>
                                <td><?php echo $room['total']; ?></td>
                                <td>
                                    <span class="<?php echo $room['available'] > 0 ? 'room-available' : 'room-occupied'; ?>">
                                        <?php echo $room['available']; ?>
                                    </span>
                                </td>
                                <td>₵<?php echo number_format($room['price'], 2); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="add_room.php?delete_id=<?php echo $room['id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Are you sure you want to delete this room?');">
                                           <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <i class="fas fa-bed"></i>
                                    <p>No rooms found. Add your first room above.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // JavaScript for form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.room-form');
            const totalInput = document.getElementById('total');
            const availableInput = document.getElementById('available');
            
            form.addEventListener('submit', function(e) {
                const total = parseInt(totalInput.value);
                const available = parseInt(availableInput.value);
                
                if (available > total) {
                    e.preventDefault();
                    alert('Available rooms cannot exceed total rooms!');
                    availableInput.focus();
                }
            });
            
            // Auto-update available rooms when total changes
            totalInput.addEventListener('change', function() {
                const total = parseInt(this.value);
                const available = parseInt(availableInput.value);
                
                if (available > total) {
                    availableInput.value = total;
                }
            });
        });
    </script>
</body>
</html>
