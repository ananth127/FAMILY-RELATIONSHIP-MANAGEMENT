<?php
// Adjust database connection details here
$host = 'localhost';
$dbname = 'relation';
$username = 'root';
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
    // Define your relationship mapping here
    // Example: "Father of Self" => "Father",
    //          "Mother of Self" => "Mother",
    
     // Self and Parents
     "Self" => "Self",
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
     // Additional Relationships

// Maternal Grandparents
"Father of Mother of Self" => "Maternal Grandfather",
"Mother of Mother of Self" => "Maternal Grandmother",

// Maternal Great Grandparents
"Father of Father of Mother of Self" => "Great Maternal Grandfather",
"Mother of Father of Mother of Self" => "Great Maternal Grandmother",
"Father of Mother of Mother of Self" => "Great Maternal Grandfather",
"Mother of Mother of Mother of Self" => "Great Maternal Grandmother",

// Maternal Siblings
"Brother of Mother of Self" => "Maternal Uncle",
"Sister of Mother of Self" => "Maternal Aunt",

// Maternal Siblings' Spouses
"Husband of Sister of Mother of Self" => "Maternal Uncle",
"Wife of Brother of Mother of Self" => "Maternal Aunt",

// Maternal Cousins
"Child of Brother of Mother of Self" => "Maternal Cousin",
"Child of Sister of Mother of Self" => "Maternal Cousin",

// Maternal Cousins' Children
"Child of Child of Brother of Mother of Self" => "First Cousin Once Removed",
"Child of Child of Sister of Mother of Self" => "First Cousin Once Removed",

// Maternal In-laws
"Sister of Mother of Self" => "Sister-in-law",
"Brother of Mother of Self" => "Brother-in-law",

// Maternal In-laws' Parents
"Father of Mother of Mother of Self" => "Maternal Grandfather-in-law",
"Mother of Mother of Mother of Self" => "Maternal Grandmother-in-law",

// Additional Children
"Daughter of Mother of Mother of Self" => "Daughter",

// Additional Grandchildren
"Daughter of Son of Daughter of Mother of Mother of Self" => "Granddaughter",

// Additional Great Grandchildren
"Daughter of Daughter of Daughter of Mother of Mother of Self" => "Great Granddaughter",

// Additional Siblings' Children
"Son of Daughter of Sister of Mother of Self" => "Nephew",
// Relationship Mapping

// Self
"Self" => "Self",

// Father
"Father of Self" => "Father",

// Paternal Grandfather
"Father of Father of Self" => "Paternal Grandfather",

// Son of Father of Father of Self
"Son of Father of Father of Self" => "Son",

// Son of Son of Father of Father of Self
"Son of Son of Father of Father of Self" => "Grandson",

// Daughter of Son of Son of Father of Father of Self
"Daughter of Son of Son of Father of Father of Self" => "Granddaughter",

// Daughter of Daughter of Son of Son of Father of Father of Self
"Daughter of Daughter of Son of Son of Father of Father of Self" => "Great Granddaughter",

// Son of Daughter of Son of Son of Father of Father of Self
"Son of Daughter of Son of Son of Father of Father of Self" => "Great Grandson",

// Son of Father of Father of Self
"Son of Father of Father of Self" => "Son",

// Daughter of Son of Father of Father of Self
"Daughter of Son of Father of Father of Self" => "Daughter",

// Son of Son of Father of Father of Self
"Son of Son of Father of Father of Self" => "Grandson",

// Daughter of Son of Father of Father of Self
"Daughter of Son of Father of Father of Self" => "Granddaughter",

// Daughter of Daughter of Son of Father of Father of Self
"Daughter of Daughter of Son of Father of Father of Self" => "Great Granddaughter",

// Son of Daughter of Son of Father of Father of Self
"Son of Daughter of Son of Father of Father of Self" => "Great Grandson",

// Paternal Grandmother
"Mother of Father of Self" => "Paternal Grandmother",

// Son of Mother of Father of Self
"Son of Mother of Father of Self" => "Son",

// Son of Son of Mother of Father of Self
"Son of Son of Mother of Father of Self" => "Grandson",

