<?php
/**
 * Inventory — STOCK ADJUSTMENT
 * Form to record Stock In / Stock Out movements
 * Tracks previous and new quantities for audit trail
 */

// Get all products for dropdown
$allProducts = db_query("SELECT product_id, product_name, stock_quantity FROM products WHERE deleted_at IS NULL ORDER BY product_name")->fetch_all(MYSQLI_ASSOC);

// Pre-select product if coming from alerts
$preselectedId = intval($_GET['product_id'] ?? 0);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = intval($_POST['product_id'] ?? 0);
    $type      = sanitize_input($_POST['adjustment_type'] ?? 'IN');
    $qty       = intval($_POST['adjustment_qty'] ?? 0);
    $reason    = sanitize_input($_POST['reason'] ?? '');

    // Validation
    if ($productId <= 0 || $qty <= 0) {
        flash('error', 'Please select a product and enter a valid quantity.');
    } else {
        $product = db_query('SELECT stock_quantity, product_name FROM products WHERE product_id=?', 'i', [$productId])->fetch_assoc();

        if (!$product) {
            flash('error', 'Product not found.');
        } else {
            $oldStock = (int)$product['stock_quantity'];
            $newStock = ($type === 'IN') ? $oldStock + $qty : max(0, $oldStock - $qty);

            // Update stock
            db_query('UPDATE products SET stock_quantity=? WHERE product_id=?', 'ii', [$newStock, $productId]);

            // Log movement
            $uid = current_user()['user_id'];
            db_query(
                'INSERT INTO inventory_movements (product_id, type, quantity, previous_qty, new_qty, reference, notes, created_by) VALUES (?,?,?,?,?,?,?,?)',
                'isiiissi', [$productId, $type, $qty, $oldStock, $newStock, 'MANUAL', $reason, $uid]
            );

            flash('success', "Stock adjusted: {$product['product_name']} ($oldStock → $newStock)");
            redirect('index.php?page=inventory');
        }
    }
}
?>

<!-- Page Header -->
<section class="section-header">
    <div>
        <p class="eyebrow"><i class="fa-solid fa-sliders"></i> Stock Adjustment</p>
        <h1>Adjust Stock</h1>
        <p>Record stock in or stock out movements for accurate inventory tracking</p>
    </div>
    <div class="header-actions">
        <a href="index.php?page=inventory" class="button button-tertiary"><i class="fa-solid fa-arrow-left"></i> Back to Inventory</a>
    </div>
</section>

<div class="dashboard-grid grid-2-1">
    <!-- Adjustment Form -->
    <section class="panel">
        <div class="panel-header"><h3><i class="fa-solid fa-boxes-stacked"></i> Stock Adjustment Form</h3></div>
        <form method="post" class="panel-body form-layout">
            <div class="form-group">
                <label>Product *</label>
                <select name="product_id" id="adj_product" required>
                    <option value="">— Select Product —</option>
                    <?php foreach ($allProducts as $p): ?>
                    <option value="<?= $p['product_id'] ?>" <?= $preselectedId == $p['product_id'] ? 'selected' : '' ?>>
                        <?= e($p['product_name']) ?> [<?= e($p['stock_quantity']) ?> in stock]
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Adjustment Type *</label>
                    <select name="adjustment_type" id="adj_type">
                        <option value="IN" selected>📦 Stock In (Add)</option>
                        <option value="OUT">📤 Stock Out (Remove)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Quantity *</label>
                    <input type="number" name="adjustment_qty" min="1" required placeholder="Enter quantity" />
                </div>
            </div>
            <div class="form-group">
                <label>Reason / Notes</label>
                <textarea name="reason" rows="3" placeholder="e.g. New shipment received, Damaged goods, etc."></textarea>
            </div>
            <div class="form-actions">
                <a href="index.php?page=inventory" class="button button-tertiary">Cancel</a>
                <button type="submit" class="button button-primary"><i class="fa-solid fa-check"></i> Apply Adjustment</button>
            </div>
        </form>
    </section>

    <!-- Quick Reference -->
    <section class="panel">
        <div class="panel-header"><h3><i class="fa-solid fa-circle-info"></i> Quick Guide</h3></div>
        <div class="panel-body">
            <div style="margin-bottom:16px;">
                <strong style="color:var(--success);"><i class="fa-solid fa-arrow-down"></i> Stock In</strong>
                <p style="font-size:.85rem;color:var(--text-secondary);margin:4px 0 0;">Use when receiving new inventory, returns, or corrections that increase stock levels.</p>
            </div>
            <div style="margin-bottom:16px;">
                <strong style="color:var(--danger);"><i class="fa-solid fa-arrow-up"></i> Stock Out</strong>
                <p style="font-size:.85rem;color:var(--text-secondary);margin:4px 0 0;">Use for damaged goods, expired items, or manual deductions not tied to orders.</p>
            </div>
            <div>
                <strong style="color:var(--info);"><i class="fa-solid fa-clock-rotate-left"></i> Automatic Tracking</strong>
                <p style="font-size:.85rem;color:var(--text-secondary);margin:4px 0 0;">Stock is automatically deducted when orders are created and restored when orders are deleted.</p>
            </div>
        </div>
    </section>
</div>
