-- 
-- 表的结构 `destoon_404`
-- 

DROP TABLE IF EXISTS `destoon_404`;
CREATE TABLE `destoon_404` (
  `itemid` int(10) unsigned NOT NULL auto_increment,
  `url` varchar(255) NOT NULL default '',
  `refer` varchar(255) NOT NULL,
  `robot` varchar(20) NOT NULL default '',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='404日志';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_ad`
-- 

DROP TABLE IF EXISTS `destoon_ad`;
CREATE TABLE `destoon_ad` (
  `aid` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `pid` int(10) unsigned NOT NULL default '0',
  `typeid` tinyint(1) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `amount` float NOT NULL default '0',
  `currency` varchar(20) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `introduce` varchar(255) NOT NULL default '',
  `hits` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `fromtime` int(10) unsigned NOT NULL default '0',
  `totime` int(10) unsigned NOT NULL default '0',
  `stat` tinyint(1) unsigned NOT NULL default '0',
  `note` text NOT NULL,
  `code` text NOT NULL,
  `text_name` varchar(100) NOT NULL default '',
  `text_url` varchar(255) NOT NULL default '',
  `text_title` varchar(100) NOT NULL default '',
  `text_style` varchar(50) NOT NULL default '',
  `image_src` varchar(255) NOT NULL default '',
  `image_url` varchar(255) NOT NULL default '',
  `image_alt` varchar(100) NOT NULL default '',
  `flash_src` varchar(255) NOT NULL default '',
  `flash_url` varchar(255) NOT NULL default '',
  `flash_loop` tinyint(1) unsigned NOT NULL default '1',
  `key_moduleid` smallint(6) unsigned NOT NULL default '0',
  `key_catid` smallint(6) unsigned NOT NULL default '0',
  `key_word` varchar(100) NOT NULL default '',
  `key_id` bigint(20) unsigned NOT NULL default '0',
  `listorder` smallint(4) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`aid`),
  KEY `pid` (`pid`)
) TYPE=MyISAM COMMENT='广告';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_ad_place`
-- 

DROP TABLE IF EXISTS `destoon_ad_place`;
CREATE TABLE `destoon_ad_place` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `moduleid` smallint(6) unsigned NOT NULL default '0',
  `typeid` tinyint(1) unsigned NOT NULL default '0',
  `open` tinyint(1) unsigned NOT NULL default '1',
  `name` varchar(255) NOT NULL default '',
  `thumb` varchar(255) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `introduce` varchar(255) NOT NULL default '',
  `code` text NOT NULL,
  `width` smallint(5) unsigned NOT NULL default '0',
  `height` smallint(5) unsigned NOT NULL default '0',
  `price` float unsigned NOT NULL default '0',
  `ads` smallint(4) unsigned NOT NULL default '0',
  `listorder` smallint(4) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `template` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`pid`)
) TYPE=MyISAM COMMENT='广告位';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_address`
-- 

DROP TABLE IF EXISTS `destoon_address`;
CREATE TABLE `destoon_address` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `truename` varchar(30) NOT NULL default '',
  `areaid` int(10) unsigned NOT NULL default '0',
  `address` varchar(255) NOT NULL default '',
  `postcode` varchar(10) NOT NULL default '',
  `telephone` varchar(30) NOT NULL default '',
  `mobile` varchar(30) NOT NULL default '',
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `listorder` smallint(4) unsigned NOT NULL default '0',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`)
) TYPE=MyISAM COMMENT='收货地址';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_admin`
-- 

DROP TABLE IF EXISTS `destoon_admin`;
CREATE TABLE `destoon_admin` (
  `adminid` smallint(6) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `listorder` smallint(4) unsigned NOT NULL default '0',
  `title` varchar(30) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `moduleid` smallint(6) NOT NULL default '0',
  `file` varchar(20) NOT NULL default '',
  `action` varchar(255) NOT NULL default '',
  `catid` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`adminid`)
) TYPE=MyISAM COMMENT='管理员';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_admin_log`
-- 

DROP TABLE IF EXISTS `destoon_admin_log`;
CREATE TABLE `destoon_admin_log` (
  `logid` int(10) unsigned NOT NULL auto_increment,
  `qstring` varchar(255) NOT NULL default '',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  `logtime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`logid`)
) TYPE=MyISAM COMMENT='管理日志';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_admin_online`
-- 

DROP TABLE IF EXISTS `destoon_admin_online`;
CREATE TABLE `destoon_admin_online` (
  `sid` varchar(32) NOT NULL default '',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  `moduleid` int(10) unsigned NOT NULL default '0',
  `qstring` varchar(255) NOT NULL default '',
  `lasttime` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `sid` (`sid`)
) TYPE=HEAP COMMENT='在线管理员';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_alert`
-- 

DROP TABLE IF EXISTS `destoon_alert`;
CREATE TABLE `destoon_alert` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `mid` smallint(6) unsigned NOT NULL default '0',
  `catid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `word` varchar(100) NOT NULL default '',
  `rate` smallint(4) unsigned NOT NULL default '0',
  `email` varchar(50) NOT NULL default '',
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '0',
  `edittime` int(10) unsigned NOT NULL default '0',
  `sendtime` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`)
) TYPE=MyISAM COMMENT='贸易提醒';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_announce`
-- 

DROP TABLE IF EXISTS `destoon_announce`;
CREATE TABLE `destoon_announce` (
  `itemid` int(10) unsigned NOT NULL auto_increment,
  `typeid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `content` text NOT NULL,
  `hits` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `fromtime` int(10) unsigned NOT NULL default '0',
  `totime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `islink` tinyint(1) unsigned NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `listorder` smallint(4) unsigned NOT NULL default '0',
  `template` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='公告';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_area`
-- 

DROP TABLE IF EXISTS `destoon_area`;
CREATE TABLE `destoon_area` (
  `areaid` int(10) unsigned NOT NULL auto_increment,
  `areaname` varchar(50) NOT NULL default '',
  `parentid` int(10) unsigned NOT NULL default '0',
  `arrparentid` varchar(255) NOT NULL default '',
  `child` tinyint(1) NOT NULL default '0',
  `arrchildid` text NOT NULL,
  `listorder` smallint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`areaid`)
) TYPE=MyISAM COMMENT='地区';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_article_21`
-- 

DROP TABLE IF EXISTS `destoon_article_21`;
CREATE TABLE `destoon_article_21` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `fee` float NOT NULL default '0',
  `subtitle` mediumtext NOT NULL,
  `introduce` varchar(255) NOT NULL default '',
  `tag` varchar(100) NOT NULL default '',
  `keyword` varchar(255) NOT NULL default '',
  `pptword` varchar(255) NOT NULL default '',
  `author` varchar(50) NOT NULL default '',
  `copyfrom` varchar(30) NOT NULL default '',
  `fromurl` varchar(255) NOT NULL default '',
  `voteid` varchar(100) NOT NULL default '',
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
  `islink` tinyint(1) unsigned NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `filepath` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `addtime` (`addtime`),
  KEY `catid` (`catid`),
  KEY `username` (`username`)
) TYPE=MyISAM COMMENT='资讯';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_article_data_21`
-- 

DROP TABLE IF EXISTS `destoon_article_data_21`;
CREATE TABLE `destoon_article_data_21` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` longtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='资讯内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_ask`
-- 

DROP TABLE IF EXISTS `destoon_ask`;
CREATE TABLE `destoon_ask` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `typeid` int(10) unsigned NOT NULL default '0',
  `qid` bigint(20) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `content` mediumtext NOT NULL,
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL,
  `edittime` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '0',
  `reply` mediumtext NOT NULL,
  `star` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='客服中心';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_banip`
-- 

DROP TABLE IF EXISTS `destoon_banip`;
CREATE TABLE `destoon_banip` (
  `itemid` int(10) unsigned NOT NULL auto_increment,
  `ip` varchar(50) NOT NULL default '',
  `editor` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `totime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='IP禁止';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_banword`
-- 

DROP TABLE IF EXISTS `destoon_banword`;
CREATE TABLE `destoon_banword` (
  `bid` int(10) unsigned NOT NULL auto_increment,
  `replacefrom` varchar(255) NOT NULL default '',
  `replaceto` varchar(255) NOT NULL default '',
  `deny` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bid`)
) TYPE=MyISAM COMMENT='词语过滤';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_brand_13`
-- 

DROP TABLE IF EXISTS `destoon_brand_13`;
CREATE TABLE `destoon_brand_13` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `fee` float NOT NULL default '0',
  `keyword` varchar(255) NOT NULL default '',
  `pptword` varchar(255) NOT NULL default '',
  `hits` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL default '',
  `homepage` varchar(255) NOT NULL default '',
  `username` varchar(30) NOT NULL default '',
  `groupid` smallint(4) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `adddate` date NOT NULL default '0000-00-00',
  `totime` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `company` varchar(100) NOT NULL default '',
  `vip` smallint(2) unsigned NOT NULL default '0',
  `validated` tinyint(1) unsigned NOT NULL default '0',
  `truename` varchar(30) NOT NULL default '',
  `telephone` varchar(50) NOT NULL default '',
  `fax` varchar(50) NOT NULL default '',
  `mobile` varchar(50) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `qq` varchar(20) NOT NULL default '',
  `wx` varchar(50) NOT NULL default '',
  `ali` varchar(30) NOT NULL default '',
  `skype` varchar(30) NOT NULL default '',
  `introduce` varchar(255) NOT NULL default '',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `editdate` date NOT NULL default '0000-00-00',
  `ip` varchar(50) NOT NULL default '',
  `template` varchar(30) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `filepath` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`),
  KEY `catid` (`catid`),
  KEY `areaid` (`areaid`),
  KEY `edittime` (`edittime`),
  KEY `editdate` (`editdate`,`vip`,`edittime`)
) TYPE=MyISAM COMMENT='品牌';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_brand_data_13`
-- 

DROP TABLE IF EXISTS `destoon_brand_data_13`;
CREATE TABLE `destoon_brand_data_13` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='品牌内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_buy_6`
-- 

DROP TABLE IF EXISTS `destoon_buy_6`;
CREATE TABLE `destoon_buy_6` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `typeid` smallint(2) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `fee` float NOT NULL default '0',
  `introduce` varchar(255) NOT NULL default '',
  `n1` varchar(100) NOT NULL,
  `n2` varchar(100) NOT NULL,
  `n3` varchar(100) NOT NULL,
  `v1` varchar(100) NOT NULL,
  `v2` varchar(100) NOT NULL,
  `v3` varchar(100) NOT NULL,
  `amount` varchar(10) NOT NULL default '',
  `price` varchar(10) NOT NULL default '',
  `pack` varchar(20) NOT NULL default '',
  `days` smallint(3) unsigned NOT NULL default '0',
  `tag` varchar(100) NOT NULL default '',
  `keyword` varchar(255) NOT NULL default '',
  `pptword` varchar(255) NOT NULL default '',
  `hits` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL default '',
  `thumb1` varchar(255) NOT NULL default '',
  `thumb2` varchar(255) NOT NULL default '',
  `thumbs` text NOT NULL,
  `username` varchar(30) NOT NULL default '',
  `groupid` smallint(4) unsigned NOT NULL default '0',
  `company` varchar(100) NOT NULL default '',
  `vip` smallint(2) unsigned NOT NULL default '0',
  `validated` tinyint(1) unsigned NOT NULL default '0',
  `truename` varchar(30) NOT NULL default '',
  `telephone` varchar(50) NOT NULL default '',
  `mobile` varchar(50) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `qq` varchar(20) NOT NULL default '',
  `wx` varchar(50) NOT NULL default '',
  `ali` varchar(30) NOT NULL default '',
  `skype` varchar(30) NOT NULL default '',
  `totime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `editdate` date NOT NULL default '0000-00-00',
  `addtime` int(10) unsigned NOT NULL default '0',
  `adddate` date NOT NULL default '0000-00-00',
  `ip` varchar(50) NOT NULL default '',
  `template` varchar(30) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `filepath` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`),
  KEY `editdate` (`editdate`,`vip`,`edittime`),
  KEY `edittime` (`edittime`),
  KEY `catid` (`catid`),
  KEY `areaid` (`areaid`)
) TYPE=MyISAM COMMENT='求购';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_buy_data_6`
-- 

DROP TABLE IF EXISTS `destoon_buy_data_6`;
CREATE TABLE `destoon_buy_data_6` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='求购内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_cache`
-- 

