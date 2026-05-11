<?php
/**
 * Header - Sidebar navigation and top bar
 */
$user = current_user();
$currentPage = $_GET['page'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= e(ucfirst($currentPage)) ?> | RetailTracker Pro</title>
    <link rel="stylesheet" href="assets/css/base.css" />
    <link rel="stylesheet" href="assets/css/components.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body>
<div class="app-shell">
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="app-sidebar" id="appSidebar">
        <div class="sidebar-header">
            <div class="brand-logo">
                <div class="brand-mark"><i class="fa-solid fa-store"></i></div>
                <div class="brand-text"><h1>RetailTracker Pro</h1><p>Retail Management</p></div>
            </div>
        </div>
        <nav class="nav-menu">
            <a href="index.php?page=dashboard" class="nav-item<?= $currentPage === 'dashboard' ? ' active' : '' ?>"><i class="fa-solid fa-gauge-high"></i><span>Dashboard</span></a>
            <a href="index.php?page=products" class="nav-item<?= $currentPage === 'products' ? ' active' : '' ?>"><i class="fa-solid fa-box-open"></i><span>Products</span></a>
            <a href="index.php?page=orders" class="nav-item<?= $currentPage === 'orders' ? ' active' : '' ?>"><i class="fa-solid fa-cart-shopping"></i><span>Orders</span></a>
            <a href="index.php?page=inventory" class="nav-item<?= $currentPage === 'inventory' ? ' active' : '' ?>"><i class="fa-solid fa-warehouse"></i><span>Inventory</span></a>
            <a href="index.php?page=reports" class="nav-item<?= $currentPage === 'reports' ? ' active' : '' ?>"><i class="fa-solid fa-chart-pie"></i><span>Reports</span></a>
            <a href="index.php?page=settings" class="nav-item<?= $currentPage === 'settings' ? ' active' : '' ?>"><i class="fa-solid fa-gear"></i><span>Settings</span></a>
        </nav>
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="user-avatar-sm"><?= e(strtoupper(substr($user['full_name'] ?? 'U', 0, 1))) ?></div>
                <div><strong><?= e($user['full_name'] ?? 'User') ?></strong><small><?= e($user['role'] ?? '') ?></small></div>
            </div>
        </div>
    </aside>

    <div class="app-content">
        <header class="topbar">
            <div class="topbar-left">
                <button class="menu-toggle" id="menuToggle"><i class="fa-solid fa-bars"></i></button>
                <h2><?= e(ucfirst(str_replace('-', ' ', $currentPage))) ?></h2>
            </div>
            <div class="topbar-right">
                <button class="icon-btn" id="themeToggle" title="Toggle theme"><i class="fa-solid fa-circle-half-stroke"></i></button>
                <div class="user-menu-wrapper">
                    <button class="user-menu-btn" id="userMenuToggle">
                        <div class="user-avatar-sm"><?= e(strtoupper(substr($user['full_name'] ?? 'U', 0, 1))) ?></div>
                        <span class="user-menu-name"><?= e($user['full_name'] ?? 'User') ?></span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-panel user-dropdown" id="userPanel">
                        <a href="index.php?page=settings" class="dropdown-item"><i class="fa-solid fa-cog"></i> Settings</a>
                        <div class="dropdown-divider"></div>
                        <a href="index.php?page=logout" class="dropdown-item text-danger"><i class="fa-solid fa-right-from-bracket"></i> Sign Out</a>
                    </div>
                </div>
            </div>
        </header>
        <main class="page-inner">
            <?php display_flash(); ?>
