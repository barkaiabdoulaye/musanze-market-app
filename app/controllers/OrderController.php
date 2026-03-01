<?php
/**
 * Order Controller
 * Handles order creation, management, and receipts
 */

require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Farmer.php';

class OrderController extends AuthController {
    private $orderModel;
    private $farmerModel;

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->orderModel = new Order();
        $this->farmerModel = new Farmer();
    }

    /**
     * List all orders
     */
    public function index() {
        $page = $_GET['p'] ?? 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $orders = $this->orderModel->getAll($limit, $offset);
        
        require_once __DIR__ . '/../views/orders/list.php';
    }

    /**
     * Show order creation form
     */
    public function create() {
        $farmers = $this->farmerModel->getAll();
        $errors = [];
        $old = $_POST;
        
        // Pre-select farmer if specified
        $selectedFarmer = $_GET['farmer_id'] ?? null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateOrderData($_POST);
            
            if (empty($errors)) {
                $data = [
                    'farmer_id' => $_POST['farmer_id'],
                    'quantity' => $_POST['quantity'],
                    'unit_price' => $_POST['unit_price'],
                    'pickup_location' => $_POST['pickup_location'],
                    'notes' => $_POST['notes'] ?? ''
                ];
                
                $id = $this->orderModel->create($data);
                
                if ($id) {
                    $_SESSION['success'] = 'Order created successfully';
                    $this->redirect('orders', ['action' => 'view', 'id' => $id]);
                } else {
                    $errors['general'] = 'Failed to create order';
                }
            }
        }
        
        require_once __DIR__ . '/../views/orders/create.php';
    }

    /**
     * View order details
     */
    public function view() {
        $id = $_GET['id'] ?? 0;
        
        $order = $this->orderModel->getById($id);
        
        if (!$order) {
            $_SESSION['error'] = 'Order not found';
            $this->redirect('orders');
        }
        
        require_once __DIR__ . '/../views/orders/details.php';
    }

    /**
     * Edit order
     */
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $order = $this->orderModel->getById($id);
        
        if (!$order) {
            $_SESSION['error'] = 'Order not found';
            $this->redirect('orders');
        }
        
        $farmers = $this->farmerModel->getAll();
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateOrderData($_POST);
            
            if (empty($errors)) {
                $data = [
                    'farmer_id' => $_POST['farmer_id'],
                    'quantity' => $_POST['quantity'],
                    'unit_price' => $_POST['unit_price'],
                    'pickup_location' => $_POST['pickup_location'],
                    'status' => $_POST['status'],
                    'notes' => $_POST['notes'] ?? ''
                ];
                
                if ($this->orderModel->update($id, $data)) {
                    $_SESSION['success'] = 'Order updated successfully';
                    $this->redirect('orders', ['action' => 'view', 'id' => $id]);
                } else {
                    $errors['general'] = 'Failed to update order';
                }
            }
        }
        
        require_once __DIR__ . '/../views/orders/edit.php';
    }

    /**
     * Update order status (AJAX)
     */
    public function updateStatus() {
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? '';
        
        if ($this->orderModel->updateStatus($id, $status)) {
            $_SESSION['success'] = 'Status updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update status';
        }
        
        $this->redirect('orders', ['action' => 'view', 'id' => $id]);
    }

    /**
     * Delete order
     */
    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        if ($this->orderModel->delete($id)) {
            $_SESSION['success'] = 'Order deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete order';
        }
        
        $this->redirect('orders');
    }

    /**
     * Generate receipt
     */
    public function receipt() {
        $id = $_GET['id'] ?? 0;
        
        $order = $this->orderModel->getById($id);
        
        if (!$order) {
            $_SESSION['error'] = 'Order not found';
            $this->redirect('orders');
        }
        
        require_once __DIR__ . '/../views/orders/receipt.php';
    }

    /**
     * Validate order data
     */
    private function validateOrderData($data) {
        $errors = [];
        
        // Farmer validation
        if (empty($data['farmer_id'])) {
            $errors['farmer_id'] = 'Farmer is required';
        }
        
        // Quantity validation
        if (empty($data['quantity'])) {
            $errors['quantity'] = 'Quantity is required';
        } elseif (!is_numeric($data['quantity']) || $data['quantity'] <= 0) {
            $errors['quantity'] = 'Quantity must be a positive number';
        }
        
        // Unit price validation
        if (empty($data['unit_price'])) {
            $errors['unit_price'] = 'Unit price is required';
        } elseif (!is_numeric($data['unit_price']) || $data['unit_price'] <= 0) {
            $errors['unit_price'] = 'Unit price must be a positive number';
        }
        
        // Pickup location validation
        if (empty($data['pickup_location'])) {
            $errors['pickup_location'] = 'Pickup location is required';
        }
        
        return $errors;
    }

    /**
     * Redirect helper
     */
    private function redirect($page, $params = []) {
        $url = BASE_URL . "/index.php?page={$page}";
        
        if (!empty($params)) {
            $url .= '&' . http_build_query($params);
        }
        
        header("Location: {$url}");
        exit;
    }
}
?>