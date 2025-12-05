<?php
$servername="localhost";
$username="root";
$password="";
$dbname="opalglow";
$conn=new mysqli($servername,$username,$password,$dbname);
if ($conn-> connect_error){
    die("database connection failed: ".$conn->connect_error);
}
?>