<?php
require '../config/session.php';
requireLogin();

// Get user info
require '../config/db_config.php';
$user_id = $_SESSION['user_id'];
$user = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user_data = mysqli_fetch_assoc($user);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Knit Accessories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gradient-to-r from-pink-100 to-blue-100 min-h-screen">
    <header class="bg-white bg-opacity-80 shadow-sm py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-400">User Dashboard</h1>
            <nav class="flex space-x-4">
                <a href="profile.php" class="px-4 py-2 bg-blue-300 text-white rounded-lg hover:bg-blue-400 transition">
                    <i class="fas fa-user mr-2"></i>Profile
                </a>
                <a href="../index.php" class="px-4 py-2 bg-pink-300 text-white rounded-lg hover:bg-pink-400 transition">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
                <a href="logout.php" class="px-4 py-2 bg-gray-300 text-white rounded-lg hover:bg-gray-400 transition">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-white bg-opacity-80 rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Welcome, <?php echo htmlspecialchars($user_data['username']); ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-pink-50 p-4 rounded-lg">
                    <h3 class="font-medium text-pink-600 mb-2"><i class="fas fa-shopping-cart mr-2"></i>Your Orders</h3>
                    <p class="text-gray-600">View and track your orders</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-medium text-blue-600 mb-2"><i class="fas fa-heart mr-2"></i>Wishlist</h3>
                    <p class="text-gray-600">Your saved items</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <h3 class="font-medium text-purple-600 mb-2"><i class="fas fa-cog mr-2"></i>Account Settings</h3>
                    <p class="text-gray-600">Manage your preferences</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>