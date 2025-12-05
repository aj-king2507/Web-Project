-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2025 at 07:32 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `opalglow`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `role` enum('owner','manager','staff') DEFAULT 'manager',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password_hash`, `full_name`, `email`, `role`, `created_at`) VALUES
(1, 'owner', '$2y$10$PcO.jdZlmF1YVoFXfJlEVewJuQUtL8vpVIMPcuTVXIE9w/cwH2FF6', 'Salon Owner', 'owner@opalglow.com', 'owner', '2025-10-28 11:21:14'),
(2, 'manager1', 'hash_mgr_example', 'Front Desk Manager', 'manager@opalglow.example', 'manager', '2025-10-28 11:21:14');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appointment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `therapist_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `status` enum('Booked','Cancelled','Completed','NoShow','Rescheduled') DEFAULT 'Booked',
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `changed_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appointment_id`, `user_id`, `therapist_id`, `service_id`, `start_datetime`, `end_datetime`, `status`, `notes`, `created_at`, `updated_at`, `changed_by`) VALUES
(4, 4, 1, 1, '2025-12-11 13:06:00', '2025-12-11 13:51:00', 'Booked', 'Booked via public booking form', '2025-12-04 07:05:15', NULL, NULL),
(5, 4, 1, 1, '2025-12-11 13:51:00', '2025-12-11 14:36:00', 'Booked', 'Booked via public booking form', '2025-12-04 07:06:03', NULL, NULL),
(6, 4, 1, 6, '2025-12-04 11:06:00', '2025-12-04 12:21:00', 'Booked', 'Booked via public booking form', '2025-12-04 07:07:07', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `contact_us_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` varchar(230) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`contact_us_id`, `name`, `email`, `phone`, `subject`, `message`, `created_at`) VALUES
(1, 'Ajmeer Shadoobuccus', 'ajmeerkng@gmail.com', '58352791', 'Facial Revival', 'More info', '2025-12-04 11:19:50');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` enum('cash','card','online') NOT NULL,
  `status` enum('Pending','Completed','Failed','Refunded') DEFAULT 'Completed',
  `paid_at` datetime DEFAULT current_timestamp(),
  `reference` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `service_id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `duration_minutes` smallint(6) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`service_id`, `name`, `description`, `duration_minutes`, `price`, `is_active`) VALUES
