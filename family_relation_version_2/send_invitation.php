<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect to login if user_id is not set in session
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.replace('Login.html');</script>";
    exit;
}

// Include your functions file
require_once 'functions.php';

// Database configuration and connection setup
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];
    
    // Send message with levels
    $recipientIds = sendMessageWithLevels($pdo, $_SESSION['user_id'], $message);
    
    // Fetch recipient names
    $recipientNames = getRecipientNames($pdo, $recipientIds);
    
    // Output recipient names
    echo "<h2>Message Sent To:</h2>";
    foreach ($recipientNames as $id => $name) {
        echo "ID: $id - Name: $name<br>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Invitation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 80%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #0056b3;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        form label {
            font-weight: bold;
        }
        form input[type="text"], form textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        form button {
            padding: 10px 15px;
            background-color: #0056b3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        form button:hover {
            background-color: #004494;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Send Invitation</h1>
        <form action="send_invitation.php" method="post">
            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="4" required>
