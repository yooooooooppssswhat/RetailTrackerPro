<?php
/**
 * Main Entry Point - Routes to the correct page
 * 
 * Modules: Dashboard, Products, Orders, Inventory, Users
 * CRUD pages: module-create.php, module-edit.php, module-delete.php
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/includes/bootstrap.php';

$page = sanitize_input($_GET['page'] ?? 'dashboard');

// Handle logout
if ($page === 'logout') {
    logout_user();
    redirect('index.php?page=login');
}

// If not logged in, show login page
if (!is_logged_in() && $page !== 'login') {
    redirect('index.php?page=login');
}

// Valid pages
$allowedPages = [
    'dashboard',
    'products', 'products-create', 'products-edit', 'products-delete',
    'orders',   'orders-create',   'orders-edit',   'orders-delete',
    'inventory', 'inventory-adjust',
    'users',    'users-create',    'users-edit',    'users-delete',
];

// Login page (own layout, no sidebar)
if ($page === 'login') {
    require_once __DIR__ . '/pages/login.php';
    exit;
}

// Check if page is valid
if (!in_array($page, $allowedPages)) {
    echo "<h1>404 - Page not found</h1>";
    exit;
}

// Role-based access control
$pageModule = explode('-', $page)[0];
if (!can_access($pageModule)) {
    flash('error', 'You do not have permission to access that page.');
    redirect('index.php?page=dashboard');
}

// Delete pages just process and redirect — no layout needed
if (str_ends_with($page, '-delete')) {
    require_once __DIR__ . '/pages/' . $page . '.php';
    exit;
}

// Load the page with sidebar layout
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/pages/' . $page . '.php';
require_once __DIR__ . '/includes/footer.php';