-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2024 at 08:54 PM
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
-- Database: `errsolsy`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `eno_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `gender` varchar(255) NOT NULL DEFAULT '-',
  `bdate` varchar(2) NOT NULL DEFAULT '-',
  `bmonth` varchar(2) NOT NULL DEFAULT '-',
  `byear` varchar(4) NOT NULL DEFAULT '-',
  `email` varchar(255) NOT NULL,
  `branch` varchar(255) NOT NULL DEFAULT '-',
  `sem` varchar(255) NOT NULL DEFAULT '-',
  `city` varchar(255) NOT NULL DEFAULT '-',
  `unique_id` int(255) NOT NULL,
  `zip` varchar(10) NOT NULL DEFAULT '-',
  `country` varchar(255) NOT NULL DEFAULT '-',
  `phone` varchar(15) NOT NULL DEFAULT '-',
  `adm_year` year DEFAULT NULL,
  `avatar` blob DEFAULT NULL,
  `role` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT 'offline',
  PRIMARY KEY (`eno_no`),
  KEY `idx_eno_no` (`eno_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `incoming_msg_id` int(11) NOT NULL,
  `outgoing_msg_id` int(11) NOT NULL,
  `msg` varchar(1000) NOT NULL,
  `eno_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`msg_id`),
  KEY `fk_message_user` (`eno_no`),
  CONSTRAINT `fk_message_user` FOREIGN KEY (`eno_no`) REFERENCES `users` (`eno_no`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
