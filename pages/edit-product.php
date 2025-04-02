<?php
require '../config/session.php';
requireLogin();

require '../config/db_config.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product data
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    header("Location: admin-dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify admin
    if ($_SESSION['email'] !== 'admin@knitshop.com') {
        header("Location: user-dashboard.php");
        exit();
    }

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    
    // Use prepared statement
    $stmt = mysqli_prepare($conn, "UPDATE products SET 
            name = ?,
            description = ?,
            price = ?,
            image_url = ?,
            category = ?
            WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssdssi", $name, $description, $price, $image_url, $category, $product_id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: admin-dashboard.php");
        exit();
    } else {
        $error = "Failed to update product. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Knit Accessories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gradient-to-r from-pink-100 to-blue-100 min-h-screen">
    <?php include '../config/session.php'; ?>
    
    <header class="bg-white bg-opacity-80 shadow-sm py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-400">Edit Product</h1>
            <a href="admin-dashboard.php" class="px-4 py-2 bg-gray-300 text-white rounded-lg hover:bg-gray-400 transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-white bg-opacity-80 rounded-lg shadow-md p-6 max-w-2xl mx-auto">
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-4">
                <div>
                    <label for="name" class="block text-gray-700 mb-2">Product Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-300 focus:border-transparent">
                </div>
                
                <div>
                    <label for="description" class="block text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="3" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-300 focus:border-transparent"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-gray-700 mb-2">Price ($)</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo $product['price']; ?>" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-300 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="category" class="block text-gray-700 mb-2">Category</label>
                        <select id="category" name="category" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-300 focus:border-transparent">
                            <option value="Scarves" <?php echo $product['category'] === 'Scarves' ? 'selected' : ''; ?>>Scarves</option>
                            <option value="Hats" <?php echo $product['category'] === 'Hats' ? 'selected' : ''; ?>>Hats</option>
                            <option value="Gloves" <?php echo $product['category'] === 'Gloves' ? 'selected' : ''; ?>>Gloves</option>
                            <option value="Blankets" <?php echo $product['category'] === 'Blankets' ? 'selected' : ''; ?>>Blankets</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label for="image_url" class="block text-gray-700 mb-2">Image URL</label>
                    <input type="url" id="image_url" name="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-300 focus:border-transparent">
                    <div class="mt-2">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Current product image" class="w-32 h-32 object-cover rounded">
                    </div>
                </div>
                
                <button type="submit" 
                    class="w-full bg-pink-400 text-white py-2 px-4 rounded-lg hover:bg-pink-500 transition duration-200 mt-6">
                    <i class="fas fa-save mr-2"></i>Update Product
                </button>
            </form>
        </div>
    </main>
</body>
</html>