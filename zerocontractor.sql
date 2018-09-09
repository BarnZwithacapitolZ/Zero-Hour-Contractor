-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 09, 2018 at 10:04 PM
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblbook`
--

INSERT INTO `tblbook` (`EmployeeID`, `DepartmentID`, `BookID`, `StartTime`, `EndTime`, `BookDate`) VALUES
(2, 1, 1, '05:45:00', '06:00:00', '2018-08-13'),
(2, 2, 2, '13:00:00', '18:00:00', '2018-08-14'),
(2, 1, 3, '07:00:00', '16:00:00', '2018-08-15'),
(2, 2, 4, '05:45:00', '12:00:00', '2018-08-17'),
(2, 3, 5, '07:00:00', '14:00:00', '2018-08-16'),
(3, 3, 6, '18:00:00', '22:00:00', '2018-08-14'),
(3, 2, 7, '16:00:00', '22:00:00', '2018-08-16'),
(3, 3, 8, '12:00:00', '18:00:00', '2018-08-19'),
(4, 3, 9, '07:00:00', '12:00:00', '2018-08-14'),
(4, 3, 10, '13:00:00', '18:00:00', '2018-08-16'),
(4, 3, 11, '07:00:00', '12:00:00', '2018-08-17'),
(4, 2, 12, '08:00:00', '12:00:00', '2018-08-18'),
(5, 3, 13, '18:00:00', '22:00:00', '2018-08-17'),
(5, 3, 14, '18:00:00', '22:00:00', '2018-08-18'),
(5, 2, 15, '06:45:00', '12:00:00', '2018-08-19'),
(2, 3, 16, '16:00:00', '22:00:00', '2018-08-13'),
(5, 1, 17, '09:00:00', '17:00:00', '2018-08-19'),
(6, 2, 18, '00:00:00', '23:00:00', '2018-08-14'),
(5, 2, 19, '09:00:00', '16:00:00', '2018-08-19'),
(1, 2, 20, '05:00:00', '23:00:00', '2018-08-22'),
(1, 1, 21, '04:00:00', '11:00:00', '2018-09-02'),
(1, 1, 22, '18:00:00', '23:00:00', '2018-09-02');

-- --------------------------------------------------------

--
-- Table structure for table `tbldepartment`
--

DROP TABLE IF EXISTS `tbldepartment`;
CREATE TABLE IF NOT EXISTS `tbldepartment` (
  `DepartmentID` int(11) NOT NULL AUTO_INCREMENT,
  `DepartmentName` varchar(256) NOT NULL,
  `DepartmentMinEmployees` int(11) NOT NULL,
  PRIMARY KEY (`DepartmentID`),
  UNIQUE KEY `DepartmentID` (`DepartmentID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbldepartment`
--

INSERT INTO `tbldepartment` (`DepartmentID`, `DepartmentName`, `DepartmentMinEmployees`) VALUES
(1, 'Office', 1),
(2, 'Checkouts', 1),
(3, 'Shop floor', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblemployee`
--

DROP TABLE IF EXISTS `tblemployee`;
CREATE TABLE IF NOT EXISTS `tblemployee` (
  `EmployeeID` int(11) NOT NULL AUTO_INCREMENT,
  `OrganizationID` int(11) NOT NULL,
  `EmployeeFirst` varchar(256) NOT NULL,
  `EmployeeLast` varchar(256) NOT NULL,
  `EmployeeType` varchar(256) NOT NULL,
  `EmployeePayrate` decimal(11,2) NOT NULL,
  `EmployeeEmail` varchar(256) NOT NULL,
  `EmployeePassword` varchar(256) NOT NULL,
  PRIMARY KEY (`EmployeeID`),
  UNIQUE KEY `EmployeeID` (`EmployeeID`),
  KEY `OrganizationID` (`OrganizationID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblemployee`
--

INSERT INTO `tblemployee` (`EmployeeID`, `OrganizationID`, `EmployeeFirst`, `EmployeeLast`, `EmployeeType`, `EmployeePayrate`, `EmployeeEmail`, `EmployeePassword`) VALUES
(1, 1, 'Sam', 'Barnes', 'employee', '6.05', 'barnes.samb@gmail.com', 'test'),
(2, 1, 'Lesley', '', 'admin', '7.83', 'test', 'test'),
(3, 1, 'Ashleigh', '', 'employee', '7.83', 'test', 'test'),
(4, 1, 'Michelle', '', 'employee', '7.83', 'test', 'test'),
(5, 1, 'Jennie', '', 'employee', '7.83', 'test', 'test'),
(6, 1, 'Peter', '', 'employee', '7.83', 'test', 'test'),
(7, 1, 'Shani', '', 'employee', '7.83', 'test', 'test'),
(8, 1, 'Laura', '', 'employee', '7.83', 'test', 'test'),
(9, 1, 'Amanda', 'BigAss', 'employee', '7.83', 'test1', 'test'),
(10, 1, 'Tim', '', 'employee', '7.83', 'test', 'test'),
(11, 1, 'Amy', '', 'employee', '7.83', 'test', 'test'),
(12, 1, 'Jo', '', 'employee', '7.83', 'test', 'test'),
(13, 1, 'Ruth', '', 'employee', '7.83', 'test', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `tblorganization`
--

DROP TABLE IF EXISTS `tblorganization`;
CREATE TABLE IF NOT EXISTS `tblorganization` (
  `OrganizationID` int(11) NOT NULL AUTO_INCREMENT,
  `OrganizationName` varchar(256) NOT NULL,
  `OrganizationMaxHours` int(11) NOT NULL,
  `OrganizationCode` int(11) NOT NULL,
  `OrganizationPayout` varchar(256) NOT NULL,
  `OrganizationColor` varchar(256) NOT NULL,
  PRIMARY KEY (`OrganizationID`),
  UNIQUE KEY `OrganizationID` (`OrganizationID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblorganization`
--

INSERT INTO `tblorganization` (`OrganizationID`, `OrganizationName`, `OrganizationMaxHours`, `OrganizationCode`, `OrganizationPayout`, `OrganizationColor`) VALUES
(1, 'Central Convienience', 8, 1458, 'monthly', 'blue');

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
-- Constraints for table `tblemployee`
--
ALTER TABLE `tblemployee`
  ADD CONSTRAINT `tblemployee_ibfk_1` FOREIGN KEY (`OrganizationID`) REFERENCES `tblorganization` (`OrganizationID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
