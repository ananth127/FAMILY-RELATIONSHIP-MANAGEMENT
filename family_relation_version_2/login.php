<?php
session_start(); // Start a session for user login

// Database configuration
$host = 'localhost';
$db = 'relationship';
$user = 'root';
$password = '';

// Create PDO instance
$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $password = $_POST['password'];
if($id!=""){
    $stmt = $pdo->prepare("SELECT password FROM userss WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // Verify the password
        if (password_verify($password, $user['password'])) {
            
            // Password is correct, create session variables
            $_SESSION['user_id'] = $id;
            header('Location: Dashboard.php');
            // Redirect to another page or dashboard
            // header('Location: dashboard.php');
            // exit();
        } else {
            echo 'Incorrect password.';
        }
    } else {
        echo 'No user found with that ID.';
    }
}
else if($name != ""){
 
$stmt = $pdo->prepare("SELECT password FROM userss WHERE name = :name");
$stmt->bindParam(':name', $name, PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // Verify the password
    if (password_verify($password, $user['password'])) {
        $stmt = $pdo->prepare("SELECT id FROM userss WHERE name = :name");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();

        // Fetching the result
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $id = $result['id'];
        }

        // Password is correct, create session variables
        $_SESSION['user_id'] = $id;
        header('Location: Dashboard.php'); // Ensure no output before this line
        exit();
    } else {
        echo 'Incorrect password.';
    }
} else {
    echo 'No user found with that name.';
}
}
    // Prepare SQL statement to prevent SQL injection
   
}
//<footer><a href="https://see-through-headset.000webhostapp.com/data/FAMILY%20RELATIONSHIP%20MANAGEMENT.pdf">Copyright © 2024 Ananth(ananth127). All rights are reserved</a></footer>