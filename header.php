<?php require_once 'functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Opal Glow</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- keep your original CSS path(s) -->
  <link rel="stylesheet" href="homepage.css">
  <link rel="stylesheet" href="login.css">
  <link rel="stylesheet" href="register.css">
  <link rel="stylesheet" href="booking.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="index.php">Opal Glow</a>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <?php if (!is_logged_in()): ?>
          <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout (<?php echo htmlspecialchars(current_user_name()); ?>)</a></li>
        <?php endif; ?>
        <!-- admin link (points to your admin login) -->
        <li class="nav-item"><a class="nav-link text-danger" href="admin_login.php">Admin</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container my-4">
