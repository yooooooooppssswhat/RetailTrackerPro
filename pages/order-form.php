<?php
/**
 * Order Form - Create or Edit an order
 * Simple: select products, set quantities, subtotal = total, save.
 * 
 * STOCK SYNC: When an order is saved, stock is automatically
 * deducted from products and logged in inventory_movements.
 * When editing, old stock is restored first, then new stock is deducted.
 */

// Get all active products for dropdown
$products = db_query("SELECT * FROM products WHERE deleted_at IS NULL ORDER BY product_name")->fetch_all(MYSQLI_ASSOC);

// Load existing order if editing
$order = null;
$orderItems = [];

if (!empty($_GET['order_id'])) {
    $orderId = intval($_GET['order_id']);
    $order = db_query('SELECT * FROM orders WHERE order_id=? AND deleted_at IS NULL', 'i', [$orderId])->fetch_assoc();
    if ($order) {
        $orderItems = db_query('SELECT * FROM order_items WHERE order_id=?', 'i', [$orderId])->fetch_all(MYSQLI_ASSOC);
    }
}

// ==========================================
// HANDLE FORM SUBMISSION
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = intval($_POST['order_id'] ?? 0);
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
            $pName = $row ? $row['product_name'] : 'Unknown';

            $lineTotal = $qty * $up;
            $items[] = ['pid' => $pid, 'name' => $pName, 'qty' => $qty, 'price' => $up, 'total' => $lineTotal];
            $subtotal += $lineTotal;
        }
    }

    if (empty($items)) {
        flash('error', 'Please add at least one product.');
    } else {
        db()->begin_transaction();
        try {
            $uid = current_user()['user_id'];

            if ($orderId > 0) {

                $existingOrder = db_query('SELECT order_number FROM orders WHERE order_id=?', 'i', [$orderId])->fetch_assoc();
                $orderNumber = $existingOrder['order_number'] ?? 'ORD-UNKNOWN';

                $oldItems = db_query('SELECT * FROM order_items WHERE order_id=?', 'i', [$orderId])->fetch_all(MYSQLI_ASSOC);
                foreach ($oldItems as $oldItem) {
                    if ($oldItem['product_id']) {
                        $prod = db_query('SELECT stock_quantity FROM products WHERE product_id=?', 'i', [$oldItem['product_id']])->fetch_assoc();
                        if ($prod) {
                            $prevQty = (int) $prod['stock_quantity'];
                            $restoredQty = $prevQty + (int) $oldItem['quantity'];

                            db_query('UPDATE products SET stock_quantity=? WHERE product_id=?', 'ii', [$restoredQty, $oldItem['product_id']]);

                            db_query(
                                'INSERT INTO inventory_movements (product_id, type, quantity, previous_qty, new_qty, reference, notes, created_by)
                                 VALUES (?,?,?,?,?,?,?,?)',
                                'isiiissi',
                                [$oldItem['product_id'], 'IN', (int) $oldItem['quantity'], $prevQty, $restoredQty, $orderNumber, 'Order edited - stock restored', $uid]
                            );
                        }
                    }
                }

                db_query('UPDATE orders SET payment_method=?, subtotal=?, total_price=? WHERE order_id=?', 'sddi', [$paymentMethod, $subtotal, $subtotal, $orderId]);

                db_query('DELETE FROM order_items WHERE order_id=?', 'i', [$orderId]);

            } else {

                $orderNumber = generate_order_number();

                // 🔥 FIXED: use PHP time instead of NOW()
                $orderDate = date('Y-m-d H:i:s');

                db_query(
                    'INSERT INTO orders (order_number, payment_method, subtotal, total_price, created_by, order_date)
                     VALUES (?,?,?,?,?,?)',
                    'ssddis',
                    [$orderNumber, $paymentMethod, $subtotal, $subtotal, $uid, $orderDate]
                );

                $orderId = db()->insert_id;
            }

            foreach ($items as $item) {
                db_query(
                    'INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, total_price)
                     VALUES (?,?,?,?,?,?)',
                    'iisidd',
                    [$orderId, $item['pid'], $item['name'], $item['qty'], $item['price'], $item['total']]
                );

                $prod = db_query('SELECT stock_quantity FROM products WHERE product_id=?', 'i', [$item['pid']])->fetch_assoc();
                if ($prod) {
                    $prevQty = (int) $prod['stock_quantity'];
                    $newQty = max(0, $prevQty - $item['qty']);

                    db_query('UPDATE products SET stock_quantity=? WHERE product_id=?', 'ii', [$newQty, $item['pid']]);

                    db_query(
                        'INSERT INTO inventory_movements (product_id, type, quantity, previous_qty, new_qty, reference, notes, created_by)
                         VALUES (?,?,?,?,?,?,?,?)',
                        'isiiissi',
                        [$item['pid'], 'OUT', $item['qty'], $prevQty, $newQty, $orderNumber, 'Order processed', $uid]
                    );
                }
            }

            db()->commit();
            flash('success', 'Order saved successfully. Stock updated.');
            redirect('index.php?page=orders');

        } catch (Exception $e) {
            db()->rollback();
            flash('error', 'Error saving order: ' . $e->getMessage());
        }
    }
}

