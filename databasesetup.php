<?php

$student_name = 'Srishti Paudel';
$lab_no       = 1;
$roll_no      = '05';

try {

    $pdo = new PDO("mysql:host=localhost;charset=utf8mb4", "root", "");


    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   

    $pdo->exec("CREATE DATABASE IF NOT EXISTS shopforyou");
    $pdo->exec("USE shopforyou");

    //table for categories
     $pdo->exec("
        CREATE TABLE IF NOT EXISTS categories (
            category_id INT AUTO_INCREMENT PRIMARY KEY,  -- unique id, generated automatically
            name        VARCHAR(100) NOT NULL,           -- category name
            description TEXT                             -- optional short description
        )
    ");
      $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            user_id       INT AUTO_INCREMENT PRIMARY KEY,  -- unique id
            username      VARCHAR(50)  NOT NULL,           -- display name
            email         VARCHAR(100) NOT NULL UNIQUE,    -- login email, no duplicates allowed
            password_hash VARCHAR(255) NOT NULL            -- HASHED password, never plain text
        )
    ");
     $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            product_id  INT AUTO_INCREMENT PRIMARY KEY,
            category_id INT NOT NULL,                    -- which category this belongs to
            name        VARCHAR(150) NOT NULL,
            price       DECIMAL(10,2) NOT NULL,          -- money: 10 digits, 2 after the point
            stock       INT NOT NULL,                    -- how many are available
            FOREIGN KEY (category_id) REFERENCES categories(category_id)
        )

    ");
     $pdo->exec("
        CREATE TABLE IF NOT EXISTS orders (
            order_id     INT AUTO_INCREMENT PRIMARY KEY,
            user_id      INT NOT NULL,                   -- who placed the order
            total_amount DECIMAL(10,2) NOT NULL,         -- order total
            status       VARCHAR(50) NOT NULL,           -- e.g. Pending, Shipped, Completed
            FOREIGN KEY (user_id) REFERENCES users(user_id)
        )
    ");
        $pdo->exec("
        CREATE TABLE IF NOT EXISTS order_items (
            order_item_id INT AUTO_INCREMENT PRIMARY KEY,
            order_id      INT NOT NULL,                  -- which order
            product_id    INT NOT NULL,                  -- which product
            quantity      INT NOT NULL,
            unit_price    DECIMAL(10,2) NOT NULL,        -- price at the time of purchase
            FOREIGN KEY (order_id)   REFERENCES orders(order_id),
            FOREIGN KEY (product_id) REFERENCES products(product_id)
        )
    ");
        
    if ($pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn() == 0) {
        $pdo->exec("
            INSERT INTO categories (name, description) VALUES
            ('Men',         'Shirts and t-shirts for men'),
            ('Women',       'Tops and jeans for women'),
            ('Kids',        'Comfortable clothing for children'),
            ('Winterwear',  'Jackets and sweaters'),
            ('Accessories', 'Caps and scarves')
        ");
    }
       
    if ($pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() == 0) {
        $hash = password_hash("Pass1234!", PASSWORD_DEFAULT);

        $pdo->exec("
            INSERT INTO users (username, email, password_hash) VALUES
            ('Srishti paudel',   'srishti@pkmc.com',   '$hash'),
            ('Roshni shakya',     'rosni@pkmc.com',     '$hash'),
            ('shusana dahal', 'shusana@pkmc.com', '$hash'),
            ('shrija khanal',   'shrija@pkmc.com',   '$hash'),
            ('smrity thapa',    'smrity@pkmc.com',    '$hash')
        ");
    }
     if ($pdo->query("SELECT COUNT(*) FROM products")->fetchColumn() == 0) {
        $pdo->exec("
            INSERT INTO products (category_id, name, price, stock) VALUES
            (1, 'Men Cotton T-Shirt',   25.00, 50),
            (2, 'Women Printed Top',    28.00, 60),
            (3, 'Kids Zip-Up Hoodie',   32.00, 25),
            (4, 'Winter Puffer Jacket', 89.00, 20),
            (5, 'Baseball Cap',         12.00, 100)
        ");
    }
        if ($pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn() == 0) {
        $pdo->exec("
            INSERT INTO orders (user_id, total_amount, status) VALUES
            (1, 25.00, 'Completed'),
            (2, 28.00, 'Shipped'),
            (3, 32.00, 'Processing'),
            (4, 89.00, 'Pending'),
            (5, 12.00, 'Completed')
        ");
    }
      if ($pdo->query("SELECT COUNT(*) FROM order_items")->fetchColumn() == 0) {
        $pdo->exec("
            INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES
            (1, 1, 1, 25.00),
            (2, 2, 1, 28.00),
            (3, 3, 1, 32.00),
            (4, 4, 1, 89.00),
            (5, 5, 1, 12.00)
        ");
    }   
    
    echo "Setup script ran successfully! and table created successfully";
    echo "<br><br>Name: $student_name<br>Lab No. $lab_no<br>Roll No. $roll_no";

}
    catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>