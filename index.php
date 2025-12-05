<?php
session_start(); // Start session to track user/admin login status
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Opal Glow</title>
    <meta name="author" content="Heena"/>
    <meta name="keywords" content="Opal Glow, Beauty Salon, Skin care, Hair spa, Body care, Mauritius, Glow treatment"/>
    <meta name="description" content="Opal Glow beauty salon offers premium skin, hair and body care treatments to help you look and feel your glowing best."/>
    <meta charset="utf-8"/>
    <meta name="robots" content="index, follow, archive"/>
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link href="https://fonts.googleapis.com/css2?family=Courgette&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <div class="logo">
        <h1>Opal Glow</h1>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about_us.php">About Us</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="contact_us.php">Contact Us</a></li>
        </ul>
    </nav>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="dashboard.php" class="login-btn">Dashboard</a>
    <?php else: ?>
        <a href="login.php" class="login-btn">Login</a>
    <?php endif; ?>

    <?php if (isset($_SESSION['admin_id'])): ?>
        <a href="admin_dashboard.php" class="admin-login-btn">Admin</a>
    <?php else: ?>
        <a href="admin_login.php" class="admin-login-btn">Admin</a>
    <?php endif; ?>
</header>

<main class="hero">
    <div class="intro">
        <p class="tagline">Your Beauty Era Starts Here.</p>
        <h2 class="intro-h2">
            We give your skin,
            hair and body the
            <span>glow</span> they deserve.
        </h2>
        <?php if (isset($_SESSION['user_id'])): ?>
        <a href="booking.php" class="booking-btn">Book Your Glow Now</a>
        <?php else: ?>
            <a href="login.php" class="booking-btn">Book Your Glow Now</a>
        <?php endif; ?>
    </div>

    <div class="hero-image">
        <img src="assets/images/homepage_image.jpg" alt="Opal Glow skincare model with radiant skin">
    </div>
</main>

<footer>
    &copy; <?php echo date("Y"); ?> Opal Glow. All Rights Reserved.
</footer>
</body>
</html>
