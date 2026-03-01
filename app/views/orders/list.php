<?php 
$pageTitle = 'Orders List';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="list-container">
    <div class="list-header">
        <h1>Orders</h1>
        <a href="<?= BASE_URL ?>/index.php?page=orders&action=create" 
           class="btn btn-primary">
            + New Order
        </a>
    </div>
    
    <!-- Filters -->
    <div class="filters">
        <div class="filter-group">
            <label for="statusFilter">Status:</label>
            <select id="statusFilter" class="filter-select">
                <option value="">All</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="dateFilter">Date:</label>
            <input type="date" id="dateFilter" class="filter-date">
        </div>
        
        <button onclick="applyFilters()" class="btn btn-secondary">Apply Filters</button>
        <button onclick="resetFilters()" class="btn btn-text">Reset</button>
        
        <a href="<?= BASE_URL ?>/index.php?page=dashboard&action=exportReport" 
           class="btn btn-success" style="margin-left: auto;">
            Export CSV
        </a>
    </div>
    
    <?php if (empty($orders)): ?>
        <div class="empty-state">
            <p>No orders found</p>
            <a href="<?= BASE_URL ?>/index.php?page=orders&action=create" 
               class="btn btn-primary">
                Create Your First Order
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table" id="ordersTable">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Farmer</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                        <th>Pickup Location</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr data-status="<?= $order['status'] ?>" 
                            data-date="<?= date('Y-m-d', strtotime($order['created_at'])) ?>">
                            <td>#<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></td>
                            <td>
                                <strong><?= htmlspecialchars($order['farmer_name']) ?></strong>
                                <br>
                                <small><?= htmlspecialchars($order['farmer_phone']) ?></small>
                            </td>
                            <td><?= number_format($order['quantity'], 2) ?> kg</td>
                            <td><?= number_format($order['unit_price']) ?> RWF</td>
                            <td><strong><?= number_format($order['total_amount']) ?> RWF</strong></td>
                            <td><?= htmlspecialchars($order['pickup_location']) ?></td>
                            <td>
                                <span class="badge badge-<?= $order['status'] ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                            <td class="actions">
                                <a href="<?= BASE_URL ?>/index.php?page=orders&action=view&id=<?= $order['id'] ?>" 
                                   class="btn btn-sm btn-info">
                                    View
                                </a>
                                <a href="<?= BASE_URL ?>/index.php?page=orders&action=edit&id=<?= $order['id'] ?>" 
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>
                                <a href="<?= BASE_URL ?>/index.php?page=orders&action=receipt&id=<?= $order['id'] ?>" 
                                   class="btn btn-sm btn-success" 
                                   target="_blank">
                                    Receipt
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const date = document.getElementById('dateFilter').value;
    const rows = document.querySelectorAll('#ordersTable tbody tr');
    
    rows.forEach(row => {
        let show = true;
        
        if (status && row.dataset.status !== status) {
            show = false;
        }
        
        if (date && row.dataset.date !== date) {
            show = false;
        }
        
        row.style.display = show ? '' : 'none';
    });
}

function resetFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('dateFilter').value = '';
    
    document.querySelectorAll('#ordersTable tbody tr').forEach(row => {
        row.style.display = '';
    });
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>