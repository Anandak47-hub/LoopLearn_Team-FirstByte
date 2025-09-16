
<?php
header('Content-Type: application/json');
include("database.php");

$sql = "SELECT sender, message, time_at FROM messages ORDER BY id DESC LIMIT 20";
$result = mysqli_query($conn, $sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => mysqli_error($conn)]);
    exit;
}

$messages = [];
while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = $row;
}

echo json_encode(array_reverse($messages));
?>