DROP TABLE IF EXISTS `destoon_cache`;
CREATE TABLE `destoon_cache` (
  `cacheid` varchar(32) NOT NULL default '',
  `totime` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `cacheid` (`cacheid`)
) TYPE=MyISAM COMMENT='文件缓存';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_cart`
-- 

DROP TABLE IF EXISTS `destoon_cart`;
CREATE TABLE `destoon_cart` (
  `userid` bigint(20) unsigned NOT NULL default '0',
  `data` text NOT NULL,
  `edittime` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `userid` (`userid`)
) TYPE=MyISAM COMMENT='购物车';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_category`
-- 

DROP TABLE IF EXISTS `destoon_category`;
CREATE TABLE `destoon_category` (
  `catid` int(10) unsigned NOT NULL auto_increment,
  `moduleid` smallint(6) unsigned NOT NULL default '0',
  `catname` varchar(50) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `catdir` varchar(255) NOT NULL default '',
  `linkurl` varchar(255) NOT NULL default '',
  `letter` varchar(4) NOT NULL default '',
  `level` tinyint(1) unsigned NOT NULL default '1',
  `item` bigint(20) unsigned NOT NULL default '0',
  `property` smallint(6) unsigned NOT NULL default '0',
  `parentid` int(10) unsigned NOT NULL default '0',
  `arrparentid` varchar(255) NOT NULL default '',
  `child` tinyint(1) NOT NULL default '0',
  `arrchildid` text NOT NULL,
  `listorder` smallint(4) unsigned NOT NULL default '0',
  `template` varchar(30) NOT NULL default '',
  `show_template` varchar(30) NOT NULL default '',
  `seo_title` varchar(255) NOT NULL default '',
  `seo_keywords` varchar(255) NOT NULL default '',
  `seo_description` varchar(255) NOT NULL default '',
  `group_list` varchar(255) NOT NULL default '',
  `group_show` varchar(255) NOT NULL default '',
  `group_add` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`catid`)
) TYPE=MyISAM COMMENT='栏目分类';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_category_option`
-- 

DROP TABLE IF EXISTS `destoon_category_option`;
CREATE TABLE `destoon_category_option` (
  `oid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `type` tinyint(1) unsigned NOT NULL default '0',
  `required` tinyint(1) unsigned NOT NULL default '0',
  `search` tinyint(1) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `value` text NOT NULL,
  `extend` text NOT NULL,
  `listorder` smallint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`oid`),
  KEY `catid` (`catid`)
) TYPE=MyISAM COMMENT='分类属性';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_category_value`
-- 

DROP TABLE IF EXISTS `destoon_category_value`;
CREATE TABLE `destoon_category_value` (
  `oid` bigint(20) unsigned NOT NULL default '0',
  `moduleid` smallint(6) NOT NULL default '0',
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `value` text NOT NULL,
  KEY `moduleid` (`moduleid`,`itemid`)
) TYPE=MyISAM COMMENT='分类属性值';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_chat`
-- 

DROP TABLE IF EXISTS `destoon_chat`;
CREATE TABLE `destoon_chat` (
  `chatid` varchar(32) NOT NULL,
  `fromuser` varchar(30) NOT NULL,
  `fgettime` int(10) unsigned NOT NULL default '0',
  `freadtime` int(10) unsigned NOT NULL default '0',
  `fnew` int(10) unsigned NOT NULL default '0',
  `touser` varchar(30) NOT NULL,
  `tgettime` int(10) unsigned NOT NULL default '0',
  `treadtime` int(10) unsigned NOT NULL default '0',
  `tnew` int(10) unsigned NOT NULL default '0',
  `lastmsg` varchar(255) NOT NULL,
  `lasttime` int(10) unsigned NOT NULL default '0',
  `forward` varchar(255) NOT NULL,
  UNIQUE KEY `chatid` (`chatid`),
  KEY `fromuser` (`fromuser`),
  KEY `touser` (`touser`),
  KEY `lasttime` (`lasttime`)
) TYPE=MyISAM COMMENT='在线聊天';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_chat_data_0`
-- 

DROP TABLE IF EXISTS `destoon_chat_data_0`;
CREATE TABLE `destoon_chat_data_0` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `content` text NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='聊天记录_0';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_chat_data_1`
-- 

DROP TABLE IF EXISTS `destoon_chat_data_1`;
CREATE TABLE `destoon_chat_data_1` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `content` text NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='聊天记录_1';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_chat_data_2`
-- 

DROP TABLE IF EXISTS `destoon_chat_data_2`;
CREATE TABLE `destoon_chat_data_2` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `content` text NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='聊天记录_2';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_chat_data_3`
-- 

DROP TABLE IF EXISTS `destoon_chat_data_3`;
CREATE TABLE `destoon_chat_data_3` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `content` text NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='聊天记录_3';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_chat_data_4`
-- 

DROP TABLE IF EXISTS `destoon_chat_data_4`;
CREATE TABLE `destoon_chat_data_4` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `content` text NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='聊天记录_4';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_chat_data_5`
-- 

DROP TABLE IF EXISTS `destoon_chat_data_5`;
CREATE TABLE `destoon_chat_data_5` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `content` text NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='聊天记录_5';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_chat_data_6`
-- 

DROP TABLE IF EXISTS `destoon_chat_data_6`;
CREATE TABLE `destoon_chat_data_6` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `content` text NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='聊天记录_6';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_chat_data_7`
-- 

DROP TABLE IF EXISTS `destoon_chat_data_7`;
CREATE TABLE `destoon_chat_data_7` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `content` text NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='聊天记录_7';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_chat_data_8`
-- 

DROP TABLE IF EXISTS `destoon_chat_data_8`;
CREATE TABLE `destoon_chat_data_8` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `content` text NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='聊天记录_8';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_chat_data_9`
-- 

DROP TABLE IF EXISTS `destoon_chat_data_9`;
CREATE TABLE `destoon_chat_data_9` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `content` text NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='聊天记录_9';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_city`
-- 

DROP TABLE IF EXISTS `destoon_city`;
CREATE TABLE `destoon_city` (
  `areaid` int(10) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `iparea` mediumtext NOT NULL,
  `domain` varchar(255) NOT NULL default '',
  `letter` varchar(4) NOT NULL default '',
  `listorder` smallint(4) unsigned NOT NULL default '0',
  `template` varchar(50) NOT NULL default '',
  `seo_title` varchar(255) NOT NULL default '',
  `seo_keywords` varchar(255) NOT NULL default '',
  `seo_description` varchar(255) NOT NULL default '',
  UNIQUE KEY `areaid` (`areaid`),
  KEY `domain` (`domain`)
) TYPE=MyISAM COMMENT='城市分站';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_club_18`
-- 

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

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_club_data_18`
-- 

DROP TABLE IF EXISTS `destoon_club_data_18`;
CREATE TABLE `destoon_club_data_18` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` longtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='商圈帖子内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_club_fans_18`
-- 

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

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_club_group_18`
-- 

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

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_club_manage_18`
-- 

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

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_club_reply_18`
-- 

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

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_comment`
-- 

DROP TABLE IF EXISTS `destoon_comment`;
CREATE TABLE `destoon_comment` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `item_mid` smallint(6) NOT NULL default '0',
  `item_id` bigint(20) unsigned NOT NULL default '0',
  `item_title` varchar(255) NOT NULL default '',
  `item_username` varchar(30) NOT NULL default '',
  `star` tinyint(1) NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `qid` bigint(20) unsigned NOT NULL default '0',
  `quotation` mediumtext NOT NULL,
  `username` varchar(30) NOT NULL default '',
  `passport` varchar(30) NOT NULL,
  `hidden` tinyint(1) NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `reply` mediumtext NOT NULL,
  `editor` varchar(30) NOT NULL default '',
  `replyer` varchar(30) NOT NULL default '',
  `replytime` int(10) unsigned NOT NULL default '0',
  `agree` int(10) unsigned NOT NULL default '0',
  `against` int(10) unsigned NOT NULL default '0',
  `quote` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  KEY `item_mid` (`item_mid`),
  KEY `item_id` (`item_id`)
) TYPE=MyISAM COMMENT='评论';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_comment_ban`
-- 

DROP TABLE IF EXISTS `destoon_comment_ban`;
CREATE TABLE `destoon_comment_ban` (
  `bid` bigint(20) unsigned NOT NULL auto_increment,
  `moduleid` smallint(6) NOT NULL default '0',
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bid`)
) TYPE=MyISAM COMMENT='评论禁止';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_comment_stat`
-- 

DROP TABLE IF EXISTS `destoon_comment_stat`;
CREATE TABLE `destoon_comment_stat` (
  `sid` bigint(20) unsigned NOT NULL auto_increment,
  `moduleid` smallint(6) NOT NULL default '0',
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `comment` int(10) unsigned NOT NULL default '0',
  `star1` int(10) unsigned NOT NULL default '0',
  `star2` int(10) unsigned NOT NULL default '0',
  `star3` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`sid`)
) TYPE=MyISAM COMMENT='评论统计';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_company`
-- 

DROP TABLE IF EXISTS `destoon_company`;
CREATE TABLE `destoon_company` (
  `userid` bigint(20) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `groupid` smallint(4) unsigned NOT NULL default '0',
  `company` varchar(100) NOT NULL default '',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `validated` tinyint(1) unsigned NOT NULL default '0',
  `validator` varchar(100) NOT NULL default '',
  `validtime` int(10) unsigned NOT NULL default '0',
  `vip` smallint(2) unsigned NOT NULL default '0',
  `vipt` smallint(2) unsigned NOT NULL default '0',
  `vipr` smallint(2) NOT NULL default '0',
  `type` varchar(100) NOT NULL default '',
  `catid` varchar(100) NOT NULL default '',
  `catids` varchar(100) NOT NULL default '',
  `areaid` int(10) unsigned NOT NULL default '0',
  `mode` varchar(100) NOT NULL default '',
  `capital` float unsigned NOT NULL default '0',
  `regunit` varchar(15) NOT NULL default '',
  `size` varchar(100) NOT NULL default '',
  `regyear` varchar(4) NOT NULL default '',
  `regcity` varchar(30) NOT NULL default '',
  `sell` varchar(255) NOT NULL default '',
  `buy` varchar(255) NOT NULL default '',
  `business` varchar(255) NOT NULL default '',
  `telephone` varchar(50) NOT NULL default '',
  `fax` varchar(50) NOT NULL default '',
  `mail` varchar(50) NOT NULL default '',
  `gzh` varchar(50) NOT NULL default '',
  `gzhqr` varchar(255) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `postcode` varchar(20) NOT NULL default '',
  `homepage` varchar(255) NOT NULL default '',
  `fromtime` int(10) unsigned NOT NULL default '0',
  `totime` int(10) unsigned NOT NULL default '0',
  `styletime` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL default '',
  `introduce` varchar(255) NOT NULL default '',
  `hits` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `keyword` varchar(255) NOT NULL default '',
  `template` varchar(30) NOT NULL default '',
  `skin` varchar(30) NOT NULL default '',
  `domain` varchar(100) NOT NULL default '',
  `icp` varchar(100) NOT NULL default '',
  `linkurl` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`userid`),
  KEY `domain` (`domain`),
  KEY `vip` (`vip`),
  KEY `areaid` (`areaid`),
  KEY `groupid` (`groupid`)
) TYPE=MyISAM COMMENT='公司';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_company_data`
-- 

DROP TABLE IF EXISTS `destoon_company_data`;
CREATE TABLE `destoon_company_data` (
  `userid` bigint(20) unsigned NOT NULL default '0',
  `content` text NOT NULL,
  PRIMARY KEY  (`userid`)
) TYPE=MyISAM COMMENT='公司内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_company_setting`
-- 

