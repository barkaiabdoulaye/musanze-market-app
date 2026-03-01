<?php 
$pageTitle = 'Edit Order';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="form-container">
    <h1>Edit Order #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></h1>
    
    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($errors['general']) ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" 
          action="<?= BASE_URL ?>/index.php?page=orders&action=edit&id=<?= $order['id'] ?>" 
          class="form" id="orderForm" novalidate>
        
        <div class="form-group">
            <label for="farmer_id">Farmer *</label>
            <select name="farmer_id" 
                    id="farmer_id" 
                    required
                    class="<?= isset($errors['farmer_id']) ? 'is-invalid' : '' ?>">
                <option value="">Select a farmer</option>
                <?php foreach ($farmers as $farmer): ?>
                    <option value="<?= $farmer['id'] ?>" 
                        <?= (($_POST['farmer_id'] ?? $order['farmer_id']) == $farmer['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($farmer['full_name']) ?> - <?= htmlspecialchars($farmer['phone']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['farmer_id'])): ?>
                <div class="invalid-feedback"><?= $errors['farmer_id'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="quantity">Quantity (kg) *</label>
                <input type="number" 
                       id="quantity" 
                       name="quantity" 
                       value="<?= htmlspecialchars($_POST['quantity'] ?? $order['quantity']) ?>"
                       required
                       min="0.01"
                       step="0.01"
                       class="<?= isset($errors['quantity']) ? 'is-invalid' : '' ?>">
                <?php if (isset($errors['quantity'])): ?>
                    <div class="invalid-feedback"><?= $errors['quantity'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="unit_price">Unit Price (RWF/kg) *</label>
                <input type="number" 
                       id="unit_price" 
                       name="unit_price" 
                       value="<?= htmlspecialchars($_POST['unit_price'] ?? $order['unit_price']) ?>"
                       required
                       min="1"
                       step="1"
                       class="<?= isset($errors['unit_price']) ? 'is-invalid' : '' ?>">
                <?php if (isset($errors['unit_price'])): ?>
                    <div class="invalid-feedback"><?= $errors['unit_price'] ?></div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="form-group">
            <label for="total_display">Total Amount</label>
            <div class="total-preview" id="totalDisplay">
                <?= number_format(
                    ($_POST['quantity'] ?? $order['quantity']) * 
                    ($_POST['unit_price'] ?? $order['unit_price'])
                ) ?> RWF
            </div>
        </div>
        
        <div class="form-group">
            <label for="pickup_location">Pickup Location *</label>
            <input type="text" 
                   id="pickup_location" 
                   name="pickup_location" 
                   value="<?= htmlspecialchars($_POST['pickup_location'] ?? $order['pickup_location']) ?>"
                   required
                   class="<?= isset($errors['pickup_location']) ? 'is-invalid' : '' ?>">
            <?php if (isset($errors['pickup_location'])): ?>
                <div class="invalid-feedback"><?= $errors['pickup_location'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea id="notes" 
                      name="notes" 
                      rows="3"><?= htmlspecialchars($_POST['notes'] ?? $order['notes'] ?? '') ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Order</button>
            <a href="<?= BASE_URL ?>/index.php?page=orders&action=view&id=<?= $order['id'] ?>" 
               class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>