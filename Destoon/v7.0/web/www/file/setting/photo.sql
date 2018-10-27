DROP TABLE IF EXISTS `destoon_photo_12`;
CREATE TABLE `destoon_photo_12` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `fee` float NOT NULL default '0',
  `introduce` varchar(255) NOT NULL default '',
  `keyword` varchar(255) NOT NULL default '',
  `pptword` varchar(255) NOT NULL default '',
  `items` int(10) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL default '',
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `template` varchar(30) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `open` tinyint(1) unsigned NOT NULL default '3',
  `password` varchar(30) NOT NULL default '',
  `question` varchar(30) NOT NULL default '',
  `answer` varchar(30) NOT NULL default '',
  `linkurl` varchar(255) NOT NULL default '',
  `filepath` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `addtime` (`addtime`),
  KEY `catid` (`catid`),
  KEY `username` (`username`)
) TYPE=MyISAM COMMENT='图库';

DROP TABLE IF EXISTS `destoon_photo_data_12`;
CREATE TABLE `destoon_photo_data_12` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` longtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='图库内容';

DROP TABLE IF EXISTS `destoon_photo_item_12`;
CREATE TABLE `destoon_photo_item_12` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `item` bigint(20) unsigned NOT NULL default '0',
  `introduce` text NOT NULL,
  `thumb` varchar(255) NOT NULL default '',
  `listorder` smallint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  KEY `listorder` (`listorder`),
  KEY `item` (`item`)
) TYPE=MyISAM COMMENT='图库图片';