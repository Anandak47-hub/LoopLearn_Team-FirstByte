<?php 
$server_db="localhost";
$user_db="root";
$pass_db="";
$name_db="quiz_app";
$conn="";
$conn=mysqli_connect($server_db,
                    $user_db,
                    $pass_db,
                    $name_db);
if($conn->connect_error){
    die("Database connection failed" . $conn->connect_error);
}
?>