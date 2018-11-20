--BlueCMS VERSION:v1.6
--Mysql VERSION:5.7.23
--Create time:2018-11-20 11:02:48
DROP TABLE IF EXISTS `blue_ad`;
CREATE TABLE `blue_ad` (
  `ad_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ad_name` varchar(40) NOT NULL,
  `time_set` tinyint(1) NOT NULL DEFAULT '0',
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `exp_content` text NOT NULL,
  PRIMARY KEY (`ad_id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_ad_phone`;
CREATE TABLE `blue_ad_phone` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(40) NOT NULL,
  `title` varchar(100) NOT NULL,
  `color` varchar(10) NOT NULL,
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `show_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_admin`;
CREATE TABLE `blue_admin` (
  `admin_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `purview` varchar(255) NOT NULL,
  `add_time` int(10) NOT NULL,
  `last_login_time` int(10) NOT NULL,
  `last_login_ip` varchar(15) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;
INSERT INTO `blue_admin` VALUES ('1','admin','admin@qq.com','e10adc3949ba59abbe56e057f20f883e','all','1542678367','1542682968','');
DROP TABLE IF EXISTS `blue_admin_log`;
CREATE TABLE `blue_admin_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(20) NOT NULL,
  `add_time` int(10) NOT NULL,
  `log_value` varchar(255) NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_ann`;
CREATE TABLE `blue_ann` (
  `ann_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` smallint(5) NOT NULL,
  `author` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `color` varchar(7) NOT NULL,
  `content` varchar(255) NOT NULL,
  `add_time` int(10) NOT NULL DEFAULT '0',
  `click` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ann_id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_ann_cat`;
CREATE TABLE `blue_ann_cat` (
  `cid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(20) NOT NULL,
  `show_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=gbk;
INSERT INTO `blue_ann_cat` VALUES ('1','网站公告','0');
INSERT INTO `blue_ann_cat` VALUES ('2','付费推广','0');
INSERT INTO `blue_ann_cat` VALUES ('3','帮助中心','0');
INSERT INTO `blue_ann_cat` VALUES ('4','关于本站','0');
DROP TABLE IF EXISTS `blue_arc_cat`;
CREATE TABLE `blue_arc_cat` (
  `cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(100) NOT NULL,
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `cat_indent` tinyint(1) NOT NULL DEFAULT '1',
  `is_havechild` tinyint(1) NOT NULL DEFAULT '0',
  `show_order` tinyint(3) NOT NULL,
  PRIMARY KEY (`cat_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_area`;
CREATE TABLE `blue_area` (
  `area_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `area_name` varchar(20) NOT NULL,
  `parentid` int(10) NOT NULL,
  `area_indent` int(1) NOT NULL DEFAULT '0',
  `ishavechild` tinyint(1) NOT NULL DEFAULT '0',
  `show_order` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`area_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;
INSERT INTO `blue_area` VALUES ('1','地区一','0','0','0','0');
DROP TABLE IF EXISTS `blue_article`;
CREATE TABLE `blue_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `color` varchar(7) NOT NULL,
  `author` varchar(20) NOT NULL,
  `source` varchar(20) NOT NULL,
  `pub_date` int(10) NOT NULL DEFAULT '0',
  `lit_pic` varchar(100) NOT NULL,
  `descript` varchar(250) NOT NULL,
  `content` mediumtext NOT NULL,
  `click` int(10) NOT NULL DEFAULT '0',
  `comment` int(10) NOT NULL DEFAULT '0',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0',
  `is_check` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_attachment`;
CREATE TABLE `blue_attachment` (
  `att_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` smallint(6) NOT NULL,
  `att_name` varchar(40) NOT NULL,
  `att_type` tinyint(1) NOT NULL DEFAULT '1',
  `is_required` tinyint(1) NOT NULL DEFAULT '1',
  `unit` varchar(20) NOT NULL,
  `att_val` varchar(255) NOT NULL,
  `show_order` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`att_id`),
  KEY `postid` (`modelid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_buy_record`;
CREATE TABLE `blue_buy_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `aid` int(10) NOT NULL,
  `pid` smallint(5) NOT NULL,
  `exp` smallint(5) NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_card_order`;
CREATE TABLE `blue_card_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `name` varchar(40) NOT NULL,
  `value` int(10) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `time` int(10) NOT NULL,
  `is_pay` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_card_type`;
CREATE TABLE `blue_card_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` int(10) NOT NULL,
  `price` int(10) NOT NULL,
  `is_close` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;
INSERT INTO `blue_card_type` VALUES ('1','便民卡','100','30','0');
DROP TABLE IF EXISTS `blue_category`;
CREATE TABLE `blue_category` (
  `cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(100) NOT NULL,
  `englishname` varchar(100) NOT NULL,
  `title_color` varchar(20) NOT NULL,
  `parentid` int(10) NOT NULL DEFAULT '0',
  `model` smallint(5) unsigned NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `cat_indent` tinyint(1) NOT NULL DEFAULT '1',
  `is_havechild` tinyint(1) NOT NULL DEFAULT '0',
  `show_order` tinyint(3) NOT NULL,
  PRIMARY KEY (`cat_id`),
  KEY `parentid` (`parentid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_comment`;
CREATE TABLE `blue_comment` (
  `com_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `mood` tinyint(3) NOT NULL,
  `content` mediumtext NOT NULL,
  `pub_date` int(10) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `is_check` tinyint(1) NOT NULL,
  PRIMARY KEY (`com_id`),
  KEY `postid` (`post_id`),
  KEY `userid` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_config`;
CREATE TABLE `blue_config` (
  `name` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
INSERT INTO `blue_config` VALUES ('site_name','演示网站');
INSERT INTO `blue_config` VALUES ('site_url','http://localhost');
INSERT INTO `blue_config` VALUES ('description','');
INSERT INTO `blue_config` VALUES ('keywords','');
INSERT INTO `blue_config` VALUES ('tel','1234567|1234567');
INSERT INTO `blue_config` VALUES ('icp','');
INSERT INTO `blue_config` VALUES ('count','');
INSERT INTO `blue_config` VALUES ('isclose','0');
INSERT INTO `blue_config` VALUES ('reason','');
INSERT INTO `blue_config` VALUES ('cookie_hash','DfEZg1482F');
INSERT INTO `blue_config` VALUES ('url_rewrite','0');
INSERT INTO `blue_config` VALUES ('qq','1234567|1234567');
INSERT INTO `blue_config` VALUES ('qq_group','1234567|1234567');
INSERT INTO `blue_config` VALUES ('right','BlueCMS ― 第一款免费开源的专业地方门户系统，专注于地方门户的CMS！');
INSERT INTO `blue_config` VALUES ('info_is_check','0');
INSERT INTO `blue_config` VALUES ('comment_is_check','0');
INSERT INTO `blue_config` VALUES ('news_is_check','0');
INSERT INTO `blue_config` VALUES ('is_gzip','0');
DROP TABLE IF EXISTS `blue_flash_image`;
CREATE TABLE `blue_flash_image` (
  `image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image_path` varchar(255) NOT NULL,
  `image_link` varchar(255) NOT NULL,
  `show_order` tinyint(3) NOT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_guest_book`;
CREATE TABLE `blue_guest_book` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `add_time` int(10) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_ipbanned`;
CREATE TABLE `blue_ipbanned` (
  `ip` varchar(15) NOT NULL,
  `add_time` int(11) NOT NULL,
  `exp` smallint(5) NOT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_link`;
CREATE TABLE `blue_link` (
  `linkid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `linkname` varchar(30) NOT NULL,
  `linksite` varchar(255) NOT NULL,
  `linklogo` varchar(255) NOT NULL,
  `showorder` tinyint(3) NOT NULL,
  PRIMARY KEY (`linkid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_model`;
CREATE TABLE `blue_model` (
  `model_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `model_name` varchar(20) NOT NULL,
  `show_order` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`model_id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_navigate`;
CREATE TABLE `blue_navigate` (
  `navid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `navname` varchar(30) NOT NULL,
  `navlink` varchar(255) NOT NULL,
  `opennew` tinyint(1) NOT NULL,
  `showorder` tinyint(3) NOT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY (`navid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_pay`;
CREATE TABLE `blue_pay` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `userid` varchar(50) NOT NULL,
  `key` varchar(60) NOT NULL,
  `email` varchar(40) NOT NULL,
  `description` text NOT NULL,
  `fee` float(6,2) NOT NULL DEFAULT '0.00',
  `logo` varchar(40) NOT NULL,
  `is_open` tinyint(1) NOT NULL DEFAULT '0',
  `show_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=gbk;
INSERT INTO `blue_pay` VALUES ('1','alipay','支付宝','','','','支付宝网站(www.alipay.com)是国内先进的网上支付平台，由全球最佳B2B公司阿里巴巴公司创办，致力于为网络交易用户提供优质的安全支付服务。','0.00','images/alipay.jpg','1','0');
INSERT INTO `blue_pay` VALUES ('2','bank','银行转账','','','','账号:
户名:dd
开户行:','0.00','','1','0');
DROP TABLE IF EXISTS `blue_post`;
CREATE TABLE `blue_post` (
  `post_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `click` int(10) NOT NULL DEFAULT '0',
  `comment` int(10) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL,
  `is_check` tinyint(1) NOT NULL DEFAULT '1',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0',
  `rec_start` int(10) NOT NULL,
  `rec_time` smallint(5) NOT NULL,
  `top_type` tinyint(1) NOT NULL,
  `top_start` int(10) NOT NULL,
  `top_time` int(10) NOT NULL,
  `is_head_line` tinyint(1) NOT NULL,
  `head_line_start` int(10) NOT NULL,
  `head_line_time` smallint(5) NOT NULL,
  PRIMARY KEY (`post_id`),
  KEY `catid` (`cat_id`,`user_id`,`area_id`,`is_recommend`,`rec_start`,`rec_time`,`top_type`,`top_start`,`top_time`,`is_head_line`,`head_line_start`,`head_line_time`),
  KEY `postid` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_post_att`;
CREATE TABLE `blue_post_att` (
  `post_id` int(10) unsigned NOT NULL,
  `att_id` smallint(6) unsigned NOT NULL,
  `value` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_post_pic`;
CREATE TABLE `blue_post_pic` (
  `pic_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL,
  `pic_path` varchar(255) NOT NULL,
  PRIMARY KEY (`pic_id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
DROP TABLE IF EXISTS `blue_service`;
CREATE TABLE `blue_service` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `type` varchar(15) NOT NULL,
  `service` varchar(10) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=gbk;
INSERT INTO `blue_service` VALUES ('1','大类置顶','info','top2','10.00');
INSERT INTO `blue_service` VALUES ('2','小类置顶','info','top1','5.00');
INSERT INTO `blue_service` VALUES ('3','分类信息推荐','info','rec','10.00');
INSERT INTO `blue_service` VALUES ('4','分类信息头条','info','head_line','10.00');
INSERT INTO `blue_service` VALUES ('5','大类置顶','company','top2','10.00');
INSERT INTO `blue_service` VALUES ('6','小类置顶','company','top1','5.00');
INSERT INTO `blue_service` VALUES ('7','商家黄页推荐','company','rec','10.00');
INSERT INTO `blue_service` VALUES ('8','商家黄页头条','company','head_line','10.00');
DROP TABLE IF EXISTS `blue_task`;
CREATE TABLE `blue_task` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  `last_time` int(10) NOT NULL,
  `exp` smallint(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`last_time`,`exp`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;
INSERT INTO `blue_task` VALUES ('1','update_info','1542678570','1');
DROP TABLE IF EXISTS `blue_user`;
CREATE TABLE `blue_user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(40) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `email` varchar(40) NOT NULL,
  `birthday` date NOT NULL DEFAULT '0000-00-00',
  `sex` tinyint(1) NOT NULL DEFAULT '0',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
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
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;
INSERT INTO `blue_user` VALUES ('1','admin','e10adc3949ba59abbe56e057f20f883e','admin@qq.com','0000-00-00','0','0.00','','','','','','','','1542678367','1542682968','');
