<?php
/**
 * Dashboard - Real-time business overview
 * 
 * Shows: Sales stats, low stock alerts, recent orders,
 * top products, and inventory health indicators.
 */

// Count totals
$totalProducts = db_query("SELECT COUNT(*) AS total FROM products WHERE deleted_at IS NULL")->fetch_assoc()['total'];
$totalOrders = db_query("SELECT COUNT(*) AS total FROM orders WHERE deleted_at IS NULL")->fetch_assoc()['total'];

// Today's sales
$today = date('Y-m-d');
$todaySales = db_query("SELECT COALESCE(SUM(total_price),0) AS total FROM orders WHERE DATE(order_date)=? AND deleted_at IS NULL", 's', [$today])->fetch_assoc()['total'];

// Total revenue
$totalRevenue = db_query("SELECT COALESCE(SUM(total_price),0) AS total FROM orders WHERE deleted_at IS NULL")->fetch_assoc()['total'];

// Low stock & out of stock counts
$lowStockCount = db_query("SELECT COUNT(*) AS cnt FROM products WHERE deleted_at IS NULL AND status='Active' AND stock_quantity > 0 AND stock_quantity <= low_stock_threshold")->fetch_assoc()['cnt'];
$outOfStockCount = db_query("SELECT COUNT(*) AS cnt FROM products WHERE deleted_at IS NULL AND status='Active' AND stock_quantity = 0")->fetch_assoc()['cnt'];
$alertCount = (int)$lowStockCount + (int)$outOfStockCount;

// Low stock products for alert section (up to 8)
$lowStockItems = db_query("SELECT product_id, product_name, stock_quantity, low_stock_threshold FROM products WHERE deleted_at IS NULL AND status='Active' AND stock_quantity <= low_stock_threshold ORDER BY stock_quantity ASC LIMIT 8")->fetch_all(MYSQLI_ASSOC);

// Recent 10 orders
$recentOrders = db_query("SELECT * FROM orders WHERE deleted_at IS NULL ORDER BY order_date DESC LIMIT 10")->fetch_all(MYSQLI_ASSOC);

// Top 5 selling products
$topProducts = db_query("
    SELECT oi.product_name, SUM(oi.quantity) AS sold, SUM(oi.total_price) AS revenue 
    FROM order_items oi JOIN orders o ON o.order_id = oi.order_id 
    WHERE o.deleted_at IS NULL GROUP BY oi.product_name ORDER BY sold DESC LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

// Recent 5 inventory movements
$recentMovements = db_query("SELECT im.*, p.product_name FROM inventory_movements im LEFT JOIN products p ON p.product_id=im.product_id ORDER BY im.created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

// Today's orders count
$todayOrders = db_query("SELECT COUNT(*) AS cnt FROM orders WHERE DATE(order_date)=? AND deleted_at IS NULL", 's', [$today])->fetch_assoc()['cnt'];
?>

<!-- Page Header -->
<section class="section-header">
    <div>
        <p class="eyebrow"><i class="fa-solid fa-gauge-high"></i> Dashboard</p>
        <h1>Welcome, <?= e($user['full_name'] ?? 'User') ?></h1>
    </div>
    <div class="header-actions">
        <a href="index.php?page=order-form" class="button button-primary"><i class="fa-solid fa-plus"></i> New Order</a>
    </div>
</section>

<!-- ========== LOW STOCK ALERT BANNER ========== -->
<?php if ($alertCount > 0): ?>
<div class="alert alert-warning" style="display:flex; align-items:center; gap:12px; margin-bottom:20px;">
    <i class="fa-solid fa-triangle-exclamation" style="font-size:1.3em;"></i>
    <div>
        <strong>Inventory Alert:</strong>
        <?php if ($outOfStockCount > 0): ?><span><?= $outOfStockCount ?> product(s) out of stock.</span><?php endif; ?>
        <?php if ($lowStockCount > 0): ?><span><?= $lowStockCount ?> product(s) running low.</span><?php endif; ?>
        <a href="index.php?page=inventory" class="text-link" style="margin-left:8px;">View Inventory →</a>
    </div>
    <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
</div>
<?php endif; ?>

<!-- Stats Cards -->
<div class="stats-grid stats-grid-4">
    <article class="stat-card stat-primary">
        <div class="stat-icon"><i class="fa-solid fa-peso-sign"></i></div>
        <div class="stat-info">
            <p>Today's Sales</p>
            <h2><?= format_currency($todaySales) ?></h2>
            <small><?= $todayOrders ?> order(s) today</small>
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
        <div class="stat-icon"><i class="fa-solid fa-receipt"></i></div>
        <div class="stat-info">
            <p>Total Orders</p>
            <h2><?= e($totalOrders) ?></h2>
        </div>
    </article>
    <article class="stat-card stat-<?= $alertCount > 0 ? 'danger' : 'warning' ?>">
        <div class="stat-icon"><i class="fa-solid fa-<?= $alertCount > 0 ? 'triangle-exclamation' : 'box-open' ?>"></i></div>
        <div class="stat-info">
            <p><?= $alertCount > 0 ? 'Stock Alerts' : 'Total Products' ?></p>
            <h2><?= $alertCount > 0 ? $alertCount : e($totalProducts) ?></h2>
            <?php if ($alertCount > 0): ?><small><?= $outOfStockCount ?> out · <?= $lowStockCount ?> low</small><?php endif; ?>
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
                                <td><a href="index.php?page=order-form&order_id=<?= e($o['order_id']) ?>"
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

<!-- ========== LOW STOCK TABLE + RECENT MOVEMENTS ========== -->
<?php if (!empty($lowStockItems) || !empty($recentMovements)): ?>
<div class="dashboard-grid grid-2-1">
    <?php if (!empty($lowStockItems)): ?>
    <section class="panel panel-warning">
        <div class="panel-header">
            <h3><i class="fa-solid fa-triangle-exclamation"></i> Low Stock Items</h3>
            <a href="index.php?page=inventory" class="text-link">Manage →</a>
        </div>
        <div class="table-scroll">
            <table class="table-basic">
                <thead><tr><th>Product</th><th>Stock</th><th>Threshold</th><th>Status</th></tr></thead>
                <tbody>
                <?php foreach ($lowStockItems as $ls):
                    $qty = (int)$ls['stock_quantity'];
                    $statusClass = $qty === 0 ? 'badge-danger' : 'badge-warning';
                    $statusText = $qty === 0 ? 'Out of Stock' : 'Low Stock';
                ?>
                <tr>
                    <td><strong><?= e($ls['product_name']) ?></strong></td>
                    <td><span class="badge <?= $statusClass ?>"><?= $qty ?></span></td>
                    <td><?= e($ls['low_stock_threshold']) ?></td>
                    <td><span class="badge <?= $statusClass ?>"><?= $statusText ?></span></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($recentMovements)): ?>
    <section class="panel">
        <div class="panel-header">
            <h3><i class="fa-solid fa-clock-rotate-left"></i> Recent Movements</h3>
            <a href="index.php?page=inventory" class="text-link">View all →</a>
        </div>
        <ul class="compact-list">
            <?php foreach ($recentMovements as $m): ?>
            <li>
                <span class="badge badge-<?= strtolower($m['type']) === 'in' ? 'success' : 'danger' ?>" style="min-width:36px; text-align:center;"><?= e($m['type']) ?></span>
                <div><strong><?= e($m['product_name'] ?? '—') ?></strong><small><?= e($m['quantity']) ?> units · <?= time_ago($m['created_at']) ?></small></div>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif; ?>
</div>
<?php endif; ?>