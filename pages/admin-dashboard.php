<?php
require '../config/session.php';
requireLogin();

require '../config/db_config.php';
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Knit Accessories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gradient-to-r from-pink-100 to-blue-100 min-h-screen">
    <header class="bg-white bg-opacity-80 shadow-sm py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-400">Admin Dashboard</h1>
            <nav class="flex items-center space-x-4">
                <a href="add-product.php" class="px-4 py-2 bg-pink-300 text-white rounded-lg hover:bg-pink-400 transition">
                    <i class="fas fa-plus mr-2"></i>Add Product
                </a>
                <a href="../index.php" class="px-4 py-2 bg-blue-300 text-white rounded-lg hover:bg-blue-400 transition">
                    <i class="fas fa-store mr-2"></i>View Shop
                </a>
                <a href="logout.php" class="px-4 py-2 bg-gray-300 text-white rounded-lg hover:bg-gray-400 transition">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-white bg-opacity-80 rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-6">Manage Products</h2>
            
            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white bg-opacity-70">
                        <thead>
                            <tr class="bg-pink-200">
                                <th class="py-2 px-4 border-b">Image</th>
                                <th class="py-2 px-4 border-b">Name</th>
                                <th class="py-2 px-4 border-b">Price</th>
                                <th class="py-2 px-4 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr class="hover:bg-pink-50">
                                <td class="py-3 px-4 border-b">
                                    <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>" class="w-16 h-16 object-cover rounded">
                                </td>
                                <td class="py-3 px-4 border-b"><?php echo $row['name']; ?></td>
                                <td class="py-3 px-4 border-b">$<?php echo number_format($row['price'], 2); ?></td>
                                <td class="py-3 px-4 border-b">
                                    <a href="edit-product.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:text-blue-700 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="delete-product.php?id=<?php echo $row['id']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-600">No products found. Add your first product!</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>