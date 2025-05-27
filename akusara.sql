-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 07:50 AM
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
-- Database: `akusara`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups`
--

CREATE TABLE `auth_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_groups`
--

INSERT INTO `auth_groups` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Akses Semua Fitur\r\n', '2025-05-18 16:34:50', '2025-05-23 16:12:57'),
(2, 'user', 'Lihat Data', '2025-05-18 16:34:50', '2025-05-23 16:13:06');

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups_users`
--

CREATE TABLE `auth_groups_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `auth_groups_users`
--

INSERT INTO `auth_groups_users` (`id`, `user_id`, `group`, `created_at`) VALUES
(18, 38, 'admin', '2025-05-20 10:41:59'),
(29, 49, 'user', '2025-05-22 09:52:49'),
(33, 53, 'admin', '2025-05-27 09:28:09');

-- --------------------------------------------------------

--
-- Table structure for table `auth_identities`
--

CREATE TABLE `auth_identities` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `secret` varchar(255) NOT NULL,
  `secret2` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_used_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `auth_identities`
--

INSERT INTO `auth_identities` (`id`, `user_id`, `type`, `secret`, `secret2`, `created_at`, `updated_at`, `last_used_at`) VALUES
(9, 38, 'email_password', '', '$2y$12$vAMpglI5iujo8qoEUrMrmuRZWQq4cpt7XnZaT6axAAXvf3.jVHg/y', '2025-05-20 10:41:58', '2025-05-27 09:23:02', '2025-05-27 09:23:02'),
(28, 49, 'email_password', '', '$2y$12$dcdnJG/699zAqZpFZQl.seovLR2ROMW4ni4E4hFpcyM0.qxMfe62m', '2025-05-22 09:52:49', '2025-05-27 09:30:42', '2025-05-27 09:30:42'),
(32, 53, 'email_password', '', '$2y$12$59V3opXga6fsFYd3RQoheuSq2TQY/recW0XUNBg7H8ywb8bpd1gr6', '2025-05-27 09:28:09', '2025-05-27 09:40:16', '2025-05-27 09:40:16');

-- --------------------------------------------------------

--
-- Table structure for table `auth_logins`
--

CREATE TABLE `auth_logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `auth_logins`
--

