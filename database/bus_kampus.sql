-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql:3306
-- Generation Time: Apr 23, 2026 at 06:45 AM
-- Server version: 8.0.44
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bus_kampus`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `guest_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bus_id` bigint UNSIGNED NOT NULL,
  `booking_date` date NOT NULL,
  `seat_number` int NOT NULL,
  `priority_need` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'low, high, other',
  `is_priority` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('pending','confirmed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` enum('qris','etoll') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `price` int NOT NULL DEFAULT '0',
  `etoll_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `is_boarded` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True jika penumpang sudah memvalidasi geofence di titik kumpul',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `booking_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `guest_name`, `guest_phone`, `bus_id`, `booking_date`, `seat_number`, `priority_need`, `is_priority`, `status`, `payment_method`, `payment_status`, `price`, `etoll_number`, `is_completed`, `is_boarded`, `notes`, `booking_code`, `created_at`, `updated_at`) VALUES
(1, 19, NULL, NULL, 1, '2026-04-03', 3, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '1234195026014117', 1, 0, NULL, 'BUS-69CFCE55D395C', '2026-04-03 22:27:33', '2026-04-05 16:45:15'),
(2, 16, NULL, NULL, 1, '2026-04-03', 5, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '1234672830219436', 1, 0, NULL, 'BUS-69CFCE55D423A', '2026-04-03 22:27:33', '2026-04-05 16:45:15'),
(3, 18, NULL, NULL, 1, '2026-04-03', 7, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '1234140641549787', 1, 0, NULL, 'BUS-69CFCE55D4B4F', '2026-04-03 22:27:33', '2026-04-05 16:43:57'),
(4, 19, NULL, NULL, 1, '2026-04-03', 12, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '1234977752811120', 1, 0, NULL, 'BUS-69CFCE55D5386', '2026-04-03 22:27:33', '2026-04-05 16:45:15'),
(5, 16, NULL, NULL, 1, '2026-04-03', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '1234961238658971', 1, 0, NULL, 'BUS-69CFCE55D5C9C', '2026-04-03 22:27:33', '2026-04-05 16:45:15'),
(6, 19, NULL, NULL, 4, '2026-04-03', 1, NULL, 0, 'confirmed', 'qris', 'paid', 0, NULL, 1, 0, NULL, 'BUS-69CFCE55D638F', '2026-04-03 22:27:33', '2026-04-05 16:45:15'),
(7, 17, NULL, NULL, 4, '2026-04-03', 2, NULL, 0, 'confirmed', 'qris', 'paid', 0, NULL, 1, 0, NULL, 'BUS-69CFCE55D6B28', '2026-04-03 22:27:33', '2026-04-05 16:45:15'),
(8, 18, NULL, NULL, 4, '2026-04-03', 8, NULL, 0, 'confirmed', 'qris', 'paid', 0, NULL, 1, 0, NULL, 'BUS-69CFCE55D743F', '2026-04-03 22:27:33', '2026-04-05 16:43:57'),
(9, 17, NULL, NULL, 4, '2026-04-03', 16, NULL, 0, 'confirmed', 'qris', 'paid', 0, NULL, 1, 0, NULL, 'BUS-69CFCE55D7B8E', '2026-04-03 22:27:33', '2026-04-05 16:45:15'),
(10, 16, NULL, NULL, 2, '2026-04-03', 4, NULL, 0, 'confirmed', 'qris', 'paid', 0, NULL, 1, 0, NULL, 'BUS-69CFCE55D8402', '2026-04-03 22:27:33', '2026-04-05 16:45:15'),
(11, 19, NULL, NULL, 2, '2026-04-03', 9, NULL, 0, 'confirmed', 'qris', 'paid', 0, NULL, 1, 0, NULL, 'BUS-69CFCE55D8B67', '2026-04-03 22:27:33', '2026-04-05 16:45:15'),
(12, 19, NULL, NULL, 2, '2026-04-03', 11, NULL, 0, 'confirmed', 'qris', 'paid', 0, NULL, 1, 0, NULL, 'BUS-69CFCE55D9224', '2026-04-03 22:27:33', '2026-04-05 16:45:15'),
(13, 17, NULL, NULL, 2, '2026-04-03', 17, NULL, 0, 'confirmed', 'qris', 'paid', 0, NULL, 1, 0, NULL, 'BUS-69CFCE55D9A89', '2026-04-03 22:27:33', '2026-04-05 16:45:15'),
(14, 16, NULL, NULL, 2, '2026-04-03', 18, NULL, 0, 'confirmed', 'qris', 'paid', 0, NULL, 1, 0, NULL, 'BUS-69CFCE55DA63C', '2026-04-03 22:27:33', '2026-04-05 16:45:15'),
(15, 19, NULL, NULL, 10, '2026-04-04', 9, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '7359575937185732', 1, 0, NULL, 'BUS-69CFE825D2357', '2026-04-04 00:17:41', '2026-04-04 20:24:03'),
(16, 19, NULL, NULL, 10, '2026-04-04', 13, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '7359575937185732', 1, 0, NULL, 'BUS-69CFE825D40B6', '2026-04-04 00:17:41', '2026-04-04 20:24:03'),
(17, 19, NULL, NULL, 5, '2026-04-04', 13, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '4317834095969125', 1, 0, NULL, 'BUS-69CFE87C30ABD', '2026-04-04 00:19:08', '2026-04-04 00:22:30'),
(18, 19, NULL, NULL, 12, '2026-04-04', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '3976268113868924', 1, 0, NULL, 'BUS-69D0ED0F1679B', '2026-04-04 18:50:55', '2026-04-04 20:09:58'),
(19, 19, NULL, NULL, 12, '2026-04-04', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '3976268113868924', 1, 0, NULL, 'BUS-69D0ED0F17302', '2026-04-04 18:50:55', '2026-04-04 20:09:58'),
(20, 19, NULL, NULL, 12, '2026-04-04', 12, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '3976268113868924', 1, 0, NULL, 'BUS-69D0ED0F17D75', '2026-04-04 18:50:55', '2026-04-04 20:09:58'),
(21, 19, NULL, NULL, 12, '2026-04-04', 16, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '3976268113868924', 1, 0, NULL, 'BUS-69D0ED0F18693', '2026-04-04 18:50:55', '2026-04-04 20:09:58'),
(22, 19, NULL, NULL, 9, '2026-04-04', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '4036303551192416', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D106521E76C', '2026-04-04 20:38:42', '2026-04-04 20:40:09'),
(23, 19, NULL, NULL, 9, '2026-04-04', 14, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '4036303551192416', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D106521F0A0', '2026-04-04 20:38:42', '2026-04-04 20:40:09'),
(24, 19, NULL, NULL, 9, '2026-04-04', 13, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '4036303551192416', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D106521F88C', '2026-04-04 20:38:42', '2026-04-04 20:40:09'),
(25, 19, NULL, NULL, 9, '2026-04-04', 16, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '4036303551192416', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D106521FEE7', '2026-04-04 20:38:42', '2026-04-04 20:40:09'),
(26, 19, NULL, NULL, 13, '2026-04-04', 12, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '4761966824324663', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D106F4B15BD', '2026-04-04 20:41:24', '2026-04-04 20:43:03'),
(27, 19, NULL, NULL, 13, '2026-04-04', 16, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '4761966824324663', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D106F4B24FA', '2026-04-04 20:41:24', '2026-04-04 20:43:03'),
(28, 19, NULL, NULL, 13, '2026-04-04', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '4761966824324663', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D106F4B2DD9', '2026-04-04 20:41:24', '2026-04-04 20:43:03'),
(29, 19, NULL, NULL, 13, '2026-04-04', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '4761966824324663', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D106F4B355B', '2026-04-04 20:41:24', '2026-04-04 20:43:03'),
(30, 19, NULL, NULL, 5, '2026-04-05', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '5349012656052405', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D136239AD46', '2026-04-05 00:02:43', '2026-04-05 00:03:51'),
(31, 19, NULL, NULL, 5, '2026-04-05', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '5349012656052405', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D136239B8CC', '2026-04-05 00:02:43', '2026-04-05 00:03:51'),
(32, 19, NULL, NULL, 5, '2026-04-05', 7, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '5349012656052405', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D136239BF1F', '2026-04-05 00:02:43', '2026-04-05 00:03:51'),
(33, 19, NULL, NULL, 5, '2026-04-05', 8, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '5349012656052405', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D136239C918', '2026-04-05 00:02:43', '2026-04-05 00:03:51'),
(34, 19, NULL, NULL, 10, '2026-04-05', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '0092637985337557', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D13F310CFA6', '2026-04-05 00:41:21', '2026-04-05 00:42:33'),
(35, 19, NULL, NULL, 10, '2026-04-05', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '0092637985337557', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D13F310D80E', '2026-04-05 00:41:21', '2026-04-05 00:42:33'),
(36, 19, NULL, NULL, 10, '2026-04-05', 16, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '0092637985337557', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D13F310E0A0', '2026-04-05 00:41:21', '2026-04-05 00:42:33'),
(37, 19, NULL, NULL, 10, '2026-04-05', 19, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '0092637985337557', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D13F310E659', '2026-04-05 00:41:21', '2026-04-05 00:42:33'),
(38, 19, NULL, NULL, 3, '2026-04-05', 13, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '1266620997413298', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D13FA9274A5', '2026-04-05 00:43:21', '2026-04-05 00:44:48'),
(39, 19, NULL, NULL, 3, '2026-04-05', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '1266620997413298', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D13FA92808F', '2026-04-05 00:43:21', '2026-04-05 00:44:48'),
(40, 19, NULL, NULL, 3, '2026-04-05', 19, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '1266620997413298', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D13FA928E33', '2026-04-05 00:43:21', '2026-04-05 00:44:48'),
(41, 19, NULL, NULL, 3, '2026-04-05', 18, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '1266620997413298', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D13FA929832', '2026-04-05 00:43:21', '2026-04-05 00:44:48'),
(42, 19, NULL, NULL, 7, '2026-04-05', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '2195296682290793', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D1424160D6F', '2026-04-05 00:54:25', '2026-04-05 00:57:33'),
(43, 19, NULL, NULL, 7, '2026-04-05', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '2195296682290793', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D1424161A35', '2026-04-05 00:54:25', '2026-04-05 00:57:33'),
(44, 19, NULL, NULL, 7, '2026-04-05', 12, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '2195296682290793', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D14241621FF', '2026-04-05 00:54:25', '2026-04-05 00:57:33'),
(45, 19, NULL, NULL, 7, '2026-04-05', 16, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '2195296682290793', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D142416279D', '2026-04-05 00:54:25', '2026-04-05 00:57:33'),
(46, 19, NULL, NULL, 13, '2026-04-05', 7, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '6673705880159380', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D15AF5E313A', '2026-04-05 02:39:49', '2026-04-05 02:41:10'),
(47, 19, NULL, NULL, 13, '2026-04-05', 18, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '6673705880159380', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D15AF5E3B5B', '2026-04-05 02:39:49', '2026-04-05 02:41:10'),
(48, 19, NULL, NULL, 13, '2026-04-05', 14, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '6673705880159380', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D15AF5E4391', '2026-04-05 02:39:49', '2026-04-05 02:41:10'),
(49, 19, NULL, NULL, 13, '2026-04-05', 10, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '6673705880159380', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D15AF5E4E1B', '2026-04-05 02:39:49', '2026-04-05 02:41:10'),
(50, 16, NULL, NULL, 12, '2026-04-05', 1, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '4747262439734181', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D2031FED2E5', '2026-04-05 14:37:19', '2026-04-05 14:38:46'),
(51, 16, NULL, NULL, 13, '2026-04-05', 5, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '2494719088871800', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D203ADBF25E', '2026-04-05 14:39:41', '2026-04-05 14:41:05'),
(52, 16, NULL, NULL, 12, '2026-04-05', 16, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '4951405318676568', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D20720E7488', '2026-04-05 14:54:24', '2026-04-05 14:55:39'),
(53, 16, NULL, NULL, 12, '2026-04-05', 5, NULL, 0, 'confirmed', 'etoll', 'paid', 0, '0859612496642209', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D20B0A50DCB', '2026-04-05 15:11:06', '2026-04-05 15:12:33'),
(54, 18, NULL, NULL, 4, '2026-04-05', 16, NULL, 0, 'confirmed', 'etoll', 'paid', 5000, '6978348088621335', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D21231339D5', '2026-04-05 15:41:37', '2026-04-05 16:14:58'),
(55, 18, NULL, NULL, 9, '2026-04-05', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 5000, '1510543143310562', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D21A7AB1BDC', '2026-04-05 16:16:58', '2026-04-05 16:18:13'),
(56, 18, NULL, NULL, 11, '2026-04-05', 13, NULL, 0, 'confirmed', 'etoll', 'paid', 5000, '8119126800399140', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D21D24E84DB', '2026-04-05 16:28:20', '2026-04-05 16:29:39'),
(57, 18, NULL, NULL, 5, '2026-04-05', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 5000, '2258883267582328', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D21DCA2E5A8', '2026-04-05 16:31:06', '2026-04-05 16:57:52'),
(58, 18, NULL, NULL, 8, '2026-04-05', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 5000, '7273193279604597', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D224208180D', '2026-04-05 16:58:08', '2026-04-05 16:59:49'),
(59, 18, NULL, NULL, 5, '2026-04-05', 14, NULL, 0, 'confirmed', 'etoll', 'paid', 5000, '4993677959171586', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D227B82488E', '2026-04-05 17:13:28', '2026-04-05 17:14:57'),
(60, 18, NULL, NULL, 2, '2026-04-05', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 5000, '3618844391712847', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D2292D312E2', '2026-04-05 17:19:41', '2026-04-05 17:21:16'),
(61, 19, NULL, NULL, 13, '2026-04-05', 18, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '1132614389497986', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D233957E9D4', '2026-04-05 18:04:05', '2026-04-05 18:19:07'),
(62, 19, NULL, NULL, 6, '2026-04-05', 18, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '8421354026802758', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D233A4B9633', '2026-04-05 18:04:20', '2026-04-05 18:06:07'),
(63, 19, NULL, NULL, 9, '2026-04-05', 16, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '3131914862724369', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D233B97BE23', '2026-04-05 18:04:41', '2026-04-05 18:08:07'),
(64, 19, NULL, NULL, 6, '2026-04-05', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '9610473021377913', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D2379906C24', '2026-04-05 18:21:13', '2026-04-05 18:23:01'),
(65, 19, NULL, NULL, 6, '2026-04-05', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '9610473021377913', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D2379907D91', '2026-04-05 18:21:13', '2026-04-05 18:23:01'),
(66, 19, NULL, NULL, 6, '2026-04-05', 16, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '9610473021377913', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D237990889D', '2026-04-05 18:21:13', '2026-04-05 18:23:01'),
(67, 19, NULL, NULL, 6, '2026-04-05', 12, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '9610473021377913', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D2379908FBB', '2026-04-05 18:21:13', '2026-04-05 18:23:01'),
(68, 19, NULL, NULL, 11, '2026-04-05', 16, NULL, 0, 'confirmed', 'qris', 'paid', 2000, NULL, 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D2505426A64', '2026-04-05 20:06:44', '2026-04-05 20:07:39'),
(69, 19, NULL, NULL, 11, '2026-04-05', 15, NULL, 0, 'confirmed', 'qris', 'paid', 2000, NULL, 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D25054276CD', '2026-04-05 20:06:44', '2026-04-05 20:07:39'),
(70, 19, NULL, NULL, 5, '2026-04-05', 19, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '9475206009752952', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D27CE5D3D5A', '2026-04-05 23:16:53', '2026-04-05 23:18:06'),
(71, 19, NULL, NULL, 5, '2026-04-05', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '9475206009752952', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D27CE5D492E', '2026-04-05 23:16:53', '2026-04-05 23:18:06'),
(72, 19, NULL, NULL, 5, '2026-04-05', 16, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '9475206009752952', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D27CE5D50E9', '2026-04-05 23:16:53', '2026-04-05 23:18:06'),
(73, 19, NULL, NULL, 5, '2026-04-05', 20, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '9475206009752952', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D27CE5D594F', '2026-04-05 23:16:53', '2026-04-05 23:18:06'),
(74, 17, NULL, NULL, 8, '2026-04-05', 6, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '1739914780945599', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D27D4F39173', '2026-04-05 23:18:39', '2026-04-05 23:20:03'),
(75, 17, NULL, NULL, 8, '2026-04-05', 7, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '1739914780945599', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D27D4F39CDF', '2026-04-05 23:18:39', '2026-04-05 23:20:03'),
(76, 17, NULL, NULL, 8, '2026-04-05', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '1739914780945599', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D27D4F3A716', '2026-04-05 23:18:39', '2026-04-05 23:20:03'),
(77, 17, NULL, NULL, 8, '2026-04-05', 10, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '1739914780945599', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D27D4F3ACFD', '2026-04-05 23:18:39', '2026-04-05 23:20:03'),
(78, 19, NULL, NULL, 9, '2026-04-05', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '4492217783033680', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D28172485B3', '2026-04-05 23:36:18', '2026-04-05 23:37:37'),
(79, 19, NULL, NULL, 9, '2026-04-05', 16, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '4492217783033680', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D2817248F5C', '2026-04-05 23:36:18', '2026-04-05 23:37:37'),
(80, 19, NULL, NULL, 9, '2026-04-05', 12, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '4492217783033680', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D2817249865', '2026-04-05 23:36:18', '2026-04-05 23:37:37'),
(81, 19, NULL, NULL, 9, '2026-04-05', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '4492217783033680', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D281724A071', '2026-04-05 23:36:18', '2026-04-05 23:37:37'),
(82, 19, NULL, NULL, 5, '2026-04-06', 7, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '8372689973396986', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D33DB975BE9', '2026-04-06 12:59:37', '2026-04-06 13:01:15'),
(83, 19, NULL, NULL, 5, '2026-04-06', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '8372689973396986', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D33DB97666C', '2026-04-06 12:59:37', '2026-04-06 13:01:15'),
(84, 19, NULL, NULL, 5, '2026-04-06', 6, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '8372689973396986', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D33DB976EE7', '2026-04-06 12:59:37', '2026-04-06 13:01:15'),
(85, 19, NULL, NULL, 5, '2026-04-06', 10, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '8372689973396986', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D33DB977717', '2026-04-06 12:59:37', '2026-04-06 13:01:15'),
(86, 19, NULL, NULL, 2, '2026-04-06', 16, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '1062311026625755', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D33F5C70F40', '2026-04-06 13:06:36', '2026-04-07 11:02:40'),
(87, 19, NULL, NULL, 2, '2026-04-06', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '1062311026625755', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D33F5C71C9C', '2026-04-06 13:06:36', '2026-04-07 11:02:40'),
(88, 19, NULL, NULL, 2, '2026-04-06', 14, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '1062311026625755', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D33F5C7272F', '2026-04-06 13:06:36', '2026-04-07 11:02:40'),
(89, 19, NULL, NULL, 1, '2026-04-07', 7, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '7391644819956057', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D478E61640C', '2026-04-07 11:24:22', '2026-04-07 11:25:50'),
(90, 19, NULL, NULL, 1, '2026-04-07', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '7391644819956057', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D478E616E41', '2026-04-07 11:24:22', '2026-04-07 11:25:50'),
(91, 19, NULL, NULL, 1, '2026-04-07', 12, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '7391644819956057', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D478E617497', '2026-04-07 11:24:22', '2026-04-07 11:25:50'),
(92, 19, NULL, NULL, 1, '2026-04-07', 8, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '7391644819956057', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D478E617B8C', '2026-04-07 11:24:22', '2026-04-07 11:25:50'),
(93, 19, NULL, NULL, 13, '2026-04-07', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '1359856836722641', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D47AA45989A', '2026-04-07 11:31:48', '2026-04-07 19:09:53'),
(94, 19, NULL, NULL, 7, '2026-04-07', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '5287005521794955', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D4EA9D44EDE', '2026-04-07 19:29:33', '2026-04-07 19:31:16'),
(95, 19, NULL, NULL, 7, '2026-04-07', 12, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '5287005521794955', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D4EA9D45ACC', '2026-04-07 19:29:33', '2026-04-07 19:31:16'),
(96, 19, NULL, NULL, 7, '2026-04-07', 16, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '5287005521794955', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D4EA9D46313', '2026-04-07 19:29:33', '2026-04-07 19:31:16'),
(97, 19, NULL, NULL, 7, '2026-04-07', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '5287005521794955', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D4EA9D468DE', '2026-04-07 19:29:33', '2026-04-07 19:31:16'),
(98, 19, NULL, NULL, 3, '2026-04-07', 9, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '9336617663908334', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D50EE512131', '2026-04-07 22:04:21', '2026-04-07 22:17:39'),
(99, 19, NULL, NULL, 3, '2026-04-07', 6, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '9336617663908334', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D50EE512C9A', '2026-04-07 22:04:21', '2026-04-07 22:17:39'),
(100, 19, NULL, NULL, 3, '2026-04-07', 5, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '9336617663908334', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D50EE5133CF', '2026-04-07 22:04:21', '2026-04-07 22:17:39'),
(101, 19, NULL, NULL, 3, '2026-04-07', 10, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '9336617663908334', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D50EE5141C0', '2026-04-07 22:04:21', '2026-04-07 22:17:39'),
(102, 19, NULL, NULL, 13, '2026-04-07', 7, NULL, 0, 'confirmed', 'qris', 'paid', 2000, NULL, 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D51913D1B1B', '2026-04-07 22:47:47', '2026-04-07 22:49:31'),
(103, 19, NULL, NULL, 13, '2026-04-07', 8, NULL, 0, 'confirmed', 'qris', 'paid', 2000, NULL, 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D51913D2BC1', '2026-04-07 22:47:47', '2026-04-07 22:49:31'),
(104, 19, NULL, NULL, 13, '2026-04-07', 12, NULL, 0, 'confirmed', 'qris', 'paid', 2000, NULL, 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D51913D3461', '2026-04-07 22:47:47', '2026-04-07 22:49:31'),
(105, 19, NULL, NULL, 13, '2026-04-07', 11, NULL, 0, 'confirmed', 'qris', 'paid', 2000, NULL, 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D51913D3B5D', '2026-04-07 22:47:47', '2026-04-07 22:49:31'),
(106, 19, NULL, NULL, 7, '2026-04-07', 18, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '0302221912211603', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D51E2F1DEBC', '2026-04-07 23:09:35', '2026-04-07 23:11:02'),
(107, 19, NULL, NULL, 7, '2026-04-07', 19, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '0302221912211603', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D51E2F1E847', '2026-04-07 23:09:35', '2026-04-07 23:11:02'),
(108, 19, NULL, NULL, 7, '2026-04-07', 17, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '0302221912211603', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D51E2F1EDF6', '2026-04-07 23:09:35', '2026-04-07 23:11:02'),
(109, 19, NULL, NULL, 7, '2026-04-07', 20, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '0302221912211603', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D51E2F1F526', '2026-04-07 23:09:35', '2026-04-07 23:11:02'),
(110, 19, NULL, NULL, 2, '2026-04-07', 5, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '0146535855728032', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D51EA6B2B41', '2026-04-07 23:11:34', '2026-04-07 23:17:59'),
(111, 19, NULL, NULL, 2, '2026-04-07', 6, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '0146535855728032', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D51EA6B456E', '2026-04-07 23:11:34', '2026-04-07 23:17:59'),
(112, 19, NULL, NULL, 2, '2026-04-07', 9, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '0146535855728032', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D51EA6B4DD7', '2026-04-07 23:11:34', '2026-04-07 23:17:59'),
(113, 19, NULL, NULL, 10, '2026-04-07', 14, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '0338089283084416', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D521017D907', '2026-04-07 23:21:37', '2026-04-07 23:21:40'),
(114, 19, NULL, NULL, 4, '2026-04-07', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '0058729441019277', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D52800D1EAC', '2026-04-07 23:51:28', '2026-04-08 00:03:20'),
(115, 19, NULL, NULL, 4, '2026-04-07', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '0058729441019277', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D52800D2CDE', '2026-04-07 23:51:28', '2026-04-08 00:03:20'),
(116, 19, NULL, NULL, 4, '2026-04-07', 16, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '0058729441019277', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D52800D35FF', '2026-04-07 23:51:28', '2026-04-08 00:03:20'),
(117, 19, NULL, NULL, 7, '2026-04-08', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '3711041712839280', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D52AF0AFFCC', '2026-04-08 00:04:00', '2026-04-08 00:05:09'),
(118, 19, NULL, NULL, 7, '2026-04-08', 14, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '3711041712839280', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D52AF0B0B4C', '2026-04-08 00:04:00', '2026-04-08 00:05:09'),
(119, 19, NULL, NULL, 7, '2026-04-08', 18, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '3711041712839280', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D52AF0B1378', '2026-04-08 00:04:00', '2026-04-08 00:05:09'),
(120, 18, NULL, NULL, 5, '2026-04-08', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 5000, '2567515535300018', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D54E2E90B5E', '2026-04-08 02:34:22', '2026-04-08 02:37:36'),
(121, 19, NULL, NULL, 8, '2026-04-08', 8, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '3532067911721308', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D5D92A4AE40', '2026-04-08 12:27:22', '2026-04-08 12:29:25'),
(122, 19, NULL, NULL, 8, '2026-04-08', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '3532067911721308', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D5D92A4B539', '2026-04-08 12:27:22', '2026-04-08 12:29:25'),
(123, 19, NULL, NULL, 8, '2026-04-08', 12, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '3532067911721308', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D5D92A4BADD', '2026-04-08 12:27:22', '2026-04-08 12:29:25'),
(124, 19, NULL, NULL, 8, '2026-04-08', 16, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '3532067911721308', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D5D92A4C021', '2026-04-08 12:27:22', '2026-04-08 12:29:25'),
(125, 18, NULL, NULL, 1, '2026-04-08', 9, NULL, 0, 'cancelled', 'etoll', 'paid', 5000, '6281533284774204', 0, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D5E5FA65F12', '2026-04-08 13:22:02', '2026-04-08 13:22:22'),
(126, 18, NULL, NULL, 9, '2026-04-08', 12, NULL, 0, 'cancelled', 'qris', 'paid', 5000, NULL, 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D5E64DCF78A', '2026-04-08 13:23:25', '2026-04-08 13:28:06'),
(127, 18, NULL, NULL, 11, '2026-04-08', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 5000, '1851730342028396', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D5E79E1D119', '2026-04-08 13:29:02', '2026-04-08 13:30:43'),
(128, 18, NULL, NULL, 2, '2026-04-08', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 5000, '9589066479280430', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa | Kebutuhan Prioritas: LOW', 'BUS-69D5E8AEEEF9A', '2026-04-08 13:33:34', '2026-04-08 15:20:20'),
(129, 19, NULL, NULL, 7, '2026-04-08', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '1828505203027452', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D5E91B7638C', '2026-04-08 13:35:23', '2026-04-08 13:36:21'),
(130, 19, NULL, NULL, 7, '2026-04-08', 7, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '0819012883865799', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D5EF078225A', '2026-04-08 14:00:39', '2026-04-08 14:25:55'),
(131, 19, NULL, NULL, 10, '2026-04-08', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '9369326935901879', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D6019D7398C', '2026-04-08 15:19:57', '2026-04-08 15:21:58'),
(132, NULL, 'saddam', '9898', 4, '2026-04-08', 15, NULL, 0, 'cancelled', NULL, 'paid', 5000, NULL, 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D64B8E0B063', '2026-04-08 20:35:26', '2026-04-08 22:27:45'),
(133, 18, NULL, NULL, 8, '2026-04-08', 15, NULL, 0, 'cancelled', 'etoll', 'paid', 5000, '8440234160427221', 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D64DD1138D8', '2026-04-08 20:45:05', '2026-04-08 21:37:33'),
(134, NULL, 'alif', '678', 1, '2026-04-08', 6, NULL, 0, 'cancelled', NULL, 'paid', 5000, NULL, 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D64E23736E2', '2026-04-08 20:46:27', '2026-04-08 20:51:00'),
(135, 17, NULL, NULL, 6, '2026-04-08', 6, NULL, 0, 'cancelled', 'etoll', 'paid', 2000, '2669805550602861', 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D657BC2A2A7', '2026-04-08 21:27:24', '2026-04-08 21:27:58'),
(136, 17, NULL, NULL, 6, '2026-04-08', 7, NULL, 0, 'cancelled', 'etoll', 'paid', 2000, '2669805550602861', 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D657BC2ADF5', '2026-04-08 21:27:24', '2026-04-08 21:27:58'),
(137, 17, NULL, NULL, 6, '2026-04-08', 18, NULL, 0, 'cancelled', 'etoll', 'paid', 2000, '2669805550602861', 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D657BC2B483', '2026-04-08 21:27:24', '2026-04-08 21:27:58'),
(138, 17, NULL, NULL, 6, '2026-04-08', 19, NULL, 0, 'cancelled', 'etoll', 'paid', 2000, '2669805550602861', 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D657BC2BA46', '2026-04-08 21:27:24', '2026-04-08 21:27:58'),
(139, 17, NULL, NULL, 8, '2026-04-08', 11, NULL, 0, 'cancelled', 'etoll', 'paid', 2000, '5182873732125693', 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D65A06925B4', '2026-04-08 21:37:10', '2026-04-08 21:37:33'),
(140, 17, NULL, NULL, 8, '2026-04-08', 7, NULL, 0, 'cancelled', 'etoll', 'paid', 2000, '5182873732125693', 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D65A0693020', '2026-04-08 21:37:10', '2026-04-08 21:37:33'),
(141, 17, NULL, NULL, 8, '2026-04-08', 14, NULL, 0, 'cancelled', 'etoll', 'paid', 2000, '2610758355938186', 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D65A9ACD791', '2026-04-08 21:39:38', '2026-04-08 21:39:39'),
(142, 17, NULL, NULL, 10, '2026-04-08', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '6984717879752699', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D65AC34B07B', '2026-04-08 21:40:19', '2026-04-08 21:40:23'),
(143, 17, NULL, NULL, 10, '2026-04-08', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '6984717879752699', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D65AC34BCDB', '2026-04-08 21:40:19', '2026-04-08 21:40:23'),
(144, 17, NULL, NULL, 10, '2026-04-08', 16, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '6984717879752699', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D65AC34C5E2', '2026-04-08 21:40:19', '2026-04-08 21:40:23'),
(145, 17, NULL, NULL, 12, '2026-04-08', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '1818383933017591', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D65B21F273B', '2026-04-08 21:41:53', '2026-04-08 21:41:57'),
(146, 17, NULL, NULL, 12, '2026-04-08', 6, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '1818383933017591', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D65B21F3304', '2026-04-08 21:41:53', '2026-04-08 21:41:57'),
(147, 17, NULL, NULL, 12, '2026-04-08', 17, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '1818383933017591', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D65B21F3B6C', '2026-04-08 21:41:53', '2026-04-08 21:41:57'),
(148, 17, NULL, NULL, 2, '2026-04-08', 16, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '8149098526450505', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D65B8907587', '2026-04-08 21:43:37', '2026-04-08 21:43:41'),
(149, 17, NULL, NULL, 2, '2026-04-08', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '8149098526450505', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D65B8907EC7', '2026-04-08 21:43:37', '2026-04-08 21:43:41'),
(150, 17, NULL, NULL, 10, '2026-04-08', 10, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '5183537502299457', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D65ED11DEBE', '2026-04-08 21:57:37', '2026-04-08 21:57:40'),
(151, 17, NULL, NULL, 8, '2026-04-08', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '9064708246014537', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D66272D4B34', '2026-04-08 22:13:06', '2026-04-08 22:13:11'),
(152, 17, NULL, NULL, 8, '2026-04-08', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '9064708246014537', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D66272D5551', '2026-04-08 22:13:06', '2026-04-08 22:13:11'),
(153, 17, NULL, NULL, 8, '2026-04-08', 19, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '9064708246014537', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D66272D6351', '2026-04-08 22:13:06', '2026-04-08 22:13:11'),
(154, 17, NULL, NULL, 10, '2026-04-08', 11, NULL, 0, 'cancelled', 'etoll', 'paid', 2000, '4727825327661526', 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D664C47ACFA', '2026-04-08 22:23:00', '2026-04-08 22:23:12'),
(155, 17, NULL, NULL, 10, '2026-04-08', 15, NULL, 0, 'cancelled', 'etoll', 'paid', 2000, '4727825327661526', 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D664C47B597', '2026-04-08 22:23:00', '2026-04-08 22:23:12'),
(156, 17, NULL, NULL, 12, '2026-04-08', 11, NULL, 0, 'cancelled', 'etoll', 'paid', 2000, '6765632019310931', 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D6650BD1611', '2026-04-08 22:24:11', '2026-04-08 22:24:33'),
(157, 17, NULL, NULL, 12, '2026-04-08', 10, NULL, 0, 'cancelled', 'etoll', 'paid', 2000, '6765632019310931', 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D6650BD1F83', '2026-04-08 22:24:11', '2026-04-08 22:24:33'),
(158, 17, NULL, NULL, 2, '2026-04-08', 11, NULL, 0, 'cancelled', 'etoll', 'paid', 2000, '4906989456562084', 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D66566A7180', '2026-04-08 22:25:42', '2026-04-08 22:26:27'),
(159, 17, NULL, NULL, 2, '2026-04-08', 15, NULL, 0, 'cancelled', 'etoll', 'paid', 2000, '4906989456562084', 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D66566A7DB4', '2026-04-08 22:25:42', '2026-04-08 22:26:27'),
(160, 17, NULL, NULL, 4, '2026-04-08', 11, NULL, 0, 'cancelled', 'etoll', 'paid', 2000, '3078829284624190', 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D665C707524', '2026-04-08 22:27:19', '2026-04-08 22:27:45'),
(161, 17, NULL, NULL, 4, '2026-04-08', 12, NULL, 0, 'cancelled', 'etoll', 'paid', 2000, '3078829284624190', 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D665C707F85', '2026-04-08 22:27:19', '2026-04-08 22:27:45'),
(162, 17, NULL, NULL, 11, '2026-04-08', 5, NULL, 0, 'cancelled', 'etoll', 'paid', 2000, '2510155401439576', 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D668E1DBAC0', '2026-04-08 22:40:33', '2026-04-08 22:40:54'),
(163, 17, NULL, NULL, 7, '2026-04-08', 15, NULL, 0, 'cancelled', 'etoll', 'paid', 2000, '2258854142482388', 0, 0, 'EXPIRED: Batal Otomatis - Penumpang tidak tervalidasi pada jam keberangkatan', 'BUS-69D66C3033636', '2026-04-08 22:54:40', '2026-04-08 22:55:34'),
(164, 17, NULL, NULL, 5, '2026-04-08', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '8264572951613626', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D66DCA59C23', '2026-04-08 23:01:30', '2026-04-08 23:03:27'),
(165, 17, NULL, NULL, 5, '2026-04-08', 7, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '8264572951613626', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D66DCA5A71D', '2026-04-08 23:01:30', '2026-04-08 23:03:27'),
(166, 17, NULL, NULL, 5, '2026-04-08', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '8264572951613626', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D66DCA5B14A', '2026-04-08 23:01:30', '2026-04-08 23:03:27'),
(167, 17, NULL, NULL, 8, '2026-04-08', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '2643306526551358', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D67A1FDC3C1', '2026-04-08 23:54:07', '2026-04-08 23:56:12'),
(168, 17, NULL, NULL, 8, '2026-04-08', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '2643306526551358', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D67A1FDCD6C', '2026-04-08 23:54:07', '2026-04-08 23:56:12'),
(169, 17, NULL, NULL, 8, '2026-04-08', 7, NULL, 0, 'confirmed', 'etoll', 'paid', 2000, '2643306526551358', 1, 0, 'Arah: Unhas Gowa -> Unhas Perintis Kemerdekaan', 'BUS-69D67A1FDD297', '2026-04-08 23:54:07', '2026-04-08 23:56:12'),
(170, 18, NULL, NULL, 6, '2026-04-09', 7, NULL, 0, 'confirmed', 'etoll', 'paid', 5000, '5304321607777009', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D7423E3F523', '2026-04-09 14:07:58', '2026-04-09 14:09:30'),
(171, 18, NULL, NULL, 8, '2026-04-09', 3, NULL, 0, 'confirmed', 'qris', 'paid', 5000, NULL, 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa | Fasilitas: Prioritas Ringan/Sedang (Lansia/Hamil)', 'BUS-69D74A137FD5E', '2026-04-09 14:41:23', '2026-04-09 14:44:36'),
(172, 18, NULL, NULL, 3, '2026-04-09', 6, NULL, 0, 'confirmed', 'qris', 'paid', 5000, NULL, 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D74BB4CDAE5', '2026-04-09 14:48:20', '2026-04-09 14:49:49'),
(173, 18, NULL, NULL, 9, '2026-04-09', 14, NULL, 0, 'confirmed', 'qris', 'paid', 5000, NULL, 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D74C9B82290', '2026-04-09 14:52:11', '2026-04-09 14:53:42'),
(174, 23, NULL, NULL, 13, '2026-04-09', 3, NULL, 0, 'confirmed', 'etoll', 'subsidized', 0, '0573593143912894', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa | Fasilitas: Prioritas Tinggi — Pengguna Kursi Roda (Gratis)', 'BUS-69D778C84202D', '2026-04-09 18:00:40', '2026-04-09 18:02:12'),
(175, 23, NULL, NULL, 7, '2026-04-09', 1, NULL, 0, 'confirmed', 'qris', 'paid', 3000, NULL, 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa | Fasilitas: Kondisi Medis/Khusus Lainnya', 'BUS-69D77DD9D8DBA', '2026-04-09 18:22:17', '2026-04-09 18:23:40'),
(176, 27, NULL, NULL, 12, '2026-04-09', 2, NULL, 0, 'confirmed', 'qris', 'subsidized', 0, NULL, 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa | Fasilitas: Prioritas Tinggi — Pengguna Kursi Roda (Gratis)', 'BUS-69D79831EB1B2', '2026-04-09 20:14:41', '2026-04-09 20:16:45'),
(177, 30, NULL, NULL, 3, '2026-04-09', 3, NULL, 0, 'confirmed', 'qris', 'paid', 5000, NULL, 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa | Fasilitas: Kondisi Medis/Khusus Lainnya', 'BUS-69D79AEDBF44E', '2026-04-09 20:26:21', '2026-04-09 23:18:01'),
(178, 27, NULL, NULL, 5, '2026-04-09', 3, NULL, 0, 'confirmed', 'qris', 'paid', 5000, NULL, 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa | Fasilitas: Prioritas Ringan/Sedang (Lansia/Hamil)', 'BUS-69D79B3DA936C', '2026-04-09 20:27:41', '2026-04-09 23:18:06'),
(179, 28, NULL, NULL, 7, '2026-04-09', 3, NULL, 0, 'cancelled', 'qris', 'paid', 5000, NULL, 0, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa | Fasilitas: Prioritas Ringan/Sedang (Lansia/Hamil)', 'BUS-69D79B888051E', '2026-04-09 20:28:56', '2026-04-09 20:29:06'),
(180, 27, NULL, NULL, 7, '2026-04-10', 7, NULL, 0, 'confirmed', 'qris', 'paid', 5000, NULL, 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa', 'BUS-69D90CC05D675', '2026-04-10 22:44:16', '2026-04-10 22:46:23'),
(181, 27, NULL, NULL, 2, '2026-04-10', 15, NULL, 0, 'cancelled', 'qris', 'paid', 5000, NULL, 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]', 'BUS-69D913CD9EA48', '2026-04-10 23:14:21', '2026-04-13 19:28:53'),
(182, 27, NULL, NULL, 5, '2026-04-10', 15, NULL, 0, 'cancelled', 'qris', 'paid', 5000, NULL, 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]', 'BUS-69D9142CAA60D', '2026-04-10 23:15:56', '2026-04-13 19:28:53'),
(183, 27, NULL, NULL, 6, '2026-04-10', 15, NULL, 0, 'cancelled', 'qris', 'paid', 5000, NULL, 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]', 'BUS-69D9166DC7A7A', '2026-04-10 23:25:33', '2026-04-13 19:28:53'),
(184, 27, NULL, NULL, 8, '2026-04-10', 15, NULL, 0, 'cancelled', 'qris', 'paid', 5000, NULL, 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]', 'BUS-69D918CBD1D78', '2026-04-10 23:35:39', '2026-04-13 19:28:53'),
(185, 27, NULL, NULL, 3, '2026-04-10', 16, NULL, 0, 'confirmed', 'qris', 'paid', 5000, NULL, 1, 1, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa [Diselamatkan oleh Sopir]', 'BUS-69D919FBB21DA', '2026-04-10 23:40:43', '2026-04-10 23:42:10'),
(186, 19, NULL, NULL, 5, '2026-04-10', 15, NULL, 0, 'cancelled', 'etoll', 'paid', 3000, '5720562083614783', 1, 0, 'Arah: Unhas Perintis Kemerdekaan -> Unhas Gowa [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]', 'BUS-69D91A4DA9E65', '2026-04-10 23:42:05', '2026-04-14 00:57:39'),
(187, 19, NULL, NULL, 13, '2026-04-14', 8, NULL, 0, 'confirmed', 'etoll', 'paid', 3000, '0825355266936838', 1, 0, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]', 'BUS-69DD209F86250', '2026-04-14 00:58:07', '2026-04-14 01:25:06'),
(188, 19, NULL, NULL, 13, '2026-04-14', 7, NULL, 0, 'confirmed', 'etoll', 'paid', 3000, '0825355266936838', 1, 0, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]', 'BUS-69DD209F86A68', '2026-04-14 00:58:07', '2026-04-14 01:25:06'),
(189, 19, NULL, NULL, 13, '2026-04-14', 11, NULL, 0, 'confirmed', 'etoll', 'paid', 3000, '0825355266936838', 1, 0, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]', 'BUS-69DD209F87024', '2026-04-14 00:58:07', '2026-04-14 01:25:06'),
(190, 19, NULL, NULL, 13, '2026-04-14', 12, NULL, 0, 'confirmed', 'etoll', 'paid', 3000, '0825355266936838', 1, 0, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]', 'BUS-69DD209F876FE', '2026-04-14 00:58:07', '2026-04-14 01:25:06'),
(191, 19, NULL, NULL, 2, '2026-04-14', 7, NULL, 0, 'confirmed', 'etoll', 'paid', 3000, '8393544172326692', 1, 1, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa [Diselamatkan oleh Sopir]', 'BUS-69DD20DF1D3D0', '2026-04-14 00:59:11', '2026-04-14 01:01:03'),
(192, 19, NULL, NULL, 2, '2026-04-14', 8, NULL, 0, 'confirmed', 'etoll', 'paid', 3000, '8393544172326692', 1, 1, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa [Diselamatkan oleh Sopir]', 'BUS-69DD20DF1DC48', '2026-04-14 00:59:11', '2026-04-14 01:01:03'),
(193, 19, NULL, NULL, 2, '2026-04-14', 15, NULL, 0, 'confirmed', 'etoll', 'paid', 3000, '8393544172326692', 1, 0, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]', 'BUS-69DD20DF1E4A0', '2026-04-14 00:59:11', '2026-04-14 01:20:12'),
(194, 19, NULL, NULL, 2, '2026-04-14', 16, NULL, 0, 'confirmed', 'etoll', 'paid', 3000, '8393544172326692', 1, 0, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]', 'BUS-69DD20DF1EA81', '2026-04-14 00:59:11', '2026-04-14 01:20:12'),
(195, 19, NULL, NULL, 13, '2026-04-14', 6, NULL, 0, 'confirmed', 'etoll', 'paid', 3000, '7246384485020582', 1, 1, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa', 'BUS-69DD2A7E777E1', '2026-04-14 01:40:14', '2026-04-14 01:42:15'),
(196, 19, NULL, NULL, 13, '2026-04-14', 10, NULL, 0, 'confirmed', 'etoll', 'paid', 3000, '7246384485020582', 1, 1, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa', 'BUS-69DD2A7E78518', '2026-04-14 01:40:14', '2026-04-14 01:42:15'),
(197, 19, NULL, NULL, 13, '2026-04-14', 14, NULL, 0, 'confirmed', 'etoll', 'paid', 3000, '7246384485020582', 1, 1, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa', 'BUS-69DD2A7E78B82', '2026-04-14 01:40:14', '2026-04-14 01:42:15'),
(198, 19, NULL, NULL, 13, '2026-04-14', 13, NULL, 0, 'confirmed', 'etoll', 'paid', 3000, '7246384485020582', 1, 1, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa', 'BUS-69DD2A7E7926C', '2026-04-14 01:40:14', '2026-04-14 01:42:15'),
(199, 19, NULL, NULL, 6, '2026-04-22', 7, NULL, 0, 'confirmed', 'qris', 'paid', 3000, NULL, 1, 0, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]', 'BUS-69E8B4B2438A3', '2026-04-22 19:44:50', '2026-04-22 19:47:30'),
(200, 19, NULL, NULL, 6, '2026-04-22', 6, NULL, 0, 'confirmed', 'qris', 'paid', 3000, NULL, 1, 0, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]', 'BUS-69E8B4B24414F', '2026-04-22 19:44:50', '2026-04-22 19:47:30'),
(201, 19, NULL, NULL, 6, '2026-04-22', 10, NULL, 0, 'confirmed', 'qris', 'paid', 3000, NULL, 1, 0, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]', 'BUS-69E8B4B2447C7', '2026-04-22 19:44:50', '2026-04-22 19:47:30'),
(202, 19, NULL, NULL, 9, '2026-04-22', 11, NULL, 0, 'confirmed', 'qris', 'paid', 3000, NULL, 1, 1, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa', 'BUS-69E8B5535C6C2', '2026-04-22 19:47:31', '2026-04-22 19:49:28'),
(203, 19, NULL, NULL, 9, '2026-04-22', 10, NULL, 0, 'confirmed', 'qris', 'paid', 3000, NULL, 1, 1, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa', 'BUS-69E8B5535CFFD', '2026-04-22 19:47:31', '2026-04-22 19:49:28'),
(204, 19, NULL, NULL, 9, '2026-04-22', 18, NULL, 0, 'confirmed', 'qris', 'paid', 3000, NULL, 1, 1, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa', 'BUS-69E8B5535D75E', '2026-04-22 19:47:31', '2026-04-22 19:49:28'),
(205, 19, NULL, NULL, 8, '2026-04-22', 10, NULL, 0, 'cancelled', 'etoll', 'paid', 3000, '0354760948967800', 0, 0, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]', 'BUS-69E8C2FE4FBA6', '2026-04-22 20:45:50', '2026-04-22 20:46:47'),
(206, 19, NULL, NULL, 8, '2026-04-22', 11, NULL, 0, 'cancelled', 'etoll', 'paid', 3000, '0354760948967800', 0, 0, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]', 'BUS-69E8C2FE5066A', '2026-04-22 20:45:50', '2026-04-22 20:46:47'),
(207, 19, NULL, NULL, 8, '2026-04-22', 15, NULL, 0, 'cancelled', 'etoll', 'paid', 3000, '0354760948967800', 0, 0, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa [Batal Otomatis: Waktu konfirmasi 15 detik ke sopir telah habis]', 'BUS-69E8C2FE50EAA', '2026-04-22 20:45:50', '2026-04-22 20:46:47'),
(208, 19, NULL, NULL, 10, '2026-04-22', 10, NULL, 0, 'confirmed', 'qris', 'paid', 3000, NULL, 1, 1, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa', 'BUS-69E8C36143812', '2026-04-22 20:47:29', '2026-04-22 20:49:15'),
(209, 19, NULL, NULL, 10, '2026-04-22', 2, NULL, 0, 'confirmed', 'qris', 'paid', 3000, NULL, 1, 1, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa', 'BUS-69E8C361441B0', '2026-04-22 20:47:29', '2026-04-22 20:49:15'),
(210, 19, NULL, NULL, 10, '2026-04-22', 3, NULL, 0, 'confirmed', 'qris', 'paid', 3000, NULL, 1, 1, 'Arah: Kampus Perintis Kemerdekaan -> Kampus Gowa', 'BUS-69E8C36144928', '2026-04-22 20:47:29', '2026-04-22 20:49:15');

-- --------------------------------------------------------

--
-- Table structure for table `buses`
--

CREATE TABLE `buses` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_number` int DEFAULT NULL,
  `driver_id` bigint UNSIGNED DEFAULT NULL,
  `plate_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL DEFAULT '16',
  `route` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departure_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `arrival_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','maintenance','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `trip_status` enum('standby','jalan','istirahat') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'standby',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `current_lat` decimal(10,8) DEFAULT NULL,
  `current_lng` decimal(11,8) DEFAULT NULL,
  `current_terminal` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `departed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buses`
--

INSERT INTO `buses` (`id`, `name`, `bus_number`, `driver_id`, `plate_number`, `capacity`, `route`, `departure_time`, `arrival_time`, `description`, `image`, `status`, `trip_status`, `created_at`, `updated_at`, `current_lat`, `current_lng`, `current_terminal`, `departed_at`) VALUES
(1, 'Bus UNHAS 01', 1, 2, 'DD 1001 BK', 20, 'Perintis UNHAS → UNHAS Gowa', '21:00', '05:00', 'Armada Bus Kampus UNHAS No. 01. Rute reguler Perintis–Gowa. Ber-AC, kapasitas 20 penumpang.', NULL, 'active', 'standby', '2026-04-03 22:27:33', '2026-04-08 23:58:12', -5.13260000, 119.48800000, 'PERINTIS', NULL),
(2, 'Bus UNHAS 02', 2, 3, 'DD 1002 BK', 20, 'Perintis UNHAS → UNHAS Gowa', '05:00', '21:00', 'Armada Bus Kampus UNHAS No. 02. Rute reguler Perintis–Gowa. Ber-AC, kapasitas 20 penumpang.', NULL, 'active', 'standby', '2026-04-03 22:27:33', '2026-04-18 20:12:30', -5.13800000, 119.48850000, NULL, '2026-04-03 22:21:33'),
(3, 'Bus UNHAS 03', 3, 4, 'DD 1003 BK', 20, 'Perintis UNHAS → UNHAS Gowa', '21:00', '05:00', 'Armada Bus Kampus UNHAS No. 03. Rute reguler Perintis–Gowa. Ber-AC, kapasitas 20 penumpang.', NULL, 'active', 'standby', '2026-04-03 22:27:33', '2026-04-10 23:42:10', -5.15300000, 119.48300000, NULL, '2026-04-03 22:11:33'),
(4, 'Bus UNHAS 04', 4, 5, 'DD 1004 BK', 20, 'Perintis UNHAS → UNHAS Gowa', '05:00', '21:00', 'Armada Bus Kampus UNHAS No. 04. Rute reguler Perintis–Gowa. Ber-AC, kapasitas 20 penumpang.', NULL, 'active', 'standby', '2026-04-03 22:27:33', '2026-04-08 22:38:13', -5.13260000, 119.48800000, 'PERINTIS', NULL),
(5, 'Bus UNHAS 05', 5, 6, 'DD 1005 BK', 20, 'Perintis UNHAS → UNHAS Gowa', '21:00', '06:01', 'Armada Bus Kampus UNHAS No. 05. Rute reguler Perintis–Gowa. Ber-AC, kapasitas 20 penumpang.', NULL, 'active', 'standby', '2026-04-03 22:27:33', '2026-04-10 23:46:23', -5.17800000, 119.46800000, NULL, '2026-04-03 22:06:33'),
(6, 'Bus UNHAS 06', 6, 7, 'DD 1006 BK', 20, 'Perintis UNHAS → UNHAS Gowa', '05:00', '21:00', 'Armada Bus Kampus UNHAS No. 06. Rute reguler Perintis–Gowa. Ber-AC, kapasitas 20 penumpang.', NULL, 'active', 'standby', '2026-04-03 22:27:33', '2026-04-22 19:47:30', -5.23030000, 119.45200000, 'GOWA', NULL),
(7, 'Bus UNHAS 07', 7, 8, 'DD 1007 BK', 20, 'Perintis UNHAS → UNHAS Gowa', '21:00', '05:00', 'Armada Bus Kampus UNHAS No. 07. Rute reguler Perintis–Gowa. Ber-AC, kapasitas 20 penumpang.', NULL, 'active', 'standby', '2026-04-03 22:27:33', '2026-04-09 23:44:45', -5.13260000, 119.48800000, 'PERINTIS', NULL),
(8, 'Bus UNHAS 08', 8, 9, 'DD 1008 BK', 20, 'Perintis UNHAS → UNHAS Gowa', '05:00', '21:00', 'Armada Bus Kampus UNHAS No. 08. Rute reguler Perintis–Gowa. Ber-AC, kapasitas 20 penumpang.', NULL, 'active', 'standby', '2026-04-03 22:27:33', '2026-04-22 20:47:58', -5.21000000, 119.45500000, NULL, '2026-04-03 22:21:33'),
(9, 'Bus UNHAS 09', 9, 10, 'DD 1009 BK', 20, 'Perintis UNHAS → UNHAS Gowa', '21:00', '05:00', 'Armada Bus Kampus UNHAS No. 09. Rute reguler Perintis–Gowa. Ber-AC, kapasitas 20 penumpang.', NULL, 'active', 'standby', '2026-04-03 22:27:33', '2026-04-22 20:25:31', -5.23030000, 119.45200000, 'GOWA', NULL),
(10, 'Bus UNHAS 10', 10, 11, 'DD 1010 BK', 20, 'Perintis UNHAS → UNHAS Gowa', '05:00', '21:00', 'Armada Bus Kampus UNHAS No. 10. Rute reguler Perintis–Gowa. Ber-AC, kapasitas 20 penumpang.', NULL, 'active', 'jalan', '2026-04-03 22:27:33', '2026-04-22 23:45:28', -5.13260000, 119.48800000, 'PERINTIS', NULL),
(11, 'Bus UNHAS 11', 11, 12, 'DD 1011 BK', 20, 'Perintis UNHAS → UNHAS Gowa', '21:00', '05:00', 'Armada Bus Kampus UNHAS No. 11. Rute reguler Perintis–Gowa. Ber-AC, kapasitas 20 penumpang.', NULL, 'active', 'standby', '2026-04-03 22:27:33', '2026-04-08 23:30:45', -5.15500000, 119.48200000, NULL, '2026-04-03 22:22:33'),
(12, 'Bus UNHAS 12', 12, 13, 'DD 1012 BK', 20, 'Perintis UNHAS → UNHAS Gowa', '05:00', '21:00', 'Armada Bus Kampus UNHAS No. 12. Rute reguler Perintis–Gowa. Ber-AC, kapasitas 20 penumpang.', NULL, 'active', 'standby', '2026-04-03 22:27:33', '2026-04-10 07:53:10', -5.13260000, 119.48800000, 'PERINTIS', NULL),
(13, 'Bus UNHAS 13', 13, 14, 'DD 1013 BK', 20, 'Perintis UNHAS → UNHAS Gowa', '21:00', '05:00', 'Armada Bus Kampus UNHAS No. 13. Rute reguler Perintis–Gowa. Ber-AC, kapasitas 20 penumpang.', NULL, 'active', 'standby', '2026-04-03 22:27:33', '2026-04-14 20:36:40', -5.23030000, 119.45200000, 'GOWA', NULL),
(14, 'cobabus', NULL, NULL, 'BB 1001 DD', 20, 'Tamalanrea → Rappocini', '05:01', '07:20', 'coba aja', NULL, 'maintenance', 'standby', '2026-04-05 19:44:50', '2026-04-06 13:04:20', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bus_reports`
--

CREATE TABLE `bus_reports` (
  `id` bigint UNSIGNED NOT NULL,
  `bus_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'daily_inspection',
  `condition` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'good',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bus_reports`
--

INSERT INTO `bus_reports` (`id`, `bus_id`, `user_id`, `type`, `condition`, `notes`, `created_at`, `updated_at`) VALUES
(1, 3, 4, 'daily_inspection', 'good', 'aman kok', '2026-04-09 11:21:15', '2026-04-09 11:21:15'),
(2, 3, 4, 'daily_inspection', 'good', 'aman aja', '2026-04-09 11:21:29', '2026-04-09 11:21:29');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000001_create_buses_table', 1),
(5, '2024_01_01_000002_create_bookings_table', 1),
(6, '2026_03_29_123527_create_tips_table', 1),
(7, '2026_03_29_125600_add_trip_status_to_buses_table', 1),
(8, '2026_03_29_133632_update_bookings_table_for_guests', 1),
(9, '2026_03_30_020909_add_is_completed_to_bookings_table', 1),
(10, '2026_03_30_020909_add_is_read_to_tips_table', 1),
(11, '2026_04_02_000001_create_terminals_table', 1),
(12, '2026_04_02_000002_add_simulation_columns_to_buses_table', 1),
(13, '2026_04_02_000003_add_payment_to_bookings_table', 1),
(14, '2026_04_05_152610_add_price_to_bookings_table', 2),
(15, '2026_04_05_170525_add_user_id_to_tips_table', 3),
(16, '2026_04_08_131351_add_priority_fields_to_bookings_table', 4),
(17, '2026_04_08_131408_add_geofence_radius_to_terminals_table', 4),
(18, '2026_04_09_111111_create_bus_reports_table', 5),
(19, '2026_04_14_000000_add_is_active_to_users_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0R9xqYINM4LBOv6kr6cxZlNLpp1QduHelUf4kGkQ', NULL, '192.168.65.1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiMFZvdUQwNmk4UHl4NWhTN0NoemI0dkNFbTh4NDhPWVplckx1b09QViI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776905289),
('4TTXkrdkFERcCxjq5PZbmvVJuFslcxrjKVO6Fpue', NULL, '192.168.65.1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoia05CcVd0MzlaeWh5UWVoUXdzNXJmaDhBZVhtRVE4SHFXaHpnam1CciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776905289),
('BTwJwXT7agalywyrLjV0myf8AlcIjGgdCB1XrgAw', NULL, '192.168.65.1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicTZjS1RORWdUM1dUZUd3Z0JVTjBiN2t6TzRGTHp0aXpoSWRuSVJFeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODE4MS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODE4MS9hZG1pbi9yZXZlbnVlIjt9fQ==', 1776919828);

-- --------------------------------------------------------

--
-- Table structure for table `terminals`
--

CREATE TABLE `terminals` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `geofence_radius` int NOT NULL DEFAULT '20' COMMENT 'Radius dalam meter untuk check-in otomatis',
  `order` int NOT NULL,
  `type` enum('origin','stop','destination') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'stop',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `terminals`
--

INSERT INTO `terminals` (`id`, `name`, `code`, `lat`, `lng`, `geofence_radius`, `order`, `type`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Terminal Perintis UNHAS', 'PERINTIS', -5.1326, 119.488, 20, 1, 'origin', 'Terminal awal keberangkatan — Jl. Perintis Kemerdekaan, Tamalanrea', '2026-04-03 22:27:25', '2026-04-03 22:27:25'),
(2, 'Halte Tamalanrea Indah', 'TAMAL', -5.1456, 119.4891, 20, 2, 'stop', 'Halte Tamalanrea Indah', '2026-04-03 22:27:25', '2026-04-03 22:27:25'),
(3, 'Halte BTP / Antang', 'ANTANG', -5.1612, 119.477, 20, 3, 'stop', 'Halte kawasan BTP – Antang', '2026-04-03 22:27:25', '2026-04-03 22:27:25'),
(4, 'Halte Pallangga', 'PALLANGGA', -5.198, 119.459, 20, 4, 'stop', 'Halte Pallangga menuju Gowa', '2026-04-03 22:27:25', '2026-04-03 22:27:25'),
(5, 'UNHAS Kampus Gowa', 'GOWA', -5.2303, 119.452, 20, 5, 'destination', 'Terminal tujuan akhir — Kampus UNHAS Gowa', '2026-04-03 22:27:25', '2026-04-03 22:27:25');

-- --------------------------------------------------------

--
-- Table structure for table `tips`
--

CREATE TABLE `tips` (
  `id` bigint UNSIGNED NOT NULL,
  `bus_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `amount` int NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tips`
--

INSERT INTO `tips` (`id`, `bus_id`, `user_id`, `amount`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 5000, 1, '2026-04-03 20:27:33', '2026-04-08 20:47:00'),
(2, 1, NULL, 10000, 1, '2026-04-03 21:27:33', '2026-04-08 20:47:00'),
(3, 4, NULL, 5000, 1, '2026-04-03 19:27:33', '2026-04-08 22:27:41'),
(4, 7, NULL, 5000, 1, '2026-04-05 00:54:58', '2026-04-07 19:30:07'),
(5, 3, NULL, 5000, 1, '2026-04-05 00:55:13', '2026-04-05 00:55:17'),
(6, 12, NULL, 5000, 1, '2026-04-05 14:55:04', '2026-04-05 14:55:07'),
(7, 12, NULL, 5000, 1, '2026-04-05 15:11:36', '2026-04-05 15:11:36'),
(8, 4, NULL, 5000, 1, '2026-04-05 15:42:13', '2026-04-08 22:27:41'),
(9, 9, NULL, 5000, 1, '2026-04-05 16:17:06', '2026-04-05 23:36:48'),
(10, 11, NULL, 5000, 1, '2026-04-05 16:28:27', '2026-04-08 13:30:21'),
(11, 11, NULL, 5000, 1, '2026-04-05 16:28:34', '2026-04-08 13:30:21'),
(12, 11, NULL, 5000, 1, '2026-04-05 16:28:35', '2026-04-08 13:30:21'),
(13, 11, NULL, 5000, 1, '2026-04-05 16:28:37', '2026-04-08 13:30:21'),
(14, 11, NULL, 5000, 1, '2026-04-05 16:28:40', '2026-04-08 13:30:21'),
(15, 11, NULL, 5000, 1, '2026-04-05 16:28:40', '2026-04-08 13:30:21'),
(16, 11, NULL, 5000, 1, '2026-04-05 16:28:41', '2026-04-08 13:30:21'),
(17, 11, NULL, 5000, 1, '2026-04-05 16:28:41', '2026-04-08 13:30:21'),
(18, 11, NULL, 5000, 1, '2026-04-05 16:28:41', '2026-04-08 13:30:21'),
(19, 11, NULL, 5000, 1, '2026-04-05 16:28:42', '2026-04-08 13:30:21'),
(20, 11, NULL, 5000, 1, '2026-04-05 16:28:42', '2026-04-08 13:30:21'),
(21, 11, NULL, 5000, 1, '2026-04-05 16:28:42', '2026-04-08 13:30:21'),
(22, 11, NULL, 5000, 1, '2026-04-05 16:28:42', '2026-04-08 13:30:21'),
(23, 11, NULL, 5000, 1, '2026-04-05 16:28:52', '2026-04-08 13:30:21'),
(24, 11, NULL, 5000, 1, '2026-04-05 16:28:52', '2026-04-08 13:30:21'),
(25, 11, NULL, 5000, 1, '2026-04-05 16:28:52', '2026-04-08 13:30:21'),
(26, 11, NULL, 5000, 1, '2026-04-05 16:28:53', '2026-04-08 13:30:21'),
(27, 11, NULL, 5000, 1, '2026-04-05 16:28:53', '2026-04-08 13:30:21'),
(28, 11, NULL, 5000, 1, '2026-04-05 16:28:53', '2026-04-08 13:30:21'),
(29, 5, NULL, 5000, 1, '2026-04-05 16:31:21', '2026-04-05 18:23:06'),
(30, 5, NULL, 5000, 1, '2026-04-05 16:31:21', '2026-04-05 18:23:06'),
(31, 5, NULL, 5000, 1, '2026-04-05 16:31:22', '2026-04-05 18:23:06'),
(32, 5, NULL, 5000, 1, '2026-04-05 16:33:09', '2026-04-05 18:23:06'),
(33, 5, NULL, 5000, 1, '2026-04-05 16:33:10', '2026-04-05 18:23:06'),
(34, 5, NULL, 5000, 1, '2026-04-05 16:33:16', '2026-04-05 18:23:06'),
(35, 8, NULL, 5000, 1, '2026-04-05 16:58:41', '2026-04-05 23:19:06'),
(36, 8, NULL, 5000, 1, '2026-04-05 16:58:44', '2026-04-05 23:19:06'),
(37, 8, NULL, 5000, 1, '2026-04-05 16:58:45', '2026-04-05 23:19:06'),
(38, 8, NULL, 5000, 1, '2026-04-05 16:58:46', '2026-04-05 23:19:06'),
(39, 8, NULL, 5000, 1, '2026-04-05 16:58:47', '2026-04-05 23:19:06'),
(40, 8, NULL, 5000, 1, '2026-04-05 16:58:47', '2026-04-05 23:19:06'),
(41, 8, NULL, 5000, 1, '2026-04-05 16:58:48', '2026-04-05 23:19:06'),
(42, 8, NULL, 5000, 1, '2026-04-05 16:58:48', '2026-04-05 23:19:06'),
(43, 8, NULL, 5000, 1, '2026-04-05 16:58:48', '2026-04-05 23:19:06'),
(44, 2, 18, 5000, 1, '2026-04-05 17:20:18', '2026-04-05 17:20:20'),
(45, 6, 19, 5000, 1, '2026-04-05 18:21:59', '2026-04-08 03:00:05'),
(46, 8, 17, 5000, 1, '2026-04-05 23:19:09', '2026-04-05 23:19:13'),
(47, 5, 19, 5000, 1, '2026-04-06 13:00:25', '2026-04-06 13:00:32'),
(48, 8, 17, 5000, 1, '2026-04-08 21:37:45', '2026-04-08 21:37:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','civitas','umum','sopir') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'umum',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Transportasi', 'admin@unhas.ac.id', NULL, '$2y$12$JXWPbSLVLYvMYa/Kwb1vjez99wjHes4aac.iukkfvl/Kd972nvGQW', 'admin', 1, NULL, '2026-04-03 22:27:26', '2026-04-03 22:27:26'),
(2, 'Hasan Basri', 'sopir01@unhas.ac.id', NULL, '$2y$12$Pbs3TyaR0GMaol7XKZ2wke1V8mohCTLAoJCVpS7d.eKeMT18aSn6q', 'sopir', 1, NULL, '2026-04-03 22:27:26', '2026-04-03 22:27:26'),
(3, 'Mukhtar Lede', 'sopir02@unhas.ac.id', NULL, '$2y$12$vLdS/1thDpT6p3sILyvRQehJUZ5T4BncYR0StJEM77Pi5F6aji.le', 'sopir', 1, NULL, '2026-04-03 22:27:26', '2026-04-03 22:27:26'),
(4, 'Syamsul Hadi', 'sopir03@unhas.ac.id', NULL, '$2y$12$DT.uizcwEa6Kl5rppsS0N.8BelBG6mb6aNt31YUZMogo1jQwK8S8O', 'sopir', 1, NULL, '2026-04-03 22:27:27', '2026-04-03 22:27:27'),
(5, 'Ridwan Karim', 'sopir04@unhas.ac.id', NULL, '$2y$12$ViWb8vAxDHV3SMxKRaIPV.uHXkHMVUtiSehLYlcVhtHQORdhu3UOW', 'sopir', 1, NULL, '2026-04-03 22:27:27', '2026-04-03 22:27:27'),
(6, 'Ambo Dalle', 'sopir05@unhas.ac.id', NULL, '$2y$12$TH2jz/lHymAxHfaP5a6B4OthHC2FGaFp0G/ra8fbbrcQ3Qt7u/AWi', 'sopir', 1, NULL, '2026-04-03 22:27:28', '2026-04-03 22:27:28'),
(7, 'Nurdin Sialana', 'sopir06@unhas.ac.id', NULL, '$2y$12$TKrsc55kkiTVwbtHxqr7Tel5zMR7BvaldYqieoETMzGEkIYN3P4AS', 'sopir', 1, NULL, '2026-04-03 22:27:28', '2026-04-03 22:27:28'),
(8, 'Saharuddin', 'sopir07@unhas.ac.id', NULL, '$2y$12$yVjvBqgjLHyQ9QhxZKf6NO7WIzCQ3/3OtQsawkMt8CR5Lk1awRgYq', 'sopir', 1, NULL, '2026-04-03 22:27:29', '2026-04-03 22:27:29'),
(9, 'Andi Mappa', 'sopir08@unhas.ac.id', NULL, '$2y$12$1zDxmMuBZEHjif8rlTBytu3FrMgH/ZSf8rTdVQwSt6/4biMYOwn2m', 'sopir', 1, NULL, '2026-04-03 22:27:29', '2026-04-03 22:27:29'),
(10, 'Baharuddin', 'sopir09@unhas.ac.id', NULL, '$2y$12$GX0yiF5qEs.yqLQ8ckJNMOmXnz0yJZA2.ayuduH9ce0gwiEVhGy5C', 'sopir', 1, NULL, '2026-04-03 22:27:29', '2026-04-03 22:27:29'),
(11, 'Suardi Usman', 'sopir10@unhas.ac.id', NULL, '$2y$12$BnHh.IXjxCC7GUVzCkbJT.9h/WrSAkmSV4HPjhBDhlA0CUcdJ4GYu', 'sopir', 1, NULL, '2026-04-03 22:27:30', '2026-04-03 22:27:30'),
(12, 'Junaidi Rahman', 'sopir11@unhas.ac.id', NULL, '$2y$12$LCmfNbO/TM3XJ.e.Cppy8OE322/U8W3HRtsXXDgXbLhPJkZQf1.xi', 'sopir', 1, NULL, '2026-04-03 22:27:30', '2026-04-03 22:27:30'),
(13, 'Kamarudding', 'sopir12@unhas.ac.id', NULL, '$2y$12$Zh4w55douBV73sfoLNxQg.I6bBwcQt0z.DDOlYBqdkJlXJllMlsRG', 'sopir', 1, NULL, '2026-04-03 22:27:31', '2026-04-05 16:14:59'),
(14, 'Arifin Dg. Nai', 'sopir13@unhas.ac.id', NULL, '$2y$12$TaOZg8hYJsvOLq.oG8CRiuET0axR0dhkg9fBN2Bx7/KSEoXGEeIgO', 'sopir', 1, NULL, '2026-04-03 22:27:31', '2026-04-03 22:27:31'),
(15, 'Muh. Budi Santoso', 'budi@unhas.ac.id', NULL, '$2y$12$bDgU4USbiB7nqGGYNw5HruGswdw2Sta2rrbV640fMhLAAcoiybMTq', 'civitas', 1, NULL, '2026-04-03 22:27:32', '2026-04-03 22:27:32'),
(16, 'Ani Wulandari', 'ani@gmail.com', NULL, '$2y$12$r1TjS9Jn96vsova6uSwRDeuPx09HTLTOlVVlB6oR3.dRdXJUPjuie', 'umum', 1, NULL, '2026-04-03 22:27:32', '2026-04-03 22:27:32'),
(17, 'Dr. Surya Darma', 'surya@unhas.ac.id', NULL, '$2y$12$3Kwp4nMP.mGO5TvLXw9rjuylWfvi.oqTXzSiwkMvgEodE/nodSR5G', 'civitas', 1, NULL, '2026-04-03 22:27:32', '2026-04-03 22:27:32'),
(18, 'Andi Fatimah', 'andi@yahoo.com', NULL, '$2y$12$LBTz/vtstWuT.orkYMM.He2wfCLXLlA6ynnPv8NSexrl/u42AV5PG', 'umum', 1, NULL, '2026-04-03 22:27:33', '2026-04-03 22:27:33'),
(19, 'Riska Amalia', 'riska@unhas.ac.id', NULL, '$2y$12$S7l/f7vIj/E2tGhsn6OjWOa1UCECYo6qk/HMQFcpsTAp5mmVG5Taq', 'civitas', 1, NULL, '2026-04-03 22:27:33', '2026-04-03 22:27:33'),
(21, 'cobasopirs', 'cobasopir@gmail.com', NULL, '$2y$12$m.wHWUL3ABB49PQ5nJCNvOvZ7wS7FKMxjHTjLV/Trn2bqedCir1i2', 'sopir', 1, NULL, '2026-04-05 19:42:57', '2026-04-09 18:36:24'),
(22, 'coba01', 'coba01@gmail.com', NULL, '$2y$12$Abx0BXT70JjnsAL9.A7vIuxFi8v96bKhZAARzkeFKLbk3mmuAaAhi', 'umum', 1, NULL, '2026-04-05 19:46:42', '2026-04-05 19:46:42'),
(23, 'Ahmad Fauzan (Kursi Roda)', 'ahmad.difabel@unhas.ac.id', NULL, '$2y$12$EHzAI74ZQhxzICHXPzJHwuHP27bM5V7WlwN.EEkvJTNXHafr.wrm2', 'civitas', 1, NULL, '2026-04-09 14:58:59', '2026-04-09 14:58:59'),
(24, 'Prof. Darwis Lanjut Usia', 'darwis.lansia@unhas.ac.id', NULL, '$2y$12$MqEVZ2JCfAvxkGAeVSk7Uua0uT1L.rKylVNwKZHGtWTYkDoe.m/qS', 'civitas', 1, NULL, '2026-04-09 14:58:59', '2026-04-09 14:58:59'),
(25, 'Siti Rahma Medis Khusus', 'siti.medis@unhas.ac.id', NULL, '$2y$12$wJeN14Np3XH77iiZMewUXOEzh3KRmKeNS1B.sOXv8SzdkoB92qYUq', 'civitas', 1, NULL, '2026-04-09 14:59:00', '2026-04-09 14:59:00'),
(26, 'Nurul Aisyah (Hamil)', 'nurul.hamil@unhas.ac.id', NULL, '$2y$12$OrMAu5RwXCuEFkSc.9EX.esPzRYCpiiNiWZpWash9Z34LveRD53ZO', 'civitas', 1, NULL, '2026-04-09 14:59:00', '2026-04-09 14:59:00'),
(27, 'Baharuddin (Kursi Roda)', 'bahar.difabel@gmail.com', NULL, '$2y$12$B4Zwyy6VtWdeLonYlTg0nOFD/GfxG2QEyLVMNG7k2ueTEcP4tlMxu', 'umum', 1, NULL, '2026-04-09 14:59:00', '2026-04-09 14:59:00'),
(28, 'Hj. Ramlah Lanjut Usia', 'ramlah.lansia@gmail.com', NULL, '$2y$12$iA4UcHszqP54PGgCui8UrOZEgq.AK3UcDYc74t6/CL.sbq71Jjd.u', 'umum', 1, NULL, '2026-04-09 14:59:00', '2026-04-09 14:59:00'),
(29, 'Irwan Syah Medis', 'irwan.medis@yahoo.com', NULL, '$2y$12$QTlOHuHzLlZo4I3cYtM3aeHunJNSMpkIU/qUTr425XuMxBFw8fPMi', 'umum', 1, NULL, '2026-04-09 14:59:01', '2026-04-09 14:59:01'),
(30, 'Fatimah Az-Zahra (Hamil)', 'fatimah.hamil@gmail.com', NULL, '$2y$12$hTVnei3mDDr7RvE1kS9gyenzNQkGDfkU07oMWrBssM5HMCfXZrqqm', 'umum', 1, NULL, '2026-04-09 14:59:01', '2026-04-09 14:59:01'),
(31, 'coba aja kok', 'cobaaja@unhas.ac.id', NULL, '$2y$12$AjmLLpk9XcX0gPatEeqapepgOWkM7Tb6rp9Xv9nCuxnPDyrK1Ifae', 'umum', 0, NULL, '2026-04-13 13:41:53', '2026-04-13 13:46:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_booking_code_unique` (`booking_code`),
  ADD KEY `bookings_user_id_foreign` (`user_id`),
  ADD KEY `bookings_bus_id_foreign` (`bus_id`);

--
-- Indexes for table `buses`
--
ALTER TABLE `buses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `buses_plate_number_unique` (`plate_number`),
  ADD KEY `buses_driver_id_foreign` (`driver_id`);

--
-- Indexes for table `bus_reports`
--
ALTER TABLE `bus_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_reports_bus_id_foreign` (`bus_id`),
  ADD KEY `bus_reports_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `terminals`
--
ALTER TABLE `terminals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `terminals_code_unique` (`code`);

--
-- Indexes for table `tips`
--
ALTER TABLE `tips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tips_bus_id_foreign` (`bus_id`),
  ADD KEY `tips_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT for table `buses`
--
ALTER TABLE `buses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `bus_reports`
--
ALTER TABLE `bus_reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `terminals`
--
ALTER TABLE `terminals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tips`
--
ALTER TABLE `tips`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_bus_id_foreign` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `buses`
--
ALTER TABLE `buses`
  ADD CONSTRAINT `buses_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bus_reports`
--
ALTER TABLE `bus_reports`
  ADD CONSTRAINT `bus_reports_bus_id_foreign` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bus_reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tips`
--
ALTER TABLE `tips`
  ADD CONSTRAINT `tips_bus_id_foreign` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tips_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
