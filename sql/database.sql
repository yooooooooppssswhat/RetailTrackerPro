-- =====================================================
-- RetailTracker Pro - Database Setup
-- Run this in phpMyAdmin to create the database
-- =====================================================

CREATE DATABASE IF NOT EXISTS order_tracker;
USE order_tracker;

-- =====================================================
-- USERS TABLE (for login)
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
    branch_id INT DEFAULT 1,
    last_login DATETIME DEFAULT NULL,
    last_activity DATETIME DEFAULT NULL,
    login_attempts INT DEFAULT 0,
    locked_until DATETIME DEFAULT NULL,
    force_password_change TINYINT(1) DEFAULT 0,
    password_changed_at DATETIME DEFAULT NULL,
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
    brand VARCHAR(100) DEFAULT NULL,
    unit VARCHAR(20) DEFAULT 'pcs',
    price DECIMAL(12,2) NOT NULL DEFAULT 0,
    cost_price DECIMAL(12,2) DEFAULT 0,
    stock_quantity INT DEFAULT 0,
    low_stock_threshold INT DEFAULT 10,
    sku_code VARCHAR(50) DEFAULT NULL,
    barcode VARCHAR(50) DEFAULT NULL,
    product_image VARCHAR(255) DEFAULT NULL,
    category_id INT DEFAULT NULL,
    supplier_id INT DEFAULT NULL,
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
    discount_amount DECIMAL(12,2) DEFAULT 0,
    tax DECIMAL(12,2) DEFAULT 0,
    shipping_fee DECIMAL(12,2) DEFAULT 0,
    total_price DECIMAL(12,2) DEFAULT 0,
    shipping_address TEXT DEFAULT NULL,
    order_notes TEXT DEFAULT NULL,
    branch_id INT DEFAULT 1,
    customer_id INT DEFAULT NULL,
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
    sku_code VARCHAR(50) DEFAULT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(12,2) NOT NULL DEFAULT 0,
    cost_price DECIMAL(12,2) DEFAULT 0,
    total_price DECIMAL(12,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
);

-- =====================================================
-- CATEGORIES TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0
);

-- =====================================================
-- SUPPLIERS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS suppliers (
    supplier_id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(200) NOT NULL,
    is_active TINYINT(1) DEFAULT 1
);

-- =====================================================
-- CUSTOMERS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(100) DEFAULT NULL,
    phone_number VARCHAR(20) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    city VARCHAR(100) DEFAULT NULL,
    customer_type ENUM('Individual','Business','Wholesale') DEFAULT 'Individual',
    notes TEXT DEFAULT NULL,
    total_spent DECIMAL(12,2) DEFAULT 0,
    loyalty_points INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL
);

-- =====================================================
-- ACTIVITY LOGS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS activity_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    action VARCHAR(50) NOT NULL,
    module VARCHAR(50) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    details TEXT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- SETTINGS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT DEFAULT NULL,
    setting_group VARCHAR(50) DEFAULT 'general'
);

-- =====================================================
-- NOTIFICATIONS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    type VARCHAR(50) DEFAULT NULL,
    priority VARCHAR(20) DEFAULT 'normal',
    title VARCHAR(200) DEFAULT NULL,
    message TEXT DEFAULT NULL,
    icon VARCHAR(50) DEFAULT 'fa-bell',
    related_id INT DEFAULT NULL,
    related_type VARCHAR(50) DEFAULT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- INVENTORY MOVEMENTS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS inventory_movements (
    movement_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT DEFAULT NULL,
    type VARCHAR(20) DEFAULT NULL,
    quantity DECIMAL(12,2) DEFAULT 0,
    previous_qty DECIMAL(12,2) DEFAULT 0,
    new_qty DECIMAL(12,2) DEFAULT 0,
    reference VARCHAR(100) DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- PAYMENTS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT DEFAULT NULL,
    payment_method VARCHAR(50) DEFAULT NULL,
    payment_status VARCHAR(30) DEFAULT 'Pending',
    amount DECIMAL(12,2) DEFAULT 0,
    transaction_reference VARCHAR(100) DEFAULT NULL,
    received_by INT DEFAULT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- BRANCHES TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS branches (
    branch_id INT AUTO_INCREMENT PRIMARY KEY,
    branch_name VARCHAR(100) NOT NULL DEFAULT 'Main Branch'
);

-- =====================================================
-- INSERT DEFAULT DATA
-- =====================================================

-- Default branch
INSERT IGNORE INTO branches (branch_id, branch_name) VALUES (1, 'Main Branch');

-- Default admin user (password: Admin@2026)
INSERT INTO users (username, password_hash, full_name, email, role, is_active, branch_id)
VALUES ('admin', '$2y$10$placeholder', 'Admin User', 'admin@retail.com', 'Admin', 1, 1)
ON DUPLICATE KEY UPDATE username = username;

-- Default settings
INSERT INTO settings (setting_key, setting_value) VALUES
('store_name', 'RetailTracker Pro'),
('currency_symbol', '₱')
ON DUPLICATE KEY UPDATE setting_key = setting_key;

-- Sample categories
INSERT IGNORE INTO categories (category_name, is_active) VALUES
('Groceries', 1), ('Beverages', 1), ('Snacks', 1), ('Personal Care', 1), ('Household', 1);
