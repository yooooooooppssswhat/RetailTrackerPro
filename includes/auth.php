<?php
/**
 * Authentication - Login, Logout, Session Check, Password
 * Role-Based Access Control (RBAC) functions
 */

// ==========================================
// SESSION & USER
// ==========================================

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in()
{
    return !empty($_SESSION['user']);
}

// ==========================================
// ROLE HELPERS
// ==========================================

function user_role()
{
    return $_SESSION['user']['role'] ?? '';
}

function is_admin()
{
    return user_role() === 'Admin';
}

/**
 * Check if the current user has one of the given roles
 * Usage: has_role('Admin') or has_role(['Admin', 'Manager'])
 */
function has_role($roles)
{
    if (is_string($roles))
        $roles = [$roles];
    return in_array(user_role(), $roles);
}

/**
 * Require one of the given roles to continue.
 * Redirects if not authorized.
 */
function require_role($roles)
{
    if (!is_logged_in()) {
        redirect('index.php?page=login');
    }
    if (!has_role($roles)) {
        flash('error', 'You do not have permission to access this page.');
        redirect('index.php?page=dashboard');
    }
}

/**
 * Role permission map — which roles can access which modules
 */
function get_role_permissions()
{
    return [
        'Admin' => ['dashboard', 'products', 'orders', 'inventory', 'users'],
        'Manager' => ['dashboard', 'products', 'orders', 'inventory'],
        'Inventory Staff' => ['dashboard', 'products', 'inventory'],
        'Cashier' => ['dashboard', 'orders'],
    ];
}

/**
 * Check if the current user can access a specific module/page
 */
function can_access($page)
{
    $perms = get_role_permissions();
    $role = user_role();
    $allowed = $perms[$role] ?? [];
    return in_array($page, $allowed);
}

// ==========================================
// LOGIN / LOGOUT
// ==========================================

function login_user($username, $password)
{
    $result = db_query('SELECT * FROM users WHERE username = ? AND deleted_at IS NULL LIMIT 1', 's', [$username]);
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        flash('error', 'Invalid username or password.');
        return false;
    }

    if (empty($user['is_active'])) {
        flash('error', 'Your account has been deactivated. Contact an administrator.');
        return false;
    }

    db_query('UPDATE users SET last_login = NOW() WHERE user_id = ?', 'i', [$user['user_id']]);

    unset($user['password_hash']);
    $_SESSION['user'] = $user;
    return true;
}

function logout_user()
{
    $_SESSION = [];
    if (session_id())
        session_destroy();
}

function change_password($userId, $newPassword)
{
    $hash = password_hash($newPassword, PASSWORD_BCRYPT);
    db_query('UPDATE users SET password_hash = ? WHERE user_id = ?', 'si', [$hash, $userId]);
}
