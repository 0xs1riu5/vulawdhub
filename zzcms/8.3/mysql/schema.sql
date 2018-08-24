-- MySQL dump 10.13  Distrib 5.7.23, for Linux (x86_64)
--
-- Host: localhost    Database: zzcms
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
-- Table structure for table `zzcms_about`
--
CREATE DATABASE zzcms  CHARACTER SET utf8 COLLATE utf8_general_ci;
DROP TABLE IF EXISTS `zzcms_about`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_about` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(50) DEFAULT NULL,
  `content` longtext,
  `link` char(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_about`
--

LOCK TABLES `zzcms_about` WRITE;
/*!40000 ALTER TABLE `zzcms_about` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_about` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_ad`
--

DROP TABLE IF EXISTS `zzcms_ad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `xuhao` int(11) NOT NULL DEFAULT '0',
  `title` char(50) DEFAULT NULL,
  `titlecolor` char(255) DEFAULT NULL,
  `link` char(255) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `bigclassname` char(50) DEFAULT NULL,
  `smallclassname` char(50) DEFAULT NULL,
  `username` char(50) DEFAULT NULL,
  `nextuser` char(50) DEFAULT NULL,
  `elite` tinyint(4) NOT NULL DEFAULT '0',
  `img` char(255) DEFAULT NULL,
  `imgwidth` int(11) DEFAULT NULL,
  `imgheight` int(11) DEFAULT NULL,
  `starttime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_ad`
--

LOCK TABLES `zzcms_ad` WRITE;
/*!40000 ALTER TABLE `zzcms_ad` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_ad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_adclass`
--

DROP TABLE IF EXISTS `zzcms_adclass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_adclass` (
  `classid` int(11) NOT NULL AUTO_INCREMENT,
  `classname` char(50) NOT NULL,
  `parentid` char(50) NOT NULL,
  `xuhao` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`classid`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_adclass`
--

LOCK TABLES `zzcms_adclass` WRITE;
/*!40000 ALTER TABLE `zzcms_adclass` DISABLE KEYS */;
INSERT INTO `zzcms_adclass` VALUES (1,'对联广告右侧','首页',0),(2,'对联广告左侧','首页',0),(3,'漂浮广告','首页',0),(4,'首页顶部','首页',0),(5,'品牌招商','首页',0),(6,'banner','首页',0),(7,'轮显广告','展会页',0),(8,'第二行','首页',0),(9,'轮显广告','首页',0),(10,'第一行','首页',0),(11,'B','首页',0),(12,'A','首页',0),(13,'首页','A',0);
/*!40000 ALTER TABLE `zzcms_adclass` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_admin`
--

DROP TABLE IF EXISTS `zzcms_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(11) DEFAULT NULL,
  `admin` char(50) DEFAULT NULL,
  `pass` char(50) DEFAULT NULL,
  `logins` int(11) DEFAULT '0',
  `loginip` char(50) DEFAULT NULL,
  `lastlogintime` datetime DEFAULT NULL,
  `showloginip` char(50) DEFAULT NULL,
  `showlogintime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_admin`
--

LOCK TABLES `zzcms_admin` WRITE;
/*!40000 ALTER TABLE `zzcms_admin` DISABLE KEYS */;
INSERT INTO `zzcms_admin` VALUES (1,1,'admin','e10adc3949ba59abbe56e057f20f883e',0,'','2013-04-12 08:46:54','','2013-04-11 15:49:15');
/*!40000 ALTER TABLE `zzcms_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_admingroup`
--

DROP TABLE IF EXISTS `zzcms_admingroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_admingroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupname` char(50) DEFAULT NULL,
  `config` varchar(500) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_admingroup`
--

LOCK TABLES `zzcms_admingroup` WRITE;
/*!40000 ALTER TABLE `zzcms_admingroup` DISABLE KEYS */;
INSERT INTO `zzcms_admingroup` VALUES (1,'超级管理员','zs#zsclass#zskeyword#dl#zh#zhclass#zx#zxclass#zxpinglun#zxtag#pp#job#jobclass#special#specialclass#adv#advclass#advtext#userreg#usernoreg#userclass#usergroup#guestbook#licence#badusermessage#fankui#uploadfiles#sendmessage#sendmail#sendsms#announcement#helps#bottomlink#friendlink#siteconfig#label#adminmanage#admingroup'),(2,'管理员(演示用)','zs#zskeyword#dl#zh#zx#zxpinglun#zxtag#pp#job#special#userreg#usernoreg#usergroup#guestbook#licence#badusermessage#fankui#sendmessage#sendmail#sendsms');
/*!40000 ALTER TABLE `zzcms_admingroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_answer`
--

DROP TABLE IF EXISTS `zzcms_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `about` int(11) DEFAULT '0',
  `content` longtext,
  `face` char(50) DEFAULT NULL,
  `editor` char(50) DEFAULT NULL,
  `ip` char(50) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `caina` tinyint(4) DEFAULT '0',
  `passed` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_answer`
--

LOCK TABLES `zzcms_answer` WRITE;
/*!40000 ALTER TABLE `zzcms_answer` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_ask`
--

DROP TABLE IF EXISTS `zzcms_ask`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_ask` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bigclassid` int(11) DEFAULT NULL,
  `bigclassname` char(50) DEFAULT NULL,
  `smallclassid` int(11) DEFAULT NULL,
  `smallclassname` char(50) DEFAULT NULL,
  `title` char(50) DEFAULT NULL,
  `content` longtext,
  `img` char(255) DEFAULT NULL,
  `jifen` int(11) DEFAULT '0',
  `editor` char(50) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `hit` int(11) DEFAULT '0',
  `elite` tinyint(4) DEFAULT '0',
  `typeid` int(11) DEFAULT '0',
  `passed` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `bigclassid` (`bigclassid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_ask`
--

LOCK TABLES `zzcms_ask` WRITE;
/*!40000 ALTER TABLE `zzcms_ask` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_ask` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_askclass`
--

DROP TABLE IF EXISTS `zzcms_askclass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_askclass` (
  `classid` int(11) NOT NULL AUTO_INCREMENT,
  `classname` char(50) DEFAULT NULL,
  `parentid` int(11) DEFAULT '0',
  `xuhao` int(11) DEFAULT '0',
  `isshowforuser` tinyint(4) DEFAULT '1',
  `isshowininfo` tinyint(4) DEFAULT '1',
  `title` char(255) DEFAULT NULL,
  `keyword` char(255) DEFAULT NULL,
  `discription` char(255) DEFAULT NULL,
  PRIMARY KEY (`classid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_askclass`
--

LOCK TABLES `zzcms_askclass` WRITE;
/*!40000 ALTER TABLE `zzcms_askclass` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_askclass` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_bad`
--

DROP TABLE IF EXISTS `zzcms_bad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_bad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(50) DEFAULT NULL,
  `ip` char(50) DEFAULT NULL,
  `dose` char(255) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `lockip` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_bad`
--

LOCK TABLES `zzcms_bad` WRITE;
/*!40000 ALTER TABLE `zzcms_bad` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_bad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_baojia`
--

DROP TABLE IF EXISTS `zzcms_baojia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_baojia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classid` tinyint(4) DEFAULT '0',
  `cp` char(50) DEFAULT NULL,
  `province` char(50) DEFAULT NULL,
  `city` char(50) DEFAULT NULL,
  `xiancheng` char(50) DEFAULT NULL,
  `price` char(50) DEFAULT NULL,
  `danwei` char(50) DEFAULT NULL,
  `companyname` char(50) DEFAULT NULL,
  `truename` char(50) DEFAULT NULL,
  `address` char(50) DEFAULT NULL,
  `tel` char(50) DEFAULT NULL,
  `email` char(100) DEFAULT NULL,
  `editor` char(50) DEFAULT NULL,
  `ip` char(50) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `hit` int(11) DEFAULT '0',
  `passed` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `province` (`province`,`city`,`xiancheng`),
  KEY `classid` (`classid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_baojia`
--

LOCK TABLES `zzcms_baojia` WRITE;
/*!40000 ALTER TABLE `zzcms_baojia` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_baojia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_dl`
--

DROP TABLE IF EXISTS `zzcms_dl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_dl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classid` tinyint(4) DEFAULT '0',
  `cpid` int(11) DEFAULT '0',
  `cp` char(50) DEFAULT NULL,
  `province` char(50) DEFAULT NULL,
  `city` char(50) DEFAULT NULL,
  `xiancheng` char(50) DEFAULT NULL,
  `content` char(255) DEFAULT NULL,
  `company` char(50) DEFAULT NULL,
  `companyname` char(50) DEFAULT NULL,
  `dlsname` char(50) DEFAULT NULL,
  `address` char(255) DEFAULT NULL,
  `tel` char(50) DEFAULT NULL,
  `email` char(100) DEFAULT NULL,
  `editor` char(50) DEFAULT NULL,
  `saver` char(50) DEFAULT NULL,
  `savergroupid` int(11) DEFAULT '0',
  `ip` char(50) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `hit` int(11) DEFAULT '0',
  `looked` tinyint(4) DEFAULT '0',
  `passed` tinyint(4) DEFAULT '0',
  `del` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `province` (`province`,`city`,`xiancheng`),
  KEY `classid` (`classid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_dl`
--

LOCK TABLES `zzcms_dl` WRITE;
/*!40000 ALTER TABLE `zzcms_dl` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_dl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_guestbook`
--

DROP TABLE IF EXISTS `zzcms_guestbook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_guestbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(50) DEFAULT NULL,
  `content` longtext,
  `sendtime` datetime DEFAULT NULL,
  `linkmen` char(50) DEFAULT NULL,
  `phone` char(50) DEFAULT NULL,
  `email` char(100) DEFAULT NULL,
  `saver` char(50) DEFAULT NULL,
  `looked` tinyint(4) DEFAULT '0',
  `passed` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_guestbook`
--

LOCK TABLES `zzcms_guestbook` WRITE;
/*!40000 ALTER TABLE `zzcms_guestbook` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_guestbook` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_help`
--

DROP TABLE IF EXISTS `zzcms_help`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classid` int(11) DEFAULT NULL,
  `title` char(50) DEFAULT NULL,
  `content` longtext,
  `img` char(255) DEFAULT NULL,
  `elite` tinyint(4) DEFAULT '0',
  `sendtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_help`
--

LOCK TABLES `zzcms_help` WRITE;
/*!40000 ALTER TABLE `zzcms_help` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_help` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_job`
--

DROP TABLE IF EXISTS `zzcms_job`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_job` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bigclassid` int(11) DEFAULT '0',
  `bigclassname` char(50) DEFAULT NULL,
  `smallclassid` int(11) DEFAULT '0',
  `smallclassname` char(50) DEFAULT NULL,
  `jobname` char(50) DEFAULT NULL,
  `province` char(50) DEFAULT NULL,
  `city` char(50) DEFAULT NULL,
  `xiancheng` char(50) DEFAULT NULL,
  `sm` varchar(1000) DEFAULT NULL,
  `editor` char(50) DEFAULT NULL,
  `comane` char(50) DEFAULT NULL,
  `userid` int(11) DEFAULT '0',
  `sendtime` datetime DEFAULT NULL,
  `hit` int(11) DEFAULT '0',
  `passed` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_job`
--

LOCK TABLES `zzcms_job` WRITE;
/*!40000 ALTER TABLE `zzcms_job` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_job` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_jobclass`
--

DROP TABLE IF EXISTS `zzcms_jobclass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_jobclass` (
  `classid` int(11) NOT NULL AUTO_INCREMENT,
  `classname` char(255) DEFAULT NULL,
  `parentid` int(11) DEFAULT '0',
  `xuhao` int(11) DEFAULT '0',
  PRIMARY KEY (`classid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_jobclass`
--

LOCK TABLES `zzcms_jobclass` WRITE;
/*!40000 ALTER TABLE `zzcms_jobclass` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_jobclass` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_licence`
--

DROP TABLE IF EXISTS `zzcms_licence`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_licence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(50) DEFAULT NULL,
  `img` char(255) DEFAULT NULL,
  `editor` char(50) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `passed` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_licence`
--

LOCK TABLES `zzcms_licence` WRITE;
/*!40000 ALTER TABLE `zzcms_licence` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_licence` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_link`
--

DROP TABLE IF EXISTS `zzcms_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bigclassid` int(11) DEFAULT '0',
  `sitename` char(50) DEFAULT NULL,
  `url` char(255) DEFAULT NULL,
  `content` char(255) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `logo` char(255) DEFAULT NULL,
  `elite` tinyint(4) DEFAULT '0',
  `passed` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_link`
--

LOCK TABLES `zzcms_link` WRITE;
/*!40000 ALTER TABLE `zzcms_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_linkclass`
--

DROP TABLE IF EXISTS `zzcms_linkclass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_linkclass` (
  `classid` int(11) NOT NULL AUTO_INCREMENT,
  `classname` char(50) DEFAULT NULL,
  `xuhao` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`classid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_linkclass`
--

LOCK TABLES `zzcms_linkclass` WRITE;
/*!40000 ALTER TABLE `zzcms_linkclass` DISABLE KEYS */;
INSERT INTO `zzcms_linkclass` VALUES (1,'合作网站',0),(2,'友链网站',0);
/*!40000 ALTER TABLE `zzcms_linkclass` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_login_times`
--

DROP TABLE IF EXISTS `zzcms_login_times`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_login_times` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` char(50) DEFAULT NULL,
  `count` int(11) DEFAULT '0',
  `sendtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_login_times`
--

LOCK TABLES `zzcms_login_times` WRITE;
/*!40000 ALTER TABLE `zzcms_login_times` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_login_times` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_looked_dls`
--

DROP TABLE IF EXISTS `zzcms_looked_dls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_looked_dls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dlsid` int(11) DEFAULT NULL,
  `username` char(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_looked_dls`
--

LOCK TABLES `zzcms_looked_dls` WRITE;
/*!40000 ALTER TABLE `zzcms_looked_dls` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_looked_dls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_looked_dls_number_oneday`
--

DROP TABLE IF EXISTS `zzcms_looked_dls_number_oneday`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_looked_dls_number_oneday` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `looked_dls_number_oneday` int(11) DEFAULT NULL,
  `username` char(50) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_looked_dls_number_oneday`
--

LOCK TABLES `zzcms_looked_dls_number_oneday` WRITE;
/*!40000 ALTER TABLE `zzcms_looked_dls_number_oneday` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_looked_dls_number_oneday` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_main`
--

DROP TABLE IF EXISTS `zzcms_main`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_main` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `proname` char(50) DEFAULT NULL,
  `szm` char(10) DEFAULT NULL,
  `prouse` char(255) DEFAULT NULL,
  `procompany` char(50) DEFAULT NULL,
  `sm` text,
  `xuhao` int(4) DEFAULT NULL,
  `bigclassid` tinyint(4) DEFAULT '0',
  `smallclassid` tinyint(4) DEFAULT '0',
  `smallclassids` char(50) DEFAULT NULL,
  `shuxing` int(4) DEFAULT NULL,
  `img` char(255) DEFAULT NULL,
  `flv` char(255) DEFAULT NULL,
  `province` char(50) DEFAULT NULL,
  `city` char(50) DEFAULT NULL,
  `xiancheng` char(50) DEFAULT NULL,
  `zc` char(255) DEFAULT NULL,
  `yq` char(255) DEFAULT NULL,
  `other` char(255) DEFAULT NULL,
  `shuxing_value` char(255) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `timefororder` char(50) DEFAULT NULL,
  `editor` char(50) DEFAULT NULL,
  `elitestarttime` datetime DEFAULT NULL,
  `eliteendtime` datetime DEFAULT NULL,
  `title` char(255) DEFAULT NULL,
  `keywords` char(255) DEFAULT NULL,
  `description` char(255) DEFAULT NULL,
  `refresh` int(11) DEFAULT '0',
  `hit` int(11) DEFAULT '0',
  `elite` tinyint(4) DEFAULT '0',
  `passed` tinyint(4) DEFAULT '0',
  `userid` int(11) DEFAULT '0',
  `comane` char(255) DEFAULT NULL,
  `qq` char(50) DEFAULT NULL,
  `groupid` int(11) DEFAULT '0',
  `renzheng` tinyint(4) DEFAULT '0',
  `ppid` int(11) DEFAULT '0',
  `gjzpm` tinyint(4) DEFAULT '0',
  `tag` char(255) DEFAULT NULL,
  `skin` char(25) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `province` (`province`,`city`,`xiancheng`),
  KEY `bigclassid` (`bigclassid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_main`
--

LOCK TABLES `zzcms_main` WRITE;
/*!40000 ALTER TABLE `zzcms_main` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_main` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_message`
--

DROP TABLE IF EXISTS `zzcms_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(50) DEFAULT NULL,
  `content` char(255) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `sendto` char(50) NOT NULL,
  `looked` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_message`
--

LOCK TABLES `zzcms_message` WRITE;
/*!40000 ALTER TABLE `zzcms_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_msg`
--

DROP TABLE IF EXISTS `zzcms_msg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(1000) NOT NULL,
  `elite` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_msg`
--

LOCK TABLES `zzcms_msg` WRITE;
/*!40000 ALTER TABLE `zzcms_msg` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_msg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_pay`
--

DROP TABLE IF EXISTS `zzcms_pay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_pay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(50) DEFAULT NULL,
  `dowhat` char(50) DEFAULT NULL,
  `RMB` char(50) DEFAULT '0',
  `mark` char(255) DEFAULT NULL,
  `sendtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_pay`
--

LOCK TABLES `zzcms_pay` WRITE;
/*!40000 ALTER TABLE `zzcms_pay` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_pay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_pinglun`
--

DROP TABLE IF EXISTS `zzcms_pinglun`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_pinglun` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `about` int(11) DEFAULT '0',
  `content` char(255) DEFAULT NULL,
  `face` char(50) DEFAULT NULL,
  `username` char(50) DEFAULT NULL,
  `ip` char(50) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `passed` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_pinglun`
--

LOCK TABLES `zzcms_pinglun` WRITE;
/*!40000 ALTER TABLE `zzcms_pinglun` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_pinglun` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_pp`
--

DROP TABLE IF EXISTS `zzcms_pp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_pp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ppname` char(255) DEFAULT NULL,
  `bigclassid` tinyint(4) DEFAULT '0',
  `smallclassid` tinyint(4) DEFAULT '0',
  `sm` longtext,
  `img` char(255) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `editor` char(50) DEFAULT NULL,
  `comane` char(50) DEFAULT NULL,
  `userid` int(11) DEFAULT '0',
  `hit` int(11) DEFAULT '0',
  `passed` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `bigclassid` (`bigclassid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_pp`
--

LOCK TABLES `zzcms_pp` WRITE;
/*!40000 ALTER TABLE `zzcms_pp` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_pp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_special`
--

DROP TABLE IF EXISTS `zzcms_special`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_special` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bigclassid` int(11) DEFAULT NULL,
  `bigclassname` char(50) DEFAULT NULL,
  `smallclassid` int(11) DEFAULT NULL,
  `smallclassname` char(50) DEFAULT NULL,
  `title` char(50) DEFAULT NULL,
  `link` char(255) DEFAULT NULL,
  `laiyuan` char(50) DEFAULT NULL,
  `keywords` char(255) DEFAULT NULL,
  `description` char(255) DEFAULT NULL,
  `content` longtext,
  `img` char(255) DEFAULT NULL,
  `editor` char(50) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `hit` int(11) DEFAULT '0',
  `passed` tinyint(4) DEFAULT '0',
  `elite` tinyint(4) DEFAULT '0',
  `groupid` int(11) DEFAULT '1',
  `jifen` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `bigclassid` (`bigclassid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_special`
--

LOCK TABLES `zzcms_special` WRITE;
/*!40000 ALTER TABLE `zzcms_special` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_special` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_specialclass`
--

DROP TABLE IF EXISTS `zzcms_specialclass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_specialclass` (
  `classid` int(11) NOT NULL AUTO_INCREMENT,
  `classname` char(50) DEFAULT NULL,
  `parentid` int(11) DEFAULT '0',
  `xuhao` int(11) DEFAULT '0',
  `isshowforuser` tinyint(4) DEFAULT '1',
  `isshowininfo` tinyint(4) DEFAULT '1',
  `title` char(255) DEFAULT NULL,
  `keyword` char(255) DEFAULT NULL,
  `discription` char(255) DEFAULT NULL,
  PRIMARY KEY (`classid`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_specialclass`
--

LOCK TABLES `zzcms_specialclass` WRITE;
/*!40000 ALTER TABLE `zzcms_specialclass` DISABLE KEYS */;
INSERT INTO `zzcms_specialclass` VALUES (1,'2015广西药交会',0,0,1,1,'','',''),(2,'访谈',1,0,1,1,'','',''),(3,'名企直击',1,0,1,1,'','',''),(4,'展会现场',1,0,1,1,'','',''),(5,'展会简介',1,0,1,1,'','',''),(6,'大背景图',1,0,1,1,'','','');
/*!40000 ALTER TABLE `zzcms_specialclass` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_tagzs`
--

DROP TABLE IF EXISTS `zzcms_tagzs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_tagzs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` char(50) DEFAULT NULL,
  `url` char(50) DEFAULT NULL,
  `xuhao` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_tagzs`
--

LOCK TABLES `zzcms_tagzs` WRITE;
/*!40000 ALTER TABLE `zzcms_tagzs` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_tagzs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_tagzx`
--

DROP TABLE IF EXISTS `zzcms_tagzx`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_tagzx` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `xuhao` int(11) DEFAULT '0',
  `keyword` char(50) DEFAULT NULL,
  `url` char(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_tagzx`
--

LOCK TABLES `zzcms_tagzx` WRITE;
/*!40000 ALTER TABLE `zzcms_tagzx` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_tagzx` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_textadv`
--

DROP TABLE IF EXISTS `zzcms_textadv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_textadv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adv` char(50) DEFAULT NULL,
  `company` char(50) NOT NULL,
  `advlink` char(50) DEFAULT NULL,
  `img` char(255) DEFAULT NULL,
  `username` char(50) DEFAULT NULL,
  `gxsj` datetime DEFAULT NULL,
  `newsid` int(11) NOT NULL DEFAULT '0',
  `passed` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `adv` (`adv`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_textadv`
--

LOCK TABLES `zzcms_textadv` WRITE;
/*!40000 ALTER TABLE `zzcms_textadv` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_textadv` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_user`
--

DROP TABLE IF EXISTS `zzcms_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(50) NOT NULL,
  `password` char(50) NOT NULL,
  `passwordtrue` char(50) DEFAULT NULL,
  `qqid` char(50) DEFAULT NULL,
  `email` char(100) DEFAULT NULL,
  `sex` char(50) DEFAULT NULL,
  `comane` char(50) DEFAULT NULL,
  `content` longtext,
  `bigclassid` int(11) DEFAULT '0',
  `smallclassid` int(11) DEFAULT '0',
  `province` char(50) DEFAULT NULL,
  `city` char(50) DEFAULT NULL,
  `xiancheng` char(50) DEFAULT NULL,
  `img` char(255) DEFAULT NULL,
  `flv` char(255) DEFAULT NULL,
  `address` char(100) DEFAULT NULL,
  `somane` char(50) DEFAULT NULL,
  `phone` char(50) DEFAULT NULL,
  `mobile` char(50) DEFAULT NULL,
  `fox` char(50) DEFAULT NULL,
  `qq` char(50) DEFAULT NULL,
  `regdate` datetime DEFAULT NULL,
  `loginip` char(50) DEFAULT NULL,
  `logins` int(11) NOT NULL DEFAULT '0',
  `homepage` char(50) DEFAULT NULL,
  `lastlogintime` datetime DEFAULT NULL,
  `lockuser` tinyint(4) NOT NULL DEFAULT '0',
  `groupid` int(11) NOT NULL DEFAULT '1',
  `totleRMB` int(11) NOT NULL DEFAULT '0',
  `startdate` datetime DEFAULT NULL,
  `enddate` datetime DEFAULT NULL,
  `showloginip` char(50) DEFAULT NULL,
  `showlogintime` datetime DEFAULT NULL,
  `elite` tinyint(4) NOT NULL DEFAULT '0',
  `renzheng` tinyint(4) NOT NULL DEFAULT '0',
  `usersf` char(20) DEFAULT NULL,
  `passed` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_user`
--

LOCK TABLES `zzcms_user` WRITE;
/*!40000 ALTER TABLE `zzcms_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_userclass`
--

DROP TABLE IF EXISTS `zzcms_userclass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_userclass` (
  `classid` int(11) NOT NULL AUTO_INCREMENT,
  `parentid` int(11) DEFAULT '0',
  `classname` char(50) NOT NULL,
  `xuhao` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`classid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_userclass`
--

LOCK TABLES `zzcms_userclass` WRITE;
/*!40000 ALTER TABLE `zzcms_userclass` DISABLE KEYS */;
INSERT INTO `zzcms_userclass` VALUES (1,0,'生产单位',0),(2,0,'经销单位',0),(4,0,'展会承办单位',0),(5,0,'其它相关行业',0);
/*!40000 ALTER TABLE `zzcms_userclass` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_userdomain`
--

DROP TABLE IF EXISTS `zzcms_userdomain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_userdomain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(50) DEFAULT NULL,
  `domain` char(50) DEFAULT NULL,
  `passed` tinyint(4) DEFAULT '0',
  `del` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_userdomain`
--

LOCK TABLES `zzcms_userdomain` WRITE;
/*!40000 ALTER TABLE `zzcms_userdomain` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_userdomain` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_usergroup`
--

DROP TABLE IF EXISTS `zzcms_usergroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_usergroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(11) NOT NULL DEFAULT '1',
  `groupname` char(50) NOT NULL,
  `grouppic` char(50) NOT NULL,
  `RMB` int(11) NOT NULL DEFAULT '0',
  `config` varchar(1000) NOT NULL DEFAULT '0',
  `looked_dls_number_oneday` int(11) NOT NULL DEFAULT '0',
  `refresh_number` int(11) NOT NULL DEFAULT '0',
  `addinfo_number` int(11) NOT NULL DEFAULT '0',
  `addinfototle_number` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_usergroup`
--

LOCK TABLES `zzcms_usergroup` WRITE;
/*!40000 ALTER TABLE `zzcms_usergroup` DISABLE KEYS */;
INSERT INTO `zzcms_usergroup` VALUES (1,1,'普通会员','/image/level1.gif',0,'showad_inzt',10,1,50,100),(2,2,'vip会员','/image/level2.gif',1999,'look_dls_data#look_dls_liuyan',100,3,100,500),(3,3,'高级会员','/image/level3.gif',2999,'look_dls_data#look_dls_liuyan',999,999,999,999);
/*!40000 ALTER TABLE `zzcms_usergroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_usermessage`
--

DROP TABLE IF EXISTS `zzcms_usermessage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_usermessage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` char(50) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `editor` char(50) DEFAULT NULL,
  `reply` varchar(255) DEFAULT NULL,
  `replytime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_usermessage`
--

LOCK TABLES `zzcms_usermessage` WRITE;
/*!40000 ALTER TABLE `zzcms_usermessage` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_usermessage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_usernoreg`
--

DROP TABLE IF EXISTS `zzcms_usernoreg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_usernoreg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usersf` char(50) DEFAULT NULL,
  `username` char(50) NOT NULL,
  `password` char(50) DEFAULT NULL,
  `comane` char(50) DEFAULT NULL,
  `kind` int(11) NOT NULL DEFAULT '0',
  `somane` char(50) DEFAULT NULL,
  `phone` char(50) DEFAULT NULL,
  `email` char(100) DEFAULT NULL,
  `checkcode` char(50) DEFAULT NULL,
  `regdate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_usernoreg`
--

LOCK TABLES `zzcms_usernoreg` WRITE;
/*!40000 ALTER TABLE `zzcms_usernoreg` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_usernoreg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_usersetting`
--

DROP TABLE IF EXISTS `zzcms_usersetting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_usersetting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(50) DEFAULT NULL,
  `skin` char(50) DEFAULT '1',
  `skin_mobile` char(50) DEFAULT '1',
  `tongji` char(255) DEFAULT NULL,
  `baidu_map` char(50) DEFAULT NULL,
  `mobile` char(50) DEFAULT NULL,
  `daohang` char(50) DEFAULT NULL,
  `bannerbg` char(50) DEFAULT NULL,
  `bannerheight` int(11) NOT NULL DEFAULT '160',
  `swf` char(50) DEFAULT NULL,
  `comanestyle` char(50) DEFAULT NULL,
  `comanecolor` char(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_usersetting`
--

LOCK TABLES `zzcms_usersetting` WRITE;
/*!40000 ALTER TABLE `zzcms_usersetting` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_usersetting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_wangkan`
--

DROP TABLE IF EXISTS `zzcms_wangkan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_wangkan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bigclassid` int(11) DEFAULT NULL,
  `title` char(50) DEFAULT NULL,
  `content` longtext,
  `img` char(255) DEFAULT NULL,
  `editor` char(50) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `hit` int(11) DEFAULT '0',
  `passed` tinyint(4) DEFAULT '0',
  `elite` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_wangkan`
--

LOCK TABLES `zzcms_wangkan` WRITE;
/*!40000 ALTER TABLE `zzcms_wangkan` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_wangkan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_wangkanclass`
--

DROP TABLE IF EXISTS `zzcms_wangkanclass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_wangkanclass` (
  `classid` int(11) NOT NULL AUTO_INCREMENT,
  `classname` char(50) DEFAULT NULL,
  `xuhao` int(11) DEFAULT '0',
  PRIMARY KEY (`classid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_wangkanclass`
--

LOCK TABLES `zzcms_wangkanclass` WRITE;
/*!40000 ALTER TABLE `zzcms_wangkanclass` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_wangkanclass` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_zh`
--

DROP TABLE IF EXISTS `zzcms_zh`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_zh` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bigclassid` int(11) DEFAULT NULL,
  `title` char(50) DEFAULT NULL,
  `address` char(100) DEFAULT NULL,
  `timestart` datetime DEFAULT NULL,
  `timeend` datetime DEFAULT NULL,
  `content` longtext,
  `editor` char(50) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `hit` int(11) DEFAULT '0',
  `passed` tinyint(4) DEFAULT '0',
  `elite` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_zh`
--

LOCK TABLES `zzcms_zh` WRITE;
/*!40000 ALTER TABLE `zzcms_zh` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_zh` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_zhclass`
--

DROP TABLE IF EXISTS `zzcms_zhclass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_zhclass` (
  `classid` int(11) NOT NULL AUTO_INCREMENT,
  `classname` char(50) DEFAULT NULL,
  `xuhao` int(11) DEFAULT '0',
  PRIMARY KEY (`classid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_zhclass`
--

LOCK TABLES `zzcms_zhclass` WRITE;
/*!40000 ALTER TABLE `zzcms_zhclass` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_zhclass` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_zsclass`
--

DROP TABLE IF EXISTS `zzcms_zsclass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_zsclass` (
  `classid` int(11) NOT NULL AUTO_INCREMENT,
  `parentid` tinyint(4) NOT NULL DEFAULT '0',
  `classname` char(50) NOT NULL,
  `classzm` char(50) DEFAULT NULL,
  `img` char(50) NOT NULL DEFAULT '0',
  `xuhao` int(11) NOT NULL DEFAULT '0',
  `title` char(255) DEFAULT NULL,
  `keyword` char(255) DEFAULT NULL,
  `discription` char(255) DEFAULT NULL,
  `isshow` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`classid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_zsclass`
--

LOCK TABLES `zzcms_zsclass` WRITE;
/*!40000 ALTER TABLE `zzcms_zsclass` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_zsclass` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_zsclass_shuxing`
--

DROP TABLE IF EXISTS `zzcms_zsclass_shuxing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_zsclass_shuxing` (
  `bigclassid` int(11) NOT NULL AUTO_INCREMENT,
  `bigclassname` char(50) DEFAULT NULL,
  `xuhao` int(11) DEFAULT '0',
  PRIMARY KEY (`bigclassid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_zsclass_shuxing`
--

LOCK TABLES `zzcms_zsclass_shuxing` WRITE;
/*!40000 ALTER TABLE `zzcms_zsclass_shuxing` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_zsclass_shuxing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_ztad`
--

DROP TABLE IF EXISTS `zzcms_ztad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_ztad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classname` char(50) DEFAULT NULL,
  `title` char(50) DEFAULT NULL,
  `link` char(255) DEFAULT NULL,
  `img` char(255) DEFAULT NULL,
  `editor` char(50) DEFAULT NULL,
  `passed` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_ztad`
--

LOCK TABLES `zzcms_ztad` WRITE;
/*!40000 ALTER TABLE `zzcms_ztad` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_ztad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_zx`
--

DROP TABLE IF EXISTS `zzcms_zx`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_zx` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bigclassid` int(11) DEFAULT NULL,
  `bigclassname` char(50) DEFAULT NULL,
  `smallclassid` int(11) DEFAULT NULL,
  `smallclassname` char(50) DEFAULT NULL,
  `title` char(50) DEFAULT NULL,
  `link` char(255) DEFAULT NULL,
  `laiyuan` char(50) DEFAULT NULL,
  `keywords` char(255) DEFAULT NULL,
  `description` char(255) DEFAULT NULL,
  `content` longtext,
  `img` char(255) DEFAULT NULL,
  `editor` char(50) DEFAULT NULL,
  `sendtime` datetime DEFAULT NULL,
  `hit` int(11) DEFAULT '0',
  `passed` tinyint(4) DEFAULT '0',
  `elite` tinyint(4) DEFAULT '0',
  `groupid` int(11) DEFAULT '1',
  `jifen` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `bigclassid` (`bigclassid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_zx`
--

LOCK TABLES `zzcms_zx` WRITE;
/*!40000 ALTER TABLE `zzcms_zx` DISABLE KEYS */;
/*!40000 ALTER TABLE `zzcms_zx` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zzcms_zxclass`
--

DROP TABLE IF EXISTS `zzcms_zxclass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zzcms_zxclass` (
  `classid` int(11) NOT NULL AUTO_INCREMENT,
  `classname` char(50) DEFAULT NULL,
  `parentid` int(11) DEFAULT '0',
  `xuhao` int(11) DEFAULT '0',
  `isshowforuser` tinyint(4) DEFAULT '1',
  `isshowininfo` tinyint(4) DEFAULT '1',
  `title` char(255) DEFAULT NULL,
  `keyword` char(255) DEFAULT NULL,
  `discription` char(255) DEFAULT NULL,
  `skin` char(50) DEFAULT NULL,
  PRIMARY KEY (`classid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zzcms_zxclass`
--

LOCK TABLES `zzcms_zxclass` WRITE;
/*!40000 ALTER TABLE `zzcms_zxclass` DISABLE KEYS */;
INSERT INTO `zzcms_zxclass` VALUES (1,'公司新闻',0,0,1,1,'','','',''),(2,'大类二',0,0,1,1,'','','',''),(3,'大类三',0,0,1,1,'','','',''),(4,'大类四',0,0,1,1,'','','','');
/*!40000 ALTER TABLE `zzcms_zxclass` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-08-24  2:18:05
