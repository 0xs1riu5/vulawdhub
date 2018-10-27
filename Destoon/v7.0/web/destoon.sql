-- MySQL dump 10.13  Distrib 5.7.23, for Linux (x86_64)
--
-- Host: localhost    Database: destoon
-- ------------------------------------------------------
-- Server version	5.7.23

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

--
-- Table structure for table `destoon_404`
--

CREATE DATABASE IF NOT EXISTS `destoon` default charset utf8 COLLATE utf8_general_ci;

use destoon;

DROP TABLE IF EXISTS `destoon_404`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_404` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL DEFAULT '',
  `refer` varchar(255) NOT NULL,
  `robot` varchar(20) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='404日志';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_404`
--

LOCK TABLES `destoon_404` WRITE;
/*!40000 ALTER TABLE `destoon_404` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_404` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_ad`
--

DROP TABLE IF EXISTS `destoon_ad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_ad` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `typeid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `amount` float NOT NULL DEFAULT '0',
  `currency` varchar(20) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `fromtime` int(10) unsigned NOT NULL DEFAULT '0',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `stat` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `note` text NOT NULL,
  `code` text NOT NULL,
  `text_name` varchar(100) NOT NULL DEFAULT '',
  `text_url` varchar(255) NOT NULL DEFAULT '',
  `text_title` varchar(100) NOT NULL DEFAULT '',
  `text_style` varchar(50) NOT NULL DEFAULT '',
  `image_src` varchar(255) NOT NULL DEFAULT '',
  `image_url` varchar(255) NOT NULL DEFAULT '',
  `image_alt` varchar(100) NOT NULL DEFAULT '',
  `flash_src` varchar(255) NOT NULL DEFAULT '',
  `flash_url` varchar(255) NOT NULL DEFAULT '',
  `flash_loop` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `key_moduleid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `key_catid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `key_word` varchar(100) NOT NULL DEFAULT '',
  `key_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`aid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='广告';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_ad`
--

LOCK TABLES `destoon_ad` WRITE;
/*!40000 ALTER TABLE `destoon_ad` DISABLE KEYS */;
INSERT INTO `destoon_ad` VALUES (1,'网站首页图片轮播1',14,5,0,0,'','http://www.destoon.com/','',0,'destoon',1540533277,'destoon',1540533277,1262275200,1577894399,0,'','','','','','','file/image/player_1.jpg','http://www.destoon.com/','','','',1,0,0,'',0,0,3),(2,'网站首页图片轮播2',14,5,0,0,'','http://www.destoon.com/','',0,'destoon',1540533277,'destoon',1540533277,1262275200,1577894399,0,'','','','','','','file/image/player_2.jpg','http://www.destoon.com/','','','',1,0,0,'',0,0,3),(3,'首页旗帜A1',21,3,0,0,'','http://www.destoon.com/','',0,'destoon',1540533277,'destoon',1540533277,1262275200,1577894399,0,'','','','','','','file/image/a1.jpg','','','','',1,0,0,'',0,0,3),(4,'首页旗帜A2',22,3,0,0,'','http://www.destoon.com/','',0,'destoon',1540533277,'destoon',1540533277,1262275200,1577894399,0,'','','','','','','file/image/a2.jpg','','','','',1,0,0,'',0,0,3),(5,'首页旗帜A3',23,3,0,0,'','http://www.destoon.com/','',0,'destoon',1540533277,'destoon',1540533277,1262275200,1577894399,0,'','','','','','','file/image/a3.jpg','','','','',1,0,0,'',0,0,3),(6,'首页旗帜A4',24,3,0,0,'','http://www.destoon.com/','',0,'destoon',1540533277,'destoon',1540533277,1262275200,1577894399,0,'','','','','','','file/image/a4.jpg','','','','',1,0,0,'',0,0,3),(7,'首页旗帜A5',25,3,0,0,'','http://www.destoon.com/','',0,'destoon',1540533277,'destoon',1540533277,1262275200,1577894399,0,'','','','','','','file/image/a5.jpg','','','','',1,0,0,'',0,0,3);
/*!40000 ALTER TABLE `destoon_ad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_ad_place`
--

DROP TABLE IF EXISTS `destoon_ad_place`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_ad_place` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `moduleid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `typeid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `open` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `code` text NOT NULL,
  `width` smallint(5) unsigned NOT NULL DEFAULT '0',
  `height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `price` float unsigned NOT NULL DEFAULT '0',
  `ads` smallint(4) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `template` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='广告位';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_ad_place`
--

LOCK TABLES `destoon_ad_place` WRITE;
/*!40000 ALTER TABLE `destoon_ad_place` DISABLE KEYS */;
INSERT INTO `destoon_ad_place` VALUES (1,5,6,1,'供应排名','','','','',0,0,0,0,0,1540533277,'destoon',1540533277,''),(2,6,6,1,'求购排名','','','','',0,0,0,0,0,1540533277,'destoon',1540533277,''),(3,16,6,1,'商城排名','','','','',0,0,0,0,0,1540533277,'destoon',1540533277,''),(4,4,6,1,'公司排名','','','','',0,0,0,0,0,1540533277,'destoon',1540533277,''),(14,0,5,1,'首页图片轮播','','','','',660,300,0,2,0,1540533277,'destoon',1540533277,''),(15,5,7,1,'供应赞助商链接','','','','',0,0,0,0,0,1540533277,'destoon',1540533277,''),(17,4,7,1,'公司赞助商链接','','','','',0,0,0,0,0,1540533277,'destoon',1540533277,''),(18,0,7,1,'求购赞助商链接','','','','',0,0,0,0,0,1540533277,'destoon',1540533277,''),(19,8,7,1,'展会赞助商链接','','','','',0,0,0,0,0,1540533277,'destoon',1540533277,''),(21,0,3,1,'首页旗帜A1','','','','',116,212,0,1,0,1540533277,'destoon',1540533277,''),(22,0,3,1,'首页旗帜A2','','','','',116,212,0,1,0,1540533277,'destoon',1540533277,''),(23,0,3,1,'首页旗帜A3','','','','',116,212,0,1,0,1540533277,'destoon',1540533277,''),(24,0,3,1,'首页旗帜A4','','','','',116,212,0,1,0,1540533277,'destoon',1540533277,''),(25,0,3,1,'首页旗帜A5','','','','',116,212,0,1,0,1540533277,'destoon',1540533277,'');
/*!40000 ALTER TABLE `destoon_ad_place` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_address`
--

DROP TABLE IF EXISTS `destoon_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_address` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `truename` varchar(30) NOT NULL DEFAULT '',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `address` varchar(255) NOT NULL DEFAULT '',
  `postcode` varchar(10) NOT NULL DEFAULT '',
  `telephone` varchar(30) NOT NULL DEFAULT '',
  `mobile` varchar(30) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收货地址';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_address`
--

LOCK TABLES `destoon_address` WRITE;
/*!40000 ALTER TABLE `destoon_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_admin`
--

DROP TABLE IF EXISTS `destoon_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_admin` (
  `adminid` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `moduleid` smallint(6) NOT NULL DEFAULT '0',
  `file` varchar(20) NOT NULL DEFAULT '',
  `action` varchar(255) NOT NULL DEFAULT '',
  `catid` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`adminid`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='管理员';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_admin`
--

LOCK TABLES `destoon_admin` WRITE;
/*!40000 ALTER TABLE `destoon_admin` DISABLE KEYS */;
INSERT INTO `destoon_admin` VALUES (1,1,0,'生成首页','?action=html','',0,'','',''),(2,1,0,'更新缓存','?action=cache','',0,'','',''),(3,1,0,'网站设置','?file=setting','',0,'','',''),(4,1,0,'模块管理','?file=module','',0,'','',''),(5,1,0,'数据维护','?file=database','',0,'','',''),(6,1,0,'模板管理','?file=template','',0,'','',''),(7,1,0,'会员管理','?moduleid=2','',0,'','',''),(8,1,0,'单页管理','?moduleid=3&file=webpage','',0,'','',''),(9,1,0,'排名推广','?moduleid=3&file=spread','',0,'','',''),(10,1,0,'广告管理','?moduleid=3&file=ad','',0,'','','');
/*!40000 ALTER TABLE `destoon_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_admin_log`
--

DROP TABLE IF EXISTS `destoon_admin_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_admin_log` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qstring` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `logtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`logid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='管理日志';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_admin_log`
--

LOCK TABLES `destoon_admin_log` WRITE;
/*!40000 ALTER TABLE `destoon_admin_log` DISABLE KEYS */;
INSERT INTO `destoon_admin_log` VALUES (1,'file=md5&action=add&js=1','destoon','172.19.0.1',1540533300),(2,'file=setting','destoon','172.19.0.1',1540533302),(3,'moduleid=2&file=setting&widget=1','destoon','172.19.0.1',1540533306),(4,'rand=57&moduleid=2&file=setting&tab=0','destoon','172.19.0.1',1540533344);
/*!40000 ALTER TABLE `destoon_admin_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_admin_online`
--

DROP TABLE IF EXISTS `destoon_admin_online`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_admin_online` (
  `sid` varchar(32) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `moduleid` int(10) unsigned NOT NULL DEFAULT '0',
  `qstring` varchar(255) NOT NULL DEFAULT '',
  `lasttime` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `sid` (`sid`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COMMENT='在线管理员';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_admin_online`
--

LOCK TABLES `destoon_admin_online` WRITE;
/*!40000 ALTER TABLE `destoon_admin_online` DISABLE KEYS */;
INSERT INTO `destoon_admin_online` VALUES ('hugr2mt5gjt55bpii6tkv13hp5','destoon','172.19.0.1',2,'moduleid=2&file=setting&tab=0',1540533344);
/*!40000 ALTER TABLE `destoon_admin_online` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_alert`
--

DROP TABLE IF EXISTS `destoon_alert`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_alert` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `word` varchar(100) NOT NULL DEFAULT '',
  `rate` smallint(4) unsigned NOT NULL DEFAULT '0',
  `email` varchar(50) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '0',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `sendtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='贸易提醒';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_alert`
--

LOCK TABLES `destoon_alert` WRITE;
/*!40000 ALTER TABLE `destoon_alert` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_alert` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_announce`
--

DROP TABLE IF EXISTS `destoon_announce`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_announce` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `fromtime` int(10) unsigned NOT NULL DEFAULT '0',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `islink` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  `template` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公告';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_announce`
--

LOCK TABLES `destoon_announce` WRITE;
/*!40000 ALTER TABLE `destoon_announce` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_announce` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_area`
--

DROP TABLE IF EXISTS `destoon_area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_area` (
  `areaid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `areaname` varchar(50) NOT NULL DEFAULT '',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0',
  `arrparentid` varchar(255) NOT NULL DEFAULT '',
  `child` tinyint(1) NOT NULL DEFAULT '0',
  `arrchildid` text NOT NULL,
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`areaid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='地区';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_area`
--

LOCK TABLES `destoon_area` WRITE;
/*!40000 ALTER TABLE `destoon_area` DISABLE KEYS */;
INSERT INTO `destoon_area` VALUES (1,'默认地区',0,'0',0,'1',1);
/*!40000 ALTER TABLE `destoon_area` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_article_21`
--

DROP TABLE IF EXISTS `destoon_article_21`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_article_21` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `fee` float NOT NULL DEFAULT '0',
  `subtitle` mediumtext NOT NULL,
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `tag` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `pptword` varchar(255) NOT NULL DEFAULT '',
  `author` varchar(50) NOT NULL DEFAULT '',
  `copyfrom` varchar(30) NOT NULL DEFAULT '',
  `fromurl` varchar(255) NOT NULL DEFAULT '',
  `voteid` varchar(100) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `islink` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `addtime` (`addtime`),
  KEY `catid` (`catid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资讯';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_article_21`
--

LOCK TABLES `destoon_article_21` WRITE;
/*!40000 ALTER TABLE `destoon_article_21` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_article_21` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_article_data_21`
--

DROP TABLE IF EXISTS `destoon_article_data_21`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_article_data_21` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` longtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资讯内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_article_data_21`
--

LOCK TABLES `destoon_article_data_21` WRITE;
/*!40000 ALTER TABLE `destoon_article_data_21` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_article_data_21` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_ask`
--

DROP TABLE IF EXISTS `destoon_ask`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_ask` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` int(10) unsigned NOT NULL DEFAULT '0',
  `qid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL,
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `reply` mediumtext NOT NULL,
  `star` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='客服中心';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_ask`
--

LOCK TABLES `destoon_ask` WRITE;
/*!40000 ALTER TABLE `destoon_ask` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_ask` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_banip`
--

DROP TABLE IF EXISTS `destoon_banip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_banip` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) NOT NULL DEFAULT '',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='IP禁止';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_banip`
--

LOCK TABLES `destoon_banip` WRITE;
/*!40000 ALTER TABLE `destoon_banip` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_banip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_banword`
--

DROP TABLE IF EXISTS `destoon_banword`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_banword` (
  `bid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `replacefrom` varchar(255) NOT NULL DEFAULT '',
  `replaceto` varchar(255) NOT NULL DEFAULT '',
  `deny` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='词语过滤';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_banword`
--

LOCK TABLES `destoon_banword` WRITE;
/*!40000 ALTER TABLE `destoon_banword` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_banword` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_brand_13`
--

DROP TABLE IF EXISTS `destoon_brand_13`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_brand_13` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `fee` float NOT NULL DEFAULT '0',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `pptword` varchar(255) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `homepage` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `groupid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `adddate` date NOT NULL DEFAULT '0000-00-00',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `company` varchar(100) NOT NULL DEFAULT '',
  `vip` smallint(2) unsigned NOT NULL DEFAULT '0',
  `validated` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `truename` varchar(30) NOT NULL DEFAULT '',
  `telephone` varchar(50) NOT NULL DEFAULT '',
  `fax` varchar(50) NOT NULL DEFAULT '',
  `mobile` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `wx` varchar(50) NOT NULL DEFAULT '',
  `ali` varchar(30) NOT NULL DEFAULT '',
  `skype` varchar(30) NOT NULL DEFAULT '',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `editdate` date NOT NULL DEFAULT '0000-00-00',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`),
  KEY `catid` (`catid`),
  KEY `areaid` (`areaid`),
  KEY `edittime` (`edittime`),
  KEY `editdate` (`editdate`,`vip`,`edittime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='品牌';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_brand_13`
--

LOCK TABLES `destoon_brand_13` WRITE;
/*!40000 ALTER TABLE `destoon_brand_13` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_brand_13` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_brand_data_13`
--

DROP TABLE IF EXISTS `destoon_brand_data_13`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_brand_data_13` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='品牌内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_brand_data_13`
--

LOCK TABLES `destoon_brand_data_13` WRITE;
/*!40000 ALTER TABLE `destoon_brand_data_13` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_brand_data_13` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_buy_6`
--

DROP TABLE IF EXISTS `destoon_buy_6`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_buy_6` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `typeid` smallint(2) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `fee` float NOT NULL DEFAULT '0',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `n1` varchar(100) NOT NULL,
  `n2` varchar(100) NOT NULL,
  `n3` varchar(100) NOT NULL,
  `v1` varchar(100) NOT NULL,
  `v2` varchar(100) NOT NULL,
  `v3` varchar(100) NOT NULL,
  `amount` varchar(10) NOT NULL DEFAULT '',
  `price` varchar(10) NOT NULL DEFAULT '',
  `pack` varchar(20) NOT NULL DEFAULT '',
  `days` smallint(3) unsigned NOT NULL DEFAULT '0',
  `tag` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `pptword` varchar(255) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `thumb1` varchar(255) NOT NULL DEFAULT '',
  `thumb2` varchar(255) NOT NULL DEFAULT '',
  `thumbs` text NOT NULL,
  `username` varchar(30) NOT NULL DEFAULT '',
  `groupid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `company` varchar(100) NOT NULL DEFAULT '',
  `vip` smallint(2) unsigned NOT NULL DEFAULT '0',
  `validated` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `truename` varchar(30) NOT NULL DEFAULT '',
  `telephone` varchar(50) NOT NULL DEFAULT '',
  `mobile` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `wx` varchar(50) NOT NULL DEFAULT '',
  `ali` varchar(30) NOT NULL DEFAULT '',
  `skype` varchar(30) NOT NULL DEFAULT '',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `editdate` date NOT NULL DEFAULT '0000-00-00',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `adddate` date NOT NULL DEFAULT '0000-00-00',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`),
  KEY `editdate` (`editdate`,`vip`,`edittime`),
  KEY `edittime` (`edittime`),
  KEY `catid` (`catid`),
  KEY `areaid` (`areaid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='求购';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_buy_6`
--

LOCK TABLES `destoon_buy_6` WRITE;
/*!40000 ALTER TABLE `destoon_buy_6` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_buy_6` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_buy_data_6`
--

DROP TABLE IF EXISTS `destoon_buy_data_6`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_buy_data_6` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='求购内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_buy_data_6`
--

LOCK TABLES `destoon_buy_data_6` WRITE;
/*!40000 ALTER TABLE `destoon_buy_data_6` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_buy_data_6` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_cache`
--

DROP TABLE IF EXISTS `destoon_cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_cache` (
  `cacheid` varchar(32) NOT NULL DEFAULT '',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `cacheid` (`cacheid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文件缓存';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_cache`
--

LOCK TABLES `destoon_cache` WRITE;
/*!40000 ALTER TABLE `destoon_cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_cart`
--

DROP TABLE IF EXISTS `destoon_cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_cart` (
  `userid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购物车';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_cart`
--

LOCK TABLES `destoon_cart` WRITE;
/*!40000 ALTER TABLE `destoon_cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_category`
--

DROP TABLE IF EXISTS `destoon_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_category` (
  `catid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `moduleid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `catname` varchar(50) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `catdir` varchar(255) NOT NULL DEFAULT '',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `letter` varchar(4) NOT NULL DEFAULT '',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `item` bigint(20) unsigned NOT NULL DEFAULT '0',
  `property` smallint(6) unsigned NOT NULL DEFAULT '0',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0',
  `arrparentid` varchar(255) NOT NULL DEFAULT '',
  `child` tinyint(1) NOT NULL DEFAULT '0',
  `arrchildid` text NOT NULL,
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  `template` varchar(30) NOT NULL DEFAULT '',
  `show_template` varchar(30) NOT NULL DEFAULT '',
  `seo_title` varchar(255) NOT NULL DEFAULT '',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '',
  `seo_description` varchar(255) NOT NULL DEFAULT '',
  `group_list` varchar(255) NOT NULL DEFAULT '',
  `group_show` varchar(255) NOT NULL DEFAULT '',
  `group_add` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`catid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='栏目分类';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_category`
--

LOCK TABLES `destoon_category` WRITE;
/*!40000 ALTER TABLE `destoon_category` DISABLE KEYS */;
INSERT INTO `destoon_category` VALUES (1,5,'供应默认分类','','1','list.php?catid=1','',1,0,0,0,'0',0,'',1,'','','','','','','',''),(2,6,'求购默认分类','','1','list.php?catid=2','',1,0,0,0,'0',0,'',1,'','','','','','','',''),(3,4,'公司默认分类','','1','list.php?catid=3','',1,0,0,0,'0',0,'',1,'','','','','','','','');
/*!40000 ALTER TABLE `destoon_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_category_option`
--

DROP TABLE IF EXISTS `destoon_category_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_category_option` (
  `oid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `required` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `search` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `extend` text NOT NULL,
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`oid`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分类属性';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_category_option`
--

LOCK TABLES `destoon_category_option` WRITE;
/*!40000 ALTER TABLE `destoon_category_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_category_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_category_value`
--

DROP TABLE IF EXISTS `destoon_category_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_category_value` (
  `oid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `moduleid` smallint(6) NOT NULL DEFAULT '0',
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `value` text NOT NULL,
  KEY `moduleid` (`moduleid`,`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分类属性值';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_category_value`
--

LOCK TABLES `destoon_category_value` WRITE;
/*!40000 ALTER TABLE `destoon_category_value` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_category_value` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_chat`
--

DROP TABLE IF EXISTS `destoon_chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_chat` (
  `chatid` varchar(32) NOT NULL,
  `fromuser` varchar(30) NOT NULL,
  `fgettime` int(10) unsigned NOT NULL DEFAULT '0',
  `freadtime` int(10) unsigned NOT NULL DEFAULT '0',
  `fnew` int(10) unsigned NOT NULL DEFAULT '0',
  `touser` varchar(30) NOT NULL,
  `tgettime` int(10) unsigned NOT NULL DEFAULT '0',
  `treadtime` int(10) unsigned NOT NULL DEFAULT '0',
  `tnew` int(10) unsigned NOT NULL DEFAULT '0',
  `lastmsg` varchar(255) NOT NULL,
  `lasttime` int(10) unsigned NOT NULL DEFAULT '0',
  `forward` varchar(255) NOT NULL,
  UNIQUE KEY `chatid` (`chatid`),
  KEY `fromuser` (`fromuser`),
  KEY `touser` (`touser`),
  KEY `lasttime` (`lasttime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='在线聊天';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_chat`
--

LOCK TABLES `destoon_chat` WRITE;
/*!40000 ALTER TABLE `destoon_chat` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_chat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_chat_data_0`
--

DROP TABLE IF EXISTS `destoon_chat_data_0`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_chat_data_0` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='聊天记录_0';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_chat_data_0`
--

LOCK TABLES `destoon_chat_data_0` WRITE;
/*!40000 ALTER TABLE `destoon_chat_data_0` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_chat_data_0` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_chat_data_1`
--

DROP TABLE IF EXISTS `destoon_chat_data_1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_chat_data_1` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='聊天记录_1';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_chat_data_1`
--

LOCK TABLES `destoon_chat_data_1` WRITE;
/*!40000 ALTER TABLE `destoon_chat_data_1` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_chat_data_1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_chat_data_2`
--

DROP TABLE IF EXISTS `destoon_chat_data_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_chat_data_2` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='聊天记录_2';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_chat_data_2`
--

LOCK TABLES `destoon_chat_data_2` WRITE;
/*!40000 ALTER TABLE `destoon_chat_data_2` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_chat_data_2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_chat_data_3`
--

DROP TABLE IF EXISTS `destoon_chat_data_3`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_chat_data_3` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='聊天记录_3';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_chat_data_3`
--

LOCK TABLES `destoon_chat_data_3` WRITE;
/*!40000 ALTER TABLE `destoon_chat_data_3` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_chat_data_3` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_chat_data_4`
--

DROP TABLE IF EXISTS `destoon_chat_data_4`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_chat_data_4` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='聊天记录_4';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_chat_data_4`
--

LOCK TABLES `destoon_chat_data_4` WRITE;
/*!40000 ALTER TABLE `destoon_chat_data_4` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_chat_data_4` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_chat_data_5`
--

DROP TABLE IF EXISTS `destoon_chat_data_5`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_chat_data_5` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='聊天记录_5';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_chat_data_5`
--

LOCK TABLES `destoon_chat_data_5` WRITE;
/*!40000 ALTER TABLE `destoon_chat_data_5` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_chat_data_5` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_chat_data_6`
--

DROP TABLE IF EXISTS `destoon_chat_data_6`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_chat_data_6` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='聊天记录_6';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_chat_data_6`
--

LOCK TABLES `destoon_chat_data_6` WRITE;
/*!40000 ALTER TABLE `destoon_chat_data_6` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_chat_data_6` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_chat_data_7`
--

DROP TABLE IF EXISTS `destoon_chat_data_7`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_chat_data_7` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='聊天记录_7';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_chat_data_7`
--

LOCK TABLES `destoon_chat_data_7` WRITE;
/*!40000 ALTER TABLE `destoon_chat_data_7` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_chat_data_7` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_chat_data_8`
--

DROP TABLE IF EXISTS `destoon_chat_data_8`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_chat_data_8` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='聊天记录_8';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_chat_data_8`
--

LOCK TABLES `destoon_chat_data_8` WRITE;
/*!40000 ALTER TABLE `destoon_chat_data_8` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_chat_data_8` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_chat_data_9`
--

DROP TABLE IF EXISTS `destoon_chat_data_9`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_chat_data_9` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chatid` varchar(32) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `chatid` (`chatid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='聊天记录_9';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_chat_data_9`
--

LOCK TABLES `destoon_chat_data_9` WRITE;
/*!40000 ALTER TABLE `destoon_chat_data_9` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_chat_data_9` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_city`
--

DROP TABLE IF EXISTS `destoon_city`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_city` (
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `iparea` mediumtext NOT NULL,
  `domain` varchar(255) NOT NULL DEFAULT '',
  `letter` varchar(4) NOT NULL DEFAULT '',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  `template` varchar(50) NOT NULL DEFAULT '',
  `seo_title` varchar(255) NOT NULL DEFAULT '',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '',
  `seo_description` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `areaid` (`areaid`),
  KEY `domain` (`domain`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='城市分站';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_city`
--

LOCK TABLES `destoon_city` WRITE;
/*!40000 ALTER TABLE `destoon_city` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_city` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_club_18`
--

DROP TABLE IF EXISTS `destoon_club_18`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_club_18` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `gid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `video` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ontop` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `fee` float NOT NULL DEFAULT '0',
  `message` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `pptword` varchar(255) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `reply` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `passport` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `replyuser` varchar(30) NOT NULL,
  `replyer` varchar(30) NOT NULL,
  `replytime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `addtime` (`addtime`),
  KEY `catid` (`catid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商圈帖子';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_club_18`
--

LOCK TABLES `destoon_club_18` WRITE;
/*!40000 ALTER TABLE `destoon_club_18` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_club_18` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_club_data_18`
--

DROP TABLE IF EXISTS `destoon_club_data_18`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_club_data_18` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` longtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商圈帖子内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_club_data_18`
--

LOCK TABLES `destoon_club_data_18` WRITE;
/*!40000 ALTER TABLE `destoon_club_data_18` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_club_data_18` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_club_fans_18`
--

DROP TABLE IF EXISTS `destoon_club_fans_18`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_club_fans_18` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `passport` varchar(30) NOT NULL,
  `reason` mediumtext NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `gid` (`gid`),
  KEY `username` (`username`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商圈粉丝';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_club_fans_18`
--

LOCK TABLES `destoon_club_fans_18` WRITE;
/*!40000 ALTER TABLE `destoon_club_fans_18` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_club_fans_18` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_club_group_18`
--

DROP TABLE IF EXISTS `destoon_club_group_18`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_club_group_18` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `style` varchar(50) NOT NULL DEFAULT '',
  `post` int(10) unsigned NOT NULL DEFAULT '0',
  `fans` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL,
  `manager` varchar(255) NOT NULL,
  `username` varchar(30) NOT NULL DEFAULT '',
  `passport` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL,
  `template` varchar(30) NOT NULL,
  `show_template` varchar(30) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `join_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `list_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `show_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `post_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `reply_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `reason` mediumtext NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`),
  KEY `addtime` (`addtime`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商圈圈子';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_club_group_18`
--

LOCK TABLES `destoon_club_group_18` WRITE;
/*!40000 ALTER TABLE `destoon_club_group_18` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_club_group_18` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_club_manage_18`
--

DROP TABLE IF EXISTS `destoon_club_manage_18`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_club_manage_18` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `tid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `rid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `typeid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `reason` mediumtext NOT NULL,
  `message` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商圈管理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_club_manage_18`
--

LOCK TABLES `destoon_club_manage_18` WRITE;
/*!40000 ALTER TABLE `destoon_club_manage_18` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_club_manage_18` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_club_reply_18`
--

DROP TABLE IF EXISTS `destoon_club_reply_18`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_club_reply_18` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `gid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  `username` varchar(30) NOT NULL DEFAULT '',
  `passport` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `tid` (`tid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商圈回复';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_club_reply_18`
--

LOCK TABLES `destoon_club_reply_18` WRITE;
/*!40000 ALTER TABLE `destoon_club_reply_18` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_club_reply_18` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_comment`
--

DROP TABLE IF EXISTS `destoon_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_comment` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_mid` smallint(6) NOT NULL DEFAULT '0',
  `item_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `item_title` varchar(255) NOT NULL DEFAULT '',
  `item_username` varchar(30) NOT NULL DEFAULT '',
  `star` tinyint(1) NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  `qid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `quotation` mediumtext NOT NULL,
  `username` varchar(30) NOT NULL DEFAULT '',
  `passport` varchar(30) NOT NULL,
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `reply` mediumtext NOT NULL,
  `editor` varchar(30) NOT NULL DEFAULT '',
  `replyer` varchar(30) NOT NULL DEFAULT '',
  `replytime` int(10) unsigned NOT NULL DEFAULT '0',
  `agree` int(10) unsigned NOT NULL DEFAULT '0',
  `against` int(10) unsigned NOT NULL DEFAULT '0',
  `quote` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `item_mid` (`item_mid`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_comment`
--

LOCK TABLES `destoon_comment` WRITE;
/*!40000 ALTER TABLE `destoon_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_comment_ban`
--

DROP TABLE IF EXISTS `destoon_comment_ban`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_comment_ban` (
  `bid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `moduleid` smallint(6) NOT NULL DEFAULT '0',
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论禁止';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_comment_ban`
--

LOCK TABLES `destoon_comment_ban` WRITE;
/*!40000 ALTER TABLE `destoon_comment_ban` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_comment_ban` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_comment_stat`
--

DROP TABLE IF EXISTS `destoon_comment_stat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_comment_stat` (
  `sid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `moduleid` smallint(6) NOT NULL DEFAULT '0',
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment` int(10) unsigned NOT NULL DEFAULT '0',
  `star1` int(10) unsigned NOT NULL DEFAULT '0',
  `star2` int(10) unsigned NOT NULL DEFAULT '0',
  `star3` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论统计';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_comment_stat`
--

LOCK TABLES `destoon_comment_stat` WRITE;
/*!40000 ALTER TABLE `destoon_comment_stat` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_comment_stat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_company`
--

DROP TABLE IF EXISTS `destoon_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_company` (
  `userid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `groupid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `company` varchar(100) NOT NULL DEFAULT '',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `validated` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `validator` varchar(100) NOT NULL DEFAULT '',
  `validtime` int(10) unsigned NOT NULL DEFAULT '0',
  `vip` smallint(2) unsigned NOT NULL DEFAULT '0',
  `vipt` smallint(2) unsigned NOT NULL DEFAULT '0',
  `vipr` smallint(2) NOT NULL DEFAULT '0',
  `type` varchar(100) NOT NULL DEFAULT '',
  `catid` varchar(100) NOT NULL DEFAULT '',
  `catids` varchar(100) NOT NULL DEFAULT '',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `mode` varchar(100) NOT NULL DEFAULT '',
  `capital` float unsigned NOT NULL DEFAULT '0',
  `regunit` varchar(15) NOT NULL DEFAULT '',
  `size` varchar(100) NOT NULL DEFAULT '',
  `regyear` varchar(4) NOT NULL DEFAULT '',
  `regcity` varchar(30) NOT NULL DEFAULT '',
  `sell` varchar(255) NOT NULL DEFAULT '',
  `buy` varchar(255) NOT NULL DEFAULT '',
  `business` varchar(255) NOT NULL DEFAULT '',
  `telephone` varchar(50) NOT NULL DEFAULT '',
  `fax` varchar(50) NOT NULL DEFAULT '',
  `mail` varchar(50) NOT NULL DEFAULT '',
  `gzh` varchar(50) NOT NULL DEFAULT '',
  `gzhqr` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `postcode` varchar(20) NOT NULL DEFAULT '',
  `homepage` varchar(255) NOT NULL DEFAULT '',
  `fromtime` int(10) unsigned NOT NULL DEFAULT '0',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `styletime` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '',
  `skin` varchar(30) NOT NULL DEFAULT '',
  `domain` varchar(100) NOT NULL DEFAULT '',
  `icp` varchar(100) NOT NULL DEFAULT '',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`userid`),
  KEY `domain` (`domain`),
  KEY `vip` (`vip`),
  KEY `areaid` (`areaid`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公司';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_company`
--

LOCK TABLES `destoon_company` WRITE;
/*!40000 ALTER TABLE `destoon_company` DISABLE KEYS */;
INSERT INTO `destoon_company` VALUES (1,'destoon',1,'DESTOON B2B网站管理系统',0,0,'',0,0,0,0,'企业单位','','',1,'',0,'人民币','','','','','','','','','','','','','','',0,0,0,'','',0,0,'','','','','','http://127.0.0.1/index.php?homepage=destoon');
/*!40000 ALTER TABLE `destoon_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_company_data`
--

DROP TABLE IF EXISTS `destoon_company_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_company_data` (
  `userid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公司内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_company_data`
--

LOCK TABLES `destoon_company_data` WRITE;
/*!40000 ALTER TABLE `destoon_company_data` DISABLE KEYS */;
INSERT INTO `destoon_company_data` VALUES (1,'');
/*!40000 ALTER TABLE `destoon_company_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_company_setting`
--

DROP TABLE IF EXISTS `destoon_company_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_company_setting` (
  `userid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `item_key` varchar(100) NOT NULL DEFAULT '',
  `item_value` text NOT NULL,
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公司设置';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_company_setting`
--

LOCK TABLES `destoon_company_setting` WRITE;
/*!40000 ALTER TABLE `destoon_company_setting` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_company_setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_cron`
--

DROP TABLE IF EXISTS `destoon_cron`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_cron` (
  `itemid` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL,
  `schedule` varchar(255) NOT NULL,
  `lasttime` int(10) unsigned NOT NULL DEFAULT '0',
  `nexttime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `note` text NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `nexttime` (`nexttime`)
) ENGINE=MyISAM AUTO_INCREMENT=101 DEFAULT CHARSET=utf8 COMMENT='计划任务';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_cron`
--

LOCK TABLES `destoon_cron` WRITE;
/*!40000 ALTER TABLE `destoon_cron` DISABLE KEYS */;
INSERT INTO `destoon_cron` VALUES (1,'更新在线状态',1,'online','10',1540533300,1540533900,0,''),(2,'内容分表创建',1,'split','0,0',1540533300,1540569600,0,''),(3,'清理过期文件缓存',0,'cache','30',1540533300,1540535100,0,''),(20,'清理过期禁止IP',0,'banip','0,10',1540533300,1540570200,0,''),(21,'清理系统临时文件',0,'temp','0,20',1540533300,1540570800,0,''),(40,'清理3天前未付款充值记录',0,'charge','1,0',1540533300,1540573200,0,''),(41,'清理30天前404日志',0,'404','1,10',1540533300,1540573800,0,''),(42,'清理30天前登录日志',0,'loginlog','1,20',1540533300,1540574400,0,''),(43,'清理30天前管理日志',0,'adminlog','1,30',1540533300,1540575000,0,''),(44,'清理30天前站内交谈',0,'chat','1,40',1540533300,1540575600,0,''),(60,'清理90天前已读信件',0,'message','2,0',0,0,1,''),(61,'清理90天前资金流水',0,'money','2,10',0,0,1,''),(62,'清理90天前积分流水',0,'credit','2,20',0,0,1,''),(63,'清理90天前短信流水',0,'sms','2,30',0,0,1,''),(64,'清理90天前短信记录',0,'smssend','2,40',0,0,1,''),(65,'清理90天前邮件记录',0,'maillog','2,50',0,0,1,'');
/*!40000 ALTER TABLE `destoon_cron` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_down_15`
--

DROP TABLE IF EXISTS `destoon_down_15`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_down_15` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `fee` float NOT NULL DEFAULT '0',
  `tag` varchar(255) NOT NULL DEFAULT '',
  `album` varchar(100) NOT NULL,
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `pptword` varchar(255) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `download` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `fileurl` varchar(255) NOT NULL DEFAULT '',
  `fileext` varchar(10) NOT NULL DEFAULT '',
  `filesize` float NOT NULL DEFAULT '0',
  `unit` varchar(10) NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`),
  KEY `addtime` (`addtime`),
  KEY `catid` (`catid`),
  KEY `album` (`album`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='下载';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_down_15`
--

LOCK TABLES `destoon_down_15` WRITE;
/*!40000 ALTER TABLE `destoon_down_15` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_down_15` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_down_data_15`
--

DROP TABLE IF EXISTS `destoon_down_data_15`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_down_data_15` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='下载内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_down_data_15`
--

LOCK TABLES `destoon_down_data_15` WRITE;
/*!40000 ALTER TABLE `destoon_down_data_15` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_down_data_15` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_exhibit_8`
--

DROP TABLE IF EXISTS `destoon_exhibit_8`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_exhibit_8` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `fee` float NOT NULL DEFAULT '0',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `pptword` varchar(255) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `orders` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `fromtime` int(10) unsigned NOT NULL DEFAULT '0',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `city` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `postcode` varchar(20) NOT NULL DEFAULT '',
  `homepage` varchar(255) NOT NULL DEFAULT '',
  `hallname` varchar(100) NOT NULL DEFAULT '',
  `sponsor` varchar(100) NOT NULL DEFAULT '',
  `undertaker` varchar(100) NOT NULL DEFAULT '',
  `truename` varchar(30) NOT NULL DEFAULT '',
  `addr` varchar(255) NOT NULL DEFAULT '',
  `telephone` varchar(100) NOT NULL DEFAULT '',
  `mobile` varchar(20) NOT NULL DEFAULT '',
  `fax` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `wx` varchar(50) NOT NULL DEFAULT '',
  `remark` mediumtext NOT NULL,
  `sign` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `addtime` (`addtime`),
  KEY `catid` (`catid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='展会';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_exhibit_8`
--

LOCK TABLES `destoon_exhibit_8` WRITE;
/*!40000 ALTER TABLE `destoon_exhibit_8` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_exhibit_8` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_exhibit_data_8`
--

DROP TABLE IF EXISTS `destoon_exhibit_data_8`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_exhibit_data_8` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='展会内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_exhibit_data_8`
--

LOCK TABLES `destoon_exhibit_data_8` WRITE;
/*!40000 ALTER TABLE `destoon_exhibit_data_8` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_exhibit_data_8` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_exhibit_sign_8`
--

DROP TABLE IF EXISTS `destoon_exhibit_sign_8`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_exhibit_sign_8` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user` varchar(30) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `amount` int(10) unsigned NOT NULL DEFAULT '0',
  `company` varchar(100) NOT NULL,
  `truename` varchar(30) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `address` varchar(255) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `qq` varchar(20) NOT NULL,
  `wx` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='展会报名';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_exhibit_sign_8`
--

LOCK TABLES `destoon_exhibit_sign_8` WRITE;
/*!40000 ALTER TABLE `destoon_exhibit_sign_8` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_exhibit_sign_8` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_favorite`
--

DROP TABLE IF EXISTS `destoon_favorite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_favorite` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `tid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  `userid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `typeid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商机收藏';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_favorite`
--

LOCK TABLES `destoon_favorite` WRITE;
/*!40000 ALTER TABLE `destoon_favorite` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_favorite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_fetch`
--

DROP TABLE IF EXISTS `destoon_fetch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_fetch` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sitename` varchar(100) NOT NULL DEFAULT '',
  `domain` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `encode` varchar(30) NOT NULL DEFAULT '',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='单页采编';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_fetch`
--

LOCK TABLES `destoon_fetch` WRITE;
/*!40000 ALTER TABLE `destoon_fetch` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_fetch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_fields`
--

DROP TABLE IF EXISTS `destoon_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_fields` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tb` varchar(30) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `length` smallint(4) unsigned NOT NULL DEFAULT '0',
  `html` varchar(30) NOT NULL DEFAULT '',
  `default_value` text NOT NULL,
  `option_value` text NOT NULL,
  `width` smallint(4) unsigned NOT NULL DEFAULT '0',
  `height` smallint(4) unsigned NOT NULL DEFAULT '0',
  `input_limit` varchar(255) NOT NULL DEFAULT '',
  `addition` varchar(255) NOT NULL DEFAULT '',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `front` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `tablename` (`tb`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='自定义字段';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_fields`
--

LOCK TABLES `destoon_fields` WRITE;
/*!40000 ALTER TABLE `destoon_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_finance_award`
--

DROP TABLE IF EXISTS `destoon_finance_award`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_finance_award` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `fee` float unsigned NOT NULL DEFAULT '0',
  `paytime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `mid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `tid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='打赏记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_finance_award`
--

LOCK TABLES `destoon_finance_award` WRITE;
/*!40000 ALTER TABLE `destoon_finance_award` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_finance_award` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_finance_card`
--

DROP TABLE IF EXISTS `destoon_finance_card`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_finance_card` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(30) NOT NULL DEFAULT '',
  `amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  UNIQUE KEY `number` (`number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='充值卡';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_finance_card`
--

LOCK TABLES `destoon_finance_card` WRITE;
/*!40000 ALTER TABLE `destoon_finance_card` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_finance_card` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_finance_cash`
--

DROP TABLE IF EXISTS `destoon_finance_cash`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_finance_cash` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `bank` varchar(50) NOT NULL DEFAULT '',
  `banktype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `branch` varchar(100) NOT NULL,
  `account` varchar(30) NOT NULL DEFAULT '',
  `truename` varchar(30) NOT NULL DEFAULT '',
  `amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `note` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='申请提现';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_finance_cash`
--

LOCK TABLES `destoon_finance_cash` WRITE;
/*!40000 ALTER TABLE `destoon_finance_cash` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_finance_cash` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_finance_charge`
--

DROP TABLE IF EXISTS `destoon_finance_charge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_finance_charge` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `bank` varchar(20) NOT NULL DEFAULT '',
  `amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `sendtime` int(10) unsigned NOT NULL DEFAULT '0',
  `receivetime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `reason` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='在线充值';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_finance_charge`
--

LOCK TABLES `destoon_finance_charge` WRITE;
/*!40000 ALTER TABLE `destoon_finance_charge` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_finance_charge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_finance_coupon`
--

DROP TABLE IF EXISTS `destoon_finance_coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_finance_coupon` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `seller` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `fromtime` int(10) unsigned NOT NULL DEFAULT '0',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `cost` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `pid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `oid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL,
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `note` varchar(255) NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='优惠券';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_finance_coupon`
--

LOCK TABLES `destoon_finance_coupon` WRITE;
/*!40000 ALTER TABLE `destoon_finance_coupon` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_finance_coupon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_finance_credit`
--

DROP TABLE IF EXISTS `destoon_finance_credit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_finance_credit` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `amount` int(10) NOT NULL DEFAULT '0',
  `balance` int(10) NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `reason` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `editor` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='积分流水';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_finance_credit`
--

LOCK TABLES `destoon_finance_credit` WRITE;
/*!40000 ALTER TABLE `destoon_finance_credit` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_finance_credit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_finance_deposit`
--

DROP TABLE IF EXISTS `destoon_finance_deposit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_finance_deposit` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL,
  `reason` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='保证金';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_finance_deposit`
--

LOCK TABLES `destoon_finance_deposit` WRITE;
/*!40000 ALTER TABLE `destoon_finance_deposit` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_finance_deposit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_finance_pay`
--

DROP TABLE IF EXISTS `destoon_finance_pay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_finance_pay` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `fee` float unsigned NOT NULL DEFAULT '0',
  `currency` varchar(20) NOT NULL DEFAULT '',
  `paytime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `mid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `tid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_finance_pay`
--

LOCK TABLES `destoon_finance_pay` WRITE;
/*!40000 ALTER TABLE `destoon_finance_pay` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_finance_pay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_finance_promo`
--

DROP TABLE IF EXISTS `destoon_finance_promo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_finance_promo` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `fromtime` int(10) unsigned NOT NULL DEFAULT '0',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `cost` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `amount` int(10) unsigned NOT NULL DEFAULT '0',
  `number` int(10) unsigned NOT NULL DEFAULT '0',
  `open` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `editor` varchar(30) NOT NULL,
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `note` varchar(255) NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='优惠促销';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_finance_promo`
--

LOCK TABLES `destoon_finance_promo` WRITE;
/*!40000 ALTER TABLE `destoon_finance_promo` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_finance_promo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_finance_record`
--

DROP TABLE IF EXISTS `destoon_finance_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_finance_record` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `bank` varchar(30) NOT NULL DEFAULT '',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `reason` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `editor` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='财务流水';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_finance_record`
--

LOCK TABLES `destoon_finance_record` WRITE;
/*!40000 ALTER TABLE `destoon_finance_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_finance_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_finance_sms`
--

DROP TABLE IF EXISTS `destoon_finance_sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_finance_sms` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `amount` int(10) NOT NULL DEFAULT '0',
  `balance` int(10) NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `reason` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `editor` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='短信增减';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_finance_sms`
--

LOCK TABLES `destoon_finance_sms` WRITE;
/*!40000 ALTER TABLE `destoon_finance_sms` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_finance_sms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_form`
--

DROP TABLE IF EXISTS `destoon_form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_form` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `groupid` varchar(255) NOT NULL,
  `verify` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `question` int(10) unsigned NOT NULL DEFAULT '0',
  `answer` int(10) unsigned NOT NULL DEFAULT '0',
  `maxanswer` int(10) unsigned NOT NULL DEFAULT '1',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `fromtime` int(10) unsigned NOT NULL DEFAULT '0',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='表单';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_form`
--

LOCK TABLES `destoon_form` WRITE;
/*!40000 ALTER TABLE `destoon_form` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_form` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_form_answer`
--

DROP TABLE IF EXISTS `destoon_form_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_form_answer` (
  `aid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `rid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `qid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  `other` varchar(255) NOT NULL,
  `item` varchar(100) NOT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='表单回复';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_form_answer`
--

LOCK TABLES `destoon_form_answer` WRITE;
/*!40000 ALTER TABLE `destoon_form_answer` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_form_answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_form_question`
--

DROP TABLE IF EXISTS `destoon_form_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_form_question` (
  `qid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` mediumtext NOT NULL,
  `required` varchar(30) NOT NULL,
  `extend` mediumtext NOT NULL,
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`qid`),
  KEY `fid` (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='表单选项';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_form_question`
--

LOCK TABLES `destoon_form_question` WRITE;
/*!40000 ALTER TABLE `destoon_form_question` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_form_question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_form_record`
--

DROP TABLE IF EXISTS `destoon_form_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_form_record` (
  `rid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `item` varchar(100) NOT NULL,
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='表单回复记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_form_record`
--

LOCK TABLES `destoon_form_record` WRITE;
/*!40000 ALTER TABLE `destoon_form_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_form_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_friend`
--

DROP TABLE IF EXISTS `destoon_friend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_friend` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  `userid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `typeid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `truename` varchar(20) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `company` varchar(100) NOT NULL DEFAULT '',
  `career` varchar(20) NOT NULL DEFAULT '',
  `telephone` varchar(20) NOT NULL DEFAULT '',
  `mobile` varchar(20) NOT NULL DEFAULT '',
  `homepage` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `wx` varchar(50) NOT NULL DEFAULT '',
  `ali` varchar(30) NOT NULL DEFAULT '',
  `skype` varchar(30) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='我的商友';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_friend`
--

LOCK TABLES `destoon_friend` WRITE;
/*!40000 ALTER TABLE `destoon_friend` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_friend` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_gift`
--

DROP TABLE IF EXISTS `destoon_gift`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_gift` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `credit` int(10) unsigned NOT NULL DEFAULT '0',
  `amount` int(10) unsigned NOT NULL DEFAULT '0',
  `groupid` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `orders` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `maxorder` int(10) unsigned NOT NULL DEFAULT '1',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `fromtime` int(10) unsigned NOT NULL DEFAULT '0',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='积分换礼';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_gift`
--

LOCK TABLES `destoon_gift` WRITE;
/*!40000 ALTER TABLE `destoon_gift` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_gift` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_gift_order`
--

DROP TABLE IF EXISTS `destoon_gift_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_gift_order` (
  `oid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `credit` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`oid`),
  KEY `itemid` (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='积分换礼订单';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_gift_order`
--

LOCK TABLES `destoon_gift_order` WRITE;
/*!40000 ALTER TABLE `destoon_gift_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_gift_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_group_17`
--

DROP TABLE IF EXISTS `destoon_group_17`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_group_17` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `fee` float NOT NULL DEFAULT '0',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `marketprice` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `savemoney` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `discount` float unsigned NOT NULL DEFAULT '0',
  `minamount` int(10) unsigned NOT NULL DEFAULT '0',
  `amount` int(10) unsigned NOT NULL DEFAULT '0',
  `logistic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `tag` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `pptword` varchar(255) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `orders` int(10) unsigned NOT NULL DEFAULT '0',
  `sales` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `groupid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `company` varchar(100) NOT NULL DEFAULT '',
  `vip` smallint(2) unsigned NOT NULL DEFAULT '0',
  `validated` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `truename` varchar(30) NOT NULL DEFAULT '',
  `telephone` varchar(50) NOT NULL DEFAULT '',
  `mobile` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `wx` varchar(50) NOT NULL DEFAULT '',
  `ali` varchar(30) NOT NULL DEFAULT '',
  `skype` varchar(30) NOT NULL DEFAULT '',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `process` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`),
  KEY `addtime` (`addtime`),
  KEY `catid` (`catid`),
  KEY `areaid` (`areaid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='团购';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_group_17`
--

LOCK TABLES `destoon_group_17` WRITE;
/*!40000 ALTER TABLE `destoon_group_17` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_group_17` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_group_data_17`
--

DROP TABLE IF EXISTS `destoon_group_data_17`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_group_data_17` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='团购内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_group_data_17`
--

LOCK TABLES `destoon_group_data_17` WRITE;
/*!40000 ALTER TABLE `destoon_group_data_17` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_group_data_17` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_group_order_17`
--

DROP TABLE IF EXISTS `destoon_group_order_17`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_group_order_17` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `buyer` varchar(30) NOT NULL DEFAULT '',
  `seller` varchar(30) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `number` int(10) unsigned NOT NULL DEFAULT '0',
  `amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `logistic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `password` varchar(6) NOT NULL DEFAULT '',
  `buyer_name` varchar(30) NOT NULL DEFAULT '',
  `buyer_address` varchar(255) NOT NULL DEFAULT '',
  `buyer_postcode` varchar(10) NOT NULL DEFAULT '',
  `buyer_mobile` varchar(30) NOT NULL DEFAULT '',
  `send_type` varchar(50) NOT NULL DEFAULT '',
  `send_no` varchar(50) NOT NULL DEFAULT '',
  `send_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `send_time` varchar(20) NOT NULL DEFAULT '',
  `send_days` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` smallint(6) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `buyer_reason` mediumtext NOT NULL,
  `refund_reason` mediumtext NOT NULL,
  `note` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `buyer` (`buyer`),
  KEY `seller` (`seller`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='团购订单';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_group_order_17`
--

LOCK TABLES `destoon_group_order_17` WRITE;
/*!40000 ALTER TABLE `destoon_group_order_17` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_group_order_17` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_guestbook`
--

DROP TABLE IF EXISTS `destoon_guestbook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_guestbook` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `reply` text NOT NULL,
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `truename` varchar(30) NOT NULL DEFAULT '',
  `telephone` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `wx` varchar(50) NOT NULL DEFAULT '',
  `ali` varchar(30) NOT NULL DEFAULT '',
  `skype` varchar(30) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='留言本';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_guestbook`
--

LOCK TABLES `destoon_guestbook` WRITE;
/*!40000 ALTER TABLE `destoon_guestbook` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_guestbook` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_honor`
--

DROP TABLE IF EXISTS `destoon_honor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_honor` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `authority` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `fromtime` int(10) unsigned NOT NULL DEFAULT '0',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='荣誉资质';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_honor`
--

LOCK TABLES `destoon_honor` WRITE;
/*!40000 ALTER TABLE `destoon_honor` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_honor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_info_22`
--

DROP TABLE IF EXISTS `destoon_info_22`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_info_22` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `fee` float NOT NULL DEFAULT '0',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `pptword` varchar(255) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `thumb1` varchar(255) NOT NULL DEFAULT '',
  `thumb2` varchar(255) NOT NULL DEFAULT '',
  `thumbs` text NOT NULL,
  `username` varchar(30) NOT NULL DEFAULT '',
  `groupid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `adddate` date NOT NULL DEFAULT '0000-00-00',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `company` varchar(100) NOT NULL DEFAULT '',
  `vip` smallint(2) unsigned NOT NULL DEFAULT '0',
  `validated` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `truename` varchar(30) NOT NULL DEFAULT '',
  `telephone` varchar(50) NOT NULL DEFAULT '',
  `fax` varchar(50) NOT NULL DEFAULT '',
  `mobile` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `wx` varchar(50) NOT NULL DEFAULT '',
  `ali` varchar(30) NOT NULL DEFAULT '',
  `skype` varchar(30) NOT NULL DEFAULT '',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `n1` varchar(100) NOT NULL,
  `n2` varchar(100) NOT NULL,
  `n3` varchar(100) NOT NULL,
  `v1` varchar(100) NOT NULL,
  `v2` varchar(100) NOT NULL,
  `v3` varchar(100) NOT NULL,
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `editdate` date NOT NULL DEFAULT '0000-00-00',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  `islink` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`),
  KEY `edittime` (`edittime`),
  KEY `catid` (`catid`),
  KEY `areaid` (`areaid`),
  KEY `editdate` (`editdate`,`vip`,`edittime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='招商';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_info_22`
--

LOCK TABLES `destoon_info_22` WRITE;
/*!40000 ALTER TABLE `destoon_info_22` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_info_22` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_info_data_22`
--

DROP TABLE IF EXISTS `destoon_info_data_22`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_info_data_22` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='招商内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_info_data_22`
--

LOCK TABLES `destoon_info_data_22` WRITE;
/*!40000 ALTER TABLE `destoon_info_data_22` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_info_data_22` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_job_9`
--

DROP TABLE IF EXISTS `destoon_job_9`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_job_9` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `fee` float NOT NULL DEFAULT '0',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `pptword` varchar(255) NOT NULL DEFAULT '',
  `department` varchar(100) NOT NULL DEFAULT '',
  `total` smallint(4) unsigned NOT NULL DEFAULT '0',
  `minsalary` int(10) unsigned NOT NULL DEFAULT '0',
  `maxsalary` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `marriage` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `education` smallint(2) unsigned NOT NULL DEFAULT '0',
  `experience` smallint(2) unsigned NOT NULL DEFAULT '0',
  `minage` smallint(2) unsigned NOT NULL DEFAULT '0',
  `maxage` smallint(2) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL,
  `apply` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `groupid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `company` varchar(100) NOT NULL DEFAULT '',
  `vip` smallint(2) unsigned NOT NULL DEFAULT '0',
  `validated` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `truename` varchar(30) NOT NULL DEFAULT '',
  `telephone` varchar(50) NOT NULL DEFAULT '',
  `mobile` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `wx` varchar(50) NOT NULL DEFAULT '',
  `ali` varchar(30) NOT NULL DEFAULT '',
  `skype` varchar(30) NOT NULL DEFAULT '',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `editdate` date NOT NULL DEFAULT '0000-00-00',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `adddate` date NOT NULL DEFAULT '0000-00-00',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`),
  KEY `editdate` (`editdate`,`vip`,`edittime`),
  KEY `edittime` (`edittime`),
  KEY `catid` (`catid`),
  KEY `areaid` (`areaid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='招聘';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_job_9`
--

LOCK TABLES `destoon_job_9` WRITE;
/*!40000 ALTER TABLE `destoon_job_9` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_job_9` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_job_apply_9`
--

DROP TABLE IF EXISTS `destoon_job_apply_9`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_job_apply_9` (
  `applyid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `jobid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `resumeid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `job_username` varchar(30) NOT NULL DEFAULT '',
  `apply_username` varchar(30) NOT NULL DEFAULT '',
  `applytime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`applyid`),
  KEY `job_username` (`job_username`),
  KEY `apply_username` (`apply_username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='应聘工作';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_job_apply_9`
--

LOCK TABLES `destoon_job_apply_9` WRITE;
/*!40000 ALTER TABLE `destoon_job_apply_9` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_job_apply_9` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_job_data_9`
--

DROP TABLE IF EXISTS `destoon_job_data_9`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_job_data_9` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='招聘内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_job_data_9`
--

LOCK TABLES `destoon_job_data_9` WRITE;
/*!40000 ALTER TABLE `destoon_job_data_9` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_job_data_9` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_job_resume_9`
--

DROP TABLE IF EXISTS `destoon_job_resume_9`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_job_resume_9` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `fee` float NOT NULL DEFAULT '0',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `truename` varchar(30) NOT NULL DEFAULT '',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `birthday` date NOT NULL DEFAULT '0000-00-00',
  `age` smallint(2) unsigned NOT NULL DEFAULT '0',
  `marriage` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `height` smallint(2) unsigned NOT NULL DEFAULT '0',
  `weight` smallint(2) unsigned NOT NULL DEFAULT '0',
  `education` smallint(2) unsigned NOT NULL DEFAULT '0',
  `school` varchar(100) NOT NULL DEFAULT '',
  `major` varchar(100) NOT NULL DEFAULT '',
  `skill` varchar(255) NOT NULL DEFAULT '',
  `language` varchar(255) NOT NULL DEFAULT '',
  `minsalary` int(10) unsigned NOT NULL DEFAULT '0',
  `maxsalary` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `experience` smallint(2) unsigned NOT NULL DEFAULT '0',
  `mobile` varchar(50) NOT NULL DEFAULT '',
  `telephone` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `wx` varchar(50) NOT NULL DEFAULT '',
  `ali` varchar(30) NOT NULL DEFAULT '',
  `skype` varchar(30) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '0',
  `situation` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `open` tinyint(1) NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`),
  KEY `edittime` (`edittime`),
  KEY `catid` (`catid`),
  KEY `areaid` (`areaid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='简历';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_job_resume_9`
--

LOCK TABLES `destoon_job_resume_9` WRITE;
/*!40000 ALTER TABLE `destoon_job_resume_9` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_job_resume_9` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_job_resume_data_9`
--

DROP TABLE IF EXISTS `destoon_job_resume_data_9`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_job_resume_data_9` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='简历内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_job_resume_data_9`
--

LOCK TABLES `destoon_job_resume_data_9` WRITE;
/*!40000 ALTER TABLE `destoon_job_resume_data_9` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_job_resume_data_9` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_job_talent_9`
--

DROP TABLE IF EXISTS `destoon_job_talent_9`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_job_talent_9` (
  `talentid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `resumeid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `jointime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`talentid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='人才库';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_job_talent_9`
--

LOCK TABLES `destoon_job_talent_9` WRITE;
/*!40000 ALTER TABLE `destoon_job_talent_9` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_job_talent_9` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_keylink`
--

DROP TABLE IF EXISTS `destoon_keylink`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_keylink` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `item` varchar(20) NOT NULL DEFAULT '',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `item` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='关联链接';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_keylink`
--

LOCK TABLES `destoon_keylink` WRITE;
/*!40000 ALTER TABLE `destoon_keylink` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_keylink` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_keyword`
--

DROP TABLE IF EXISTS `destoon_keyword`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_keyword` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `moduleid` smallint(6) NOT NULL DEFAULT '0',
  `word` varchar(255) NOT NULL DEFAULT '',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `letter` varchar(255) NOT NULL DEFAULT '',
  `items` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `total_search` int(10) unsigned NOT NULL DEFAULT '0',
  `month_search` int(10) unsigned NOT NULL DEFAULT '0',
  `week_search` int(10) unsigned NOT NULL DEFAULT '0',
  `today_search` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '3',
  PRIMARY KEY (`itemid`),
  KEY `moduleid` (`moduleid`),
  KEY `word` (`word`),
  KEY `letter` (`letter`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='关键词';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_keyword`
--

LOCK TABLES `destoon_keyword` WRITE;
/*!40000 ALTER TABLE `destoon_keyword` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_keyword` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_know_10`
--

DROP TABLE IF EXISTS `destoon_know_10`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_know_10` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `fee` float NOT NULL DEFAULT '0',
  `credit` int(10) unsigned NOT NULL DEFAULT '0',
  `aid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `process` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `message` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `addition` mediumtext NOT NULL,
  `comment` mediumtext NOT NULL,
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `pptword` varchar(255) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `raise` int(10) unsigned NOT NULL DEFAULT '0',
  `agree` int(10) unsigned NOT NULL DEFAULT '0',
  `against` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `answer` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `passport` varchar(30) NOT NULL,
  `ask` varchar(30) NOT NULL,
  `expert` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `addtime` (`addtime`),
  KEY `catid` (`catid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='知道';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_know_10`
--

LOCK TABLES `destoon_know_10` WRITE;
/*!40000 ALTER TABLE `destoon_know_10` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_know_10` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_know_answer_10`
--

DROP TABLE IF EXISTS `destoon_know_answer_10`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_know_answer_10` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `qid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `vote` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `passport` varchar(30) NOT NULL,
  `expert` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `qid` (`qid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='知道回答';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_know_answer_10`
--

LOCK TABLES `destoon_know_answer_10` WRITE;
/*!40000 ALTER TABLE `destoon_know_answer_10` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_know_answer_10` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_know_data_10`
--

DROP TABLE IF EXISTS `destoon_know_data_10`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_know_data_10` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` longtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='知道内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_know_data_10`
--

LOCK TABLES `destoon_know_data_10` WRITE;
/*!40000 ALTER TABLE `destoon_know_data_10` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_know_data_10` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_know_expert_10`
--

DROP TABLE IF EXISTS `destoon_know_expert_10`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_know_expert_10` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `style` varchar(50) NOT NULL DEFAULT '',
  `major` varchar(255) NOT NULL,
  `ask` int(10) unsigned NOT NULL DEFAULT '0',
  `answer` int(10) unsigned NOT NULL DEFAULT '0',
  `best` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `passport` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `introduce` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='知道专家';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_know_expert_10`
--

LOCK TABLES `destoon_know_expert_10` WRITE;
/*!40000 ALTER TABLE `destoon_know_expert_10` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_know_expert_10` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_know_vote_10`
--

DROP TABLE IF EXISTS `destoon_know_vote_10`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_know_vote_10` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `qid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `aid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `passport` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='知道投票';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_know_vote_10`
--

LOCK TABLES `destoon_know_vote_10` WRITE;
/*!40000 ALTER TABLE `destoon_know_vote_10` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_know_vote_10` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_link`
--

DROP TABLE IF EXISTS `destoon_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_link` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(4) NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`),
  KEY `listorder` (`listorder`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='友情链接';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_link`
--

LOCK TABLES `destoon_link` WRITE;
/*!40000 ALTER TABLE `destoon_link` DISABLE KEYS */;
INSERT INTO `destoon_link` VALUES (1,0,0,'DESTOON B2B','','http://static.destoon.com/logo.gif','DESTOON B2B网站管理系统','',1540533277,'destoon',1540533277,0,1,3,'http://www.destoon.com/');
/*!40000 ALTER TABLE `destoon_link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_login`
--

DROP TABLE IF EXISTS `destoon_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_login` (
  `logid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `passsalt` varchar(8) NOT NULL,
  `admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `loginip` varchar(50) NOT NULL DEFAULT '',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  `message` varchar(255) NOT NULL DEFAULT '',
  `agent` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`logid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='登录日志';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_login`
--

LOCK TABLES `destoon_login` WRITE;
/*!40000 ALTER TABLE `destoon_login` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_mail`
--

DROP TABLE IF EXISTS `destoon_mail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_mail` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `sendtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邮件订阅';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_mail`
--

LOCK TABLES `destoon_mail` WRITE;
/*!40000 ALTER TABLE `destoon_mail` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_mail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_mail_list`
--

DROP TABLE IF EXISTS `destoon_mail_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_mail_list` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `typeids` varchar(255) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订阅列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_mail_list`
--

LOCK TABLES `destoon_mail_list` WRITE;
/*!40000 ALTER TABLE `destoon_mail_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_mail_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_mail_log`
--

DROP TABLE IF EXISTS `destoon_mail_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_mail_log` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邮件记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_mail_log`
--

LOCK TABLES `destoon_mail_log` WRITE;
/*!40000 ALTER TABLE `destoon_mail_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_mail_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_mall_16`
--

DROP TABLE IF EXISTS `destoon_mall_16`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_mall_16` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `mycatid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `elite` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `fee` float NOT NULL DEFAULT '0',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `brand` varchar(100) NOT NULL DEFAULT '',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `step` mediumtext NOT NULL,
  `amount` int(10) unsigned NOT NULL DEFAULT '0',
  `unit` varchar(20) NOT NULL,
  `tag` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `pptword` varchar(255) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `orders` int(10) unsigned NOT NULL DEFAULT '0',
  `sales` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `thumb1` varchar(255) NOT NULL DEFAULT '',
  `thumb2` varchar(255) NOT NULL DEFAULT '',
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
  `express_1` int(10) unsigned NOT NULL DEFAULT '0',
  `express_name_1` varchar(100) NOT NULL,
  `fee_start_1` decimal(10,2) unsigned NOT NULL,
  `fee_step_1` decimal(10,2) unsigned NOT NULL,
  `express_2` int(10) unsigned NOT NULL DEFAULT '0',
  `express_name_2` varchar(100) NOT NULL,
  `fee_start_2` decimal(10,2) unsigned NOT NULL,
  `fee_step_2` decimal(10,2) unsigned NOT NULL,
  `express_3` int(10) unsigned NOT NULL DEFAULT '0',
  `express_name_3` varchar(100) NOT NULL,
  `fee_start_3` decimal(10,2) unsigned NOT NULL,
  `fee_step_3` decimal(10,2) unsigned NOT NULL,
  `cod` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `groupid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `company` varchar(100) NOT NULL DEFAULT '',
  `vip` smallint(2) unsigned NOT NULL DEFAULT '0',
  `validated` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `truename` varchar(30) NOT NULL DEFAULT '',
  `telephone` varchar(50) NOT NULL DEFAULT '',
  `mobile` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `wx` varchar(50) NOT NULL DEFAULT '',
  `ali` varchar(30) NOT NULL DEFAULT '',
  `skype` varchar(30) NOT NULL DEFAULT '',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `editdate` date NOT NULL DEFAULT '0000-00-00',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `adddate` date NOT NULL DEFAULT '0000-00-00',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`),
  KEY `editdate` (`editdate`,`vip`,`edittime`),
  KEY `catid` (`catid`),
  KEY `areaid` (`areaid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商城';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_mall_16`
--

LOCK TABLES `destoon_mall_16` WRITE;
/*!40000 ALTER TABLE `destoon_mall_16` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_mall_16` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_mall_comment_16`
--

DROP TABLE IF EXISTS `destoon_mall_comment_16`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_mall_comment_16` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `mallid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `buyer` varchar(30) NOT NULL DEFAULT '',
  `seller` varchar(30) NOT NULL DEFAULT '',
  `buyer_star` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `buyer_comment` text NOT NULL,
  `buyer_ctime` int(10) unsigned NOT NULL DEFAULT '0',
  `buyer_reply` text NOT NULL,
  `buyer_rtime` int(10) unsigned NOT NULL DEFAULT '0',
  `seller_star` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `seller_comment` text NOT NULL,
  `seller_ctime` int(10) unsigned NOT NULL DEFAULT '0',
  `seller_reply` text NOT NULL,
  `seller_rtime` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `itemid` (`itemid`),
  KEY `buyer` (`buyer`),
  KEY `seller` (`seller`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单评论';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_mall_comment_16`
--

LOCK TABLES `destoon_mall_comment_16` WRITE;
/*!40000 ALTER TABLE `destoon_mall_comment_16` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_mall_comment_16` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_mall_data_16`
--

DROP TABLE IF EXISTS `destoon_mall_data_16`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_mall_data_16` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商城内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_mall_data_16`
--

LOCK TABLES `destoon_mall_data_16` WRITE;
/*!40000 ALTER TABLE `destoon_mall_data_16` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_mall_data_16` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_mall_express_16`
--

DROP TABLE IF EXISTS `destoon_mall_express_16`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_mall_express_16` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL,
  `express` varchar(30) NOT NULL,
  `fee_start` decimal(10,2) unsigned NOT NULL,
  `fee_step` decimal(10,2) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `items` int(10) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  `note` varchar(255) NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='运费模板';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_mall_express_16`
--

LOCK TABLES `destoon_mall_express_16` WRITE;
/*!40000 ALTER TABLE `destoon_mall_express_16` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_mall_express_16` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_mall_stat_16`
--

DROP TABLE IF EXISTS `destoon_mall_stat_16`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_mall_stat_16` (
  `mallid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `seller` varchar(30) NOT NULL DEFAULT '',
  `scomment` int(10) unsigned NOT NULL DEFAULT '0',
  `s1` int(10) unsigned NOT NULL DEFAULT '0',
  `s2` int(10) unsigned NOT NULL DEFAULT '0',
  `s3` int(10) unsigned NOT NULL DEFAULT '0',
  `buyer` varchar(30) NOT NULL DEFAULT '',
  `bcomment` int(10) unsigned NOT NULL DEFAULT '0',
  `b1` int(10) unsigned NOT NULL DEFAULT '0',
  `b2` int(10) unsigned NOT NULL DEFAULT '0',
  `b3` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `mallid` (`mallid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评分统计';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_mall_stat_16`
--

LOCK TABLES `destoon_mall_stat_16` WRITE;
/*!40000 ALTER TABLE `destoon_mall_stat_16` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_mall_stat_16` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_mall_view_16`
--

DROP TABLE IF EXISTS `destoon_mall_view_16`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_mall_view_16` (
  `uid` varchar(50) NOT NULL,
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `seller` varchar(30) NOT NULL,
  `lasttime` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `uid` (`uid`),
  KEY `username` (`username`),
  KEY `lasttime` (`lasttime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='浏览历史';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_mall_view_16`
--

LOCK TABLES `destoon_mall_view_16` WRITE;
/*!40000 ALTER TABLE `destoon_mall_view_16` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_mall_view_16` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_member`
--

DROP TABLE IF EXISTS `destoon_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_member` (
  `userid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `passport` varchar(30) NOT NULL DEFAULT '',
  `company` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `passsalt` varchar(8) NOT NULL,
  `payword` varchar(32) NOT NULL DEFAULT '',
  `paysalt` varchar(8) NOT NULL,
  `email` varchar(50) NOT NULL DEFAULT '',
  `message` smallint(6) unsigned NOT NULL DEFAULT '0',
  `chat` smallint(6) unsigned NOT NULL DEFAULT '0',
  `sound` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `online` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `avatar` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `truename` varchar(20) NOT NULL DEFAULT '',
  `mobile` varchar(50) NOT NULL DEFAULT '',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `wx` varchar(50) NOT NULL DEFAULT '',
  `wxqr` varchar(255) NOT NULL DEFAULT '',
  `ali` varchar(30) NOT NULL DEFAULT '',
  `skype` varchar(30) NOT NULL DEFAULT '',
  `department` varchar(30) NOT NULL DEFAULT '',
  `career` varchar(30) NOT NULL DEFAULT '',
  `admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `role` varchar(255) NOT NULL DEFAULT '',
  `aid` int(10) unsigned NOT NULL DEFAULT '0',
  `groupid` smallint(4) unsigned NOT NULL DEFAULT '4',
  `regid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `sms` int(10) NOT NULL DEFAULT '0',
  `credit` int(10) NOT NULL DEFAULT '0',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `deposit` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `regip` varchar(50) NOT NULL DEFAULT '',
  `regtime` int(10) unsigned NOT NULL DEFAULT '0',
  `loginip` varchar(50) NOT NULL DEFAULT '',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  `logintimes` int(10) unsigned NOT NULL DEFAULT '1',
  `send` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `vemail` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `vmobile` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `vtruename` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `vbank` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `vcompany` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `vtrade` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `trade` varchar(50) NOT NULL DEFAULT '',
  `support` varchar(50) NOT NULL DEFAULT '',
  `inviter` varchar(30) NOT NULL DEFAULT '',
  `note` text NOT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `passport` (`passport`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='会员';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_member`
--

LOCK TABLES `destoon_member` WRITE;
/*!40000 ALTER TABLE `destoon_member` DISABLE KEYS */;
INSERT INTO `destoon_member` VALUES (1,'destoon','destoon','DESTOON B2B网站管理系统','55b7823522f23ee929e43a4977340a61','E1k9YvUP','3e9835fb3eef42c32504b4d63a551021','gxyEBRjB','mail@yourdomain.com',0,0,0,0,1,1,'姓名','','','','','','','','',1,'',0,1,6,1,0,0,0.00,0.00,1445261241,'172.19.0.1',1540533277,'172.19.0.1',1540533300,2,1,1,1,1,0,0,0,'','','','');
/*!40000 ALTER TABLE `destoon_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_member_check`
--

DROP TABLE IF EXISTS `destoon_member_check`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_member_check` (
  `userid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员资料审核';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_member_check`
--

LOCK TABLES `destoon_member_check` WRITE;
/*!40000 ALTER TABLE `destoon_member_check` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_member_check` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_member_group`
--

DROP TABLE IF EXISTS `destoon_member_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_member_group` (
  `groupid` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `groupname` varchar(50) NOT NULL DEFAULT '',
  `vip` smallint(2) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`groupid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='会员组';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_member_group`
--

LOCK TABLES `destoon_member_group` WRITE;
/*!40000 ALTER TABLE `destoon_member_group` DISABLE KEYS */;
INSERT INTO `destoon_member_group` VALUES (1,'管理员',0,1),(2,'禁止访问',0,2),(3,'游客',0,3),(4,'待审核会员',0,4),(5,'个人会员',0,5),(6,'企业会员',0,6),(7,'VIP会员',1,7);
/*!40000 ALTER TABLE `destoon_member_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_member_misc`
--

DROP TABLE IF EXISTS `destoon_member_misc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_member_misc` (
  `userid` bigint(20) unsigned NOT NULL,
  `username` varchar(30) NOT NULL DEFAULT '',
  `bank` varchar(30) NOT NULL DEFAULT '',
  `banktype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `branch` varchar(100) NOT NULL,
  `account` varchar(30) NOT NULL DEFAULT '',
  `reply` text NOT NULL,
  `black` text NOT NULL,
  `send` tinyint(1) unsigned NOT NULL DEFAULT '1',
  UNIQUE KEY `userid` (`userid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员杂项';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_member_misc`
--

LOCK TABLES `destoon_member_misc` WRITE;
/*!40000 ALTER TABLE `destoon_member_misc` DISABLE KEYS */;
INSERT INTO `destoon_member_misc` VALUES (1,'destoon','',0,'','','','',1);
/*!40000 ALTER TABLE `destoon_member_misc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_message`
--

DROP TABLE IF EXISTS `destoon_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_message` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `typeid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `fromuser` varchar(30) NOT NULL DEFAULT '',
  `touser` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `isread` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `issend` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `feedback` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `groupids` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `touser` (`touser`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='站内信件';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_message`
--

LOCK TABLES `destoon_message` WRITE;
/*!40000 ALTER TABLE `destoon_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_module`
--

DROP TABLE IF EXISTS `destoon_module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_module` (
  `moduleid` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(20) NOT NULL DEFAULT '',
  `moduledir` varchar(20) NOT NULL DEFAULT '',
  `domain` varchar(255) NOT NULL DEFAULT '',
  `mobile` varchar(255) NOT NULL DEFAULT '',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  `islink` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isblank` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `logo` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `installtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`moduleid`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='模型';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_module`
--

LOCK TABLES `destoon_module` WRITE;
/*!40000 ALTER TABLE `destoon_module` DISABLE KEYS */;
INSERT INTO `destoon_module` VALUES (1,'destoon','核心','','','','http://127.0.0.1/','',1,0,0,0,0,0,1540533277),(2,'member','会员','member','','','http://127.0.0.1/member/','',2,0,0,0,0,0,1540533277),(3,'extend','扩展','extend','','','http://127.0.0.1/extend/','',0,0,0,0,0,0,1540533277),(4,'company','公司','company','','','http://127.0.0.1/company/','',7,0,1,0,0,0,1540533277),(5,'sell','供应','sell','','','http://127.0.0.1/sell/','',5,0,1,0,0,0,1540533277),(6,'buy','求购','buy','','','http://127.0.0.1/buy/','',6,0,1,0,0,0,1540533277),(7,'quote','行情','quote','','','http://127.0.0.1/quote/','',9,0,1,0,0,0,1540533277),(8,'exhibit','展会','exhibit','','','http://127.0.0.1/exhibit/','',10,0,1,0,0,0,1540533277),(9,'job','人才','job','','','http://127.0.0.1/job/','',14,0,1,0,0,0,1540533277),(10,'know','知道','know','','','http://127.0.0.1/know/','',15,0,1,0,0,0,1540533277),(11,'special','专题','special','','','http://127.0.0.1/special/','',16,0,1,0,0,0,1540533277),(12,'photo','图库','photo','','','http://127.0.0.1/photo/','',17,0,1,0,0,0,1540533277),(13,'brand','品牌','brand','','','http://127.0.0.1/brand/','',13,0,1,0,0,0,1540533277),(14,'video','视频','video','','','http://127.0.0.1/video/','',18,0,1,0,0,0,1540533277),(15,'down','下载','down','','','http://127.0.0.1/down/','',19,0,1,0,0,0,1540533277),(16,'mall','商城','mall','','','http://127.0.0.1/mall/','',4,0,1,0,0,0,1540533277),(17,'group','团购','group','','','http://127.0.0.1/group/','',8,0,1,0,0,0,1540533277),(18,'club','商圈','club','','','http://127.0.0.1/club/','',20,0,1,0,0,0,1540533277),(21,'article','资讯','news','','','http://127.0.0.1/news/','',11,0,1,0,0,0,1540533277),(22,'info','招商','invest','','','http://127.0.0.1/invest/','',12,0,1,0,0,0,1540533277);
/*!40000 ALTER TABLE `destoon_module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_news`
--

DROP TABLE IF EXISTS `destoon_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_news` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL,
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公司新闻';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_news`
--

LOCK TABLES `destoon_news` WRITE;
/*!40000 ALTER TABLE `destoon_news` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_news_data`
--

DROP TABLE IF EXISTS `destoon_news_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_news_data` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公司新闻内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_news_data`
--

LOCK TABLES `destoon_news_data` WRITE;
/*!40000 ALTER TABLE `destoon_news_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_news_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_oauth`
--

DROP TABLE IF EXISTS `destoon_oauth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_oauth` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `site` varchar(30) NOT NULL DEFAULT '',
  `openid` varchar(255) NOT NULL DEFAULT '',
  `nickname` varchar(255) NOT NULL DEFAULT '',
  `avatar` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `logintimes` int(10) unsigned NOT NULL DEFAULT '0',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`),
  KEY `site` (`site`,`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='一键登录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_oauth`
--

LOCK TABLES `destoon_oauth` WRITE;
/*!40000 ALTER TABLE `destoon_oauth` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_oauth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_online`
--

DROP TABLE IF EXISTS `destoon_online`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_online` (
  `userid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `moduleid` int(10) unsigned NOT NULL DEFAULT '0',
  `online` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `lasttime` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COMMENT='在线会员';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_online`
--

LOCK TABLES `destoon_online` WRITE;
/*!40000 ALTER TABLE `destoon_online` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_online` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_order`
--

DROP TABLE IF EXISTS `destoon_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_order` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mid` smallint(6) unsigned NOT NULL DEFAULT '16',
  `mallid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `pid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `cid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `buyer` varchar(30) NOT NULL DEFAULT '',
  `seller` varchar(30) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `number` int(10) unsigned NOT NULL DEFAULT '0',
  `amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fee_name` varchar(30) NOT NULL DEFAULT '',
  `buyer_name` varchar(30) NOT NULL DEFAULT '',
  `buyer_address` varchar(255) NOT NULL DEFAULT '',
  `buyer_postcode` varchar(10) NOT NULL DEFAULT '',
  `buyer_mobile` varchar(30) NOT NULL DEFAULT '',
  `buyer_star` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `seller_star` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `send_type` varchar(50) NOT NULL DEFAULT '',
  `send_no` varchar(50) NOT NULL DEFAULT '',
  `send_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `send_time` varchar(20) NOT NULL DEFAULT '',
  `send_days` int(10) unsigned NOT NULL DEFAULT '0',
  `cod` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `trade_no` varchar(50) NOT NULL DEFAULT '',
  `add_time` smallint(6) NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `buyer_reason` mediumtext NOT NULL,
  `refund_reason` mediumtext NOT NULL,
  `note` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `buyer` (`buyer`),
  KEY `seller` (`seller`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_order`
--

LOCK TABLES `destoon_order` WRITE;
/*!40000 ALTER TABLE `destoon_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_page`
--

DROP TABLE IF EXISTS `destoon_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_page` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公司单页';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_page`
--

LOCK TABLES `destoon_page` WRITE;
/*!40000 ALTER TABLE `destoon_page` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_page_data`
--

DROP TABLE IF EXISTS `destoon_page_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_page_data` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公司单页内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_page_data`
--

LOCK TABLES `destoon_page_data` WRITE;
/*!40000 ALTER TABLE `destoon_page_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_page_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_photo_12`
--

DROP TABLE IF EXISTS `destoon_photo_12`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_photo_12` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `fee` float NOT NULL DEFAULT '0',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `pptword` varchar(255) NOT NULL DEFAULT '',
  `items` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `open` tinyint(1) unsigned NOT NULL DEFAULT '3',
  `password` varchar(30) NOT NULL DEFAULT '',
  `question` varchar(30) NOT NULL DEFAULT '',
  `answer` varchar(30) NOT NULL DEFAULT '',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `addtime` (`addtime`),
  KEY `catid` (`catid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图库';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_photo_12`
--

LOCK TABLES `destoon_photo_12` WRITE;
/*!40000 ALTER TABLE `destoon_photo_12` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_photo_12` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_photo_data_12`
--

DROP TABLE IF EXISTS `destoon_photo_data_12`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_photo_data_12` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` longtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图库内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_photo_data_12`
--

LOCK TABLES `destoon_photo_data_12` WRITE;
/*!40000 ALTER TABLE `destoon_photo_data_12` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_photo_data_12` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_photo_item_12`
--

DROP TABLE IF EXISTS `destoon_photo_item_12`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_photo_item_12` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item` bigint(20) unsigned NOT NULL DEFAULT '0',
  `introduce` text NOT NULL,
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `listorder` (`listorder`),
  KEY `item` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图库图片';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_photo_item_12`
--

LOCK TABLES `destoon_photo_item_12` WRITE;
/*!40000 ALTER TABLE `destoon_photo_item_12` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_photo_item_12` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_poll`
--

DROP TABLE IF EXISTS `destoon_poll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_poll` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `groupid` varchar(255) NOT NULL,
  `verify` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `thumb_width` smallint(6) unsigned NOT NULL DEFAULT '0',
  `thumb_height` smallint(6) unsigned NOT NULL DEFAULT '0',
  `poll_max` smallint(6) unsigned NOT NULL DEFAULT '0',
  `poll_page` smallint(6) unsigned NOT NULL DEFAULT '0',
  `poll_cols` smallint(6) unsigned NOT NULL DEFAULT '0',
  `poll_order` smallint(6) unsigned NOT NULL DEFAULT '0',
  `polls` int(10) unsigned NOT NULL DEFAULT '0',
  `items` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `fromtime` int(10) unsigned NOT NULL DEFAULT '0',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `template_poll` varchar(30) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='票选';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_poll`
--

LOCK TABLES `destoon_poll` WRITE;
/*!40000 ALTER TABLE `destoon_poll` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_poll` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_poll_item`
--

DROP TABLE IF EXISTS `destoon_poll_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_poll_item` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pollid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `polls` int(10) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `pollid` (`pollid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='票选选项';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_poll_item`
--

LOCK TABLES `destoon_poll_item` WRITE;
/*!40000 ALTER TABLE `destoon_poll_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_poll_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_poll_record`
--

DROP TABLE IF EXISTS `destoon_poll_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_poll_record` (
  `rid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `pollid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `polltime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='票选记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_poll_record`
--

LOCK TABLES `destoon_poll_record` WRITE;
/*!40000 ALTER TABLE `destoon_poll_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_poll_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_question`
--

DROP TABLE IF EXISTS `destoon_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_question` (
  `qid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL DEFAULT '',
  `answer` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`qid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='验证问题';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_question`
--

LOCK TABLES `destoon_question` WRITE;
/*!40000 ALTER TABLE `destoon_question` DISABLE KEYS */;
INSERT INTO `destoon_question` VALUES (1,'5+6=?','11'),(2,'7+8=?','15'),(3,'11*11=?','121'),(4,'12-5=?','7'),(5,'21-9=?','12');
/*!40000 ALTER TABLE `destoon_question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_quote_7`
--

DROP TABLE IF EXISTS `destoon_quote_7`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_quote_7` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `fee` float NOT NULL DEFAULT '0',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `tag` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `pptword` varchar(255) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `adddate` date NOT NULL DEFAULT '0000-00-00',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `addtime` (`addtime`),
  KEY `catid` (`catid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='行情';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_quote_7`
--

LOCK TABLES `destoon_quote_7` WRITE;
/*!40000 ALTER TABLE `destoon_quote_7` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_quote_7` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_quote_data_7`
--

DROP TABLE IF EXISTS `destoon_quote_data_7`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_quote_data_7` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` longtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='行情内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_quote_data_7`
--

LOCK TABLES `destoon_quote_data_7` WRITE;
/*!40000 ALTER TABLE `destoon_quote_data_7` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_quote_data_7` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_quote_price_7`
--

DROP TABLE IF EXISTS `destoon_quote_price_7`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_quote_price_7` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL,
  `market` smallint(6) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL,
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `company` varchar(100) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `qq` varchar(20) NOT NULL,
  `wx` varchar(50) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `note` varchar(255) NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `addtime` (`addtime`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='行情报价';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_quote_price_7`
--

LOCK TABLES `destoon_quote_price_7` WRITE;
/*!40000 ALTER TABLE `destoon_quote_price_7` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_quote_price_7` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_quote_product_7`
--

DROP TABLE IF EXISTS `destoon_quote_product_7`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_quote_product_7` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
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
  `item` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `seo_title` varchar(255) NOT NULL,
  `seo_keywords` varchar(255) NOT NULL,
  `seo_description` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='行情产品';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_quote_product_7`
--

LOCK TABLES `destoon_quote_product_7` WRITE;
/*!40000 ALTER TABLE `destoon_quote_product_7` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_quote_product_7` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_sell_5`
--

DROP TABLE IF EXISTS `destoon_sell_5`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_sell_5` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `mycatid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `typeid` smallint(2) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `elite` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `fee` float NOT NULL DEFAULT '0',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `n1` varchar(100) NOT NULL,
  `n2` varchar(100) NOT NULL,
  `n3` varchar(100) NOT NULL,
  `v1` varchar(100) NOT NULL,
  `v2` varchar(100) NOT NULL,
  `v3` varchar(100) NOT NULL,
  `brand` varchar(100) NOT NULL DEFAULT '',
  `unit` varchar(10) NOT NULL DEFAULT '',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `minamount` float unsigned NOT NULL DEFAULT '0',
  `amount` float unsigned NOT NULL DEFAULT '0',
  `days` smallint(3) unsigned NOT NULL DEFAULT '0',
  `tag` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `pptword` varchar(255) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `thumb1` varchar(255) NOT NULL DEFAULT '',
  `thumb2` varchar(255) NOT NULL DEFAULT '',
  `thumbs` text NOT NULL,
  `username` varchar(30) NOT NULL DEFAULT '',
  `groupid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `company` varchar(100) NOT NULL DEFAULT '',
  `vip` smallint(2) unsigned NOT NULL DEFAULT '0',
  `validated` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `truename` varchar(30) NOT NULL DEFAULT '',
  `telephone` varchar(50) NOT NULL DEFAULT '',
  `mobile` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `wx` varchar(50) NOT NULL DEFAULT '',
  `ali` varchar(30) NOT NULL DEFAULT '',
  `skype` varchar(30) NOT NULL DEFAULT '',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `editdate` date NOT NULL DEFAULT '0000-00-00',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `adddate` date NOT NULL DEFAULT '0000-00-00',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`),
  KEY `editdate` (`editdate`,`vip`,`edittime`),
  KEY `edittime` (`edittime`),
  KEY `catid` (`catid`),
  KEY `mycatid` (`mycatid`),
  KEY `areaid` (`areaid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='供应';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_sell_5`
--

LOCK TABLES `destoon_sell_5` WRITE;
/*!40000 ALTER TABLE `destoon_sell_5` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_sell_5` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_sell_data_5`
--

DROP TABLE IF EXISTS `destoon_sell_data_5`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_sell_data_5` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='供应内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_sell_data_5`
--

LOCK TABLES `destoon_sell_data_5` WRITE;
/*!40000 ALTER TABLE `destoon_sell_data_5` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_sell_data_5` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_sell_search_5`
--

DROP TABLE IF EXISTS `destoon_sell_search_5`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_sell_search_5` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `sorttime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='供应搜索';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_sell_search_5`
--

LOCK TABLES `destoon_sell_search_5` WRITE;
/*!40000 ALTER TABLE `destoon_sell_search_5` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_sell_search_5` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_session`
--

DROP TABLE IF EXISTS `destoon_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_session` (
  `sessionid` varchar(32) NOT NULL DEFAULT '',
  `data` text NOT NULL,
  `lastvisit` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `sessionid` (`sessionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='SESSION';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_session`
--

LOCK TABLES `destoon_session` WRITE;
/*!40000 ALTER TABLE `destoon_session` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_setting`
--

DROP TABLE IF EXISTS `destoon_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_setting` (
  `item` varchar(30) NOT NULL DEFAULT '',
  `item_key` varchar(100) NOT NULL DEFAULT '',
  `item_value` text NOT NULL,
  KEY `item` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='网站设置';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_setting`
--

LOCK TABLES `destoon_setting` WRITE;
/*!40000 ALTER TABLE `destoon_setting` DISABLE KEYS */;
INSERT INTO `destoon_setting` VALUES ('1','check_week',''),('1','admin_week',''),('1','cloud_express','0'),('1','sms_sign',''),('1','sms_ok','成功'),('1','sms_len','70'),('1','sms_max','5'),('1','sms_fee','0.1'),('1','sms','0'),('1','page_text','18'),('1','page_logo','18'),('1','page_club','8'),('1','page_down','3'),('1','page_know','6'),('1','page_job','5'),('1','page_exhibit','6'),('1','page_brand','16'),('1','page_photo','3'),('1','page_video','3'),('1','page_special','1'),('1','page_news','6'),('1','page_newsh','1'),('1','page_newst','1'),('1','page_group','10'),('1','page_info','10'),('1','page_sell','10'),('1','page_mall','10'),('1','message_weixin','1'),('1','message_type','1,2,3'),('1','message_time','60'),('1','message_group','6,7'),('1','message_email','0'),('1','mail_log','1'),('1','mail_name',''),('1','mail_sender',''),('1','mail_sign',''),('1','smtp_pass',''),('1','smtp_user',''),('1','smtp_auth','1'),('1','smtp_port','25'),('1','smtp_host',''),('1','mail_delimiter','1'),('1','mail_type','close'),('1','water_fontcolor','#000000'),('1','water_fontsize','20'),('1','water_font','simhei.ttf'),('1','water_text','www.destoon.com'),('1','water_jpeg_quality','90'),('1','water_transition','60'),('1','water_mark','watermark.png'),('1','max_image','800'),('1','thumb_title','0'),('1','thumb_album','0'),('1','middle_h','300'),('1','middle_w','400'),('1','water_middle','0'),('1','water_com','1'),('1','gif_ani','1'),('1','bmp_jpg','1'),('1','water_pos','9'),('1','water_min_wh','180'),('1','water_margin','10'),('1','water_type','2'),('1','file_my','my.php'),('1','file_login','login.php'),('1','file_register','register.php'),('1','defend_proxy','0'),('1','defend_reload','0'),('1','defend_cc','0'),('1','safe_domain',''),('1','check_referer','1'),('1','uploaddir','Ym/d'),('1','uploadsize','20480'),('1','uploadtype','jpg|jpeg|png|gif|bmp|mp4|flv|rar|zip|pdf|doc|docx|xls|xlsx|ppt|ppts'),('1','uploadlog','1'),('1','anticopy','0'),('1','ip_login','0'),('1','login_log','0'),('1','admin_log','1'),('1','admin_online','1'),('1','md5_pass','1'),('1','captcha_admin','0'),('1','captcha_cn','0'),('1','captcha_chars',''),('1','check_hour',''),('1','admin_hour',''),('1','admin_ip',''),('1','admin_area',''),('1','remote_url',''),('1','ftp_path',''),('1','ftp_save','0'),('1','ftp_pasv','1'),('1','ftp_ssl','0'),('1','ftp_pass',''),('1','ftp_user',''),('1','ftp_port','21'),('1','ftp_host',''),('1','ftp_remote','0'),('1','max_len','50000'),('1','schcate_limit','10'),('1','pagesize','20'),('1','pushtime','10'),('1','online','1200'),('1','search_limit','1'),('1','max_kw','32'),('1','min_kw','3'),('1','search_check_kw','0'),('1','search_kw','1'),('1','save_draft','0'),('1','search_tips','1'),('1','anti_spam','1'),('1','log_credit','1'),('1','pages_mode','0'),('1','lazy','0'),('1','gzip_enable','0'),('1','cache_hits','0'),('1','cache_search','0'),('1','task_item','86400'),('1','task_list','1800'),('1','task_index','600'),('1','log_404','0'),('1','pcharset','0'),('1','search_rewrite','0'),('1','com_https','0'),('1','com_www','0'),('1','rewrite','0'),('1','index_html','0'),('1','file_ext','html'),('1','index','index'),('1','seo_description','DESTOON B2B网站系统是基于PHP+MySQL的B2B（电子商务）行业门户解决方案'),('1','seo_keywords','B2B网站系统,B2B行业门户系统,B2B解决方案,DESTOON ,B2B,电子商务'),('1','seo_delimiter','_'),('1','seo_title','DESTOON B2B网站系统|B2B（电子商务）行业门户解决方案'),('1','trade_nu','notify.php'),('1','trade_tp','0'),('1','trade_ac',''),('1','trade_id',''),('1','trade_pw',''),('1','trade_hm','http://www.alipay.com/'),('1','trade_nm','支付宝'),('1','trade',''),('1','im_skype','0'),('1','im_wx','1'),('1','im_ali','1'),('1','im_qq','1'),('1','im_web','1'),('1','admin_left','218'),('1','max_cart','50'),('1','quick_pay','200'),('1','credit_unit','点'),('1','credit_name','积分'),('1','money_sign','￥'),('1','money_unit','元'),('1','money_name','资金'),('1','city_ip','1'),('1','city','1'),('1','icpno',''),('1','close','0'),('1','close_reason','网站维护中，请稍候访问...'),('1','telephone',''),('1','copyright','(c)2008-2018 DESTOON B2B SYSTEM All Rights Reserved'),('1','logo',''),('1','sitename','DESTOON B2B网站管理系统'),('1','module','destoon'),('1','mobile','http://demo.destoon.com/v7.0/mobile//'),('2','pay_banks','站内|支付宝|微信支付|财付通|现金|招商银行|工商银行|农业银行|建设银行|交通银行|中国银行|邮政储蓄|邮政汇款'),('2','trade_day','10'),('2','deposit','1000'),('2','cash_fee_max','50'),('2','cash_fee_min','1'),('2','cash_fee','1'),('2','cash_max','10000'),('2','cash_min','50'),('2','cash_times','3'),('2','cash_banks','支付宝|微信|财付通|招商银行|工商银行|农业银行|建设银行|交通银行|中国银行|邮政储蓄|邮政汇款'),('2','cash_enable','1'),('2','pay_url',''),('2','awards','1|2|5|10|20|50|100'),('2','mincharge','0'),('2','pay_card','1'),('2','pay_online','1'),('2','link_check','2'),('2','credit_clear','0'),('2','credit_save','0'),('2','credit_check','2'),('2','page_clear','0'),('2','page_save','0'),('2','news_thumb_height','180'),('2','news_save','0'),('2','news_clear','0'),('2','page_check','2'),('2','news_thumb_width','240'),('2','introduce_length','0'),('2','introduce_save','0'),('2','introduce_clear','0'),('2','news_check','2'),('2','thumb_height','180'),('2','thumb_width','180'),('2','cate_max','6'),('2','mode_max','2'),('2','com_mode','制造商|贸易商|服务商|其他机构'),('2','money_unit','人民币|港元|台币|美元|欧元|英镑'),('2','com_size','1-49人|50-99人|100-499人|500-999人|1000-3000人|3000-5000人|5000-10000人|10000人以上'),('2','com_type','企业单位|事业单位或社会团体|个体经营|其他'),('2','editor','Basic'),('2','vfax',''),('2','vcompany','1'),('2','vbank','1'),('2','vtruename','1'),('2','vmobile','1'),('2','vemail','1'),('2','vmember','1'),('2','chat_img','1'),('2','chat_url','1'),('2','chat_file','1'),('2','chat_mintime','3'),('2','chat_timeout','600'),('2','chat_poll','1'),('2','alertid','5|6|22'),('2','alert_check','2'),('2','chat_maxlen','300'),('2','auth_days','3'),('2','captcha_home','2'),('2','captcha_edit','2'),('2','captcha_sendmessage','2'),('2','maxtouser','5'),('2','login_scan','1'),('2','login_sms','1'),('2','login_time','864000'),('2','captcha_login','0'),('2','lock_hour','1'),('2','login_times','5'),('2','edit_check','thumb,areaid,type,business,regyear,capital,address,telephone,gzh,gzhqr,content'),('2','usernote',''),('2','iptimeout','0'),('2','banagent',''),('2','defend_proxy','0'),('2','credit_register','0'),('2','sms_register','0'),('2','money_register','0'),('2','question_register','0'),('2','captcha_register','0'),('2','welcome_sms','1'),('2','welcome_email','1'),('2','welcome_message','1'),('2','checkuser','0'),('2','banemail',''),('2','banmodec','0'),('2','bancompany',''),('2','mixpassword','1,2'),('2','maxpassword','20'),('2','minpassword','6'),('2','banmodeu','0'),('2','banusername','admin|system|master|web|sell|buy|company|quote|job|article|info|page|bbs'),('2','maxusername','20'),('2','minusername','4'),('2','enable_register','1'),('3','baidunews_items','90'),('3','baidunews_update','60'),('3','baidunews_email','web@destoon.com'),('3','baidunews','1'),('3','sitemaps_update','60'),('3','sitemaps_items','10000'),('3','sitemaps_module','16,5,6,4,17,7,8,21,22,9,10,11,12,14,15'),('3','sitemaps_priority','0.8'),('3','sitemaps_changefreq','monthly'),('3','sitemaps','1'),('3','feed_pagesize','50'),('3','feed_domain',''),('3','feed_enable','2'),('3','archiver_domain',''),('3','archiver_enable','1'),('3','form_domain',''),('3','form_enable','1'),('3','poll_domain',''),('3','poll_enable','1'),('3','vote_domain',''),('3','vote_enable','1'),('3','gift_domain',''),('3','gift_time','86400'),('3','gift_enable','1'),('3','guestbook_enable','1'),('3','guestbook_domain',''),('3','guestbook_type','业务合作|意见建议|使用问题|页面错误|不良信息|其他'),('3','guestbook_captcha','1'),('3','comment_am','网友'),('3','credit_del_comment','5'),('3','credit_add_comment','2'),('3','comment_limit','30'),('3','comment_pagesize','10'),('3','comment_time','30'),('3','comment_max','500'),('3','comment_min','5'),('3','comment_vote','1'),('3','comment_admin_del','1'),('3','comment_user_del','4'),('3','comment_captcha_add','1'),('3','comment_check','2'),('3','comment_vote_group','5,6,7'),('3','comment_group','5,6,7'),('3','comment_show','1'),('3','comment_api_key',''),('3','comment_module','5,6,4,17,7,8,21,22,13,9,11,12,14,15'),('3','comment_api',''),('3','comment_api_id',''),('3','comment_domain',''),('3','link_request',''),('3','link_reg','1'),('3','link_domain',''),('3','link_enable','1'),('3','announce_domain',''),('3','announce_enable','1'),('3','ad_currency','money'),('3','ad_buy','1'),('3','ad_view','1'),('3','ad_domain',''),('3','ad_enable','1'),('3','spread_currency','money'),('3','spread_list','1'),('3','spread_check','1'),('3','spread_step','100'),('3','spread_month','6'),('3','spread_max','10'),('3','spread_price','200'),('3','spread_domain',''),('3','mobile_adr','77@aprcc7byyvzxyi4i'),('3','mobile_ios','77@d9xuz1ukp1goudwh'),('3','mobile_ajax','1'),('3','mobile_goto','1'),('3','mobile_pid','14'),('3','mobile_sitename','DESTOON'),('3','mobile_domain',''),('3','mobile_enable','1'),('3','show_url','1'),('3','list_url','1'),('3','weixin','0'),('3','oauth','0'),('3','module','extend'),('3','mobile','http://127.0.0.1/mobile/extend/'),('3','feed_url','http://127.0.0.1/feed/'),('3','feed_mob','http://127.0.0.1/mobile/feed/'),('3','archiver_url','http://127.0.0.1/archiver/'),('3','archiver_mob','http://127.0.0.1/mobile/archiver/'),('3','form_url','http://127.0.0.1/form/'),('3','form_mob','http://127.0.0.1/mobile/form/'),('3','poll_url','http://127.0.0.1/poll/'),('3','poll_mob','http://127.0.0.1/mobile/poll/'),('3','vote_url','http://127.0.0.1/vote/'),('3','vote_mob','http://127.0.0.1/mobile/vote/'),('3','gift_url','http://127.0.0.1/gift/'),('3','gift_mob','http://127.0.0.1/mobile/gift/'),('3','guestbook_url','http://127.0.0.1/guestbook/'),('3','guestbook_mob','http://127.0.0.1/mobile/guestbook/'),('3','comment_url','http://127.0.0.1/comment/'),('3','comment_mob','http://127.0.0.1/mobile/comment/'),('3','link_url','http://127.0.0.1/link/'),('3','link_mob','http://127.0.0.1/mobile/link/'),('3','announce_url','http://127.0.0.1/announce/'),('3','announce_mob','http://127.0.0.1/mobile/announce/'),('3','ad_url','http://127.0.0.1/ad/'),('3','ad_mob','http://127.0.0.1/mobile/ad/'),('3','spread_url','http://127.0.0.1/spread/'),('3','spread_mob','http://127.0.0.1/mobile/spread/'),('3','mobile_url','http://127.0.0.1/mobile/'),('3','mobile_mob','http://127.0.0.1/mobile/mobile/'),('4','group_message','3,5,6,7'),('4','group_buy','3,5,6,7'),('4','group_index','3,5,6,7'),('4','seo_description_search',''),('4','seo_keywords_search',''),('4','seo_title_search',''),('4','group_list','3,5,6,7'),('4','group_search','3,5,6,7'),('4','group_price','3,5,6,7'),('4','seo_description_show','{内容标题}{内容简介}{分类名称}{分类SEO描述}{模块名称}{网站名称}{网站SEO描述}'),('4','group_inquiry','3,5,6,7'),('4','seo_keywords_show','{内容标题}{分类名称}{分类SEO关键词}{模块名称}{网站SEO关键词}'),('4','seo_title_show','{内容标题}{分类名称}{分类SEO标题}{模块名称}{网站名称}{网站SEO标题}{分隔符}'),('4','seo_description_list','{网站SEO描述}{网站名称}{模块名称}{分类SEO描述}{分类名称}'),('4','seo_keywords_list','{分类名称}{分类SEO关键词}{模块名称}{网站名称}{网站SEO关键词}'),('4','seo_title_list','{分类SEO标题}{页码}{模块名称}{分隔符}{网站名称}'),('4','seo_keywords_index','{模块名称}{网站名称}{网站SEO标题}'),('4','seo_description_index','{模块名称}{网站名称}{网站SEO标题}'),('4','php_list_urlid','5'),('4','seo_title_index','{模块名称}{分隔符}{页码}{网站名称}'),('4','htm_list_urlid','0'),('4','htm_list_prefix','company_list_'),('4','list_html','0'),('4','index_html','0'),('4','page_comment','0'),('4','hits','1'),('4','pagesize','20'),('4','page_inew','10'),('4','group_contact','5,6,7'),('4','page_inews','10'),('4','page_ivip','10'),('4','page_irec','10'),('4','page_subcat','6'),('4','level','推荐公司'),('4','kf','qq,53kf,tq,qiao'),('4','stats','baidu,qq,cnzz,51la'),('4','map','baidu'),('4','vip_honor','1'),('4','vip_maxyear','5'),('4','vip_year','1'),('4','vip_cominfo','1'),('4','vip_maxgroupvip','3'),('4','delvip','1'),('4','openall','0'),('4','split','0'),('4','comment','1'),('4','homeurl','0'),('4','fields','userid,username,company,linkurl,thumb,catid,areaid,vip,groupid,validated,business,mode'),('4','order','vip desc,userid desc'),('4','template_search',''),('4','template_list',''),('4','template_index',''),('4','title_index','{$seo_modulename}{$seo_delimiter}{$seo_page}{$seo_sitename}'),('4','title_list','{$seo_cattitle}{$seo_page}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('4','title_show','{$seo_showtitle}{$seo_catname}{$seo_cattitle}{$seo_modulename}{$seo_sitename}{$seo_sitetitle}{$seo_delimiter}'),('4','title_search',''),('4','keywords_index','{$seo_modulename}{$seo_sitename}{$seo_sitetitle}'),('4','keywords_list','{$seo_catname}{$seo_catkeywords}{$seo_modulename}{$seo_sitename}{$seo_sitekeywords}'),('4','keywords_show','{$seo_showtitle}{$seo_catname}{$seo_catkeywords}{$seo_modulename}{$seo_sitekeywords}'),('4','keywords_search',''),('4','description_index','{$seo_modulename}{$seo_sitename}{$seo_sitetitle}'),('4','description_list','{$seo_sitedescription}{$seo_sitename}{$seo_modulename}{$seo_catdescription}{$seo_catname}'),('4','description_show','{$seo_showtitle}{$seo_showintroduce}{$seo_catname}{$seo_catdescription}{$seo_modulename}{$seo_sitename}{$seo_sitedescription}'),('4','description_search',''),('4','module','company'),('4','mobile','http://demo.destoon.com/v7.0/mobile/company/'),('5','free_limit_7','-1'),('5','limit_7','100'),('5','free_limit_6','0'),('5','limit_6','30'),('5','free_limit_5','0'),('5','limit_5','3'),('5','free_limit_4','0'),('5','limit_4','-1'),('5','free_limit_3','0'),('5','limit_3','-1'),('5','free_limit_2','0'),('5','limit_2','-1'),('5','free_limit_1','-1'),('5','limit_1','0'),('5','credit_refresh','1'),('5','credit_color','100'),('5','credit_elite','100'),('5','credit_del','5'),('5','credit_add','2'),('5','fee_award','0'),('5','fee_back','0'),('5','fee_period','0'),('5','fee_view','0'),('5','fee_add','0'),('5','fee_currency','money'),('5','fee_mode','0'),('5','question_add','2'),('5','captcha_add','2'),('5','check_add','2'),('5','question_inquiry','2'),('5','captcha_inquiry','2'),('5','group_elite','6,7'),('5','group_compare','3,5,6,7'),('5','group_refresh','3,5,6'),('5','group_color','7'),('5','group_search','3,5,6,7'),('5','group_contact','3,5,6,7'),('5','group_index','3,5,6,7'),('5','group_list','3,5,6,7'),('5','group_show','3,5,6,7'),('5','seo_description_search',''),('5','seo_keywords_search',''),('5','seo_title_search',''),('5','seo_description_show',''),('5','seo_keywords_show',''),('5','seo_description_list',''),('5','seo_title_show','{内容标题}{分隔符}{分类名称}{模块名称}{分隔符}{网站名称}'),('5','seo_keywords_list',''),('5','seo_title_list','{分类SEO标题}{页码}{模块名称}{分隔符}{网站名称}'),('5','seo_description_index',''),('5','seo_keywords_index',''),('5','php_item_urlid','0'),('5','seo_title_index','{模块名称}{分隔符}{页码}{网站名称}'),('5','htm_item_urlid','1'),('5','htm_item_prefix','sell_info_'),('5','show_html','0'),('5','php_list_urlid','0'),('5','htm_list_urlid','0'),('5','htm_list_prefix','sell_list_'),('5','list_html','0'),('5','index_html','0'),('5','page_comment','0'),('5','hits','1'),('5','max_width','1000'),('5','pagesize','20'),('5','page_ihits','9'),('5','page_iedit',' 10'),('5','page_inew','10'),('5','page_irec','12'),('5','page_subcat','5'),('5','upload_thumb','0'),('5','checkorder','1'),('5','swfu','2'),('5','level','推荐信息'),('5','fulltext','0'),('5','sphinx_port',''),('5','sphinx_name','destoon,delta'),('5','sphinx_host',''),('5','inquiry_ask','我对贵公司的产品非常感兴趣，能否发一些详细资料给我参考？|请您发一份比较详细的产品规格说明，谢谢！|请问贵公司产品是否可以代理？代理条件是什么？|我公司有意购买此产品，可否提供此产品的报价单和最小起订量？'),('5','sphinx','0'),('5','save_remotepic','0'),('5','clear_link','0'),('5','keylink','0'),('5','split','0'),('5','cat_property','1'),('5','type','供应|提供服务|供应二手|提供加工|提供合作|库存'),('5','inquiry_type','单价|产品规格|型号|价格条款|原产地|能否提供样品|最小订货量|交货期|供货能力|销售条款及附加条件|包装方式|质量/安全认证 '),('5','fields','itemid,title,thumb,linkurl,style,catid,areaid,introduce,addtime,edittime,username,company,groupid,vip,qq,wx,ali,skype,validated,price,unit,minamount,amount'),('5','editor','Simple'),('5','order','editdate desc,vip desc,edittime desc'),('5','introduce_length','120'),('5','template_inquiry',''),('5','thumb_height','200'),('5','thumb_width','200'),('5','template_compare',''),('5','template_my',''),('5','template_search',''),('5','template_show',''),('5','template_list',''),('5','template_index',''),('5','title_index','{$seo_modulename}{$seo_delimiter}{$seo_page}{$seo_sitename}'),('5','title_list','{$seo_cattitle}{$seo_page}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('5','title_show','{$seo_showtitle}{$seo_delimiter}{$seo_catname}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('5','title_search',''),('5','keywords_index',''),('5','keywords_list',''),('5','keywords_show',''),('5','keywords_search',''),('5','description_index',''),('5','description_list',''),('5','description_show',''),('5','description_search',''),('5','module','sell'),('5','mobile','http://demo.destoon.com/v7.0/mobile/sell/'),('6','free_limit_7','-1'),('6','limit_7','100'),('6','free_limit_6','0'),('6','limit_6','30'),('6','free_limit_5','0'),('6','limit_5','3'),('6','free_limit_4','0'),('6','limit_4','-1'),('6','free_limit_3','0'),('6','limit_3','-1'),('6','free_limit_2','0'),('6','limit_2','-1'),('6','free_limit_1','-1'),('6','limit_1','0'),('6','credit_refresh','1'),('6','credit_color','100'),('6','credit_del','5'),('6','credit_add','2'),('6','fee_award','0'),('6','fee_back','0'),('6','fee_period','0'),('6','fee_view','0'),('6','fee_add','0'),('6','fee_currency','money'),('6','fee_mode','1'),('6','question_add','2'),('6','captcha_add','2'),('6','check_add','2'),('6','question_price','2'),('6','captcha_price','2'),('6','group_refresh','7'),('6','group_color','7'),('6','group_search','3,5,6,7'),('6','group_contact','3,5,6,7'),('6','group_show','3,5,6,7'),('6','group_list','3,5,6,7'),('6','group_index','3,5,6,7'),('6','seo_description_search',''),('6','seo_keywords_search',''),('6','seo_title_search',''),('6','seo_keywords_show',''),('6','seo_description_show',''),('6','seo_title_show','{内容标题}{分隔符}{分类名称}{模块名称}{分隔符}{网站名称}'),('6','seo_description_list',''),('6','seo_keywords_list',''),('6','seo_title_list','{分类SEO标题}{页码}{模块名称}{分隔符}{网站名称}'),('6','seo_description_index',''),('6','seo_title_index','{模块名称}{分隔符}{页码}{网站名称}'),('6','seo_keywords_index',''),('6','php_item_urlid','0'),('6','htm_item_urlid','0'),('6','htm_item_prefix','buy_info_'),('6','show_html','0'),('6','php_list_urlid','0'),('6','htm_list_urlid','0'),('6','htm_list_prefix','buy_list_'),('6','list_html','0'),('6','index_html','0'),('6','page_comment','0'),('6','hits','1'),('6','max_width','1000'),('6','pagesize','20'),('6','page_ihits','9'),('6','page_iedit','10'),('6','page_inew','10'),('6','page_irec','12'),('6','page_subcat','6'),('6','level','推荐信息'),('6','fulltext','0'),('6','split','0'),('6','keylink','0'),('6','clear_link','0'),('6','type','求购|紧急求购|求购二手|寻求加工|寻求合作|招标'),('6','price_ask','请您发一份比较详细的产品规格说明，谢谢！|请问您对此产品是长期有需求吗？|请问您对此产品有多大的需求量？'),('6','cat_property','0'),('6','save_remotepic','0'),('6','order','editdate desc,vip desc,edittime desc'),('6','fields','itemid,title,thumb,linkurl,style,catid,areaid,introduce,addtime,edittime,username,company,groupid,vip,qq,wx,ali,skype,validated,price,hits'),('6','introduce_length','120'),('6','editor','Destoon'),('6','thumb_height','200'),('6','thumb_width','200'),('6','template_price',''),('6','template_my',''),('6','template_search',''),('6','template_show',''),('6','template_list',''),('6','template_index',''),('6','title_index','{$seo_modulename}{$seo_delimiter}{$seo_page}{$seo_sitename}'),('6','title_list','{$seo_cattitle}{$seo_page}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('6','title_show','{$seo_showtitle}{$seo_delimiter}{$seo_catname}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('6','title_search',''),('6','keywords_index',''),('6','keywords_list',''),('6','keywords_show',''),('6','keywords_search',''),('6','description_index',''),('6','description_list',''),('6','description_show',''),('6','description_search',''),('6','module','buy'),('6','mobile','http://demo.destoon.com/v7.0/mobile/buy/'),('7','free_limit_5','0'),('7','limit_6','30'),('7','limit_5','3'),('7','free_limit_4','0'),('7','limit_4','-1'),('7','free_limit_3','0'),('7','limit_3','-1'),('7','free_limit_2','0'),('7','limit_2','-1'),('7','free_limit_1','-1'),('7','limit_1','0'),('7','credit_color','100'),('7','credit_del','5'),('7','free_limit_7','-1'),('7','credit_add','2'),('7','limit_7','100'),('7','free_limit_6','0'),('7','pre_view','200'),('7','fee_award','0'),('7','fee_back','0'),('7','fee_period','0'),('7','fee_view','0'),('7','fee_add','0'),('7','fee_currency','money'),('7','fee_mode','1'),('7','question_add','2'),('7','captcha_add','2'),('7','check_add','2'),('7','group_add_price','3,5,6,7'),('7','group_show_price','3,5,6,7'),('7','group_color','7'),('7','group_search','3,5,6,7'),('7','group_show','3,5,6,7'),('7','group_list','3,5,6,7'),('7','seo_description_search',''),('7','group_index','3,5,6,7'),('7','seo_keywords_search',''),('7','seo_title_search',''),('7','seo_keywords_show',''),('7','seo_description_show',''),('7','seo_title_show','{内容标题}{分隔符}{分类名称}{模块名称}{分隔符}{网站名称}'),('7','seo_description_list',''),('7','seo_keywords_list',''),('7','seo_title_list','{分类SEO标题}{页码}{模块名称}{分隔符}{网站名称}'),('7','seo_description_index',''),('7','seo_keywords_index',''),('7','seo_title_index','{模块名称}{分隔符}{页码}{网站名称}'),('7','php_item_urlid','0'),('7','htm_item_urlid','0'),('7','htm_item_prefix',''),('7','htm_list_urlid','0'),('7','show_html','0'),('7','php_list_urlid','0'),('7','htm_list_prefix',''),('7','list_html','0'),('7','index_html','0'),('7','page_comment','0'),('7','hits','1'),('7','max_width','800'),('7','page_child','5'),('7','page_icat','5'),('7','pagesize','20'),('7','level','推荐行情|暂未指定|推荐图文|头条相关|头条推荐'),('7','fulltext','0'),('7','split','0'),('7','keylink','1'),('7','clear_link','0'),('7','cat_property','0'),('7','save_remotepic','0'),('7','fields','itemid,title,thumb,linkurl,style,catid,introduce,hits,addtime,edittime,username'),('7','order','addtime desc'),('7','introduce_length','120'),('7','editor','Destoon'),('7','thumb_height','180'),('7','thumb_width','240'),('7','template_price',''),('7','template_product',''),('7','template_my',''),('7','template_search',''),('7','template_show',''),('7','template_list',''),('7','template_index',''),('7','title_index','{$seo_modulename}{$seo_delimiter}{$seo_page}{$seo_sitename}'),('7','title_list','{$seo_cattitle}{$seo_page}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('7','title_show','{$seo_showtitle}{$seo_delimiter}{$seo_catname}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('7','title_search',''),('7','keywords_index',''),('7','keywords_list',''),('7','keywords_show',''),('7','keywords_search',''),('7','description_index',''),('7','description_list',''),('7','description_show',''),('7','description_search',''),('7','module','quote'),('7','mobile','http://demo.destoon.com/v7.0/mobile/quote/'),('8','free_limit_5','0'),('8','limit_6','30'),('8','free_limit_7','-1'),('8','limit_5','3'),('8','free_limit_4','0'),('8','limit_7','100'),('8','limit_4','-1'),('8','limit_3','-1'),('8','free_limit_3','0'),('8','free_limit_2','0'),('8','limit_2','-1'),('8','free_limit_6','0'),('8','free_limit_1','-1'),('8','limit_1','0'),('8','credit_color','100'),('8','credit_del','5'),('8','credit_add','2'),('8','pre_view','200'),('8','fee_award','100'),('8','fee_back','0'),('8','fee_period','0'),('8','fee_view','0'),('8','fee_add','0'),('8','fee_currency','money'),('8','fee_mode','1'),('8','question_add','2'),('8','captcha_add','2'),('8','check_add','2'),('8','captcha_sign','2'),('8','group_color','7'),('8','group_search','3,5,6,7'),('8','group_contact','5,6,7'),('8','group_show','3,5,6,7'),('8','group_list','3,5,6,7'),('8','group_index','3,5,6,7'),('8','seo_description_search',''),('8','seo_keywords_search',''),('8','seo_title_search',''),('8','seo_description_list',''),('8','seo_title_show','{内容标题}{分隔符}{分类名称}{模块名称}{分隔符}{网站名称}'),('8','seo_keywords_show',''),('8','seo_description_show',''),('8','seo_title_list','{分类SEO标题}{页码}{模块名称}{分隔符}{网站名称}'),('8','seo_keywords_list',''),('8','seo_description_index',''),('8','seo_title_index','{模块名称}{分隔符}{页码}{网站名称}'),('8','seo_keywords_index',''),('8','php_item_urlid','0'),('8','htm_item_urlid','0'),('8','htm_item_prefix',''),('8','show_html','0'),('8','php_list_urlid','0'),('8','htm_list_urlid','0'),('8','htm_list_prefix',''),('8','list_html','0'),('8','index_html','0'),('8','page_comment','0'),('8','hits','1'),('8','max_width','800'),('8','pagesize','10'),('8','cat_hall_num','2'),('8','cat_hall','0'),('8','cat_service_num','8'),('8','cat_service','0'),('8','cat_news_num','10'),('8','cat_news','0'),('8','news_id','21'),('8','page_islide','3'),('8','page_icat','10'),('8','level','推荐展会|展会幻灯'),('8','fulltext','0'),('8','split','0'),('8','keylink','1'),('8','clear_link','0'),('8','save_remotepic','0'),('8','cat_property','0'),('8','fields','itemid,title,thumb,linkurl,style,catid,addtime,edittime,username,fromtime,totime,city,address,sponsor'),('8','order','addtime desc'),('8','editor','Destoon'),('8','introduce_length','0'),('8','thumb_height','180'),('8','thumb_width','240'),('8','template_sign',''),('8','template_my',''),('8','template_search',''),('8','template_show',''),('8','template_list',''),('8','template_index',''),('8','title_index','{$seo_modulename}{$seo_delimiter}{$seo_page}{$seo_sitename}'),('8','title_list','{$seo_cattitle}{$seo_page}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('8','title_show','{$seo_showtitle}{$seo_delimiter}{$seo_catname}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('8','title_search',''),('8','keywords_index',''),('8','keywords_list',''),('8','keywords_show',''),('8','keywords_search',''),('8','description_index',''),('8','description_list',''),('8','description_show',''),('8','description_search',''),('8','module','exhibit'),('8','mobile','http://demo.destoon.com/v7.0/mobile/exhibit/'),('9','resume_free_limit_7','0'),('9','resume_limit_7','-1'),('9','free_limit_7','-1'),('9','limit_7','100'),('9','resume_free_limit_6','0'),('9','resume_limit_6','-1'),('9','resume_free_limit_5','0'),('9','free_limit_6','0'),('9','limit_6','30'),('9','resume_limit_5','2'),('9','free_limit_5','0'),('9','limit_5','3'),('9','resume_free_limit_4','0'),('9','limit_4','-1'),('9','free_limit_4','0'),('9','resume_limit_4','-1'),('9','resume_free_limit_3','0'),('9','resume_limit_3','-1'),('9','free_limit_3','0'),('9','limit_3','-1'),('9','resume_free_limit_2','0'),('9','resume_limit_2','-1'),('9','free_limit_2','0'),('9','limit_2','-1'),('9','resume_free_limit_1','-1'),('9','resume_limit_1','0'),('9','free_limit_1','-1'),('9','limit_1','0'),('9','credit_del_resume','5'),('9','credit_add_resume','2'),('9','credit_refresh','1'),('9','credit_color','100'),('9','credit_del','5'),('9','credit_add','2'),('9','question_add_resume','2'),('9','captcha_add_resume','2'),('9','check_add_resume','2'),('9','group_apply','5'),('9','fee_award','0'),('9','group_list','3,5,6,7'),('9','group_show','3,5,6,7'),('9','group_contact','5,6,7'),('9','group_search','3,5,6,7'),('9','fee_back','0'),('9','fee_period','0'),('9','fee_view','0'),('9','fee_add_resume','0'),('9','fee_add','0'),('9','fee_mode','0'),('9','fee_currency','money'),('9','question_add','2'),('9','captcha_add','2'),('9','check_add','2'),('9','group_talent','7'),('9','group_search_resume','3,5,6,7'),('9','group_contact_resume','7'),('9','group_show_resume','3,5,6,7'),('9','group_refresh','3,5,6,7'),('9','group_color','7'),('9','group_index','3,5,6,7'),('9','seo_description_search',''),('9','seo_keywords_search',''),('9','seo_title_search',''),('9','seo_description_show',''),('9','seo_title_show','{内容标题}{分隔符}{分类名称}{模块名称}{分隔符}{网站名称}'),('9','seo_keywords_show',''),('9','seo_description_list',''),('9','seo_keywords_index',''),('9','seo_description_index',''),('9','seo_title_list','{分类SEO标题}{页码}{模块名称}{分隔符}{网站名称}'),('9','seo_keywords_list',''),('9','php_item_urlid','0'),('9','seo_title_index','{模块名称}{分隔符}{页码}{网站名称}'),('9','htm_item_urlid','1'),('9','htm_item_prefix',''),('9','show_html','0'),('9','php_list_urlid','0'),('9','htm_list_urlid','0'),('9','htm_list_prefix',''),('9','list_html','0'),('9','index_html','0'),('9','page_comment','0'),('9','hits','1'),('9','max_width','1000'),('9','pagesize','20'),('9','page_iresume','10'),('9','page_ijob','10'),('9','level','推荐'),('9','split','0'),('9','clear_link','0'),('9','save_remotepic','0'),('9','cat_property','0'),('9','situation','目前正在找工作|观望有好机会再考虑|半年内无换工作计划'),('9','education','学历|初中|高中|大专|本科|硕士|博士'),('9','marriage','婚姻|未婚|已婚'),('9','gender','性别|男士|女士'),('9','type','类型|全职|兼职|实习'),('9','editor','Destoon'),('9','order','editdate desc,vip desc,edittime desc'),('9','fields','itemid,title,linkurl,style,catid,areaid,introduce,addtime,edittime,username,company,groupid,vip,qq,wx,ali,skype,validated,minsalary,maxsalary,minage,maxage,total'),('9','introduce_length','120'),('9','thumb_height','140'),('9','thumb_width','100'),('9','template_my_resume',''),('9','template_my',''),('9','template_apply',''),('9','template_resume',''),('9','template_search',''),('9','template_show',''),('9','template_list',''),('9','template_index',''),('9','title_index','{$seo_modulename}{$seo_delimiter}{$seo_page}{$seo_sitename}'),('9','title_list','{$seo_cattitle}{$seo_page}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('9','title_show','{$seo_showtitle}{$seo_delimiter}{$seo_catname}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('9','title_search',''),('9','keywords_index',''),('9','keywords_list',''),('9','keywords_show',''),('9','keywords_search',''),('9','description_index',''),('9','description_list',''),('9','description_show',''),('9','description_search',''),('9','module','job'),('9','mobile','http://demo.destoon.com/v7.0/mobile/job/'),('10','limit_6','30'),('10','answer_limit_6','30'),('10','answer_limit_7','100'),('10','free_limit_7','-1'),('10','limit_7','100'),('10','free_limit_6','0'),('10','answer_limit_5','-1'),('10','free_limit_5','0'),('10','limit_5','3'),('10','answer_limit_4','-1'),('10','free_limit_4','0'),('10','limit_4','-1'),('10','answer_limit_3','-1'),('10','free_limit_3','0'),('10','limit_3','-1'),('10','answer_limit_2','-1'),('10','free_limit_2','0'),('10','limit_2','-1'),('10','answer_limit_1','0'),('10','free_limit_1','-1'),('10','limit_1','0'),('10','credit_deal','20'),('10','credit_maxvote','30'),('10','credit_del_answer','5'),('10','credit_maxanswer','50'),('10','credit_vote','1'),('10','credit_answer','2'),('10','credit_best','20'),('10','credit_hidden','10'),('10','credit_color','100'),('10','credit_del','20'),('10','credit_add','0'),('10','pre_view','200'),('10','fee_award','0'),('10','fee_back','0'),('10','fee_period','0'),('10','fee_view','0'),('10','fee_add','0'),('10','fee_currency','money'),('10','fee_mode','1'),('10','captcha_answer','2'),('10','question_answer','0'),('10','check_answer','2'),('10','group_vote','3,5,6,7'),('10','group_answer','3,5,6,7'),('10','question_add','2'),('10','captcha_add','2'),('10','check_add','2'),('10','group_color','7'),('10','group_search','3,5,6,7'),('10','group_show','3,5,6,7'),('10','group_list','3,5,6,7'),('10','group_index','3,5,6,7'),('10','seo_description_search',''),('10','seo_keywords_search',''),('10','seo_title_search',''),('10','seo_description_show',''),('10','seo_keywords_show',''),('10','seo_title_show','{内容标题}{分隔符}{分类名称}{模块名称}{分隔符}{网站名称}'),('10','seo_description_list',''),('10','seo_keywords_list',''),('10','seo_description_index',''),('10','seo_title_list','{分类SEO标题}{页码}{模块名称}{分隔符}{网站名称}'),('10','seo_keywords_index',''),('10','seo_title_index','{模块名称}{分隔符}{页码}{网站名称}'),('10','php_item_urlid','0'),('10','htm_item_urlid','1'),('10','htm_item_prefix',''),('10','show_html','0'),('10','php_list_urlid','0'),('10','htm_list_urlid','0'),('10','htm_list_prefix',''),('10','list_html','0'),('10','index_html','0'),('10','page_comment','0'),('10','hits','1'),('10','max_width','750'),('10','answer_pagesize','10'),('10','pagesize','20'),('10','page_iexpert','8'),('10','page_iresolve','8'),('10','page_ivote','8'),('10','page_isolve','8'),('10','page_irec','8'),('10','messagedays','14'),('10','highcredit','20'),('10','raisecredit','20'),('10','raisedays','3'),('10','maxraise','2'),('10','minvote','3'),('10','votedays','5'),('10','overdays','15'),('10','answer_message','1'),('10','answer_repeat','1'),('10','credits','0|5|10|15|20|30|50|80|100'),('10','level','精彩推荐'),('10','fulltext','0'),('10','split','0'),('10','keylink','1'),('10','cat_property','0'),('10','save_remotepic','0'),('10','clear_link','0'),('10','clear_alink','1'),('10','fields','itemid,title,thumb,linkurl,style,catid,introduce,addtime,edittime,username,passport,answer,process,credit'),('10','order','addtime desc'),('10','editor','Simple'),('10','introduce_length','0'),('10','thumb_height','180'),('10','thumb_width','240'),('10','template_my_answer',''),('10','template_my',''),('10','template_faq',''),('10','template_expert',''),('10','template_answer',''),('10','template_search',''),('10','template_show',''),('10','template_list',''),('10','template_index',''),('10','title_index','{$seo_modulename}{$seo_delimiter}{$seo_page}{$seo_sitename}'),('10','title_list','{$seo_cattitle}{$seo_page}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('10','title_show','{$seo_showtitle}{$seo_delimiter}{$seo_catname}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('10','title_search',''),('10','keywords_index',''),('10','keywords_list',''),('10','keywords_show',''),('10','keywords_search',''),('10','description_index',''),('10','description_list',''),('10','description_show',''),('10','description_search',''),('10','module','know'),('10','mobile','http://demo.destoon.com/v7.0/mobile/know/'),('11','group_show','3,5,6,7'),('11','group_search','3,5,6,7'),('11','fee_award','0'),('11','group_list','3,5,6,7'),('11','group_index','3,5,6,7'),('11','seo_description_search',''),('11','seo_keywords_search',''),('11','seo_title_search',''),('11','seo_description_show',''),('11','seo_keywords_show',''),('11','seo_title_show','{内容标题}{分隔符}{分类名称}{模块名称}{分隔符}{网站名称}'),('11','seo_keywords_list',''),('11','seo_description_list',''),('11','seo_title_list','{分类SEO标题}{页码}{模块名称}{分隔符}{网站名称}'),('11','seo_title_index','{模块名称}{分隔符}{页码}{网站名称}'),('11','seo_keywords_index',''),('11','seo_description_index',''),('11','php_item_urlid','0'),('11','htm_item_urlid','1'),('11','htm_item_prefix',''),('11','show_html','0'),('11','php_list_urlid','0'),('11','htm_list_urlid','0'),('11','htm_list_prefix',''),('11','list_html','0'),('11','index_html','0'),('11','page_comment','0'),('11','hits','1'),('11','max_width','1000'),('11','pagesize','20'),('11','level_item','推荐信息|幻灯图片|推荐图文|头条相关|头条推荐|视频报道'),('11','page_irec','6'),('11','page_icat','6'),('11','level','推荐专题|暂未指定|推荐图文|头条相关|头条推荐'),('11','fulltext','0'),('11','split','0'),('11','clear_link','0'),('11','cat_property','0'),('11','save_remotepic','0'),('11','fields','itemid,title,thumb,linkurl,style,catid,introduce,addtime,edittime,islink,hits'),('11','order','addtime desc'),('11','editor','Destoon'),('11','introduce_length','120'),('11','banner_height','200'),('11','banner_width','1200'),('11','thumb_height','180'),('11','thumb_width','240'),('11','template_show',''),('11','template_type',''),('11','template_search',''),('11','template_list',''),('11','template_index',''),('11','title_index','{$seo_modulename}{$seo_delimiter}{$seo_page}{$seo_sitename}'),('11','title_list','{$seo_cattitle}{$seo_page}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('11','title_show','{$seo_showtitle}{$seo_delimiter}{$seo_catname}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('11','title_search',''),('11','keywords_index',''),('11','keywords_list',''),('11','keywords_show',''),('11','keywords_search',''),('11','description_index',''),('11','description_list',''),('11','description_show',''),('11','description_search',''),('11','module','special'),('11','mobile','http://demo.destoon.com/v7.0/mobile/special/'),('12','free_limit_7','-1'),('12','limit_7','100'),('12','free_limit_6','0'),('12','limit_6','30'),('12','free_limit_5','0'),('12','limit_5','3'),('12','free_limit_4','0'),('12','limit_4','-1'),('12','free_limit_3','0'),('12','limit_3','-1'),('12','free_limit_2','0'),('12','limit_2','-1'),('12','free_limit_1','-1'),('12','limit_1','0'),('12','credit_color','100'),('12','credit_del','5'),('12','credit_add','2'),('12','pre_view','200'),('12','fee_award','100'),('12','fee_back','0'),('12','fee_period','0'),('12','fee_view','0'),('12','fee_add','0'),('12','fee_currency','money'),('12','fee_mode','0'),('12','question_add','1'),('12','captcha_add','2'),('12','check_add','1'),('12','group_color','7'),('12','group_search','3,5,6,7'),('12','group_show','3,5,6,7'),('12','group_list','3,5,6,7'),('12','seo_description_show',''),('12','seo_title_search',''),('12','seo_keywords_search',''),('12','group_index','3,5,6,7'),('12','seo_description_search',''),('12','seo_keywords_show',''),('12','seo_description_list',''),('12','seo_title_show','{内容标题}{分隔符}{分类名称}{模块名称}{分隔符}{网站名称}'),('12','seo_keywords_list',''),('12','seo_keywords_index',''),('12','seo_description_index',''),('12','seo_title_list','{分类SEO标题}{页码}{模块名称}{分隔符}{网站名称}'),('12','php_item_urlid','0'),('12','seo_title_index','{模块名称}{分隔符}{页码}{网站名称}'),('12','htm_item_urlid','1'),('12','htm_item_prefix',''),('12','show_html','0'),('12','php_list_urlid','0'),('12','htm_list_urlid','0'),('12','htm_list_prefix',''),('12','list_html','0'),('12','index_html','0'),('12','page_comment','0'),('12','hits','1'),('12','max_width','1000'),('12','pagesize','18'),('12','page_islide','3'),('12','page_irec','6'),('12','page_icat','6'),('12','swfu_max','20'),('12','level','推荐图库|幻灯图片|推荐图文|头条相关|头条推荐'),('12','fulltext','0'),('12','split','0'),('12','keylink','0'),('12','clear_link','0'),('12','save_remotepic','0'),('12','cat_property','0'),('12','fields','itemid,title,thumb,linkurl,style,catid,introduce,addtime,edittime,username,items,open'),('12','order','addtime desc'),('12','editor','Simple'),('12','introduce_length','120'),('12','maxitem','30'),('12','thumb_height','180'),('12','thumb_width','240'),('12','template_view',''),('12','template_private',''),('12','template_my',''),('12','template_search',''),('12','template_show',''),('12','template_list',''),('12','template_index',''),('12','title_index','{$seo_modulename}{$seo_delimiter}{$seo_page}{$seo_sitename}'),('12','title_list','{$seo_cattitle}{$seo_page}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('12','title_show','{$seo_showtitle}{$seo_delimiter}{$seo_catname}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('12','title_search',''),('12','keywords_index',''),('12','keywords_list',''),('12','keywords_show',''),('12','keywords_search',''),('12','description_index',''),('12','description_list',''),('12','description_show',''),('12','description_search',''),('12','module','photo'),('12','mobile','http://demo.destoon.com/v7.0/mobile/photo/'),('13','free_limit_7','-1'),('13','limit_7','100'),('13','free_limit_6','0'),('13','free_limit_5','0'),('13','limit_6','30'),('13','limit_5','3'),('13','free_limit_4','0'),('13','limit_4','-1'),('13','free_limit_3','0'),('13','limit_3','-1'),('13','free_limit_2','0'),('13','limit_2','-1'),('13','free_limit_1','-1'),('13','limit_1','0'),('13','credit_refresh','1'),('13','credit_color','100'),('13','credit_del','5'),('13','credit_add','2'),('13','fee_view','0'),('13','fee_award','0'),('13','fee_back','0'),('13','fee_period','0'),('13','fee_add','0'),('13','fee_currency','money'),('13','fee_mode','0'),('13','question_add','2'),('13','captcha_add','2'),('13','group_refresh','3,5,6,7'),('13','captcha_message','2'),('13','question_message','2'),('13','check_add','2'),('13','group_color','3,5,6,7'),('13','group_search','3,5,6,7'),('13','group_contact','6,7'),('13','group_show','3,5,6,7'),('13','group_list','3,5,6,7'),('13','group_index','3,5,6,7'),('13','seo_description_search',''),('13','seo_keywords_search',''),('13','seo_title_search',''),('13','seo_description_show',''),('13','seo_title_show','{内容标题}{分隔符}{分类名称}{模块名称}{分隔符}{网站名称}'),('13','seo_keywords_list',''),('13','seo_description_list',''),('13','seo_title_list','{分类SEO标题}{页码}{模块名称}{分隔符}{网站名称}'),('13','seo_description_index','{模块名称}{网站名称}{网站SEO标题}'),('13','seo_keywords_index','{模块名称}{网站名称}{网站SEO标题}'),('13','seo_keywords_show',''),('13','seo_title_index','{模块名称}{分隔符}{页码}{网站名称}'),('13','php_item_urlid','0'),('13','htm_item_urlid','0'),('13','htm_item_prefix',''),('13','show_html','0'),('13','php_list_urlid','0'),('13','htm_list_urlid','0'),('13','htm_list_prefix',''),('13','list_html','0'),('13','index_html','0'),('13','page_comment','0'),('13','hits','1'),('13','max_width','1000'),('13','pagesize','20'),('13','page_icat','15'),('13','page_irec','18'),('13','page_subcat','6'),('13','level','推荐品牌'),('13','fulltext','0'),('13','split','0'),('13','keylink','0'),('13','clear_link','0'),('13','introduce_length','120'),('13','editor','Destoon'),('13','order','editdate desc,vip desc,edittime desc'),('13','fields','itemid,title,thumb,linkurl,style,catid,areaid,introduce,addtime,edittime,username,company,groupid,vip,qq,wx,ali,skype,validated,hits'),('13','message_ask','请问我这个地方有加盟商了吗？|我想加盟，请来电话告诉我具体细节。|初步打算加盟贵公司，请寄资料。|请问贵公司哪里有样板店或直营店？|想了解加盟细节，请尽快寄一份资料。 '),('13','cat_property','0'),('13','save_remotepic','0'),('13','thumb_height','60'),('13','thumb_width','180'),('13','template_message',''),('13','template_my',''),('13','template_search',''),('13','template_show',''),('13','template_list',''),('13','template_index',''),('13','title_index','{$seo_modulename}{$seo_delimiter}{$seo_page}{$seo_sitename}'),('13','title_list','{$seo_cattitle}{$seo_page}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('13','title_show','{$seo_showtitle}{$seo_delimiter}{$seo_catname}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('13','title_search',''),('13','keywords_index','{$seo_modulename}{$seo_sitename}{$seo_sitetitle}'),('13','keywords_list',''),('13','keywords_show',''),('13','keywords_search',''),('13','description_index','{$seo_modulename}{$seo_sitename}{$seo_sitetitle}'),('13','description_list',''),('13','description_show',''),('13','description_search',''),('13','module','brand'),('13','mobile','http://demo.destoon.com/v7.0/mobile/brand/'),('14','limit_7','100'),('14','free_limit_7','-1'),('14','free_limit_6','0'),('14','limit_6','30'),('14','free_limit_5','0'),('14','limit_5','3'),('14','free_limit_4','0'),('14','limit_4','-1'),('14','free_limit_3','0'),('14','limit_3','-1'),('14','free_limit_2','0'),('14','limit_2','-1'),('14','free_limit_1','-1'),('14','limit_1','0'),('14','credit_color','100'),('14','credit_del','5'),('14','credit_add','2'),('14','fee_award','100'),('14','fee_back','0'),('14','fee_period','0'),('14','fee_view','0'),('14','fee_add','0'),('14','fee_currency','money'),('14','fee_mode','0'),('14','question_add','2'),('14','captcha_add','2'),('14','check_add','2'),('14','question_message','2'),('14','captcha_message','2'),('14','group_upload','6,7'),('14','group_color','7'),('14','group_search','3,5,6,7'),('14','group_show','3,5,6,7'),('14','group_list','3,5,6,7'),('14','group_index','3,5,6,7'),('14','seo_description_search',''),('14','seo_keywords_search',''),('14','seo_title_search',''),('14','seo_description_show',''),('14','seo_keywords_show',''),('14','seo_description_list',''),('14','seo_title_show','{内容标题}{分隔符}{分类名称}{模块名称}{分隔符}{网站名称}'),('14','seo_keywords_list',''),('14','seo_title_list','{分类SEO标题}{页码}{模块名称}{分隔符}{网站名称}'),('14','seo_title_index','{模块名称}{分隔符}{页码}{网站名称}'),('14','seo_description_index',''),('14','seo_keywords_index',''),('14','php_item_urlid','0'),('14','htm_item_urlid','1'),('14','htm_item_prefix',''),('14','show_html','0'),('14','php_list_urlid','0'),('14','htm_list_urlid','0'),('14','htm_list_prefix',''),('14','list_html','0'),('14','index_html','0'),('14','page_comment','0'),('14','hits','1'),('14','max_width','1000'),('14','pagesize','20'),('14','page_icat','6'),('14','page_irec','6'),('14','swfu','0'),('14','upload','mp4|flv'),('14','flvend',''),('14','flvstart',''),('14','flvlink',''),('14','flvmargin','10 auto auto 10'),('14','flvlogo','video.png'),('14','autostart','1'),('14','level','推荐视频'),('14','fulltext','0'),('14','split','0'),('14','keylink','0'),('14','video_width','600'),('14','video_height','500'),('14','introduce_length','120'),('14','editor','Destoon'),('14','order','addtime desc'),('14','fields','itemid,title,thumb,linkurl,style,catid,introduce,addtime,edittime,username,hits'),('14','cat_property','0'),('14','save_remotepic','0'),('14','clear_link','0'),('14','thumb_height','180'),('14','thumb_width','240'),('14','template_my',''),('14','template_search',''),('14','template_show',''),('14','template_list',''),('14','template_index',''),('14','title_index','{$seo_modulename}{$seo_delimiter}{$seo_page}{$seo_sitename}'),('14','title_list','{$seo_cattitle}{$seo_page}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('14','title_show','{$seo_showtitle}{$seo_delimiter}{$seo_catname}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('14','title_search',''),('14','keywords_index',''),('14','keywords_list',''),('14','keywords_show',''),('14','keywords_search',''),('14','description_index',''),('14','description_list',''),('14','description_show',''),('14','description_search',''),('14','module','video'),('14','mobile','http://demo.destoon.com/v7.0/mobile/video/'),('15','limit_6','30'),('15','free_limit_5','0'),('15','limit_5','3'),('15','free_limit_4','0'),('15','free_limit_7','-1'),('15','limit_4','-1'),('15','free_limit_3','0'),('15','limit_3','-1'),('15','free_limit_2','0'),('15','limit_2','-1'),('15','free_limit_1','-1'),('15','limit_1','0'),('15','credit_color','100'),('15','credit_del','5'),('15','credit_add','2'),('15','fee_award','100'),('15','fee_back','0'),('15','fee_period','0'),('15','fee_view','0'),('15','fee_add','0'),('15','fee_currency','money'),('15','fee_mode','0'),('15','question_add','2'),('15','captcha_add','2'),('15','check_add','2'),('15','question_message','2'),('15','captcha_message','2'),('15','limit_7','100'),('15','free_limit_6','0'),('15','group_upload','6,7'),('15','group_color','7'),('15','group_search','3,5,6,7'),('15','group_contact','5,6,7'),('15','group_show','3,5,6,7'),('15','group_list','3,5,6,7'),('15','group_index','3,5,6,7'),('15','seo_description_search',''),('15','seo_keywords_search',''),('15','seo_title_search',''),('15','seo_title_show','{内容标题}{分隔符}{分类名称}{模块名称}{分隔符}{网站名称}'),('15','seo_description_show',''),('15','seo_keywords_show',''),('15','seo_keywords_list',''),('15','seo_description_list',''),('15','seo_keywords_index',''),('15','seo_title_list','{分类SEO标题}{页码}{模块名称}{分隔符}{网站名称}'),('15','seo_description_index',''),('15','seo_title_index','{模块名称}{分隔符}{页码}{网站名称}'),('15','php_item_urlid','0'),('15','htm_item_urlid','1'),('15','htm_item_prefix',''),('15','show_html','0'),('15','php_list_urlid','0'),('15','htm_list_urlid','0'),('15','htm_list_prefix',''),('15','list_html','0'),('15','index_html','0'),('15','page_comment','0'),('15','hits','1'),('15','max_width','550'),('15','pagesize','20'),('15','page_icat','10'),('15','swfu','1'),('15','page_irec','6'),('15','upload','rar|zip|pdf|doc|jpg|gif|png|docx'),('15','readsize','10'),('15','level','推荐下载'),('15','fulltext','0'),('15','split','0'),('15','keylink','0'),('15','clear_link','0'),('15','cat_property','0'),('15','save_remotepic','0'),('15','fields','itemid,title,thumb,linkurl,style,catid,introduce,addtime,edittime,username,fileext,filesize,unit,download'),('15','order','addtime desc'),('15','editor','Destoon'),('15','introduce_length','120'),('15','thumb_height','180'),('15','thumb_width','240'),('15','template_my',''),('15','template_search',''),('15','template_show',''),('15','template_list',''),('15','template_index',''),('15','title_index','{$seo_modulename}{$seo_delimiter}{$seo_page}{$seo_sitename}'),('15','title_list','{$seo_cattitle}{$seo_page}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('15','title_show','{$seo_showtitle}{$seo_delimiter}{$seo_catname}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('15','title_search',''),('15','keywords_index',''),('15','keywords_list',''),('15','keywords_show',''),('15','keywords_search',''),('15','description_index',''),('15','description_list',''),('15','description_show',''),('15','description_search',''),('15','module','down'),('15','mobile','http://demo.destoon.com/v7.0/mobile/down/'),('16','free_limit_7','-1'),('16','limit_7','100'),('16','free_limit_6','0'),('16','limit_6','30'),('16','free_limit_5','0'),('16','limit_5','3'),('16','free_limit_4','0'),('16','limit_4','-1'),('16','free_limit_3','0'),('16','limit_3','-1'),('16','free_limit_2','0'),('16','limit_2','-1'),('16','free_limit_1','-1'),('16','limit_1','0'),('16','credit_refresh','1'),('16','credit_elite','100'),('16','credit_color','100'),('16','credit_del','5'),('16','credit_add','2'),('16','fee_award','0'),('16','fee_back','0'),('16','fee_period','0'),('16','fee_view','0'),('16','fee_add','0'),('16','fee_currency','money'),('16','fee_mode','0'),('16','question_add','2'),('16','captcha_add','2'),('16','check_add','2'),('16','question_inquiry','2'),('16','captcha_inquiry','2'),('16','group_elite','3,5,6,7'),('16','group_compare','3,5,6,7'),('16','group_refresh','3,5,6,7'),('16','group_color','3,5,6,7'),('16','group_search','3,5,6,7'),('16','group_contact','3,5,6,7'),('16','group_show','3,5,6,7'),('16','group_list','3,5,6,7'),('16','group_index','3,5,6,7'),('16','seo_description_search',''),('16','seo_title_search',''),('16','seo_keywords_search',''),('16','seo_description_show','{内容标题}{内容简介}{分类名称}{分类SEO描述}{模块名称}{网站名称}{网站SEO描述}'),('16','seo_keywords_show',''),('16','seo_title_show','{内容标题}{分隔符}{分类名称}{模块名称}{分隔符}{网站名称}'),('16','seo_keywords_list',''),('16','seo_description_list',''),('16','htm_item_prefix','mall_info_'),('16','htm_item_urlid','1'),('16','php_item_urlid','0'),('16','seo_title_index','{模块名称}{分隔符}{页码}{网站名称}'),('16','seo_keywords_index','{模块名称}{网站名称}{网站SEO标题}'),('16','seo_title_list','{分类名称}{分类SEO标题}{页码}{模块名称}{分隔符}{网站名称}{分类SEO标题}{模块名称}{网站名称}{页码}'),('16','seo_description_index','{模块名称}{网站名称}{网站SEO标题}'),('16','show_html','0'),('16','php_list_urlid','0'),('16','htm_list_urlid','0'),('16','htm_list_prefix','mall_list_'),('16','list_html','0'),('16','index_html','0'),('16','hits','1'),('16','max_width','1000'),('16','pagesize','20'),('16','page_inew','12'),('16','page_irec','5'),('16','page_subcat','5'),('16','checkorder','0'),('16','swfu','1'),('16','level','推荐商品'),('16','fulltext','0'),('16','split','0'),('16','keylink','0'),('16','clear_link','0'),('16','fields','itemid,title,thumb,linkurl,style,catid,areaid,brand,addtime,edittime,username,company,groupid,vip,qq,wx,ali,skype,validated,price,amount,orders,comments'),('16','save_remotepic','0'),('16','cat_property','0'),('16','order','editdate desc,vip desc,edittime desc'),('16','editor','Destoon'),('16','introduce_length','0'),('16','thumb_height','200'),('16','thumb_width','200'),('16','template_view',''),('16','template_my',''),('16','template_compare',''),('16','template_search',''),('16','template_show',''),('16','template_list',''),('16','template_index',''),('16','title_index','{$seo_modulename}{$seo_delimiter}{$seo_page}{$seo_sitename}'),('16','title_list','{$seo_catname}{$seo_cattitle}{$seo_page}{$seo_modulename}{$seo_delimiter}{$seo_sitename}{$seo_cattitle}{$seo_modulename}{$seo_sitename}{$seo_page}'),('16','title_show','{$seo_showtitle}{$seo_delimiter}{$seo_catname}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('16','title_search',''),('16','keywords_index','{$seo_modulename}{$seo_sitename}{$seo_sitetitle}'),('16','keywords_list',''),('16','keywords_show',''),('16','keywords_search',''),('16','description_index','{$seo_modulename}{$seo_sitename}{$seo_sitetitle}'),('16','description_list',''),('16','description_show','{$seo_showtitle}{$seo_showintroduce}{$seo_catname}{$seo_catdescription}{$seo_modulename}{$seo_sitename}{$seo_sitedescription}'),('16','description_search',''),('16','module','mall'),('16','mobile','http://demo.destoon.com/v7.0/mobile/mall/'),('17','limit_7','100'),('17','free_limit_7','-1'),('17','free_limit_6','0'),('17','free_limit_5','0'),('17','limit_5','3'),('17','free_limit_4','0'),('17','limit_4','-1'),('17','free_limit_3','0'),('17','limit_3','-1'),('17','free_limit_2','0'),('17','limit_2','-1'),('17','free_limit_1','-1'),('17','limit_1','0'),('17','credit_refresh','1'),('17','credit_color','100'),('17','credit_del','5'),('17','credit_add','2'),('17','fee_award','0'),('17','fee_back','0'),('17','fee_period','0'),('17','fee_view','0'),('17','fee_add','0'),('17','fee_currency','money'),('17','fee_mode','1'),('17','question_add','2'),('17','captcha_add','2'),('17','check_add','2'),('17','question_inquiry','2'),('17','captcha_inquiry','2'),('17','group_refresh','3,5,6,7'),('17','group_color','7'),('17','group_search','3,5,6,7'),('17','group_contact','3,5,6,7'),('17','group_show','3,5,6,7'),('17','group_list','3,5,6,7'),('17','group_index','3,5,6,7'),('17','seo_description_search',''),('17','seo_keywords_search',''),('17','seo_title_search',''),('17','seo_description_show',''),('17','limit_6','30'),('17','seo_keywords_show',''),('17','seo_title_show','{内容标题}{分隔符}{分类名称}{模块名称}{分隔符}{网站名称}'),('17','seo_description_list',''),('17','seo_keywords_list',''),('17','seo_title_index','{模块名称}{分隔符}{页码}{网站名称}'),('17','seo_keywords_index',''),('17','seo_description_index',''),('17','seo_title_list','{分类SEO标题}{页码}{模块名称}{分隔符}{网站名称}'),('17','php_item_urlid','0'),('17','split','0'),('17','fulltext','0'),('17','level','推荐团购'),('17','swfu','1'),('17','page_subcat','9'),('17','page_irec','4'),('17','page_icat','4'),('17','pagesize','9'),('17','max_width','1000'),('17','hits','1'),('17','page_comment','0'),('17','index_html','0'),('17','list_html','0'),('17','htm_list_prefix','group_list_'),('17','htm_list_urlid','0'),('17','php_list_urlid','0'),('17','show_html','0'),('17','htm_item_prefix','group_info_'),('17','htm_item_urlid','1'),('17','keylink','0'),('17','clear_link','0'),('17','save_remotepic','0'),('17','cat_property','0'),('17','fields','itemid,title,thumb,linkurl,style,catid,areaid,introduce,addtime,edittime,username,company,groupid,vip,qq,wx,ali,skype,validated,price,marketprice,savemoney,discount,sales,orders,minamount,amount'),('17','order','addtime desc'),('17','editor','Destoon'),('17','introduce_length','120'),('17','thumb_height','300'),('17','thumb_width','400'),('17','template_buy',''),('17','template_my',''),('17','template_search',''),('17','template_list',''),('17','template_show',''),('17','template_index',''),('17','title_index','{$seo_modulename}{$seo_delimiter}{$seo_page}{$seo_sitename}'),('17','title_list','{$seo_cattitle}{$seo_page}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('17','title_show','{$seo_showtitle}{$seo_delimiter}{$seo_catname}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('17','title_search',''),('17','keywords_index',''),('17','keywords_list',''),('17','keywords_show',''),('17','keywords_search',''),('17','description_index',''),('17','description_list',''),('17','description_show',''),('17','description_search',''),('17','module','group'),('17','mobile','http://demo.destoon.com/v7.0/mobile/group/'),('18','reply_limit_7','100'),('18','join_limit_7','0'),('18','group_limit_7','10'),('18','free_limit_7','-1'),('18','limit_7','100'),('18','reply_limit_6','30'),('18','join_limit_6','0'),('18','group_limit_6','3'),('18','limit_6','30'),('18','free_limit_6','0'),('18','reply_limit_5','10'),('18','join_limit_5','0'),('18','group_limit_5','1'),('18','free_limit_5','0'),('18','limit_5','3'),('18','reply_limit_4','-1'),('18','join_limit_4','-1'),('18','group_limit_4','-1'),('18','free_limit_4','0'),('18','limit_4','-1'),('18','reply_limit_3','-1'),('18','join_limit_3','-1'),('18','group_limit_3','-1'),('18','free_limit_3','0'),('18','limit_3','-1'),('18','reply_limit_2','-1'),('18','join_limit_2','-1'),('18','group_limit_2','-1'),('18','free_limit_2','0'),('18','limit_2','-1'),('18','reply_limit_1','0'),('18','join_limit_1','0'),('18','group_limit_1','0'),('18','free_limit_1','-1'),('18','limit_1','0'),('18','credit_del_reply','2'),('18','credit_reply','1'),('18','credit_del','5'),('18','credit_level','10'),('18','credit_add','3'),('18','pre_view','200'),('18','fee_award','100'),('18','fee_back','0'),('18','fee_period','0'),('18','fee_view','0'),('18','fee_add','0'),('18','fee_currency','money'),('18','fee_mode','1'),('18','question_reply','2'),('18','captcha_reply','2'),('18','check_reply','2'),('18','question_add','2'),('18','captcha_add','2'),('18','check_add','2'),('18','question_group','2'),('18','captcha_group','2'),('18','check_group','2'),('18','group_reply','3,5,6,7'),('18','group_search','3,5,6,7'),('18','group_show','3,5,6,7'),('18','group_list','3,5,6,7'),('18','group_index','3,5,6,7'),('18','seo_description_search',''),('18','seo_keywords_search',''),('18','seo_title_search',''),('18','seo_description_show',''),('18','seo_description_list',''),('18','seo_title_show','{内容标题}{分隔符}{页码}{$GRP[\'title\']}{$MOD[\'seo_name\']}{分隔符}{模块名称}{分隔符}{网站名称}'),('18','seo_keywords_show',''),('18','seo_keywords_list',''),('18','seo_title_list','{分类SEO标题}{页码}{模块名称}{分隔符}{网站名称}'),('18','seo_keywords_index',''),('18','seo_description_index',''),('18','seo_title_index','{模块名称}{分隔符}{页码}{网站名称}'),('18','seo_name','圈'),('18','php_item_urlid','0'),('18','htm_item_urlid','4'),('18','htm_item_prefix',''),('18','show_html','0'),('18','php_list_urlid','0'),('18','htm_list_urlid','0'),('18','htm_list_prefix',''),('18','list_html','0'),('18','index_html','0'),('18','hits','1'),('18','max_width','750'),('18','reply_pagesize','10'),('18','pagesize','20'),('18','maxontop','5'),('18','page_islide','3'),('18','page_icat','6'),('18','floor','沙发|藤椅|板凳|马扎|地板'),('18','manage_reason','1'),('18','manage_message','1'),('18','manage_reasons','广告/SPAM|恶意灌水|违规内容|文不对题|重复发帖|我很赞同|精品文章|原创内容|感谢分享'),('18','swfu','1'),('18','level','精华1|精华2'),('18','fulltext','0'),('18','split','0'),('18','keylink','1'),('18','clear_alink','1'),('18','clear_link','0'),('18','cat_property','0'),('18','save_remotepic','0'),('18','fields','itemid,title,ontop,video,level,thumb,linkurl,style,catid,introduce,hits,addtime,edittime,username,passport,reply,replyer,replytime '),('18','order','addtime desc'),('18','editor','Destoon'),('18','introduce_length','0'),('18','thumb_height','180'),('18','template_my_fans',''),('18','template_my_manage',''),('18','thumb_width','240'),('18','template_my_join',''),('18','template_my_reply',''),('18','template_my_group',''),('18','template_my',''),('18','template_fans',''),('18','template_group',''),('18','template_search',''),('18','template_show',''),('18','template_list',''),('18','template_index',''),('18','title_index','{$seo_modulename}{$seo_delimiter}{$seo_page}{$seo_sitename}'),('18','title_list','{$seo_cattitle}{$seo_page}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('18','title_show','{$seo_showtitle}{$seo_delimiter}{$seo_page}{$GRP[\'title\']}{$MOD[\'seo_name\']}{$seo_delimiter}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('18','title_search',''),('18','keywords_index',''),('18','keywords_list',''),('18','keywords_show',''),('18','keywords_search',''),('18','description_index',''),('18','description_list',''),('18','description_show',''),('18','description_search',''),('18','module','club'),('18','mobile','http://demo.destoon.com/v7.0/mobile/club/'),('21','free_limit_7','-1'),('21','limit_7','100'),('21','free_limit_6','0'),('21','limit_6','30'),('21','free_limit_5','0'),('21','limit_5','3'),('21','free_limit_4','0'),('21','limit_4','-1'),('21','free_limit_3','0'),('21','limit_3','-1'),('21','free_limit_2','0'),('21','limit_2','-1'),('21','free_limit_1','-1'),('21','limit_1','0'),('21','credit_color','100'),('21','credit_del','5'),('21','credit_add','2'),('21','pre_view','200'),('21','fee_award','100'),('21','fee_back','0'),('21','fee_period','0'),('21','fee_view','0'),('21','fee_add','0'),('21','fee_currency','money'),('21','fee_mode','0'),('21','question_add','2'),('21','captcha_add','2'),('21','check_add','2'),('21','group_color','7'),('21','group_search','3,5,6,7'),('21','group_show','3,5,6,7'),('21','group_list','3,5,6,7'),('21','group_index','3,5,6,7'),('21','seo_description_search',''),('21','seo_keywords_search',''),('21','seo_title_search',''),('21','seo_description_show',''),('21','seo_title_show','{内容标题}{分隔符}{分类名称}{模块名称}{分隔符}{网站名称}'),('21','seo_keywords_show',''),('21','seo_description_list',''),('21','seo_keywords_list',''),('21','seo_title_list','{分类SEO标题}{页码}{模块名称}{分隔符}{网站名称}'),('21','seo_description_index',''),('21','seo_keywords_index',''),('21','php_item_urlid','0'),('21','seo_title_index','{模块名称}{分隔符}{页码}{网站名称}'),('21','htm_item_urlid','1'),('21','htm_item_prefix',''),('21','show_html','0'),('21','php_list_urlid','0'),('21','htm_list_urlid','0'),('21','htm_list_prefix',''),('21','list_html','0'),('21','index_html','0'),('21','show_np','1'),('21','page_comment','0'),('21','hits','1'),('21','max_width','800'),('21','page_shits','10'),('21','page_srec','10'),('21','page_srecimg','4'),('21','page_srelate','10'),('21','page_lhits','10'),('21','page_lrec','10'),('21','page_lrecimg','4'),('21','show_lcat','1'),('21','page_child','6'),('21','pagesize','20'),('21','page_ihits','10'),('21','page_irecimg','6'),('21','show_icat','1'),('21','page_icat','6'),('21','page_islide','3'),('21','swfu','2'),('21','fulltext','1'),('21','level','推荐文章|幻灯图片|推荐图文|头条相关|头条推荐'),('21','split','0'),('21','keylink','1'),('21','clear_link','0'),('21','save_remotepic','0'),('21','cat_property','0'),('21','order','addtime desc'),('21','fields','itemid,title,thumb,linkurl,style,catid,introduce,addtime,edittime,username,islink,hits'),('21','editor','Destoon'),('21','introduce_length','120'),('21','thumb_height','180'),('21','thumb_width','240'),('21','template_my',''),('21','template_search',''),('21','template_list',''),('21','template_show',''),('21','template_index',''),('21','title_index','{$seo_modulename}{$seo_delimiter}{$seo_page}{$seo_sitename}'),('21','title_list','{$seo_cattitle}{$seo_page}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('21','title_show','{$seo_showtitle}{$seo_delimiter}{$seo_catname}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('21','title_search',''),('21','keywords_index',''),('21','keywords_list',''),('21','keywords_show',''),('21','keywords_search',''),('21','description_index',''),('21','description_list',''),('21','description_show',''),('21','description_search',''),('21','module','article'),('21','mobile','http://demo.destoon.com/v7.0/mobile/news/'),('22','free_limit_7','-1'),('22','limit_7','100'),('22','free_limit_6','0'),('22','limit_6','30'),('22','free_limit_5','0'),('22','limit_5','3'),('22','free_limit_4','0'),('22','limit_4','-1'),('22','free_limit_3','0'),('22','limit_3','-1'),('22','free_limit_2','0'),('22','limit_2','-1'),('22','free_limit_1','-1'),('22','limit_1','0'),('22','credit_refresh','1'),('22','credit_color','100'),('22','credit_del','5'),('22','credit_add','2'),('22','fee_award','0'),('22','fee_back','0'),('22','fee_period','0'),('22','fee_view','0'),('22','fee_add','0'),('22','fee_currency','money'),('22','check_add','2'),('22','captcha_add','2'),('22','question_add','2'),('22','fee_mode','1'),('22','question_message','2'),('22','group_search','3,5,6,7'),('22','group_color','7'),('22','group_refresh','5,6,7'),('22','captcha_message','2'),('22','group_contact','6,7'),('22','seo_title_search',''),('22','seo_keywords_search',''),('22','group_show','3,5,6,7'),('22','group_list','3,5,6,7'),('22','seo_description_search',''),('22','group_index','3,5,6,7'),('22','seo_keywords_list',''),('22','seo_description_list',''),('22','seo_title_show','{内容标题}{分隔符}{分类名称}{模块名称}{分隔符}{网站名称}'),('22','seo_keywords_show',''),('22','seo_description_show',''),('22','seo_title_list','{分类SEO标题}{页码}{模块名称}{分隔符}{网站名称}'),('22','seo_description_index',''),('22','seo_keywords_index',''),('22','seo_title_index','{模块名称}{分隔符}{页码}{网站名称}'),('22','php_item_urlid','0'),('22','htm_item_urlid','1'),('22','htm_item_prefix',''),('22','php_list_urlid','0'),('22','show_html','0'),('22','htm_list_urlid','0'),('22','htm_list_prefix',''),('22','list_html','0'),('22','index_html','0'),('22','page_comment','0'),('22','hits','1'),('22','max_width','1000'),('22','page_srelate','10'),('22','show_message','1'),('22','page_lkw','10'),('22','show_larea','1'),('22','show_lcat','1'),('22','pagesize','20'),('22','page_ihits','9'),('22','show_iarea','1'),('22','show_icat','1'),('22','page_icat','8'),('22','page_irec','12'),('22','page_subcat','5'),('22','swfu','2'),('22','level','推荐信息'),('22','fulltext','0'),('22','split','0'),('22','message_ask','请问我这个地方有加盟商了吗？|我想加盟，请来电话告诉我具体细节。|初步打算加盟贵公司，请寄资料。|请问贵公司哪里有样板店或直营店？|想了解加盟细节，请尽快寄一份资料。 '),('22','cat_property','0'),('22','save_remotepic','0'),('22','clear_link','0'),('22','keylink','0'),('22','fields','itemid,title,thumb,linkurl,style,catid,areaid,introduce,addtime,edittime,username,company,groupid,vip,qq,wx,ali,skype,validated,islink,hits'),('22','order','edittime desc'),('22','editor','Destoon'),('22','introduce_length','120'),('22','thumb_height','200'),('22','template_message',''),('22','thumb_width','200'),('22','template_search',''),('22','template_my',''),('22','template_show',''),('22','template_list',''),('22','template_index',''),('22','title_index','{$seo_modulename}{$seo_delimiter}{$seo_page}{$seo_sitename}'),('22','title_list','{$seo_cattitle}{$seo_page}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('22','title_show','{$seo_showtitle}{$seo_delimiter}{$seo_catname}{$seo_modulename}{$seo_delimiter}{$seo_sitename}'),('22','title_search',''),('22','keywords_index',''),('22','keywords_list',''),('22','keywords_show',''),('22','keywords_search',''),('22','description_index',''),('22','description_list',''),('22','description_show',''),('22','description_search',''),('22','module','info'),('22','mobile','http://demo.destoon.com/v7.0/mobile/invest/'),('pay-alipay','percent','0'),('pay-alipay','notify',''),('pay-alipay','keycode',''),('pay-alipay','partnerid',''),('pay-alipay','email',''),('pay-alipay','order','1'),('pay-alipay','name','支付宝'),('pay-alipay','enable','0'),('pay-aliwap','percent','0'),('pay-aliwap','notify',''),('pay-aliwap','keycode',''),('pay-aliwap','partnerid',''),('pay-aliwap','order','2'),('pay-aliwap','name','支付宝'),('pay-aliwap','enable','0'),('pay-weixin','percent','2'),('pay-weixin','notify',''),('pay-weixin','keycode',''),('pay-weixin','appid',''),('pay-weixin','partnerid',''),('pay-weixin','order','3'),('pay-weixin','name','微信支付'),('pay-weixin','enable','0'),('pay-tenpay','percent','0'),('pay-tenpay','notify',''),('pay-tenpay','keycode',''),('pay-tenpay','partnerid',''),('pay-tenpay','order','4'),('pay-tenpay','name','财付通'),('pay-tenpay','enable','0'),('pay-upay','percent','0'),('pay-upay','notify',''),('pay-upay','keycode',''),('pay-upay','cert',''),('pay-upay','partnerid',''),('pay-upay','order','5'),('pay-upay','name','中国银联'),('pay-upay','enable','0'),('pay-chinabank','notify',''),('pay-chinabank','keycode',''),('pay-chinabank','partnerid',''),('pay-chinabank','order','6'),('pay-chinabank','name','网银在线'),('pay-chinabank','enable','0'),('pay-yeepay','percent','0'),('pay-yeepay','keycode',''),('pay-yeepay','partnerid',''),('pay-yeepay','order','7'),('pay-yeepay','name','易宝支付'),('pay-yeepay','enable','0'),('pay-kq99bill','percent','0'),('pay-kq99bill','notify',''),('pay-kq99bill','cert',''),('pay-kq99bill','partnerid',''),('pay-kq99bill','order','8'),('pay-kq99bill','name','快钱支付'),('pay-kq99bill','enable','0'),('pay-chinapay','percent','1'),('pay-chinapay','partnerid',''),('pay-chinapay','order','9'),('pay-chinapay','name','银联在线'),('pay-chinapay','enable','0'),('pay-paypal','percent','0'),('pay-paypal','currency','USD'),('pay-paypal','keycode',''),('pay-paypal','notify',''),('pay-paypal','partnerid',''),('pay-paypal','order','10'),('pay-paypal','name','贝宝'),('pay-paypal','enable','0'),('oauth-netease','id',''),('oauth-qq','key',''),('oauth-qq','id',''),('oauth-qq','order','1'),('oauth-qq','name','QQ登录'),('oauth-qq','enable','0'),('oauth-sina','sync','0'),('oauth-sina','key',''),('oauth-sina','id',''),('oauth-sina','order','2'),('oauth-sina','name','新浪微博'),('oauth-sina','enable','0'),('oauth-baidu','key',''),('oauth-baidu','id',''),('oauth-baidu','order','3'),('oauth-baidu','name','百度'),('oauth-baidu','enable','0'),('oauth-netease','order','4'),('oauth-netease','name','网易通行证'),('oauth-netease','enable','0'),('oauth-wechat','key',''),('oauth-wechat','id',''),('oauth-wechat','order','5'),('oauth-wechat','name','微信'),('oauth-wechat','enable','0'),('oauth-taobao','id',''),('oauth-taobao','order','6'),('oauth-taobao','name','淘宝'),('oauth-taobao','enable','0'),('weixin','bind','点击可绑定会员帐号、查看会员信息、收发站内信件、管理我的订单等服务内容'),('weixin','welcome','感谢您的关注，请点击菜单查看相应的服务'),('weixin','auto',''),('weixin','weixin',''),('weixin','aeskey',''),('weixin','apptoken',''),('weixin','appsecret',''),('weixin','appid',''),('weixin','credit','10'),('weixin-menu','menu','a:3:{i:0;a:6:{i:0;a:2:{s:4:\"name\";s:6:\"最新\";s:3:\"key\";s:0:\"\";}i:1;a:2:{s:4:\"name\";s:6:\"资讯\";s:3:\"key\";s:7:\"V_mid21\";}i:2;a:2:{s:4:\"name\";s:6:\"供应\";s:3:\"key\";s:6:\"V_mid5\";}i:3;a:2:{s:4:\"name\";s:6:\"求购\";s:3:\"key\";s:6:\"V_mid6\";}i:4;a:2:{s:4:\"name\";s:6:\"商城\";s:3:\"key\";s:7:\"V_mid16\";}i:5;a:2:{s:4:\"name\";s:6:\"招商\";s:3:\"key\";s:7:\"V_mid22\";}}i:1;a:6:{i:0;a:2:{s:4:\"name\";s:6:\"会员\";s:3:\"key\";s:8:\"V_member\";}i:1;a:2:{s:4:\"name\";s:0:\"\";s:3:\"key\";s:0:\"\";}i:2;a:2:{s:4:\"name\";s:0:\"\";s:3:\"key\";s:0:\"\";}i:3;a:2:{s:4:\"name\";s:0:\"\";s:3:\"key\";s:0:\"\";}i:4;a:2:{s:4:\"name\";s:0:\"\";s:3:\"key\";s:0:\"\";}i:5;a:2:{s:4:\"name\";s:0:\"\";s:3:\"key\";s:0:\"\";}}i:2;a:6:{i:0;a:2:{s:4:\"name\";s:6:\"更多\";s:3:\"key\";s:24:\"http://127.0.0.1/mobile/\";}i:1;a:2:{s:4:\"name\";s:0:\"\";s:3:\"key\";s:0:\"\";}i:2;a:2:{s:4:\"name\";s:0:\"\";s:3:\"key\";s:0:\"\";}i:3;a:2:{s:4:\"name\";s:0:\"\";s:3:\"key\";s:0:\"\";}i:4;a:2:{s:4:\"name\";s:0:\"\";s:3:\"key\";s:0:\"\";}i:5;a:2:{s:4:\"name\";s:0:\"\";s:3:\"key\";s:0:\"\";}}}'),('group-1','listorder','1'),('group-1','reg','0'),('group-1','type','0'),('group-1','edit_limit','0'),('group-1','refresh_limit','0'),('group-1','day_limit','0'),('group-1','hour_limit','0'),('group-1','add_limit','0'),('group-1','copy','1'),('group-1','delete','1'),('group-1','vweixin','0'),('group-1','vdeposit','0'),('group-1','vcompany','0'),('group-1','vtruename','0'),('group-1','vmobile','0'),('group-1','resume','1'),('group-1','vemail','0'),('group-1','moduleids','16,5,6,17,7,8,21,22,13,9,10,12,14,15,18'),('group-1','link_limit','0'),('group-1','honor_limit','0'),('group-1','page_limit','0'),('group-1','news_limit','0'),('group-1','kf','1'),('group-1','stats','1'),('group-1','map','1'),('group-1','style','0'),('group-1','main_d','1,5'),('group-1','main_c','1,5'),('group-1','home_main','0'),('group-1','side_d','0,3,6'),('group-1','side_c','0,3,6'),('group-1','home_side','0'),('group-1','menu_d','0,6,7,11'),('group-1','menu_c','0,6,7,11'),('group-1','home_menu','0'),('group-1','home','0'),('group-1','styleid','0'),('group-1','homepage','0'),('group-1','type_limit','0'),('group-1','price_limit','0'),('group-1','inquiry_limit','0'),('group-1','message_limit','0'),('group-1','promo_limit','0'),('group-1','express_limit','0'),('group-1','address_limit','0'),('group-1','alert_limit','0'),('group-1','favorite_limit','0'),('group-1','friend_limit','0'),('group-1','inbox_limit','0'),('group-1','chat','1'),('group-1','ad','1'),('group-1','spread','1'),('group-1','sms','1'),('group-1','sendmail','1'),('group-1','trade_order','1'),('group-1','group_order','1'),('group-1','mail','1'),('group-1','ask','1'),('group-1','cash','1'),('group-1','question','0'),('group-1','captcha','0'),('group-1','check','0'),('group-1','uploadpt','0'),('group-1','uploadcredit','0'),('group-1','uploadday','0'),('group-1','uploadlimit','0'),('group-1','uploadsize','0'),('group-1','uploadtype',''),('group-1','upload','1'),('group-1','editor','Destoon'),('group-1','grade','0'),('group-1','biz','1'),('group-1','commission','0'),('group-1','discount','100'),('group-1','fee','0'),('group-1','fee_mode','0'),('group-2','listorder','2'),('group-2','reg','0'),('group-2','type','0'),('group-2','vmobile','0'),('group-2','edit_limit','-1'),('group-2','refresh_limit','-1'),('group-2','day_limit','-1'),('group-2','hour_limit','-1'),('group-2','add_limit','-1'),('group-2','copy','0'),('group-2','delete','0'),('group-2','vweixin','0'),('group-2','vdeposit','0'),('group-2','vcompany','0'),('group-2','vtruename','0'),('group-2','vemail','0'),('group-2','resume','0'),('group-2','moduleids','6'),('group-2','link_limit','-1'),('group-2','honor_limit','-1'),('group-2','page_limit','-1'),('group-2','news_limit','-1'),('group-2','kf','0'),('group-2','stats','0'),('group-2','map','0'),('group-2','style','0'),('group-2','main_d','5'),('group-2','main_c','5'),('group-2','home_main','0'),('group-2','side_d','0'),('group-2','side_c','0'),('group-2','home_side','0'),('group-2','menu_d','0'),('group-2','menu_c','0'),('group-2','home_menu','0'),('group-2','home','0'),('group-2','styleid','0'),('group-2','homepage','0'),('group-2','type_limit','-1'),('group-2','price_limit','-1'),('group-2','inquiry_limit','-1'),('group-2','message_limit','-1'),('group-2','promo_limit','-1'),('group-2','express_limit','-1'),('group-2','address_limit','-1'),('group-2','alert_limit','-1'),('group-2','favorite_limit','-1'),('group-2','friend_limit','-1'),('group-2','inbox_limit','-1'),('group-2','chat','0'),('group-2','ad','0'),('group-2','group_order','0'),('group-2','spread','0'),('group-2','trade_order','0'),('group-2','sendmail','0'),('group-2','sms','0'),('group-2','mail','0'),('group-2','ask','0'),('group-2','cash','0'),('group-2','question','1'),('group-2','captcha','1'),('group-2','check','1'),('group-2','uploadpt','1'),('group-2','uploadcredit','10'),('group-2','uploadday','10'),('group-2','uploadlimit','2'),('group-2','uploadsize','200'),('group-2','uploadtype',''),('group-2','upload','0'),('group-2','editor','Basic'),('group-2','grade','0'),('group-2','biz','0'),('group-2','commission','0'),('group-2','discount','100'),('group-2','fee','0'),('group-2','fee_mode','0'),('group-3','listorder','3'),('group-3','reg','0'),('group-3','type','0'),('group-3','refresh_limit','-1'),('group-3','day_limit','3'),('group-3','edit_limit','-1'),('group-3','hour_limit','1'),('group-3','add_limit','30'),('group-3','copy','0'),('group-3','vweixin','0'),('group-3','delete','0'),('group-3','vdeposit','0'),('group-3','vcompany','0'),('group-3','vtruename','0'),('group-3','vmobile','0'),('group-3','vemail','0'),('group-3','resume','0'),('group-3','moduleids','5,6,8,22,9'),('group-3','link_limit','-1'),('group-3','honor_limit','-1'),('group-3','page_limit','-1'),('group-3','news_limit','-1'),('group-3','kf','0'),('group-3','stats','0'),('group-3','map','0'),('group-3','style','0'),('group-3','main_d','5'),('group-3','main_c','5'),('group-3','home_main','0'),('group-3','side_d','0'),('group-3','side_c','0'),('group-3','home_side','0'),('group-3','menu_d','0'),('group-3','menu_c','0'),('group-3','home_menu','0'),('group-3','home','0'),('group-3','styleid','0'),('group-3','homepage','0'),('group-3','type_limit','-1'),('group-3','price_limit','10'),('group-3','inquiry_limit','30'),('group-3','message_limit','30'),('group-3','promo_limit','-1'),('group-3','express_limit','-1'),('group-3','address_limit','-1'),('group-3','alert_limit','-1'),('group-3','favorite_limit','-1'),('group-3','friend_limit','-1'),('group-3','inbox_limit','-1'),('group-3','chat','1'),('group-3','ad','0'),('group-3','spread','0'),('group-3','group_order','0'),('group-3','trade_order','0'),('group-3','sendmail','0'),('group-3','sms','0'),('group-3','mail','0'),('group-3','ask','0'),('group-3','cash','0'),('group-3','question','1'),('group-3','captcha','1'),('group-3','check','1'),('group-3','uploadpt','1'),('group-3','uploadcredit','0'),('group-3','uploadday','10'),('group-3','uploadlimit','5'),('group-3','uploadsize','500'),('group-3','uploadtype',''),('group-3','upload','0'),('group-3','editor','Basic'),('group-3','grade','0'),('group-3','biz','0'),('group-3','commission','0'),('group-3','discount','100'),('group-3','fee','0'),('group-3','fee_mode','0'),('group-4','listorder','4'),('group-4','reg','0'),('group-4','type','0'),('group-4','edit_limit','-1'),('group-4','refresh_limit','-1'),('group-4','day_limit','-1'),('group-4','hour_limit','-1'),('group-4','add_limit','-1'),('group-4','copy','0'),('group-4','delete','0'),('group-4','vweixin','0'),('group-4','vdeposit','0'),('group-4','vcompany','0'),('group-4','vtruename','0'),('group-4','vmobile','0'),('group-4','vemail','0'),('group-4','resume','0'),('group-4','moduleids','6'),('group-4','link_limit','-1'),('group-4','honor_limit','-1'),('group-4','page_limit','-1'),('group-4','news_limit','-1'),('group-4','kf','0'),('group-4','stats','0'),('group-4','map','0'),('group-4','style','0'),('group-4','main_c','5'),('group-4','main_d','5'),('group-4','home_main','0'),('group-4','side_d','0'),('group-4','menu_c','0'),('group-4','menu_d','0'),('group-4','side_c','0'),('group-4','home_side','0'),('group-4','home_menu','0'),('group-4','home','0'),('group-4','styleid','0'),('group-4','homepage','0'),('group-4','type_limit','-1'),('group-4','price_limit','-1'),('group-4','inquiry_limit','-1'),('group-4','message_limit','-1'),('group-4','promo_limit','-1'),('group-4','express_limit','-1'),('group-4','address_limit','-1'),('group-4','alert_limit','-1'),('group-4','favorite_limit','-1'),('group-4','friend_limit','-1'),('group-4','inbox_limit','-1'),('group-4','trade_order','0'),('group-4','group_order','0'),('group-4','spread','0'),('group-4','ad','0'),('group-4','chat','1'),('group-4','sendmail','0'),('group-4','sms','0'),('group-4','mail','0'),('group-4','ask','0'),('group-4','cash','0'),('group-4','question','1'),('group-4','captcha','1'),('group-4','check','1'),('group-4','uploadpt','1'),('group-4','uploadcredit','5'),('group-4','uploadday','10'),('group-4','uploadlimit','5'),('group-4','uploadsize','500'),('group-4','uploadtype',''),('group-4','upload','0'),('group-4','editor','Basic'),('group-4','grade','0'),('group-4','biz','0'),('group-4','commission','0'),('group-4','discount','100'),('group-4','fee','0'),('group-4','fee_mode','0'),('group-5','listorder','5'),('group-5','reg','1'),('group-5','type','0'),('group-5','edit_limit','3'),('group-5','day_limit','3'),('group-5','refresh_limit','43200'),('group-5','hour_limit','1'),('group-5','add_limit','60'),('group-5','copy','1'),('group-5','delete','1'),('group-5','vweixin','0'),('group-5','vdeposit','0'),('group-5','vcompany','0'),('group-5','vtruename','0'),('group-5','vmobile','0'),('group-5','vemail','0'),('group-5','resume','1'),('group-5','moduleids','5,6,10,12,18'),('group-5','link_limit','-1'),('group-5','honor_limit','-1'),('group-5','page_limit','-1'),('group-5','news_limit','-1'),('group-5','kf','0'),('group-5','stats','0'),('group-5','map','0'),('group-5','style','0'),('group-5','main_d','5'),('group-5','main_c','5'),('group-5','home_main','0'),('group-5','side_d','0'),('group-5','side_c','0'),('group-5','home_side','0'),('group-5','menu_d','0'),('group-5','menu_c','0'),('group-5','home_menu','0'),('group-5','home','0'),('group-5','styleid','0'),('group-5','homepage','0'),('group-5','type_limit','10'),('group-5','price_limit','-1'),('group-5','inquiry_limit','3'),('group-5','message_limit','10'),('group-5','promo_limit','-1'),('group-5','express_limit','-1'),('group-5','address_limit','10'),('group-5','alert_limit','3'),('group-5','favorite_limit','20'),('group-5','friend_limit','10'),('group-5','inbox_limit','20'),('group-5','chat','1'),('group-5','ad','1'),('group-5','spread','0'),('group-5','group_order','0'),('group-5','trade_order','0'),('group-5','sendmail','1'),('group-5','sms','1'),('group-5','mail','1'),('group-5','ask','0'),('group-5','cash','0'),('group-5','question','1'),('group-5','captcha','1'),('group-5','check','1'),('group-5','uploadpt','1'),('group-5','uploadcredit','1'),('group-5','uploadday','20'),('group-5','uploadlimit','5'),('group-5','uploadsize',''),('group-5','uploadtype',''),('group-5','upload','1'),('group-5','editor','Simple'),('group-5','grade','1'),('group-5','biz','0'),('group-5','commission','0'),('group-5','discount','100'),('group-5','fee','0'),('group-5','fee_mode','0'),('group-6','listorder','6'),('group-6','reg','1'),('group-6','type','1'),('group-6','day_limit','5'),('group-6','refresh_limit','21600'),('group-6','edit_limit','0'),('group-6','hour_limit','2'),('group-6','add_limit','60'),('group-6','copy','1'),('group-6','delete','1'),('group-6','vweixin','0'),('group-6','vtruename','0'),('group-6','vcompany','0'),('group-6','vdeposit','0'),('group-6','vmobile','0'),('group-6','vemail','0'),('group-6','resume','0'),('group-6','moduleids','16,5,6,17,7,8,22,13,9,10,12'),('group-6','link_limit','20'),('group-6','honor_limit','10'),('group-6','page_limit','5'),('group-6','news_limit','20'),('group-6','kf','0'),('group-6','map','1'),('group-6','stats','0'),('group-6','style','0'),('group-6','main_d','0,1,2'),('group-6','main_c','0,1,2,3,4,5,6'),('group-6','home_main','0'),('group-6','side_c','0,1,2,3,4,5,6'),('group-6','side_d','0,2,4,6'),('group-6','home_menu','0'),('group-6','menu_c','0,1,2,3,4,5,6,7,8,9,10,11'),('group-6','menu_d','0,1,2,3,4,6,7'),('group-6','home_side','0'),('group-6','home','0'),('group-6','styleid','0'),('group-6','type_limit','10'),('group-6','homepage','1'),('group-6','price_limit','3'),('group-6','inquiry_limit','10'),('group-6','message_limit','20'),('group-6','promo_limit','3'),('group-6','express_limit','5'),('group-6','address_limit','10'),('group-6','alert_limit','5'),('group-6','favorite_limit','50'),('group-6','friend_limit','50'),('group-6','inbox_limit','50'),('group-6','group_order','1'),('group-6','spread','0'),('group-6','ad','1'),('group-6','chat','1'),('group-6','trade_order','1'),('group-6','sendmail','1'),('group-6','sms','1'),('group-6','mail','1'),('group-6','ask','1'),('group-6','cash','0'),('group-6','question','1'),('group-6','captcha','1'),('group-6','check','1'),('group-6','uploadday','50'),('group-6','uploadcredit','2'),('group-6','uploadpt','0'),('group-6','uploadlimit','5'),('group-6','uploadsize',''),('group-6','uploadtype',''),('group-6','editor','Destoon'),('group-6','upload','1'),('group-6','grade','1'),('group-6','biz','1'),('group-6','commission','0'),('group-6','discount','100'),('group-6','fee','0'),('group-6','fee_mode','0'),('group-7','listorder','7'),('group-7','reg','0'),('group-7','type','1'),('group-7','edit_limit','0'),('group-7','refresh_limit','3600'),('group-7','day_limit','10'),('group-7','hour_limit','5'),('group-7','add_limit','0'),('group-7','copy','1'),('group-7','delete','1'),('group-7','vweixin','0'),('group-7','vdeposit','0'),('group-7','vcompany','0'),('group-7','vtruename','0'),('group-7','vmobile','0'),('group-7','resume','0'),('group-7','vemail','1'),('group-7','moduleids','16,5,6,17,7,8,21,22,13,9,10,12,14,15,18'),('group-7','link_limit','0'),('group-7','kf','1'),('group-7','news_limit','0'),('group-7','page_limit','10'),('group-7','honor_limit','0'),('group-7','stats','1'),('group-7','map','1'),('group-7','style','1'),('group-7','main_d','0,1,2,7'),('group-7','main_c','0,1,2,4,5,6,7'),('group-7','home_main','1'),('group-7','side_c','0,1,2,3,4,5,6'),('group-7','side_d','0,1,2,4,6'),('group-7','home_side','1'),('group-7','menu_d','0,1,2,3,4,5,6,7,8,9,10,11,12,13'),('group-7','home','1'),('group-7','home_menu','1'),('group-7','menu_c','0,1,2,3,4,5,6,7,8,9,10,11,12,13'),('group-7','styleid','2'),('group-7','homepage','1'),('group-7','type_limit','20'),('group-7','price_limit','20'),('group-7','inquiry_limit','50'),('group-7','message_limit','100'),('group-7','promo_limit','5'),('group-7','express_limit','10'),('group-7','address_limit','10'),('group-7','alert_limit','10'),('group-7','favorite_limit','100'),('group-7','friend_limit','200'),('group-7','inbox_limit','500'),('group-7','chat','1'),('group-7','ad','1'),('group-7','spread','1'),('group-7','group_order','1'),('group-7','trade_order','1'),('group-7','sendmail','1'),('group-7','sms','1'),('group-7','mail','1'),('group-7','ask','1'),('group-7','cash','1'),('group-7','question','0'),('group-7','captcha','0'),('group-7','check','0'),('group-7','uploadpt','0'),('group-7','uploadcredit','0'),('group-7','uploadday','100'),('group-7','uploadlimit','10'),('group-7','uploadsize',''),('group-7','uploadtype',''),('group-7','upload','1'),('group-7','editor','Destoon'),('group-7','grade','1'),('group-7','biz','1'),('group-7','commission','0'),('group-7','discount',''),('group-7','fee','2000'),('group-7','fee_mode','1'),('destoon','backtime','1540533277'),('pay-chinabank','percent','0'),('oauth-netease','key',''),('oauth-taobao','key',''),('2','send_types','平邮|EMS|顺丰快递|申通快递|圆通快递|中通快递|国通快递|宅急送|韵达快递|天天快递|如风达|百世汇通|全峰快递|快捷快递|其它'),('2','credit_less','1'),('2','credit_edit','10'),('2','credit_login','1'),('2','credit_user','20'),('2','credit_ip','2'),('2','credit_maxip','50'),('2','credit_charge','1'),('2','credit_add_credit','2'),('2','credit_del_credit','5'),('2','credit_add_news','2'),('2','credit_del_news','5'),('2','credit_add_page','2'),('2','credit_del_page','5'),('2','credit_buy','30|100|500|1000'),('2','credit_price','5|10|45|85'),('2','credit_exchange','0'),('2','ex_type','PW'),('2','ex_host','localhost'),('2','ex_user','root'),('2','ex_pass',''),('2','ex_data',''),('2','ex_prex',''),('2','ex_fdnm',''),('2','ex_rate',''),('2','ex_name',''),('2','passport','0'),('2','passport_charset','gbk'),('2','passport_url',''),('2','passport_key',''),('2','uc_api',''),('2','uc_ip',''),('2','uc_mysql','1'),('2','uc_dbhost',''),('2','uc_dbuser',''),('2','uc_dbpwd',''),('2','uc_dbname',''),('2','uc_dbpre',''),('2','uc_charset','utf8'),('2','uc_appid',''),('2','uc_key',''),('2','uc_bbs','1'),('2','uc_bbspre',''),('2','oauth','0');
/*!40000 ALTER TABLE `destoon_setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_sms`
--

DROP TABLE IF EXISTS `destoon_sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_sms` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(30) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  `word` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `sendtime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL,
  `code` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='短信记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_sms`
--

LOCK TABLES `destoon_sms` WRITE;
/*!40000 ALTER TABLE `destoon_sms` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_sms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_special_11`
--

DROP TABLE IF EXISTS `destoon_special_11`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_special_11` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `tag` varchar(100) NOT NULL DEFAULT '',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `pptword` varchar(255) NOT NULL DEFAULT '',
  `items` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `banner` varchar(255) NOT NULL DEFAULT '',
  `cfg_photo` smallint(4) unsigned NOT NULL DEFAULT '0',
  `cfg_video` smallint(4) unsigned NOT NULL DEFAULT '0',
  `cfg_type` smallint(4) unsigned NOT NULL DEFAULT '0',
  `seo_title` varchar(255) NOT NULL DEFAULT '',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '',
  `seo_description` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '0',
  `template_type` varchar(30) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `islink` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `domain` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `addtime` (`addtime`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='专题';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_special_11`
--

LOCK TABLES `destoon_special_11` WRITE;
/*!40000 ALTER TABLE `destoon_special_11` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_special_11` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_special_data_11`
--

DROP TABLE IF EXISTS `destoon_special_data_11`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_special_data_11` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` longtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='专题内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_special_data_11`
--

LOCK TABLES `destoon_special_data_11` WRITE;
/*!40000 ALTER TABLE `destoon_special_data_11` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_special_data_11` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_special_item_11`
--

DROP TABLE IF EXISTS `destoon_special_item_11`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_special_item_11` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `specialid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `typeid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `addtime` (`addtime`),
  KEY `specialid` (`specialid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='专题信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_special_item_11`
--

LOCK TABLES `destoon_special_item_11` WRITE;
/*!40000 ALTER TABLE `destoon_special_item_11` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_special_item_11` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_sphinx`
--

DROP TABLE IF EXISTS `destoon_sphinx`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_sphinx` (
  `moduleid` int(10) unsigned NOT NULL DEFAULT '0',
  `maxid` bigint(20) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `moduleid` (`moduleid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Sphinx';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_sphinx`
--

LOCK TABLES `destoon_sphinx` WRITE;
/*!40000 ALTER TABLE `destoon_sphinx` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_sphinx` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_spread`
--

DROP TABLE IF EXISTS `destoon_spread`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_spread` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `tid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `word` varchar(50) NOT NULL DEFAULT '',
  `price` float NOT NULL DEFAULT '0',
  `currency` varchar(30) NOT NULL DEFAULT '',
  `company` varchar(100) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `fromtime` int(10) unsigned NOT NULL DEFAULT '0',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='排名推广';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_spread`
--

LOCK TABLES `destoon_spread` WRITE;
/*!40000 ALTER TABLE `destoon_spread` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_spread` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_spread_price`
--

DROP TABLE IF EXISTS `destoon_spread_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_spread_price` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `word` varchar(50) NOT NULL DEFAULT '',
  `price` float NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='排名起价';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_spread_price`
--

LOCK TABLES `destoon_spread_price` WRITE;
/*!40000 ALTER TABLE `destoon_spread_price` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_spread_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_style`
--

DROP TABLE IF EXISTS `destoon_style`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_style` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `skin` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(50) NOT NULL DEFAULT '',
  `author` varchar(30) NOT NULL DEFAULT '',
  `groupid` varchar(30) NOT NULL DEFAULT '',
  `fee` float NOT NULL DEFAULT '0',
  `currency` varchar(20) NOT NULL DEFAULT '',
  `money` float NOT NULL DEFAULT '0',
  `credit` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='公司主页模板';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_style`
--

LOCK TABLES `destoon_style` WRITE;
/*!40000 ALTER TABLE `destoon_style` DISABLE KEYS */;
INSERT INTO `destoon_style` VALUES (1,0,'默认模板','default','homepage','DESTOON.COM',',6,7,',0,'money',0,0,0,1540533277,'destoon',1540533277,0),(2,0,'深蓝模板','blue','homepage','DESTOON.COM',',6,7,',0,'money',0,0,0,1540533277,'destoon',1540533277,0),(3,0,'绿色模板','green','homepage','DESTOON.COM',',6,7,',0,'money',0,0,0,1540533277,'destoon',1540533277,0),(4,0,'紫色模板','purple','homepage','DESTOON.COM',',6,7,',0,'money',0,0,0,1540533277,'destoon',1540533277,0),(5,0,'橙色模板','orange','homepage','DESTOON.COM',',6,7,',0,'money',0,0,0,1540533277,'destoon',1540533277,0);
/*!40000 ALTER TABLE `destoon_style` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_type`
--

DROP TABLE IF EXISTS `destoon_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_type` (
  `typeid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(4) NOT NULL DEFAULT '0',
  `typename` varchar(255) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `item` varchar(20) NOT NULL DEFAULT '',
  `cache` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`typeid`),
  KEY `listorder` (`listorder`),
  KEY `item` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分类';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_type`
--

LOCK TABLES `destoon_type` WRITE;
/*!40000 ALTER TABLE `destoon_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_upgrade`
--

DROP TABLE IF EXISTS `destoon_upgrade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_upgrade` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `gid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `groupid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `amount` float NOT NULL DEFAULT '0',
  `message` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `company` varchar(100) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `reason` text NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员升级';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_upgrade`
--

LOCK TABLES `destoon_upgrade` WRITE;
/*!40000 ALTER TABLE `destoon_upgrade` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_upgrade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_upload_0`
--

DROP TABLE IF EXISTS `destoon_upload_0`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_upload_0` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(32) NOT NULL DEFAULT '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `fileurl` varchar(255) NOT NULL DEFAULT '',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0',
  `fileext` varchar(10) NOT NULL DEFAULT '',
  `upfrom` varchar(10) NOT NULL DEFAULT '',
  `width` int(10) unsigned NOT NULL DEFAULT '0',
  `height` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`pid`),
  KEY `item` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='上传记录0';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_upload_0`
--

LOCK TABLES `destoon_upload_0` WRITE;
/*!40000 ALTER TABLE `destoon_upload_0` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_upload_0` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_upload_1`
--

DROP TABLE IF EXISTS `destoon_upload_1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_upload_1` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(32) NOT NULL DEFAULT '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `fileurl` varchar(255) NOT NULL DEFAULT '',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0',
  `fileext` varchar(10) NOT NULL DEFAULT '',
  `upfrom` varchar(10) NOT NULL DEFAULT '',
  `width` int(10) unsigned NOT NULL DEFAULT '0',
  `height` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`pid`),
  KEY `item` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='上传记录1';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_upload_1`
--

LOCK TABLES `destoon_upload_1` WRITE;
/*!40000 ALTER TABLE `destoon_upload_1` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_upload_1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_upload_2`
--

DROP TABLE IF EXISTS `destoon_upload_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_upload_2` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(32) NOT NULL DEFAULT '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `fileurl` varchar(255) NOT NULL DEFAULT '',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0',
  `fileext` varchar(10) NOT NULL DEFAULT '',
  `upfrom` varchar(10) NOT NULL DEFAULT '',
  `width` int(10) unsigned NOT NULL DEFAULT '0',
  `height` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`pid`),
  KEY `item` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='上传记录2';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_upload_2`
--

LOCK TABLES `destoon_upload_2` WRITE;
/*!40000 ALTER TABLE `destoon_upload_2` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_upload_2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_upload_3`
--

DROP TABLE IF EXISTS `destoon_upload_3`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_upload_3` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(32) NOT NULL DEFAULT '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `fileurl` varchar(255) NOT NULL DEFAULT '',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0',
  `fileext` varchar(10) NOT NULL DEFAULT '',
  `upfrom` varchar(10) NOT NULL DEFAULT '',
  `width` int(10) unsigned NOT NULL DEFAULT '0',
  `height` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`pid`),
  KEY `item` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='上传记录3';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_upload_3`
--

LOCK TABLES `destoon_upload_3` WRITE;
/*!40000 ALTER TABLE `destoon_upload_3` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_upload_3` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_upload_4`
--

DROP TABLE IF EXISTS `destoon_upload_4`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_upload_4` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(32) NOT NULL DEFAULT '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `fileurl` varchar(255) NOT NULL DEFAULT '',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0',
  `fileext` varchar(10) NOT NULL DEFAULT '',
  `upfrom` varchar(10) NOT NULL DEFAULT '',
  `width` int(10) unsigned NOT NULL DEFAULT '0',
  `height` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`pid`),
  KEY `item` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='上传记录4';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_upload_4`
--

LOCK TABLES `destoon_upload_4` WRITE;
/*!40000 ALTER TABLE `destoon_upload_4` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_upload_4` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_upload_5`
--

DROP TABLE IF EXISTS `destoon_upload_5`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_upload_5` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(32) NOT NULL DEFAULT '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `fileurl` varchar(255) NOT NULL DEFAULT '',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0',
  `fileext` varchar(10) NOT NULL DEFAULT '',
  `upfrom` varchar(10) NOT NULL DEFAULT '',
  `width` int(10) unsigned NOT NULL DEFAULT '0',
  `height` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`pid`),
  KEY `item` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='上传记录5';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_upload_5`
--

LOCK TABLES `destoon_upload_5` WRITE;
/*!40000 ALTER TABLE `destoon_upload_5` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_upload_5` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_upload_6`
--

DROP TABLE IF EXISTS `destoon_upload_6`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_upload_6` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(32) NOT NULL DEFAULT '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `fileurl` varchar(255) NOT NULL DEFAULT '',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0',
  `fileext` varchar(10) NOT NULL DEFAULT '',
  `upfrom` varchar(10) NOT NULL DEFAULT '',
  `width` int(10) unsigned NOT NULL DEFAULT '0',
  `height` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`pid`),
  KEY `item` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='上传记录6';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_upload_6`
--

LOCK TABLES `destoon_upload_6` WRITE;
/*!40000 ALTER TABLE `destoon_upload_6` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_upload_6` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_upload_7`
--

DROP TABLE IF EXISTS `destoon_upload_7`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_upload_7` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(32) NOT NULL DEFAULT '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `fileurl` varchar(255) NOT NULL DEFAULT '',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0',
  `fileext` varchar(10) NOT NULL DEFAULT '',
  `upfrom` varchar(10) NOT NULL DEFAULT '',
  `width` int(10) unsigned NOT NULL DEFAULT '0',
  `height` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`pid`),
  KEY `item` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='上传记录7';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_upload_7`
--

LOCK TABLES `destoon_upload_7` WRITE;
/*!40000 ALTER TABLE `destoon_upload_7` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_upload_7` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_upload_8`
--

DROP TABLE IF EXISTS `destoon_upload_8`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_upload_8` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(32) NOT NULL DEFAULT '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `fileurl` varchar(255) NOT NULL DEFAULT '',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0',
  `fileext` varchar(10) NOT NULL DEFAULT '',
  `upfrom` varchar(10) NOT NULL DEFAULT '',
  `width` int(10) unsigned NOT NULL DEFAULT '0',
  `height` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`pid`),
  KEY `item` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='上传记录8';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_upload_8`
--

LOCK TABLES `destoon_upload_8` WRITE;
/*!40000 ALTER TABLE `destoon_upload_8` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_upload_8` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_upload_9`
--

DROP TABLE IF EXISTS `destoon_upload_9`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_upload_9` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(32) NOT NULL DEFAULT '',
  `tb` varchar(30) NOT NULL,
  `moduleid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `fileurl` varchar(255) NOT NULL DEFAULT '',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0',
  `fileext` varchar(10) NOT NULL DEFAULT '',
  `upfrom` varchar(10) NOT NULL DEFAULT '',
  `width` int(10) unsigned NOT NULL DEFAULT '0',
  `height` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`pid`),
  KEY `item` (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='上传记录9';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_upload_9`
--

LOCK TABLES `destoon_upload_9` WRITE;
/*!40000 ALTER TABLE `destoon_upload_9` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_upload_9` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_validate`
--

DROP TABLE IF EXISTS `destoon_validate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_validate` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(30) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `thumb1` varchar(255) NOT NULL DEFAULT '',
  `thumb2` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资料认证';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_validate`
--

LOCK TABLES `destoon_validate` WRITE;
/*!40000 ALTER TABLE `destoon_validate` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_validate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_video_14`
--

DROP TABLE IF EXISTS `destoon_video_14`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_video_14` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `fee` float NOT NULL DEFAULT '0',
  `tag` varchar(255) NOT NULL DEFAULT '',
  `album` varchar(100) NOT NULL,
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `pptword` varchar(255) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `video` varchar(255) NOT NULL DEFAULT '',
  `mobile` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `width` smallint(4) unsigned NOT NULL DEFAULT '0',
  `height` smallint(4) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`),
  KEY `username` (`username`),
  KEY `addtime` (`addtime`),
  KEY `catid` (`catid`),
  KEY `album` (`album`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='视频';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_video_14`
--

LOCK TABLES `destoon_video_14` WRITE;
/*!40000 ALTER TABLE `destoon_video_14` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_video_14` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_video_data_14`
--

DROP TABLE IF EXISTS `destoon_video_data_14`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_video_data_14` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='视频内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_video_data_14`
--

LOCK TABLES `destoon_video_data_14` WRITE;
/*!40000 ALTER TABLE `destoon_video_data_14` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_video_data_14` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_vote`
--

DROP TABLE IF EXISTS `destoon_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_vote` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` int(10) unsigned NOT NULL DEFAULT '0',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `groupid` varchar(255) NOT NULL,
  `verify` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `choose` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `vote_min` smallint(2) unsigned NOT NULL DEFAULT '0',
  `vote_max` smallint(2) unsigned NOT NULL DEFAULT '0',
  `votes` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `fromtime` int(10) unsigned NOT NULL DEFAULT '0',
  `totime` int(10) unsigned NOT NULL DEFAULT '0',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `linkto` varchar(255) NOT NULL DEFAULT '',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `template_vote` varchar(30) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '',
  `s1` varchar(255) NOT NULL DEFAULT '',
  `s2` varchar(255) NOT NULL DEFAULT '',
  `s3` varchar(255) NOT NULL DEFAULT '',
  `s4` varchar(255) NOT NULL DEFAULT '',
  `s5` varchar(255) NOT NULL DEFAULT '',
  `s6` varchar(255) NOT NULL DEFAULT '',
  `s7` varchar(255) NOT NULL DEFAULT '',
  `s8` varchar(255) NOT NULL DEFAULT '',
  `s9` varchar(255) NOT NULL DEFAULT '',
  `s10` varchar(255) NOT NULL DEFAULT '',
  `v1` int(10) unsigned NOT NULL DEFAULT '0',
  `v2` int(10) unsigned NOT NULL DEFAULT '0',
  `v3` int(10) unsigned NOT NULL DEFAULT '0',
  `v4` int(10) unsigned NOT NULL DEFAULT '0',
  `v5` int(10) unsigned NOT NULL DEFAULT '0',
  `v6` int(10) unsigned NOT NULL DEFAULT '0',
  `v7` int(10) unsigned NOT NULL DEFAULT '0',
  `v8` int(10) unsigned NOT NULL DEFAULT '0',
  `v9` int(10) unsigned NOT NULL DEFAULT '0',
  `v10` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='投票';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_vote`
--

LOCK TABLES `destoon_vote` WRITE;
/*!40000 ALTER TABLE `destoon_vote` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_vote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_vote_record`
--

DROP TABLE IF EXISTS `destoon_vote_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_vote_record` (
  `rid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `ip` varchar(50) NOT NULL DEFAULT '',
  `votetime` int(10) unsigned NOT NULL DEFAULT '0',
  `votes` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`rid`),
  KEY `itemid` (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='投票记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_vote_record`
--

LOCK TABLES `destoon_vote_record` WRITE;
/*!40000 ALTER TABLE `destoon_vote_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_vote_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_webpage`
--

DROP TABLE IF EXISTS `destoon_webpage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_webpage` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(30) NOT NULL DEFAULT '',
  `areaid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(50) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `seo_title` varchar(255) NOT NULL DEFAULT '',
  `seo_keywords` varchar(255) NOT NULL DEFAULT '',
  `seo_description` varchar(255) NOT NULL DEFAULT '',
  `editor` varchar(30) NOT NULL DEFAULT '',
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(4) NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `islink` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `domain` varchar(255) NOT NULL DEFAULT '',
  `template` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='单网页';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_webpage`
--

LOCK TABLES `destoon_webpage` WRITE;
/*!40000 ALTER TABLE `destoon_webpage` DISABLE KEYS */;
INSERT INTO `destoon_webpage` VALUES (1,'1',0,0,'关于我们','','关于我们','','','','destoon',1319006891,5,0,0,'about/index.html','',''),(2,'1',0,0,'联系方式','','联系方式','','','','destoon',1310696453,4,0,0,'about/contact.html','',''),(3,'1',0,0,'使用协议','','使用协议','','','','destoon',1310696460,3,0,0,'about/agreement.html','',''),(4,'1',0,0,'版权隐私','','版权隐私','','','','destoon',1310696468,2,0,0,'about/copyright.html','','');
/*!40000 ALTER TABLE `destoon_webpage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_weixin_auto`
--

DROP TABLE IF EXISTS `destoon_weixin_auto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_weixin_auto` (
  `itemid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) NOT NULL,
  `reply` text NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信回复';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_weixin_auto`
--

LOCK TABLES `destoon_weixin_auto` WRITE;
/*!40000 ALTER TABLE `destoon_weixin_auto` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_weixin_auto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_weixin_bind`
--

DROP TABLE IF EXISTS `destoon_weixin_bind`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_weixin_bind` (
  `username` varchar(30) NOT NULL DEFAULT '',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信扫码绑定';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_weixin_bind`
--

LOCK TABLES `destoon_weixin_bind` WRITE;
/*!40000 ALTER TABLE `destoon_weixin_bind` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_weixin_bind` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_weixin_chat`
--

DROP TABLE IF EXISTS `destoon_weixin_chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_weixin_chat` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `editor` varchar(30) NOT NULL,
  `openid` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL,
  `event` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  `misc` mediumtext NOT NULL,
  PRIMARY KEY (`itemid`),
  KEY `openid` (`openid`),
  KEY `addtime` (`addtime`),
  KEY `event` (`event`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信消息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_weixin_chat`
--

LOCK TABLES `destoon_weixin_chat` WRITE;
/*!40000 ALTER TABLE `destoon_weixin_chat` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_weixin_chat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destoon_weixin_user`
--

DROP TABLE IF EXISTS `destoon_weixin_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destoon_weixin_user` (
  `itemid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `openid` varchar(255) NOT NULL DEFAULT '',
  `nickname` varchar(255) NOT NULL DEFAULT '',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `language` varchar(100) NOT NULL,
  `headimgurl` varchar(255) NOT NULL,
  `edittime` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `visittime` int(10) unsigned NOT NULL DEFAULT '0',
  `credittime` int(10) unsigned NOT NULL DEFAULT '0',
  `subscribe` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `push` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`itemid`),
  UNIQUE KEY `openid` (`openid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信用户';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destoon_weixin_user`
--

LOCK TABLES `destoon_weixin_user` WRITE;
/*!40000 ALTER TABLE `destoon_weixin_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `destoon_weixin_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-26 13:58:00
