-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2025 at 11:34 AM
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
(3, 1, 'logout', 'User logged out', '2025-01-19 06:25:14', '2025-01-19 06:25:14'),
(4, 1, 'login', 'User logged in successfully', '2025-01-19 06:26:36', '2025-01-19 06:26:36'),
(5, 1, 'login', 'User logged in successfully', '2025-01-19 06:35:25', '2025-01-19 06:35:25'),
(6, 1, 'login', 'User logged in successfully', '2025-01-19 06:36:32', '2025-01-19 06:36:32'),
(7, 1, 'login', 'User logged in successfully', '2025-01-27 12:40:14', '2025-01-27 12:40:14'),
(8, 1, 'logout', 'User logged out', '2025-01-27 13:15:31', '2025-01-27 13:15:31'),
(9, 1, 'login', 'User logged in successfully', '2025-01-27 13:19:55', '2025-01-27 13:19:55'),
(10, 1, 'login', 'User logged in successfully', '2025-01-29 15:42:09', '2025-01-29 15:42:09'),
(11, 1, 'create_student', 'Created student ID 3', '2025-01-29 16:26:49', '2025-01-29 16:26:49'),
(12, 1, 'create_student', 'Created student ID 4', '2025-01-29 16:36:31', '2025-01-29 16:36:31'),
(13, 1, 'login', 'User logged in successfully', '2025-01-31 22:39:34', '2025-01-31 22:39:34'),
(14, 1, 'login', 'User logged in successfully', '2025-02-23 08:49:53', '2025-02-23 08:49:53'),
(15, 1, 'login', 'User logged in successfully', '2025-02-23 10:14:36', '2025-02-23 10:14:36'),
(16, 1, 'create_student', 'Created student ID 6', '2025-02-23 10:21:59', '2025-02-23 10:21:59'),
(17, 1, 'login', 'User logged in successfully', '2025-04-06 09:33:16', '2025-04-06 09:33:16');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'BSIT', '2025-01-29 16:06:51', '2025-01-29 16:06:51'),
(2, 'BSE', '2025-01-29 16:06:51', '2025-01-29 16:06:51'),
(3, 'BSA', '2025-01-29 16:06:51', '2025-01-29 16:06:51'),
(4, 'BSAB', '2025-01-29 16:06:51', '2025-01-29 16:06:51'),
(5, 'BSCrim', '2025-01-29 16:06:51', '2025-01-29 16:06:51'),
(6, 'BSLEA', '2025-01-29 16:06:51', '2025-01-29 16:06:51'),
(7, 'BFAS', '2025-01-29 16:06:51', '2025-01-29 16:06:51');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `created_at`, `updated_at`) VALUES
(2, 'IICT', '2025-01-29 16:26:25', '2025-01-29 16:26:25'),
(3, 'SAA', '2025-02-23 10:29:14', '2025-02-23 10:29:14');

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

INSERT INTO `students` (`id`, `firstname`, `middlename`, `lastname`, `year`, `course_id`, `section`, `department_id`, `rfid`, `image`, `sex`, `created_at`, `updated_at`) VALUES
(1, 'Franklin', 'M.', 'Salacup', 4, 1, '3', 1, '0003388020', 'uploads/678225656cb77_npyf2gsu.png', 'Male', '2025-01-29 16:25:00', '2025-01-29 16:25:00'),
(2, 'Jobert', 'A.', 'Aguilar', 4, 1, 'BSIT 4ANS', 1, '0003403864', 'uploads/678226514b67c_DJI_20241116093942_0139_D (3).png', 'Male', '2025-01-29 16:25:00', '2025-01-29 16:25:00'),
(3, 'Jobert', 'DELA CRUZ', 'Aguilar', 4, 1, '3', 2, '2342342', 'uploads/679a56c93a35e_474258935_586926977457044_1046888536965818386_n.jpg', 'Male', '2025-01-29 16:26:49', '2025-01-29 16:26:49'),
(4, 'SHIELA', 'DELA CRUZ', 'GANTE', 4, 1, 'A NS', 2, '24123123', 'assets/img/default-avatar.png', 'Female', '2025-01-29 16:36:31', '2025-01-29 16:36:31'),
(6, 'SHIELA', 'DELA CRUZ', 'GANTE', 2, 3, 'A NS', 2, '098765432', 'uploads/67baf6c78ce24_404385044_122105012270120244_4883291354320556715_n.jpg', 'Male', '2025-02-23 10:21:59', '2025-02-23 10:21:59'),
(4294967295, 'Arisha', 'ashley', 'Abad', 2021, 0, 'BSIT NS (2021-2022) 4A', 0, '', NULL, 'Other', '2025-02-23 11:17:26', '2025-02-23 11:17:26');

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4294967296;

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
