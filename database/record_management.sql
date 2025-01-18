-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2025 at 11:52 PM
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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Finance'),
(2, 'Human Resources'),
(3, 'IT Department'),
(4, 'Marketing'),
(5, 'Operations'),
(7, 'sample'),
(8, 'sample2');

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
(1, 'Office of the Municipal Mayor'),
(2, 'Office of the Sangguniang Bayan'),
(4, 'General Services Office'),
(5, 'AMD'),
(6, 'SAMPLE');

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
(5, 1, 4),
(6, 1, 5),
(7, 2, 6),
(8, 4, 7),
(9, 5, 10),
(10, 6, 11);

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
(1, 2, 19, 'ordinance', 'view', '2025-01-16 08:58:55'),
(2, 2, 19, 'ordinance', 'download', '2025-01-16 06:19:46'),
(4, 2, 18, 'ordinance', 'view', '2025-01-16 08:58:48'),
(7, 2, 18, 'ordinance', 'download', '2025-01-16 13:42:11'),
(8, 2, 1, 'resolution', 'view', '2025-01-16 08:58:42'),
(12, 2, 20, 'ordinance', 'view', '2025-01-16 14:12:21'),
(13, 2, 20, 'ordinance', 'download', '2025-01-16 20:14:34');

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
(1, 1, 4),
(2, 2, 1),
(3, 2, 2),
(4, 2, 4),
(5, 2, 5),
(6, 3, 5),
(7, 4, 1),
(8, 5, 1),
(9, 6, 1),
(10, 7, 1),
(11, 7, 2),
(12, 7, 4),
(13, 7, 5),
(15, 8, 1),
(16, 8, 2),
(17, 9, 1),
(18, 10, 4),
(19, 11, 1),
(20, 11, 5),
(21, 12, 1),
(22, 13, 1),
(28, 14, 1),
(29, 14, 2),
(30, 14, 4),
(31, 14, 5),
(32, 14, 6);

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
(1, 2, 2, '2025-01-16 18:16:02'),
(2, 5, 2, '2025-01-16 22:58:12'),
(3, 7, 2, '2025-01-17 11:42:50'),
(4, 2, 3, '2025-01-17 18:31:32'),
(5, 8, 2, '2025-01-17 21:31:41'),
(6, 6, 2, '2025-01-17 21:31:59'),
(7, 4, 2, '2025-01-17 21:42:33'),
(8, 9, 2, '2025-01-17 21:51:22');

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
(1, 3, 2, 'Hello, this is a test message for ordinance 101.', NULL, 101, 'ordinance', '2025-01-14 10:00:00', 1),
(2, 2, 3, 'Thank you! I\'ll review it and get back to you.', NULL, 101, 'ordinance', '2025-01-14 10:05:00', 1),
(3, 3, 2, 'Can you review this attachment?', 'attachment1.pdf', 102, 'ordinance', '2025-01-14 11:00:00', 1),
(4, 2, 3, 'Got it. Reviewing now.', NULL, 102, 'ordinance', '2025-01-14 11:10:00', 1),
(5, 2, 3, 'asdasd', NULL, 0, 'ordinance', '2025-01-16 03:50:21', 1),
(6, 2, 3, 'sd', NULL, 0, 'ordinance', '2025-01-16 03:53:51', 1),
(7, 2, 3, 'qweqwe', NULL, 0, 'ordinance', '2025-01-16 04:02:43', 1),
(8, 3, 2, 'qweqwe', NULL, 0, 'ordinance', '2025-01-16 04:03:51', 1),
(9, 2, 3, 'qweqwe', NULL, 0, 'ordinance', '2025-01-16 04:05:47', 1),
(10, 3, 2, 'qweqwe', NULL, 0, 'ordinance', '2025-01-16 04:06:13', 1),
(11, 2, 3, 'qweqwe', NULL, 0, 'ordinance', '2025-01-16 04:12:28', 1),
(12, 2, 3, 'qweqweqwe', NULL, 0, 'ordinance', '2025-01-16 04:16:47', 1),
(13, 3, 2, 'qweqweqwe', NULL, 0, 'ordinance', '2025-01-16 04:16:57', 1),
(14, 2, 3, 'qweqwe', NULL, 0, 'ordinance', '2025-01-16 04:29:49', 1),
(15, 3, 2, 'qweqwe', NULL, 0, 'ordinance', '2025-01-16 04:30:00', 1),
(16, 3, 2, 'qweqweqwe', NULL, 0, 'ordinance', '2025-01-16 04:31:24', 1),
(17, 2, 3, 'qwe', NULL, 0, 'ordinance', '2025-01-16 04:31:39', 1),
(18, 3, 2, 'qweqweqwe', NULL, 0, 'ordinance', '2025-01-16 04:32:51', 1),
(19, 2, 3, 'qweqwe', NULL, 0, 'ordinance', '2025-01-16 04:34:56', 1),
(20, 2, 3, 'qweqwe', NULL, 0, 'ordinance', '2025-01-16 04:34:57', 1),
(21, 2, 3, 'qweqwe', NULL, 0, 'ordinance', '2025-01-16 04:34:59', 1),
(22, 2, 3, 'qwe', NULL, 0, 'ordinance', '2025-01-16 05:54:23', 1),
(23, 2, 3, 'qwe', NULL, 0, 'ordinance', '2025-01-16 06:00:23', 1),
(24, 2, 3, 'qwe', NULL, 0, 'ordinance', '2025-01-16 06:00:23', 1),
(25, 2, 3, 'wewewe', NULL, 0, 'ordinance', '2025-01-16 06:00:27', 1),
(26, 2, 3, 'wewewe', NULL, 0, 'ordinance', '2025-01-16 06:00:27', 1),
(27, 2, 3, 'ww', '../uploads/messages/678830b8224ff.docx', 0, 'ordinance', '2025-01-16 06:03:36', 1),
(28, 2, 5, 'wewe', NULL, 0, 'ordinance', '2025-01-16 14:50:18', 0),
(29, 2, 3, 'qwe', NULL, 0, 'ordinance', '2025-01-16 14:52:59', 1),
(30, 2, 3, 'qweqwe', NULL, 0, 'ordinance', '2025-01-16 15:05:32', 1),
(31, 2, 4, 'qweqwe', NULL, 0, 'ordinance', '2025-01-16 15:06:01', 1),
(32, 2, 4, 'qwe', NULL, 0, 'ordinance', '2025-01-16 15:14:06', 1),
(33, 2, 7, 'qweqwe', NULL, 0, 'ordinance', '2025-01-16 15:21:16', 0),
(34, 4, 2, 'wewe', NULL, 0, 'ordinance', '2025-01-16 15:31:03', 1),
(35, 2, 6, 'qweqwqwe', NULL, 0, 'ordinance', '2025-01-16 15:52:18', 0),
(36, 2, 7, 'eqweqweqwe', NULL, 0, 'ordinance', '2025-01-16 15:52:43', 0),
(37, 2, 4, 'qweqwe', NULL, 0, 'ordinance', '2025-01-16 16:00:11', 1),
(38, 2, 5, 'asdasdqweq', NULL, 0, 'ordinance', '2025-01-16 16:02:47', 0),
(39, 2, 3, 'qwe', NULL, 0, 'ordinance', '2025-01-16 16:03:08', 1),
(40, 2, 4, 'qweq', NULL, 0, 'ordinance', '2025-01-16 16:03:11', 1),
(41, 4, 2, 'qwe', NULL, 0, 'ordinance', '2025-01-16 16:04:23', 1),
(42, 4, 2, 'ew', NULL, 0, 'ordinance', '2025-01-16 16:04:28', 1),
(43, 4, 2, '22', NULL, 0, 'ordinance', '2025-01-16 16:05:44', 1),
(44, 2, 4, 'qweqwe', NULL, 0, 'ordinance', '2025-01-16 16:06:36', 1),
(45, 2, 6, 'qwe', NULL, 0, 'ordinance', '2025-01-16 16:13:30', 0),
(46, 2, 4, 'asd', NULL, 0, 'ordinance', '2025-01-16 16:22:20', 1),
(47, 2, 3, 'qweqwe', NULL, 0, 'ordinance', '2025-01-16 16:25:19', 1),
(48, 2, 3, 'we', '1737015999_1736955302_border.pdf', 0, 'ordinance', '2025-01-16 16:26:39', 1),
(49, 2, 7, 'wewe', NULL, 0, 'ordinance', '2025-01-16 16:57:15', 0),
(50, 3, 4, 'qwe', NULL, 0, 'ordinance', '2025-01-16 22:20:13', 0),
(51, 3, 2, 'qweqweqweqwe', NULL, 0, 'ordinance', '2025-01-16 22:36:01', 1),
(52, 2, 3, 'hi', NULL, 0, 'ordinance', '2025-01-17 19:40:47', 1),
(53, 2, 3, 'file to', '1737114071_1736955302_border.pdf', 0, 'ordinance', '2025-01-17 19:41:11', 1),
(54, 2, 3, 'hello', NULL, 0, 'ordinance', '2025-01-18 01:00:31', 1),
(55, 3, 2, 'hi', NULL, 0, 'ordinance', '2025-01-18 01:00:47', 1),
(56, 3, 2, 'hi', NULL, 0, 'ordinance', '2025-01-18 01:00:47', 1),
(57, 3, 2, 'h', NULL, 0, 'ordinance', '2025-01-18 01:02:04', 1),
(58, 2, 3, 'w', NULL, 0, 'ordinance', '2025-01-18 01:02:15', 1),
(59, 3, 2, 'asd', '1737139684_1736955302_border.pdf', 0, 'ordinance', '2025-01-18 02:48:04', 1),
(60, 3, 2, 'qweqwe', NULL, 0, 'ordinance', '2025-01-18 03:11:42', 1),
(61, 3, 2, 'we', '1737141111_1736955302_border.pdf', 0, 'ordinance', '2025-01-18 03:11:51', 1),
(62, 3, 2, 'eqweqwe', '1737141374_1736955302_border.pdf', 0, 'ordinance', '2025-01-18 03:16:14', 1),
(63, 3, 4, 'qweqwe', NULL, 0, 'ordinance', '2025-01-18 03:18:02', 0),
(64, 3, 4, 'qwe', '1737141488_1736955302_border.pdf', 0, 'ordinance', '2025-01-18 03:18:08', 0),
(65, 3, 4, 'qwe', NULL, 0, 'ordinance', '2025-01-18 03:18:14', 0),
(66, 3, 4, 'qweqwe', '1737141532_1736955302_border.pdf', 0, 'ordinance', '2025-01-18 03:18:52', 0),
(67, 3, 4, 'qweq', NULL, 0, 'ordinance', '2025-01-18 03:18:53', 0),
(68, 3, 5, 'qweqwe', NULL, 0, 'ordinance', '2025-01-18 03:20:08', 0),
(69, 3, 5, 'qweqweq', NULL, 0, 'ordinance', '2025-01-18 03:22:20', 0),
(70, 3, 2, 'qwe', NULL, 0, 'ordinance', '2025-01-18 03:30:09', 1);

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
  `rejection_reason` text DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `view_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ordinances`
