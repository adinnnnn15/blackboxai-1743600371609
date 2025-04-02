<?php
require '../config/session.php';
requireGuest();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../config/db_config.php';
    
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['user_id'] = mysqli_insert_id($conn);
        header("Location: user-dashboard.php");
        exit();
    } else {
        $error = "Registration failed: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Knit Accessories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gradient-to-r from-pink-100 to-blue-100 min-h-screen flex items-center justify-center">
    <div class="bg-white bg-opacity-80 rounded-lg shadow-lg p-8 w-full max-w-md">
        <h1 class="text-3xl font-bold text-center text-pink-400 mb-6">Register</h1>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="space-y-4">
            <div>
                <label for="username" class="block text-gray-700 mb-2">Username</label>
                <input type="text" id="username" name="username" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-300 focus:border-transparent">
            </div>
            
            <div>
                <label for="email" class="block text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-300 focus:border-transparent">
            </div>
            
            <div>
                <label for="password" class="block text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-300 focus:border-transparent">
            </div>
            
            <button type="submit" 
                class="w-full bg-pink-400 text-white py-2 px-4 rounded-lg hover:bg-pink-500 transition duration-200">
                Register
            </button>
        </form>
        
        <p class="mt-4 text-center text-gray-600">
            Already have an account? <a href="login.php" class="text-pink-400 hover:underline">Login here</a>
        </p>
    </div>
</body>
</html>