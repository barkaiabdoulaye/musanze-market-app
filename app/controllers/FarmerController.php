<?php
/**
 * Farmer Controller
 * Handles farmer registration and management
 */

require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../models/Farmer.php';
require_once __DIR__ . '/../models/Order.php';

class FarmerController extends AuthController {
    private $farmerModel;
    private $orderModel;

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->farmerModel = new Farmer();
        $this->orderModel = new Order();
    }

    /**
     * List all farmers
     */
    public function index() {
        $page = $_GET['p'] ?? 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $farmers = $this->farmerModel->getAll($limit, $offset);
        $totalFarmers = $this->farmerModel->getCount();
        
        require_once __DIR__ . '/../views/farmers/list.php';
    }

    /**
     * Show farmer registration form
     */
    public function register() {
        $errors = [];
        $old = $_POST;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate input
            $errors = $this->validateFarmerData($_POST);
            
            if (empty($errors)) {
                $data = [
                    'full_name' => trim($_POST['full_name']),
                    'phone' => trim($_POST['phone']),
                    'location' => trim($_POST['location'] ?? '')
                ];
                
                $id = $this->farmerModel->create($data);
                
                if ($id) {
                    $_SESSION['success'] = 'Farmer registered successfully';
                    $this->redirect('farmers', ['action' => 'view', 'id' => $id]);
                } else {
                    $errors['general'] = 'Failed to register farmer';
                }
            }
        }
        
        require_once __DIR__ . '/../views/farmers/register.php';
    }

    /**
     * View farmer details
     */
    public function view() {
        $id = $_GET['id'] ?? 0;
        
        $farmer = $this->farmerModel->getWithStats($id);
        
        if (!$farmer) {
            $_SESSION['error'] = 'Farmer not found';
            $this->redirect('farmers');
        }
        
        $orders = $this->orderModel->getByFarmer($id);
        
        require_once __DIR__ . '/../views/farmers/details.php';
    }

    /**
     * Edit farmer
     */
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $farmer = $this->farmerModel->getById($id);
        
        if (!$farmer) {
            $_SESSION['error'] = 'Farmer not found';
            $this->redirect('farmers');
        }
        
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateFarmerData($_POST, $id);
            
            if (empty($errors)) {
                $data = [
                    'full_name' => trim($_POST['full_name']),
                    'phone' => trim($_POST['phone']),
                    'location' => trim($_POST['location'] ?? '')
                ];
                
                if ($this->farmerModel->update($id, $data)) {
                    $_SESSION['success'] = 'Farmer updated successfully';
                    $this->redirect('farmers', ['action' => 'view', 'id' => $id]);
                } else {
                    $errors['general'] = 'Failed to update farmer';
                }
            }
        }
        
        require_once __DIR__ . '/../views/farmers/edit.php';
    }

    /**
     * Delete farmer
     */
    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        if ($this->farmerModel->delete($id)) {
            $_SESSION['success'] = 'Farmer deleted successfully';
        } else {
            $_SESSION['error'] = 'Cannot delete farmer with existing orders';
        }
        
        $this->redirect('farmers');
    }

    /**
     * Search farmers (AJAX)
     */
    public function search() {
        $keyword = $_GET['q'] ?? '';
        
        if (strlen($keyword) < 2) {
            header('Content-Type: application/json');
            echo json_encode([]);
            return;
        }
        
        $results = $this->farmerModel->search($keyword);
        
        header('Content-Type: application/json');
        echo json_encode($results);
    }

    /**
     * Validate farmer data
     */
    private function validateFarmerData($data, $excludeId = null) {
        $errors = [];
        
        // Full name validation
        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Full name is required';
        } elseif (strlen($data['full_name']) < 3) {
            $errors['full_name'] = 'Name must be at least 3 characters';
        }
        
        // Phone validation
        if (empty($data['phone'])) {
            $errors['phone'] = 'Phone number is required';
        } else {
            $phone = preg_replace('/\D/', '', $data['phone']);
            if (strlen($phone) < 10) {
                $errors['phone'] = 'Phone must have at least 10 digits';
            } elseif ($this->farmerModel->phoneExists($data['phone'], $excludeId)) {
                $errors['phone'] = 'Phone number already exists';
            }
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