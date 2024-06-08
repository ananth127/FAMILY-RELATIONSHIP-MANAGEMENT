<?php
// Database configuration
$host = 'localhost'; // or your host
$db = 'relationship';
$user = 'root';
$password = '';

// Create PDO instance
$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}

// Function to generate a unique 16-digit ID
function generateUniqueID($pdo, $table, $column) {
    do {
        $id = sprintf('%016d', rand(0, '9999999999999999')); // Generate 16-digit number
        // Check if the ID exists in the database
        $query = "SELECT COUNT(*) FROM $table WHERE $column = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id]);
    } while ($stmt->fetchColumn() > 0);
    return $id;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $password = $_POST['password'] ?? '';
    $address = $_POST['address'] ?? '';
    $dob = $_POST['dob'] ?? '';

    // Generate unique IDs
    $id = generateUniqueID($pdo, 'users', 'id');
    $commonid = generateUniqueID($pdo, 'users', 'common_id');
    
    // Insert data into the database
    $query = "INSERT INTO users (id, common_id, name, gender, password, address, dob, father_id, mother_id, marital_id)
              VALUES (:id, :common_id, :name, :gender, :password, :address, :dob, NULL, NULL, NULL)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'id' => $id,
        'common_id' => $commonid,
        'name' => $name,
        'gender' => $gender,
        'password' => password_hash($password, PASSWORD_DEFAULT), // Hash the password before storing it
        'address' => $address,
        'dob' => $dob
    ]);

    echo "User registered successfully with ID $id and Common ID $commonid";
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
        <input type="date" id="dob" name="dob" value="12-01-2004" >

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="123" required>

        <button type="submit">Sign Up</button>
    </form>
    <div>
        <a href="Login.html">Sign-up</a> <!-- Should this be "Login" instead of "Sign-up"? -->
    </div>
</div>
</body>
</html>
