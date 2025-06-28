-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2025 at 05:17 PM
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
-- Database: `ati_rms`
--

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_code` varchar(20) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `year` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `course_group` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_code`, `subject_name`, `year`, `semester`, `course_group`) VALUES
(1, 'HNDIT1012', 'Visual Application Programming (VAP)', 1, 1, 'IT'),
(2, 'HNDIT1022', 'Web Design (WD)', 1, 1, 'IT'),
(3, 'HNDIT1032', 'Computer and Network Systems (CNS)', 1, 1, 'IT'),
(4, 'HNDIT1042', 'Information Management and Information Systems (IMIS)', 1, 1, 'IT'),
(5, 'HNDIT1052', 'ICT Project (Individual) (ICTP)', 1, 1, 'IT'),
(6, 'HNDIT1062', 'Communication Skills (CS)', 1, 1, 'IT'),
(7, 'HNDIT2012', 'Fundamentals of Programming (FP)', 1, 2, 'IT'),
(8, 'HNDIT2022', 'Software Development (SD)', 1, 2, 'IT'),
(9, 'HNDIT2032', 'System Analysis and Design (SAD)', 1, 2, 'IT'),
(10, 'HNDIT2042', 'Data Communication and Computer Networks (DCCN)', 1, 2, 'IT'),
(11, 'HNDIT2052', 'Principles of User Interface Design (UID)', 1, 2, 'IT'),
(12, 'HNDIT2062', 'ICT Project (Group) (ICTP)', 1, 2, 'IT'),
(13, 'HNDIT2072', 'Technical Writing (TW)', 1, 2, 'IT'),
(14, 'HNDIT2082', 'Human Value & Professional Ethics (HV)', 1, 2, 'IT'),
(15, 'HNDIT4012', 'Software Engineering (SE)', 2, 2, 'IT'),
(16, 'HNDIT4022', 'Software Quality Assurance (SQA)', 2, 2, 'IT'),
(17, 'HNDIT4032', 'IT Project Management (ITPM)', 2, 2, 'IT'),
(18, 'HNDIT4042', 'Professional World (PW)', 2, 2, 'IT'),
(19, 'HNDIT4052', 'Programming Individual Project (PIP)', 2, 2, 'IT'),
(20, 'HNDIT4222', 'Business Analysis Practice (BAP)', 2, 2, 'IT'),
(21, 'HNDIT4252', 'Mobile Application Development (MAD)', 2, 2, 'IT'),
(22, 'HNDIT3012', 'Object Oriented Programming (OOP)', 2, 1, 'IT'),
(23, 'HNDIT3022', 'Web Programming (WP)', 2, 1, 'IT'),
(24, 'HNDIT3032', 'Data Structures and Algorithms (DSA)', 2, 1, 'IT'),
(25, 'HNDIT3042', 'Database Management Systems (DBMS)', 2, 1, 'IT'),
(26, 'HNDIT3052', 'Operating Systems (OS)', 2, 1, 'IT'),
(27, 'HNDIT3062', 'Information and Computer Security (ICS)', 2, 1, 'IT'),
(28, 'HNDIT3072', 'Statistics for IT (SIT)', 2, 1, 'IT');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