--

INSERT INTO `ordinances` (`id`, `title`, `description`, `submitted_by`, `status`, `submission_date`, `rejection_reason`, `attachment`, `view_count`) VALUES
(11, 'Noise Control Ordinance', 'Regulation to control noise levels in residential areas.', 1, 'Approved', '2025-01-13 16:00:00', NULL, NULL, 0),
(12, 'Waste Management Ordinance', 'Guidelines for proper waste segregation and disposal.', 2, 'Approved', '2025-01-13 16:00:00', NULL, NULL, 0),
(13, 'Curfew Ordinance', 'Curfew from 10 PM to 4 AM for minors.', 3, 'Rejected', '2025-01-09 16:00:00', NULL, NULL, 0),
(14, 'Anti-Littering Ordinance', 'Prohibition of littering in public areas.', 4, 'Rejected', '2025-01-11 16:00:00', NULL, NULL, 0),
(15, 'Traffic Regulation Ordinance', 'Rules for managing traffic in high-density areas.', 2, 'Rejected', '2025-01-13 16:00:00', NULL, NULL, 0),
(16, 'sample', 'sample', 2, 'Rejected', '2025-01-14 14:44:59', NULL, NULL, 0),
(17, 'sample', 'sample', 2, 'Approved', '2025-01-14 15:03:45', NULL, '1736867025_Sample.docx', 1),
(18, 'sample', 'sample', 2, 'Approved', '2025-01-15 13:48:07', NULL, '1736948887_Sample.docx', 0),
(19, 'ew', 'ew', 2, 'Approved', '2025-01-15 15:35:02', NULL, '1736955302_border.pdf', 0),
(20, 'zsd', 'sd', 2, 'Approved', '2025-01-16 09:28:24', NULL, '1737019704_1736955302_border.pdf', 0),
(21, '4', 'wer', 2, 'Pending', '2025-01-16 09:29:10', NULL, '1737019750_1736955302_border.pdf', 0),
(22, 'sample', 'sample', 2, 'Pending', '2025-01-17 14:49:37', NULL, '1737125377_1736955302_border.pdf', 0);

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
(1, 'qwe', 'qweqwe', '1737031955_1736955302_border.pdf', 'Ordinance', 1, '2025-01-16 12:52:35'),
(2, 'qwe', 'eqwe', '1737049176_1736955302_border.pdf', 'Events', 1, '2025-01-16 17:39:36'),
(3, 'sampleee', 'sampleee', '1737065641_1736955302_border.pdf', 'None', 1, '2025-01-16 22:14:01'),
(4, 'EWEW', 'QWE', '1737065944_1736955302_border.pdf', 'None', 1, '2025-01-16 22:19:04'),
(5, 'qweqwe', 'qweqwe', '1737067205_1736955302_border.pdf', 'None', 1, '2025-01-16 22:40:05'),
(6, 'Upload Files', 'Upload Files', '1737069863_1736955302_border.pdf', 'None', 1, '2025-01-16 23:24:23'),
(7, 'public ', 'public', '1737114147_1736955302_border.pdf', 'Events', 1, '2025-01-17 11:42:27'),
(8, 'qweqwe', 'qweqweqwe', 'File_Views_Downloads.pdf', 'Ordinance', 1, '2025-01-17 11:44:55'),
(9, 'qwe', 'eweqwe', '1737150241_www.png', 'Ordinance', 1, '2025-01-17 21:44:01'),
(10, 'qwe', 'eqwe', '1737151924_1736955302_border.pdf', 'None', 1, '2025-01-17 22:12:04'),
(11, 'qwe', 'eqweqwe', '1737151991_www.png', 'None', 1, '2025-01-17 22:13:11'),
(12, 'wqeqw', 'eqweqwe', '1737152226_www.png', 'Ordinance', 1, '2025-01-17 22:17:06'),
(13, 'qweqw', 'eqweqweqwe', '1737152236_1736955302_border.pdf', 'Resolution', 1, '2025-01-17 22:17:16'),
(14, 'qweqw', 'eqweqwe', '1737152245_www.png', 'Events', 1, '2025-01-17 22:17:25');

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
(4, 'Municipal Mayor'),
(5, 'Administrative Officer V'),
(6, 'Secretary to the Sangguniang Bayan'),
(7, 'Administrative Assistant II'),
(10, 'GPU'),
(11, 'QWEQWEQWEQWEQWE');

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

