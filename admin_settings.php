<?php
include('db_connect.php');
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch user data from the database
$query = "SELECT * FROM admin WHERE admin_id = $admin_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Update user data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Update the admin's settings
    $update_query = "UPDATE admin SET username='$username', email='$email', password_hash='$hashed_password' WHERE admin_id = $admin_id";
    
    if (mysqli_query($conn, $update_query)) {
        $message = "Settings updated successfully!";
    } else {
        $message = "Error updating settings: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Admin</title>
    <link rel="stylesheet" href="assets/css/admin_settings.css">
</head>
<body>
    <!-- Header Section (Updated to match admin_report.php) -->
    <header class="admin-header">
        <div class="header-container">
            <div class="logo">
                <h1>OpalGlow Admin Panel</h1>
            </div>
            <nav class="navbar">
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="settings-form">
            <h2>Update Your Information</h2>

            <?php if (isset($message)) { ?>
                <div class="message"><?php echo $message; ?></div>
            <?php } ?>

            <form action="admin_settings.php" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required placeholder="Enter new password">
                </div>
                <button type="submit" class="btn-primary">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2025 OpalGlow - All Rights Reserved</p>
    </footer>
</body>
</html>
