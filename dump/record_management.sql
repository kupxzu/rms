-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2025 at 11:00 PM
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
(4, 'General Services Office');

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
(8, 4, 7);

-- --------------------------------------------------------

--
-- Table structure for table `direct_messages`
--

CREATE TABLE `direct_messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `related_item_id` int(11) NOT NULL,
  `related_item_type` enum('ordinance','resolution') NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `direct_messages`
--

INSERT INTO `direct_messages` (`id`, `sender_id`, `receiver_id`, `related_item_id`, `related_item_type`, `message`, `timestamp`, `sent_at`) VALUES
(1, 2, 3, 12, 'ordinance', 'Hello, I have a question about the ordinance.', '2025-01-14 20:17:23', '2025-01-14 20:17:23'),
(2, 3, 2, 12, 'ordinance', 'Sure, let me know your question.', '2025-01-14 20:17:23', '2025-01-14 20:17:23'),
(3, 2, 3, 12, 'ordinance', 'Can you provide clarification on section 3?', '2025-01-14 20:17:23', '2025-01-14 20:17:23'),
(4, 3, 2, 12, 'ordinance', 'Yes, section 3 refers to the implementation guidelines.', '2025-01-14 20:17:23', '2025-01-14 20:17:23'),
(5, 2, 3, 12, 'ordinance', 'Thank you for the clarification!', '2025-01-14 20:17:23', '2025-01-14 20:17:23'),
(6, 3, 2, 12, 'ordinance', 'You are welcome!', '2025-01-14 20:17:23', '2025-01-14 20:17:23'),
(7, 2, 3, 13, 'ordinance', 'asdasd', '2025-01-14 20:37:45', '2025-01-14 20:37:45'),
(8, 2, 3, 13, 'ordinance', 'asdasd', '2025-01-14 20:37:45', '2025-01-14 20:37:45'),
(9, 2, 3, 13, 'ordinance', 'ewewe', '2025-01-14 20:38:31', '2025-01-14 20:38:31'),
(10, 3, 3, 13, 'ordinance', 'qweqwe', '2025-01-14 20:39:31', '2025-01-14 20:39:31'),
(11, 3, 3, 12, 'ordinance', 'qweqwe', '2025-01-14 20:39:39', '2025-01-14 20:39:39'),
(12, 2, 3, 12, 'ordinance', 'asdasd', '2025-01-14 20:40:00', '2025-01-14 20:40:00'),
(13, 2, 3, 13, 'ordinance', 'qweqwe', '2025-01-14 21:02:50', '2025-01-14 21:02:50');

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
(16, 'sample', 'sample', 2, 'Pending', '2025-01-14 14:44:59', NULL, NULL, 0),
(17, 'sample', 'sample', 2, 'Pending', '2025-01-14 15:03:45', NULL, '1736867025_Sample.docx', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ordinance_replies`
--

