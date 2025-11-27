<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
session_start();
include 'db_connect.php';
$php_errormsg="";
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $username=trim($_POST['username']);
    $email=trim($_POST['email']);
    $password=$_POST['password'];
    $confirm_password=$_POST['confirm_password'];
    if(empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $php_errormsg="All fields are required.";
    }elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $php_errormsg="Invalid email format.";
    }elseif(strlen($password)<8){
        $php_errormsg="Password length must be 8 characters at least";
    }elseif($password !==$confirm_password){
        $php_errormsg="Password do not match";
    }else{
        $stmt=$conn->prepare("Select id FROM users WHERE email=? or username=?");
        $stmt->bind_param("ss",$email,$username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows>0){
            $php_errormsg="Username or email already registered.";
        }else{
            $hashed_password=password_hash($password,PASSWORD_DEFAULT);
            $insert=$conn->prepare("Insert into users(username,email,password)VALUES(?,?,?)");
            $insert->bind_param("sss",$username,$email,$hashed_password);
            if ($insert->execute()){
                $_SESSION['success']="Registration successful!You can now login.";
                header("Location:login.php");
                exit;
            }else{
               echo "Database error: " . $insert->error ;
            }
            $insert->close();
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
        <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <!-- Display errors -->
    <?php 
    if(!empty($php_errormsg)) {
        echo "<p style='color:red;'>$php_errormsg</p>";
    }
    ?>
    <form method ="POST" action="">
        Username:<input type ="text" name ="username" required><br><br>
        Email: <input type ="email" name="email" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        Confirm Pasword: <input type="password" name="confirm_password" required><br><br>
        <input type="submit" value ="Register">
    </form>
    <p>Already have an account? <a href="login.php"> Login here</a></p>
</body>
</html>