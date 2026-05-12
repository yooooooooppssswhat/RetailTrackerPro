<?php
/**
 * Users — DELETE
 * Soft-deletes a user and redirects back
 */
$deleteId = intval($_GET['id'] ?? 0);

if ($deleteId <= 0) {
    flash('error', 'Invalid user.');
    redirect('index.php?page=users');
}

// Cannot delete own account
if ($deleteId == current_user()['user_id']) {
    flash('error', 'You cannot delete your own account.');
    redirect('index.php?page=users');
}

db_query('UPDATE users SET deleted_at=NOW(), is_active=0 WHERE user_id=?', 'i', [$deleteId]);
flash('success', 'User removed successfully.');
redirect('index.php?page=users');
