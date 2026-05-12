-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2026 at 06:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `retail_tracker_pro`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `movement_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'IN',
  `quantity` int(11) NOT NULL,
  `previous_qty` int(11) DEFAULT 0,
  `new_qty` int(11) DEFAULT 0,
  `reference` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_number` varchar(30) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'Cash',
  `shipping_address` text DEFAULT NULL,
  `courier_name` varchar(100) DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `estimated_delivery` date DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT 0.00,
  `discount_amount` decimal(12,2) DEFAULT 0.00,
  `tax` decimal(12,2) DEFAULT 0.00,
  `shipping_fee` decimal(12,2) DEFAULT 0.00,
  `total_price` decimal(12,2) DEFAULT 0.00,
  `order_notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(150) NOT NULL,
  `sku_code` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `unit_price` decimal(12,2) DEFAULT 0.00,
  `cost_price` decimal(12,2) DEFAULT 0.00,
  `discount` decimal(12,2) DEFAULT 0.00,
  `total_price` decimal(12,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `sku_code` varchar(50) DEFAULT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `unit` varchar(20) DEFAULT 'pcs',
  `cost_price` decimal(12,2) DEFAULT 0.00,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `stock_quantity` int(11) DEFAULT 0,
  `low_stock_threshold` int(11) DEFAULT 10,
  `weight` decimal(8,3) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category_id`, `description`, `sku_code`, `barcode`, `brand`, `unit`, `cost_price`, `price`, `stock_quantity`, `low_stock_threshold`, `weight`, `product_image`, `status`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Premium Jasmine Rice 5kg', NULL, 'High quality jasmine rice', NULL, NULL, NULL, 'pcs', 0.00, 245.00, 50, 20, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(2, 'Jasmine Rice 25kg', NULL, 'Bulk jasmine rice', NULL, NULL, NULL, 'pcs', 0.00, 1050.00, 57, 10, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(3, 'Corned Beef 260g', NULL, 'Canned corned beef', NULL, NULL, NULL, 'pcs', 0.00, 58.75, 157, 30, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(4, 'Sardines in Tomato Sauce 155g', NULL, 'Canned sardines', NULL, NULL, NULL, 'pcs', 0.00, 22.50, 200, 40, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(5, 'Soy Sauce 1L', NULL, 'Cooking condiment', NULL, NULL, NULL, 'pcs', 0.00, 52.00, 109, 20, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(6, 'Cooking Oil 1L', NULL, 'Vegetable cooking oil', NULL, NULL, NULL, 'pcs', 0.00, 89.00, 151, 25, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(7, 'White Sugar 1kg', NULL, 'Refined white sugar', NULL, NULL, NULL, 'pcs', 0.00, 68.00, 145, 30, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(8, 'All-Purpose Flour 1kg', NULL, 'Baking flour', NULL, NULL, NULL, 'pcs', 0.00, 62.00, 84, 20, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(9, 'Iodized Salt 250g', NULL, 'Cooking salt', NULL, NULL, NULL, 'pcs', 0.00, 12.00, 240, 40, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(10, 'Vinegar 1L', NULL, 'Cooking vinegar', NULL, NULL, NULL, 'pcs', 0.00, 38.00, 88, 20, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(11, 'Coca-Cola 1.5L', NULL, 'Soft drink', NULL, NULL, NULL, 'pcs', 0.00, 62.00, 108, 25, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(12, 'Sprite 1.5L', NULL, 'Lemon-lime soft drink', NULL, NULL, NULL, 'pcs', 0.00, 62.00, 90, 25, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(13, 'Nescafe Classic 100g', NULL, 'Instant coffee', NULL, NULL, NULL, 'pcs', 0.00, 195.00, 62, 15, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(14, 'Bottled Water 500ml', NULL, 'Purified drinking water', NULL, NULL, NULL, 'pcs', 0.00, 15.00, 300, 50, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(15, 'C2 Green Tea 500ml', NULL, 'Green tea drink', NULL, NULL, NULL, 'pcs', 0.00, 25.00, 150, 30, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(16, 'Piattos Cheese 85g', NULL, 'Cheese flavored chips', NULL, NULL, NULL, 'pcs', 0.00, 32.00, 130, 25, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(17, 'Sky Flakes Crackers 250g', NULL, 'Crackers', NULL, NULL, NULL, 'pcs', 0.00, 42.00, 100, 20, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(18, 'Lucky Me Instant Noodles', NULL, 'Instant noodles', NULL, NULL, NULL, 'pcs', 0.00, 14.00, 360, 50, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(19, 'Oishi Prawn Crackers 60g', NULL, 'Prawn flavored crackers', NULL, NULL, NULL, 'pcs', 0.00, 28.00, 130, 25, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(20, 'Chippy BBQ 110g', NULL, 'BBQ flavored chips', NULL, NULL, NULL, 'pcs', 0.00, 30.00, 110, 25, NULL, NULL, 'Active', NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL DEFAULT '',
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `role` enum('Admin','Manager','Cashier','Inventory Staff') NOT NULL DEFAULT 'Cashier',
  `avatar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `force_password_change` tinyint(1) DEFAULT 0,
  `password_changed_at` datetime DEFAULT NULL,
  `login_attempts` int(11) DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `full_name`, `phone`, `role`, `avatar`, `is_active`, `force_password_change`, `password_changed_at`, `login_attempts`, `locked_until`, `last_login`, `last_activity`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'admin@retailtracker.ph', '$2y$10$wJgdOeu3Q8P1SVNdyESxUezLOSdxlRAOvjAO3d4CHu9b3zbdwInii', 'Admin User', '09171234567', 'Admin', NULL, 1, 0, NULL, 0, NULL, '2026-05-12 09:47:18', NULL, NULL, '2026-05-12 16:38:51', '2026-05-12 09:47:18', NULL),
(2, 'manager', 'manager@retailtracker.ph', '$2y$10$uN/tgUcRWCH1v1WRh8eFdeS1dU3PDKxByi7sxdR2PasLeWbQWd7o6', 'Manager', '09182345678', 'Manager', NULL, 1, 0, NULL, 0, NULL, NULL, NULL, NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(3, 'cashier1', 'cashier@retailtracker.ph', '$2y$10$ILzbaxuls054ikGKBPFNheAsepck69EAGVEJFxAN2zy636LNkn7jS', 'Cashier', '09193456789', 'Cashier', NULL, 1, 0, NULL, 0, NULL, NULL, NULL, NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL),
(4, 'inventory1', 'inventory@retailtracker.ph', '$2y$10$YQcqDSDc6uCGKXTgo2BVieVKDmUcaGXWKdaM4TD7WcWUEvge..woW', 'Inventory Staff', '09204567890', 'Inventory Staff', NULL, 1, 0, NULL, 0, NULL, NULL, NULL, NULL, '2026-05-12 16:38:51', '2026-05-12 16:38:51', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`movement_id`),
  ADD KEY `idx_inv_product` (`product_id`),
  ADD KEY `idx_inv_date` (`created_at`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_number` (`order_number`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `idx_oi_order` (`order_id`),
  ADD KEY `idx_oi_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `movement_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
