<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: Login.html');
    exit;
}


// Database configuration and connection setup (use your actual login details)
$host = 'localhost';
$db = 'relationship';
$user = 'root';
$password = '';
$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetching user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch relational details (father, mother, marital)
$relatives = ['father_id', 'mother_id', 'marital_id','SIBLINGS_ID'];
foreach ($relatives as $relative) {
    if (!empty($user[$relative])) {
        $rel_stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
        $rel_stmt->execute([$user[$relative]]);
        $rel_result = $rel_stmt->fetch(PDO::FETCH_ASSOC);
        $user[$relative . '_name'] = $rel_result['name'] ?? 'Not available';
    } else {
        $user[$relative . '_name'] = 'Not set';
    }
}

// Updating relative IDs
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updates = ['father_id' => $_POST['father_id'], 'mother_id' => $_POST['mother_id'], 'marital_id' => $_POST['marital_id']];
    $update_stmt = $pdo->prepare("UPDATE users SET father_id = ?, mother_id = ?, marital_id = ? WHERE id = ?");
    $update_stmt->execute([$updates['father_id'], $updates['mother_id'], $updates['marital_id'], $_SESSION['user_id']]);
    header('Location: dashboard.php'); // Refresh the page to see updated data
    exit;
}

// Logout handling
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: Login.html');
    exit;
}




// function getAncestry($pdo, $personId) {
//     $sql = "
//     WITH RECURSIVE Ancestors AS (
//         SELECT id, name, father_id, mother_id
//         FROM users
//         WHERE id = :personId
//         UNION ALL
//         SELECT u.id, u.name, u.father_id, u.mother_id
//         FROM users u
//         JOIN Ancestors a ON u.id = a.father_id OR u.id = a.mother_id
//     )
//     SELECT name FROM Ancestors WHERE father_id IS NOT NULL OR mother_id IS NOT NULL;
//     ";

//     $stmt = $pdo->prepare($sql);
//     $stmt->execute(['personId' => $personId]);
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }

// $ancestors = [];
// if (!empty($_GET['personId'])) {
//     $personId = intval($_GET['personId']);
//     $ancestors = getAncestry($pdo, $personId);
// }
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
</head>
<body>
    <h1>Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($user['name']); ?></p>
    <h2>Profile</h2>
    <ul>
        <li>Name: <?php echo htmlspecialchars($user['name']); ?></li>
        <li>ID: <?php echo htmlspecialchars($user['id']); ?></li>
        <li>Father's Name: <?php echo htmlspecialchars($user['father_id_name']); ?></li>
        <li>Mother's Name: <?php echo htmlspecialchars($user['mother_id_name']); ?></li>
        <li>Marital/Wife's Name: <?php echo htmlspecialchars($user['marital_id_name']); ?></li>
        <li>Siblings: <?php echo htmlspecialchars($user['SIBLINGS_ID_name']); ?></li>
        <li>Gender: <?php echo htmlspecialchars($user['gender']); ?></li>
        <li>Address: <?php echo htmlspecialchars($user['address']); ?></li>
    </ul>
    <h2>Update Relations</h2>
    <form action="dashboard.php" method="post">
        <label for="father_id">Father ID:</label>
        <input type="text" id="father_id" name="father_id" value="<?php echo htmlspecialchars($user['father_id']); ?>"><br>
        <label for="mother_id">Mother ID:</label>
        <input type="text" id="mother_id" name="mother_id" value="<?php echo htmlspecialchars($user['mother_id']); ?>"><br>
        <label for="marital_id">Marital ID:</label>
        <input type="text" id="marital_id" name="marital_id" value="<?php echo htmlspecialchars($user['marital_id']); ?>"><br>
        <label for="marital_id">SIBLINGS_ID:</label>
        <input type="text" id="SIBLINGS_ID" name="SIBLINGS_ID" value="<?php echo htmlspecialchars($user['SIBLINGS_ID']); ?>"><br>
        <button type="submit">Update</button>
    </form>
    <div>
    <h1>User Ancestry Dashboard</h1>
    <form action="fetch.php"  method="post">
        <label for="personId">Enter User ID:</label>
        <input type="text" id="personId" name="personId" required>
        <button type="submit">Get Ancestors</button>
    </form>

    <?php if (!empty($ancestors)): ?>
    <h2>Ancestors:</h2>
    <ul>
        <?php foreach ($ancestors as $ancestor): ?>
        <li><?= htmlspecialchars($ancestor['name']) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['personId'])): ?>
    <p>No ancestors found or invalid ID provided.</p>
    <?php endif; ?>
    </div>
    <div>
        <a href="fetch.php">fetch</a>
    </div>
    <p><a href="dashboard.php?logout">Logout</a></p>
</body>
</html>
