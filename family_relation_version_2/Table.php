

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

                $starting_id =$_POST['personId']; ;  // Adjust as needed
                fetchParents($pdo, $starting_id);
            ?>
        </tbody>
    </table>
    <footer><a style="font-family: 'Courier New', Courier, monospace; background-color: #f4f4f4;  border-radius: 4px; padding: 2px 4px; color: #c7254e; text-decoration: none;" href="https://see-through-headset.000webhostapp.com/data/FAMILY%20RELATIONSHIP%20MANAGEMENT.pdf">Copyright © 2024 Ananth(ananth127). All rights are reserved
</a></footer>
</body>
</html>
