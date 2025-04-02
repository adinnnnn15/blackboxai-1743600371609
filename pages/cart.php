<?php
require '../config/session.php';
requireLogin();
require '../config/db_config.php';

$user_id = $_SESSION['user_id'];

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update quantities
    if (isset($_POST['update'])) {
        foreach ($_POST['quantity'] as $cart_id => $quantity) {
            $quantity = max(1, intval($quantity));
            $stmt = mysqli_prepare($conn, "UPDATE carts SET quantity = ? WHERE id = ? AND user_id = ?");
            mysqli_stmt_bind_param($stmt, "iii", $quantity, $cart_id, $user_id);
            mysqli_stmt_execute($stmt);
        }
    } 
    // Remove item
    elseif (isset($_POST['remove'])) {
        $cart_id = intval($_POST['remove']);
        $stmt = mysqli_prepare($conn, "DELETE FROM carts WHERE id = ? AND user_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $cart_id, $user_id);
        mysqli_stmt_execute($stmt);
    }
}

// Get cart items with product details
$cart_items = [];
$total = 0;
$sql = "SELECT c.id as cart_id, p.*, c.quantity 
        FROM carts c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = $user_id";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $row['subtotal'] = $row['price'] * $row['quantity'];
    $total += $row['subtotal'];
    $cart_items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Knit Accessories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gradient-to-r from-pink-100 to-blue-100 min-h-screen">
    <header class="bg-white bg-opacity-80 shadow-sm py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-400">Shopping Cart</h1>
            <nav>
                <a href="../index.php" class="px-4 py-2 bg-gray-300 text-white rounded-lg hover:bg-gray-400 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
                </a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <?php if (empty($cart_items)): ?>
            <div class="bg-white bg-opacity-80 rounded-lg shadow-lg p-8 text-center">
                <i class="fas fa-shopping-cart text-5xl text-gray-300 mb-4"></i>
                <h2 class="text-xl font-semibold text-gray-700">Your cart is empty</h2>
                <p class="text-gray-600 mt-2">Start shopping to add items to your cart</p>
                <a href="../index.php" class="mt-4 inline-block px-4 py-2 bg-pink-400 text-white rounded-lg hover:bg-pink-500 transition">
                    Browse Products
                </a>
            </div>
        <?php else: ?>
            <form method="POST" class="bg-white bg-opacity-80 rounded-lg shadow-lg overflow-hidden">
                <div class="divide-y divide-gray-200">
                    <?php foreach ($cart_items as $item): ?>
                    <div class="p-4 flex flex-col md:flex-row gap-4">
                        <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" 
                            class="w-full md:w-32 h-32 object-cover rounded">
                        <div class="flex-grow">
                            <h3 class="text-lg font-semibold"><?= htmlspecialchars($item['name']) ?></h3>
                            <p class="text-gray-600"><?= htmlspecialchars($item['category']) ?></p>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" name="quantity[<?= $item['cart_id'] ?>]" 
                                value="<?= $item['quantity'] ?>" min="1"
                                class="w-16 px-2 py-1 border rounded text-center">
                            <span class="font-semibold">$<?= number_format($item['subtotal'], 2) ?></span>
                            <button type="submit" name="remove" value="<?= $item['cart_id'] ?>"
                                class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="p-4 bg-gray-50 flex justify-between items-center">
                    <div class="text-xl font-bold">
                        Total: $<?= number_format($total, 2) ?>
                    </div>
                    <div class="space-x-2">
                        <button type="submit" name="update"
                            class="px-4 py-2 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition">
                            Update Cart
                        </button>
                        <a href="checkout.php" class="px-4 py-2 bg-pink-400 text-white rounded-lg hover:bg-pink-500 transition">
                            Checkout
                        </a>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>