-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2026 at 01:29 AM
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
-- Database: `db_user_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `log_activity`
--

CREATE TABLE `log_activity` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `aktivitas` varchar(255) DEFAULT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_activity`
--

INSERT INTO `log_activity` (`id`, `user_id`, `username`, `aktivitas`, `waktu`) VALUES
(2, NULL, 'admin', 'Register', '2026-06-13 10:37:45'),
(3, 6, 'admin', 'Login', '2026-06-13 11:08:28'),
(4, 6, 'admin', 'Ubah Status: admin (Admin → User)', '2026-06-13 14:11:46'),
(5, 6, 'admin', 'Ubah Status: admin (User → Admin)', '2026-06-13 14:11:49'),
(6, 6, 'admin', 'Ubah Status: admin (Admin → Admin)', '2026-06-13 14:13:23'),
(7, 6, 'admin', 'Ubah Status: admin (Admin → Admin)', '2026-06-13 14:13:25'),
(8, 6, 'admin', 'Ubah Status: admin (Admin → Admin)', '2026-06-13 14:13:25'),
(9, 6, 'admin', 'Ubah Status: admin (Admin → Admin)', '2026-06-13 14:13:25'),
(10, 6, 'admin', 'Ubah Status: admin (Admin → Admin)', '2026-06-13 14:13:26'),
(11, 6, 'admin', 'Ubah Status: admin (Admin → Admin)', '2026-06-13 14:13:37'),
(12, 6, 'admin', 'Ubah Status: admin (Admin → Admin)', '2026-06-13 14:13:39'),
(13, 6, 'admin', 'Ubah Status: admin (Admin → Admin)', '2026-06-13 14:13:39'),
(14, 6, 'admin', 'Logout', '2026-06-13 14:23:39'),
(15, NULL, 'dikki', 'Register', '2026-06-13 14:25:17'),
(16, NULL, 'dikki', 'Login', '2026-06-13 14:25:29'),
(17, NULL, 'dikki', 'Logout', '2026-06-13 14:25:48'),
(18, 6, 'admin', 'Login', '2026-06-13 14:25:54'),
(19, 6, 'admin', 'Ubah Status: dikki (User → Admin)', '2026-06-13 14:26:05'),
(20, 6, 'admin', 'Logout', '2026-06-13 14:26:11'),
(21, NULL, 'dikki', 'Login', '2026-06-13 14:26:19'),
(22, NULL, 'dikki', 'Logout', '2026-06-13 14:26:38'),
(23, 6, 'admin', 'Login', '2026-06-13 14:27:08'),
(24, 6, 'admin', 'Ubah Status: dikki (Admin → User)', '2026-06-13 14:27:22'),
(25, 6, 'admin', 'Logout', '2026-06-13 14:27:25'),
(26, 6, 'admin', 'Login', '2026-06-13 22:54:22'),
(27, 6, 'admin', 'Logout', '2026-06-13 22:55:25'),
(28, 8, 'frana', 'Register', '2026-06-13 22:55:51'),
(29, 8, 'frana', 'Login', '2026-06-13 22:56:06'),
(30, 8, 'frana', 'Logout', '2026-06-13 22:59:40'),
(31, NULL, 'dikki', 'Login', '2026-06-13 23:00:00'),
(32, NULL, 'dikki', 'Logout', '2026-06-13 23:00:14'),
(33, 6, 'admin', 'Login', '2026-06-13 23:00:23'),
(34, 6, 'admin', 'Ubah Status: frana (User → Admin)', '2026-06-13 23:00:40'),
(35, 6, 'admin', 'Logout', '2026-06-13 23:00:49'),
(36, 8, 'frana', 'Login', '2026-06-13 23:00:58'),
(37, 8, 'frana', 'Logout', '2026-06-13 23:02:20'),
(38, NULL, 'dikki', 'Login', '2026-06-13 23:02:34'),
(39, NULL, 'dikki', 'Logout', '2026-06-13 23:02:39'),
(40, 8, 'frana', 'Login', '2026-06-13 23:02:49'),
(41, 8, 'frana', 'Reset Password: dikki', '2026-06-13 23:03:12'),
(42, 8, 'frana', 'Logout', '2026-06-13 23:03:16'),
(43, NULL, 'dikki', 'Login', '2026-06-13 23:03:40'),
(44, NULL, 'dikki', 'Logout', '2026-06-13 23:03:51'),
(45, 8, 'frana', 'Login', '2026-06-13 23:03:57'),
(46, 8, 'frana', 'Hapus User: dikki', '2026-06-13 23:04:18'),
(47, 8, 'frana', 'Logout', '2026-06-13 23:04:32'),
(48, 6, 'admin', 'Login', '2026-06-13 23:04:42'),
(49, 6, 'admin', 'Ubah Status: frana (Admin → User)', '2026-06-13 23:04:59'),
(50, 6, 'admin', 'Logout', '2026-06-13 23:06:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('Admin','User') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `status`, `created_at`) VALUES
(6, 'admin', '$2y$10$tWPWNu/U5njyMKiUsHgOc.0yUv7VkmBwqunquHdN5mm3M9xlN1nrG', 'Admin', '2026-06-13 10:58:01'),
(8, 'frana', '$2y$10$I7K1c.clIzM6G2sY38YIye/Nfxtc0mzm83tnOsA9JglXhqdzkPnPe', 'User', '2026-06-13 22:55:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `log_activity`
--
ALTER TABLE `log_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_activity`
--
ALTER TABLE `log_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `log_activity`
--
ALTER TABLE `log_activity`
  ADD CONSTRAINT `log_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
