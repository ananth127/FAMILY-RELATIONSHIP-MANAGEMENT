
<?php
$host = 'localhost';
$db = 'id21263871_feedback';
$user = 'id21263871_feedbackapp';
$password = 'Ajai@1107';

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
    
    // Great Grandparents
    "Father of Father of Father of Self" => "Great Paternal Grandfather",
    "Mother of Father of Father of Self" => "Great Paternal Grandmother",
    "Father of Mother of Father of Self" => "Great Maternal Grandfather",
    "Mother of Mother of Father of Self" => "Great Maternal Grandmother",
    "Father of Father of Mother of Self" => "Great Paternal Grandfather",
    "Mother of Father of Mother of Self" => "Great Paternal Grandmother",
    "Father of Mother of Mother of Self" => "Great Maternal Grandfather",
    "Mother of Mother of Mother of Self" => "Great Maternal Grandmother",
    
    // Siblings
    "Brother of Self" => "Brother",
    "Sister of Self" => "Sister",
    
    // Siblings' Spouses
    "Husband of Brother of Self" => "Brother-in-law",
    "Wife of Brother of Self" => "Sister-in-law",
    "Husband of Sister of Self" => "Brother-in-law",
    "Wife of Sister of Self" => "Sister-in-law",
    
    // Siblings' Children
    "Son of Brother of Self" => "Nephew",
    "Daughter of Brother of Self" => "Niece",
    "Son of Sister of Self" => "Nephew",
    "Daughter of Sister of Self" => "Niece",
    
    // Uncles and Aunts
    "Brother of Father of Self" => "Paternal Uncle",
    "Sister of Father of Self" => "Paternal Aunt",
    "Brother of Mother of Self" => "Maternal Uncle",
    "Sister of Mother of Self" => "Maternal Aunt",
    
    // Spouses of Uncles and Aunts
    "Husband of Sister of Father of Self" => "Paternal Uncle",
    "Wife of Brother of Father of Self" => "Paternal Aunt",
    "Husband of Sister of Mother of Self" => "Maternal Uncle",
    "Wife of Brother of Mother of Self" => "Maternal Aunt",
    
    // Cousins
    "Child of Brother of Father of Self" => "Paternal Cousin",
    "Child of Sister of Father of Self" => "Paternal Cousin",
    "Child of Brother of Mother of Self" => "Maternal Cousin",
    "Child of Sister of Mother of Self" => "Maternal Cousin",
    
    // Cousins' Children
    "Child of Child of Brother of Father of Self" => "First Cousin Once Removed",
    "Child of Child of Sister of Father of Self" => "First Cousin Once Removed",
    "Child of Child of Brother of Mother of Self" => "First Cousin Once Removed",
    "Child of Child of Sister of Mother of Self" => "First Cousin Once Removed",
    
    // In-laws
    "Husband of Self" => "Husband",
    "Wife of Self" => "Wife",
    "Father of Husband of Self" => "Father-in-law",
    "Mother of Husband of Self" => "Mother-in-law",
    "Father of Wife of Self" => "Father-in-law",
    "Mother of Wife of Self" => "Mother-in-law",
    "Brother of Husband of Self" => "Brother-in-law",
    "Sister of Husband of Self" => "Sister-in-law",
    "Brother of Wife of Self" => "Brother-in-law",
    "Sister of Wife of Self" => "Sister-in-law",
    
    // In-laws' Parents
    "Father of Father of Husband of Self" => "Paternal Grandfather-in-law",
    "Mother of Father of Husband of Self" => "Paternal Grandmother-in-law",
    "Father of Mother of Husband of Self" => "Maternal Grandfather-in-law",
    "Mother of Mother of Husband of Self" => "Maternal Grandmother-in-law",
    "Father of Father of Wife of Self" => "Paternal Grandfather-in-law",
    "Mother of Father of Wife of Self" => "Paternal Grandmother-in-law",
    "Father of Mother of Wife of Self" => "Maternal Grandfather-in-law",
    "Mother of Mother of Wife of Self" => "Maternal Grandmother-in-law",
    
    // Children
    "Son of Self" => "Son",
    "Daughter of Self" => "Daughter",
    
    // Children's Spouses
    "Wife of Son of Self" => "Daughter-in-law",
    "Husband of Daughter of Self" => "Son-in-law",
    
    // Grandchildren
    "Son of Son of Self" => "Grandson",
    "Daughter of Son of Self" => "Granddaughter",
    "Son of Daughter of Self" => "Grandson",
    "Daughter of Daughter of Self" => "Granddaughter",
    
    // Great Grandchildren
    "Son of Son of Son of Self" => "Great Grandson",
    "Daughter of Son of Son of Self" => "Great Granddaughter",
    "Son of Daughter of Son of Self" => "Great Grandson",
    "Daughter of Daughter of Son of Self" => "Great Granddaughter",
    "Son of Son of Daughter of Self" => "Great Grandson",
    "Daughter of Son of Daughter of Self" => "Great Granddaughter",
    "Son of Daughter of Daughter of Self" => "Great Grandson",
    "Daughter of Daughter of Daughter of Self" => "Great Granddaughter",
];

