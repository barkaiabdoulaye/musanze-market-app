<?php 
$pageTitle = 'Register Farmer';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="form-container">
    <h1>Register New Farmer</h1>
    
    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($errors['general']) ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="<?= BASE_URL ?>/index.php?page=farmers&action=register" 
          class="form" novalidate>
        
        <div class="form-group">
            <label for="full_name">Full Name *</label>
            <input type="text" 
                   id="full_name" 
                   name="full_name" 
                   value="<?= htmlspecialchars($old['full_name'] ?? '') ?>"
                   required
                   class="<?= isset($errors['full_name']) ? 'is-invalid' : '' ?>">
            <?php if (isset($errors['full_name'])): ?>
                <div class="invalid-feedback"><?= $errors['full_name'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="phone">Phone Number *</label>
            <input type="tel" 
                   id="phone" 
                   name="phone" 
                   value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                   required
                   pattern="[0-9]{10,}"
                   title="Please enter at least 10 digits"
                   class="<?= isset($errors['phone']) ? 'is-invalid' : '' ?>">
            <?php if (isset($errors['phone'])): ?>
                <div class="invalid-feedback"><?= $errors['phone'] ?></div>
            <?php endif; ?>
            <small>Format: 10 digits minimum (e.g., 0788123456)</small>
        </div>
        
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" 
                   id="location" 
                   name="location" 
                   value="<?= htmlspecialchars($old['location'] ?? '') ?>"
                   placeholder="e.g., Musanze, Ruhengeri...">
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Register Farmer</button>
            <a href="<?= BASE_URL ?>/index.php?page=farmers&action=index" 
               class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>