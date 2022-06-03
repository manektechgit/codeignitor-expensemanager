-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2022 at 08:56 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `expense_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('A','I','D') NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created`, `modified`, `status`) VALUES
(1, 'Salary Edited', '2022-01-22 16:25:22', '2022-01-22 16:26:09', 'D'),
(2, 'Salary', '2022-01-22 16:32:09', '2022-01-22 16:32:09', 'A'),
(3, 'Electricity Bills', '2022-01-22 16:32:20', '2022-01-22 16:32:20', 'A'),
(4, 'Food expense', '2022-01-22 16:32:25', '2022-01-22 16:32:25', 'A'),
(5, 'Other Income', '2022-01-22 16:32:31', '2022-01-22 16:32:31', 'A'),
(6, 'Other Expense', '2022-01-22 16:32:37', '2022-01-22 16:32:37', 'A'),
(7, 'Food Bills', '2022-01-22 16:32:43', '2022-01-22 16:32:43', 'A'),
(8, 'Tea Expense', '2022-01-22 16:32:48', '2022-01-22 16:32:48', 'A'),
(9, 'Raw materials', '2022-01-22 16:33:00', '2022-01-22 16:33:13', 'A'),
(10, 'Grant Sanctioned', '2022-01-22 23:14:21', '2022-01-22 23:14:21', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `id` int(11) NOT NULL,
  `fk_category_id` int(11) NOT NULL,
  `title` varchar(1024) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `expense_date` date NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('A','I','D') NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `expense`
--

INSERT INTO `expense` (`id`, `fk_category_id`, `title`, `amount`, `expense_date`, `description`, `created`, `modified`, `status`) VALUES
(1, 3, 'Light Bill paid', '2000', '2022-01-20', 'Light bill paid for month of Nov-Dec 2021', '2022-01-22 23:48:09', '2022-01-22 23:49:57', 'A'),
(2, 4, 'Monthly Lunch', '5500', '2022-01-01', 'Food Bill Paid for monthly lunch packs', '2022-01-22 23:48:53', '2022-01-22 23:49:41', 'A'),
(3, 8, 'Tea expense as on january 2022', '1500', '2022-01-23', '', '2022-01-23 00:45:07', '2022-01-23 00:45:07', 'A'),
(4, 9, 'Raw Materials', '2200', '2022-01-12', '', '2022-01-23 00:46:04', '2022-01-23 00:46:04', 'A'),
(5, 3, 'Electricity Bill Paid', '3000', '2021-12-20', '', '2022-01-23 00:46:36', '2022-01-23 00:46:36', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `income`
--

CREATE TABLE `income` (
  `id` int(11) NOT NULL,
  `fk_category_id` int(11) NOT NULL,
  `title` varchar(1024) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `income_date` date NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('A','I','D') NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `income`
--

INSERT INTO `income` (`id`, `fk_category_id`, `title`, `amount`, `income_date`, `description`, `created`, `modified`, `status`) VALUES
(1, 2, 'Salary Credited January 2022', '50000', '2022-01-01', 'salary credited for the month of January 2022', '2022-01-22 22:48:16', '2022-01-22 23:09:30', 'D'),
(2, 3, 'Light Bill paid', '5500', '2022-01-06', 'asfasf', '2022-01-22 22:48:40', '2022-01-22 23:09:24', 'D'),
(3, 4, 'Lunch', '2000', '2022-01-04', 'saf', '2022-01-22 22:51:12', '2022-01-22 23:09:27', 'D'),
(4, 10, 'Grant Sanctioned', '325000', '2021-12-01', 'Got grant sanctioned on december 2021', '2022-01-22 23:15:01', '2022-01-22 23:15:01', 'A'),
(5, 10, 'Grant Sanctioned', '400000', '2022-01-01', '', '2022-01-22 23:15:30', '2022-01-22 23:15:30', 'A'),
(6, 2, 'Salary Credited', '20000', '2022-01-10', '', '2022-01-22 23:27:15', '2022-01-22 23:27:15', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `role_master`
--

CREATE TABLE `role_master` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('A','I','D') NOT NULL DEFAULT 'A'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role_master`
--

INSERT INTO `role_master` (`id`, `name`, `created`, `modified`, `status`) VALUES
(1, 'Admin', '2020-05-05 22:52:36', '2020-05-05 22:52:36', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fk_role_id` int(11) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `profile_image` varchar(50) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('A','I','D') NOT NULL DEFAULT 'A'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fk_role_id`, `first_name`, `last_name`, `email`, `password`, `phone`, `profile_image`, `created`, `modified`, `status`) VALUES
(1, 1, 'Admin', 'EM', 'admin@em.com', 'YWRtaW4=', '1234567890', '', '2020-11-10 22:38:43', '2022-01-22 15:45:03', 'A');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income`
--
ALTER TABLE `income`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_master`
--
ALTER TABLE `role_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `income`
--
ALTER TABLE `income`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `role_master`
--
ALTER TABLE `role_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
