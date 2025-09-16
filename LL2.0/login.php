<?php
session_start();
if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        $email=strtolower(trim($_POST["email"]));
        $password=trim($_POST["password"]);

        $userFile="users.json";
        $users=file_exists($userFile)?json_decode(file_get_contents($userFile),true):[];
        
        foreach($users as $user){
            if($user["email"]==$email && password_verify($password,$user["password"]))
            {
                $_SESSION["fullname"]=$user["fullname"];
                $_SESSION["email"]=$user["email"];
                $_SESSION["credit"]=$user["credit"];
                header("Location:dashboard.php");
                exit();
            }
        }
        echo "Wrong Credentials!! <a href='Login.html'>Try again </a>";
        exit();
        
    }
?>