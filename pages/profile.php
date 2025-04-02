<?php
require '../config/session.php';
requireLogin();

// Get user info
require '../config/db_config.php';
$user_id = $_SESSION['user_id'];
$user = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user_data = mysqli_fetch_assoc($user);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $stmt = mysqli_prepare($conn, "UPDATE users SET username = ?, email = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssi", $username, $email, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success = "Profile updated successfully!";
        $user_data['username'] = $username;
        $user_data['email'] = $email;
    } else {
        $error = "Failed to update profile: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Knit Accessories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gradient-to-r from-pink-100 to-blue-100 min-h-screen">
    <header class="bg-white bg-opacity-80 shadow-sm py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-pink-400">Your Profile</h1>
            <nav>
                <a href="user-dashboard.php" class="px-4 py-2 bg-gray-300 text-white rounded-lg hover:bg-gray-400 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <div class="bg-white bg-opacity-80 rounded-lg shadow-lg p-6 max-w-2xl mx-auto">
            <?php if (isset($success)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label for="username" class="block text-gray-700 mb-2">Username</label>
                    <input type="text" id="username" name="username" 
                        value="<?php echo htmlspecialchars($user_data['username']); ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-300 focus:border-transparent">
                </div>
                
                <div>
                    <label for="email" class="block text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" 
                        value="<?php echo htmlspecialchars($user_data['email']); ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-300 focus:border-transparent">
                </div>
                
                <button type="submit" 
                    class="w-full bg-pink-400 text-white py-2 px-4 rounded-lg hover:bg-pink-500 transition duration-200 mt-6">
                    <i class="fas fa-save mr-2"></i>Update Profile
                </button>
            </form>
        </div>
    </main>
</body>
</html>