<?php

$host = '127.0.0.1'; 
$dbname = 'quiz';
$username = 'root';
$password = '';
$port = 3307; 




$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,      
    PDO::ATTR_EMULATE_PREPARES   => false,                 
];

try {
   $pdo = new PDO("mysql:host=127.0.0.1;port=3307;dbname=quiz", 'root', '');

    
} catch (PDOException $e) {
    
    die('Database connection failed: ' . $e->getMessage());
}
?>
