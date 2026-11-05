-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 01, 2026 at 07:45 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ltmnm_final_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `account_id` bigint UNSIGNED NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('user','seller','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `status` enum('active','locked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `email`, `password_hash`, `role`, `status`, `is_verified`, `created_at`, `updated_at`) VALUES
(3, 'peterdin2017@gmail.com', '$2y$12$9espykIVlK3URuj1a..n/uSp/aD4g87l/JgGJhozm44zfQrFxMGmS', 'user', 'active', 1, '2026-10-10 21:42:03', '2026-10-16 21:20:59'),
(5, 'peterdin2017@gmail.com', '$2y$12$ZRnntMHXsIS78wIPlx4xQegVfOQtWMMFJ1s8PUzSe/pAo/SmO9zjy', 'seller', 'active', 1, '2026-10-11 04:37:03', '2026-10-11 08:26:50'),
(9, 'peterdin2017@gmail.com', '$2y$12$4pTqvm/V81WiitLY8MQh/uMI05AbeIwebdOxXt6xk8QrzUdnb5HnS', 'admin', 'active', 1, '2026-10-11 06:35:22', '2026-11-01 03:36:11'),
(10, 'peterdin2019@gmail.com', '$2y$12$nHoyX/lXEk5JQLXqditXl.IqUj4z/JP7zQ1o03O/u0MmpxleP7Y7a', 'seller', 'active', 0, '2026-10-12 09:12:54', '2026-10-12 09:12:54'),
(12, 'peterdin2029@gmail.com', '$2y$12$t96LmS4r5iE3lc0M2Mxb2eydO.I0e/c.YatszSEBcbB2f837BWPuq', 'user', 'active', 1, '2026-10-12 09:33:47', '2026-10-12 23:32:54'),
(13, 'peterdin2029@gmail.com', '$2y$12$96QuhLQqLFXEAAWk15nNduvmXnOpcQwxGh2mVY0bTYPMs2nnGH6HG', 'seller', 'active', 1, '2026-10-12 23:33:46', '2026-10-12 23:34:26'),
(16, 'peterdin2016@gmail.com', '$2y$12$wYZv1GdcFUHW0nBjDe3pzO2Tv.5H2Zui8Nxe8gTIoltBcWrslI5nm', 'seller', 'active', 1, '2026-10-18 02:16:21', '2026-10-19 22:49:22'),
(17, 'peterdin2016@gmail.com', '$2y$12$.jDQZIAT1.J12CXxsNvg2eD2ARYQiTeSj2DJprxt/Dc5ucdfr4MuK', 'user', 'locked', 1, '2026-10-19 21:18:30', '2026-11-01 03:32:11'),
(33, 'ledinhtangood@gmail.com', '$2y$12$xQmoQe/mYD3Ot0iCThrzNeQteGrSbgRRoDvYuoKZSEYolrYTY3yfG', 'seller', 'active', 1, '2026-10-30 06:58:06', '2026-10-30 12:56:53'),
(34, 'peterdin2022@gmail.com', '$2y$12$zs4rvjtSNKI4HiTYcAcgi.Sv23jRJki.6Rze2VecBTsV0qZK4Fd9i', 'user', 'active', 0, '2026-10-31 11:48:33', '2026-10-31 11:48:33'),
(35, 'peterdin2022@gmail.com', '$2y$12$qrxugxeTg0qMfg4A3/wxUe/zLhapwVtmiw2bH.9sbFTEFnZGXE6GG', 'seller', 'locked', 1, '2026-10-31 13:43:54', '2026-11-01 03:32:19');

-- --------------------------------------------------------

--
-- Table structure for table `account_saved_vouchers`
--

CREATE TABLE `account_saved_vouchers` (
  `saved_voucher_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `voucher_type` enum('seller','platform') COLLATE utf8mb4_unicode_ci NOT NULL,
  `voucher_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_saved_vouchers`
--

INSERT INTO `account_saved_vouchers` (`saved_voucher_id`, `account_id`, `voucher_type`, `voucher_id`, `created_at`, `updated_at`) VALUES
(4, 3, 'seller', 2, '2026-10-30 16:26:39', '2026-10-30 16:26:39'),
(6, 3, 'seller', 3, '2026-10-31 12:56:26', '2026-10-31 12:56:26');

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `address_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `recipient_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `district` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ward` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`address_id`, `account_id`, `recipient_name`, `phone`, `province`, `district`, `ward`, `street_address`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 3, 'Đinh Nguyễn Hoàng Phúc', '0974039906', 'Hồ Chí Minh', 'Bình Chánh', 'Vĩnh Lộc B', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, Huyện Bình Chánh, Thành Phố Hồ Chí Minh', 1, '2026-10-16 18:17:16', '2026-10-16 18:17:16'),
(2, 3, 'Đinh Nguyễn Hoàng Phúc', '0974039906', 'Hồ Chí Minh', 'Tân Phú', 'Tây Thạnh', '140 Lê Trọng Tấn, Tây Thạnh, Hồ Chí Minh', 0, '2026-10-16 21:36:34', '2026-10-16 21:36:34'),
(3, 3, 'Lê Trúc Ngân', '0363638620', 'Hồ Chí Minh', 'Bình Chánh', 'Lê Minh Xuân', 'Bình Lợi', 0, '2026-10-30 18:01:36', '2026-10-30 18:01:36');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `cart_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`cart_id`, `account_id`, `created_at`, `updated_at`) VALUES
(1, 3, '2026-10-15 21:54:04', '2026-10-15 21:54:04'),
(2, 17, '2026-10-23 10:39:30', '2026-10-23 10:39:30');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `item_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`cart_item_id`, `cart_id`, `product_id`, `variant_id`, `quantity`, `item_key`, `created_at`, `updated_at`) VALUES
(35, 1, 41, 29, 2, '41:29', '2026-10-31 12:14:27', '2026-10-31 12:55:06'),
(37, 1, 40, 27, 1, '40:27', '2026-10-31 12:55:46', '2026-10-31 12:55:46'),
(38, 1, 44, NULL, 1, '44:simple', '2026-11-01 04:26:39', '2026-11-01 04:26:39'),
(39, 1, 45, NULL, 1, '45:simple', '2026-11-01 04:26:41', '2026-11-01 04:26:41'),
(40, 1, 46, NULL, 1, '46:simple', '2026-11-01 04:26:41', '2026-11-01 04:26:41');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `category_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `parent_id`, `category_name`, `created_at`, `updated_at`, `slug`) VALUES
(1, NULL, 'Điện thoại & Phụ kiện', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-1'),
(2, NULL, 'Máy tính & Laptop', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-2'),
(3, NULL, 'Điện tử & Thiết bị số', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-3'),
(4, NULL, 'Nhà cửa & Đời sống', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-4'),
(5, NULL, 'Thời trang nữ', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-5'),
(6, NULL, 'Thời trang nam', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-6'),
(7, NULL, 'Sắc đẹp & Chăm sóc cá nhân', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-7'),
(8, NULL, 'Mẹ & Bé', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-8'),
(9, NULL, 'Thể thao & Du lịch', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-9'),
(10, NULL, 'Ô tô & Xe máy', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-10'),
(11, NULL, 'Sách & Văn phòng phẩm', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-11'),
(12, NULL, 'Thực phẩm & Đồ uống', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-12'),
(13, 1, 'Điện thoại', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-13'),
(14, 1, 'Máy tính bảng', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-14'),
(15, 1, 'Ốp lưng & Bao da', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-15'),
(16, 1, 'Cáp, sạc & Pin dự phòng', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-16'),
(17, 1, 'Tai nghe', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-17'),
(18, 2, 'Laptop', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-18'),
(19, 2, 'Máy tính để bàn', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-19'),
(20, 2, 'Màn hình', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-20'),
(21, 2, 'Bàn phím & Chuột', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-21'),
(22, 2, 'Linh kiện máy tính', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-22'),
(23, 3, 'Tivi', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-23'),
(24, 3, 'Loa', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-24'),
(25, 3, 'Máy ảnh', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-25'),
(26, 3, 'Thiết bị gia dụng nhỏ', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-26'),
(27, 4, 'Đồ dùng nhà bếp', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-27'),
(28, 4, 'Trang trí nhà cửa', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-28'),
(29, 4, 'Chăn ga gối nệm', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-29'),
(30, 4, 'Nội thất', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-30'),
(31, 4, 'Dụng cụ vệ sinh', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-31'),
(32, 5, 'Áo nữ', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-32'),
(33, 5, 'Đầm & Váy', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-33'),
(34, 5, 'Quần nữ', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-34'),
(35, 5, 'Giày nữ', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-35'),
(36, 5, 'Túi xách nữ', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-36'),
(37, 6, 'Áo nam', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-37'),
(38, 6, 'Quần nam', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-38'),
(39, 6, 'Giày nam', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-39'),
(40, 6, 'Túi & Ví nam', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-40'),
(41, 6, 'Phụ kiện thời trang', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-41'),
(42, 7, 'Chăm sóc da', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-42'),
(43, 7, 'Trang điểm', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-43'),
(44, 7, 'Chăm sóc tóc', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-44'),
(45, 7, 'Nước hoa', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-45'),
(46, 7, 'Chăm sóc cơ thể', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-46'),
(47, 8, 'Tã & Đồ vệ sinh', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-47'),
(48, 8, 'Sữa & Thực phẩm cho bé', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-48'),
(49, 8, 'Quần áo trẻ em', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-49'),
(50, 8, 'Đồ chơi', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-50'),
(51, 8, 'Đồ dùng cho mẹ', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-51'),
(52, 9, 'Dụng cụ thể thao', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-52'),
(53, 9, 'Quần áo thể thao', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-53'),
(54, 9, 'Vali & Túi du lịch', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-54'),
(55, 9, 'Đồ cắm trại', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-55'),
(56, 10, 'Phụ kiện ô tô', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-56'),
(57, 10, 'Phụ tùng xe máy', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-57'),
(58, 10, 'Mũ bảo hiểm', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-58'),
(59, 10, 'Chăm sóc xe', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-59'),
(60, 11, 'Sách', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-60'),
(61, 11, 'Dụng cụ học tập', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-61'),
(62, 11, 'Văn phòng phẩm', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-62'),
(63, 12, 'Đồ ăn khô', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-63'),
(64, 12, 'Bánh kẹo', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-64'),
(65, 12, 'Đồ uống', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-65'),
(66, 12, 'Thực phẩm đóng hộp', '2026-10-13 08:16:58', '2026-10-13 08:16:58', 'category-66'),
(67, 1, 'Ốp lưng', '2026-10-28 17:15:04', '2026-10-28 17:15:04', 'category-67'),
(68, NULL, 'Đồng Hồ', '2026-10-28 17:15:04', '2026-10-28 17:15:04', 'category-68');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `conversation_id` bigint UNSIGNED NOT NULL,
  `customer_account_id` bigint UNSIGNED NOT NULL,
  `seller_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`conversation_id`, `customer_account_id`, `seller_id`, `product_id`, `status`, `last_message_at`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 11, 'open', '2026-11-01 04:26:23', '2026-10-23 08:42:03', '2026-11-01 04:26:23'),
(2, 17, 2, NULL, 'open', '2026-10-23 09:47:33', '2026-10-23 09:47:31', '2026-10-23 09:47:33');

