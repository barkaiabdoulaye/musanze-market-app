<?php
/**
 * Dashboard Controller
 * Displays statistics and summary information
 */

require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Farmer.php';

class DashboardController extends AuthController {
    private $orderModel;
    private $farmerModel;

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->orderModel = new Order();
        $this->farmerModel = new Farmer();
    }

    /**
     * Display dashboard
     */
    public function index() {
        // Get current user
        $currentUser = $this->getCurrentUser();
        
        // Get statistics
        $todayStats = $this->orderModel->getTodayStats();
        $weekStats = $this->orderModel->getStats('week');
        $monthStats = $this->orderModel->getStats('month');
        $totalStats = $this->orderModel->getStats('all');
        
        // Get recent orders
        $recentOrders = $this->orderModel->getRecent(10);
        
        // Get status distribution
        $statusDistribution = $this->orderModel->getStatusDistribution();
        
        // Get top farmers
        $topFarmers = $this->getTopFarmers(5);
        
        // Get daily totals for chart
        $dailyTotals = $this->orderModel->getDailyTotals(7);
        
        // Get counts
        $totalFarmers = $this->farmerModel->getCount();
        
        // Load view
        require_once __DIR__ . '/../views/dashboard/index.php';
    }

    /**
     * Get top farmers by order value
     */
    private function getTopFarmers($limit = 5) {
        $conn = Database::getInstance()->getConnection();
        
        $stmt = $conn->prepare("
            SELECT 
                f.id,
                f.full_name,
                COUNT(o.id) as total_orders,
                COALESCE(SUM(o.total_amount), 0) as total_value
            FROM farmers f
            LEFT JOIN orders o ON f.id = o.farmer_id
            GROUP BY f.id
            ORDER BY total_value DESC
            LIMIT ?
        ");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * API endpoint for weekly stats (AJAX)
     */
    public function weeklyStats() {
        $this->requireAuth();
        
        $stats = $this->orderModel->getDailyTotals(7);
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Export report as CSV
     */
    public function exportReport() {
        $this->requireAuth();
        
        $period = $_GET['period'] ?? 'week';
        
        switch ($period) {
            case 'today':
                $orders = $this->orderModel->getByDateRange(date('Y-m-d'), date('Y-m-d'));
                break;
            case 'week':
                $start = date('Y-m-d', strtotime('-7 days'));
                $end = date('Y-m-d');
                $orders = $this->orderModel->getByDateRange($start, $end);
                break;
            case 'month':
                $start = date('Y-m-01');
                $end = date('Y-m-t');
                $orders = $this->orderModel->getByDateRange($start, $end);
                break;
            default:
                $orders = $this->orderModel->getAll();
        }
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="orders-report-' . date('Y-m-d') . '.csv"');
        
        // Create CSV
        $output = fopen('php://output', 'w');
        
        // Headers
        fputcsv($output, ['ID', 'Farmer', 'Quantity', 'Unit Price', 'Total', 'Status', 'Date']);
        
        // Data
        foreach ($orders as $order) {
            fputcsv($output, [
                $order['id'],
                $order['farmer_name'],
                $order['quantity'] . ' kg',
                number_format($order['unit_price']) . ' RWF',
                number_format($order['total_amount']) . ' RWF',
                $order['status'],
                $order['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }
}
?>