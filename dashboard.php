<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Opal Glow Dashboard</title>
    <meta name="author" content="Heena"/>
    <meta name="keywords" content="Opal Glow, Beauty Salon, Skin care, Hair spa, Body care, Mauritius, Glow treatment"/>
    <meta name="description" content="Opal Glow beauty salon offers premium skin, hair and body care treatments to help you look and feel your glowing best."/>
    <meta name="robots" content="index, follow, archive"/>
    <link rel="stylesheet" href="assets/css/dashboard.css">
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
                <li><a href="dashboard.php"class="active">Home</a></li>
                <li><a href="about_us.php">About Us</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="contact_us.php">Contact</a></li>
            </ul>
        </nav>
        <div class="user-actions">
            <span class="person-emoji">👤</span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <main class="hero">
        <div class="intro">
            <p class="tagline">Your Beauty Era Starts Here.</p>
            <h2 class="intro-h2">
                We give your skin,
                hair and body the
                <span>glow</span> they deserve.
            </h2>
            <a href="booking.php" class="booking-btn">Book Your Glow Now</a>
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
