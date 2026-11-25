<?php

$student_name = 'Srishti Paudel';
$lab_no       = 1;
$roll_no      = '05';

try {

    $pdo = new PDO("mysql:host=localhost;charset=utf8mb4", "root", "");


    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Setup script ran successfully!";
    echo "<br><br>Name: $student_name<br>Lab No. $lab_no<br>Roll No. $roll_no";

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
        // ------------------------------------------------------------
    // STEP 7: sample categories (5 rows)
    // Only inserted when the table is empty, so re-running adds no duplicates.
    // ------------------------------------------------------------
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
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>