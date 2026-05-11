<?php
/**
 * Authentication - Login, Logout, Session Check, Password
 */

function current_user() {
    return $_SESSION['user'] ?? null;
}

function is_logged_in() {
    return !empty($_SESSION['user']);
}

function is_admin() {
    return ($_SESSION['user']['role'] ?? '') === 'Admin';
}

function login_user($username, $password) {
    // Find the user in the database
    $result = db_query('SELECT * FROM users WHERE username = ? AND deleted_at IS NULL LIMIT 1', 's', [$username]);
    $user = $result->fetch_assoc();

    // Check if user exists and password matches
    if (!$user || !password_verify($password, $user['password_hash'])) {
        flash('error', 'Invalid username or password.');
        return false;
    }

    // Save user to session (remove password for safety)
    unset($user['password_hash']);
    $_SESSION['user'] = $user;
    return true;
}

function logout_user() {
    $_SESSION = [];
    if (session_id()) session_destroy();
}

function change_password($userId, $newPassword) {
    $hash = password_hash($newPassword, PASSWORD_BCRYPT);
    db_query('UPDATE users SET password_hash = ? WHERE user_id = ?', 'si', [$hash, $userId]);
}