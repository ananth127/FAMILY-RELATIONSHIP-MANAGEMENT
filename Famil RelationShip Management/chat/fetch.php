<?php

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "relationship";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch parent's name
function getParentName($parentId, $conn) {
    if ($parentId) {
        $sql = "SELECT name FROM family_tree WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $parentId);
        $stmt->execute();
        $stmt->bind_result($parentName);
        $stmt->fetch();
        $stmt->close();
        return $parentName;
    } else {
        return "Unknown";
    }
}

// Function to fetch siblings' names
function getSiblingsNames($userId, $parentId, $conn) {
    $sql = "SELECT name FROM family_tree WHERE parent_id = ? AND id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $parentId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $siblings = array();
    while ($row = $result->fetch_assoc()) {
        $siblings[] = $row['name'];
    }
    $stmt->close();
    return $siblings;
}

// Function to fetch children's names
function getChildrenNames($userId, $conn) {
    $sql = "SELECT name FROM family_tree WHERE parent_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $children = array();
    while ($row = $result->fetch_assoc()) {
        $children[] = $row['name'];
    }
    $stmt->close();
    return $children;
}

// Function to fetch grandchildren's names
function getGrandchildrenNames($userId, $conn) {
    $sql = "SELECT name FROM family_tree WHERE parent_id IN (SELECT id FROM family_tree WHERE parent_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $grandchildren = array();
    while ($row = $result->fetch_assoc()) {
        $grandchildren[] = $row['name'];
    }
    $stmt->close();
    return $grandchildren;
}

// Function to fetch grandparents' names
function getGrandparentsNames($userId, $conn) {
    $sql = "SELECT name FROM family_tree WHERE id IN (SELECT parent_id FROM family_tree WHERE id = 
            (SELECT parent_id FROM family_tree WHERE id = ?))";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $grandparents = array();
    while ($row = $result->fetch_assoc()) {
        $grandparents[] = $row['name'];
    }
    $stmt->close();
    return $grandparents;
}

// Function to fetch nieces/nephews names
function getNiecesNephewsNames($userId, $parentId, $conn) {
    $sql = "SELECT name FROM family_tree WHERE parent_id = ? AND id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $parentId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $niecesNephews = array();
    while ($row = $result->fetch_assoc()) {
        $niecesNephews[] = $row['name'];
    }
    $stmt->close();
    return $niecesNephews;
}

// Function to fetch cousins' names
function getCousinsNames($userId, $parentId, $conn) {
    $sql = "SELECT name FROM family_tree WHERE parent_id IN (SELECT id FROM family_tree WHERE parent_id = ?) AND id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $parentId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $cousins = array();
    while ($row = $result->fetch_assoc()) {
        $cousins[] = $row['name'];
    }
    $stmt->close();
    return $cousins;
}

// Fetch all users
$sql = "SELECT id, name, parent_id FROM family_tree";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'><tr><th>User</th><th>Relationship</th></tr>";
    while($row = $result->fetch_assoc()) {
        $userId = $row["id"];
        $userName = $row["name"];
        $parentId = $row["parent_id"];
        
        $parentName = getParentName($parentId, $conn);
        $siblingsNames = getSiblingsNames($userId, $parentId, $conn);
        $childrenNames = getChildrenNames($userId, $conn);
        $grandparentsNames = getGrandparentsNames($userId, $conn);
        $grandchildrenNames = getGrandchildrenNames($userId, $conn);
        $niecesNephewsNames = getNiecesNephewsNames($userId, $parentId, $conn);
        $cousinsNames = getCousinsNames($userId, $parentId, $conn);
        
        echo "<tr><td>$userName</td><td>";
        
        // Check if parent exists
        if ($parentName !== "Unknown") {
            echo " - " . $userName . " is the child of " . $parentName . "<br>";
        }
        
        // Check if siblings exist
        if (!empty($siblingsNames)) {
            echo " - " . $userName . " has the following siblings: " . implode(", ", $siblingsNames) . "<br>";
        }
        
        // Check if children exist
        if (!empty($childrenNames)) {
            echo " - " . $userName . " is the parent of the following: " . implode(", ", $childrenNames) . "<br>";
        }
        
        // Check if grandparents exist
        if (!empty($grandparentsNames)) {
            echo " - " . $userName . " is the grandchild of the following: " . implode(", ", $grandparentsNames) . "<br>";
        }

        // Check if grandchildren exist
        if (!empty($grandchildrenNames)) {
            echo " - " . $userName . " is the grandparent of the following: " . implode(", ", $grandchildrenNames) . "<br>";
        }
        
        // Check if nieces/nephews exist
        if (!empty($niecesNephewsNames)) {
            echo " - " . $userName . " is the aunt/uncle of the following: " . implode(", ", $niecesNephewsNames) . "<br>";
        }
        
        // Check if cousins exist
        if (!empty($cousinsNames)) {
            echo " - " . $userName . " is the cousin of the following: " . implode(", ", $cousinsNames) . "<br>";
        }
        
        // Add more relationship types here
        
        echo "</td></tr>";
    }
    echo "</table>";
} else {
    echo "No records found.";
}

// Close connection
$conn->close();

?>
