-- =====================================================
-- RetailTracker Pro - Database Setup (Cleaned)
-- Run this in phpMyAdmin to create the database
-- Only includes tables for: Dashboard, Products, Orders,
-- Inventory, and Users modules
-- =====================================================

CREATE DATABASE IF NOT EXISTS retail_tracker_pro;
USE retail_tracker_pro;

-- =====================================================
-- USERS TABLE (for login + RBAC)
-- Roles: Admin, Manager, Inventory Staff, Cashier
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) DEFAULT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    role ENUM('Admin', 'Manager', 'Cashier', 'Inventory Staff') DEFAULT 'Cashier',
    is_active TINYINT(1) DEFAULT 1,
    last_login DATETIME DEFAULT NULL,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL
);

-- =====================================================
-- PRODUCTS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(200) NOT NULL,
    description TEXT DEFAULT NULL,
    price DECIMAL(12,2) NOT NULL DEFAULT 0,
    stock_quantity INT DEFAULT 0,
    low_stock_threshold INT DEFAULT 10,
    product_image VARCHAR(255) DEFAULT NULL,
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL
);

-- =====================================================
-- ORDERS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(30) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'Cash',
    subtotal DECIMAL(12,2) DEFAULT 0,
    total_price DECIMAL(12,2) DEFAULT 0,
    created_by INT DEFAULT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL
);

-- =====================================================
-- ORDER ITEMS TABLE (products in each order)
-- =====================================================
CREATE TABLE IF NOT EXISTS order_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT DEFAULT NULL,
    product_name VARCHAR(200) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(12,2) NOT NULL DEFAULT 0,
    total_price DECIMAL(12,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
);

-- =====================================================
-- INVENTORY MOVEMENTS TABLE (stock tracking)
-- =====================================================
CREATE TABLE IF NOT EXISTS inventory_movements (
    movement_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT DEFAULT NULL,
    type VARCHAR(20) DEFAULT NULL,
    quantity INT DEFAULT 0,
    previous_qty INT DEFAULT 0,
    new_qty INT DEFAULT 0,
    reference VARCHAR(100) DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- INSERT DEFAULT DATA
-- =====================================================

-- Default admin user (password: Admin@2026)
INSERT INTO users (username, password_hash, full_name, email, role, is_active)
VALUES ('admin', '$2y$10$wJgdOeu3Q8P1SVNdyESxUezLOSdxlRAOvjAO3d4CHu9b3zbdwInii', 'Admin User', 'admin@retail.com', 'Admin', 1)
ON DUPLICATE KEY UPDATE username = username;
