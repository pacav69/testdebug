DROP TABLE IF EXISTS `#__unclebill`;
 
CREATE TABLE `#__unclebill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `greeting` varchar(25) NOT NULL,
   PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
 
INSERT INTO `#__unclebill` (`greeting`) VALUES
	('UNCLEBILL'),
	('Good bye World!');
