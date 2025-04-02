<?php
session_start();

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /pages/login.php");
        exit();
    }
}

// Redirect if logged in
function requireGuest() {
    if (isLoggedIn()) {
        header("Location: /pages/admin-dashboard.php");
        exit();
    }
}
?>