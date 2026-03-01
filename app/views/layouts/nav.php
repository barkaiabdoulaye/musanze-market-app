<nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="container">
        <div class="nav-brand">
            <a href="<?= BASE_URL ?>/index.php?page=dashboard">
                <h1>Musanze Market</h1>
            </a>
        </div>
        
        <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        
        <div class="nav-menu" id="navMenu">
            <ul class="nav-links">
                <li>
                    <a href="<?= BASE_URL ?>/index.php?page=dashboard" 
                       class="<?= ($_GET['page'] ?? '') === 'dashboard' ? 'active' : '' ?>">
                        Dashboard
                    </a>
                </li>
                
                <li class="nav-dropdown">
                    <a href="#" class="dropdown-toggle">
                        Orders <span class="caret">▼</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?= BASE_URL ?>/index.php?page=orders&action=create">
                                New Order
                            </a>
                        </li>
                        <li>
                            <a href="<?= BASE_URL ?>/index.php?page=orders&action=index">
                                All Orders
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="nav-dropdown">
                    <a href="#" class="dropdown-toggle">
                        Farmers <span class="caret">▼</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?= BASE_URL ?>/index.php?page=farmers&action=register">
                                Register Farmer
                            </a>
                        </li>
                        <li>
                            <a href="<?= BASE_URL ?>/index.php?page=farmers&action=index">
                                All Farmers
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="nav-user">
                    <span class="username">
                        Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?>
                    </span>
                    <a href="<?= BASE_URL ?>/index.php?page=login&action=logout" 
                       class="btn btn-logout">
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>