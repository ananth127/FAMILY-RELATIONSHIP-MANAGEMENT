-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2024 at 06:48 PM
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
-- Table structure for table `family_tree`
--

CREATE TABLE `family_tree` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `family_tree`
--

INSERT INTO `family_tree` (`id`, `name`, `parent_id`) VALUES
(1, 'John & Mary Smith', NULL),
(2, 'Michael Smith', 1),
(3, 'Sarah Johnson', 1),
(4, 'Matthew Smith', 1),
(5, 'Olivia Smith', 2),
(6, 'Ethan Smith', 2),
(7, 'Emily Johnson', 3),
(8, 'Daniel Johnson', 3),
(9, 'Lily Smith', 4),
(10, 'Noah Smith', 4),
(11, 'Sophia Green', 7),
(12, 'Jennifer Brown', 6),
(13, 'Robert Brown', 6),
(14, 'Emma Brown', 11),
(15, 'Jacob', NULL),
(16, 'John & Mary Smith', NULL),
(17, 'Michael Smith', 1),
(18, 'Sarah Johnson', 1),
(19, 'Matthew Smith', 1),
(20, 'Olivia Smith', 2),
(21, 'Ethan Smith', 2),
(22, 'Emily Johnson', 3),
(23, 'Daniel Johnson', 3),
(24, 'Lily Smith', 4),
(25, 'Noah Smith', 4),
(26, 'Sophia Green', 7),
(27, 'Jennifer Brown', 6),
(28, 'Robert Brown', 6),
(29, 'Emma Brown', 11),
(30, 'Jacob', NULL),
(31, 'John & Mary Smith', NULL),
(32, 'Michael Smith', 1),
(33, 'Sarah Johnson', 1),
(34, 'Matthew Smith', 1),
(35, 'Olivia Smith', 2),
(36, 'Ethan Smith', 2),
(37, 'Emily Johnson', 3),
(38, 'Daniel Johnson', 3),
(39, 'Lily Smith', 4),
(40, 'Noah Smith', 4),
(41, 'Sophia Green', 7),
(42, 'Jennifer Brown', 6),
(43, 'Robert Brown', 6),
(44, 'Emma Brown', 11),
(45, 'Jacob', NULL),
(46, 'John & Mary Smith', NULL),
(47, 'Michael Smith', 1),
(48, 'Sarah Johnson', 1),
(49, 'Matthew Smith', 1),
(50, 'Olivia Smith', 2),
(51, 'Ethan Smith', 2),
(52, 'Emily Johnson', 3),
(53, 'Daniel Johnson', 3),
(54, 'Lily Smith', 4),
(55, 'Noah Smith', 4),
(56, 'Sophia Green', 7),
(57, 'Jennifer Brown', 6),
(58, 'Robert Brown', 6),
(59, 'Emma Brown', 11),
(60, 'Jacob', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) NOT NULL,
  `sender_id` bigint(20) NOT NULL,
  `recipient_id` bigint(20) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `recipient_id`, `message`, `created_at`) VALUES
