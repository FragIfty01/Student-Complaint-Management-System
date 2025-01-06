-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2025 at 08:02 PM
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
-- Database: `complaint system`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminmessages`
--

CREATE TABLE `adminmessages` (
  `MessageID` int(11) NOT NULL,
  `ComplaintID` int(11) DEFAULT NULL,
  `AdminID` varchar(255) DEFAULT NULL,
  `StudentID` varchar(255) DEFAULT NULL,
  `MessageText` text DEFAULT NULL,
  `DateSent` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminmessages`
--

INSERT INTO `adminmessages` (`MessageID`, `ComplaintID`, `AdminID`, `StudentID`, `MessageText`, `DateSent`) VALUES
(1, 1, 'admin1', '2000', 'sadqawdasd', '2025-01-04 19:42:56'),
(4, 1, 'admin1', '2000', 'ssd', '2025-01-05 07:58:25'),
(5, 2, 'admin2', '2000', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2025-01-05 08:00:00'),
(6, 4, 'admin2', '2000', 'sssssssssssssssssssssssssssssssssssssssssssssssssssssss', '2025-01-05 08:38:48'),
(7, 5, 'admin1', '2', 'ssssssssssssssssssssssssssssssssssss', '2025-01-05 17:55:48'),
(8, 7, 'admin1', '3', 'aoisjdoia', '2025-01-05 18:27:41');

-- --------------------------------------------------------

--
-- Table structure for table `complaintcategory`
--

CREATE TABLE `complaintcategory` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(255) NOT NULL,
  `AssignedAdminID` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaintcategory`
--

INSERT INTO `complaintcategory` (`CategoryID`, `CategoryName`, `AssignedAdminID`) VALUES
(5, 'Cafeteria complaints', 'admin1'),
(6, 'General mismanagement', 'admin2'),
(7, 'Bully/Harassment', 'admin3'),
(8, 'Faculty', 'admin4');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `ComplaintID` int(11) NOT NULL,
  `UserID` varchar(255) NOT NULL,
  `ComplaintText` text NOT NULL,
  `Status` enum('Pending','Assigned','Reviewed') DEFAULT 'Pending',
  `CategoryID` int(11) DEFAULT NULL,
  `Attachment` varchar(255) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`ComplaintID`, `UserID`, `ComplaintText`, `Status`, `CategoryID`, `Attachment`, `CreatedAt`, `UpdatedAt`) VALUES
(1, '2000', 'swasds', 'Reviewed', 5, 'Screenshot_144.png', '2025-01-04 19:29:27', '2025-01-05 07:58:25'),
(2, '2000', 'asdssswasd', 'Reviewed', 5, 'Screenshot_142.png', '2025-01-04 19:29:34', '2025-01-05 08:00:00'),
(3, '2000', 'ssssssssssssssssssssszzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz', 'Pending', 8, 'Screenshot_141.png', '2025-01-05 08:07:42', '2025-01-05 08:07:42'),
(4, '2000', 'ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss', 'Reviewed', 6, 'Screenshot_139.png', '2025-01-05 08:38:30', '2025-01-05 08:38:48'),
(5, '2', 'aaaaaaaaaaaa', 'Reviewed', 5, 'Screenshot_145.png', '2025-01-05 17:54:32', '2025-01-05 17:55:48'),
(6, '2', 'sssssssssssssssssssssssss', 'Pending', 8, '', '2025-01-05 17:56:44', '2025-01-05 17:56:44'),
(7, '3', 'sssssssssssszzzzzzzzzzzz', 'Reviewed', 5, 'Screenshot_145.png', '2025-01-05 18:26:14', '2025-01-05 18:27:42');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` enum('student','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Password`, `Role`) VALUES
('1000', '1000', 'student'),
('2', '2', 'student'),
('2000', '2000', 'student'),
('20001', '1234', 'student'),
('21', '1234', 'student'),
('3', '3', 'student'),
('admin1', '1234', 'admin'),
('admin2', '1234', 'admin'),
('admin3', '1234', 'admin'),
('admin4', '1234', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminmessages`
--
ALTER TABLE `adminmessages`
  ADD PRIMARY KEY (`MessageID`),
  ADD KEY `ComplaintID` (`ComplaintID`),
  ADD KEY `AdminID` (`AdminID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Indexes for table `complaintcategory`
--
ALTER TABLE `complaintcategory`
  ADD PRIMARY KEY (`CategoryID`),
  ADD KEY `AssignedAdminID` (`AssignedAdminID`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`ComplaintID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminmessages`
--
ALTER TABLE `adminmessages`
  MODIFY `MessageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `complaintcategory`
--
ALTER TABLE `complaintcategory`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `ComplaintID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adminmessages`
--
ALTER TABLE `adminmessages`
  ADD CONSTRAINT `adminmessages_ibfk_1` FOREIGN KEY (`ComplaintID`) REFERENCES `complaints` (`ComplaintID`) ON DELETE CASCADE,
  ADD CONSTRAINT `adminmessages_ibfk_2` FOREIGN KEY (`AdminID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `adminmessages_ibfk_3` FOREIGN KEY (`StudentID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `complaintcategory`
--
ALTER TABLE `complaintcategory`
  ADD CONSTRAINT `complaintcategory_ibfk_1` FOREIGN KEY (`AssignedAdminID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`),
  ADD CONSTRAINT `complaints_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `complaintcategory` (`CategoryID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
