DROP TABLE IF EXISTS `destoon_club_18`;
CREATE TABLE `destoon_club_18` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `gid` bigint(20) unsigned NOT NULL default '0',
  `video` tinyint(1) unsigned NOT NULL default '0',
  `ontop` tinyint(1) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `fee` float NOT NULL default '0',
  `message` tinyint(1) unsigned NOT NULL default '0',
  `introduce` varchar(255) NOT NULL default '',
  `keyword` varchar(255) NOT NULL default '',
  `pptword` varchar(255) NOT NULL default '',
  `hits` int(10) unsigned NOT NULL default '0',
  `reply` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL default '',
  `username` varchar(30) NOT NULL default '',
  `passport` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `replyuser` varchar(30) NOT NULL,
  `replyer` varchar(30) NOT NULL,
  `replytime` int(10) unsigned NOT NULL default '0',
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
) TYPE=MyISAM COMMENT='商圈帖子';

DROP TABLE IF EXISTS `destoon_club_data_18`;
CREATE TABLE `destoon_club_data_18` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` longtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='商圈帖子内容';

DROP TABLE IF EXISTS `destoon_club_fans_18`;
CREATE TABLE `destoon_club_fans_18` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `gid` bigint(20) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `passport` varchar(30) NOT NULL,
  `reason` mediumtext NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  KEY `gid` (`gid`),
  KEY `username` (`username`),
  KEY `status` (`status`)
) TYPE=MyISAM COMMENT='商圈粉丝';

DROP TABLE IF EXISTS `destoon_club_group_18`;
CREATE TABLE `destoon_club_group_18` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL,
  `style` varchar(50) NOT NULL default '',
  `post` int(10) unsigned NOT NULL default '0',
  `fans` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL,
  `manager` varchar(255) NOT NULL,
  `username` varchar(30) NOT NULL default '',
  `passport` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL,
  `template` varchar(30) NOT NULL,
  `show_template` varchar(30) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `join_type` tinyint(1) unsigned NOT NULL default '0',
  `list_type` tinyint(1) unsigned NOT NULL default '0',
  `show_type` tinyint(1) unsigned NOT NULL default '0',
  `post_type` tinyint(1) unsigned NOT NULL default '0',
  `reply_type` tinyint(1) unsigned NOT NULL default '0',
  `reason` mediumtext NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`),
  KEY `addtime` (`addtime`),
  KEY `status` (`status`)
) TYPE=MyISAM COMMENT='商圈圈子';

DROP TABLE IF EXISTS `destoon_club_manage_18`;
CREATE TABLE `destoon_club_manage_18` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `gid` bigint(20) unsigned NOT NULL default '0',
  `tid` bigint(20) unsigned NOT NULL default '0',
  `rid` bigint(20) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `totime` int(10) unsigned NOT NULL default '0',
  `typeid` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `reason` mediumtext NOT NULL,
  `message` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='商圈管理';

DROP TABLE IF EXISTS `destoon_club_reply_18`;
CREATE TABLE `destoon_club_reply_18` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `tid` bigint(20) unsigned NOT NULL default '0',
  `gid` bigint(20) unsigned NOT NULL default '0',
  `fid` int(10) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `username` varchar(30) NOT NULL default '',
  `passport` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  KEY `tid` (`tid`),
  KEY `status` (`status`)
) TYPE=MyISAM COMMENT='商圈回复';