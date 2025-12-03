-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2025 at 12:56 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

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
(1, 'owner', 'hash_owner_example', 'Salon Owner', 'owner@opalglow.example', 'owner', '2025-10-28 11:21:14'),
(2, 'manager1', 'hash_mgr_example', 'Front Desk Manager', 'manager@opalglow.example', 'manager', '2025-10-28 11:21:14');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appointment_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `therapist_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `status` enum('Booked','Cancelled','Completed','NoShow','Rescheduled') DEFAULT 'Booked',
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `changed_by` int(11) DEFAULT NULL
) ;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appointment_id`, `customer_id`, `therapist_id`, `service_id`, `start_datetime`, `end_datetime`, `status`, `notes`, `created_at`, `updated_at`, `changed_by`) VALUES
(1, 1, 1, 1, '2025-11-03 10:00:00', '2025-11-03 11:00:00', 'Booked', 'First-time client', '2025-10-28 11:37:25', NULL, 1),
(2, 2, 2, 3, '2025-11-03 15:30:00', '2025-11-03 16:20:00', 'Booked', NULL, '2025-10-28 11:37:25', NULL, 1),
(3, 3, 1, 4, '2025-11-05 09:30:00', '2025-11-05 10:00:00', 'Booked', '', '2025-10-28 11:37:25', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(120) NOT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `first_name`, `last_name`, `email`, `phone`, `password_hash`, `created_at`, `is_deleted`) VALUES
(1, 'Maya', 'Singh', 'maya@example.com', '+230-5700-1111', 'hash_maya', '2025-10-28 11:23:54', 0),
(2, 'Leo', 'Ferreira', 'leo@example.com', '+230-5700-2222', 'hash_leo', '2025-10-28 11:23:54', 0),
(3, 'Ava', 'Morel', 'ava@example.com', '+230-5700-3333', 'hash_ava', '2025-10-28 11:23:54', 0);

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
) ;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `appointment_id`, `amount`, `method`, `status`, `paid_at`, `reference`) VALUES
(1, 1, 1800.00, 'card', 'Completed', '2025-11-03 11:05:00', 'POS#A123'),
(2, 2, 600.00, 'online', 'Pending', '2025-11-01 12:00:00', 'DEP#L456');

-- --------------------------------------------------------
--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
    client_id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(150) NOT NULL,
    client_email VARCHAR(150) NOT NULL,
    client_phone VARCHAR(50) NOT NULL,
    service VARCHAR(150) NOT NULL,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

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
) ;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`service_id`, `name`, `description`, `duration_minutes`, `price`, `is_active`) VALUES
(1, 'Swedish Massage', 'Relaxing full-body massage', 60, 1800.00, 1),
(2, 'Deep Tissue Massage', 'Targeted muscle therapy', 45, 1600.00, 1),
(3, 'Classic Facial', 'Cleanse + hydrate', 50, 1200.00, 1),
(4, 'Manicure', 'Nails + polish', 30, 700.00, 1);

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
(1, 'Nadia', 'Patel', 'nadia@salon.example', '+230-5700-4444', 'Massage', 1),
(2, 'Iris', 'Costa', 'iris@salon.example', '+230-5700-5555', 'Facial', 1);

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
) ;

--
-- Dumping data for table `therapist_availability`
--

INSERT INTO `therapist_availability` (`availability_id`, `therapist_id`, `start_datetime`, `end_datetime`, `is_recurring`, `weekday`, `notes`) VALUES
(1, 1, '2025-11-03 09:00:00', '2025-11-03 17:00:00', 0, NULL, 'Day shift'),
(2, 1, '2025-11-05 09:00:00', '2025-11-05 17:00:00', 0, NULL, 'Day shift'),
(3, 2, '2025-11-03 12:00:00', '2025-11-03 20:00:00', 0, NULL, 'Late shift'),
(4, 2, '2025-11-06 10:00:00', '2025-11-06 18:00:00', 0, NULL, 'Day shift');

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
  ADD KEY `fk_appt_customer` (`customer_id`),
  ADD KEY `fk_appt_service` (`service_id`),
  ADD KEY `fk_appt_changedby` (`changed_by`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`);

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
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `therapist`
--
ALTER TABLE `therapist`
  MODIFY `therapist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `therapist_availability`
--
ALTER TABLE `therapist_availability`
  MODIFY `availability_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `fk_appt_changedby` FOREIGN KEY (`changed_by`) REFERENCES `admin` (`admin_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_appt_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_appt_service` FOREIGN KEY (`service_id`) REFERENCES `service` (`service_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_appt_therapist` FOREIGN KEY (`therapist_id`) REFERENCES `therapist` (`therapist_id`) ON UPDATE CASCADE;

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
