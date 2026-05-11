<?php
/**
 * Main Entry Point - Routes to the correct page
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

// List of valid pages
$allowedPages = ['dashboard', 'products', 'orders', 'order-form', 'inventory', 'reports', 'settings'];

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

// Load the page with sidebar layout
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/pages/' . $page . '.php';
require_once __DIR__ . '/includes/footer.php';