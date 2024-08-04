<?php
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

    // Create the userss table if it doesn't exist
    $createTableQuery = "CREATE TABLE IF NOT EXISTS userss (
        id INT PRIMARY KEY,
        common_id INT,
        name VARCHAR(255),
        gender VARCHAR(10),
        password VARCHAR(255),
        address TEXT,
        dob DATE,
        father_id INT,
        mother_id INT,
        marital_id INT,
        mobile_number VARCHAR(15)
    )";
    $pdo->exec($createTableQuery);
} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}

// Function to generate a unique 16-digit ID
function generateUniqueID($pdo, $table, $column) {
    do {
        $id = sprintf('%016d', rand(0, 9999999999999999)); // Generate 16-digit number
        // Check if the ID exists in the database
        $query = "SELECT COUNT(*) FROM $table WHERE $column = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id]);
    } while ($stmt->fetchColumn() > 0);
    return $id;
}

// Check if user already exists
function checkUser($pdo, $name) {
    $query = "SELECT COUNT(*) FROM userss WHERE name = :name";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['name' => $name]);
    $count = $stmt->fetchColumn();

    return $count > 0; // Return true if user exists, otherwise false
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $password = $_POST['password'] ?? '';
    $address = $_POST['address'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $mobileno = $_POST['mobileno'] ?? '';
    
    if (checkUser($pdo, $name)) {
        echo "User already exists.";
    } else {
        $id = generateUniqueID($pdo, 'userss', 'id');
    
        // Insert data into the database
        $query = "INSERT INTO userss (id, name, gender, password, address, dob, father_id, mother_id, marital_id, mobile_number)
                  VALUES (:id, :name, :gender, :password, :address, :dob, NULL, NULL, NULL, :mobileno)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'id' => $id,
            'name' => $name,
            'gender' => $gender,
            'password' => password_hash($password, PASSWORD_DEFAULT), // Hash the password before storing it
            'address' => $address,
            'dob' => $dob,
            'mobileno' => $mobileno
        ]);

        echo "User registered successfully with ID $id ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sign Up Form</title>
</head>
<body>
<div>
    <h2>Sign Up</h2>
    <form action="" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="others">Others</option>
        </select>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required>
        
        <label for="mobileno">Mobile No.:</label>
        <input type="number" id="mobileno" name="mobileno" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Sign Up</button>
    </form>
    <div>
        <a href="Login.html">Login</a>
    </div>
</div>
<footer><a style="font-family: 'Courier New', Courier, monospace; background-color: #f4f4f4;  border-radius: 4px; padding: 2px 4px; color: #c7254e; text-decoration: none;" href="https://see-through-headset.000webhostapp.com/data/FAMILY%20RELATIONSHIP%20MANAGEMENT.pdf">Copyright © 2024 Ananth(ananth127). All rights are reserved
</a></footer>
</body>
</html>