DROP TABLE IF EXISTS `destoon_company_setting`;
CREATE TABLE `destoon_company_setting` (
  `userid` bigint(20) unsigned NOT NULL default '0',
  `item_key` varchar(100) NOT NULL default '',
  `item_value` text NOT NULL,
  KEY `userid` (`userid`)
) TYPE=MyISAM COMMENT='公司设置';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_cron`
-- 

DROP TABLE IF EXISTS `destoon_cron`;
CREATE TABLE `destoon_cron` (
  `itemid` smallint(6) unsigned NOT NULL auto_increment,
  `title` varchar(30) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL default '0',
  `name` varchar(20) NOT NULL,
  `schedule` varchar(255) NOT NULL,
  `lasttime` int(10) unsigned NOT NULL default '0',
  `nexttime` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '0',
  `note` text NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `nexttime` (`nexttime`)
) TYPE=MyISAM COMMENT='计划任务';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_down_15`
-- 

DROP TABLE IF EXISTS `destoon_down_15`;
CREATE TABLE `destoon_down_15` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `fee` float NOT NULL default '0',
  `tag` varchar(255) NOT NULL default '',
  `album` varchar(100) NOT NULL,
  `keyword` varchar(255) NOT NULL default '',
  `pptword` varchar(255) NOT NULL default '',
  `hits` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `download` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL default '',
  `fileurl` varchar(255) NOT NULL default '',
  `fileext` varchar(10) NOT NULL default '',
  `filesize` float NOT NULL default '0',
  `unit` varchar(10) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `introduce` varchar(255) NOT NULL default '',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `template` varchar(30) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `filepath` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`),
  KEY `addtime` (`addtime`),
  KEY `catid` (`catid`),
  KEY `album` (`album`)
) TYPE=MyISAM COMMENT='下载';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_down_data_15`
-- 

DROP TABLE IF EXISTS `destoon_down_data_15`;
CREATE TABLE `destoon_down_data_15` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='下载内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_exhibit_8`
-- 

DROP TABLE IF EXISTS `destoon_exhibit_8`;
CREATE TABLE `destoon_exhibit_8` (
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
  `hits` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `orders` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL default '',
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `fromtime` int(10) unsigned NOT NULL default '0',
  `totime` int(10) unsigned NOT NULL default '0',
  `city` varchar(50) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `postcode` varchar(20) NOT NULL default '',
  `homepage` varchar(255) NOT NULL default '',
  `hallname` varchar(100) NOT NULL default '',
  `sponsor` varchar(100) NOT NULL default '',
  `undertaker` varchar(100) NOT NULL default '',
  `truename` varchar(30) NOT NULL default '',
  `addr` varchar(255) NOT NULL default '',
  `telephone` varchar(100) NOT NULL default '',
  `mobile` varchar(20) NOT NULL default '',
  `fax` varchar(20) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `qq` varchar(20) NOT NULL default '',
  `wx` varchar(50) NOT NULL default '',
  `remark` mediumtext NOT NULL,
  `sign` tinyint(1) unsigned NOT NULL default '0',
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
) TYPE=MyISAM COMMENT='展会';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_exhibit_data_8`
-- 

DROP TABLE IF EXISTS `destoon_exhibit_data_8`;
CREATE TABLE `destoon_exhibit_data_8` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='展会内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_exhibit_sign_8`
-- 

DROP TABLE IF EXISTS `destoon_exhibit_sign_8`;
CREATE TABLE `destoon_exhibit_sign_8` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `id` bigint(20) unsigned NOT NULL default '0',
  `user` varchar(30) NOT NULL,
  `title` varchar(100) NOT NULL default '',
  `amount` int(10) unsigned NOT NULL default '0',
  `company` varchar(100) NOT NULL,
  `truename` varchar(30) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `areaid` int(10) unsigned NOT NULL default '0',
  `address` varchar(255) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `qq` varchar(20) NOT NULL,
  `wx` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `id` (`id`)
) TYPE=MyISAM COMMENT='展会报名';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_favorite`
-- 

DROP TABLE IF EXISTS `destoon_favorite`;
CREATE TABLE `destoon_favorite` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `mid` smallint(6) unsigned NOT NULL default '0',
  `tid` bigint(20) unsigned NOT NULL default '0',
  `listorder` smallint(4) unsigned NOT NULL default '0',
  `userid` bigint(20) unsigned NOT NULL default '0',
  `typeid` bigint(20) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `thumb` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  KEY `userid` (`userid`)
) TYPE=MyISAM COMMENT='商机收藏';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_fetch`
-- 

DROP TABLE IF EXISTS `destoon_fetch`;
CREATE TABLE `destoon_fetch` (
  `itemid` int(10) unsigned NOT NULL auto_increment,
  `sitename` varchar(100) NOT NULL default '',
  `domain` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `encode` varchar(30) NOT NULL default '',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='单页采编';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_fields`
-- 

DROP TABLE IF EXISTS `destoon_fields`;
CREATE TABLE `destoon_fields` (
  `itemid` int(10) unsigned NOT NULL auto_increment,
  `tb` varchar(30) NOT NULL default '',
  `name` varchar(50) NOT NULL default '',
  `title` varchar(100) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  `type` varchar(20) NOT NULL default '',
  `length` smallint(4) unsigned NOT NULL default '0',
  `html` varchar(30) NOT NULL default '',
  `default_value` text NOT NULL,
  `option_value` text NOT NULL,
  `width` smallint(4) unsigned NOT NULL default '0',
  `height` smallint(4) unsigned NOT NULL default '0',
  `input_limit` varchar(255) NOT NULL default '',
  `addition` varchar(255) NOT NULL default '',
  `display` tinyint(1) unsigned NOT NULL default '0',
  `front` tinyint(1) unsigned NOT NULL default '0',
  `listorder` smallint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  KEY `tablename` (`tb`)
) TYPE=MyISAM COMMENT='自定义字段';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_finance_award`
-- 

DROP TABLE IF EXISTS `destoon_finance_award`;
CREATE TABLE `destoon_finance_award` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `fee` float unsigned NOT NULL default '0',
  `paytime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `mid` smallint(6) unsigned NOT NULL default '0',
  `tid` bigint(20) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`)
) TYPE=MyISAM COMMENT='打赏记录';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_finance_card`
-- 

DROP TABLE IF EXISTS `destoon_finance_card`;
CREATE TABLE `destoon_finance_card` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `number` varchar(30) NOT NULL default '',
  `password` varchar(30) NOT NULL default '',
  `amount` decimal(10,2) unsigned NOT NULL default '0.00',
  `editor` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `totime` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `updatetime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  UNIQUE KEY `number` (`number`)
) TYPE=MyISAM COMMENT='充值卡';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_finance_cash`
-- 

DROP TABLE IF EXISTS `destoon_finance_cash`;
CREATE TABLE `destoon_finance_cash` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `bank` varchar(50) NOT NULL default '',
  `banktype` tinyint(1) unsigned NOT NULL default '0',
  `branch` varchar(100) NOT NULL,
  `account` varchar(30) NOT NULL default '',
  `truename` varchar(30) NOT NULL default '',
  `amount` decimal(10,2) unsigned NOT NULL default '0.00',
  `fee` decimal(10,2) unsigned NOT NULL default '0.00',
  `addtime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `note` varchar(255) NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`)
) TYPE=MyISAM COMMENT='申请提现';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_finance_charge`
-- 

DROP TABLE IF EXISTS `destoon_finance_charge`;
CREATE TABLE `destoon_finance_charge` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `bank` varchar(20) NOT NULL default '',
  `amount` decimal(10,2) unsigned NOT NULL default '0.00',
  `fee` decimal(10,2) unsigned NOT NULL default '0.00',
  `money` decimal(10,2) unsigned NOT NULL default '0.00',
  `sendtime` int(10) unsigned NOT NULL default '0',
  `receivetime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default '0',
  `reason` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`)
) TYPE=MyISAM COMMENT='在线充值';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_finance_coupon`
-- 

DROP TABLE IF EXISTS `destoon_finance_coupon`;
CREATE TABLE `destoon_finance_coupon` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `seller` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `fromtime` int(10) unsigned NOT NULL default '0',
  `totime` int(10) unsigned NOT NULL default '0',
  `price` decimal(10,2) unsigned NOT NULL default '0.00',
  `cost` decimal(10,2) unsigned NOT NULL default '0.00',
  `pid` bigint(20) unsigned NOT NULL default '0',
  `oid` bigint(20) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL,
  `edittime` int(10) unsigned NOT NULL default '0',
  `note` varchar(255) NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`)
) TYPE=MyISAM COMMENT='优惠券';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_finance_credit`
-- 

DROP TABLE IF EXISTS `destoon_finance_credit`;
CREATE TABLE `destoon_finance_credit` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `amount` int(10) NOT NULL default '0',
  `balance` int(10) NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `reason` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  `editor` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`)
) TYPE=MyISAM COMMENT='积分流水';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_finance_deposit`
-- 

DROP TABLE IF EXISTS `destoon_finance_deposit`;
CREATE TABLE `destoon_finance_deposit` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `amount` decimal(10,2) NOT NULL default '0.00',
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL,
  `reason` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`)
) TYPE=MyISAM COMMENT='保证金';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_finance_pay`
-- 

DROP TABLE IF EXISTS `destoon_finance_pay`;
CREATE TABLE `destoon_finance_pay` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `fee` float unsigned NOT NULL default '0',
  `currency` varchar(20) NOT NULL default '',
  `paytime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `mid` smallint(6) unsigned NOT NULL default '0',
  `tid` bigint(20) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`)
) TYPE=MyISAM COMMENT='支付记录';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_finance_promo`
-- 

DROP TABLE IF EXISTS `destoon_finance_promo`;
CREATE TABLE `destoon_finance_promo` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `fromtime` int(10) unsigned NOT NULL default '0',
  `totime` int(10) unsigned NOT NULL default '0',
  `price` decimal(10,2) unsigned NOT NULL default '0.00',
  `cost` decimal(10,2) unsigned NOT NULL default '0.00',
  `amount` int(10) unsigned NOT NULL default '0',
  `number` int(10) unsigned NOT NULL default '0',
  `open` tinyint(1) unsigned NOT NULL default '1',
  `editor` varchar(30) NOT NULL,
  `edittime` int(10) unsigned NOT NULL default '0',
  `note` varchar(255) NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`)
) TYPE=MyISAM COMMENT='优惠促销';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_finance_record`
-- 

DROP TABLE IF EXISTS `destoon_finance_record`;
CREATE TABLE `destoon_finance_record` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `bank` varchar(30) NOT NULL default '',
  `amount` decimal(10,2) NOT NULL default '0.00',
  `balance` decimal(10,2) NOT NULL default '0.00',
  `addtime` int(10) unsigned NOT NULL default '0',
  `reason` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  `editor` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`)
) TYPE=MyISAM COMMENT='财务流水';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_finance_sms`
-- 

DROP TABLE IF EXISTS `destoon_finance_sms`;
CREATE TABLE `destoon_finance_sms` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `amount` int(10) NOT NULL default '0',
  `balance` int(10) NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `reason` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  `editor` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`)
) TYPE=MyISAM COMMENT='短信增减';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_form`
-- 

