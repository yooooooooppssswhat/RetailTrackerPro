<?php
/**
 * Products — CREATE
 * Shows add product form and handles POST submission
 */

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
        $imagePath = '';
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

        $uid = current_user()['user_id'];
        db_query(
            'INSERT INTO products (product_name, price, stock_quantity, description, product_image, created_by) VALUES (?,?,?,?,?,?)',
            'sdissi', [$name, $price, $stock, $description, $imagePath, $uid]
        );
        flash('success', 'Product added successfully.');
        redirect('index.php?page=products');
    }
}
?>

<section class="section-header">
    <div>
        <p class="eyebrow"><i class="fa-solid fa-plus"></i> Add Product</p>
        <h1>Create New Product</h1>
    </div>
    <div class="header-actions">
        <a href="index.php?page=products" class="button button-tertiary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
</section>

<section class="panel">
    <form method="post" enctype="multipart/form-data" class="panel-body form-layout">
        <div class="form-group">
            <label>Product Name *</label>
            <input type="text" name="product_name" required placeholder="Enter product name" />
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Price *</label>
                <input type="number" name="price" step="0.01" min="0" required placeholder="0.00" />
            </div>
            <div class="form-group">
                <label>Stock Quantity</label>
                <input type="number" name="stock_quantity" min="0" value="0" />
            </div>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3" placeholder="Product description..."></textarea>
        </div>
        <div class="form-group">
            <label>Product Image</label>
            <input type="file" name="product_image" accept="image/*" />
        </div>
        <div class="form-actions">
            <a href="index.php?page=products" class="button button-tertiary">Cancel</a>
            <button type="submit" class="button button-primary"><i class="fa-solid fa-save"></i> Save Product</button>
        </div>
    </form>
</section>
