<?php
/**
 * Users — CREATE
 * Shows add user form and handles POST submission
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $fullName = sanitize_input($_POST['full_name'] ?? '');
    $email    = sanitize_input($_POST['email'] ?? '');
    $phone    = sanitize_input($_POST['phone'] ?? '');
    $role     = sanitize_input($_POST['role'] ?? 'Cashier');

    $validRoles = ['Admin', 'Manager', 'Cashier', 'Inventory Staff'];

    if ($username === '' || $fullName === '') {
        flash('error', 'Username and full name are required.');
    } elseif (strlen($password) < 6) {
        flash('error', 'Password must be at least 6 characters.');
    } elseif (!in_array($role, $validRoles)) {
        flash('error', 'Invalid role selected.');
    } else {
        // Check uniqueness
        $existing = db_query('SELECT user_id FROM users WHERE username=? AND deleted_at IS NULL', 's', [$username])->fetch_assoc();
        if ($existing) {
            flash('error', 'Username already exists.');
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $createdBy = current_user()['user_id'];
            db_query(
                'INSERT INTO users (username, password_hash, full_name, email, phone, role, is_active, created_by) VALUES (?,?,?,?,?,?,1,?)',
                'ssssssi', [$username, $hash, $fullName, $email, $phone, $role, $createdBy]
            );
            flash('success', "User '$fullName' created successfully.");
            redirect('index.php?page=users');
        }
    }
}
?>

<section class="section-header">
    <div>
        <p class="eyebrow"><i class="fa-solid fa-user-plus"></i> Add User</p>
        <h1>Create New User</h1>
    </div>
    <div class="header-actions">
        <a href="index.php?page=users" class="button button-tertiary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
</section>

<section class="panel">
    <form method="post" class="panel-body form-layout">
        <div class="form-row">
            <div class="form-group">
                <label>Username *</label>
                <input type="text" name="username" required placeholder="e.g. jdoe" />
            </div>
            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" required minlength="6" placeholder="Min 6 characters" />
            </div>
        </div>
        <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="full_name" required placeholder="e.g. Juan Dela Cruz" />
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="user@example.com" />
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" placeholder="09xxxxxxxxx" />
            </div>
        </div>
        <div class="form-group">
            <label>Role *</label>
            <select name="role">
                <option value="Cashier">Cashier — manage orders only</option>
                <option value="Inventory Staff">Inventory Staff — manage products only</option>
                <option value="Manager">Manager — manage products and orders</option>
                <option value="Admin">Admin — full access</option>
            </select>
        </div>
        <div class="form-actions">
            <a href="index.php?page=users" class="button button-tertiary">Cancel</a>
            <button type="submit" class="button button-primary"><i class="fa-solid fa-save"></i> Create User</button>
        </div>
    </form>
</section>
