<?php
require '../config/session.php';
requireLogin();
require '../config/db_config.php';

$user_id = $_SESSION['user_id'];

// Handle wishlist actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove'])) {
        $product_id = intval($_POST['remove']);
        $stmt = mysqli_prepare($conn, "DELETE FROM wishlists WHERE user_id = ? AND product_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
        mysqli_stmt_execute($stmt);
    } elseif (isset($_POST['move_to_cart'])) {
        $product_id = intval($_POST['move_to_cart']);
        // Check if already in cart
        $check_sql = "SELECT id FROM carts WHERE user_id = $user_id AND product_id = $product_id";
        $result = mysqli_query($conn, $check_sql);
        
        if (mysqli_num_rows($result) === 0) {
            $insert_sql = "INSERT INTO carts (user_id, product_id) VALUES ($user_id, $product_id)";
            mysqli_query($conn, $insert_sql);
        }
        // Remove from wishlist
        $delete_sql = "DELETE FROM wishlists WHERE user_id = $user_id AND product_id = $product_id";
        mysqli_query($conn, $delete_sql);
    }
}

// Get wishlist items
$wishlist_items = [];
$sql = "SELECT p.* FROM wishlists w 
        JOIN products p ON w.product_id = p.id 
        WHERE w.user_id = $user_id
        ORDER BY w.created_at DESC";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $wishlist_items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - Knit Accessories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gradient-to-r from-pink-100 to-blue-100 min-h-screen">
    <header class="bg-white bg-opacity-80 shadow-sm py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-400">My Wishlist</h1>
            <nav>
                <a href="../index.php" class="px-4 py-2 bg-gray-300 text-white rounded-lg hover:bg-gray-400 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
                </a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <?php if (empty($wishlist_items)): ?>
            <div class="bg-white bg-opacity-80 rounded-lg shadow-lg p-8 text-center">
                <i class="fas fa-heart text-5xl text-gray-300 mb-4"></i>
                <h2 class="text-xl font-semibold text-gray-700">Your wishlist is empty</h2>
                <p class="text-gray-600 mt-2">Add some products to your wishlist</p>
                <a href="../index.php" class="mt-4 inline-block px-4 py-2 bg-pink-400 text-white rounded-lg hover:bg-pink-500 transition">
                    Browse Products
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($wishlist_items as $item): ?>
                <div class="bg-white bg-opacity-80 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($item['name']) ?></h3>
                        <p class="text-gray-600 mt-2"><?= htmlspecialchars($item['category']) ?></p>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-lg font-bold text-pink-500">$<?= number_format($item['price'], 2) ?></span>
                            <div class="space-x-2">
                                <form method="POST" action="wishlist.php" class="inline">
                                    <input type="hidden" name="move_to_cart" value="<?= $item['id'] ?>">
                                    <button type="submit" class="px-3 py-1 bg-pink-200 text-pink-700 rounded hover:bg-pink-300">
                                        <i class="fas fa-cart-plus mr-1"></i>Add to Cart
                                    </button>
                                </form>
                                <form method="POST" action="wishlist.php" class="inline">
                                    <input type="hidden" name="remove" value="<?= $item['id'] ?>">
                                    <button type="submit" class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>