<?php 
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="dashboard">
    <h1>Dashboard</h1>
    
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-content">
                <h3>Today's Orders</h3>
                <p class="stat-number"><?= $todayStats['total_orders'] ?? 0 ?></p>
                <p class="stat-label"><?= number_format($todayStats['total_value'] ?? 0) ?> RWF</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">📈</div>
            <div class="stat-content">
                <h3>This Week</h3>
                <p class="stat-number"><?= $weekStats['total_orders'] ?? 0 ?></p>
                <p class="stat-label"><?= number_format($weekStats['total_value'] ?? 0) ?> RWF</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-content">
                <h3>This Month</h3>
                <p class="stat-number"><?= $monthStats['total_orders'] ?? 0 ?></p>
                <p class="stat-label"><?= number_format($monthStats['total_value'] ?? 0) ?> RWF</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <h3>Total Farmers</h3>
                <p class="stat-number"><?= $totalFarmers ?? 0 ?></p>
                <p class="stat-label">Registered farmers</p>
            </div>
        </div>
    </div>
    
    <!-- Charts and Lists -->
    <div class="dashboard-grid">
        <!-- Status Distribution -->
        <div class="dashboard-card">
            <h2>Order Status</h2>
            <div class="status-distribution">
                <?php foreach ($statusDistribution as $stat): ?>
                    <div class="status-item status-<?= $stat['status'] ?>">
                        <span class="status-label"><?= ucfirst($stat['status']) ?></span>
                        <span class="status-count"><?= $stat['count'] ?> orders</span>
                        <span class="status-value"><?= number_format($stat['total']) ?> RWF</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Top Farmers -->
        <div class="dashboard-card">
            <h2>Top Farmers</h2>
            <div class="top-farmers">
                <?php foreach ($topFarmers as $index => $farmer): ?>
                    <div class="farmer-rank">
                        <span class="rank">#<?= $index + 1 ?></span>
                        <span class="name"><?= htmlspecialchars($farmer['full_name']) ?></span>
                        <span class="value"><?= number_format($farmer['total_value']) ?> RWF</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Daily Totals Chart -->
        <div class="dashboard-card full-width">
            <h2>Last 7 Days Activity</h2>
            <div class="chart-container">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="recent-orders">
        <div class="section-header">
            <h2>Recent Orders</h2>
            <a href="<?= BASE_URL ?>/index.php?page=orders&action=index" class="btn btn-secondary">
                View All
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Farmer</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['farmer_name']) ?></td>
                            <td><?= number_format($order['quantity'], 2) ?> kg</td>
                            <td><?= number_format($order['total_amount']) ?> RWF</td>
                            <td>
                                <span class="badge badge-<?= $order['status'] ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
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
    </div>
</div>

<!-- Chart.js for visualizations -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Daily chart data from PHP
    const dailyData = <?= json_encode($dailyTotals) ?>;
    
    const ctx = document.getElementById('dailyChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dailyData.map(d => d.date),
            datasets: [{
                label: 'Order Value (RWF)',
                data: dailyData.map(d => d.total),
                borderColor: '#2E7D32',
                backgroundColor: 'rgba(46, 125, 50, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>