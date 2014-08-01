-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Host: 50.63.233.41
-- Generation Time: Nov 14, 2012 at 05:59 PM
-- Server version: 5.0.92
-- PHP Version: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ryevents`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `uid` int(11) NOT NULL auto_increment,
  `event_id` varchar(23) NOT NULL,
  `event` varchar(50) NOT NULL,
  `company` varchar(50) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `event_id` (`event_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` VALUES(1, '5099636c0923b1.14985668', 'michiganfall2010', 'General Electric', 0, '2012-11-06 12:22:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `event_activities`
--

CREATE TABLE `event_activities` (
  `uid` int(11) NOT NULL auto_increment,
  `event_id` varchar(23) NOT NULL,
  `resume_file` varchar(50) NOT NULL,
  `recruiter_id` varchar(23) NOT NULL,
  `comments` text NOT NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `event_activities`
--

INSERT INTO `event_activities` VALUES(1, '1', '509947cebc0db9.68702234_1.pdf', '1', 'none', '2012-11-06 14:03:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `recruiters`
--

CREATE TABLE `recruiters` (
  `uid` int(11) NOT NULL auto_increment,
  `unique_id` varchar(23) NOT NULL,
  `name` varchar(50) NOT NULL,
  `company` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `encrypted_password` varchar(80) NOT NULL,
  `salt` varchar(10) NOT NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `unique_id` (`unique_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `recruiters`
--

INSERT INTO `recruiters` VALUES(1, '509952e8a6a2e6.86406507', 'Joe Fish', 'General Electric', 'joe@ge.com', 'zHQ7W2fpEbcbRm/jc9wNu1sN5tsxZjhkYzIyMDA4', '1f8dc22008', '2012-11-06 11:11:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `resumes`
--

CREATE TABLE `resumes` (
  `uid` int(11) NOT NULL auto_increment,
  `user_id` varchar(23) NOT NULL,
  `resume_name` varchar(50) NOT NULL,
  `resume_file` varchar(50) NOT NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `resumes`
--

INSERT INTO `resumes` VALUES(1, '509947cebc0db9.68702234', 'Finance Resume', '509947cebc0db9.68702234_1.pdf', '2012-11-06 10:43:49', '2012-11-06 10:43:49');
INSERT INTO `resumes` VALUES(2, '509947cebc0db9.68702234', 'Engineering Resume', '509947cebc0db9.68702234_2.pdf', '2012-11-06 10:43:55', '2012-11-06 10:43:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL auto_increment,
  `unique_id` varchar(23) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `encrypted_password` varchar(80) NOT NULL,
  `salt` varchar(10) NOT NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `unique_id` (`unique_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` VALUES(1, '509947cebc0db9.68702234', 'Bob Smith', 'bob@nyu.edu', 'GSf84zTOej6aparzEuVcikceEsY2ZjNlZWIyNTUz', '6f3eeb2553', '2012-11-06 10:24:30', NULL);
