<?php
/**
 * Authentication Controller
 * Handles login, logout, and session management
 */

require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
        
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Display login form and handle login submission
     */
    public function login() {
        $error = '';
        
        // If already logged in, redirect to dashboard
        if ($this->userModel->isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // Validate input
            if (empty($username) || empty($password)) {
                $error = 'Please fill in all fields';
            } else {
                // Attempt authentication
                $user = $this->userModel->authenticate($username, $password);
                
                if ($user) {
                    // Set session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['login_time'] = time();
                    
                    // Redirect to dashboard
                    $this->redirect('dashboard');
                } else {
                    $error = 'Invalid username or password';
                }
            }
        }
        
        // Load login view
        require_once __DIR__ . '/../views/auth/login.php';
    }

    /**
     * Logout user
     */
    public function logout() {
        $this->userModel->logout();
        $this->redirect('login');
    }

    /**
     * Check if user is authenticated
     */
    public function requireAuth() {
        if (!$this->userModel->isLoggedIn()) {
            $this->redirect('login');
        }
    }

    /**
     * Redirect to specified page
     */
    private function redirect($page, $params = []) {
        $url = BASE_URL . "/index.php?page={$page}";
        
        if (!empty($params)) {
            $url .= '&' . http_build_query($params);
        }
        
        header("Location: {$url}");
        exit;
    }

    /**
     * Create default admin (one-time setup)
     */
    public function setup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $_POST['username'] ?? 'admin',
                'password' => $_POST['password'] ?? 'admin123'
            ];
            
            if ($this->userModel->create($data)) {
                echo "Admin user created successfully!";
            } else {
                echo "Error creating admin user";
            }
        } else {
            // Show setup form
            ?>
            <form method="POST">
                <h2>Create Admin User</h2>
                <input type="text" name="username" placeholder="Username" value="admin">
                <input type="password" name="password" placeholder="Password" value="admin123">
                <button type="submit">Create Admin</button>
            </form>
            <?php
        }
    }

    /**
     * Get current user info
     */
    public function getCurrentUser() {
        if ($this->userModel->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username']
            ];
        }
        
        return null;
    }
}
?>