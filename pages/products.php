<?php
/**
 * Products — READ / LIST
 * Displays all products in a table with search
 */
$search = sanitize_input($_GET['search'] ?? '');

if ($search !== '') {
    $like = "%$search%";
    $products = db_query("SELECT * FROM products WHERE deleted_at IS NULL AND product_name LIKE ? ORDER BY created_at DESC", 's', [$like])->fetch_all(MYSQLI_ASSOC);
} else {
    $products = db_query("SELECT * FROM products WHERE deleted_at IS NULL ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
}
?>

<section class="section-header">
    <div>
        <p class="eyebrow"><i class="fa-solid fa-box-open"></i> Products</p>
        <h1>Product Management</h1>
    </div>
    <div class="header-actions">
        <a href="index.php?page=products-create" class="button button-primary"><i class="fa-solid fa-plus"></i> Add Product</a>
    </div>
</section>

<section class="panel">
    <div class="panel-actions">
        <form method="get" class="search-filters">
            <input type="hidden" name="page" value="products" />
            <input type="text" name="search" value="<?= e($search) ?>" placeholder="Search products..." />
            <button type="submit" class="button button-tertiary"><i class="fa-solid fa-search"></i> Search</button>
        </form>
    </div>
    <div class="table-scroll">
        <table class="table-basic">
            <thead><tr><th>Product</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead>
            <tbody>
            <?php if (empty($products)): ?>
                <tr><td colspan="4" class="empty-state"><i class="fa-solid fa-box-open"></i><p>No products found</p></td></tr>
            <?php else: foreach ($products as $p): ?>
            <tr>
                <td class="product-cell">
                    <?php if (!empty($p['product_image']) && file_exists(__DIR__.'/../'.$p['product_image'])): ?>
                        <img src="<?= e($p['product_image']) ?>" class="thumb" />
                    <?php else: ?>
                        <div class="thumb thumb-placeholder"><?= strtoupper(substr($p['product_name'],0,1)) ?></div>
                    <?php endif; ?>
                    <div><strong><?= e($p['product_name']) ?></strong></div>
                </td>
                <td><strong><?= format_currency($p['price']) ?></strong></td>
                <td><?= e($p['stock_quantity']) ?></td>
                <td class="actions-cell">
                    <a class="btn-icon" href="index.php?page=products-edit&id=<?= $p['product_id'] ?>"><i class="fa-solid fa-pen"></i></a>
                    <a class="btn-icon btn-danger" href="index.php?page=products-delete&id=<?= $p['product_id'] ?>" onclick="return confirm('Delete this product?')"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</section>
