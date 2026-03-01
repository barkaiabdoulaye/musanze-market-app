<?php
/**
 * Order Model
 * Handles order creation and management
 */

require_once __DIR__ . '/../config/database.php';

class Order {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Get all orders with farmer details
     */
    public function getAll($limit = null, $offset = 0) {
        $sql = "
            SELECT o.*, 
                   f.full_name as farmer_name, 
                   f.phone as farmer_phone 
            FROM orders o 
            JOIN farmers f ON o.farmer_id = f.id 
            ORDER BY o.created_at DESC
        ";
        
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
     * Get order by ID with farmer details
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("
            SELECT o.*, 
                   f.full_name as farmer_name, 
                   f.phone as farmer_phone,
                   f.location
            FROM orders o 
            JOIN farmers f ON o.farmer_id = f.id 
            WHERE o.id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    /**
     * Create new order
     */
    public function create($data) {
        // Auto-calculate total
        $totalAmount = $data['quantity'] * $data['unit_price'];
        
        $stmt = $this->conn->prepare("
            INSERT INTO orders (
                farmer_id, quantity, unit_price, 
                total_amount, pickup_location, status, notes
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $status = 'pending';
        $notes = $data['notes'] ?? '';
        
        $stmt->bind_param(
            "idddsss", 
            $data['farmer_id'], 
            $data['quantity'], 
            $data['unit_price'], 
            $totalAmount,
            $data['pickup_location'],
            $status,
            $notes
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        
        return false;
    }

    /**
     * Update order
     */
    public function update($id, $data) {
        // Recalculate total
        $totalAmount = $data['quantity'] * $data['unit_price'];
        
        $stmt = $this->conn->prepare("
            UPDATE orders 
            SET farmer_id = ?, quantity = ?, unit_price = ?, 
                total_amount = ?, pickup_location = ?, 
                status = ?, notes = ?
            WHERE id = ?
        ");
        
        $notes = $data['notes'] ?? '';
        
        $stmt->bind_param(
            "idddsssi", 
            $data['farmer_id'], 
            $data['quantity'], 
            $data['unit_price'], 
            $totalAmount,
            $data['pickup_location'],
            $data['status'],
            $notes,
            $id
        );
        
        return $stmt->execute();
    }

    /**
     * Update order status only
     */
    public function updateStatus($id, $status) {
        $validStatuses = ['pending', 'completed', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            return false;
        }
        
        $stmt = $this->conn->prepare("
            UPDATE orders SET status = ? WHERE id = ?
        ");
        $stmt->bind_param("si", $status, $id);
        
        return $stmt->execute();
    }

    /**
     * Delete order
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Get today's statistics
     */
    public function getTodayStats() {
        $today = date('Y-m-d');
        
        $stmt = $this->conn->prepare("
            SELECT 
                COUNT(*) as total_orders, 
                COALESCE(SUM(total_amount), 0) as total_value,
                COUNT(DISTINCT farmer_id) as unique_farmers,
                COALESCE(AVG(total_amount), 0) as average_value
            FROM orders 
            WHERE DATE(created_at) = ?
        ");
        $stmt->bind_param("s", $today);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    /**
     * Get recent orders
     */
    public function getRecent($limit = 5) {
        $stmt = $this->conn->prepare("
            SELECT o.*, f.full_name as farmer_name
            FROM orders o 
            JOIN farmers f ON o.farmer_id = f.id 
            ORDER BY o.created_at DESC 
            LIMIT ?
        ");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get orders by farmer
     */
    public function getByFarmer($farmerId, $limit = null) {
        $sql = "
            SELECT * FROM orders 
            WHERE farmer_id = ? 
            ORDER BY created_at DESC
        ";
        
        if ($limit !== null) {
            $sql .= " LIMIT ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $farmerId, $limit);
        } else {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $farmerId);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get orders by date range
     */
    public function getByDateRange($startDate, $endDate) {
        $stmt = $this->conn->prepare("
            SELECT o.*, f.full_name as farmer_name
            FROM orders o
            JOIN farmers f ON o.farmer_id = f.id
            WHERE DATE(o.created_at) BETWEEN ? AND ?
            ORDER BY o.created_at DESC
        ");
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get order statistics
     */
    public function getStats($period = 'all') {
        switch ($period) {
            case 'today':
                $condition = "DATE(created_at) = CURDATE()";
                break;
            case 'week':
                $condition = "YEARWEEK(created_at) = YEARWEEK(CURDATE())";
                break;
            case 'month':
                $condition = "MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
                break;
            default:
                $condition = "1=1";
        }
        
        $sql = "
            SELECT 
                COUNT(*) as total_orders,
                COALESCE(SUM(total_amount), 0) as total_value,
                COUNT(DISTINCT farmer_id) as total_farmers,
                COALESCE(AVG(total_amount), 0) as avg_order_value,
                MIN(total_amount) as min_order,
                MAX(total_amount) as max_order
            FROM orders
            WHERE {$condition}
        ";
        
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    /**
     * Get status distribution
     */
    public function getStatusDistribution() {
        $result = $this->conn->query("
            SELECT 
                status,
                COUNT(*) as count,
                COALESCE(SUM(total_amount), 0) as total
            FROM orders
            GROUP BY status
        ");
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get daily totals for chart
     */
    public function getDailyTotals($days = 7) {
        $stmt = $this->conn->prepare("
            SELECT 
                DATE(created_at) as date,
                COUNT(*) as order_count,
                COALESCE(SUM(total_amount), 0) as total
            FROM orders
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            GROUP BY DATE(created_at)
            ORDER BY date DESC
        ");
        $stmt->bind_param("i", $days);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Generate unique order reference
     */
    public function generateReference() {
        $year = date('Y');
        $month = date('m');
        
        // Get last order number for this month
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as count 
            FROM orders 
            WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?
        ");
        $stmt->bind_param("ss", $year, $month);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $number = str_pad($row['count'] + 1, 4, '0', STR_PAD_LEFT);
        
        return "ORD-{$year}{$month}-{$number}";
    }
}
?>