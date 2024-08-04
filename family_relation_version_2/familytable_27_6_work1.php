<?php
$host = 'localhost';
$db = 'relationship';
$user = 'root';
$password = '';

// Create PDO instance
$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$relationshipMap = [
    // Self
    "Self" => "Self",
    
    // Parents
    "Father of Self" => "Father",
    "Mother of Self" => "Mother",
    
    // Grandparents
    "Father of Father of Self" => "Paternal Grandfather",
    "Mother of Father of Self" => "Paternal Grandmother",
    "Father of Mother of Self" => "Maternal Grandfather",
    "Mother of Mother of Self" => "Maternal Grandmother",
    
    // Siblings
    "Brother of Self" => "Brother",
    "Sister of Self" => "Sister",
    
    // Children
    "Son of Self" => "Son",
    "Daughter of Self" => "Daughter",
    
    // Spouses
    "Husband of Self" => "Husband",
    "Wife of Self" => "Wife",
];

function fetchFamilyTree($pdo, $id) {
    global $relationshipMap;
    
    // Initialize the queue for BFS
    $queue = [];
    array_push($queue, ["id" => $id, "relationship" => "Self", "depth" => 0]);
    
    while (!empty($queue)) {
        $current = array_shift($queue);
        $currentId = $current['id'];
        $currentRelationship = $current['relationship'];
        $currentDepth = $current['depth'];
        
        // Fetch the current person
        $stmt = $pdo->prepare("SELECT id, name, gender, father_id, mother_id, marital_id FROM userss WHERE id = ?");
        $stmt->execute([$currentId]);
        $person = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($person) {
            $name = htmlspecialchars($person['name']);
            $relationshipName = $relationshipMap[$currentRelationship] ?? $currentRelationship;
            $rowColor = $currentDepth % 2 == 0 ? '#f0f0f0' : '#d0d0d0';
            echo "<tr style='background-color: $rowColor;'>";
            echo "<td style='padding-left: " . ($currentDepth * 20) . "px;'>{$person['id']}</td>";
            echo "<td>$name</td>";
            echo "<td>$relationshipName</td>";
            echo "</tr>";
            
            if ($currentDepth < 2) {
                // Enqueue parents
                if ($person['father_id']) {
                    array_push($queue, ["id" => $person['father_id'], "relationship" => "Father of $currentRelationship", "depth" => $currentDepth + 1]);
                }
                if ($person['mother_id']) {
                    array_push($queue, ["id" => $person['mother_id'], "relationship" => "Mother of $currentRelationship", "depth" => $currentDepth + 1]);
                }
                
                // Enqueue descendants
                $descendantStmt = $pdo->prepare("SELECT id, gender FROM userss WHERE father_id = ? OR mother_id = ?");
                $descendantStmt->execute([$currentId, $currentId]);
                while ($descendant = $descendantStmt->fetch(PDO::FETCH_ASSOC)) {
                    $childRelationship = ($descendant['gender'] === 'male') ? "Son" : "Daughter";
                    array_push($queue, ["id" => $descendant['id'], "relationship" => "$childRelationship of $currentRelationship", "depth" => $currentDepth + 1]);
                }
                
                // Enqueue spouse
                if ($person['marital_id']) {
                    $spouseStmt = $pdo->prepare("SELECT id, gender FROM userss WHERE id = ?");
                    $spouseStmt->execute([$person['marital_id']]);
                    $spouse = $spouseStmt->fetch(PDO::FETCH_ASSOC);
                    if ($spouse) {
                        $spouseRelationship = ($spouse['gender'] === 'male') ? "Husband" : "Wife";
                        array_push($queue, ["id" => $spouse['id'], "relationship" => "$spouseRelationship of $currentRelationship", "depth" => $currentDepth + 1]);
                    }
                }
            }
        }
    }
}

$id = $_POST['personId']; // Default to ID 1 if not provided
?>

<!DOCTYPE html>
<html>
<head>
    <title>Family Tree</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Family Tree</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Relationship</th>
        </tr>
        <?php fetchFamilyTree($pdo, $id); ?>
    </table>
</body>
</html>
