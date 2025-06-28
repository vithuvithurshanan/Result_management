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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
