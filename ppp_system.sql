-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Sep 08, 2025 at 03:56 PM
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
-- Database: `ppp_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `proposal_id` int(11) NOT NULL,
  `project_title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Ongoing','Completed','Pending') DEFAULT 'Pending',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proposals`
--

CREATE TABLE `proposals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `estimated_cost` decimal(15,2) NOT NULL,
  `roi` decimal(5,2) DEFAULT NULL,
  `attachments` varchar(255) DEFAULT NULL,
  `organization` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `status` enum('Pending','Under Review','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `viability_score` int(11) DEFAULT NULL,
  `viability_label` enum('Viable','Borderline','Not Viable') DEFAULT NULL,
  `viability_last_eval_at` datetime DEFAULT NULL,
  `viability_model_version` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposals`
--

INSERT INTO `proposals` (`id`, `user_id`, `project_title`, `category`, `description`, `address`, `estimated_cost`, `roi`, `attachments`, `organization`, `contact_person`, `email`, `phone`, `status`, `created_at`, `updated_at`, `viability_score`, `viability_label`, `viability_last_eval_at`, `viability_model_version`) VALUES
(1, 14, 'Market Market', 'others', 'ugaisfuagdisagdhikzax', 'asjkabksjdb', 2323232.00, 23.00, 'uploads/1756918319_1756480937620.jpg', 'Dandadan', 'Ayase', 'momo123@gmail.com', '09645433117', 'Pending', '2025-09-03 16:52:05', '2025-09-03 16:52:05', NULL, NULL, NULL, NULL),
(5, 12, 'Market Market', 'education', 'sdsf', 'Fiore KIngdom', 3366.00, 43.00, 'uploads/1756960883_1193347.jpg', 'Dandadan', '78538490987', 'user@example.com', '', 'Pending', '2025-09-04 04:41:23', '2025-09-04 04:41:23', NULL, NULL, NULL, NULL),
(6, 14, 'Guild', 'infrastructure', 'jshfdj', 'Fiore KIngdom', 1232.00, 999.99, 'uploads/1756966129_91b81dde5379dd7fd0108920a2ce1ea5.jpg', 'Fairy Tail', '78538490987', 'erza8@gmail.com', '', 'Pending', '2025-09-04 06:08:50', '2025-09-04 06:08:50', NULL, NULL, NULL, NULL),
(7, 14, 'Guild', 'Infrastructure', 'SJDHOISOIJSAPIJ', 'Fiore KIngdom', 1232.00, 999.99, 'uploads/1756966134_91b81dde5379dd7fd0108920a2ce1ea5.jpg', 'Fairy Tail', '78538490987', 'erza8@gmail.com', '', 'Rejected', '2025-09-04 06:08:54', '2025-09-06 17:37:54', NULL, NULL, NULL, NULL),
(8, 14, 'sdsdfsf', 'infrastructure', 'sad', 'Fiore KIngdom', 43423.00, 323.00, 'uploads/1756966585_1193347.jpg', 'eeeeee', '78538490987', 'user@gmail.com', '', 'Pending', '2025-09-04 06:16:25', '2025-09-04 06:16:25', NULL, NULL, NULL, NULL),
(9, 15, 'Market Market', 'Infrastructure', 'asd', 'ddddsd', 323.00, 12.00, 'uploads/1756989559_shinobu-kocho-and-wisteria-lpvk6bctw08mq0eq.webp', 'Demon Slayer', 'Kanae', 'erza8@gmail.com', '', 'Approved', '2025-09-04 12:39:19', '2025-09-06 16:58:38', NULL, NULL, NULL, NULL),
(11, 14, 'Market Market', 'Infrastructure', 'sddsd', 'sdsdsd', 224.00, 233.00, 'uploads/1757099245_Fuerte , Aira Jane - student-scores.pdf', 'Dandadan', 'fsgfs', 'erza8@gmail.com', '424678674534232', 'Approved', '2025-09-05 19:07:25', '2025-09-07 20:14:34', NULL, NULL, NULL, NULL),
(12, 14, 'Market Market', 'health', 'sddsd', 'sdsdsd', 224.00, 233.00, 'uploads/1757099357_Fuerte , Aira Jane - student-scores.pdf', 'Dandadan', 'fsgfs', 'erza8@gmail.com', '424678674534232', 'Pending', '2025-09-05 19:09:17', '2025-09-05 19:09:17', NULL, NULL, NULL, NULL),
(13, 14, 'Market Market', 'Infrastructure', 'sddsd', 'sdsdsd', 224.00, 233.00, 'uploads/1757099431_Fuerte , Aira Jane - student-scores.pdf', 'Dandadan', 'NEZOKU', 'erza8@gmail.com', '424678674534232', 'Approved', '2025-09-05 19:10:31', '2025-09-06 17:11:36', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `company` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `login_attempts` int(11) NOT NULL DEFAULT 0,
  `lock_time` int(11) NOT NULL DEFAULT 0,
  `reset_otp` varchar(10) DEFAULT NULL,
  `reset_expires` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `company`, `contact_number`, `password`, `role`, `created_at`, `login_attempts`, `lock_time`, `reset_otp`, `reset_expires`) VALUES
