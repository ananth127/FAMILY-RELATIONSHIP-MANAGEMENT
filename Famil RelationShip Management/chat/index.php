<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HMS welcome</title>
</head>

<body>
<?php
    if (isset($_POST['submitt'])) {
        $number = $_POST['number'];
        if ($number == 24) { // Changed to '==' for comparison
            header("Location: home.php"); // Corrected file name
            exit; // Added exit to prevent further execution
        }
    }
?>

    <div>
    <form action="" method='POST'>
                        <input type="text" name='number'>
                        <input type="submit" name='submitt'>
                    </form>

    </div>
</body>

</html>