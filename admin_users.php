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

// Handle POST actions: delete, update, export CSV
$errors = [];
$messages = [];

// Delete user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!hash_equals($csrf_token, $_POST['csrf_token'] ?? '')) {
        $errors[] = "Invalid CSRF token.";
    } else {
        $delete_id = intval($_POST['user_id'] ?? 0);
        if ($delete_id > 0) {
            $stmt = $mysqli->prepare("DELETE FROM users WHERE user_id = ?");
            $stmt->bind_param("i", $delete_id);
            if ($stmt->execute()) {
                $messages[] = "User deleted successfully.";
            } else {
                $errors[] = "Failed to delete user.";
            }
            $stmt->close();
        } else {
            $errors[] = "Invalid user ID.";
        }
    }
}

// Export CSV (GET or POST)
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    // Basic authorization: ensure admin session
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=opalglow_users_' . date('Ymd_His') . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['user_id', 'first_name', 'last_name', 'username', 'email', 'phone', 'created_at']);
    $res = $mysqli->query("SELECT user_id, first_name, last_name, username, email, phone, created_at FROM users ORDER BY user_id DESC");
    while ($row = $res->fetch_assoc()) {
        // never output password hashes
        fputcsv($output, [$row['user_id'], $row['first_name'], $row['last_name'], $row['username'], $row['email'], $row['phone'], $row['created_at']]);
    }
    fclose($output);
    exit;
}

// --- Fetch list with search + pagination --- 
$search = trim($_GET['search'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$per_page = 12;
$offset = ($page - 1) * $per_page;

// Build query safely
$params = [];
$where_sql = "";
if ($search !== '') {
    // search first name, last name, or email
    $where_sql = "WHERE first_name LIKE ? OR last_name LIKE ? OR username LIKE ? OR email LIKE ?";
    $like = "%{$search}%";
    $params = [$like, $like, $like, $like];
}

// Count total
$count_sql = "SELECT COUNT(*) as cnt FROM users " . $where_sql;
if ($stmt = $mysqli->prepare($count_sql)) {
    if (!empty($params)) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    }
    $stmt->execute();
    $stmt->bind_result($total_count);
    $stmt->fetch();
    $stmt->close();
} else {
    $total_count = 0;
}

$total_pages = max(1, ceil($total_count / $per_page));

$list_sql = "SELECT user_id, username, first_name, last_name, email, phone, created_at FROM users ";
$list_sql .= $where_sql ? $where_sql . " " : "";
$list_sql .= "ORDER BY user_id DESC LIMIT ? OFFSET ?";

// Fetch page rows (fixed bind_param when using dynamic search params)
if ($stmt = $mysqli->prepare($list_sql)) {
    if (!empty($params)) {
        $types = str_repeat('s', count($params)) . 'ii';
        $bind_values = array_merge($params, [$per_page, $offset]);

        $refs = [];
        $refs[] = & $types;
        foreach ($bind_values as $k => $v) {
            $refs[] = & $bind_values[$k];
        }

        call_user_func_array([$stmt, 'bind_param'], $refs);
    } else {
        $stmt->bind_param('ii', $per_page, $offset);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Manage Users — Admin — Opal Glow</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" href="assets/css/admin_users.css">
</head>
<body>
    <header class="admin-header">
        <div class="dashboard-logo">OpalGlow</div>
        <div class="header-right">
            <span class="admin-badge">👤 Admin</span>
            <a href="admin_dashboard.php" class="back-btn">← Dashboard</a>
            <a href="logout.php" class="logout-btn">⏻ Logout</a>
        </div>
    </header>

    <main class="container">
        <h1>Manage Users</h1>
        <p class="subtitle">View, search, edit, and remove registered users. All actions are logged and protected.</p>

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

        <section class="controls">
            <form method="get" class="search-form" onsubmit="return true;">
                <input type="text" name="search" placeholder="Search by name, email or role" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn">Search</button>
                <a href="admin_users.php" class="btn btn-ghost">Reset</a>
            </form>

            <div class="right-controls">
                <a href="admin_users.php?export=csv" class="btn btn-outline">Export CSV</a>
                <a href="admin_create_user.php" class="btn">Create User</a>
            </div>
        </section>

        <section class="user-grid">
            <?php if (empty($users)): ?>
                <div class="empty">No users found.</div>
            <?php else: ?>
                <?php foreach ($users as $u): ?>
                    <article class="user-card">
                        <div class="user-info">
                            <div class="avatar"><?php echo strtoupper($u['username'][0] ?? 'U'); ?></div>
                            <div>
                                <div class="user-name"><?php echo htmlspecialchars($u['username']); ?></div>
                                <div class="user-email"><?php echo htmlspecialchars($u['email']); ?></div>
                                <div class="user-meta">Joined: <?php echo htmlspecialchars($u['created_at']); ?></div>
                            </div>
                        </div>

                        <div class="card-actions">
                            <a href="admin_edit_user.php?user_id=<?php echo $u['user_id']; ?>" class="btn btn-small">Edit</a>

                            <form method="post" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <input type="hidden" name="user_id" value="<?php echo (int)$u['user_id']; ?>">
                                <button type="submit" class="btn btn-danger btn-small">Delete</button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <!-- Pagination -->
        <nav class="pagination">
            <?php if ($page > 1): ?>
                <a class="page" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">← Prev</a>
            <?php else: ?>
                <span class="page disabled">← Prev</span>
            <?php endif; ?>

            <span class="page-info">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>

            <?php if ($page < $total_pages): ?>
                <a class="page" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">Next →</a>
            <?php else: ?>
                <span class="page disabled">Next →</span>
            <?php endif; ?>
        </nav>
    </main>

    <footer class="admin-footer">
        &copy; <?php echo date("Y"); ?> Opal Glow. All rights reserved.
    </footer>
</body>
</html>
