-- MySQL dump 10.13  Distrib 5.7.23, for Linux (x86_64)
--
-- Host: localhost    Database: bluecms
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
-- Table structure for table `blue_ad`
--

CREATE DATABASE `bluecms` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
use bluecms;


DROP TABLE IF EXISTS `blue_ad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_ad`
--

LOCK TABLES `blue_ad` WRITE;
/*!40000 ALTER TABLE `blue_ad` DISABLE KEYS */;
/*!40000 ALTER TABLE `blue_ad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_ad_phone`
--

DROP TABLE IF EXISTS `blue_ad_phone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_ad_phone`
--

LOCK TABLES `blue_ad_phone` WRITE;
/*!40000 ALTER TABLE `blue_ad_phone` DISABLE KEYS */;
/*!40000 ALTER TABLE `blue_ad_phone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_admin`
--

DROP TABLE IF EXISTS `blue_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_admin`
--

LOCK TABLES `blue_admin` WRITE;
/*!40000 ALTER TABLE `blue_admin` DISABLE KEYS */;
INSERT INTO `blue_admin` VALUES (1,'admin','admin@qq.com','e10adc3949ba59abbe56e057f20f883e','all',1542678367,1542688414,'');
/*!40000 ALTER TABLE `blue_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_admin_log`
--

DROP TABLE IF EXISTS `blue_admin_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blue_admin_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(20) NOT NULL,
  `add_time` int(10) NOT NULL,
  `log_value` varchar(255) NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_admin_log`
--

LOCK TABLES `blue_admin_log` WRITE;
/*!40000 ALTER TABLE `blue_admin_log` DISABLE KEYS */;
INSERT INTO `blue_admin_log` VALUES (1,'admin',1542683061,'更新站点成功'),(2,'admin',1542687429,'添加栏目成功');
/*!40000 ALTER TABLE `blue_admin_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_ann`
--

DROP TABLE IF EXISTS `blue_ann`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_ann`
--

LOCK TABLES `blue_ann` WRITE;
/*!40000 ALTER TABLE `blue_ann` DISABLE KEYS */;
/*!40000 ALTER TABLE `blue_ann` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_ann_cat`
--

DROP TABLE IF EXISTS `blue_ann_cat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blue_ann_cat` (
  `cid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(20) NOT NULL,
  `show_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_ann_cat`
--

LOCK TABLES `blue_ann_cat` WRITE;
/*!40000 ALTER TABLE `blue_ann_cat` DISABLE KEYS */;
INSERT INTO `blue_ann_cat` VALUES (1,'网站公告',0),(2,'付费推广',0),(3,'帮助中心',0),(4,'关于本站',0);
/*!40000 ALTER TABLE `blue_ann_cat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_arc_cat`
--

DROP TABLE IF EXISTS `blue_arc_cat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_arc_cat`
--

LOCK TABLES `blue_arc_cat` WRITE;
/*!40000 ALTER TABLE `blue_arc_cat` DISABLE KEYS */;
INSERT INTO `blue_arc_cat` VALUES (1,'test',0,'','','',0,0,0);
/*!40000 ALTER TABLE `blue_arc_cat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_area`
--

DROP TABLE IF EXISTS `blue_area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blue_area` (
  `area_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `area_name` varchar(20) NOT NULL,
  `parentid` int(10) NOT NULL,
  `area_indent` int(1) NOT NULL DEFAULT '0',
  `ishavechild` tinyint(1) NOT NULL DEFAULT '0',
  `show_order` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`area_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_area`
--

LOCK TABLES `blue_area` WRITE;
/*!40000 ALTER TABLE `blue_area` DISABLE KEYS */;
INSERT INTO `blue_area` VALUES (1,'地区一',0,0,0,0);
/*!40000 ALTER TABLE `blue_area` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_article`
--

DROP TABLE IF EXISTS `blue_article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_article`
--

LOCK TABLES `blue_article` WRITE;
/*!40000 ALTER TABLE `blue_article` DISABLE KEYS */;
INSERT INTO `blue_article` VALUES (1,1,1,'ls','','admin','',1542687457,'',' qq ','<p>qq</p>',4,1,0,1);
/*!40000 ALTER TABLE `blue_article` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_attachment`
--

DROP TABLE IF EXISTS `blue_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_attachment`
--

LOCK TABLES `blue_attachment` WRITE;
/*!40000 ALTER TABLE `blue_attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `blue_attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_buy_record`
--

DROP TABLE IF EXISTS `blue_buy_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blue_buy_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `aid` int(10) NOT NULL,
  `pid` smallint(5) NOT NULL,
  `exp` smallint(5) NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_buy_record`
--

LOCK TABLES `blue_buy_record` WRITE;
/*!40000 ALTER TABLE `blue_buy_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `blue_buy_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_card_order`
--

DROP TABLE IF EXISTS `blue_card_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_card_order`
--

LOCK TABLES `blue_card_order` WRITE;
/*!40000 ALTER TABLE `blue_card_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `blue_card_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_card_type`
--

DROP TABLE IF EXISTS `blue_card_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blue_card_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` int(10) NOT NULL,
  `price` int(10) NOT NULL,
  `is_close` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_card_type`
--

LOCK TABLES `blue_card_type` WRITE;
/*!40000 ALTER TABLE `blue_card_type` DISABLE KEYS */;
INSERT INTO `blue_card_type` VALUES (1,'便民卡',100,30,0);
/*!40000 ALTER TABLE `blue_card_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_category`
--

DROP TABLE IF EXISTS `blue_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_category`
--

LOCK TABLES `blue_category` WRITE;
/*!40000 ALTER TABLE `blue_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `blue_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_comment`
--

DROP TABLE IF EXISTS `blue_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_comment`
--

LOCK TABLES `blue_comment` WRITE;
/*!40000 ALTER TABLE `blue_comment` DISABLE KEYS */;
INSERT INTO `blue_comment` VALUES (1,1,0,1,6,'luffy',1542687946,'192.168.0.1',1);
/*!40000 ALTER TABLE `blue_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_config`
--

DROP TABLE IF EXISTS `blue_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blue_config` (
  `name` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_config`
--

LOCK TABLES `blue_config` WRITE;
/*!40000 ALTER TABLE `blue_config` DISABLE KEYS */;
INSERT INTO `blue_config` VALUES ('site_name','演示网站'),('site_url','http://localhost\'<?php'),('description',''),('keywords',''),('tel','1234567|1234567'),('icp',''),('count',''),('isclose','0'),('reason',''),('cookie_hash','DfEZg1482F'),('url_rewrite','0'),('qq','1234567|1234567'),('qq_group','1234567|1234567'),('right','BlueCMS ― 第一款免费开源的专业地方门户系统，专注于地方门户的CMS！'),('info_is_check','0'),('comment_is_check','0'),('news_is_check','0'),('is_gzip','0');
/*!40000 ALTER TABLE `blue_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_flash_image`
--

DROP TABLE IF EXISTS `blue_flash_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blue_flash_image` (
  `image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image_path` varchar(255) NOT NULL,
  `image_link` varchar(255) NOT NULL,
  `show_order` tinyint(3) NOT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_flash_image`
--

LOCK TABLES `blue_flash_image` WRITE;
/*!40000 ALTER TABLE `blue_flash_image` DISABLE KEYS */;
/*!40000 ALTER TABLE `blue_flash_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_guest_book`
--

DROP TABLE IF EXISTS `blue_guest_book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blue_guest_book` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `add_time` int(10) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_guest_book`
--

LOCK TABLES `blue_guest_book` WRITE;
/*!40000 ALTER TABLE `blue_guest_book` DISABLE KEYS */;
INSERT INTO `blue_guest_book` VALUES (1,0,1,1542688426,'192.168.0.1','luffy');
/*!40000 ALTER TABLE `blue_guest_book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_ipbanned`
--

DROP TABLE IF EXISTS `blue_ipbanned`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blue_ipbanned` (
  `ip` varchar(15) NOT NULL,
  `add_time` int(11) NOT NULL,
  `exp` smallint(5) NOT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_ipbanned`
--

LOCK TABLES `blue_ipbanned` WRITE;
/*!40000 ALTER TABLE `blue_ipbanned` DISABLE KEYS */;
/*!40000 ALTER TABLE `blue_ipbanned` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_link`
--

DROP TABLE IF EXISTS `blue_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blue_link` (
  `linkid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `linkname` varchar(30) NOT NULL,
  `linksite` varchar(255) NOT NULL,
  `linklogo` varchar(255) NOT NULL,
  `showorder` tinyint(3) NOT NULL,
  PRIMARY KEY (`linkid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_link`
--

LOCK TABLES `blue_link` WRITE;
/*!40000 ALTER TABLE `blue_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `blue_link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_model`
--

DROP TABLE IF EXISTS `blue_model`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blue_model` (
  `model_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `model_name` varchar(20) NOT NULL,
  `show_order` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`model_id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_model`
--

LOCK TABLES `blue_model` WRITE;
/*!40000 ALTER TABLE `blue_model` DISABLE KEYS */;
/*!40000 ALTER TABLE `blue_model` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_navigate`
--

DROP TABLE IF EXISTS `blue_navigate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blue_navigate` (
  `navid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `navname` varchar(30) NOT NULL,
  `navlink` varchar(255) NOT NULL,
  `opennew` tinyint(1) NOT NULL,
  `showorder` tinyint(3) NOT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY (`navid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_navigate`
--

LOCK TABLES `blue_navigate` WRITE;
/*!40000 ALTER TABLE `blue_navigate` DISABLE KEYS */;
/*!40000 ALTER TABLE `blue_navigate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_pay`
--

DROP TABLE IF EXISTS `blue_pay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_pay`
--

LOCK TABLES `blue_pay` WRITE;
/*!40000 ALTER TABLE `blue_pay` DISABLE KEYS */;
INSERT INTO `blue_pay` VALUES (1,'alipay','支付宝','','','','支付宝网站(www.alipay.com)是国内先进的网上支付平台，由全球最佳B2B公司阿里巴巴公司创办，致力于为网络交易用户提供优质的安全支付服务。',0.00,'images/alipay.jpg',1,0),(2,'bank','银行转账','','','','账号:\r\n户名:dd\r\n开户行:',0.00,'',1,0);
/*!40000 ALTER TABLE `blue_pay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_post`
--

DROP TABLE IF EXISTS `blue_post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_post`
--

LOCK TABLES `blue_post` WRITE;
/*!40000 ALTER TABLE `blue_post` DISABLE KEYS */;
/*!40000 ALTER TABLE `blue_post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_post_att`
--

DROP TABLE IF EXISTS `blue_post_att`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blue_post_att` (
  `post_id` int(10) unsigned NOT NULL,
  `att_id` smallint(6) unsigned NOT NULL,
  `value` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_post_att`
--

LOCK TABLES `blue_post_att` WRITE;
/*!40000 ALTER TABLE `blue_post_att` DISABLE KEYS */;
/*!40000 ALTER TABLE `blue_post_att` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_post_pic`
--

DROP TABLE IF EXISTS `blue_post_pic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blue_post_pic` (
  `pic_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL,
  `pic_path` varchar(255) NOT NULL,
  PRIMARY KEY (`pic_id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_post_pic`
--

LOCK TABLES `blue_post_pic` WRITE;
/*!40000 ALTER TABLE `blue_post_pic` DISABLE KEYS */;
/*!40000 ALTER TABLE `blue_post_pic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_service`
--

DROP TABLE IF EXISTS `blue_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blue_service` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `type` varchar(15) NOT NULL,
  `service` varchar(10) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_service`
--

LOCK TABLES `blue_service` WRITE;
/*!40000 ALTER TABLE `blue_service` DISABLE KEYS */;
INSERT INTO `blue_service` VALUES (1,'大类置顶','info','top2',10.00),(2,'小类置顶','info','top1',5.00),(3,'分类信息推荐','info','rec',10.00),(4,'分类信息头条','info','head_line',10.00),(5,'大类置顶','company','top2',10.00),(6,'小类置顶','company','top1',5.00),(7,'商家黄页推荐','company','rec',10.00),(8,'商家黄页头条','company','head_line',10.00);
/*!40000 ALTER TABLE `blue_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_task`
--

DROP TABLE IF EXISTS `blue_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blue_task` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  `last_time` int(10) NOT NULL,
  `exp` smallint(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time` (`last_time`,`exp`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_task`
--

LOCK TABLES `blue_task` WRITE;
/*!40000 ALTER TABLE `blue_task` DISABLE KEYS */;
INSERT INTO `blue_task` VALUES (1,'update_info',1542678570,1);
/*!40000 ALTER TABLE `blue_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blue_user`
--

DROP TABLE IF EXISTS `blue_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=gbk;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blue_user`
--

LOCK TABLES `blue_user` WRITE;
/*!40000 ALTER TABLE `blue_user` DISABLE KEYS */;
INSERT INTO `blue_user` VALUES (1,'admin','e10adc3949ba59abbe56e057f20f883e','admin@qq.com','0000-00-00',0,0.00,'','','','','','','',1542678367,1542688414,''),(2,'luffy','e10adc3949ba59abbe56e057f20f883e','test@qq.com','0000-00-00',0,0.00,'','','','','','','',1542687312,1542687312,'192.168.0.1');
/*!40000 ALTER TABLE `blue_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-11-20 12:34:14
