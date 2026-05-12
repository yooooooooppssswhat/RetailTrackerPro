<?php
/**
 * Reports Page - Comprehensive sales & inventory reports
 * 
 * Sections:
 * - Sales summary stats (Revenue, Orders, Avg Order, Profit)
 * - Top selling products
 * - Payment method breakdown
 * - Daily sales table
 * - Inventory summary (stock value, low stock, movements)
 * - Stock movement log for the selected period
 */

$dateFrom = sanitize_input($_GET['from'] ?? date('Y-m-01'));
$dateTo   = sanitize_input($_GET['to'] ?? date('Y-m-d'));

// ==========================================
// SALES DATA
// ==========================================

// Sales summary
$summary = db_query("SELECT COUNT(*) AS total_orders, COALESCE(SUM(total_price),0) AS total_revenue FROM orders WHERE DATE(order_date) BETWEEN ? AND ? AND deleted_at IS NULL", 'ss', [$dateFrom, $dateTo])->fetch_assoc();
$avgOrder = $summary['total_orders'] > 0 ? $summary['total_revenue'] / $summary['total_orders'] : 0;

// Profit calculation — revenue minus cost
$profitData = db_query("SELECT COALESCE(SUM(oi.total_price),0) AS revenue, COALESCE(SUM(oi.cost_price * oi.quantity),0) AS cost FROM order_items oi JOIN orders o ON o.order_id=oi.order_id WHERE DATE(o.order_date) BETWEEN ? AND ? AND o.deleted_at IS NULL", 'ss', [$dateFrom, $dateTo])->fetch_assoc();
$estimatedProfit = $profitData['revenue'] - $profitData['cost'];

// Top selling products
$topProducts = db_query("SELECT oi.product_name, SUM(oi.quantity) AS total_sold, SUM(oi.total_price) AS total_revenue, SUM(oi.cost_price * oi.quantity) AS total_cost FROM order_items oi JOIN orders o ON o.order_id=oi.order_id WHERE DATE(o.order_date) BETWEEN ? AND ? AND o.deleted_at IS NULL GROUP BY oi.product_name ORDER BY total_sold DESC LIMIT 10", 'ss', [$dateFrom, $dateTo])->fetch_all(MYSQLI_ASSOC);

// Daily sales
$dailySales = db_query("SELECT DATE(order_date) AS sale_date, COUNT(*) AS order_count, COALESCE(SUM(total_price),0) AS daily_revenue FROM orders WHERE DATE(order_date) BETWEEN ? AND ? AND deleted_at IS NULL GROUP BY DATE(order_date) ORDER BY sale_date DESC", 'ss', [$dateFrom, $dateTo])->fetch_all(MYSQLI_ASSOC);

// Payment breakdown
$paymentBreakdown = db_query("SELECT payment_method, COUNT(*) AS count, COALESCE(SUM(total_price),0) AS total FROM orders WHERE DATE(order_date) BETWEEN ? AND ? AND deleted_at IS NULL GROUP BY payment_method ORDER BY total DESC", 'ss', [$dateFrom, $dateTo])->fetch_all(MYSQLI_ASSOC);

// ==========================================
// INVENTORY DATA
// ==========================================

// Current inventory summary
$inventoryStats = db_query("SELECT COUNT(*) AS total_products, COALESCE(SUM(stock_quantity),0) AS total_units, COALESCE(SUM(price * stock_quantity),0) AS stock_value FROM products WHERE deleted_at IS NULL")->fetch_assoc();

$lowStockCount = db_query("SELECT COUNT(*) AS cnt FROM products WHERE deleted_at IS NULL AND status='Active' AND stock_quantity > 0 AND stock_quantity <= low_stock_threshold")->fetch_assoc()['cnt'];

$outOfStockCount = db_query("SELECT COUNT(*) AS cnt FROM products WHERE deleted_at IS NULL AND status='Active' AND stock_quantity = 0")->fetch_assoc()['cnt'];

// Low stock products for the report
$lowStockProducts = db_query("SELECT product_name, stock_quantity, low_stock_threshold, price, (price * stock_quantity) AS stock_value FROM products WHERE deleted_at IS NULL AND status='Active' AND stock_quantity <= low_stock_threshold ORDER BY stock_quantity ASC LIMIT 20")->fetch_all(MYSQLI_ASSOC);

// Stock movements within date range
$stockMovements = db_query("SELECT im.*, p.product_name, u.full_name AS user_name FROM inventory_movements im LEFT JOIN products p ON p.product_id=im.product_id LEFT JOIN users u ON u.user_id=im.created_by WHERE DATE(im.created_at) BETWEEN ? AND ? ORDER BY im.created_at DESC LIMIT 50", 'ss', [$dateFrom, $dateTo])->fetch_all(MYSQLI_ASSOC);

