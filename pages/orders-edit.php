<?php
/**
 * Orders — EDIT
 * Shows order form pre-filled with existing data, handles POST update
 * Restores old stock, then deducts new stock
 */

$orderId = intval($_GET['id'] ?? 0);
if ($orderId <= 0) { flash('error', 'Invalid order.'); redirect('index.php?page=orders'); }

$order = db_query('SELECT * FROM orders WHERE order_id=? AND deleted_at IS NULL', 'i', [$orderId])->fetch_assoc();
if (!$order) { flash('error', 'Order not found.'); redirect('index.php?page=orders'); }

$orderItems = db_query('SELECT * FROM order_items WHERE order_id=?', 'i', [$orderId])->fetch_all(MYSQLI_ASSOC);
$products = db_query("SELECT * FROM products WHERE deleted_at IS NULL ORDER BY product_name")->fetch_all(MYSQLI_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentMethod = sanitize_input($_POST['payment_method'] ?? 'Cash');
    $productIds = $_POST['product_id'] ?? [];
    $quantities = $_POST['quantity'] ?? [];
    $unitPrices = $_POST['unit_price'] ?? [];

    $items = [];
    $subtotal = 0;
    foreach ($productIds as $i => $pid) {
        $pid = intval($pid);
        $qty = max(1, intval($quantities[$i] ?? 0));
        $up = floatval($unitPrices[$i] ?? 0);
        if ($pid > 0 && $qty > 0 && $up > 0) {
            $row = db_query('SELECT product_name FROM products WHERE product_id=? LIMIT 1', 'i', [$pid])->fetch_assoc();
            $lineTotal = $qty * $up;
            $items[] = ['pid' => $pid, 'name' => $row['product_name'] ?? 'Unknown', 'qty' => $qty, 'price' => $up, 'total' => $lineTotal];
            $subtotal += $lineTotal;
        }
    }

    if (empty($items)) {
        flash('error', 'Please add at least one product.');
    } else {
        db()->begin_transaction();
        try {
            $uid = current_user()['user_id'];
            $orderNumber = $order['order_number'];

            // Restore old stock
            $oldItems = db_query('SELECT * FROM order_items WHERE order_id=?', 'i', [$orderId])->fetch_all(MYSQLI_ASSOC);
            foreach ($oldItems as $oi) {
                if ($oi['product_id']) {
                    $prod = db_query('SELECT stock_quantity FROM products WHERE product_id=?', 'i', [$oi['product_id']])->fetch_assoc();
                    if ($prod) {
                        $prev = (int)$prod['stock_quantity'];
                        $restored = $prev + (int)$oi['quantity'];
                        db_query('UPDATE products SET stock_quantity=? WHERE product_id=?', 'ii', [$restored, $oi['product_id']]);
                        db_query('INSERT INTO inventory_movements (product_id,type,quantity,previous_qty,new_qty,reference,notes,created_by) VALUES (?,?,?,?,?,?,?,?)',
                            'isiiissi', [$oi['product_id'], 'IN', (int)$oi['quantity'], $prev, $restored, $orderNumber, 'Order edited - stock restored', $uid]);
                    }
                }
            }

            // Update order
            db_query('UPDATE orders SET payment_method=?, subtotal=?, total_price=? WHERE order_id=?', 'sddi', [$paymentMethod, $subtotal, $subtotal, $orderId]);
            db_query('DELETE FROM order_items WHERE order_id=?', 'i', [$orderId]);

            // Insert new items and deduct stock
            foreach ($items as $item) {
                db_query('INSERT INTO order_items (order_id,product_id,product_name,quantity,unit_price,total_price) VALUES (?,?,?,?,?,?)',
                    'iisidd', [$orderId, $item['pid'], $item['name'], $item['qty'], $item['price'], $item['total']]);
                $prod = db_query('SELECT stock_quantity FROM products WHERE product_id=?', 'i', [$item['pid']])->fetch_assoc();
                if ($prod) {
                    $prev = (int)$prod['stock_quantity'];
                    $newQty = max(0, $prev - $item['qty']);
                    db_query('UPDATE products SET stock_quantity=? WHERE product_id=?', 'ii', [$newQty, $item['pid']]);
                    db_query('INSERT INTO inventory_movements (product_id,type,quantity,previous_qty,new_qty,reference,notes,created_by) VALUES (?,?,?,?,?,?,?,?)',
                        'isiiissi', [$item['pid'], 'OUT', $item['qty'], $prev, $newQty, $orderNumber, 'Order updated', $uid]);
                }
            }

            db()->commit();
            flash('success', 'Order updated successfully.');
            redirect('index.php?page=orders');
        } catch (Exception $e) {
            db()->rollback();
            flash('error', 'Error: ' . $e->getMessage());
        }
    }
}
?>

