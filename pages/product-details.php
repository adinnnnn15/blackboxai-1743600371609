<?php
require '../config/session.php';
require '../config/db_config.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Knit Accessories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gradient-to-r from-pink-100 to-blue-100 min-h-screen">
    <header class="bg-white bg-opacity-80 shadow-sm py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-400">Knit Accessories</h1>
            <nav>
                <?php if (isLoggedIn()): ?>
                    <a href="admin-dashboard.php" class="px-4 py-2 bg-pink-300 text-white rounded-lg hover:bg-pink-400 transition">Dashboard</a>
                <?php else: ?>
                    <a href="login.php" class="px-4 py-2 bg-pink-300 text-white rounded-lg hover:bg-pink-400 transition">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-white bg-opacity-80 rounded-lg shadow-lg overflow-hidden max-w-4xl mx-auto">
            <div class="md:flex">
                <div class="md:w-1/2 p-6">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" 
                        class="w-full h-auto rounded-lg object-cover">
                </div>
                <div class="md:w-1/2 p-6">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($product['name']); ?></h2>
                    <span class="text-pink-500 text-2xl font-bold block mb-4">$<?php echo number_format($product['price'], 2); ?></span>
                    
                    <div class="mb-4">
                        <span class="bg-pink-200 text-pink-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                            <?php echo htmlspecialchars($product['category']); ?>
                        </span>
                    </div>
                    
                    <p class="text-gray-700 mb-6"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    
                    <div class="flex flex-col space-y-4 mb-4">
                        <div class="flex space-x-4">
                            <form method="POST" action="add-to-cart.php" class="flex-1">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <button type="submit" class="w-full bg-pink-400 hover:bg-pink-500 text-white py-2 px-4 rounded-lg transition">
                                    <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                                </button>
                            </form>
                            <form method="POST" action="wishlist-action.php" class="flex-1">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="action" value="add">
                                <button type="submit" class="w-full bg-blue-400 hover:bg-blue-500 text-white py-2 px-4 rounded-lg transition">
                                    <i class="fas fa-heart mr-2"></i>Add to Wishlist
                                </button>
                            </form>
                        </div>
                        <a href="../index.php" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-lg transition">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Shop
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white bg-opacity-80 py-6 mt-8">
        <div class="container mx-auto px-4 text-center text-gray-600">
            <p>© 2023 Knit Accessories Shop. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>