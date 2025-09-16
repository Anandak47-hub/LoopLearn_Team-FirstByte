<?php 
include("database.php");
$data = json_decode(file_get_contents("php://input"),true);

$user_name=$data['user_name'];
$topic_id=intval($data['topic_id']);
$score= intval($data['score']);

$sql="INSERT INTO scores(user_name,topic_id,score) VALUES (?,?,?)";

$stmt=$conn->prepare($sql);
$stmt->bind_param('sii',$user_name,$topic_id,$score);
$stmt->execute();

echo json_encode(["status"=>"success"]);
?>