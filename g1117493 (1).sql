-- phpMyAdmin SQL Dump
-- version 4.0.10.20
-- https://www.phpmyadmin.net
--
-- Host: mydb.ics.purdue.edu
-- Generation Time: Apr 18, 2021 at 11:04 PM
-- Server version: 5.5.62-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `g1117493`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE IF NOT EXISTS `assignments` (
  `assignment_id` int(11) NOT NULL AUTO_INCREMENT,
  `classname` varchar(100) NOT NULL,
  `assignmentname` varchar(100) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `startdate` datetime NOT NULL,
  `enddate` datetime NOT NULL,
  `duration` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  PRIMARY KEY (`assignment_id`),
  KEY `classname` (`classname`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`assignment_id`, `classname`, `assignmentname`, `description`, `startdate`, `enddate`, `duration`, `type`) VALUES
(2, 'IE332', 'title', 'Hello', '2021-04-14 18:35:00', '2021-04-16 18:35:00', 1800, 'Homework'),
(3, 'IE332', 'title', 'ML', '2021-04-15 21:03:00', '2021-04-16 21:03:00', 1800, 'Exam'),
(4, 'IE332', 'title', 'hello', '2021-04-14 22:18:00', '2021-04-27 22:18:00', 3600, 'Homework'),
(5, 'IE334', 'title', 'Hello', '2021-04-14 22:23:00', '2021-04-23 22:23:00', 3600, 'Homework'),
(6, 'IE332', 'title', 'hi', '2021-04-15 22:30:00', '2021-05-01 22:30:00', 3600, 'Exam');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE IF NOT EXISTS `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classname` varchar(100) NOT NULL,
  `departname` varchar(50) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `symbol` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `departname` (`departname`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `classname`, `departname`, `description`, `symbol`) VALUES
(5, 'Web Developer', '1', 'Hello', 'IE332'),
(9, 'IE334', '1', 'IE334', 'IE334');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE IF NOT EXISTS `departments` (
  `departname` varchar(50) NOT NULL,
  `collegename` varchar(50) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`departname`, `collegename`, `id`) VALUES
('Industrial Engineering', 'Purdue', 1);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `assignment_id` int(11) DEFAULT NULL,
  `event_start` datetime DEFAULT NULL,
  `event_end` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `student_id`, `assignment_id`, `event_start`, `event_end`) VALUES
(6, 2, 2, '2021-04-14 18:30:00', '2021-04-14 19:00:00'),
(7, 2, 4, '2021-04-14 22:00:00', '2021-04-14 23:00:00'),
(8, 2, 3, '2021-04-15 21:00:00', '2021-04-15 21:30:00'),
(9, 2, 6, '2021-04-15 22:30:00', '2021-04-15 23:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `roster`
--

CREATE TABLE IF NOT EXISTS `roster` (
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  PRIMARY KEY (`student_id`,`class_id`),
  KEY `class_id` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roster`
--

INSERT INTO `roster` (`student_id`, `class_id`) VALUES
(0, 0),
(2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `sleeptime` time DEFAULT '00:00:00',
  `sleepduration` int(100) DEFAULT '1000',
  `weekfreetime` int(100) DEFAULT '1000',
  `department_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `designation`, `sleeptime`, `sleepduration`, `weekfreetime`, `department_id`) VALUES
(2, 'Senior', '00:00:00', 1000, 1000, 1),
(3, 'Senior', '00:00:00', 1000, 1000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `image`) VALUES
(1, 'Zaid Qubain', 'IE@purdue.edu', '25d55ad283aa400af464c76d713c07ad', 'picture13.jpg'),
(2, 'Zaid Qubain', 'zaidqubain@purdue.edu', '25f9e794323b453885f5181f1b624d0b', '9c5207bac6e39db3fe1096119b3aa30a.jpeg'),
(3, 'Lauren Anderson', 'ander867@purdue.edu', '7bed02d7eb481949eae48e4f04d8da0e', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
