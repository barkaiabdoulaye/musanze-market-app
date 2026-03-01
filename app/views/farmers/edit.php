<?php 
$pageTitle = 'Edit Farmer';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="form-container">
    <h1>Edit Farmer</h1>
    
    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($errors['general']) ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" 
          action="<?= BASE_URL ?>/index.php?page=farmers&action=edit&id=<?= $farmer['id'] ?>" 
          class="form" novalidate>
        
        <div class="form-group">
            <label for="full_name">Full Name *</label>
            <input type="text" 
                   id="full_name" 
                   name="full_name" 
                   value="<?= htmlspecialchars($_POST['full_name'] ?? $farmer['full_name']) ?>"
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
                   value="<?= htmlspecialchars($_POST['phone'] ?? $farmer['phone']) ?>"
                   required
                   class="<?= isset($errors['phone']) ? 'is-invalid' : '' ?>">
            <?php if (isset($errors['phone'])): ?>
                <div class="invalid-feedback"><?= $errors['phone'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" 
                   id="location" 
                   name="location" 
                   value="<?= htmlspecialchars($_POST['location'] ?? $farmer['location']) ?>"
                   placeholder="e.g., Musanze, Ruhengeri...">
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Farmer</button>
            <a href="<?= BASE_URL ?>/index.php?page=farmers&action=view&id=<?= $farmer['id'] ?>" 
               class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>