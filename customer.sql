-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2026 at 02:24 PM
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
(8, 'Goh Chee Ping', '$2y$10$WwnkobAup0xNDH8ajH4dbOOaAEH9mpED5VkQ2e0Lde2FUPDMwObg6', '012345678900', '01234567890', 'cheeping1212@gmail.com', '2026-02-03 20:44:06'),
(9, 'Goh Chee Ping', '$2y$10$oCdwTUPRrpw01MtPQEdJOOkguXgiox8Erk/M/EuDRpa3N.Y5Bev.a', '012345678999', '01234567899', 'cheeping121212@gmail.com', '2026-02-03 20:46:47'),
(10, 'Test', '$2y$10$8ffqryEfWPekx3SJSBKA7.WvzlI5SXkTA57MRh3tfF7zggwnmRw3G', '123456789012', '01201201234', 'test@gmail.com', '2026-02-03 21:19:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Cus_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `Cus_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
