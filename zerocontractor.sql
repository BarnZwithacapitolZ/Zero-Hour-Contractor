-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 06, 2019 at 01:05 PM
-- Server version: 5.7.21
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zerocontractor`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblbook`
--

DROP TABLE IF EXISTS `tblbook`;
CREATE TABLE IF NOT EXISTS `tblbook` (
  `EmployeeID` int(11) NOT NULL,
  `DepartmentID` int(11) NOT NULL,
  `BookID` int(11) NOT NULL AUTO_INCREMENT,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  `BookDate` date NOT NULL,
  `Description` text NOT NULL,
  PRIMARY KEY (`BookID`),
  UNIQUE KEY `BookID` (`BookID`),
  KEY `DepartmentID` (`DepartmentID`),
  KEY `EmployeeID` (`EmployeeID`,`DepartmentID`,`BookID`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblbook`
--

INSERT INTO `tblbook` (`EmployeeID`, `DepartmentID`, `BookID`, `StartTime`, `EndTime`, `BookDate`, `Description`) VALUES
(6, 2, 3, '08:41:00', '18:41:00', '2018-09-13', 'This is a thing for a thing by a thing to test the thing. I have said the word thing enough.'),
(7, 2, 4, '08:32:00', '06:35:00', '2018-09-22', 'this is a thing for a thing that is for a thing because it is cool'),
(7, 2, 5, '09:42:00', '14:54:00', '2018-10-02', 'go to the lecture to do da thing innit'),
(6, 2, 6, '06:42:00', '14:54:00', '2018-10-04', 'this is a test to test the thing that is working properly. I don\'t know what to write here so just leave it up to your imagination. '),
(6, 3, 7, '04:41:00', '06:22:00', '2018-10-10', 'Make sure it works with more than one booked hour per day'),
(6, 2, 8, '10:45:00', '19:42:00', '2018-10-10', ''),
(6, 3, 14, '05:33:00', '14:47:00', '2018-10-31', ''),
(6, 2, 15, '11:00:00', '15:00:00', '2019-08-31', 'this is a test to see if the request updates and if it does then kill me'),
(6, 2, 16, '15:34:00', '16:00:00', '2019-08-31', 'this is a test to see if the request updates'),
(47, 2, 18, '09:00:00', '10:00:00', '2018-11-15', 'test'),
(6, 3, 21, '15:00:00', '17:00:00', '2018-11-15', ''),
(48, 8, 22, '11:47:00', '04:13:00', '2018-11-18', ''),
(47, 3, 23, '10:00:00', '16:00:00', '2018-11-16', 'blah'),
(6, 3, 24, '10:00:00', '15:00:00', '2018-11-15', ''),
(48, 3, 26, '15:00:00', '17:00:00', '2018-11-16', ''),
(6, 3, 27, '09:33:00', '16:00:00', '2019-01-17', 'test'),
(6, 8, 28, '09:00:00', '12:45:00', '2019-02-09', 'this is a test'),
(47, 8, 29, '13:30:00', '16:45:00', '2019-02-09', 'another test'),
(6, 3, 30, '13:44:00', '16:41:00', '2019-02-09', ''),
(47, 8, 31, '11:41:00', '08:49:00', '2019-02-14', 'this is a test'),
(47, 11, 32, '09:35:00', '14:42:00', '2019-09-06', '');

-- --------------------------------------------------------

--
-- Table structure for table `tblcompany`
--

DROP TABLE IF EXISTS `tblcompany`;
CREATE TABLE IF NOT EXISTS `tblcompany` (
  `CompanyID` int(11) NOT NULL AUTO_INCREMENT,
  `CompanyName` varchar(256) NOT NULL,
  `CompanyStart` time NOT NULL,
  `CompanyStop` time NOT NULL,
  `CompanyMaxHours` int(11) NOT NULL,
  `CompanyStartDay` int(11) NOT NULL,
  `CompanyEndDay` int(11) NOT NULL,
  `CompanyPayout` varchar(256) NOT NULL,
  PRIMARY KEY (`CompanyID`),
  UNIQUE KEY `CompanyID` (`CompanyID`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcompany`
--

INSERT INTO `tblcompany` (`CompanyID`, `CompanyName`, `CompanyStart`, `CompanyStop`, `CompanyMaxHours`, `CompanyStartDay`, `CompanyEndDay`, `CompanyPayout`) VALUES
(6, 'thing', '09:00:00', '17:00:00', 12, 1, 5, 'daily'),
(7, 'test', '08:00:00', '22:00:00', 12, 5, 7, 'daily'),
(9, 'peanutscompany', '08:00:00', '17:00:00', 8, 1, 5, 'weekly'),
(10, 'shmall', '09:00:00', '17:00:00', 8, 1, 5, 'weekly');

-- --------------------------------------------------------

--
-- Table structure for table `tbldepartment`
--

DROP TABLE IF EXISTS `tbldepartment`;
CREATE TABLE IF NOT EXISTS `tbldepartment` (
  `DepartmentID` int(11) NOT NULL AUTO_INCREMENT,
  `CompanyID` int(11) NOT NULL,
  `DepartmentName` varchar(256) NOT NULL,
  `DepartmentMinEmployees` int(11) NOT NULL,
  PRIMARY KEY (`DepartmentID`),
  UNIQUE KEY `DepartmentID` (`DepartmentID`),
  KEY `CompanyID` (`CompanyID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbldepartment`
--

INSERT INTO `tbldepartment` (`DepartmentID`, `CompanyID`, `DepartmentName`, `DepartmentMinEmployees`) VALUES
(2, 6, 'Shit department', 1),
(3, 6, 'another one', 2),
(8, 6, '2 + 2 is 4 - 1 thats 3 quick mafs', 1),
(9, 7, 'office', 1),
(10, 9, 'office', 6),
(11, 6, 'titstitstits', 69);

-- --------------------------------------------------------

--
-- Table structure for table `tblemployee`
--

DROP TABLE IF EXISTS `tblemployee`;
CREATE TABLE IF NOT EXISTS `tblemployee` (
  `EmployeeID` int(11) NOT NULL AUTO_INCREMENT,
  `CompanyID` int(11) NOT NULL,
  `EmployeeFirst` varchar(256) NOT NULL,
  `EmployeeLast` varchar(256) NOT NULL,
  `EmployeeType` varchar(256) NOT NULL,
  `EmployeePayrate` decimal(11,2) NOT NULL,
  `EmployeeEmail` varchar(256) NOT NULL,
  `EmployeePassword` varchar(256) NOT NULL,
  PRIMARY KEY (`EmployeeID`),
  UNIQUE KEY `EmployeeID` (`EmployeeID`),
  KEY `CompanyID` (`CompanyID`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblemployee`
--

INSERT INTO `tblemployee` (`EmployeeID`, `CompanyID`, `EmployeeFirst`, `EmployeeLast`, `EmployeeType`, `EmployeePayrate`, `EmployeeEmail`, `EmployeePassword`) VALUES
(6, 6, 'thing', 'thing', 'admin', '34.00', 'thing@thing.com', '$2y$10$F1e84tV5MlKI0bDWVT99Rem0ReE7NdrxScjeMRC3j1HbCxX6KMhwS'),
(7, 7, 'test', 'test', 'admin', '23.00', 'test@test.com', '$2y$10$3KBjJ6Or0ioMel47PTf1/O0teQtGfKcNw34ETJH9xhaFzeikVhxlK'),
(45, 7, 'notadmin', 'man', 'employee', '5.00', 'notadmin@cunt.com', '$2y$10$LO/W6IWFC3ipoKsfwT2OmujqrnMw9lmHlYVk7Y3g0m8JV82R8KEO6'),
(46, 7, 'bob', 'man', 'employee', '6.05', 'bob@man.com', '$2y$10$ErhVDZf9wIa39HwsnVWQpeSfGbvm6K9DseCZRxmgHqL.lU.mRBZDe'),
(47, 6, 'shit', 'man', 'employee', '6.50', 'shit@man.com', '$2y$10$iX5YNhyCvPEZUAKx3.MAFOo5fWBWJyuwI12pwFtoOoHllAb9ykQEq'),
(48, 6, 'random', 'man', 'employee', '4.00', 'random@man.com', '$2y$10$qnA25Grp2xsWzGhzO5yQeO5Q.tqgF8jIa3/iwaQYw3ElzLAdXHJeC'),
(49, 6, 'bob', 'mrman', 'employee', '6.05', 'bob@mrman.com', '$2y$10$Xu507tTIRAit5jVvGL3wee8Pg8591xYv4e3J1LWsSxIu2O0sjH2tO'),
(51, 9, 'mrpeanut', 'man', 'admin', '10.25', 'peanut@man.com', '$2y$10$1YfNcYtUxpG7rtRlzaw1A.h/cqZnpbobYykQ2dCsRhC1cErzbjchW'),
(52, 10, 'sam', 'barnes', 'admin', '9.00', 'barnes.samb@gmail.com', '$2y$10$ujXhOHwOzD4kqSlBc33DyujGzqRZ5AgYcYCfjzKsfLGC0u1j5B/Ky');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblbook`
--
ALTER TABLE `tblbook`
  ADD CONSTRAINT `tblbook_ibfk_1` FOREIGN KEY (`DepartmentID`) REFERENCES `tbldepartment` (`DepartmentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblbook_ibfk_2` FOREIGN KEY (`EmployeeID`) REFERENCES `tblemployee` (`EmployeeID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbldepartment`
--
ALTER TABLE `tbldepartment`
  ADD CONSTRAINT `tbldepartment_ibfk_1` FOREIGN KEY (`CompanyID`) REFERENCES `tblcompany` (`CompanyID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblemployee`
--
ALTER TABLE `tblemployee`
  ADD CONSTRAINT `tblemployee_ibfk_1` FOREIGN KEY (`CompanyID`) REFERENCES `tblcompany` (`CompanyID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
