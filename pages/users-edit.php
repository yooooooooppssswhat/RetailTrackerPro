<?php
/**
 * Users — EDIT
 * Shows edit form pre-filled with user data, handles POST update
 * Includes password reset section
 */

$userId = intval($_GET['id'] ?? 0);
if ($userId <= 0) { flash('error', 'Invalid user.'); redirect('index.php?page=users'); }

$editUser = db_query('SELECT * FROM users WHERE user_id=? AND deleted_at IS NULL', 'i', [$userId])->fetch_assoc();
if (!$editUser) { flash('error', 'User not found.'); redirect('index.php?page=users'); }

$isSelf = ($userId == current_user()['user_id']);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'update_profile') {
    $fullName = sanitize_input($_POST['full_name'] ?? '');
    $email    = sanitize_input($_POST['email'] ?? '');
    $phone    = sanitize_input($_POST['phone'] ?? '');
    $role     = sanitize_input($_POST['role'] ?? $editUser['role']);
    $isActive = intval($_POST['is_active'] ?? 1);

    $validRoles = ['Admin', 'Manager', 'Cashier', 'Inventory Staff'];

    if ($fullName === '') {
        flash('error', 'Full name is required.');
    } elseif (!in_array($role, $validRoles)) {
        flash('error', 'Invalid role.');
    } else {
        // Safety: cannot change own role or deactivate self
        if ($isSelf) { $role = current_user()['role']; $isActive = 1; }

        db_query('UPDATE users SET full_name=?, email=?, phone=?, role=?, is_active=? WHERE user_id=?',
            'ssssii', [$fullName, $email, $phone, $role, $isActive, $userId]);
        flash('success', "User updated successfully.");
        redirect('index.php?page=users');
    }
}

// Handle password reset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_action'] ?? '') === 'reset_password') {
    $newPass = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';

    if (strlen($newPass) < 6) {
        flash('error', 'Password must be at least 6 characters.');
    } elseif ($newPass !== $confirmPass) {
        flash('error', 'Passwords do not match.');
    } else {
        change_password($userId, $newPass);
        flash('success', 'Password reset successfully.');
        redirect('index.php?page=users');
    }
}
?>

<section class="section-header">
    <div>
        <p class="eyebrow"><i class="fa-solid fa-user-pen"></i> Edit User</p>
        <h1>Edit: <?= e($editUser['full_name']) ?></h1>
    </div>
    <div class="header-actions">
        <a href="index.php?page=users" class="button button-tertiary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
</section>

<div class="dashboard-grid grid-2-1">
    <!-- Profile Info -->
    <section class="panel">
        <div class="panel-header"><h3><i class="fa-solid fa-user"></i> Profile Information</h3></div>
        <form method="post" class="panel-body form-layout">
            <input type="hidden" name="form_action" value="update_profile" />
            <div class="form-group">
                <label>Username</label>
                <input type="text" value="<?= e($editUser['username']) ?>" disabled class="input-disabled" />
                <small class="form-hint">Username cannot be changed</small>
            </div>
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="full_name" value="<?= e($editUser['full_name']) ?>" required />
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= e($editUser['email'] ?? '') ?>" />
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?= e($editUser['phone'] ?? '') ?>" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" <?= $isSelf ? 'disabled' : '' ?>>
                        <?php foreach (['Admin','Manager','Inventory Staff','Cashier'] as $r): ?>
                        <option value="<?= e($r) ?>" <?= $editUser['role']===$r ? 'selected' : '' ?>><?= e($r) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($isSelf): ?><input type="hidden" name="role" value="<?= e($editUser['role']) ?>" /><small class="form-hint">Cannot change your own role</small><?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="is_active" <?= $isSelf ? 'disabled' : '' ?>>
                        <option value="1" <?= $editUser['is_active'] ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= !$editUser['is_active'] ? 'selected' : '' ?>>Inactive</option>
                    </select>
                    <?php if ($isSelf): ?><input type="hidden" name="is_active" value="1" /><?php endif; ?>
                </div>
            </div>
            <div class="form-actions">
                <a href="index.php?page=users" class="button button-tertiary">Cancel</a>
                <button type="submit" class="button button-primary"><i class="fa-solid fa-save"></i> Save Changes</button>
            </div>
        </form>
    </section>

    <!-- Password Reset -->
    <section class="panel">
        <div class="panel-header"><h3><i class="fa-solid fa-key"></i> Reset Password</h3></div>
        <form method="post" class="panel-body form-layout">
            <input type="hidden" name="form_action" value="reset_password" />
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" required minlength="6" placeholder="Min 6 characters" />
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required placeholder="Re-enter password" />
            </div>
            <div class="form-actions">
                <button type="submit" class="button button-primary"><i class="fa-solid fa-key"></i> Reset Password</button>
            </div>
        </form>
    </section>
</div>
