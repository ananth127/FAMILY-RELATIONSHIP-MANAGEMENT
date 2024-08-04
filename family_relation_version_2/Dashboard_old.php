<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect to login if user_id is not set in session
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.replace('Login.html');</script>";
    exit;
}
// Database configuration and connection setup
$host = 'localhost';
$dbname = 'relationship';
$username = 'root';
$password = '';
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
$relatives = ['father_id', 'mother_id', 'marital_id', 'CHILDRENS_ID'];
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
// Fetch siblings
function fetchSiblings($pdo, $siblingsJson) {
    $siblings = [];
    // Check if $siblingsJson is not null before decoding
    if (!is_null($siblingsJson)) {
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
    } else {
        return "No siblings available.";
    }

    return implode(', ', $siblings);
}



$user['siblings'] = fetchSiblings($pdo, $user['CHILDRENS_ID']);
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
        echo "<script>window.location.replace('Dashboard.php?failed');</script>";
    } else {
    $updates = [
        'father_id' => $_POST['father_id'], 
        'mother_id' => $_POST['mother_id'], 
        'marital_id' => $_POST['marital_id']
    ];

    // Fetch current children IDs and decode JSON
    $currentChildren = json_decode($user['CHILDRENS_ID'], true);
    if (!is_array($currentChildren)) {
        $currentChildren = [];
    }

    // Append new child ID if provided
    if (!empty($_POST['new_child_id'])) {
        $newChildId = intval($_POST['new_child_id']);
        if (!in_array($newChildId, $currentChildren)) {
            $currentChildren[] = $newChildId;
        }
    }

    // Encode back to JSON
    $updates['CHILDRENS_ID'] = json_encode($currentChildren);

   
$update_stmt = $pdo->prepare("UPDATE userss SET father_id = ?, mother_id = ?, marital_id = ?, CHILDRENS_ID = ? WHERE id = ?");
$update_stmt->execute([
    // Check if mother_id is not an empty string before passing it to the query
    !empty($updates['father_id']) ? $updates['father_id'] : null, 
    !empty($updates['mother_id']) ? $updates['mother_id'] : null,
    !empty($updates['marital_id']) ? $updates['marital_id'] : null,
    $updates['CHILDRENS_ID'], 
    $_SESSION['user_id']
]);

   
   echo "<script>window.location.replace('Dashboard.php?success');</script>"; // Refresh the page to see updated data
    }
}
if (isset($_GET['success'])) {
    
    echo"Updated successfully!!!";
}
if (isset($_GET['failed'])) {
    
    echo "This ID is only for reference. Can't make any changes!!";
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: Login.html');
    exit;
}

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
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <style>
        /* General styles */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
    color: #333;
}

h1, h2 {
    color: #0056b3;
    text-align: center;
}

ul {
    list-style-type: none;
    padding: 0;
}

li {
    margin: 10px 0;
}

/* Container for profile details */
.container {
    width: 80%;
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background: #ffffff;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    border-radius: 8px;
}

.container h2 {
    border-bottom: 2px solid #0056b3;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

/* Form styles */
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

form label {
    font-weight: bold;
}

form input[type="text"] {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

form button {
    padding: 10px 15px;
    background-color: #0056b3;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

form button:hover {
    background-color: #004494;
}

/* Form container for update relations */
.form-container {
    margin-top: 20px;
}

/* Form container for ancestry */
.ancestry-container {
    margin-top: 40px;
}

/* Link styles */
a {
    color: #0056b3;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

.logout-link {
    display: block;
    text-align: center;
    margin-top: 20px;
}

    </style>
</head>
<body>
    <h1>Dashboard</h1>
    <div class="container">
        <p>Welcome, <?php echo htmlspecialchars($user['name']); ?></p>
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
   <div class="container form-container">
    <h2>Update Relations</h2>
    <form action="" method="post">
        <label for="father_id">Father ID:</label>
        <input type="text" id="father_id" name="father_id" value="<?php echo isset($user['father_id']) ? htmlspecialchars($user['father_id']) : ''; ?>"><br>
        <label for="mother_id">Mother ID:</label>
        <input type="text" id="mother_id" name="mother_id" value="<?php echo isset($user['mother_id']) ? htmlspecialchars($user['mother_id']) : ''; ?>"><br>
        <label for="marital_id">Marital ID:</label>
        <input type="text" id="marital_id" name="marital_id" value="<?php echo isset($user['marital_id']) ? htmlspecialchars($user['marital_id']) : ''; ?>"><br>
        <label for="new_child_id">Add Child ID:</label>
        <input type="text" id="new_child_id" name="new_child_id" value="<?php echo isset($user['CHILDRENS_ID']) ? htmlspecialchars($user['CHILDRENS_ID']) : ''; ?>"><br>
        <button type="submit">Update</button>
    </form>
</div>

    
    <div class="container">
        <h1>User Relationship Dashboard</h1>
      
    <div>
        <form action="Table.php" method="post">
            <input type="text" name="personId" value="<?php echo $user['id']; ?>">
            <button type="submit">Family</button>
        </form>
    </div>
    <div>
        
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
        <form action="familytable_27_6_work1.php" method="post">
            <input type="text" name="personId" value="<?php echo $user['id']; ?>">
            <button type="submit">familytable_27_6_work1</button>
        </form>
        <form action="familytable_27_6_work2.php" method="post">
            <input type="text" name="personId" value="<?php echo $user['id']; ?>">
            <button type="submit">familytable_27_6_work2</button>
        </form>
        <form action="view.php" method="post">
            <input type="text" name="personId" value="<?php echo $user['id']; ?>">
            <button type="submit">view Tree</button>
        </form>
        <form action="page_3_7.php" method="post">
            <input type="text" name="personId" value="<?php echo $user['id']; ?>">
            <button type="submit">page_3_7</button>
        </form>
        <form action="page_3_7_graph.php" method="post">
            <input type="text" name="personId" value="<?php echo $user['id']; ?>">
            <button type="submit">page_3_7_graph</button>
        </form>
        <form action="graph_3_7_1.php" method="post">
            <input type="text" name="personId" value="<?php echo $user['id']; ?>">
            <button type="submit">graph_3_7_1</button>
        </form>
        <form action="graph_3_7_2.php" method="post">
            <input type="text" name="personId" value="<?php echo $user['id']; ?>">
            <button type="submit">graph_3_7_2</button>
        </form>

        </div>
    </div>
    <p class="logout-link"><a href="Dashboard.php?logout">Logout</a></p>
</body>
<footer><a style="font-family: 'Courier New', Courier, monospace; background-color: #f4f4f4;  border-radius: 4px; padding: 2px 4px; color: #c7254e; text-decoration: none;" href="https://see-through-headset.000webhostapp.com/data/FAMILY%20RELATIONSHIP%20MANAGEMENT.pdf">Copyright © 2024 Ananth(ananth127). All rights are reserved
</a></footer>
</html>

