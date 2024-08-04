<?php
$host = 'localhost';
$dbname = 'relationship';
$username = 'root';
$password = '';
$dsn = "mysql:host=$host;dbname=$dbname;charset=UTF8";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
