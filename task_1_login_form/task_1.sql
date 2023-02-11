-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2023 at 03:55 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kcsc`
--

-- --------------------------------------------------------

--
-- Table structure for table `task_1`
--

CREATE TABLE `task_1` (
  `id` int(100) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_1`
--

INSERT INTO `task_1` (`id`, `username`, `password`, `address`, `phone`, `image`) VALUES
(9, 'hongnam', '25f9e794323b453885f5181f1b624d0b', '141 Chien Thang', '0123456789', 'hongnamAvatar.jpg'),
(11, 'trunghau', '25f9e794323b453885f5181f1b624d0b', '141 Chien Thang', '0123456789', 'trunghauAvatar.jpg'),
(12, 'dinhkhiem', '25f9e794323b453885f5181f1b624d0b', '141 Chien Thang', '0123456789', 'dinhkhiemAvatar.jpg'),
(14, 'tuananh', '25f9e794323b453885f5181f1b624d0b', '141 Chien Thang', '0123456789', 'tuananhAvatar.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `task_1`
--
ALTER TABLE `task_1`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `task_1`
--
ALTER TABLE `task_1`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
