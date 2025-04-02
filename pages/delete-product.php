<?php
require '../config/session.php';
requireLogin();
require '../config/db_config.php';

// Verify admin
if ($_SESSION['email'] !== 'admin@knitshop.com') {
    header("Location: user-dashboard.php");
    exit();
}

// Get and validate product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($product_id <= 0) {
    header("Location: admin-dashboard.php?error=Invalid+product");
    exit();
}

// Use prepared statement
$stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $product_id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: admin-dashboard.php?success=Product+deleted");
} else {
    header("Location: admin-dashboard.php?error=Delete+failed");
}
exit();
?>