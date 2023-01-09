-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 02, 2018 at 01:17 AM
-- Server version: 5.7.21
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bytejam`
--
CREATE DATABASE IF NOT EXISTS `bytejam` DEFAULT CHARACTER SET latin1 COLLATE latin1_general_cs;
USE `bytejam`;

-- --------------------------------------------------------

--
-- Table structure for table `admin_settings`
--

DROP TABLE IF EXISTS `admin_settings`;
CREATE TABLE `admin_settings` (
  `id` int(11) NOT NULL,
  `voting_start_date` datetime DEFAULT NULL,
  `voting_end_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Truncate table before insert `admin_settings`
--

TRUNCATE TABLE `admin_settings`;
--
-- Dumping data for table `admin_settings`
--

INSERT INTO `admin_settings` (`id`, `voting_start_date`, `voting_end_date`) VALUES
(1, '2018-09-11 01:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `pin` char(6) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Truncate table before insert `company`
--

TRUNCATE TABLE `company`;
-- --------------------------------------------------------

--
-- Table structure for table `division_rep`
--

DROP TABLE IF EXISTS `division_rep`;
CREATE TABLE `division_rep` (
  `id` int(11) NOT NULL,
  `name` varchar(75) COLLATE latin1_general_cs NOT NULL,
  `education_division` int(11) NOT NULL,
  `pin` char(6) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Truncate table before insert `division_rep`
--

TRUNCATE TABLE `division_rep`;
-- --------------------------------------------------------

--
-- Table structure for table `education_division`
--

DROP TABLE IF EXISTS `education_division`;
CREATE TABLE `education_division` (
  `id` int(11) NOT NULL,
  `division` varchar(75) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Truncate table before insert `education_division`
--

TRUNCATE TABLE `education_division`;
--
-- Dumping data for table `education_division`
--

INSERT INTO `education_division` (`id`, `division`) VALUES
(1, 'IHCC'),
(2, 'bits'),
(3, 'bytes');

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `name` varchar(75) COLLATE latin1_general_cs NOT NULL,
  `education_division` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Truncate table before insert `team`
--

TRUNCATE TABLE `team`;
-- --------------------------------------------------------

--
-- Table structure for table `vote_result`
--

DROP TABLE IF EXISTS `vote_result`;
CREATE TABLE `vote_result` (
  `voter_id` varchar(24) COLLATE latin1_general_cs NOT NULL,
  `voter_type` char(20) COLLATE latin1_general_cs NOT NULL,
  `team_id` int(11) NOT NULL,
  `rating` json NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Truncate table before insert `vote_result`
--

TRUNCATE TABLE `vote_result`;
--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_settings`
--
ALTER TABLE `admin_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pin` (`pin`);

--
-- Indexes for table `division_rep`
--
ALTER TABLE `division_rep`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pin` (`pin`),
  ADD KEY `education_division` (`education_division`);

--
-- Indexes for table `education_division`
--
ALTER TABLE `education_division`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`),
  ADD KEY `education_division` (`education_division`);

--
-- Indexes for table `vote_result`
--
ALTER TABLE `vote_result`
  ADD KEY `team_id` (`team_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_settings`
--
ALTER TABLE `admin_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `division_rep`
--
ALTER TABLE `division_rep`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `education_division`
--
ALTER TABLE `education_division`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `division_rep`
--
ALTER TABLE `division_rep`
  ADD CONSTRAINT `division_rep_ibfk_1` FOREIGN KEY (`education_division`) REFERENCES `education_division` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `team_ibfk_1` FOREIGN KEY (`education_division`) REFERENCES `education_division` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vote_result`
--
ALTER TABLE `vote_result`
  ADD CONSTRAINT `vote_result_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
