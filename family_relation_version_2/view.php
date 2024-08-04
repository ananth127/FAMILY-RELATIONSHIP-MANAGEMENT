<?php
ini_set('memory_limit', '1024M'); // Increase memory limit

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

function fetchFamilyTree($pdo, $id, $relationship = 'Self', $depth = 0, $maxDepth = 3) {
    global $relationshipMap;

    // Stop if max depth is reached
    if ($depth > $maxDepth) {
        return null;
    }

    // Fetch the current person
    $stmt = $pdo->prepare("SELECT id, name, gender, father_id, mother_id, marital_id FROM userss WHERE id = ?");
    $stmt->execute([$id]);
    $person = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$person) {
        return null;
    }
    
    $name = htmlspecialchars($person['name']);
    $relationshipName = $relationshipMap[$relationship] ?? $relationship;
    
    $tree = [
        'id' => $person['id'],
        'name' => $name,
        'relationship' => $relationshipName,
        'children' => []
    ];
    
    // Add parents
    if ($person['father_id']) {
        $tree['children'][] = fetchFamilyTree($pdo, $person['father_id'], "Father of $relationship", $depth + 1, $maxDepth);
    }
    if ($person['mother_id']) {
        $tree['children'][] = fetchFamilyTree($pdo, $person['mother_id'], "Mother of $relationship", $depth + 1, $maxDepth);
    }
    
    // Add descendants
    $descendantStmt = $pdo->prepare("SELECT id, gender FROM userss WHERE father_id = ? OR mother_id = ?");
    $descendantStmt->execute([$person['id'], $person['id']]);
    while ($descendant = $descendantStmt->fetch(PDO::FETCH_ASSOC)) {
        $childRelationship = ($descendant['gender'] === 'male') ? "Son" : "Daughter";
        $tree['children'][] = fetchFamilyTree($pdo, $descendant['id'], "$childRelationship of $relationship", $depth + 1, $maxDepth);
    }
    
    // Add spouse
    if ($person['marital_id']) {
        $spouseStmt = $pdo->prepare("SELECT id, gender FROM userss WHERE id = ?");
        $spouseStmt->execute([$person['marital_id']]);
        $spouse = $spouseStmt->fetch(PDO::FETCH_ASSOC);
        if ($spouse) {
            $spouseRelationship = ($spouse['gender'] === 'male') ? "Husband" : "Wife";
            $tree['children'][] = fetchFamilyTree($pdo, $spouse['id'], "$spouseRelationship of $relationship", $depth + 1, $maxDepth);
        }
    }
    
    return $tree;
}

$id = $_POST['personId'] ?? 1; // Default to ID 1 if not provided
$familyTree = fetchFamilyTree($pdo, $id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Family Tree</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .tree-node {
            display: inline-block;
            text-align: center;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #fff;
            margin: 10px;
            min-width: 120px;
            position: relative;
        }
        .tree-line {
            stroke: #ccc;
            stroke-width: 2px;
            fill: none;
        }
        svg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }
        .tree-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            position: relative;
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Family Tree</h1>
    <div id="tree-container" class="tree-container"></div>
    <script>
        function createSVGElement(tag, attrs) {
            let elem = document.createElementNS('http://www.w3.org/2000/svg', tag);
            for (let attr in attrs) {
                elem.setAttribute(attr, attrs[attr]);
            }
            return elem;
        }

        function renderTree(tree, container, parentNode = null) {
            if (!tree) return;

            let node = document.createElement('div');
            node.className = 'tree-node';
            node.innerHTML = `${tree.name}<br>(${tree.relationship})`;

            container.appendChild(node);

            if (parentNode) {
                let parentRect = parentNode.getBoundingClientRect();
                let nodeRect = node.getBoundingClientRect();

                let parentX = parentRect.left + parentRect.width / 2;
                let parentY = parentRect.top + parentRect.height;

                let nodeX = nodeRect.left + nodeRect.width / 2;
                let nodeY = nodeRect.top;

                let svg = document.querySelector('svg');
                if (!svg) {
                    svg = createSVGElement('svg', {});
                    document.body.appendChild(svg);
                }

                let path = createSVGElement('path', {
                    d: `M${parentX},${parentY} C${parentX},${(parentY + nodeY) / 2} ${nodeX},${(parentY + nodeY) / 2} ${nodeX},${nodeY}`,
                    class: 'tree-line'
                });
                svg.appendChild(path);
            }

            if (tree.children && tree.children.length > 0) {
                let childrenContainer = document.createElement('div');
                childrenContainer.style.display = 'flex';
                childrenContainer.style.justifyContent = 'center';
                container.appendChild(childrenContainer);

                tree.children.forEach(child => {
                    renderTree(child, childrenContainer, node);
                });
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            let treeContainer = document.getElementById('tree-container');
            renderTree(<?php echo json_encode($familyTree); ?>, treeContainer);
        });
    </script>
</body>
</html>