DROP TABLE IF EXISTS `destoon_form`;
CREATE TABLE `destoon_form` (
  `itemid` int(10) unsigned NOT NULL auto_increment,
  `typeid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `content` mediumtext NOT NULL,
  `groupid` varchar(255) NOT NULL,
  `verify` tinyint(1) unsigned NOT NULL default '0',
  `display` tinyint(1) unsigned NOT NULL default '0',
  `question` int(10) unsigned NOT NULL default '0',
  `answer` int(10) unsigned NOT NULL default '0',
  `maxanswer` int(10) unsigned NOT NULL default '1',
  `hits` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `fromtime` int(10) unsigned NOT NULL default '0',
  `totime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `template` varchar(30) NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='表单';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_form_answer`
-- 

DROP TABLE IF EXISTS `destoon_form_answer`;
CREATE TABLE `destoon_form_answer` (
  `aid` bigint(20) unsigned NOT NULL auto_increment,
  `fid` bigint(20) unsigned NOT NULL default '0',
  `rid` bigint(20) unsigned NOT NULL default '0',
  `qid` bigint(20) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `other` varchar(255) NOT NULL,
  `item` varchar(100) NOT NULL,
  PRIMARY KEY  (`aid`)
) TYPE=MyISAM COMMENT='表单回复';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_form_question`
-- 

DROP TABLE IF EXISTS `destoon_form_question`;
CREATE TABLE `destoon_form_question` (
  `qid` bigint(20) unsigned NOT NULL auto_increment,
  `fid` int(10) unsigned NOT NULL default '0',
  `type` tinyint(1) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `value` mediumtext NOT NULL,
  `required` varchar(30) NOT NULL,
  `extend` mediumtext NOT NULL,
  `listorder` smallint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`qid`),
  KEY `fid` (`fid`)
) TYPE=MyISAM COMMENT='表单选项';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_form_record`
-- 

DROP TABLE IF EXISTS `destoon_form_record`;
CREATE TABLE `destoon_form_record` (
  `rid` bigint(20) unsigned NOT NULL auto_increment,
  `fid` bigint(20) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `item` varchar(100) NOT NULL,
  PRIMARY KEY  (`rid`)
) TYPE=MyISAM COMMENT='表单回复记录';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_friend`
-- 

DROP TABLE IF EXISTS `destoon_friend`;
CREATE TABLE `destoon_friend` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `listorder` smallint(4) unsigned NOT NULL default '0',
  `userid` bigint(20) unsigned NOT NULL default '0',
  `typeid` bigint(20) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `truename` varchar(20) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `company` varchar(100) NOT NULL default '',
  `career` varchar(20) NOT NULL default '',
  `telephone` varchar(20) NOT NULL default '',
  `mobile` varchar(20) NOT NULL default '',
  `homepage` varchar(255) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `qq` varchar(20) NOT NULL default '',
  `wx` varchar(50) NOT NULL default '',
  `ali` varchar(30) NOT NULL default '',
  `skype` varchar(30) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  KEY `userid` (`userid`)
) TYPE=MyISAM COMMENT='我的商友';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_gift`
-- 

DROP TABLE IF EXISTS `destoon_gift`;
CREATE TABLE `destoon_gift` (
  `itemid` int(10) unsigned NOT NULL auto_increment,
  `typeid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `thumb` varchar(255) NOT NULL default '',
  `credit` int(10) unsigned NOT NULL default '0',
  `amount` int(10) unsigned NOT NULL default '0',
  `groupid` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `orders` int(10) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  `maxorder` int(10) unsigned NOT NULL default '1',
  `addtime` int(10) unsigned NOT NULL default '0',
  `fromtime` int(10) unsigned NOT NULL default '0',
  `totime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='积分换礼';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_gift_order`
-- 

DROP TABLE IF EXISTS `destoon_gift_order`;
CREATE TABLE `destoon_gift_order` (
  `oid` bigint(20) unsigned NOT NULL auto_increment,
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `credit` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `status` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`oid`),
  KEY `itemid` (`itemid`)
) TYPE=MyISAM COMMENT='积分换礼订单';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_group_17`
-- 

DROP TABLE IF EXISTS `destoon_group_17`;
CREATE TABLE `destoon_group_17` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `fee` float NOT NULL default '0',
  `introduce` varchar(255) NOT NULL default '',
  `price` decimal(10,2) unsigned NOT NULL default '0.00',
  `marketprice` decimal(10,2) unsigned NOT NULL default '0.00',
  `savemoney` decimal(10,2) unsigned NOT NULL default '0.00',
  `discount` float unsigned NOT NULL default '0',
  `minamount` int(10) unsigned NOT NULL default '0',
  `amount` int(10) unsigned NOT NULL default '0',
  `logistic` tinyint(1) unsigned NOT NULL default '0',
  `tag` varchar(100) NOT NULL default '',
  `keyword` varchar(255) NOT NULL default '',
  `pptword` varchar(255) NOT NULL default '',
  `hits` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `orders` int(10) unsigned NOT NULL default '0',
  `sales` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL default '',
  `username` varchar(30) NOT NULL default '',
  `groupid` smallint(4) unsigned NOT NULL default '0',
  `company` varchar(100) NOT NULL default '',
  `vip` smallint(2) unsigned NOT NULL default '0',
  `validated` tinyint(1) unsigned NOT NULL default '0',
  `truename` varchar(30) NOT NULL default '',
  `telephone` varchar(50) NOT NULL default '',
  `mobile` varchar(50) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `qq` varchar(20) NOT NULL default '',
  `wx` varchar(50) NOT NULL default '',
  `ali` varchar(30) NOT NULL default '',
  `skype` varchar(30) NOT NULL default '',
  `totime` int(10) unsigned NOT NULL default '0',
  `endtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `template` varchar(30) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `process` tinyint(1) unsigned NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `filepath` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`),
  KEY `addtime` (`addtime`),
  KEY `catid` (`catid`),
  KEY `areaid` (`areaid`)
) TYPE=MyISAM COMMENT='团购';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_group_data_17`
-- 

DROP TABLE IF EXISTS `destoon_group_data_17`;
CREATE TABLE `destoon_group_data_17` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='团购内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_group_order_17`
-- 

DROP TABLE IF EXISTS `destoon_group_order_17`;
CREATE TABLE `destoon_group_order_17` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `gid` bigint(20) unsigned NOT NULL default '0',
  `buyer` varchar(30) NOT NULL default '',
  `seller` varchar(30) NOT NULL default '',
  `title` varchar(100) NOT NULL default '',
  `thumb` varchar(255) NOT NULL default '',
  `price` decimal(10,2) unsigned NOT NULL default '0.00',
  `number` int(10) unsigned NOT NULL default '0',
  `amount` decimal(10,2) unsigned NOT NULL default '0.00',
  `logistic` tinyint(1) unsigned NOT NULL default '0',
  `password` varchar(6) NOT NULL default '',
  `buyer_name` varchar(30) NOT NULL default '',
  `buyer_address` varchar(255) NOT NULL default '',
  `buyer_postcode` varchar(10) NOT NULL default '',
  `buyer_mobile` varchar(30) NOT NULL default '',
  `send_type` varchar(50) NOT NULL default '',
  `send_no` varchar(50) NOT NULL default '',
  `send_status` tinyint(1) unsigned NOT NULL default '0',
  `send_time` varchar(20) NOT NULL default '',
  `send_days` int(10) unsigned NOT NULL default '0',
  `add_time` smallint(6) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `updatetime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `buyer_reason` mediumtext NOT NULL,
  `refund_reason` mediumtext NOT NULL,
  `note` varchar(255) NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  KEY `buyer` (`buyer`),
  KEY `seller` (`seller`)
) TYPE=MyISAM COMMENT='团购订单';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_guestbook`
-- 

DROP TABLE IF EXISTS `destoon_guestbook`;
CREATE TABLE `destoon_guestbook` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `areaid` int(10) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `content` text NOT NULL,
  `reply` text NOT NULL,
  `hidden` tinyint(1) unsigned NOT NULL default '0',
  `truename` varchar(30) NOT NULL default '',
  `telephone` varchar(50) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `qq` varchar(20) NOT NULL default '',
  `wx` varchar(50) NOT NULL default '',
  `ali` varchar(30) NOT NULL default '',
  `skype` varchar(30) NOT NULL default '',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`)
) TYPE=MyISAM COMMENT='留言本';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_honor`
-- 

DROP TABLE IF EXISTS `destoon_honor`;
CREATE TABLE `destoon_honor` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `content` mediumtext NOT NULL,
  `authority` varchar(100) NOT NULL default '',
  `thumb` varchar(255) NOT NULL default '',
  `hits` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `fromtime` int(10) unsigned NOT NULL default '0',
  `totime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='荣誉资质';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_info_22`
-- 

DROP TABLE IF EXISTS `destoon_info_22`;
CREATE TABLE `destoon_info_22` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `fee` float NOT NULL default '0',
  `keyword` varchar(255) NOT NULL default '',
  `pptword` varchar(255) NOT NULL default '',
  `hits` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL default '',
  `thumb1` varchar(255) NOT NULL default '',
  `thumb2` varchar(255) NOT NULL default '',
  `thumbs` text NOT NULL,
  `username` varchar(30) NOT NULL default '',
  `groupid` smallint(4) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `adddate` date NOT NULL default '0000-00-00',
  `totime` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `company` varchar(100) NOT NULL default '',
  `vip` smallint(2) unsigned NOT NULL default '0',
  `validated` tinyint(1) unsigned NOT NULL default '0',
  `truename` varchar(30) NOT NULL default '',
  `telephone` varchar(50) NOT NULL default '',
  `fax` varchar(50) NOT NULL default '',
  `mobile` varchar(50) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `qq` varchar(20) NOT NULL default '',
  `wx` varchar(50) NOT NULL default '',
  `ali` varchar(30) NOT NULL default '',
  `skype` varchar(30) NOT NULL default '',
  `introduce` varchar(255) NOT NULL default '',
  `n1` varchar(100) NOT NULL,
  `n2` varchar(100) NOT NULL,
  `n3` varchar(100) NOT NULL,
  `v1` varchar(100) NOT NULL,
  `v2` varchar(100) NOT NULL,
  `v3` varchar(100) NOT NULL,
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `editdate` date NOT NULL default '0000-00-00',
  `ip` varchar(50) NOT NULL default '',
  `template` varchar(30) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `listorder` smallint(4) unsigned NOT NULL default '0',
  `islink` tinyint(1) unsigned NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `filepath` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`),
  KEY `edittime` (`edittime`),
  KEY `catid` (`catid`),
  KEY `areaid` (`areaid`),
  KEY `editdate` (`editdate`,`vip`,`edittime`)
) TYPE=MyISAM COMMENT='招商';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_info_data_22`
-- 

DROP TABLE IF EXISTS `destoon_info_data_22`;
CREATE TABLE `destoon_info_data_22` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='招商内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_job_9`
-- 

