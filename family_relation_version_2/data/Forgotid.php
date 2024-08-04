<?php

$host = 'localhost';
$db = 'id21263871_feedback';
$user = 'id21263871_feedbackapp';
$password = 'Ajai@1107';

// Create PDO instance
$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['mobile_number']) && !empty($_POST['dob'])) {
        $mobile_number = $_POST['mobile_number'];
        $dob = $_POST['dob']; // Make sure the date format matches your database format

        // Prepare the SQL query
        $stmt = $pdo->prepare("SELECT id FROM userss WHERE mobile_number = ? AND dob = ?");
        $stmt->bindParam(1, $mobile_number, PDO::PARAM_STR);
        $stmt->bindParam(2, $dob, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "User ID: " . $result['id'];
        } else {
            echo "User not found";
        }
    } else {
        echo "Please provide both mobile number and date of birth.";
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fetch User ID</title>
</head>
<body>
    <h1>Fetch User ID</h1>
    <form method="POST" action="">
        <label for="mobile_number">Mobile Number:</label>
        <input type="text" id="mobile_number" name="mobile_number" required><br><br>
        <label for="dob">Date of Birth (YYYY-MM-DD):</label>
        <input type="date" id="dob" name="dob" required><br><br>
        <input type="submit" value="Fetch User ID">
    </form>
    <footer><a style="font-family: 'Courier New', Courier, monospace; background-color: #f4f4f4;  border-radius: 4px; padding: 2px 4px; color: #c7254e; text-decoration: none;" href="https://see-through-headset.000webhostapp.com/data/FAMILY%20RELATIONSHIP%20MANAGEMENT.pdf">Copyright © 2024 Ananth(ananth127). All rights are reserved
</a></footer>
</body>
</html>
