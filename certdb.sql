-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2024 at 08:07 PM
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
-- Database: `certdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`) VALUES
(1, 'admin', '$2y$10$8DtCBRijwqkciStAXoJ9LewxuWngi.0X0MFD4BqSpDaRKrmh3ddiO');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `certificate_number` varchar(255) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `course` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `issue_date` date NOT NULL DEFAULT '2024-01-01',
  `mentor_name` varchar(255) NOT NULL DEFAULT 'Unknown'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `certificate_number`, `student_name`, `course`, `email`, `phone_number`, `issue_date`, `mentor_name`) VALUES
(2, 'CERT456', 'Jane Smith', 'hi', 'jane.smith@example.com', '987-654-3210', '2024-01-01', 'Dibyendu'),
(21, 'CERT1001', 'Anirban Biswas', 'Digital Marketing', 'toanirban@gmail.com', '9999999985', '2024-12-25', 'Anirban Da'),
(22, 'CERT1002', 'Sudip Kumar Denre', 'Digital Marketing', 'toskd@gmail.com', '5544558855', '2024-12-18', 'Anirban Da'),
(23, 'CERT1003', 'Sukanta Mondal', 'Web Development(WordPress)', 'tosukanta@gmail.com', '4568951525', '2024-12-24', 'Anirban Da'),
(24, 'CERT1004', 'Souvik Mridha', 'Graphic Design', 'tosouvik@gmail.com', '4568956854', '2024-12-11', 'Souvik Da'),
(25, 'CERT1005', 'Chanchal Haldur', 'Digital Marketing', 'tochanchal@gmail.com', '7458524564', '2025-01-02', 'Anirban Da'),
(26, 'CERT1006', 'Dibyendu Kumar Khaskel', 'Web Development(Coding)', 'todkkhaskel@gmail.com', '4585556865', '2024-12-26', 'Anirban Da'),
(27, 'CERT1007', 'Nabin Kumbhakar', 'Digital Marketing', 'tonabin@gmail.com', '4587878787', '2024-12-25', 'Anirban Da');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `certificate_number` (`certificate_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
