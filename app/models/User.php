<?php
/**
 * User Model
 * Handles admin/aggregator authentication
 */

require_once __DIR__ . '/../config/database.php';

class User {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Find user by username
     */
    public function findByUsername($username) {
        $stmt = $this->conn->prepare("
            SELECT id, username, password, created_at, last_login 
            FROM users 
            WHERE username = ? 
            LIMIT 1
        ");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        return $result->fetch_assoc();
    }

    /**
     * Authenticate user
     */
    public function authenticate($username, $password) {
        $user = $this->findByUsername($username);
        
        if (!$user) {
            return false;
        }
        
        if (password_verify($password, $user['password'])) {
            // Update last login
            $this->updateLastLogin($user['id']);
            return $user;
        }
        
        return false;
    }

    /**
     * Update last login timestamp
     */
    private function updateLastLogin($userId) {
        $stmt = $this->conn->prepare("
            UPDATE users 
            SET last_login = NOW() 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }

    /**
     * Create new user (admin)
     */
    public function create($data) {
        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT, ['cost' => HASH_COST]);
        
        $stmt = $this->conn->prepare("
            INSERT INTO users (username, password) 
            VALUES (?, ?)
        ");
        $stmt->bind_param("ss", $data['username'], $hashedPassword);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        
        return false;
    }

    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['username']);
    }

    /**
     * Get current user ID
     */
    public function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get current username
     */
    public function getCurrentUsername() {
        return $_SESSION['username'] ?? null;
    }

    /**
     * Logout user
     */
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        session_destroy();
    }

    /**
     * Change password
     */
    public function changePassword($userId, $oldPassword, $newPassword) {
        // Verify old password
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (!password_verify($oldPassword, $user['password'])) {
            return false;
        }
        
        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT, ['cost' => HASH_COST]);
        $updateStmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateStmt->bind_param("si", $hashedPassword, $userId);
        
        return $updateStmt->execute();
    }
}
?>