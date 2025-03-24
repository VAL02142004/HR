-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2023 at 08:55 AM
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
-- Database: `file_manager_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_file`
--

CREATE TABLE `tbl_file` (
  `tbl_file_id` int(11) NOT NULL,
  `file_title` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `file_uploader` varchar(255) NOT NULL,
  `date_uploaded` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_file`
--

INSERT INTO `tbl_file` (`tbl_file_id`, `file_title`, `file`, `file_uploader`, `date_uploaded`) VALUES
(1, 'JavaScript ', 'What Is JavaScript.docx', 'Lorem Ipsum', '2023-10-16 23:22:31'),
(2, 'HTML', 'HTML stands for.docx', 'Jane Doe', '2023-10-16 23:23:21'),
(3, 'CSS', 'What Is CSS and How Does It Work.docx', 'John Doe', '2023-10-16 23:23:39'),
(4, 'Projects', 'List of Projects.txt', 'Lorem Ipsum', '2023-10-16 23:57:39'),
(5, 'Programming Languages', 'ProgrammingLanguage.docx', 'Jane Doe', '2023-10-16 23:59:17'),
(6, 'Coding Guide', '10 Tips for Writing Clean.docx', 'Lorem Ipsum', '2023-10-17 00:00:08'),
(7, 'List of Webhost', 'Best Web Hosting Service.docx', 'Lorem Ipsum', '2023-10-17 00:00:34'),
(8, 'VS Code Extenstions', 'Top 10 VS Code Extensions That Will Make Your Code Easier.docx', 'Lorem Ipsum', '2023-10-17 00:01:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_file`
--
ALTER TABLE `tbl_file`
  ADD PRIMARY KEY (`tbl_file_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_file`
--
ALTER TABLE `tbl_file`
  MODIFY `tbl_file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
