--
-- 表的结构 `blue_ad`
--
DROP TABLE IF EXISTS `blue_ad`;
CREATE TABLE `blue_ad` (
  `ad_id` int(10) unsigned NOT NULL auto_increment,
  `ad_name` varchar(40) NOT NULL,
  `time_set` tinyint(1) NOT NULL default '0',
  `start_time` int(11) NOT NULL default '0',
  `end_time` int(11) NOT NULL default '0',
  `content` text NOT NULL,
  `exp_content` text NOT NULL,
  PRIMARY KEY  (`ad_id`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_admin`
--
DROP TABLE IF EXISTS `blue_admin`;
CREATE TABLE `blue_admin` (
  `admin_id` smallint(5) unsigned NOT NULL auto_increment,
  `admin_name` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `purview` varchar(255) NOT NULL,
  `add_time` int(10) NOT NULL,
  `last_login_time` int(10) NOT NULL,
  `last_login_ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`admin_id`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_admin_log`
--
DROP TABLE IF EXISTS `blue_admin_log`;
CREATE TABLE `blue_admin_log` (
  `log_id` int(10) unsigned NOT NULL auto_increment,
  `admin_name` varchar(20) NOT NULL,
  `add_time` int(10) NOT NULL,
  `log_value` varchar(255) NOT NULL,
  PRIMARY KEY  (`log_id`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_ad_phone`
--
DROP TABLE IF EXISTS `blue_ad_phone`;
CREATE TABLE `blue_ad_phone` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `content` varchar(40) NOT NULL,
  `title` varchar(100) NOT NULL,
  `color` varchar(10) NOT NULL,
  `start_time` int(10) unsigned NOT NULL default '0',
  `end_time` int(10) unsigned NOT NULL default '0',
  `is_show` tinyint(1) unsigned NOT NULL default '0',
  `show_order` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_ann`
--
DROP TABLE IF EXISTS `blue_ann`;
CREATE TABLE `blue_ann` (
  `ann_id` int(10) unsigned NOT NULL auto_increment,
  `cid` smallint(5) NOT NULL,
  `author` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `color` varchar(7) NOT NULL,
  `content` varchar(255) NOT NULL,
  `add_time` int(10) NOT NULL default '0',
  `click` int(10) NOT NULL default '0',
  PRIMARY KEY  (`ann_id`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_ann_cat
--
DROP TABLE IF EXISTS `blue_ann_cat`;
CREATE TABLE `blue_ann_cat` (
  `cid` smallint(5) unsigned NOT NULL auto_increment,
  `cat_name` varchar(20) NOT NULL,
  `show_order` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cid`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_arc_cat`
--
DROP TABLE IF EXISTS `blue_arc_cat`;
CREATE TABLE `blue_arc_cat` (
  `cat_id` int(10) unsigned NOT NULL auto_increment,
  `cat_name` varchar(100) NOT NULL,
  `parent_id` int(10) NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `cat_indent` tinyint(1) NOT NULL default '1',
  `is_havechild` tinyint(1) NOT NULL default '0',
  `show_order` tinyint(3) NOT NULL,
  PRIMARY KEY  (`cat_id`),
  KEY `parent_id` (`parent_id`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_area`
--
DROP TABLE IF EXISTS `blue_area`;
CREATE TABLE `blue_area` (
  `area_id` int(10) unsigned NOT NULL auto_increment,
  `area_name` varchar(20) NOT NULL,
  `parentid` int(10) NOT NULL,
  `area_indent` int(1) NOT NULL default '0',
  `ishavechild` tinyint(1) NOT NULL default '0',
  `show_order` smallint(5) NOT NULL default '0',
  PRIMARY KEY  (`area_id`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_article`
--
DROP TABLE IF EXISTS `blue_article`;
CREATE TABLE `blue_article` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `color` varchar(7) NOT NULL,
  `author` varchar(20) NOT NULL,
  `source` varchar(20) NOT NULL,
  `pub_date` int(10) NOT NULL default '0',
  `lit_pic` varchar(100) NOT NULL,
  `descript` varchar(250) NOT NULL,
  `content` mediumtext NOT NULL,
  `click` int(10) NOT NULL default '0',
  `comment` int(10) NOT NULL default '0',
  `is_recommend` tinyint(1) NOT NULL default '0',
  `is_check` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_attachment`
--
DROP TABLE IF EXISTS `blue_attachment`;
CREATE TABLE `blue_attachment` (
  `att_id` smallint(6) unsigned NOT NULL auto_increment,
  `modelid` smallint(6) NOT NULL,
  `att_name` varchar(40) NOT NULL,
  `att_type` tinyint(1) NOT NULL default '1',
  `is_required` tinyint(1) NOT NULL default '1',
  `unit` varchar(20) NOT NULL,
  `att_val` varchar(255) NOT NULL,
  `show_order` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`att_id`),
  KEY `postid` (`modelid`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_buy_record`
--
DROP TABLE IF EXISTS `blue_buy_record`;
CREATE TABLE `blue_buy_record` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) NOT NULL,
  `aid` int(10) NOT NULL,
  `pid` smallint(5) NOT NULL,
  `exp` smallint(5) NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_card_order`
--
DROP TABLE IF EXISTS `blue_card_order`;
CREATE TABLE `blue_card_order` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `name` varchar(40) NOT NULL,
  `value` int(10) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `time` int(10) NOT NULL,
  `is_pay` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_card_type`
--
DROP TABLE IF EXISTS `blue_card_type`;
CREATE TABLE `blue_card_type` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) character set gbk NOT NULL,
  `value` int(10) NOT NULL,
  `price` int(10) NOT NULL,
  `is_close` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_category`
--
DROP TABLE IF EXISTS `blue_category`;
CREATE TABLE `blue_category` (
  `cat_id` int(10) unsigned NOT NULL auto_increment,
  `cat_name` varchar(100) NOT NULL,
  `englishname` varchar(100) NOT NULL,
  `title_color` varchar(20) NOT NULL,
  `parentid` int(10) NOT NULL default '0',
  `model` smallint(5) unsigned NOT NULL default '1',
  `title` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `cat_indent` tinyint(1) NOT NULL default '1',
  `is_havechild` tinyint(1) NOT NULL default '0',
  `show_order` tinyint(3) NOT NULL,
  PRIMARY KEY  (`cat_id`),
  KEY `parentid` (`parentid`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_comment`
--
DROP TABLE IF EXISTS `blue_comment`;
CREATE TABLE `blue_comment` (
  `com_id` int(10) unsigned NOT NULL auto_increment,
  `post_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `mood` tinyint(3) NOT NULL,
  `content` mediumtext NOT NULL,
  `pub_date` int(10) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `is_check` tinyint(1) NOT NULL,
  PRIMARY KEY  (`com_id`),
  KEY `postid` (`post_id`),
  KEY `userid` (`user_id`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_config`
--
DROP TABLE IF EXISTS `blue_config`;
CREATE TABLE `blue_config` (
  `name` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL
) TYPE=MyISAM;

--
-- 表的结构 `blue_flash_image`
--
DROP TABLE IF EXISTS `blue_image`;
CREATE TABLE `blue_flash_image` (
  `image_id` int(10) unsigned NOT NULL auto_increment,
  `image_path` varchar(255) NOT NULL,
  `image_link` varchar(255) NOT NULL,
  `show_order` tinyint(3) NOT NULL,
  PRIMARY KEY  (`image_id`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_guest_book`
--
DROP TABLE IF EXISTS `blue_image`;
CREATE TABLE `blue_guest_book` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `rid` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `add_time` int(10) NOT NULL,
  `ip` varchar(15) character set gbk NOT NULL,
  `content` text character set gbk NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `rid` (`rid`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_ipbanned`
--
DROP TABLE IF EXISTS `blue_ipbanned`;
CREATE TABLE `blue_ipbanned` (
  `ip` varchar(15) NOT NULL,
  `add_time` int(11) NOT NULL,
  `exp` smallint(5) NOT NULL,
  PRIMARY KEY  (`ip`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_link`
--
DROP TABLE IF EXISTS `blue_link`;
CREATE TABLE `blue_link` (
  `linkid` int(10) unsigned NOT NULL auto_increment,
  `linkname` varchar(30) NOT NULL,
  `linksite` varchar(255) NOT NULL,
  `linklogo` varchar(255) NOT NULL,
  `showorder` tinyint(3) NOT NULL,
  PRIMARY KEY  (`linkid`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_model`
--
DROP TABLE IF EXISTS `blue_model`;
CREATE TABLE `blue_model` (
  `model_id` smallint(5) unsigned NOT NULL auto_increment,
  `model_name` varchar(20) NOT NULL,
  `show_order` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`model_id`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_navigate`
--
DROP TABLE IF EXISTS `blue_navigate`;
CREATE TABLE `blue_navigate` (
  `navid` int(10) unsigned NOT NULL auto_increment,
  `navname` varchar(30) NOT NULL,
  `navlink` varchar(255) NOT NULL,
  `opennew` tinyint(1) NOT NULL,
  `showorder` tinyint(3) NOT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY  (`navid`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_pay`
--
DROP TABLE IF EXISTS `blue_pay`;
CREATE TABLE `blue_pay` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `userid` varchar(50) NOT NULL,
  `key` varchar(60) NOT NULL,
  `email` varchar(40) NOT NULL,
  `description` text NOT NULL,
  `fee` float(6,2) NOT NULL default '0.00',
  `logo` varchar(40) NOT NULL,
  `is_open` tinyint(1) NOT NULL default '0',
  `show_order` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_post`
--
DROP TABLE IF EXISTS `blue_post`;
CREATE TABLE `blue_post` (
  `post_id` int(10) unsigned NOT NULL auto_increment,
  `cat_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `area_id` int(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `lit_pic` varchar(50) NOT NULL,
  `link_man` varchar(30) NOT NULL,
  `link_phone` varchar(20) NOT NULL,
  `link_qq` varchar(20) NOT NULL,
  `link_email` varchar(40) NOT NULL,
  `link_address` varchar(255) NOT NULL,
  `pub_date` int(10) NOT NULL,
  `useful_time` int(10) NOT NULL,
  `click` int(10) NOT NULL default '0',
  `comment` int(10) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  `is_check` tinyint(1) NOT NULL default '1',
  `is_recommend` tinyint(1) NOT NULL default '0',
  `rec_start` int(10) NOT NULL,
  `rec_time` smallint(5) NOT NULL,
  `top_type` tinyint(1) NOT NULL,
  `top_start` int(10) NOT NULL,
  `top_time` int(10) NOT NULL,
  `is_head_line` tinyint(1) NOT NULL,
  `head_line_start` int(10) NOT NULL,
  `head_line_time` smallint(5) NOT NULL,
  PRIMARY KEY  (`post_id`),
  KEY `catid` (`cat_id`,`user_id`,`area_id`,`is_recommend`,`rec_start`,`rec_time`,`top_type`,`top_start`,`top_time`,`is_head_line`,`head_line_start`,`head_line_time`),
  KEY `postid` (`post_id`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_post_att`
--
DROP TABLE IF EXISTS `blue_att`;
CREATE TABLE `blue_post_att` (
  `post_id` int(10) unsigned NOT NULL,
  `att_id` smallint(6) unsigned NOT NULL,
  `value` varchar(100) NOT NULL
) TYPE=MyISAM;

--
-- 表的结构 `blue_post_pic`
--
DROP TABLE IF EXISTS `blue_post_pic`;
CREATE TABLE `blue_post_pic` (
  `pic_id` int(10) unsigned NOT NULL auto_increment,
  `post_id` int(10) unsigned NOT NULL,
  `pic_path` varchar(255) NOT NULL,
  PRIMARY KEY  (`pic_id`),
  KEY `post_id` (`post_id`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_service`
--
DROP TABLE IF EXISTS `blue_service`;
CREATE TABLE `blue_service` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  `type` varchar(15) NOT NULL,
  `service` varchar(10) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_task`
--
DROP TABLE IF EXISTS `blue_service`;
CREATE TABLE `blue_task` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(15) NOT NULL,
  `last_time` int(10) NOT NULL,
  `exp` smallint(5) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `time` (`last_time`,`exp`)
) TYPE=MyISAM;

--
-- 表的结构 `blue_user`
--
DROP TABLE IF EXISTS `blue_user`;
CREATE TABLE `blue_user` (
  `user_id` int(10) unsigned NOT NULL auto_increment,
  `user_name` varchar(40) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `email` varchar(40) NOT NULL,
  `birthday` date NOT NULL default '0000-00-00',
  `sex` tinyint(1) NOT NULL default '0',
  `money` decimal(10,2) NOT NULL default '0.00',
  `face_pic` varchar(50) NOT NULL,
  `mobile_phone` varchar(20) NOT NULL,
  `home_phone` varchar(20) NOT NULL,
  `office_phone` varchar(20) NOT NULL,
  `qq` varchar(20) NOT NULL,
  `msn` varchar(60) NOT NULL,
  `address` varchar(255) NOT NULL,
  `reg_time` int(10) NOT NULL,
  `last_login_time` int(10) unsigned NOT NULL,
  `last_login_ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`user_id`)
) TYPE=MyISAM;