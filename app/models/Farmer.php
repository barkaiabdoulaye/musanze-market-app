<?php
/**
 * Farmer Model
 * Handles farmer registration and management
 */

require_once __DIR__ . '/../config/database.php';

class Farmer {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Get all farmers
     */
    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT * FROM farmers ORDER BY created_at DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT ? OFFSET ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $limit, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->conn->query($sql);
        }
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get farmer by ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM farmers WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    /**
     * Create new farmer
     */
    public function create($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO farmers (full_name, phone, location) 
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("sss", 
            $data['full_name'], 
            $data['phone'], 
            $data['location']
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        
        return false;
    }

    /**
     * Update farmer
     */
    public function update($id, $data) {
        $stmt = $this->conn->prepare("
            UPDATE farmers 
            SET full_name = ?, phone = ?, location = ? 
            WHERE id = ?
        ");
        $stmt->bind_param("sssi", 
            $data['full_name'], 
            $data['phone'], 
            $data['location'], 
            $id
        );
        
        return $stmt->execute();
    }

    /**
     * Delete farmer
     */
    public function delete($id) {
        // First check if farmer has orders
        $checkStmt = $this->conn->prepare("
            SELECT COUNT(*) as count FROM orders WHERE farmer_id = ?
        ");
        $checkStmt->bind_param("i", $id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            // Has orders - soft delete or prevent
            return false;
        }
        
        // No orders - safe to delete
        $stmt = $this->conn->prepare("DELETE FROM farmers WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Search farmers
     */
    public function search($keyword) {
        $keyword = "%{$keyword}%";
        $stmt = $this->conn->prepare("
            SELECT * FROM farmers 
            WHERE full_name LIKE ? 
               OR phone LIKE ? 
               OR location LIKE ?
            ORDER BY full_name
            LIMIT 10
        ");
        $stmt->bind_param("sss", $keyword, $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get farmer with order statistics
     */
    public function getWithStats($id) {
        $stmt = $this->conn->prepare("
            SELECT 
                f.*,
                COUNT(o.id) as total_orders,
                COALESCE(SUM(o.total_amount), 0) as total_value,
                COALESCE(AVG(o.total_amount), 0) as avg_order_value,
                MAX(o.created_at) as last_order_date
            FROM farmers f
            LEFT JOIN orders o ON f.id = o.farmer_id
            WHERE f.id = ?
            GROUP BY f.id
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    /**
     * Get farmer count
     */
    public function getCount() {
        $result = $this->conn->query("SELECT COUNT(*) as count FROM farmers");
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    /**
     * Get recent farmers
     */
    public function getRecent($limit = 5) {
        $stmt = $this->conn->prepare("
            SELECT * FROM farmers 
            ORDER BY created_at DESC 
            LIMIT ?
        ");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Check if phone exists
     */
    public function phoneExists($phone, $excludeId = null) {
        $sql = "SELECT id FROM farmers WHERE phone = ?";
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $phone, $excludeId);
        } else {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $phone);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }
}
?>