-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2026 at 09:49 AM
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
  `status` varchar(50) DEFAULT 'Pending',
  `payment_status` varchar(50) DEFAULT 'Pending',
  `price` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`app_id`, `cus_id`, `app_date`, `app_time`, `service_type`, `status`, `payment_status`, `price`) VALUES
(1, 10, '2026-02-11', '11:46:00', 'Teeth Filling', 'Confirmed', 'Paid', 0.00),
(2, 10, '2026-02-03', '11:51:00', 'Teeth Filling', 'Confirmed', 'Pending', 0.00),
(3, 10, '2026-02-05', '10:17:00', 'Teeth Filling', 'Confirmed', 'Pending', 0.00),
(4, 10, '2026-02-05', '11:12:00', 'Clear Aligner', 'Confirmed', 'Pending', 0.00),
(5, 8, '2026-02-05', '15:08:00', 'Teeth Crown and Bridge', 'Confirmed', 'Pending', 0.00),
(6, 8, '2026-02-06', '15:26:00', 'Teeth Scaling', 'Confirmed', 'Pending', 0.00),
(7, 8, '2026-02-05', '16:30:00', 'Teeth Scaling', 'Confirmed', 'Pending', 0.00);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `app_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