INSERT INTO `auth_logins` (`id`, `ip_address`, `user_agent`, `id_type`, `identifier`, `user_id`, `date`, `success`) VALUES
(1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'email_password', 'superadmin@admin.com', 1, '2025-03-14 08:35:51', 1),
(2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'email_password', 'syifa@gmail.com', 3, '2025-03-14 08:50:28', 1),
(3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'email_password', 'syifa@gmail.com', 3, '2025-03-14 08:56:28', 1),
(4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'email_password', 'syifa@gmail.com', 3, '2025-03-14 08:56:59', 1),
(5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'email_password', 'superadmin@admin.com', 1, '2025-03-14 08:57:30', 1),
(6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'syifa', 3, '2025-03-14 09:13:27', 1),
(7, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-14 09:13:56', 1),
(8, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'syifa', 3, '2025-03-14 10:08:34', 1),
(9, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-14 10:08:55', 1),
(10, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-14 10:46:21', 1),
(11, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-14 10:46:39', 1),
(12, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-14 12:58:48', 1),
(13, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-14 15:39:48', 1),
(14, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-16 13:56:31', 1),
(15, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-16 13:58:07', 1),
(16, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-16 20:22:44', 1),
(17, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-17 08:19:02', 1),
(18, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', NULL, '2025-03-17 10:25:23', 0),
(19, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-17 10:25:37', 1),
(20, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-17 21:37:05', 1),
(21, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-17 21:37:06', 1),
(22, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-19 14:52:34', 1),
(23, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-19 14:52:35', 1),
(24, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-19 23:19:10', 1),
(25, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-20 08:35:12', 1),
(26, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-20 08:40:45', 1),
(27, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-21 02:16:19', 1),
(28, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-21 08:49:28', 1),
(29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'syifa', 3, '2025-03-21 14:06:05', 1),
(30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-22 12:30:59', 1),
(31, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-22 12:31:42', 1),
(32, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-22 18:50:55', 1),
(33, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-23 11:52:27', 1),
(34, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-23 23:50:44', 1),
(35, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-24 01:42:15', 1),
(36, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-24 01:44:33', 1),
(37, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-24 08:32:53', 1),
(38, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-26 16:39:42', 1),
(39, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-26 21:27:32', 1),
(40, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', NULL, '2025-03-27 13:28:54', 0),
(41, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-27 13:29:08', 1),
(42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-27 21:57:44', 1),
(43, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-28 11:32:08', 1),
(44, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-28 22:14:58', 1),
(45, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-29 09:19:58', 1),
(46, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-29 09:19:59', 1),
(47, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-29 14:07:04', 1),
(48, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-29 14:17:15', 1),
(49, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-29 15:07:41', 1),
(50, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-29 15:11:44', 1),
(51, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-03-29 21:41:39', 1),
(52, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-01 09:34:24', 1),
(53, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-01 09:34:26', 1),
(54, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-01 20:01:59', 1),
(55, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-01 20:35:59', 1),
(56, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-01 20:42:11', 1),
(57, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-01 20:47:15', 1),
(58, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-02 11:16:44', 1),
(59, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-03 05:09:23', 1),
(60, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-03 05:09:24', 1),
(61, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-03 10:32:18', 1),
(62, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-03 10:32:19', 1),
(63, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-03 18:52:36', 1),
(64, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-04 22:57:52', 1),
(65, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-05 11:57:34', 1),
(66, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-05 19:28:17', 1),
(67, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-05 23:43:36', 1),
(68, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-06 10:23:47', 1),
(69, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-06 12:09:30', 1),
(70, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-06 16:53:19', 1),
(71, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-07 19:53:45', 1),
(72, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-08 09:44:43', 1),
(73, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-08 14:24:19', 1),
(74, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-08 21:36:19', 1),
(75, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-08 21:36:20', 1),
(76, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-09 09:59:22', 1),
(77, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-09 18:52:16', 1),
(78, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-09 20:58:37', 1),
(79, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-10 09:25:09', 1),
(80, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-10 20:31:51', 1),
(81, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 3, '2025-04-10 22:08:36', 1),
(82, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-10 22:11:53', 1),
(83, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', NULL, '2025-04-10 22:32:37', 0),
(84, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', NULL, '2025-04-10 22:32:45', 0),
(85, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-10 22:40:07', 1),
(86, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-10 22:40:46', 1),
(87, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-11 10:16:15', 1),
(88, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-11 11:24:31', 1),
(89, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-11 11:32:58', 1),
(90, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-11 11:36:57', 1),
(91, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-11 12:01:08', 1),
(92, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-11 12:22:01', 1),
(93, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-11 12:23:03', 1),
(94, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-11 12:24:30', 1),
(95, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-11 12:25:11', 1),
(96, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-11 12:27:26', 1),
(97, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-11 12:54:29', 1),
(98, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-11 12:54:45', 1),
(99, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-11 13:13:41', 1),
(100, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-11 13:19:06', 1),
(101, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-11 13:20:29', 1),
(102, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-11 13:25:36', 1),
(103, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-11 14:53:49', 1),
(104, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-11 15:03:33', 1),
(105, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', NULL, '2025-04-11 15:16:41', 0),
(106, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-11 15:16:51', 1),
(107, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-11 15:17:24', 1),
(108, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-11 15:35:54', 1),
(109, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', NULL, '2025-04-11 15:52:42', 0),
(110, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-11 15:52:48', 1),
(111, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-11 16:03:00', 1),
(112, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-11 21:31:45', 1),
(113, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-14 17:18:42', 1),
(114, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-14 18:53:48', 1),
(115, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-14 21:29:39', 1),
(116, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-14 21:54:39', 1),
(117, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-15 11:17:01', 1),
(118, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-15 22:08:48', 1),
(119, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmi', NULL, '2025-04-16 10:52:48', 0),
(120, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-16 10:52:59', 1),
(121, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-16 14:26:54', 1),
(122, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-17 14:24:33', 1),
(123, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-19 13:14:47', 1),
(124, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-19 19:57:48', 1),
(125, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-20 10:20:01', 1),
(126, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-20 16:03:57', 1),
(127, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-22 10:00:55', 1),
(128, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-23 13:59:31', 1),
(129, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-23 21:34:33', 1),
(130, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-24 14:55:15', 1),
(131, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-28 08:18:20', 1),
(132, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-28 21:23:55', 1),
(133, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-28 23:44:46', 1),
(134, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-29 09:25:52', 1),
(135, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-29 09:29:53', 1),
(136, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-29 09:30:06', 1),
(137, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-29 09:30:36', 1),
(138, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-04-29 10:26:56', 1),
(139, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-29 17:48:53', 1),
(140, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', NULL, '2025-04-29 21:19:09', 0),
(141, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-29 21:19:22', 1),
(142, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-30 08:17:29', 1),
(143, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-04-30 21:38:28', 1),
(144, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-01 09:41:20', 1),
(145, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-01 13:08:50', 1),
(146, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-01 14:22:26', 1),
(147, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-01 14:22:55', 1),
(148, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-01 19:33:28', 1),
(149, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-02 09:07:32', 1),
(150, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-02 09:07:33', 1),
(151, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-02 21:43:02', 1),
(152, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-03 09:42:19', 1),
(153, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-03 13:12:39', 1),
(154, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-03 17:25:50', 1),
(155, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-03 20:02:13', 1),
(156, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-04 10:16:31', 1),
(157, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-04 17:52:31', 1),
(158, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-04 17:59:17', 1),
(159, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-04 21:09:22', 1),
(160, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-05 08:16:15', 1),
(161, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-05 08:35:49', 1),
(162, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-05 08:51:28', 1),
(163, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-05 08:51:47', 1),
(164, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-05 10:39:25', 1),
(165, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', NULL, '2025-05-05 15:58:20', 0),
(166, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', NULL, '2025-05-05 15:58:30', 0),
(167, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', NULL, '2025-05-05 15:58:50', 0),
(168, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-05 15:58:59', 1),
(169, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-06 09:50:58', 1),
(170, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', NULL, '2025-05-06 21:32:07', 0),
(171, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-06 21:32:13', 1),
(172, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-07 08:53:28', 1),
(173, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-08 07:57:06', 1),
(174, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-09 08:13:12', 1),
(175, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'SYIFA', NULL, '2025-05-09 21:33:48', 0),
(176, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'SYIFA', 8, '2025-05-09 21:33:58', 1),
(177, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-10 10:24:08', 1),
(178, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-11 09:01:36', 1),
(179, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 8, '2025-05-12 09:23:11', 1),
(180, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'syifa', NULL, '2025-05-12 20:13:41', 0),
(181, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-12 20:13:46', 1),
(182, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-12 21:51:30', 1),
(183, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 8, '2025-05-13 09:01:32', 1),
(184, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 8, '2025-05-13 14:25:34', 1),
(185, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', NULL, '2025-05-13 19:56:10', 0),
(186, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 8, '2025-05-13 19:56:16', 1),
(187, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 8, '2025-05-14 08:04:48', 1),
(188, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-14 21:17:23', 1),
(189, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-15 10:41:49', 1),
(190, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', NULL, '2025-05-15 20:38:27', 0),
(191, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', NULL, '2025-05-15 20:38:43', 0),
(192, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 8, '2025-05-15 20:38:59', 1),
(193, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-16 08:30:33', 1),
(194, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-16 21:57:56', 1),
(195, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-17 09:36:10', 1),
(196, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'syifa', 8, '2025-05-17 17:34:09', 1),
(197, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-17 22:10:34', 1),
(198, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-17 22:11:53', 1),
(199, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-17 22:39:46', 1),
(200, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-17 23:13:51', 1),
(201, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-17 23:25:09', 1),
(202, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'syifa', NULL, '2025-05-18 09:30:02', 0),
(203, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'syifa', NULL, '2025-05-18 09:30:09', 0),
(204, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', NULL, '2025-05-18 09:30:20', 0),
(205, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 8, '2025-05-18 09:30:33', 1),
(206, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'superadmin', NULL, '2025-05-18 13:20:41', 0),
(207, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-18 13:20:46', 1),
(208, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-18 13:29:29', 1),
(209, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-18 13:29:45', 1),
(210, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-18 13:37:13', 1),
(211, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2025-05-18 13:47:03', 1),
(212, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 17, '2025-05-18 19:54:41', 1),
(213, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 17, '2025-05-18 20:03:28', 1),
(214, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 1, '2025-05-18 20:06:42', 1),
(215, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 17, '2025-05-18 20:07:05', 1),
(216, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 17, '2025-05-18 21:09:03', 1),
(217, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 1, '2025-05-18 21:11:19', 1),
(218, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', NULL, '2025-05-19 08:27:53', 0),
(219, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 1, '2025-05-19 08:28:00', 1),
(220, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'KUROMI', 34, '2025-05-19 14:46:28', 1),
(221, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'KUROMI', 34, '2025-05-19 14:49:05', 1),
(222, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'KUROMI', 34, '2025-05-20 08:57:38', 1),
(223, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'RILAKKUMA', NULL, '2025-05-20 09:29:08', 0),
(224, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'KUROMI', 34, '2025-05-20 09:29:58', 1),
(225, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'KUROMI', 37, '2025-05-20 10:41:27', 1),
(226, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-20 10:59:26', 1),
(227, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-20 11:05:45', 0),
(228, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-20 11:05:52', 0),
(229, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-20 11:06:00', 1),
(230, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-20 11:06:26', 0),
(231, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-20 11:06:41', 0),
(232, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-20 11:06:51', 0),
(233, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-20 11:12:29', 0),
(234, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-20 11:13:15', 0),
(235, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-20 11:24:51', 1),
(236, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 40, '2025-05-20 11:25:25', 1),
(237, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'KUROMI', 37, '2025-05-20 11:25:40', 1),
(238, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-20 11:26:11', 0),
(239, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-20 11:26:18', 0),
(240, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-20 11:29:12', 0),
(241, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-20 11:32:45', 0),
(242, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', NULL, '2025-05-20 11:33:00', 0),
(243, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-20 11:33:06', 1),
(244, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-20 11:33:38', 0),
(245, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-20 11:40:19', 0),
(246, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-20 11:40:31', 1),
(247, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-20 11:41:02', 0),
(248, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-20 11:48:46', 0),
(249, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-20 11:48:54', 1),
(250, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-20 11:49:21', 0),
(251, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-20 11:52:45', 1),
(252, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 40, '2025-05-20 11:53:22', 1),
(253, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-20 11:53:47', 1),
(254, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-20 11:54:17', 1),
(255, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-20 20:28:08', 1),
(256, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-21 09:31:55', 1),
(257, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-21 18:09:15', 1),
(258, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'AINUN', 41, '2025-05-21 19:55:35', 1),
(259, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-21 19:56:02', 1),
(260, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-21 20:05:14', 1),
(261, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'AINUN', 41, '2025-05-21 20:20:26', 1),
(262, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'AINUN', NULL, '2025-05-21 20:21:43', 0),
(263, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-21 20:21:57', 1),
(264, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-21 22:42:25', 0),
(265, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-21 22:42:35', 1),
(266, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-21 22:43:07', 0),
(267, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-21 22:43:15', 0),
(268, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-21 22:43:25', 1),
(269, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-21 22:43:56', 0),
(270, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-21 22:44:09', 1),
(271, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-21 22:55:51', 0),
(272, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-21 22:55:56', 0),
(273, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'KUROMI', 37, '2025-05-21 22:56:04', 1),
(274, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'KUROMI', NULL, '2025-05-21 22:56:44', 0),
(275, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'KUROMI', NULL, '2025-05-21 22:56:51', 0),
(276, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'KUROMI', NULL, '2025-05-21 22:56:57', 0),
(277, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-21 23:07:06', 1);
INSERT INTO `auth_logins` (`id`, `ip_address`, `user_agent`, `id_type`, `identifier`, `user_id`, `date`, `success`) VALUES
(278, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'KUROMI', NULL, '2025-05-21 23:07:32', 0),
(279, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-21 23:07:44', 1),
(280, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'KUROMI', NULL, '2025-05-21 23:08:15', 0),
(281, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-21 23:13:31', 1),
(282, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'KUROMI', NULL, '2025-05-21 23:14:03', 0),
(283, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'KUROMI', NULL, '2025-05-21 23:14:10', 0),
(284, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'KUROMI', NULL, '2025-05-21 23:14:19', 0),
(285, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-21 23:14:47', 1),
(286, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'KUROMI', NULL, '2025-05-21 23:15:15', 0),
(287, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-21 23:32:04', 1),
(288, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-21 23:32:38', 0),
(289, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-21 23:32:44', 0),
(290, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-21 23:32:49', 0),
(291, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-21 23:34:29', 1),
(292, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-21 23:35:02', 0),
(293, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-21 23:35:31', 0),
(294, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-21 23:36:01', 1),
(295, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-21 23:37:21', 0),
(296, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 04:58:37', 0),
(297, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 04:58:50', 1),
(298, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 05:02:46', 0),
(299, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 05:04:30', 1),
(300, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 05:04:58', 0),
(301, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 05:06:29', 1),
(302, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 05:07:00', 0),
(303, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 05:11:43', 1),
(304, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 05:12:15', 0),
(305, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 05:22:53', 1),
(306, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 05:23:30', 0),
(307, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 05:26:26', 1),
(308, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 05:32:01', 0),
(309, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 05:33:26', 1),
(310, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'AUL', NULL, '2025-05-22 05:44:03', 0),
(311, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA ', 38, '2025-05-22 05:55:42', 1),
(312, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'AINUN', 43, '2025-05-22 05:56:21', 1),
(313, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'AINUN', NULL, '2025-05-22 05:56:45', 0),
(314, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 05:56:56', 1),
(315, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 06:17:23', 0),
(316, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 06:22:55', 1),
(317, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 06:23:27', 0),
(318, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 06:24:58', 1),
(319, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 06:25:31', 0),
(320, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 06:33:02', 1),
(321, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 06:33:29', 0),
(322, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 06:39:53', 1),
(323, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 06:40:19', 0),
(324, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 06:50:44', 1),
(325, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 06:52:50', 1),
(326, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 06:53:25', 0),
(327, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 06:54:12', 1),
(328, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 06:55:42', 0),
(329, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 06:59:17', 1),
(330, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 07:00:06', 0),
(331, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 07:00:56', 0),
(332, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 07:01:04', 1),
(333, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 07:01:47', 0),
(334, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 07:03:57', 1),
(335, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 07:05:28', 0),
(336, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 07:06:51', 1),
(337, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 07:07:18', 0),
(338, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 07:13:39', 1),
(339, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 07:28:24', 1),
(340, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 07:29:32', 0),
(341, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 07:37:43', 1),
(342, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 40, '2025-05-22 07:39:08', 1),
(343, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 07:39:28', 0),
(344, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 40, '2025-05-22 07:39:36', 1),
(345, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 07:39:53', 1),
(346, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 07:40:17', 0),
(347, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 07:46:48', 0),
(348, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 40, '2025-05-22 07:46:53', 1),
(349, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 07:47:36', 0),
(350, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 40, '2025-05-22 07:47:41', 1),
(351, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 07:50:02', 0),
(352, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 40, '2025-05-22 07:50:07', 1),
(353, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:15:50', 0),
(354, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 40, '2025-05-22 08:16:21', 1),
(355, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:16:53', 0),
(356, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 40, '2025-05-22 08:16:58', 1),
(357, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:18:55', 0),
(358, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 40, '2025-05-22 08:19:01', 1),
(359, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:19:51', 0),
(360, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:21:55', 0),
(361, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:22:00', 0),
(362, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:22:11', 0),
(363, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:22:21', 0),
(364, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:22:26', 0),
(365, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:22:47', 0),
(366, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:22:52', 0),
(367, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:22:57', 0),
(368, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 08:23:04', 1),
(369, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:23:31', 0),
(370, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:23:36', 0),
(371, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 08:26:22', 1),
(372, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:26:48', 0),
(373, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:26:53', 0),
(374, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:27:50', 0),
(375, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', NULL, '2025-05-22 08:29:11', 0),
(376, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 08:29:17', 1),
(377, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 08:34:00', 1),
(378, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 46, '2025-05-22 08:35:29', 1),
(379, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:35:55', 0),
(380, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 46, '2025-05-22 08:39:44', 1),
(381, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 08:40:24', 1),
(382, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:44:15', 0),
(383, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 48, '2025-05-22 08:44:24', 1),
(384, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:46:31', 0),
(385, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:46:44', 0),
(386, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:46:52', 0),
(387, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:47:01', 0),
(388, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:47:05', 0),
(389, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:47:09', 0),
(390, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 08:47:13', 0),
(391, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 08:47:20', 1),
(392, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 09:27:49', 0),
(393, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 09:27:53', 0),
(394, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 09:28:04', 1),
(395, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 09:33:29', 0),
(396, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 09:43:23', 0),
(397, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 09:43:29', 1),
(398, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 09:45:01', 0),
(399, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 09:46:02', 1),
(400, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 09:47:10', 0),
(401, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 09:48:42', 0),
(402, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-22 09:48:48', 0),
(403, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 09:49:23', 1),
(404, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 09:51:59', 1),
(405, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 49, '2025-05-22 09:53:50', 1),
(406, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 09:56:25', 1),
(407, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 49, '2025-05-22 09:56:56', 1),
(408, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 49, '2025-05-22 09:57:27', 1),
(409, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 10:56:31', 1),
(410, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'JUN', 50, '2025-05-22 10:57:07', 1),
(411, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'JUN', 50, '2025-05-22 10:57:32', 1),
(412, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-22 21:26:41', 1),
(413, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', NULL, '2025-05-23 07:59:40', 0),
(414, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-23 07:59:46', 1),
(415, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-23 21:30:20', 1),
(416, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-24 14:41:26', 1),
(417, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 49, '2025-05-24 15:32:20', 1),
(418, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-24 15:44:27', 0),
(419, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-24 15:45:22', 1),
(420, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-24 16:20:06', 0),
(421, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 49, '2025-05-24 16:20:20', 1),
(422, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-24 16:21:21', 0),
(423, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-24 16:21:29', 0),
(424, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-24 16:21:34', 0),
(425, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-24 16:21:42', 0),
(426, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-24 16:22:18', 1),
(427, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-24 16:22:47', 0),
(428, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-24 16:23:08', 0),
(429, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-24 16:23:16', 0),
(430, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-24 16:23:25', 0),
(431, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-24 16:24:07', 0),
(432, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-24 16:27:19', 0),
(433, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-24 16:27:27', 0),
(434, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-24 16:27:47', 0),
(435, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-24 16:27:58', 1),
(436, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-24 16:28:31', 0),
(437, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-24 16:52:21', 0),
(438, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-24 16:52:28', 1),
(439, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'AUL', 51, '2025-05-24 16:54:02', 1),
(440, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'AUL', NULL, '2025-05-24 16:54:24', 0),
(441, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'AUL', NULL, '2025-05-24 16:54:29', 0),
(442, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-24 17:02:55', 1),
(443, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'AUL', 52, '2025-05-24 17:03:41', 1),
(444, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'AUL', 52, '2025-05-24 17:04:06', 1),
(445, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-24 17:04:19', 1),
(446, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'AUL', 52, '2025-05-24 17:04:46', 1),
(447, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'AUL', 52, '2025-05-24 17:05:09', 1),
(448, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-24 17:05:25', 0),
(449, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-24 17:05:32', 0),
(450, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-24 17:05:42', 1),
(451, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 49, '2025-05-24 17:06:11', 1),
(452, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 49, '2025-05-24 17:17:01', 1),
(453, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-24 23:30:25', 1),
(454, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-25 12:59:18', 0),
(455, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 49, '2025-05-25 12:59:24', 1),
(456, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 49, '2025-05-25 13:09:47', 1),
(457, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-25 13:12:54', 0),
(458, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-25 13:13:00', 0),
(459, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-25 13:13:08', 1),
(460, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 49, '2025-05-25 13:14:00', 1),
(461, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 49, '2025-05-25 13:14:28', 1),
(462, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-25 13:17:32', 0),
(463, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-25 13:17:42', 0),
(464, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-25 13:17:51', 1),
(465, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-25 13:18:51', 0),
(466, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', NULL, '2025-05-25 13:19:06', 0),
(467, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-25 13:19:22', 1),
(468, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 49, '2025-05-25 13:30:13', 1),
(469, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 49, '2025-05-25 13:30:44', 1),
(470, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 49, '2025-05-25 13:37:40', 1),
(471, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-26 13:17:35', 1),
(472, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 49, '2025-05-26 18:47:33', 1),
(473, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-26 18:48:23', 1),
(474, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-26 20:07:20', 1),
(475, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'ABC', NULL, '2025-05-26 20:36:53', 0),
(476, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-26 21:28:36', 1),
(477, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'SYIFA', 38, '2025-05-27 09:23:02', 1),
(478, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'LISTHIS', NULL, '2025-05-27 09:28:22', 0),
(479, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'LISTHIA', 53, '2025-05-27 09:28:30', 1),
(480, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'LISTHIA', 53, '2025-05-27 09:30:20', 1),
(481, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'DOKY', 49, '2025-05-27 09:30:42', 1),
(482, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'username', 'LISTHIA', 53, '2025-05-27 09:40:16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions_users`
--

CREATE TABLE `auth_permissions_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `permission` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_remember_tokens`
--

CREATE TABLE `auth_remember_tokens` (
  `id` int(11) UNSIGNED NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashedValidator` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `expires` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_token_logins`
--

CREATE TABLE `auth_token_logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bahan`
--

CREATE TABLE `bahan` (
  `bahan_id` int(11) NOT NULL,
  `grup_id` int(11) NOT NULL,
  `kode_bahan` varchar(50) NOT NULL,
  `nama_bahan` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bahan`
--

INSERT INTO `bahan` (`bahan_id`, `grup_id`, `kode_bahan`, `nama_bahan`, `created_at`, `updated_at`) VALUES
(1, 1, 'ND', 'NYLON CRINKLE DOFF', '2025-03-21 01:57:30', '2025-05-26 15:23:22'),
(2, 1, 'CM', 'COMBED', '2025-03-21 02:54:48', '2025-03-21 03:08:30'),
(3, 1, 'CD', 'CARDED', '2025-03-21 02:58:59', '2025-03-21 02:58:59'),
(4, 1, 'FC', 'FLEECE', '2025-03-21 03:00:37', '2025-03-21 03:00:37'),
(6, 1, 'FT', 'FRENCH TERRY', '2025-03-21 03:40:03', '2025-03-21 03:40:03'),
(9, 1, 'TS', 'TASLAN MILKY', '2025-03-22 15:48:46', '2025-03-22 15:48:46'),
(10, 1, 'BT', 'BABY TERRY', '2025-03-22 15:49:03', '2025-03-22 15:49:03'),
(11, 1, 'NC', 'NYLON CRINKLE ', '2025-03-22 15:49:23', '2025-03-22 15:49:23'),
(12, 1, 'DF', 'DOUBLE FACE', '2025-03-22 15:49:39', '2025-03-22 15:49:39'),
(13, 1, 'OT', 'OTTOMAN', '2025-03-22 15:49:52', '2025-03-22 15:49:52'),
(14, 1, 'DM', 'DIAMOND', '2025-03-22 15:50:06', '2025-03-22 15:50:06'),
(15, 1, 'LC', 'LACOSTE', '2025-04-20 03:28:04', '2025-04-20 03:28:04'),
(16, 1, 'DP', 'DESPO', '2025-04-20 03:35:48', '2025-04-20 03:36:08'),
(17, 1, 'DK', 'DOUBLE KNIT', '2025-04-20 03:36:23', '2025-04-20 03:36:30'),
(18, 1, 'PN', 'POPLIN', '2025-04-20 03:36:44', '2025-04-20 03:36:44'),
(19, 1, 'PE', 'PE', '2025-04-20 03:36:58', '2025-04-23 16:17:30'),
(20, 1, 'TC', 'TC', '2025-04-20 03:37:11', '2025-04-20 03:37:11'),
(21, 1, 'OE', 'OE', '2025-04-20 03:37:22', '2025-04-20 03:37:22'),
(22, 1, 'DA', 'DIADORA', '2025-04-20 03:37:34', '2025-04-20 03:37:34'),
(23, 1, 'CV', 'CVC', '2025-04-20 03:37:44', '2025-04-20 03:37:44'),
(24, 2, 'KK', 'KAIN KERAS STP 725', '2025-04-20 03:38:03', '2025-04-20 03:38:03'),
(25, 2, 'RB', 'RIB', '2025-04-20 03:38:13', '2025-04-20 03:38:13'),
(26, 2, 'KF', 'KAIN KERAS 2016F', '2025-04-20 03:38:30', '2025-04-20 03:38:30'),
(27, 2, 'KR', 'KRAH', '2025-04-20 03:38:57', '2025-04-20 03:38:57'),
(28, 2, 'MS', 'MANSET', '2025-04-20 03:39:10', '2025-04-20 03:39:10'),
(29, 2, 'BR', 'BUR', '2025-04-20 03:39:25', '2025-04-20 03:39:25'),
(30, 3, 'KE', 'KARET ELASTIC', '2025-04-20 03:39:43', '2025-04-20 03:39:43'),
(31, 3, 'VT', 'VITERBAND', '2025-04-20 03:41:02', '2025-04-20 03:41:02'),
(32, 3, 'BN', 'BENANG', '2025-04-20 03:41:13', '2025-04-20 03:41:13'),
(33, 3, 'ZP', 'ZIPPER', '2025-04-20 03:41:25', '2025-04-20 03:41:25'),
(34, 3, 'TL', 'TALIKUR', '2025-04-20 03:41:36', '2025-04-20 03:41:36'),
(35, 3, 'MI', 'MATA ITIK', '2025-04-20 03:41:49', '2025-04-20 03:41:49'),
(36, 3, 'KC', 'KANCING', '2025-04-20 03:42:03', '2025-04-20 03:42:03'),
(37, 3, 'LB', 'LABEL', '2025-04-20 03:42:12', '2025-04-20 03:42:12'),
(38, 4, 'SZ', 'STIKER SIZE', '2025-04-20 03:42:25', '2025-04-20 03:42:25'),
(39, 4, 'HT', 'HANTAG', '2025-04-20 03:42:49', '2025-04-20 03:42:49'),
(40, 4, 'BC', 'BARCODE SHARKS DWS', '2025-04-20 03:43:08', '2025-04-20 03:43:08'),
(41, 5, 'PO', 'PLASTIK OPP', '2025-04-20 03:44:25', '2025-04-20 03:44:25'),
(42, 5, 'DS', 'DUS', '2025-04-20 03:44:35', '2025-04-20 03:44:35'),
(43, 5, 'TH', 'TALI HANTAG', '2025-04-20 03:44:47', '2025-04-20 03:44:47'),
(44, 5, 'TR', 'TALI RAPIA', '2025-04-20 03:45:09', '2025-04-20 03:45:09'),
(45, 5, 'PL', 'PLASTIK', '2025-04-20 03:45:24', '2025-04-20 03:45:24'),
(46, 5, 'PP', 'PLASTIK PP', '2025-04-20 03:45:41', '2025-04-20 03:45:41'),
(47, 6, 'MT', 'MATRESS', '2025-04-20 03:45:59', '2025-04-20 03:45:59'),
(48, 6, 'KM', 'KERTAS MARKER', '2025-04-20 03:46:10', '2025-04-20 03:46:10');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pemakaian`
--

CREATE TABLE `detail_pemakaian` (
  `det_pakai_id` int(11) NOT NULL,
  `pakai_id` int(11) NOT NULL,
  `stok_id` int(11) NOT NULL,
  `qty_det_pakai` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pemakaian`
--

INSERT INTO `detail_pemakaian` (`det_pakai_id`, `pakai_id`, `stok_id`, `qty_det_pakai`, `created_at`, `updated_at`) VALUES
(4, 4, 1, 30.00, '2025-05-09 01:34:42', '2025-05-09 01:34:42'),
(5, 5, 1, 30.00, '2025-05-09 01:42:15', '2025-05-09 01:42:15'),
(7, 7, 1, 30.00, '2025-05-09 01:45:34', '2025-05-09 01:45:34'),
(8, 8, 1, 30.00, '2025-05-09 01:46:32', '2025-05-09 01:46:32'),
(64, 42, 15, 30.00, '2025-05-23 01:58:52', '2025-05-23 01:58:52'),
(65, 43, 16, 30.00, '2025-05-27 02:44:41', '2025-05-27 02:44:41');

-- --------------------------------------------------------

--
-- Table structure for table `detail_penerimaan`
--

CREATE TABLE `detail_penerimaan` (
  `det_terima_id` int(11) NOT NULL,
  `terima_id` int(11) NOT NULL,
  `stok_id` int(11) NOT NULL,
  `qty_det_terima` decimal(15,2) NOT NULL DEFAULT 0.00,
  `jumlah_det_terima` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_penerimaan`
--

INSERT INTO `detail_penerimaan` (`det_terima_id`, `terima_id`, `stok_id`, `qty_det_terima`, `jumlah_det_terima`, `created_at`, `updated_at`) VALUES
(243, 56, 15, 30.00, 1575000.00, '2025-05-21 07:31:25', '2025-05-21 07:31:25'),
(244, 56, 18, 10.00, 575000.00, '2025-05-21 07:31:25', '2025-05-21 07:31:25'),
(245, 57, 15, 20.00, 1050000.00, '2025-05-21 07:31:40', '2025-05-21 07:31:40'),
(246, 57, 16, 30.00, 1575000.00, '2025-05-21 07:31:40', '2025-05-21 07:31:40'),
(249, 60, 15, 20.00, 1050000.00, '2025-05-22 04:07:43', '2025-05-22 04:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `detail_po`
--

CREATE TABLE `detail_po` (
  `detpo_id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL,
  `stok_id` int(11) NOT NULL,
  `qty_detpo` decimal(15,2) NOT NULL DEFAULT 0.00,
  `jumlah_detpo` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_po`
--

INSERT INTO `detail_po` (`detpo_id`, `po_id`, `stok_id`, `qty_detpo`, `jumlah_detpo`, `created_at`, `updated_at`) VALUES
(129, 50, 15, 20.00, 1050000.00, '2025-05-21 04:49:30', '2025-05-21 04:49:30'),
(130, 50, 16, 30.00, 1575000.00, '2025-05-21 04:49:30', '2025-05-21 04:49:30'),
(131, 51, 15, 30.00, 1575000.00, '2025-05-21 05:27:07', '2025-05-21 05:27:07'),
(132, 51, 18, 10.00, 575000.00, '2025-05-21 05:27:07', '2025-05-21 05:27:07'),
(133, 52, 15, 20.00, 1050000.00, '2025-05-21 13:14:17', '2025-05-21 13:14:17');

--
-- Triggers `detail_po`
--
DELIMITER $$
CREATE TRIGGER `after_insert_detail_po` AFTER INSERT ON `detail_po` FOR EACH ROW BEGIN
    UPDATE purchase_order
    SET total_qty = (
        SELECT COALESCE(SUM(qty_detpo), 0) FROM detail_po WHERE po_id = NEW.po_id
    ),
    jml_total_po = (
        SELECT COALESCE(SUM(jumlah_detpo), 0) FROM detail_po WHERE po_id = NEW.po_id
    )
    WHERE po_id = NEW.po_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_detail_po` BEFORE INSERT ON `detail_po` FOR EACH ROW BEGIN
    DECLARE harga DECIMAL(15,2);
    
    -- Ambil harga beli dari tabel stok_bahan
    SELECT harga_beli INTO harga FROM stok_bahan WHERE stok_id = NEW.stok_id;
    
    -- Hitung jumlah_detpo
    SET NEW.jumlah_detpo = harga * NEW.qty_detpo;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `detail_retur`
--

CREATE TABLE `detail_retur` (
  `det_retur_id` int(11) NOT NULL,
  `retur_id` int(11) NOT NULL,
  `stok_id` int(11) NOT NULL,
  `qty_det_retur` decimal(15,2) NOT NULL DEFAULT 0.00,
  `jumlah_det_retur` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_retur`
--

INSERT INTO `detail_retur` (`det_retur_id`, `retur_id`, `stok_id`, `qty_det_retur`, `jumlah_det_retur`, `created_at`, `updated_at`) VALUES
(119, 52, 15, 20.00, 1050000.00, '2025-05-21 07:34:37', '2025-05-21 07:34:37');

-- --------------------------------------------------------

--
-- Table structure for table `grup`
--

CREATE TABLE `grup` (
  `grup_id` int(11) NOT NULL,
  `kode_grup` int(11) NOT NULL,
  `nama_grup` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grup`
--

INSERT INTO `grup` (`grup_id`, `kode_grup`, `nama_grup`, `created_at`, `updated_at`) VALUES
(1, 1, 'BAHAN UTAMA', '2025-03-21 01:46:21', '2025-05-12 07:13:11'),
(2, 2, 'BAHAN PEMBANTU', '2025-03-21 01:50:10', '2025-05-12 07:13:18'),
(3, 3, 'ACCESSORIES JAHIT', '2025-03-21 01:50:44', '2025-05-12 07:13:25'),
(4, 4, 'ACCESSORIES FINISHING', '2025-03-21 01:50:57', '2025-05-12 07:13:32'),
(5, 5, 'PACKING', '2025-03-21 01:51:05', '2025-05-12 07:13:41'),
(6, 6, 'TOOLS', '2025-03-21 01:51:13', '2025-05-12 07:13:50'),
(15, 7, 'TEST', '2025-05-19 02:28:24', '2025-05-19 02:33:33');

-- --------------------------------------------------------

--
-- Table structure for table `ket_supplier`
--

CREATE TABLE `ket_supplier` (
  `ket_id` int(11) NOT NULL,
  `ket_jenis` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ket_supplier`
--

INSERT INTO `ket_supplier` (`ket_id`, `ket_jenis`, `created_at`, `updated_at`) VALUES
(1, 'TIDAK ADA KETERANGAN', '2025-03-14 13:18:49', '2025-04-22 12:23:57'),
(2, 'BAHAN', '2025-03-14 13:19:11', '2025-04-22 12:24:11'),
(3, 'CELUPAN', '2025-03-14 13:19:21', '2025-04-22 12:24:28'),
(4, 'TALIKUR', '2025-03-14 13:19:30', '2025-04-22 12:24:52'),
(5, 'ZIPPER', '2025-03-14 13:20:00', '2025-04-22 12:25:02'),
(6, 'BUR', '2025-03-22 23:21:00', '2025-04-22 12:25:12'),
(8, 'PLASTIK', '2025-04-20 22:30:33', '2025-04-22 12:25:23'),
(9, 'KERTAS', '2025-04-22 10:24:45', '2025-04-22 12:26:32'),
(10, 'KANCING', '2025-04-22 10:26:54', '2025-04-22 12:26:43'),
(11, 'BENANG', '2025-04-22 10:29:07', '2025-04-22 12:26:53'),
(12, 'DUS', '2025-04-22 10:37:42', '2025-04-23 23:24:03'),
(13, 'ATK', '2025-04-22 10:39:45', '2025-04-22 12:27:14'),
(14, 'EMBRO', '2025-04-22 12:27:32', '2025-04-22 12:27:32'),
(15, 'OBAT SABLON', '2025-04-22 12:37:24', '2025-04-22 12:37:24'),
(16, 'LABEL', '2025-04-22 12:39:04', '2025-04-22 12:39:04'),
(17, 'HANTAG', '2025-04-22 12:40:03', '2025-04-22 12:40:03'),
(18, 'JAHIT', '2025-04-22 12:47:18', '2025-04-22 12:47:18'),
(19, 'PRINTING', '2025-04-22 14:44:20', '2025-04-22 14:44:20'),
(20, 'FINISHING', '2025-04-22 14:46:02', '2025-04-22 14:46:02'),
(21, 'KAIN', '2025-04-22 14:52:47', '2025-04-22 14:52:47'),
(22, 'STICKER SIZE COLE', '2025-04-22 15:04:04', '2025-04-22 15:04:04');

-- --------------------------------------------------------

--
-- Table structure for table `lotrol_pemakaian`
--

CREATE TABLE `lotrol_pemakaian` (
  `lotrol_pakai_id` int(11) NOT NULL,
  `pakai_id` int(11) NOT NULL,
  `det_pakai_id` int(11) NOT NULL,
  `lotrol_terima_id` int(11) DEFAULT NULL,
  `po_id` int(11) NOT NULL,
  `lot_pakai` varchar(50) NOT NULL,
  `lot_terima_asal` varchar(50) NOT NULL,
  `rol_pakai` varchar(10) NOT NULL,
  `rol_terima_asal` varchar(10) NOT NULL,
  `qty_lotrol_pakai` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lotrol_pemakaian`
--

INSERT INTO `lotrol_pemakaian` (`lotrol_pakai_id`, `pakai_id`, `det_pakai_id`, `lotrol_terima_id`, `po_id`, `lot_pakai`, `lot_terima_asal`, `rol_pakai`, `rol_terima_asal`, `qty_lotrol_pakai`, `created_at`, `updated_at`) VALUES
(150, 43, 65, 203, 50, 'TERIMA22', 'TERIMA22', 'R01', 'R01', 10.50, '2025-05-27 02:44:52', '2025-05-27 02:44:52'),
(151, 43, 65, 204, 50, 'TERIMA22', 'TERIMA22', 'R01', 'R02', 12.00, '2025-05-27 02:44:52', '2025-05-27 02:44:52'),
(152, 43, 65, 205, 50, 'TERIMA22', 'TERIMA22', 'R01', 'R03', 7.50, '2025-05-27 02:44:52', '2025-05-27 02:44:52');

-- --------------------------------------------------------

--
-- Table structure for table `lotrol_penerimaan`
--

CREATE TABLE `lotrol_penerimaan` (
  `lotrol_terima_id` int(11) NOT NULL,
  `terima_id` int(11) NOT NULL,
  `det_terima_id` int(11) NOT NULL,
  `lot_terima` varchar(50) NOT NULL,
  `rol_terima` varchar(10) NOT NULL,
  `qty_lotrol_terima` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lotrol_penerimaan`
--

INSERT INTO `lotrol_penerimaan` (`lotrol_terima_id`, `terima_id`, `det_terima_id`, `lot_terima`, `rol_terima`, `qty_lotrol_terima`, `created_at`, `updated_at`) VALUES
(184, 56, 243, 'BT', 'R01', 12.00, '2025-05-21 07:33:35', '2025-05-21 07:33:35'),
(185, 56, 243, 'BT', 'R02', 10.00, '2025-05-21 07:33:35', '2025-05-21 07:33:35'),
(186, 56, 243, 'BT', 'R03', 8.00, '2025-05-21 07:33:35', '2025-05-21 07:33:35'),
(187, 57, 245, 'TB', 'R01', 5.00, '2025-05-21 07:34:00', '2025-05-21 07:34:00'),
(188, 57, 245, 'TB', 'R02', 5.00, '2025-05-21 07:34:00', '2025-05-21 07:34:00'),
(189, 57, 245, 'TB', 'R03', 10.00, '2025-05-21 07:34:00', '2025-05-21 07:34:00'),
(196, 60, 249, 'I', 'R01', 5.00, '2025-05-22 04:09:27', '2025-05-22 04:09:27'),
(197, 60, 249, 'I', 'R02', 3.00, '2025-05-22 04:09:27', '2025-05-22 04:09:27'),
(198, 60, 249, 'I', 'R03', 5.00, '2025-05-22 04:09:27', '2025-05-22 04:09:27'),
(199, 60, 249, 'I', 'R04', 7.00, '2025-05-22 04:09:27', '2025-05-22 04:09:27'),
(200, 56, 244, 'BT4', 'R01', 3.50, '2025-05-22 15:27:45', '2025-05-22 15:27:45'),
(201, 56, 244, 'BT4', 'R02', 6.50, '2025-05-22 15:27:45', '2025-05-22 15:27:45'),
(203, 57, 246, 'TERIMA22', 'R01', 10.50, '2025-05-27 02:43:45', '2025-05-27 02:43:45'),
(204, 57, 246, 'TERIMA22', 'R02', 12.00, '2025-05-27 02:43:45', '2025-05-27 02:43:45'),
(205, 57, 246, 'TERIMA22', 'R03', 7.50, '2025-05-27 02:43:45', '2025-05-27 02:43:45');

-- --------------------------------------------------------

--
-- Table structure for table `lotrol_retur`
--

CREATE TABLE `lotrol_retur` (
  `lotrol_retur_id` int(11) NOT NULL,
  `retur_id` int(11) NOT NULL,
  `det_retur_id` int(11) NOT NULL,
  `lotrol_terima_id` int(11) DEFAULT NULL,
  `po_id` int(11) NOT NULL,
  `lot_retur` varchar(50) NOT NULL,
  `lot_terima_asal` varchar(50) NOT NULL,
  `rol_retur` varchar(10) NOT NULL,
  `rol_terima_asal` varchar(10) NOT NULL,
  `qty_lotrol_retur` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lotrol_retur`
--

INSERT INTO `lotrol_retur` (`lotrol_retur_id`, `retur_id`, `det_retur_id`, `lotrol_terima_id`, `po_id`, `lot_retur`, `lot_terima_asal`, `rol_retur`, `rol_terima_asal`, `qty_lotrol_retur`, `created_at`, `updated_at`) VALUES
(192, 52, 119, 187, 52, 'I', 'TB', 'R01', 'R01', 5.00, '2025-05-23 04:08:57', '2025-05-23 04:08:57'),
(193, 52, 119, 188, 52, 'I', 'TB', 'R01', 'R02', 5.00, '2025-05-23 04:08:57', '2025-05-23 04:08:57'),
(194, 52, 119, 196, 52, 'I', 'I', 'R01', 'R01', 5.00, '2025-05-23 04:08:57', '2025-05-23 04:08:57'),
(195, 52, 119, 198, 52, 'I', 'I', 'R01', 'R03', 3.00, '2025-05-23 04:08:57', '2025-05-23 06:06:43');

-- --------------------------------------------------------

--
-- Table structure for table `pemakaian`
--

CREATE TABLE `pemakaian` (
  `pakai_id` int(11) NOT NULL,
  `nomor_pakai` varchar(50) NOT NULL,
  `tgl_keluar` date DEFAULT NULL,
  `nomor_ws_warna_pakai` varchar(50) DEFAULT NULL,
  `nomor_ws_pakai` varchar(50) DEFAULT NULL,
  `nomor_art_pakai` varchar(50) DEFAULT NULL,
  `nama_penerima` varchar(100) DEFAULT NULL,
  `ket_pakai` text DEFAULT NULL,
  `admin_pakai` varchar(50) DEFAULT NULL,
  `total_qty_pakai` decimal(15,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemakaian`
--

INSERT INTO `pemakaian` (`pakai_id`, `nomor_pakai`, `tgl_keluar`, `nomor_ws_warna_pakai`, `nomor_ws_pakai`, `nomor_art_pakai`, `nama_penerima`, `ket_pakai`, `admin_pakai`, `total_qty_pakai`, `created_at`, `updated_at`) VALUES
(42, 'PH2505-001', '2025-05-23', 'A', 'B', 'C', 'SYIFA', 'D', 'SYIFA', 30.00, '2025-05-23 01:58:52', '2025-05-23 01:58:52'),
(43, 'PH2505-002', '2025-05-27', 'ABC', 'DEF', 'GHI', 'SYIFA', 'JKL', 'LISTHIA', 30.00, '2025-05-27 02:44:41', '2025-05-27 02:44:41');

-- --------------------------------------------------------

--
-- Table structure for table `penerimaan_pembelian`
--

CREATE TABLE `penerimaan_pembelian` (
  `terima_id` int(11) NOT NULL,
  `nomor_bon` varchar(50) NOT NULL,
  `tgl_terima` date DEFAULT NULL,
  `supplier_id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL,
  `nomor_faktur` varchar(50) DEFAULT NULL,
  `surat_jalan` varchar(50) DEFAULT NULL,
  `tgl_kirim` date DEFAULT NULL,
  `tgl_jatuh_tempo` date DEFAULT NULL,
  `admin_terima` varchar(100) DEFAULT NULL,
  `total_qty_terima` decimal(15,2) DEFAULT 0.00,
  `total_beli_terima` decimal(15,2) DEFAULT 0.00,
  `ppn_terima` decimal(15,2) DEFAULT 0.00,
  `total_beli_terima_ppn` decimal(15,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penerimaan_pembelian`
--

INSERT INTO `penerimaan_pembelian` (`terima_id`, `nomor_bon`, `tgl_terima`, `supplier_id`, `po_id`, `nomor_faktur`, `surat_jalan`, `tgl_kirim`, `tgl_jatuh_tempo`, `admin_terima`, `total_qty_terima`, `total_beli_terima`, `ppn_terima`, `total_beli_terima_ppn`, `created_at`, `updated_at`) VALUES
(56, 'BM2505-001', '2025-05-21', 1, 51, 'NFA', 'NSJ', '2025-05-21', '2025-05-21', 'SYIFA', 40.00, 2150000.00, 236500.00, 2386500.00, '2025-05-21 07:31:25', '2025-05-21 07:31:25'),
(57, 'BM2505-002', '2025-05-21', 1, 50, 'NA', 'JS', '2025-05-21', '2025-05-21', 'SYIFA', 50.00, 2625000.00, 288750.00, 2913750.00, '2025-05-21 07:31:40', '2025-05-21 07:31:40'),
(60, 'BM2505-003', '2025-05-22', 1, 52, 'NFS', 'SJS', '2025-05-22', '2025-05-22', 'JUN', 20.00, 1050000.00, 115500.00, 1165500.00, '2025-05-22 04:07:43', '2025-05-22 04:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE `purchase_order` (
  `po_id` int(11) NOT NULL,
  `nomor_po` varchar(50) NOT NULL,
  `tgl_order` date DEFAULT NULL,
  `max_tgl_kirim` date DEFAULT NULL,
  `nomor_ws` varchar(50) DEFAULT NULL,
  `nomor_art` varchar(50) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `ket_po` text DEFAULT NULL,
  `admin_po` varchar(100) DEFAULT NULL,
  `total_qty` decimal(15,2) DEFAULT 0.00,
  `jml_total_po` decimal(15,2) DEFAULT 0.00,
  `ppn_po` decimal(15,2) DEFAULT 0.00,
  `jml_total_po_ppn` decimal(15,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_order`
--

INSERT INTO `purchase_order` (`po_id`, `nomor_po`, `tgl_order`, `max_tgl_kirim`, `nomor_ws`, `nomor_art`, `supplier_id`, `ket_po`, `admin_po`, `total_qty`, `jml_total_po`, `ppn_po`, `jml_total_po_ppn`, `created_at`, `updated_at`) VALUES
(50, 'PO2505-001', '2025-05-21', '2025-06-20', 'NWS', 'NA', 1, '-', 'SYIFA', 50.00, 2625000.00, 288750.00, 2913750.00, '2025-05-21 04:49:30', '2025-05-21 04:49:30'),
(51, 'PO2504-001', '2025-04-27', '2025-05-27', 'ABC', 'IN', 1, '-', 'SYIFA', 40.00, 2150000.00, 236500.00, 2386500.00, '2025-04-27 05:27:06', '2025-04-27 05:27:07'),
(52, 'PO2505-002', '2025-05-21', '2025-06-20', 'A', 'B', 1, 'C', 'SYIFA', 20.00, 1050000.00, 115500.00, 1165500.00, '2025-05-21 13:14:17', '2025-05-21 13:14:17');

-- --------------------------------------------------------

--
-- Table structure for table `retur`
--

CREATE TABLE `retur` (
  `retur_id` int(11) NOT NULL,
  `nomor_retur` varchar(50) NOT NULL,
  `tgl_retur` date DEFAULT NULL,
  `supplier_id` int(11) NOT NULL,
  `ket_retur` text DEFAULT NULL,
  `admin_retur` varchar(100) DEFAULT NULL,
  `total_qty_retur` decimal(15,2) DEFAULT 0.00,
  `total_beli_retur` decimal(15,2) DEFAULT 0.00,
  `ppn_retur` decimal(15,2) DEFAULT 0.00,
  `total_beli_retur_ppn` decimal(15,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `retur`
--

INSERT INTO `retur` (`retur_id`, `nomor_retur`, `tgl_retur`, `supplier_id`, `ket_retur`, `admin_retur`, `total_qty_retur`, `total_beli_retur`, `ppn_retur`, `total_beli_retur_ppn`, `created_at`, `updated_at`) VALUES
(52, 'RT2505-001', '2025-05-21', 1, 'RUSAK', 'SYIFA', 20.00, 1050000.00, 115500.00, 1165500.00, '2025-05-21 07:34:37', '2025-05-21 07:34:37');

-- --------------------------------------------------------

--
-- Table structure for table `satuan`
--

CREATE TABLE `satuan` (
  `satuan_id` int(11) NOT NULL,
  `kode_satuan` varchar(50) NOT NULL,
  `jenis_satuan` varchar(50) NOT NULL,
  `isi_satuan` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `satuan`
--

INSERT INTO `satuan` (`satuan_id`, `kode_satuan`, `jenis_satuan`, `isi_satuan`, `unit_id`, `created_at`, `updated_at`) VALUES
(1, 'BALL', 'BALL', 24, 1, '2025-03-21 04:01:33', '2025-03-21 04:01:33'),
(2, 'BKS1', 'BKS 10 GROS', 1440, 2, '2025-03-21 05:10:11', '2025-03-21 05:10:11'),
(3, 'BKS2', 'BKS120', 120, 2, '2025-03-21 05:57:37', '2025-03-21 06:33:37'),
(5, 'BKS3', 'BUNGKUS-20', 20, 2, '2025-03-21 06:34:19', '2025-03-21 06:34:19'),
(6, 'CONES', 'CONES', 1, 3, '2025-03-21 06:34:43', '2025-03-22 13:35:28'),
(8, 'DUS', 'DUS', 1, 4, '2025-03-22 16:07:39', '2025-03-22 16:07:39'),
(9, 'DZC', 'LUSIN', 12, 3, '2025-03-22 16:08:03', '2025-03-22 16:08:03'),
(10, 'DZP', 'LUSIN', 12, 2, '2025-03-22 16:08:23', '2025-03-22 16:08:23'),
(11, 'GROS1', 'GROSS1', 288, 2, '2025-03-22 16:08:46', '2025-03-22 16:08:46'),
(12, 'GROSS', 'GROSS', 144, 2, '2025-03-22 16:09:06', '2025-03-22 16:09:06'),
(13, 'IKAT', 'IKAT', 1000, 2, '2025-03-22 16:09:30', '2025-04-23 14:38:40'),
(14, 'KG', 'KG', 1, 1, '2025-03-23 05:13:38', '2025-03-23 05:13:38'),
(15, 'KG1', 'KG', 37, 2, '2025-04-17 07:28:19', '2025-04-17 07:28:19'),
(16, 'LBR', 'LEMBAR', 1, 5, '2025-04-17 07:32:51', '2025-04-17 07:32:51'),
(17, 'MTR1', 'METER', 100, 6, '2025-04-17 07:33:31', '2025-04-17 07:33:31'),
(18, 'MTR2', 'METER', 1, 7, '2025-04-17 07:34:11', '2025-04-17 07:34:11'),
(19, 'PACK', 'PACK', 400, 8, '2025-04-17 07:35:23', '2025-04-17 07:35:23'),
(20, 'PAK1', 'PACK', 10, 9, '2025-04-17 07:35:58', '2025-04-17 07:35:58'),
(21, 'PAK2', 'PAK', 1000, 2, '2025-04-17 07:45:32', '2025-04-17 07:45:32'),
(22, 'PAK3', 'PAK', 80, 2, '2025-04-17 07:46:05', '2025-04-17 07:46:05'),
(23, 'PCS', 'PCS', 1, 2, '2025-04-17 07:46:48', '2025-04-23 07:55:41'),
(24, 'PSG', 'PASANG', 2, 1, '2025-04-17 07:47:28', '2025-04-17 07:47:28'),
(25, 'ROL1', 'ROL', 1000, 2, '2025-04-17 07:48:08', '2025-04-17 07:48:08'),
(26, 'ROL10', 'ROLL', 1500, 2, '2025-04-17 07:50:32', '2025-04-17 07:50:32'),
(27, 'ROL11', 'ROL', 100, 10, '2025-04-17 07:51:03', '2025-04-17 07:51:03'),
(28, 'ROL2', 'ROLL', 100, 10, '2025-04-17 08:07:45', '2025-04-17 08:07:45'),
(29, 'ROL25', 'ROL25', 25, 1, '2025-04-17 08:08:12', '2025-04-17 08:08:12'),
(30, 'ROL3', 'ROL', 1, 9, '2025-04-17 08:08:39', '2025-04-17 08:08:39'),
(31, 'ROL4', 'ROL', 50, 10, '2025-04-17 08:09:32', '2025-04-17 08:09:32'),
(32, 'ROL5', 'ROL4', 100, 7, '2025-04-17 08:09:56', '2025-04-17 08:09:56'),
(33, 'ROL50', 'ROL', 50, 10, '2025-04-17 08:10:22', '2025-05-19 02:49:57'),
(34, 'ROL6', 'ROLL', 90, 7, '2025-04-17 08:10:53', '2025-04-17 08:10:53'),
(35, 'ROL7', 'ROLL', 60, 10, '2025-04-17 08:11:21', '2025-04-17 08:11:21'),
(36, 'ROL8', 'ROL', 18, 8, '2025-04-17 08:11:51', '2025-04-17 08:11:51'),
(37, 'ROLL', 'ROLL', 20, 8, '2025-04-17 08:12:20', '2025-04-17 08:12:20'),
(38, 'ROLL1', 'ROLL', 24, 1, '2025-04-17 08:12:39', '2025-04-17 08:12:39'),
(39, 'SET', 'SET', 1, 11, '2025-04-17 08:12:58', '2025-04-17 08:12:58'),
(40, 'YARD', 'YARD', 1, 10, '2025-04-17 08:13:16', '2025-04-17 08:13:16');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(9) NOT NULL,
  `class` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(31) NOT NULL DEFAULT 'string',
  `context` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stok_bahan`
--

CREATE TABLE `stok_bahan` (
  `stok_id` int(11) NOT NULL,
  `kode_stok` varchar(50) NOT NULL,
  `grup_id` int(11) NOT NULL,
  `bahan_id` int(11) NOT NULL,
  `warna_id` int(11) NOT NULL,
  `satuan_id` int(11) NOT NULL,
  `harga_beli` decimal(15,2) DEFAULT 0.00,
  `harga_per_pcs` decimal(15,2) DEFAULT 0.00,
  `last_received` date DEFAULT NULL,
  `supplier_id` int(11) NOT NULL,
  `nama_barang` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stok_bahan`
--

INSERT INTO `stok_bahan` (`stok_id`, `kode_stok`, `grup_id`, `bahan_id`, `warna_id`, `satuan_id`, `harga_beli`, `harga_per_pcs`, `last_received`, `supplier_id`, `nama_barang`, `created_at`, `updated_at`) VALUES
(15, '1BT0001', 1, 10, 22, 15, 52500.00, 52500.00, '2024-01-04', 1, 'SK 30S BABYTERRY PE ABU TUA', '2025-05-21 03:24:35', '2025-05-21 04:07:02'),
(16, '1BT0002', 1, 10, 22, 15, 52500.00, 52500.00, '2024-01-04', 1, 'SK 30S BABYTERRY PE ABU TUA', '2025-05-21 04:06:16', '2025-05-21 04:06:16'),
(17, '1BT0003', 1, 10, 27, 15, 56500.00, 56500.00, '2024-01-04', 1, 'SK 30S BABYTERRY PE BABY PINK', '2025-05-21 04:08:22', '2025-05-21 04:08:22'),
(18, '1BT0004', 1, 10, 48, 15, 57500.00, 57500.00, '2024-01-04', 1, 'SK 30S BABYTERRY PE BURGUNDY', '2025-05-21 04:26:31', '2025-05-21 04:48:41'),
(19, '1BT0005', 1, 10, 55, 15, 53000.00, 53000.00, '2024-01-04', 1, 'SK 30S BABYTERRY PE COKLAT', '2025-05-21 04:36:55', '2025-05-26 16:01:40');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL,
  `kode_supplier` varchar(50) NOT NULL,
  `nama_supplier` varchar(50) NOT NULL,
  `ket_id` int(11) NOT NULL,
  `alamat_supplier` text NOT NULL,
  `kota_supplier` varchar(50) NOT NULL,
  `no_telp_supplier` varchar(20) DEFAULT NULL,
  `no_hp_supplier` varchar(20) DEFAULT NULL,
  `kontak_supplier` varchar(50) DEFAULT NULL,
  `ntop_supplier` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `kode_supplier`, `nama_supplier`, `ket_id`, `alamat_supplier`, `kota_supplier`, `no_telp_supplier`, `no_hp_supplier`, `kontak_supplier`, `ntop_supplier`, `created_at`, `updated_at`) VALUES
(1, 'S001', 'PT. INTAN JAYA TEXTILE', 2, 'JALAN CARINGIN NO 439', 'KOTA BANDUNG', '022-5432272', '081312342360', 'SUMI ', 75, '2025-03-14 13:26:17', '2025-04-22 12:28:50'),
(2, 'S002', 'PT. SOLJER ABADI', 2, 'JALAN RAYA MAJALAYA - RANCAEKEK NO 289', 'KAB BANDUNG', '-', '081395249899', 'HELMI', 60, '2025-03-16 22:54:52', '2025-04-22 12:28:59'),
(6, 'S003', 'PT GUNAJAYA ', 3, 'JALAN RANCAJIGANG NO 110', 'KAB BANDUNG', '-', '083121074340', 'ATEP', 0, '2025-03-17 08:21:44', '2025-04-22 12:29:09'),
(7, 'S004', 'CV HIDUP BARU', 4, 'JALAN RAYA CIJERAH NO 25A', 'KOTA BANDUNG', '-', '082215000125', 'WINARTI', 75, '2025-03-17 09:05:56', '2025-04-22 12:29:19'),
(8, 'S005', 'PT FAJARINDO FALIMAN ZIPPER', 5, 'JALAN FALIMAN JAYA NO KM 19', 'KOTA TANGERANG', '021-6190016', '087877154051', 'IKA', 30, '2025-03-17 09:07:35', '2025-04-22 12:29:29'),
(10, 'S006', 'CV JAYA SENTOSA MANDIRI', 6, 'RAHAYU SADANG', 'KOTA BANDUNG', '022-5417951', '085101160599', 'HENDRA', 60, '2025-03-22 23:16:42', '2025-04-22 12:29:51'),
(11, 'S007', 'CV ANEKA SARANA MAJU TERUS', 2, 'BIZPARKCOMMERCIAL ESTATE BLOK A1 NO 35', 'KOTA BANDUNG', '022-95436545', '08987112656', 'ROS', 90, '2025-03-22 23:18:23', '2025-04-22 12:30:02'),
(12, 'S008', 'CV SINAR JAYA LESTARI', 2, 'JALAN ANCOL IX NO 2', 'KOTA BANDUNG', '-', '081220778837', 'PAUL', 75, '2025-03-22 23:19:50', '2025-04-22 12:30:12'),
(13, 'S009', 'WIJAYA PLASTIK', 8, 'JALAN RAYA CIJERAH NO 25A', 'KOTA BANDUNG', '-', '08122371001', 'HELEN', 30, '2025-03-22 23:22:23', '2025-04-22 12:30:22'),
(14, 'S010', 'PT TEGUH JAYA', 2, 'JALAN MENGGER NO 99', 'KOTA BANDUNG', '-', '081221220255', 'KIKI', 30, '2025-03-22 23:23:44', '2025-04-22 12:30:33'),
(15, 'S011', 'CV KOLASE 323', 4, 'JALAN MELONG CIJERAH V NO 9', 'KOTA BANDUNG', '-', '081219124477', 'ANTON', 30, '2025-03-22 23:25:46', '2025-04-22 12:31:08'),
(16, 'S012', 'MIMING MARKER', 9, '-', 'KOTA BANDUNG', '-', '081222677669', 'MIMING', 7, '2025-04-22 10:16:58', '2025-04-22 12:31:35'),
(17, 'S013', 'CV CHARISMA ', 4, 'JALAN CIBADUYUT NO 177', 'KOTA BANDUNG', '-', '081321087087', 'IIS', 30, '2025-04-22 10:22:01', '2025-04-22 12:31:51'),
(18, 'S014', 'PT BEST & BEST ', 5, 'JALAN FALIMAN JAYA NO 29 DAAN MOGOT KM 19', 'KOTA TANGERANG', '0215519678', '081808885828', 'LIANA', 0, '2025-04-22 10:23:08', '2025-04-22 12:32:06'),
(19, 'S015', 'PD SRIRIZKY JAYA', 2, 'JALAN CIGONDEWAH', 'KOTA BANDUNG', '-', '085222666382', 'YADI', 30, '2025-04-22 10:23:57', '2025-04-22 12:32:20'),
(20, 'S016', 'TK BOLA DUNIA', 10, 'JALAN OTTO ISKANDAR DINATA NO 210', 'KOTA BANDUNG', '-', '0895708760209', 'NANI', 60, '2025-04-22 10:25:32', '2025-04-22 12:32:41'),
(21, 'S017', 'PT TRISHA INDO SANDANG', 2, 'JALAN TERUSAN PASIRKOJA 238A', 'KOTA BANDUNG', '-', '081257571238', 'RINA', 60, '2025-04-22 10:26:15', '2025-04-22 12:32:56'),
(22, 'S018', 'TK TRIJAYA', 11, '-', 'KOTA BANDUNG', '-', '081321387575', 'HERY', 30, '2025-04-22 10:27:49', '2025-04-22 12:33:34'),
(23, 'S019', 'CV CIPTA SANDANG SENTOSA', 2, 'JALAN RENGAS NO 54', 'KOTA BANDUNG', '-', '081809866147', 'IDA', 30, '2025-04-22 10:28:35', '2025-04-22 12:33:46'),
(24, 'S020', 'CV KINARYA MASAGI', 12, '-', 'KAB BANDUNG', '-', '081368742010', 'ANA', 30, '2025-04-22 10:29:42', '2025-04-22 12:33:59'),
(25, 'S021', 'MUKTI YASA', 2, 'JALAN RANCAMALANG NO 3', 'KOTA BANDUNG', '-', '085603354547', 'IYOS', 0, '2025-04-22 10:30:27', '2025-04-22 12:34:23'),
(26, 'S022', 'CV PELITA SEJATI', 2, 'JALAN TAMIM NO 53', 'KOTA BANDUNG', '-', '081213151015', '-', 0, '2025-04-22 10:31:14', '2025-04-22 12:34:39'),
(27, 'S023', 'CV PELITA ABADI', 2, 'JALAN TAMIM NO 57-59', 'KOTA BANDUNG', '-', '0811234047', '-', 0, '2025-04-22 10:32:18', '2025-04-22 12:34:53'),
(28, 'S024', 'CV PELITA LESTARI', 2, 'JALAN TAMIM NO 60', 'KOTA BANDUNG', '-', '0811234594', '-', 0, '2025-04-22 10:37:10', '2025-04-22 12:35:14'),
(29, 'S025', 'SUBUR MAKMUR', 13, 'JL KALIPAH APO', 'BANDUNG', '-', '-', '-', 30, '2025-04-22 10:38:22', '2025-04-22 12:35:33'),
(30, 'S026', 'HOSANA', 8, 'BANDUNG', '-', '-', '-', '-', 14, '2025-04-22 10:39:16', '2025-04-22 12:35:55'),
(31, 'S027', 'RCM', 14, '-', '-', '-', '-', '-', 0, '2025-04-22 10:40:14', '2025-04-23 14:22:36'),
(32, 'S028', 'CV CIPTA KREASI JAYA ', 6, 'JL.ALAMANDA,KP BENCONGAN NO.89A', 'KOTA TANGERANG', '021-55652340', '-', '-', 30, '2025-04-22 10:41:03', '2025-04-22 12:36:24'),
(34, 'S029', 'ALFA JESPERO ADIWARNA', 15, 'JL.SUKA MULYA NO.20 ( 17 - 20 ),KOMP SUMBER SARI', 'KOTA BANDUNG', '-', '081222023777', '-', 45, '2025-04-22 12:38:12', '2025-04-22 14:33:30'),
(35, 'S030', 'PT SRI INDAH LABELTAMA', 16, '-', '-', '-', '-', '-', 0, '2025-04-22 12:39:39', '2025-04-22 14:33:40'),
(36, 'S031', 'MULYA GRAFIKA', 17, '-', 'DKI JAKARTA', '-', '-', '-', 0, '2025-04-22 12:40:31', '2025-04-22 14:33:50'),
(37, 'S032', 'PT SETIA KAWAN SEJATI', 5, '-', 'KOTA TANGERANG', '-', '-', '-', 0, '2025-04-22 12:41:24', '2025-04-22 14:34:00'),
(38, 'S033', 'KAMOLA WOVEN', 1, '-', 'KOTA BANDUNG', '-', '-', '-', 0, '2025-04-22 12:42:12', '2025-04-22 14:34:13'),
(39, 'S034', 'KAMOLA PRINTING', 1, '-', 'KOTA BANDUNG', '-', '-', '-', 0, '2025-04-22 12:42:44', '2025-04-22 14:34:22'),
(40, 'S035', 'JAYA ABADI GARMENT', 1, '-', 'KOTA BANDUNG', '-', '-', '-', 0, '2025-04-22 12:43:11', '2025-04-22 14:34:35'),
(41, 'S036', 'PT INDAH SUBUR SEJATI', 1, '-', '-', '-', '-', '-', 0, '2025-04-22 12:43:38', '2025-04-22 14:34:45'),
(42, 'S037', 'MATARI', 1, '-', '-', '-', '-', '-', 0, '2025-04-22 12:43:56', '2025-04-22 14:34:55'),
(43, 'S038', 'CIGONDEWAH', 1, '-', '-', '-', '-', '-', 0, '2025-04-22 12:44:15', '2025-04-22 14:35:16'),
(44, 'S039', 'DUNIA GARMENT', 1, '-', '-', '-', '-', '-', 0, '2025-04-22 12:44:47', '2025-04-22 14:35:29'),
(45, 'S040', 'MARTIN LESTARI', 1, '-', '-', '-', '-', '-', 0, '2025-04-22 12:45:10', '2025-04-22 14:35:40'),
(46, 'S041', 'PT SOLAN', 1, '-', '-', '-', '-', '-', 0, '2025-04-22 12:45:32', '2025-04-22 14:35:49'),
(47, 'S042', 'DWI JAYA INDAH', 1, '-', '-', '-', '-', '-', 0, '2025-04-22 12:45:52', '2025-04-22 14:35:59'),
(48, 'S043', 'TOBER HANTAG', 1, '-', '-', '-', '-', '-', 0, '2025-04-22 12:46:08', '2025-04-22 14:36:08'),
(49, 'S044', 'INDOLABEL', 1, '-', '-', '-', '-', '-', 0, '2025-04-22 12:46:28', '2025-04-22 14:36:18'),
(50, 'S045', 'PT KNITTO TEKSTIL INDONESIA', 1, 'JL HOLIS NO 35-37', 'KOTA BANDUNG', '-', '-', '-', 0, '2025-04-22 12:47:00', '2025-04-22 14:36:30'),
(51, 'S046', 'BU ANI', 18, '-', 'BANDUNG', '-', '081991395700', '-', 0, '2025-04-22 12:47:48', '2025-04-22 14:36:40'),
(52, 'S047', 'BU ENI', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 12:48:16', '2025-04-22 14:36:51'),
(53, 'S048', 'BU NINING', 18, '-', '-', '-', '-', '-', 0, '2025-04-22 12:48:38', '2025-04-22 14:37:01'),
(54, 'S049', 'BAPAK DONY', 18, '-', '-', '-', '-', '-', 0, '2025-04-22 12:49:02', '2025-04-22 14:37:13'),
(57, 'S050', 'BAPAK DODY', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:08:56', '2025-04-23 14:23:32'),
(58, 'S051', 'BU RINA', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:10:00', '2025-04-22 14:37:34'),
(59, 'S052', 'BU SUMI', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:19:20', '2025-04-22 14:37:41'),
(60, 'S053', 'BAPAK HERDY', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:22:42', '2025-04-22 14:37:50'),
(61, 'S054', 'PT. JANGKAR', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:22:59', '2025-04-22 14:37:58'),
(63, 'S055', 'BAPAK DIMAN', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:23:49', '2025-04-22 14:38:07'),
(64, 'S056', 'BAPAK DENY', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:38:50', '2025-04-22 14:38:50'),
(65, 'S057', 'BAPAK HERMAN', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:39:10', '2025-04-22 14:39:10'),
(66, 'S058', 'BAPAK JOKO', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:39:31', '2025-04-22 14:39:31'),
(67, 'S059', 'BAPAK KARDO', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:39:51', '2025-04-22 14:39:51'),
(68, 'S060', 'BAPAK KRISNO-', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:40:08', '2025-04-22 14:40:08'),
(69, 'S061', 'BAPAK MARWAN', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:40:28', '2025-04-22 14:40:28'),
(70, 'S062', 'BAPAK RAHMAT', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:40:45', '2025-04-22 14:40:45'),
(71, 'S063', 'BAPAK SLAMET', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:41:05', '2025-04-22 14:41:05'),
(72, 'S064', 'BAPAK RUSMAN', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:41:26', '2025-04-22 14:41:26'),
(73, 'S065', 'BAPAK SUKMAN', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:41:45', '2025-04-22 14:41:45'),
(74, 'S066', 'BAPAK WARYOTO', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:42:06', '2025-04-22 14:42:06'),
(75, 'S067', 'BAPAK ENJANG', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:42:22', '2025-04-22 14:42:22'),
(76, 'S068', 'BU ENAA', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:42:41', '2025-04-22 14:42:41'),
(77, 'S069', 'BAPAK IWAN', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:42:57', '2025-04-22 14:42:57'),
(78, 'S070', 'TOP TRILAND', 16, '-', '-', '-', '-', '-', 0, '2025-04-22 14:43:31', '2025-04-22 14:43:31'),
(79, 'S071', 'WANGSAGRAFIKA', 17, '-', '-', '-', '-', '-', 0, '2025-04-22 14:43:59', '2025-04-22 14:43:59'),
(80, 'S072', 'JAYA MAKMUR', 19, '-', 'BANDUNG', '-', '0816619596', 'PAK ERICK', 30, '2025-04-22 14:44:54', '2025-04-22 14:44:54'),
(81, 'S073', 'BAPAK ANDY', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:45:12', '2025-04-22 14:45:12'),
(82, 'S074', 'BU ENI NURAENI', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:45:29', '2025-04-22 14:45:29'),
(83, 'S075', 'MARTINI', 20, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:46:15', '2025-04-22 14:46:15'),
(84, 'S076', 'BAPAK DARSITO', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:46:38', '2025-04-22 14:46:38'),
(85, 'S077', 'BAPAK ABAS', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:46:55', '2025-04-22 14:46:55'),
(86, 'S078', 'CI MEINA', 14, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:47:16', '2025-04-22 14:47:16'),
(87, 'S079', 'BAPAK ASO-', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:49:10', '2025-04-22 14:49:10'),
(88, 'S080', 'PAK CAHYADI RADEN MUDA', 19, '-', 'BANDUNG', '-', '083190034628', '-', 0, '2025-04-22 14:50:16', '2025-04-22 14:50:16'),
(89, 'S081', 'PANCA BORDIR', 14, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:51:01', '2025-04-22 14:51:01'),
(90, 'S082', 'PAK RIKI', 19, '-', 'BANDUNG', '-', '08170171728', '-', 0, '2025-04-22 14:51:37', '2025-04-22 14:51:37'),
(91, 'S083', 'KO AHIM', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:51:57', '2025-04-22 14:51:57'),
(92, 'S084', 'PT RIA INDAH MANDIRI', 1, '-', '-', '-', '-', '-', 0, '2025-04-22 14:52:18', '2025-04-22 14:52:18'),
(93, 'S085', 'PT DUNIA SANDANG ', 21, 'JAKARTA PUSAT', '-', '-', '-', '-', 0, '2025-04-22 14:53:04', '2025-04-22 14:53:04'),
(94, 'S086', 'PT KENARI MULIA LESTARI', 21, 'JL KENARI NO 14 BANDUNG', '-', '-', '-', '-', 0, '2025-04-22 14:53:34', '2025-04-22 14:53:34'),
(95, 'S087', 'CI MELLY', 10, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:53:53', '2025-04-22 14:53:53'),
(96, 'S088', 'CI LUSY', 10, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:54:31', '2025-04-22 14:54:31'),
(97, 'S089', 'IPAH', 20, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:54:56', '2025-04-22 14:54:56'),
(98, 'S090', 'GABRIEL HIYOKUNARU', 21, '-', '-', '-', '-', '-', 0, '2025-04-22 14:55:16', '2025-04-22 14:55:16'),
(99, 'S091', 'BAPAK IYAN', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:55:35', '2025-04-22 14:55:35'),
(100, 'S092', 'BAPAK KOMAR', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:56:12', '2025-04-22 14:56:12'),
(101, 'S093', 'HIG LABEL', 16, 'JL LANDAK 14 - BURANGRANG BANDUNG', '-', '-', '-', '-', 0, '2025-04-22 14:56:44', '2025-04-22 14:56:44'),
(102, 'S094', 'PT NATIONAL LABEL', 16, '-', 'TANGERANG', '-', '-', '-', 28, '2025-04-22 14:57:13', '2025-04-22 14:58:16'),
(103, 'S095', 'BAPAK ANTON', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:58:37', '2025-04-22 14:58:37'),
(104, 'S096', 'BAPAK GIV', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:58:56', '2025-04-22 14:58:56'),
(105, 'S097', 'BU DIANA', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:59:13', '2025-04-22 14:59:13'),
(106, 'S098', 'BAPAK HAMDI', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 14:59:41', '2025-04-22 14:59:41'),
(107, 'S099', 'BAPAK SABAR', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:00:00', '2025-04-22 15:00:00'),
(108, 'S100', 'PT SUMBER ARTHO INDORAYA', 1, '-', 'BANDUNG', '-', '-', '-', 30, '2025-04-22 15:00:51', '2025-04-22 15:00:51'),
(109, 'S101', 'BAPAK USMAN', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:01:08', '2025-04-22 15:01:08'),
(110, 'S102', 'BU MEIDA', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:01:36', '2025-04-22 15:01:36'),
(111, 'S103', 'BAPAK ROJAN', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:01:56', '2025-04-22 15:01:56'),
(112, 'S104', 'BU IDA', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:02:15', '2025-04-22 15:02:15'),
(113, 'S105', 'MANDIRI OFFSET', 17, '-', '-', '-', '-', '-', 0, '2025-04-22 15:02:34', '2025-04-22 15:02:34'),
(114, 'S106', 'SAKURA LABEL', 16, '-', '-', '-', '-', '-', 0, '2025-04-22 15:02:55', '2025-04-22 15:02:55'),
(115, 'S107', 'CV MAJU BERSAMA', 1, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:03:12', '2025-04-22 15:03:12'),
(116, 'S108', 'CV DWI JAYA INDAH PLASTIC', 1, '-', '-', '-', '-', '-', 0, '2025-04-22 15:03:52', '2025-04-22 15:03:52'),
(117, 'S109', 'PT SOLO MURNI ', 22, '-', '-', '-', '-', '-', 0, '2025-04-22 15:04:24', '2025-04-22 15:04:24'),
(118, 'S110', 'BAPAK ENCENG', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:04:43', '2025-04-22 15:04:43'),
(119, 'S111', 'BAPAK NANA', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:06:36', '2025-04-22 15:06:36'),
(120, 'S112', 'BU WATI AKUSARA', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:06:52', '2025-04-22 15:06:52'),
(121, 'S113', 'BAPAK RIAN', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:07:11', '2025-04-22 15:07:11'),
(122, 'S114', 'BAPAK MULYANA', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:07:28', '2025-04-22 15:07:28'),
(123, 'S115', 'PT BERLIAN ARTHA LABEL', 22, 'JALAN LINGKUNGAN IV NO 23A KEL KAMAL KEC KALIDERES', 'JAKARTA BARAT', '-', '-', '-', 0, '2025-04-22 15:08:00', '2025-04-22 15:08:00'),
(124, 'S116', 'KO JEFRY', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:08:16', '2025-04-22 15:08:16'),
(125, 'S117', 'CV KARUNIA BERKAT LESTARI', 1, 'JALAN TAMIM 63-A02 BANDUNG', '-', '-', '-', '-', 0, '2025-04-22 15:08:42', '2025-04-22 15:08:42'),
(126, 'S118', 'BU EVA', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:09:09', '2025-04-22 15:09:09'),
(127, 'S119', 'BAPAK MAULANA', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:09:24', '2025-04-22 15:09:24'),
(128, 'S120', 'GRAFFITY LABELINDO', 1, '-', 'BANDUNG', '-', '-', 'PAK DENA', 0, '2025-04-22 15:09:53', '2025-04-22 15:09:53'),
(129, 'S121', 'BAPAK HENDAR', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:10:11', '2025-04-22 15:10:11'),
(130, 'S122', 'BAPAK AGUNG', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:10:28', '2025-04-22 15:10:28'),
(131, 'S123', 'BAPAK ASWIN', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:10:45', '2025-04-22 15:10:45'),
(132, 'S124', 'BAPAK DANI', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:11:05', '2025-04-22 15:11:05'),
(133, 'S125', 'BAPAK SUPRI', 18, '-', 'BANDUNG', '-', '-', '-', 0, '2025-04-22 15:11:24', '2025-04-22 15:11:24');

-- --------------------------------------------------------

--
-- Table structure for table `unit_satuan`
--

CREATE TABLE `unit_satuan` (
  `unit_id` int(11) NOT NULL,
  `jenis_unit` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unit_satuan`
--

INSERT INTO `unit_satuan` (`unit_id`, `jenis_unit`, `created_at`, `updated_at`) VALUES
(1, 'KG', '2025-03-21 03:52:20', '2025-03-21 03:52:20'),
(2, 'PCS', '2025-03-21 03:52:20', '2025-03-21 03:52:20'),
(3, 'CONES', '2025-03-21 03:52:45', '2025-03-21 03:52:45'),
(4, 'DUS', '2025-03-21 03:52:45', '2025-03-21 03:52:45'),
(5, 'LBR', '2025-03-21 03:53:02', '2025-03-21 03:53:02'),
(6, 'CM', '2025-03-21 03:53:02', '2025-03-21 03:53:02'),
(7, 'MTR', '2025-03-21 03:54:23', '2025-03-21 03:54:23'),
(8, 'METER', '2025-03-21 03:54:23', '2025-03-21 03:54:23'),
(9, 'ROLL', '2025-03-21 03:54:54', '2025-03-21 03:54:54'),
(10, 'YARD', '2025-03-21 03:54:54', '2025-03-21 03:54:54'),
(11, 'SET', '2025-03-21 03:55:36', '2025-03-21 03:55:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `last_active` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `active`, `last_active`, `created_at`, `updated_at`, `password`) VALUES
(38, 'SYIFA', 1, '2025-05-27 09:28:08', '2025-05-20 10:41:57', '2025-05-20 11:54:05', ''),
(49, 'DOKY', 1, '2025-05-27 09:39:52', '2025-05-22 09:52:48', '2025-05-25 13:37:24', ''),
(53, 'LISTHIA', 1, '2025-05-27 11:20:54', '2025-05-27 09:28:09', '2025-05-27 09:28:50', '');

-- --------------------------------------------------------

--
-- Table structure for table `warna`
--

CREATE TABLE `warna` (
  `warna_id` int(11) NOT NULL,
  `kode_warna` varchar(50) NOT NULL,
  `jenis_warna` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warna`
--

INSERT INTO `warna` (`warna_id`, `kode_warna`, `jenis_warna`, `created_at`, `updated_at`) VALUES
(1, '01', 'ABU', '2025-03-20 02:05:14', '2025-05-26 15:26:33'),
(2, '02', 'ABU BINTIK (101)', '2025-03-20 02:05:14', '2025-03-20 02:05:14'),
(3, '03', 'ABU D 133', '2025-03-20 02:05:14', '2025-03-20 02:05:14'),
(4, '04', 'ABU DOFF', '2025-03-20 02:05:14', '2025-03-20 03:32:09'),
(10, '05', 'ABU HITAM', '2025-03-20 03:39:20', '2025-03-20 03:39:20'),
(12, '06', 'ABU KEBIRUAN', '2025-03-20 03:41:30', '2025-03-20 03:41:30'),
(14, '07', 'ABU MERAH', '2025-03-22 15:11:23', '2025-03-22 15:11:23'),
(15, '08', 'ABU MUDA', '2025-03-22 15:11:39', '2025-03-22 15:11:39'),
(16, '09', 'ABU MUDA (1)', '2025-03-22 15:11:52', '2025-03-22 15:12:00'),
(17, '10', 'ABU PARALON D0301', '2025-03-22 15:12:15', '2025-03-22 15:12:15'),
(18, '11', 'ABU SEDANG', '2025-03-22 15:12:24', '2025-04-23 16:04:40'),
(19, '12', 'ABU SEDANG D301', '2025-03-22 15:18:29', '2025-03-22 15:18:29'),
(20, '13', 'ABU SIRO', '2025-03-22 15:18:41', '2025-03-22 15:18:41'),
(21, '14', 'ABU SLUB', '2025-03-22 15:18:51', '2025-03-22 15:18:59'),
(22, '15', 'ABU TUA', '2025-03-22 15:19:07', '2025-03-22 15:19:07'),
(23, '16', 'ABU TUA (10)', '2025-04-19 06:15:49', '2025-04-19 06:15:49'),
(24, '17', 'ABU TUA TWOTONE', '2025-04-19 06:16:11', '2025-04-19 06:16:21'),
(25, '18', 'ABU TWOTONE', '2025-04-19 06:16:37', '2025-04-19 06:16:48'),
(26, '19', 'ARMY', '2025-04-19 06:17:12', '2025-04-19 06:17:12'),
(27, '20', 'BABY PINK', '2025-04-19 06:17:24', '2025-04-19 06:17:24'),
(28, '21', 'BENHUR', '2025-04-19 06:17:40', '2025-04-19 06:17:40'),
(29, '22', 'BIRU', '2025-04-19 06:17:52', '2025-04-19 06:17:52'),
(30, '23', 'BIRU DONGKER (28)', '2025-04-19 06:18:18', '2025-04-19 06:18:18'),
(31, '24', 'BIRU INSIGHT', '2025-04-19 06:19:18', '2025-04-19 06:19:18'),
(32, '25', 'BIRU KEUNGUAN K944', '2025-04-19 06:19:47', '2025-04-19 06:19:47'),
(33, '26', 'BIRU MUDA', '2025-04-19 06:20:11', '2025-04-19 06:20:11'),
(34, '27', 'BIRU MUDA (6)', '2025-04-19 06:20:27', '2025-04-19 06:20:27'),
(35, '28', 'BIRU MUDA TWOTONE', '2025-04-19 06:20:57', '2025-04-19 06:20:57'),
(36, '29', 'BIRU SIRO', '2025-04-19 06:21:32', '2025-04-19 06:21:32'),
(37, '30', 'BIRU TUA TWOTONE', '2025-04-19 06:22:02', '2025-04-19 06:22:02'),
(38, '31', 'BIRU TURKIS D550', '2025-04-19 06:24:40', '2025-04-19 06:24:40'),
(39, '32', 'BIRU TURKIS K0905', '2025-04-19 06:25:38', '2025-04-19 06:25:38'),
(40, '33', 'BIRU TURQIS', '2025-04-19 06:25:58', '2025-04-19 06:25:58'),
(41, '34', 'BIRU TURQIS TWOTONE', '2025-04-19 06:26:36', '2025-04-19 06:26:36'),
(42, '35', 'BIRU TWOTONE', '2025-04-19 06:27:06', '2025-04-19 06:27:06'),
(43, '36', 'BIRU TWOTONE D379', '2025-04-19 06:28:20', '2025-04-19 06:28:20'),
(44, '37', 'BLUE BLACK', '2025-04-19 06:28:37', '2025-04-19 08:42:59'),
(45, '38', 'BLUE D835', '2025-04-19 06:29:27', '2025-04-19 06:29:27'),
(46, '39', 'BROKEN WHITE', '2025-04-19 06:29:41', '2025-04-19 06:29:41'),
(47, '40', 'BROKEN WHITE D122', '2025-04-19 06:33:09', '2025-04-19 06:33:09'),
(48, '41', 'BURGUNDY', '2025-04-19 06:33:24', '2025-04-19 06:33:24'),
(49, '42', 'BW', '2025-04-19 06:33:37', '2025-04-19 06:33:55'),
(50, '43', 'BW BIRU', '2025-04-19 07:06:26', '2025-04-19 07:06:26'),
(51, '44', 'CARBON HITAM', '2025-04-19 07:23:18', '2025-04-19 08:46:23'),
(53, '45', 'CHARCOAL', '2025-04-19 08:46:40', '2025-04-19 08:46:40'),
(54, '46', 'CHOCOMILK', '2025-04-19 08:47:11', '2025-04-19 08:47:11'),
(55, '47', 'COKLAT', '2025-04-19 08:47:27', '2025-04-19 08:47:27'),
(56, '48', 'COKLAT (5)', '2025-04-19 08:47:58', '2025-04-19 08:47:58'),
(57, '49', 'COKLAT (64)', '2025-04-19 08:48:16', '2025-04-19 08:48:16'),
(58, '50', 'COKLAT BW', '2025-04-19 08:48:33', '2025-04-19 08:48:33'),
(59, '51', 'COKLAT DOFF', '2025-04-19 08:48:53', '2025-04-19 08:48:53'),
(60, '52', 'COKLAT METALIC', '2025-04-19 08:49:01', '2025-04-19 08:49:01'),
(61, '53', 'COKLAT TWOTONE', '2025-04-19 08:49:15', '2025-04-19 08:49:15'),
(62, '54', 'COKLAT TUA', '2025-04-19 08:49:28', '2025-04-19 08:49:28'),
(63, '55', 'CREAM', '2025-04-19 08:49:41', '2025-04-19 08:49:41'),
(64, '56', 'CREAM (1)', '2025-04-19 08:49:56', '2025-04-19 08:49:56'),
(65, '57', 'CREAM HITAM', '2025-04-19 08:50:10', '2025-04-19 08:50:10'),
(66, '58', 'CREAM TWOTONE', '2025-04-19 08:50:34', '2025-04-19 08:50:34'),
(67, '59', 'DARK GREY', '2025-04-19 08:50:53', '2025-04-19 08:50:53'),
(68, '60', 'DEEP MAROON', '2025-04-19 08:51:12', '2025-04-19 08:51:12'),
(69, '61', 'DEEP PURPLE', '2025-04-19 08:51:26', '2025-04-19 08:51:26'),
(70, '62', 'DOUBLE BLACK', '2025-04-19 08:51:51', '2025-04-19 08:51:51'),
(71, '63', 'DUSTY PINK', '2025-04-19 08:52:03', '2025-04-19 08:52:03'),
(72, '64', 'FULLPRINT ABSTRACK', '2025-04-19 08:52:23', '2025-04-19 08:52:23'),
(73, '65', 'FULLPRINT ARMY', '2025-04-19 08:52:47', '2025-04-19 08:52:47'),
(74, '66', 'GOLD', '2025-04-19 08:53:01', '2025-04-19 08:53:01'),
(75, '67', 'GOLDEN BROWN', '2025-04-19 08:53:17', '2025-04-19 08:53:17'),
(76, '68', 'GREEN BOTTLE', '2025-04-19 08:53:51', '2025-04-19 08:53:51'),
(77, '69', 'HITAM', '2025-04-19 08:54:11', '2025-04-19 08:54:11'),
(78, '70', 'HIJAU', '2025-04-19 08:54:22', '2025-04-19 08:54:22'),
(79, '71', 'HIJAU (40)', '2025-04-19 08:54:35', '2025-04-19 08:54:35'),
(80, '72', 'HIJAU ARMY', '2025-04-19 08:54:48', '2025-04-19 08:54:48'),
(81, '73', 'HIJAU ARMY (3)', '2025-04-19 08:55:11', '2025-04-19 08:55:11'),
(82, '74', 'HIJAU ARMY (76)', '2025-04-19 08:55:31', '2025-04-19 08:55:31'),
(83, '75', 'HIJAU ARMY TUA (35)', '2025-04-19 08:55:51', '2025-04-19 08:55:51'),
(84, '76', 'HIJAU BOTOL', '2025-04-19 08:56:11', '2025-04-19 08:56:11'),
(85, '77', 'HIJAU D879', '2025-04-19 08:56:26', '2025-04-19 08:56:26'),
(86, '78', 'HIJAU MINT (75)', '2025-04-19 08:56:40', '2025-04-19 08:56:40'),
(87, '79', 'HIJAU MUDA D871', '2025-04-19 08:56:59', '2025-04-19 08:56:59'),
(88, '80', 'HIJAU PUCUK', '2025-04-19 08:57:19', '2025-04-19 08:57:19'),
(89, '81', 'HIJAU SEDANG', '2025-04-19 08:57:34', '2025-04-19 08:57:34'),
(90, '82', 'HIJAU TNI', '2025-04-19 08:57:52', '2025-04-19 08:57:52'),
(91, '83', 'HIJAU TUA D876', '2025-04-19 08:58:04', '2025-04-19 08:58:04'),
(92, '84', 'HIJAU TUA TWOTONE', '2025-04-19 08:58:19', '2025-04-19 08:58:19'),
(93, '85', 'HIJAU TWOTONE', '2025-04-19 08:58:38', '2025-04-19 08:58:38'),
(94, '86', 'HITAM', '2025-04-19 08:58:53', '2025-04-19 08:58:53'),
(95, '87', 'HITAM (18)', '2025-04-19 08:59:24', '2025-04-19 08:59:24'),
(96, '88', 'HITAM (19)', '2025-04-19 08:59:42', '2025-04-19 08:59:42'),
(97, '89', 'HITAM (24)', '2025-04-19 08:59:59', '2025-04-19 08:59:59'),
(98, '90', 'HITAM (30)', '2025-04-19 09:00:16', '2025-04-19 09:00:16'),
(99, '91', 'HITAM (95)', '2025-04-19 09:00:29', '2025-04-19 09:00:29'),
(100, '92', 'HITAM 23831100B', '2025-04-19 09:00:41', '2025-04-19 09:00:41'),
(101, '93', 'HITAM 23831391B', '2025-04-19 09:00:53', '2025-04-19 09:00:53'),
(102, '94', 'HITAM D 580', '2025-04-19 09:01:07', '2025-04-19 09:01:07'),
(103, '95', 'HITAM D580', '2025-04-19 09:01:24', '2025-04-19 09:01:24'),
(104, '96', 'HITAM DOFF', '2025-04-19 09:01:42', '2025-04-19 09:01:42'),
(105, '97', 'HITAM EMAS', '2025-04-19 09:01:54', '2025-04-19 09:01:54'),
(106, '98', 'HITAM EX NAVY', '2025-04-19 09:02:11', '2025-04-19 09:02:11'),
(107, '99', 'HITAM GALAXY', '2025-04-19 09:02:22', '2025-04-19 09:02:22'),
(108, 'A0', 'HITAM ORANGE', '2025-04-19 10:40:28', '2025-04-19 10:40:28'),
(109, 'A1', 'HITAM POLOS', '2025-04-19 10:40:41', '2025-04-19 10:40:41'),
(110, 'A2', 'HITAM PUTIH', '2025-04-19 10:40:58', '2025-04-19 10:40:58'),
(111, 'A3', 'HITAM SIRO', '2025-04-19 10:41:11', '2025-04-19 10:41:11'),
(112, 'A4', 'HITAM SLUB', '2025-04-19 10:41:27', '2025-04-19 10:41:27'),
(113, 'A5', 'HITAM TULISAN', '2025-04-19 10:41:40', '2025-04-19 10:41:40'),
(114, 'A6', 'HITAM TW', '2025-04-19 10:41:54', '2025-04-19 10:41:54'),
(115, 'A7', 'HITAM TWOTONE', '2025-04-19 10:42:10', '2025-04-19 10:42:10'),
(116, 'A8', 'HTAM', '2025-04-19 10:42:21', '2025-04-19 10:42:21'),
(117, 'A9', 'KHAKI', '2025-04-19 10:42:33', '2025-04-19 10:42:33'),
(118, 'B0', 'KUNING', '2025-04-19 10:42:45', '2025-04-19 10:42:45'),
(119, 'B1', 'KUNING BUSUK', '2025-04-19 10:42:56', '2025-04-19 10:42:56'),
(120, 'B2', 'KUNING BUSUK SLUB', '2025-04-19 10:43:09', '2025-04-19 10:43:09'),
(121, 'B3', 'KUNING BUSUK TWOTONE', '2025-04-19 10:43:24', '2025-04-19 10:43:24'),
(122, 'B4', 'KUNING KUNYIT (50)', '2025-04-19 10:43:41', '2025-04-19 10:43:41'),
(123, 'B5', 'KUNING MUDA D847', '2025-04-19 10:43:52', '2025-04-19 10:43:52'),
(124, 'B6', 'KUNING TERANG (9)', '2025-04-19 10:44:11', '2025-04-19 10:44:11'),
(125, 'B7', 'KUNING TERANG K0870', '2025-04-19 10:44:22', '2025-04-19 10:44:22'),
(126, 'B8', 'LILAC (102)', '2025-04-19 10:44:35', '2025-04-19 10:44:35'),
(127, 'B9', 'LILAC (4)', '2025-04-19 10:44:55', '2025-04-19 10:44:55'),
(128, 'C0', 'LYLAC', '2025-04-19 10:45:06', '2025-04-19 10:45:06'),
(129, 'C1', 'LYLAC D244', '2025-04-19 10:45:20', '2025-04-19 10:45:20'),
(130, 'C2', 'M 71', '2025-04-19 10:45:34', '2025-04-19 10:45:34'),
(131, 'C3', 'M 71 PUTIH', '2025-04-19 10:45:46', '2025-04-19 10:45:46'),
(132, 'C4', 'M 71 SIRO', '2025-04-19 10:45:56', '2025-04-19 10:45:56'),
(133, 'C5', 'M 71 SLUB', '2025-04-19 10:46:08', '2025-04-19 10:46:08'),
(134, 'C6', 'M 79', '2025-04-19 10:46:36', '2025-04-19 10:46:36'),
(135, 'C7', 'M 81', '2025-04-19 10:46:51', '2025-04-19 10:46:51'),
(136, 'C8', 'M71', '2025-04-19 10:47:03', '2025-04-19 10:47:03'),
(137, 'C9', 'M71 (20)', '2025-04-19 10:47:20', '2025-04-19 10:47:20'),
(138, 'D0', 'M71 (38)', '2025-04-19 10:47:31', '2025-04-19 10:47:31'),
(139, 'D1', 'M71 (53)', '2025-04-19 10:47:56', '2025-04-19 10:47:56'),
(140, 'D2', 'M71 (57)', '2025-04-19 10:48:15', '2025-04-19 10:48:15'),
(141, 'D3', 'M71 D0204', '2025-04-19 10:48:27', '2025-04-19 10:48:27'),
(142, 'D4', 'M71 D204', '2025-04-19 10:48:42', '2025-04-19 10:48:42'),
(143, 'D5', 'M71 D577', '2025-04-19 10:48:54', '2025-04-19 10:48:54'),
(144, 'D6', 'M71 PUTIH (1)', '2025-04-19 10:49:20', '2025-04-19 10:49:20'),
(145, 'D7', 'M71 PUTIH (50)', '2025-04-19 10:49:32', '2025-04-19 10:49:32'),
(146, 'D8', 'M71 TUA', '2025-04-19 10:49:43', '2025-04-19 10:49:43'),
(147, 'D9', 'M71 TUA (54)', '2025-04-19 10:50:02', '2025-04-19 10:50:02'),
(148, 'E0', 'M79 (21)', '2025-04-19 10:50:13', '2025-04-19 10:50:13'),
(149, 'E1', 'M81', '2025-04-19 10:50:25', '2025-04-19 10:50:25'),
(150, 'E2', 'MARMER HITAM', '2025-04-19 10:50:43', '2025-04-19 10:50:43'),
(151, 'E3', 'MARMER NAVY', '2025-04-19 10:50:55', '2025-04-19 10:50:55'),
(152, 'E4', 'MAROON', '2025-04-19 10:51:06', '2025-04-19 10:51:06'),
(153, 'E5', 'MAROON (11)', '2025-04-19 10:51:24', '2025-04-19 10:51:24'),
(154, 'E6', 'MAROON D 159', '2025-04-19 10:51:38', '2025-04-19 10:51:38'),
(155, 'E7', 'MAROON D520', '2025-04-19 10:51:51', '2025-04-19 10:51:51'),
(156, 'E8', 'MAROON MUDA TWOTONE', '2025-04-19 10:52:03', '2025-04-19 10:52:03'),
(157, 'E9', 'MAROON SIRO', '2025-04-19 10:52:14', '2025-04-19 10:52:14'),
(158, 'F0', 'MAROON SLUB', '2025-04-19 10:52:26', '2025-04-19 10:52:26'),
(159, 'F1', 'MAROON TUA TWOTONE', '2025-04-19 10:52:38', '2025-04-19 10:52:38'),
(160, 'F2', 'MAROON TWOTONE', '2025-04-19 10:52:48', '2025-04-19 10:52:48'),
(161, 'F3', 'MEDIUM GREY 2 (22)', '2025-04-19 10:53:00', '2025-04-19 10:53:00'),
(162, 'F4', 'MERAH', '2025-04-19 10:53:10', '2025-04-19 10:53:10'),
(163, 'F5', 'MERAH (1)', '2025-04-19 10:53:24', '2025-04-19 10:53:24'),
(164, 'F6', 'MERAH (31)', '2025-04-19 10:53:35', '2025-04-19 10:53:35'),
(165, 'F7', 'MERAH (35)', '2025-04-19 10:53:51', '2025-04-19 10:53:51'),
(166, 'F8', 'MERAH (82)', '2025-04-19 10:54:06', '2025-04-19 10:54:06'),
(167, 'F9', 'MERAH (9)', '2025-04-19 10:54:17', '2025-04-19 10:54:17'),
(168, 'G0', 'MERAH 0518', '2025-04-19 10:54:30', '2025-04-19 10:54:30'),
(169, 'G1', 'MERAH CABE D171', '2025-04-19 12:59:18', '2025-04-19 12:59:18'),
(170, 'G2', 'MERAH D171', '2025-04-19 12:59:34', '2025-04-19 12:59:34'),
(171, 'G3', 'MERAH HITAM', '2025-04-19 12:59:43', '2025-04-19 12:59:43'),
(172, 'G4', 'MERAH K1264', '2025-04-19 13:00:01', '2025-04-19 13:00:01'),
(173, 'G5', 'MERAH SLUB', '2025-04-19 13:00:11', '2025-04-19 13:00:11'),
(174, 'G6', 'MERAH TERANG', '2025-04-19 13:00:21', '2025-04-19 13:00:21'),
(175, 'G7', 'MERAH TERANG D003', '2025-04-19 13:00:31', '2025-04-19 13:00:31'),
(176, 'G8', 'MERAH TUA', '2025-04-19 13:00:48', '2025-04-19 13:00:48'),
(177, 'G9', 'MERAH TUA D520', '2025-04-19 13:01:02', '2025-04-19 13:01:02'),
(178, 'H0', 'MERAH TWOTONE', '2025-04-19 13:01:11', '2025-04-19 13:01:11'),
(179, 'H1', 'MISTY SEDANG D384', '2025-04-19 13:01:47', '2025-04-19 13:01:47'),
(180, 'H2', 'MOCCA', '2025-04-19 13:02:13', '2025-04-19 13:02:13'),
(181, 'H3', 'MOTIF BINTANG', '2025-04-19 13:02:33', '2025-04-19 13:02:33'),
(182, 'H4', 'MUSTARD', '2025-04-19 13:02:44', '2025-04-19 13:02:44'),
(183, 'H5', 'MUSTARD D0508', '2025-04-19 13:02:58', '2025-04-19 13:02:58'),
(184, 'H6', 'NAVY', '2025-04-19 13:03:06', '2025-04-19 13:03:06'),
(185, 'H7', 'NAVY (13)', '2025-04-19 13:10:31', '2025-04-19 13:10:31'),
(186, 'H8', 'NAVY 23436381C', '2025-04-19 13:10:50', '2025-04-19 13:10:50'),
(187, 'H9', 'NAVY 23436525', '2025-04-19 13:11:06', '2025-04-19 13:11:06'),
(188, 'I0', 'NAVY D 165', '2025-04-19 13:11:19', '2025-04-19 13:11:19'),
(189, 'I1', 'NAVY D0560', '2025-04-19 13:11:33', '2025-04-19 13:11:33'),
(190, 'I2', 'NAVY D117', '2025-04-19 13:11:45', '2025-04-19 13:11:45'),
(191, 'I3', 'NAVY G0196', '2025-04-19 13:12:02', '2025-04-19 13:12:02'),
(192, 'I4', 'NAVY SLUB', '2025-04-19 13:12:15', '2025-04-19 13:12:15'),
(193, 'I5', 'NAVY TUA (29)', '2025-04-19 13:12:33', '2025-04-19 13:12:33'),
(194, 'I6', 'NAVY TWOTONE', '2025-04-19 13:12:45', '2025-04-19 13:12:45'),
(195, 'I7', 'NAW', '2025-04-19 13:13:28', '2025-04-19 13:13:28'),
(196, 'I8', 'OFF WHITE', '2025-04-19 13:13:38', '2025-04-19 13:13:38'),
(197, 'I9', 'OLIVE', '2025-04-19 13:13:46', '2025-04-19 13:13:46'),
(198, 'J0', 'ORANGE', '2025-04-19 13:13:57', '2025-04-19 13:13:57'),
(199, 'J1', 'ORANGE BATA (2)', '2025-04-19 13:14:10', '2025-04-19 13:14:10'),
(200, 'J2', 'ORANGE MUDA', '2025-04-19 13:14:28', '2025-04-19 13:14:28'),
(201, 'J3', 'ORANGE MUDA D136', '2025-04-19 13:15:13', '2025-04-19 13:15:13'),
(202, 'J4', 'ORANGE MUDA SLUB', '2025-04-19 13:15:28', '2025-04-19 13:15:28'),
(203, 'J5', 'ORANGE TUA', '2025-04-19 13:16:14', '2025-04-19 13:16:14'),
(204, 'J6', 'ORANGE TUA 283', '2025-04-19 13:16:26', '2025-04-19 13:16:26'),
(205, 'J7', 'ORANGE TUA K0283', '2025-04-19 13:16:45', '2025-04-19 13:16:45'),
(206, 'J8', 'ORANGE TUA SLUB', '2025-04-19 13:18:44', '2025-04-19 13:18:44'),
(207, 'J9', 'ORANGE TWOTONE', '2025-04-19 13:19:02', '2025-04-19 13:19:02'),
(208, 'K0', 'PEACH SLUB', '2025-04-19 13:19:36', '2025-04-19 13:19:36'),
(209, 'K1', 'PINK', '2025-04-19 13:19:49', '2025-04-19 13:19:49'),
(210, 'K2', 'PINK D341', '2025-04-19 13:20:12', '2025-04-19 13:20:12'),
(211, 'K3', 'PINK MUDA D512', '2025-04-19 13:20:43', '2025-04-19 13:20:43'),
(212, 'K4', 'PINK MUDA D516', '2025-04-19 13:21:02', '2025-04-19 13:21:02'),
(213, 'K5', 'PINK STABILO', '2025-04-19 13:21:13', '2025-04-19 13:21:13'),
(214, 'K6', 'PINK STABILO D397', '2025-04-19 13:21:45', '2025-04-19 13:21:45'),
(215, 'K7', 'PUTIH', '2025-04-19 13:22:54', '2025-04-19 13:22:54'),
(216, 'K8', 'PUTIH (22)', '2025-04-19 13:23:14', '2025-04-19 13:23:14'),
(217, 'K9', 'PUTIH (56)', '2025-04-19 13:23:35', '2025-04-19 13:23:35'),
(218, 'L0', 'PUTIH A0001', '2025-04-19 13:24:09', '2025-04-19 13:24:09'),
(219, 'L1', 'PUTIH GARIS HITAM', '2025-04-19 13:24:26', '2025-04-19 13:24:26'),
(220, 'L2', 'PUTIH SLUB', '2025-04-19 13:24:41', '2025-04-19 13:24:41'),
(221, 'L3', 'PUTIH TULISAN HITAM', '2025-04-19 13:24:57', '2025-04-19 13:25:16'),
(222, 'L4', 'ROYAL BLUE D 115', '2025-04-19 13:25:33', '2025-04-19 13:25:33'),
(223, 'L5', 'SAGE', '2025-04-19 13:26:03', '2025-04-19 13:26:03'),
(224, 'L6', 'SALEM', '2025-04-19 13:26:17', '2025-04-19 13:26:17'),
(225, 'L7', 'SALEM K0802', '2025-04-19 13:26:57', '2025-04-19 13:26:57'),
(226, 'L8', 'SALEM SLUB', '2025-04-19 13:27:16', '2025-04-19 13:27:16'),
(227, 'L9', 'SANDWASH HIJAU', '2025-04-19 13:28:03', '2025-04-19 13:28:03'),
(228, 'M0', 'SANDWASH UNGU', '2025-04-19 13:28:26', '2025-05-19 02:58:35'),
(229, 'M1', 'SILVER', '2025-04-19 13:28:45', '2025-04-19 13:28:45'),
(230, 'M2', 'SKY BLUE (35)', '2025-04-19 13:29:00', '2025-04-19 13:29:00'),
(231, 'M3', 'STRIPE PUTIH M 81', '2025-04-19 13:29:31', '2025-04-19 13:29:31'),
(232, 'M4', 'TAPETA', '2025-04-19 13:30:19', '2025-04-19 13:30:19'),
(233, 'M5', 'TERACOTA (1)', '2025-04-19 13:30:33', '2025-04-19 13:30:33'),
(234, 'M6', 'TOSCA', '2025-04-19 13:30:43', '2025-04-19 13:30:43'),
(235, 'M7', 'TOSCA MUDA', '2025-04-19 13:30:53', '2025-04-19 13:30:53'),
(236, 'M8', 'TOSCA SLUB', '2025-04-19 13:31:04', '2025-04-19 13:31:04'),
(237, 'M9', 'TOSCA TUA', '2025-04-19 13:31:13', '2025-04-19 13:31:13'),
(238, 'N0', 'TOSCA TUA (10)', '2025-04-19 13:31:24', '2025-04-19 13:31:24'),
(239, 'N1', 'TURQIS TWOTONE', '2025-04-19 13:31:45', '2025-04-19 13:31:55'),
(240, 'N2', 'UNGU', '2025-04-19 13:32:04', '2025-04-19 13:32:04'),
(241, 'N3', 'UNGU TUA D061', '2025-04-19 13:33:17', '2025-04-19 13:33:17'),
(242, 'N4', 'WHITE YELLOWISH', '2025-04-19 13:33:43', '2025-04-19 13:33:43'),
(243, 'N5', 'YELLOW DANDELIONS', '2025-04-19 13:33:57', '2025-04-19 13:33:57'),
(244, 'N6', 'TROPIC GREEN', '2025-04-19 13:34:08', '2025-04-19 13:34:08'),
(245, 'N7', 'MILO', '2025-04-19 13:34:16', '2025-04-19 13:34:16'),
(246, 'N8', 'BEIGE', '2025-04-19 13:34:23', '2025-04-19 13:34:23'),
(247, 'N9', 'UMBER', '2025-04-19 13:34:32', '2025-04-19 13:34:32'),
(248, 'O0', 'GREEN BAY', '2025-04-19 13:34:45', '2025-04-19 13:34:45'),
(249, 'O1', 'MINERAL BLUE', '2025-04-19 13:35:00', '2025-04-19 13:35:00'),
(250, 'O2', 'SEA GREEN', '2025-04-19 13:35:10', '2025-04-19 13:35:10'),
(251, 'O3', 'SALUR', '2025-04-19 13:35:18', '2025-04-19 13:35:18'),
(252, 'O4', 'NAVY BLUE', '2025-04-19 13:35:27', '2025-04-19 13:35:27'),
(253, 'O5', 'LATTE', '2025-04-19 13:35:34', '2025-04-19 13:35:34'),
(254, 'O6', 'ENSIGHT BLUE', '2025-04-19 13:35:48', '2025-04-19 13:35:48'),
(255, 'O7', 'JET BLACK', '2025-04-19 13:36:00', '2025-04-19 13:36:00'),
(256, 'O8', 'SAFARI', '2025-04-19 13:36:08', '2025-04-19 13:36:08'),
(257, 'O9', 'MATCHA', '2025-04-19 13:36:16', '2025-04-19 13:36:16'),
(258, 'P0', 'ABU HIJAU', '2025-04-19 13:36:27', '2025-04-19 13:36:27'),
(259, 'P1', 'IVORY', '2025-04-19 13:36:35', '2025-04-19 13:36:35'),
(260, 'P2', 'TURKISH', '2025-04-19 13:36:44', '2025-04-19 13:36:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_groups`
--
ALTER TABLE `auth_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_groups_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_identities`
--
ALTER TABLE `auth_identities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_logins`
--
ALTER TABLE `auth_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_permissions_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `auth_remember_tokens_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bahan`
--
ALTER TABLE `bahan`
  ADD PRIMARY KEY (`bahan_id`),
  ADD UNIQUE KEY `kode` (`kode_bahan`),
  ADD UNIQUE KEY `kode_2` (`kode_bahan`),
  ADD KEY `group_id` (`grup_id`);

--
-- Indexes for table `detail_pemakaian`
--
ALTER TABLE `detail_pemakaian`
  ADD PRIMARY KEY (`det_pakai_id`),
  ADD KEY `pakai_id` (`pakai_id`),
  ADD KEY `stok_id` (`stok_id`);

--
-- Indexes for table `detail_penerimaan`
--
ALTER TABLE `detail_penerimaan`
  ADD PRIMARY KEY (`det_terima_id`),
  ADD KEY `stok_id` (`stok_id`),
  ADD KEY `fk_detail_penerimaan` (`terima_id`);

--
-- Indexes for table `detail_po`
--
ALTER TABLE `detail_po`
  ADD PRIMARY KEY (`detpo_id`),
  ADD KEY `po_id` (`po_id`),
  ADD KEY `stok_id` (`stok_id`);

--
-- Indexes for table `detail_retur`
--
ALTER TABLE `detail_retur`
  ADD PRIMARY KEY (`det_retur_id`),
  ADD KEY `retur_id` (`retur_id`),
  ADD KEY `stok_id` (`stok_id`);

--
-- Indexes for table `grup`
--
ALTER TABLE `grup`
  ADD PRIMARY KEY (`grup_id`),
  ADD UNIQUE KEY `unique_kode_grup` (`kode_grup`);

--
-- Indexes for table `ket_supplier`
--
ALTER TABLE `ket_supplier`
  ADD PRIMARY KEY (`ket_id`);

--
-- Indexes for table `lotrol_pemakaian`
--
ALTER TABLE `lotrol_pemakaian`
  ADD PRIMARY KEY (`lotrol_pakai_id`),
  ADD KEY `fk_lotrol_pakai_id` (`pakai_id`),
  ADD KEY `fk_lotrol_det_pakai_id` (`det_pakai_id`),
  ADD KEY `fk_lotrol_pakai_po_id` (`po_id`),
  ADD KEY `fk_lotrol_pemakaian` (`lot_pakai`,`rol_pakai`),
  ADD KEY `fk_lotrol_pemakaian_lotrol_penerimaan` (`lotrol_terima_id`);

--
-- Indexes for table `lotrol_penerimaan`
--
ALTER TABLE `lotrol_penerimaan`
  ADD PRIMARY KEY (`lotrol_terima_id`),
  ADD KEY `fk_lotrol_penerimaan` (`det_terima_id`),
  ADD KEY `fk_lotrol_penerimaan_terima` (`terima_id`);

--
-- Indexes for table `lotrol_retur`
--
ALTER TABLE `lotrol_retur`
  ADD PRIMARY KEY (`lotrol_retur_id`),
  ADD KEY `fk_lotrol_retur_id` (`retur_id`),
  ADD KEY `fk_lotrol_det_retur_id` (`det_retur_id`),
  ADD KEY `fk_lotrol_retur_po` (`po_id`),
  ADD KEY `fk_lotrol_retur` (`lot_retur`,`rol_retur`),
  ADD KEY `fk_lotrol_retur_lotrol_penerimaan` (`lotrol_terima_id`);

--
-- Indexes for table `pemakaian`
--
ALTER TABLE `pemakaian`
  ADD PRIMARY KEY (`pakai_id`);

--
-- Indexes for table `penerimaan_pembelian`
--
ALTER TABLE `penerimaan_pembelian`
  ADD PRIMARY KEY (`terima_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `fk_penerimaan_po` (`po_id`);

--
-- Indexes for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD PRIMARY KEY (`po_id`),
  ADD UNIQUE KEY `nomor_po` (`nomor_po`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `retur`
--
ALTER TABLE `retur`
  ADD PRIMARY KEY (`retur_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`satuan_id`),
  ADD UNIQUE KEY `satuan` (`kode_satuan`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stok_bahan`
--
ALTER TABLE `stok_bahan`
  ADD PRIMARY KEY (`stok_id`),
  ADD UNIQUE KEY `kode_stok` (`kode_stok`),
  ADD KEY `fk_stok_grup` (`grup_id`),
  ADD KEY `fk_stok_bahan` (`bahan_id`),
  ADD KEY `fk_stok_warna` (`warna_id`),
  ADD KEY `fk_stok_satuan` (`satuan_id`),
  ADD KEY `fk_stok_supplier` (`supplier_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`),
  ADD UNIQUE KEY `kode` (`kode_supplier`),
  ADD KEY `keterangan_id` (`ket_id`);

--
-- Indexes for table `unit_satuan`
--
ALTER TABLE `unit_satuan`
  ADD PRIMARY KEY (`unit_id`),
  ADD UNIQUE KEY `unit` (`jenis_unit`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `warna`
--
ALTER TABLE `warna`
  ADD PRIMARY KEY (`warna_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_groups`
--
ALTER TABLE `auth_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `auth_identities`
--
ALTER TABLE `auth_identities`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `auth_logins`
--
ALTER TABLE `auth_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=483;

--
-- AUTO_INCREMENT for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bahan`
--
ALTER TABLE `bahan`
  MODIFY `bahan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `detail_pemakaian`
--
ALTER TABLE `detail_pemakaian`
  MODIFY `det_pakai_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `detail_penerimaan`
--
ALTER TABLE `detail_penerimaan`
  MODIFY `det_terima_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT for table `detail_po`
--
ALTER TABLE `detail_po`
  MODIFY `detpo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `detail_retur`
--
ALTER TABLE `detail_retur`
  MODIFY `det_retur_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `grup`
--
ALTER TABLE `grup`
  MODIFY `grup_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `ket_supplier`
--
ALTER TABLE `ket_supplier`
  MODIFY `ket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `lotrol_pemakaian`
--
ALTER TABLE `lotrol_pemakaian`
  MODIFY `lotrol_pakai_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT for table `lotrol_penerimaan`
--
ALTER TABLE `lotrol_penerimaan`
  MODIFY `lotrol_terima_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- AUTO_INCREMENT for table `lotrol_retur`
--
ALTER TABLE `lotrol_retur`
  MODIFY `lotrol_retur_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT for table `pemakaian`
--
ALTER TABLE `pemakaian`
  MODIFY `pakai_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `penerimaan_pembelian`
--
ALTER TABLE `penerimaan_pembelian`
  MODIFY `terima_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `po_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `retur`
--
ALTER TABLE `retur`
  MODIFY `retur_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `satuan`
--
ALTER TABLE `satuan`
  MODIFY `satuan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stok_bahan`
--
ALTER TABLE `stok_bahan`
  MODIFY `stok_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `warna`
--
ALTER TABLE `warna`
  MODIFY `warna_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=264;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_identities`
--
ALTER TABLE `auth_identities`
  ADD CONSTRAINT `fk_auth_identities_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  ADD CONSTRAINT `auth_permissions_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  ADD CONSTRAINT `auth_remember_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bahan`
--
ALTER TABLE `bahan`
  ADD CONSTRAINT `bahan_ibfk_1` FOREIGN KEY (`grup_id`) REFERENCES `grup` (`grup_id`) ON DELETE CASCADE;

--
-- Constraints for table `detail_penerimaan`
--
ALTER TABLE `detail_penerimaan`
  ADD CONSTRAINT `detail_penerimaan_ibfk_2` FOREIGN KEY (`stok_id`) REFERENCES `stok_bahan` (`stok_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detail_penerimaan` FOREIGN KEY (`terima_id`) REFERENCES `penerimaan_pembelian` (`terima_id`) ON DELETE CASCADE;

--
-- Constraints for table `detail_po`
--
ALTER TABLE `detail_po`
  ADD CONSTRAINT `detail_po_ibfk_1` FOREIGN KEY (`po_id`) REFERENCES `purchase_order` (`po_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_po_ibfk_2` FOREIGN KEY (`stok_id`) REFERENCES `stok_bahan` (`stok_id`) ON DELETE CASCADE;

--
-- Constraints for table `detail_retur`
--
ALTER TABLE `detail_retur`
  ADD CONSTRAINT `detail_retur_ibfk_1` FOREIGN KEY (`retur_id`) REFERENCES `retur` (`retur_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_retur_ibfk_2` FOREIGN KEY (`stok_id`) REFERENCES `stok_bahan` (`stok_id`) ON DELETE CASCADE;

--
-- Constraints for table `lotrol_pemakaian`
--
ALTER TABLE `lotrol_pemakaian`
  ADD CONSTRAINT `fk_lotrol_det_pakai_id` FOREIGN KEY (`det_pakai_id`) REFERENCES `detail_pemakaian` (`det_pakai_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lotrol_pakai_id` FOREIGN KEY (`pakai_id`) REFERENCES `pemakaian` (`pakai_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lotrol_pakai_po_id` FOREIGN KEY (`po_id`) REFERENCES `purchase_order` (`po_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lotrol_pemakaian_lotrol_penerimaan` FOREIGN KEY (`lotrol_terima_id`) REFERENCES `lotrol_penerimaan` (`lotrol_terima_id`) ON DELETE CASCADE;

--
-- Constraints for table `lotrol_penerimaan`
--
ALTER TABLE `lotrol_penerimaan`
  ADD CONSTRAINT `fk_lotrol_penerimaan` FOREIGN KEY (`det_terima_id`) REFERENCES `detail_penerimaan` (`det_terima_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lotrol_penerimaan_terima` FOREIGN KEY (`terima_id`) REFERENCES `penerimaan_pembelian` (`terima_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lotrol_penerimaan_ibfk_1` FOREIGN KEY (`terima_id`) REFERENCES `penerimaan_pembelian` (`terima_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lotrol_penerimaan_ibfk_2` FOREIGN KEY (`det_terima_id`) REFERENCES `detail_penerimaan` (`det_terima_id`) ON DELETE CASCADE;

--
-- Constraints for table `lotrol_retur`
--
ALTER TABLE `lotrol_retur`
  ADD CONSTRAINT `fk_lotrol_det_retur_id` FOREIGN KEY (`det_retur_id`) REFERENCES `detail_retur` (`det_retur_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lotrol_retur_id` FOREIGN KEY (`retur_id`) REFERENCES `retur` (`retur_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lotrol_retur_lotrol_penerimaan` FOREIGN KEY (`lotrol_terima_id`) REFERENCES `lotrol_penerimaan` (`lotrol_terima_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lotrol_retur_po` FOREIGN KEY (`po_id`) REFERENCES `purchase_order` (`po_id`) ON DELETE CASCADE;

--
-- Constraints for table `penerimaan_pembelian`
--
ALTER TABLE `penerimaan_pembelian`
  ADD CONSTRAINT `fk_penerimaan_po` FOREIGN KEY (`po_id`) REFERENCES `purchase_order` (`po_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penerimaan_pembelian_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penerimaan_pembelian_ibfk_2` FOREIGN KEY (`po_id`) REFERENCES `purchase_order` (`po_id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD CONSTRAINT `purchase_order_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`) ON DELETE CASCADE;

--
-- Constraints for table `retur`
--
ALTER TABLE `retur`
  ADD CONSTRAINT `retur_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`);

--
-- Constraints for table `satuan`
--
ALTER TABLE `satuan`
  ADD CONSTRAINT `satuan_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `unit_satuan` (`unit_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stok_bahan`
--
ALTER TABLE `stok_bahan`
  ADD CONSTRAINT `fk_stok_bahan` FOREIGN KEY (`bahan_id`) REFERENCES `bahan` (`bahan_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stok_grup` FOREIGN KEY (`grup_id`) REFERENCES `grup` (`grup_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stok_satuan` FOREIGN KEY (`satuan_id`) REFERENCES `satuan` (`satuan_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stok_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stok_warna` FOREIGN KEY (`warna_id`) REFERENCES `warna` (`warna_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supplier`
--
ALTER TABLE `supplier`
  ADD CONSTRAINT `fk_ket_supplier` FOREIGN KEY (`ket_id`) REFERENCES `ket_supplier` (`ket_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
