<?php
require_once 'config.php';

try {
    
    $createRoomsTable = "
    CREATE TABLE IF NOT EXISTS rooms (
        id INT AUTO_INCREMENT PRIMARY KEY,
        room_type ENUM('standard', 'deluxe', 'suite') NOT NULL,
        total INT NOT NULL,
        available INT NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $conn->exec($createRoomsTable);
    echo "Rooms table created successfully.<br>";

    
    $rooms = [
        ['standard', 20, 8, 1000.00],
        ['deluxe', 15, 3, 1500.00],
        ['suite', 10, 1, 2000.00]
    ];

    $stmt = $conn->prepare("
        INSERT INTO rooms (room_type, total, available, price) 
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE available = VALUES(available)
    ");

    foreach ($rooms as $room) {
        $stmt->execute($room);
    }
    echo "Sample room data inserted successfully.<br>";

        $createBookingsTable = "
    CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        room_id INT NOT NULL,
        guest_name VARCHAR(100) NOT NULL,
        guest_email VARCHAR(100) NOT NULL,
        guest_phone VARCHAR(20) NOT NULL,
        check_in DATE NOT NULL,
        check_out DATE NOT NULL,
        status ENUM('confirmed', 'checked_in', 'checked_out', 'cancelled') DEFAULT 'confirmed',
        total_amount DECIMAL(10, 2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (room_id) REFERENCES rooms(id)
    )";
    
    $conn->exec($createBookingsTable);
    echo "Bookings table created successfully.<br>";

    
    $checkRoleColumn = $conn->query("SHOW COLUMNS FROM users LIKE 'role'");
    if ($checkRoleColumn->rowCount() == 0) {
        $conn->exec("ALTER TABLE users ADD COLUMN role ENUM('user', 'admin') DEFAULT 'user'");
        echo "Role column added to users table.<br>";
        
        // Set the first user as admin for testing
        $conn->exec("UPDATE users SET role = 'admin' WHERE id = 1 LIMIT 1");
        echo "Admin user set (first user in database).<br>";
    }

    echo "<h3>Database initialization completed successfully!</h3>";
    echo "<p>You can now access the admin dashboard using:</p>";
    echo "<ul>";
    echo "<li>Email: admin@echobay.com</li>";
    echo "<li>Password: admin123</li>";
    echo "</ul>";
    echo "<p>Or use the first user in your database as admin.</p>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