(85, 3799605302006940, 2046893897116990, 'hgmn k', '2024-08-04 16:40:33'),
(86, 3799605302006940, 3328708246130992, 'hgmn k', '2024-08-04 16:40:33'),
(87, 8791673525195699, 664598302268981, 'kj', '2024-08-04 16:41:40'),
(88, 8791673525195699, 895910511901996, 'kj', '2024-08-04 16:41:40'),
(89, 8791673525195699, 4182877735814728, 'kj', '2024-08-04 16:41:40'),
(90, 8791673525195699, 4627158304007355, 'kj', '2024-08-04 16:41:40'),
(91, 8791673525195699, 2046893897116990, 'kj', '2024-08-04 16:41:40'),
(92, 8791673525195699, 3328708246130992, 'kj', '2024-08-04 16:41:40'),
(93, 8791673525195699, 1712507766851459, 'kj', '2024-08-04 16:41:40'),
(94, 8791673525195699, 1771564450280641, 'kj', '2024-08-04 16:41:40'),
(95, 8791673525195699, 179997297239592, 'kj', '2024-08-04 16:41:40'),
(96, 8791673525195699, 1714161713957614, 'kj', '2024-08-04 16:41:40'),
(97, 8791673525195699, 5748098539261875, 'kj', '2024-08-04 16:41:40'),
(98, 8791673525195699, 547014778720514, 'kj', '2024-08-04 16:41:40'),
(99, 8791673525195699, 8658619180814968, 'kj', '2024-08-04 16:41:40'),
(100, 8791673525195699, 813666091941504, 'kj', '2024-08-04 16:41:40'),
(101, 8791673525195699, 9268563954307548, 'kj', '2024-08-04 16:41:40'),
(102, 1527065121928627, 9880852315166081, 'hkg', '2024-08-04 16:43:42'),
(103, 1527065121928627, 1739167897649080, 'hkg', '2024-08-04 16:43:42'),
(104, 1527065121928627, 1128401606227755, 'hkg', '2024-08-04 16:43:42'),
(105, 1527065121928627, 8060721774534445, 'hkg', '2024-08-04 16:43:42'),
(106, 1527065121928627, 5676983227448355, 'hkg', '2024-08-04 16:43:42'),
(107, 1527065121928627, 2909098374613885, 'hkg', '2024-08-04 16:43:42'),
(108, 1527065121928627, 5657344980780944, 'hkg', '2024-08-04 16:43:42'),
(109, 1527065121928627, 1578421200372437, 'hkg', '2024-08-04 16:43:42'),
(110, 1527065121928627, 1677226592014261, 'hkg', '2024-08-04 16:43:42');

-- --------------------------------------------------------

--
-- Table structure for table `relationships`
--

CREATE TABLE `relationships` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `related_user_id` int(11) DEFAULT NULL,
  `relationship` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `relationships`
--

INSERT INTO `relationships` (`id`, `user_id`, `related_user_id`, `relationship`) VALUES
(1, 2, 1, 'Son'),
(2, 3, 1, 'Daughter'),
(3, 4, 1, 'Son'),
(4, 2, 1, 'Son'),
(5, 3, 1, 'Daughter'),
(6, 4, 1, 'Son');

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
('0040094392002057', '4955830035446996', 'SONY', 'male', '$2y$10$AtJc5xKIuYlITfVEqGBwR.3JOReD0Lur5nYrwGUL5hSkSd6jk3c4m', 'KAR', '0000-00-00', NULL, NULL, NULL, '[1161550309325385]'),
('0887535772953003', '2582766030271331', 'RAJA', 'male', '$2y$10$UldrnN9qiozmNvmO.kwwg.aQ75OZMqIoHXdeXUNuQV4M3ZD8mFYuG', 'NAM', '0000-00-00', '3504376285247770', '9975885224833769', '1161550309325385', '[2133529551574202]'),
('1161550309325385', '1978161536171007', 'SAJA', 'female', '$2y$10$YOW//mB3fNnCKVtXHrtNluLLGUxe2m7zT.zqob104g/xDIdlamuAm', 'KAR', '0000-00-00', '0040094392002057', '9188080556388512', '0887535772953003', '[2133529551574202]'),
('2133529551574202', '9145489836333482', 'MAJA', 'male', '$2y$10$R0bW0MiHLzYMD/WGI6HwyujZiuUBET46WZ512A8icHT.0uAavz8u.', 'NAM', '0000-00-00', '0887535772953003', '1161550309325385', '', '[]'),
('2696719591591931', '5611555323363039', 'ANANTH S', 'male', '$2y$10$7l3XfhizuV.Ap9XQKfKxPuANMtf1hxzatBAXO3den.fbcVsi5QguG', 'namakkal', '0113-03-12', '4592485123123285', '2696719591591906', '2696719591591935', '[]'),
('3504376285247770', '1321867915266493', 'RAM', 'male', '$2y$10$.1W879dnBUWxFyaOMwN4nOjzEpCqovvU3ubtLu1sgJeBkEbcx9KLu', 'NAM', '0000-00-00', '', '', '', '[\"2696719591591931\",\"5611555323363039\"]'),
('4592485123123285', '3812797212172672', 'SABARISWARAN A', 'male', '$2y$10$/nbYljKpRLjbqQF/e8ygwetGX/MpRVrHsuq624nqbjk7OLcupwRMi', 'DEVKOTTAI', '2004-07-05', NULL, NULL, NULL, '[]'),
('9188080556388512', '9321845021466116', 'SONI', 'female', '$2y$10$sz1/aQnD/.ffVbI74v0enuQ.JEPeCuJBffKJhoVT8wVkpw82g4cKm', 'KAR', '0000-00-00', NULL, NULL, NULL, '[1161550309325385]'),
('9975885224833769', '9117669810813573', 'RAMI', 'female', '$2y$10$yObLc2giwFPq2nwMa/tcqeQJzxn6iNK.mR/h.KS4R0stPObU8h.Ka', 'NAM', '0000-00-00', NULL, NULL, NULL, '[0887535772953003]');

