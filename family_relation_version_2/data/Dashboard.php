<?php
session_start();

// Redirect to login if user_id is not set in session
if (!isset($_SESSION['user_id'])) {
    header('Location: Login.html');
    exit;
}

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

// Fetching user details
$stmt = $pdo->prepare("SELECT * FROM userss WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch relational details (father, mother, marital)
$relatives = ['father_id', 'mother_id', 'marital_id', 'SIBLINGS_ID'];
foreach ($relatives as $relative) {
    if (!empty($user[$relative])) {
                  $rel_stmt = $pdo->prepare("SELECT name FROM userss WHERE id = ?");
        $rel_stmt->execute([$user[$relative]]);
        $rel_result = $rel_stmt->fetch(PDO::FETCH_ASSOC);
        $user[$relative . '_name'] = $rel_result['name'] ?? 'Not available';
    } else {
        $user[$relative . '_name'] = 'Not set';
    }
}

// Fetch siblings
function fetchSiblings($pdo, $siblingsJson) {
    $siblings = [];
    $siblingIds = json_decode($siblingsJson, true);

    if (!is_array($siblingIds) || empty($siblingIds)) {
        return "No siblings available.";
    }

    $placeholders = implode(',', array_fill(0, count($siblingIds), '?'));
    $stmt = $pdo->prepare("SELECT name FROM userss WHERE id IN ($placeholders)");
    $stmt->execute($siblingIds);

    if ($stmt->rowCount() > 0) {
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $siblings[] = htmlspecialchars($result['name']);
        }
    }

    return implode(', ', $siblings);
}

$user['siblings'] = fetchSiblings($pdo, $user['CHILDRENS_ID']);

// Function to get ancestry
function getAncestry($pdo, $personId) {
    $sql = "
    WITH RECURSIVE Ancestors AS (
        SELECT id, name, father_id, mother_id
        FROM userss
        WHERE id = :personId
        UNION ALL
        SELECT u.id, u.name, u.father_id, u.mother_id
        FROM userss u
        JOIN Ancestors a ON u.id = a.father_id OR u.id = a.mother_id
    )
    SELECT name FROM Ancestors WHERE father_id IS NOT NULL OR mother_id IS NOT NULL;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['personId' => $personId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$ancestors = [];
if (!empty($_GET['personId'])) {
    $personId = intval($_GET['personId']);
    $ancestors = getAncestry($pdo, $personId);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #3a3d40, #181a1b);
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        header {
            width: 100%;
            background: #232526;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        header h1 {
            margin: 0;
            color: #ffffff;
            font-size: 24px;
        }
        .container {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
            margin: 20px 0;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            background: #f7f7f7;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        label, input, button {
            margin: 5px 0;
        }
        input, button {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input {
            width: 100%;
            max-width: 300px;
        }
        button {
            background: #4CAF50;
            color: #fff;
            cursor: pointer;
            width: 100%;
            max-width: 300px;
        }
        button:hover {
            background: #45a049;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>User Dashboard</h1>
    </header>

    <div class="container">
        <h2>Welcome</h2>
        <p>Welcome, <?php echo htmlspecialchars($user['name']); ?></p>
    </div>

    <div class="container">
        <h2>Profile</h2>
        <ul>
            <li>Name: <?php echo htmlspecialchars($user['name']); ?></li>
            <li>ID: <?php echo htmlspecialchars($user['id']); ?></li>
            <li>Father's Name: <?php echo htmlspecialchars($user['father_id_name']); ?></li>
            <li>Mother's Name: <?php echo htmlspecialchars($user['mother_id_name']); ?></li>
            <li>Marital/Wife's Name: <?php echo htmlspecialchars($user['marital_id_name']); ?></li>
            <li>Siblings: <?php echo htmlspecialchars($user['siblings']); ?></li>
            <li>Gender: <?php echo htmlspecialchars($user['gender']); ?></li>
            <li>Address: <?php echo htmlspecialchars($user['address']); ?></li>
        </ul>
    </div>

    <div class="container">
        <h2>Update Relations</h2>
        <form action="update.php" method="post">
            <label for="father_id">Father ID:</label>
            <input type="text" id="father_id" name="father_id" value="<?php echo htmlspecialchars($user['father_id']); ?>"><br>
            <label for="mother_id">Mother ID:</label>
            <input type="text" id="mother_id" name="mother_id" value="<?php echo htmlspecialchars($user['mother_id']); ?>"><br>
            <label for="marital_id">Marital ID:</label>
            <input type="text" id="marital_id" name="marital_id" value="<?php echo htmlspecialchars($user['marital_id']); ?>"><br>
            <label for="siblings_ID">SIBLINGS_ID:</label>
            <input type="text" id="SIBLINGS_ID" name="CHILDRENS_ID" value="<?php echo htmlspecialchars($user['CHILDRENS_ID']); ?>"><br>
            <button type="submit">Update</button>
        </form>
    </div>

    <div class="container">
        <h2>Navigation</h2>
        <form action="Table.php" method="post">
            <input type="text" name="personId" value="<?php echo $user['id']; ?>">
            <button type="submit">Family</button>
        </form>
        <form action="parchil1.php" method="post">
            <input type="text" name="personId" value="<?php echo $user['id']; ?>">
            <button type="submit">Relatives</button>
        </form>
        <form action="parchil2_ancestor.php" method="post">
            <input type="text" name="personId" value="<?php echo $user['id']; ?>">
            <button type="submit">Ancestor</button>
        </form>
        <form action="new_11_1.php" method="post">
            <input type="text" name="personId" value="<?php echo $user['id']; ?>">
            <button type="submit">New 11_1</button>
        </form>
    </div>

    <div class="container">
        <p><a href="Login.html">Logout</a></p>
        <footer><a style="font-family: 'Courier New', Courier, monospace; background-color: #f4f4f4;  border-radius: 4px; padding: 2px 4px; color: #c7254e; text-decoration: none;" href="https://see-through-headset.000webhostapp.com/data/FAMILY%20RELATIONSHIP%20MANAGEMENT.pdf">Copyright © 2024 Ananth(ananth127). All rights are reserved
        </a></footer> 
    </div>
</body>
</html>

