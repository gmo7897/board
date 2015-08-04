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
-- Database: `gmocom_apple_boards`
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

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`messageid`, `topicnum`, `boardnum`, `messageby`, `messagestuff`, `messdate`) VALUES
(1, 1, 1, 'gmo', 'Maybe. I dunno.&#13;<br />&#13;<br />I really need to work through a lot of the code of this site. &#62;&#95;&#62;', 1438228332),
(2, 2, 2, 'gmo', 'There are a lot of goofy things in the code here. &#13;<br />&#13;<br />If something isn&#39;t working for you. Let me know.', 1438394529),
(3, 2, 2, 'Mith', 'Indeed ', 1438394921),
(4, 3, 2, 'Mith', '&#94;&#95;&#94;b&#13;<br />&#13;<br />Edit &#45; Error&#58; messages must have at least 5 non&#45;whitespace characters.', 1438395170),
(5, 4, 1, 'gmo', 'Just FYI&#44; there are a lot of issues with the site as it stands right now. The original code that I have is written in PHP4&#44; and a lot has changed in PHP since then. There are several things that newer versions of PHP do better&#44; and there are also things from PHP4 that no longer exist in PHP5 and beyond.&#13;<br />&#13;<br />Also&#44; prior to PHP4&#44; this site was written in ASP&#44; and some of that legacy code is still hanging around &#45; to include links leading to .aspx pages.&#13;<br />&#13;<br />I&#39;m currently working to bring the code to a more up&#45;to&#45;date standard&#44; but it is taking some time. There are about 70 files total for the site&#44; and I&#39;m having to go through each file pretty much line by line to make sure things are up&#45;to&#45;date &#45; especially any database connection&#44; which is where most of the site&#39;s deprecated features are coming into play &#40;unfortunately&#41;. &#13;<br />&#13;<br />If there is something that&#39;s truly breaking the site&#44; shoot me a PM on GameFAQs&#44; and I will try to fix that. Otherwise&#44; feel free to just carry on as normal&#44; and soon enough&#44; I will have a lot of the site working smoothly.', 1438395313),
(6, 4, 1, 'madfoot', 'u suck', 1438395363),
(7, 3, 2, 'gmo', 'yeah... there&#39;s a lot here that needs fixed. &#61;P', 1438395391),
(8, 5, 2, 'CumshotDragon', '&#173;make me admin &#13;<br />&#45;&#45;&#45;&#13;<br />A is for Allah&#44; nothing but Allah', 1438395493),
(9, 4, 1, 'CumshotDragon', '&#173;gmo is lame it is his fault &#13;<br />&#45;&#45;&#45;&#13;<br />A is for Allah&#44; nothing but Allah', 1438395528),
(10, 3, 2, 'CumshotDragon', '&#173;hi mithril&#13;<br />&#45;&#45;&#45;&#13;<br />A is for Allah&#44; nothing but Allah', 1438395549),
(11, 6, 2, 'madfoot', '&#173;&#13;<br />&#45;&#45;&#45;&#13;<br />post memes', 1438395651),
(12, 6, 2, 'CumshotDragon', '&#173;you suck &#13;<br />&#45;&#45;&#45;&#13;<br />A is for Allah&#44; nothing but Allah', 1438395688),
(13, 3, 2, 'gmo', 'What&#39;s up&#63;', 1438396195),
(14, 3, 2, 'gmo', 'Fun fact &#45; as of right now&#44; all of the mod and admin tools are broken. I cannot even activate accounts without going directly into the database and adjusting user levels. I can&#39;t view user profiles. I cannot view marked messages. Nothing...&#13;<br />', 1438396356),
(15, 3, 2, 'CumshotDragon', 'cool&#13;<br />&#45;&#45;&#45;&#13;<br />A is for Allah&#44; nothing but Allah', 1438396421),
(16, 3, 2, 'Naya', 'That&#39;s the best kind of code though. Lost Facts wasn&#39;t nightmarish enough&#44; so now we have something &#42;much better&#33;&#42;', 1438397615),
(17, 6, 2, 'Naya', 'what am madfoot drinking todya', 1438397808),
(18, 5, 2, 'CumshotDragon', '&#173;bump&#13;<br />&#45;&#45;&#45;&#13;<br />A is for Allah&#44; nothing but Allah', 1438404083),
(19, 5, 2, 'Naya', '&#42;support&#42;&#13;<br />&#13;<br />In the meantime&#44; you can feel like an admin by viewing the page on a twisted nematic screen and then rocking your head back and forth. The psychedelic colours will make everything <i>soooooooooo fabulous&#33;&#33;</i>', 1438405522),
(20, 7, 2, 'Mith', 'Turbo&#45;cunts', 1438432353),
(21, 3, 2, 'Mith', 'Ocelot&#44; my son ', 1438432396),
(22, 3, 2, 'CumshotDragon', '&#173;hey fellaheens &#13;<br />&#45;&#45;&#45;&#13;<br />A is for Allah&#44; nothing but Allah', 1438440437),
(23, 3, 2, 'OTACON120', 'This is going to be fun to get used to until things are brought up to 2015. &#62;&#95;&#62;', 1438497179),
(24, 3, 2, 'TheFifthPerson', 'Personally I think this is perfect. Who needs fancy features&#63;', 1438535143),
(25, 4, 1, 'yoshifan1', 'The first thing we need after everything is fixed is quick post.', 1438537506),
(26, 8, 2, 'Neoconkers', 'hi&#13;<br />&#13;<br />&#13;<br />hi', 1438537674),
(27, 3, 2, 'yoshifan1', '&#173;I said the code was bad.  My PHP learning process was a slow one&#44; and didn&#39;t help that the code was first written in JSP 4.2&#44; then sloppily translated to ASP.NET 2.0&#44; then hastily translated to PHP4.&#13;<br />&#45;&#45;&#45;&#13;<br />This is my signature&#13;<br />http&#58;&#47;&#47;www.crabdom.com&#47;forums &#45; a place that exists', 1438537841),
(28, 8, 2, 'yoshifan1', '&#173;Hello&#13;<br />&#45;&#45;&#45;&#13;<br />This is my signature&#13;<br />http&#58;&#47;&#47;www.crabdom.com&#47;forums &#45; a place that exists', 1438537884),
(29, 3, 2, 'gmo', 'heh... I never knew this was written in jsp first. At least there&#39;s none of that hanging around that I can tell. ', 1438547706),
(30, 7, 2, 'TheFifthPerson', 'Cuntbox', 1438558575);

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
