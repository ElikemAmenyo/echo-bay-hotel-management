<?php
// logout.php - Handle user/logout
session_start();

$redirect = "login.php"; // default redirect

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    $redirect = "admin_login.php";
}

session_unset();
session_destroy();

// Redirect based on role
header("Location: " . $redirect);
exit();
