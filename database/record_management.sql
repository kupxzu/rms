-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2025 at 09:57 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `record_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`) VALUES
(1, 'Legislative'),
(2, 'MPDC'),
(3, 'Budget Office'),
(4, 'Accounting Office'),
(5, 'MIO'),
(6, 'MCDO'),
(7, 'MDRRMO'),
(8, 'Sample'),
(9, 'Assessor\'s Office'),
(10, 'MTO'),
(11, 'MSWDO'),
(12, 'MCR'),
(13, 'MAO'),
(14, 'MWSE'),
(15, 'Engineering Office'),
(16, 'GSO'),
(17, 'MENRO'),
(18, 'Legal Office'),
(19, 'MHO');

-- --------------------------------------------------------

--
-- Table structure for table `department_position`
--

CREATE TABLE `department_position` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department_position`
--

INSERT INTO `department_position` (`id`, `department_id`, `position_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4),
(5, 5, 5),
(6, 6, 6),
(7, 7, 7),
(8, 8, 8),
(9, 9, 9),
(10, 10, 10),
(11, 11, 11),
(12, 12, 12),
(13, 13, 13),
(14, 14, 14),
(15, 15, 15),
(16, 16, 16),
(17, 17, 17),
(18, 18, 18),
(19, 19, 19);

-- --------------------------------------------------------

--
-- Table structure for table `document_views`
--

CREATE TABLE `document_views` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `document_type` varchar(50) NOT NULL,
  `action` enum('view','download') NOT NULL,
  `action_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `document_views`
--

INSERT INTO `document_views` (`id`, `user_id`, `document_id`, `document_type`, `action`, `action_timestamp`) VALUES
(1, 31, 1, 'resolution', 'view', '2025-01-30 19:59:27'),
(2, 31, 1, 'ordinance', 'view', '2025-01-30 19:59:31'),
(3, 31, 1, 'ordinance', 'download', '2025-01-30 19:59:34'),
(4, 31, 1, 'resolution', 'download', '2025-01-30 19:59:37');

-- --------------------------------------------------------

--
-- Table structure for table `file_permissions`
--

CREATE TABLE `file_permissions` (
  `id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `file_permissions`
--

