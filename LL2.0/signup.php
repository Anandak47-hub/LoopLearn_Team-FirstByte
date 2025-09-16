<?php
    session_start();
    if($_SERVER["REQUEST_METHOD"]=="POST")
        {
            //inputing the information
            $fullname=htmlspecialchars(trim($_POST["fullname"]));
            $password=password_hash(trim($_POST["password"]),PASSWORD_DEFAULT);
            $email=htmlspecialchars(trim($_POST["email"]));
            $reffer=htmlspecialchars(trim($_POST["referral_code"]));


            //saving in a simple session variable
           
            //Load users
            $userFile="users.json";
            $users=file_exists($userFile)? json_decode(file_get_contents($userFile),true): [];

            //check if email already exist
            foreach($users as $user)
            {
                if($user["email"]==$email){echo"Email already exist!! try loging in<br> ";
                    exit();
                }
            }
            $users[]=[
                "fullname" => $fullname,
                "email"=> $email,
                "password"=> $password,
                "credit"=> 120,
                "typing"=>false
            ];
            file_put_contents($userFile,json_encode($users,JSON_PRETTY_PRINT));

            $_SESSION["fullname"]=$fullname;
            $_SESSION["email"]=$email;
            $_SESSION["credit"]=120;


            //TODO->save the database or file if neeed

            header("Location:dashboard.php");
            exit();

        }
        else{
            //if request method is not post then send them back to login page
        header("Location:Login.html");
        exit();
        }
?>