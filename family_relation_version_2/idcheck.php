<?php
$idno=$_POST["id"];
    if ($idno!=9225){
        echo "<h2>Invalid IDNO !!!!!</h2>";
    }
else{
    ?>



<!DOCTYPE html>
<html lang="eng">
<head>
    <link rel="stylesheet" href="studentsnew.html">
    <title>STUDENT PROFILE</title>
    <style>
        table, th, td {
  border: 1px solid;
  text-align:center;
  
}
td{
    width:130px;
}
    </style>
    <script></script>
</head>
<div class="bg">
    <div class="fbt">
       <center> <h2>STUDENTS FEEDBACK DEATILS</h2>
    </div>
    <div class="fbb">
        <h2>INFORMATION TECHNOLOGY</h2></center>
        <form action="index.html" method="post">
            
            <div>
                
            
                    

                   
                        <br>
                        <br>
                                    <br>
                    <CENTER><table >
                        <tr><br>
                            
                            <td>S.NO</td>
                            <td>NAME</td>
                            <td>SCHOOL</td>
                            <td>FEEDBACK</td>
                            <td>RATING</td>
                        </tr><br>
                        <?php 
                        
                        $con = new mysqli('localhost','id21263871_feedbackapp','Ajai@1107','id21263871_feedback');

                        $query="select * from `feedback12`";
                        $result=mysqli_query($con,$query);
                        while($row =mysqli_fetch_array($result)){
                            ?>
                            <tr>
                                
                                <td><?php echo $row['S.NO']; ?></td>
                                <td><?php echo $row['NAME']; ?></td>
                                <td><?php echo $row['SCHOOL']; ?></td>
                                <td><?php echo $row['FEEDBACK']; ?></td>
                                <td><?php echo $row['RATING']; ?></td>
                            </tr>
                            

                            <?php
                             
                        }
                           
                        

            
                        

                    
                     
                    ?>
                    

                </table></center>
            </div><br>
            <a href="studentsnew.html"> NEW STUDENT </a> ADD here</a></p>
           <br>Export : 
            <a href="export.php"><img src="excel.png" style="padding-top:10px;width:40px;height:40px;"></a><br><br>
        </form>
        <br>
    </div>
</div>

</html>
<?php
    
    
}

?>