DROP TABLE IF EXISTS `destoon_job_9`;
CREATE TABLE `destoon_job_9` (
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
  `department` varchar(100) NOT NULL default '',
  `total` smallint(4) unsigned NOT NULL default '0',
  `minsalary` int(10) unsigned NOT NULL default '0',
  `maxsalary` int(10) unsigned NOT NULL default '0',
  `type` tinyint(1) unsigned NOT NULL default '0',
  `gender` tinyint(1) unsigned NOT NULL default '0',
  `marriage` tinyint(1) unsigned NOT NULL default '0',
  `education` smallint(2) unsigned NOT NULL default '0',
  `experience` smallint(2) unsigned NOT NULL default '0',
  `minage` smallint(2) unsigned NOT NULL default '0',
  `maxage` smallint(2) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL,
  `apply` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `groupid` smallint(4) unsigned NOT NULL default '0',
  `company` varchar(100) NOT NULL default '',
  `vip` smallint(2) unsigned NOT NULL default '0',
  `validated` tinyint(1) unsigned NOT NULL default '0',
  `truename` varchar(30) NOT NULL default '',
  `telephone` varchar(50) NOT NULL default '',
  `mobile` varchar(50) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `qq` varchar(20) NOT NULL default '',
  `wx` varchar(50) NOT NULL default '',
  `ali` varchar(30) NOT NULL default '',
  `skype` varchar(30) NOT NULL default '',
  `sex` tinyint(1) unsigned NOT NULL default '1',
  `totime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `editdate` date NOT NULL default '0000-00-00',
  `addtime` int(10) unsigned NOT NULL default '0',
  `adddate` date NOT NULL default '0000-00-00',
  `ip` varchar(50) NOT NULL default '',
  `template` varchar(30) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `filepath` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`),
  KEY `editdate` (`editdate`,`vip`,`edittime`),
  KEY `edittime` (`edittime`),
  KEY `catid` (`catid`),
  KEY `areaid` (`areaid`)
) TYPE=MyISAM COMMENT='招聘';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_job_apply_9`
-- 

DROP TABLE IF EXISTS `destoon_job_apply_9`;
CREATE TABLE `destoon_job_apply_9` (
  `applyid` bigint(20) unsigned NOT NULL auto_increment,
  `jobid` bigint(20) unsigned NOT NULL default '0',
  `resumeid` bigint(20) unsigned NOT NULL default '0',
  `job_username` varchar(30) NOT NULL default '',
  `apply_username` varchar(30) NOT NULL default '',
  `applytime` int(10) unsigned NOT NULL default '0',
  `updatetime` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`applyid`),
  KEY `job_username` (`job_username`),
  KEY `apply_username` (`apply_username`)
) TYPE=MyISAM COMMENT='应聘工作';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_job_data_9`
-- 

DROP TABLE IF EXISTS `destoon_job_data_9`;
CREATE TABLE `destoon_job_data_9` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='招聘内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_job_resume_9`
-- 

DROP TABLE IF EXISTS `destoon_job_resume_9`;
CREATE TABLE `destoon_job_resume_9` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `fee` float NOT NULL default '0',
  `introduce` varchar(255) NOT NULL default '',
  `keyword` varchar(255) NOT NULL default '',
  `truename` varchar(30) NOT NULL default '',
  `gender` tinyint(1) unsigned NOT NULL default '0',
  `birthday` date NOT NULL default '0000-00-00',
  `age` smallint(2) unsigned NOT NULL default '0',
  `marriage` tinyint(1) unsigned NOT NULL default '0',
  `height` smallint(2) unsigned NOT NULL default '0',
  `weight` smallint(2) unsigned NOT NULL default '0',
  `education` smallint(2) unsigned NOT NULL default '0',
  `school` varchar(100) NOT NULL default '',
  `major` varchar(100) NOT NULL default '',
  `skill` varchar(255) NOT NULL default '',
  `language` varchar(255) NOT NULL default '',
  `minsalary` int(10) unsigned NOT NULL default '0',
  `maxsalary` int(10) unsigned NOT NULL default '0',
  `type` tinyint(1) unsigned NOT NULL default '0',
  `experience` smallint(2) unsigned NOT NULL default '0',
  `mobile` varchar(50) NOT NULL default '',
  `telephone` varchar(50) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `qq` varchar(20) NOT NULL default '',
  `wx` varchar(50) NOT NULL default '',
  `ali` varchar(30) NOT NULL default '',
  `skype` varchar(30) NOT NULL default '',
  `hits` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL default '',
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `template` varchar(30) NOT NULL default '0',
  `situation` tinyint(1) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `open` tinyint(1) NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`),
  KEY `edittime` (`edittime`),
  KEY `catid` (`catid`),
  KEY `areaid` (`areaid`)
) TYPE=MyISAM COMMENT='简历';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_job_resume_data_9`
-- 

DROP TABLE IF EXISTS `destoon_job_resume_data_9`;
CREATE TABLE `destoon_job_resume_data_9` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='简历内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_job_talent_9`
-- 

DROP TABLE IF EXISTS `destoon_job_talent_9`;
CREATE TABLE `destoon_job_talent_9` (
  `talentid` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `resumeid` bigint(20) unsigned NOT NULL default '0',
  `jointime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`talentid`),
  KEY `username` (`username`)
) TYPE=MyISAM COMMENT='人才库';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_keylink`
-- 

DROP TABLE IF EXISTS `destoon_keylink`;
CREATE TABLE `destoon_keylink` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `item` varchar(20) NOT NULL default '',
  `listorder` smallint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  KEY `item` (`item`)
) TYPE=MyISAM COMMENT='关联链接';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_keyword`
-- 

DROP TABLE IF EXISTS `destoon_keyword`;
CREATE TABLE `destoon_keyword` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `moduleid` smallint(6) NOT NULL default '0',
  `word` varchar(255) NOT NULL default '',
  `keyword` varchar(255) NOT NULL default '',
  `letter` varchar(255) NOT NULL default '',
  `items` int(10) unsigned NOT NULL default '0',
  `updatetime` int(10) unsigned NOT NULL default '0',
  `total_search` int(10) unsigned NOT NULL default '0',
  `month_search` int(10) unsigned NOT NULL default '0',
  `week_search` int(10) unsigned NOT NULL default '0',
  `today_search` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '3',
  PRIMARY KEY  (`itemid`),
  KEY `moduleid` (`moduleid`),
  KEY `word` (`word`),
  KEY `letter` (`letter`),
  KEY `keyword` (`keyword`)
) TYPE=MyISAM COMMENT='关键词';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_know_10`
-- 

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

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_know_answer_10`
-- 

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

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_know_data_10`
-- 

DROP TABLE IF EXISTS `destoon_know_data_10`;
CREATE TABLE `destoon_know_data_10` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` longtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='知道内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_know_expert_10`
-- 

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

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_know_vote_10`
-- 

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

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_link`
-- 

DROP TABLE IF EXISTS `destoon_link`;
CREATE TABLE `destoon_link` (
  `itemid` int(10) unsigned NOT NULL auto_increment,
  `typeid` bigint(20) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `thumb` varchar(255) NOT NULL default '',
  `introduce` varchar(255) NOT NULL default '',
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `listorder` smallint(4) NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`),
  KEY `listorder` (`listorder`)
) TYPE=MyISAM COMMENT='友情链接';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_login`
-- 

DROP TABLE IF EXISTS `destoon_login`;
CREATE TABLE `destoon_login` (
  `logid` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `passsalt` varchar(8) NOT NULL,
  `admin` tinyint(1) unsigned NOT NULL default '0',
  `loginip` varchar(50) NOT NULL default '',
  `logintime` int(10) unsigned NOT NULL default '0',
  `message` varchar(255) NOT NULL default '',
  `agent` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`logid`)
) TYPE=MyISAM COMMENT='登录日志';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_mail`
-- 

DROP TABLE IF EXISTS `destoon_mail`;
CREATE TABLE `destoon_mail` (
  `itemid` int(10) unsigned NOT NULL auto_increment,
  `typeid` bigint(20) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `sendtime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='邮件订阅';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_mail_list`
-- 

DROP TABLE IF EXISTS `destoon_mail_list`;
CREATE TABLE `destoon_mail_list` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `typeids` varchar(255) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `edittime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  UNIQUE KEY `username` (`username`)
) TYPE=MyISAM COMMENT='订阅列表';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_mail_log`
-- 

DROP TABLE IF EXISTS `destoon_mail_log`;
CREATE TABLE `destoon_mail_log` (
  `itemid` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(50) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '0',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='邮件记录';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_mall_16`
-- 

DROP TABLE IF EXISTS `destoon_mall_16`;
CREATE TABLE `destoon_mall_16` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `mycatid` bigint(20) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `elite` tinyint(1) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `fee` float NOT NULL default '0',
  `introduce` varchar(255) NOT NULL default '',
  `brand` varchar(100) NOT NULL default '',
  `price` decimal(10,2) unsigned NOT NULL default '0.00',
  `step` mediumtext NOT NULL,
  `amount` int(10) unsigned NOT NULL default '0',
  `unit` varchar(20) NOT NULL,
  `tag` varchar(100) NOT NULL default '',
  `keyword` varchar(255) NOT NULL default '',
  `pptword` varchar(255) NOT NULL default '',
  `hits` int(10) unsigned NOT NULL default '0',
  `orders` int(10) unsigned NOT NULL default '0',
  `sales` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL default '',
  `thumb1` varchar(255) NOT NULL default '',
  `thumb2` varchar(255) NOT NULL default '',
  `thumbs` text NOT NULL,
  `relate_name` varchar(100) NOT NULL,
  `relate_id` varchar(255) NOT NULL,
  `relate_title` varchar(100) NOT NULL,
  `n1` varchar(100) NOT NULL,
  `n2` varchar(100) NOT NULL,
  `n3` varchar(100) NOT NULL,
  `v1` varchar(255) NOT NULL,
  `v2` varchar(255) NOT NULL,
  `v3` varchar(255) NOT NULL,
  `express_1` int(10) unsigned NOT NULL default '0',
  `express_name_1` varchar(100) NOT NULL,
  `fee_start_1` decimal(10,2) unsigned NOT NULL,
  `fee_step_1` decimal(10,2) unsigned NOT NULL,
  `express_2` int(10) unsigned NOT NULL default '0',
  `express_name_2` varchar(100) NOT NULL,
  `fee_start_2` decimal(10,2) unsigned NOT NULL,
  `fee_step_2` decimal(10,2) unsigned NOT NULL,
  `express_3` int(10) unsigned NOT NULL default '0',
  `express_name_3` varchar(100) NOT NULL,
  `fee_start_3` decimal(10,2) unsigned NOT NULL,
  `fee_step_3` decimal(10,2) unsigned NOT NULL,
  `cod` tinyint(1) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `groupid` smallint(4) unsigned NOT NULL default '0',
  `company` varchar(100) NOT NULL default '',
  `vip` smallint(2) unsigned NOT NULL default '0',
  `validated` tinyint(1) unsigned NOT NULL default '0',
  `truename` varchar(30) NOT NULL default '',
  `telephone` varchar(50) NOT NULL default '',
  `mobile` varchar(50) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `qq` varchar(20) NOT NULL default '',
  `wx` varchar(50) NOT NULL default '',
  `ali` varchar(30) NOT NULL default '',
  `skype` varchar(30) NOT NULL default '',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `editdate` date NOT NULL default '0000-00-00',
  `addtime` int(10) unsigned NOT NULL default '0',
  `adddate` date NOT NULL default '0000-00-00',
  `ip` varchar(50) NOT NULL default '',
  `template` varchar(30) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `filepath` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`),
  KEY `editdate` (`editdate`,`vip`,`edittime`),
  KEY `catid` (`catid`),
  KEY `areaid` (`areaid`)
) TYPE=MyISAM COMMENT='商城';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_mall_comment_16`
-- 

DROP TABLE IF EXISTS `destoon_mall_comment_16`;
CREATE TABLE `destoon_mall_comment_16` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `mallid` bigint(20) unsigned NOT NULL default '0',
  `buyer` varchar(30) NOT NULL default '',
  `seller` varchar(30) NOT NULL default '',
  `buyer_star` tinyint(1) unsigned NOT NULL default '0',
  `buyer_comment` text NOT NULL,
  `buyer_ctime` int(10) unsigned NOT NULL default '0',
  `buyer_reply` text NOT NULL,
  `buyer_rtime` int(10) unsigned NOT NULL default '0',
  `seller_star` tinyint(1) unsigned NOT NULL default '0',
  `seller_comment` text NOT NULL,
  `seller_ctime` int(10) unsigned NOT NULL default '0',
  `seller_reply` text NOT NULL,
  `seller_rtime` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `itemid` (`itemid`),
  KEY `buyer` (`buyer`),
  KEY `seller` (`seller`)
) TYPE=MyISAM COMMENT='订单评论';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_mall_data_16`
-- 

DROP TABLE IF EXISTS `destoon_mall_data_16`;
CREATE TABLE `destoon_mall_data_16` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='商城内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_mall_express_16`
-- 

