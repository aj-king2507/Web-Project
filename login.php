<?php
session_start(); // Start session to track login status
include 'db_connect.php'; // Include database connection

$php_errormsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $php_errormsg = "Email and password are required!";
    } else {
        $stmt = $conn->prepare("SELECT user_id, username, password_hash FROM users WHERE email=?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $username, $hashed_password);
            $stmt->fetch();

            if (!empty($hashed_password) && password_verify($password, $hashed_password)) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                header("Location: dashboard.php");
                exit;
            } else {
                $php_errormsg = "Invalid password!";
            }
        } else {
            $php_errormsg = "No account found with that email.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - Opal Glow</title>
    <meta name="author" content="Heena"/>
    <meta name="keywords" content="Opal Glow, Beauty Salon, Skin care, Hair spa, Body care, Mauritius, Glow treatment"/>
    <meta name="description" content="Opal Glow beauty salon offers premium skin, hair and body care treatments to help you look and feel your glowing best."/>
    <meta charset="utf-8"/>
    <meta name="robots" content="index, follow, archive"/>
    <link rel="stylesheet" href="assets/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
</head>

<body>
<div class="split-container">
    <div class="left-side">
        <img src="assets/images/login_image.jpg" alt="Opal Glow spa setting with candles, towel, orchid and water droplets">
    </div>

    <div class="right-side">
        <div class="back-home">
            <a href="index.php">&larr; Homepage</a>
        </div>

        <h2 class="title">Welcome Back</h2>
        <p class="subtitle">Log In To Continue Your Glow Journey.</p>

        <?php if (!empty($php_errormsg)) {
            echo "<p style='color:red;'>$php_errormsg</p>";
        } ?>

        <form action="" method="POST" class="login-form">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <p class="register-text">
            Don't have an account? <a href="register.php">Register</a>
        </p>
    </div>
</div>
</body>
</html>