CREATE TABLE `ordinance_replies` (
  `id` int(11) NOT NULL,
  `ordinance_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `reply_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ordinance_replies`
--

INSERT INTO `ordinance_replies` (`id`, `ordinance_id`, `user_id`, `message`, `attachment`, `reply_date`) VALUES
(1, 14, 4, 'qwe', NULL, '2025-01-15 01:26:51'),
(2, 14, 2, 'qweqwe', NULL, '2025-01-15 01:32:13'),
(3, 14, 4, 'qweqwe', NULL, '2025-01-15 01:47:41');

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
(7, 'Administrative Assistant II');

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `records` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `records`
--

INSERT INTO `records` (`id`, `title`, `description`, `category_id`, `created_at`) VALUES
(1, 'Budget Report Q11', 'This report contains the budget analysis for the first quarter of the year.', 5, '2025-01-12 18:26:25'),
(2, 'Employee Onboarding Guide', 'A guide to onboarding new employees.', 2, '2025-01-12 18:26:25'),
(3, 'Server Maintenance Schedule', 'Planned maintenance schedule for IT servers.', 3, '2025-01-12 18:26:25'),
(4, 'Ad Campaign Results', 'Results from the latest marketing campaign.', 4, '2025-01-12 18:26:25'),
(5, 'Inventory Management Workflow', 'Workflow for managing inventory in operations.', 5, '2025-01-12 18:26:25'),
(7, 'sample', 'sample', 2, '2025-01-13 11:58:13');

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
(3, 13, 'ordinance', 'asda', 'sdasdasd', '2025-01-14 22:34:30');

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
(3, 'Healthcare Improvement Resolution', 'Proposal to improve healthcare facilities in the district.', 3, 'Approved', '2025-01-11 16:00:00', NULL, NULL),
(4, 'Green Park Development', 'Resolution to develop green spaces for recreational purposes.', 4, 'Approved', '2025-01-13 16:00:00', NULL, NULL),
(5, 'Public Safety Initiative', 'Plan to enhance public safety measures across the city.', 5, 'Rejected', '2025-01-13 16:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `transaction_type` enum('borrow','return') NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `record_id`, `user_id`, `transaction_type`, `transaction_date`) VALUES
(1, 1, 1, 'borrow', '2025-01-12 19:10:59'),
(2, 4, 2, 'return', '2025-01-12 19:13:46'),
(4, 1, 1, 'borrow', '2025-01-12 19:19:45'),
(5, 1, 2, 'borrow', '2025-01-12 19:19:45'),
(6, 3, 2, 'borrow', '2025-01-13 11:57:24');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_dp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `age`, `sex`, `contact`, `email`, `username`, `password`, `role`, `created_at`, `id_dp`) VALUES
(1, '', '', 0, 'Male', '', '', 'admin', '$2y$10$TdOrRhrc7dYmAPLzpBAF8.Slzqumgt2n0PXHRjg0VsVjljeL31F7u', 'admin', '2025-01-12 16:27:47', NULL),
(2, 'name1', 'name', 0, 'Male', '', '', 'users', '$2y$10$GlgTr2jSCIJ/0oQxDxUQm.CcZMkHeV8l1AoK1MvVi11MzX59mL59y', 'user', '2025-01-12 16:27:47', 6),
(3, '1', '2', 0, 'Male', '', '', 'sample', '$2y$10$GlgTr2jSCIJ/0oQxDxUQm.CcZMkHeV8l1AoK1MvVi11MzX59mL59y', 'user', '2025-01-12 18:08:09', NULL),
(4, 'name', 'name', 0, 'Male', '', '', '2', '$2y$10$GlgTr2jSCIJ/0oQxDxUQm.CcZMkHeV8l1AoK1MvVi11MzX59mL59y', 'user', '2025-01-13 20:08:56', 5),
(5, 'sample', 'sample', 22, 'Male', '0911111111', 'caffir@gmail.com', '38F66724', '$2y$10$GlgTr2jSCIJ/0oQxDxUQm.CcZMkHeV8l1AoK1MvVi11MzX59mL59y', 'user', '2025-01-14 09:41:09', 7),
(6, 'sample2', 'sample', 22, 'Female', '0911111111', 'yonshhai@gmail.com', 'DB191904', '$2y$10$GlgTr2jSCIJ/0oQxDxUQm.CcZMkHeV8l1AoK1MvVi11MzX59mL59y', 'user', '2025-01-14 10:01:14', 5);

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
-- Indexes for table `direct_messages`
--
ALTER TABLE `direct_messages`
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
-- Indexes for table `ordinance_replies`
--
ALTER TABLE `ordinance_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ordinance_id` (`ordinance_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

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
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `record_id` (`record_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_id_dp` (`id_dp`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `department_position`
--
ALTER TABLE `department_position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `direct_messages`
--
ALTER TABLE `direct_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `ordinances`
--
ALTER TABLE `ordinances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `ordinance_replies`
--
ALTER TABLE `ordinance_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reject_reasons`
--
ALTER TABLE `reject_reasons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `resolutions`
--
ALTER TABLE `resolutions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- Constraints for table `direct_messages`
--
ALTER TABLE `direct_messages`
  ADD CONSTRAINT `direct_messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `direct_messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ordinances`
--
ALTER TABLE `ordinances`
  ADD CONSTRAINT `ordinances_ibfk_1` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ordinance_replies`
--
ALTER TABLE `ordinance_replies`
  ADD CONSTRAINT `ordinance_replies_ibfk_1` FOREIGN KEY (`ordinance_id`) REFERENCES `ordinances` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ordinance_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `records`
--
ALTER TABLE `records`
  ADD CONSTRAINT `records_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `resolutions`
--
ALTER TABLE `resolutions`
  ADD CONSTRAINT `resolutions_ibfk_1` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `records` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_id_dp` FOREIGN KEY (`id_dp`) REFERENCES `department_position` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
