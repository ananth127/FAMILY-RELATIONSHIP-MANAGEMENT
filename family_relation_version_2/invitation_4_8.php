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

function sendInvitations($pdo, $id, $relationshipMap) {
    // Fetch the family tree
    $treeData = fetchFamilyTree($pdo, $id, $relationshipMap);
    $familyTree = json_decode($treeData, true);

    // List of invited people
    $invitedNames = [];

    foreach ($familyTree as $person) {
        // Simulate sending an invitation
        // Here you could add code to send an email or any other type of invitation
        $invitedNames[] = $person['name'];
    }

    return json_encode($invitedNames);
}

function fetchFamilyTree($pdo, $id, $relationshipMap) {
    // Initialize the queue for BFS
    $queue = [];
    $visited = [];
    $treeData = [];
    array_push($queue, ["id" => $id, "relationship" => "Self", "depth" => 0, "parent" => null]);
    $visited[$id] = true;

    while (!empty($queue)) {
        $current = array_shift($queue);
        $currentId = $current['id'];
        $currentRelationship = $current['relationship'];
        $currentDepth = $current['depth'];
        $parent = $current['parent'];

        // Fetch the current person
        $stmt = $pdo->prepare("SELECT id, name, gender, father_id, mother_id, marital_id FROM userss WHERE id = ?");
        $stmt->execute([$currentId]);
        $person = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($person) {
            $name = htmlspecialchars($person['name']);
            $relationshipName = $relationshipMap[$currentRelationship] ?? $currentRelationship;
            $treeData[] = ["id" => $person['id'], "name" => $name, "relationship" => $relationshipName, "parent" => $parent];

            if ($currentDepth < 4) {
                // Enqueue parents
                if ($person['father_id'] && !isset($visited[$person['father_id']])) {
                    array_push($queue, ["id" => $person['father_id'], "relationship" => "Father of $currentRelationship", "depth" => $currentDepth + 1, "parent" => $person['id']]);
                    $visited[$person['father_id']] = true;
                }
                if ($person['mother_id'] && !isset($visited[$person['mother_id']])) {
                    array_push($queue, ["id" => $person['mother_id'], "relationship" => "Mother of $currentRelationship", "depth" => $currentDepth + 1, "parent" => $person['id']]);
                    $visited[$person['mother_id']] = true;
                }

                // Enqueue descendants
                $descendantStmt = $pdo->prepare("SELECT id, gender FROM userss WHERE father_id = ? OR mother_id = ?");
                $descendantStmt->execute([$currentId, $currentId]);
                while ($descendant = $descendantStmt->fetch(PDO::FETCH_ASSOC)) {
                    if (!isset($visited[$descendant['id']])) {
                        $childRelationship = ($descendant['gender'] === 'male') ? "Son" : "Daughter";
                        array_push($queue, ["id" => $descendant['id'], "relationship" => "$childRelationship of $currentRelationship", "depth" => $currentDepth + 1, "parent" => $person['id']]);
                        $visited[$descendant['id']] = true;
                    }
                }

                // Enqueue spouse
                if ($person['marital_id'] && !isset($visited[$person['marital_id']])) {
                    $spouseStmt = $pdo->prepare("SELECT id, gender FROM userss WHERE id = ?");
                    $spouseStmt->execute([$person['marital_id']]);
                    $spouse = $spouseStmt->fetch(PDO::FETCH_ASSOC);
                    if ($spouse) {
                        $spouseRelationship = ($spouse['gender'] === 'male') ? "Husband" : "Wife";
                        array_push($queue, ["id" => $spouse['id'], "relationship" => "$spouseRelationship of $currentRelationship", "depth" => $currentDepth + 1, "parent" => $person['id']]);
                        $visited[$spouse['id']] = true;
                    }
                }
            }
        }
    }
    return json_encode($treeData);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['personId'] ?? 1; // Default to ID 1 if not provided
    $relationshipMap = [
        "Self" => "Self",
        "Father of Self" => "Father",
        "Mother of Self" => "Mother",
        "Father of Father of Self" => "Paternal Grandfather",
        "Mother of Father of Self" => "Paternal Grandmother",
        "Father of Mother of Self" => "Maternal Grandfather",
        "Mother of Mother of Self" => "Maternal Grandmother",
        "Brother of Self" => "Brother",
        "Sister of Self" => "Sister",
        "Son of Self" => "Son",
        "Daughter of Self" => "Daughter",
        "Husband of Self" => "Husband",
        "Wife of Self" => "Wife",
    ];
    $invitedNames = sendInvitations($pdo, $id, $relationshipMap);
    echo $invitedNames;
}
?>
