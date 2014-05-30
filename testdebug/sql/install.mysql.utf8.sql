-- phpMyAdmin SQL Dump
-- version 2.9.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Sep 02, 2011 at 07:42 AM
-- Server version: 5.0.27
-- PHP Version: 5.2.0
-- 
-- Database: `jom`
-- 

-- --------------------------------------------------------


-- 
-- Table structure for table `#__dartsleague_config`
-- 

CREATE TABLE `#__dartsleague_config` (
  `id` int(11) NOT NULL auto_increment,
  `dartsleagueversion` varchar(255) NOT NULL,
  `dartsleaguename` varchar(255) NOT NULL,
  `season` varchar(255) NOT NULL,
  `noweeks` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `#__dartsleague_config`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `#__dartsleague_events`
-- 

CREATE TABLE `#__dartsleague_events` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(45) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `#__dartsleague_events`
-- 


-- --------------------------------------------------------


-- Table structure for table `#__dartsleague_matchresults`
-- 

CREATE TABLE `#__dartsleague_matchresults` (
  `resultsid` int(11) NOT NULL,
  `hometeam` int(11) NOT NULL,
  `awayteam` int(11) NOT NULL,
  `approved` datetime NOT NULL,
  PRIMARY KEY  (`resultsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `#__dartsleague_matchresults`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `#__dartsleague_player_score`
-- 

CREATE TABLE `#__dartsleague_player_score` (
  `playerid` int(11) NOT NULL,
  `gamesplayed` int(11) default '0',
  `gameswon` int(11) default '0',
  `gameslost` int(11) default '0',
  `tons` int(11) default '0',
  `ton140s` int(11) default '0',
  `ton180s` int(11) default '0',
  `hipoints` int(11) default '0',
  `season` varchar(255) NOT NULL,
  `weekno` int(11) NOT NULL,
  `published` int(1) NOT NULL,
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `trash` smallint(6) NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY  (`playerid`),
  KEY `weekno` (`weekno`),
  KEY `season` (`season`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `#__dartsleague_player_score`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `#__dartsleague_playerevents`
-- 

CREATE TABLE `#__dartsleague_playerevents` (
  `id` int(11) NOT NULL auto_increment,
  `eventid` int(11) NOT NULL,
  `playerid` int(11) NOT NULL,
  `eventdate` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `#__dartsleague_playerevents`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `#__dartsleague_players`
-- 

CREATE TABLE `#__dartsleague_players` (
  `id` int(11) NOT NULL auto_increment,
  `firstname` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `plalias` varchar(30) default NULL,
  `gender` enum('m','f') NOT NULL default 'm',
  `nationality` varchar(15) default NULL,
  `birthdate` datetime default NULL,
  `email` varchar(30) default NULL,
  `mobile` int(11) default NULL,
  `teamid` int(11) NOT NULL,
  `teamposition` varchar(45) default NULL,
  `published` int(1) NOT NULL,
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `trash` smallint(6) NOT NULL,
  `ordering` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `catid` smallint(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- 
-- Dumping data for table `#__dartsleague_players`
-- 

INSERT INTO `#__dartsleague_players` (`id`, `firstname`, `lastname`, `plalias`, `gender`, `nationality`, `birthdate`, `email`, `mobile`, `teamid`, `teamposition`, `published`, `introtext`, `fulltext`, `trash`, `ordering`, `image`, `catid`) VALUES 
(1, 'player1', 'last', 'alias', 'm', 'oz', '0000-00-00 00:00:00', '1@2.3', 1234567890, 7, '', 1, '', '', 0, 0, '', 5),
(2, 'female1', '', '', 'f', '', '0000-00-00 00:00:00', '', 0, 6, '', 1, '', '', 0, 0, '', 0),
(3, 'gggplayer', 'ggglast', 'gggal', 'm', 'zx', '0000-00-00 00:00:00', '', 0, 1, NULL, 1, '', '', 0, 0, '', 0),
(4, 'gggggggggggg', '', '', 'm', '', '0000-00-00 00:00:00', '', 99, 1, NULL, 1, '', '', 1, 0, '', 0),
(5, 'ggggkkkkllll', '', '', 'm', '', '0000-00-00 00:00:00', '', 0, 1, NULL, 1, '', '', 0, 0, '', 0),
(6, 'pl100', '', '', 'm', '', '0000-00-00 00:00:00', '', 0, 7, NULL, 1, '', '', 0, 0, '', 0),
(7, 'pl99', '', '', 'm', '', '0000-00-00 00:00:00', '', 0, 7, NULL, 1, '', '', 0, 0, '', 0),
(8, 'pl105', '', '', 'm', '', '0000-00-00 00:00:00', '', 2147483647, 6, NULL, 1, '', '', 0, 0, '', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `#__dartsleague_rating`
-- 

CREATE TABLE `#__dartsleague_rating` (
  `itemID` int(11) NOT NULL default '0',
  `rating_sum` int(11) unsigned NOT NULL default '0',
  `rating_count` int(11) unsigned NOT NULL default '0',
  `lastip` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`itemID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `#__dartsleague_rating`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `#__dartsleague_schedule`
-- 

CREATE TABLE `#__dartsleague_schedule` (
  `id` int(11) NOT NULL auto_increment,
  `season` varchar(255) NOT NULL,
  `weekno` int(11) NOT NULL,
  `date` date NOT NULL,
  `teamhomeid` int(11) NOT NULL,
  `teamawayid` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `#__dartsleague_schedule`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `#__dartsleague_schedules`
-- 

CREATE TABLE `#__dartsleague_schedules` (
  `id` int(11) NOT NULL auto_increment,
  `season` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `#__dartsleague_schedules`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `#__dartsleague_scorecard`
-- 

CREATE TABLE `#__dartsleague_scorecard` (
  `id` int(11) NOT NULL,
  `submitteam` int(11) NOT NULL,
  `hometeam` varchar(45) NOT NULL,
  `awayteam` varchar(45) NOT NULL,
  `status` int(11) NOT NULL default '0',
  `submitdate` datetime NOT NULL,
  `ht_player1` varchar(45) default NULL,
  `ht_player1_score` int(11) default NULL,
  `at_player1` varchar(45) default NULL,
  `at_player1_score` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `#__dartsleague_scorecard`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `#__dartsleague_scoresheet`
-- 

CREATE TABLE `#__dartsleague_scoresheet` (
  `id` int(11) NOT NULL,
  `weekno` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `published` smallint(6) NOT NULL,
  `trash` smallint(6) NOT NULL,
  `team` varchar(255) NOT NULL,
  `season` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `catid` int(11) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `#__dartsleague_scoresheet`
-- 
-- --------------------------------------------------------

-- 
-- Table structure for table `#__dartsleague_seasons`
-- 

CREATE TABLE `#__dartsleague_seasons` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `year` varchar(255) NOT NULL,
  `no_of_weeks` varchar(255) NOT NULL,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  `published` int(1) NOT NULL default '1',
  `trash` smallint(6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `#__dartsleague_seasons`
-- 

-- --------------------------------------------------------
- --------------------------------------------------------
-- 
-- Table structure for table `#__dartsleague_team_div`
-- 

CREATE TABLE `#__dartsleague_team_div` (
  `teamid` int(11) NOT NULL,
  `division` varchar(2) NOT NULL,
  `overallrank` int(11) NOT NULL,
  `divisionrank` int(11) NOT NULL,
  `season` varchar(255) NOT NULL,
  PRIMARY KEY  (`teamid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `#__dartsleague_team_div`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `#__dartsleague_team_score`
-- 

CREATE TABLE `#__dartsleague_team_score` (
  `teamid` int(11) NOT NULL,
  `wins` int(11) default NULL,
  `losses` int(11) default NULL,
  `draws` int(11) default NULL,
  `mkymse` int(11) NOT NULL default '0',
  `ohone` int(11) NOT NULL default '0',
  `weekno` int(11) default NULL,
  `season` varchar(255) NOT NULL,
  PRIMARY KEY  (`teamid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `#__dartsleague_team_score`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `#__dartsleague_teams`
-- 

CREATE TABLE `#__dartsleague_teams` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(45) default NULL,
  `catid` int(11) NOT NULL,
  `checked_out` int(11) default NULL,
  `checked_out_time` date default NULL,
  `published` smallint(1) default NULL,
  `ordering` int(11) unsigned default NULL,
  `hits` int(11) unsigned default NULL,
  `trash` smallint(6) NOT NULL default '0',
  `params` text,
  `created` date NOT NULL,
  `created_by` int(11) NOT NULL default '0',
  `modified` date NOT NULL,
  `modified_by` int(11) NOT NULL default '0',
  `description` text NOT NULL,
  `parent` int(11) NOT NULL,
  `venueid` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `#__dartsleague_trophies`
-- 

CREATE TABLE `#__dartsleague_trophies` (
  `id` int(11) NOT NULL auto_increment,
  `season` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `#__dartsleague_trophies`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `#__dartsleague_user_groups`
-- 

CREATE TABLE `#__dartsleague_user_groups` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `permissions` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `#__dartsleague_user_groups`
-- 

INSERT INTO `#__dartsleague_user_groups` (`id`, `name`, `permissions`) VALUES 
(1, 'Registered', 'frontEdit=0\nadd=0\neditOwn=0\neditAll=0\npublish=0\ncomment=1\ninheritance=0\ncategories=all\n\n'),
(2, 'Site Owner', 'frontEdit=1\nadd=1\neditOwn=1\neditAll=1\npublish=1\ncomment=1\ninheritance=1\ncategories=all\n\n');

-- --------------------------------------------------------

-- 
-- Table structure for table `#__dartsleague_users`
-- 

CREATE TABLE `#__dartsleague_users` (
  `id` int(11) NOT NULL auto_increment,
  `userID` int(11) NOT NULL,
  `userName` varchar(255) default NULL,
  `gender` enum('m','f') NOT NULL default 'm',
  `description` text NOT NULL,
  `image` varchar(255) default NULL,
  `url` varchar(255) default NULL,
  `group` int(11) NOT NULL default '0',
  `plugins` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `userID` (`userID`),
  KEY `group` (`group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `#__dartsleague_users`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `#__dartsleague_venues`
-- 

CREATE TABLE `#__dartsleague_venues` (
  `id` int(11) NOT NULL auto_increment,
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `name` varchar(45) NOT NULL,
  `address` varchar(50) NOT NULL,
  `contact` int(11) NOT NULL,
  `boardnum` int(11) NOT NULL,
  `mapinfo` varchar(45) NOT NULL,
  `vip` varchar(1) NOT NULL,
  `extra_fields` text NOT NULL,
  `trash` smallint(6) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` int(1) NOT NULL,
  `params` text NOT NULL,
  `catid` int(11) NOT NULL,
  `hits` int(11) NOT NULL,
  `checked_out` varchar(255) NOT NULL,
  `checked_out_time` date NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `id_2` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `#__dartsleague_venues`
-- 
