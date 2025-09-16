<?php
$file = "classes.json";

// Read old data
$data = [];
if (file_exists($file)) {
    $data = json_decode(file_get_contents($file), true);
}

// New class
$newClass = [
    "title" => $_POST['title'],
    "host" => $_POST['host'],
    "room_url" => $_POST['roomUrl'],
    "created_at" => date("Y-m-d H:i:s")
];

// Add to array
$data[] = $newClass;

// Save back to file
file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));

echo json_encode(["success" => true]);
