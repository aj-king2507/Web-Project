<?php
$servername="localhost";
$username="root";
$password="";
$dbname="user_authentication";
$conn=new mysqli($servername,$username,$password,$dbname);
if ($conn-> connect_error){
    die("database connection failed: ".$conn->connect_error);
}
?>