DROP TABLE IF EXISTS `destoon_mall_express_16`;
CREATE TABLE `destoon_mall_express_16` (
  `itemid` int(10) unsigned NOT NULL auto_increment,
  `parentid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `title` varchar(255) NOT NULL,
  `express` varchar(30) NOT NULL,
  `fee_start` decimal(10,2) unsigned NOT NULL,
  `fee_step` decimal(10,2) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  `items` int(10) unsigned NOT NULL default '0',
  `listorder` smallint(4) unsigned NOT NULL default '0',
  `note` varchar(255) NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='运费模板';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_mall_stat_16`
-- 

DROP TABLE IF EXISTS `destoon_mall_stat_16`;
CREATE TABLE `destoon_mall_stat_16` (
  `mallid` bigint(20) unsigned NOT NULL default '0',
  `seller` varchar(30) NOT NULL default '',
  `scomment` int(10) unsigned NOT NULL default '0',
  `s1` int(10) unsigned NOT NULL default '0',
  `s2` int(10) unsigned NOT NULL default '0',
  `s3` int(10) unsigned NOT NULL default '0',
  `buyer` varchar(30) NOT NULL default '',
  `bcomment` int(10) unsigned NOT NULL default '0',
  `b1` int(10) unsigned NOT NULL default '0',
  `b2` int(10) unsigned NOT NULL default '0',
  `b3` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `mallid` (`mallid`)
) TYPE=MyISAM COMMENT='评分统计';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_mall_view_16`
-- 

DROP TABLE IF EXISTS `destoon_mall_view_16`;
CREATE TABLE `destoon_mall_view_16` (
  `uid` varchar(50) NOT NULL,
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `seller` varchar(30) NOT NULL,
  `lasttime` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `uid` (`uid`),
  KEY `username` (`username`),
  KEY `lasttime` (`lasttime`)
) TYPE=MyISAM COMMENT='浏览历史';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_member`
-- 

DROP TABLE IF EXISTS `destoon_member`;
CREATE TABLE `destoon_member` (
  `userid` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `passport` varchar(30) NOT NULL default '',
  `company` varchar(100) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `passsalt` varchar(8) NOT NULL,
  `payword` varchar(32) NOT NULL default '',
  `paysalt` varchar(8) NOT NULL,
  `email` varchar(50) NOT NULL default '',
  `message` smallint(6) unsigned NOT NULL default '0',
  `chat` smallint(6) unsigned NOT NULL default '0',
  `sound` tinyint(1) unsigned NOT NULL default '1',
  `online` tinyint(1) unsigned NOT NULL default '1',
  `avatar` tinyint(1) unsigned NOT NULL default '0',
  `gender` tinyint(1) unsigned NOT NULL default '1',
  `truename` varchar(20) NOT NULL default '',
  `mobile` varchar(50) NOT NULL default '',
  `qq` varchar(20) NOT NULL default '',
  `wx` varchar(50) NOT NULL default '',
  `wxqr` varchar(255) NOT NULL default '',
  `ali` varchar(30) NOT NULL default '',
  `skype` varchar(30) NOT NULL default '',
  `department` varchar(30) NOT NULL default '',
  `career` varchar(30) NOT NULL default '',
  `admin` tinyint(1) unsigned NOT NULL default '0',
  `role` varchar(255) NOT NULL default '',
  `aid` int(10) unsigned NOT NULL default '0',
  `groupid` smallint(4) unsigned NOT NULL default '4',
  `regid` smallint(4) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `sms` int(10) NOT NULL default '0',
  `credit` int(10) NOT NULL default '0',
  `money` decimal(10,2) NOT NULL default '0.00',
  `deposit` decimal(10,2) unsigned NOT NULL default '0.00',
  `edittime` int(10) unsigned NOT NULL default '0',
  `regip` varchar(50) NOT NULL default '',
  `regtime` int(10) unsigned NOT NULL default '0',
  `loginip` varchar(50) NOT NULL default '',
  `logintime` int(10) unsigned NOT NULL default '0',
  `logintimes` int(10) unsigned NOT NULL default '1',
  `send` tinyint(1) unsigned NOT NULL default '1',
  `vemail` tinyint(1) unsigned NOT NULL default '0',
  `vmobile` tinyint(1) unsigned NOT NULL default '0',
  `vtruename` tinyint(1) unsigned NOT NULL default '0',
  `vbank` tinyint(1) unsigned NOT NULL default '0',
  `vcompany` tinyint(1) unsigned NOT NULL default '0',
  `vtrade` tinyint(1) unsigned NOT NULL default '0',
  `trade` varchar(50) NOT NULL default '',
  `support` varchar(50) NOT NULL default '',
  `inviter` varchar(30) NOT NULL default '',
  `note` text NOT NULL,
  PRIMARY KEY  (`userid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `passport` (`passport`),
  KEY `groupid` (`groupid`)
) TYPE=MyISAM COMMENT='会员';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_member_check`
-- 

DROP TABLE IF EXISTS `destoon_member_check`;
CREATE TABLE `destoon_member_check` (
  `userid` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `content` mediumtext NOT NULL,
  `addtime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`userid`),
  UNIQUE KEY `username` (`username`)
) TYPE=MyISAM COMMENT='会员资料审核';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_member_group`
-- 

DROP TABLE IF EXISTS `destoon_member_group`;
CREATE TABLE `destoon_member_group` (
  `groupid` smallint(4) unsigned NOT NULL auto_increment,
  `groupname` varchar(50) NOT NULL default '',
  `vip` smallint(2) unsigned NOT NULL default '0',
  `listorder` smallint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`groupid`)
) TYPE=MyISAM COMMENT='会员组';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_member_misc`
-- 

DROP TABLE IF EXISTS `destoon_member_misc`;
CREATE TABLE `destoon_member_misc` (
  `userid` bigint(20) unsigned NOT NULL,
  `username` varchar(30) NOT NULL default '',
  `bank` varchar(30) NOT NULL default '',
  `banktype` tinyint(1) unsigned NOT NULL default '0',
  `branch` varchar(100) NOT NULL,
  `account` varchar(30) NOT NULL default '',
  `reply` text NOT NULL,
  `black` text NOT NULL,
  `send` tinyint(1) unsigned NOT NULL default '1',
  UNIQUE KEY `userid` (`userid`),
  UNIQUE KEY `username` (`username`)
) TYPE=MyISAM COMMENT='会员杂项';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_message`
-- 

DROP TABLE IF EXISTS `destoon_message`;
CREATE TABLE `destoon_message` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `typeid` tinyint(1) unsigned NOT NULL default '0',
  `content` text NOT NULL,
  `fromuser` varchar(30) NOT NULL default '',
  `touser` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `isread` tinyint(1) unsigned NOT NULL default '0',
  `issend` tinyint(1) unsigned NOT NULL default '0',
  `feedback` tinyint(1) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '0',
  `groupids` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `touser` (`touser`)
) TYPE=MyISAM COMMENT='站内信件';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_module`
-- 

DROP TABLE IF EXISTS `destoon_module`;
CREATE TABLE `destoon_module` (
  `moduleid` smallint(6) unsigned NOT NULL auto_increment,
  `module` varchar(20) NOT NULL default '',
  `name` varchar(20) NOT NULL default '',
  `moduledir` varchar(20) NOT NULL default '',
  `domain` varchar(255) NOT NULL default '',
  `mobile` varchar(255) NOT NULL default '',
  `linkurl` varchar(255) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `listorder` smallint(4) unsigned NOT NULL default '0',
  `islink` tinyint(1) unsigned NOT NULL default '0',
  `ismenu` tinyint(1) unsigned NOT NULL default '0',
  `isblank` tinyint(1) unsigned NOT NULL default '0',
  `logo` tinyint(1) unsigned NOT NULL default '0',
  `disabled` tinyint(1) unsigned NOT NULL default '0',
  `installtime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`moduleid`)
) TYPE=MyISAM COMMENT='模型';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_news`
-- 

DROP TABLE IF EXISTS `destoon_news`;
CREATE TABLE `destoon_news` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `typeid` bigint(20) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `hits` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL,
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='公司新闻';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_news_data`
-- 

DROP TABLE IF EXISTS `destoon_news_data`;
CREATE TABLE `destoon_news_data` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='公司新闻内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_oauth`
-- 

DROP TABLE IF EXISTS `destoon_oauth`;
CREATE TABLE `destoon_oauth` (
  `itemid` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `site` varchar(30) NOT NULL default '',
  `openid` varchar(255) NOT NULL default '',
  `nickname` varchar(255) NOT NULL default '',
  `avatar` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `logintimes` int(10) unsigned NOT NULL default '0',
  `logintime` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`),
  KEY `site` (`site`,`openid`)
) TYPE=MyISAM COMMENT='一键登录';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_online`
-- 

DROP TABLE IF EXISTS `destoon_online`;
CREATE TABLE `destoon_online` (
  `userid` bigint(20) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  `moduleid` int(10) unsigned NOT NULL default '0',
  `online` tinyint(1) unsigned NOT NULL default '1',
  `lasttime` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `userid` (`userid`)
) TYPE=HEAP DEFAULT CHARSET=utf8 COMMENT='在线会员';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_order`
-- 

DROP TABLE IF EXISTS `destoon_order`;
CREATE TABLE `destoon_order` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `mid` smallint(6) unsigned NOT NULL default '16',
  `mallid` bigint(20) unsigned NOT NULL default '0',
  `pid` bigint(20) unsigned NOT NULL default '0',
  `cid` bigint(20) unsigned NOT NULL default '0',
  `buyer` varchar(30) NOT NULL default '',
  `seller` varchar(30) NOT NULL default '',
  `title` varchar(100) NOT NULL default '',
  `thumb` varchar(255) NOT NULL default '',
  `price` decimal(10,2) unsigned NOT NULL default '0.00',
  `number` int(10) unsigned NOT NULL default '0',
  `amount` decimal(10,2) unsigned NOT NULL default '0.00',
  `discount` decimal(10,2) unsigned NOT NULL default '0.00',
  `fee` decimal(10,2) NOT NULL default '0.00',
  `fee_name` varchar(30) NOT NULL default '',
  `buyer_name` varchar(30) NOT NULL default '',
  `buyer_address` varchar(255) NOT NULL default '',
  `buyer_postcode` varchar(10) NOT NULL default '',
  `buyer_mobile` varchar(30) NOT NULL default '',
  `buyer_star` tinyint(1) unsigned NOT NULL default '0',
  `seller_star` tinyint(1) unsigned NOT NULL default '0',
  `send_type` varchar(50) NOT NULL default '',
  `send_no` varchar(50) NOT NULL default '',
  `send_status` tinyint(1) unsigned NOT NULL default '0',
  `send_time` varchar(20) NOT NULL default '',
  `send_days` int(10) unsigned NOT NULL default '0',
  `cod` tinyint(1) unsigned NOT NULL default '0',
  `trade_no` varchar(50) NOT NULL default '',
  `add_time` smallint(6) NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `updatetime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `buyer_reason` mediumtext NOT NULL,
  `refund_reason` mediumtext NOT NULL,
  `note` varchar(255) NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  KEY `buyer` (`buyer`),
  KEY `seller` (`seller`),
  KEY `pid` (`pid`)
) TYPE=MyISAM COMMENT='订单';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_page`
-- 

DROP TABLE IF EXISTS `destoon_page`;
CREATE TABLE `destoon_page` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `hits` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `listorder` smallint(4) unsigned NOT NULL default '0',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='公司单页';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_page_data`
-- 

