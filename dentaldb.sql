-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2026-02-05 04:41:14
-- 服务器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `dentaldb`
--

-- --------------------------------------------------------

--
-- 表的结构 `appointments`
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
-- 转存表中的数据 `appointments`
--

INSERT INTO `appointments` (`app_id`, `cus_id`, `app_date`, `app_time`, `service_type`, `status`, `payment_status`, `price`) VALUES
(1, 10, '2026-02-11', '11:46:00', 'Teeth Filling', 'Confirmed', 'Paid', 0.00),
(2, 10, '2026-02-03', '11:51:00', 'Teeth Filling', 'Confirmed', 'Pending', 0.00),
(3, 10, '2026-02-05', '10:17:00', 'Teeth Filling', 'Confirmed', 'Pending', 0.00),
(4, 10, '2026-02-05', '11:12:00', 'Clear Aligner', 'Confirmed', 'Pending', 0.00),
(5, 8, '2026-02-05', '15:08:00', 'Teeth Crown and Bridge', 'Confirmed', 'Pending', 0.00),
(6, 8, '2026-02-06', '15:26:00', 'Teeth Scaling', 'Confirmed', 'Pending', 0.00),
(7, 8, '2026-02-05', '16:30:00', 'Teeth Scaling', 'Confirmed', 'Pending', 0.00),
(8, 8, '2026-02-05', '15:00:00', 'Teeth Scaling', 'Confirmed', 'Pending', 0.00),
(9, 10, '2026-02-05', '10:00:00', 'Wisdom Teeth Removal', 'Confirmed', 'Pending', 0.00),
(10, 10, '2026-02-05', '10:00:00', 'Teeth Filling', 'Confirmed', 'Pending', 0.00),
(11, 10, '2026-02-05', '17:20:00', 'Teeth Filling', 'Confirmed', 'Pending', 0.00);

-- --------------------------------------------------------

--
-- 表的结构 `customer`
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
-- 转存表中的数据 `customer`
--

INSERT INTO `customer` (`Cus_ID`, `Cus_Name`, `Cus_Password`, `Cus_IC`, `Cus_Phone`, `Cus_Email`, `Register_Date`) VALUES
(7, 'TT', '$2y$10$FYmHSQUtYQb3N6EX9VVN8OWq0UbbnBZoA7CbgSFwLK13ZWOwSvKiG', '060717010244', '013456789', 'tt@gmail.com', '2026-01-25 04:37:04'),
(8, 'Goh Chee Ping', '$2y$10$WwnkobAup0xNDH8ajH4dbOOaAEH9mpED5VkQ2e0Lde2FUPDMwObg6', '009876543210', '01234567890', 'cheeping1212@gmail.com', '2026-02-03 20:44:06'),
(10, 'Test', '$2y$10$8ffqryEfWPekx3SJSBKA7.WvzlI5SXkTA57MRh3tfF7zggwnmRw3G', '111111111111', '01201201234', 'test@gmail.com', '2026-02-03 21:19:47');

-- --------------------------------------------------------

--
-- 表的结构 `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `service_description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `services`
--

INSERT INTO `services` (`service_id`, `service_name`, `service_description`, `price`, `image_path`) VALUES
(1, 'Teeth Scaling', NULL, 80.00, 'default.jpg'),
(2, 'Teeth Filling', NULL, 12000.00, 'default.jpg'),
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
-- 转储表的索引
--

--
-- 表的索引 `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`app_id`),
  ADD KEY `cus_id` (`cus_id`);

--
-- 表的索引 `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Cus_ID`);

--
-- 表的索引 `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `appointments`
--
ALTER TABLE `appointments`
  MODIFY `app_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `customer`
--
ALTER TABLE `customer`
  MODIFY `Cus_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
