<?php
    if (isset($_POST['username'])) {
        $number = $_POST['username'];
        if ($number == 24) { // Changed to '==' for comparison
            header("Location: home.php"); // Corrected file name
            exit; // Added exit to prevent further execution
        }
    }
?>