$currentOrderNumber = $order['order_number'] ?? generate_order_number();
?>

<!-- Page Header -->
<section class="section-header">
    <div>
        <p class="eyebrow"><i class="fa-solid fa-file-invoice"></i> <?= $order ? 'Edit Order' : 'New Order' ?></p>
        <h1><?= $order ? 'Edit Order' : 'Create New Order' ?></h1>
        <p>Order: <strong><?= e($currentOrderNumber) ?></strong></p>
    </div>
    <div class="header-actions">
        <a href="index.php?page=orders" class="button button-tertiary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
</section>

<form method="post" class="order-layout">
    <input type="hidden" name="order_id" value="<?= e($order['order_id'] ?? 0) ?>" />

    <div class="order-main">
        <section class="panel">
            <div class="panel-header">
                <h3><i class="fa-solid fa-box"></i> Order Items</h3>
                <button type="button" class="button button-tertiary" onclick="addItemRow()"><i
                        class="fa-solid fa-plus"></i> Add Item</button>
            </div>
            <div class="table-scroll">
                <table class="table-basic order-items-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="orderItemsBody">
                        <?php foreach ($orderItems as $item): ?>
                            <tr>
                                <td><select name="product_id[]" onchange="updatePrice(this)">
                                        <?php foreach ($products as $p): ?>
                                            <option value="<?= $p['product_id'] ?>" data-price="<?= $p['price'] ?>"
                                                data-stock="<?= $p['stock_quantity'] ?>"
                                                <?= $item['product_id'] == $p['product_id'] ? 'selected' : '' ?>>
                                                <?= e($p['product_name']) ?> [<?= $p['stock_quantity'] ?> in stock]
                                            </option>
                                        <?php endforeach; ?>
                                    </select></td>
                                <td><input type="number" name="quantity[]" min="1" value="<?= e($item['quantity']) ?>"
                                        oninput="calcTotals()" /></td>
                                <td><input type="number" step="0.01" name="unit_price[]"
                                        value="<?= e($item['unit_price']) ?>" oninput="calcTotals()" /></td>
                                <td class="item-total"><?= format_currency($item['total_price']) ?></td>
                                <td><button type="button" class="btn-icon btn-danger"
                                        onclick="this.closest('tr').remove(); calcTotals()"><i
                                            class="fa-solid fa-xmark"></i></button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <div class="summary-panel">
            <div class="summary-total"><span>Total</span><strong
                    id="grandTotal"><?= format_currency($order['total_price'] ?? 0) ?></strong></div>
        </div>
    </div>

    <div class="order-sidebar">
        <section class="panel">
            <h3><i class="fa-solid fa-sliders"></i> Payment</h3>
            <div class="form-group"><label>Payment Method</label>
                <select name="payment_method">
                    <?php foreach (['Cash', 'GCash', 'Bank Transfer', 'Credit Card'] as $m): ?>
                        <option value="<?= e($m) ?>" <?= ($order['payment_method'] ?? 'Cash') === $m ? 'selected' : '' ?>>
                            <?= e($m) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </section>
        <button type="submit" class="button button-primary button-block"><i class="fa-solid fa-save"></i> Save
            Order</button>
    </div>
</form>

<script>
    var productsData = <?= json_encode($products) ?>;

    function addItemRow() {
        var tbody = document.getElementById('orderItemsBody');
        var row = document.createElement('tr');
        var options = '<option value="0">Select product</option>';
        productsData.forEach(function (p) {
            options += '<option value="' + p.product_id + '" data-price="' + p.price + '" data-stock="' + p.stock_quantity + '">' + p.product_name + ' [' + p.stock_quantity + ' in stock]</option>';
        });
        row.innerHTML = '<td><select name="product_id[]" onchange="updatePrice(this)">' + options + '</select></td>'
            + '<td><input type="number" name="quantity[]" min="1" value="1" oninput="calcTotals()" /></td>'
            + '<td><input type="number" step="0.01" name="unit_price[]" value="0.00" oninput="calcTotals()" /></td>'
            + '<td class="item-total">₱0.00</td>'
            + '<td><button type="button" class="btn-icon btn-danger" onclick="this.closest(\'tr\').remove(); calcTotals()"><i class="fa-solid fa-xmark"></i></button></td>';
        tbody.appendChild(row);
    }

    function updatePrice(select) {
        var price = parseFloat(select.selectedOptions[0].dataset.price || 0);
        select.closest('tr').querySelector('input[name="unit_price[]"]').value = price.toFixed(2);
        calcTotals();
    }

    function calcTotals() {
        var total = 0;
        document.querySelectorAll('#orderItemsBody tr').forEach(function (row) {
            var qty = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
            var price = parseFloat(row.querySelector('input[name="unit_price[]"]').value) || 0;
            var lineTotal = qty * price;
            row.querySelector('.item-total').textContent = '₱' + lineTotal.toFixed(2);
            total += lineTotal;
        });
        document.getElementById('grandTotal').textContent = '₱' + total.toFixed(2);
    }

    window.addEventListener('load', calcTotals);
</script>