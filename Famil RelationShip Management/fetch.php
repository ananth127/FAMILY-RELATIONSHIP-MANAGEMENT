function fetchParents($pdo, $id) {
    // Prepare the SQL query to select the father_id, mother_id from the people table
    $stmt = $pdo->prepare("SELECT father_id, mother_id FROM people WHERE id = ?");
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();

    // Check if we have a result
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $father_id = $result['father_id'];
        $mother_id = $result['mother_id'];

        // Output the IDs
        echo "Person ID: $id - Father ID: $father_id, Mother ID: $mother_id<br>";

        // Display siblings
        fetchSiblings($pdo, $id, $father_id, $mother_id);

        // Recursive calls for father and mother if they exist
        if ($father_id !== null) {
            fetchParents($pdo, $father_id);
        }
        if ($mother_id !== null) {
            fetchParents($pdo, $mother_id);
        }
    } else {
        echo "No parents found for ID: $id<br>";
    }
}

function fetchSiblings($pdo, $id, $father_id, $mother_id) {
    // Prepare the SQL query to select siblings (same father or mother, different ID)
    $stmt = $pdo->prepare("SELECT id, name FROM people WHERE (father_id = ? OR mother_id = ?) AND id != ?");
    $stmt->bindParam(1, $father_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $mother_id, PDO::PARAM_INT);
    $stmt->bindParam(3, $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $siblings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Siblings of ID $id: ";
        foreach ($siblings as $sibling) {
            echo $sibling['name'] . " (ID: " . $sibling['id'] . "), ";
        }
        echo "<br>";
    } else {
        echo "No siblings found for ID: $id<br>";
    }
}
