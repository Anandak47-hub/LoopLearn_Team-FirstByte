<?php 
session_start();
if(!isset($_SESSION["email"])){
    echo json_encode(['error'=>'Not logged in']);
    exit;
}
    $email=$_SESSION["email"];
    $userFile="users.json";
    $users=json_decode(file_get_contents($userFile),true);
    
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $typing=($_POST["typing"]==='true');

        foreach($users as &$user)
            {
                if($user['email']===$email){
                    $user["typing"]=$typing;
                    file_put_contents($userFile,json_encode($users,JSON_PRETTY_PRINT));
                    echo json_encode(["Status"=>"Updated"]);
                    exit;
                }
            }
            echo json_encode(["Error"=>"User not found"]);
            exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Optional: get another user's typing status by email passed as ?email=...
    $targetEmail = $_GET['email'] ?? null;
    
    if ($targetEmail) {
        foreach ($users as $user) {
            if ($user['email'] === $targetEmail) {
                echo json_encode(['typing' => $user['typing'] ?? false]);
                exit;
            }
        }
        echo json_encode(['typing' => false]);
        exit;
    }

    echo json_encode(['error' => 'No target email specified']);
    exit;
}
?>