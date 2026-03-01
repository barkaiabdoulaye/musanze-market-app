<?php 
$pageTitle = 'Farmers List';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="list-container">
    <div class="list-header">
        <h1>Farmers List</h1>
        <a href="<?= BASE_URL ?>/index.php?page=farmers&action=register" 
           class="btn btn-primary">
            + Register New Farmer
        </a>
    </div>
    
    <!-- Search Bar -->
    <div class="search-section">
        <div class="search-bar">
            <input type="text" 
                   id="searchFarmers" 
                   placeholder="Search farmers by name, phone or location..."
                   class="search-input">
            <div id="searchResults" class="search-results"></div>
        </div>
    </div>
    
    <?php if (empty($farmers)): ?>
        <div class="empty-state">
            <p>No farmers registered yet</p>
            <a href="<?= BASE_URL ?>/index.php?page=farmers&action=register" 
               class="btn btn-primary">
                Register Your First Farmer
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Location</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($farmers as $farmer): ?>
                        <tr>
                            <td>#<?= $farmer['id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($farmer['full_name']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($farmer['phone']) ?></td>
                            <td><?= htmlspecialchars($farmer['location'] ?: '-') ?></td>
                            <td><?= date('d/m/Y', strtotime($farmer['created_at'])) ?></td>
                            <td class="actions">
                                <a href="<?= BASE_URL ?>/index.php?page=farmers&action=view&id=<?= $farmer['id'] ?>" 
                                   class="btn btn-sm btn-info">
                                    View
                                </a>
                                <a href="<?= BASE_URL ?>/index.php?page=farmers&action=edit&id=<?= $farmer['id'] ?>" 
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>
                                <a href="#" 
                                   onclick="confirmDelete(<?= $farmer['id'] ?>, '<?= htmlspecialchars($farmer['full_name']) ?>')" 
                                   class="btn btn-sm btn-danger">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=farmers&action=index&p=<?= $page - 1 ?>" class="btn btn-sm">Previous</a>
            <?php endif; ?>
            
            <span class="page-info">Page <?= $page ?></span>
            
            <?php if (count($farmers) == 20): ?>
                <a href="?page=farmers&action=index&p=<?= $page + 1 ?>" class="btn btn-sm">Next</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function confirmDelete(id, name) {
    if (confirm(`Are you sure you want to delete farmer "${name}"?`)) {
        window.location.href = '<?= BASE_URL ?>/index.php?page=farmers&action=delete&id=' + id;
    }
}

// Live search
const searchInput = document.getElementById('searchFarmers');
const searchResults = document.getElementById('searchResults');

searchInput.addEventListener('input', function() {
    const query = this.value.trim();
    
    if (query.length < 2) {
        searchResults.innerHTML = '';
        return;
    }
    
    fetch('<?= BASE_URL ?>/index.php?page=farmers&action=search&q=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                searchResults.innerHTML = '<div class="search-result-item">No results found</div>';
                return;
            }
            
            let html = '';
            data.forEach(farmer => {
                html += `
                    <div class="search-result-item">
                        <a href="<?= BASE_URL ?>/index.php?page=farmers&action=view&id=${farmer.id}">
                            <strong>${farmer.full_name}</strong>
                            <small>${farmer.phone} - ${farmer.location || 'No location'}</small>
                        </a>
                    </div>
                `;
            });
            searchResults.innerHTML = html;
        });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>