-- --------------------------------------------------------

--
-- Table structure for table `userss`
--

CREATE TABLE `userss` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `father_id` bigint(20) DEFAULT NULL,
  `mother_id` bigint(20) DEFAULT NULL,
  `marital_id` bigint(20) DEFAULT NULL,
  `CHILDRENS_ID` text DEFAULT NULL,
  `mobile_number` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userss`
--

INSERT INTO `userss` (`id`, `name`, `gender`, `password`, `address`, `dob`, `father_id`, `mother_id`, `marital_id`, `CHILDRENS_ID`, `mobile_number`) VALUES
(179997297239592, 'SOMNI', 'female', '$2y$10$DhRadfNzX7SR3yHReAHM7eLLLDGrvr8xgzlr9Qk2/qIco8STTSa3e', 'namakkal', '1999-05-18', 4747014269409917, 1771564450280641, 8176963590774985, '[\"547014778720514\",\"8658619180814968\"]', NULL),
(359737983746512, 'SARAN', 'male', '$2y$10$.mp0P9IWWTYuXVp9nclq..hcyAzo23q50JwF8ETZ/R9cZ.ilhOj3S', 'NAMAKKAL', '2003-02-09', NULL, NULL, 5657344980780944, '[\"1677226592014261\",\"1578421200372437\"]', NULL),
(547014778720514, 'ARAVIN', 'male', '$2y$10$..jQRMkBNpEOShz1vCAgTedo8zEXAnnJnXQK6XBkUJlWnvVdyvaOm', 'NAMAKKAL', '1992-02-09', NULL, 179997297239592, 6276859416939610, '[\"9268563954307548\",\"813666091941504\"]', NULL),
(664598302268981, 'RAVI', 'male', '$2y$10$5CqxLo9XkllGbKyx9KtJqenDHzsBJCNxRw7RRi3D3oVIzUBPS6aB.', 'namakkal', '1999-06-23', 4182877735814728, 4627158304007355, 895910511901996, '[\"8791673525195699\",7954178433602547]', NULL),
(787585895146318, 'SURESH KUMAR S', 'male', '$2y$10$Hk3/sKeTSrwGxdfr4Yxnr.Fd1X.pU.1rSO.Rn2vh6.0C0v.yuFrL2', 'KARUR', '1971-08-11', 8171110628877776, NULL, NULL, '[2657450064511091,0]', '6380540058'),
(813666091941504, 'MAYA', 'female', '$2y$10$5gF67kLeg3BTrtDTYOBTJuZ3vZf4hVU1F8/YWnwSPMM6xWnFV.6FG', 'NAMAKKAL', '2001-03-09', 547014778720514, NULL, NULL, NULL, NULL),
(895910511901996, 'TAMANA', 'female', '$2y$10$vTiDpLCLpzRSnOTQezono.Gkat8Std8eaR04jsK0cRLzNlLAF7DXm', 'Namakkal', '1999-03-29', 2046893897116990, 3328708246130992, 664598302268981, '[\"8791673525195699\",\"7954178433602547\"]', NULL),
(940397701755220, 'waste', 'male', '$2y$10$I9EjWZhRIQE3b1cYRDX7Z.vu1GcnJbz7.sXlcltuV0yLNGa0l30OS', 'waste', '2452-03-01', NULL, NULL, NULL, NULL, NULL),
(1128401606227755, 'NANDHA', 'male', '$2y$10$6.AuyLkwIfFjAemo5F6R1uItrJk2KGLF8hjg0x5uklS3KCl6Ub7yu', 'NAMAKKAL', '2001-06-01', 7954178433602547, 1527065121928627, NULL, NULL, NULL),
(1527065121928627, 'KEELA', 'female', '$2y$10$lCDBQVVHhsL7pg6.lnIAr.Z6/HXT7grnOQBZjrCeerxFc65dm7wwe', 'NAMAKKAL', '2991-01-12', 9880852315166081, 1739167897649080, 7954178433602547, '[\"1128401606227755\",\"8060721774534445\"]', NULL),
(1578421200372437, 'PAWAN', 'male', '$2y$10$DX6IEuQ18DlVPG2J.bX4q.FUx29dFUyS0a2B1qFxiQ04Y0Skqddru', 'NAMAKKAL', '2004-04-07', NULL, 5657344980780944, NULL, NULL, NULL),
(1677226592014261, 'PAVI', 'female', '$2y$10$ym7/SScpxlFM6/8I547aq.o5JO5sTYGkXRxgovcP.HnZcL7J.5h66', 'NAMAKKAL', '2002-03-09', NULL, 5657344980780944, NULL, NULL, NULL),
(1712507766851459, 'RAMIYA', 'female', '$2y$10$zbzBG9fXRxXQEmUZvOQto.wYTn.PrP.E8HBdN2Q1qglhVt24kQ6Aq', 'Namakkal', '1999-04-18', 8791673525195699, 7466897594435183, NULL, NULL, NULL),
(1714161713957614, 'SABARISWARAN A', 'male', '$2y$10$0ctOA9osXpc1RhGXOmDoKugth5LFQX4vMNY1/UerjyPp8p7EXujX6', 'KARUR', '0042-03-12', NULL, 1771564450280641, NULL, '[0]', '12345678'),
(1739167897649080, 'SUNDARI', 'female', '$2y$10$bxKTpyPOW2PplPq0CyLIpeMh6XRcK7weEHQ1mYMoO3ui3usCKnvS2', 'NAMAKKAL', '1999-07-09', NULL, NULL, 9880852315166081, '[\"1527065121928627\",\"5358949286395591\"]', NULL),
(1771564450280641, 'RAMYA', 'female', '$2y$10$tMYUue7HO64T9CZcXFP6tey.cvHd8cmM9L37xWIzNGGzep8bZ3Haq', 'namakkal', '1999-04-26', 8791673525195699, 7466897594435183, 4747014269409917, '[\"179997297239592\",\"5748098539261875\"]', NULL),
(2046893897116990, 'SAM', 'male', '$2y$10$uLmy8MgFFGKyp3/lsFONkeAI9eY/F3GYVywXAJ348y.5qwIHHcBMe', 'Namakkal', '1999-05-18', NULL, NULL, 3328708246130992, '[\"895910511901996\",\"3799605302006940\"]', NULL),
(2657450064511091, 'SANZAY S K', 'male', '$2y$10$yT6j8kwuWKstWqrCyEFAuOoAvOCjrRoBwRffQExviAksf3afcvwvi', 'KARUR', '2003-09-20', 787585895146318, NULL, NULL, '[]', '6380540058'),
(2909098374613885, 'KAVYA', 'female', '$2y$10$YjQ3Om3OvaGp/KQJG0YN9O5.IvmsmKhtubPwJucdRFr44IMRoq7uy', 'NAMAKKAL', '2001-09-16', 5676983227448355, 8973649723680530, NULL, NULL, NULL),
(3328708246130992, 'SAMYA', 'female', '$2y$10$KAupSVYqH34wLlf15JifKOu8qWMKgtSBRA0ZNTcHlNa0rBtgr9zH.', 'Namakkal', '2004-02-17', NULL, NULL, 2046893897116990, '[\"895910511901996\",\"3799605302006940\"]', NULL),
(3799605302006940, 'DAS', 'male', '$2y$10$NNNDea3a3qKaXo0.CUPuke/nm.KDrgWBeMIQHl8iBNh2P5A9BTVUm', 'Namakkal', '1999-03-13', 2046893897116990, 3328708246130992, NULL, NULL, NULL),
(3951234330658705, 'KAVI', 'female', '$2y$10$TrNnvhK.iNcH1FMft3yFSORKJoTjtLPvyE095usx4FgXXVHEMW9WC', 'NAMAKKAL', '1999-09-05', NULL, NULL, 8060721774534445, '[\"5676983227448355\"]', NULL),
(4182877735814728, 'RAM', 'male', '$2y$10$6qCxR0U3bOhV30C33BDi5u67KK5J0qFZj/AAubuZ0fDnwyH/ez2mq', 'Namakkal', '1999-04-14', 0, 0, 4627158304007355, '[\"7694655204757716\",\"664598302268981\"]', NULL),
(4627158304007355, 'REVATHI', 'female', '$2y$10$QJ58Qu./CfNco3Of.pCA0uR6xzZyaPVdnfoEZ0In5OF3C4tM8SRb.', 'Namakkal', '1999-02-18', NULL, NULL, 4182877735814728, '[\"7694655204757716\",\"664598302268981\"]', NULL),
(4747014269409917, 'SOMU', 'male', '$2y$10$jmiGU0P9LPqgJyfWn2b1gO09MR/p.25DBlLDPQTlljSYI5pe/PmCO', 'Namakkal', '1999-07-31', NULL, NULL, 1771564450280641, '[\"179997297239592\",\"5748098539261875\"]', NULL),
(5358949286395591, 'VELAN', 'male', '$2y$10$1fWRZVG.y.tu7km0QIDIV.t1BVgiU.MtaQh23bcrrxC819qF/JNI2', 'NAMAKKAL', '1997-12-28', NULL, NULL, NULL, NULL, NULL),
(5657344980780944, 'SARANYA', 'female', '$2y$10$inPLtZKwnDmusjqIfPuPLehGa4DsJKxePo91DNADApfRRPOOXz36W', 'NAMAKKAL', '2002-05-07', 5676983227448355, 8973649723680530, 359737983746512, '[\"1677226592014261\",\"1578421200372437\"]', NULL),
(5676983227448355, 'NANDHAN', 'male', '$2y$10$VhGeG/G.BFydcQ/0vL8qV.ihBoljqggLWRR58prr7Kte/uWHWSGKK', 'NAMAKKAL', '2001-04-09', 8060721774534445, 3951234330658705, 8973649723680530, '[\"2909098374613885\",\"5657344980780944\"]', NULL),
(5731747362086809, 'JAYAN', 'male', '$2y$10$zbIGEwuNhQ.AR.V9B4AR/OVyxXGL0i4qt0W.a38QxsDFASAISAzjC', 'NAMAKKAL', '1985-02-09', 5775454370814894, 5739379494428062, NULL, NULL, NULL),
(5739379494428062, 'SMYA', 'female', '$2y$10$lE7x4nVuQ/n7adOU08VDvOeenAmQmJLWkoOkitCUf6E7q/eQu6Kri', 'NAMAKKAL', '2001-02-09', NULL, NULL, 5775454370814894, '[\"5731747362086809\",\"8176963590774985\"]', NULL),
(5748098539261875, 'RAJ', 'male', '$2y$10$/VGQvrQMAw1SQBD3uQ8xMeecEg8ba0VVFTlRukJRpWJl6xZavRxRy', 'Namakkal', '1999-06-16', 4747014269409917, 1771564450280641, NULL, NULL, NULL),
(5775454370814894, 'RAMA', 'male', '$2y$10$bWZkrN6jCfL4P6ETDsI5XuCSMKnH6zq4ULYsuBa65HF49r7/LY7XS', 'NAMAKKAL', '1987-02-09', NULL, NULL, 5739379494428062, NULL, NULL),
(6276859416939610, 'AMYA', 'female', '$2y$10$2u/Homzu949FSsI9nAgodun61/lmYqET/KTQX/0zbqOK/yXL.EXCS', 'NAMAKKAL', '1996-11-08', NULL, NULL, 547014778720514, '[\"9268563954307548\",\"813666091941504\"]', NULL),
(7466897594435183, 'NAMYA', 'female', '$2y$10$VcIsfNDHq5yzkvpyjNbx2.iFPROdaG0rqVEaPPh9.t9diQdCJIdnu', 'Namakkal', '1999-05-18', 7694655204757716, 9111626714516844, 8791673525195699, '[\"1771564450280641\",\"1712507766851459\"]', NULL),
(7694655204757716, 'SAJ', 'male', '$2y$10$qnzrDdu9dBJNg/9Gf.eb6.qq.Ig1Rt7yh.sTqhVlbIRm6BjTqcaHO', 'Namakkal', '1999-02-02', 4182877735814728, 4627158304007355, 9111626714516844, '[\"7466897594435183\"]', NULL),
(7954178433602547, 'REVANTH', 'male', '$2y$10$5ll1JbS0zYwRsW6DXf9n9ulhEcDMqaCKYmYAyhdEFpVgUOzkj5hda', 'Namakkal', '1999-05-14', 664598302268981, 895910511901996, 1527065121928627, '[\"1128401606227755\",\"8060721774534445\"]', NULL),
(8060721774534445, 'BHARATH', 'male', '$2y$10$mwtqk0ymis1TqDs8gIQVN..iWz7mGYs3CPHDzWtu5yegQxllCLC2a', 'NAMAKKAL', '2001-05-08', 7954178433602547, 1527065121928627, 3951234330658705, '[\"5676983227448355\"]', NULL),
(8171110628877776, 'SHANMUGAM', 'male', '$2y$10$LPIZATlNs2zAoo210V4nZ.Jcaj2WvhEqiJdLmZact/auQK.riMilq', 'KARUR', '1930-04-05', NULL, NULL, NULL, '[787585895146318]', '6380540058'),
(8176963590774985, 'AVIN', 'male', '$2y$10$Xe8TWy1UhvEaASKni0/kLuRV0bfKPIBDfyxJN9sbdHInRbSqu4bey', 'NAMAKKAL', '2001-04-06', 5775454370814894, 5739379494428062, 179997297239592, '[\"547014778720514\",\"8658619180814968\"]', NULL),
(8658619180814968, 'ARAVINTH', 'male', '$2y$10$i3zZLQJB0ueWzRprkfTVd.vkTGjih6Sw8k/852coP8iU/H/mvejEi', 'NAMAKKAL', '1992-12-29', NULL, 179997297239592, NULL, NULL, NULL),
(8791673525195699, 'REVI', 'male', '$2y$10$WzjTCWr26lc30nFU7UgLduJzdCeAgww5ngmyyMf8e5c.N65OavZNm', 'Namakkal', '1999-01-28', 664598302268981, 895910511901996, 746689759443518, '[\"1771564450280641\",\"1712507766851459\"]', NULL),
(8973649723680530, 'KAVITHA', 'female', '$2y$10$/37wFNvncHopIPZslZy62u26jo0/q7WDlw4zhdyWx/WA35s./G3FC', 'NAMAKKAL', '2001-09-08', NULL, NULL, 5676983227448355, '[\"2909098374613885\",\"5657344980780944\"]', NULL),
(9111626714516844, 'AAMYA', 'female', '$2y$10$6wnA4de2B0hKQ9rSGbH4ler.ZNzipzir99vEVvUrlGCoBldC7Wg3W', 'Namakkal', '1999-07-11', NULL, NULL, 7694655204757716, '[\"7466897594435183\"]', NULL),
(9268563954307548, 'MIYA', 'female', '$2y$10$vBjw6YDj8Epvz98lbnMQmO9de1HE5BecA3vBeOz7AVLCMyi4NmN9i', 'NAMAKKAL', '1982-03-09', 547014778720514, NULL, NULL, NULL, NULL),
(9880852315166081, 'SUNDAR', 'male', '$2y$10$aRFEpunNeU54EpPGP06ULuREMUkftPLDVCKDLF9DXul.YY6OvMnnS', 'NAMAKKAL', '1002-07-12', NULL, NULL, 1739167897649080, '[\"1527065121928627\",\"5358949286395591\"]', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `family_tree`
--
ALTER TABLE `family_tree`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `recipient_id` (`recipient_id`);

--
-- Indexes for table `relationships`
--
ALTER TABLE `relationships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `userss`
--
ALTER TABLE `userss`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `family_tree`
--
ALTER TABLE `family_tree`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `relationships`
--
ALTER TABLE `relationships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `userss` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `userss` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
