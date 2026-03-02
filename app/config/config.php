<?php
/**
 * Main Configuration File
 * Contains all application settings and constants
 */

// =====================================================
// Database Configuration - VERSION PRODUCTION
// =====================================================
define('DB_HOST', 'sql103.byetcluster.com');      // Votre host InfinityFree
define('DB_USER', 'if0_41278794');                // Votre username
define('DB_PASS', '123');      // CHANGEZ CECI! 
define('DB_NAME', 'if0_41278794_if0_41278794_musanze'); // Votre DB name

// =====================================================
// Application URLs and Paths - VERSION PRODUCTION
// =====================================================
define('BASE_URL', 'https://musanze-market.infinityfreeapp.com/musanze-market-app/public'); // VOTRE URL
define('SITE_NAME', 'Musanze Market Order System');
define('SITE_DESCRIPTION', 'Potato market order management for Musanze aggregators');

// =====================================================
// Session Configuration
// =====================================================
if (session_status() === PHP_SESSION_NONE) {
    // Session security settings
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0);
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.gc_maxlifetime', 7200);
    
    session_name('musanze_session');
    session_start();
}

// =====================================================
// Timezone Configuration
// =====================================================
date_default_timezone_set('Africa/Kigali');

// =====================================================
// Error Reporting - TEMPORAIREMENT EN MODE DEBUG
// =====================================================
// Changé temporairement pour voir les erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../logs/error.log');

// =====================================================
// Currency Settings
// =====================================================
define('CURRENCY_SYMBOL', 'RWF');
define('CURRENCY_CODE', 'RWF');
define('DECIMAL_PLACES', 0);

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
define('APP_ENV', 'development'); // Changé en development pour le debug

// =====================================================
// Helper functions
// =====================================================
function base_url($path = '') {
    // Éviter les doubles slashes
    $path = ltrim($path, '/');
    return BASE_URL . ($path ? '/' . $path : '');
}

function redirect($url) {
    header("Location: " . base_url($url));
    exit;
}

function format_currency($amount) {
    return number_format($amount, 0, ',', ' ') . ' ' . CURRENCY_SYMBOL;
}

// =====================================================
// Test de connexion - ACTIF POUR LE DEBUG
// =====================================================

echo "<!-- Début du test de connexion... -->\n";

try {
    echo "<!-- Tentative de connexion à " . DB_HOST . " avec utilisateur " . DB_USER . " -->\n";
    
    $test_conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($test_conn->connect_error) {
        echo "<!-- ERREUR: " . $test_conn->connect_error . " -->\n";
        error_log("Database connection failed: " . $test_conn->connect_error);
    } else {
        echo "<!-- SUCCÈS: Connexion à la base de données réussie! -->\n";
        
        // Vérifier que les tables existent
        $result = $test_conn->query("SHOW TABLES");
        echo "<!-- Tables trouvées: " . $result->num_rows . " -->\n";
    }
    $test_conn->close();
} catch (Exception $e) {
    echo "<!-- EXCEPTION: " . $e->getMessage() . " -->\n";
    error_log("Database error: " . $e->getMessage());
}

echo "<!-- Fin du test de connexion -->\n";

?>