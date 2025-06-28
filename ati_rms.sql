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

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` varchar(50) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `year` varchar(10) DEFAULT NULL,
  `batch` varchar(20) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `name`, `email`, `password`, `department`, `year`, `batch`, `phone`) VALUES
('MAN/IT/2022/F/28', 'T.Abisha', 'abi@gmail.com', '$2y$10$noUgaoXPWs72yU9VE5shcOWlRJnr68v2FmPTK/eGdLS5tyeYKH8sy', 'IT', NULL, '2022', '0768387402'),
('MAN/IT/2022/F/30', 'Pavithana', 'pavi@gmail.com', '$2y$10$V5/8kMjE.JghAJGbynM6Zedfv284wmHipgsgJdZ6Wt1DCNLIEsec6', 'IT', NULL, '2021', '0768387402'),
('MAN/IT/2022/F/32', 'suvetha', 'suve@gmail.com', '$2y$10$g57hvxxUpPeEU7zh9EABCuEAxob5Yrzv.yWWY13Zn2kMWj1fH3pz6', 'IT', NULL, '2023', '0768387402'),
('MAN/IT/2022/F/52', 'X.Pratheepa', 'pratheepa@gamil.com', '$2y$10$Mnk8kjceH/UhgSoSzGD6P.SpqfPWzJkY49VFTuH3cpKdZO2bLy8MG', 'IT', NULL, NULL, '0772593707'),
('man/it/48', 'mathu', 'mathu@gmail.com', '$2y$10$c8a8gXiJhpvQRSRMV.7BM.wvcjxDmjEUmtZoGpO0spmnVsPcD1Be6', 'IT', NULL, NULL, '0768387402'),
('S001', 'John Doe', 'johndoe@example.com', '$2y$10$zCYm/NB6H/3W5e5IaZDhS.y73fgiuaK1pDcbhvWw6SNdQNgd5NGSG', 'Computer Science', NULL, NULL, '1234567890'),
('S002', 'Jane Smith', 'janesmith@example.com', '$2y$10$mJ4NmhBfxN9uto4pc2p9kOJyYDC0JfWjdpfhio4Q43m66F/Idyhmi', 'Information Technology', NULL, NULL, '0987654321'),
('S003', 'Robert Brown', 'robertbrown@example.com', '$2y$10$4sUmpVEx2sbvtmLrfk/P6u3klfv1WbdconJ2ZMNirifKbP8NIsKxK', 'Software Engineering', NULL, NULL, '1122334455'),
('S004', 'Emily Davis', 'emilydavis@example.com', '$2y$10$QQKKgizKbKD4lj5PRsh1AOz8Dbw7djWDELI.E6BdQCaBrkIzAGkda', 'Data Science', NULL, NULL, '5566778899');

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
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

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
