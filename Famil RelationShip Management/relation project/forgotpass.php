<?php
// Database connection
$host = 'localhost';
$dbname = 'relation';
$username = 'root';
$password = '';
// Create PDO instance
$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mobile = $_POST['mobile'];
    $dob = $_POST['dob'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];

    if ($password !== $repassword) {
        echo "Passwords do not match!";
    } else {
        // Check if the user exists with the provided mobile number and date of birth
        $stmt = $pdo->prepare("SELECT id FROM userss WHERE mobile_number = ? AND dob = ?");
        $stmt->execute([$mobile, $dob]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Update the password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $updateStmt = $pdo->prepare("UPDATE userss SET password = ? WHERE id = ?");
            $updateStmt->execute([$hashedPassword, $user['id']]);

            echo "Password changed successfully.";
        } else {
            echo "User not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
</head>
<body>
    <h1>Change Password</h1>
    <form method="POST" action="">
        <label for="mobile">Mobile Number:</label>
        <input type="text" id="mobile" name="mobile" required><br>

        <label for="dob">Date of Birth (YYYY-MM-DD):</label>
        <input type="date" id="dob" name="dob" required><br>

        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="repassword">Re-enter New Password:</label>
        <input type="password" id="repassword" name="repassword" required><br>

        <input type="submit" value="Change Password">
    </form>
    <footer><a style="font-family: 'Courier New', Courier, monospace; background-color: #f4f4f4;  border-radius: 4px; padding: 2px 4px; color: #c7254e; text-decoration: none;" href="https://see-through-headset.000webhostapp.com/data/FAMILY%20RELATIONSHIP%20MANAGEMENT.pdf">Copyright © 2024 Ananth(ananth127). All rights are reserved
</a></footer>
</body>
</html>