// Movement summary (total IN vs OUT in period)
$movementSummary = db_query("SELECT type, COUNT(*) AS count, COALESCE(SUM(quantity),0) AS total_qty FROM inventory_movements WHERE DATE(created_at) BETWEEN ? AND ? GROUP BY type", 'ss', [$dateFrom, $dateTo])->fetch_all(MYSQLI_ASSOC);
$totalIn = 0; $totalOut = 0;
foreach ($movementSummary as $ms) {
    if ($ms['type'] === 'IN') $totalIn = (int)$ms['total_qty'];
    if ($ms['type'] === 'OUT') $totalOut = (int)$ms['total_qty'];
}
?>

<section class="section-header"><div><p class="eyebrow"><i class="fa-solid fa-chart-pie"></i> Reports</p><h1>Sales & Inventory Reports</h1></div></section>

<!-- ========== DATE FILTER ========== -->
<section class="panel">
    <form method="get" class="search-filters">
        <input type="hidden" name="page" value="reports" />
        <div class="form-group"><label>From</label><input type="date" name="from" value="<?= e($dateFrom) ?>" /></div>
        <div class="form-group"><label>To</label><input type="date" name="to" value="<?= e($dateTo) ?>" /></div>
        <button type="submit" class="button button-primary"><i class="fa-solid fa-filter"></i> Filter</button>
    </form>
</section>

<!-- ========== SALES STATS ========== -->
<h2 class="section-title"><i class="fa-solid fa-cash-register"></i> Sales Overview</h2>
<div class="stats-grid stats-grid-4">
    <article class="stat-card stat-primary"><div class="stat-icon"><i class="fa-solid fa-peso-sign"></i></div><div class="stat-info"><p>Total Revenue</p><h2><?= format_currency($summary['total_revenue']) ?></h2></div></article>
    <article class="stat-card stat-success"><div class="stat-icon"><i class="fa-solid fa-receipt"></i></div><div class="stat-info"><p>Total Orders</p><h2><?= e($summary['total_orders']) ?></h2></div></article>
    <article class="stat-card stat-info"><div class="stat-icon"><i class="fa-solid fa-tags"></i></div><div class="stat-info"><p>Avg. Order Value</p><h2><?= format_currency($avgOrder) ?></h2></div></article>
    <article class="stat-card stat-warning"><div class="stat-icon"><i class="fa-solid fa-chart-line"></i></div><div class="stat-info"><p>Estimated Profit</p><h2><?= format_currency($estimatedProfit) ?></h2><small>Revenue − Cost</small></div></article>
</div>

<!-- ========== TOP PRODUCTS & PAYMENT METHODS ========== -->
<div class="dashboard-grid grid-2-1">
    <section class="panel">
        <div class="panel-header"><h3><i class="fa-solid fa-fire"></i> Top Selling Products</h3></div>
        <div class="table-scroll"><table class="table-basic"><thead><tr><th>#</th><th>Product</th><th>Qty Sold</th><th>Revenue</th><th>Cost</th><th>Profit</th></tr></thead><tbody>
        <?php if (empty($topProducts)): ?><tr><td colspan="6" class="empty-state">No data for this period</td></tr>
        <?php else: foreach ($topProducts as $i => $tp):
            $profit = (float)$tp['total_revenue'] - (float)$tp['total_cost'];
        ?>
        <tr><td><?= $i+1 ?></td><td><strong><?= e($tp['product_name']) ?></strong></td><td><?= e($tp['total_sold']) ?></td><td><?= format_currency($tp['total_revenue']) ?></td><td><?= format_currency($tp['total_cost']) ?></td><td><strong><?= format_currency($profit) ?></strong></td></tr>
        <?php endforeach; endif; ?>
        </tbody></table></div>
    </section>
    <section class="panel">
        <div class="panel-header"><h3><i class="fa-solid fa-credit-card"></i> Payment Methods</h3></div>
        <ul class="compact-list">
        <?php if (empty($paymentBreakdown)): ?><li class="text-muted">No data</li>
        <?php else: foreach ($paymentBreakdown as $pm): ?>
        <li><div><strong><?= e($pm['payment_method']) ?></strong><small><?= e($pm['count']) ?> orders</small></div><span><?= format_currency($pm['total']) ?></span></li>
        <?php endforeach; endif; ?>
        </ul>
    </section>
</div>

