<?php
session_start();

// Only allow logged-in admins
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// CSRF token (simple)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// DB credentials
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = ''; // no password
$db_name = 'opalglow';

// Create connection (mysqli)
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_errno) {
    die("Database connection failed: " . $mysqli->connect_error);
}

$errors = [];
$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($csrf_token, $_POST['csrf_token'] ?? '')) {
        $errors[] = "Invalid CSRF token.";
    } else {
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');

        // Validate required fields
        if ($first_name === '' || $last_name === '' || $username === '' || $email === '' || $password === '' || $confirm_password === '') {
            $errors[] = "All fields are required.";
        } elseif ($password !== $confirm_password) {
            $errors[] = "Passwords do not match.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email address.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $mysqli->prepare("INSERT INTO users (first_name, last_name, username, email, phone, password_hash) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $first_name, $last_name, $username, $email, $phone, $hashed_password);
            if ($stmt->execute()) {
                $messages[] = "User created successfully.";
            } else {
                $errors[] = "Failed to create user.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Create User — Admin — Opal Glow</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" href="assets/css/admin_create_user.css">
</head>
<body>
    <header class="admin-header">
        <div class="dashboard-logo">OpalGlow</div>
        <div class="header-right">
            <span class="admin-badge">👤 Admin</span>
            <a href="admin_users.php" class="back-btn">← Back</a>
            <a href="logout.php" class="logout-btn">⏻ Logout</a>
        </div>
    </header>

    <main class="container">
        <h1>Create User</h1>
        <p class="subtitle">Fill out the form below to create a new user.</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $e) echo htmlspecialchars($e) . "<br>"; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($messages)): ?>
            <div class="alert alert-success">
                <?php foreach ($messages as $m) echo htmlspecialchars($m) . "<br>"; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="create-user-form">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <label>
                First Name
                <input type="text" name="first_name" required>
            </label>

            <label>
                Last Name
                <input type="text" name="last_name" required>
            </label>

            <label>
                Username
                <input type="text" name="username" required>
            </label>

            <label>
                Email
                <input type="email" name="email" required>
            </label>

            <label>
                Phone
                <input type="text" name="phone">
            </label>

            <label>
                Password
                <input type="password" name="password" required>
            </label>

            <label>
                Confirm Password
                <input type="password" name="confirm_password" required>
            </label>

            <button type="submit" class="btn">Create User</button>
        </form>
    </main>

    <footer class="admin-footer">
        &copy; <?php echo date("Y"); ?> Opal Glow. All rights reserved.
    </footer>
</body>
</html>
