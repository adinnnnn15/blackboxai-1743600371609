<?php
require '../config/session.php';
requireLogin();
require '../config/db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $action = $_POST['action'] ?? 'add';

    if ($action === 'add') {
        $stmt = mysqli_prepare($conn, "INSERT IGNORE INTO wishlists (user_id, product_id) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
        mysqli_stmt_execute($stmt);
    } elseif ($action === 'remove') {
        $stmt = mysqli_prepare($conn, "DELETE FROM wishlists WHERE user_id = ? AND product_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
        mysqli_stmt_execute($stmt);
    }
}

// Redirect back to previous page
header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../index.php'));
exit();
?>