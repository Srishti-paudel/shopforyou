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
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>