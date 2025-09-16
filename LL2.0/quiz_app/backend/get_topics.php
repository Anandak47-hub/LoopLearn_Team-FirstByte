<?php 
include("database.php");
$search=isset($_GET['q'])?$_GET['q']:'';
$stmt=$conn->prepare("SELECT id,name FROM topics WHERE name LIKE CONCAT('%','?','%')");
$stmt->execute();
$result=$stmt->get_result();

$topics=[];
while($row=$result->fetch_assoc()){
    $topics[]=$row;
}
echo json_encode($topics);
?>