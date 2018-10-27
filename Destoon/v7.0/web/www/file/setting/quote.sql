DROP TABLE IF EXISTS `destoon_quote_7`;
CREATE TABLE `destoon_quote_7` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `fee` float NOT NULL default '0',
  `introduce` varchar(255) NOT NULL default '',
  `tag` varchar(100) NOT NULL default '',
  `keyword` varchar(255) NOT NULL default '',
  `pptword` varchar(255) NOT NULL default '',
  `hits` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL default '',
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `adddate` date NOT NULL default '0000-00-00',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `template` varchar(30) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `filepath` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `addtime` (`addtime`),
  KEY `catid` (`catid`),
  KEY `username` (`username`)
) TYPE=MyISAM COMMENT='行情';

DROP TABLE IF EXISTS `destoon_quote_data_7`;
CREATE TABLE `destoon_quote_data_7` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` longtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='行情内容';

DROP TABLE IF EXISTS `destoon_quote_price_7`;
CREATE TABLE `destoon_quote_price_7` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `pid` bigint(20) unsigned NOT NULL default '0',
  `price` decimal(10,2) NOT NULL,
  `market` smallint(6) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL,
  `areaid` int(10) unsigned NOT NULL default '0',
  `company` varchar(100) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `qq` varchar(20) NOT NULL,
  `wx` varchar(50) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `note` varchar(255) NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `addtime` (`addtime`),
  KEY `pid` (`pid`)
) TYPE=MyISAM COMMENT='行情报价';

DROP TABLE IF EXISTS `destoon_quote_product_7`;
CREATE TABLE `destoon_quote_product_7` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `unit` varchar(10) NOT NULL,
  `price` decimal(10,2) unsigned NOT NULL,
  `minprice` decimal(10,2) unsigned NOT NULL,
  `maxprice` decimal(10,2) unsigned NOT NULL,
  `n1` varchar(100) NOT NULL,
  `n2` varchar(100) NOT NULL,
  `n3` varchar(100) NOT NULL,
  `v1` varchar(100) NOT NULL,
  `v2` varchar(100) NOT NULL,
  `v3` varchar(100) NOT NULL,
  `market` varchar(255) NOT NULL,
  `item` int(10) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `seo_title` varchar(255) NOT NULL,
  `seo_keywords` varchar(255) NOT NULL,
  `seo_description` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='行情产品';