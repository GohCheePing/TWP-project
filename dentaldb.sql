-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2026 at 04:40 PM
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
-- Database: `dentaldb`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `app_id` int(11) NOT NULL,
  `cus_id` int(11) NOT NULL,
  `app_date` date NOT NULL,
  `app_time` time NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Cus_ID` int(11) NOT NULL,
  `Cus_Name` varchar(30) NOT NULL,
  `Cus_Password` varchar(255) NOT NULL,
  `Cus_IC` varchar(50) NOT NULL,
  `Cus_Phone` varchar(15) NOT NULL,
  `Cus_Email` varchar(40) NOT NULL,
  `Register_Date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`Cus_ID`, `Cus_Name`, `Cus_Password`, `Cus_IC`, `Cus_Phone`, `Cus_Email`, `Register_Date`) VALUES
(1, 'LZY', 'LZY11', '101010123', '01110336789', 'lzy1@gmail.com', '2026-01-25 00:46:51'),
(7, 'TT', '$2y$10$FYmHSQUtYQb3N6EX9VVN8OWq0UbbnBZoA7CbgSFwLK13ZWOwSvKiG', '060717010244', '013456789', 'tt@gmail.com', '2026-01-25 04:37:04'),
(8, 'Goh Chee Ping', '$2y$10$IqV1ousfZ5w2KjtA1Fyx7uJcgqsgQKDoKqNr/6dfZ6DtVw9l35yde', '012345678900', '01234567890', 'cheeping1212@gmail.com', '2026-02-01 16:11:41');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `service_description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_name`, `service_description`, `price`, `image_path`) VALUES
(1, 'Teeth Scaling', NULL, 150.00, 'default.jpg'),
(2, 'Teeth Filling', NULL, 120.00, 'default.jpg'),
(3, 'Clear Aligner', NULL, 5000.00, 'default.jpg'),
(4, 'Night Guard', NULL, 300.00, 'default.jpg'),
(5, 'Wisdom Teeth Removal', NULL, 800.00, 'default.jpg'),
(6, 'Fissure Sealant', NULL, 90.00, 'default.jpg'),
(7, 'Teeth Crown and Bridge', NULL, 1500.00, 'default.jpg'),
(8, 'Root Canal Treatment', NULL, 900.00, 'default.jpg'),
(9, 'Denture', NULL, 1200.00, 'default.jpg'),
(10, 'Full Mouth Rehabilitation', NULL, 8000.00, 'default.jpg'),
(11, 'Gum Treatment', NULL, 250.00, 'default.jpg'),
(12, 'Teeth Consultation', NULL, 50.00, 'default.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`app_id`),
  ADD KEY `cus_id` (`cus_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Cus_ID`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `app_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `Cus_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`cus_id`) REFERENCES `customer` (`Cus_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
