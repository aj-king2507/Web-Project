<?php
session_start();

// Only allow logged-in admins
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard — Opal Glow</title>
    <link rel="stylesheet" href="assets/css/admin_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="admin-header">
        <div class="dashboard-logo">OpalGlow</div>
        <div class="header-right">
            <span class="admin-badge">👤 Admin</span>
            <a href="logout.php" class="logout-btn">
                <span class="logout-icon">⏻</span> Logout
            </a>
        </div>
    </header>


    <main>
        <h1 class="dashboard-title">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></h1>
        <p class="dashboard-subtitle">Manage the salon efficiently with style.</p>

        <section class="dashboard-cards">
            <div class="card">
                <div class="card-icon">👥</div>
                <h2>Manage Users</h2>
                <p>View, edit, or delete registered users.</p>
                <a href="admin_users.php" class="btn">Go</a>
            </div>

            <div class="card">
                <div class="card-icon">📊</div>
                <h2>View Reports</h2>
                <p>Check system logs, activity reports, and statistics.</p>
                <a href="admin_report.php" class="btn">Go</a>
            </div>

            <div class="card">
                <div class="card-icon">⚙️</div>
                <h2>Settings</h2>
                <p>Configure site settings and admin privileges.</p>
                <a href="admin_settings.php" class="btn">Go</a>
            </div>

            <div class="card">
                <div class="card-icon">📅</div>
                <h2>Appointments</h2>
                <p>Review upcoming bookings and customer details.</p>
                <a href="admin_appointments.php" class="btn">Go</a>
            </div>
        </section>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> Opal Glow. All rights reserved.
    </footer>

    <script>
        // Animate cards on load
        const cards = document.querySelectorAll('.card');
        window.addEventListener('load', () => {
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('show');
                }, index * 150);
            });
        });
    </script>
</body>
</html>