(1, 'Japanese Head Spa', 'Relax your scalp and relieve tension', 45, 50.00, 1),
(2, 'Keratin Hair Treatment', 'Smooth and Frizz-free Hair Treatment', 90, 120.00, 1),
(3, 'Glow Revival Facial', 'Hydrate and Rejuvenate Your Skin', 60, 80.00, 1),
(4, 'Microdermabrasion', 'Exfoliate and Remove Dead Skin Cells', 50, 70.00, 1),
(5, 'Serenity Massage', 'Relaxing Full Body Massage', 60, 65.00, 1),
(6, 'Body Scrub and Wrap', 'Exfoliation and Hydrating Wrap for Smooth Skin', 75, 90.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `therapist`
--

CREATE TABLE `therapist` (
  `therapist_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `specialty` varchar(80) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `therapist`
--

INSERT INTO `therapist` (`therapist_id`, `first_name`, `last_name`, `email`, `phone`, `specialty`, `is_active`) VALUES
(1, 'Angela', 'Smith', 'angelasmith@opalglow.com', '+230-5700-4444', '12 years in holistic scalp & relaxation therapies\n\nFlair: Calm, meticulous, an', 1),
(2, 'Brian', 'Thompson', 'brianthompson@opalglow.com', '+230-5700-5555', '10 years in advanced hair care and straightening techniques.\r\nFlair: Creative, d', 1),
(3, 'Clara', 'Martinez', 'claramartinez@opalglow.com', '+230-5700-6666', '8 years in dermatology and facial rejuvenation; Flair: Friendly, professional, a', 1),
(4, 'David', 'Lee', 'davidlee@opalglow.com', '+230-5700-7777', '15 years in advanced skin exfoliation techniques; Flair: Precise, calm, and expe', 1),
(5, 'Mia', 'Wilson', 'miawilson@opalglow.com', '+230-5700-8888', '10 years in therapeutic massage & stress relief treatments; Flair: Relaxing, str', 1),
(6, 'Isabelle', 'Davis', 'isabelledavis@opalglow.com', '+230-5700-9999', '12 years in body care & skin revitalization; Flair: Energetic, precise, and skil', 1);

-- --------------------------------------------------------

--
-- Table structure for table `therapist_availability`
--

CREATE TABLE `therapist_availability` (
  `availability_id` int(11) NOT NULL,
  `therapist_id` int(11) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `is_recurring` tinyint(1) DEFAULT 0,
  `weekday` tinyint(4) DEFAULT NULL,
  `notes` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `therapist_availability`
--

INSERT INTO `therapist_availability` (`availability_id`, `therapist_id`, `start_datetime`, `end_datetime`, `is_recurring`, `weekday`, `notes`) VALUES
(1, 1, '2025-12-01 09:00:00', '2026-02-01 17:00:00', 0, NULL, 'Day shift'),
(2, 2, '2025-12-01 09:00:00', '2026-02-01 17:00:00', 0, NULL, 'Day shift'),
(3, 3, '2025-12-01 09:00:00', '2026-02-01 17:00:00', 0, NULL, 'Normal shift'),
(4, 4, '2025-12-01 09:00:00', '2026-02-01 17:00:00', 0, NULL, 'Day shift'),
(5, 5, '2025-12-01 09:00:00', '2026-02-01 17:00:00', 0, NULL, 'Day shift'),
(6, 6, '2025-12-01 09:00:00', '2026-02-01 17:00:00', 0, NULL, 'Day shift');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(120) NOT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `username`, `email`, `phone`, `password_hash`, `created_at`, `is_deleted`) VALUES
(4, 'Ajmeer', 'Shadoo-buccus', 'Ajmeer', 'ajmeerkng@gmail.com', '59794792', '$2y$10$W6JhV1uwkRkryprTx1..JO1YRES796xMTnETwB/CliNY2fB6RSSaG', '2025-12-02 10:24:57', 0),
(8, 'Sarah', 'Basdeo', 'Sarah Basdeo', 'sarah@gmail.com', '59272792', '$2y$10$f1QSUZWEuUUHLv7QxeEGwO00Vf659qNVR/dLS5ZCe88a.nZC.WyMy', '2025-12-05 06:20:16', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointment_id`),
  ADD UNIQUE KEY `uk_therapist_start` (`therapist_id`,`start_datetime`),
  ADD KEY `fk_appt_user` (`user_id`),
  ADD KEY `fk_appt_service` (`service_id`),
  ADD KEY `fk_appt_changedby` (`changed_by`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`contact_us_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD UNIQUE KEY `uk_payment_one_per_appt` (`appointment_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`service_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `therapist`
--
ALTER TABLE `therapist`
  ADD PRIMARY KEY (`therapist_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `therapist_availability`
--
ALTER TABLE `therapist_availability`
  ADD PRIMARY KEY (`availability_id`),
  ADD KEY `fk_avail_therapist` (`therapist_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `contact_us_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `therapist`
--
ALTER TABLE `therapist`
  MODIFY `therapist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `therapist_availability`
--
ALTER TABLE `therapist_availability`
  MODIFY `availability_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `fk_appt_changedby` FOREIGN KEY (`changed_by`) REFERENCES `admin` (`admin_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_appt_service` FOREIGN KEY (`service_id`) REFERENCES `service` (`service_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_appt_therapist` FOREIGN KEY (`therapist_id`) REFERENCES `therapist` (`therapist_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_appt_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_appt` FOREIGN KEY (`appointment_id`) REFERENCES `appointment` (`appointment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `therapist_availability`
--
ALTER TABLE `therapist_availability`
  ADD CONSTRAINT `fk_avail_therapist` FOREIGN KEY (`therapist_id`) REFERENCES `therapist` (`therapist_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
