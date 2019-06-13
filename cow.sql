-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2019 at 02:47 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cow`
--

-- --------------------------------------------------------

--
-- Table structure for table `dailyplan`
--

CREATE TABLE IF NOT EXISTS `dailyplan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(30) NOT NULL,
  `breakfast` varchar(60) NOT NULL,
  `lunch` varchar(60) NOT NULL,
  `dinner` varchar(60) NOT NULL,
  `snacks` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `dailyplan`
--

INSERT INTO `dailyplan` (`id`, `uid`, `breakfast`, `lunch`, `dinner`, `snacks`) VALUES
(1, 3, '1-100;4-100;2-100;5-100;7-100;2-100', '4-100;5-50', '4-100;5-60;6-100', '6-150;7-100'),
(2, 5, '1-100;6-100', '5-100', '3-100;3-100;5-100', '4-100'),
(3, 7, '', '', '', ''),
(4, 8, '4-100;2-100;6-100;8-100', '', '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
