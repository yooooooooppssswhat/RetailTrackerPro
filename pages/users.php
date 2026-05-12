<?php
/**
 * Users — READ / LIST
 * Displays all users with role badges and status
 * Admin-only page
 */

// Stats
$totalUsers = db_query("SELECT COUNT(*) AS cnt FROM users WHERE deleted_at IS NULL")->fetch_assoc()['cnt'];
$activeUsers = db_query("SELECT COUNT(*) AS cnt FROM users WHERE deleted_at IS NULL AND is_active=1")->fetch_assoc()['cnt'];
$inactiveUsers = $totalUsers - $activeUsers;

// Search & filter
$search = sanitize_input($_GET['search'] ?? '');
$filterRole = sanitize_input($_GET['role'] ?? '');

$sql = "SELECT * FROM users WHERE deleted_at IS NULL";
$types = '';
$params = [];

if ($search !== '') {
    $sql .= " AND (full_name LIKE ? OR username LIKE ? OR email LIKE ?)";
    $like = "%$search%";
    $types .= 'sss';
    $params = array_merge($params, [$like, $like, $like]);
}
if ($filterRole !== '') {
    $sql .= " AND role = ?";
    $types .= 's';
    $params[] = $filterRole;
}
$sql .= " ORDER BY created_at DESC";

$users = db_query($sql, $types, $params)->fetch_all(MYSQLI_ASSOC);

// Role badge colors
function role_badge_class($role) {
    $map = ['Admin' => 'badge-purple', 'Manager' => 'badge-info', 'Inventory Staff' => 'badge-warning', 'Cashier' => 'badge-success'];
    return $map[$role] ?? 'badge-default';
}
?>

<section class="section-header">
    <div>
        <p class="eyebrow"><i class="fa-solid fa-users"></i> Users</p>
        <h1>User Management</h1>
    </div>
    <div class="header-actions">
        <a href="index.php?page=users-create" class="button button-primary"><i class="fa-solid fa-plus"></i> Add User</a>
    </div>
</section>

<!-- Stats -->
<div class="stats-grid stats-grid-3">
    <article class="stat-card stat-primary">
        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
        <div class="stat-info"><p>Total Users</p><h2><?= e($totalUsers) ?></h2></div>
    </article>
    <article class="stat-card stat-success">
        <div class="stat-icon"><i class="fa-solid fa-circle-check"></i></div>
        <div class="stat-info"><p>Active</p><h2><?= e($activeUsers) ?></h2></div>
    </article>
    <article class="stat-card stat-danger">
        <div class="stat-icon"><i class="fa-solid fa-circle-xmark"></i></div>
        <div class="stat-info"><p>Inactive</p><h2><?= e($inactiveUsers) ?></h2></div>
    </article>
</div>

<!-- Search & Filter -->
<section class="panel">
    <div class="panel-actions">
        <form method="get" class="search-filters">
            <input type="hidden" name="page" value="users" />
            <input type="text" name="search" value="<?= e($search) ?>" placeholder="Search by name, username, email..." />
            <select name="role">
                <option value="">All Roles</option>
                <?php foreach (['Admin','Manager','Inventory Staff','Cashier'] as $r): ?>
                <option value="<?= e($r) ?>" <?= $filterRole===$r ? 'selected' : '' ?>><?= e($r) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="button button-tertiary"><i class="fa-solid fa-search"></i> Filter</button>
        </form>
    </div>

    <div class="table-scroll">
        <table class="table-basic">
            <thead><tr><th>User</th><th>Username</th><th>Role</th><th>Status</th><th>Last Login</th><th>Actions</th></tr></thead>
            <tbody>
            <?php if (empty($users)): ?>
                <tr><td colspan="6" class="empty-state"><i class="fa-solid fa-users"></i><p>No users found</p></td></tr>
            <?php else: foreach ($users as $u):
                $isSelf = ($u['user_id'] == current_user()['user_id']);
            ?>
            <tr>
                <td class="product-cell">
                    <div class="user-avatar-sm"><?= e(strtoupper(substr($u['full_name'],0,1))) ?></div>
                    <div><strong><?= e($u['full_name']) ?></strong><br><small class="text-muted"><?= e($u['email'] ?? '—') ?></small></div>
                </td>
                <td><code><?= e($u['username']) ?></code></td>
                <td><span class="badge <?= role_badge_class($u['role']) ?>"><?= e($u['role']) ?></span></td>
                <td>
                    <?php if ($u['is_active']): ?>
                        <span class="badge badge-success">Active</span>
                    <?php else: ?>
                        <span class="badge badge-danger">Inactive</span>
                    <?php endif; ?>
                </td>
                <td><?= $u['last_login'] ? format_datetime($u['last_login']) : '<span class="text-muted">Never</span>' ?></td>
                <td class="actions-cell">
                    <a class="btn-icon" href="index.php?page=users-edit&id=<?= $u['user_id'] ?>" title="Edit"><i class="fa-solid fa-pen"></i></a>
                    <?php if (!$isSelf): ?>
                    <a class="btn-icon btn-danger" href="index.php?page=users-delete&id=<?= $u['user_id'] ?>" onclick="return confirm('Delete user <?= e($u['full_name']) ?>?')" title="Delete"><i class="fa-solid fa-trash"></i></a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</section>
