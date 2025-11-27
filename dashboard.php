<?php 
session_start();
if (!isset($_SESSION['user_id'])){
    header("Location:login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head><title>Dashboard</title></head>
    <body>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']);?>!</h2>
        <p>You are successfully logged in.</p>
        <a href= "logoutpage.php">logout</a>
    </body>
</html>