<section class="section-header">
    <div>
        <p class="eyebrow"><i class="fa-solid fa-pen"></i> Edit Order</p>
        <h1>Edit Order: <?= e($order['order_number']) ?></h1>
    </div>
    <div class="header-actions">
        <a href="index.php?page=orders" class="button button-tertiary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
</section>

<form method="post" class="order-layout">
    <div class="order-main">
        <section class="panel">
            <div class="panel-header">
                <h3><i class="fa-solid fa-box"></i> Order Items</h3>
                <button type="button" class="button button-tertiary" onclick="addItemRow()"><i class="fa-solid fa-plus"></i> Add Item</button>
            </div>
            <div class="table-scroll">
                <table class="table-basic order-items-table">
                    <thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Total</th><th></th></tr></thead>
                    <tbody id="orderItemsBody">
                        <?php foreach ($orderItems as $item): ?>
                        <tr>
                            <td><select name="product_id[]" onchange="updatePrice(this)">
                                <?php foreach ($products as $p): ?>
                                <option value="<?= $p['product_id'] ?>" data-price="<?= $p['price'] ?>" data-stock="<?= $p['stock_quantity'] ?>" <?= $item['product_id']==$p['product_id'] ? 'selected' : '' ?>><?= e($p['product_name']) ?> [<?= $p['stock_quantity'] ?> in stock]</option>
                                <?php endforeach; ?>
                            </select></td>
                            <td><input type="number" name="quantity[]" min="1" value="<?= e($item['quantity']) ?>" oninput="calcTotals()" /></td>
                            <td><input type="number" step="0.01" name="unit_price[]" value="<?= e($item['unit_price']) ?>" oninput="calcTotals()" /></td>
                            <td class="item-total"><?= format_currency($item['total_price']) ?></td>
                            <td><button type="button" class="btn-icon btn-danger" onclick="this.closest('tr').remove(); calcTotals()"><i class="fa-solid fa-xmark"></i></button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <div class="summary-panel">
            <div class="summary-total"><span>Total</span><strong id="grandTotal"><?= format_currency($order['total_price']) ?></strong></div>
        </div>
    </div>
    <div class="order-sidebar">
        <section class="panel">
            <h3><i class="fa-solid fa-sliders"></i> Payment</h3>
            <div class="form-group"><label>Payment Method</label>
                <select name="payment_method">
                    <?php foreach (['Cash','GCash','Bank Transfer','Credit Card'] as $m): ?>
                    <option value="<?= e($m) ?>" <?= $order['payment_method']===$m ? 'selected' : '' ?>><?= e($m) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </section>
        <button type="submit" class="button button-primary button-block"><i class="fa-solid fa-save"></i> Update Order</button>
    </div>
</form>

<script>
var productsData = <?= json_encode($products) ?>;
function addItemRow() {
    var tbody = document.getElementById('orderItemsBody');
    var row = document.createElement('tr');
    var opts = '<option value="0">Select product</option>';
    productsData.forEach(function(p) { opts += '<option value="'+p.product_id+'" data-price="'+p.price+'" data-stock="'+p.stock_quantity+'">'+p.product_name+' ['+p.stock_quantity+' in stock]</option>'; });
    row.innerHTML = '<td><select name="product_id[]" onchange="updatePrice(this)">'+opts+'</select></td><td><input type="number" name="quantity[]" min="1" value="1" oninput="calcTotals()" /></td><td><input type="number" step="0.01" name="unit_price[]" value="0.00" oninput="calcTotals()" /></td><td class="item-total">₱0.00</td><td><button type="button" class="btn-icon btn-danger" onclick="this.closest(\'tr\').remove(); calcTotals()"><i class="fa-solid fa-xmark"></i></button></td>';
    tbody.appendChild(row);
}
function updatePrice(s) { var p=parseFloat(s.selectedOptions[0].dataset.price||0); s.closest('tr').querySelector('input[name="unit_price[]"]').value=p.toFixed(2); calcTotals(); }
function calcTotals() {
    var t=0; document.querySelectorAll('#orderItemsBody tr').forEach(function(r) {
        var q=parseFloat(r.querySelector('input[name="quantity[]"]').value)||0;
        var p=parseFloat(r.querySelector('input[name="unit_price[]"]').value)||0;
        var l=q*p; r.querySelector('.item-total').textContent='₱'+l.toFixed(2); t+=l;
    }); document.getElementById('grandTotal').textContent='₱'+t.toFixed(2);
}
window.addEventListener('load', calcTotals);
</script>
