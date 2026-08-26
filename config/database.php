<?php
//Database Configuration
define('DB_HOST', 'sql202.infinityfree.com');
define('DB_USER', 'if0_38914729');
define('DB_PASS', 'Mkori0339');
define('DB_NAME', 'if0_38914729_ngalambela');
define('DB_SOCKET', '/opt/lampp/var/mysql/mysql.sock');
// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_NAME', 'ngalambela_db');
// define('DB_SOCKET', '/opt/lampp/var/mysql/mysql.sock');
//Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, 3306, DB_SOCKET);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db(DB_NAME);

// Site configuration constants
define('SITE_NAME', 'Ngalambela');

// Auto-detect the site URL from the current request
if (isset($_SERVER['HTTP_HOST'])) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    // Detect the base path (e.g. /brandweb if hosted in a subdirectory)
    $script_dir = dirname($_SERVER['SCRIPT_NAME']);
    $base_path = preg_replace('#/admin$#', '', $script_dir); // remove /admin if already inside admin
    $base_path = preg_replace('#/config$#', '', $base_path); // remove /config if inside config
    $base_path = rtrim($base_path, '/');
    define('SITE_URL', $protocol . '://' . $_SERVER['HTTP_HOST'] . $base_path);
} else {
    define('SITE_URL', 'http://localhost/brandweb');
}
define('ADMIN_URL', SITE_URL . '/admin');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

// Session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper functions
function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(trim($data));
}

function redirect($url) {
    // Clean any output buffers before redirect
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    header("Location: $url");
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']) && $_SESSION['admin_id'] > 0;
}

function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function getSetting($key, $default = '') {
    global $conn;
    $stmt = $conn->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    $stmt->bind_param("s", $key);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['setting_value'];
    }
    return $default;
}

function getSettingByLang($key, $lang = null, $default = '') {
    if ($lang === null) {
        $lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
    }
    // Try translated version first
    $value = getSetting($key . '_' . $lang, null);
    if ($value !== null) {
        return $value;
    }
    return getSetting($key, $default);
}
?>