
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
    <?php endif; ?>
    <footer><a style="font-family: 'Courier New', Courier, monospace; background-color: #f4f4f4;  border-radius: 4px; padding: 2px 4px; color: #c7254e; text-decoration: none;" href="https://see-through-headset.000webhostapp.com/data/FAMILY%20RELATIONSHIP%20MANAGEMENT.pdf">Copyright © 2024 Ananth(ananth127). All rights are reserved
    </a></footer>
</body>
</html>
