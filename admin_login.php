<?php
session_start();
include 'db_connect.php'; // Make sure $conn is correctly set


$php_errormsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = trim($_POST['identifier']); // <-- use 'identifier' to match your form
    $password = $_POST['password'];

    if (empty($identifier) || empty($password)) {
        $php_errormsg = "Email/Username and password are required!";
    } else {
        // Fetch admin by email or username
        $stmt = $conn->prepare("SELECT admin_id, username, password_hash, role FROM admin WHERE email=? OR username=?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $username, $hashed_password, $role);
            $stmt->fetch();
            $hashed_password = trim($hashed_password);
            if (!empty($hashed_password) && password_verify($password, $hashed_password)) {
                // Successful admin login
                session_regenerate_id(true);
                $_SESSION['admin_id'] = $id;
                $_SESSION['admin_username'] = $username;
                $_SESSION['admin_role'] = $role;

                header("Location: admin_dashboard.php"); 
                exit;
            } else {
                $php_errormsg = "Invalid password!";
            }
        } else {
            $php_errormsg = "No admin account found with that email or username.";
        }

        $stmt->close();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin Login — Opal Glow</title>

  <!-- External CSS -->
  <link rel="stylesheet" href="assets/css/admin_login.css">
</head>
<body>
  <div class="card" role="main" aria-labelledby="adminLoginTitle">
    <div class="brand">
      <div class="logo">OG</div>
      <div>
        <div class="brand-title">Opal Glow</div>
        <div class="brand-subtitle">Admin Portal</div>
      </div>
    </div>

    <h1 id="adminLoginTitle">Administrator Sign In</h1>
    <p class="lead">Login with your admin credentials.</p>

    <form id="adminLoginForm" method="post" action="admin_login.php" novalidate>
      <div id="serverError" class="error" style="display:none;"></div>

      <div class="field">
        <label for="identifier">Email or Username</label>
        <input id="identifier" name="identifier" type="text" autocomplete="username" required>
      </div>

      <div class="field">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required>
      </div>

      <div class="options-row">
        <label class="show-pass">
          <input id="showpass" type="checkbox">
          Show password
        </label>
        <a href="#" class="small">Forgot password?</a>
      </div>

      <div class="actions">
        <button type="submit" class="btn btn-primary">Sign in</button>
        <a href="index.php" class="btn btn-ghost">Back</a>
      </div>
    </form>

    <div class="footer-note">
      Only authorized personnel may access this area.
    </div>
  </div>

  <script>
    // Show/hide password toggle
    document.getElementById('showpass').addEventListener('change', function(){
      document.getElementById('password').type = this.checked ? 'text' : 'password';
    });

    // Client-side validation
    document.getElementById('adminLoginForm').addEventListener('submit', function(e){
      var identifier = document.getElementById('identifier').value.trim();
      var pass = document.getElementById('password').value;
      var errBox = document.getElementById('serverError');
      errBox.style.display = 'none';

      if (!identifier || !pass) {
        e.preventDefault();
        errBox.textContent = 'Please enter both identifier and password.';
        errBox.style.display = 'block';
      }
    });
  </script>
</body>
</html>
