<?php
/**
 * Reports Page - Simple sales and product reports
 */
$dateFrom = sanitize_input($_GET['from'] ?? date('Y-m-01'));
$dateTo   = sanitize_input($_GET['to'] ?? date('Y-m-d'));

// Sales summary
$summary = db_query("SELECT COUNT(*) AS total_orders, COALESCE(SUM(total_price),0) AS total_revenue FROM orders WHERE DATE(order_date) BETWEEN ? AND ? AND deleted_at IS NULL", 'ss', [$dateFrom, $dateTo])->fetch_assoc();
$avgOrder = $summary['total_orders'] > 0 ? $summary['total_revenue'] / $summary['total_orders'] : 0;

// Top selling products
$topProducts = db_query("SELECT oi.product_name, SUM(oi.quantity) AS total_sold, SUM(oi.total_price) AS total_revenue FROM order_items oi JOIN orders o ON o.order_id=oi.order_id WHERE DATE(o.order_date) BETWEEN ? AND ? AND o.deleted_at IS NULL GROUP BY oi.product_name ORDER BY total_sold DESC LIMIT 10", 'ss', [$dateFrom, $dateTo])->fetch_all(MYSQLI_ASSOC);

// Daily sales
$dailySales = db_query("SELECT DATE(order_date) AS sale_date, COUNT(*) AS order_count, COALESCE(SUM(total_price),0) AS daily_revenue FROM orders WHERE DATE(order_date) BETWEEN ? AND ? AND deleted_at IS NULL GROUP BY DATE(order_date) ORDER BY sale_date DESC", 'ss', [$dateFrom, $dateTo])->fetch_all(MYSQLI_ASSOC);

// Payment breakdown
$paymentBreakdown = db_query("SELECT payment_method, COUNT(*) AS count, COALESCE(SUM(total_price),0) AS total FROM orders WHERE DATE(order_date) BETWEEN ? AND ? AND deleted_at IS NULL GROUP BY payment_method ORDER BY total DESC", 'ss', [$dateFrom, $dateTo])->fetch_all(MYSQLI_ASSOC);
?>

<section class="section-header"><div><p class="eyebrow"><i class="fa-solid fa-chart-pie"></i> Reports</p><h1>Sales Reports</h1></div></section>

<section class="panel">
    <form method="get" class="search-filters">
        <input type="hidden" name="page" value="reports" />
        <div class="form-group"><label>From</label><input type="date" name="from" value="<?= e($dateFrom) ?>" /></div>
        <div class="form-group"><label>To</label><input type="date" name="to" value="<?= e($dateTo) ?>" /></div>
        <button type="submit" class="button button-primary"><i class="fa-solid fa-filter"></i> Filter</button>
    </form>
</section>

<div class="stats-grid stats-grid-4">
    <article class="stat-card stat-primary"><div class="stat-icon"><i class="fa-solid fa-peso-sign"></i></div><div class="stat-info"><p>Total Revenue</p><h2><?= format_currency($summary['total_revenue']) ?></h2></div></article>
    <article class="stat-card stat-success"><div class="stat-icon"><i class="fa-solid fa-receipt"></i></div><div class="stat-info"><p>Total Orders</p><h2><?= e($summary['total_orders']) ?></h2></div></article>
    <article class="stat-card stat-info"><div class="stat-icon"><i class="fa-solid fa-tags"></i></div><div class="stat-info"><p>Avg. Order Value</p><h2><?= format_currency($avgOrder) ?></h2></div></article>
</div>

<div class="dashboard-grid grid-2-1">
    <section class="panel">
        <div class="panel-header"><h3><i class="fa-solid fa-fire"></i> Top Selling Products</h3></div>
        <div class="table-scroll"><table class="table-basic"><thead><tr><th>#</th><th>Product</th><th>Qty Sold</th><th>Revenue</th></tr></thead><tbody>
        <?php if (empty($topProducts)): ?><tr><td colspan="4" class="empty-state">No data</td></tr>
        <?php else: foreach ($topProducts as $i => $tp): ?>
        <tr><td><?= $i+1 ?></td><td><strong><?= e($tp['product_name']) ?></strong></td><td><?= e($tp['total_sold']) ?></td><td><?= format_currency($tp['total_revenue']) ?></td></tr>
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

<section class="panel">
    <div class="panel-header"><h3><i class="fa-solid fa-calendar-days"></i> Daily Sales</h3></div>
    <div class="table-scroll"><table class="table-basic"><thead><tr><th>Date</th><th>Orders</th><th>Revenue</th></tr></thead><tbody>
    <?php if (empty($dailySales)): ?><tr><td colspan="3" class="empty-state">No data</td></tr>
    <?php else: foreach ($dailySales as $ds): ?>
    <tr><td><?= format_date($ds['sale_date']) ?></td><td><?= e($ds['order_count']) ?></td><td><strong><?= format_currency($ds['daily_revenue']) ?></strong></td></tr>
    <?php endforeach; endif; ?>
    </tbody></table></div>
</section>
