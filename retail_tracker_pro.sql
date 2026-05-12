-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2026 at 05:22 AM
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
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  `description` text NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`log_id`, `user_id`, `action`, `module`, `description`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'CREATE', 'orders', 'Created order ORD-2026-0004', 'Customer: Deli Fresh Bakeshop, Total: ???3,920.00', NULL, NULL, '2026-05-11 07:31:34'),
(2, 3, 'CREATE', 'orders', 'Created order ORD-2026-0005', 'Customer: Roberto Garcia Jr., Total: ???2,119.04', NULL, NULL, '2026-05-11 07:31:34'),
(3, 1, 'UPDATE', 'orders', 'Updated order ORD-2026-0006 status to Confirmed', 'Payment received via Maya', NULL, NULL, '2026-05-11 07:31:34'),
(4, 3, 'UPDATE', 'orders', 'Order ORD-2026-0001 marked as Delivered', NULL, NULL, NULL, '2026-05-11 07:31:34'),
(5, 1, 'CREATE', 'products', 'Added new product: Fabric Conditioner 900ml', 'Category: Cleaning Products, Price: ???132.00', NULL, NULL, '2026-05-11 07:31:34'),
(6, 1, 'UPDATE', 'inventory', 'Restocked Premium Jasmine Rice 5kg (+50 units)', 'Previous: 35, New: 85', NULL, NULL, '2026-05-11 07:31:34'),
(7, 3, 'CREATE', 'customers', 'Added new customer Patricia Anne Cruz', NULL, NULL, NULL, '2026-05-11 07:31:34'),
(8, 1, 'LOGIN', 'auth', 'Admin logged in successfully', 'IP: 127.0.0.1', NULL, NULL, '2026-05-11 07:31:34'),
(9, 1, 'LOGIN', 'auth', 'System Administrator logged in successfully', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 07:32:57'),
(10, 1, 'CREATE', 'orders', 'Created order ORD-2026-0011', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 07:36:58'),
(11, 1, 'CREATE', 'products', 'Added product: Test Product XYZ', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 07:38:50'),
(12, 1, 'CREATE', 'customers', 'Added customer: Test Customer ABC', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 07:39:23'),
(13, 1, 'CREATE', 'products', 'Added product: Auto Code Test Product', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 08:04:42'),
(14, 1, 'CREATE', 'orders', 'Created order ORD-2026-0012', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 08:07:22'),
(15, 1, 'LOGOUT', 'auth', 'User logged out', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:19:35'),
(16, 1, 'LOGIN', 'auth', 'System Administrator logged in successfully', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:19:43'),
(17, 1, 'LOGOUT', 'auth', 'User logged out', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:19:46'),
(18, 4, 'LOGIN', 'auth', 'Ana Reyes logged in successfully', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:20:00'),
(19, 4, 'LOGOUT', 'auth', 'User logged out', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:20:24'),
(20, NULL, 'LOGIN_FAIL', 'auth', 'Failed login for user: admin', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:23:24'),
(21, NULL, 'LOGIN_FAIL', 'auth', 'Failed login for user: admin', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:24:32'),
(22, NULL, 'LOGIN_FAIL', 'auth', 'Failed login for user: manager', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:24:48'),
(23, 1, 'LOGIN', 'auth', 'System Administrator logged in successfully', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:27:42'),
(24, 1, 'DELETE', 'products', 'Soft-deleted product ID: 44', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:29:02'),
(25, 1, 'CREATE', 'products', 'Added product: Upload Test Item', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:32:34'),
(26, 1, 'UPDATE', 'users', 'Updated user: Aika', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:45:01'),
(27, 1, 'UPDATE', 'users', 'Updated user: Jeszel', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:45:12'),
(28, 1, 'UPDATE', 'users', 'Updated user: Jeszel', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:45:12'),
(29, 1, 'UPDATE', 'users', 'Updated user: Mary', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:45:23'),
(30, 1, 'UPDATE', 'users', 'Updated user: Mary', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:45:23'),
(31, 1, 'UPDATE', 'users', 'Updated user: Owner', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:45:35'),
(32, NULL, 'LOGIN_FAIL', 'auth', 'Failed login attempt for: staff', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:48:45'),
(33, 2, 'LOGIN', 'auth', 'Aika logged in successfully', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:48:59'),
(34, 2, 'DELETE', 'products', 'Soft-deleted product ID: 46', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:49:05'),
(35, 2, 'DELETE', 'products', 'Soft-deleted product ID: 45', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:49:17'),
(36, 2, 'LOGOUT', 'auth', 'User logged out', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:49:27'),
(37, NULL, 'LOGIN_FAIL', 'auth', 'Failed login for user: admin', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:49:33'),
(38, NULL, 'LOGIN_FAIL', 'auth', 'Failed login for user: admin', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:49:41'),
(39, NULL, 'LOGIN_FAIL', 'auth', 'Failed login for user: admin', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:49:54'),
(40, NULL, 'LOGIN_FAIL', 'auth', 'Failed login for user: admin', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:50:08'),
(41, NULL, 'LOGIN_FAIL', 'auth', 'Failed login for user: admin', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:50:11'),
(42, 3, 'LOGIN', 'auth', 'Jeszel logged in successfully', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:51:23'),
(43, 3, 'CREATE', 'products', 'Added product: Coffe', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:51:54'),
(44, 3, 'DELETE', 'products', 'Soft-deleted product ID: 47', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:52:06'),
(45, 3, 'LOGOUT', 'auth', 'User logged out', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 09:52:24'),
(46, 1, 'LOGIN', 'auth', 'Owner logged in successfully', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:06:36'),
(47, 1, 'UPDATE', 'users', 'Updated user: Owner', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:25:40'),
(48, 1, 'LOGOUT', 'auth', 'User logged out', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:25:51'),
(49, NULL, 'LOGIN_FAIL', 'auth', 'Failed login for user: admin', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:26:13'),
(50, NULL, 'LOGIN_FAIL', 'auth', 'Failed login for user: admin', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:26:19'),
(51, 2, 'LOGIN', 'auth', 'Aika logged in successfully', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:28:09'),
(52, 2, 'LOGOUT', 'auth', 'User logged out', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:28:20'),
(53, NULL, 'LOGIN_FAIL', 'auth', 'Failed login for user: admin', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:28:28'),
(54, NULL, 'LOGIN_FAIL', 'auth', 'Failed login for user: admin', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:28:37'),
(55, 3, 'LOGIN', 'auth', 'Jeszel logged in successfully', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:28:47'),
(56, 3, 'LOGOUT', 'auth', 'User logged out', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:28:58'),
(57, 3, 'LOGOUT', 'auth', 'User logged out', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:28:58'),
(58, NULL, 'LOGIN_FAIL', 'auth', 'Failed login for user: admin', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-11 10:29:58');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT 'fa-tag',
  `color` varchar(20) DEFAULT '#3b82f6',
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`, `icon`, `color`, `sort_order`, `is_active`, `created_at`) VALUES
(1, 'Grocery', 'Rice, canned goods, cooking essentials', 'fa-wheat-awn', '#22c55e', 1, 1, '2026-05-11 07:31:34'),
(2, 'Beverages', 'Soft drinks, juices, water, coffee, tea', 'fa-mug-hot', '#3b82f6', 2, 1, '2026-05-11 07:31:34'),
(3, 'Snacks', 'Chips, crackers, cookies, candy', 'fa-cookie-bite', '#f59e0b', 3, 1, '2026-05-11 07:31:34'),
(4, 'Frozen Goods', 'Frozen meat, seafood, ready meals', 'fa-snowflake', '#06b6d4', 4, 1, '2026-05-11 07:31:34'),
(5, 'Household Supplies', 'Cleaning tools, trash bags, storage', 'fa-house', '#8b5cf6', 5, 1, '2026-05-11 07:31:34'),
(6, 'Personal Care', 'Soap, shampoo, toothpaste, skincare', 'fa-pump-soap', '#ec4899', 6, 1, '2026-05-11 07:31:34'),
(7, 'Office Supplies', 'Paper, pens, folders, tape', 'fa-pen-ruler', '#64748b', 7, 1, '2026-05-11 07:31:34'),
(8, 'Bakery', 'Bread, pastries, cakes', 'fa-bread-slice', '#d97706', 8, 1, '2026-05-11 07:31:34'),
(9, 'Dairy Products', 'Milk, cheese, butter, yogurt, eggs', 'fa-cheese', '#eab308', 9, 1, '2026-05-11 07:31:34'),
(10, 'Cleaning Products', 'Detergent, bleach, disinfectant', 'fa-spray-can-sparkles', '#14b8a6', 10, 1, '2026-05-11 07:31:34');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `movement_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `type` enum('IN','OUT','ADJUSTMENT','RETURN','DAMAGED','TRANSFER','RESTORE') NOT NULL,
  `quantity` int(11) NOT NULL,
  `previous_qty` int(11) DEFAULT 0,
  `new_qty` int(11) DEFAULT 0,
  `reference` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_movements`
--

INSERT INTO `inventory_movements` (`movement_id`, `product_id`, `type`, `quantity`, `previous_qty`, `new_qty`, `reference`, `notes`, `created_by`, `created_at`) VALUES
(1, 44, 'IN', 25, 0, 25, 'INITIAL', 'Initial stock', NULL, '2026-05-11 07:38:50'),
(2, 29, 'OUT', 1, 65, 64, 'ORD-2026-0018', 'Order processed', 1, '2026-05-11 18:01:54'),
(3, 5, 'OUT', 90, 95, 5, 'ORD-2026-0018', 'Order processed', 1, '2026-05-11 18:01:54'),
(4, 8, 'OUT', 1, 70, 69, 'ORD-2026-0019', 'Order processed', 1, '2026-05-12 09:33:57'),
(5, 8, 'OUT', 1, 69, 68, 'ORD-2026-0020', 'Order processed', 1, '2026-05-12 09:36:09'),
(6, 1, 'IN', 10, 85, 95, 'ORD-2026-0007', 'Order deleted - stock restored', 1, '2026-05-12 09:37:57'),
(7, 7, 'IN', 20, 100, 120, 'ORD-2026-0007', 'Order deleted - stock restored', 1, '2026-05-12 09:37:57'),
(8, 6, 'IN', 8, 120, 128, 'ORD-2026-0007', 'Order deleted - stock restored', 1, '2026-05-12 09:37:57'),
(9, 5, 'IN', 14, 5, 19, 'ORD-2026-0007', 'Order deleted - stock restored', 1, '2026-05-12 09:37:57'),
(10, 16, 'IN', 10, 120, 130, 'ORD-2026-0008', 'Order deleted - stock restored', 1, '2026-05-12 09:38:00'),
(11, 18, 'IN', 10, 250, 260, 'ORD-2026-0008', 'Order deleted - stock restored', 1, '2026-05-12 09:38:00'),
(12, 8, 'IN', 1, 68, 69, 'ORD-2026-0020', 'Order deleted - stock restored', 1, '2026-05-12 09:38:02'),
(13, 8, 'IN', 1, 69, 70, 'ORD-2026-0019', 'Order deleted - stock restored', 1, '2026-05-12 09:38:04'),
(14, 38, 'IN', 1, 7, 8, 'ORD-2026-0013', 'Order deleted - stock restored', 1, '2026-05-12 09:38:06'),
(15, 11, 'IN', 1, 100, 101, 'ORD-2026-0013', 'Order deleted - stock restored', 1, '2026-05-12 09:38:06'),
(16, 26, 'IN', 1, 180, 181, 'ORD-2026-0013', 'Order deleted - stock restored', 1, '2026-05-12 09:38:06'),
(17, 40, 'IN', 1, 70, 71, 'ORD-2026-0013', 'Order deleted - stock restored', 1, '2026-05-12 09:38:06'),
(18, 30, 'IN', 1, 50, 51, 'ORD-2026-0013', 'Order deleted - stock restored', 1, '2026-05-12 09:38:06'),
(19, 34, 'IN', 1, 30, 31, 'ORD-2026-0013', 'Order deleted - stock restored', 1, '2026-05-12 09:38:06'),
(20, 29, 'IN', 1, 64, 65, 'ORD-2026-0018', 'Order deleted - stock restored', 1, '2026-05-12 09:38:08'),
(21, 5, 'IN', 90, 19, 109, 'ORD-2026-0018', 'Order deleted - stock restored', 1, '2026-05-12 09:38:08'),
(22, 2, 'IN', 25, 30, 55, 'ORD-2026-0017', 'Order deleted - stock restored', 1, '2026-05-12 09:38:09'),
(23, 48, 'IN', 5, 12, 17, 'ORD-2026-0016', 'Order deleted - stock restored', 1, '2026-05-12 09:38:11'),
(24, 2, 'IN', 2, 55, 57, 'ORD-2026-0015', 'Order deleted - stock restored', 1, '2026-05-12 09:38:13'),
(25, 1, 'IN', 2, 95, 97, 'ORD-2026-0014', 'Order deleted - stock restored', 1, '2026-05-12 09:38:14'),
(26, 8, 'IN', 2, 70, 72, 'ORD-2026-0012', 'Order deleted - stock restored', 1, '2026-05-12 09:38:16'),
(27, 8, 'IN', 3, 72, 75, 'ORD-2026-0011', 'Order deleted - stock restored', 1, '2026-05-12 09:38:18'),
(28, 1, 'IN', 10, 97, 107, 'ORD-2026-0010', 'Order deleted - stock restored', 1, '2026-05-12 09:38:19'),
(29, 6, 'IN', 10, 128, 138, 'ORD-2026-0010', 'Order deleted - stock restored', 1, '2026-05-12 09:38:19'),
(30, 7, 'IN', 10, 120, 130, 'ORD-2026-0010', 'Order deleted - stock restored', 1, '2026-05-12 09:38:19'),
(31, 9, 'IN', 15, 180, 195, 'ORD-2026-0010', 'Order deleted - stock restored', 1, '2026-05-12 09:38:19'),
(32, 38, 'IN', 2, 8, 10, 'ORD-2026-0009', 'Order deleted - stock restored', 1, '2026-05-12 09:38:21'),
(33, 33, 'IN', 2, 8, 10, 'ORD-2026-0009', 'Order deleted - stock restored', 1, '2026-05-12 09:38:21'),
(34, 27, 'IN', 3, 75, 78, 'ORD-2026-0006', 'Order deleted - stock restored', 1, '2026-05-12 09:38:22'),
(35, 29, 'IN', 2, 65, 67, 'ORD-2026-0006', 'Order deleted - stock restored', 1, '2026-05-12 09:38:22'),
(36, 41, 'IN', 3, 85, 88, 'ORD-2026-0006', 'Order deleted - stock restored', 1, '2026-05-12 09:38:22'),
(37, 1, 'IN', 3, 107, 110, 'ORD-2026-0005', 'Order deleted - stock restored', 1, '2026-05-12 09:38:24'),
(38, 3, 'IN', 8, 150, 158, 'ORD-2026-0005', 'Order deleted - stock restored', 1, '2026-05-12 09:38:24'),
(39, 11, 'IN', 5, 101, 106, 'ORD-2026-0005', 'Order deleted - stock restored', 1, '2026-05-12 09:38:24'),
(40, 26, 'IN', 9, 181, 190, 'ORD-2026-0005', 'Order deleted - stock restored', 1, '2026-05-12 09:38:24'),
(41, 32, 'IN', 30, 40, 70, 'ORD-2026-0004', 'Order deleted - stock restored', 1, '2026-05-12 09:38:25'),
(42, 8, 'IN', 10, 75, 85, 'ORD-2026-0004', 'Order deleted - stock restored', 1, '2026-05-12 09:38:25'),
(43, 37, 'IN', 2, 35, 37, 'ORD-2026-0004', 'Order deleted - stock restored', 1, '2026-05-12 09:38:25'),
(44, 13, 'IN', 2, 60, 62, 'ORD-2026-0003', 'Order deleted - stock restored', 1, '2026-05-12 09:38:27'),
(45, 32, 'IN', 2, 70, 72, 'ORD-2026-0003', 'Order deleted - stock restored', 1, '2026-05-12 09:38:27'),
(46, 35, 'IN', 1, 50, 51, 'ORD-2026-0003', 'Order deleted - stock restored', 1, '2026-05-12 09:38:27'),
(47, 28, 'IN', 1, 90, 91, 'ORD-2026-0003', 'Order deleted - stock restored', 1, '2026-05-12 09:38:27'),
(48, 1, 'IN', 20, 110, 130, 'ORD-2026-0002', 'Order deleted - stock restored', 1, '2026-05-12 09:38:28'),
(49, 7, 'IN', 15, 130, 145, 'ORD-2026-0002', 'Order deleted - stock restored', 1, '2026-05-12 09:38:28'),
(50, 6, 'IN', 10, 138, 148, 'ORD-2026-0002', 'Order deleted - stock restored', 1, '2026-05-12 09:38:28'),
(51, 18, 'IN', 100, 260, 360, 'ORD-2026-0002', 'Order deleted - stock restored', 1, '2026-05-12 09:38:28'),
(52, 9, 'IN', 45, 195, 240, 'ORD-2026-0002', 'Order deleted - stock restored', 1, '2026-05-12 09:38:28'),
(53, 1, 'IN', 2, 130, 132, 'ORD-2026-0001', 'Order deleted - stock restored', 1, '2026-05-12 09:38:30'),
(54, 6, 'IN', 3, 148, 151, 'ORD-2026-0001', 'Order deleted - stock restored', 1, '2026-05-12 09:38:30'),
(55, 11, 'IN', 3, 106, 109, 'ORD-2026-0001', 'Order deleted - stock restored', 1, '2026-05-12 09:38:30'),
(56, 39, 'IN', 2, 80, 82, 'ORD-2026-0001', 'Order deleted - stock restored', 1, '2026-05-12 09:38:30'),
(57, 8, 'OUT', 1, 85, 84, 'ORD-2026-0021', 'Order processed', 1, '2026-05-12 09:40:25'),
(58, 30, 'OUT', 1, 51, 50, 'ORD-2026-0021', 'Order processed', 1, '2026-05-12 09:40:25'),
(59, 26, 'OUT', 1, 190, 189, 'ORD-2026-0021', 'Order processed', 1, '2026-05-12 09:40:25'),
(60, 23, 'OUT', 1, 65, 64, 'ORD-2026-0021', 'Order processed', 1, '2026-05-12 09:40:25'),
(61, 34, 'OUT', 1, 31, 30, 'ORD-2026-0022', 'Order processed', 1, '2026-05-12 09:40:48'),
(62, 41, 'OUT', 1, 88, 87, 'ORD-2026-0022', 'Order processed', 1, '2026-05-12 09:40:48'),
(63, 10, 'OUT', 1, 90, 89, 'ORD-2026-0022', 'Order processed', 1, '2026-05-12 09:40:48'),
(64, 10, 'OUT', 1, 89, 88, 'ORD-2026-0022', 'Order processed', 1, '2026-05-12 09:40:48'),
(65, 29, 'OUT', 1, 67, 66, 'ORD-2026-0023', 'Order processed', 1, '2026-05-12 09:41:09'),
(66, 3, 'OUT', 1, 158, 157, 'ORD-2026-0023', 'Order processed', 1, '2026-05-12 09:41:09'),
(67, 24, 'OUT', 1, 70, 69, 'ORD-2026-0023', 'Order processed', 1, '2026-05-12 09:41:09'),
(68, 28, 'OUT', 1, 91, 90, 'ORD-2026-0023', 'Order processed', 1, '2026-05-12 09:41:09'),
(69, 28, 'OUT', 1, 90, 89, 'ORD-2026-0023', 'Order processed', 1, '2026-05-12 09:41:09'),
(70, 34, 'OUT', 1, 30, 29, 'ORD-2026-0024', 'Order processed', 1, '2026-05-12 11:18:35'),
(71, 11, 'OUT', 1, 109, 108, 'ORD-2026-0024', 'Order processed', 1, '2026-05-12 11:18:35'),
(72, 34, 'OUT', 1, 29, 28, 'ORD-2026-0024', 'Order processed', 1, '2026-05-12 11:18:35');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_number`, `payment_method`, `shipping_address`, `courier_name`, `tracking_number`, `estimated_delivery`, `subtotal`, `discount_amount`, `tax`, `shipping_fee`, `total_price`, `order_notes`, `created_by`, `order_date`, `updated_at`, `deleted_at`) VALUES
(1, 'ORD-2026-0001', 'GCash', '45 Mabini St, Sampaloc, Manila', NULL, NULL, NULL, 1245.00, 0.00, 149.40, 0.00, 1394.40, NULL, 3, '2026-04-20 09:15:00', '2026-05-12 09:38:30', '2026-05-12 09:38:30'),
(2, 'ORD-2026-0002', 'Cash', 'Stall 12, Divisoria Market, Manila', NULL, NULL, NULL, 8750.00, 500.00, 990.00, 0.00, 9240.00, NULL, 3, '2026-04-22 10:30:00', '2026-05-12 09:38:28', '2026-05-12 09:38:28'),
(3, 'ORD-2026-0003', 'Bank Transfer', '78 P. Noval St, Quiapo, Manila', NULL, NULL, NULL, 685.50, 0.00, 82.26, 0.00, 767.76, NULL, 3, '2026-04-25 14:00:00', '2026-05-12 09:38:27', '2026-05-12 09:38:27'),
(4, 'ORD-2026-0004', 'Cash', '23 Aurora Blvd, Cubao, QC', NULL, NULL, NULL, 3500.00, 0.00, 420.00, 0.00, 3920.00, NULL, 1, '2026-04-27 08:45:00', '2026-05-12 09:38:25', '2026-05-12 09:38:25'),
(5, 'ORD-2026-0005', 'Credit Card', 'Unit 5B, Green Residences, Taft Ave', NULL, NULL, NULL, 1892.00, 0.00, 227.04, 0.00, 2119.04, NULL, 3, '2026-04-28 16:20:00', '2026-05-12 09:38:24', '2026-05-12 09:38:24'),
(6, 'ORD-2026-0006', 'Maya', '12 Kalayaan Ave, Makati', NULL, NULL, NULL, 956.00, 50.00, 108.72, 0.00, 1014.72, NULL, 1, '2026-04-29 11:00:00', '2026-05-12 09:38:22', '2026-05-12 09:38:22'),
(7, 'ORD-2026-0007', 'Cash', 'Blk 5 Lot 10, Bagong Silang, Caloocan', NULL, NULL, NULL, 5250.00, 250.00, 600.00, 0.00, 5600.00, NULL, 3, '2026-04-15 07:30:00', '2026-05-12 09:37:57', '2026-05-12 09:37:57'),
(8, 'ORD-2026-0008', 'GCash', '45 Mabini St, Sampaloc, Manila', NULL, NULL, NULL, 450.00, 0.00, 54.00, 0.00, 504.00, NULL, 3, '2026-04-18 13:00:00', '2026-05-12 09:38:00', '2026-05-12 09:38:00'),
(9, 'ORD-2026-0009', 'Cash', '55 JP Rizal St, Mandaluyong', NULL, NULL, NULL, 320.00, 0.00, 38.40, 0.00, 358.40, NULL, 1, '2026-05-01 10:00:00', '2026-05-12 09:38:21', '2026-05-12 09:38:21'),
(10, 'ORD-2026-0010', 'Check', 'Stall 12, Divisoria Market, Manila', NULL, NULL, NULL, 4200.00, 0.00, 504.00, 0.00, 4704.00, NULL, 3, '2026-05-02 09:00:00', '2026-05-12 09:38:19', '2026-05-12 09:38:19'),
(11, 'ORD-2026-0011', 'Cash', '', NULL, NULL, NULL, 186.00, 0.00, 0.00, 0.00, 186.00, '', 1, '2026-05-11 07:36:00', '2026-05-12 09:38:18', '2026-05-12 09:38:18'),
(12, 'ORD-2026-0012', 'Cash', '', NULL, NULL, NULL, 124.00, 0.00, 0.00, 0.00, 124.00, '', 1, '2026-05-11 08:06:00', '2026-05-12 09:38:16', '2026-05-12 09:38:16'),
(13, 'ORD-2026-0013', 'Cash', NULL, NULL, NULL, NULL, 377.00, 0.00, 0.00, 0.00, 377.00, NULL, 1, '2026-05-11 23:11:43', '2026-05-12 09:38:06', '2026-05-12 09:38:06'),
(14, 'ORD-2026-0014', 'Cash', NULL, NULL, NULL, NULL, 490.00, 0.00, 0.00, 0.00, 490.00, NULL, 1, '2026-05-11 17:11:49', '2026-05-12 09:38:14', '2026-05-12 09:38:14'),
(15, 'ORD-2026-0015', 'Bank Transfer', NULL, NULL, NULL, NULL, 2100.00, 0.00, 0.00, 0.00, 2100.00, NULL, 1, '2026-05-11 17:22:58', '2026-05-12 09:38:13', '2026-05-12 09:38:13'),
(16, 'ORD-2026-0016', 'Cash', NULL, NULL, NULL, NULL, 5.00, 0.00, 0.00, 0.00, 5.00, NULL, 1, '2026-05-11 17:23:55', '2026-05-12 09:38:11', '2026-05-12 09:38:11'),
(17, 'ORD-2026-0017', 'Cash', NULL, NULL, NULL, NULL, 26250.00, 0.00, 0.00, 0.00, 26250.00, NULL, 1, '2026-05-11 17:28:06', '2026-05-12 09:38:09', '2026-05-12 09:38:09'),
(18, 'ORD-2026-0018', 'Cash', NULL, NULL, NULL, NULL, 4778.00, 0.00, 0.00, 0.00, 4778.00, NULL, 1, '2026-05-11 18:01:54', '2026-05-12 09:38:08', '2026-05-12 09:38:08'),
(19, 'ORD-2026-0019', 'Cash', NULL, NULL, NULL, NULL, 62.00, 0.00, 0.00, 0.00, 62.00, NULL, 1, '2026-05-12 09:33:57', '2026-05-12 09:38:04', '2026-05-12 09:38:04'),
(20, 'ORD-2026-0020', 'Cash', NULL, NULL, NULL, NULL, 62.00, 0.00, 0.00, 0.00, 62.00, NULL, 1, '2026-05-12 09:36:09', '2026-05-12 09:38:02', '2026-05-12 09:38:02'),
(21, 'ORD-2026-0021', 'GCash', NULL, NULL, NULL, NULL, 304.00, 0.00, 0.00, 0.00, 304.00, NULL, 1, '2026-05-12 09:40:25', '2026-05-12 09:40:25', NULL),
(22, 'ORD-2026-0022', 'Bank Transfer', NULL, NULL, NULL, NULL, 209.00, 0.00, 0.00, 0.00, 209.00, NULL, 1, '2026-05-12 09:40:48', '2026-05-12 09:40:48', NULL),
(23, 'ORD-2026-0023', 'GCash', NULL, NULL, NULL, NULL, 424.75, 0.00, 0.00, 0.00, 424.75, NULL, 1, '2026-05-12 09:41:09', '2026-05-12 09:41:09', NULL),
(24, 'ORD-2026-0024', 'Cash', NULL, NULL, NULL, NULL, 138.00, 0.00, 0.00, 0.00, 138.00, NULL, 1, '2026-05-12 11:18:35', '2026-05-12 11:18:35', NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `product_name`, `sku_code`, `quantity`, `unit_price`, `cost_price`, `discount`, `total_price`) VALUES
(1, 1, 1, 'Premium Jasmine Rice 5kg', 'GRC-001', 2, 245.00, 195.00, 0.00, 490.00),
(2, 1, 6, 'Cooking Oil 1L', 'GRC-006', 3, 89.00, 68.00, 0.00, 267.00),
(3, 1, 11, 'Coca-Cola 1.5L', 'BEV-001', 3, 62.00, 42.00, 0.00, 186.00),
(4, 1, 39, 'Laundry Detergent 1kg', 'CLN-001', 2, 155.00, 115.00, 0.00, 310.00),
(5, 2, 1, 'Premium Jasmine Rice 5kg', 'GRC-001', 20, 245.00, 195.00, 0.00, 4900.00),
(6, 2, 7, 'White Sugar 1kg', 'GRC-007', 15, 68.00, 52.00, 0.00, 1020.00),
(7, 2, 6, 'Cooking Oil 1L', 'GRC-006', 10, 89.00, 68.00, 0.00, 890.00),
(8, 2, 18, 'Lucky Me Instant Noodles', 'SNK-003', 100, 14.00, 9.50, 0.00, 1400.00),
(9, 2, 9, 'Iodized Salt 250g', 'GRC-009', 45, 12.00, 8.00, 0.00, 540.00),
(10, 3, 13, 'Nescafe Classic 100g', 'BEV-003', 2, 195.00, 145.00, 0.00, 390.00),
(11, 3, 32, 'Tasty Bread Loaf', 'BKR-001', 2, 82.00, 62.00, 0.00, 164.00),
(12, 3, 35, 'Fresh Milk 1L', 'DRY-001', 1, 115.00, 85.00, 0.00, 115.00),
(13, 3, 28, 'Toothpaste 150ml', 'PRC-003', 1, 95.00, 68.00, 0.00, 95.00),
(14, 4, 32, 'Tasty Bread Loaf', 'BKR-001', 30, 82.00, 62.00, 0.00, 2460.00),
(15, 4, 8, 'All-Purpose Flour 1kg', 'GRC-008', 10, 62.00, 45.00, 0.00, 620.00),
(16, 4, 37, 'Butter Salted 225g', 'DRY-003', 2, 195.00, 145.00, 0.00, 390.00),
(17, 5, 1, 'Premium Jasmine Rice 5kg', 'GRC-001', 3, 245.00, 195.00, 0.00, 735.00),
(18, 5, 3, 'Corned Beef 260g', 'GRC-003', 8, 58.75, 42.00, 0.00, 470.00),
(19, 5, 11, 'Coca-Cola 1.5L', 'BEV-001', 5, 62.00, 42.00, 0.00, 310.00),
(20, 5, 26, 'Bath Soap 90g', 'PRC-001', 9, 42.00, 28.00, 0.00, 378.00),
(21, 6, 27, 'Shampoo 200ml', 'PRC-002', 3, 158.00, 115.00, 0.00, 474.00),
(22, 6, 29, 'Deodorant Roll-on 40ml', 'PRC-004', 2, 98.00, 72.00, 0.00, 196.00),
(23, 6, 41, 'Dishwashing Liquid 500ml', 'CLN-003', 3, 95.00, 68.00, 0.00, 285.00),
(24, 7, 1, 'Premium Jasmine Rice 5kg', 'GRC-001', 10, 245.00, 195.00, 0.00, 2450.00),
(25, 7, 7, 'White Sugar 1kg', 'GRC-007', 20, 68.00, 52.00, 0.00, 1360.00),
(26, 7, 6, 'Cooking Oil 1L', 'GRC-006', 8, 89.00, 68.00, 0.00, 712.00),
(27, 7, 5, 'Soy Sauce 1L', 'GRC-005', 14, 52.00, 38.00, 0.00, 728.00),
(28, 8, 16, 'Piattos Cheese 85g', 'SNK-001', 10, 32.00, 22.00, 0.00, 320.00),
(29, 8, 18, 'Lucky Me Instant Noodles', 'SNK-003', 10, 14.00, 9.50, 0.00, 140.00),
(30, 9, 38, 'Eggs Medium (12pcs)', 'DRY-004', 2, 108.00, 82.00, 0.00, 216.00),
(31, 9, 33, 'Pandesal (10pcs)', 'BKR-002', 2, 50.00, 35.00, 0.00, 100.00),
(32, 10, 1, 'Premium Jasmine Rice 5kg', 'GRC-001', 10, 245.00, 195.00, 0.00, 2450.00),
(33, 10, 6, 'Cooking Oil 1L', 'GRC-006', 10, 89.00, 68.00, 0.00, 890.00),
(34, 10, 7, 'White Sugar 1kg', 'GRC-007', 10, 68.00, 52.00, 0.00, 680.00),
(35, 10, 9, 'Iodized Salt 250g', 'GRC-009', 15, 12.00, 8.00, 0.00, 180.00),
(36, 11, 8, 'All-Purpose Flour 1kg', 'GRC-008', 3, 62.00, 45.00, 0.00, 186.00),
(37, 12, 8, 'All-Purpose Flour 1kg', 'GRC-008', 2, 62.00, 45.00, 0.00, 124.00),
(38, 13, 38, 'Eggs Medium (12pcs)', NULL, 1, 108.00, 0.00, 0.00, 108.00),
(39, 13, 11, 'Coca-Cola 1.5L', NULL, 1, 62.00, 0.00, 0.00, 62.00),
(40, 13, 26, 'Bath Soap 90g', NULL, 1, 42.00, 0.00, 0.00, 42.00),
(41, 13, 40, 'Bleach 1L', NULL, 1, 62.00, 0.00, 0.00, 62.00),
(42, 13, 30, 'Ballpen Blue (12pcs)', NULL, 1, 65.00, 0.00, 0.00, 65.00),
(43, 13, 34, 'Ensaymada Classic', NULL, 1, 38.00, 0.00, 0.00, 38.00),
(44, 14, 1, 'Premium Jasmine Rice 5kg', NULL, 2, 245.00, 0.00, 0.00, 490.00),
(45, 15, 2, 'Jasmine Rice 25kg', NULL, 2, 1050.00, 0.00, 0.00, 2100.00),
(46, 16, 48, 'Test Product XYZ', NULL, 5, 1.00, 0.00, 0.00, 5.00),
(47, 17, 2, 'Jasmine Rice 25kg', NULL, 25, 1050.00, 0.00, 0.00, 26250.00),
(48, 18, 29, 'Deodorant Roll-on 40ml', NULL, 1, 98.00, 0.00, 0.00, 98.00),
(49, 18, 5, 'Soy Sauce 1L', NULL, 90, 52.00, 0.00, 0.00, 4680.00),
(50, 19, 8, 'All-Purpose Flour 1kg', NULL, 1, 62.00, 0.00, 0.00, 62.00),
(51, 20, 8, 'All-Purpose Flour 1kg', NULL, 1, 62.00, 0.00, 0.00, 62.00),
(52, 21, 8, 'All-Purpose Flour 1kg', NULL, 1, 62.00, 0.00, 0.00, 62.00),
(53, 21, 30, 'Ballpen Blue (12pcs)', NULL, 1, 65.00, 0.00, 0.00, 65.00),
(54, 21, 26, 'Bath Soap 90g', NULL, 1, 42.00, 0.00, 0.00, 42.00),
(55, 21, 23, 'Beef Hotdog Regular 500g', NULL, 1, 135.00, 0.00, 0.00, 135.00),
(56, 22, 34, 'Ensaymada Classic', NULL, 1, 38.00, 0.00, 0.00, 38.00),
(57, 22, 41, 'Dishwashing Liquid 500ml', NULL, 1, 95.00, 0.00, 0.00, 95.00),
(58, 22, 10, 'Vinegar 1L', NULL, 1, 38.00, 0.00, 0.00, 38.00),
(59, 22, 10, 'Vinegar 1L', NULL, 1, 38.00, 0.00, 0.00, 38.00),
(60, 23, 29, 'Deodorant Roll-on 40ml', NULL, 1, 98.00, 0.00, 0.00, 98.00),
(61, 23, 3, 'Corned Beef 260g', NULL, 1, 58.75, 0.00, 0.00, 58.75),
(62, 23, 24, 'Trash Bag XL 10pcs', NULL, 1, 78.00, 0.00, 0.00, 78.00),
(63, 23, 28, 'Toothpaste 150ml', NULL, 1, 95.00, 0.00, 0.00, 95.00),
(64, 23, 28, 'Toothpaste 150ml', NULL, 1, 95.00, 0.00, 0.00, 95.00),
(65, 24, 34, 'Ensaymada Classic', NULL, 1, 38.00, 0.00, 0.00, 38.00),
(66, 24, 11, 'Coca-Cola 1.5L', NULL, 1, 62.00, 0.00, 0.00, 62.00),
(67, 24, 34, 'Ensaymada Classic', NULL, 1, 38.00, 0.00, 0.00, 38.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'Cash',
  `payment_status` enum('Pending','Completed','Failed','Refunded') DEFAULT 'Pending',
  `amount` decimal(12,2) DEFAULT 0.00,
  `transaction_reference` varchar(100) DEFAULT NULL,
  `received_by` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `payment_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `order_id`, `payment_method`, `payment_status`, `amount`, `transaction_reference`, `received_by`, `notes`, `payment_date`) VALUES
(1, 1, 'GCash', 'Completed', 1394.40, 'GC-20260420-001', 3, NULL, '2026-05-11 07:31:34'),
(2, 2, 'Cash', 'Completed', 9240.00, NULL, 3, NULL, '2026-05-11 07:31:34'),
(3, 3, 'Bank Transfer', 'Completed', 767.76, 'BT-20260425-001', 3, NULL, '2026-05-11 07:31:34'),
(4, 4, 'Cash', 'Completed', 3920.00, NULL, 1, NULL, '2026-05-11 07:31:34'),
(5, 6, 'Maya', 'Completed', 1014.72, 'MY-20260429-001', 1, NULL, '2026-05-11 07:31:34'),
(6, 7, 'Cash', 'Completed', 5600.00, NULL, 3, NULL, '2026-05-11 07:31:34'),
(7, 8, 'GCash', 'Refunded', 504.00, 'GC-20260418-REF', 3, NULL, '2026-05-11 07:31:34'),
(8, 9, 'Cash', 'Completed', 358.40, NULL, 1, NULL, '2026-05-11 07:31:34'),
(9, 11, 'Cash', 'Pending', 186.00, '', 1, NULL, '2026-05-11 07:36:58'),
(10, 12, 'Cash', 'Pending', 124.00, '', 1, NULL, '2026-05-11 08:07:22');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category_id`, `description`, `sku_code`, `barcode`, `brand`, `unit`, `cost_price`, `price`, `stock_quantity`, `low_stock_threshold`, `weight`, `product_image`, `status`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Premium Jasmine Rice 5kg', 1, '', 'GRC-001', '4801234560011', 'Do??a Maria', 'bag', 195.00, 245.00, 50, 20, NULL, '', 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:38:37', NULL),
(2, 'Jasmine Rice 25kg', 1, NULL, 'GRC-002', '4801234560012', 'Sinandomeng', 'sack', 850.00, 1050.00, 57, 10, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:38:13', NULL),
(3, 'Corned Beef 260g', 1, NULL, 'GRC-003', '4801234560013', 'Argentina', 'can', 42.00, 58.75, 157, 30, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:41:09', NULL),
(4, 'Sardines in Tomato Sauce 155g', 1, NULL, 'GRC-004', '4801234560014', 'Mega', 'can', 16.50, 22.50, 200, 40, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-11 07:31:34', NULL),
(5, 'Soy Sauce 1L', 1, '', 'GRC-005', '4801234560015', 'Silver Swan', 'bottle', 38.00, 52.00, 109, 20, NULL, 'upload/products/prod_6a0274d860a94.webp', 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:38:08', NULL),
(6, 'Cooking Oil 1L', 1, NULL, 'GRC-006', '4801234560016', 'Baguio', 'bottle', 68.00, 89.00, 151, 25, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:38:30', NULL),
(7, 'White Sugar 1kg', 1, NULL, 'GRC-007', '4801234560017', 'Washed', 'pack', 52.00, 68.00, 145, 30, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:38:28', NULL),
(8, 'All-Purpose Flour 1kg', 1, NULL, 'GRC-008', '4801234560018', 'Maya', 'pack', 45.00, 62.00, 84, 20, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:40:25', NULL),
(9, 'Iodized Salt 250g', 1, NULL, 'GRC-009', '4801234560019', 'Pagasa', 'pack', 8.00, 12.00, 240, 40, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:38:28', NULL),
(10, 'Vinegar 1L', 1, NULL, 'GRC-010', '4801234560020', 'Datu Puti', 'bottle', 28.00, 38.00, 88, 20, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:40:48', NULL),
(11, 'Coca-Cola 1.5L', 2, NULL, 'BEV-001', '4801234560021', 'Coca-Cola', 'bottle', 42.00, 62.00, 108, 25, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 11:18:35', NULL),
(12, 'Sprite 1.5L', 2, NULL, 'BEV-002', '4801234560022', 'Sprite', 'bottle', 42.00, 62.00, 90, 25, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-11 07:31:34', NULL),
(13, 'Nescafe Classic 100g', 2, NULL, 'BEV-003', '4801234560023', 'Nescafe', 'jar', 145.00, 195.00, 62, 15, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:38:27', NULL),
(14, 'Bottled Water 500ml', 2, NULL, 'BEV-004', '4801234560024', 'Nature Spring', 'bottle', 8.00, 15.00, 300, 50, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-11 07:31:34', NULL),
(15, 'C2 Green Tea 500ml', 2, NULL, 'BEV-005', '4801234560025', 'C2', 'bottle', 18.00, 25.00, 150, 30, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-11 07:31:34', NULL),
(16, 'Piattos Cheese 85g', 3, NULL, 'SNK-001', '4801234560026', 'Piattos', 'pack', 22.00, 32.00, 130, 25, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:38:00', NULL),
(17, 'Sky Flakes Crackers 250g', 3, NULL, 'SNK-002', '4801234560027', 'Sky Flakes', 'pack', 28.00, 42.00, 100, 20, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-11 07:31:34', NULL),
(18, 'Lucky Me Instant Noodles Beef', 3, NULL, 'SNK-003', '4801234560028', 'Lucky Me', 'pack', 9.50, 14.00, 360, 50, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:38:28', NULL),
(19, 'Oishi Prawn Crackers 60g', 3, NULL, 'SNK-004', '4801234560029', 'Oishi', 'pack', 18.00, 28.00, 130, 25, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-11 07:31:34', NULL),
(20, 'Chippy BBQ 110g', 3, NULL, 'SNK-005', '4801234560030', 'Chippy', 'pack', 20.00, 30.00, 110, 25, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-11 07:31:34', NULL),
(21, 'Pork Tocino 450g', 4, NULL, 'FRZ-001', '4801234560031', 'Purefoods', 'pack', 125.00, 168.00, 45, 15, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-11 07:31:34', NULL),
(22, 'Chicken Nuggets 200g', 4, NULL, 'FRZ-002', '4801234560032', 'Magnolia', 'pack', 85.00, 115.00, 55, 15, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-11 07:31:34', NULL),
(23, 'Beef Hotdog Regular 500g', 4, NULL, 'FRZ-003', '4801234560033', 'Tender Juicy', 'pack', 98.00, 135.00, 64, 15, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:40:25', NULL),
(24, 'Trash Bag XL 10pcs', 5, NULL, 'HSE-001', '4801234560034', 'Glad', 'pack', 55.00, 78.00, 69, 15, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:41:09', NULL),
(25, 'Paper Towel 2-ply', 5, NULL, 'HSE-002', '4801234560035', 'Scott', 'roll', 32.00, 48.00, 85, 20, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-11 07:31:34', NULL),
(26, 'Bath Soap 90g', 6, NULL, 'PRC-001', '4801234560036', 'Safeguard', 'bar', 28.00, 42.00, 189, 30, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:40:25', NULL),
(27, 'Shampoo 200ml', 6, NULL, 'PRC-002', '4801234560037', 'Head & Shoulders', 'bottle', 115.00, 158.00, 78, 20, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:38:22', NULL),
(28, 'Toothpaste 150ml', 6, NULL, 'PRC-003', '4801234560038', 'Colgate', 'tube', 68.00, 95.00, 89, 20, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:41:09', NULL),
(29, 'Deodorant Roll-on 40ml', 6, NULL, 'PRC-004', '4801234560039', 'Rexona', 'piece', 72.00, 98.00, 66, 15, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:41:09', NULL),
(30, 'Ballpen Blue (12pcs)', 7, NULL, 'OFS-001', '4801234560040', 'HBW', 'box', 42.00, 65.00, 50, 10, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:40:25', NULL),
(31, 'Bond Paper A4 (500 sheets)', 7, NULL, 'OFS-002', '4801234560041', 'Hard Copy', 'ream', 215.00, 285.00, 40, 10, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-11 07:31:34', NULL),
(32, 'Tasty Bread Loaf', 8, NULL, 'BKR-001', '4801234560042', 'Gardenia', 'loaf', 62.00, 82.00, 72, 15, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:38:27', NULL),
(33, 'Pandesal (10pcs)', 8, NULL, 'BKR-002', '4801234560043', 'Local', 'pack', 35.00, 50.00, 10, 15, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:38:21', NULL),
(34, 'Ensaymada Classic', 8, NULL, 'BKR-003', '4801234560044', 'Goldilocks', 'piece', 25.00, 38.00, 28, 10, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 11:18:35', NULL),
(35, 'Fresh Milk 1L', 9, NULL, 'DRY-001', '4801234560045', 'Nestle', 'carton', 85.00, 115.00, 51, 15, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:38:27', NULL),
(36, 'Quickmelt Cheese 200g', 9, NULL, 'DRY-002', '4801234560046', 'Eden', 'pack', 72.00, 98.00, 55, 15, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-11 07:31:34', NULL),
(37, 'Butter Salted 225g', 9, NULL, 'DRY-003', '4801234560047', 'Anchor', 'bar', 145.00, 195.00, 37, 10, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:38:25', NULL),
(38, 'Eggs Medium (12pcs)', 9, NULL, 'DRY-004', '4801234560048', 'Fresh', 'tray', 82.00, 108.00, 10, 15, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:38:21', NULL),
(39, 'Laundry Detergent 1kg', 10, NULL, 'CLN-001', '4801234560049', 'Tide', 'pack', 115.00, 155.00, 82, 20, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:38:30', NULL),
(40, 'Bleach 1L', 10, NULL, 'CLN-002', '4801234560050', 'Zonrox', 'bottle', 42.00, 62.00, 71, 20, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:38:06', NULL),
(41, 'Dishwashing Liquid 500ml', 10, NULL, 'CLN-003', '4801234560051', 'Joy', 'bottle', 68.00, 95.00, 87, 20, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-12 09:40:48', NULL),
(42, 'Floor Cleaner 1L', 10, NULL, 'CLN-004', '4801234560052', 'Mr. Clean', 'bottle', 78.00, 108.00, 50, 15, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-11 07:31:34', NULL),
(43, 'Fabric Conditioner 900ml', 10, NULL, 'CLN-005', '4801234560053', 'Downy', 'pouch', 95.00, 132.00, 60, 15, NULL, NULL, 'Active', NULL, '2026-05-11 07:31:34', '2026-05-11 07:31:34', NULL),
(44, 'Test Product XYZ', NULL, '', NULL, NULL, '', 'pcs', 0.00, 99.50, 25, 10, NULL, '', 'Active', 1, '2026-05-11 07:38:50', '2026-05-11 09:29:02', '2026-05-11 09:29:02'),
(45, 'Auto Code Test Product', NULL, '', 'GEN-0001', '4804086253703', '', 'pcs', 0.00, 149.96, 0, 10, NULL, '', 'Active', 1, '2026-05-11 08:04:42', '2026-05-11 09:49:17', '2026-05-11 09:49:17'),
(46, 'Upload Test Item', NULL, '', 'GEN-0002', '4804859648910', '', 'pcs', 0.00, 99.99, 0, 10, NULL, '', 'Active', 1, '2026-05-11 09:32:34', '2026-05-11 09:49:05', '2026-05-11 09:49:05'),
(47, 'Coffe', 1, '', 'GRO-0001', '4807780335980', 'Silver Swan', 'pcs', 10.00, 15.00, 0, 10, NULL, '', 'Active', 3, '2026-05-11 09:51:54', '2026-05-11 09:52:06', '2026-05-11 09:52:06'),
(48, 'Test Product XYZ', NULL, '', NULL, NULL, NULL, 'pcs', 0.00, 1.00, 17, 10, NULL, '', 'Active', 1, '2026-05-11 23:23:47', '2026-05-12 09:38:11', '2026-05-11 17:27:37');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_group` varchar(50) DEFAULT 'general',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `setting_key`, `setting_value`, `setting_group`, `updated_at`) VALUES
(1, 'store_name', 'RetailTracker Pro', 'general', '2026-05-11 07:31:34'),
(2, 'store_address', '123 Rizal Avenue, Manila, Philippines', 'general', '2026-05-11 07:31:34'),
(3, 'store_phone', '(02) 8123-4567', 'general', '2026-05-11 07:31:34'),
(4, 'store_email', 'info@retailtracker.ph', 'general', '2026-05-11 07:31:34'),
(5, 'currency', 'PHP', 'general', '2026-05-11 07:31:34'),
(6, 'currency_symbol', '₱', 'general', '2026-05-11 07:40:30'),
(7, 'tax_rate', '12', 'general', '2026-05-11 07:40:30'),
(8, 'low_stock_default', '10', 'general', '2026-05-11 07:40:30'),
(9, 'pagination_limit', '25', 'system', '2026-05-11 07:31:34'),
(10, 'date_format', 'M d, Y', 'system', '2026-05-11 07:31:34'),
(11, 'time_format', 'h:i A', 'system', '2026-05-11 07:31:34'),
(12, 'timezone', 'Asia/Manila', 'system', '2026-05-11 07:31:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `full_name`, `phone`, `role`, `avatar`, `is_active`, `force_password_change`, `password_changed_at`, `login_attempts`, `locked_until`, `last_login`, `last_activity`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'admin@retailtracker.ph', '$2y$10$3bprlCAqVggZp/.GNo4BhOu/.yOmupOLBm.o8vwcMGLVIhpHP4J9O', 'Owner', '09171234567', 'Admin', NULL, 1, 0, NULL, 5, '2026-05-11 10:44:58', '2026-05-11 10:06:36', '2026-05-11 10:24:00', NULL, '2026-05-11 07:31:34', '2026-05-11 23:28:42', NULL),
(2, 'manager', 'manager@retailtracker.ph', '$2y$10$uN/tgUcRWCH1v1WRh8eFdeS1dU3PDKxByi7sxdR2PasLeWbQWd7o6', 'Aika', '09182345678', 'Manager', NULL, 1, 0, NULL, 0, NULL, '2026-05-11 10:28:09', '2026-05-11 10:28:09', NULL, '2026-05-11 07:31:34', '2026-05-11 23:28:42', NULL),
(3, 'cashier1', 'cashier@retailtracker.ph', '$2y$10$ILzbaxuls054ikGKBPFNheAsepck69EAGVEJFxAN2zy636LNkn7jS', 'Jeszel', '09193456789', 'Cashier', NULL, 1, 0, NULL, 0, NULL, '2026-05-11 10:28:47', '2026-05-11 10:28:47', NULL, '2026-05-11 07:31:34', '2026-05-11 23:28:42', NULL),
(4, 'inventory1', 'inventory@retailtracker.ph', '$2y$10$YQcqDSDc6uCGKXTgo2BVieVKDmUcaGXWKdaM4TD7WcWUEvge..woW', 'Mary', '09204567890', 'Inventory Staff', NULL, 1, 0, NULL, 0, NULL, '2026-05-11 09:20:00', '2026-05-11 09:20:01', NULL, '2026-05-11 07:31:34', '2026-05-11 23:28:42', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_log_user` (`user_id`),
  ADD KEY `idx_log_action` (`action`),
  ADD KEY `idx_log_module` (`module`),
  ADD KEY `idx_log_date` (`created_at`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`movement_id`),
  ADD KEY `idx_inv_product` (`product_id`),
  ADD KEY `idx_inv_type` (`type`),
  ADD KEY `idx_inv_date` (`created_at`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_ord_number` (`order_number`),
  ADD KEY `idx_ord_date` (`order_date`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `idx_oi_order` (`order_id`),
  ADD KEY `idx_oi_product` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `idx_pay_order` (`order_id`),
  ADD KEY `received_by` (`received_by`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `idx_sku` (`sku_code`),
  ADD UNIQUE KEY `idx_barcode` (`barcode`),
  ADD KEY `idx_prod_name` (`product_name`),
  ADD KEY `idx_prod_cat` (`category_id`),
  ADD KEY `idx_prod_status` (`status`),
  ADD KEY `idx_prod_stock` (`stock_quantity`,`low_stock_threshold`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_user_role` (`role`),
  ADD KEY `idx_user_active` (`is_active`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `movement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `inventory_movements_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_movements_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`received_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
