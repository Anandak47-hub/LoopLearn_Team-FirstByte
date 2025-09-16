<?php 
include("database.php");
$topic_id=intval($_GET['topic_id']);
$difficulty=$_GET['difficulty'];//easy ba medium,hard

$stmt=$conn->prepare("SELECT id, question_text, option_a, option_b, option_c, option_d,correct_option
                      FROM questions WHERE topic_id=? AND difficulty=?
                      ORDER BY RAND() LIMIT 10");

$stmt->bind_param('is',$topic_id,$difficulty);
$stmt->execute();
$result= $stmt->get_result();

$questions=[];
while($row=$result->fetch_assoc()){
    $questions[]=$row;
}
echo json_encode($questions);
?>