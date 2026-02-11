<?php
session_start();
require_once "../config.php";

$token = $_GET['token'] ?? '';
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'] ?? '';
    $new_password = trim($_POST['password']);

    if (empty($new_password) || empty($token)) {
        $message = "<p style='color:red'>Invalid or missing fields.</p>";
    } else {
        $stmt = $conn->prepare("SELECT id, reset_expiry FROM users WHERE reset_token = :token LIMIT 1");
        $stmt->execute([':token' => $token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && strtotime($user['reset_expiry']) > time()) {
            $hash = password_hash($new_password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE users 
                SET password_hash = :hash, reset_token = NULL, reset_expiry = NULL 
                WHERE id = :id");
            $stmt->execute([':hash' => $hash, ':id' => $user['id']]);

            $message = "<p style='color:green'>✅ Password reset successfully! <a href='login.php'>Login now</a>.</p>";
        } else {
            $message = "<p style='color:red'>Invalid or expired token.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password</title>
<link rel="stylesheet" href="../CSS/password.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-form-container">
        <h2>Reset Password</h2>
        <?= $message ?>
        <?php if (!$_POST): ?>
        <form method="POST" action="">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token); ?>">
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" required placeholder="Enter new password">
            </div>
            <button type="submit" class="auth-btn">Reset Password</button>
        </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