<!-- ========== DAILY SALES ========== -->
<section class="panel">
    <div class="panel-header"><h3><i class="fa-solid fa-calendar-days"></i> Daily Sales</h3></div>
    <div class="table-scroll"><table class="table-basic"><thead><tr><th>Date</th><th>Orders</th><th>Revenue</th></tr></thead><tbody>
    <?php if (empty($dailySales)): ?><tr><td colspan="3" class="empty-state">No data for this period</td></tr>
    <?php else: foreach ($dailySales as $ds): ?>
    <tr><td><?= format_date($ds['sale_date']) ?></td><td><?= e($ds['order_count']) ?></td><td><strong><?= format_currency($ds['daily_revenue']) ?></strong></td></tr>
    <?php endforeach; endif; ?>
    </tbody></table></div>
</section>

<!-- ========== INVENTORY REPORT ========== -->
<h2 class="section-title"><i class="fa-solid fa-warehouse"></i> Inventory Report</h2>
<div class="stats-grid stats-grid-4">
    <article class="stat-card stat-primary"><div class="stat-icon"><i class="fa-solid fa-boxes-stacked"></i></div><div class="stat-info"><p>Total Products</p><h2><?= e($inventoryStats['total_products']) ?></h2><small><?= number_format($inventoryStats['total_units']) ?> units</small></div></article>
    <article class="stat-card stat-success"><div class="stat-icon"><i class="fa-solid fa-peso-sign"></i></div><div class="stat-info"><p>Stock Value</p><h2><?= format_currency($inventoryStats['stock_value']) ?></h2></div></article>
    <article class="stat-card stat-warning"><div class="stat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div><div class="stat-info"><p>Low Stock</p><h2><?= e($lowStockCount) ?></h2></div></article>
    <article class="stat-card stat-danger"><div class="stat-icon"><i class="fa-solid fa-circle-xmark"></i></div><div class="stat-info"><p>Out of Stock</p><h2><?= e($outOfStockCount) ?></h2></div></article>
</div>

<!-- ========== LOW STOCK REPORT ========== -->
<?php if (!empty($lowStockProducts)): ?>
<section class="panel panel-warning">
    <div class="panel-header"><h3><i class="fa-solid fa-triangle-exclamation"></i> Low & Out-of-Stock Products</h3></div>
    <div class="table-scroll"><table class="table-basic"><thead><tr><th>Product</th><th>Current Stock</th><th>Threshold</th><th>Price</th><th>Stock Value</th><th>Status</th></tr></thead><tbody>
    <?php foreach ($lowStockProducts as $lp):
        $stockQty = (int)$lp['stock_quantity'];
        if ($stockQty === 0) { $statusClass = 'badge-danger'; $statusText = 'Out of Stock'; }
        else { $statusClass = 'badge-warning'; $statusText = 'Low Stock'; }
    ?>
    <tr><td><strong><?= e($lp['product_name']) ?></strong></td><td><span class="badge <?= $statusClass ?>"><?= e($stockQty) ?></span></td><td><?= e($lp['low_stock_threshold']) ?></td><td><?= format_currency($lp['price']) ?></td><td><?= format_currency($lp['stock_value']) ?></td><td><span class="badge <?= $statusClass ?>"><?= $statusText ?></span></td></tr>
    <?php endforeach; ?>
    </tbody></table></div>
</section>
<?php endif; ?>

<!-- ========== STOCK MOVEMENT LOG ========== -->
<section class="panel">
    <div class="panel-header">
        <h3><i class="fa-solid fa-clock-rotate-left"></i> Stock Movements</h3>
        <div class="panel-meta">
            <span class="badge badge-success"><i class="fa-solid fa-arrow-down"></i> IN: <?= number_format($totalIn) ?> units</span>
            <span class="badge badge-danger"><i class="fa-solid fa-arrow-up"></i> OUT: <?= number_format($totalOut) ?> units</span>
        </div>
    </div>
    <div class="table-scroll"><table class="table-basic"><thead><tr><th>Date</th><th>Product</th><th>Type</th><th>Qty</th><th>Before</th><th>After</th><th>Reference</th><th>Notes</th><th>By</th></tr></thead><tbody>
    <?php if (empty($stockMovements)): ?><tr><td colspan="9" class="empty-state">No movements in this period</td></tr>
    <?php else: foreach ($stockMovements as $m): ?>
    <tr>
        <td><?= format_datetime($m['created_at']) ?></td>
        <td><strong><?= e($m['product_name'] ?? '—') ?></strong></td>
        <td><span class="badge badge-<?= strtolower($m['type']) === 'in' ? 'success' : 'danger' ?>"><?= e($m['type']) ?></span></td>
        <td><?= e($m['quantity']) ?></td>
        <td><?= e($m['previous_qty']) ?></td>
        <td><?= e($m['new_qty']) ?></td>
        <td><small><?= e($m['reference'] ?? '—') ?></small></td>
        <td><small><?= e($m['notes'] ?? '—') ?></small></td>
        <td><?= e($m['user_name'] ?? 'System') ?></td>
    </tr>
    <?php endforeach; endif; ?>
    </tbody></table></div>
</section>
