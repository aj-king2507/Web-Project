<?php
// admin_login_debug.php  -- TEMPORARY. DELETE when done.
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db_connect.php'; // must set $conn

$debug = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email) {
        $debug[] = "Please enter admin email.";
    } else {
        // 1) Prepare & execute fetch
        $stmt = $conn->prepare("SELECT admin_id, username, email, password_hash FROM admin WHERE email = ?");
        if (!$stmt) {
            $debug[] = "Prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("s", $email);
            if (!$stmt->execute()) {
                $debug[] = "Execute failed: " . $stmt->error;
            } else {
                $res = $stmt->get_result();
                $debug[] = "Query rows returned: " . ($res ? $res->num_rows : 'no result');

                if ($res && $res->num_rows === 1) {
                    $row = $res->fetch_assoc();
                    $stored = $row['password_hash'];

                    $debug[] = "admin_id: " . $row['admin_id'];
                    $debug[] = "username: " . $row['username'];
                    $debug[] = "email: " . $row['email'];

                    // raw info
                    $debug[] = "Stored hash (raw): " . $stored;
                    $debug[] = "Stored hash length (chars): " . mb_strlen($stored, '8bit');
                    $debug[] = "Stored hash HEX: " . bin2hex($stored);

                    // Compare to example hash (optional)
                    // $debug[] = "example hash prefix: " . substr('$2b$12$oFKhsY...',0,10);

                    // password_verify on raw
                    $verify_raw = password_verify($password, $stored);
                    $debug[] = "password_verify(raw) => " . ($verify_raw ? 'TRUE' : 'FALSE');

                    // trimmed stored hash test (in case of accidental spaces/newlines)
                    $trimmed = trim($stored);
                    $debug[] = "Trimmed hash length: " . mb_strlen($trimmed, '8bit');
                    $debug[] = "Trimmed hash HEX: " . bin2hex($trimmed);
                    $verify_trim = password_verify($password, $trimmed);
                    $debug[] = "password_verify(trimmed) => " . ($verify_trim ? 'TRUE' : 'FALSE');

                    // show if stored and trimmed differ
                    $debug[] = "Stored === Trimmed ? " . (($stored === $trimmed) ? 'YES' : 'NO');

                    // show PHP version & algorithm info (helpful)
                    $debug[] = "PHP version: " . phpversion();
                    $debug[] = "password_hash algo used example (PASSWORD_DEFAULT): " . password_hash('x', PASSWORD_DEFAULT);
                } else {
                    $debug[] = "Admin not found or multiple rows. rows: " . ($res ? $res->num_rows : 'no result');
                }
                $res && $res->free();
            }
            $stmt->close();
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Admin Login Debug</title></head>
<body>
<h2>Admin Login Debug (TEMP)</h2>
<form method="POST">
    Email: <input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Test</button>
</form>

<?php if (!empty($debug)): ?>
    <hr>
    <h3>Debug Output</h3>
    <pre><?php echo htmlspecialchars(implode("\n", $debug), ENT_QUOTES | ENT_SUBSTITUTE); ?></pre>
<?php endif; ?>

<p><strong>When finished:</strong> delete this file.</p>
</body>
</html>
