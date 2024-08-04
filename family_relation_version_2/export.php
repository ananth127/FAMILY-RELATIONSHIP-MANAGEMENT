<?php
    $con = new mysqli('localhost','id21263871_feedbackapp','Ajai@1107','id21263871_feedback');

if(!$con){
    die("Connection Error");
}
date_default_timezone_set('Asia/Kolkata');
 $date=date('Y-m-d H:i:s');
$sql="select * from feedback12";
$res=mysqli_query($con,$sql);
$html='Created : '.$date;
$html.='<table><tr><td>S.NO</td><td>NAME</td><td>SCHOOL</td><td>FEEDBACK</td><td>RATING</td></tr>';
while($row =mysqli_fetch_array($res)){

$html.='<tr><td>'.$row['S.NO'].'</td><td>'.$row['NAME'].'</td><td>'.$row['SCHOOL'].'</td><td>'.$row['FEEDBACK'].'</td><td>'.$row['RATING'].'</td></tr>';

}
$html.='</table>';

header("Content-Type: application/xls");
header("Content-Disposition: attachment;filename=STUDENTS_FEEDBACK.xls");

echo $html;
?>


