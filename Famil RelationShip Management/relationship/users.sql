-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2024 at 06:51 PM
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
-- Database: `relationship`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(255) NOT NULL,
  `common_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` enum('male','female','others') NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `father_id` varchar(255) DEFAULT NULL,
  `mother_id` varchar(255) DEFAULT NULL,
  `marital_id` varchar(255) DEFAULT NULL,
  `SIBLINGS_ID` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `common_id`, `name`, `gender`, `password`, `address`, `dob`, `father_id`, `mother_id`, `marital_id`, `SIBLINGS_ID`) VALUES
('0040094392002057', '4955830035446996', 'SONY', 'male', '$2y$10$AtJc5xKIuYlITfVEqGBwR.3JOReD0Lur5nYrwGUL5hSkSd6jk3c4m', 'KAR', '0000-00-00', NULL, NULL, NULL, '1161550309325385'),
('0887535772953003', '2582766030271331', 'RAJA', 'male', '$2y$10$UldrnN9qiozmNvmO.kwwg.aQ75OZMqIoHXdeXUNuQV4M3ZD8mFYuG', 'NAM', '0000-00-00', '3504376285247770', '9975885224833769', '1161550309325385', '2133529551574202'),
('1161550309325385', '1978161536171007', 'SAJA', 'female', '$2y$10$YOW//mB3fNnCKVtXHrtNluLLGUxe2m7zT.zqob104g/xDIdlamuAm', 'KAR', '0000-00-00', '0040094392002057', '9188080556388512', '0887535772953003', '2133529551574202'),
('2133529551574202', '9145489836333482', 'MAJA', 'male', '$2y$10$R0bW0MiHLzYMD/WGI6HwyujZiuUBET46WZ512A8icHT.0uAavz8u.', 'NAM', '0000-00-00', '0887535772953003', '1161550309325385', '', NULL),
('2696719591591931', '5611555323363039', 'ANANTH S', 'male', '$2y$10$7l3XfhizuV.Ap9XQKfKxPuANMtf1hxzatBAXO3den.fbcVsi5QguG', 'namakkal', '0113-03-12', '4592485123123285', '2696719591591906', '2696719591591935', NULL),
('3504376285247770', '1321867915266493', 'RAM', 'male', '$2y$10$.1W879dnBUWxFyaOMwN4nOjzEpCqovvU3ubtLu1sgJeBkEbcx9KLu', 'NAM', '0000-00-00', NULL, NULL, NULL, '0887535772953003'),
('4592485123123285', '3812797212172672', 'SABARISWARAN A', 'male', '$2y$10$/nbYljKpRLjbqQF/e8ygwetGX/MpRVrHsuq624nqbjk7OLcupwRMi', 'DEVKOTTAI', '2004-07-05', NULL, NULL, NULL, NULL),
('9188080556388512', '9321845021466116', 'SONI', 'female', '$2y$10$sz1/aQnD/.ffVbI74v0enuQ.JEPeCuJBffKJhoVT8wVkpw82g4cKm', 'KAR', '0000-00-00', NULL, NULL, NULL, '1161550309325385'),
('9975885224833769', '9117669810813573', 'RAMI', 'female', '$2y$10$yObLc2giwFPq2nwMa/tcqeQJzxn6iNK.mR/h.KS4R0stPObU8h.Ka', 'NAM', '0000-00-00', NULL, NULL, NULL, '0887535772953003');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `id` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
