-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2025 at 05:16 PM
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
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `grade` varchar(3) NOT NULL,
  `year` int(11) NOT NULL,
  `semester` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `student_id`, `subject_id`, `grade`, `year`, `semester`) VALUES
(21, 'MAN/IT/2022/F/28', 1, 'B+', 1, 1),
(22, 'MAN/IT/2022/F/32', 22, 'B+', 2, 1),
(23, 'MAN/IT/2022/F/28 ', 2, 'A+', 1, 1),
(24, 'MAN/IT/2022/F/28', 3, 'A+', 1, 1),
(26, 'MAN/IT/2022/F/28', 4, 'A+', 1, 1),
(27, 'MAN/IT/2022/F/28', 5, 'A', 1, 1),
(28, 'MAN/IT/2022/F/28', 6, 'A-', 1, 1),
(29, 'MAN/IT/2022/F/28', 7, 'A+', 1, 2),
(31, 'MAN/IT/2022/F/28', 8, 'B+', 1, 2),
(32, 'MAN/IT/2022/F/28', 9, 'A+', 1, 2),
(33, 'MAN/IT/2022/F/28', 10, 'B+', 1, 2),
(34, 'MAN/IT/2022/F/28', 11, 'A-', 1, 2),
(35, 'MAN/IT/2022/F/28', 12, 'B+', 1, 2),
(36, 'MAN/IT/2022/F/28', 5, 'B-', 1, 1),
(37, 'MAN/IT/2022/F/28', 22, 'A+', 2, 1),
(38, 'MAN/IT/2022/F/28', 23, 'A', 2, 1),
(39, 'MAN/IT/2022/F/28', 24, 'A+', 2, 1),
(40, 'MAN/IT/2022/F/28', 19, 'A-', 2, 2),
(41, 'MAN/IT/2022/F/28', 21, 'B+', 2, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
