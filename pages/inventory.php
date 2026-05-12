<?php
/**
 * Inventory Page - Real-time stock monitoring & adjustments
 * 
 * Features:
 * - 4 stat cards (Total Products, Low Stock, Out of Stock, Stock Value)
 * - Low stock alerts table
 * - Full stock overview table with status indicators
 * - Recent inventory movements log
 * - Manual stock adjustment modal
 */

// HANDLE STOCK ADJUSTMENT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = intval($_POST['product_id'] ?? 0);
    $type      = sanitize_input($_POST['adjustment_type'] ?? 'IN');
    $qty       = intval($_POST['adjustment_qty'] ?? 0);
    $reason    = sanitize_input($_POST['reason'] ?? '');

    if ($productId <= 0 || $qty <= 0) {
        flash('error', 'Invalid product or quantity.');
    } else {
        $product = db_query('SELECT stock_quantity, product_name FROM products WHERE product_id=?', 'i', [$productId])->fetch_assoc();

        if (!$product) {
            flash('error', 'Product not found.');
        } else {
            $oldStock = (int)$product['stock_quantity'];
            $newStock = ($type === 'IN') ? $oldStock + $qty : max(0, $oldStock - $qty);

            db_query('UPDATE products SET stock_quantity=? WHERE product_id=?', 'ii', [$newStock, $productId]);

            $uid = current_user()['user_id'];
            db_query('INSERT INTO inventory_movements (product_id, type, quantity, previous_qty, new_qty, reference, notes, created_by) VALUES (?,?,?,?,?,?,?,?)',
                'isiiissi', [$productId, $type, $qty, $oldStock, $newStock, 'MANUAL', $reason, $uid]);

            flash('success', "Stock adjusted: {$product['product_name']} ($oldStock → $newStock)");
            redirect('index.php?page=inventory');
        }
    }
}

// ==========================================
// FETCH DATA
// ==========================================
$allProducts = db_query("SELECT p.*, c.category_name FROM products p LEFT JOIN categories c ON c.category_id=p.category_id WHERE p.deleted_at IS NULL ORDER BY p.product_name")->fetch_all(MYSQLI_ASSOC);

// Low stock items (stock > 0 but <= threshold)
$lowStock = array_filter($allProducts, function($p) {
    return $p['stock_quantity'] > 0 && $p['stock_quantity'] <= $p['low_stock_threshold'] && $p['status'] === 'Active';
});

// Out of stock items (stock = 0)
$outOfStock = array_filter($allProducts, function($p) {
    return (int)$p['stock_quantity'] === 0 && $p['status'] === 'Active';
});

// Calculate total stock value
$totalStockValue = 0;
foreach ($allProducts as $p) {
    $totalStockValue += (float)$p['price'] * (int)$p['stock_quantity'];
}

// Total units in stock
$totalUnits = 0;
foreach ($allProducts as $p) {
    $totalUnits += (int)$p['stock_quantity'];
}

// Recent movements (last 25)
$movements = db_query("SELECT im.*, p.product_name, u.full_name AS user_name FROM inventory_movements im LEFT JOIN products p ON p.product_id=im.product_id LEFT JOIN users u ON u.user_id=im.created_by ORDER BY im.created_at DESC LIMIT 25")->fetch_all(MYSQLI_ASSOC);
?>

<section class="section-header">
    <div><p class="eyebrow"><i class="fa-solid fa-warehouse"></i> Inventory</p><h1>Inventory Management</h1></div>
    <div class="header-actions"><button class="button button-primary" onclick="showModal('adjustModal')"><i class="fa-solid fa-sliders"></i> Stock Adjustment</button></div>
</section>

<!-- ========== STAT CARDS ========== -->
<div class="stats-grid stats-grid-4">
    <article class="stat-card stat-primary">
        <div class="stat-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
        <div class="stat-info"><p>Total Products</p><h2><?= count($allProducts) ?></h2><small><?= number_format($totalUnits) ?> total units</small></div>
    </article>
    <article class="stat-card stat-warning">
        <div class="stat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div class="stat-info"><p>Low Stock</p><h2><?= count($lowStock) ?></h2><small>Below threshold</small></div>
    </article>
    <article class="stat-card stat-danger">
        <div class="stat-icon"><i class="fa-solid fa-circle-xmark"></i></div>
        <div class="stat-info"><p>Out of Stock</p><h2><?= count($outOfStock) ?></h2><small>Need restocking</small></div>
    </article>
    <article class="stat-card stat-success">
        <div class="stat-icon"><i class="fa-solid fa-peso-sign"></i></div>
        <div class="stat-info"><p>Stock Value</p><h2><?= format_currency($totalStockValue) ?></h2><small>Based on sell price</small></div>
    </article>
</div>

