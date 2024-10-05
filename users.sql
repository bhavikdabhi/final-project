-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2024 at 08:12 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `users`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `eno_no` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL DEFAULT '-',
  `bdate` varchar(255) NOT NULL DEFAULT '-',
  `bmonth` varchar(255) NOT NULL DEFAULT '-',
  `byear` varchar(255) NOT NULL DEFAULT '-',
  `email` varchar(255) NOT NULL,
  `branch` varchar(255) NOT NULL DEFAULT '-',
  `sem` varchar(255) NOT NULL DEFAULT '-',
  `city` varchar(255) NOT NULL DEFAULT '-',
  `street` varchar(255) NOT NULL DEFAULT '-',
  `zip` varchar(255) NOT NULL DEFAULT '-',
  `country` varchar(255) NOT NULL DEFAULT '-',
  `phone` varchar(255) NOT NULL DEFAULT '-',
  `adm_year` longtext DEFAULT NULL,
  `avatar` longblob DEFAULT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`first_name`, `last_name`, `eno_no`, `gender`, `bdate`, `bmonth`, `byear`, `email`, `branch`, `sem`, `city`, `street`, `zip`, `country`, `phone`, `adm_year`, `avatar`, `role`) VALUES
('Aarav', 'Patel', '231293116001', 'Male', '01', '01', '2000', 'aarav.patel@example.com', 'Computer Science', '5', 'Ahmedabad', '123 MG Road', '380001', 'India', '9876543210', '2018', NULL, 'student'),
('Anaya', 'Sharma', '231293116002', 'Female', '02', '02', '2001', 'anaya.sharma@example.com', 'Mechanical Engineering', '6', 'Surat', '456 Ring Road', '395003', 'India', '8765432109', '2019', NULL, 'student'),
('Ishaan', 'Singh', '231293116003', 'Male', '03', '03', '2002', 'ishaan.singh@example.com', 'Electrical Engineering', '4', 'Vadodara', '789 Alkapuri', '390007', 'India', '7654321098', '2020', NULL, 'student'),
('Diya', 'Reddy', '231293116004', 'Female', '04', '04', '2003', 'diya.reddy@example.com', 'Civil Engineering', '3', 'Rajkot', '101 Race Course Road', '360001', 'India', '6543210987', '2021', NULL, 'student'),
('Vivaan', 'Kumar', '231293116005', 'Male', '05', '05', '2000', 'vivaan.kumar@example.com', 'Chemical Engineering', '5', 'Bhavnagar', '202 Waghawadi Road', '364001', 'India', '5432109876', '2018', NULL, 'student'),
('Aadhya', 'Nair', '231293116006', 'Female', '06', '06', '2001', 'aadhya.nair@example.com', 'Aerospace Engineering', '6', 'Jamnagar', '303 Patel Colony', '361008', 'India', '4321098765', '2019', NULL, 'student'),
('Arjun', 'Mehta', '231293116007', 'Male', '07', '07', '2002', 'arjun.mehta@example.com', 'Biomedical Engineering', '4', 'Junagadh', '404 Majevadi Gate', '362001', 'India', '3210987654', '2020', NULL, 'student'),
('Kavya', 'Bose', '231293116008', 'Female', '08', '08', '2003', 'kavya.bose@example.com', 'Industrial Engineering', '3', 'Anand', '505 Vallabh Vidyanagar', '388120', 'India', '2109876543', '2021', NULL, 'student'),
('Rohan', 'Das', '231293116009', 'Male', '09', '09', '2000', 'rohan.das@example.com', 'Software Engineering', '5', 'Gandhinagar', '606 Sector 17', '382017', 'India', '1098765432', '2018', NULL, 'student'),
('Mira', 'Chopra', '231293116010', 'Female', '10', '10', '2001', 'mira.chopra@example.com', 'Petroleum Engineering', '6', 'Bhuj', '707 Hospital Road', '370001', 'India', '0987654321', '2019', NULL, 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`eno_no`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