function fetchAncestralAndDescendant($pdo, $id, $relationship = "Self", $depth = 0) {
    global $relationshipMap;

    // Prepare the SQL query
    $stmt = $pdo->prepare("SELECT id, name, gender, father_id, mother_id FROM userss WHERE id = ?");
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();

    // Initialize row color for better readability
    $rowColor = $depth % 2 == 0 ? '#f0f0f0' : '#d0d0d0';

    // Check if we have a result
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $name = htmlspecialchars($result['name']);
        $gender = $result['gender'];
        $father_id = $result['father_id'];
        $mother_id = $result['mother_id'];

        $relationshipName = $relationshipMap[$relationship] ?? $relationship;

        echo "<tr style='background-color: $rowColor;'>";
        echo "<td style='padding-left: " . ($depth * 20) . "px;'>{$result['id']}</td>";
        echo "<td>$name</td>";
        echo "<td>$relationshipName</td>";
        echo "</tr>";

        // Recursive calls for father and mother if they exist
        if ($father_id !== null) {
            fetchAncestralAndDescendant($pdo, $father_id, "Father of $relationship", $depth + 1);
        }
        if ($mother_id !== null) {
            fetchAncestralAndDescendant($pdo, $mother_id, "Mother of $relationship", $depth + 1);
        }

        // Fetch and display descendants
        fetchDescendants($pdo, $id, $relationship, $depth + 1);
    } else {
        echo "<tr style='background-color: $rowColor;'><td colspan='3' style='padding-left: " . ($depth * 20) . "px;'>No data found for ID: $id</td></tr>";
    }
}

function fetchDescendants($pdo, $id, $relationship, $depth) {
    global $relationshipMap;

    // Prepare the SQL query to fetch descendants
    $stmt = $pdo->prepare("SELECT id, name, gender FROM userss WHERE father_id = ? OR mother_id = ?");
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->bindParam(2, $id, PDO::PARAM_INT);
    $stmt->execute();

    // Initialize row color for better readability
    $rowColor = $depth % 2 == 0 ? '#f0f0f0' : '#d0d0d0';

    // Check if we have descendants
    if ($stmt->rowCount() > 0) {
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $name = htmlspecialchars($result['name']);
            $gender = $result['gender'];

            // Construct relationship based on gender
            $childRelationship = ($gender === 'male') ? "Son" : "Daughter";
            $relationshipName = $relationshipMap["$childRelationship of $relationship"] ?? "$childRelationship of $relationship";

            echo "<tr style='background-color: $rowColor;'>";
            echo "<td style='padding-left: " . ($depth * 20) . "px;'>{$result['id']}</td>";
            echo "<td>$name</td>";
            echo "<td>$relationshipName</td>";
            echo "</tr>";

            // Recursive call for each descendant
            fetchDescendants($pdo, $result['id'], "$childRelationship of $relationship", $depth + 1);
        }
    }
}

function fetchParents($pdo, $id, $relationship = "Self", $depth = 0) {
    // Prepare the SQL query
    $stmt = $pdo->prepare("SELECT id, name, father_id, mother_id FROM userss WHERE id = ?");
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();

    // Initialize row color for better readability
    $rowColor = $depth % 2 == 0 ? '#f0f0f0' : '#d0d0d0';

    // Check if we have a result
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $name = htmlspecialchars($result['name']);
        $father_id = $result['father_id'];
        $mother_id = $result['mother_id'];
        
        // Output the row
        echo "<tr style='background-color: $rowColor;'>";
        echo "<td style='padding-left: " . ($depth * 20) . "px;'>$id</td>";
        echo "<td>$name</td>";
        echo "<td>$relationship</td>";
        echo "</tr>";

        // Fetch and display siblings
        if ($father_id !== null || $mother_id !== null) {
            fetchSiblings($pdo, $id, $father_id, $mother_id, "Sibling of $relationship", $depth + 1);
        }

        // Recursive calls for father and mother if they exist
        if ($father_id !== null) {
            fetchParents($pdo, $father_id, "Father of $relationship", $depth + 1);
        }
        if ($mother_id !== null) {
            fetchParents($pdo, $mother_id, "Mother of $relationship", $depth + 1);
        }
    } else {
        echo "<tr style='background-color: $rowColor;'><td colspan='3' style='padding-left: " . ($depth * 20) . "px;'>No parents found for ID: $id</td></tr>";
    }
}

