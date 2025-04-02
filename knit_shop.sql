-- Create database
CREATE DATABASE IF NOT EXISTS knit_shop;
USE knit_shop;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    category VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample admin user (password: admin123)
INSERT INTO users (username, email, password) 
VALUES ('admin', 'admin@knitshop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Wishlist table
CREATE TABLE IF NOT EXISTS wishlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    UNIQUE KEY (user_id, product_id)
);

-- Cart table
CREATE TABLE IF NOT EXISTS carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Sample products
INSERT INTO products (name, description, price, image_url, category) VALUES
('Knitted Scarf', 'Soft and warm hand-knitted scarf in pastel colors', 24.99, 'https://images.pexels.com/photos/1021291/pexels-photo-1021291.jpeg', 'Scarves'),
('Wool Hat', 'Cozy winter hat with pom-pom', 19.99, 'https://images.pexels.com/photos/35185/hats-fedora-hat-woman-face.jpg', 'Hats'),
('Fingerless Gloves', 'Stylish fingerless gloves for chilly days', 16.99, 'https://images.pexels.com/photos/63448/pexels-photo-63448.jpeg', 'Gloves'),
('Baby Blanket', 'Soft knitted baby blanket in mint green', 29.99, 'https://images.pexels.com/photos/37347/office-sitting-room-executive-sitting.jpg', 'Blankets');