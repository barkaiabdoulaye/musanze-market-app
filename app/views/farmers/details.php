<?php 
$pageTitle = 'Farmer Details';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="details-container">
    <div class="details-header">
        <h1>Farmer Details</h1>
        <div class="header-actions">
            <a href="<?= BASE_URL ?>/index.php?page=farmers&action=edit&id=<?= $farmer['id'] ?>" 
               class="btn btn-warning">
                Edit
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=orders&action=create&farmer_id=<?= $farmer['id'] ?>" 
               class="btn btn-success">
                New Order
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=farmers&action=index" 
               class="btn btn-secondary">
                Back to List
            </a>
        </div>
    </div>
    
    <div class="details-grid">
        <!-- Personal Information -->
        <div class="detail-card">
            <h3>Personal Information</h3>
            <div class="detail-items">
                <div class="detail-item">
                    <span class="detail-label">Full Name:</span>
                    <span class="detail-value"><?= htmlspecialchars($farmer['full_name']) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value"><?= htmlspecialchars($farmer['phone']) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Location:</span>
                    <span class="detail-value"><?= htmlspecialchars($farmer['location'] ?: 'Not specified') ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Registered On:</span>
                    <span class="detail-value"><?= date('d/m/Y', strtotime($farmer['created_at'])) ?></span>
                </div>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="detail-card">
            <h3>Statistics</h3>
            <div class="stats-mini-grid">
                <div class="stat-mini">
                    <span class="stat-mini-label">Total Orders</span>
                    <span class="stat-mini-value"><?= $farmer['total_orders'] ?? 0 ?></span>
                </div>
                <div class="stat-mini">
                    <span class="stat-mini-label">Total Value</span>
                    <span class="stat-mini-value"><?= number_format($farmer['total_value'] ?? 0) ?> RWF</span>
                </div>
                <div class="stat-mini">
                    <span class="stat-mini-label">Average Order</span>
                    <span class="stat-mini-value"><?= number_format($farmer['avg_order_value'] ?? 0) ?> RWF</span>
                </div>
                <div class="stat-mini">
                    <span class="stat-mini-label">Last Order</span>
                    <span class="stat-mini-value">
                        <?= $farmer['last_order_date'] ? date('d/m/Y', strtotime($farmer['last_order_date'])) : 'Never' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order History -->
    <div class="order-history">
        <h2>Order History</h2>
        
        <?php if (empty($orders)): ?>
            <div class="empty-state">
                <p>No orders found for this farmer</p>
                <a href="<?= BASE_URL ?>/index.php?page=orders&action=create&farmer_id=<?= $farmer['id'] ?>" 
                   class="btn btn-primary">
                    Create First Order
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Pickup Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?= $order['id'] ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                <td><?= number_format($order['quantity'], 2) ?> kg</td>
                                <td><?= number_format($order['unit_price']) ?> RWF</td>
                                <td><strong><?= number_format($order['total_amount']) ?> RWF</strong></td>
                                <td>
                                    <span class="badge badge-<?= $order['status'] ?>">
                                        <?= ucfirst($order['status']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($order['pickup_location']) ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>/index.php?page=orders&action=view&id=<?= $order['id'] ?>" 
                                       class="btn btn-sm btn-info">
                                        View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>