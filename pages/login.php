<?php
require '../config/session.php';
requireGuest();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../config/db_config.php';
    
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    $sql = "SELECT id, password FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            // Redirect based on selected login type
            $user_type = $_POST['user_type'] ?? 'user';
            if ($email === 'admin@knitshop.com' && $user_type === 'admin') {
                header("Location: admin-dashboard.php");
            } else {
                header("Location: user-dashboard.php");
            }
            exit();
        }
    }
    $error = "Invalid email or password";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Knit Accessories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gradient-to-r from-pink-100 to-blue-100 min-h-screen flex items-center justify-center">
    <div class="bg-white bg-opacity-80 rounded-lg shadow-lg p-8 w-full max-w-md">
        <h1 class="text-3xl font-bold text-center text-pink-400 mb-6">Login</h1>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="radio" name="user_type" value="user" checked class="form-radio text-pink-500">
                <span class="ml-2">User Login</span>
            </label>
            <label class="inline-flex items-center ml-6">
                <input type="radio" name="user_type" value="admin" class="form-radio text-pink-500">
                <span class="ml-2">Admin Login</span>
            </label>
        </div>
        <form method="POST" class="space-y-4">
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
                Login
            </button>
        </form>
        
        <p class="mt-4 text-center text-gray-600">
            Don't have an account? <a href="register.php" class="text-pink-400 hover:underline">Register here</a>
        </p>
    </div>
</body>
</html>