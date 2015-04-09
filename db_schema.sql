-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2015 at 04:38 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dc_db`
--
CREATE DATABASE IF NOT EXISTS `dc_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `dc_db`;

-- --------------------------------------------------------

--
-- Table structure for table `dc_comments`
--

DROP TABLE IF EXISTS `dc_comments`;
CREATE TABLE IF NOT EXISTS `dc_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Comment ID',
  `transaction_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Transaction ID',
  `name` varchar(128) COLLATE utf8_unicode_ci DEFAULT '' COMMENT 'Donor''s Name',
  `alias` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Donor''s Alias',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Donor''s Email',
  `message` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Donor''s Comment',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of Comment',
  `incentive` int(10) DEFAULT NULL COMMENT 'Incentive attached',
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_id` (`transaction_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5208 ;

-- --------------------------------------------------------

--
-- Table structure for table `dc_donations`
--

DROP TABLE IF EXISTS `dc_donations`;
CREATE TABLE IF NOT EXISTS `dc_donations` (
  `transaction_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `donor_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `donation_amount` double NOT NULL DEFAULT '0',
  `original_request` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `event_id` varchar(13) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `incentive_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`transaction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dc_events`
--

DROP TABLE IF EXISTS `dc_events`;
CREATE TABLE IF NOT EXISTS `dc_events` (
  `event_id` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `charity_name` text COLLATE utf8_unicode_ci NOT NULL,
  `image_url` text COLLATE utf8_unicode_ci NOT NULL,
  `paypal_email` text COLLATE utf8_unicode_ci NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `event_url` text COLLATE utf8_unicode_ci NOT NULL,
  `targetAmount` int(11) NOT NULL,
  `startDate` bigint(11) NOT NULL,
  `endDate` bigint(11) NOT NULL,
  `current` int(1) unsigned zerofill NOT NULL,
  `dt` bigint(20) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dc_events`
--

INSERT INTO `dc_events` (`event_id`, `user_id`, `title`, `charity_name`, `image_url`, `paypal_email`, `url`, `event_url`, `targetAmount`, `startDate`, `endDate`, `current`, `dt`) VALUES
('5525d878d9bfc', 8, 'test', 'Robbie Davis', '', 'robbie@therobbiedavis.com', 'http://therobbiedavis.com', 'http://therobbiedavis.com', 100, 1428543600, 1430358008, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `dc_events_test`
--

DROP TABLE IF EXISTS `dc_events_test`;
CREATE TABLE IF NOT EXISTS `dc_events_test` (
  `event_id` varchar(13) NOT NULL,
  `title` text NOT NULL,
  `targetAmount` int(11) NOT NULL,
  `startDate` bigint(11) NOT NULL,
  `endDate` bigint(11) NOT NULL,
  `current` int(1) unsigned zerofill NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dc_incentives`
--

DROP TABLE IF EXISTS `dc_incentives`;
CREATE TABLE IF NOT EXISTS `dc_incentives` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Incentive ID',
  `event_id` varchar(13) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Event ID',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Incentive Name',
  `incentive` double NOT NULL DEFAULT '5' COMMENT 'Incentive Amount',
  `hidden` int(1) NOT NULL DEFAULT '0' COMMENT 'Is this hidden?',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Time of Incentive',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

DROP TABLE IF EXISTS `stats`;
CREATE TABLE IF NOT EXISTS `stats` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hour` int(3) DEFAULT NULL,
  `don_total` float DEFAULT NULL,
  `don_diff` float unsigned DEFAULT NULL,
  `event_id` varchar(13) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1172 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(15) NOT NULL DEFAULT '',
  `password` varchar(45) NOT NULL DEFAULT '',
  `sessionid` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `sessionid`) VALUES
(1, 'admin', '63bddf0cbc21d36c8c19808e22784df2', 'nqd2ciepgvtlg8og1nma51hh35');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
