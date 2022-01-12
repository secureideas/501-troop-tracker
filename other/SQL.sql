-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: ls-b461765348ab37f7774d47c6788e7713192d710a.c7bgdu4hw8kg.us-east-1.rds.amazonaws.com:3306
-- Generation Time: Jan 12, 2022 at 11:00 PM
-- Server version: 8.0.23
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trooptracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `501st_costumes`
--

CREATE TABLE `501st_costumes` (
  `legionid` int NOT NULL,
  `costumeid` int NOT NULL,
  `prefix` varchar(2) NOT NULL,
  `costumename` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `bucketoff` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `501st_troopers`
--

CREATE TABLE `501st_troopers` (
  `legionid` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `squad` int NOT NULL,
  `approved` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0',
  `standing` int NOT NULL DEFAULT '0',
  `joindate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `awards`
--

CREATE TABLE `awards` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `icon` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `award_troopers`
--

CREATE TABLE `award_troopers` (
  `id` int NOT NULL,
  `trooperid` int NOT NULL,
  `awardid` int NOT NULL,
  `awarded` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int UNSIGNED NOT NULL,
  `troopid` int NOT NULL,
  `trooperid` int NOT NULL,
  `comment` text NOT NULL,
  `important` int NOT NULL,
  `posted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `costumes`
--

CREATE TABLE `costumes` (
  `id` int UNSIGNED NOT NULL,
  `costume` varchar(50) NOT NULL,
  `era` int NOT NULL,
  `club` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `trooperid` int NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `txn_id` varchar(255) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `droid_troopers`
--

CREATE TABLE `droid_troopers` (
  `forum_id` varchar(255) NOT NULL,
  `droidname` varchar(255) NOT NULL,
  `imageurl` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int UNSIGNED NOT NULL,
  `oldid` int NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `dateStart` datetime DEFAULT NULL,
  `dateEnd` datetime DEFAULT NULL,
  `website` varchar(500) DEFAULT NULL,
  `numberOfAttend` int DEFAULT NULL,
  `requestedNumber` int DEFAULT NULL,
  `requestedCharacter` text,
  `secureChanging` tinyint(1) DEFAULT NULL,
  `blasters` tinyint(1) DEFAULT NULL,
  `lightsabers` tinyint(1) DEFAULT NULL,
  `parking` tinyint(1) DEFAULT NULL,
  `mobility` tinyint(1) DEFAULT NULL,
  `amenities` text,
  `referred` text,
  `comments` text,
  `location` varchar(500) DEFAULT NULL,
  `label` varchar(100) DEFAULT NULL,
  `postComment` text,
  `notes` text,
  `limitedEvent` tinyint(1) DEFAULT NULL,
  `limitTo` int DEFAULT NULL,
  `limitRebels` int NOT NULL DEFAULT '500',
  `limit501st` int NOT NULL DEFAULT '500',
  `limitMando` int NOT NULL DEFAULT '500',
  `limitDroid` int NOT NULL DEFAULT '500',
  `limitOther` int NOT NULL DEFAULT '500',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `moneyRaised` int NOT NULL DEFAULT '0',
  `squad` int NOT NULL,
  `link` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `event_notifications`
--

CREATE TABLE `event_notifications` (
  `troopid` int NOT NULL,
  `trooperid` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_sign_up`
--

CREATE TABLE `event_sign_up` (
  `id` int UNSIGNED NOT NULL,
  `trooperid` int DEFAULT NULL,
  `troopid` int NOT NULL,
  `costume` varchar(50) DEFAULT NULL,
  `costume_backup` varchar(50) NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0',
  `addedby` int NOT NULL DEFAULT '0',
  `signuptime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mando_costumes`
--

CREATE TABLE `mando_costumes` (
  `mandoid` int NOT NULL,
  `costumeurl` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mando_troopers`
--

CREATE TABLE `mando_troopers` (
  `mandoid` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `costume` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `message` varchar(100) NOT NULL,
  `trooperid` int NOT NULL,
  `type` int NOT NULL DEFAULT '0',
  `json` text NOT NULL,
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notification_check`
--

CREATE TABLE `notification_check` (
  `troopid` int NOT NULL DEFAULT '0',
  `trooperid` int NOT NULL DEFAULT '0',
  `commentid` int NOT NULL DEFAULT '0',
  `trooperstatus` int NOT NULL DEFAULT '0',
  `troopstatus` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rebel_costumes`
--

CREATE TABLE `rebel_costumes` (
  `rebelid` int NOT NULL,
  `costumename` varchar(255) NOT NULL,
  `costumeimage` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rebel_troopers`
--

CREATE TABLE `rebel_troopers` (
  `rebelid` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `rebelforum` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `lastidtrooper` int NOT NULL DEFAULT '0',
  `lastidevent` int NOT NULL DEFAULT '0',
  `lastidlink` int NOT NULL DEFAULT '0',
  `siteclosed` int NOT NULL DEFAULT '0',
  `signupclosed` int NOT NULL DEFAULT '0',
  `lastnotification` int NOT NULL DEFAULT '0',
  `supportgoal` int NOT NULL DEFAULT '0',
  `notifyevent` int NOT NULL DEFAULT '0',
  `lastimportantcomment` int NOT NULL DEFAULT '0',
  `syncdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sg_troopers`
--

CREATE TABLE `sg_troopers` (
  `sgid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `costumename` varchar(100) NOT NULL,
  `ranktitle` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `titles`
--

CREATE TABLE `titles` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `title_troopers`
--

CREATE TABLE `title_troopers` (
  `id` int NOT NULL,
  `trooperid` int NOT NULL,
  `titleid` int NOT NULL,
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `troopers`
--

CREATE TABLE `troopers` (
  `id` int UNSIGNED NOT NULL,
  `oldid` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(240) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `squad` int NOT NULL,
  `permissions` int NOT NULL DEFAULT '0',
  `p501` int NOT NULL DEFAULT '0',
  `pRebel` int NOT NULL DEFAULT '0',
  `pDroid` int NOT NULL DEFAULT '0',
  `pMando` int NOT NULL DEFAULT '0',
  `pOther` int NOT NULL DEFAULT '0',
  `tkid` varchar(20) NOT NULL,
  `forum_id` varchar(255) NOT NULL,
  `rebelforum` varchar(255) NOT NULL,
  `mandoid` int NOT NULL,
  `sgid` varchar(10) NOT NULL DEFAULT '0',
  `password` varchar(255) DEFAULT NULL,
  `last_active` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approved` int NOT NULL DEFAULT '0',
  `subscribe` int NOT NULL DEFAULT '1',
  `theme` int NOT NULL DEFAULT '0',
  `supporter` int NOT NULL DEFAULT '0',
  `esquad1` tinyint(1) DEFAULT '1',
  `esquad2` tinyint(1) DEFAULT '1',
  `esquad3` tinyint(1) DEFAULT '1',
  `esquad4` tinyint(1) DEFAULT '1',
  `esquad5` tinyint(1) DEFAULT '1',
  `ecomments` tinyint(1) DEFAULT '1',
  `efast` tinyint(1) DEFAULT '0',
  `ecommandnotify` tinyint(1) DEFAULT '1',
  `econfirm` tinyint(1) DEFAULT '1',
  `datecreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int NOT NULL,
  `troopid` int NOT NULL,
  `trooperid` int NOT NULL,
  `filename` varchar(255) NOT NULL,
  `admin` int NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `501st_troopers`
--
ALTER TABLE `501st_troopers`
  ADD PRIMARY KEY (`legionid`);

--
-- Indexes for table `awards`
--
ALTER TABLE `awards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `award_troopers`
--
ALTER TABLE `award_troopers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `costumes`
--
ALTER TABLE `costumes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`txn_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_sign_up`
--
ALTER TABLE `event_sign_up`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mando_troopers`
--
ALTER TABLE `mando_troopers`
  ADD PRIMARY KEY (`mandoid`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `titles`
--
ALTER TABLE `titles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `title_troopers`
--
ALTER TABLE `title_troopers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `troopers`
--
ALTER TABLE `troopers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `awards`
--
ALTER TABLE `awards`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `award_troopers`
--
ALTER TABLE `award_troopers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `costumes`
--
ALTER TABLE `costumes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_sign_up`
--
ALTER TABLE `event_sign_up`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `titles`
--
ALTER TABLE `titles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `title_troopers`
--
ALTER TABLE `title_troopers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `troopers`
--
ALTER TABLE `troopers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