DROP TABLE IF EXISTS `destoon_page_data`;
CREATE TABLE `destoon_page_data` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='公司单页内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_photo_12`
-- 

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

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_photo_data_12`
-- 

DROP TABLE IF EXISTS `destoon_photo_data_12`;
CREATE TABLE `destoon_photo_data_12` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` longtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='图库内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_photo_item_12`
-- 

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

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_poll`
-- 

DROP TABLE IF EXISTS `destoon_poll`;
CREATE TABLE `destoon_poll` (
  `itemid` int(10) unsigned NOT NULL auto_increment,
  `typeid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `content` mediumtext NOT NULL,
  `groupid` varchar(255) NOT NULL,
  `verify` tinyint(1) unsigned NOT NULL default '0',
  `thumb_width` smallint(6) unsigned NOT NULL default '0',
  `thumb_height` smallint(6) unsigned NOT NULL default '0',
  `poll_max` smallint(6) unsigned NOT NULL default '0',
  `poll_page` smallint(6) unsigned NOT NULL default '0',
  `poll_cols` smallint(6) unsigned NOT NULL default '0',
  `poll_order` smallint(6) unsigned NOT NULL default '0',
  `polls` int(10) unsigned NOT NULL default '0',
  `items` int(10) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `fromtime` int(10) unsigned NOT NULL default '0',
  `totime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `template_poll` varchar(30) NOT NULL default '',
  `template` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM COMMENT='票选';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_poll_item`
-- 

DROP TABLE IF EXISTS `destoon_poll_item`;
CREATE TABLE `destoon_poll_item` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `pollid` bigint(20) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `introduce` varchar(255) NOT NULL default '',
  `thumb` varchar(255) NOT NULL default '',
  `linkurl` varchar(255) NOT NULL default '',
  `polls` int(10) unsigned NOT NULL default '0',
  `listorder` smallint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  KEY `pollid` (`pollid`)
) TYPE=MyISAM COMMENT='票选选项';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_poll_record`
-- 

DROP TABLE IF EXISTS `destoon_poll_record`;
CREATE TABLE `destoon_poll_record` (
  `rid` bigint(20) unsigned NOT NULL auto_increment,
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `pollid` bigint(20) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  `polltime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`rid`)
) TYPE=MyISAM COMMENT='票选记录';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_question`
-- 

DROP TABLE IF EXISTS `destoon_question`;
CREATE TABLE `destoon_question` (
  `qid` int(10) unsigned NOT NULL auto_increment,
  `question` varchar(255) NOT NULL default '',
  `answer` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`qid`)
) TYPE=MyISAM COMMENT='验证问题';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_quote_7`
-- 

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

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_quote_data_7`
-- 

DROP TABLE IF EXISTS `destoon_quote_data_7`;
CREATE TABLE `destoon_quote_data_7` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` longtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='行情内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_quote_price_7`
-- 

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

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_quote_product_7`
-- 

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

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_sell_5`
-- 

DROP TABLE IF EXISTS `destoon_sell_5`;
CREATE TABLE `destoon_sell_5` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `mycatid` bigint(20) unsigned NOT NULL default '0',
  `typeid` smallint(2) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `elite` tinyint(1) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `fee` float NOT NULL default '0',
  `introduce` varchar(255) NOT NULL default '',
  `n1` varchar(100) NOT NULL,
  `n2` varchar(100) NOT NULL,
  `n3` varchar(100) NOT NULL,
  `v1` varchar(100) NOT NULL,
  `v2` varchar(100) NOT NULL,
  `v3` varchar(100) NOT NULL,
  `brand` varchar(100) NOT NULL default '',
  `unit` varchar(10) NOT NULL default '',
  `price` decimal(10,2) unsigned NOT NULL default '0.00',
  `minamount` float unsigned NOT NULL default '0',
  `amount` float unsigned NOT NULL default '0',
  `days` smallint(3) unsigned NOT NULL default '0',
  `tag` varchar(100) NOT NULL default '',
  `keyword` varchar(255) NOT NULL default '',
  `pptword` varchar(255) NOT NULL default '',
  `hits` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL default '',
  `thumb1` varchar(255) NOT NULL default '',
  `thumb2` varchar(255) NOT NULL default '',
  `thumbs` text NOT NULL,
  `username` varchar(30) NOT NULL default '',
  `groupid` smallint(4) unsigned NOT NULL default '0',
  `company` varchar(100) NOT NULL default '',
  `vip` smallint(2) unsigned NOT NULL default '0',
  `validated` tinyint(1) unsigned NOT NULL default '0',
  `truename` varchar(30) NOT NULL default '',
  `telephone` varchar(50) NOT NULL default '',
  `mobile` varchar(50) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `qq` varchar(20) NOT NULL default '',
  `wx` varchar(50) NOT NULL default '',
  `ali` varchar(30) NOT NULL default '',
  `skype` varchar(30) NOT NULL default '',
  `totime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `editdate` date NOT NULL default '0000-00-00',
  `addtime` int(10) unsigned NOT NULL default '0',
  `adddate` date NOT NULL default '0000-00-00',
  `ip` varchar(50) NOT NULL default '',
  `template` varchar(30) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `filepath` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`),
  KEY `editdate` (`editdate`,`vip`,`edittime`),
  KEY `edittime` (`edittime`),
  KEY `catid` (`catid`),
  KEY `mycatid` (`mycatid`),
  KEY `areaid` (`areaid`)
) TYPE=MyISAM COMMENT='供应';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_sell_data_5`
-- 

DROP TABLE IF EXISTS `destoon_sell_data_5`;
CREATE TABLE `destoon_sell_data_5` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='供应内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_sell_search_5`
-- 

DROP TABLE IF EXISTS `destoon_sell_search_5`;
CREATE TABLE `destoon_sell_search_5` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `status` tinyint(1) NOT NULL default '0',
  `sorttime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`),
  KEY `catid` (`catid`)
) TYPE=MyISAM COMMENT='供应搜索';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_session`
-- 

DROP TABLE IF EXISTS `destoon_session`;
CREATE TABLE `destoon_session` (
  `sessionid` varchar(32) NOT NULL default '',
  `data` text NOT NULL,
  `lastvisit` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `sessionid` (`sessionid`)
) TYPE=MyISAM COMMENT='SESSION';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_setting`
-- 

DROP TABLE IF EXISTS `destoon_setting`;
CREATE TABLE `destoon_setting` (
  `item` varchar(30) NOT NULL default '',
  `item_key` varchar(100) NOT NULL default '',
  `item_value` text NOT NULL,
  KEY `item` (`item`)
) TYPE=MyISAM COMMENT='网站设置';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_sms`
-- 

DROP TABLE IF EXISTS `destoon_sms`;
CREATE TABLE `destoon_sms` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `mobile` varchar(30) NOT NULL default '',
  `message` text NOT NULL,
  `word` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `sendtime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL,
  `code` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='短信记录';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_special_11`
-- 

DROP TABLE IF EXISTS `destoon_special_11`;
CREATE TABLE `destoon_special_11` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `introduce` varchar(255) NOT NULL default '',
  `tag` varchar(100) NOT NULL default '',
  `keyword` varchar(255) NOT NULL default '',
  `pptword` varchar(255) NOT NULL default '',
  `items` int(10) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL default '',
  `banner` varchar(255) NOT NULL default '',
  `cfg_photo` smallint(4) unsigned NOT NULL default '0',
  `cfg_video` smallint(4) unsigned NOT NULL default '0',
  `cfg_type` smallint(4) unsigned NOT NULL default '0',
  `seo_title` varchar(255) NOT NULL default '',
  `seo_keywords` varchar(255) NOT NULL default '',
  `seo_description` varchar(255) NOT NULL default '',
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `template` varchar(30) NOT NULL default '0',
  `template_type` varchar(30) NOT NULL,
  `status` tinyint(1) NOT NULL default '0',
  `islink` tinyint(1) unsigned NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `filepath` varchar(255) NOT NULL default '',
  `domain` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `addtime` (`addtime`),
  KEY `catid` (`catid`)
) TYPE=MyISAM COMMENT='专题';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_special_data_11`
-- 

DROP TABLE IF EXISTS `destoon_special_data_11`;
CREATE TABLE `destoon_special_data_11` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` longtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='专题内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_special_item_11`
-- 

DROP TABLE IF EXISTS `destoon_special_item_11`;
CREATE TABLE `destoon_special_item_11` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `specialid` bigint(20) unsigned NOT NULL default '0',
  `typeid` bigint(20) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `introduce` varchar(255) NOT NULL default '',
  `thumb` varchar(255) NOT NULL default '',
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `linkurl` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `addtime` (`addtime`),
  KEY `specialid` (`specialid`)
) TYPE=MyISAM COMMENT='专题信息';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_sphinx`
-- 

DROP TABLE IF EXISTS `destoon_sphinx`;
CREATE TABLE `destoon_sphinx` (
  `moduleid` int(10) unsigned NOT NULL default '0',
  `maxid` bigint(20) unsigned NOT NULL default '0',
  UNIQUE KEY `moduleid` (`moduleid`)
) TYPE=MyISAM COMMENT='Sphinx';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_spread`
-- 

DROP TABLE IF EXISTS `destoon_spread`;
CREATE TABLE `destoon_spread` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `mid` smallint(6) unsigned NOT NULL default '0',
  `tid` bigint(20) unsigned NOT NULL default '0',
  `word` varchar(50) NOT NULL default '',
  `price` float NOT NULL default '0',
  `currency` varchar(30) NOT NULL default '',
  `company` varchar(100) NOT NULL default '',
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `fromtime` int(10) unsigned NOT NULL default '0',
  `totime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='排名推广';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_spread_price`
-- 

DROP TABLE IF EXISTS `destoon_spread_price`;
CREATE TABLE `destoon_spread_price` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `mid` smallint(6) unsigned NOT NULL default '0',
  `word` varchar(50) NOT NULL default '',
  `price` float NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='排名起价';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_style`
-- 

DROP TABLE IF EXISTS `destoon_style`;
CREATE TABLE `destoon_style` (
  `itemid` int(10) unsigned NOT NULL auto_increment,
  `typeid` bigint(20) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `skin` varchar(50) NOT NULL default '',
  `template` varchar(50) NOT NULL default '',
  `author` varchar(30) NOT NULL default '',
  `groupid` varchar(30) NOT NULL default '',
  `fee` float NOT NULL default '0',
  `currency` varchar(20) NOT NULL default '',
  `money` float NOT NULL default '0',
  `credit` int(10) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `listorder` smallint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='公司主页模板';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_type`
-- 

DROP TABLE IF EXISTS `destoon_type`;
CREATE TABLE `destoon_type` (
  `typeid` bigint(20) unsigned NOT NULL auto_increment,
  `parentid` bigint(20) unsigned NOT NULL default '0',
  `listorder` smallint(4) NOT NULL default '0',
  `typename` varchar(255) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `item` varchar(20) NOT NULL default '',
  `cache` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`typeid`),
  KEY `listorder` (`listorder`),
  KEY `item` (`item`)
) TYPE=MyISAM COMMENT='分类';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_upgrade`
-- 

DROP TABLE IF EXISTS `destoon_upgrade`;
CREATE TABLE `destoon_upgrade` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `userid` bigint(20) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `gid` smallint(4) unsigned NOT NULL default '0',
  `groupid` smallint(4) unsigned NOT NULL default '0',
  `amount` float NOT NULL default '0',
  `message` tinyint(1) unsigned NOT NULL default '0',
  `company` varchar(100) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `reason` text NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='会员升级';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_upload_0`
-- 

