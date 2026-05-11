<?php
/**
 * Settings Page - Store settings and change password
 */

// HANDLE STORE SETTINGS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'store_settings') {
    $storeName = sanitize_input($_POST['store_name'] ?? '');
    $currency  = sanitize_input($_POST['currency_symbol'] ?? '₱');
    if ($storeName !== '') {
        set_setting('store_name', $storeName);
        set_setting('currency_symbol', $currency);
        flash('success', 'Store settings updated.');
    } else {
        flash('error', 'Store name is required.');
    }
    redirect('index.php?page=settings');
}

// HANDLE PASSWORD CHANGE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'change_password') {
    $currentPass = $_POST['current_password'] ?? '';
    $newPass     = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';

    $row = db_query('SELECT password_hash FROM users WHERE user_id=?', 'i', [current_user()['user_id']])->fetch_assoc();

    if (!password_verify($currentPass, $row['password_hash'])) {
        flash('error', 'Current password is incorrect.');
    } elseif (strlen($newPass) < 6) {
        flash('error', 'New password must be at least 6 characters.');
    } elseif ($newPass !== $confirmPass) {
        flash('error', 'Passwords do not match.');
    } else {
        change_password(current_user()['user_id'], $newPass);
        flash('success', 'Password changed successfully.');
    }
    redirect('index.php?page=settings');
}

$storeName = get_setting('store_name', 'RetailTracker Pro');
$currency  = get_setting('currency_symbol', '₱');
?>

<section class="section-header"><div><p class="eyebrow"><i class="fa-solid fa-gear"></i> Settings</p><h1>Settings</h1></div></section>

<div class="dashboard-grid grid-2-1">
    <section class="panel">
        <div class="panel-header"><h3><i class="fa-solid fa-store"></i> Store Information</h3></div>
        <form method="post" class="panel-body">
            <input type="hidden" name="form_action" value="store_settings" />
            <div class="form-group"><label>Store Name</label><input type="text" name="store_name" value="<?= e($storeName) ?>" required /></div>
            <div class="form-group"><label>Currency Symbol</label><input type="text" name="currency_symbol" value="<?= e($currency) ?>" maxlength="5" /></div>
            <div class="modal-footer"><button type="submit" class="button button-primary"><i class="fa-solid fa-save"></i> Save Settings</button></div>
        </form>
    </section>
    <section class="panel">
        <div class="panel-header"><h3><i class="fa-solid fa-lock"></i> Change Password</h3></div>
        <form method="post" class="panel-body">
            <input type="hidden" name="form_action" value="change_password" />
            <div class="form-group"><label>Current Password</label><input type="password" name="current_password" required /></div>
            <div class="form-group"><label>New Password</label><input type="password" name="new_password" required minlength="6" /></div>
            <div class="form-group"><label>Confirm Password</label><input type="password" name="confirm_password" required /></div>
            <div class="modal-footer"><button type="submit" class="button button-primary"><i class="fa-solid fa-key"></i> Change Password</button></div>
        </form>
    </section>
</div>
