<?php 
$pageTitle = 'Order Details';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="details-container">
    <div class="details-header">
        <h1>Order #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></h1>
        <div class="header-actions">
            <a href="<?= BASE_URL ?>/index.php?page=orders&action=edit&id=<?= $order['id'] ?>" 
               class="btn btn-warning">
                Edit Order
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=orders&action=receipt&id=<?= $order['id'] ?>" 
               class="btn btn-success" 
               target="_blank">
                Print Receipt
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=orders&action=index" 
               class="btn btn-secondary">
                Back to List
            </a>
        </div>
    </div>
    
    <!-- Status Update -->
    <div class="status-update-card">
        <h3>Update Status</h3>
        <form method="POST" action="<?= BASE_URL ?>/index.php?page=orders&action=updateStatus" 
              class="status-form">
            <input type="hidden" name="id" value="<?= $order['id'] ?>">
            <div class="form-row">
                <select name="status" id="status" class="status-select">
                    <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
    
    <div class="details-grid">
        <!-- Order Information -->
        <div class="detail-card">
            <h3>Order Information</h3>
            <div class="detail-items">
                <div class="detail-item">
                    <span class="detail-label">Quantity:</span>
                    <span class="detail-value"><?= number_format($order['quantity'], 2) ?> kg</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Unit Price:</span>
                    <span class="detail-value"><?= number_format($order['unit_price']) ?> RWF/kg</span>
                </div>
                <div class="detail-item highlight">
                    <span class="detail-label">Total Amount:</span>
                    <span class="detail-value total"><?= number_format($order['total_amount']) ?> RWF</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Pickup Location:</span>
                    <span class="detail-value"><?= htmlspecialchars($order['pickup_location']) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <span class="badge badge-<?= $order['status'] ?>">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Created:</span>
                    <span class="detail-value"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
                </div>
                <?php if (!empty($order['notes'])): ?>
                    <div class="detail-item full-width">
                        <span class="detail-label">Notes:</span>
                        <span class="detail-value"><?= nl2br(htmlspecialchars($order['notes'])) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Farmer Information -->
        <div class="detail-card">
            <h3>Farmer Information</h3>
            <div class="detail-items">
                <div class="detail-item">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value"><?= htmlspecialchars($order['farmer_name']) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value"><?= htmlspecialchars($order['farmer_phone']) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Location:</span>
                    <span class="detail-value"><?= htmlspecialchars($order['location'] ?: 'Not specified') ?></span>
                </div>
                <div class="detail-item">
                    <a href="<?= BASE_URL ?>/index.php?page=farmers&action=view&id=<?= $order['farmer_id'] ?>" 
                       class="btn btn-sm btn-info">
                        View Farmer Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="action-buttons">
        <button onclick="confirmDelete(<?= $order['id'] ?>)" class="btn btn-danger">
            Delete Order
        </button>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        window.location.href = '<?= BASE_URL ?>/index.php?page=orders&action=delete&id=' + id;
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>