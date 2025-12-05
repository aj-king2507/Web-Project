<?php
// functions.php
if (session_status() === PHP_SESSION_NONE) {
    // safe cookie settings (adjust domain when deploying)
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

function is_logged_in() {
    return !empty($_SESSION['user_id']);
}

function current_user_name() {
    return $_SESSION['username'] ?? null;
}

/* === CSRF helpers === */
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    $t = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
    return '<input type="hidden" name="csrf_token" value="' . $t . '">';
}

function verify_csrf($token) {
    return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

/* Small helper to print flash messages */
function flash_set($key, $message) {
    $_SESSION['flash'][$key] = $message;
}
function flash_get($key) {
    if (!empty($_SESSION['flash'][$key])) {
        $m = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $m;
    }
    return null;
}
