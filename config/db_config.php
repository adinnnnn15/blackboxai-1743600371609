<?php
// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', ''); // Set your MySQL password if exists
define('DB_NAME', 'knit_shop');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection with error handling
try {
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    if (!$conn) {
        throw new Exception("Database connection failed: " . mysqli_connect_error());
    }
    
    // Set charset to utf8
    mysqli_set_charset($conn, "utf8mb4");
    
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}
?>
