<?php
session_start();
include 'db_connect.php';
$php_errormsg="";
if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $email=trim($_POST['email']);
    $password=$_POST['password'];
    if(empty($email)|| empty($password)){
        $php_errormsg="Email and password are required!";
    }else{
    $stmt=$conn->prepare("SELECT id,username,password FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $stmt->store_result();
if($stmt->num_rows==1){
    $stmt->bind_result($id,$username,$hashed_password);
    $stmt->fetch();
    if (!empty($hashed_password) && password_verify($password,$hashed_password)){
        $_SESSION['user_id']=$id;
        $_SESSION['username']=$username;
        header("Location:dashboard.php");
        exit;
    }else{
        $php_errormsg="Invalid password!";
    }
}else{
    $php_errormsg="No account found with that email.";
}
$stmt->close();
}
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
    </head>
    <body>
        <h2>Login</h2>
        <! --Success message from registration -->
        <?php
        if (isset($_SESSION['success'])){
             echo"<p style='color:green;'>".$_SESSION['success']."</p>";
             unset($_SESSION['success']);
        }
        ?>
        <! -- Error message -->  
        <?php  
        if (!empty($php_errormsg)){
            echo"<p style='color:red;'>$php_errormsg</p>";
        }
        ?>
<form method="POST">
Email:<input type="email" name="email" required><br><br>
Password:<input type ="password" name="password" required><br><br>
<input type="submit" value="Login">
</form>
<p>Don't have an account? <a href="Registration.php">Register here</a></p>
    </body>
</html>