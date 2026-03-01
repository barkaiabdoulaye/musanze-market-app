<?php
/**
 * Main Configuration File
 * Contains all application settings and constants
 */

// =====================================================
// Database Configuration
// =====================================================
define('DB_HOST', 'localhost');      // Database host (usually localhost)
define('DB_USER', 'root');           // Database username
define('DB_PASS', '');               // Database password
define('DB_NAME', 'musanze_market'); // Database name

// =====================================================
// Application URLs and Paths
// =====================================================
// Base URL - Change this to your domain in production
// Local development:
define('BASE_URL', 'http://localhost/musanze-market-app/public');

// Production example (uncomment when deploying):
// define('BASE_URL', 'https://musanze-market.infinityfreeapp.com');

define('SITE_NAME', 'Musanze Market Order System');
define('SITE_DESCRIPTION', 'Potato market order management for Musanze aggregators');

// =====================================================
// Session Configuration
// =====================================================
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    // Session security settings
    ini_set('session.cookie_httponly', 1);        // Prevent JavaScript access to session cookie
    ini_set('session.use_only_cookies', 1);       // Force sessions to only use cookies
    ini_set('session.cookie_secure', 0);          // Set to 1 if using HTTPS
    ini_set('session.cookie_samesite', 'Strict'); // CSRF protection
    ini_set('session.gc_maxlifetime', 7200);       // Session timeout (2 hours)
    
    // Session name
    session_name('musanze_session');
}

// =====================================================
// Timezone Configuration
// =====================================================
date_default_timezone_set('Africa/Kigali'); // Rwanda timezone

// =====================================================
// Error Reporting
// =====================================================
// Development mode - Show all errors
if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    // Production mode - Hide errors
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../../logs/error.log');
}

// =====================================================
// File Upload Configuration
// =====================================================
define('MAX_FILE_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf']);
define('UPLOAD_PATH', __DIR__ . '/../../public/uploads/');

// Create upload directory if it doesn't exist
if (!is_dir(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0755, true);
}

// =====================================================
// Pagination Settings
// =====================================================
define('ITEMS_PER_PAGE', 20); // Number of items per page in lists
define('MAX_PAGINATION_LINKS', 5); // Maximum pagination links to show

// =====================================================
// Security Settings
// =====================================================
define('HASH_COST', 12); // Bcrypt cost factor for password hashing
define('CSRF_TOKEN_NAME', 'csrf_token'); // CSRF token name

// =====================================================
// Currency Settings
// =====================================================
define('CURRENCY_SYMBOL', 'RWF');
define('CURRENCY_CODE', 'RWF');
define('DECIMAL_PLACES', 0); // RWF has no decimal places

// =====================================================
// Date/Time Formats
// =====================================================
define('DATE_FORMAT', 'd/m/Y');
define('DATETIME_FORMAT', 'd/m/Y H:i');
define('SQL_DATE_FORMAT', 'Y-m-d');
define('SQL_DATETIME_FORMAT', 'Y-m-d H:i:s');

// =====================================================
// Application Settings
// =====================================================
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // development, staging, production

// =====================================================
// Email Configuration (if needed)
// =====================================================
define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('SMTP_FROM', 'noreply@musanze-market.com');
define('SMTP_FROM_NAME', 'Musanze Market System');

// =====================================================
// Cache Settings
// =====================================================
define('CACHE_ENABLED', false);
define('CACHE_PATH', __DIR__ . '/../../cache/');
define('CACHE_TIME', 3600); // 1 hour in seconds

// Create cache directory if it doesn't exist
if (CACHE_ENABLED && !is_dir(CACHE_PATH)) {
    mkdir(CACHE_PATH, 0755, true);
}

// =====================================================
// API Settings (if applicable)
// =====================================================
define('API_ENABLED', false);
define('API_KEY', '');

// =====================================================
// Feature Flags
// =====================================================
define('ENABLE_REGISTRATION', true);
define('ENABLE_EXPORT', true);
define('ENABLE_RECEIPT_PRINT', true);

// =====================================================
// Load environment-specific configuration
// =====================================================
$env_file = __DIR__ . '/env.php';
if (file_exists($env_file)) {
    require_once $env_file;
}

// =====================================================
// Helper function to get base URL
// =====================================================
function base_url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

// =====================================================
// Helper function to redirect
// =====================================================
function redirect($url) {
    header("Location: " . base_url($url));
    exit;
}

// =====================================================
// Helper function to check if running locally
// =====================================================
function is_localhost() {
    $whitelist = ['127.0.0.1', '::1', 'localhost'];
    return in_array($_SERVER['SERVER_NAME'] ?? '', $whitelist);
}

// =====================================================
// Helper function to get current URL
// =====================================================
function current_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

// =====================================================
// Helper function to format currency
// =====================================================
function format_currency($amount) {
    return number_format($amount, DECIMAL_PLACES, ',', ' ') . ' ' . CURRENCY_SYMBOL;
}

// =====================================================
// Helper function to format date
// =====================================================
function format_date($date, $format = DATE_FORMAT) {
    if (empty($date)) return '-';
    $timestamp = is_numeric($date) ? $date : strtotime($date);
    return date($format, $timestamp);
}

// =====================================================
// Helper function to generate CSRF token
// =====================================================
function generate_csrf_token() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

// =====================================================
// Helper function to verify CSRF token
// =====================================================
function verify_csrf_token($token) {
    if (!isset($_SESSION[CSRF_TOKEN_NAME]) || $token !== $_SESSION[CSRF_TOKEN_NAME]) {
        return false;
    }
    return true;
}

// =====================================================
// Initialize application
// =====================================================
// Set memory limit for large operations
ini_set('memory_limit', '256M');

// Set maximum execution time
ini_set('max_execution_time', 300);

// Default charset
ini_set('default_charset', 'UTF-8');

// =====================================================
// Debug function (only in development)
// =====================================================
if (APP_ENV === 'development') {
    function debug_log($data, $title = '') {
        $log_file = __DIR__ . '/../../logs/debug.log';
        $timestamp = date('Y-m-d H:i:s');
        $message = $title ? "[$timestamp] $title:\n" : "[$timestamp] ";
        $message .= print_r($data, true) . "\n------------------------\n";
        file_put_contents($log_file, $message, FILE_APPEND);
    }
}

// =====================================================
// Database connection test (commented out by default)
// =====================================================

try {
    $test_conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($test_conn->connect_error) {
        error_log("Database connection failed: " . $test_conn->connect_error);
    }
    $test_conn->close();
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
}

?>