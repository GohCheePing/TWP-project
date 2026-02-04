-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2026 at 10:33 AM
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
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
