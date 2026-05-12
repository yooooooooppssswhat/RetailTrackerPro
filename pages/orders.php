<?php
/**
 * Orders — READ / LIST
 * Displays all orders in a table
 */
$orders = db_query("SELECT * FROM orders WHERE deleted_at IS NULL ORDER BY order_date DESC LIMIT 100")->fetch_all(MYSQLI_ASSOC);
?>

<section class="section-header">
    <div>
        <p class="eyebrow"><i class="fa-solid fa-cart-shopping"></i> Orders</p>
        <h1>Order Management</h1>
    </div>
    <div class="header-actions">
        <a href="index.php?page=orders-create" class="button button-primary"><i class="fa-solid fa-plus"></i> New Order</a>
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
                <td><a href="index.php?page=orders-edit&id=<?= e($o['order_id']) ?>" class="text-link"><strong><?= e($o['order_number']) ?></strong></a></td>
                <td><?= e($o['payment_method']) ?></td>
                <td><strong><?= format_currency($o['total_price']) ?></strong></td>
                <td><?= format_date($o['order_date']) ?></td>
                <td class="actions-cell">
                    <a class="btn-icon" href="index.php?page=orders-edit&id=<?= e($o['order_id']) ?>"><i class="fa-solid fa-pen"></i></a>
                    <a class="btn-icon btn-danger" href="index.php?page=orders-delete&id=<?= e($o['order_id']) ?>" onclick="return confirm('Delete this order? Stock will be restored.')"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</section>
