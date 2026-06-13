-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2026 at 04:37 PM
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
(15, 7, 'dikki', 'Register', '2026-06-13 14:25:17'),
(16, 7, 'dikki', 'Login', '2026-06-13 14:25:29'),
(17, 7, 'dikki', 'Logout', '2026-06-13 14:25:48'),
(18, 6, 'admin', 'Login', '2026-06-13 14:25:54'),
(19, 6, 'admin', 'Ubah Status: dikki (User → Admin)', '2026-06-13 14:26:05'),
(20, 6, 'admin', 'Logout', '2026-06-13 14:26:11'),
(21, 7, 'dikki', 'Login', '2026-06-13 14:26:19'),
(22, 7, 'dikki', 'Logout', '2026-06-13 14:26:38'),
(23, 6, 'admin', 'Login', '2026-06-13 14:27:08'),
(24, 6, 'admin', 'Ubah Status: dikki (Admin → User)', '2026-06-13 14:27:22'),
(25, 6, 'admin', 'Logout', '2026-06-13 14:27:25');

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
(7, 'dikki', '$2y$10$RWvJBoj47BTAOr10QzKEC..yVCPyItw5je4x5wPeIqOadGdZXvJAy', 'User', '2026-06-13 14:25:17');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
