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

// Fetch user data for editing
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    $stmt = $mysqli->prepare("SELECT user_id, first_name, last_name, username, email, phone FROM users WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    if (!$user) {
        die("User not found.");
    }
} else {
    die("User ID not specified.");
}

// Handle update action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    if (!hash_equals($csrf_token, $_POST['csrf_token'] ?? '')) {
        $errors[] = "Invalid CSRF token.";
    } else {
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if ($first_name === '' || $last_name === '' || $username === '' || $email === '') {
            $errors[] = "Missing required fields.";
        } else {
            // Basic email validation
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email address.";
            } else {
                $stmt = $mysqli->prepare("UPDATE users SET first_name = ?, last_name = ?, username = ?, email = ?, phone = ? WHERE user_id = ?");
                $stmt->bind_param("sssssi", $first_name, $last_name, $username, $email, $phone, $user_id);
                if ($stmt->execute()) {
                    $messages[] = "User updated successfully.";
                } else {
                    $errors[] = "Failed to update user.";
                }
                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Edit User — Admin — Opal Glow</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" href="assets/css/admin_edit_user.css">
</head>
<body>
    <header class="admin-header">
        <div class="dashboard-logo">OpalGlow</div>
        <div class="header-right">
            <a href="admin_users.php" class="back-btn">← Back</a>
            <a href="logout.php" class="logout-btn">⏻ Logout</a>
        </div>
    </header>

    <main class="container">
        <h1>Edit User</h1>

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

        <form action="admin_edit_user.php?user_id=<?php echo $user_id; ?>" method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <label>First Name</label>
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>

            <label>Last Name</label>
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>

            <label>Username</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">

            <button type="submit" class="btn">Save Changes</button>
        </form>
    </main>
</body>
</html>