INSERT INTO `file_permissions` (`id`, `file_id`, `department_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(23, 4, 1),
(24, 4, 2),
(25, 4, 3),
(26, 4, 4),
(27, 4, 5),
(28, 4, 6),
(29, 4, 7),
(30, 4, 8),
(31, 4, 9),
(32, 4, 10),
(33, 4, 11),
(34, 4, 12),
(35, 4, 13),
(36, 4, 14),
(37, 4, 15),
(38, 4, 16),
(39, 4, 17),
(40, 4, 18),
(41, 4, 19),
(42, 3, 1),
(43, 5, 1),
(48, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `file_views`
--

CREATE TABLE `file_views` (
  `id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `file_views`
--

INSERT INTO `file_views` (`id`, `file_id`, `user_id`, `viewed_at`) VALUES
(1, 6, 31, '2025-01-30 19:53:45'),
(2, 1, 31, '2025-01-30 19:53:48'),
(3, 5, 31, '2025-01-30 19:53:51'),
(4, 3, 31, '2025-01-30 19:53:53'),
(5, 2, 31, '2025-01-30 19:53:55'),
(6, 4, 31, '2025-01-30 20:00:23');

-- --------------------------------------------------------

--
-- Table structure for table `ingoing`
--

CREATE TABLE `ingoing` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `attachment` varchar(500) NOT NULL,
  `file_type` enum('pdf','docx','jpg','jpeg','png') NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `memorandums`
--

CREATE TABLE `memorandums` (
  `id` int(11) NOT NULL,
  `vip_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `attachment` varchar(500) NOT NULL,
  `file_type` enum('pdf','docx','jpg','jpeg','png') NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `memorandums`
--

INSERT INTO `memorandums` (`id`, `vip_id`, `title`, `description`, `attachment`, `file_type`, `uploaded_at`) VALUES
(4, 1, 'sample2', 'sample2', '../../../../RMS/uploads/memorandums/679b9d1f56112.pdf', 'pdf', '2025-01-30 15:39:11');

-- --------------------------------------------------------

--
-- Table structure for table `messages_with_attachments`
--

CREATE TABLE `messages_with_attachments` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `related_item_id` int(11) NOT NULL,
  `related_item_type` enum('ordinance','resolution') NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages_with_attachments`
--

INSERT INTO `messages_with_attachments` (`id`, `sender_id`, `receiver_id`, `message`, `attachment`, `related_item_id`, `related_item_type`, `sent_at`, `is_read`) VALUES
(1, 31, 32, 'qwe', NULL, 0, 'ordinance', '2025-01-29 19:10:04', 1),
(2, 31, 32, '2222', NULL, 0, 'ordinance', '2025-01-29 19:17:23', 1),
(3, 32, 49, 'hello ma duds', NULL, 0, 'ordinance', '2025-01-29 19:29:14', 0),
(4, 32, 31, '2222', NULL, 0, 'ordinance', '2025-01-30 23:54:07', 1),
(5, 32, 31, '2222', NULL, 0, 'ordinance', '2025-01-31 00:15:46', 1),
(6, 32, 31, '2222', NULL, 0, 'ordinance', '2025-01-31 00:39:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ordinances`
--

CREATE TABLE `ordinances` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `submitted_by` int(11) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `attachment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ordinances`
--

INSERT INTO `ordinances` (`id`, `title`, `description`, `submitted_by`, `status`, `submission_date`, `attachment`) VALUES
(1, 'sample', 'sample', 31, 'Approved', '2025-01-30 19:54:26', '1738266866_sample_pdf.pdf'),
(2, 'sample', 'sample', 31, 'Pending', '2025-01-30 19:59:10', '1738267150_sample_pdf.pdf'),
(3, 'sample', 'sample', 31, 'Pending', '2025-01-30 20:31:13', '1738269073_sample_pdf.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `ordinances_resolutions`
--

CREATE TABLE `ordinances_resolutions` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` enum('Ordinance','Resolution','Events','None') NOT NULL DEFAULT 'None',
  `uploaded_by` int(11) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ordinances_resolutions`
--

INSERT INTO `ordinances_resolutions` (`id`, `title`, `description`, `file_path`, `file_type`, `uploaded_by`, `uploaded_at`) VALUES
(1, 'Sample', 'Sample', '../../RMS/uploads/files/1738249434_sample_pdf.pdf', 'None', 1, '2025-01-30 15:03:54'),
(2, 'Sample', 'Sample', '../../RMS/uploads/files/1738249474_sample_pdf.pdf', 'Ordinance', 1, '2025-01-30 15:04:34'),
(3, 'Samples', 'Sample', '../../RMS/uploads/files/1738249484_sample_pdf.pdf', 'Resolution', 1, '2025-01-30 15:04:44'),
(4, 'Samples', 'Sample', '../../RMS/uploads/files/1738249494_sample_pdf.pdf', 'Events', 1, '2025-01-30 15:04:54'),
(5, 'sample', 'sample', '../../RMS/uploads/files/1738263582_sample_pdf.pdf', 'Ordinance', 1, '2025-01-30 18:59:42'),
(6, '2222', '22', '../../RMS/uploads/files/1738263804_sample_pdf.pdf', 'None', 1, '2025-01-30 19:03:24');

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `name`) VALUES
(1, 'Head of Legislative'),
(2, 'Head of MPDC'),
(3, 'Head of Budget Office'),
(4, 'Head of Accounting Office'),
(5, 'Head of MIO'),
(6, 'Head of MCDO'),
(7, 'Head of MDRRMO'),
(8, 'Sample'),
(9, 'Head of Assessor\'s Office'),
(10, 'Head of MTO'),
(11, 'Head of MSWDO'),
(12, 'Head of MCR'),
(13, 'Head of MAO'),
(14, 'Head of MWSE'),
(15, 'Head of Engineering Office'),
(16, 'Head of GSO'),
(17, 'Head of MENRO'),
(18, 'Head of Legal Office'),
(19, 'Head of MHO');

-- --------------------------------------------------------

--
-- Table structure for table `private_files`
--

CREATE TABLE `private_files` (
  `id` int(11) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `private_files`
--

INSERT INTO `private_files` (`id`, `uploaded_by`, `title`, `description`, `file_name`, `file_type`, `uploaded_at`) VALUES
(1, 31, 'sample', 'sample', '679be413587db.pdf', 'pdf', '2025-01-30 20:41:55');

-- --------------------------------------------------------

--
-- Table structure for table `reject_reasons`
--

CREATE TABLE `reject_reasons` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_type` enum('ordinance','resolution') NOT NULL,
  `rejection_title` varchar(255) NOT NULL,
  `rejection_reason` text NOT NULL,
  `rejection_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `resolutions`
--

CREATE TABLE `resolutions` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `submitted_by` int(11) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `attachment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `resolutions`
--

INSERT INTO `resolutions` (`id`, `title`, `description`, `submitted_by`, `status`, `submission_date`, `attachment`) VALUES
(1, 'sample', 'sample', 31, 'Approved', '2025-01-30 19:54:32', '1738266872_sample_pdf.pdf'),
(2, 'sample', 'sample', 31, 'Pending', '2025-01-30 19:59:18', '1738267158_sample_pdf.pdf'),
(3, 'sample', 'sample', 31, 'Pending', '2025-01-30 20:33:14', '1738269194_sample_pdf.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `age` int(3) NOT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `contact` varchar(15) NOT NULL,
  `email` varchar(150) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `profile_pic` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_dp` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `age`, `sex`, `contact`, `email`, `username`, `password`, `role`, `profile_pic`, `created_at`, `id_dp`, `active`) VALUES
(1, 'LGU', 'Admin', 0, 'Male', '', '', 'admin', '$2y$10$TdOrRhrc7dYmAPLzpBAF8.Slzqumgt2n0PXHRjg0VsVjljeL31F7u', 'admin', NULL, '2025-01-12 16:27:47', NULL, 1),
(31, 'John', 'Doe', 25, 'Male', '09123456789', 'yonshhai@gmail.com\n', '1', '$2a$12$m4y1X0Fx4xu4.ZzVPL1CCem1fJHOxlrgLqbmqaNI5g//dq3whphpi', 'user', 'profile_31_1738150344.jpg', '2025-01-23 16:56:38', 1, 1),
(32, 'Jane', 'Smith', 23, 'Female', '09187654321', 'kikay05.tuliao@gmail.com', '2', '$2a$12$m4y1X0Fx4xu4.ZzVPL1CCem1fJHOxlrgLqbmqaNI5g//dq3whphpi', 'user', 'profile_32_1737716693.jpg', '2025-01-23 16:56:38', 3, 1),
(33, 'Mark', 'Johnson', 28, 'Male', '09123334445', 'markjohnson@email.com', '3', '$2a$12$m4y1X0Fx4xu4.ZzVPL1CCem1fJHOxlrgLqbmqaNI5g//dq3whphpi', 'user', 'profile3.jpg', '2025-01-23 16:56:38', 2, 1),
(34, 'Emily', 'Davis', 22, 'Female', '09234567890', 'emilydavis@email.com', '4', '$2y$10$XU1c/5FJHgg23AgKH.fV3eUYY0b2SRG.7oHpYYExAs.O2DF9ALjz6', 'user', 'profile4.jpg', '2025-01-23 16:56:38', 4, 1),
(35, 'Michael', 'Brown', 30, 'Male', '09345678901', 'michaelbrown@email.com', '5', '$2y$10$XU1c/5FJHgg23AgKH.fV3eUYY0b2SRG.7oHpYYExAs.O2DF9ALjz6', 'user', 'profile5.jpg', '2025-01-23 16:56:38', 5, 1),
(36, 'Sarah', 'Wilson', 27, 'Female', '09456789012', 'sarahwilson@email.com', '6', '$2y$10$XU1c/5FJHgg23AgKH.fV3eUYY0b2SRG.7oHpYYExAs.O2DF9ALjz6', 'user', 'profile6.jpg', '2025-01-23 16:56:38', 6, 1),
(37, 'Chris', 'Moore', 29, 'Male', '09567890123', 'chrismoore@email.com', '7', '$2y$10$XU1c/5FJHgg23AgKH.fV3eUYY0b2SRG.7oHpYYExAs.O2DF9ALjz6', 'user', 'profile7.jpg', '2025-01-23 16:56:38', 7, 1),
(38, 'Amanda', 'Taylor', 26, 'Female', '09678901234', 'amandataylor@email.com', '8', '$2y$10$XU1c/5FJHgg23AgKH.fV3eUYY0b2SRG.7oHpYYExAs.O2DF9ALjz6', 'user', 'profile8.jpg', '2025-01-23 16:56:38', 8, 0),
(39, 'David', 'Anderson', 24, 'Male', '09789012345', 'davidanderson@email.com', '9', '$2y$10$XU1c/5FJHgg23AgKH.fV3eUYY0b2SRG.7oHpYYExAs.O2DF9ALjz6', 'user', 'profile9.jpg', '2025-01-23 16:56:38', 9, 1),
(40, 'Sophia', 'Martinez', 25, 'Female', '09890123456', 'sophiamartinez@email.com', '10', '$2y$10$XU1c/5FJHgg23AgKH.fV3eUYY0b2SRG.7oHpYYExAs.O2DF9ALjz6', 'user', 'profile10.jpg', '2025-01-23 16:56:38', 10, 1),
(41, 'Ethan', 'Harris', 23, 'Male', '09901234567', 'ethanharris@email.com', 'ethanharris', '$2y$10$XU1c/5FJHgg23AgKH.fV3eUYY0b2SRG.7oHpYYExAs.O2DF9ALjz6', 'user', 'profile11.jpg', '2025-01-23 16:56:38', 11, 1),
(42, 'Isabella', 'Clark', 28, 'Female', '09102345678', 'isabellaclark@email.com', 'isabellaclark', '$2y$10$XU1c/5FJHgg23AgKH.fV3eUYY0b2SRG.7oHpYYExAs.O2DF9ALjz6', 'user', 'profile12.jpg', '2025-01-23 16:56:38', 12, 1),
(43, 'Liam', 'Rodriguez', 27, 'Male', '09213456789', 'liamrodriguez@email.com', 'liamrodriguez', '$2y$10$XU1c/5FJHgg23AgKH.fV3eUYY0b2SRG.7oHpYYExAs.O2DF9ALjz6', 'user', 'profile13.jpg', '2025-01-23 16:56:38', 13, 1),
(44, 'Olivia', 'Lewis', 26, 'Female', '09324567890', 'olivialewis@email.com', 'olivialewis', '$2y$10$XU1c/5FJHgg23AgKH.fV3eUYY0b2SRG.7oHpYYExAs.O2DF9ALjz6', 'user', 'profile14.jpg', '2025-01-23 16:56:38', 14, 1),
(45, 'Noah', 'Lee', 29, 'Male', '09435678901', 'noahlee@email.com', 'noahlee', '$2y$10$XU1c/5FJHgg23AgKH.fV3eUYY0b2SRG.7oHpYYExAs.O2DF9ALjz6', 'user', 'profile15.jpg', '2025-01-23 16:56:38', 15, 1),
(46, 'Ava', 'Walker', 30, 'Female', '09546789012', 'avawalker@email.com', 'avawalker', '$2y$10$XU1c/5FJHgg23AgKH.fV3eUYY0b2SRG.7oHpYYExAs.O2DF9ALjz6', 'user', 'profile16.jpg', '2025-01-23 16:56:38', 16, 1),
(47, 'Mason', 'Perez', 28, 'Male', '09657890123', 'masonperez@email.com', 'masonperez', '$2y$10$XU1c/5FJHgg23AgKH.fV3eUYY0b2SRG.7oHpYYExAs.O2DF9ALjz6', 'user', 'profile17.jpg', '2025-01-23 16:56:38', 17, 1),
(48, 'Mia', 'Hall', 25, 'Female', '09768901234', 'miahall@email.com', 'miahall', '$2y$10$XU1c/5FJHgg23AgKH.fV3eUYY0b2SRG.7oHpYYExAs.O2DF9ALjz6', 'user', 'profile18.jpg', '2025-01-23 16:56:38', 18, 1),
(49, 'Elijah', 'Allen', 24, 'Male', '09879012345', 'elijahallen@email.com', 'elijahallen', '$2y$10$XU1c/5FJHgg23AgKH.fV3eUYY0b2SRG.7oHpYYExAs.O2DF9ALjz6', 'user', 'profile19.jpg', '2025-01-23 16:56:38', 19, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` enum('login','logout') NOT NULL,
  `action_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`id`, `user_id`, `action`, `action_timestamp`) VALUES
(1, 31, 'login', '2025-01-30 14:16:38'),
(2, 31, 'logout', '2025-01-30 14:43:33'),
(3, 1, 'login', '2025-01-30 14:43:38'),
(4, 1, 'logout', '2025-01-30 15:04:58'),
(5, 31, 'login', '2025-01-30 15:05:00'),
(6, 31, 'logout', '2025-01-30 15:29:22'),
(7, 31, 'login', '2025-01-30 15:37:18'),
(8, 32, 'login', '2025-01-30 15:53:57'),
(9, 32, 'logout', '2025-01-30 17:03:22'),
(10, 31, 'logout', '2025-01-30 17:03:37'),
(11, 31, 'login', '2025-01-30 18:25:40'),
(12, 32, 'login', '2025-01-30 18:26:23'),
(13, 31, 'logout', '2025-01-30 18:35:03'),
(14, 1, 'login', '2025-01-30 18:35:12'),
(15, 32, 'logout', '2025-01-30 19:53:39'),
(16, 31, 'login', '2025-01-30 19:53:42'),
(17, 1, 'logout', '2025-01-30 20:03:34'),
(18, 31, 'logout', '2025-01-30 20:21:22'),
(19, 31, 'login', '2025-01-30 20:21:39'),
(20, 31, 'logout', '2025-01-30 20:46:57'),
(21, 1, 'login', '2025-01-30 20:47:04'),
(22, 1, 'logout', '2025-01-30 20:47:11');

-- --------------------------------------------------------

--
-- Table structure for table `vip_users`
--

CREATE TABLE `vip_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` enum('Mayor','ViceMayor') NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vip_users`
--

INSERT INTO `vip_users` (`id`, `name`, `position`, `username`, `password_hash`, `created_at`) VALUES
(1, 'Miguel B. Decena, Jr.', 'Mayor', 'v1', '$2a$12$m4y1X0Fx4xu4.ZzVPL1CCem1fJHOxlrgLqbmqaNI5g//dq3whphpi', '2025-01-30 15:35:13'),
(2, 'Christina C. Magbitang', 'ViceMayor', 'v2', '$2a$12$m4y1X0Fx4xu4.ZzVPL1CCem1fJHOxlrgLqbmqaNI5g//dq3whphpi', '2025-01-30 15:35:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department_position`
--
ALTER TABLE `department_position`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `position_id` (`position_id`);

--
-- Indexes for table `document_views`
--
ALTER TABLE `document_views`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_action` (`user_id`,`document_id`,`document_type`,`action`);

--
-- Indexes for table `file_permissions`
--
ALTER TABLE `file_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_id` (`file_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `file_views`
--
ALTER TABLE `file_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_id` (`file_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ingoing`
--
ALTER TABLE `ingoing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `recipient_id` (`recipient_id`);

--
-- Indexes for table `memorandums`
--
ALTER TABLE `memorandums`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vip_id` (`vip_id`);

--
-- Indexes for table `messages_with_attachments`
--
ALTER TABLE `messages_with_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `ordinances`
--
ALTER TABLE `ordinances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submitted_by` (`submitted_by`);

--
-- Indexes for table `ordinances_resolutions`
--
ALTER TABLE `ordinances_resolutions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `private_files`
--
ALTER TABLE `private_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `reject_reasons`
--
ALTER TABLE `reject_reasons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fk_reject_item` (`item_id`,`item_type`);

--
-- Indexes for table `resolutions`
--
ALTER TABLE `resolutions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submitted_by` (`submitted_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_id_dp` (`id_dp`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `vip_users`
--
ALTER TABLE `vip_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `department_position`
--
ALTER TABLE `department_position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `document_views`
--
ALTER TABLE `document_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `file_permissions`
--
ALTER TABLE `file_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `file_views`
--
ALTER TABLE `file_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ingoing`
--
ALTER TABLE `ingoing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `memorandums`
--
ALTER TABLE `memorandums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages_with_attachments`
--
ALTER TABLE `messages_with_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ordinances`
--
ALTER TABLE `ordinances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ordinances_resolutions`
--
ALTER TABLE `ordinances_resolutions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `private_files`
--
ALTER TABLE `private_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reject_reasons`
--
ALTER TABLE `reject_reasons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resolutions`
--
ALTER TABLE `resolutions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `vip_users`
--
ALTER TABLE `vip_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `department_position`
--
ALTER TABLE `department_position`
  ADD CONSTRAINT `department_position_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `department_position_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `file_permissions`
--
ALTER TABLE `file_permissions`
  ADD CONSTRAINT `file_permissions_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `ordinances_resolutions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `file_permissions_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `file_views`
--
ALTER TABLE `file_views`
  ADD CONSTRAINT `file_views_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `ordinances_resolutions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `file_views_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ingoing`
--
ALTER TABLE `ingoing`
  ADD CONSTRAINT `ingoing_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ingoing_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `memorandums`
--
ALTER TABLE `memorandums`
  ADD CONSTRAINT `memorandums_ibfk_1` FOREIGN KEY (`vip_id`) REFERENCES `vip_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages_with_attachments`
--
ALTER TABLE `messages_with_attachments`
  ADD CONSTRAINT `messages_with_attachments_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_with_attachments_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ordinances`
--
ALTER TABLE `ordinances`
  ADD CONSTRAINT `ordinances_ibfk_1` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ordinances_resolutions`
--
ALTER TABLE `ordinances_resolutions`
  ADD CONSTRAINT `ordinances_resolutions_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `private_files`
--
ALTER TABLE `private_files`
  ADD CONSTRAINT `private_files_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `resolutions`
--
ALTER TABLE `resolutions`
  ADD CONSTRAINT `resolutions_ibfk_1` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_id_dp` FOREIGN KEY (`id_dp`) REFERENCES `department_position` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
