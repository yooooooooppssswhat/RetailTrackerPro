<?php
/**
 * Orders List Page - View and delete orders
 * 
 * STOCK SYNC: When an order is deleted, stock is automatically
 * restored to the products and logged in inventory_movements.
 */

// Handle Delete — Restore stock before soft-deleting the order
if (($_GET['action'] ?? '') === 'delete' && !empty($_GET['id'])) {
    $deleteId = intval($_GET['id']);

    // Get order info before deletion
    $orderInfo = db_query('SELECT order_number FROM orders WHERE order_id=? AND deleted_at IS NULL', 'i', [$deleteId])->fetch_assoc();

    if ($orderInfo) {
        db()->begin_transaction();
        try {
            $uid = current_user()['user_id'];
            $orderNumber = $orderInfo['order_number'];

            // Restore stock for all items in this order
            $items = db_query('SELECT * FROM order_items WHERE order_id=?', 'i', [$deleteId])->fetch_all(MYSQLI_ASSOC);

            foreach ($items as $item) {
                if ($item['product_id']) {
                    $product = db_query('SELECT stock_quantity FROM products WHERE product_id=?', 'i', [$item['product_id']])->fetch_assoc();
                    if ($product) {
                        $prevQty = (int)$product['stock_quantity'];
                        $restoredQty = $prevQty + (int)$item['quantity'];
                        db_query('UPDATE products SET stock_quantity=? WHERE product_id=?', 'ii', [$restoredQty, $item['product_id']]);

                        // Log stock restoration movement
                        db_query('INSERT INTO inventory_movements (product_id, type, quantity, previous_qty, new_qty, reference, notes, created_by) VALUES (?,?,?,?,?,?,?,?)',
                            'isiiissi', [$item['product_id'], 'IN', (int)$item['quantity'], $prevQty, $restoredQty, $orderNumber, 'Order deleted - stock restored', $uid]);
                    }
                }
            }

            // Soft-delete the order
            db_query('UPDATE orders SET deleted_at=NOW() WHERE order_id=?', 'i', [$deleteId]);

            db()->commit();
            flash('success', 'Order removed. Stock has been restored.');
        } catch (Exception $e) {
            db()->rollback();
            flash('error', 'Error deleting order: ' . $e->getMessage());
        }
    } else {
        flash('error', 'Order not found.');
    }
    redirect('index.php?page=orders');
}

// Fetch all orders
$orders = db_query("SELECT * FROM orders WHERE deleted_at IS NULL ORDER BY order_date DESC LIMIT 100")->fetch_all(MYSQLI_ASSOC);
?>

<!-- Page Header -->
<section class="section-header">
    <div>
        <p class="eyebrow"><i class="fa-solid fa-cart-shopping"></i> Orders</p>
        <h1>Order Management</h1>
    </div>
    <div class="header-actions">
        <a href="index.php?page=order-form" class="button button-primary"><i class="fa-solid fa-plus"></i> New Order</a>
    </div>
</section>

<section class="panel">
    <div class="table-scroll">
        <table class="table-basic">
            <thead><tr><th>Order #</th><th>Payment</th><th>Total</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
            <?php if (empty($orders)): ?>
                <tr><td colspan="5" class="empty-state"><i class="fa-solid fa-inbox"></i><p>No orders found</p></td></tr>
            <?php else: foreach ($orders as $o): ?>
            <tr>
                <td><a href="index.php?page=order-form&order_id=<?= e($o['order_id']) ?>" class="text-link"><strong><?= e($o['order_number']) ?></strong></a></td>
                <td><?= e($o['payment_method']) ?></td>
                <td><strong><?= format_currency($o['total_price']) ?></strong></td>
                <td><?= format_date($o['order_date']) ?></td>
                <td class="actions-cell">
                    <a class="btn-icon" href="index.php?page=order-form&order_id=<?= e($o['order_id']) ?>"><i class="fa-solid fa-pen"></i></a>
                    <a class="btn-icon btn-danger" href="index.php?page=orders&action=delete&id=<?= e($o['order_id']) ?>" onclick="return confirm('Delete this order? Stock will be restored.')"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</section>
