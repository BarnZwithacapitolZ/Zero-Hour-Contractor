-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 13, 2018 at 04:55 PM
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
  PRIMARY KEY (`BookID`),
  UNIQUE KEY `EmployeeID` (`EmployeeID`,`DepartmentID`,`BookID`),
  KEY `DepartmentID` (`DepartmentID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

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
  UNIQUE KEY `OrganizationID` (`CompanyID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcompany`
--

INSERT INTO `tblcompany` (`CompanyID`, `CompanyName`, `CompanyStart`, `CompanyStop`, `CompanyMaxHours`, `CompanyStartDay`, `CompanyEndDay`, `CompanyPayout`) VALUES
(6, 'thing', '08:00:00', '22:00:00', 12, 1, 4, 'daily'),
(7, 'test', '08:00:00', '22:00:00', 12, 3, 7, 'daily');

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
  UNIQUE KEY `OrganizationID_2` (`CompanyID`),
  KEY `OrganizationID` (`CompanyID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

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
  UNIQUE KEY `OrganizationID_2` (`CompanyID`),
  KEY `OrganizationID` (`CompanyID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblemployee`
--

INSERT INTO `tblemployee` (`EmployeeID`, `CompanyID`, `EmployeeFirst`, `EmployeeLast`, `EmployeeType`, `EmployeePayrate`, `EmployeeEmail`, `EmployeePassword`) VALUES
(6, 6, 'thing', 'thing', 'admin', '34.00', 'thing@thing.com', '$2y$10$F1e84tV5MlKI0bDWVT99Rem0ReE7NdrxScjeMRC3j1HbCxX6KMhwS'),
(7, 7, 'test', 'test', 'admin', '23.00', 'test@test.com', '$2y$10$3KBjJ6Or0ioMel47PTf1/O0teQtGfKcNw34ETJH9xhaFzeikVhxlK');

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
