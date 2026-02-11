<?php
session_start();
require_once '../config.php';

// Only allow logged-in admins to add other admins
if (!isset($_SESSION['admin_id']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../admin_login.php');
    exit();
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['first_name']);
    $lname = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $conn->prepare("INSERT INTO admins (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$fname, $lname, $email, $password_hash]);
            $success = "Admin user created successfully!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                $error = "Email already exists!";
            } else {
                $error = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Admin - Echo Bay Lodge</title>
    <link rel="stylesheet" href="../CSS/admin_forms.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="极://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-form-container">
        <!-- Header -->
        <div class="admin-form-header">
            <h1><i class="fas fa-user-plus"></i> Create Admin User</h1>
            <p>Add a new administrator to the system</p>
        </div>

        <!-- Navigation -->
        <div class="admin-form-nav">
            <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="add_room.php"><i class="fas fa-bed"></i> Room Management</a></li>
                <li><a href="create_admin.php" class="active"><i class="fas fa-user-plus"></i> Create Admin</a></li>
                <li><a href="../logout.php" style="color: #dc3545;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Create Admin Form -->
        <div class="form-section">
            <h2><i class="fas fa-user-shield"></i> New Administrator</h2>
            
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

            <form method="post" class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" required 
                               placeholder="Enter first name">
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required 
                               placeholder="Enter last name">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="admin@example.com">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required 
                               placeholder="Enter password" minlength="6">
                        <div class="password-hint">
                            <small>Minimum 6 characters</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required 
                               placeholder="Confirm password">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Create Admin
                    </button>
                    <a href="admin_dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </form>

            <div class="form-footer">
                <p>
                    <i class="fas fa-info-circle"></i>
                    New admins will have full access to the management dashboard and system settings.
                </p>
            </div>
        </div>
    </div>

    <script>
        // JavaScript for password validation
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            const form = document.querySelector('.admin-form');

            form.addEventListener('submit', function(e) {
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    alert('Passwords do not match!');
                    confirmPassword.focus();
                }
            });

            // Real-time password matching
            confirmPassword.addEventListener('input', function() {
                if (password.value !== this.value) {
                    this.style.borderColor = '#dc3545';
                } else {
                    this.style.borderColor = '#28a745';
                }
            });

            // Password strength indicator
            password.addEventListener('input', function() {
                const strengthBar = document.createElement('div');
                strengthBar.className = 'password-strength-bar';
                
                let strength = 0;
                if (this.value.length >= 6) strength++;
                if (this.value.match(/[a-z]/)) strength++;
                if (this.value.match(/[A-Z]/)) strength++;
                if (this.value.match(/[0-9]/)) strength++;
                if (this.value.match(/[^a-zA-Z0-9]/)) strength++;

                const strengthColors = ['#dc3545', '#ffc107', '#ffc107', '#17a2b8', '#28a745'];
                strengthBar.style.width = (strength * 20) + '%';
                strengthBar.style.background = strengthColors[strength - 1] || '#dc3545';
                
                const existingBar = document.querySelector('.password-strength-bar');
                if (existingBar) {
                    existingBar.parentNode.removeChild(existingBar);
                }
                
                const strengthContainer = document.createElement('div');
                strengthContainer.className = 'password-strength';
                strengthContainer.appendChild(strengthBar);
                
                this.parentNode.appendChild(strengthContainer);
            });
        });
    </script>
</body>
</html>
