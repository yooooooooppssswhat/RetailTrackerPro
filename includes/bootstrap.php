<?php
/**
 * Bootstrap - Loads all required files
 * This is included at the top of every page.
 */

// Start session (needed for login)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set timezone
date_default_timezone_set('Asia/Manila');

// Start output buffering
ob_start();

// Load our files
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';