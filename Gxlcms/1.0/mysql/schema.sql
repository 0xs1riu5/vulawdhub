-- MySQL dump 10.13  Distrib 5.7.22, for Linux (x86_64)
--
-- Host: localhost    Database: gxlcms
-- ------------------------------------------------------
-- Server version	5.7.22

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `gxlcms` default charset utf8 COLLATE utf8_general_ci;

use gxlcms;

--
-- Table structure for table `gxl_admin`
--

DROP TABLE IF EXISTS `gxl_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gxl_admin` (
  `admin_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(50) NOT NULL,
  `admin_pwd` char(255) NOT NULL,
  `admin_count` smallint(6) NOT NULL,
  `admin_ok` varchar(50) NOT NULL,
  `admin_del` bigint(1) NOT NULL,
  `admin_ip` varchar(40) NOT NULL,
  `admin_email` varchar(40) NOT NULL,
  `admin_logintime` int(11) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gxl_admin`
--

LOCK TABLES `gxl_admin` WRITE;
/*!40000 ALTER TABLE `gxl_admin` DISABLE KEYS */;
INSERT INTO `gxl_admin` VALUES (1,'admin','21232f297a57a5a743894a0e4a801fc3',987,'1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1',0,'172.20.0.1','admin@qq.com',1531617267);
/*!40000 ALTER TABLE `gxl_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gxl_ads`
--

DROP TABLE IF EXISTS `gxl_ads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gxl_ads` (
  `ads_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `ads_name` varchar(50) NOT NULL,
  `ads_content` text NOT NULL,
  PRIMARY KEY (`ads_id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gxl_ads`
--

LOCK TABLES `gxl_ads` WRITE;
/*!40000 ALTER TABLE `gxl_ads` DISABLE KEYS */;
INSERT INTO `gxl_ads` VALUES (38,'ddd','');
/*!40000 ALTER TABLE `gxl_ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gxl_cm`
--

DROP TABLE IF EXISTS `gxl_cm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gxl_cm` (
  `cm_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `cm_cid` mediumint(9) NOT NULL,
  `cm_sid` tinyint(1) NOT NULL DEFAULT '1',
  `cm_uid` mediumint(9) NOT NULL DEFAULT '1',
  `cm_content` text NOT NULL,
  `cm_up` mediumint(9) NOT NULL DEFAULT '0',
  `cm_down` mediumint(9) NOT NULL DEFAULT '0',
  `cm_ip` varchar(20) NOT NULL,
  `cm_addtime` int(11) NOT NULL,
  `cm_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gxl_cm`
--

LOCK TABLES `gxl_cm` WRITE;
/*!40000 ALTER TABLE `gxl_cm` DISABLE KEYS */;
/*!40000 ALTER TABLE `gxl_cm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gxl_collect`
--

DROP TABLE IF EXISTS `gxl_collect`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gxl_collect` (
  `collect_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `collect_title` varchar(50) NOT NULL,
  `collect_encoding` varchar(10) NOT NULL,
  `collect_player` varchar(50) NOT NULL,
  `collect_savepic` tinyint(4) NOT NULL,
  `collect_order` tinyint(4) NOT NULL,
  `collect_pagetype` tinyint(4) NOT NULL,
  `collect_liststr` text NOT NULL,
  `collect_pagestr` text NOT NULL,
  `collect_pagesid` smallint(6) unsigned NOT NULL,
  `collect_pageeid` smallint(6) unsigned NOT NULL,
  `collect_listurlstr` text NOT NULL,
  `collect_listlink` text NOT NULL,
  `collect_listpicstr` text NOT NULL,
  `collect_cid` text NOT NULL,
  `collect_listname` text NOT NULL,
  `collect_keywords` text NOT NULL,
  `collect_name` text NOT NULL,
  `collect_titlee` text NOT NULL,
  `collect_actor` text NOT NULL,
  `collect_director` text NOT NULL,
  `collect_content` text NOT NULL,
  `collect_pic` text NOT NULL,
  `collect_area` text NOT NULL,
  `collect_language` text NOT NULL,
  `collect_year` text NOT NULL,
  `collect_continu` text NOT NULL,
  `collect_urlstr` text NOT NULL,
  `collect_urlname` text NOT NULL,
  `collect_urllink` text NOT NULL,
  `collect_url` text NOT NULL,
  `collect_replace` text NOT NULL,
  PRIMARY KEY (`collect_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gxl_collect`
--

LOCK TABLES `gxl_collect` WRITE;
/*!40000 ALTER TABLE `gxl_collect` DISABLE KEYS */;
/*!40000 ALTER TABLE `gxl_collect` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gxl_comment`
--

DROP TABLE IF EXISTS `gxl_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gxl_comment` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ting_id` int(10) DEFAULT NULL,
  `userid` int(10) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `creat_at` int(11) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '评论状态{0:未审核,-1:未通过审核,1:通过审核}',
  `content` varchar(255) DEFAULT NULL,
  `support` mediumint(8) DEFAULT '0' COMMENT '支持数',
  `reply` tinyint(1) DEFAULT '0' COMMENT '是否为回复',
  `oppose` mediumint(8) DEFAULT '0' COMMENT '反对数',
  `pid` int(10) DEFAULT NULL,
  `ispass` int(1) DEFAULT '0' COMMENT '1 通过 0 不通过',
  `rcid` int(10) NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `vod_id` (`ting_id`),
  KEY `userid` (`userid`),
  KEY `status` (`status`),
  KEY `ispass` (`ispass`),
  KEY `pid` (`pid`),
  KEY `ip` (`ip`),
  KEY `creat_at` (`creat_at`),
  KEY `rcid` (`rcid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gxl_comment`
--

LOCK TABLES `gxl_comment` WRITE;
/*!40000 ALTER TABLE `gxl_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `gxl_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gxl_comment_opinion`
--

DROP TABLE IF EXISTS `gxl_comment_opinion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gxl_comment_opinion` (
  `opinion_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` int(10) DEFAULT NULL,
  `opinion` int(1) DEFAULT NULL COMMENT '0 反对 1同意',
  `creat_date` int(11) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`opinion_id`),
  KEY `comment_id` (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gxl_comment_opinion`
--

LOCK TABLES `gxl_comment_opinion` WRITE;
/*!40000 ALTER TABLE `gxl_comment_opinion` DISABLE KEYS */;
/*!40000 ALTER TABLE `gxl_comment_opinion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gxl_gb`
--

DROP TABLE IF EXISTS `gxl_gb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gxl_gb` (
  `gb_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `gb_cid` mediumint(8) NOT NULL DEFAULT '0',
  `gb_uid` mediumint(9) NOT NULL DEFAULT '1',
  `gb_content` text NOT NULL,
  `gb_intro` text NOT NULL,
  `gb_addtime` int(11) NOT NULL,
  `gb_ip` varchar(20) NOT NULL,
  `gb_oid` tinyint(1) NOT NULL DEFAULT '0',
  `gb_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gb_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gxl_gb`
--

LOCK TABLES `gxl_gb` WRITE;
/*!40000 ALTER TABLE `gxl_gb` DISABLE KEYS */;
/*!40000 ALTER TABLE `gxl_gb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gxl_guestbook`
--

DROP TABLE IF EXISTS `gxl_guestbook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gxl_guestbook` (
  `gb_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `gb_cid` mediumint(8) NOT NULL DEFAULT '0',
  `gb_uid` mediumint(9) NOT NULL DEFAULT '1',
  `nickname` varchar(20) NOT NULL,
  `gb_title` varchar(200) NOT NULL COMMENT '标题',
  `gb_content` text NOT NULL,
  `gb_intro` text NOT NULL,
  `gb_addtime` int(11) NOT NULL,
  `gb_ip` varchar(20) NOT NULL,
  `gb_oid` tinyint(1) NOT NULL DEFAULT '0',
  `gb_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gb_id`),
  KEY `gb_uid` (`gb_uid`),
  KEY `gb_cid` (`gb_cid`),
  KEY `nickname` (`nickname`),
  KEY `gb_addtime` (`gb_addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gxl_guestbook`
--

LOCK TABLES `gxl_guestbook` WRITE;
/*!40000 ALTER TABLE `gxl_guestbook` DISABLE KEYS */;
/*!40000 ALTER TABLE `gxl_guestbook` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gxl_link`
--

DROP TABLE IF EXISTS `gxl_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gxl_link` (
  `link_id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `link_name` varchar(255) NOT NULL,
  `link_logo` varchar(255) NOT NULL,
  `link_url` varchar(255) NOT NULL,
  `link_order` tinyint(4) NOT NULL,
  `link_type` tinyint(1) NOT NULL,
  PRIMARY KEY (`link_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gxl_link`
--

LOCK TABLES `gxl_link` WRITE;
/*!40000 ALTER TABLE `gxl_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `gxl_link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gxl_list`
--

DROP TABLE IF EXISTS `gxl_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gxl_list` (
  `list_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `list_pid` smallint(3) NOT NULL,
  `list_oid` smallint(3) NOT NULL,
  `list_sid` tinyint(1) NOT NULL,
  `list_name` char(20) NOT NULL,
  `list_skin` char(20) NOT NULL,
  `list_skin_detail` varchar(20) NOT NULL DEFAULT 'gxl_ting',
  `list_skin_play` varchar(20) NOT NULL DEFAULT 'gxl_play',
  `list_skin_type` varchar(20) NOT NULL DEFAULT 'gxl_tingtype',
  `list_dir` varchar(90) NOT NULL,
  `list_status` tinyint(1) NOT NULL DEFAULT '1',
  `list_keywords` varchar(255) NOT NULL,
  `list_title` varchar(50) NOT NULL,
  `list_description` varchar(255) NOT NULL,
  `list_jumpurl` varchar(150) NOT NULL,
  PRIMARY KEY (`list_id`),
  KEY `list_oid` (`list_oid`),
  KEY `list_name` (`list_name`),
  KEY `list_dir` (`list_dir`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gxl_list`
--

LOCK TABLES `gxl_list` WRITE;
/*!40000 ALTER TABLE `gxl_list` DISABLE KEYS */;
INSERT INTO `gxl_list` VALUES (1,0,2,1,'文学名著','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','wenxue',1,'','','',''),(2,0,1,1,'有声小说','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','tingbook',1,'','','','http://'),(3,0,3,1,'曲艺戏曲','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','xiqu',1,'','','','http://'),(4,0,4,1,'相声评书','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','zongyi',1,'','','','http://'),(8,1,2,1,'散文随笔','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','sanwen',1,'','','','http://'),(9,1,1,1,'通俗文学','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','tongsu',1,'','','','http://'),(10,1,5,1,'诗词歌赋','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','shici',1,'','','','http://'),(11,1,3,1,'青春文学','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','qingchui',1,'','','','http://'),(12,1,4,1,' 名家名著','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','mingjia',1,'','','','http://'),(13,1,6,1,' 外国文学','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','waiguo',1,'','','','http://'),(15,2,1,1,'恐怖惊悚','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','kongbu',1,'','','','http://'),(16,2,2,1,'悬疑探险','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','xuanyi',1,'','','','http://'),(17,2,6,1,'都市传说','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','dushi',1,'','','','http://'),(18,2,5,1,'武侠仙侠','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','wuxia',1,'','','','http://'),(19,2,8,1,'穿越架空','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','chuanyue',1,'','','','http://'),(23,2,3,1,' 玄幻奇幻','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','xuanhuan',1,'','','','http://'),(24,2,4,1,'历史军事','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','lishi',1,'','','','http://'),(25,2,7,1,' 网游科幻','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','wangyou',1,'','','','http://'),(26,1,7,1,'国学经典','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','guoxue',1,'','','','http://'),(28,1,8,1,'影视文学','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','yingshi',1,'','','','http://'),(35,0,5,1,'少儿天地','gxl_letter','gxl_ting','gxl_play','gxl_tingtype','weidianying',1,'','','','http://');
/*!40000 ALTER TABLE `gxl_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gxl_news`
--

DROP TABLE IF EXISTS `gxl_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gxl_news` (
  `news_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `news_cid` smallint(6) NOT NULL DEFAULT '0',
  `news_name` varchar(255) NOT NULL,
  `news_keywords` varchar(255) NOT NULL,
  `news_color` char(8) NOT NULL,
  `news_pic` varchar(255) NOT NULL,
  `news_inputer` varchar(50) NOT NULL,
  `news_reurl` varchar(255) NOT NULL,
  `news_remark` text NOT NULL,
  `news_content` text NOT NULL,
  `news_hits` mediumint(8) NOT NULL,
  `news_hits_day` mediumint(8) NOT NULL,
  `news_hits_week` mediumint(8) NOT NULL,
  `news_hits_month` mediumint(8) NOT NULL,
  `news_hits_lasttime` int(11) NOT NULL,
  `news_stars` tinyint(1) NOT NULL,
  `news_status` tinyint(1) NOT NULL DEFAULT '1',
  `news_up` mediumint(8) NOT NULL,
  `news_down` mediumint(8) NOT NULL,
  `news_jumpurl` varchar(255) NOT NULL,
  `news_letter` char(2) NOT NULL,
  `news_addtime` int(8) NOT NULL,
  `news_skin` varchar(30) NOT NULL,
  `news_gold` decimal(3,1) NOT NULL,
  `news_golder` smallint(6) NOT NULL,
  PRIMARY KEY (`news_id`),
  KEY `news_cid` (`news_cid`),
  KEY `news_up` (`news_up`),
  KEY `news_down` (`news_down`),
  KEY `news_gold` (`news_gold`),
  KEY `news_hits` (`news_hits`,`news_cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gxl_news`
--

LOCK TABLES `gxl_news` WRITE;
/*!40000 ALTER TABLE `gxl_news` DISABLE KEYS */;
/*!40000 ALTER TABLE `gxl_news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gxl_slide`
--

DROP TABLE IF EXISTS `gxl_slide`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gxl_slide` (
  `slide_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `slide_oid` tinyint(3) NOT NULL,
  `slide_cid` tinyint(3) NOT NULL DEFAULT '1',
  `slide_name` varchar(255) NOT NULL,
  `slide_logo` varchar(255) NOT NULL,
  `slide_pic` varchar(255) NOT NULL,
  `slide_url` varchar(255) NOT NULL,
  `slide_content` varchar(255) NOT NULL,
  `slide_status` tinyint(1) NOT NULL,
  `slide_vid` mediumint(8) NOT NULL,
  PRIMARY KEY (`slide_id`),
  KEY `slide_status` (`slide_status`),
  KEY `slide_oid` (`slide_oid`),
  KEY `slide_cid` (`slide_cid`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gxl_slide`
--

LOCK TABLES `gxl_slide` WRITE;
/*!40000 ALTER TABLE `gxl_slide` DISABLE KEYS */;
/*!40000 ALTER TABLE `gxl_slide` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gxl_special`
--

DROP TABLE IF EXISTS `gxl_special`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gxl_special` (
  `special_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `special_banner` varchar(150) NOT NULL,
  `special_logo` varchar(150) NOT NULL,
  `special_name` varchar(150) NOT NULL,
  `special_keywords` varchar(150) NOT NULL,
  `special_description` varchar(255) NOT NULL,
  `special_color` char(8) NOT NULL,
  `special_skin` varchar(50) NOT NULL,
  `special_addtime` int(11) NOT NULL,
  `special_hits` mediumint(8) NOT NULL,
  `special_hits_day` mediumint(8) NOT NULL,
  `special_hits_week` mediumint(8) NOT NULL,
  `special_hits_month` mediumint(8) NOT NULL,
  `special_hits_lasttime` int(11) NOT NULL,
  `special_stars` tinyint(1) NOT NULL DEFAULT '1',
  `special_status` tinyint(1) NOT NULL,
  `special_content` text NOT NULL,
  `special_up` mediumint(8) NOT NULL,
  `special_down` mediumint(8) NOT NULL,
  `special_gold` decimal(3,1) NOT NULL,
  `special_golder` smallint(6) NOT NULL,
  `special_letters` varchar(255) DEFAULT NULL,
  `special_mx` varchar(155) NOT NULL,
  PRIMARY KEY (`special_id`),
  UNIQUE KEY `special_letters` (`special_letters`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gxl_special`
--

LOCK TABLES `gxl_special` WRITE;
/*!40000 ALTER TABLE `gxl_special` DISABLE KEYS */;
/*!40000 ALTER TABLE `gxl_special` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gxl_tag`
--

DROP TABLE IF EXISTS `gxl_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gxl_tag` (
  `tag_id` mediumint(8) NOT NULL,
  `tag_sid` tinyint(1) NOT NULL,
  `tag_name` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gxl_tag`
--

LOCK TABLES `gxl_tag` WRITE;
/*!40000 ALTER TABLE `gxl_tag` DISABLE KEYS */;
INSERT INTO `gxl_tag` VALUES (0,1,'未知');
/*!40000 ALTER TABLE `gxl_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gxl_ting`
--

DROP TABLE IF EXISTS `gxl_ting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gxl_ting` (
  `ting_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '作品id',
  `ting_cid` smallint(5) NOT NULL DEFAULT '0' COMMENT '作品栏目cid',
  `ting_name` varchar(255) NOT NULL DEFAULT '' COMMENT '作品名称',
  `ting_title` varchar(255) NOT NULL DEFAULT '' COMMENT '作品备注',
  `ting_keywords` varchar(255) NOT NULL,
  `ting_color` char(8) NOT NULL DEFAULT '' COMMENT '标题颜色',
  `ting_anchor` varchar(255) NOT NULL COMMENT '主播',
  `ting_author` varchar(255) NOT NULL COMMENT '作者',
  `ting_content` text NOT NULL COMMENT '作品描述',
  `ting_pic` varchar(255) NOT NULL DEFAULT '' COMMENT '作品图片',
  `ting_language` char(10) NOT NULL DEFAULT '' COMMENT '作品语言',
  `ting_addtime` int(11) NOT NULL DEFAULT '0' COMMENT '作品时间',
  `ting_hits` mediumint(8) NOT NULL DEFAULT '0' COMMENT '总点击',
  `ting_hits_day` mediumint(8) NOT NULL DEFAULT '0' COMMENT '日点击',
  `ting_hits_week` mediumint(8) NOT NULL DEFAULT '0' COMMENT '周点击',
  `ting_hits_month` mediumint(8) NOT NULL DEFAULT '0' COMMENT '月点击',
  `ting_hits_lasttime` int(11) NOT NULL,
  `ting_stars` tinyint(1) NOT NULL DEFAULT '0',
  `ting_status` tinyint(1) NOT NULL DEFAULT '1',
  `ting_up` mediumint(8) NOT NULL DEFAULT '0' COMMENT '顶',
  `ting_down` mediumint(8) NOT NULL DEFAULT '0' COMMENT '踩',
  `ting_play` varchar(255) NOT NULL,
  `ting_server` varchar(255) NOT NULL,
  `ting_url` longtext NOT NULL COMMENT '播放地址',
  `ting_inputer` varchar(30) NOT NULL,
  `ting_reurl` varchar(255) NOT NULL,
  `ting_jumpurl` varchar(150) NOT NULL,
  `ting_letter` char(2) NOT NULL,
  `ting_skin` varchar(30) NOT NULL,
  `ting_gold` decimal(3,1) NOT NULL,
  `ting_golder` smallint(6) NOT NULL,
  `ting_length` smallint(3) NOT NULL,
  `reid` int(10) NOT NULL DEFAULT '0',
  `HasGetComment` smallint(10) NOT NULL DEFAULT '0',
  `ting_letters` varchar(255) DEFAULT '0' COMMENT '首字母',
  `ting_total` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ting_id`),
  KEY `ting_letters` (`ting_letters`),
  KEY `ting_actor` (`ting_anchor`),
  KEY `ting_director` (`ting_author`),
  KEY `ting_up` (`ting_up`),
  KEY `ting_down` (`ting_down`),
  KEY `ting_gold` (`ting_gold`),
  KEY `ting_addtime` (`ting_addtime`,`ting_cid`),
  KEY `ting_hits` (`ting_hits`,`ting_cid`),
  KEY `ting_hits_month` (`ting_hits_month`,`ting_cid`),
  KEY `ting_filmtime` (`ting_cid`),
  KEY `ting_cid` (`ting_cid`,`ting_status`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='听数据';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gxl_ting`
--

LOCK TABLES `gxl_ting` WRITE;
/*!40000 ALTER TABLE `gxl_ting` DISABLE KEYS */;
INSERT INTO `gxl_ting` VALUES (21,9,'test','test','0','','test','test','','test','',1531617297,0,0,0,0,0,1,1,0,0,'','','','admin','','','t','',0.0,0,0,0,0,'',NULL);
/*!40000 ALTER TABLE `gxl_ting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gxl_ting_mark`
--

DROP TABLE IF EXISTS `gxl_ting_mark`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gxl_ting_mark` (
  `ting_id` int(10) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `F1` int(2) DEFAULT '0',
  `creat_date` int(11) DEFAULT NULL,
  `mark_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `F2` int(2) DEFAULT '0',
  `F3` int(2) DEFAULT '0',
  `F4` int(2) DEFAULT '0',
  `F5` int(2) DEFAULT '0',
  PRIMARY KEY (`mark_id`),
  KEY `vod_id` (`ting_id`),
  KEY `mark_id` (`mark_id`),
  KEY `ip` (`ip`),
  KEY `creat_date` (`creat_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gxl_ting_mark`
--

LOCK TABLES `gxl_ting_mark` WRITE;
/*!40000 ALTER TABLE `gxl_ting_mark` DISABLE KEYS */;
/*!40000 ALTER TABLE `gxl_ting_mark` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gxl_view`
--

DROP TABLE IF EXISTS `gxl_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gxl_view` (
  `view_id` mediumint(8) unsigned NOT NULL,
  `view_did` mediumint(8) NOT NULL,
  `view_uid` mediumint(8) NOT NULL,
  `view_addtime` int(10) NOT NULL,
  PRIMARY KEY (`view_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gxl_view`
--

LOCK TABLES `gxl_view` WRITE;
/*!40000 ALTER TABLE `gxl_view` DISABLE KEYS */;
/*!40000 ALTER TABLE `gxl_view` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-07-15  1:20:47
