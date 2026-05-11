<?php
/**
 * Products Page - Add, Edit, Delete, and List products
 */

// ==========================================
// HANDLE ADD / EDIT (form submitted)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId   = intval($_POST['product_id'] ?? 0);
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
        $imagePath = $_POST['existing_image'] ?? '';
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

        if ($productId > 0) {
            db_query('UPDATE products SET product_name=?, price=?, stock_quantity=?, description=?, product_image=? WHERE product_id=?',
                'sdissi', [$name, $price, $stock, $description, $imagePath, $productId]);
            flash('success', 'Product updated.');
        } else {
            $uid = current_user()['user_id'];
            db_query('INSERT INTO products (product_name, price, stock_quantity, description, product_image, created_by) VALUES (?,?,?,?,?,?)',
                'sdissi', [$name, $price, $stock, $description, $imagePath, $uid]);
            flash('success', 'Product added.');
        }
        redirect('index.php?page=products');
    }
}

// ==========================================
// HANDLE DELETE
// ==========================================
if (($_GET['action'] ?? '') === 'delete' && !empty($_GET['id'])) {
    $deleteId = intval($_GET['id']);
    db_query('UPDATE products SET deleted_at=NOW() WHERE product_id=?', 'i', [$deleteId]);
    flash('success', 'Product removed.');
    redirect('index.php?page=products');
}

// ==========================================
// FETCH ALL PRODUCTS
// ==========================================
$search = sanitize_input($_GET['search'] ?? '');

if ($search !== '') {
    $like = "%$search%";
    $products = db_query("SELECT * FROM products WHERE deleted_at IS NULL AND product_name LIKE ? ORDER BY created_at DESC", 's', [$like])->fetch_all(MYSQLI_ASSOC);
} else {
    $products = db_query("SELECT * FROM products WHERE deleted_at IS NULL ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
}
?>

<!-- Page Header -->
<section class="section-header">
    <div>
        <p class="eyebrow"><i class="fa-solid fa-box-open"></i> Products</p>
        <h1>Product Management</h1>
    </div>
    <div class="header-actions">
        <button class="button button-primary" onclick="resetProductModal(); showModal('productModal')"><i class="fa-solid fa-plus"></i> Add Product</button>
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
                    <button class="btn-icon" onclick='editProduct(<?= json_encode($p) ?>)'><i class="fa-solid fa-pen"></i></button>
                    <a class="btn-icon btn-danger" href="index.php?page=products&action=delete&id=<?= $p['product_id'] ?>" onclick="return confirm('Delete this product?')"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Add/Edit Product Modal -->
<div class="modal-overlay" id="productModal">
    <div class="modal">
        <div class="modal-header"><h3 id="productModalTitle"><i class="fa-solid fa-box"></i> Add Product</h3><button class="modal-close" onclick="closeModal('productModal')">&times;</button></div>
        <form method="post" enctype="multipart/form-data" class="modal-body">
            <input type="hidden" name="product_id" id="product_id" value="0" />
            <input type="hidden" name="existing_image" id="existing_image" value="" />
            <div class="form-group"><label>Product Name *</label><input type="text" name="product_name" id="product_name" required /></div>
            <div class="form-row">
                <div class="form-group"><label>Price *</label><input type="number" name="price" id="price" step="0.01" min="0" required /></div>
                <div class="form-group"><label>Stock Quantity</label><input type="number" name="stock_quantity" id="stock_quantity" min="0" value="0" /></div>
            </div>
            <div class="form-group"><label>Description</label><textarea name="description" id="description" rows="2"></textarea></div>
            <div class="form-group"><label>Product Image</label><input type="file" name="product_image" accept="image/*" /></div>
            <div class="modal-footer">
                <button type="button" class="button button-tertiary" onclick="closeModal('productModal')">Cancel</button>
                <button type="submit" class="button button-primary"><i class="fa-solid fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>

<script>
function editProduct(p) {
    document.getElementById('productModalTitle').innerHTML = '<i class="fa-solid fa-pen"></i> Edit Product';
    document.getElementById('product_id').value = p.product_id;
    document.getElementById('product_name').value = p.product_name;
    document.getElementById('price').value = p.price;
    document.getElementById('stock_quantity').value = p.stock_quantity;
    document.getElementById('description').value = p.description || '';
    document.getElementById('existing_image').value = p.product_image || '';
    showModal('productModal');
}
function resetProductModal() {
    document.getElementById('productModalTitle').innerHTML = '<i class="fa-solid fa-box"></i> Add Product';
    document.getElementById('product_id').value = 0;
    document.getElementById('product_name').value = '';
    document.getElementById('price').value = '';
    document.getElementById('stock_quantity').value = 0;
    document.getElementById('description').value = '';
    document.getElementById('existing_image').value = '';
}
</script>
