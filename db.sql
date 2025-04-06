-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2025 at 07:25 AM
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
-- Database: `library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'logout', 'User logged out', '2025-01-19 06:20:05', '2025-01-19 06:20:05'),
(2, 1, 'login', 'User logged in successfully', '2025-01-19 06:24:51', '2025-01-19 06:24:51'),
(3, 1, 'logout', 'User logged out', '2025-01-19 06:25:14', '2025-01-19 06:25:14');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` enum('in','out') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `student_id`, `timestamp`, `type`) VALUES
(1, 2, '2025-01-11 09:12:22', 'in'),
(2, 1, '2025-01-11 09:12:26', 'in'),
(3, 1, '2025-01-11 09:13:25', 'out'),
(4, 2, '2025-01-11 09:13:29', 'out'),
(5, 1, '2025-01-11 10:04:01', 'in'),
(6, 2, '2025-01-11 10:04:04', 'in'),
(7, 1, '2025-01-11 10:04:15', 'out'),
(8, 2, '2025-01-11 10:04:18', 'out'),
(9, 2, '2025-01-11 10:04:31', 'in'),
(10, 1, '2025-01-11 10:04:33', 'in'),
(11, 2, '2025-01-11 10:04:44', 'out'),
(12, 1, '2025-01-11 10:04:46', 'out'),
(13, 2, '2025-01-11 10:24:06', 'in'),
(14, 1, '2025-01-11 10:24:17', 'in'),
(15, 1, '2025-01-11 10:25:26', 'out'),
(16, 1, '2025-01-11 10:30:17', 'in'),
(17, 2, '2025-01-11 10:30:24', 'out'),
(18, 2, '2025-01-11 10:30:34', 'in'),
(19, 1, '2025-01-11 10:30:44', 'out'),
(20, 2, '2025-01-11 10:31:00', 'out'),
(21, 2, '2025-01-11 10:31:15', 'in'),
(22, 2, '2025-01-11 10:31:30', 'out'),
(23, 1, '2025-01-11 10:31:33', 'in'),
(24, 2, '2025-01-11 10:31:45', 'in'),
(25, 1, '2025-01-11 10:32:00', 'out'),
(26, 2, '2025-01-11 10:32:10', 'out'),
(27, 1, '2025-01-11 10:33:55', 'in'),
(28, 2, '2025-01-11 10:34:02', 'in'),
(29, 1, '2025-01-19 06:27:17', 'in'),
(30, 1, '2025-01-19 06:27:42', 'out'),
(31, 1, '2025-01-19 06:28:30', 'in'),
(32, 2, '2025-01-19 06:36:43', 'in'),
(33, 1, '2025-02-23 10:17:32', 'in'),
(34, 1, '2025-02-23 10:28:26', 'out');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(10) UNSIGNED NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `year` int(4) NOT NULL,
  `course_id` int(10) UNSIGNED NOT NULL,
  `section` varchar(50) NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `rfid` varchar(50) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sex` enum('Male','Female','Other') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `firstname`, `middlename`, `lastname`, `year`, `course`, `section`, `department`, `rfid`, `image`, `sex`, `created_at`, `updated_at`) VALUES
(1, 'Franklin', 'M.', 'Salacup', 4, 'BSIT', '3', '1', '0003388020', 'uploads/678225656cb77_npyf2gsu.png', 'Male', '2025-01-11 08:01:41', '2025-01-11 08:01:41'),
(2, 'Jobert', 'A.', 'Aguilar', 4, 'BSIT', 'BSIT 4ANS', 'IICT', '0003403864', 'uploads/678226514b67c_DJI_20241116093942_0139_D (3).png', 'Male', '2025-01-11 08:05:37', '2025-01-11 08:05:37'),
(3, 'SHIELA', 'GANTE', 'Aguilar', 4, 'BSIT', 'BSIT 4ANS', 'IICT', '123456879', 'uploads/678c8dd28131d_dolly.jpg', 'Female', '2025-01-19 05:29:54', '2025-01-19 05:30:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$nWFZFedp7vFUycKGQEyuVeYVgmc0k24IP3QUL.sKl54shEajakAcW', '2025-01-11 08:46:41', '2025-01-11 08:46:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rfid` (`rfid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
