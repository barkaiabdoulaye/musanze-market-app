<?php $pageTitle = 'Login'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h2>Musanze Market</h2>
            <p>Order Management System</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?= BASE_URL ?>/index.php?page=login" 
              class="login-form" novalidate>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                       required 
                       autofocus
                       placeholder="Enter your username">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required
                       placeholder="Enter your password">
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                Sign In
            </button>
        </form>
        
        <div class="login-footer">
            <p class="small">Default: admin / 123</p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>