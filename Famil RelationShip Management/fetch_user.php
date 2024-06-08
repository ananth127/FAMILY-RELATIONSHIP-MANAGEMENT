<?php
// Start the session
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

// Check if user_id is provided
if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    // Fetch the user and direct relatives
    $query = $pdo->prepare("SELECT u.id, u.name, u.gender, 
                                    f.id AS father_id, f.name AS father_name, 
                                    m.id AS mother_id, m.name AS mother_name
                            FROM users u
                            LEFT JOIN users f ON u.father_id = f.id
                            LEFT JOIN users m ON u.mother_id = m.id
                            WHERE u.id = ?");
    $query->execute([$userId]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    // Fetch siblings
    $siblingsQuery = $pdo->prepare("SELECT id, name FROM users WHERE father_id = ? OR mother_id = ?");
    $siblingsQuery->execute([$user['father_id'], $user['mother_id']]);
    $siblings = $siblingsQuery->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <h1>User Relationships for ID: <?= htmlspecialchars($userId) ?></h1>
    <table border="1">
        <tr>
            <th>Relation</th>
            <th>Name</th>
            <th>ID</th>
        </tr>
        <tr>
            <td>User</td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['id']) ?></td>
        </tr>
        <tr>
            <td>Father</td>
            <td><?= htmlspecialchars($user['father_name'] ?? 'Unknown') ?></td>
            <td><?= htmlspecialchars($user['father_id'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <td>Mother</td>
            <td><?= htmlspecialchars($user['mother_name'] ?? 'Unknown') ?></td>
            <td><?= htmlspecialchars($user['mother_id'] ?? 'N/A') ?></td>
        </tr>
        <?php foreach ($siblings as $sibling) : ?>
            <tr>
                <td>Sibling</td>
                <td><?= htmlspecialchars($sibling['name']) ?></td>
                <td><?= htmlspecialchars($sibling['id']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
} else {
    echo "<p>Please provide a user ID.</p>";
}
?>