function fetchSiblings($pdo, $id, $father_id, $mother_id, $relationship, $depth) {
    $stmt = $pdo->prepare("SELECT id, name FROM userss WHERE (father_id = ? OR mother_id = ?) AND id != ?");
    $stmt->execute([$father_id, $mother_id, $id]);

    // Initialize row color for better readability
    $rowColor = $depth % 2 == 0 ? '#f0f0f0' : '#d0d0d0';

    // Check if we have results
    if ($stmt->rowCount() > 0) {
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sibling_id = $result['id'];
            $name = htmlspecialchars($result['name']);
            
            // Output the row
            echo "<tr style='background-color: $rowColor;'>";
            echo "<td style='padding-left: " . ($depth * 20) . "px;'>$sibling_id</td>";
            echo "<td>$name</td>";
            echo "<td>$relationship</td>";
            echo "</tr>";
        }
    }
}

function fetchChildren($pdo, $id, $relationship = "Self", $depth = 0) {
    // Prepare the SQL query
    $stmt = $pdo->prepare("SELECT id, name FROM userss WHERE father_id = ? OR mother_id = ?");
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->bindParam(2, $id, PDO::PARAM_INT);
    $stmt->execute();

    // Initialize row color for better readability
    $rowColor = $depth % 2 == 0 ? '#f0f0f0' : '#d0d0d0';

    // Check if we have results
    if ($stmt->rowCount() > 0) {
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $child_id = $result['id'];
            $name = htmlspecialchars($result['name']);
            
            // Output the row
            echo "<tr style='background-color: $rowColor;'>";
            echo "<td style='padding-left: " . ($depth * 20) . "px;'>$child_id</td>";
            echo "<td>$name</td>";
            echo "<td>$relationship</td>";
            echo "</tr>";

            // Recursive call for each child
            fetchChildren($pdo, $child_id, "Child of $relationship", $depth + 1);
        }
    } else {
        echo "<tr style='background-color: $rowColor;'><td colspan='3' style='padding-left: " . ($depth * 20) . "px;'>No children found for ID: $id</td></tr>";
    }
}function fetchSpouseGender($pdo, $marital_id) {
    // Prepare the SQL query
    $stmt = $pdo->prepare("SELECT gender FROM userss WHERE marital_id = ?");
    $stmt->bindParam(1, $marital_id, PDO::PARAM_INT);
    $stmt->execute();

    // Check if we have a result
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['gender'];
    } else {
        return null;
    }
}

// Function to fetch wife's relatives and their relationships to the user
function fetchWifeRelatives($pdo, $wife_id, $relationship = "Wife's Relative") {
    // Prepare the SQL query to fetch wife's relatives
    $stmt = $pdo->prepare("SELECT id, name FROM userss WHERE father_id = ? OR mother_id = ?");
    $stmt->bindParam(1, $wife_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $wife_id, PDO::PARAM_INT);
    $stmt->execute();

    // Check if we have results
    if ($stmt->rowCount() > 0) {
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $relative_id = $result['id'];
            $name = htmlspecialchars($result['name']);

            // Output the row
            echo "<tr>";
            echo "<td>$relative_id</td>";
            echo "<td>$name</td>";
            echo "<td>$relationship</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No relatives found for the wife</td></tr>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Tree</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #cccccc; padding: 8px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h1>Family Tree</h1>

    <form method="POST" action="">
        <label for="personId">Enter Person ID:</label>
        <input type="text" id="personId" name="personId" required>
        <input type="submit" value="Show Family Tree">
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['personId'])): ?>
        <h2>Ancestry</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Relationship to Starting ID</th>
                </tr>
            </thead>
            <tbody>
            <?php
                    $starting_id = $_POST['personId'];
                    fetchParents($pdo, $starting_id);
                ?>
            </tbody>
        </table>

        <h2>Descendants</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Relationship to Starting ID</th>
                </tr>
            </thead>
            <tbody>
            <?php
                    fetchChildren($pdo, $starting_id);
                ?>
            </tbody>
        </table>

        <?php
        $marital_id = $_POST['personId'];
        $spouse_gender = fetchSpouseGender($pdo, $marital_id);

        if ($spouse_gender !== null) {
            if ($spouse_gender === 'female') {
                echo "<h2>Husband's Relatives</h2>";
            } else {
                echo "<h2>Wife's Relatives</h2>";
            }

            echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Name</th>";
            echo "<th>Relationship to User</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            fetchWifeRelatives($pdo, $marital_id);
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No spouse found for marital ID: $marital_id</p>";
        }
        ?>
    <?php endif; ?>
    <footer><a style="font-family: 'Courier New', Courier, monospace; background-color: #f4f4f4;  border-radius: 4px; padding: 2px 4px; color: #c7254e; text-decoration: none;" href="https://see-through-headset.000webhostapp.com/data/FAMILY%20RELATIONSHIP%20MANAGEMENT.pdf">Copyright © 2024 Ananth(ananth127). All rights are reserved
</a></footer>
</body>
</html>
