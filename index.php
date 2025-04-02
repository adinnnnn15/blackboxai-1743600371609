<?php
require 'config/session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knit Accessories Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gradient-to-r from-pink-100 to-blue-100 min-h-screen">
    <header class="bg-white bg-opacity-70 shadow-sm py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-pink-400">Knit Accessories</h1>
            <nav>
                <?php if (isLoggedIn()): ?>
                    <a href="pages/user-dashboard.php" class="px-4 py-2 bg-pink-300 text-white rounded-lg hover:bg-pink-400 transition">Dashboard</a>
                <?php else: ?>
                    <a href="pages/login.php" class="px-4 py-2 bg-pink-300 text-white rounded-lg hover:bg-pink-400 transition">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Our Products</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            require 'config/db_config.php';
            $sql = "SELECT * FROM products LIMIT 4";
            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo '
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <img src="'.$row['image_url'].'" alt="'.$row['name'].'" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-xl font-semibold text-gray-800">'.$row['name'].'</h3>
                            <p class="text-gray-600 mt-2">'.$row['description'].'</p>
                            <div class="mt-4 flex justify-between items-center">
                                <span class="text-lg font-bold text-pink-500">$'.$row['price'].'</span>
                                <a href="pages/product-details.php?id='.$row['id'].'" class="px-3 py-1 bg-pink-200 text-pink-700 rounded hover:bg-pink-300">View Details</a>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<p class="text-gray-600">No products available at the moment.</p>';
            }
            ?>
        </div>
    </main>
</body>
</html>