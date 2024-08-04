<?php


    $name=$_POST["name"];
    $school=$_POST["school"];
    $feedback=$_POST["feedback"];
    $rating=$_POST["rating"];


    //data base connection
    $conn = new mysqli('localhost','id21263871_feedbackapp','Ajai@1107','id21263871_feedback');
    if($conn->connect_error){
        echo"connetion problem!!!! ";
    }else{
        $stmt = $conn->prepare("select * from `feedback12` WHERE NAME=?");
                        $stmt->bind_param("s",$name);
                        $stmt->execute();
                        $stmt_result= $stmt->get_result();
                         
        $stmt = $conn->prepare("insert into `feedback12` (NAME,SCHOOL,RATING,FEEDBACK)values(?,?,?,?)");
        $stmt->bind_param("ssss",$name,$school,$rating,$feedback);
        $stmt->execute();
        echo"registration successfully.....";
        $stmt->close();
        $conn->close();}
    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Password</title>
    </head>
    <body>
        <br><a href ="index.html">Go to Home Page</a>
</body>
</html>