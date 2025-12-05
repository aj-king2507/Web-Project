<?php
session_start();
include 'db_connect.php'; // Make sure your $conn is correctly set

$php_errormsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $php_errormsg = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $php_errormsg = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $php_errormsg = "Password must be at least 8 characters.";
    } elseif ($password !== $confirm_password) {
        $php_errormsg = "Passwords do not match.";
    } else {
        // Check if email or username exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email=? OR username=?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $php_errormsg = "Username or email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $email, $hashed_password);

            if ($insert->execute()) {
                $_SESSION['success'] = "Registration successful! You can now login.";
                header("Location: login.php");
                exit;
            } else {
                $php_errormsg = "Database error: " . $insert->error;
            }
            $insert->close();
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - Opal Glow</title>
    <meta name="author" content="Heena"/>
    <meta name="keywords" content="Opal Glow, Beauty Salon, Skin care, Hair spa, Body care, Mauritius, Glow treatment"/>
    <meta name="description" content="Opal Glow beauty salon offers premium skin, hair and body care treatments to help you look and feel your glowing best."/>
    <meta charset="utf-8"/>
    <meta name="robots" content="index, follow, archive"/>
    <link rel="stylesheet" href="assets/css/register.css">
    <link href="https://fonts.googleapis.com/css2?family=Courgette&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
</head>

<body>
<div class="split-container">
    <div class="left-side">
        <img src="assets/images/registration_image.jpg" alt="Woman receiving Japanese head massage at Opal Glow salon">
    </div>

    <div class="right-side">
        <div class="back-home">
            <a href="index.php">&larr; Homepage</a>
        </div>

        <h2 class="title">Create Your <span>Glow</span></h2>
        <p class="subtitle">Let Your Glow For Skin, Hair And Body Begin.</p>

        <?php if (!empty($php_errormsg)) {
            echo "<p style='color:red;'>$php_errormsg</p>";
        } ?>

        <form action="" method="POST" class="registration-form">
            <label for="username">Your Name</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" minlength="8" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" minlength="8" required>

            <button type="submit" class="register-btn">Register</button>
        </form>

        <p class="login-text">
            Already have an account? <a href="login.php">Login</a>
        </p>
    </div>
</div>
</body>
</html>
