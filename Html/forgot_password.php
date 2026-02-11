<?php
session_start();
require_once "../config.php";

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = "";

// Handle POST submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("<p style='color:red'>Invalid CSRF token</p>");
    }

    $email = trim($_POST['email']);

    if (empty($email)) {
        $message = "<p style='color:red'>Please enter your email address.</p>";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Generate a test token (in production, email this)
            $reset_token = bin2hex(random_bytes(16));
            $expiry = date('Y-m-d H:i:s', time() + 3600); // expires in 1 hour

            // Store in database (create columns if missing: reset_token, reset_expiry)
            $stmt = $conn->prepare("UPDATE users SET reset_token = :token, reset_expiry = :expiry WHERE id = :id");
            $stmt->execute([
                ':token' => $reset_token,
                ':expiry' => $expiry,
                ':id' => $user['id']
            ]);

            // Test link
            $reset_link = "http://localhost/Online_Hotel_Booking/Html/reset_password.php?token=$reset_token";

            $message = "<p style='color:green'>
                Reset link: <a href='$reset_link'>$reset_link</a><br>
            </p>";
        } else {
            $message = "<p style='color:red'>No account found with that email.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Echo Bay Lodge</title>
    <link rel="stylesheet" href="../CSS/password.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-form-container">
        <h2>Forgot Password</h2>
        <p>Enter your registered email to reset your password</p>

        <?= $message ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="Enter your email">
            </div>

            <button type="submit" class="auth-btn">Send Reset Link</button>
        </form>

        <p class="auth-switch"><a href="login.php">Back to Login</a></p>
    </div>
</div>
</body>
</html>
