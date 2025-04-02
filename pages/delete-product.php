<?php
require '../config/session.php';
requireLogin();

require '../config/db_config.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id > 0) {
    $sql = "DELETE FROM products WHERE id = $product_id";
    mysqli_query($conn, $sql);
}

header("Location: admin-dashboard.php");
exit();
?>