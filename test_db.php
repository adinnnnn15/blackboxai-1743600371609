<?php
require 'config/db_config.php';

echo "<h2>Database Connection Test</h2>";

// Test connection
if ($conn) {
    echo "<p style='color:green'>✓ Connected to database successfully</p>";
    
    // Test if database exists
    $result = mysqli_query($conn, "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'knit_shop'");
    if (mysqli_num_rows($result) > 0) {
        echo "<p style='color:green'>✓ Database 'knit_shop' exists</p>";
        
        // Test if users table exists
        mysqli_select_db($conn, "knit_shop");
        $result = mysqli_query($conn, "SHOW TABLES LIKE 'users'");
        if (mysqli_num_rows($result) > 0) {
            echo "<p style='color:green'>✓ Table 'users' exists</p>";
            
            // Test sample query
            $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
            $row = mysqli_fetch_assoc($result);
            echo "<p>Found {$row['count']} user(s) in database</p>";
        } else {
            echo "<p style='color:red'>✗ Table 'users' doesn't exist</p>";
        }
    } else {
        echo "<p style='color:red'>✗ Database 'knit_shop' doesn't exist</p>";
    }
} else {
    echo "<p style='color:red'>✗ Connection failed</p>";
    echo "<p>Error: " . mysqli_connect_error() . "</p>";
}

echo "<h3>Recommendations:</h3>";
echo "<ul>";
echo "<li>Import knit_shop.sql to create database structure</li>";
echo "<li>Verify MySQL server is running</li>";
echo "<li>Check db_config.php credentials</li>";
echo "</ul>";
?>