<!-- ========== LOW STOCK ALERTS ========== -->
<?php if (!empty($outOfStock) || !empty($lowStock)): ?>
<section class="panel panel-warning">
    <div class="panel-header"><h3><i class="fa-solid fa-triangle-exclamation"></i> Stock Alerts</h3><span class="badge badge-danger"><?= count($outOfStock) + count($lowStock) ?> items need attention</span></div>
    <div class="table-scroll"><table class="table-basic"><thead><tr><th>Product</th><th>Stock</th><th>Threshold</th><th>Status</th><th>Action</th></tr></thead><tbody>
    <?php foreach ($outOfStock as $os): ?>
    <tr>
        <td><strong><?= e($os['product_name']) ?></strong></td>
        <td><span class="badge badge-danger">0</span></td>
        <td><?= e($os['low_stock_threshold']) ?></td>
        <td><span class="badge badge-danger"><i class="fa-solid fa-circle-xmark"></i> Out of Stock</span></td>
        <td><button class="button button-tertiary button-sm" onclick="document.getElementById('adj_product').value=<?= $os['product_id'] ?>; showModal('adjustModal')"><i class="fa-solid fa-plus"></i> Restock</button></td>
    </tr>
    <?php endforeach; ?>
    <?php foreach ($lowStock as $ls): ?>
    <tr>
        <td><strong><?= e($ls['product_name']) ?></strong></td>
        <td><span class="badge badge-warning"><?= e($ls['stock_quantity']) ?></span></td>
        <td><?= e($ls['low_stock_threshold']) ?></td>
        <td><span class="badge badge-warning"><i class="fa-solid fa-triangle-exclamation"></i> Low Stock</span></td>
        <td><button class="button button-tertiary button-sm" onclick="document.getElementById('adj_product').value=<?= $ls['product_id'] ?>; showModal('adjustModal')"><i class="fa-solid fa-plus"></i> Restock</button></td>
    </tr>
    <?php endforeach; ?>
    </tbody></table></div>
</section>
<?php endif; ?>

<!-- ========== STOCK OVERVIEW ========== -->
<section class="panel">
    <div class="panel-header"><h3><i class="fa-solid fa-clipboard-list"></i> Stock Overview</h3></div>
    <div class="table-scroll"><table class="table-basic"><thead><tr><th>Product</th><th>Category</th><th>Price</th><th>In Stock</th><th>Threshold</th><th>Value</th><th>Status</th></tr></thead><tbody>
    <?php if (empty($allProducts)): ?>
        <tr><td colspan="7" class="empty-state">No products found</td></tr>
    <?php else: foreach ($allProducts as $p):
        $stockQty = (int)$p['stock_quantity'];
        $threshold = (int)$p['low_stock_threshold'];
        if ($stockQty === 0) { $statusClass = 'badge-danger'; $statusText = 'Out of Stock'; }
        elseif ($stockQty <= $threshold) { $statusClass = 'badge-warning'; $statusText = 'Low Stock'; }
        else { $statusClass = 'badge-success'; $statusText = 'In Stock'; }
        $lineValue = (float)$p['price'] * $stockQty;
    ?>
    <tr>
        <td><strong><?= e($p['product_name']) ?></strong></td>
        <td><?= e($p['category_name'] ?? '—') ?></td>
        <td><?= format_currency($p['price']) ?></td>
        <td><strong><?= e($stockQty) ?></strong></td>
        <td><?= e($threshold) ?></td>
        <td><?= format_currency($lineValue) ?></td>
        <td><span class="badge <?= $statusClass ?>"><?= $statusText ?></span></td>
    </tr>
    <?php endforeach; endif; ?>
    </tbody></table></div>
</section>

<!-- ========== RECENT MOVEMENTS ========== -->
<section class="panel">
    <div class="panel-header"><h3><i class="fa-solid fa-clock-rotate-left"></i> Recent Stock Movements</h3></div>
    <div class="table-scroll"><table class="table-basic"><thead><tr><th>Date</th><th>Product</th><th>Type</th><th>Qty</th><th>Before</th><th>After</th><th>Reference</th><th>Notes</th><th>By</th></tr></thead><tbody>
    <?php if (empty($movements)): ?><tr><td colspan="9" class="empty-state">No movements recorded yet</td></tr>
    <?php else: foreach ($movements as $m): ?>
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

<!-- ========== STOCK ADJUSTMENT MODAL ========== -->
<div class="modal-overlay" id="adjustModal">
    <div class="modal">
        <div class="modal-header"><h3><i class="fa-solid fa-sliders"></i> Stock Adjustment</h3><button class="modal-close" onclick="closeModal('adjustModal')">&times;</button></div>
        <form method="post" class="modal-body">
            <div class="form-group"><label>Product</label>
                <select name="product_id" id="adj_product" required><option value="">Select Product</option>
                <?php foreach ($allProducts as $p): ?><option value="<?= $p['product_id'] ?>">[ <?= e($p['stock_quantity']) ?> ] <?= e($p['product_name']) ?></option><?php endforeach; ?>
                </select></div>
            <div class="form-row">
                <div class="form-group"><label>Type</label><select name="adjustment_type"><option value="IN">Stock In</option><option value="OUT">Stock Out</option></select></div>
                <div class="form-group"><label>Quantity</label><input type="number" name="adjustment_qty" min="1" required /></div>
            </div>
            <div class="form-group"><label>Reason</label><textarea name="reason" rows="2"></textarea></div>
            <div class="modal-footer"><button type="button" class="button button-tertiary" onclick="closeModal('adjustModal')">Cancel</button><button type="submit" class="button button-primary"><i class="fa-solid fa-check"></i> Apply</button></div>
        </form>
    </div>
</div>