// Daughter of Son of Son of Mother of Father of Self
"Daughter of Son of Son of Mother of Father of Self" => "Granddaughter",

// Daughter of Daughter of Son of Son of Mother of Father of Self
"Daughter of Daughter of Son of Son of Mother of Father of Self" => "Great Granddaughter",

// Son of Daughter of Son of Son of Mother of Father of Self
"Son of Daughter of Son of Son of Mother of Father of Self" => "Great Grandson",

// Son of Mother of Father of Self
"Son of Mother of Father of Self" => "Son",

// Daughter of Son of Mother of Father of Self
"Daughter of Son of Mother of Father of Self" => "Daughter",

// Son of Son of Mother of Father of Self
"Son of Son of Mother of Father of Self" => "Grandson",

// Daughter of Son of Mother of Father of Self
"Daughter of Son of Mother of Father of Self" => "Granddaughter",

// Daughter of Daughter of Son of Mother of Father of Self
"Daughter of Daughter of Son of Mother of Father of Self" => "Great Granddaughter",

// Son of Daughter of Son of Mother of Father of Self
"Son of Daughter of Son of Mother of Father of Self" => "Great Grandson",

// Maternal Grandfather
"Father of Mother of Self" => "Maternal Grandfather",

// Daughter of Father of Mother of Self
"Daughter of Father of Mother of Self" => "Daughter",

// Son of Daughter of Father of Mother of Self
"Son of Daughter of Father of Mother of Self" => "Grandson",

// Daughter of Son of Daughter of Father of Mother of Self
"Daughter of Son of Daughter of Father of Mother of Self" => "Granddaughter",

// Daughter of Daughter of Son of Daughter of Father of Mother of Self
"Daughter of Daughter of Son of Daughter of Father of Mother of Self" => "Great Granddaughter",

// Son of Daughter of Son of Daughter of Father of Mother of Self
"Son of Daughter of Son of Daughter of Father of Mother of Self" => "Great Grandson",

// Daughter of Father of Mother of Self
"Daughter of Father of Mother of Self" => "Daughter",

// Son of Daughter of Father of Mother of Self
"Son of Daughter of Father of Mother of Self" => "Son",

// Daughter of Son of Daughter of Father of Mother of Self
"Daughter of Son of Daughter of Father of Mother of Self" => "Granddaughter",

// Daughter of Daughter of Son of Daughter of Father of Mother of Self
"Daughter of Daughter of Son of Daughter of Father of Mother of Self" => "Great Granddaughter",

//


];

function fetchAncestralAndDescendant($pdo, $id, $relationship = "Self", $depth = 0) {
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
            $relationshipName = $relationshipMap[$childRelationship] ?? $childRelationship;

            echo "<tr style='background-color: $rowColor;'>";
            echo "<td style='padding-left: " . ($depth * 20) . "px;'>{$result['id']}</td>";
            echo "<td>$name</td>";
            echo "<td>$relationshipName</td>";
            echo "</tr>";

            // Recursive call for each descendant
            fetchAncestralAndDescendant($pdo, $result['id'], $relationshipName, $depth + 1);
        }
    } else {
        // No descendants found
        echo "<tr style='background-color: $rowColor;'><td colspan='3' style='padding-left: " . ($depth * 20) . "px;'>No descendants found for ID: $id</td></tr>";
    }
}


// Usage Example
$starting_id = $_POST['personId']; // Adjust as needed

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Family Tree</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #cccccc; padding: 8px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h1>Family Tree</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Relationship</th>
            </tr>
        </thead>
        <tbody>";
// Call the function to fetch and display descendants
fetchAncestralAndDescendant($pdo, $starting_id);

echo "</tbody>
</table>

";
?>
<footer><a style="font-family: 'Courier New', Courier, monospace; background-color: #f4f4f4;  border-radius: 4px; padding: 2px 4px; color: #c7254e; text-decoration: none;" href="https://see-through-headset.000webhostapp.com/data/FAMILY%20RELATIONSHIP%20MANAGEMENT.pdf">Copyright © 2024 Ananth(ananth127). All rights are reserved
</a></footer>
</body>
</html>
