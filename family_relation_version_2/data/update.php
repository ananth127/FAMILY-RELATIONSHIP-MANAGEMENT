<?php
session_start();

// Database configuration and connection setup
$host = 'localhost';
$dbname = 'id21263871_feedback';
$username = 'id21263871_feedbackapp';
$password = 'Ajai@1107';
$dsn = "mysql:host=$host;dbname=$dbname;charset=UTF8";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to check if mobile number is null
function checkmob($pdo) {
    if (!isset($_SESSION['user_id'])) {
        return false; // No user ID in session
    }

    $stmt = $pdo->prepare("SELECT mobile_number FROM userss WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return is_null($user['mobile_number']);
    } else {
        return false; // No user found with the given person ID
    }
}

// Updating relative IDs
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (checkmob($pdo)) {
        echo "This ID is only for reference. Can't make any changes!";
    } else {
        $updates = [
            'father_id' => $_POST['father_id'], 
            'mother_id' => $_POST['mother_id'], 
            'marital_id' => $_POST['marital_id'],
            'CHILDRENS_ID' => $_POST['CHILDRENS_ID']
        ];
        $update_stmt = $pdo->prepare("UPDATE userss SET father_id = ?, mother_id = ?, marital_id = ?, CHILDRENS_ID = ? WHERE id = ?");
        $update_stmt->execute([
            $updates['father_id'], 
            $updates['mother_id'], 
            $updates['marital_id'], 
            $updates['CHILDRENS_ID'], 
            $_SESSION['user_id']
        ]);
        header('Location: dashboard.php'); // Refresh the page to see updated data
        exit;
    }
}
?>
