<?php
/**
 * Front Controller - Entry Point
 * Routes all requests to appropriate controllers
 */

// Load configuration
require_once __DIR__ . '/../app/config/config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get request parameters
$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

// Simple routing
try {
    switch($page) {
        case 'login':
            require_once __DIR__ . '/../app/controllers/AuthController.php';
            $controller = new AuthController();
            
            if ($action === 'logout') {
                $controller->logout();
            } else {
                $controller->login();
            }
            break;
            
        case 'dashboard':
            require_once __DIR__ . '/../app/controllers/DashboardController.php';
            $controller = new DashboardController();
            
            if ($action === 'weeklyStats') {
                $controller->weeklyStats();
            } elseif ($action === 'exportReport') {
                $controller->exportReport();
            } else {
                $controller->index();
            }
            break;
            
        case 'farmers':
            require_once __DIR__ . '/../app/controllers/FarmerController.php';
            $controller = new FarmerController();
            
            switch($action) {
                case 'register':
                    $controller->register();
                    break;
                case 'view':
                    $controller->view();
                    break;
                case 'edit':
                    $controller->edit();
                    break;
                case 'delete':
                    $controller->delete();
                    break;
                case 'search':
                    $controller->search();
                    break;
                default:
                    $controller->index();
            }
            break;
            
        case 'orders':
            require_once __DIR__ . '/../app/controllers/OrderController.php';
            $controller = new OrderController();
            
            switch($action) {
                case 'create':
                    $controller->create();
                    break;
                case 'view':
                    $controller->view();
                    break;
                case 'edit':
                    $controller->edit();
                    break;
                case 'delete':
                    $controller->delete();
                    break;
                case 'receipt':
                    $controller->receipt();
                    break;
                case 'updateStatus':
                    $controller->updateStatus();
                    break;
                default:
                    $controller->index();
            }
            break;
            
        default:
            // 404 Not Found
            header("HTTP/1.0 404 Not Found");
            echo "<h1>404 - Page Not Found</h1>";
            echo "<p>The page you requested could not be found.</p>";
            echo "<a href='" . BASE_URL . "/index.php?page=dashboard'>Go to Dashboard</a>";
    }
} catch (Exception $e) {
    // Error handling
    error_log($e->getMessage());
    echo "<h1>An error occurred</h1>";
    echo "<p>Please try again later.</p>";
}
?>