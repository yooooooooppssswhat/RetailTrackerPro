<?php
/**
 * Products — DELETE
 * Soft-deletes a product and redirects back
 */
$deleteId = intval($_GET['id'] ?? 0);

if ($deleteId <= 0) {
    flash('error', 'Invalid product.');
    redirect('index.php?page=products');
}

db_query('UPDATE products SET deleted_at=NOW() WHERE product_id=?', 'i', [$deleteId]);
flash('success', 'Product removed successfully.');
redirect('index.php?page=products');
