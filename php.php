<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "appointments_system";

// CONNECT TO DATABASE
$conn = mysqli_connect($host, $user, $pass, $dbname);

// CHECK CONNECTION
if(!$conn){
    die("Database connection failed: " . mysqli_connect_error());
}
?>