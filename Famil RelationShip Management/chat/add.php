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

// Create family tree table
$sql = "CREATE TABLE IF NOT EXISTS family_tree (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    parent_id INT
)";
if ($conn->query($sql) === TRUE) {
    echo "Family tree table created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Insert family tree data
$sql = "INSERT INTO family_tree (name, parent_id) VALUES 
    ('John & Mary Smith', NULL),
    ('Michael Smith', 1),
    ('Sarah Johnson', 1),
    ('Matthew Smith', 1),
    ('Olivia Smith', 2),
    ('Ethan Smith', 2),
    ('Emily Johnson', 3),
    ('Daniel Johnson', 3),
    ('Lily Smith', 4),
    ('Noah Smith', 4),
    ('Sophia Green', 7),
    ('Jennifer Brown', 6),
    ('Robert Brown', 6),
    ('Emma Brown', 11),
    ('Jacob', NULL)
";
if ($conn->query($sql) === TRUE) {
    echo "Family tree data inserted successfully.<br>";
} else {
    echo "Error inserting data: " . $conn->error;
}

// Close connection
$conn->close();

?>
