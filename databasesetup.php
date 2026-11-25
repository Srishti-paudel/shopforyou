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
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>