(1, 'LUCY', 'lucy@gmail.com', '', '', '$2y$10$1G6H9Rrr8Fhwu.NLhbhlAOpNSXmzyB6UnR6ncXLKZArklCjUI7ivO', 'user', '2025-05-20 12:04:43', 0, 0, NULL, NULL),
(3, 'erza scar', 'erza8@gmail.com', '', '', '$2y$10$kQMrLAqPgodDCWff/dV7LuPK7llN3o/jB0BjmJAkcciPXGuMId6q.', 'admin', '2025-05-20 12:21:33', 0, 0, NULL, NULL),
(7, 'Ruby Chan', 'ruby@gmail.com', '', '', '$2y$10$BI7WV1ieS7nT8eaIuCY5KelsN7VGsNIHu94/V89WDdfcZlvEs2voO', 'user', '2025-05-20 14:23:45', 0, 0, NULL, NULL),
(8, 'Luffy', 'luffy@gmail.com', '', '', '$2y$10$tnHE2CNNK0z08QWbhXhyg.5ZbSwzbK3e9aVl6PxrPIRaC4NGSjTJC', 'user', '2025-05-20 17:59:53', 0, 0, NULL, NULL),
(9, 'Aira Jane Fuerte', 'airajanefuerte008@gmail.com', '', '', '$2y$10$m8uCx0V1um67YZqhhjqN6erwy9askOt9MmTHO3O31.gGbrdcBSllW', 'admin', '2025-08-27 10:02:18', 0, 0, NULL, NULL),
(11, 'Jella Pronton', 'jella@123', '', '', '$2y$10$Yy8Ef5BTcZQ3/8W5C8Zj8uMARfzW164ucksyCASufQgc92o6uwwdy', 'user', '2025-09-03 10:20:34', 0, 0, NULL, NULL),
(12, 'Jella Pronton', 'jella@gmail.com', '', '', '$2y$10$tXsoI6Y1uQUeKkwfpQd5V.7IKNUEIh5KMqOyN5L5PHyWgcBnk2aCi', 'user', '2025-09-03 10:21:47', 0, 0, NULL, NULL),
(13, 'Aira Jane Fuerte', 'airajane@gmail.com', '', '', '$2y$10$GOuV0FY/1x8q9w.chFNVWOOQ3Ze8MQDsZTj3GNy0pFdEWeKrC.VvS', 'user', '2025-09-03 11:22:04', 0, 0, NULL, NULL),
(14, 'Momo Ayase', 'momo123@gmail.com', 'Dandadan', '097642315654', '$2y$10$BBtqygsqFq5av9MxgMkdAen/pDP1DrOde5xUwVBmjaI/bchbcdHmW', 'user', '2025-09-03 11:33:07', 0, 0, NULL, NULL),
(15, 'Shinobu Kochou', 'shinobu@gmail.com', 'Demon Slayer Corp.', '08754322176', '$2y$10$mx7HWqbxrsYvCZKL13sRReeCan35k19vAM4M4Olixv6tERlDzV6wu', 'user', '2025-09-04 12:36:21', 0, 0, NULL, NULL),
(16, 'User', 'user@gmai.com', 'Us', '09643577890', '$2y$10$4k7ma2FJs5h.Tjt/kLR6YuBGP.yI9/lxqSYOmOFZNp7f6IbyglVIu', 'user', '2025-09-04 14:17:22', 0, 0, NULL, NULL),
(17, 'AJ Fuerte', 'ajfuerte@gmail.com', 'BISIPI', '09345777661', '$2y$10$8GzycsxoyYY.G.5mJTyhUO81GoJth29jp3.zveCWsPg0a9z7qZ3.6', 'user', '2025-09-04 14:19:53', 0, 0, NULL, NULL),
(18, 'Daniel Fuerte', 'daniel@gmail.com', 'FHJKJL', '096958754875', '$2y$10$U5aTyvoq0VsHijfhuYHK1.FO8tnUcOj0fHiP3VxgxiO7U7VVJK/ye', 'user', '2025-09-06 06:24:37', 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `viability_results`
--

CREATE TABLE `viability_results` (
  `id` int(11) NOT NULL,
  `proposal_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `label` enum('Viable','Borderline','Not Viable') NOT NULL,
  `model_version` varchar(50) DEFAULT 'v1',
  `reasons` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`reasons`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_id` (`proposal_id`);

--
-- Indexes for table `proposals`
--
ALTER TABLE `proposals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `viability_results`
--
ALTER TABLE `viability_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_id` (`proposal_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposals`
--
ALTER TABLE `proposals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `viability_results`
--
ALTER TABLE `viability_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proposals`
--
ALTER TABLE `proposals`
  ADD CONSTRAINT `proposals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
