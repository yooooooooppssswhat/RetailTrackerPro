<?php
/**
 * Orders — DELETE
 * Soft-deletes order and restores stock
 */
$deleteId = intval($_GET['id'] ?? 0);
if ($deleteId <= 0) { flash('error', 'Invalid order.'); redirect('index.php?page=orders'); }

$orderInfo = db_query('SELECT order_number FROM orders WHERE order_id=? AND deleted_at IS NULL', 'i', [$deleteId])->fetch_assoc();
if (!$orderInfo) { flash('error', 'Order not found.'); redirect('index.php?page=orders'); }

db()->begin_transaction();
try {
    $uid = current_user()['user_id'];
    $orderNumber = $orderInfo['order_number'];

    // Restore stock
    $items = db_query('SELECT * FROM order_items WHERE order_id=?', 'i', [$deleteId])->fetch_all(MYSQLI_ASSOC);
    foreach ($items as $item) {
        if ($item['product_id']) {
            $prod = db_query('SELECT stock_quantity FROM products WHERE product_id=?', 'i', [$item['product_id']])->fetch_assoc();
            if ($prod) {
                $prev = (int)$prod['stock_quantity'];
                $restored = $prev + (int)$item['quantity'];
                db_query('UPDATE products SET stock_quantity=? WHERE product_id=?', 'ii', [$restored, $item['product_id']]);
                db_query('INSERT INTO inventory_movements (product_id,type,quantity,previous_qty,new_qty,reference,notes,created_by) VALUES (?,?,?,?,?,?,?,?)',
                    'isiiissi', [$item['product_id'], 'IN', (int)$item['quantity'], $prev, $restored, $orderNumber, 'Order deleted - stock restored', $uid]);
            }
        }
    }

    db_query('UPDATE orders SET deleted_at=NOW() WHERE order_id=?', 'i', [$deleteId]);
    db()->commit();
    flash('success', 'Order removed. Stock restored.');
} catch (Exception $e) {
    db()->rollback();
    flash('error', 'Error: ' . $e->getMessage());
}
redirect('index.php?page=orders');
