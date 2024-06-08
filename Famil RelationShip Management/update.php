<?php
session_start();

// Database credentials
$host = 'localhost';
$dbname = 'relationship';
$username = 'root';
$password = '';

// Create a PDO instance and set up the connection
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch existing user data to check against
    $query = $pdo->prepare("SELECT father_id, mother_id, marital_id, SIBLINGS_ID FROM users WHERE id = ?");
    $query->execute([$_SESSION['user_id']]);
    $existing = $query->fetch(PDO::FETCH_ASSOC);

    $updates = [];
    $parameters = [];

    // Conditional updates for father, mother, and marital IDs
    foreach (['father_id', 'mother_id', 'marital_id'] as $field) {
        if (is_null($existing[$field]) && !empty($_POST[$field])) {
            $updates[] = "$field = ?";
            $parameters[] = $_POST[$field];
        }
    }

    // Update for SIBLINGS_ID
    if (isset($_POST['SIBLINGS_ID'])) {
        $existingSiblings = json_decode($existing['SIBLINGS_ID']) ?: [];
        $newSiblings = array_filter(array_map('trim', explode(',', $_POST['SIBLINGS_ID'])));
        $updatedSiblings = array_unique(array_merge($existingSiblings, $newSiblings));
        $updates[] = 'SIBLINGS_ID = ?';
        $parameters[] = json_encode($updatedSiblings);
    }

    // Perform the update if there are fields to update
    if (!empty($updates)) {
        $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
        $parameters[] = $_SESSION['user_id'];
        $update_stmt = $pdo->prepare($sql);
        $update_stmt->execute($parameters);

        if ($update_stmt->rowCount() > 0) {
            echo "Updated successfully.";
        } else {
            echo "No updates made or user ID does not exist. Check your input data.";
        }
    } else {
        echo "No valid updates provided or fields already set.";
    }

    // Redirect to dashboard
    header('Location: dashboard.php');
    exit;
}
?>
