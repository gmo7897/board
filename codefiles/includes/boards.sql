-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 03, 2015 at 07:16 PM
-- Server version: 5.5.40-cll
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `boards`
--
CREATE DATABASE IF NOT EXISTS `boards` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `boards`;

-- --------------------------------------------------------

--
-- Table structure for table `appeals`
--

CREATE TABLE IF NOT EXISTS `appeals` (
  `appealid` int(11) NOT NULL AUTO_INCREMENT,
  `appealby` varchar(15) NOT NULL,
  `appealto` varchar(15) NOT NULL,
  `modnum` int(11) NOT NULL,
  `reason` text NOT NULL,
  PRIMARY KEY (`appealid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `boards`
--

CREATE TABLE IF NOT EXISTS `boards` (
  `boardid` int(11) NOT NULL AUTO_INCREMENT,
  `catnum` int(11) NOT NULL,
  `boardname` varchar(30) NOT NULL,
  `boardlevel` smallint(6) NOT NULL DEFAULT '0',
  `topcount` int(11) NOT NULL DEFAULT '0',
  `messcount` int(11) NOT NULL DEFAULT '0',
  `boardextrainfo` text NOT NULL,
  PRIMARY KEY (`boardid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `boards`
--

INSERT INTO `boards` (`boardid`, `catnum`, `boardname`, `boardlevel`, `topcount`, `messcount`, `boardextrainfo`) VALUES
(-1, 3, 'Moderator Applications', 100, 0, 0, 'Moderator applications are posted here'),
(0, 3, 'Deleted Messages', 70, 0, 0, 'Deleted Messages are placed here'),
(1, 1, 'Message Board Announcements', -5, 2, 5, 'Announcements are posted here'),

-- --------------------------------------------------------

--
-- Table structure for table `catagories`
--

CREATE TABLE IF NOT EXISTS `catagories` (
  `catid` int(11) NOT NULL AUTO_INCREMENT,
  `catname` varchar(20) NOT NULL,
  `catplacement` int(11) NOT NULL,
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `catagories`
--

INSERT INTO `catagories` (`catid`, `catname`, `catplacement`) VALUES
(1, 'Announcement Boards', 1),
(2, 'Social Boards', 2),
(3, 'Administration', 3);

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE IF NOT EXISTS `levels` (
  `levelid` int(11) NOT NULL AUTO_INCREMENT,
  `levelnum` int(11) NOT NULL,
  `levelname` text NOT NULL,
  PRIMARY KEY (`levelid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`levelid`, `levelnum`, `levelname`) VALUES
(1, -5, '<b>-5: Denied</b><br />User has been denied activation. Cannot post messages.'),
(2, -4, '<b>-4: Closed</b><br />User has closed his/her account. Cannot be restored.'),
(3, -3, '<b>-3: Pending Closure</b><br />User has marked the account to be closed.  Will be closed in 48-72 hours.'),
(4, -2, '<b>-2: Banned</b><br />User has been banned for many TOU violations. Cannot post messages.'),
(5, -1, '<b>-1: Suspended</b><br />User has been suspended for one or more major TOU violations. Awaiting administrator decision.'),
(6, 0, '<b>0: Inactive</b><br />User has not been activated yet. Pending review by an administrator.'),
(7, 1, '<b>1: Locked</b><br />User''s account has been comprimised and all prviledges have been removed to prevent abuse.  Must contact an administrator for reactivation.'),
(8, 2, '<b>2: Timed Suspension</b><br />User has been suspended for a set period of time for a severe TOU violation.  Cannot post messages.'),
(9, 3, '<b>3: Probation</b><br />User has been placed on probation for a major TOU violation. Can post 5 messages per day, no topics.  Restored after 72 hours.'),
(10, 4, '<b>4&#58; Not Serious</b>&#13;<br />User&#39;s aura has fallen bellow 0.  Can post 0 topics&#44; 10 messages per day.  Any further violations are an automatic ban'),
(11, 5, '<b>5: Provisional Member</b><br />User has been activated but does not have full posting priviledges.  Can post 5 topics, 20 messages per day.  Upgraded to New Member after 72 hours with positive seriousness'),
(12, 20, '<b>20&#58; New Member</b>&#13;<br />This user has recently joined the AppletLand &#47; LostFacts message boards.'),
(13, 25, '<b>25: WBS Regular</b><br />User has achieved 50 seriousness.'),
(14, 30, '<b>30: Serious Member</b><br />User has achieved 111 seriousness.'),
(15, 33, '<b>33&#58; Very Serious Member</b>&#13;<br />User has achieved 220 aura.'),
(16, 36, '<b>36&#58; Dedicated Member</b>&#13;<br />User has achieved 400 aura.'),
(17, 39, '<b>39&#58; WBS 4 Life</b>&#13;<br />User has achieved 650 aura.'),
(18, 42, '<b>42&#58; WBS Elite</b>&#13;<br />User has maxed out his&#47;her aura at 1000.'),
(19, 60, '<b>60&#58; WBS VIP</b>&#13;<br />User is recognized as being an important part of AppletLand &#47; LostFacts.'),
(20, 70, '<b>70&#58; AppletLand &#47; LostFacts Enforcer</b>&#13;<br />User can delete marked messages and suspend offending users.'),
(21, 80, '<b>80&#58; AppletLand &#47; LostFacts Head Enforcer</b>&#13;<br />User can act on suspended users as well as all other enforcer privileges.'),
(22, 100, '<b>100: Site Manager</b><br />User has full control over the site and is responsible for its upkeep.');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `messageid` int(11) NOT NULL AUTO_INCREMENT,
  `topicnum` int(11) NOT NULL,
  `boardnum` int(11) NOT NULL,
  `messageby` varchar(15) NOT NULL,
  `messagestuff` text NOT NULL,
  `messdate` int(20) NOT NULL,
  PRIMARY KEY (`messageid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;



-- --------------------------------------------------------

--
-- Table structure for table `moderations`
--

CREATE TABLE IF NOT EXISTS `moderations` (
  `modid` int(11) NOT NULL AUTO_INCREMENT,
  `mesnum` int(11) NOT NULL,
  `modby` varchar(15) NOT NULL,
  `reason` text NOT NULL,
  `modaction` text NOT NULL,
  `topicdel` tinyint(4) NOT NULL DEFAULT '0',
  `boardnum` int(11) NOT NULL,
  `contested` tinyint(4) NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `messby` varchar(15) NOT NULL,
  `moddate` int(20) NOT NULL,
  PRIMARY KEY (`modid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `modqueue`
--

CREATE TABLE IF NOT EXISTS `modqueue` (
  `queueid` int(11) NOT NULL AUTO_INCREMENT,
  `mesid` int(11) NOT NULL,
  `markby` varchar(15) NOT NULL,
  `messageby` varchar(15) NOT NULL,
  `reason` text NOT NULL,
  `message` text NOT NULL,
  `markcount` int(11) NOT NULL,
  `markers` text NOT NULL,
  PRIMARY KEY (`queueid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;


-- --------------------------------------------------------

--
-- Table structure for table `shopitems`
--

CREATE TABLE IF NOT EXISTS `shopitems` (
  `itemid` int(11) NOT NULL AUTO_INCREMENT,
  `itemname` varchar(50) NOT NULL,
  `itemdescription` text NOT NULL,
  `price` int(11) NOT NULL,
  `owners` text NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `shopitems`
--

INSERT INTO `shopitems` (`itemid`, `itemname`, `itemdescription`, `price`, `owners`) VALUES
(1, 'HTML: Underline', 'Allows you to use underlines in posts.', 150, ''),
(2, 'VIP Card', 'Become a VIP with this instant credibility card', 1200, ''),
(3, 'HTML: Marquee', 'Allows you to use &#60;marquee&#62;marquee&#60;&#47;marquee&#62; in posts.', 175, ''),
(4, 'HTML: Big', 'Allows you to use &#60;big&#62;big&#60;&#47;big&#62; in posts.', 100, ''),
(5, 'HTML: Small', 'Allows you to use &#60;small&#62;small&#60;&#47;small&#62; in posts.', 100, ''),
(6, 'HTML: Code', 'Allows you to use &#60;code&#62;code&#60;&#47;code&#62; in posts.', 111, ''),
(7, 'HTML: Links', 'Allows you to use &#60;link&#62;links&#60;&#47;link&#62; in posts.', 350, ''),
(8, 'HTML: Color', 'This item allows you to use the tags&#58; &#60;red&#62;red&#60;&#47;red&#62;&#44; &#60;green&#62;green&#60;&#47;green&#62;&#44; &#60;blue&#62;blue&#60;&#47;blue&#62;&#44; &#60;yellow&#62;yellow&#60;&#47;yellow&#62;&#44; &#60;purple&#62;purple&#60;&#47;purple&#62;', 250, '');

-- --------------------------------------------------------

--
-- Table structure for table `systemmess`
--

CREATE TABLE IF NOT EXISTS `systemmess` (
  `messid` int(11) NOT NULL AUTO_INCREMENT,
  `messby` varchar(15) NOT NULL,
  `messto` varchar(15) NOT NULL,
  `mess` text NOT NULL,
  PRIMARY KEY (`messid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE IF NOT EXISTS `topics` (
  `topicid` int(11) NOT NULL AUTO_INCREMENT,
  `boardnum` int(11) NOT NULL,
  `topicby` varchar(15) NOT NULL,
  `topicname` varchar(80) NOT NULL,
  `topicactive` tinyint(4) NOT NULL DEFAULT '0',
  `posts` int(11) NOT NULL,
  `lastpost` int(20) NOT NULL,
  PRIMARY KEY (`topicid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;


-- --------------------------------------------------------

--
-- Table structure for table `usermap`
--

CREATE TABLE IF NOT EXISTS `usermap` (
  `mapid` int(11) NOT NULL AUTO_INCREMENT,
  `mapowner` varchar(15) NOT NULL,
  `mainip` text NOT NULL,
  `sharedips` text NOT NULL,
  `namelist` text NOT NULL,
  PRIMARY KEY (`mapid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `privemail` text NOT NULL,
  `pubemail` text,
  `userlevel` smallint(6) NOT NULL,
  `regdate` int(20) NOT NULL,
  `updatetime` int(20) NOT NULL,
  `lastlogindate` int(20) NOT NULL,
  `registeredip` text NOT NULL,
  `lastusedip` text NOT NULL,
  `signature` varchar(180) DEFAULT NULL,
  `themechoice` int(11) NOT NULL,
  `messages` int(11) NOT NULL DEFAULT '0',
  `appoints` int(11) NOT NULL DEFAULT '0',
  `regkey` varchar(15) NOT NULL,
  `unwarntime` int(20) NOT NULL,
  `dailyposts` int(11) NOT NULL DEFAULT '0',
  `dailytopics` int(11) NOT NULL DEFAULT '0',
  `timezone` int(11) NOT NULL DEFAULT '0',
  `biscuits` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
