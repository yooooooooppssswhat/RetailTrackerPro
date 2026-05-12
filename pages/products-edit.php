<?php
/**
 * Products — EDIT
 * Shows edit form pre-filled with product data, handles POST update
 */

$productId = intval($_GET['id'] ?? 0);
if ($productId <= 0) {
    flash('error', 'Invalid product.');
    redirect('index.php?page=products');
}

// Load product
$product = db_query('SELECT * FROM products WHERE product_id=? AND deleted_at IS NULL', 'i', [$productId])->fetch_assoc();
if (!$product) {
    flash('error', 'Product not found.');
    redirect('index.php?page=products');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = sanitize_input($_POST['product_name'] ?? '');
    $price       = floatval($_POST['price'] ?? 0);
    $stock       = intval($_POST['stock_quantity'] ?? 0);
    $description = sanitize_input($_POST['description'] ?? '');

    if ($name === '') {
        flash('error', 'Product name is required.');
    } elseif ($price <= 0) {
        flash('error', 'Price must be greater than zero.');
    } else {
        // Handle image upload
        $imagePath = $product['product_image'] ?? '';
        if (!empty($_FILES['product_image']['name']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../upload/products/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
                $filename = uniqid('prod_') . '.' . $ext;
                move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadDir . $filename);
                $imagePath = 'upload/products/' . $filename;
            }
        }

        db_query(
            'UPDATE products SET product_name=?, price=?, stock_quantity=?, description=?, product_image=? WHERE product_id=?',
            'sdissi', [$name, $price, $stock, $description, $imagePath, $productId]
        );
        flash('success', 'Product updated successfully.');
        redirect('index.php?page=products');
    }
}
?>

<section class="section-header">
    <div>
        <p class="eyebrow"><i class="fa-solid fa-pen"></i> Edit Product</p>
        <h1>Edit: <?= e($product['product_name']) ?></h1>
    </div>
    <div class="header-actions">
        <a href="index.php?page=products" class="button button-tertiary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
</section>

<section class="panel">
    <form method="post" enctype="multipart/form-data" class="panel-body form-layout">
        <div class="form-group">
            <label>Product Name *</label>
            <input type="text" name="product_name" value="<?= e($product['product_name']) ?>" required />
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Price *</label>
                <input type="number" name="price" step="0.01" min="0" value="<?= e($product['price']) ?>" required />
            </div>
            <div class="form-group">
                <label>Stock Quantity</label>
                <input type="number" name="stock_quantity" min="0" value="<?= e($product['stock_quantity']) ?>" />
            </div>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3"><?= e($product['description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Product Image</label>
            <?php if (!empty($product['product_image'])): ?>
                <div class="current-image"><img src="<?= e($product['product_image']) ?>" class="thumb-lg" /><small>Current image</small></div>
            <?php endif; ?>
            <input type="file" name="product_image" accept="image/*" />
            <small class="form-hint">Leave empty to keep current image</small>
        </div>
        <div class="form-actions">
            <a href="index.php?page=products" class="button button-tertiary">Cancel</a>
            <button type="submit" class="button button-primary"><i class="fa-solid fa-save"></i> Update Product</button>
        </div>
    </form>
</section>
