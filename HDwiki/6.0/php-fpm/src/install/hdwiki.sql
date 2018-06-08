
DROP TABLE IF EXISTS wiki_attachment;
CREATE TABLE wiki_attachment (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `did` mediumint(8) unsigned NOT NULL default '0',
  `time` int(10) unsigned NOT NULL default '0',
  `filename` char(100) NOT NULL default '',
  `description` char(100) NOT NULL default '',
  `filetype` char(50) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `attachment` char(100) NOT NULL default '',
  `downloads` mediumint(8) NOT NULL default '0',
  `coindown` SMALLINT(4) NOT NULL DEFAULT '0',
  `isimage` tinyint(1) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `state` tinyint(4) NOT NULL default '0',
  `focus` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `did` (`did`),
  KEY `uid` (`uid`),
  KEY `time` (`time`),
  KEY `downloads` (`downloads`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_activation;
CREATE TABLE wiki_activation (
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `code` char(32) NOT NULL,
  `time` int(10) unsigned NOT NULL default '0',
  `available` tinyint(1) NOT NULL default '1',
  `type` tinyint(1) NOT NULL default '0'
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_banned;
CREATE TABLE wiki_banned (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `ip1` smallint(3) NOT NULL default '0',
  `ip2` smallint(3) NOT NULL default '0',
  `ip3` smallint(3) NOT NULL default '0',
  `ip4` smallint(3) NOT NULL default '0',
  `admin` varchar(15) NOT NULL,
  `time` int(10) unsigned NOT NULL default '0',
  `expiration` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_category;
CREATE TABLE wiki_category (
  `cid` smallint(6) unsigned NOT NULL auto_increment,
  `pid` smallint(6) unsigned NOT NULL default '0',
  `name` char(50) NOT NULL default '',
  `displayorder` tinyint(3) NOT NULL default '0',
  `docs` mediumint(8) unsigned NOT NULL default '0',
  `image` varchar(255) NOT NULL default '',
  `navigation` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`cid`),
  KEY `pid` (`pid`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_creditdetail;
CREATE TABLE wiki_creditdetail (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `operation` varchar(100) NOT NULL default '',
  `credit2` smallint(6) NOT NULL default '0',
  `credit1` smallint(6) NOT NULL default '0',
  `time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_doc;
CREATE TABLE wiki_doc (
  `did` mediumint(8) unsigned NOT NULL auto_increment,
  `cid` int(6) unsigned NOT NULL,
  `letter` char(1) NOT NULL,
  `title` varchar(80) NOT NULL default '',
  `tag` varchar(250) NOT NULL,
  `summary` varchar(250) NOT NULL,
  `content` mediumtext NOT NULL,
  `author` varchar(15) NOT NULL default '',
  `authorid` mediumint(8) unsigned NOT NULL default '0',
  `time` int(10) unsigned NOT NULL default '0',
  `lastedit` int(10) unsigned NOT NULL default '0',
  `lasteditor` char(15) NOT NULL default '',
  `lasteditorid` mediumint(8) unsigned NOT NULL default '0',
  `views` int(10) unsigned NOT NULL default '0',
  `edits` mediumint(8) unsigned NOT NULL default '0',
  `editions` mediumint(8) unsigned NOT NULL DEFAULT '0',
   comments mediumint(8) unsigned NOT NULL DEFAULT '0',
   votes mediumint(8) unsigned NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL default '1',
  `locked` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`did`),
  KEY `title` (`title`),
  KEY `cid` (`cid`), 
  KEY `authorid` (`authorid`),
  KEY `letter` (`letter`),
  KEY `lastedit` (`lastedit`),
  KEY `time` (`time`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_edition;
CREATE TABLE wiki_edition (
  `eid` mediumint(8) unsigned NOT NULL auto_increment,
  `cid` smallint(6) unsigned NOT NULL default '0',
  `did` mediumint(8) unsigned NOT NULL default '0',
  `author` varchar(15) NOT NULL default '',
  `authorid` mediumint(8) unsigned NOT NULL default '0',
  `time` int(10) unsigned NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `title` varchar(80) NOT NULL default '',
  `tag` varchar(250) NOT NULL,
  `summary` varchar(250) NOT NULL,
  `content` mediumtext NOT NULL,
  `words` int(10) unsigned NOT NULL default '0',
  `images` int(10) unsigned NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `judge` tinyint(1) NOT NULL default '0',
  `excellent` tinyint(1) NOT NULL default '0',
  `big` tinyint(1) NOT NULL default '0',
  `reason` varchar(250) NOT NULL default '',
  `coins` int(10) NOT NULL default '0',
  PRIMARY KEY  (`eid`),
  KEY `cid` (`cid`),
  KEY `authorid` (`authorid`),
  KEY `time` (`time`),
  KEY `did` (`did`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_comment;
CREATE TABLE wiki_comment (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `did` mediumint(8) unsigned NOT NULL default '0',
  `comment` char(200) NOT NULL default '',
  `reply` mediumtext NOT NULL,
  `author` varchar(15) NOT NULL default '',
  `authorid` mediumint(8) unsigned NOT NULL default '0',
  `oppose` tinyint(6) unsigned NOT NULL default '0',
  `aegis` tinyint(6) unsigned NOT NULL default '0',
  `time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `time` (`time`),
  KEY `did` (`did`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_lock;
CREATE TABLE  wiki_lock (
  `did` mediumint(8) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`did`,`uid`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_focus;
CREATE TABLE wiki_focus (
  `did` mediumint(8) unsigned NOT NULL default '0',
  `title` varchar(80) NOT NULL default '',
  `tag` varchar(250) NOT NULL default '',
  `summary` varchar(250) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `time` int(10) unsigned NOT NULL default '0',
  `displayorder` tinyint(4) NOT NULL default '0',
  `type` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`did`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_friendlink;
CREATE TABLE wiki_friendlink (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `logo` varchar(100) default NULL,
  `description` varchar(200) default NULL,
  `displayorder` tinyint(2) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_language;
CREATE TABLE  wiki_language (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL default '',
  `available` tinyint(1) NOT NULL default '1',
  `path` varchar(100) NOT NULL,
  `copyright` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_session;
CREATE TABLE  wiki_session (
  `sid` char(6)  NOT NULL default '',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` char(15) NOT NULL default '',
  `code` char(4) NOT NULL default '',
  `islogin` tinyint(1) NOT NULL default '0',
  `time` int(10) unsigned NOT NULL default '0',
  `referer` varchar(150) default NULL,
  UNIQUE KEY `sid` (`sid`),
  KEY `uid` (`uid`)
) TYPE=HEAP;


DROP TABLE IF EXISTS wiki_setting;
CREATE TABLE  wiki_setting (
  `variable` varchar(32) NOT NULL,
  `value` text,
  PRIMARY KEY  (`variable`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_theme;
CREATE TABLE  wiki_theme (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL default '',
  `available` tinyint(1) NOT NULL default '1',
  `path` varchar(100) NOT NULL,
  `copyright` varchar(100) NOT NULL,
   `css` text  NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_task;
CREATE TABLE  wiki_task (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `url` varchar(50) NOT NULL,
  `lastrun` int(10) unsigned NOT NULL default '0',
  `nextrun` int(10) unsigned NOT NULL default '0',
  `weekday` tinyint(1) NOT NULL,
  `day` tinyint(1) NOT NULL,
  `hour` tinyint(2) NOT NULL,
  `minute` tinyint(2) NOT NULL,
  `status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_user;
CREATE TABLE  wiki_user (
  `uid` mediumint(8) unsigned NOT NULL auto_increment,
  `username` char(20) NOT NULL default '',
  `password` char(32) NOT NULL default '',
  `email` char(50) NOT NULL default '',
  `gender` tinyint(1) NOT NULL default '0',
  `credit2` int(10) NOT NULL default '0',
  `credit1` int(10) NOT NULL default '0',
  `birthday` int(10) unsigned NOT NULL default '0',
  `image` varchar(255) NOT NULL default '',
  `postcode` varchar(30) NOT NULL default '',
  `location` varchar(30) NOT NULL default '',
  `regip` char(15) NOT NULL default '',
  `regtime` int(10) unsigned NOT NULL default '0',
  `lastip` char(15) NOT NULL default '',
  `lasttime` int(10) unsigned NOT NULL default '0',
  `groupid` smallint(6) unsigned NOT NULL default '0',
  `timeoffset` varchar(20) NOT NULL default '8',
  `style` varchar(20) NOT NULL default 'default',
  `language` varchar(20) NOT NULL default 'zh',
  `signature` text NOT NULL,
  `truename` varchar(30) NOT NULL DEFAULT '',
  `telephone` varchar(30) NOT NULL DEFAULT '',
  `qq` varchar(12) NOT NULL DEFAULT '',
  `msn` varchar(100) NOT NULL DEFAULT '',
  `authstr` varchar(20) NOT NULL DEFAULT '',
  `creates` mediumint(8) unsigned NOT NULL default '0',
  `edits` mediumint(8) unsigned NOT NULL default '0',
  `views` int(10) unsigned NOT NULL default '0',
  `checkup` int(10) unsigned NOT NULL default '1',
  `newdocs` smallint(6) NOT NULL DEFAULT '-1',
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `credit2` (`credit2`),
  KEY `views` (`views`)
) TYPE=MyISAM;



DROP TABLE IF EXISTS wiki_usergroup;
CREATE TABLE wiki_usergroup (
  groupid smallint(6) unsigned  NOT NULL auto_increment,
  grouptitle char(30) NOT NULL DEFAULT '',
  regulars  text NOT NULL,
  `default` text NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
   creditslower int(10) NOT NULL DEFAULT '0',
   creditshigher int(10) NOT NULL DEFAULT '0',
   stars tinyint(3) NOT NULL DEFAULT '0',
   color char(7) NOT NULL DEFAULT '',
   groupavatar char(60) NOT NULL DEFAULT '',
   primary key (groupid),
   KEY creditsrange (creditslower,creditshigher)
)TYPE=MyISAM;

DROP TABLE IF EXISTS wiki_regulargroup;
CREATE TABLE wiki_regulargroup(
  id smallint(6) unsigned  NOT NULL auto_increment,
  title char(30) NOT NULL DEFAULT '',
  `size` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  primary key (id)
)TYPE=MyISAM;

DROP TABLE IF EXISTS wiki_regular_relation;
CREATE TABLE wiki_regular_relation(
  id int(11) unsigned  NOT NULL auto_increment,
  `idleft` int(11) NOT NULL DEFAULT '0',
  `idright` int(11) NOT NULL DEFAULT '0',
  primary key (id)
)TYPE=MyISAM;

DROP TABLE IF EXISTS wiki_regular;
CREATE TABLE wiki_regular(
  id smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  regular varchar(255)  NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `regulargroupid` int(10) unsigned NOT NULL default '0',
  primary key (id)
)TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_plugin;
CREATE TABLE wiki_plugin (
  pluginid smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  available tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(40) NOT NULL DEFAULT '',
  identifier varchar(40) NOT NULL DEFAULT '',
  description varchar(255) NOT NULL DEFAULT '',
  datatables varchar(255) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  copyright varchar(100) NOT NULL DEFAULT '',
  homepage varchar(100) NOT NULL DEFAULT '',
  version varchar(100) NOT NULL DEFAULT 'v1.0' ,
  suit varchar(100) NOT NULL DEFAULT '' ,
  modules text  NULL,
  PRIMARY KEY (pluginid),
  UNIQUE KEY identifier (identifier)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_pluginvar;
CREATE TABLE wiki_pluginvar (
  pluginvarid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  pluginid smallint(6) unsigned NOT NULL DEFAULT '0',
  displayorder tinyint(3) NOT NULL DEFAULT '0',
  title varchar(100) NOT NULL DEFAULT '',
  description varchar(255) NOT NULL DEFAULT '',
  variable varchar(40) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT 'text',
  `value` text NOT NULL,
  extra text NOT NULL,
  PRIMARY KEY (pluginvarid),
  KEY pluginid (pluginid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_pluginhook;
CREATE TABLE wiki_pluginhook (
  pluginhookid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  pluginid smallint(6) unsigned NOT NULL DEFAULT '0',
  available tinyint(1) NOT NULL DEFAULT '0',
  title varchar(255) NOT NULL DEFAULT '',
  description mediumtext NOT NULL,
  `code` mediumtext NOT NULL,
  PRIMARY KEY (pluginhookid),
  KEY pluginid (pluginid),
  KEY available (available)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_word;
CREATE TABLE wiki_word (
  id smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  admin varchar(15) NOT NULL DEFAULT '',
  find varchar(255) NOT NULL DEFAULT '',
  replacement varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_pms;
CREATE TABLE wiki_pms (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` varchar(15) NOT NULL,
  `fromid` mediumint(8) unsigned NOT NULL default '0',
  `toid` mediumint(8) unsigned NOT NULL default '0',
  `to` varchar(15) NOT NULL,
  `drafts` tinyint(1) NOT NULL default '0',
  `new` tinyint(1) NOT NULL default '0',
  `subject` varchar(75) NOT NULL,
  `message` text NOT NULL,
  `time` int(10) unsigned NOT NULL default '0',
  `delstatus` tinyint(1) NOT NULL default '0',
  `og` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `toid` (`toid`,`drafts`,`time`),
  KEY `fromid` (`fromid`,`drafts`,`time`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_blacklist;
CREATE TABLE wiki_blacklist (
  `uid` mediumint(8) unsigned NOT NULL,
  `blacklist` text NOT NULL,
  PRIMARY KEY  (`uid`)
) TYPE=MyISAM;


DROP TABLE IF EXISTS wiki_synonym;
CREATE TABLE wiki_synonym (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `srctitle` varchar(80) NOT NULL,
  `destdid` int(10) NOT NULL,
  `desttitle` varchar(80) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `srctitle` (`srctitle`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS wiki_advertisement;
CREATE TABLE wiki_advertisement (
  `advid` mediumint(8) unsigned NOT NULL auto_increment,
  `available` tinyint(1) NOT NULL default '0',
  `type` varchar(50) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `targets` text NOT NULL,
  `position` tinyint(2) NOT NULL default '0',
  `parameters` text NOT NULL,
  `code` text NOT NULL,
  `starttime` int(10) unsigned NOT NULL default '0',
  `endtime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`advid`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS wiki_autosave;
CREATE TABLE wiki_autosave (
  `aid` int(8) unsigned NOT NULL auto_increment,
  `uid` int(6) unsigned NOT NULL,
  `did` int(8) unsigned NOT NULL,
  `id` int(4) NOT NULL,
  `content` mediumtext NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`aid`),
  KEY `uid` (`uid`),
  KEY `did` (`did`)
) TYPE=MyISAM;

 
DROP TABLE IF EXISTS wiki_channel;
CREATE TABLE wiki_channel (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` char(50) NOT NULL default '',
   url char(200) NOT NULL default '',
   displayorder smallint(3) unsigned NOT NULL default '0',
  `available` tinyint(1) NOT NULL default '1',
  `position` tinyint(1) NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS wiki_docreference;
CREATE TABLE wiki_docreference (
  `id` INT NOT NULL auto_increment,
  `did` int(11) NOT NULL DEFAULT '0',
  `name` VARCHAR(200) NOT NULL,
  `url` VARCHAR(200) NOT NULL,
  KEY `did` (`did`),
  PRIMARY KEY (`id`)
) TYPE=MYISAM;

DROP TABLE IF EXISTS wiki_relation;
CREATE TABLE wiki_relation (
  `id` mediumint(8) NOT NULL auto_increment,
  `did` mediumint(8) NOT NULL,
  `title` varchar(80) NOT NULL,
  `relatedtitle` varchar(80) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `title` (`title`),
  KEY `did` (`did`)
) TYPE=MYISAM;

DROP TABLE IF EXISTS wiki_categorylink;
CREATE TABLE wiki_categorylink (
  `id` int(8) unsigned NOT NULL auto_increment,
  `did` int(8) unsigned NOT NULL default '0',
  `cid` int(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `did` (`did`),
  KEY `cid` (`cid`)
) TYPE=MYISAM;

DROP TABLE IF EXISTS wiki_gift;
CREATE TABLE wiki_gift (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(80) NOT NULL,
  `description` varchar(250) NOT NULL,
  `image` varchar(250) NOT NULL,
  `credit` int(10) NOT NULL default '0',
  `time` int(11) NOT NULL,
  `available` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS wiki_giftlog;
CREATE TABLE wiki_giftlog (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(6) unsigned NOT NULL,
  `gid` smallint(6) NOT NULL,
  `extra` text NOT NULL,
  `time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL default '0',
   PRIMARY KEY  (`id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS wiki_recycle;
CREATE TABLE wiki_recycle ( 
  `id` INT NOT NULL AUTO_INCREMENT ,
  `type` CHAR(30) NOT NULL ,
  `keyword` VARCHAR( 250 ) NOT NULL , 
  `content` MEDIUMTEXT NOT NULL , 
  `file` text NOT NULL , 
  `adminid` INT NOT NULL , 
  `admin` CHAR( 30 ) NOT NULL , 
  `dateline` INT NOT NULL , 
  PRIMARY KEY  (`id`),
  KEY `keyword` (`keyword`), 
  KEY `type` (`type`),
  KEY `adminid` (`adminid` ),
  KEY `admin` (`admin` ), 
  KEY `dateline` (`dateline` )
) TYPE=MYISAM;

DROP TABLE IF EXISTS wiki_innerlinkcache;
CREATE TABLE wiki_innerlinkcache (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `did` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `titleid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `did` (`did`),
  KEY `title` (`title`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS wiki_datacall;
CREATE TABLE  wiki_datacall (
  `id` smallint(5) NOT NULL auto_increment,
  `name` varchar(60) NOT NULL,
  `type` varchar(60) NOT NULL,
  `category` varchar(60)  NOT NULL DEFAULT '0',
  `classname` varchar(60) NOT NULL DEFAULT 'sql',
  `function` varchar(60) NOT NULL,
  `desc` varchar(255)  NOT NULL,
  `param` text  NOT NULL,
  `cachetime` int(10) NOT NULL DEFAULT '0',
  `available` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `category` (`category`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS wiki_block;
CREATE TABLE wiki_block (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `theme` varchar(50) NOT NULL DEFAULT '',
  `file` varchar(50) NOT NULL DEFAULT '',
  `area` varchar(50) NOT NULL DEFAULT '',
  `areaorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `block` varchar(50) NOT NULL DEFAULT '',
  `fun` varchar(30) NOT NULL DEFAULT '',
  `tpl` varchar(50) DEFAULT NULL,
  `params` longtext,
  `modified` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS wiki_docfavorite;
CREATE TABLE wiki_docfavorite (
  `id` mediumint(8) NOT NULL auto_increment,
  `uid` mediumint(8) NOT NULL,
  `did` mediumint(8) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS wiki_mailqueue;
CREATE TABLE  wiki_mailqueue (
`id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`touid` MEDIUMINT( 8 ) NOT NULL DEFAULT  '0',
`tomail` VARCHAR( 100 ) NOT NULL,
`frommail` VARCHAR( 100 ) NOT NULL,
`subject` VARCHAR( 255 ) NOT NULL,
`message` TEXT NOT NULL,
`html` TINYINT( 1 ) NOT NULL DEFAULT  '0',
`priority` TINYINT( 1 ) NOT NULL DEFAULT  '1',
`time` INT( 10 ) UNSIGNED NOT NULL,
`failures` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT  '0'
) TYPE = MYISAM;

DROP TABLE IF EXISTS wiki_uniontitle;
CREATE TABLE wiki_uniontitle (
  `did` int(11) unsigned NOT NULL default '0',
  `docdeclaration` text NOT NULL,
  `time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (`did`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS wiki_privatetitle;
CREATE TABLE wiki_privatetitle (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(80) NOT NULL default '',
	PRIMARY KEY (`id`),
	UNIQUE KEY `title` (`title`)
)TYPE=MyISAM;

DROP TABLE IF EXISTS wiki_visitlist;
CREATE TABLE wiki_visitlist (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`ip` VARCHAR( 40 ) NOT NULL DEFAULT '',
`useragent` VARCHAR( 255 ) NOT NULL DEFAULT '',
`time` INT( 10 ) NOT NULL DEFAULT '0'
) TYPE = MYISAM ;

DROP TABLE IF EXISTS wiki_nav;
CREATE TABLE wiki_nav (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` char(15) NOT NULL,
  `code` text NOT NULL,
  `position` tinyint(1) default NULL,
  `time` int(10) NOT NULL,
  `lastedit` int(10) NOT NULL,
  `lasteditor` char(15) NOT NULL,
  `lasteditorid` mediumint(8) default NULL,
  PRIMARY KEY  (`id`),
  KEY `AK_name` (`name`),
  KEY `AK_lastedit` (`lastedit`)
) TYPE = MYISAM ;

DROP TABLE IF EXISTS wiki_navlink;
CREATE TABLE wiki_navlink (
  `id` int(10) unsigned NOT NULL auto_increment,
  `navid` varchar(15) default NULL,
  `did` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `navid` (`navid`,`did`)
) TYPE = MYISAM ;

DROP TABLE IF EXISTS wiki_navmodel;
CREATE TABLE wiki_navmodel (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(15) NOT NULL,
  `code` text NOT NULL,
  `status` tinyint(1) default NULL,
  PRIMARY KEY  (`id`)
) TYPE = MYISAM ;
