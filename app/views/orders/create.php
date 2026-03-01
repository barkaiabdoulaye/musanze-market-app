<?php 
$pageTitle = 'Create Order';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="form-container">
    <h1>Create New Order</h1>
    
    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($errors['general']) ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="<?= BASE_URL ?>/index.php?page=orders&action=create" 
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
                        <?= (($selectedFarmer ?? '') == $farmer['id'] || ($old['farmer_id'] ?? '') == $farmer['id']) ? 'selected' : '' ?>>
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
                       value="<?= htmlspecialchars($old['quantity'] ?? '') ?>"
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
                       value="<?= htmlspecialchars($old['unit_price'] ?? '') ?>"
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
            <div class="total-preview" id="totalDisplay">0 RWF</div>
        </div>
        
        <div class="form-group">
            <label for="pickup_location">Pickup Location *</label>
            <input type="text" 
                   id="pickup_location" 
                   name="pickup_location" 
                   value="<?= htmlspecialchars($old['pickup_location'] ?? '') ?>"
                   required
                   placeholder="e.g., Musanze Market, Collection Point..."
                   class="<?= isset($errors['pickup_location']) ? 'is-invalid' : '' ?>">
            <?php if (isset($errors['pickup_location'])): ?>
                <div class="invalid-feedback"><?= $errors['pickup_location'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="notes">Notes (Optional)</label>
            <textarea id="notes" 
                      name="notes" 
                      rows="3"><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create Order</button>
            <a href="<?= BASE_URL ?>/index.php?page=orders&action=index" 
               class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>