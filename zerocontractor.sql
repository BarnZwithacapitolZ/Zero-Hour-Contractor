-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 12, 2018 at 10:39 PM
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
  `StartTime` time NOT NULL,
  `FinishTime` time NOT NULL,
  `Date` date NOT NULL,
  PRIMARY KEY (`EmployeeID`,`DepartmentID`),
  KEY `EmployeeID` (`EmployeeID`,`DepartmentID`),
  KEY `DepartmentID` (`DepartmentID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(1, 'office', 1),
(2, 'checkouts', 1),
(3, 'shop floor', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblemployee`
--

DROP TABLE IF EXISTS `tblemployee`;
CREATE TABLE IF NOT EXISTS `tblemployee` (
  `EmployeeID` int(11) NOT NULL AUTO_INCREMENT,
  `OrganizationID` int(11) NOT NULL,
  `EmployeeName` varchar(256) NOT NULL,
  `EmployeeType` varchar(256) NOT NULL,
  `EmployeePayrate` decimal(11,2) NOT NULL,
  `EmployeeEmail` varchar(256) NOT NULL,
  `EmployeePassword` varchar(256) NOT NULL,
  PRIMARY KEY (`EmployeeID`),
  UNIQUE KEY `EmployeeID` (`EmployeeID`),
  KEY `OrganizationID` (`OrganizationID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblemployee`
--

INSERT INTO `tblemployee` (`EmployeeID`, `OrganizationID`, `EmployeeName`, `EmployeeType`, `EmployeePayrate`, `EmployeeEmail`, `EmployeePassword`) VALUES
(1, 1, 'Sam', 'employee', '6.05', 'barnes.samb@gmail.com', 'test'),
(2, 1, 'lesley', 'admin', '7.83', 'test', 'test');

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
  ADD CONSTRAINT `tblbook_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `tblemployee` (`EmployeeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblbook_ibfk_2` FOREIGN KEY (`DepartmentID`) REFERENCES `tbldepartment` (`DepartmentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblemployee`
--
ALTER TABLE `tblemployee`
  ADD CONSTRAINT `tblemployee_ibfk_1` FOREIGN KEY (`OrganizationID`) REFERENCES `tblorganization` (`OrganizationID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