-- --------------------------------------------------------

--
-- Table structure for table `customer_notifications`
--

CREATE TABLE `customer_notifications` (
  `notification_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `support_request_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_notifications`
--

INSERT INTO `customer_notifications` (`notification_id`, `account_id`, `support_request_id`, `title`, `message`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 'Cập nhật yêu cầu hỗ trợ #1', 'Đã xong', '2026-10-30 07:58:41', '2026-10-30 07:58:27', '2026-10-30 07:58:41'),
(2, 3, 1, 'Cập nhật yêu cầu hỗ trợ #1', 'Đã xong', '2026-10-30 13:07:43', '2026-10-30 12:58:36', '2026-10-30 13:07:43'),
(3, 3, 2, 'Cập nhật yêu cầu hỗ trợ #2', 'Kiểm tra ở phần đơn mua nhé', '2026-10-30 13:09:15', '2026-10-30 13:09:09', '2026-10-30 13:09:15'),
(4, 3, 3, 'Cập nhật yêu cầu hỗ trợ #3', 'Yes sơ', '2026-10-30 13:16:52', '2026-10-30 13:16:47', '2026-10-30 13:16:52'),
(5, 3, 4, 'Cập nhật yêu cầu hỗ trợ #4', '132', '2026-10-30 13:27:24', '2026-10-30 13:19:38', '2026-10-30 13:27:24'),
(6, 3, 5, 'Cập nhật yêu cầu hỗ trợ #5', '12345', '2026-10-31 10:06:01', '2026-10-31 08:04:13', '2026-10-31 10:06:01'),
(7, 3, 6, 'Cập nhật yêu cầu hỗ trợ #6', '123', '2026-10-31 10:06:01', '2026-10-31 08:13:19', '2026-10-31 10:06:01');

-- --------------------------------------------------------

--
-- Table structure for table `customer_support_requests`
--

CREATE TABLE `customer_support_requests` (
  `request_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED DEFAULT NULL,
  `requester_role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `topic` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachments` json DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_reply` text COLLATE utf8mb4_unicode_ci,
  `handled_by` bigint UNSIGNED DEFAULT NULL,
  `handled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_support_requests`
--

INSERT INTO `customer_support_requests` (`request_id`, `account_id`, `requester_role`, `topic`, `message`, `attachments`, `status`, `admin_reply`, `handled_by`, `handled_at`, `created_at`, `updated_at`) VALUES
(1, 3, 'user', 'product', '1234567890', NULL, 'resolved', 'Đã xong', 9, '2026-10-30 12:58:36', '2026-10-30 07:26:38', '2026-10-30 12:58:36'),
(2, 3, 'user', 'shipping', 'Làm thế nào để kt ?', '[\"support-requests/0aN0r6iynooolHS3x8KwKgYKEOLihzILz0YKtX5y.png\"]', 'resolved', 'Kiểm tra ở phần đơn mua nhé', 9, '2026-10-30 13:09:09', '2026-10-30 13:08:19', '2026-10-30 13:09:09'),
(3, 3, 'user', 'account', 'Tôi k nhận được hàng', NULL, 'resolved', 'Yes sơ', 9, '2026-10-30 13:16:47', '2026-10-30 13:16:33', '2026-10-30 13:16:47'),
(4, 3, 'user', 'product', 'Chưa nhận được hàng nhé', NULL, 'resolved', '132', 9, '2026-10-30 13:19:38', '2026-10-30 13:19:29', '2026-10-30 13:19:38'),
(5, 3, 'user', 'account', 'trqwytryqtqwyt', '[\"support-requests/tIEY2a3hvf7sFSKzIbd15gjFrlvvrlThy4ZKhMv4.png\"]', 'resolved', '12345', 9, '2026-10-31 08:04:13', '2026-10-31 08:03:46', '2026-10-31 08:04:13'),
(6, 3, 'user', 'other', '12345jthgkeajkhkfjhg', NULL, 'resolved', '123', 9, '2026-10-31 08:13:19', '2026-10-31 08:12:53', '2026-10-31 08:13:19');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` bigint UNSIGNED NOT NULL,
  `conversation_id` bigint UNSIGNED NOT NULL,
  `sender_account_id` bigint UNSIGNED NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `conversation_id`, `sender_account_id`, `body`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'alo', '2026-10-23 08:54:43', '2026-10-23 08:42:08', '2026-10-23 08:54:43'),
