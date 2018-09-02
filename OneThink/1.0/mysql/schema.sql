-- MySQL dump 10.13  Distrib 5.7.23, for Linux (x86_64)
--
-- Host: localhost    Database: onethink
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
-- Table structure for table `onethink_action`
--

CREATE DATABASE IF NOT EXISTS onethink DEFAULT CHARSET utf8 COLLATE utf8_general_ci;
use onethink;

DROP TABLE IF EXISTS `onethink_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '行为唯一标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '行为说明',
  `remark` char(140) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text NOT NULL COMMENT '行为规则',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统行为表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_action`
--

LOCK TABLES `onethink_action` WRITE;
/*!40000 ALTER TABLE `onethink_action` DISABLE KEYS */;
INSERT INTO `onethink_action` VALUES (1,'user_login','用户登录','积分+10，每天一次','table:member|field:score|condition:uid={$self} AND status>-1|rule:score+10|cycle:24|max:1;',2,1,1383295068),(2,'add_article','发布文章','积分+5，每天上限5次','table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:5',2,1,1380173180),(3,'review','评论','评论积分+1，无限制','table:member|field:score|condition:uid={$self}|rule:score+1',2,1,1383285646),(4,'add_document_blog','发表博客','积分+10，每天上限5次','table:member|field:score|condition:uid={$self}|rule:score+10|cycle:24|max:5',2,1,1383285637),(5,'add_document_topic','发表讨论','积分+5，每天上限10次','table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:10',2,1,1383285551),(6,'update_config','更新配置','新增或修改或删除配置','',1,1,1383294988),(7,'update_model','更新模型','新增或修改模型','',1,1,1383295057),(8,'update_attribute','更新属性','新增或更新或删除属性','',1,1,1383295963),(9,'update_channel','更新导航','新增或修改或删除导航','',1,1,1383296301),(10,'update_menu','更新菜单','新增或修改或删除菜单','',1,1,1383296392),(11,'update_category','更新分类','新增或修改或删除分类','',1,1,1383296765);
/*!40000 ALTER TABLE `onethink_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_action_log`
--

DROP TABLE IF EXISTS `onethink_action_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `action_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '行为id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '触发行为的数据id',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '日志备注',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间',
  PRIMARY KEY (`id`),
  KEY `action_id_ix` (`action_id`),
  KEY `user_id_ix` (`user_id`),
  KEY `action_ip_ix` (`action_ip`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_action_log`
--

LOCK TABLES `onethink_action_log` WRITE;
/*!40000 ALTER TABLE `onethink_action_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `onethink_action_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_addons`
--

DROP TABLE IF EXISTS `onethink_addons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_addons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `config` text COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `has_adminlist` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台列表',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='插件表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_addons`
--

LOCK TABLES `onethink_addons` WRITE;
/*!40000 ALTER TABLE `onethink_addons` DISABLE KEYS */;
INSERT INTO `onethink_addons` VALUES (15,'EditorForAdmin','后台编辑器','用于增强整站长文本的输入和显示',1,'{\"editor_type\":\"4\",\"editor_wysiwyg\":\"1\",\"editor_height\":\"500px\",\"editor_resize_type\":\"1\"}','thinkphp','0.1',1383126253,0),(2,'SiteStat','站点统计信息','统计站点的基础信息',1,'{\"title\":\"\\u7cfb\\u7edf\\u4fe1\\u606f\",\"width\":\"1\",\"display\":\"1\",\"status\":\"0\"}','thinkphp','0.1',1379512015,0),(3,'DevTeam','开发团队信息','开发团队成员信息',1,'{\"title\":\"OneThink\\u5f00\\u53d1\\u56e2\\u961f\",\"width\":\"2\",\"display\":\"1\"}','thinkphp','0.1',1379512022,0),(4,'SystemInfo','系统环境信息','用于显示一些服务器的信息',1,'{\"title\":\"\\u7cfb\\u7edf\\u4fe1\\u606f\",\"width\":\"2\",\"display\":\"1\"}','thinkphp','0.1',1379512036,0),(5,'Editor','前台编辑器','用于增强整站长文本的输入和显示',1,'{\"editor_type\":\"2\",\"editor_wysiwyg\":\"1\",\"editor_height\":\"300px\",\"editor_resize_type\":\"1\"}','thinkphp','0.1',1379830910,0),(6,'Attachment','附件','用于文档模型上传附件',1,'null','thinkphp','0.1',1379842319,1),(9,'SocialComment','通用社交化评论','集成了各种社交化评论插件，轻松集成到系统中。',1,'{\"comment_type\":\"1\",\"comment_uid_youyan\":\"\",\"comment_short_name_duoshuo\":\"\",\"comment_data_list_duoshuo\":\"\"}','thinkphp','0.1',1380273962,0);
/*!40000 ALTER TABLE `onethink_addons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_attachment`
--

DROP TABLE IF EXISTS `onethink_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '附件显示名',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '附件类型',
  `source` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '资源ID',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联记录ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '附件大小',
  `dir` int(12) unsigned NOT NULL DEFAULT '0' COMMENT '上级目录ID',
  `sort` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `idx_record_status` (`record_id`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表\r\n@author   麦当苗儿\r\n@version  2013-06-19';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_attachment`
--

LOCK TABLES `onethink_attachment` WRITE;
/*!40000 ALTER TABLE `onethink_attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `onethink_attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_attribute`
--

DROP TABLE IF EXISTS `onethink_attribute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_attribute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '字段名',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '字段注释',
  `field` varchar(100) NOT NULL DEFAULT '' COMMENT '字段定义',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '数据类型',
  `value` varchar(100) NOT NULL DEFAULT '' COMMENT '字段默认值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  `model_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '模型id',
  `is_must` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必填',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=187 DEFAULT CHARSET=utf8 COMMENT='模型属性表\r\n@author huajie';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_attribute`
--

LOCK TABLES `onethink_attribute` WRITE;
/*!40000 ALTER TABLE `onethink_attribute` DISABLE KEYS */;
INSERT INTO `onethink_attribute` VALUES (1,'uid','用户ID','int(10) unsigned NOT NULL ','num','0','',0,'',1,0,1,1384508362,1383891233),(2,'name','标识','char(40) NOT NULL ','string','\'\'','同一根节点下标识不重复',1,'',1,0,1,1383894743,1383891233),(3,'title','标题','char(80) NOT NULL ','string','\'\'','文档标题',1,'',1,0,1,1383894778,1383891233),(4,'category_id','所属分类','int(10) unsigned NOT NULL ','string','','',0,'',1,0,1,1384508336,1383891233),(5,'description','描述','char(140) NOT NULL ','textarea','\'\'','',1,'',1,0,1,1383894927,1383891233),(6,'root','根节点','int(10) unsigned NOT NULL ','num','0','该文档的顶级文档编号',0,'',1,0,1,1384508323,1383891233),(7,'pid','所属ID','int(10) unsigned NOT NULL ','num','0','父文档编号',0,'',1,0,1,1384508543,1383891233),(8,'model_id','内容模型ID','tinyint(3) unsigned NOT NULL ','num','0','该文档所对应的模型',0,'',1,0,1,1384508350,1383891233),(9,'type','内容类型','tinyint(3) unsigned NOT NULL ','select','2','',1,'1:目录\r\n2:主题\r\n3:段落',1,0,1,1384511157,1383891233),(10,'position','推荐位','smallint(5) unsigned NOT NULL ','checkbox','0','多个推荐则将其推荐值相加',1,'1:列表推荐\r\n2:频道页推荐\r\n4:首页推荐',1,0,1,1383895640,1383891233),(11,'link_id','外链','int(10) unsigned NOT NULL ','num','0','0-非外链，大于0-外链ID,需要函数进行链接与编号的转换',1,'',1,0,1,1383895757,1383891233),(12,'cover_id','封面','int(10) unsigned NOT NULL ','picture','0','0-无封面，大于0-封面图片ID，需要函数处理',1,'',1,0,1,1384147827,1383891233),(13,'display','可见性','tinyint(3) unsigned NOT NULL ','radio','0','',1,'0:不可见\r\n1:所有人可见',1,0,1,1383896023,1383891233),(14,'dateline','截至时间','int(10) unsigned NOT NULL ','datetime','0','0-永久有效',1,'',1,0,1,1383895812,1383891233),(15,'attach','附件数量','tinyint(3) unsigned NOT NULL ','num','0','',1,'',1,0,1,1383895825,1383891233),(16,'view','浏览量','int(10) unsigned NOT NULL ','num','0','',1,'',1,0,1,1383895835,1383891233),(17,'comment','评论数','int(10) unsigned NOT NULL ','num','0','',1,'',1,0,1,1383895846,1383891233),(18,'extend','扩展统计字段','int(10) unsigned NOT NULL ','num','0','根据需求自行使用',0,'',1,0,1,1384508264,1383891233),(19,'level','优先级','int(10) unsigned NOT NULL ','num','0','越高排序越靠前',1,'',1,0,1,1383895894,1383891233),(20,'create_time','创建时间','int(10) unsigned NOT NULL ','datetime','0','',1,'',1,0,1,1383895903,1383891233),(21,'update_time','更新时间','int(10) unsigned NOT NULL ','datetime','0','',0,'',1,0,1,1384508277,1383891233),(22,'status','数据状态','tinyint(4) NOT NULL ','radio','0','',0,'-1:删除\r\n0:禁用\r\n1:正常\r\n2:待审核\r\n3:草稿',1,0,1,1384508496,1383891233),(23,'parse','内容解析类型','tinyint(3) unsigned NOT NULL ','select','0','',0,'0:html\r\n1:ubb\r\n2:markdown',2,0,1,1384511049,1383891243),(24,'content','文章内容','text NOT NULL ','editor','','',1,'',2,0,1,1383896225,1383891243),(25,'template','详情页显示模板','varchar(100) NOT NULL ','string','\'\'','参照display方法参数的定义',1,'',2,0,1,1383896190,1383891243),(26,'bookmark','收藏数','int(10) unsigned NOT NULL ','num','0','',1,'',2,0,1,1383896103,1383891243),(27,'parse','内容解析类型','tinyint(3) unsigned NOT NULL ','select','0','',1,'0:html\r\n1:ubb\r\n2:markdown',3,0,1,1383896487,1383891252),(28,'content','下载详细描述','text NOT NULL ','editor','','',1,'',3,0,1,1383896438,1383891252),(29,'template','详情页显示模板','varchar(100) NOT NULL ','string','\'\'','',1,'',3,0,1,1383896429,1383891252),(30,'file_id','文件ID','int(10) unsigned NOT NULL ','file','0','需要函数处理',1,'',3,0,1,1383896415,1383891252),(31,'download','下载次数','int(10) unsigned NOT NULL ','num','0','',1,'',3,0,1,1383896380,1383891252),(32,'size','文件大小','bigint(20) unsigned NOT NULL ','num','0','单位bit',1,'',3,0,1,1383896371,1383891252),(33,'name','行为唯一标识','char(30) NOT NULL ','string','\'\'','标识唯一',1,'',4,0,1,1383897155,1383891262),(34,'title','行为说明','char(80) NOT NULL ','textarea','\'\'','',1,'',4,0,1,1383897133,1383891262),(35,'remark','行为描述','char(140) NOT NULL ','textarea','\'\'','',1,'',4,0,1,1383897032,1383891262),(36,'rule','行为规则','text NOT NULL ','textarea','','',1,'',4,0,1,1383897011,1383891262),(37,'type','类型','tinyint(2) unsigned NOT NULL ','select','1','',1,'1:系统\r\n2:用户',4,0,1,1383896955,1383891262),(38,'status','状态','tinyint(2) NOT NULL ','radio','0','',1,'-1:已删除\r\n0:禁用\r\n1:正常',4,0,1,1383896868,1383891262),(39,'update_time','修改时间','int(11) unsigned NOT NULL ','datetime','0','',1,'',4,0,1,1383896756,1383891262),(40,'action_id','行为id','int(10) unsigned NOT NULL ','num','0','',1,'',5,0,1,1383897599,1383891340),(41,'user_id','执行用户id','int(10) unsigned NOT NULL ','num','0','',1,'',5,0,1,1383897587,1383891340),(42,'action_ip','执行行为者ip','bigint(20) NOT NULL ','string','','',1,'',5,0,1,1383897570,1383891341),(43,'model','触发行为的表','varchar(50) NOT NULL ','string','\'\'','',1,'',5,0,1,1383897523,1383891341),(44,'record_id','触发行为的数据id','int(10) unsigned NOT NULL ','num','0','',1,'',5,0,1,1383897512,1383891341),(45,'remark','日志备注','varchar(255) NOT NULL ','textarea','\'\'','',1,'',5,0,1,1383897352,1383891341),(46,'status','状态','tinyint(2) NOT NULL ','checkbox','1','',1,'-1:已删除\r\n0:禁用\r\n1:正常',5,0,1,1383897331,1383891341),(47,'create_time','执行行为的时间','int(10) unsigned NOT NULL ','datetime','0','',1,'',5,0,1,1383897274,1383891341),(48,'name','插件名或标识','varchar(40) NOT NULL ','string','','区分大小写',1,'',6,0,1,1383898041,1383891396),(49,'title','中文名','varchar(20) NOT NULL ','string','\'\'','',1,'',6,0,1,1383898024,1383891396),(50,'description','插件描述','text NULL ','editor','','',1,'',6,0,1,1383897787,1383891396),(51,'status','状态','tinyint(1) NOT NULL ','radio','1','',1,'1:启用\r\n0:禁用\r\n-1:损坏',6,0,1,1383897771,1383891396),(52,'config','配置','text NULL ','textarea','','序列化存放',1,'',6,0,1,1383897747,1383891396),(53,'author','作者','varchar(40) NULL ','string','\'\'','',1,'',6,0,1,1383897731,1383891396),(54,'version','版本号','varchar(20) NULL ','string','\'\'','',1,'',6,0,1,1383897715,1383891396),(55,'create_time','安装时间','int(10) unsigned NOT NULL ','datetime','0','',1,'',6,0,1,1383897704,1383891396),(56,'has_adminlist','是否有后台列表','tinyint(1) unsigned NOT NULL ','bool','0','',1,'1:有后台列表\r\n0:无后台列表',6,0,1,1383897682,1383891396),(57,'uid','用户ID','int(10) unsigned NOT NULL ','num','0','',1,'',7,0,1,1383900457,1383891410),(58,'title','附件显示名','char(30) NOT NULL ','string','\'\'','',1,'',7,0,1,1383900447,1383891410),(59,'type','附件类型','tinyint(3) unsigned NOT NULL ','select','0','',1,'0:目录\r\n1:外链\r\n2:文件',7,0,1,1383900433,1383891410),(60,'source','资源ID','int(10) unsigned NOT NULL ','num','0','0-目录， 大于0-当资源为文件时其值为file_id,当资源为外链时其值为link_id',1,'',7,0,1,1383900405,1383891410),(61,'record_id','关联记录ID','int(10) unsigned NOT NULL ','num','0','',1,'',7,0,1,1383900384,1383891410),(62,'download','下载次数','int(10) unsigned NOT NULL ','num','0','',1,'',7,0,1,1383900376,1383891410),(63,'size','附件大小','bigint(20) unsigned NOT NULL ','num','0','当附件为目录或外链时，该值为0',1,'',7,0,1,1383900367,1383891410),(64,'dir','上级目录ID','int(12) unsigned NOT NULL ','num','0','0-根目录',1,'',7,0,1,1383900283,1383891410),(65,'sort','排序','int(8) unsigned NOT NULL ','num','0','',1,'',7,0,1,1383900264,1383891410),(66,'create_time','创建时间','int(10) unsigned NOT NULL ','datetime','0','',1,'',7,0,1,1383900256,1383891410),(67,'update_time','更新时间','int(11) unsigned NOT NULL ','datetime','0','',1,'',7,0,1,1383900249,1383891410),(68,'status','状态','tinyint(1) NOT NULL ','select','0','',1,'-1:已删除\r\n0:禁用\r\n1:正常',7,0,1,1383900239,1383891410),(69,'name','字段名','varchar(30) NOT NULL ','string','\'\'','',1,'',8,0,1,1383901456,1383891441),(70,'title','字段注释','varchar(100) NOT NULL ','textarea','\'\'','',1,'',8,0,1,1383901439,1383891441),(71,'field','字段定义','varchar(100) NOT NULL ','string','\'\'','',1,'',8,0,1,1383901414,1383891441),(72,'type','数据类型','varchar(20) NOT NULL ','string','\'\'','用于表单展示',1,'',8,0,1,1383901399,1383891441),(73,'value','字段默认值','varchar(100) NOT NULL ','string','\'\'','',1,'',8,0,1,1383901261,1383891441),(74,'remark','备注','varchar(100) NOT NULL ','textarea','\'\'','',1,'',8,0,1,1383901247,1383891441),(75,'is_show','是否显示','tinyint(1) unsigned NOT NULL ','select','1','',1,'0:不显示\r\n1:始终显示\r\n2:新增时显示\r\n3:编辑时显示',8,0,1,1383901027,1383891441),(76,'extra','参数','varchar(255) NOT NULL ','textarea','\'\'','表单显示',1,'',8,0,1,1383900964,1383891441),(77,'model_id','模型id','int(10) unsigned NOT NULL ','num','0','',1,'',8,0,1,1383900862,1383891441),(78,'is_must','是否必填','tinyint(1) unsigned NOT NULL ','bool','0','',1,'0:否\r\n1:是',8,0,1,1383900852,1383891441),(79,'status','状态','tinyint(2) NOT NULL ','select','0','',1,'-1:已删除\r\n0:禁用\r\n1:正常',8,0,1,1383900822,1383891441),(80,'update_time','更新时间','int(11) unsigned NOT NULL ','datetime','0','',1,'',8,0,1,1383900771,1383891441),(81,'create_time','创建时间','int(11) unsigned NOT NULL ','datetime','0','',1,'',8,0,1,1383900759,1383891441),(82,'name','标志','varchar(30) NOT NULL ','string','','',1,'',9,0,1,1383901549,1383891453),(83,'title','标题','varchar(50) NOT NULL ','string','','',1,'',9,0,1,1383891453,1383891453),(84,'pid','上级分类ID','int(10) unsigned NOT NULL ','num','0','',1,'',9,0,1,1383901524,1383891453),(85,'sort','排序（同级有效）','int(10) unsigned NOT NULL ','num','0','',1,'',9,0,1,1383902180,1383891453),(86,'list_row','列表每页行数','tinyint(3) unsigned NOT NULL ','num','10','',1,'',9,0,1,1383902154,1383891453),(87,'meta_title','SEO的网页标题','varchar(50) NOT NULL ','string','\'\'','',1,'',9,0,1,1383902118,1383891453),(88,'keywords','关键字','varchar(255) NOT NULL ','textarea','\'\'','',1,'',9,0,1,1383902106,1383891453),(89,'description','描述','varchar(255) NOT NULL ','textarea','\'\'','',1,'',9,0,1,1383902089,1383891453),(90,'template_index','频道页模板','varchar(100) NOT NULL ','string','','',1,'',9,0,1,1383891453,1383891453),(91,'template_lists','列表页模板','varchar(100) NOT NULL ','string','','',1,'',9,0,1,1383891453,1383891453),(92,'template_detail','详情页模板','varchar(100) NOT NULL ','string','','',1,'',9,0,1,1383891453,1383891453),(93,'template_edit','编辑页模板','varchar(100) NOT NULL ','string','','',1,'',9,0,1,1383891453,1383891453),(94,'model','关联模型','varchar(100) NOT NULL ','string','\'\'','',1,'',9,0,1,1383902070,1383891453),(95,'type','允许发布的内容类型','varchar(100) NOT NULL ','radio','\'\'','',1,'1:目录\r\n2:主题\r\n3:段落',9,0,1,1383902026,1383891453),(96,'link_id','外链','int(10) unsigned NOT NULL ','num','0','0-非外链，大于0-外链ID',1,'',9,0,1,1383902007,1383891453),(97,'allow_publish','是否允许发布内容','tinyint(3) unsigned NOT NULL ','radio','0','（0-不允许，1-允许）',1,'0:不允许\r\n1:仅允许后台\r\n2:允许前后台',9,0,1,1385343315,1383891453),(98,'display','可见性','tinyint(3) unsigned NOT NULL ','radio','0','',1,'0:所有人可见\r\n1:管理员可见\r\n2:不可见',9,0,1,1383900863,1383891453),(99,'reply','是否允许回复','tinyint(3) unsigned NOT NULL ','radio','0','',1,'1:允许\r\n0:不允许',9,0,1,1383900823,1383891453),(100,'check','发布的文章是否需要审核','tinyint(3) unsigned NOT NULL ','radio','0','0：不需要，1：需要',1,'0:不需要\r\n1:需要',9,0,1,1383901976,1383891453),(101,'reply_model','','varchar(100) NOT NULL ','string','\'\'','',1,'',9,0,1,1383901959,1383891453),(102,'extend','扩展设置','text NOT NULL ','textarea','','JSON数据',1,'',9,0,1,1383901931,1383891453),(103,'create_time','创建时间','int(10) unsigned NOT NULL ','datetime','0','',1,'',9,0,1,1383900444,1383891453),(104,'update_time','更新时间','int(10) unsigned NOT NULL ','datetime','0','',1,'',9,0,1,1383900436,1383891453),(105,'status','数据状态','tinyint(4) NOT NULL ','radio','0','-1-删除，0-禁用，1-正常，2-待审核',1,'-1:删除\r\n0:禁用\r\n1:正常\r\n2:待审核',9,0,1,1383900393,1383891453),(106,'pid','上级频道ID','int(10) unsigned NOT NULL ','string','0','',1,'',10,0,1,1383891460,1383891460),(107,'title','频道标题','char(30) NOT NULL ','string','','',1,'',10,0,1,1383891460,1383891460),(108,'url','频道连接','char(100) NOT NULL ','string','','',1,'',10,0,1,1383891460,1383891460),(109,'sort','导航排序','int(10) unsigned NOT NULL ','num','0','',1,'',10,0,1,1383900256,1383891460),(110,'create_time','创建时间','int(10) unsigned NOT NULL ','datetime','0','',1,'',10,0,1,1383900247,1383891460),(111,'update_time','更新时间','int(10) unsigned NOT NULL ','datetime','0','',1,'',10,0,1,1383900239,1383891460),(112,'status','状态','tinyint(4) NOT NULL ','radio','0','',1,'1:正常\r\n0:禁用',10,0,1,1383900228,1383891460),(113,'name','配置名称','varchar(30) NOT NULL ','string','\'\'','',1,'',11,0,1,1383901902,1383891466),(114,'type','配置类型','tinyint(3) unsigned NOT NULL ','select','0','',1,'0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举',11,0,1,1383901877,1383891466),(115,'title','配置说明','varchar(50) NOT NULL ','string','\'\'','',1,'',11,0,1,1383901743,1383891466),(116,'group','配置分组','tinyint(3) unsigned NOT NULL ','radio','0','0-无分组，1-基本设置',1,'0:无分组\r\n1:基本设置',11,0,1,1383901731,1383891466),(117,'extra','配置值','varchar(255) NOT NULL ','textarea','\'\'','',1,'',11,0,1,1383901707,1383891466),(118,'remark','配置说明','varchar(100) NOT NULL ','textarea','','',1,'',11,0,1,1383901666,1383891466),(119,'create_time','创建时间','int(10) unsigned NOT NULL ','datetime','0','',1,'',11,0,1,1383899711,1383891466),(120,'update_time','更新时间','int(10) unsigned NOT NULL ','datetime','0','',1,'',11,0,1,1383899704,1383891466),(121,'status','状态','tinyint(4) NOT NULL ','radio','0','',1,'1:启用\r\n0:禁用',11,0,1,1383901650,1383891466),(122,'value','配置值','text NOT NULL ','textarea','','',1,'',11,0,1,1383901634,1383891466),(123,'sort','排序','smallint(3) unsigned NOT NULL ','num','0','',1,'',11,0,1,1383901610,1383891467),(124,'name','原始文件名','char(30) NOT NULL ','string','\'\'','',1,'',12,0,1,1383902322,1383891474),(125,'savename','保存名称','char(20) NOT NULL ','string','\'\'','',1,'',12,0,1,1383902308,1383891474),(126,'savepath','文件保存路径','char(30) NOT NULL ','string','\'\'','',1,'',12,0,1,1383902297,1383891474),(127,'ext','文件后缀','char(5) NOT NULL ','string','\'\'','',1,'',12,0,1,1383902285,1383891474),(128,'mime','文件mime类型','char(40) NOT NULL ','string','\'\'','',1,'',12,0,1,1383902267,1383891474),(129,'size','文件大小','int(10) unsigned NOT NULL ','num','0','',1,'',12,0,1,1383902259,1383891474),(130,'md5','文件md5','char(32) NOT NULL ','string','\'\'','',1,'',12,0,1,1383902245,1383891474),(131,'sha1','文件 sha1编码','char(40) NOT NULL ','string','\'\'','',1,'',12,0,1,1383902236,1383891474),(132,'location','文件保存位置','tinyint(3) unsigned NOT NULL ','checkbox','0','',1,'0:本地\r\n1:FTP',12,0,1,1383899469,1383891474),(133,'create_time','上传时间','int(10) unsigned NOT NULL ','datetime','','',1,'',12,0,1,1383899436,1383891474),(134,'name','钩子名称','varchar(40) NOT NULL ','string','\'\'','',1,'',13,0,1,1383902516,1383891480),(135,'description','描述','text NOT NULL ','editor','','',1,'',13,0,1,1383902505,1383891480),(136,'type','类型','tinyint(1) unsigned NOT NULL ','select','1','',1,'1:Controller\r\n2:Widget',13,0,1,1383902484,1383891480),(137,'update_time','更新时间','int(10) unsigned NOT NULL ','datetime','0','',1,'',13,0,1,1383902552,1383891480),(138,'addons','钩子挂载的插件 \'，\'分割','varchar(255) NULL ','string','','',1,'',13,0,1,1383891480,1383891480),(139,'uid','用户ID','int(10) unsigned NOT NULL ','num','0','',1,'',14,0,1,1383898187,1383891488),(140,'nickname','昵称','char(16) NOT NULL ','string','\'\'','',1,'',14,0,1,1383902646,1383891488),(141,'sex','性别','tinyint(3) unsigned NOT NULL ','radio','0','',1,'0:女\r\n1:男',14,0,1,1383898158,1383891488),(142,'birthday','生日','date NOT NULL ','string','0000-00-00','',1,'',14,0,1,1383891488,1383891488),(143,'qq','qq号','char(10) NOT NULL ','string','\'\'','',1,'',14,0,1,1383902605,1383891488),(144,'score','用户积分','mediumint(8) NOT NULL ','num','0','',1,'',14,0,1,1383898066,1383891488),(145,'login','登录次数','int(10) unsigned NOT NULL ','num','0','',1,'',14,0,1,1383898055,1383891488),(146,'reg_ip','注册IP','bigint(20) NOT NULL ','string','0','',1,'',14,0,1,1383898045,1383891488),(147,'reg_time','注册时间','int(10) unsigned NOT NULL ','datetime','0','',1,'',14,0,1,1383897783,1383891488),(148,'last_login_ip','最后登录IP','bigint(20) NOT NULL ','string','0','',1,'',14,0,1,1383891488,1383891488),(149,'last_login_time','最后登录时间','int(10) unsigned NOT NULL ','datetime','0','',1,'',14,0,1,1383897775,1383891488),(150,'status','会员状态','tinyint(4) NOT NULL ','radio','0','',1,'1:正常\r\n0:禁用',14,0,1,1383898472,1383891488),(151,'title','标题','varchar(50) NOT NULL ','string','\'\'','',1,'',15,0,1,1383902756,1383891503),(152,'pid','上级分类ID','int(10) unsigned NOT NULL ','num','0','',1,'',15,0,1,1383897737,1383891503),(153,'sort','排序（同级有效）','int(10) unsigned NOT NULL ','num','0','',1,'',15,0,1,1383902746,1383891503),(154,'url','链接地址','char(255) NOT NULL ','string','\'\'','',1,'',15,0,1,1383902737,1383891503),(155,'hide','是否隐藏','tinyint(1) unsigned NOT NULL ','radio','0','',1,'1:是\r\n0:否',15,0,1,1383902727,1383891503),(156,'tip','提示','varchar(255) NOT NULL ','textarea','\'\'','',1,'',15,0,1,1383902703,1383891503),(157,'group','分组','varchar(50) NULL ','string','\'\'','',1,'',15,0,1,1383902688,1383891503),(158,'is_dev','是否仅开发者模式可见','tinyint(1) unsigned NOT NULL ','radio','0','',1,'1:开发者模式可见\r\n0:开发者模式不可见',15,0,1,1383902676,1383891503),(159,'name','模型标识','char(30) NOT NULL ','string','\'\'','',1,'',16,0,1,1383903045,1383891514),(160,'title','模型名称','char(30) NOT NULL ','string','\'\'','',1,'',16,0,1,1383903037,1383891514),(161,'extend','继承的模型','int(10) unsigned NOT NULL ','num','0','',1,'',16,0,1,1383903026,1383891514),(162,'relation','继承与被继承模型的关联字段','varchar(30) NOT NULL ','string','','',1,'',16,0,1,1383891514,1383891514),(163,'need_pk','新建表时是否需要主键字段','tinyint(1) unsigned NOT NULL ','radio','1','',1,'1:是\r\n0:否',16,0,1,1383903004,1383891514),(164,'field_sort','表单字段排序','text NOT NULL ','textarea','','',1,'',16,0,1,1383902978,1383891514),(165,'field_group','字段分组','varchar(255) NOT NULL ','string','1:基础','',1,'',16,0,1,1383891514,1383891514),(166,'attribute_list','属性列表（表的字段）','text NOT NULL ','textarea','','',1,'',16,0,1,1383902908,1383891514),(167,'template_list','列表模板','varchar(100) NOT NULL ','string','\'\'','',1,'',16,0,1,1383902888,1383891514),(168,'template_add','新增模板','varchar(100) NOT NULL ','string','\'\'','',1,'',16,0,1,1383902878,1383891514),(169,'template_edit','编辑模板','varchar(100) NOT NULL ','string','\'\'','',1,'',16,0,1,1383902869,1383891514),(170,'list_grid','列表定义','varchar(255) NOT NULL ','textarea','\'\'','',1,'',16,0,1,1383902861,1383891514),(171,'list_row','列表数据长度','smallint(2) unsigned NOT NULL ','num','10','',1,'',16,0,1,1383897602,1383891514),(172,'search_key','默认搜索字段','varchar(50) NOT NULL ','string','\'\'','',1,'',16,0,1,1383902840,1383891514),(173,'search_list','高级搜索的字段','varchar(255) NOT NULL ','string','\'\'','',1,'',16,0,1,1383902828,1383891514),(174,'create_time','创建时间','int(10) unsigned NOT NULL ','datetime','0','',1,'',16,0,1,1383897557,1383891514),(175,'update_time','更新时间','int(10) unsigned NOT NULL ','datetime','0','',1,'',16,0,1,1383897549,1383891514),(176,'status','状态','tinyint(3) unsigned NOT NULL ','radio','0','',1,'-1:已删除\r\n0:禁用\r\n1:正常',16,0,1,1383902811,1383891514),(177,'path','路径','varchar(255) NOT NULL ','string','\'\'','',1,'',17,0,1,1383903110,1383891522),(178,'url','图片链接','varchar(255) NOT NULL ','string','\'\'','',1,'',17,0,1,1383903103,1383891522),(179,'md5','文件md5','char(32) NOT NULL ','string','\'\'','',1,'',17,0,1,1383903093,1383891522),(180,'sha1','文件 sha1编码','char(40) NOT NULL ','string','\'\'','',1,'',17,0,1,1383903084,1383891522),(181,'status','状态','tinyint(2) NOT NULL ','radio','0','',1,'-1:已删除\r\n0:禁用\r\n1:正常',17,0,1,1383903075,1383891522),(182,'create_time','创建时间','int(10) unsigned NOT NULL ','datetime','0','',1,'',17,0,1,1383897491,1383891522),(183,'url','链接地址','char(255) NOT NULL ','string','\'\'','',1,'',18,0,1,1383903175,1383891532),(184,'short','短网址','char(100) NOT NULL ','string','\'\'','',1,'',18,0,1,1383903162,1383891532),(185,'status','状态','tinyint(2) NOT NULL ','radio','2','',1,'-1:已删除\r\n0:禁用\r\n1:正常\r\n2:未审核',18,0,1,1383903153,1383891532),(186,'create_time','创建时间','int(10) unsigned NOT NULL ','datetime','0','',1,'',18,0,1,1383903130,1383891532);
/*!40000 ALTER TABLE `onethink_attribute` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_auth_extend`
--

DROP TABLE IF EXISTS `onethink_auth_extend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_auth_extend` (
  `group_id` mediumint(10) unsigned NOT NULL COMMENT '用户id',
  `extend_id` mediumint(8) unsigned NOT NULL COMMENT '扩展表中数据的id',
  `type` tinyint(1) unsigned NOT NULL COMMENT '扩展类型标识 1:栏目分类权限;2:模型权限',
  UNIQUE KEY `group_extend_type` (`group_id`,`extend_id`,`type`),
  KEY `uid` (`group_id`),
  KEY `group_id` (`extend_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组与分类的对应关系表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_auth_extend`
--

LOCK TABLES `onethink_auth_extend` WRITE;
/*!40000 ALTER TABLE `onethink_auth_extend` DISABLE KEYS */;
INSERT INTO `onethink_auth_extend` VALUES (1,1,1),(1,1,2),(1,2,1),(1,2,2),(1,3,1),(1,3,2),(1,4,1),(1,37,1);
/*!40000 ALTER TABLE `onethink_auth_extend` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_auth_group`
--

DROP TABLE IF EXISTS `onethink_auth_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_auth_group`
--

LOCK TABLES `onethink_auth_group` WRITE;
/*!40000 ALTER TABLE `onethink_auth_group` DISABLE KEYS */;
INSERT INTO `onethink_auth_group` VALUES (1,'admin',1,'默认用户组','',1,'1,2,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,79,80,81,82,83,84,86,87,88,89,90,91,92,93,94,95,96,97,100,102,103,105,106');
/*!40000 ALTER TABLE `onethink_auth_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_auth_group_access`
--

DROP TABLE IF EXISTS `onethink_auth_group_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_auth_group_access`
--

LOCK TABLES `onethink_auth_group_access` WRITE;
/*!40000 ALTER TABLE `onethink_auth_group_access` DISABLE KEYS */;
INSERT INTO `onethink_auth_group_access` VALUES (19,1),(20,1),(24,2),(25,2);
/*!40000 ALTER TABLE `onethink_auth_group_access` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_auth_rule`
--

DROP TABLE IF EXISTS `onethink_auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`module`,`name`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=211 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_auth_rule`
--

LOCK TABLES `onethink_auth_rule` WRITE;
/*!40000 ALTER TABLE `onethink_auth_rule` DISABLE KEYS */;
INSERT INTO `onethink_auth_rule` VALUES (1,'admin',2,'Admin/Index/index','首页',1,''),(2,'admin',2,'Admin/Article/mydocument','内容',1,''),(3,'admin',2,'Admin/User/index','用户',1,''),(4,'admin',2,'Admin/Addons/index','扩展',1,''),(5,'admin',2,'Admin/Config/group','系统',1,''),(6,'admin',1,'Admin/Index/index','首页',-1,''),(7,'admin',1,'Admin/article/add','新增',1,''),(8,'admin',1,'Admin/article/edit','编辑',1,''),(9,'admin',1,'Admin/article/setStatus','改变状态',1,''),(10,'admin',1,'Admin/article/update','保存',1,''),(11,'admin',1,'Admin/article/autoSave','保存草稿',1,''),(12,'admin',1,'Admin/article/move','移动',1,''),(13,'admin',1,'Admin/article/copy','复制',1,''),(14,'admin',1,'Admin/article/paste','粘贴',1,''),(15,'admin',1,'Admin/article/permit','还原',1,''),(16,'admin',1,'Admin/article/clear','清空',1,''),(17,'admin',1,'Admin/article/index','文档列表',1,''),(18,'admin',1,'Admin/article/recycle','回收站',1,''),(19,'admin',1,'Admin/User/addAction','新增用户行为',1,''),(20,'admin',1,'Admin/User/editAction','编辑用户行为',1,''),(21,'admin',1,'Admin/User/saveAction','保存用户行为',1,''),(22,'admin',1,'Admin/User/setStatus','变更行为状态',1,''),(23,'admin',1,'Admin/User/changeStatus?method=forbidUser','禁用会员',1,''),(24,'admin',1,'Admin/User/changeStatus?method=resumeUser','启用会员',1,''),(25,'admin',1,'Admin/User/changeStatus?method=deleteUser','删除会员',1,''),(26,'admin',1,'Admin/User/index','用户信息',1,''),(27,'admin',1,'Admin/User/action','用户行为',1,''),(28,'admin',1,'Admin/AuthManager/changeStatus?method=deleteGroup','删除',1,''),(29,'admin',1,'Admin/AuthManager/changeStatus?method=forbidGroup','禁用',1,''),(30,'admin',1,'Admin/AuthManager/changeStatus?method=resumeGroup','恢复',1,''),(31,'admin',1,'Admin/AuthManager/createGroup','新增',1,''),(32,'admin',1,'Admin/AuthManager/editGroup','编辑',1,''),(33,'admin',1,'Admin/AuthManager/writeGroup','保存用户组',1,''),(34,'admin',1,'Admin/AuthManager/group','授权',1,''),(35,'admin',1,'Admin/AuthManager/access','访问授权',1,''),(36,'admin',1,'Admin/AuthManager/user','成员授权',1,''),(37,'admin',1,'Admin/AuthManager/removeFromGroup','解除授权',1,''),(38,'admin',1,'Admin/AuthManager/addToGroup','保存成员授权',1,''),(39,'admin',1,'Admin/AuthManager/category','分类授权',1,''),(40,'admin',1,'Admin/AuthManager/addToCategory','保存分类授权',1,''),(41,'admin',1,'Admin/AuthManager/index','权限管理',1,''),(42,'admin',1,'Admin/Addons/create','创建',1,''),(43,'admin',1,'Admin/Addons/checkForm','检测创建',1,''),(44,'admin',1,'Admin/Addons/preview','预览',1,''),(45,'admin',1,'Admin/Addons/build','快速生成插件',1,''),(46,'admin',1,'Admin/Addons/config','设置',1,''),(47,'admin',1,'Admin/Addons/disable','禁用',1,''),(48,'admin',1,'Admin/Addons/enable','启用',1,''),(49,'admin',1,'Admin/Addons/install','安装',1,''),(50,'admin',1,'Admin/Addons/uninstall','卸载',1,''),(51,'admin',1,'Admin/Addons/saveconfig','更新配置',1,''),(52,'admin',1,'Admin/Addons/adminList','插件后台列表',1,''),(53,'admin',1,'Admin/Addons/execute','URL方式访问插件',1,''),(54,'admin',1,'Admin/Addons/index','插件管理',1,''),(55,'admin',1,'Admin/Addons/hooks','钩子管理',1,''),(56,'admin',1,'Admin/model/add','新增',1,''),(57,'admin',1,'Admin/model/edit','编辑',1,''),(58,'admin',1,'Admin/model/setStatus','改变状态',1,''),(59,'admin',1,'Admin/model/update','保存数据',1,''),(60,'admin',1,'Admin/Model/index','模型管理',1,''),(61,'admin',1,'Admin/Config/edit','编辑',1,''),(62,'admin',1,'Admin/Config/del','删除',1,''),(63,'admin',1,'Admin/Config/add','新增',1,''),(64,'admin',1,'Admin/Config/save','保存',1,''),(65,'admin',1,'Admin/Config/group','网站设置',1,''),(66,'admin',1,'Admin/Config/index','配置管理',1,''),(67,'admin',1,'Admin/Channel/add','新增',1,''),(68,'admin',1,'Admin/Channel/edit','编辑',1,''),(69,'admin',1,'Admin/Channel/del','删除',1,''),(70,'admin',1,'Admin/Channel/index','导航管理',1,''),(71,'admin',1,'Admin/Category/edit','编辑',1,''),(72,'admin',1,'Admin/Category/add','新增',1,''),(73,'admin',1,'Admin/Category/remove','删除',1,''),(74,'admin',1,'Admin/Category/index','分类管理',1,''),(75,'admin',1,'Admin/file/upload','上传控件',-1,''),(76,'admin',1,'Admin/file/uploadPicture','上传图片',-1,''),(77,'admin',1,'Admin/file/download','下载',-1,''),(94,'admin',1,'Admin/AuthManager/modelauth','模型授权',1,''),(79,'admin',1,'Admin/article/batchOperate','导入',1,''),(80,'admin',1,'Admin/Database/index?type=export','备份数据库',1,''),(81,'admin',1,'Admin/Database/index?type=import','还原数据库',1,''),(82,'admin',1,'Admin/Database/export','备份',1,''),(83,'admin',1,'Admin/Database/optimize','优化表',1,''),(84,'admin',1,'Admin/Database/repair','修复表',1,''),(86,'admin',1,'Admin/Database/import','恢复',1,''),(87,'admin',1,'Admin/Database/del','删除',1,''),(88,'admin',1,'Admin/User/add','新增用户',1,''),(89,'admin',1,'Admin/Attribute/index','属性管理',1,''),(90,'admin',1,'Admin/Attribute/add','新增',1,''),(91,'admin',1,'Admin/Attribute/edit','编辑',1,''),(92,'admin',1,'Admin/Attribute/setStatus','改变状态',1,''),(93,'admin',1,'Admin/Attribute/update','保存数据',1,''),(95,'admin',1,'Admin/AuthManager/addToModel','保存模型授权',1,''),(96,'admin',1,'Admin/Category/move','移动',1,''),(97,'admin',1,'Admin/Category/merge','合并',1,''),(98,'admin',1,'Admin/Config/menu','后台菜单管理',-1,''),(99,'admin',1,'Admin/Article/mydocument','内容',-1,''),(100,'admin',1,'Admin/Menu/index','菜单管理',1,''),(101,'admin',1,'Admin/other','其他',-1,''),(102,'admin',1,'Admin/Menu/add','新增',1,''),(103,'admin',1,'Admin/Menu/edit','编辑',1,''),(104,'admin',1,'Admin/Think/lists?model=article','文章管理',-1,''),(105,'admin',1,'Admin/Think/lists?model=download','下载管理',1,''),(106,'admin',1,'Admin/Think/lists?model=config','配置管理',1,''),(107,'admin',1,'Admin/Action/actionlog','行为日志',1,''),(108,'admin',1,'Admin/User/updatePassword','修改密码',1,''),(109,'admin',1,'Admin/User/updateNickname','修改昵称',1,''),(110,'admin',1,'Admin/action/edit','查看行为日志',1,''),(205,'admin',1,'Admin/think/add','新增数据',1,''),(111,'admin',2,'Admin/article/index','文档列表',-1,''),(112,'admin',2,'Admin/article/add','新增',-1,''),(113,'admin',2,'Admin/article/edit','编辑',-1,''),(114,'admin',2,'Admin/article/setStatus','改变状态',-1,''),(115,'admin',2,'Admin/article/update','保存',-1,''),(116,'admin',2,'Admin/article/autoSave','保存草稿',-1,''),(117,'admin',2,'Admin/article/move','移动',-1,''),(118,'admin',2,'Admin/article/copy','复制',-1,''),(119,'admin',2,'Admin/article/paste','粘贴',-1,''),(120,'admin',2,'Admin/article/batchOperate','导入',-1,''),(121,'admin',2,'Admin/article/recycle','回收站',-1,''),(122,'admin',2,'Admin/article/permit','还原',-1,''),(123,'admin',2,'Admin/article/clear','清空',-1,''),(124,'admin',2,'Admin/User/add','新增用户',-1,''),(125,'admin',2,'Admin/User/action','用户行为',-1,''),(126,'admin',2,'Admin/User/addAction','新增用户行为',-1,''),(127,'admin',2,'Admin/User/editAction','编辑用户行为',-1,''),(128,'admin',2,'Admin/User/saveAction','保存用户行为',-1,''),(129,'admin',2,'Admin/User/setStatus','变更行为状态',-1,''),(130,'admin',2,'Admin/User/changeStatus?method=forbidUser','禁用会员',-1,''),(131,'admin',2,'Admin/User/changeStatus?method=resumeUser','启用会员',-1,''),(132,'admin',2,'Admin/User/changeStatus?method=deleteUser','删除会员',-1,''),(133,'admin',2,'Admin/AuthManager/index','权限管理',-1,''),(134,'admin',2,'Admin/AuthManager/changeStatus?method=deleteGroup','删除',-1,''),(135,'admin',2,'Admin/AuthManager/changeStatus?method=forbidGroup','禁用',-1,''),(136,'admin',2,'Admin/AuthManager/changeStatus?method=resumeGroup','恢复',-1,''),(137,'admin',2,'Admin/AuthManager/createGroup','新增',-1,''),(138,'admin',2,'Admin/AuthManager/editGroup','编辑',-1,''),(139,'admin',2,'Admin/AuthManager/writeGroup','保存用户组',-1,''),(140,'admin',2,'Admin/AuthManager/group','授权',-1,''),(141,'admin',2,'Admin/AuthManager/access','访问授权',-1,''),(142,'admin',2,'Admin/AuthManager/user','成员授权',-1,''),(143,'admin',2,'Admin/AuthManager/removeFromGroup','解除授权',-1,''),(144,'admin',2,'Admin/AuthManager/addToGroup','保存成员授权',-1,''),(145,'admin',2,'Admin/AuthManager/category','分类授权',-1,''),(146,'admin',2,'Admin/AuthManager/addToCategory','保存分类授权',-1,''),(147,'admin',2,'Admin/AuthManager/modelauth','模型授权',-1,''),(148,'admin',2,'Admin/AuthManager/addToModel','保存模型授权',-1,''),(149,'admin',2,'Admin/Addons/create','创建',-1,''),(150,'admin',2,'Admin/Addons/checkForm','检测创建',-1,''),(151,'admin',2,'Admin/Addons/preview','预览',-1,''),(152,'admin',2,'Admin/Addons/build','快速生成插件',-1,''),(153,'admin',2,'Admin/Addons/config','设置',-1,''),(154,'admin',2,'Admin/Addons/disable','禁用',-1,''),(155,'admin',2,'Admin/Addons/enable','启用',-1,''),(156,'admin',2,'Admin/Addons/install','安装',-1,''),(157,'admin',2,'Admin/Addons/uninstall','卸载',-1,''),(158,'admin',2,'Admin/Addons/saveconfig','更新配置',-1,''),(159,'admin',2,'Admin/Addons/adminList','插件后台列表',-1,''),(160,'admin',2,'Admin/Addons/execute','URL方式访问插件',-1,''),(161,'admin',2,'Admin/Addons/hooks','钩子管理',-1,''),(162,'admin',2,'Admin/Model/index','模型管理',-1,''),(163,'admin',2,'Admin/model/add','新增',-1,''),(164,'admin',2,'Admin/model/edit','编辑',-1,''),(165,'admin',2,'Admin/model/setStatus','改变状态',-1,''),(166,'admin',2,'Admin/model/update','保存数据',-1,''),(167,'admin',2,'Admin/Attribute/index','属性管理',-1,''),(168,'admin',2,'Admin/Attribute/add','新增',-1,''),(169,'admin',2,'Admin/Attribute/edit','编辑',-1,''),(170,'admin',2,'Admin/Attribute/setStatus','改变状态',-1,''),(171,'admin',2,'Admin/Attribute/update','保存数据',-1,''),(172,'admin',2,'Admin/Config/index','配置管理',-1,''),(173,'admin',2,'Admin/Config/edit','编辑',-1,''),(174,'admin',2,'Admin/Config/del','删除',-1,''),(175,'admin',2,'Admin/Config/add','新增',-1,''),(176,'admin',2,'Admin/Config/save','保存',-1,''),(177,'admin',2,'Admin/Menu/index','菜单管理',-1,''),(178,'admin',2,'Admin/Channel/index','导航管理',-1,''),(179,'admin',2,'Admin/Channel/add','新增',-1,''),(180,'admin',2,'Admin/Channel/edit','编辑',-1,''),(181,'admin',2,'Admin/Channel/del','删除',-1,''),(182,'admin',2,'Admin/Category/index','分类管理',-1,''),(183,'admin',2,'Admin/Category/edit','编辑',-1,''),(184,'admin',2,'Admin/Category/add','新增',-1,''),(185,'admin',2,'Admin/Category/remove','删除',-1,''),(186,'admin',2,'Admin/Category/move','移动',-1,''),(187,'admin',2,'Admin/Category/merge','合并',-1,''),(188,'admin',2,'Admin/Database/index?type=export','备份数据库',-1,''),(189,'admin',2,'Admin/Database/export','备份',-1,''),(190,'admin',2,'Admin/Database/optimize','优化表',-1,''),(191,'admin',2,'Admin/Database/repair','修复表',-1,''),(192,'admin',2,'Admin/Database/index?type=import','还原数据库',-1,''),(193,'admin',2,'Admin/Database/import','恢复',-1,''),(194,'admin',2,'Admin/Database/del','删除',-1,''),(195,'admin',2,'Admin/other','其他',1,''),(196,'admin',2,'Admin/Menu/add','新增',-1,''),(197,'admin',2,'Admin/Menu/edit','编辑',-1,''),(198,'admin',2,'Admin/Think/lists?model=article','应用',-1,''),(199,'admin',2,'Admin/Think/lists?model=download','下载管理',-1,''),(200,'admin',2,'Admin/Think/lists?model=config','应用',1,''),(201,'admin',2,'Admin/Action/actionlog','行为日志',-1,''),(202,'admin',2,'Admin/User/updatePassword','修改密码',-1,''),(203,'admin',2,'Admin/User/updateNickname','修改昵称',-1,''),(204,'admin',2,'Admin/action/edit','查看行为日志',-1,''),(206,'admin',1,'Admin/think/edit','编辑数据',1,''),(207,'admin',1,'Admin/Menu/import','导入',1,''),(208,'admin',1,'Admin/Model/generate','生成',1,''),(209,'admin',1,'Admin/Addons/addHook','新增钩子',1,''),(210,'admin',1,'Admin/Addons/edithook','编辑钩子',1,'');
/*!40000 ALTER TABLE `onethink_auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_category`
--

DROP TABLE IF EXISTS `onethink_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(30) NOT NULL COMMENT '标志',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `list_row` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '列表每页行数',
  `meta_title` varchar(50) NOT NULL DEFAULT '' COMMENT 'SEO的网页标题',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `template_index` varchar(100) NOT NULL COMMENT '频道页模板',
  `template_lists` varchar(100) NOT NULL COMMENT '列表页模板',
  `template_detail` varchar(100) NOT NULL COMMENT '详情页模板',
  `template_edit` varchar(100) NOT NULL COMMENT '编辑页模板',
  `model` varchar(100) NOT NULL DEFAULT '' COMMENT '关联模型',
  `type` varchar(100) NOT NULL DEFAULT '' COMMENT '允许发布的内容类型',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链',
  `allow_publish` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许发布内容',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '可见性',
  `reply` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许回复',
  `check` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '发布的文章是否需要审核',
  `reply_model` varchar(100) NOT NULL DEFAULT '',
  `extend` text NOT NULL COMMENT '扩展设置',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='分类表\r\n@author   麦当苗儿\r\n@version  2013-05-21';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_category`
--

LOCK TABLES `onethink_category` WRITE;
/*!40000 ALTER TABLE `onethink_category` DISABLE KEYS */;
INSERT INTO `onethink_category` VALUES (1,'blog','博客',0,0,10,'','','','','','','','2','2,1',0,0,1,0,0,'1','',1379474947,1382701539,1),(2,'default_blog','默认分类',1,1,10,'','','','','','','','2,3','2,1,3',0,1,1,0,1,'1','',1379475028,1384423564,1),(3,'topic','讨论',0,0,10,'','','','Article/index_topic','Article/lists_topic','Article/Article/detail_topic','','2','2',0,0,1,0,0,'1','',1379475049,1382701899,1),(4,'default_topic','默认分类',3,0,10,'','','','','Article/lists_topic','Article/Article/detail_topic','','2,3','2,3',0,1,1,1,1,'1','',1379475068,1383878121,1);
/*!40000 ALTER TABLE `onethink_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_channel`
--

DROP TABLE IF EXISTS `onethink_channel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '频道ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级频道ID',
  `title` char(30) NOT NULL COMMENT '频道标题',
  `url` char(100) NOT NULL COMMENT '频道连接',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '导航排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_channel`
--

LOCK TABLES `onethink_channel` WRITE;
/*!40000 ALTER TABLE `onethink_channel` DISABLE KEYS */;
INSERT INTO `onethink_channel` VALUES (1,0,'首页','Index/index',2,1379475111,1379923177,1),(2,0,'博客','Article/index?category=blog',0,1379475131,1379483713,1),(3,0,'讨论','Article/index?category=topic',0,1379475154,1379483726,1);
/*!40000 ALTER TABLE `onethink_channel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_config`
--

DROP TABLE IF EXISTS `onethink_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) NOT NULL COMMENT '配置说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text NOT NULL COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_config`
--

LOCK TABLES `onethink_config` WRITE;
/*!40000 ALTER TABLE `onethink_config` DISABLE KEYS */;
INSERT INTO `onethink_config` VALUES (1,'WEB_SITE_TITLE',1,'网站标题',1,'','网站标题前台显示标题',1378898976,1379235274,1,'OneThink内容管理框架',0),(2,'WEB_SITE_DESCRIPTION',2,'网站描述',1,'','网站搜索引擎描述',1378898976,1379235841,1,'OneThink内容管理框架',1),(3,'WEB_SITE_KEYWORD',2,'网站关键字',1,'','网站搜索引擎关键字',1378898976,1381390100,1,'ThinkPHP,OneThink',3),(4,'WEB_SITE_CLOSE',4,'关闭站点',1,'0:关闭,1:开启','站点关闭后其他用户不能访问，管理员可以正常访问',1378898976,1379235296,1,'1',0),(9,'CONFIG_TYPE_LIST',3,'配置类型列表',4,'','主要用于数据解析和页面表单的生成',1378898976,1379235348,1,'0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举',0),(10,'WEB_SITE_ICP',1,'网站备案号',1,'','设置在网站底部显示的备案号，如“沪ICP备12007941号-2',1378900335,1379235859,1,'',4),(11,'DOCUMENT_POSITION',3,'文档推荐位',2,'','文档推荐位，推荐到多个位置KEY值相加即可',1379053380,1379235329,1,'1:列表页推荐\r\n2:频道页推荐\r\n4:网站首页推荐',0),(12,'DOCUMENT_DISPLAY',3,'文档可见性',2,'','文章可见性仅影响前台显示，后台不收影响',1379056370,1379235322,1,'0:所有人可见\r\n1:仅注册会员可见\r\n2:仅管理员可见',0),(13,'COLOR_STYLE',4,'后台色系',1,'default_color:默认\r\nblue_color:紫罗兰','后台颜色风格',1379122533,1379235904,1,'blue_color',5),(20,'CONFIG_GROUP_LIST',3,'配置分组',4,'','配置分组',1379228036,1384418383,1,'1:基本\r\n2:内容\r\n3:用户\r\n4:系统',0),(21,'HOOKS_TYPE',3,'钩子的类型',4,'','类型 1-用于扩展显示内容，2-用于扩展业务处理',1379313397,1379313407,1,'1:视图\r\n2:控制器',0),(22,'AUTH_CONFIG',3,'Auth配置',4,'','自定义Auth.class.php类配置',1379409310,1379409564,1,'AUTH_ON:1\r\nAUTH_TYPE:2',0),(23,'OPEN_DRAFTBOX',4,'是否开启草稿功能',2,'0:关闭草稿功能\r\n1:开启草稿功能\r\n','新增文章时的草稿功能配置',1379484332,1379484591,1,'1',0),(24,'AOTUSAVE_DRAFT',0,'自动保存草稿时间',2,'','自动保存草稿的时间间隔，单位：秒',1379484574,1379484574,1,'60',0),(25,'LIST_ROWS',0,'后台每页记录数',2,'','后台数据每页显示记录数',1379503896,1380427745,1,'10',5),(26,'USER_ALLOW_REGISTER',4,'是否允许用户注册',3,'0:关闭注册\r\n1:允许注册','是否开放用户注册',1379504487,1379504580,1,'1',0),(27,'CODEMIRROR_THEME',4,'预览插件的CodeMirror主题',4,'3024-day:3024 day\r\n3024-night:3024 night\r\nambiance:ambiance\r\nbase16-dark:base16 dark\r\nbase16-light:base16 light\r\nblackboard:blackboard\r\ncobalt:cobalt\r\neclipse:eclipse\r\nelegant:elegant\r\nerlang-dark:erlang-dark\r\nlesser-dark:lesser-dark\r\nmidnight:midnight','详情见CodeMirror官网',1379814385,1384740813,1,'ambiance',0),(28,'DATA_BACKUP_PATH',1,'数据库备份根路径',4,'','路径必须以 / 结尾',1381482411,1381482411,1,'./Data/',0),(29,'DATA_BACKUP_PART_SIZE',0,'数据库备份卷大小',4,'','该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M',1381482488,1381729564,1,'20971520',0),(30,'DATA_BACKUP_COMPRESS',4,'数据库备份文件是否启用压缩',4,'0:不压缩\r\n1:启用压缩','压缩备份文件需要PHP环境支持gzopen,gzwrite函数',1381713345,1381729544,1,'1',0),(31,'DATA_BACKUP_COMPRESS_LEVEL',4,'数据库备份文件压缩级别',4,'1:普通\r\n4:一般\r\n9:最高','数据库备份文件的压缩级别，该配置在开启压缩时生效',1381713408,1381713408,1,'9',0),(32,'DEVELOP_MODE',4,'开启开发者模式',4,'0:关闭\r\n1:开启','是否开启开发者模式',1383105995,1383291877,1,'1',0);
/*!40000 ALTER TABLE `onethink_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_document`
--

DROP TABLE IF EXISTS `onethink_document`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_document` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` char(40) NOT NULL DEFAULT '' COMMENT '标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '标题',
  `category_id` int(10) unsigned NOT NULL COMMENT '所属分类',
  `description` char(140) NOT NULL DEFAULT '' COMMENT '描述',
  `root` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '根节点',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属ID',
  `model_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容模型ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '内容类型',
  `position` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '封面',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '可见性',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '截至时间',
  `attach` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '附件数量',
  `view` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览量',
  `comment` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `extend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '扩展统计字段',
  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '优先级',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据状态',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_category_status` (`category_id`,`status`),
  KEY `idx_status_type_pid` (`status`,`type`,`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=102 DEFAULT CHARSET=utf8 COMMENT='文档模型基础表\r\n@author   麦当苗儿\r\n@version  2013-05-21';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_document`
--

LOCK TABLES `onethink_document` WRITE;
/*!40000 ALTER TABLE `onethink_document` DISABLE KEYS */;
/*!40000 ALTER TABLE `onethink_document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_document_article`
--

DROP TABLE IF EXISTS `onethink_document_article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_document_article` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型',
  `content` text NOT NULL COMMENT '文章内容',
  `template` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页显示模板',
  `bookmark` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型文章表\r\n@author   麦当苗儿\r\n@version  2013-05-24';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_document_article`
--

LOCK TABLES `onethink_document_article` WRITE;
/*!40000 ALTER TABLE `onethink_document_article` DISABLE KEYS */;
/*!40000 ALTER TABLE `onethink_document_article` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_document_download`
--

DROP TABLE IF EXISTS `onethink_document_download`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_document_download` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型',
  `content` text NOT NULL COMMENT '下载详细描述',
  `template` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页显示模板',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型下载表\r\n@author   麦当苗儿\r\n@version  2013-05-24';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_document_download`
--

LOCK TABLES `onethink_document_download` WRITE;
/*!40000 ALTER TABLE `onethink_document_download` DISABLE KEYS */;
/*!40000 ALTER TABLE `onethink_document_download` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_file`
--

DROP TABLE IF EXISTS `onethink_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '原始文件名',
  `savename` char(20) NOT NULL DEFAULT '' COMMENT '保存名称',
  `savepath` char(30) NOT NULL DEFAULT '' COMMENT '文件保存路径',
  `ext` char(5) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `mime` char(40) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `location` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '文件保存位置',
  `create_time` int(10) unsigned NOT NULL COMMENT '上传时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_md5` (`md5`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='文件表\r\n@author   麦当苗儿\r\n@version  2013-05-21';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_file`
--

LOCK TABLES `onethink_file` WRITE;
/*!40000 ALTER TABLE `onethink_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `onethink_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_hooks`
--

DROP TABLE IF EXISTS `onethink_hooks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_hooks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text NOT NULL COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  PRIMARY KEY (`id`),
  UNIQUE KEY `搜索索引` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_hooks`
--

LOCK TABLES `onethink_hooks` WRITE;
/*!40000 ALTER TABLE `onethink_hooks` DISABLE KEYS */;
INSERT INTO `onethink_hooks` VALUES (1,'pageHeader','页面header钩子，一般用于加载插件CSS文件和代码',1,0,''),(2,'pageFooter','页面footer钩子，一般用于加载插件JS文件和JS代码',1,0,'ReturnTop'),(3,'documentEditForm','添加编辑表单的 扩展内容钩子',1,0,'Attachment'),(4,'documentDetailAfter','文档末尾显示',1,0,'Attachment,SocialComment'),(5,'documentDetailBefore','页面内容前显示用钩子',1,0,''),(6,'documentSaveComplete','保存文档数据后的扩展钩子',2,0,'Attachment'),(7,'documentEditFormContent','添加编辑表单的内容显示钩子',1,0,'Editor'),(8,'adminArticleEdit','后台内容编辑页编辑器',1,1378982734,'EditorForAdmin'),(13,'AdminIndex','首页小格子个性化显示',1,1382596073,'SiteStat,SystemInfo,DevTeam'),(14,'topicComment','评论提交方式扩展钩子。',1,1380163518,'Editor'),(16,'app_begin','应用开始',2,1384481614,'');
/*!40000 ALTER TABLE `onethink_hooks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_member`
--

DROP TABLE IF EXISTS `onethink_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_member` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `nickname` char(16) NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date NOT NULL DEFAULT '0000-00-00' COMMENT '生日',
  `qq` char(10) NOT NULL DEFAULT '' COMMENT 'qq号',
  `score` mediumint(8) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '会员状态',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `ix_uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='会员表\r\n@author   麦当苗儿\r\n@version  2013-05-27';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_member`
--

LOCK TABLES `onethink_member` WRITE;
/*!40000 ALTER TABLE `onethink_member` DISABLE KEYS */;
INSERT INTO `onethink_member` VALUES (1,'Administrator',0,'0000-00-00','',0,1,0,1535849943,0,1535849943,1);
/*!40000 ALTER TABLE `onethink_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_menu`
--

DROP TABLE IF EXISTS `onethink_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_menu`
--

LOCK TABLES `onethink_menu` WRITE;
/*!40000 ALTER TABLE `onethink_menu` DISABLE KEYS */;
INSERT INTO `onethink_menu` VALUES (1,'首页',0,0,'Index/index',0,'',NULL,0),(2,'内容',0,0,'Article/mydocument',0,'',NULL,0),(3,'文档列表',2,0,'article/index',1,'','内容',0),(4,'新增',3,0,'article/add',0,'',NULL,0),(5,'编辑',3,0,'article/edit',0,'',NULL,0),(6,'改变状态',3,0,'article/setStatus',0,'',NULL,0),(7,'保存',3,0,'article/update',0,'',NULL,0),(8,'保存草稿',3,0,'article/autoSave',0,'',NULL,0),(9,'移动',3,0,'article/move',0,'',NULL,0),(10,'复制',3,0,'article/copy',0,'',NULL,0),(11,'粘贴',3,0,'article/paste',0,'',NULL,0),(12,'导入',3,0,'article/batchOperate',0,'',NULL,0),(13,'回收站',2,0,'article/recycle',1,'','内容',0),(14,'还原',13,0,'article/permit',0,'',NULL,0),(15,'清空',13,0,'article/clear',0,'',NULL,0),(16,'用户',0,0,'User/index',0,'',NULL,0),(17,'用户信息',16,0,'User/index',0,'','用户管理',0),(18,'新增用户',17,0,'User/add',0,'添加新用户',NULL,0),(19,'用户行为',16,0,'User/action',0,'','用户管理',0),(20,'新增用户行为',19,0,'User/addAction',0,'\"用户->用户行为\"中的新增',NULL,0),(21,'编辑用户行为',19,0,'User/editAction',0,'\"用户->用户行为\"点击标题进行编辑',NULL,0),(22,'保存用户行为',19,0,'User/saveAction',0,'\"用户->用户行为\"保存编辑和新增的用户行为',NULL,0),(23,'变更行为状态',19,0,'User/setStatus',0,'\"用户->用户行为\"中的启用,禁用和删除权限',NULL,0),(24,'禁用会员',19,0,'User/changeStatus?method=forbidUser',0,'\"用户->用户信息\"中的禁用',NULL,0),(25,'启用会员',19,0,'User/changeStatus?method=resumeUser',0,'\"用户->用户信息\"中的启用',NULL,0),(26,'删除会员',19,0,'User/changeStatus?method=deleteUser',0,'\"用户->用户信息\"中的删除',NULL,0),(27,'权限管理',16,0,'AuthManager/index',0,'','',0),(28,'删除',27,0,'AuthManager/changeStatus?method=deleteGroup',0,'删除用户组',NULL,0),(29,'禁用',27,0,'AuthManager/changeStatus?method=forbidGroup',0,'禁用用户组',NULL,0),(30,'恢复',27,0,'AuthManager/changeStatus?method=resumeGroup',0,'恢复已禁用的用户组',NULL,0),(31,'新增',27,0,'AuthManager/createGroup',0,'创建新的用户组',NULL,0),(32,'编辑',27,0,'AuthManager/editGroup',0,'编辑用户组名称和描述',NULL,0),(33,'保存用户组',27,0,'AuthManager/writeGroup',0,'新增和编辑用户组的\"保存\"按钮',NULL,0),(34,'授权',27,0,'AuthManager/group',0,'\"后台 \\ 用户 \\ 用户信息\"列表页的\"授权\"操作按钮,用于设置用户所属用户组',NULL,0),(35,'访问授权',27,0,'AuthManager/access',0,'\"后台 \\ 用户 \\ 权限管理\"列表页的\"访问授权\"操作按钮',NULL,0),(36,'成员授权',27,0,'AuthManager/user',0,'\"后台 \\ 用户 \\ 权限管理\"列表页的\"成员授权\"操作按钮',NULL,0),(37,'解除授权',27,0,'AuthManager/removeFromGroup',0,'\"成员授权\"列表页内的解除授权操作按钮',NULL,0),(38,'保存成员授权',27,0,'AuthManager/addToGroup',0,'\"用户信息\"列表页\"授权\"时的\"保存\"按钮和\"成员授权\"里右上角的\"添加\"按钮)',NULL,0),(39,'分类授权',27,0,'AuthManager/category',0,'\"后台 \\ 用户 \\ 权限管理\"列表页的\"分类授权\"操作按钮',NULL,0),(40,'保存分类授权',27,0,'AuthManager/addToCategory',0,'\"分类授权\"页面的\"保存\"按钮',NULL,0),(41,'模型授权',27,0,'AuthManager/modelauth',0,'\"后台 \\ 用户 \\ 权限管理\"列表页的\"模型授权\"操作按钮',NULL,0),(42,'保存模型授权',27,0,'AuthManager/addToModel',0,'\"分类授权\"页面的\"保存\"按钮',NULL,0),(43,'扩展',0,5,'Addons/index',0,'','',0),(44,'插件管理',43,0,'Addons/index',0,'','扩展',0),(45,'创建',44,0,'Addons/create',0,'服务器上创建插件结构向导',NULL,0),(46,'检测创建',44,0,'Addons/checkForm',0,'检测插件是否可以创建',NULL,0),(47,'预览',44,0,'Addons/preview',0,'预览插件定义类文件',NULL,0),(48,'快速生成插件',44,0,'Addons/build',0,'开始生成插件结构',NULL,0),(49,'设置',44,0,'Addons/config',0,'设置插件配置',NULL,0),(50,'禁用',44,0,'Addons/disable',0,'禁用插件',NULL,0),(51,'启用',44,0,'Addons/enable',0,'启用插件',NULL,0),(52,'安装',44,0,'Addons/install',0,'安装插件',NULL,0),(53,'卸载',44,0,'Addons/uninstall',0,'卸载插件',NULL,0),(54,'更新配置',44,0,'Addons/saveconfig',0,'更新插件配置处理',NULL,0),(55,'插件后台列表',44,0,'Addons/adminList',0,'',NULL,0),(56,'URL方式访问插件',44,0,'Addons/execute',0,'控制是否有权限通过url访问插件控制器方法',NULL,0),(57,'钩子管理',43,0,'Addons/hooks',0,'','扩展',0),(58,'模型管理',68,0,'Model/index',0,'','系统设置',0),(59,'新增',58,0,'model/add',0,'',NULL,0),(60,'编辑',58,0,'model/edit',0,'',NULL,0),(61,'改变状态',58,0,'model/setStatus',0,'',NULL,0),(62,'保存数据',58,0,'model/update',0,'',NULL,0),(63,'属性管理',68,0,'Attribute/index',1,'','',0),(64,'新增',63,0,'Attribute/add',0,'',NULL,0),(65,'编辑',63,0,'Attribute/edit',0,'',NULL,0),(66,'改变状态',63,0,'Attribute/setStatus',0,'',NULL,0),(67,'保存数据',63,0,'Attribute/update',0,'',NULL,0),(68,'系统',0,0,'Config/group',0,'',NULL,0),(69,'网站设置',68,0,'Config/group',0,'','系统设置',0),(70,'配置管理',68,0,'Config/index',0,'','系统设置',0),(71,'编辑',70,0,'Config/edit',0,'新增编辑和保存配置',NULL,0),(72,'删除',70,0,'Config/del',0,'删除配置',NULL,0),(73,'新增',70,0,'Config/add',0,'新增配置',NULL,0),(74,'保存',70,0,'Config/save',0,'保存配置',NULL,0),(75,'菜单管理',68,0,'Menu/index',0,'','系统设置',0),(76,'导航管理',68,0,'Channel/index',0,'','系统设置',0),(77,'新增',76,0,'Channel/add',0,'',NULL,0),(78,'编辑',76,0,'Channel/edit',0,'',NULL,0),(79,'删除',76,0,'Channel/del',0,'',NULL,0),(80,'分类管理',68,0,'Category/index',0,'','系统设置',0),(81,'编辑',80,0,'Category/edit',0,'编辑和保存栏目分类',NULL,0),(82,'新增',80,0,'Category/add',0,'新增栏目分类',NULL,0),(83,'删除',80,0,'Category/remove',0,'删除栏目分类',NULL,0),(84,'移动',80,0,'Category/move',0,'移动栏目分类',NULL,0),(85,'合并',80,0,'Category/merge',0,'合并栏目分类',NULL,0),(86,'备份数据库',68,0,'Database/index?type=export',0,'','数据备份',0),(87,'备份',86,0,'Database/export',0,'备份数据库',NULL,0),(88,'优化表',86,0,'Database/optimize',0,'优化数据表',NULL,0),(89,'修复表',86,0,'Database/repair',0,'修复数据表',NULL,0),(90,'还原数据库',68,0,'Database/index?type=import',0,'','数据备份',0),(91,'恢复',90,0,'Database/import',0,'数据库恢复',NULL,0),(92,'删除',90,0,'Database/del',0,'删除备份文件',NULL,0),(93,'其他',0,0,'other',1,'',NULL,0),(96,'新增',75,0,'Menu/add',0,'','系统设置',0),(98,'编辑',75,0,'Menu/edit',0,'','',0),(102,'应用',0,0,'Think/lists?model=config',0,'','',0),(104,'下载管理',102,0,'Think/lists?model=download',0,'','',0),(105,'配置管理',102,0,'Think/lists?model=config',0,'','',0),(106,'行为日志',16,0,'Action/actionlog',0,'','行为管理',0),(108,'修改密码',16,0,'User/updatePassword',1,'','',0),(109,'修改昵称',16,0,'User/updateNickname',1,'','',0),(110,'查看行为日志',106,0,'action/edit',1,'','',0),(112,'新增数据',58,0,'think/add',1,'','',0),(113,'编辑数据',58,0,'think/edit',1,'','',0),(114,'导入',75,0,'Menu/import',0,'','',0),(115,'生成',58,0,'Model/generate',0,'','',0),(116,'新增钩子',57,0,'Addons/addHook',0,'','',0),(117,'编辑钩子',57,0,'Addons/edithook',0,'','',0);
/*!40000 ALTER TABLE `onethink_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_model`
--

DROP TABLE IF EXISTS `onethink_model`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_model` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '模型标识',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  `extend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '继承的模型',
  `relation` varchar(30) NOT NULL DEFAULT '' COMMENT '继承与被继承模型的关联字段',
  `need_pk` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '新建表时是否需要主键字段',
  `field_sort` text NOT NULL COMMENT '表单字段排序',
  `field_group` varchar(255) NOT NULL DEFAULT '1:基础' COMMENT '字段分组',
  `attribute_list` text NOT NULL COMMENT '属性列表（表的字段）',
  `template_list` varchar(100) NOT NULL DEFAULT '' COMMENT '列表模板',
  `template_add` varchar(100) NOT NULL DEFAULT '' COMMENT '新增模板',
  `template_edit` varchar(100) NOT NULL DEFAULT '' COMMENT '编辑模板',
  `list_grid` text NOT NULL COMMENT '列表定义',
  `list_row` smallint(2) unsigned NOT NULL DEFAULT '10' COMMENT '列表数据长度',
  `search_key` varchar(50) NOT NULL DEFAULT '' COMMENT '默认搜索字段',
  `search_list` varchar(255) NOT NULL DEFAULT '' COMMENT '高级搜索的字段',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='文档模型表\r\n@author   麦当苗儿\r\n@version  2013-06-19';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_model`
--

LOCK TABLES `onethink_model` WRITE;
/*!40000 ALTER TABLE `onethink_model` DISABLE KEYS */;
INSERT INTO `onethink_model` VALUES (1,'document','基础文档',0,'',1,'{\"1\":[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\",\"10\",\"11\",\"12\",\"13\",\"14\",\"15\",\"16\",\"17\",\"18\",\"19\",\"20\",\"21\",\"22\"]}','1:基础','','','','','id:编号\r\ntitle:标题:article/index?cate_id=[category_id]&pid=[id]\r\ntype|get_document_type:类型\r\nlevel:优先级\r\nupdate_time|time_format:最后更新\r\nstatus_text:状态\r\nview:浏览\r\nid:操作:[EDIT]&cate_id=[category_id]|编辑,article/setstatus?status=-1&ids=[id]|删除',0,'','',1383891233,1384507827,1),(2,'article','文章文档',1,'',1,'{\"1\":[\"3\",\"2\",\"5\",\"24\"],\"2\":[\"23\",\"14\",\"25\",\"11\",\"9\",\"15\",\"16\",\"10\",\"17\",\"19\",\"13\",\"20\",\"12\",\"26\"]}','1:基础,2:扩展','','','','','id:编号\r\ntitle:标题:article/edit?cate_id=[category_id]&id=[id]\r\ncontent:内容',0,'','',1383891243,1384743355,1),(3,'download','下载文档',1,'',1,'{\"1\":[\"29\",\"3\",\"2\",\"5\",\"27\",\"28\",\"30\",\"9\",\"10\",\"12\",\"15\",\"14\",\"11\",\"13\",\"31\",\"17\",\"16\",\"19\",\"32\",\"20\"]}','1:基础,2:扩展','','','','','id:编号\r\ntitle:标题',0,'','',1383891252,1385714025,1),(4,'action','行为定义',0,'',1,'{\"1\":[\"33\",\"34\",\"35\",\"36\",\"37\",\"38\",\"39\"]}','1:基础','','','','','id:编号',0,'','',1383891262,1383891784,1),(5,'action_log','行为日志',0,'',1,'{\"1\":[\"40\",\"41\",\"42\",\"43\",\"44\",\"45\",\"46\",\"47\"]}','1:基础','','','','','id:编号',0,'','',1383891340,1383891802,1),(6,'addons','插件',0,'',1,'{\"1\":[\"48\",\"49\",\"50\",\"51\",\"52\",\"53\",\"54\",\"55\",\"56\"]}','1:基础','','','','','id:编号',0,'','',1383891396,1383891828,1),(7,'attachment','附件',0,'',1,'{\"1\":[\"57\",\"58\",\"59\",\"60\",\"61\",\"62\",\"63\",\"64\",\"65\",\"66\",\"67\",\"68\"]}','1:基础','','','','','id:编号',0,'','',1383891410,1383891847,1),(8,'attribute','属性',0,'',1,'{\"1\":[\"69\",\"70\",\"71\",\"72\",\"73\",\"74\",\"75\",\"76\",\"77\",\"78\",\"79\",\"80\",\"81\"]}','1:基础','','','','','id:编号',0,'','',1383891441,1383891888,1),(9,'category','分类',0,'',1,'{\"1\":[\"82\",\"83\",\"84\",\"85\",\"86\",\"87\",\"88\",\"89\",\"90\",\"91\",\"92\",\"93\",\"94\",\"95\",\"96\",\"97\",\"98\",\"99\",\"100\",\"101\",\"102\",\"103\",\"104\",\"105\"]}','1:基础','','','','','id:编号',0,'','',1383891453,1383891904,1),(10,'channel','前台菜单',0,'',1,'{\"1\":[\"106\",\"107\",\"108\",\"109\",\"110\",\"111\",\"112\"]}','1:基础','','','','','id:编号',0,'','',1383891460,1383891939,1),(11,'config','配置',0,'',1,'{\"1\":[\"113\",\"114\",\"115\",\"116\",\"117\",\"118\",\"119\",\"120\",\"121\",\"122\",\"123\"]}','1:基础','','','','','id:编号\r\nname:名称:edit?id=[id]&model=[MODEL]\r\ntitle:配置',0,'title|name','',1383891466,1384248402,1),(12,'file','文件上传',0,'',1,'{\"1\":[\"124\",\"125\",\"126\",\"127\",\"128\",\"129\",\"130\",\"131\",\"132\",\"133\"]}','1:基础','','','','','id:编号',0,'','',1383891474,1383891964,1),(13,'hooks','钩子',0,'',1,'{\"1\":[\"134\",\"135\",\"136\",\"137\",\"138\"]}','1:基础','','','','','id:编号',0,'','',1383891480,1384495820,1),(14,'member','用户信息',0,'',1,'{\"1\":[\"139\",\"140\",\"141\",\"142\",\"143\",\"144\",\"145\",\"146\",\"147\",\"148\",\"149\",\"150\"]}','1:基础','','','','','id:编号',0,'','',1383891488,1384151998,1),(15,'menu','后台菜单',0,'',1,'{\"1\":[\"151\",\"152\",\"153\",\"154\",\"155\",\"156\",\"157\",\"158\"]}','1:基础','','','','','id:编号',0,'','',1383891503,1383892107,1),(16,'model','模型',0,'',1,'{\"1\":[\"159\",\"160\",\"161\",\"162\",\"163\",\"164\",\"165\",\"166\",\"167\",\"168\",\"169\",\"170\",\"171\",\"172\",\"173\",\"174\",\"175\",\"176\"]}','1:基础','','','','','id:编号',0,'','',1383891514,1383892121,1),(17,'picture','图片上传',0,'',1,'{\"1\":[\"177\",\"178\",\"179\",\"180\",\"181\",\"182\"]}','1:基础','','','','','id:编号',0,'','',1383891522,1383892140,1),(18,'url','链接管理',0,'',1,'{\"1\":[\"183\",\"184\",\"185\",\"186\"]}','1:基础','','','','','id:编号',0,'','',1383891531,1385617833,1);
/*!40000 ALTER TABLE `onethink_model` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_picture`
--

DROP TABLE IF EXISTS `onethink_picture`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_picture`
--

LOCK TABLES `onethink_picture` WRITE;
/*!40000 ALTER TABLE `onethink_picture` DISABLE KEYS */;
/*!40000 ALTER TABLE `onethink_picture` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_ucenter_admin`
--

DROP TABLE IF EXISTS `onethink_ucenter_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_ucenter_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员用户ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '管理员状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='管理员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_ucenter_admin`
--

LOCK TABLES `onethink_ucenter_admin` WRITE;
/*!40000 ALTER TABLE `onethink_ucenter_admin` DISABLE KEYS */;
INSERT INTO `onethink_ucenter_admin` VALUES (1,1,1);
/*!40000 ALTER TABLE `onethink_ucenter_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_ucenter_app`
--

DROP TABLE IF EXISTS `onethink_ucenter_app`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_ucenter_app` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '应用ID',
  `title` varchar(30) NOT NULL COMMENT '应用名称',
  `url` varchar(100) NOT NULL COMMENT '应用URL',
  `ip` char(15) NOT NULL COMMENT '应用IP',
  `auth_key` varchar(100) NOT NULL COMMENT '加密KEY',
  `sys_login` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '同步登陆',
  `allow_ip` varchar(255) NOT NULL COMMENT '允许访问的IP',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '应用状态',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='应用表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_ucenter_app`
--

LOCK TABLES `onethink_ucenter_app` WRITE;
/*!40000 ALTER TABLE `onethink_ucenter_app` DISABLE KEYS */;
/*!40000 ALTER TABLE `onethink_ucenter_app` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_ucenter_member`
--

DROP TABLE IF EXISTS `onethink_ucenter_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_ucenter_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(16) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `email` char(32) NOT NULL COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL COMMENT '用户手机',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '用户状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='用户表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_ucenter_member`
--

LOCK TABLES `onethink_ucenter_member` WRITE;
/*!40000 ALTER TABLE `onethink_ucenter_member` DISABLE KEYS */;
INSERT INTO `onethink_ucenter_member` VALUES (1,'Administrator','0cac7555cc745582589d418efb4937e5','admin@qq.com','',1535849943,2887254017,0,0,1535849943,1);
/*!40000 ALTER TABLE `onethink_ucenter_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_ucenter_setting`
--

DROP TABLE IF EXISTS `onethink_ucenter_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_ucenter_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '设置ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型（1-用户配置）',
  `value` text NOT NULL COMMENT '配置数据',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='设置表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_ucenter_setting`
--

LOCK TABLES `onethink_ucenter_setting` WRITE;
/*!40000 ALTER TABLE `onethink_ucenter_setting` DISABLE KEYS */;
/*!40000 ALTER TABLE `onethink_ucenter_setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_url`
--

DROP TABLE IF EXISTS `onethink_url`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_url` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '链接唯一标识',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `short` char(100) NOT NULL DEFAULT '' COMMENT '短网址',
  `status` tinyint(2) NOT NULL DEFAULT '2' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_url` (`url`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='链接表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_url`
--

LOCK TABLES `onethink_url` WRITE;
/*!40000 ALTER TABLE `onethink_url` DISABLE KEYS */;
/*!40000 ALTER TABLE `onethink_url` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onethink_userdata`
--

DROP TABLE IF EXISTS `onethink_userdata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `onethink_userdata` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `type` tinyint(3) unsigned NOT NULL COMMENT '类型标识',
  `target_id` int(10) unsigned NOT NULL COMMENT '目标id',
  UNIQUE KEY `uid` (`uid`,`type`,`target_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onethink_userdata`
--

LOCK TABLES `onethink_userdata` WRITE;
/*!40000 ALTER TABLE `onethink_userdata` DISABLE KEYS */;
/*!40000 ALTER TABLE `onethink_userdata` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-09-02  1:00:57
