<?php 
session_start();
include("database.php");

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){//username from session
 http_response_code(401);
 echo json_encode(['error'=>'Not logged in']);
 exit;
}
$data=json_decode(file_get_contents('php://input'),true);
$user_id=$_SESSION['user_id'];
$topic_id=intval($data['topic_id']);
$title= trim($data['title']);

//insert and update user's title
$stmt=$conn->prepare("
                    INSERT INTO user_topic_titles(user_id,topic_id,title)
                    VALUES (?,?,?)
                    ON DUPLICATE KEY UPDATE title =VALUES(title),achieved_at=NOW()");
$stmt->bind_param('iis',$user_id,$topic_id,$title);
$stmt->execute();
echo json_encode(['success'=>true]);

?>