(2, 1, 5, '123', '2026-10-23 08:54:52', '2026-10-23 08:54:47', '2026-10-23 08:54:52'),
(3, 1, 3, '132', '2026-10-23 09:46:37', '2026-10-23 09:46:35', '2026-10-23 09:46:37'),
(4, 1, 5, '?', '2026-10-23 09:46:46', '2026-10-23 09:46:45', '2026-10-23 09:46:46'),
(5, 2, 17, '123', '2026-10-23 09:47:48', '2026-10-23 09:47:33', '2026-10-23 09:47:48'),
(7, 1, 3, '123', '2026-10-29 05:02:44', '2026-10-28 17:48:42', '2026-10-29 05:02:44'),
(8, 1, 3, '123', '2026-10-30 15:50:04', '2026-10-30 15:49:52', '2026-10-30 15:50:04'),
(9, 1, 5, '123', '2026-10-30 16:21:27', '2026-10-30 16:21:23', '2026-10-30 16:21:27'),
(10, 1, 5, '456', '2026-10-30 16:21:42', '2026-10-30 16:21:38', '2026-10-30 16:21:42'),
(11, 1, 5, '567', '2026-10-30 16:22:16', '2026-10-30 16:22:13', '2026-10-30 16:22:16'),
(12, 1, 5, '567', '2026-10-30 16:22:55', '2026-10-30 16:22:37', '2026-10-30 16:22:55'),
(13, 1, 3, 'Hé lô', '2026-10-31 00:13:27', '2026-10-30 18:02:10', '2026-10-31 00:13:27'),
(14, 1, 5, '132', '2026-10-31 02:14:07', '2026-10-31 02:14:00', '2026-10-31 02:14:07'),
(15, 1, 3, '123', '2026-10-31 07:58:11', '2026-10-31 07:40:03', '2026-10-31 07:58:11'),
(16, 1, 5, '123', '2026-10-31 07:58:25', '2026-10-31 07:58:13', '2026-10-31 07:58:25'),
(17, 1, 3, '123', '2026-10-31 07:58:33', '2026-10-31 07:58:31', '2026-10-31 07:58:33'),
(18, 1, 3, '123', '2026-10-31 08:07:12', '2026-10-31 08:07:10', '2026-10-31 08:07:12'),
(19, 1, 5, '123', '2026-11-01 04:26:21', '2026-11-01 03:44:16', '2026-11-01 04:26:21'),
(20, 1, 3, '123', NULL, '2026-11-01 04:26:23', '2026-11-01 04:26:23');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_10_09_062533_create_accounts_table', 1),
(2, '2026_10_09_062534_create_users_table', 1),
(3, '2026_10_09_062535_create_sellers_table', 1),
(4, '2026_10_09_062536_create_otps_table', 1),
(5, '2026_10_13_072204_create_categories_table', 2),
(6, '2026_10_13_072205_create_products_table', 2),
(7, '2026_10_13_072206_create_product_images_table', 2),
(8, '2026_10_13_072207_create_product_variants_table', 2),
(9, '2026_10_13_072208_create_product_reports_table', 2),
(10, '2026_10_16_044932_create_carts_table', 3),
(11, '2026_10_16_044933_create_cart_items_table', 3),
(12, '2026_10_17_011059_create_addresses_table', 4),
(13, '2026_10_22_122853_create_orders_table', 5),
(14, '2026_10_22_122947_create_order_items_table', 5),
(15, '2026_10_23_150911_create_conversations_table', 6),
(16, '2026_10_23_150937_create_messages_table', 6),
(17, '2026_10_21_000001_create_reviews_table', 7),
(18, '2026_10_24_170406_create_review_images_table', 8),
(19, '2026_10_26_144743_create_order_return_requests_table', 9),
(20, '2026_10_26_155007_create_seller_vouchers_table', 10),
(21, '2026_10_26_214317_create_account_saved_vouchers_table', 11),
(22, '2026_10_26_225205_create_platform_vouchers_table', 12),
(23, '2026_10_30_141746_create_customer_support_requests_table', 13),
(24, '2026_10_30_144201_create_customer_notifications_table', 14);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` bigint UNSIGNED NOT NULL,
  `order_code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `seller_id` bigint UNSIGNED NOT NULL,
  `shipping_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` enum('cod','vnpay','bank_transfer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cod',
  `payment_status` enum('pending','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `order_status` enum('pending','processing','shipping','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `subtotal` decimal(14,2) NOT NULL DEFAULT '0.00',
  `shipping_fee` decimal(14,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `note` text COLLATE utf8mb4_unicode_ci,
  `cancellation_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_code`, `account_id`, `seller_id`, `shipping_name`, `shipping_phone`, `shipping_address`, `payment_method`, `payment_status`, `order_status`, `subtotal`, `shipping_fee`, `discount_amount`, `total_amount`, `note`, `cancellation_reason`, `created_at`, `updated_at`) VALUES
(1, 'ORD-261022-HQ3RMCEZ', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, Huyện Bình Chánh, Thành Phố Hồ Chí Minh, Vĩnh Lộc B, Bình Chánh, Hồ Chí Minh', 'cod', 'pending', 'completed', 19500000.00, 30000.00, 0.00, 19530000.00, NULL, NULL, '2026-10-22 05:58:11', '2026-10-24 04:42:08'),
(2, 'DH-2-2026-10-22-8647', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, Huyện Bình Chánh, Thành Phố Hồ Chí Minh, Vĩnh Lộc B, Bình Chánh, Hồ Chí Minh', 'cod', 'pending', 'completed', 19500000.00, 30000.00, 0.00, 19530000.00, NULL, NULL, '2026-10-22 06:07:13', '2026-10-24 04:42:06'),
(3, 'DH-2-2026-10-22-1679', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, Huyện Bình Chánh, Thành Phố Hồ Chí Minh, Vĩnh Lộc B, Bình Chánh, Hồ Chí Minh', 'cod', 'pending', 'completed', 29070000.00, 30000.00, 0.00, 29100000.00, NULL, NULL, '2026-10-22 06:07:28', '2026-10-24 04:42:04'),
(4, 'DH-2-2026-10-24-3903', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '140 Lê Trọng Tấn, Tây Thạnh, Hồ Chí Minh, Tây Thạnh, Tân Phú, Hồ Chí Minh', 'cod', 'pending', 'completed', 136500000.00, 30000.00, 0.00, 136530000.00, '123', NULL, '2026-10-23 20:24:01', '2026-10-24 04:42:02'),
(5, 'DH-2-2026-10-26-9976', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, Huyện Bình Chánh, Thành Phố Hồ Chí Minh, Vĩnh Lộc B, Bình Chánh, Hồ Chí Minh', 'cod', 'pending', 'completed', 39000000.00, 30000.00, 0.00, 39030000.00, NULL, NULL, '2026-10-26 00:05:31', '2026-10-26 00:06:06'),
(6, 'DH-2-2026-10-26-8397', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, Huyện Bình Chánh, Thành Phố Hồ Chí Minh, Vĩnh Lộc B, Bình Chánh, Hồ Chí Minh', 'cod', 'pending', 'completed', 19500000.00, 30000.00, 0.00, 19530000.00, NULL, NULL, '2026-10-26 00:13:57', '2026-10-26 00:14:15'),
(7, 'DH-2-2026-10-26-5209', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, Huyện Bình Chánh, Thành Phố Hồ Chí Minh, Vĩnh Lộc B, Bình Chánh, Hồ Chí Minh', 'cod', 'pending', 'completed', 19800000.00, 30000.00, 0.00, 19830000.00, NULL, NULL, '2026-10-26 00:21:41', '2026-10-26 07:36:04'),
(8, 'DH-2-2026-10-26-4857', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, Huyện Bình Chánh, Thành Phố Hồ Chí Minh, Vĩnh Lộc B, Bình Chánh, Hồ Chí Minh', 'cod', 'pending', 'completed', 787500000.00, 30000.00, 0.00, 787530000.00, NULL, NULL, '2026-10-26 07:35:29', '2026-10-26 07:36:02'),
(9, 'DH-2-2026-10-26-6726', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, Huyện Bình Chánh, Thành Phố Hồ Chí Minh, Vĩnh Lộc B, Bình Chánh, Hồ Chí Minh', 'cod', 'pending', 'cancelled', 526500000.00, 30000.00, 0.00, 526530000.00, NULL, NULL, '2026-10-26 08:18:01', '2026-10-26 08:18:45'),
(10, 'DH-2-2026-10-26-5081', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, Huyện Bình Chánh, Thành Phố Hồ Chí Minh, Vĩnh Lộc B, Bình Chánh, Hồ Chí Minh', 'cod', 'pending', 'cancelled', 526500000.00, 30000.00, 0.00, 526530000.00, NULL, 'không bán', '2026-10-26 08:20:10', '2026-10-26 08:26:08'),
(11, 'DH-2-2026-10-26-5878', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, Huyện Bình Chánh, Thành Phố Hồ Chí Minh, Vĩnh Lộc B, Bình Chánh, Hồ Chí Minh', 'cod', 'pending', 'completed', 376200000.00, 30000.00, 0.00, 376230000.00, NULL, NULL, '2026-10-26 08:35:57', '2026-10-26 08:36:11'),
(12, 'DH-2-2026-10-26-8817', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, Huyện Bình Chánh, Thành Phố Hồ Chí Minh, Vĩnh Lộc B, Bình Chánh, Hồ Chí Minh', 'cod', 'paid', 'completed', 19500000.00, 30000.00, 0.00, 19530000.00, NULL, NULL, '2026-10-26 08:39:28', '2026-10-26 08:39:55'),
(13, 'DH-2-2026-10-26-1671', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, Huyện Bình Chánh, Thành Phố Hồ Chí Minh, Vĩnh Lộc B, Bình Chánh, Hồ Chí Minh', 'cod', 'paid', 'completed', 1930500000.00, 30000.00, 4000000.00, 1926530000.00, NULL, NULL, '2026-10-26 09:13:50', '2026-10-26 09:14:30'),
(14, 'DH-2-2026-10-26-8805', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '140 Lê Trọng Tấn, Tây Thạnh, Hồ Chí Minh, Tây Thạnh, Tân Phú, Hồ Chí Minh', 'cod', 'paid', 'completed', 39000000.00, 30000.00, 4000000.00, 35030000.00, NULL, NULL, '2026-10-26 15:29:53', '2026-10-30 17:58:28'),
(15, 'DH-2-2026-10-26-8294', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, Huyện Bình Chánh, Thành Phố Hồ Chí Minh, Vĩnh Lộc B, Bình Chánh, Hồ Chí Minh', 'vnpay', 'paid', 'completed', 19500000.00, 30000.00, 4400000.00, 15130000.00, NULL, NULL, '2026-10-26 16:51:47', '2026-10-30 17:58:18'),
(16, 'DH-2-2026-10-27-2868', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, Huyện Bình Chánh, Thành Phố Hồ Chí Minh, Vĩnh Lộc B, Bình Chánh, Hồ Chí Minh', 'cod', 'paid', 'completed', 19500000.00, 30000.00, 0.00, 19530000.00, NULL, NULL, '2026-10-26 17:02:36', '2026-10-30 17:38:24'),
(22, 'DH-2-2026-10-31-3622', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, Huyện Bình Chánh, Thành Phố Hồ Chí Minh, Vĩnh Lộc B, Bình Chánh, Hồ Chí Minh', 'cod', 'pending', 'cancelled', 19500000.00, 30000.00, 0.00, 19530000.00, NULL, NULL, '2026-10-31 02:12:46', '2026-10-31 02:13:01'),
(23, 'DH-2-2026-10-31-8659', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, Huyện Bình Chánh, Thành Phố Hồ Chí Minh, Vĩnh Lộc B, Bình Chánh, Hồ Chí Minh', 'cod', 'pending', 'pending', 25990000.00, 30000.00, 0.00, 26020000.00, NULL, NULL, '2026-10-31 12:01:42', '2026-10-31 12:01:42'),
(24, 'DH-2-2026-10-31-4282', 3, 2, 'Đinh Nguyễn Hoàng Phúc', '0974039906', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, Huyện Bình Chánh, Thành Phố Hồ Chí Minh, Vĩnh Lộc B, Bình Chánh, Hồ Chí Minh', 'cod', 'paid', 'completed', 25990000.00, 30000.00, 0.00, 26020000.00, NULL, NULL, '2026-10-31 12:02:18', '2026-10-31 12:03:25');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `variant_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(14,2) NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `total_price` decimal(14,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `seller_voucher_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_voucher_discount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `platform_voucher_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform_voucher_id` bigint UNSIGNED DEFAULT NULL,
  `platform_voucher_discount` decimal(14,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `product_variant_id`, `product_name`, `variant_name`, `price`, `quantity`, `total_price`, `created_at`, `updated_at`, `seller_voucher_code`, `seller_voucher_discount`, `platform_voucher_code`, `platform_voucher_id`, `platform_voucher_discount`) VALUES
(1, 1, 11, NULL, 'Điện thoại iPhone 15 Chính hãng VN/A', 'Màu Đen - 128GB', 19500000.00, 1, 19500000.00, '2026-10-22 05:58:11', '2026-10-26 08:31:34', NULL, 0.00, NULL, NULL, 0.00),
(2, 2, 11, NULL, 'Điện thoại iPhone 15 Chính hãng VN/A', 'Màu Đen - 128GB', 19500000.00, 1, 19500000.00, '2026-10-22 06:07:13', '2026-10-26 08:31:21', NULL, 0.00, NULL, NULL, 0.00),
(3, 3, NULL, NULL, 'Điện thoại Samsung Galaxy A55 5G', 'Tím Nhạt - 8GB/128GB', 9690000.00, 3, 29070000.00, '2026-10-22 06:07:28', '2026-10-24 09:53:51', NULL, 0.00, NULL, NULL, 0.00),
(4, 4, 11, NULL, 'Điện thoại iPhone 15 Chính hãng VN/A', 'Màu Đen - 128GB', 19500000.00, 7, 136500000.00, '2026-10-23 20:24:01', '2026-10-26 08:31:05', NULL, 0.00, NULL, NULL, 0.00),
(5, 5, 11, 19, 'Điện thoại iPhone 15 Chính hãng VN/A', 'Màu Đen - 128GB', 19500000.00, 2, 39000000.00, '2026-10-26 00:05:31', '2026-10-26 00:05:31', NULL, 0.00, NULL, NULL, 0.00),
(6, 6, 11, 19, 'Điện thoại iPhone 15 Chính hãng VN/A', 'Màu Đen - 128GB', 19500000.00, 1, 19500000.00, '2026-10-26 00:13:57', '2026-10-26 00:13:57', NULL, 0.00, NULL, NULL, 0.00),
(7, 7, 11, 21, 'Điện thoại iPhone 15 Chính hãng VN/A', 'Màu Hồng - 128GB', 19800000.00, 1, 19800000.00, '2026-10-26 00:21:41', '2026-10-26 00:21:41', NULL, 0.00, NULL, NULL, 0.00),
(8, 8, 11, 20, 'Điện thoại iPhone 15 Chính hãng VN/A', 'Màu Đen - 256GB', 22500000.00, 35, 787500000.00, '2026-10-26 07:35:29', '2026-10-26 07:35:29', NULL, 0.00, NULL, NULL, 0.00),
(9, 9, 11, 19, 'Điện thoại iPhone 15 Chính hãng VN/A', 'Màu Đen - 128GB', 19500000.00, 27, 526500000.00, '2026-10-26 08:18:01', '2026-10-26 08:18:01', NULL, 0.00, NULL, NULL, 0.00),
(10, 10, 11, 19, 'Điện thoại iPhone 15 Chính hãng VN/A', 'Màu Đen - 128GB', 19500000.00, 27, 526500000.00, '2026-10-26 08:20:10', '2026-10-26 08:20:10', NULL, 0.00, NULL, NULL, 0.00),
(11, 11, 11, 21, 'Điện thoại iPhone 15 Chính hãng VN/A', 'Màu Hồng - 128GB', 19800000.00, 19, 376200000.00, '2026-10-26 08:35:57', '2026-10-26 08:35:57', NULL, 0.00, NULL, NULL, 0.00),
(12, 12, 11, 19, 'Điện thoại iPhone 15 Chính hãng VN/A', 'Màu Đen - 128GB', 19500000.00, 1, 19500000.00, '2026-10-26 08:39:28', '2026-10-26 08:39:28', NULL, 0.00, NULL, NULL, 0.00),
(13, 13, 11, 19, 'Điện thoại iPhone 15 Chính hãng VN/A', 'Màu Đen - 128GB', 19500000.00, 99, 1930500000.00, '2026-10-26 09:13:50', '2026-10-26 09:13:50', NULL, 0.00, NULL, NULL, 0.00),
(14, 14, 11, 19, 'Điện thoại iPhone 15 Chính hãng VN/A', 'Màu Đen - 128GB', 19500000.00, 2, 39000000.00, '2026-10-26 15:29:53', '2026-10-26 15:29:53', NULL, 0.00, NULL, NULL, 0.00),
(15, 15, 11, 19, 'Điện thoại iPhone 15 Chính hãng VN/A', 'Màu Đen - 128GB', 19500000.00, 1, 19500000.00, '2026-10-26 16:51:47', '2026-10-26 16:51:47', 'Phuc', 4000000.00, 'SALE', 1, 400000.00),
(16, 16, 11, 19, 'Điện thoại iPhone 15 Chính hãng VN/A', 'Màu Đen - 128GB', 19500000.00, 1, 19500000.00, '2026-10-26 17:02:36', '2026-10-26 17:02:36', NULL, 0.00, NULL, NULL, 0.00),
(22, 22, 40, 25, 'Điện thoại Samsung Galaxy A55 5G', 'Màu Đen - 128GB', 19500000.00, 1, 19500000.00, '2026-10-31 02:12:46', '2026-10-31 02:12:46', NULL, 0.00, NULL, NULL, 0.00),
(23, 23, 41, 28, 'Điện thoại Asus ROG Phone 8', 'Đen Nhám (Phantom Black) - 16GB/256GB', 25990000.00, 1, 25990000.00, '2026-10-31 12:01:42', '2026-10-31 12:01:42', NULL, 0.00, NULL, NULL, 0.00),
(24, 24, 41, 29, 'Điện thoại Asus ROG Phone 8', 'Xám Xanh (Rebel Grey) - 16GB/256GB', 25990000.00, 1, 25990000.00, '2026-10-31 12:02:18', '2026-10-31 12:02:18', NULL, 0.00, NULL, NULL, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_return_requests`
--

CREATE TABLE `order_return_requests` (
  `return_request_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `request_type` enum('return','refund') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `seller_note` text COLLATE utf8mb4_unicode_ci,
  `handled_by` bigint UNSIGNED DEFAULT NULL,
  `handled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_return_requests`
--

INSERT INTO `order_return_requests` (`return_request_id`, `order_id`, `account_id`, `request_type`, `reason`, `description`, `status`, `seller_note`, `handled_by`, `handled_at`, `created_at`, `updated_at`) VALUES
(1, 8, 3, 'return', 'damaged', '123', 'rejected', NULL, 5, '2026-10-26 08:09:30', '2026-10-26 07:58:17', '2026-10-26 08:09:30'),
(2, 7, 3, 'return', 'other', NULL, 'approved', NULL, 5, '2026-10-26 08:10:08', '2026-10-26 08:09:57', '2026-10-26 08:10:08'),
(3, 16, 3, 'return', 'damaged', NULL, 'pending', NULL, NULL, NULL, '2026-10-31 02:13:20', '2026-10-31 02:13:20');

-- --------------------------------------------------------

--
-- Table structure for table `otps`
--

CREATE TABLE `otps` (
  `otp_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `account_type` enum('user','seller','admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `purpose` enum('register','reset_password') COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otps`
--

INSERT INTO `otps` (`otp_id`, `account_id`, `account_type`, `email`, `otp_hash`, `attempts`, `purpose`, `expires_at`, `used_at`, `created_at`, `updated_at`) VALUES
(3, 3, 'user', 'peterdin2017@gmail.com', '$2y$12$rdYPcivhl1WS9dxrrewjV.8a8Eu9un4J8QJWyNq06EuYd2kZ/Jx9q', 5, 'register', '2026-10-10 21:47:03', '2026-10-10 21:44:25', '2026-10-10 21:42:03', '2026-10-10 21:44:25'),
(4, 3, 'user', 'peterdin2017@gmail.com', '$2y$12$aEWalMfCMrYV3NhwXr4/W.I0BO0KBNJ6Od/RGSyJg/3c2v1Wgjl/O', 0, 'register', '2026-10-10 21:49:26', '2026-10-10 21:45:04', '2026-10-10 21:44:26', '2026-10-10 21:45:04'),
(5, 3, 'user', 'peterdin2017@gmail.com', '$2y$12$3b1/E.SSDF0vbTF4wwWvPedOPaYYxUjSksOGNX1k9B4F7GXhSCdIq', 0, 'register', '2026-10-10 21:50:04', '2026-10-10 21:45:09', '2026-10-10 21:45:04', '2026-10-10 21:45:09'),
(6, 3, 'user', 'peterdin2017@gmail.com', '$2y$12$jNA61UgaLMfdMQ3BZ0jSMey2nh.bOrUPlUhJ4ru5XV42WlRwyYqbe', 0, 'register', '2026-10-10 21:50:09', '2026-10-10 22:48:06', '2026-10-10 21:45:09', '2026-10-10 22:48:06'),
(7, 3, 'user', 'peterdin2017@gmail.com', '$2y$12$z5Wi.1mK.U81bP9fvfCnhuipZLpEIwPPe1hEGdqfLi2Pfymt5G9ci', 1, 'register', '2026-10-10 22:53:07', '2026-10-10 22:49:10', '2026-10-10 22:48:07', '2026-10-10 22:49:10'),
(17, 3, 'user', 'peterdin2017@gmail.com', '$2y$12$uDqKSNhbmTwnjS0cBwGPJekXos/pUZ.IIidlqsnxzAjZpjxR74rEi', 0, 'reset_password', '2026-10-11 00:37:02', '2026-10-11 04:39:52', '2026-10-11 00:32:02', '2026-10-11 04:39:52'),
(18, 5, 'seller', 'peterdin2017@gmail.com', '$2y$12$li.IkasjxP.W5e.6pp9g8eSBJYWsmK8WQEHJUVcJCsET/iO46ucxS', 0, 'register', '2026-10-11 04:42:04', '2026-10-11 04:37:25', '2026-10-11 04:37:04', '2026-10-11 04:37:25'),
(19, 5, 'seller', 'peterdin2017@gmail.com', '$2y$12$kAQJaDKZb8/w5acGf2lMk.xk3O4hLohs9S5s4L8kRMRV2o9UR7Yki', 0, 'reset_password', '2026-10-11 04:48:43', '2026-10-11 04:44:08', '2026-10-11 04:43:43', '2026-10-11 04:44:08'),
(20, 3, 'user', 'peterdin2017@gmail.com', '$2y$12$CT/irlL3aTi84kjmaVOTJ.8VdvU3zE4u9u6.yNBQWqDZJyAfYGPtm', 0, 'reset_password', '2026-10-11 04:51:14', '2026-10-11 04:46:34', '2026-10-11 04:46:14', '2026-10-11 04:46:34'),
(21, 3, 'user', 'peterdin2017@gmail.com', '$2y$12$p1j4Fwms/N0lshgOvYoEG.YC757dU0./VJ/3Z5eRQ6IvxdLkjjtLO', 0, 'reset_password', '2026-10-11 04:58:18', '2026-10-11 04:53:23', '2026-10-11 04:53:18', '2026-10-11 04:53:23'),
(22, 3, 'user', 'peterdin2017@gmail.com', '$2y$12$vCL6yzw7UbA/AT5xq0rOh.oBz9ZkLN3IbMmJQLGIP3Pcul6zh7SMi', 0, 'reset_password', '2026-10-11 04:58:23', '2026-10-11 04:53:56', '2026-10-11 04:53:23', '2026-10-11 04:53:56'),
(23, 5, 'seller', 'peterdin2017@gmail.com', '$2y$12$wD/lufBek9nCZZsYz3brHe8FvJ5uqQCL42iCsyaVjQRG7fIN4.s5a', 0, 'reset_password', '2026-10-11 04:59:44', '2026-10-11 04:55:01', '2026-10-11 04:54:44', '2026-10-11 04:55:01'),
(28, 3, 'user', 'peterdin2017@gmail.com', '$2y$12$s5isJoaDlkBEM0vceVUneempi8jwwchCttvjrfaUzof71nPxBwtgi', 0, 'reset_password', '2026-10-11 05:28:09', '2026-10-11 07:03:48', '2026-10-11 05:23:09', '2026-10-11 07:03:48'),
(43, 3, 'user', 'peterdin2017@gmail.com', '$2y$12$vZekB2OPccrsLzEjFdrPQ./Qj4R8VLd3OErZYZMcx6S2g/2WMJUh.', 0, 'reset_password', '2026-10-11 07:08:48', '2026-10-11 07:04:58', '2026-10-11 07:03:48', '2026-10-11 07:04:58'),
(44, 9, 'admin', 'peterdin2017@gmail.com', '$2y$12$cAikJ8XRQyanZ4wV/FwZReREsMbDd9fCpTqAVKzJEjSLIBXxww2Vm', 0, 'reset_password', '2026-10-11 07:34:58', '2026-10-11 07:31:05', '2026-10-11 07:29:58', '2026-10-11 07:31:05'),
(45, 9, 'admin', 'peterdin2017@gmail.com', '$2y$12$upn17BAW/PU7OYclsDZo7.NOGcggd6Q6tvUQqXHFE5ijC6D6WFsm2', 0, 'reset_password', '2026-10-11 07:36:05', '2026-10-11 07:31:32', '2026-10-11 07:31:05', '2026-10-11 07:31:32'),
(46, 9, 'admin', 'peterdin2017@gmail.com', '$2y$12$q6lsa5JyNSjyzCE4R/e6s.PBrnsM/qpxnEW6pzktya1xiaHE5vNXS', 0, 'reset_password', '2026-10-11 07:37:00', '2026-10-11 07:34:28', '2026-10-11 07:32:00', '2026-10-11 07:34:28'),
(47, 9, 'admin', 'peterdin2017@gmail.com', '$2y$12$UYrka2tltkn.UfZ9zfcTE.I2FJvM8Ppgfm.OxrJmWa3dZ7nz0xacq', 0, 'reset_password', '2026-10-11 07:48:17', '2026-10-11 07:43:40', '2026-10-11 07:43:17', '2026-10-11 07:43:40'),
(48, 5, 'seller', 'peterdin2017@gmail.com', '$2y$12$RYbyMvgP4zt7UltanaastOycsAqQiJeaPb7Fg4sowQ0hRuCRftMqG', 0, 'reset_password', '2026-10-11 08:31:14', '2026-10-11 08:26:37', '2026-10-11 08:26:14', '2026-10-11 08:26:37'),
(49, 10, 'seller', 'peterdin2019@gmail.com', '$2y$12$f0wA8O0Mo5RKU9lYg11BMejIb2rcB227Zzn4ki.BzkpjZavXDXnle', 0, 'register', '2026-10-12 09:17:54', '2026-10-12 09:13:29', '2026-10-12 09:12:54', '2026-10-12 09:13:29'),
(50, 10, 'seller', 'peterdin2019@gmail.com', '$2y$12$YiIMgOJ89Cvlfec6Ds3KEuGhMe5IpHTZHM1EF3v9pk5w53P75.rxO', 0, 'register', '2026-10-12 09:18:29', NULL, '2026-10-12 09:13:29', '2026-10-12 09:13:29'),
(52, 12, 'user', 'peterdin2029@gmail.com', '$2y$12$BM/U0y/n8chz1ANYr27SROPAg2Gm8hnTpcAg76ON5d1Wx8Pr8uwQO', 0, 'register', '2026-10-12 09:38:47', '2026-10-12 09:58:48', '2026-10-12 09:33:47', '2026-10-12 09:58:48'),
(53, 12, 'user', 'peterdin2029@gmail.com', '$2y$12$SLyvWUj6x0KT41UTv9La9eRnOzKQbzV9KvDM0u3m6VfVC3NR.QFg6', 0, 'register', '2026-10-12 10:03:48', '2026-10-12 10:00:14', '2026-10-12 09:58:48', '2026-10-12 10:00:14'),
(54, 12, 'user', 'peterdin2029@gmail.com', '$2y$12$n4pDNxTKu2HkqErArzak8up5KqSFLSboW6zs6V/GkFcMiwX6zRfwC', 0, 'register', '2026-10-12 10:08:21', '2026-10-12 10:03:53', '2026-10-12 10:03:21', '2026-10-12 10:03:53'),
(55, 12, 'user', 'peterdin2029@gmail.com', '$2y$12$rmZaiQA55cWEub3svzNLKOKSNbZmEX4kYFGVR.O4FzzbtO1pqJSQ.', 0, 'register', '2026-10-12 10:09:33', '2026-10-12 10:04:38', '2026-10-12 10:04:33', '2026-10-12 10:04:38'),
(56, 12, 'user', 'peterdin2029@gmail.com', '$2y$12$7cXy30VjdEv01jFCdk4B0eE0KqMe36HxvJlbbwR7NbiXIteDUjrym', 0, 'register', '2026-10-12 10:09:38', '2026-10-12 10:05:12', '2026-10-12 10:04:38', '2026-10-12 10:05:12'),
(57, 12, 'user', 'peterdin2029@gmail.com', '$2y$12$tLDjGDbZCf85Oxf1RakIB.K98iIlFedKhCj.4oaAhgF7ONJIJ5one', 0, 'register', '2026-10-12 10:10:12', '2026-10-12 10:06:10', '2026-10-12 10:05:12', '2026-10-12 10:06:10'),
(58, 12, 'user', 'peterdin2029@gmail.com', '$2y$12$UqU8tgP6mN0Rl2lyCJmLHOpLfzlp261EP30DmaB1T7ITznHeU.iiS', 0, 'register', '2026-10-12 23:16:40', '2026-10-12 23:12:03', '2026-10-12 23:11:40', '2026-10-12 23:12:03'),
(59, 12, 'user', 'peterdin2029@gmail.com', '$2y$12$PbLRv4t043MqFSipaAYo1.P.LRgOC9j6/eJH37sN3RFDlaEoWSkQu', 0, 'register', '2026-10-12 23:35:42', '2026-10-12 23:31:10', '2026-10-12 23:30:42', '2026-10-12 23:31:10'),
(60, 12, 'user', 'peterdin2029@gmail.com', '$2y$12$7/RLdehzyZ.xcA94qQgomOWlR56UyaAu/5f6LV1QqZA8LC45Pp.nG', 0, 'reset_password', '2026-10-12 23:37:18', '2026-10-12 23:32:40', '2026-10-12 23:32:18', '2026-10-12 23:32:40'),
(61, 13, 'seller', 'peterdin2029@gmail.com', '$2y$12$SYX8oklv/ilEJpYgXDXeR.exXFF57Wjv.7wkDkGwSxIoFl4gs4J92', 0, 'register', '2026-10-12 23:38:46', '2026-10-12 23:34:06', '2026-10-12 23:33:46', '2026-10-12 23:34:06'),
(62, 9, 'admin', 'peterdin2017@gmail.com', '$2y$12$w40sEUeiDmlm8/hSq4hs3.W8cPCi6wleSfCm.iBwSR/7862Nna7RW', 0, 'reset_password', '2026-10-12 23:40:23', '2026-10-12 23:35:52', '2026-10-12 23:35:23', '2026-10-12 23:35:52'),
(63, 3, 'user', 'peterdin2017@gmail.com', '$2y$12$OQCBE3zTxolax4bODaoFrOpSzWtuevq4dkE14onxO0yNmfJ/WezO2', 0, 'reset_password', '2026-10-14 22:30:31', '2026-10-14 22:26:10', '2026-10-14 22:25:31', '2026-10-14 22:26:10'),
(66, 3, 'user', 'peterdin2017@gmail.com', '$2y$12$kmnN9NCWrUCvyHYdRwk8MOdrknj7xeHQsHxNJ07nDkrdiIE.iiWI6', 0, 'reset_password', '2026-10-16 21:23:11', '2026-10-16 21:18:35', '2026-10-16 21:18:11', '2026-10-16 21:18:35'),
(67, 3, 'user', 'peterdin2017@gmail.com', '$2y$12$ta1T.tKZpXKxZi4g/.mwBemZ3fliw/LAHJrRhU.Cy23xsK6rfSM8K', 1, 'reset_password', '2026-10-16 21:23:36', '2026-10-16 21:19:34', '2026-10-16 21:18:36', '2026-10-16 21:19:34'),
(68, 3, 'user', 'peterdin2017@gmail.com', '$2y$12$rMlkKmoue8rZ.4JBxgXu4.VGsq9qe.xQR9ehwtqSwL0DH7WkF76Na', 0, 'reset_password', '2026-10-16 21:24:34', '2026-10-16 21:20:39', '2026-10-16 21:19:34', '2026-10-16 21:20:39'),
(69, 16, 'seller', 'peterdin2016@gmail.com', '$2y$12$b0rfPEEfYHAdIjClT60Cq.ydKq6filiFlrDMDmAXWf6Z3ZuotLQCG', 0, 'register', '2026-10-18 02:21:21', '2026-10-19 22:47:20', '2026-10-18 02:16:21', '2026-10-19 22:47:20'),
(70, 17, 'user', 'peterdin2016@gmail.com', '$2y$12$CFqp6253jXyw.ak4D5e9PuM52JMr9rG3gCDMEhmvUBIE6PNwUwg/m', 0, 'register', '2026-10-19 21:23:30', '2026-10-19 21:19:15', '2026-10-19 21:18:30', '2026-10-19 21:19:15'),
(71, 16, 'seller', 'peterdin2016@gmail.com', '$2y$12$Q9/Hw.3wWi5eJVzaUXT5TOLtYSLrVa//tie5UN9WM1Q10TEhn9osG', 0, 'register', '2026-10-19 22:52:21', '2026-10-19 22:48:37', '2026-10-19 22:47:21', '2026-10-19 22:48:37'),
(72, 9, 'admin', 'peterdin2017@gmail.com', '$2y$12$u0hPhvNscZNHBYi0GS/OH.dQD.QJWHznbhgP/DBmSMofRvwQquCz.', 0, 'reset_password', '2026-10-19 23:36:52', '2026-10-19 23:31:57', '2026-10-19 23:31:52', '2026-10-19 23:31:57'),
(73, 9, 'admin', 'peterdin2017@gmail.com', '$2y$12$UeV7GyKhDH62zuTNthBMjuVxmf0JfGyYMRmqQNAtL7922Sek8EUeC', 0, 'reset_password', '2026-10-19 23:36:58', '2026-10-19 23:32:36', '2026-10-19 23:31:58', '2026-10-19 23:32:36'),
(75, 34, 'user', 'peterdin2022@gmail.com', '$2y$12$dY78cPtZ9qIqZjcYk2LHnOtkOOzQ6bGamvf81mE5gE8k0ipqd.dxq', 0, 'register', '2026-10-31 11:53:34', NULL, '2026-10-31 11:48:34', '2026-10-31 11:48:34'),
(76, 3, 'user', 'peterdin2017@gmail.com', '$2y$12$2KikiPQ6HPDhoEHhCQSNfOyh8U6CRwBr6nxHiW13LAQmmaceSIyCS', 0, 'reset_password', '2026-10-31 11:55:55', NULL, '2026-10-31 11:50:55', '2026-10-31 11:50:55'),
(77, 35, 'seller', 'peterdin2022@gmail.com', '$2y$12$Gm9NPE.OrGDfaf6p1J3kUeAgZFOLqKODAnvg0Nnv5A9PiGPuJ2C8m', 0, 'register', '2026-10-31 13:48:55', '2026-10-31 13:44:00', '2026-10-31 13:43:55', '2026-10-31 13:44:00'),
(78, 35, 'seller', 'peterdin2022@gmail.com', '$2y$12$x3dqSXWFUSNa3cTOj8eiYedDSNDoNo9ZSg4e7H5j2qInATZZE42ku', 1, 'register', '2026-10-31 13:49:01', '2026-10-31 13:44:54', '2026-10-31 13:44:01', '2026-10-31 13:44:54'),
(79, 9, 'admin', 'peterdin2017@gmail.com', '$2y$12$tINe1MQ4Ang3ziL6FN48ROPyRzUWAGBQdlf0kMmOUbWUYRu7v07pG', 0, 'reset_password', '2026-11-01 03:40:32', '2026-11-01 03:36:02', '2026-11-01 03:35:32', '2026-11-01 03:36:02');

-- --------------------------------------------------------

--
-- Table structure for table `platform_vouchers`
--

CREATE TABLE `platform_vouchers` (
  `platform_voucher_id` bigint UNSIGNED NOT NULL,
  `voucher_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `voucher_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `discount_type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `discount_value` decimal(14,2) NOT NULL,
  `max_discount_amount` decimal(14,2) DEFAULT NULL,
  `min_order_amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `usage_limit` int UNSIGNED DEFAULT NULL,
  `used_count` int UNSIGNED NOT NULL DEFAULT '0',
  `per_user_limit` int UNSIGNED NOT NULL DEFAULT '1',
  `starts_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `platform_vouchers`
--

INSERT INTO `platform_vouchers` (`platform_voucher_id`, `voucher_code`, `voucher_name`, `description`, `discount_type`, `discount_value`, `max_discount_amount`, `min_order_amount`, `usage_limit`, `used_count`, `per_user_limit`, `starts_at`, `expires_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 'SALE', 'sale10', '123', 'percentage', 20.00, 400000.00, 0.00, 20, 1, 1, '2026-10-26 23:16:00', '2026-10-27 23:17:00', 'active', '2026-10-26 16:17:18', '2026-10-31 08:00:24'),
(2, '2026', '2026', '2026', 'percentage', 10.00, NULL, 0.00, 1, 0, 1, '2026-10-31 15:00:00', '2026-11-05 15:00:00', 'active', '2026-10-31 08:00:57', '2026-11-01 03:33:05');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` bigint UNSIGNED NOT NULL,
  `seller_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `product_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `brand` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `stock_quantity` int UNSIGNED NOT NULL DEFAULT '0',
  `weight_gram` int UNSIGNED DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sold_count` int UNSIGNED NOT NULL DEFAULT '0',
  `rating_avg` decimal(2,1) NOT NULL DEFAULT '0.0',
  `rating_count` int UNSIGNED NOT NULL DEFAULT '0',
  `status` enum('draft','active','hidden','locked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `locked_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locked_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `seller_id`, `category_id`, `product_name`, `slug`, `description`, `brand`, `price`, `stock_quantity`, `weight_gram`, `image_url`, `sold_count`, `rating_avg`, `rating_count`, `status`, `published_at`, `locked_reason`, `locked_at`, `created_at`, `updated_at`) VALUES
(11, 2, 13, 'Điện thoại iPhone 15 Chính hãng VN/A', 'product-11', 'Điện thoại iPhone 15 mang đến bước đột phá về thiết kế với mặt lưng kính pha màu nguyên khối và khung viền nhôm chuẩn hàng không vũ trụ, tạo cảm giác cầm nắm chắc chắn và sang trọng. Điểm nhấn nổi bật nhất là sự xuất hiện của Dynamic Island, thay thế hoàn toàn thiết kế tai thỏ cũ, giúp bạn tương tác thông minh với các cảnh báo và hoạt động trực tiếp. Về nhiếp ảnh, máy được trang bị camera chính 48MP với độ phân giải siêu cao, kết hợp cùng camera Ultra Wide, cho ra những bức ảnh sắc nét, chi tiết ngay cả trong điều kiện thiếu sáng. Hiệu năng vượt trội nhờ chip A16 Bionic cực kỳ mạnh mẽ, xử lý mượt mà các tựa game đồ họa cao và tiết kiệm pin tối đa. Đặc biệt, iPhone 15 đã chính thức chuyển sang cổng sạc USB-C phổ biến, hỗ trợ sạc nhanh và truyền dữ liệu tiện lợi hơn bao giờ hết. Khả năng kháng nước, kháng bụi đạt chuẩn IP68 bền bỉ.', 'Apple', 19500000.00, 6, 123, 'products/2/11/2-11-704682.jpg', 171, 3.6, 9, 'active', '2026-10-31 14:13:36', NULL, NULL, '2026-10-25 23:00:40', '2026-10-31 14:13:36'),
(40, 2, 13, 'Điện thoại Samsung Galaxy A55 5G', 'product-40', 'Samsung Galaxy A55 5G là bản nâng cấp toàn diện trong phân khúc tầm trung với thiết kế nguyên khối sang trọng. Máy sở hữu khung viền kim loại chắc chắn cùng mặt kính cường lực Gorilla Glass Victus+ ở cả hai mặt, bảo vệ máy tối ưu khỏi trầy xước. Màn hình Super AMOLED 6.6 inch sắc nét với tần số quét 120Hz mang đến trải nghiệm vuốt chạm cực kỳ mượt mà, công nghệ Vision Booster giúp hiển thị rõ nét ngay cả dưới ánh nắng gắt. Cụm 3 camera sau đỉnh cao, trong đó camera chính 50MP hỗ trợ chống rung quang học (OIS) và công nghệ Nightography, giúp chụp ảnh và quay video đêm sáng rõ, giảm nhiễu hạt. Trái tim của máy là vi xử lý Exynos 1480 8 nhân, tích hợp hệ thống tản nhiệt buồng hơi lớn hơn 74%, đảm bảo hiệu suất ổn định khi chơi game. Nền tảng bảo mật Samsung Knox Vault giúp bảo vệ dữ liệu cá nhân an toàn tuyệt đối. Viên pin 5000mAh đi kèm sạc nhanh 25W.', 'Samsung', 19500000.00, 85, 343, 'products/2/40/2-40-228426.jpg', 1, 0.0, 0, 'active', '2026-10-31 01:59:38', NULL, NULL, '2026-10-30 16:35:16', '2026-10-31 14:13:36'),
(41, 2, 13, 'Điện thoại Asus ROG Phone 8', 'product-41', 'Asus ROG Phone 8 là dòng điện thoại gaming thế hệ mới mang thiết kế mỏng nhẹ hơn đáng kể, phù hợp cho cả nhu cầu sử dụng hàng ngày và cày game chuyên nghiệp. Sức mạnh vô song đến từ siêu chip Snapdragon 8 Gen 3 mới nhất, kết hợp cùng RAM chuẩn LPDDR5X và bộ nhớ UFS 4.0 tốc độ cao, xử lý nhẹ nhàng mọi tựa game AAA nặng nhất hiện nay. Hệ thống tản nhiệt GameCool 8 tiên tiến với cấu trúc dẫn nhiệt trực tiếp, giúp duy trì nhiệt độ mát mẻ, không tụt khung hình (FPS) khi chơi liên tục. Màn hình E6 AMOLED 6.78 inch với tần số quét siêu tốc 165Hz và tốc độ lấy mẫu cảm ứng 720Hz mang lại phản hồi tức thì cho các pha combat. Điểm ăn tiền nằm ở nút bấm cảm ứng AirTrigger siêu nhạy ở cạnh viền, cho phép thiết lập các thao tác phức tạp như một tay cầm console thực thụ. Máy đạt chuẩn kháng nước IP68 đầu tiên trên dòng ROG và có viên pin 5500mAh hỗ trợ sạc nhanh 65W.', 'Asus', 25990000.00, 9, 343, 'products/2/41/2-41-304070.jpg', 2, 0.0, 0, 'active', '2026-10-31 02:11:06', NULL, NULL, '2026-10-30 16:55:09', '2026-10-31 14:13:35'),
(42, 2, 13, 'Điện thoại Xiaomi 14 Ultra 5G', 'dien-thoai-xiaomi-14-ultra-5g', 'Chiếc flagship đỉnh cao, tập trung mạnh mẽ vào nhiếp ảnh với cụm 4 camera 50MP hợp tác cùng Leica. Cảm biến chính 1-inch thế hệ mới cho khả năng thu sáng vượt trội, chụp đêm như ban ngày. Thiết kế mặt lưng da vegan cao cấp kết hợp khung nhôm chắc chắn, mang lại cảm giác cầm nắm đầm tay. Màn hình AMOLED 6.73 inch WQHD+ 120Hz hiển thị siêu sắc nét. Hiệu năng đỉnh cao nhờ vi xử lý Snapdragon 8 Gen 3, đáp ứng hoàn hảo mọi nhu cầu đa nhiệm, lập trình ứng dụng trên di động, chơi game nặng hay chỉnh sửa video. Pin 5000mAh hỗ trợ sạc nhanh 90W và sạc không dây 80W siêu tốc.', 'Xiaomi', 29990000.00, 22, 341, 'products/2/42/2-42-318798.jpg', 0, 0.0, 0, 'active', '2026-10-31 13:26:50', NULL, NULL, '2026-10-31 13:26:50', '2026-10-31 14:13:34'),
(43, 2, 13, 'Điện thoại OPPO Find N3 Flip 5G', 'dien-thoai-oppo-find-n3-flip-5g', 'Điện thoại gập vỏ sò thời trang với nếp gấp tàng hình thế hệ mới. Màn hình ngoài thiết kế dạng dọc vô cùng tiện lợi để xem thông báo, trả lời tin nhắn nhanh, xem bản đồ và dùng các ứng dụng cơ bản mà không cần mở máy. Tích hợp cụm 3 camera tinh chỉnh bởi Hasselblad độc đáo, cho chất lượng ảnh chân dung tuyệt đẹp với màu sắc chân thực. Bản lề Flexion Hinge siêu bền bỉ, thao tác gập mở mượt mà và kín khít. Chip MediaTek Dimensity 9200 xử lý tác vụ trơn tru, máy mát mẻ. Pin 4300mAh cùng sạc nhanh SuperVOOC 44W, thiết kế nhỏ gọn dễ dàng bỏ túi.', 'OPPO', 22990000.00, 8, 123, 'products/2/43/2-43-674478.jpg', 0, 0.0, 0, 'active', '2026-10-31 13:29:54', NULL, NULL, '2026-10-31 13:29:54', '2026-10-31 14:13:33'),
(44, 2, 18, 'Laptop Máy trạm Dell Precision 5520', 'laptop-may-tram-dell-precision-5520', 'Dòng máy trạm di động (Mobile Workstation) sở hữu thiết kế viền màn hình InfinityEdge siêu mỏng, vỏ nhôm nguyên khối kết hợp chiếu nghỉ tay bằng sợi carbon vô cùng chắc chắn và chuyên nghiệp. Màn hình 15.6 inch chống chói tái tạo màu sắc chuẩn xác, bảo vệ mắt tốt, cực kỳ lý tưởng cho sinh viên CNTT khi phải ngồi gõ code, thiết kế UI/UX hay làm việc với các hệ quản trị cơ sở dữ liệu trong nhiều giờ. Trang bị vi xử lý Intel Core i7 dòng H hiệu năng cao cùng card đồ họa chuyên dụng NVIDIA Quadro, tối ưu hóa cho các phần mềm kỹ thuật, máy ảo, và các môi trường lập trình nặng (như Visual Studio, Android Studio). Bàn phím nảy êm ái, hành trình sâu, đi kèm touchpad phủ kính mượt mà.', 'Dell', 13500000.00, 13, 1780, 'products/2/44/2-44-943765.jpg', 0, 0.0, 0, 'active', '2026-10-31 13:32:29', NULL, NULL, '2026-10-31 13:32:14', '2026-10-31 14:13:31'),
(45, 2, 68, 'Đồng hồ thông minh Apple Watch Series 9 GPS', 'dong-ho-thong-minh-apple-watch-series-9-gps', 'Apple Watch Series 9 mang đến trải nghiệm đột phá với con chip S9 SiP mạnh mẽ, cho phép xử lý các tác vụ mượt mà và hỗ trợ tính năng \"Chạm hai lần\" (Double Tap) cực kỳ tiện lợi để nhận cuộc gọi, mở thông báo mà không cần chạm vào màn hình. Màn hình Retina luôn sáng (Always-On) có độ sáng lên đến 2000 nits, hiển thị rõ ràng ngay cả dưới trời nắng gắt. Thiết bị là trợ lý sức khỏe đắc lực với các cảm biến đo nhịp tim liên tục, đo nồng độ oxy trong máu (SpO2), chụp điện tâm đồ (ECG) và theo dõi các giai đoạn giấc ngủ chi tiết. Tính năng Phát hiện va chạm và Phát hiện ngã sẽ tự động gọi cấp cứu khi có sự cố. Vỏ nhôm nguyên khối tái chế 100% thân thiện với môi trường. Kháng nước WR50, thoải mái bơi lội.', 'Apple', 9490000.00, 14, 32, 'products/2/45/2-45-542944.jpg', 0, 0.0, 0, 'active', '2026-10-31 13:35:55', NULL, NULL, '2026-10-31 13:35:55', '2026-11-01 03:46:05'),
(46, 2, 68, 'Đồng hồ Nam cơ tự động Seiko 5 Sports', 'dong-ho-nam-co-tu-dong-seiko-5-sports', 'Seiko 5 Sports là biểu tượng của sự bền bỉ và nam tính. Phiên bản này sử dụng bộ máy cơ tự động (Automatic) Caliber 4R36 in-house lừng danh của Nhật Bản với 24 chân kính, hỗ trợ lên cót tay và dừng kim giây khi chỉnh giờ (Hacking Stop), thời gian trữ cót lên đến 41 giờ. Thiết kế vỏ và dây đeo làm từ thép không gỉ 316L nguyên khối đánh bóng tinh xảo, chống ăn mòn cực tốt. Mặt kính Hardlex độc quyền của Seiko chống trầy xước và chịu va đập hiệu quả. Mặt số thể thao với các cọc số lớn, phủ lớp dạ quang LumiBrite siêu sáng, giúp xem giờ dễ dàng trong bóng tối. Thiết kế lộ đáy (See-through case back) mặt sau cho phép quan sát nhịp đập của bộ máy cơ khí. Chuẩn chống nước 10ATM (100m).', 'Seiko', 6250000.00, 8, 140, 'products/2/46/2-46-855772.jpg', 0, 0.0, 0, 'active', '2026-11-01 04:22:00', NULL, NULL, '2026-11-01 04:22:00', '2026-11-01 04:22:01');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `image_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`image_id`, `product_id`, `image_url`, `sort_order`, `created_at`, `updated_at`) VALUES
(41, 11, 'products/2/11/2-11-497950.jpg', 1, '2026-10-25 23:00:40', '2026-10-25 23:00:40'),
(42, 11, 'products/2/11/2-11-781424.jpg', 2, '2026-10-25 23:00:40', '2026-10-25 23:00:40'),
(43, 11, 'products/2/11/2-11-995512.jpg', 3, '2026-10-25 23:00:40', '2026-10-25 23:00:40'),
(44, 40, 'products/2/40/2-40-335044.jpg', 1, '2026-10-30 16:36:57', '2026-10-30 16:36:57'),
(45, 40, 'products/2/40/2-40-825914.jpg', 2, '2026-10-30 16:36:57', '2026-10-30 16:36:57'),
(46, 40, 'products/2/40/2-40-124122.jpg', 3, '2026-10-30 16:36:57', '2026-10-30 16:36:57'),
(47, 40, 'products/2/40/2-40-786833.jpg', 4, '2026-10-30 16:36:57', '2026-10-30 16:36:57'),
(48, 40, 'products/2/40/2-40-195390.jpg', 5, '2026-10-30 16:36:57', '2026-10-30 16:36:57'),
(49, 40, 'products/2/40/2-40-806848.jpg', 6, '2026-10-30 16:36:57', '2026-10-30 16:36:57'),
(50, 40, 'products/2/40/2-40-344861.jpg', 7, '2026-10-30 16:36:57', '2026-10-30 16:36:57'),
(51, 40, 'products/2/40/2-40-289616.jpg', 8, '2026-10-30 16:36:57', '2026-10-30 16:36:57'),
(52, 41, 'products/2/41/2-41-275934.jpg', 1, '2026-10-30 16:55:09', '2026-10-30 16:55:09'),
(53, 41, 'products/2/41/2-41-550633.jpg', 2, '2026-10-30 16:55:09', '2026-10-30 16:55:09'),
(54, 41, 'products/2/41/2-41-575288.jpg', 3, '2026-10-30 16:55:09', '2026-10-30 16:55:09'),
(55, 42, 'products/2/42/2-42-554198.jpg', 1, '2026-10-31 13:26:51', '2026-10-31 13:26:51'),
(56, 42, 'products/2/42/2-42-723864.jpg', 2, '2026-10-31 13:26:51', '2026-10-31 13:26:51'),
(57, 42, 'products/2/42/2-42-615003.jpg', 3, '2026-10-31 13:26:51', '2026-10-31 13:26:51'),
(58, 42, 'products/2/42/2-42-820521.png', 4, '2026-10-31 13:26:51', '2026-10-31 13:26:51'),
(59, 43, 'products/2/43/2-43-463861.jpg', 1, '2026-10-31 13:29:54', '2026-10-31 13:29:54'),
(60, 43, 'products/2/43/2-43-465538.jpg', 2, '2026-10-31 13:29:54', '2026-10-31 13:29:54'),
(61, 43, 'products/2/43/2-43-535998.jpg', 3, '2026-10-31 13:29:54', '2026-10-31 13:29:54'),
(62, 44, 'products/2/44/2-44-802221.jpg', 1, '2026-10-31 13:32:14', '2026-10-31 13:32:14'),
(63, 44, 'products/2/44/2-44-174467.jpg', 2, '2026-10-31 13:32:14', '2026-10-31 13:32:14'),
(64, 44, 'products/2/44/2-44-500924.jpg', 3, '2026-10-31 13:32:14', '2026-10-31 13:32:14'),
(65, 44, 'products/2/44/2-44-117371.jpg', 4, '2026-10-31 13:32:14', '2026-10-31 13:32:14'),
(66, 45, 'products/2/45/2-45-901172.jpg', 1, '2026-10-31 13:35:56', '2026-10-31 13:35:56'),
(67, 45, 'products/2/45/2-45-736324.png', 2, '2026-10-31 13:35:56', '2026-10-31 13:35:56'),
(68, 46, 'products/2/46/2-46-218581.jpg', 1, '2026-11-01 04:22:01', '2026-11-01 04:22:01'),
(69, 46, 'products/2/46/2-46-339591.jpg', 2, '2026-11-01 04:22:01', '2026-11-01 04:22:01'),
(70, 46, 'products/2/46/2-46-617570.jpg', 3, '2026-11-01 04:22:01', '2026-11-01 04:22:01'),
(71, 46, 'products/2/46/2-46-430061.jpg', 4, '2026-11-01 04:22:01', '2026-11-01 04:22:01');

-- --------------------------------------------------------

--
-- Table structure for table `product_reports`
--

CREATE TABLE `product_reports` (
  `report_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `reason` enum('fake','prohibited','wrong_info','copyright','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','resolved','dismissed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `handled_by` bigint UNSIGNED DEFAULT NULL,
  `handled_at` timestamp NULL DEFAULT NULL,
  `admin_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_reports`
--

INSERT INTO `product_reports` (`report_id`, `product_id`, `account_id`, `reason`, `description`, `status`, `handled_by`, `handled_at`, `admin_note`, `created_at`, `updated_at`) VALUES
(2, 11, 3, 'prohibited', 'hàng lỏ', 'resolved', 9, '2026-10-26 17:06:57', 'hàng sai', '2026-10-26 17:06:36', '2026-10-26 17:06:57'),
(8, 40, 3, 'fake', NULL, 'resolved', 9, '2026-10-31 01:58:59', '123', '2026-10-31 01:58:29', '2026-10-31 01:58:59'),
(9, 41, 3, 'wrong_info', NULL, 'pending', NULL, NULL, NULL, '2026-10-31 12:58:44', '2026-10-31 12:58:44');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `variant_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `variant_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `stock_quantity` int UNSIGNED NOT NULL DEFAULT '0',
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`variant_id`, `product_id`, `variant_name`, `price`, `stock_quantity`, `image_url`, `created_at`, `updated_at`) VALUES
(19, 11, 'Màu Đen - 128GB', 19500000.00, 6, 'products/2/11/2-11-194827.jpg', '2026-10-25 23:00:40', '2026-10-26 17:02:36'),
(20, 11, 'Màu Đen - 256GB', 22500000.00, 0, 'products/2/11/2-11-268033.jpg', '2026-10-25 23:00:40', '2026-10-26 07:35:29'),
(21, 11, 'Màu Hồng - 128GB', 19800000.00, 0, 'products/2/11/2-11-715403.jpg', '2026-10-25 23:00:40', '2026-10-26 08:35:57'),
(25, 40, 'Màu Đen - 128GB', 19500000.00, 30, 'products/2/40/2-40-305333.jpg', '2026-10-30 16:36:57', '2026-10-31 02:13:01'),
(26, 40, 'Màu Đen - 256GB', 22500000.00, 20, 'products/2/40/2-40-822319.jpg', '2026-10-30 16:36:57', '2026-10-30 16:36:57'),
(27, 40, 'Màu Hồng - 128GB', 19800000.00, 35, 'products/2/40/2-40-719535.jpg', '2026-10-30 16:36:57', '2026-10-30 16:36:57'),
(28, 41, 'Đen Nhám (Phantom Black) - 16GB/256GB', 25990000.00, 0, 'products/2/41/2-41-265136.jpg', '2026-10-30 16:55:09', '2026-10-31 12:01:42'),
(29, 41, 'Xám Xanh (Rebel Grey) - 16GB/256GB', 25990000.00, 9, 'products/2/41/2-41-806441.jpg', '2026-10-30 16:55:09', '2026-10-31 12:02:18'),
(30, 42, 'Đen - 16GB/512GB', 29990000.00, 10, 'products/2/42/2-42-146321.jpg', '2026-10-31 13:26:51', '2026-10-31 13:26:51'),
(31, 42, 'Trắng - 16GB/512GB', 29990000.00, 12, 'products/2/42/2-42-542137.jpg', '2026-10-31 13:26:51', '2026-10-31 13:26:51'),
(32, 43, 'Đen Nhám - 12GB/256GB', 22990000.00, 3, 'products/2/43/2-43-556761.jpg', '2026-10-31 13:29:54', '2026-10-31 13:29:54'),
(33, 43, 'Vàng Nhạt - 12GB/256GB', 22990000.00, 5, 'products/2/43/2-43-574788.jpg', '2026-10-31 13:29:54', '2026-10-31 13:29:54');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `order_item_id` bigint UNSIGNED NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `seller_reply` text COLLATE utf8mb4_unicode_ci,
  `seller_replied_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `account_id`, `product_id`, `order_item_id`, `rating`, `comment`, `created_at`, `updated_at`, `seller_reply`, `seller_replied_at`) VALUES
(4, 3, 11, 8, 5, NULL, '2026-10-26 08:30:53', '2026-10-26 08:30:53', NULL, NULL),
(5, 3, 11, 7, 4, NULL, '2026-10-26 08:30:58', '2026-10-26 08:30:58', NULL, NULL),
(6, 3, 11, 4, 4, NULL, '2026-10-26 08:31:05', '2026-10-26 08:31:05', NULL, NULL),
(7, 3, 11, 5, 2, NULL, '2026-10-26 08:31:14', '2026-10-26 08:31:14', NULL, NULL),
(8, 3, 11, 2, 4, NULL, '2026-10-26 08:31:21', '2026-10-26 08:31:21', NULL, NULL),
(9, 3, 11, 6, 1, NULL, '2026-10-26 08:31:26', '2026-10-26 08:31:26', NULL, NULL),
(10, 3, 11, 1, 2, NULL, '2026-10-26 08:31:34', '2026-10-26 08:31:34', NULL, NULL),
(11, 3, 11, 13, 5, NULL, '2026-10-30 06:20:47', '2026-10-30 06:44:25', 'Shop cảm ơn', '2026-10-30 06:44:25'),
(12, 3, 11, 16, 5, NULL, '2026-10-30 18:00:28', '2026-10-30 18:00:28', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `review_images`
--

CREATE TABLE `review_images` (
  `id` bigint UNSIGNED NOT NULL,
  `review_id` bigint UNSIGNED NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `review_images`
--

INSERT INTO `review_images` (`id`, `review_id`, `image_path`, `created_at`, `updated_at`) VALUES
(5, 11, 'reviews/2UxwQLJ0IEBMOtVsfM2vr0nKaCm1MiiQbjUGB34r.png', '2026-10-30 06:20:47', '2026-10-30 06:20:47');

-- --------------------------------------------------------

--
-- Table structure for table `sellers`
--

CREATE TABLE `sellers` (
  `seller_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shop_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_province` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_district` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_ward` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','active','suspended','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reject_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sellers`
--

INSERT INTO `sellers` (`seller_id`, `account_id`, `avatar`, `shop_name`, `shop_address`, `shop_province`, `shop_district`, `shop_ward`, `status`, `reject_reason`, `approved_at`, `created_at`, `updated_at`) VALUES
(2, 5, 'avatars/sellers/2-20261030205124851.png', 'Phúc Store', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, huyện Bình Chánh, Thành Phố Hồ Chí Minh', 'Hồ Chí Minh', 'Bình Chánh', 'Vĩnh Lộc B', 'active', NULL, '2026-10-11 08:22:19', '2026-10-11 04:38:06', '2026-10-30 13:51:24'),
(6, 13, NULL, 'Phúc Store', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, huyện Bình Chánh, Thành Phố Hồ Chí Minh', 'Hồ Chí Minh', 'Bình Chánh', 'Vĩnh Lộc B', 'active', NULL, '2026-10-12 23:36:34', '2026-10-12 23:34:26', '2026-10-12 23:36:34'),
(7, 16, NULL, 'Ceo phôn éc', '49 đường 6B, ấp 6, xã Vĩnh Lộc B, huyện Bình Chánh, Thành Phố Hồ Chí Minh', 'Hồ Chí Minh', 'Bình Chánh', 'Vĩnh Lộc B', 'rejected', '123', NULL, '2026-10-19 22:49:22', '2026-10-19 22:50:04'),
(15, 35, NULL, 'Ceo phôn éc', '140 Lê Trọng Tấn, Phường Tây Thạnh, Thành phố Hồ Chí Minh', 'Hồ Chí Minh', 'Tân Phú', 'Tây Thạnh', 'rejected', '123', NULL, '2026-10-31 13:46:10', '2026-10-31 14:10:04');

-- --------------------------------------------------------

--
-- Table structure for table `seller_vouchers`
--

CREATE TABLE `seller_vouchers` (
  `voucher_id` bigint UNSIGNED NOT NULL,
  `seller_id` bigint UNSIGNED NOT NULL,
  `code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_type` enum('percent','fixed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(14,2) NOT NULL,
  `min_order_amount` decimal(14,2) NOT NULL DEFAULT '0.00',
  `max_discount` decimal(14,2) DEFAULT NULL,
  `usage_limit` int UNSIGNED DEFAULT NULL,
  `used_count` int UNSIGNED NOT NULL DEFAULT '0',
  `starts_at` timestamp NOT NULL,
  `expires_at` timestamp NOT NULL,
  `status` enum('active','paused') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seller_vouchers`
--

INSERT INTO `seller_vouchers` (`voucher_id`, `seller_id`, `code`, `discount_type`, `discount_value`, `min_order_amount`, `max_discount`, `usage_limit`, `used_count`, `starts_at`, `expires_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'Phuc', 'percent', 30.00, 0.00, 4000000.00, 20, 3, '2026-10-26 15:04:00', '2026-10-27 11:05:00', 'paused', '2026-10-26 09:05:35', '2026-10-30 12:52:31'),
(2, 2, 'PhucStore', 'percent', 20.00, 0.00, 1000000.00, NULL, 0, '2026-10-30 12:10:00', '2026-11-06 12:10:00', 'active', '2026-10-30 12:10:54', '2026-10-30 12:14:50'),
(3, 2, 'Phuc2', 'percent', 10.00, 300000.00, 100000.00, 20, 0, '2026-10-30 17:16:00', '2026-11-07 17:16:00', 'active', '2026-10-30 17:17:04', '2026-10-30 17:17:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `account_id` bigint UNSIGNED NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`account_id`, `full_name`, `phone`, `gender`, `birthdate`, `avatar`, `created_at`, `updated_at`) VALUES
(3, 'Đinh Nguyễn Hoàng Phúc', '0974039906', 'male', '2006-10-14', 'avatars/users/3-20261031010156.png', '2026-10-10 22:49:24', '2026-10-30 18:01:56'),
(12, 'Phuc1', NULL, NULL, NULL, NULL, '2026-10-12 23:31:50', '2026-10-12 23:31:50'),
(17, 'Đinh Nguyễn Hoàng Phúc', NULL, NULL, NULL, NULL, '2026-10-19 21:20:36', '2026-10-19 21:20:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `accounts_email_role_unique` (`email`,`role`);

--
-- Indexes for table `account_saved_vouchers`
--
ALTER TABLE `account_saved_vouchers`
  ADD PRIMARY KEY (`saved_voucher_id`),
  ADD UNIQUE KEY `account_saved_vouchers_unique` (`account_id`,`voucher_type`,`voucher_id`),
  ADD KEY `account_saved_vouchers_account_id_voucher_type_created_at_index` (`account_id`,`voucher_type`,`created_at`);

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `addresses_account_id_is_default_index` (`account_id`,`is_default`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD UNIQUE KEY `carts_account_id_unique` (`account_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD UNIQUE KEY `cart_items_cart_id_item_key_unique` (`cart_id`,`item_key`),
  ADD KEY `cart_items_product_id_foreign` (`product_id`),
  ADD KEY `cart_items_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`conversation_id`),
  ADD UNIQUE KEY `conversations_customer_account_id_seller_id_unique` (`customer_account_id`,`seller_id`),
  ADD KEY `conversations_product_id_foreign` (`product_id`),
  ADD KEY `conversations_seller_id_last_message_at_index` (`seller_id`,`last_message_at`);

--
-- Indexes for table `customer_notifications`
--
ALTER TABLE `customer_notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `customer_notifications_support_request_id_foreign` (`support_request_id`),
  ADD KEY `customer_notifications_account_id_read_at_index` (`account_id`,`read_at`);

--
-- Indexes for table `customer_support_requests`
--
ALTER TABLE `customer_support_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `customer_support_requests_account_id_foreign` (`account_id`),
  ADD KEY `customer_support_requests_handled_by_foreign` (`handled_by`),
  ADD KEY `customer_support_requests_status_created_at_index` (`status`,`created_at`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `messages_sender_account_id_foreign` (`sender_account_id`),
  ADD KEY `messages_conversation_id_message_id_index` (`conversation_id`,`message_id`),
  ADD KEY `messages_conversation_id_read_at_index` (`conversation_id`,`read_at`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `orders_order_code_unique` (`order_code`),
  ADD KEY `orders_account_id_created_at_index` (`account_id`,`created_at`),
  ADD KEY `orders_seller_id_order_status_index` (`seller_id`,`order_status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`),
  ADD KEY `order_items_product_variant_id_foreign` (`product_variant_id`),
  ADD KEY `order_items_order_id_index` (`order_id`),
  ADD KEY `order_items_platform_voucher_id_foreign` (`platform_voucher_id`);

--
-- Indexes for table `order_return_requests`
--
ALTER TABLE `order_return_requests`
  ADD PRIMARY KEY (`return_request_id`),
  ADD UNIQUE KEY `order_return_requests_order_id_unique` (`order_id`),
  ADD KEY `order_return_requests_handled_by_foreign` (`handled_by`),
  ADD KEY `order_return_requests_account_id_status_index` (`account_id`,`status`);

--
-- Indexes for table `otps`
--
ALTER TABLE `otps`
  ADD PRIMARY KEY (`otp_id`),
  ADD KEY `otps_account_type_email_purpose_index` (`account_type`,`email`,`purpose`),
  ADD KEY `otps_account_id_foreign` (`account_id`);

--
-- Indexes for table `platform_vouchers`
--
ALTER TABLE `platform_vouchers`
  ADD PRIMARY KEY (`platform_voucher_id`),
  ADD UNIQUE KEY `platform_vouchers_voucher_code_unique` (`voucher_code`),
  ADD KEY `platform_vouchers_status_starts_at_expires_at_index` (`status`,`starts_at`,`expires_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_seller_id_foreign` (`seller_id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_status_published_at_index` (`status`,`published_at`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_reports`
--
ALTER TABLE `product_reports`
  ADD PRIMARY KEY (`report_id`),
  ADD UNIQUE KEY `product_reports_product_id_account_id_unique` (`product_id`,`account_id`),
  ADD KEY `product_reports_account_id_foreign` (`account_id`),
  ADD KEY `product_reports_handled_by_foreign` (`handled_by`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`variant_id`),
  ADD UNIQUE KEY `product_variants_product_id_variant_name_unique` (`product_id`,`variant_name`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD UNIQUE KEY `reviews_order_item_id_unique` (`order_item_id`),
  ADD KEY `reviews_product_id_index` (`product_id`),
  ADD KEY `reviews_account_id_foreign` (`account_id`);

--
-- Indexes for table `review_images`
--
ALTER TABLE `review_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `review_images_review_id_index` (`review_id`);

--
-- Indexes for table `sellers`
--
ALTER TABLE `sellers`
  ADD PRIMARY KEY (`seller_id`),
  ADD UNIQUE KEY `sellers_account_id_unique` (`account_id`);

--
-- Indexes for table `seller_vouchers`
--
ALTER TABLE `seller_vouchers`
  ADD PRIMARY KEY (`voucher_id`),
  ADD UNIQUE KEY `seller_vouchers_seller_id_code_unique` (`seller_id`,`code`),
  ADD KEY `seller_vouchers_seller_id_status_starts_at_expires_at_index` (`seller_id`,`status`,`starts_at`,`expires_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`account_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `account_saved_vouchers`
--
ALTER TABLE `account_saved_vouchers`
  MODIFY `saved_voucher_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `address_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `cart_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `conversation_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer_notifications`
--
ALTER TABLE `customer_notifications`
  MODIFY `notification_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `customer_support_requests`
--
ALTER TABLE `customer_support_requests`
  MODIFY `request_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `order_return_requests`
--
ALTER TABLE `order_return_requests`
  MODIFY `return_request_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `otp_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `platform_vouchers`
--
ALTER TABLE `platform_vouchers`
  MODIFY `platform_voucher_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `product_reports`
--
ALTER TABLE `product_reports`
  MODIFY `report_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `variant_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `review_images`
--
ALTER TABLE `review_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sellers`
--
ALTER TABLE `sellers`
  MODIFY `seller_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `seller_vouchers`
--
ALTER TABLE `seller_vouchers`
  MODIFY `voucher_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_saved_vouchers`
--
ALTER TABLE `account_saved_vouchers`
  ADD CONSTRAINT `account_saved_vouchers_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE;

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`cart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`) ON DELETE SET NULL;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`category_id`) ON DELETE RESTRICT;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_customer_account_id_foreign` FOREIGN KEY (`customer_account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `conversations_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`seller_id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_notifications`
--
ALTER TABLE `customer_notifications`
  ADD CONSTRAINT `customer_notifications_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_notifications_support_request_id_foreign` FOREIGN KEY (`support_request_id`) REFERENCES `customer_support_requests` (`request_id`) ON DELETE SET NULL;

--
-- Constraints for table `customer_support_requests`
--
ALTER TABLE `customer_support_requests`
  ADD CONSTRAINT `customer_support_requests_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `customer_support_requests_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `accounts` (`account_id`) ON DELETE SET NULL;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`conversation_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_account_id_foreign` FOREIGN KEY (`sender_account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`seller_id`) ON DELETE RESTRICT;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_platform_voucher_id_foreign` FOREIGN KEY (`platform_voucher_id`) REFERENCES `platform_vouchers` (`platform_voucher_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`variant_id`) ON DELETE SET NULL;

--
-- Constraints for table `order_return_requests`
--
ALTER TABLE `order_return_requests`
  ADD CONSTRAINT `order_return_requests_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_return_requests_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `accounts` (`account_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_return_requests_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `otps`
--
ALTER TABLE `otps`
  ADD CONSTRAINT `otps_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `products_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`seller_id`) ON DELETE RESTRICT;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_reports`
--
ALTER TABLE `product_reports`
  ADD CONSTRAINT `product_reports_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reports_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `accounts` (`account_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_reports_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `review_images`
--
ALTER TABLE `review_images`
  ADD CONSTRAINT `review_images_review_id_foreign` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`review_id`) ON DELETE CASCADE;

--
-- Constraints for table `sellers`
--
ALTER TABLE `sellers`
  ADD CONSTRAINT `sellers_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE;

--
-- Constraints for table `seller_vouchers`
--
ALTER TABLE `seller_vouchers`
  ADD CONSTRAINT `seller_vouchers_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`seller_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