DROP TABLE IF EXISTS `destoon_upload_0`;
CREATE TABLE `destoon_upload_0` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `item` varchar(32) NOT NULL default '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL default '0',
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `fileurl` varchar(255) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `fileext` varchar(10) NOT NULL default '',
  `upfrom` varchar(10) NOT NULL default '',
  `width` int(10) unsigned NOT NULL default '0',
  `height` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`pid`),
  KEY `item` (`item`)
) TYPE=MyISAM COMMENT='上传记录0';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_upload_1`
-- 

DROP TABLE IF EXISTS `destoon_upload_1`;
CREATE TABLE `destoon_upload_1` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `item` varchar(32) NOT NULL default '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL default '0',
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `fileurl` varchar(255) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `fileext` varchar(10) NOT NULL default '',
  `upfrom` varchar(10) NOT NULL default '',
  `width` int(10) unsigned NOT NULL default '0',
  `height` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`pid`),
  KEY `item` (`item`)
) TYPE=MyISAM COMMENT='上传记录1';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_upload_2`
-- 

DROP TABLE IF EXISTS `destoon_upload_2`;
CREATE TABLE `destoon_upload_2` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `item` varchar(32) NOT NULL default '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL default '0',
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `fileurl` varchar(255) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `fileext` varchar(10) NOT NULL default '',
  `upfrom` varchar(10) NOT NULL default '',
  `width` int(10) unsigned NOT NULL default '0',
  `height` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`pid`),
  KEY `item` (`item`)
) TYPE=MyISAM COMMENT='上传记录2';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_upload_3`
-- 

DROP TABLE IF EXISTS `destoon_upload_3`;
CREATE TABLE `destoon_upload_3` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `item` varchar(32) NOT NULL default '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL default '0',
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `fileurl` varchar(255) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `fileext` varchar(10) NOT NULL default '',
  `upfrom` varchar(10) NOT NULL default '',
  `width` int(10) unsigned NOT NULL default '0',
  `height` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`pid`),
  KEY `item` (`item`)
) TYPE=MyISAM COMMENT='上传记录3';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_upload_4`
-- 

DROP TABLE IF EXISTS `destoon_upload_4`;
CREATE TABLE `destoon_upload_4` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `item` varchar(32) NOT NULL default '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL default '0',
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `fileurl` varchar(255) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `fileext` varchar(10) NOT NULL default '',
  `upfrom` varchar(10) NOT NULL default '',
  `width` int(10) unsigned NOT NULL default '0',
  `height` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`pid`),
  KEY `item` (`item`)
) TYPE=MyISAM COMMENT='上传记录4';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_upload_5`
-- 

DROP TABLE IF EXISTS `destoon_upload_5`;
CREATE TABLE `destoon_upload_5` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `item` varchar(32) NOT NULL default '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL default '0',
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `fileurl` varchar(255) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `fileext` varchar(10) NOT NULL default '',
  `upfrom` varchar(10) NOT NULL default '',
  `width` int(10) unsigned NOT NULL default '0',
  `height` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`pid`),
  KEY `item` (`item`)
) TYPE=MyISAM COMMENT='上传记录5';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_upload_6`
-- 

DROP TABLE IF EXISTS `destoon_upload_6`;
CREATE TABLE `destoon_upload_6` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `item` varchar(32) NOT NULL default '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL default '0',
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `fileurl` varchar(255) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `fileext` varchar(10) NOT NULL default '',
  `upfrom` varchar(10) NOT NULL default '',
  `width` int(10) unsigned NOT NULL default '0',
  `height` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`pid`),
  KEY `item` (`item`)
) TYPE=MyISAM COMMENT='上传记录6';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_upload_7`
-- 

DROP TABLE IF EXISTS `destoon_upload_7`;
CREATE TABLE `destoon_upload_7` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `item` varchar(32) NOT NULL default '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL default '0',
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `fileurl` varchar(255) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `fileext` varchar(10) NOT NULL default '',
  `upfrom` varchar(10) NOT NULL default '',
  `width` int(10) unsigned NOT NULL default '0',
  `height` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`pid`),
  KEY `item` (`item`)
) TYPE=MyISAM COMMENT='上传记录7';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_upload_8`
-- 

DROP TABLE IF EXISTS `destoon_upload_8`;
CREATE TABLE `destoon_upload_8` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `item` varchar(32) NOT NULL default '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL default '0',
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `fileurl` varchar(255) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `fileext` varchar(10) NOT NULL default '',
  `upfrom` varchar(10) NOT NULL default '',
  `width` int(10) unsigned NOT NULL default '0',
  `height` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`pid`),
  KEY `item` (`item`)
) TYPE=MyISAM COMMENT='上传记录8';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_upload_9`
-- 

DROP TABLE IF EXISTS `destoon_upload_9`;
CREATE TABLE `destoon_upload_9` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `item` varchar(32) NOT NULL default '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL default '0',
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `fileurl` varchar(255) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `fileext` varchar(10) NOT NULL default '',
  `upfrom` varchar(10) NOT NULL default '',
  `width` int(10) unsigned NOT NULL default '0',
  `height` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`pid`),
  KEY `item` (`item`)
) TYPE=MyISAM COMMENT='上传记录9';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_validate`
-- 

DROP TABLE IF EXISTS `destoon_validate`;
CREATE TABLE `destoon_validate` (
  `itemid` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `type` varchar(30) NOT NULL default '',
  `thumb` varchar(255) NOT NULL default '',
  `thumb1` varchar(255) NOT NULL default '',
  `thumb2` varchar(255) NOT NULL default '',
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='资料认证';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_video_14`
-- 

DROP TABLE IF EXISTS `destoon_video_14`;
CREATE TABLE `destoon_video_14` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `fee` float NOT NULL default '0',
  `tag` varchar(255) NOT NULL default '',
  `album` varchar(100) NOT NULL,
  `keyword` varchar(255) NOT NULL default '',
  `pptword` varchar(255) NOT NULL default '',
  `hits` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL default '',
  `video` varchar(255) NOT NULL default '',
  `mobile` tinyint(1) unsigned NOT NULL default '0',
  `width` smallint(4) unsigned NOT NULL default '0',
  `height` smallint(4) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `introduce` varchar(255) NOT NULL default '',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `template` varchar(30) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `filepath` varchar(255) NOT NULL default '',
  `note` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemid`),
  KEY `username` (`username`),
  KEY `addtime` (`addtime`),
  KEY `catid` (`catid`),
  KEY `album` (`album`)
) TYPE=MyISAM COMMENT='视频';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_video_data_14`
-- 

DROP TABLE IF EXISTS `destoon_video_data_14`;
CREATE TABLE `destoon_video_data_14` (
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='视频内容';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_vote`
-- 

DROP TABLE IF EXISTS `destoon_vote`;
CREATE TABLE `destoon_vote` (
  `itemid` int(10) unsigned NOT NULL auto_increment,
  `typeid` int(10) unsigned NOT NULL default '0',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `content` mediumtext NOT NULL,
  `groupid` varchar(255) NOT NULL,
  `verify` tinyint(1) unsigned NOT NULL default '0',
  `choose` tinyint(1) unsigned NOT NULL default '0',
  `vote_min` smallint(2) unsigned NOT NULL default '0',
  `vote_max` smallint(2) unsigned NOT NULL default '0',
  `votes` int(10) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `fromtime` int(10) unsigned NOT NULL default '0',
  `totime` int(10) unsigned NOT NULL default '0',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `linkto` varchar(255) NOT NULL default '',
  `linkurl` varchar(255) NOT NULL default '',
  `template_vote` varchar(30) NOT NULL default '',
  `template` varchar(30) NOT NULL default '',
  `s1` varchar(255) NOT NULL default '',
  `s2` varchar(255) NOT NULL default '',
  `s3` varchar(255) NOT NULL default '',
  `s4` varchar(255) NOT NULL default '',
  `s5` varchar(255) NOT NULL default '',
  `s6` varchar(255) NOT NULL default '',
  `s7` varchar(255) NOT NULL default '',
  `s8` varchar(255) NOT NULL default '',
  `s9` varchar(255) NOT NULL default '',
  `s10` varchar(255) NOT NULL default '',
  `v1` int(10) unsigned NOT NULL default '0',
  `v2` int(10) unsigned NOT NULL default '0',
  `v3` int(10) unsigned NOT NULL default '0',
  `v4` int(10) unsigned NOT NULL default '0',
  `v5` int(10) unsigned NOT NULL default '0',
  `v6` int(10) unsigned NOT NULL default '0',
  `v7` int(10) unsigned NOT NULL default '0',
  `v8` int(10) unsigned NOT NULL default '0',
  `v9` int(10) unsigned NOT NULL default '0',
  `v10` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='投票';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_vote_record`
-- 

DROP TABLE IF EXISTS `destoon_vote_record`;
CREATE TABLE `destoon_vote_record` (
  `rid` bigint(20) unsigned NOT NULL auto_increment,
  `itemid` bigint(20) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  `votetime` int(10) unsigned NOT NULL default '0',
  `votes` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`rid`),
  KEY `itemid` (`itemid`)
) TYPE=MyISAM COMMENT='投票记录';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_webpage`
-- 

DROP TABLE IF EXISTS `destoon_webpage`;
CREATE TABLE `destoon_webpage` (
  `itemid` int(10) unsigned NOT NULL auto_increment,
  `item` varchar(30) NOT NULL default '',
  `areaid` int(10) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `style` varchar(50) NOT NULL default '',
  `content` mediumtext NOT NULL,
  `seo_title` varchar(255) NOT NULL default '',
  `seo_keywords` varchar(255) NOT NULL default '',
  `seo_description` varchar(255) NOT NULL default '',
  `editor` varchar(30) NOT NULL default '',
  `edittime` int(10) unsigned NOT NULL default '0',
  `listorder` smallint(4) NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  `islink` tinyint(1) unsigned NOT NULL default '0',
  `linkurl` varchar(255) NOT NULL default '',
  `domain` varchar(255) NOT NULL default '',
  `template` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`itemid`)
) TYPE=MyISAM COMMENT='单网页';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_weixin_auto`
-- 

DROP TABLE IF EXISTS `destoon_weixin_auto`;
CREATE TABLE `destoon_weixin_auto` (
  `itemid` int(10) unsigned NOT NULL auto_increment,
  `keyword` varchar(255) NOT NULL,
  `reply` text NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `keyword` (`keyword`)
) TYPE=MyISAM COMMENT='微信回复';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_weixin_bind`
-- 

DROP TABLE IF EXISTS `destoon_weixin_bind`;
CREATE TABLE `destoon_weixin_bind` (
  `username` varchar(30) NOT NULL default '',
  `sid` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `username` (`username`)
) TYPE=MyISAM COMMENT='微信扫码绑定';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_weixin_chat`
-- 

DROP TABLE IF EXISTS `destoon_weixin_chat`;
CREATE TABLE `destoon_weixin_chat` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `editor` varchar(30) NOT NULL,
  `openid` varchar(255) NOT NULL default '',
  `type` varchar(20) NOT NULL,
  `event` tinyint(1) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `misc` mediumtext NOT NULL,
  PRIMARY KEY  (`itemid`),
  KEY `openid` (`openid`),
  KEY `addtime` (`addtime`),
  KEY `event` (`event`)
) TYPE=MyISAM COMMENT='微信消息';

-- --------------------------------------------------------

-- 
-- 表的结构 `destoon_weixin_user`
-- 

DROP TABLE IF EXISTS `destoon_weixin_user`;
CREATE TABLE `destoon_weixin_user` (
  `itemid` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `openid` varchar(255) NOT NULL default '',
  `nickname` varchar(255) NOT NULL default '',
  `sex` tinyint(1) unsigned NOT NULL default '0',
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `language` varchar(100) NOT NULL,
  `headimgurl` varchar(255) NOT NULL,
  `edittime` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `visittime` int(10) unsigned NOT NULL default '0',
  `credittime` int(10) unsigned NOT NULL default '0',
  `subscribe` tinyint(1) unsigned NOT NULL default '1',
  `push` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`itemid`),
  UNIQUE KEY `openid` (`openid`),
  KEY `username` (`username`)
) TYPE=MyISAM COMMENT='微信用户';
