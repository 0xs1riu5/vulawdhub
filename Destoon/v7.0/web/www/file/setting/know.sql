DROP TABLE IF EXISTS `destoon_know_10`;
CREATE TABLE `destoon_know_10` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `fee` float NOT NULL default '0',
  `credit` int(10) unsigned NOT NULL default '0',
  `aid` bigint(20) unsigned NOT NULL default '0',
  `hidden` tinyint(1) unsigned NOT NULL default '0',
  `process` tinyint(1) unsigned NOT NULL default '0',
  `message` tinyint(1) unsigned NOT NULL default '0',
  `addition` mediumtext NOT NULL,
  `comment` mediumtext NOT NULL,
  `introduce` varchar(255) NOT NULL default '',
  `keyword` varchar(255) NOT NULL default '',
  `pptword` varchar(255) NOT NULL default '',
  `hits` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `raise` int(10) unsigned NOT NULL default '0',
  `agree` int(10) unsigned NOT NULL default '0',
  `against` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL default '',
  `answer` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `passport` varchar(30) NOT NULL,
  `ask` varchar(30) NOT NULL,
  `expert` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `totime` int(10) unsigned NOT NULL default '0',
  `updatetime` int(10) unsigned NOT NULL default '0',
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
) TYPE=MyISAM COMMENT='知道';

DROP TABLE IF EXISTS `destoon_know_answer_10`;
CREATE TABLE `destoon_know_answer_10` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `qid` bigint(20) unsigned NOT NULL default '0',
  `url` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `vote` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `passport` varchar(30) NOT NULL,
  `expert` tinyint(1) unsigned NOT NULL default '0',
  `hidden` tinyint(1) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  KEY `qid` (`qid`)
) TYPE=MyISAM COMMENT='知道回答';

DROP TABLE IF EXISTS `destoon_know_data_10`;
CREATE TABLE `destoon_know_data_10` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` longtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='知道内容';

DROP TABLE IF EXISTS `destoon_know_expert_10`;
CREATE TABLE `destoon_know_expert_10` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `style` varchar(50) NOT NULL default '',
  `major` varchar(255) NOT NULL,
  `ask` int(10) unsigned NOT NULL default '0',
  `answer` int(10) unsigned NOT NULL default '0',
  `best` int(10) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `passport` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `introduce` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='知道专家';

DROP TABLE IF EXISTS `destoon_know_vote_10`;
CREATE TABLE `destoon_know_vote_10` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `qid` bigint(20) unsigned NOT NULL default '0',
  `aid` bigint(20) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `passport` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='知道投票';