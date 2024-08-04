<?php
session_start();

// Database credentials
$host = 'localhost';
$db = 'id21263871_feedback';
$user = 'id21263871_feedbackapp';
$password = 'Ajai@1107';

// Create a PDO instance and set up the connection
$dsn = "mysql:host=$host;dbname=$db;charset=utf8";
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch existing user data to check against
    $query = $pdo->prepare("SELECT father_id, mother_id, marital_id, CHILDRENS_ID FROM userss WHERE id = ?");
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

    // Update for CHILDRENS_ID
    if (isset($_POST['CHILDRENS_ID'])) {
        $existingSiblings = json_decode($existing['CHILDRENS_ID']) ?: [];
        $newSiblings = array_filter(array_map('trim', explode(',', $_POST['CHILDRENS_ID'])));
        $updatedSiblings = array_unique(array_merge($existingSiblings, $newSiblings));
        $updates[] = 'CHILDRENS_ID = ?';
        $parameters[] = json_encode($updatedSiblings);
    }

    // Perform the update if there are fields to update
    if (!empty($updates)) {
        $sql = "UPDATE userss SET " . implode(', ', $updates) . " WHERE id = ?";
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
    header('Location: Dashboard.php');
    exit;
}
?>
