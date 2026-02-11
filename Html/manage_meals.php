<?php
session_start();
require_once "../config.php";

// Only allow admins
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../admin_login.php");
    exit();
} 

// ==========================
// Handle Add Meal 
// ==========================
if (isset($_POST['add_meal'])) { 
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $availability = isset($_POST['availability']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO meals (name, description, category, price, availability) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $desc, $category, $price, $availability]);
}

// ==========================
// Handle Delete Meal
// ==========================
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM meals WHERE id=?");
    $stmt->execute([$id]);
    header("Location: manage_meals.php");
    exit();
}

// ==========================
// Fetch all meals
// ==========================
$stmt = $conn->query("SELECT * FROM meals ORDER BY created_at DESC");
$meals = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==========================
// Fetch all orders
// ==========================
$sql = "SELECT o.id, u.first_name, u.last_name, m.name AS meal_name, o.quantity, o.order_date 
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN meals m ON o.meal_id = m.id
        ORDER BY o.order_date DESC";
$stmt = $conn->query($sql);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Meals - Admin Dashboard</title>
    <link rel="stylesheet" href="../CSS/manage_meals.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="admin-container">
        <!-- Header -->
        <div class="admin-header">
            <h1><i class="fas fa-tachometer-alt"></i>Manage Meals</h1>
            <p>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?>!</p>
        </div>

      <!-- Navigation -->
        <div class="admin-nav">
            <ul>
                <li><a href="./admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="./add_room.php"><i class="fas fa-bed"></i> Room Management</a></li>
                <li><a href="./meals.php"  class="active"><i class="fas fa-calendar-check"></i> Meals</a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
                <li><a href="../logout.php" style="color: #dc3545;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

<div class="meals-container">
    <!-- Add Meal Form -->
    <form method="POST">
        <input type="text" name="name" placeholder="Meal Name" required>
        <input type="text" name="category" placeholder="Category (e.g., Breakfast)" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <label><input type="checkbox" name="availability" checked> Available</label>
        <button type="submit" name="add_meal" class="btn btn-add">Add Meal</button>
    </form>

    <!-- Meals Table -->
    <h2>📋 Meals List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Meal Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Price</th>
                <th>Available</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($meals): ?>
                <?php foreach ($meals as $meal): ?>
                    <tr>
                        <td><?= $meal['id'] ?></td>
                        <td><?= htmlspecialchars($meal['name']) ?></td>
                        <td><?= htmlspecialchars($meal['category']) ?></td>
                        <td><?= htmlspecialchars($meal['description']) ?></td>
                        <td>$<?= number_format($meal['price'], 2) ?></td>
                        <td><?= $meal['availability'] ? "✅ Yes" : "❌ No" ?></td>
                        <td>
                            <a href="manage_meals.php?delete=<?= $meal['id'] ?>" class="btn btn-del" onclick="return confirm('Delete this meal?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No meals added yet</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Orders Table -->
    <h2 style="margin-top:40px;">📑 User Orders</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Meal</th>
                <th>Quantity</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($orders): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['first_name'] . " " . $order['last_name']) ?></td>
                        <td><?= htmlspecialchars($order['meal_name']) ?></td>
                        <td><?= $order['quantity'] ?></td>
                        <td><?= $order['order_date'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No orders yet</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