--
-- Dumping data for table `reject_reasons`
--

INSERT INTO `reject_reasons` (`id`, `item_id`, `item_type`, `rejection_title`, `rejection_reason`, `rejection_date`) VALUES
(1, 1, 'ordinance', 'Duplicate Ordinance', 'This ordinance already exists.', '2025-01-14 22:33:45'),
(2, 2, 'resolution', 'asdas', 'dasdasd', '2025-01-14 22:34:24'),
(3, 13, 'ordinance', 'asda', 'sdasdasd', '2025-01-14 22:34:30'),
(4, 16, 'ordinance', 'qwe', 'qwe', '2025-01-15 21:41:53'),
(5, 6, 'resolution', 'e222', 'qweqweq', '2025-01-16 00:21:57'),
(6, 5, 'resolution', 'No Attachment	', 'No Attachment	', '2025-01-16 00:46:14');

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
  `rejection_reason` text DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `resolutions`
--

INSERT INTO `resolutions` (`id`, `title`, `description`, `submitted_by`, `status`, `submission_date`, `rejection_reason`, `attachment`) VALUES
(1, 'Scholarship Grant Resolution', 'Proposal to provide scholarships to deserving students.', 1, 'Approved', '2025-01-13 16:00:00', NULL, NULL),
(2, 'Community Center Construction', 'Resolution for constructing a new community center.', 2, 'Rejected', '2025-01-09 16:00:00', NULL, NULL),
(3, 'Healthcare Improvement Resolution', 'Proposal to improve healthcare facilities in the district.', 3, 'Pending', '2025-01-11 16:00:00', NULL, NULL),
(4, 'Green Park Development', 'Resolution to develop green spaces for recreational purposes.', 4, 'Pending', '2025-01-13 16:00:00', NULL, NULL),
(5, 'Public Safety Initiative', 'Plan to enhance public safety measures across the city.', 5, 'Rejected', '2025-01-13 16:00:00', '1736955302_border.pdf', NULL),
(6, 'qwe', 'qweqwe', 2, 'Rejected', '2025-01-15 16:20:33', NULL, '1736958033_border.pdf'),
(7, 'qwe', 'qweqwe', 2, 'Pending', '2025-01-16 14:12:05', NULL, '1737036725_1736955302_border.pdf');

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
(1, '', '', 0, 'Male', '', '', 'admin', '$2y$10$TdOrRhrc7dYmAPLzpBAF8.Slzqumgt2n0PXHRjg0VsVjljeL31F7u', 'admin', NULL, '2025-01-12 16:27:47', NULL, 1),
(2, 'Gin', 'Fast', 0, 'Male', '', 'castuel@gmail.com', 'users', '$2y$10$GlgTr2jSCIJ/0oQxDxUQm.CcZMkHeV8l1AoK1MvVi11MzX59mL59y', 'user', 'profile_2_1737128367.jpeg', '2025-01-12 16:27:47', 6, 1),
(3, 'Red', 'Horse', 4, 'Male', '0911111111', 'el@gmail.com', 'sample', '$2y$10$GlgTr2jSCIJ/0oQxDxUQm.CcZMkHeV8l1AoK1MvVi11MzX59mL59y', 'user', 'profile_3_1737038307.png', '2025-01-12 18:08:09', 9, 1),
(4, 'Tan', 'Duay', 0, 'Male', '', '', '2', '$2y$10$GlgTr2jSCIJ/0oQxDxUQm.CcZMkHeV8l1AoK1MvVi11MzX59mL59y', 'user', NULL, '2025-01-13 20:08:56', 5, 1),
(5, 'So', 'Ju', 22, 'Male', '0911111111', 'caffir@gmail.com', '38F66724', '$2y$10$GlgTr2jSCIJ/0oQxDxUQm.CcZMkHeV8l1AoK1MvVi11MzX59mL59y', 'user', NULL, '2025-01-14 09:41:09', 7, 1),
(6, 'Al', 'Fonso', 22, 'Female', '0911111111', 'yonshhai@gmail.com', 'DB191904', '$2y$10$WCxVqPSBgBHuxEDaj16uL.gTuzJieaKco.xyO90h.WvGQsXpyk2BK', 'user', NULL, '2025-01-14 10:01:14', 5, 1),
(7, 'Wis', 'Key', 23, 'Male', '0911111111', 'jowelynjoytasi@gmail.com', '3CB09194', '$2y$10$wMWW7wbnahsDV49MVXPjDeMMd8HXWpj0xvs5T8BZfJffb9o7jGTVC', 'user', NULL, '2025-01-15 13:38:20', 8, 0);

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
(1, 2, 'logout', '2025-01-16 20:07:20'),
(2, 2, 'login', '2025-01-16 20:14:00'),
(3, 2, 'logout', '2025-01-16 21:08:23'),
(4, 2, 'login', '2025-01-16 21:09:00'),
(5, 2, 'logout', '2025-01-16 23:21:46'),
(6, 1, 'login', '2025-01-16 23:21:51'),
(7, 1, 'logout', '2025-01-16 23:24:28'),
(8, 2, 'login', '2025-01-16 23:24:34'),
(9, 2, 'logout', '2025-01-16 23:25:38'),
(10, 1, 'login', '2025-01-16 23:25:45'),
(11, 1, 'login', '2025-01-17 11:06:02'),
(12, 1, 'logout', '2025-01-17 11:36:34'),
(13, 2, 'login', '2025-01-17 11:36:43'),
(14, 2, 'logout', '2025-01-17 11:38:04'),
(15, 3, 'login', '2025-01-17 11:38:16'),
(16, 3, 'logout', '2025-01-17 11:39:01'),
(17, 2, 'login', '2025-01-17 11:40:08'),
(18, 3, 'login', '2025-01-17 11:40:14'),
(19, 3, 'logout', '2025-01-17 11:41:24'),
(20, 1, 'login', '2025-01-17 11:41:28'),
(21, 1, 'logout', '2025-01-17 11:49:06'),
(22, 2, 'login', '2025-01-17 11:49:12'),
(23, 2, 'logout', '2025-01-17 11:53:22'),
(24, 1, 'login', '2025-01-17 11:53:28'),
(25, 1, 'logout', '2025-01-17 12:59:52'),
(26, 2, 'login', '2025-01-17 13:00:15'),
(27, 1, 'login', '2025-01-17 14:47:55'),
(28, 2, 'logout', '2025-01-17 16:56:31'),
(29, 3, 'login', '2025-01-17 16:58:49'),
(30, 1, 'logout', '2025-01-17 16:59:33'),
(31, 2, 'login', '2025-01-17 16:59:56'),
(32, 3, 'logout', '2025-01-17 19:34:21'),
(33, 1, 'login', '2025-01-17 19:34:26'),
(34, 2, 'login', '2025-01-17 21:07:19'),
(35, 2, 'logout', '2025-01-17 22:31:14'),
(36, 1, 'logout', '2025-01-17 22:50:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `department_position`
--
ALTER TABLE `department_position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `document_views`
--
ALTER TABLE `document_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `file_permissions`
--
ALTER TABLE `file_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `file_views`
--
ALTER TABLE `file_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `messages_with_attachments`
--
ALTER TABLE `messages_with_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `ordinances`
--
ALTER TABLE `ordinances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `ordinances_resolutions`
--
ALTER TABLE `ordinances_resolutions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reject_reasons`
--
ALTER TABLE `reject_reasons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `resolutions`
--
ALTER TABLE `resolutions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

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
