<?php
/**
 * Dashboard - Business overview
 * Shows stats, recent orders, and low stock alerts
 */

$totalProducts = db_query("SELECT COUNT(*) AS total FROM products WHERE deleted_at IS NULL")->fetch_assoc()['total'];
$totalOrders = db_query("SELECT COUNT(*) AS total FROM orders WHERE deleted_at IS NULL")->fetch_assoc()['total'];
$totalUsers = db_query("SELECT COUNT(*) AS total FROM users WHERE deleted_at IS NULL")->fetch_assoc()['total'];

$today = date('Y-m-d');
$todaySales = db_query("SELECT COALESCE(SUM(total_price),0) AS total FROM orders WHERE DATE(order_date)=? AND deleted_at IS NULL", 's', [$today])->fetch_assoc()['total'];
$totalRevenue = db_query("SELECT COALESCE(SUM(total_price),0) AS total FROM orders WHERE deleted_at IS NULL")->fetch_assoc()['total'];
$todayOrders = db_query("SELECT COUNT(*) AS cnt FROM orders WHERE DATE(order_date)=? AND deleted_at IS NULL", 's', [$today])->fetch_assoc()['cnt'];

$lowStockCount = db_query("SELECT COUNT(*) AS cnt FROM products WHERE deleted_at IS NULL AND status='Active' AND stock_quantity <= low_stock_threshold")->fetch_assoc()['cnt'];

$recentOrders = db_query("SELECT * FROM orders WHERE deleted_at IS NULL ORDER BY order_date DESC LIMIT 8")->fetch_all(MYSQLI_ASSOC);

$topProducts = db_query("SELECT oi.product_name, SUM(oi.quantity) AS sold, SUM(oi.total_price) AS revenue FROM order_items oi JOIN orders o ON o.order_id=oi.order_id WHERE o.deleted_at IS NULL GROUP BY oi.product_name ORDER BY sold DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

$lowStockItems = db_query("SELECT product_name, stock_quantity, low_stock_threshold FROM products WHERE deleted_at IS NULL AND status='Active' AND stock_quantity <= low_stock_threshold ORDER BY stock_quantity ASC LIMIT 8")->fetch_all(MYSQLI_ASSOC);
?>

<section class="section-header">
    <div>
        <p class="eyebrow"><i class="fa-solid fa-gauge-high"></i> Dashboard</p>
        <h1>Welcome, <?= e($user['full_name'] ?? 'User') ?></h1>
    </div>
</section>

<!-- Stats Cards -->
<div class="stats-grid stats-grid-4">
    <article class="stat-card stat-primary">
        <div class="stat-icon"><i class="fa-solid fa-peso-sign"></i></div>
        <div class="stat-info">
            <p>Today's Sales</p>
            <h2><?= format_currency($todaySales) ?></h2><small><?= $todayOrders ?> order(s) today</small>
        </div>
    </article>
    <article class="stat-card stat-success">
        <div class="stat-icon"><i class="fa-solid fa-chart-line"></i></div>
        <div class="stat-info">
            <p>Total Revenue</p>
            <h2><?= format_currency($totalRevenue) ?></h2>
        </div>
    </article>
    <article class="stat-card stat-info">
        <div class="stat-icon"><i class="fa-solid fa-box-open"></i></div>
        <div class="stat-info">
            <p>Products</p>
            <h2><?= e($totalProducts) ?></h2>
        </div>
    </article>
    <article class="stat-card stat-<?= $lowStockCount > 0 ? 'danger' : 'warning' ?>">
        <div class="stat-icon"><i
                class="fa-solid fa-<?= $lowStockCount > 0 ? 'triangle-exclamation' : 'receipt' ?>"></i></div>
        <div class="stat-info">
            <p><?= $lowStockCount > 0 ? 'Low Stock Alerts' : 'Total Orders' ?></p>
            <h2><?= $lowStockCount > 0 ? $lowStockCount : e($totalOrders) ?></h2>
        </div>
    </article>
</div>

<!-- Recent Orders & Top Products -->
<div class="dashboard-grid grid-2-1">
    <section class="panel">
        <div class="panel-header">
            <h3><i class="fa-solid fa-clock-rotate-left"></i> Recent Orders</h3><a href="index.php?page=orders"
                class="text-link">View all</a>
        </div>
        <div class="table-scroll">
            <table class="table-basic">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentOrders)): ?>
                        <tr>
                            <td colspan="4" class="empty-state">No orders yet</td>
                        </tr>
                    <?php else:
                        foreach ($recentOrders as $o): ?>
                            <tr>
                                <td><a href="index.php?page=orders-edit&id=<?= e($o['order_id']) ?>"
                                        class="text-link"><?= e($o['order_number']) ?></a></td>
                                <td><small><?= e($o['payment_method']) ?></small></td>
                                <td><?= format_currency($o['total_price']) ?></td>
                                <td><?= format_date($o['order_date']) ?></td>
                            </tr>
                        <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </section>
    <section class="panel">
        <div class="panel-header">
            <h3><i class="fa-solid fa-fire"></i> Top Products</h3>
        </div>
        <ul class="compact-list">
            <?php if (empty($topProducts)): ?>
                <li class="text-muted">No sales data yet</li>
            <?php else:
                foreach ($topProducts as $i => $tp): ?>
                    <li><span class="rank">#<?= $i + 1 ?></span>
                        <div><strong><?= e($tp['product_name']) ?></strong><small><?= e($tp['sold']) ?> sold ·
                                <?= format_currency($tp['revenue']) ?></small></div>
                    </li>
                <?php endforeach; endif; ?>
        </ul>
    </section>
</div>

<!-- Low Stock Alerts -->
<?php if (!empty($lowStockItems)): ?>
    <section class="panel panel-warning">
        <div class="panel-header">
            <h3><i class="fa-solid fa-triangle-exclamation"></i> Low Stock Items</h3><a href="index.php?page=products"
                class="text-link">Manage →</a>
        </div>
        <div class="table-scroll">
            <table class="table-basic">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Stock</th>
                        <th>Threshold</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lowStockItems as $ls):
                        $qty = (int) $ls['stock_quantity'];
                        $cls = $qty === 0 ? 'badge-danger' : 'badge-warning';
                        $txt = $qty === 0 ? 'Out of Stock' : 'Low Stock';
                        ?>
                        <tr>
                            <td><strong><?= e($ls['product_name']) ?></strong></td>
                            <td><span class="badge <?= $cls ?>"><?= $qty ?></span></td>
                            <td><?= e($ls['low_stock_threshold']) ?></td>
                            <td><span class="badge <?= $cls ?>"><?= $txt ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endif; ?>