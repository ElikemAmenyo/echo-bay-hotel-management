<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once "config.php"; 

// Only handle POST requests
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("<p style='color:red'>Invalid CSRF token</p>");
}
    // Sanitize and validate inputs
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, first_name, last_name, email, password_hash 
                                FROM users 
                                WHERE email = :email 
                                LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the password
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . " " . $user['last_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['just_logged_in'] = true;
                // Handle "Remember Me" functionality
                if (isset($_POST['remember'])) {
                    setcookie('remember_email', $email, time() + (30 * 24 * 60 * 60), "/"); // 30 days
                } else {
                    setcookie('remember_email', '', time() - 3600, "/"); // delete cookie
                }   

                header("Location: ./Html/index.php");
                exit();
            } else {
                echo "<p style='color:red'>Invalid email or password</p>";
            }
        } else {
            echo "<p style='color:red'>Invalid email or password</p>";
        }
    } else {
        echo "<p style='color:red'>Please enter both email and password</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Echo Bay Lodge</title>
    <link rel="stylesheet" href="CSS/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-left">
            <div class="auth-logo">
                <img src="images/Logo/Logo.png" alt="Echo Bay Lodge">
            </div>
            <div class="auth-welcome">
                <h1>Welcome Back</h1>
                <p>Sign in to your account to continue your booking experience</p>
            </div>
            <div class="auth-features">
                <div class="feature"><i class="fas fa-bed"></i><span>Book luxury rooms</span></div>
                <div class="feature"><i class="fas fa-calendar-check"></i><span>Manage reservations</span></div>
                <div class="feature"><i class="fas fa-star"></i><span>Exclusive member benefits</span></div>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-form-container">
                <h2>Sign In</h2>
                <p class="auth-subtitle">Enter your credentials to access your account</p>

                <?php if (isset($_GET['error'])): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>

                <form id="loginForm" class="auth-form" method="POST" action="login.php" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                    <div class="form-group">
                        <label for="loginEmail">Email Address</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope"></i>
                            <input
                                type="email"
                                id="loginEmail"
                                name="email"
                                required
                                placeholder="Enter your email"
                                value="<?php echo isset($_COOKIE['remember_email']) ? htmlspecialchars($_COOKIE['remember_email']) : ''; ?>"
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="loginPassword">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="loginPassword" name="password" required placeholder="Enter your password">
                            <i class="fas fa-eye toggle-password" data-target="loginPassword"></i>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" id="rememberMe" name="remember" <?php echo isset($_COOKIE['remember_email']) ? 'checked' : ''; ?>>
                            <span class="checkmark"></span>
                            Remember me
                        </label>
                        <a href="./Html/forgot_password.php" class="forgot-link">Forgot password?</a>
                    </div>

                    <button type="submit" class="auth-btn">
                        <span class="btn-text">Sign In</span>
                        <i class="fas fa-spinner fa-spin" style="display: none;"></i>
                    </button>
                </form>

                <div class="auth-divider"><span>or continue with</span></div>

                <div class="social-login">
                    <button class="social-btn google" onclick="window.location.href='PHP/google_login.php'">
                        <i class="fab fa-google"></i> Google
                    </button>
                    <button class="social-btn facebook" onclick="window.location.href='PHP/facebook_login.php'">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </button>
                </div>

                <p class="auth-switch">
                    Don't have an account? <a href="signup.php">Sign up here</a><br>
                    <a href="./admin_login.php">Admin Login</a>
                </p>
            </div>
        </div>
    </div>

    <!-- If JS intercepts the form via AJAX, make sure it POSTS to login.php and handles CSRF -->
    <script>
        // basic toggle
        document.addEventListener('click', (e) => {
            const icon = e.target.closest('.toggle-password');
            if (!icon) return;
            const id = icon.getAttribute('data-target');
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye-slash');
        });
    </script>
    <script src="js/auth-ajax.js"></script>
    <script src="js/auth.js"></script>
</body>
</html>
