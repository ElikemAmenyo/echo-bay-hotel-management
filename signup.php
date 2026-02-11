<?php
session_start();
require_once 'config.php';

// Initialize variables
$errors = [];
$input = [];
$success = false;

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) { 
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
} 

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = 'Invalid form submission';
    } else {
        // Sanitize inputs
        $input = [
            'firstName' => trim($_POST['firstName'] ?? ''),
            'lastName' => trim($_POST['lastName'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phoneNumber' => trim($_POST['phoneNumber'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'confirmPassword' => $_POST['confirmPassword'] ?? '',
            'agreeTerms' => isset($_POST['agreeTerms']),
            'security_question' => trim($_POST['security_question'] ?? ''),
            'security_answer' => trim($_POST['security_answer'] ?? ''),
        ];

        // Validate inputs
        if (empty($input['firstName'])) {
            $errors['firstName'] = 'First name is required';
        }
        if (empty($input['lastName'])) {
            $errors['lastName'] = 'Last name is required';
        }
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }
        if (strlen($input['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
        }
        if ($input['password'] !== $input['confirmPassword']) {
            $errors['confirmPassword'] = 'Passwords do not match';
        }
        if (empty($input['security_question'])) {
            $errors['security_question'] = 'Please select a security question';
        }
        if (empty($input['security_answer'])) {
            $errors['security_answer'] = 'Please provide an answer to the security question';
        }
        if (!$input['agreeTerms']) {
            $errors['agreeTerms'] = 'You must accept the terms';
        }

        // If all inputs valid, proceed
        if (empty($errors)) {
            try {
                // Check if email exists
                $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
                $check->execute([$input['email']]);
                
                if ($check->rowCount() > 0) {
                    $errors['email'] = 'Email already registered';
                } else {
                    // Hash password and security answer
                    $passwordHash = password_hash($input['password'], PASSWORD_BCRYPT);
                    $hashedAnswer = password_hash($input['security_answer'], PASSWORD_DEFAULT);

                   
                    $stmt = $conn->prepare("
                        INSERT INTO users 
                        (first_name, last_name, email, phone, password_hash, security_question, security_answer)
                        VALUES (:first_name, :last_name, :email, :phone, :password_hash, :security_question, :security_answer)
                    ");
                    
                    $stmt->execute([
                        ':first_name' => $input['firstName'],
                        ':last_name' => $input['lastName'],
                        ':email' => $input['email'],
                        ':phone' => $input['phoneNumber'],
                        ':password_hash' => $passwordHash,
                        ':security_question' => $input['security_question'],
                        ':security_answer' => $hashedAnswer
                    ]);
                    
                    if ($stmt->rowCount() > 0) {
                        $success = true;
                        $_SESSION['registration_success'] = true;
                    } else {
                        throw new Exception('No rows affected');
                    }
                }
            } catch (PDOException $e) {
                error_log("Database error: " . $e->getMessage());
                $errors[] = 'Registration failed. Please try again.';
            } catch (Exception $e) {
                error_log("Error: " . $e->getMessage());
                $errors[] = 'Registration failed. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Echo Bay Lodge</title>
    <link rel="stylesheet" href="./CSS/auth.css">
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
                <h1>Join Echo Bay Lodge</h1>
                <p>Create your account to unlock exclusive member benefits and seamless booking experiences</p>
            </div>
            <div class="auth-benefits">
                <h3>Member Benefits</h3>
                <ul>
                    <li><i class="fas fa-check"></i> Exclusive member rates</li>
                    <li><i class="fas fa-check"></i> Priority booking access</li>
                    <li><i class="fas fa-check"></i> Free room upgrades</li>
                    <li><i class="fas fa-check"></i> Loyalty rewards program</li>
                    <li><i class="fas fa-check"></i> 24/7 dedicated support</li>
                </ul>
            </div>
        </div>
        
        <div class="auth-right">
            <div class="auth-form-container">
                <h2>Create Account</h2>
                <p class="auth-subtitle">Fill in your details to get started</p>
                
                <?php if ($success): ?>
                    <div class="success-message">
                        Registration successful! Redirecting to login page...
                    </div>
                    <script>
                        setTimeout(function() {
                            window.location.href = './login.php';
                        }, 3000);
                    </script>
                <?php else: ?>
                    <?php if (!empty($errors)): ?>
                        <div class="error-message">
                            <strong>Please fix the following errors:</strong>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <?php if (is_string($error)): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form id="signupForm" class="auth-form" method="post">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-user"></i>
                                    <input type="text" id="firstName" name="firstName" required placeholder="First name"
                                           value="<?php echo htmlspecialchars($input['firstName'] ?? ''); ?>"
                                           class="<?php echo isset($errors['firstName']) ? 'error-field' : ''; ?>">
                                </div>
                                <?php if (isset($errors['firstName'])): ?>
                                    <div class="error-message"><?php echo htmlspecialchars($errors['firstName']); ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-user"></i>
                                    <input type="text" id="lastName" name="lastName" required placeholder="Last name"
                                           value="<?php echo htmlspecialchars($input['lastName'] ?? ''); ?>"
                                           class="<?php echo isset($errors['lastName']) ? 'error-field' : ''; ?>">
                                </div>
                                <?php if (isset($errors['lastName'])): ?>
                                    <div class="error-message"><?php echo htmlspecialchars($errors['lastName']); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="email" name="email" required placeholder="Enter your email"
                                       value="<?php echo htmlspecialchars($input['email'] ?? ''); ?>"
                                       class="<?php echo isset($errors['email']) ? 'error-field' : ''; ?>">
                            </div>
                            <?php if (isset($errors['email'])): ?>
                                <div class="error-message"><?php echo htmlspecialchars($errors['email']); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="phoneNumber">Phone Number</label>
                            <div class="input-wrapper">
                                <i class="fas fa-phone"></i>
                                <input type="tel" id="phoneNumber" name="phoneNumber" required placeholder="+1 (555) 123-4567"
                                       value="<?php echo htmlspecialchars($input['phoneNumber'] ?? ''); ?>"
                                       class="<?php echo isset($errors['phoneNumber']) ? 'error-field' : ''; ?>">
                            </div>
                            <?php if (isset($errors['phoneNumber'])): ?>
                                <div class="error-message"><?php echo htmlspecialchars($errors['phoneNumber']); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="password" name="password" required placeholder="Create a password"
                                       class="<?php echo isset($errors['password']) ? 'error-field' : ''; ?>">
                                <i class="fas fa-eye toggle-password" data-target="password"></i>
                            </div>
                            <div class="password-strength">
                                <div class="password-strength-bar" id="passwordStrengthBar"></div>
                            </div>
                            <small class="password-hint">At least 8 characters with one uppercase, one lowercase, and one number</small>
                            <?php if (isset($errors['password'])): ?>
                                <div class="error-message"><?php echo htmlspecialchars($errors['password']); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="confirmPassword" name="confirmPassword" required 
                                       placeholder="Confirm your password"
                                       class="<?php echo isset($errors['confirmPassword']) ? 'error-field' : ''; ?>">
                                <i class="fas fa-eye toggle-password" data-target="confirmPassword"></i>
                            </div>
                            <?php if (isset($errors['confirmPassword'])): ?>
                                <div class="error-message"><?php echo htmlspecialchars($errors['confirmPassword']); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <!--  Security Question Section -->
                         <div class="form-group">
                     <label>Select a Security Question:</label>
                     <div class="input-wrapper">
                     <select name="security_question" required class="select-option">
                        <option value="">-- Select a Question --</option>
                        <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                        <option value="What is your favorite color?">What is your favorite color?</option>
                        <option value="What is the name of your first pet?">What is the name of your first pet?</option>
                        <option value="What city were you born in?">What city were you born in?</option>
                        <option value="What is your favorite food?">What is your favorite food?</option>
                    </select>
                    </div>
                     <?php if (isset($errors['security_question'])): ?>
                         <div class="error-message"><?php echo htmlspecialchars($errors['security_question']); ?></div>
                     <?php endif; ?>
                </div>
                        
                         <div class="form-group">
                            <label>Your Answer:</label>
                            <div class="input-wrapper">
                            <input type="text" name="security_answer" placeholder="Enter your answer" required>
                            </div>
                         </div>
                        
                        <div class="form-group">
                            <label class="checkbox-container terms-checkbox">
                                <input type="checkbox" id="agreeTerms" name="agreeTerms" required
                                       <?php echo isset($input['agreeTerms']) && $input['agreeTerms'] ? 'checked' : ''; ?>>
                                <span class="checkmark"></span>
                                I agree to the <a href="terms.php" class="link">Terms of Service</a> and <a href="privacy.php" class="link">Privacy Policy</a>
                            </label>
                            <?php if (isset($errors['agreeTerms'])): ?>
                                <div class="error-message"><?php echo htmlspecialchars($errors['agreeTerms']); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" class="auth-btn" id="submitBtn">
                            <span class="btn-text">Create Account</span>
                            <i class="fas fa-spinner fa-spin" id="spinner" style="display: none;"></i>
                        </button>
                    </form>
                    
                    <div class="auth-footer">
                        <p>Already have an account? <a href="../Online_Hotel_Booking/login.php" class="link">Log in</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Password visibility toggle
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function() {
                const target = this.getAttribute('data-target');
                const input = document.getElementById(target);
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('fa-eye-slash');
            });
        });

        // Password strength meter
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrengthBar');
            let strength = 0;
            
            if (password.length > 0) strength += 20;
            if (password.length >= 8) strength += 20;
            if (/[A-Z]/.test(password)) strength += 20;
            if (/[a-z]/.test(password)) strength += 20;
            if (/[0-9]/.test(password)) strength += 20;
            
            strengthBar.style.width = strength + '%';
            
            if (strength < 40) {
                strengthBar.style.backgroundColor = '#ff4444';
            } else if (strength < 80) {
                strengthBar.style.backgroundColor = '#ffbb33';
            } else {
                strengthBar.style.backgroundColor = '#00C851';
            }
        });

        // Form submission handler
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const spinner = document.getElementById('spinner');
            
            // Validate passwords match
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return;
            }
            
            //loading spinner
            submitBtn.disabled = true;
            spinner.style.display = 'inline-block';
            document.querySelector('.btn-text').textContent = 'Creating Account...';
        });

        // Phone number formatting
        document.getElementById('phoneNumber').addEventListener('input', function(e) {
            const x = this.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
            this.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        });
    </script>
</body>
</html>