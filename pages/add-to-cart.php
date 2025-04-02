<?php
require '../config/session.php';
requireLogin();
require '../config/db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);

    // Check if item already in cart
    $check_sql = "SELECT id, quantity FROM carts WHERE user_id = ? AND product_id = ?";
    $stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        // Update quantity if exists
        $row = mysqli_fetch_assoc($result);
        $new_quantity = $row['quantity'] + 1;
        $update_sql = "UPDATE carts SET quantity = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($stmt, "ii", $new_quantity, $row['id']);
    } else {
        // Add new item to cart
        $insert_sql = "INSERT INTO carts (user_id, product_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $insert_sql);
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
    }
    
    mysqli_stmt_execute($stmt);
}

// Redirect back to product page or cart
header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'cart.php'));
exit();
?>