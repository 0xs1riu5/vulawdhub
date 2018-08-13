<?php die();?>
SET NAMES utf8;
DROP TABLE IF EXISTS `zt_action`;
CREATE TABLE `zt_action` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `objectType` varchar(30) NOT NULL DEFAULT '',
  `objectID` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `product` varchar(255) NOT NULL,
  `project` mediumint(9) NOT NULL,
  `actor` varchar(30) NOT NULL DEFAULT '',
  `action` varchar(30) NOT NULL DEFAULT '',
  `date` datetime NOT NULL,
  `comment` text NOT NULL,
  `extra` text NOT NULL,
  `read` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `action` (`objectID`,`product`,`project`,`action`,`date`)
) ENGINE=MyISAM AUTO_INCREMENT=116 DEFAULT CHARSET=utf8;
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('1','user','20',',0,','0','azhi','logout','2012-06-05 09:24:52','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('2','user','1',',0,','0','admin','login','2012-06-05 09:25:00','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('3','user','1',',0,','0','admin','logout','2012-06-05 09:51:10','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('4','user','2',',0,','0','productManager','login','2012-06-05 09:51:33','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('5','user','2',',0,','0','productManager','logout','2012-06-05 09:53:05','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('6','user','1',',0,','0','admin','login','2012-06-05 09:53:10','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('7','user','1',',0,','0','admin','logout','2012-06-05 09:53:52','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('8','user','2',',0,','0','productManager','login','2012-06-05 09:54:07','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('9','product','1',',1,','0','productManager','opened','2012-06-05 09:57:07','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('10','productplan','1',',1,','0','productManager','opened','2012-06-05 10:02:49','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('11','story','1',',1,','0','productManager','opened','2012-06-05 10:09:49','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('12','story','2',',1,','0','productManager','opened','2012-06-05 10:16:37','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('13','story','3',',1,','0','productManager','opened','2012-06-05 10:18:10','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('14','story','3',',1,','0','productManager','changed','2012-06-05 10:19:06','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('15','story','4',',1,','0','productManager','opened','2012-06-05 10:20:16','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('16','story','5',',1,','0','productManager','opened','2012-06-05 10:21:39','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('17','story','6',',1,','0','productManager','opened','2012-06-05 10:23:11','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('18','story','7',',1,','0','productManager','opened','2012-06-05 10:24:19','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('19','story','1',',1,','0','productManager','reviewed','2012-06-05 10:25:19','','Pass','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('20','story','2',',1,','0','productManager','reviewed','2012-06-05 10:25:33','','Pass','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('21','story','3',',1,','0','productManager','reviewed','2012-06-05 10:25:38','','Pass','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('22','story','4',',1,','0','productManager','reviewed','2012-06-05 10:25:42','','Pass','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('23','user','2',',0,','0','productManager','logout','2012-06-05 10:26:20','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('24','user','3',',0,','0','projectManager','login','2012-06-05 10:26:38','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('25','project','1',',1,','1','projectManager','opened','2012-06-05 10:28:36','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('26','story','4',',1,','1','projectManager','linked2project','2012-06-05 10:31:02','','1','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('27','story','3',',1,','1','projectManager','linked2project','2012-06-05 10:31:02','','1','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('28','story','2',',1,','1','projectManager','linked2project','2012-06-05 10:31:02','','1','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('29','story','1',',1,','1','projectManager','linked2project','2012-06-05 10:31:02','','1','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('30','task','1',',1,','1','projectManager','opened','2012-06-05 10:32:03','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('31','task','2',',1,','1','projectManager','opened','2012-06-05 10:32:23','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('32','task','3',',1,','1','projectManager','opened','2012-06-05 10:33:01','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('33','task','4',',1,','1','projectManager','opened','2012-06-05 10:33:21','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('34','task','5',',1,','1','projectManager','opened','2012-06-05 10:33:44','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('35','task','6',',1,','1','projectManager','opened','2012-06-05 10:33:59','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('36','task','7',',1,','1','projectManager','opened','2012-06-05 10:34:25','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('37','task','8',',1,','1','projectManager','opened','2012-06-05 10:34:45','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('38','task','9',',1,','1','projectManager','opened','2012-06-05 10:35:01','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('39','user','3',',0,','0','projectManager','logout','2012-06-05 10:37:30','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('40','user','4',',0,','0','dev1','login','2012-06-05 10:37:40','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('41','task','1',',1,','1','dev1','started','2012-06-05 10:37:54','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('42','task','1',',1,','1','dev1','finished','2012-06-05 10:38:00','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('43','task','8',',1,','1','dev1','finished','2012-06-05 10:39:14','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('44','task','9',',1,','1','dev1','edited','2012-06-05 10:41:20','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('45','task','8',',1,','1','dev1','edited','2012-06-05 10:41:20','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('46','task','7',',1,','1','dev1','edited','2012-06-05 10:41:20','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('47','task','6',',1,','1','dev1','edited','2012-06-05 10:41:20','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('48','task','5',',1,','1','dev1','edited','2012-06-05 10:41:20','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('49','task','4',',1,','1','dev1','edited','2012-06-05 10:41:20','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('50','task','3',',1,','1','dev1','edited','2012-06-05 10:41:20','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('51','task','2',',1,','1','dev1','edited','2012-06-05 10:41:20','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('52','task','1',',1,','1','dev1','edited','2012-06-05 10:41:20','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('53','user','4',',0,','0','dev1','logout','2012-06-05 10:41:50','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('54','user','5',',0,','0','dev2','login','2012-06-05 10:41:56','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('55','task','4',',1,','1','dev2','edited','2012-06-05 10:42:56','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('56','task','3',',1,','1','dev2','edited','2012-06-05 10:42:57','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('57','user','5',',0,','0','dev2','logout','2012-06-05 10:43:02','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('58','user','6',',0,','0','dev3','login','2012-06-05 10:43:07','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('59','task','6',',1,','1','dev3','edited','2012-06-05 10:43:32','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('60','task','5',',1,','1','dev3','edited','2012-06-05 10:43:32','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('61','user','6',',0,','0','dev3','logout','2012-06-05 10:43:42','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('62','user','3',',0,','0','projectManager','login','2012-06-05 10:44:05','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('63','user','3',',0,','0','projectManager','logout','2012-06-05 10:50:03','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('64','user','10',',0,','0','testManager','login','2012-06-05 10:50:09','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('65','user','10',',0,','0','testManager','logout','2012-06-05 10:50:14','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('66','user','10',',0,','0','testManager','login','2012-06-05 10:50:23','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('67','user','10',',0,','0','testManager','logout','2012-06-05 10:50:32','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('68','user','1',',0,','0','admin','login','2012-06-05 10:50:36','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('69','user','1',',0,','0','admin','logout','2012-06-05 10:50:53','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('70','user','10',',0,','0','testManager','login','2012-06-05 10:51:01','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('71','user','10',',0,','0','testManager','logout','2012-06-05 10:51:33','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('72','user','7',',0,','0','tester1','login','2012-06-05 10:51:38','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('73','bug','1',',1,','1','tester1','opened','2012-06-05 10:56:11','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('74','bug','2',',1,','1','tester1','opened','2012-06-05 10:57:11','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('75','user','7',',0,','0','tester1','logout','2012-06-05 10:57:13','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('76','user','8',',0,','0','tester2','login','2012-06-05 10:57:24','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('77','bug','3',',1,','1','tester2','opened','2012-06-05 10:58:22','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('78','user','8',',0,','0','tester2','logout','2012-06-05 10:58:39','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('79','user','9',',0,','0','tester3','login','2012-06-05 10:58:46','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('80','bug','4',',1,','1','tester3','opened','2012-06-05 11:00:19','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('81','case','1',',1,','0','tester3','opened','2012-06-05 11:02:18','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('82','case','1',',1,','0','tester3','edited','2012-06-05 11:02:47','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('83','user','9',',0,','0','tester3','logout','2012-06-05 11:02:48','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('84','user','7',',0,','0','tester1','login','2012-06-05 11:02:56','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('85','case','2',',1,','0','tester1','opened','2012-06-05 11:03:28','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('86','case','3',',1,','0','tester1','opened','2012-06-05 11:03:54','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('87','user','7',',0,','0','tester1','logout','2012-06-05 11:04:00','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('88','user','8',',0,','0','tester2','login','2012-06-05 11:04:10','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('89','case','4',',1,','0','tester2','opened','2012-06-05 11:04:48','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('90','user','8',',0,','0','tester2','logout','2012-06-05 11:04:52','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('91','user','10',',0,','0','testManager','login','2012-06-05 11:04:58','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('92','testtask','1',',1,','1','testManager','opened','2012-06-05 11:07:41','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('93','testtask','1',',1,','1','testManager','edited','2012-06-05 11:07:52','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('94','user','10',',0,','0','testManager','logout','2012-06-05 11:08:10','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('95','user','1',',0,','0','admin','login','2012-06-05 11:08:15','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('96','user','1',',0,','0','admin','logout','2012-06-05 11:08:23','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('97','user','10',',0,','0','testManager','login','2012-06-05 11:08:35','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('98','user','10',',0,','0','testManager','logout','2012-06-05 11:08:55','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('99','user','7',',0,','0','tester1','login','2012-06-05 11:08:59','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('100','user','7',',0,','0','tester1','logout','2012-06-05 11:09:52','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('101','user','1',',0,','0','admin','login','2012-06-05 11:09:54','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('102','user','1',',0,','0','admin','logout','2012-06-05 11:10:38','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('103','user','10',',0,','0','testManager','login','2012-06-05 11:10:42','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('104','user','10',',0,','0','testManager','logout','2012-06-05 11:11:13','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('105','user','3',',0,','0','projectManager','login','2012-06-05 11:11:16','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('106','build','1',',1,','1','projectManager','opened','2012-06-05 11:11:45','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('107','project','2',',1,','2','projectManager','opened','2012-06-05 11:12:28','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('108','user','3',',0,','0','projectManager','logout','2012-06-05 11:14:40','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('109','user','2',',0,','0','productManager','login','2012-06-05 11:14:43','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('110','product','2',',2,','0','productManager','opened','2012-06-05 11:15:20','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('111','user','1',',0,','0','admin','login','2018-08-02 18:28:50','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('112','user','1',',0,','0','admin','logout','2018-08-02 18:29:02','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('113','user','1',',0,','0','admin','logout','2018-08-02 18:29:04','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('114','user','1',',0,','0','admin','login','2018-08-02 18:31:04','','','0');
INSERT INTO `zt_action`(`id`,`objectType`,`objectID`,`product`,`project`,`actor`,`action`,`date`,`comment`,`extra`,`read`) VALUES ('115','user','1',',0,','0','admin','login','2018-08-03 08:56:35','','','0');
DROP TABLE IF EXISTS `zt_block`;
CREATE TABLE `zt_block` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account` char(30) NOT NULL,
  `module` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `source` varchar(20) NOT NULL,
  `block` varchar(20) NOT NULL,
  `params` text NOT NULL,
  `order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `grid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `accountModuleOrder` (`account`,`module`,`order`),
  KEY `block` (`account`,`module`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
INSERT INTO `zt_block`(`id`,`account`,`module`,`title`,`source`,`block`,`params`,`order`,`grid`,`height`,`hidden`) VALUES ('1','admin','my','流程图','','flowchart','','1','8','0','0');
INSERT INTO `zt_block`(`id`,`account`,`module`,`title`,`source`,`block`,`params`,`order`,`grid`,`height`,`hidden`) VALUES ('2','admin','my','最新动态','','dynamic','','2','4','0','0');
INSERT INTO `zt_block`(`id`,`account`,`module`,`title`,`source`,`block`,`params`,`order`,`grid`,`height`,`hidden`) VALUES ('3','admin','my','进行中的项目','project','list','{\"num\":15,\"orderBy\":\"id_desc\",\"type\":\"undone\"}','3','8','0','0');
INSERT INTO `zt_block`(`id`,`account`,`module`,`title`,`source`,`block`,`params`,`order`,`grid`,`height`,`hidden`) VALUES ('4','admin','my','我的待办','todo','list','{\"num\":\"20\"}','4','4','0','0');
INSERT INTO `zt_block`(`id`,`account`,`module`,`title`,`source`,`block`,`params`,`order`,`grid`,`height`,`hidden`) VALUES ('5','admin','my','指派给我','','assigntome','','5','8','0','0');
INSERT INTO `zt_block`(`id`,`account`,`module`,`title`,`source`,`block`,`params`,`order`,`grid`,`height`,`hidden`) VALUES ('6','admin','my','指派给我的需求','product','story','{\"num\":15,\"orderBy\":\"id_desc\",\"type\":\"assignedTo\"}','6','4','0','0');
INSERT INTO `zt_block`(`id`,`account`,`module`,`title`,`source`,`block`,`params`,`order`,`grid`,`height`,`hidden`) VALUES ('7','admin','my','未关闭的产品','product','list','{\"num\":15,\"type\":\"noclosed\"}','7','8','0','0');
INSERT INTO `zt_block`(`id`,`account`,`module`,`title`,`source`,`block`,`params`,`order`,`grid`,`height`,`hidden`) VALUES ('8','admin','my','指派给我的用例','qa','case','{\"num\":15,\"orderBy\":\"id_desc\",\"type\":\"assigntome\"}','8','4','0','0');
INSERT INTO `zt_block`(`id`,`account`,`module`,`title`,`source`,`block`,`params`,`order`,`grid`,`height`,`hidden`) VALUES ('9','admin','qa','指派给我的Bug','qa','bug','{\"num\":15,\"orderBy\":\"id_desc\",\"type\":\"assignedTo\"}','1','4','0','0');
INSERT INTO `zt_block`(`id`,`account`,`module`,`title`,`source`,`block`,`params`,`order`,`grid`,`height`,`hidden`) VALUES ('10','admin','qa','指派给我的用例','qa','case','{\"num\":15,\"orderBy\":\"id_desc\",\"type\":\"assigntome\"}','2','4','0','0');
INSERT INTO `zt_block`(`id`,`account`,`module`,`title`,`source`,`block`,`params`,`order`,`grid`,`height`,`hidden`) VALUES ('11','admin','qa','待测版本列表','qa','testtask','{\"num\":15,\"orderBy\":\"id_desc\",\"type\":\"wait\"}','3','4','0','0');
DROP TABLE IF EXISTS `zt_branch`;
CREATE TABLE `zt_branch` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product` (`product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_bug`;
CREATE TABLE `zt_bug` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `branch` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `module` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `project` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plan` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `story` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `storyVersion` smallint(6) NOT NULL DEFAULT '1',
  `task` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `toTask` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `toStory` mediumint(8) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `severity` tinyint(4) NOT NULL DEFAULT '0',
  `pri` tinyint(3) unsigned NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT '',
  `os` varchar(30) NOT NULL DEFAULT '',
  `browser` varchar(30) NOT NULL DEFAULT '',
  `hardware` varchar(30) NOT NULL,
  `found` varchar(30) NOT NULL DEFAULT '',
  `steps` text NOT NULL,
  `status` enum('active','resolved','closed') NOT NULL DEFAULT 'active',
  `color` char(7) NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `activatedCount` smallint(6) NOT NULL,
  `activatedDate` datetime NOT NULL,
  `mailto` text,
  `openedBy` varchar(30) NOT NULL DEFAULT '',
  `openedDate` datetime NOT NULL,
  `openedBuild` varchar(255) NOT NULL,
  `assignedTo` varchar(30) NOT NULL DEFAULT '',
  `assignedDate` datetime NOT NULL,
  `deadline` date NOT NULL,
  `resolvedBy` varchar(30) NOT NULL DEFAULT '',
  `resolution` varchar(30) NOT NULL DEFAULT '',
  `resolvedBuild` varchar(30) NOT NULL DEFAULT '',
  `resolvedDate` datetime NOT NULL,
  `closedBy` varchar(30) NOT NULL DEFAULT '',
  `closedDate` datetime NOT NULL,
  `duplicateBug` mediumint(8) unsigned NOT NULL,
  `linkBug` varchar(255) NOT NULL,
  `case` mediumint(8) unsigned NOT NULL,
  `caseVersion` smallint(6) NOT NULL DEFAULT '1',
  `result` mediumint(8) unsigned NOT NULL,
  `testtask` mediumint(8) unsigned NOT NULL,
  `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  `lastEditedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `bug` (`product`,`module`,`project`,`assignedTo`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
INSERT INTO `zt_bug`(`id`,`product`,`branch`,`module`,`project`,`plan`,`story`,`storyVersion`,`task`,`toTask`,`toStory`,`title`,`keywords`,`severity`,`pri`,`type`,`os`,`browser`,`hardware`,`found`,`steps`,`status`,`color`,`confirmed`,`activatedCount`,`activatedDate`,`mailto`,`openedBy`,`openedDate`,`openedBuild`,`assignedTo`,`assignedDate`,`deadline`,`resolvedBy`,`resolution`,`resolvedBuild`,`resolvedDate`,`closedBy`,`closedDate`,`duplicateBug`,`linkBug`,`case`,`caseVersion`,`result`,`testtask`,`lastEditedBy`,`lastEditedDate`,`deleted`) VALUES ('1','1','0','8','1','0','1','1','1','0','0','首页页面问题','','3','0','interface','','','','','<p>[步骤]进入首页</p>
<p>[结果]出现乱码&nbsp;&nbsp;&nbsp;&nbsp;</p>
<p>[期望]正常显示</p>','active','','0','0','0000-00-00 00:00:00','','tester1','2012-06-05 10:56:11','trunk','dev1','2012-06-05 10:56:11','0000-00-00','','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','0','','0','1','0','0','','0000-00-00 00:00:00','0');
INSERT INTO `zt_bug`(`id`,`product`,`branch`,`module`,`project`,`plan`,`story`,`storyVersion`,`task`,`toTask`,`toStory`,`title`,`keywords`,`severity`,`pri`,`type`,`os`,`browser`,`hardware`,`found`,`steps`,`status`,`color`,`confirmed`,`activatedCount`,`activatedDate`,`mailto`,`openedBy`,`openedDate`,`openedBuild`,`assignedTo`,`assignedDate`,`deadline`,`resolvedBy`,`resolution`,`resolvedBuild`,`resolvedDate`,`closedBy`,`closedDate`,`duplicateBug`,`linkBug`,`case`,`caseVersion`,`result`,`testtask`,`lastEditedBy`,`lastEditedDate`,`deleted`) VALUES ('2','1','0','9','1','0','2','1','4','0','0','新闻中心页面问题','','3','0','codeerror','','','','','<p>[步骤]进入新闻中心</p>
<p>[结果]页面出现乱码</p>
<p>[期望]正常显示</p>','active','','0','0','0000-00-00 00:00:00','','tester1','2012-06-05 10:57:11','trunk','dev2','2012-06-05 10:57:11','0000-00-00','','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','0','','0','1','0','0','','0000-00-00 00:00:00','0');
INSERT INTO `zt_bug`(`id`,`product`,`branch`,`module`,`project`,`plan`,`story`,`storyVersion`,`task`,`toTask`,`toStory`,`title`,`keywords`,`severity`,`pri`,`type`,`os`,`browser`,`hardware`,`found`,`steps`,`status`,`color`,`confirmed`,`activatedCount`,`activatedDate`,`mailto`,`openedBy`,`openedDate`,`openedBuild`,`assignedTo`,`assignedDate`,`deadline`,`resolvedBy`,`resolution`,`resolvedBuild`,`resolvedDate`,`closedBy`,`closedDate`,`duplicateBug`,`linkBug`,`case`,`caseVersion`,`result`,`testtask`,`lastEditedBy`,`lastEditedDate`,`deleted`) VALUES ('3','1','0','10','1','0','3','2','6','0','0','成果展示页面问题','','3','0','codeerror','','','','','<p>[步骤]进入成果展示&nbsp;&nbsp;&nbsp;&nbsp;</p>
<p>[结果]乱码</p>
<p>[期望]正常显示</p>','active','','0','0','0000-00-00 00:00:00','','tester2','2012-06-05 10:58:22','trunk','dev3','2012-06-05 10:58:22','0000-00-00','','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','0','','0','1','0','0','','0000-00-00 00:00:00','0');
INSERT INTO `zt_bug`(`id`,`product`,`branch`,`module`,`project`,`plan`,`story`,`storyVersion`,`task`,`toTask`,`toStory`,`title`,`keywords`,`severity`,`pri`,`type`,`os`,`browser`,`hardware`,`found`,`steps`,`status`,`color`,`confirmed`,`activatedCount`,`activatedDate`,`mailto`,`openedBy`,`openedDate`,`openedBuild`,`assignedTo`,`assignedDate`,`deadline`,`resolvedBy`,`resolution`,`resolvedBuild`,`resolvedDate`,`closedBy`,`closedDate`,`duplicateBug`,`linkBug`,`case`,`caseVersion`,`result`,`testtask`,`lastEditedBy`,`lastEditedDate`,`deleted`) VALUES ('4','1','0','11','1','0','4','1','9','0','0','售后服务页面问题','','3','0','codeerror','','','','','<p>[步骤]进入售后服务</p>
<p>[结果]乱码</p>
<p>[期望]正常显示</p>','active','','0','0','0000-00-00 00:00:00','','tester3','2012-06-05 11:00:19','trunk','dev1','2012-06-05 11:00:19','0000-00-00','','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','0','','0','1','0','0','','0000-00-00 00:00:00','0');
DROP TABLE IF EXISTS `zt_build`;
CREATE TABLE `zt_build` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `branch` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `project` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` char(150) NOT NULL,
  `scmPath` char(255) NOT NULL,
  `filePath` char(255) NOT NULL,
  `date` date NOT NULL,
  `stories` text NOT NULL,
  `bugs` text NOT NULL,
  `builder` char(30) NOT NULL DEFAULT '',
  `desc` text NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `build` (`product`,`project`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
INSERT INTO `zt_build`(`id`,`product`,`branch`,`project`,`name`,`scmPath`,`filePath`,`date`,`stories`,`bugs`,`builder`,`desc`,`deleted`) VALUES ('1','1','0','1','第一期版本','','','2012-06-05','3,2,1,4','','projectManager','','0');
DROP TABLE IF EXISTS `zt_burn`;
CREATE TABLE `zt_burn` (
  `project` mediumint(8) unsigned NOT NULL,
  `date` date NOT NULL,
  `estimate` float NOT NULL,
  `left` float NOT NULL,
  `consumed` float NOT NULL,
  PRIMARY KEY (`project`,`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `zt_burn`(`project`,`date`,`estimate`,`left`,`consumed`) VALUES ('1','2012-06-05','0','0','38');
DROP TABLE IF EXISTS `zt_case`;
CREATE TABLE `zt_case` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `branch` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `lib` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `module` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `path` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `story` mediumint(30) unsigned NOT NULL DEFAULT '0',
  `storyVersion` smallint(6) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `precondition` text NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `pri` tinyint(3) unsigned NOT NULL DEFAULT '3',
  `type` char(30) NOT NULL DEFAULT '1',
  `stage` varchar(255) NOT NULL,
  `howRun` varchar(30) NOT NULL,
  `scriptedBy` varchar(30) NOT NULL,
  `scriptedDate` date NOT NULL,
  `scriptStatus` varchar(30) NOT NULL,
  `scriptLocation` varchar(255) NOT NULL,
  `status` char(30) NOT NULL DEFAULT '1',
  `color` char(7) NOT NULL,
  `frequency` enum('1','2','3') NOT NULL DEFAULT '1',
  `order` tinyint(30) unsigned NOT NULL DEFAULT '0',
  `openedBy` char(30) NOT NULL DEFAULT '',
  `openedDate` datetime NOT NULL,
  `reviewedBy` varchar(255) NOT NULL,
  `reviewedDate` date NOT NULL,
  `lastEditedBy` char(30) NOT NULL DEFAULT '',
  `lastEditedDate` datetime NOT NULL,
  `version` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `linkCase` varchar(255) NOT NULL,
  `fromBug` mediumint(8) unsigned NOT NULL,
  `fromCaseID` mediumint(8) unsigned NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `lastRunner` varchar(30) NOT NULL,
  `lastRunDate` datetime NOT NULL,
  `lastRunResult` char(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `case` (`product`,`module`,`story`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
INSERT INTO `zt_case`(`id`,`product`,`branch`,`lib`,`module`,`path`,`story`,`storyVersion`,`title`,`precondition`,`keywords`,`pri`,`type`,`stage`,`howRun`,`scriptedBy`,`scriptedDate`,`scriptStatus`,`scriptLocation`,`status`,`color`,`frequency`,`order`,`openedBy`,`openedDate`,`reviewedBy`,`reviewedDate`,`lastEditedBy`,`lastEditedDate`,`version`,`linkCase`,`fromBug`,`fromCaseID`,`deleted`,`lastRunner`,`lastRunDate`,`lastRunResult`) VALUES ('1','1','0','0','0','0','4','1','售后服务的测试用例','','','1','feature','feature','','','0000-00-00','','','normal','','1','0','tester3','2012-06-05 11:02:18','','0000-00-00','tester3','2012-06-05 11:02:46','1','','0','0','0','testManager','2012-06-05 11:11:00','pass');
INSERT INTO `zt_case`(`id`,`product`,`branch`,`lib`,`module`,`path`,`story`,`storyVersion`,`title`,`precondition`,`keywords`,`pri`,`type`,`stage`,`howRun`,`scriptedBy`,`scriptedDate`,`scriptStatus`,`scriptLocation`,`status`,`color`,`frequency`,`order`,`openedBy`,`openedDate`,`reviewedBy`,`reviewedDate`,`lastEditedBy`,`lastEditedDate`,`version`,`linkCase`,`fromBug`,`fromCaseID`,`deleted`,`lastRunner`,`lastRunDate`,`lastRunResult`) VALUES ('2','1','0','0','0','0','1','1','首页的测试用例','','','3','feature','','','','0000-00-00','','','normal','','1','0','tester1','2012-06-05 11:03:28','','0000-00-00','','0000-00-00 00:00:00','1','','0','0','0','testManager','2012-06-05 11:11:05','pass');
INSERT INTO `zt_case`(`id`,`product`,`branch`,`lib`,`module`,`path`,`story`,`storyVersion`,`title`,`precondition`,`keywords`,`pri`,`type`,`stage`,`howRun`,`scriptedBy`,`scriptedDate`,`scriptStatus`,`scriptLocation`,`status`,`color`,`frequency`,`order`,`openedBy`,`openedDate`,`reviewedBy`,`reviewedDate`,`lastEditedBy`,`lastEditedDate`,`version`,`linkCase`,`fromBug`,`fromCaseID`,`deleted`,`lastRunner`,`lastRunDate`,`lastRunResult`) VALUES ('3','1','0','0','0','0','2','1','新闻中心的测试用例','','','3','feature','feature','','','0000-00-00','','','normal','','1','0','tester1','2012-06-05 11:03:54','','0000-00-00','','0000-00-00 00:00:00','1','','0','0','0','testManager','2012-06-05 11:11:07','pass');
INSERT INTO `zt_case`(`id`,`product`,`branch`,`lib`,`module`,`path`,`story`,`storyVersion`,`title`,`precondition`,`keywords`,`pri`,`type`,`stage`,`howRun`,`scriptedBy`,`scriptedDate`,`scriptStatus`,`scriptLocation`,`status`,`color`,`frequency`,`order`,`openedBy`,`openedDate`,`reviewedBy`,`reviewedDate`,`lastEditedBy`,`lastEditedDate`,`version`,`linkCase`,`fromBug`,`fromCaseID`,`deleted`,`lastRunner`,`lastRunDate`,`lastRunResult`) VALUES ('4','1','0','0','0','0','3','2','成果展示测试用例','','','3','feature','feature','','','0000-00-00','','','normal','','1','0','tester2','2012-06-05 11:04:48','','0000-00-00','','0000-00-00 00:00:00','1','','0','0','0','testManager','2012-06-05 11:11:08','pass');
DROP TABLE IF EXISTS `zt_casestep`;
CREATE TABLE `zt_casestep` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `case` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `version` smallint(3) unsigned NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL DEFAULT 'step',
  `desc` text NOT NULL,
  `expect` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `case` (`case`,`version`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
INSERT INTO `zt_casestep`(`id`,`parent`,`case`,`version`,`type`,`desc`,`expect`) VALUES ('1','0','1','1','step','进入首页','正常显示');
INSERT INTO `zt_casestep`(`id`,`parent`,`case`,`version`,`type`,`desc`,`expect`) VALUES ('2','0','2','1','step','进入首页','正常显示');
INSERT INTO `zt_casestep`(`id`,`parent`,`case`,`version`,`type`,`desc`,`expect`) VALUES ('3','0','3','1','step','进入新闻中心','正常显示');
INSERT INTO `zt_casestep`(`id`,`parent`,`case`,`version`,`type`,`desc`,`expect`) VALUES ('4','0','4','1','step','进入成果展示','正常显示');
DROP TABLE IF EXISTS `zt_company`;
CREATE TABLE `zt_company` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(120) DEFAULT NULL,
  `phone` char(20) DEFAULT NULL,
  `fax` char(20) DEFAULT NULL,
  `address` char(120) DEFAULT NULL,
  `zipcode` char(10) DEFAULT NULL,
  `website` char(120) DEFAULT NULL,
  `backyard` char(120) DEFAULT NULL,
  `guest` enum('1','0') NOT NULL DEFAULT '0',
  `admins` char(255) DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
INSERT INTO `zt_company`(`id`,`name`,`phone`,`fax`,`address`,`zipcode`,`website`,`backyard`,`guest`,`admins`,`deleted`) VALUES ('1','路飞','','','','','','','0',',admin,','0');
DROP TABLE IF EXISTS `zt_config`;
CREATE TABLE `zt_config` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `owner` char(30) NOT NULL DEFAULT '',
  `module` varchar(30) NOT NULL,
  `section` char(30) NOT NULL DEFAULT '',
  `key` char(30) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`owner`,`module`,`section`,`key`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
INSERT INTO `zt_config`(`id`,`owner`,`module`,`section`,`key`,`value`) VALUES ('1','system','common','global','showDemoUsers','1');
INSERT INTO `zt_config`(`id`,`owner`,`module`,`section`,`key`,`value`) VALUES ('2','system','common','global','version','9.8.3');
INSERT INTO `zt_config`(`id`,`owner`,`module`,`section`,`key`,`value`) VALUES ('3','system','common','global','flow','full');
INSERT INTO `zt_config`(`id`,`owner`,`module`,`section`,`key`,`value`) VALUES ('4','system','common','safe','mode','1');
INSERT INTO `zt_config`(`id`,`owner`,`module`,`section`,`key`,`value`) VALUES ('5','system','common','safe','changeWeak','1');
INSERT INTO `zt_config`(`id`,`owner`,`module`,`section`,`key`,`value`) VALUES ('6','system','common','global','cron','1');
INSERT INTO `zt_config`(`id`,`owner`,`module`,`section`,`key`,`value`) VALUES ('7','admin','my','common','blockInited','1');
INSERT INTO `zt_config`(`id`,`owner`,`module`,`section`,`key`,`value`) VALUES ('8','admin','common','global','novice','0');
INSERT INTO `zt_config`(`id`,`owner`,`module`,`section`,`key`,`value`) VALUES ('9','system','cron','run','status','running');
INSERT INTO `zt_config`(`id`,`owner`,`module`,`section`,`key`,`value`) VALUES ('10','system','common','global','sn','2f4d0cf32bca22173bcd82614f09f00b');
INSERT INTO `zt_config`(`id`,`owner`,`module`,`section`,`key`,`value`) VALUES ('11','admin','qa','','homepage','index');
INSERT INTO `zt_config`(`id`,`owner`,`module`,`section`,`key`,`value`) VALUES ('12','admin','qa','common','blockInited','1');
INSERT INTO `zt_config`(`id`,`owner`,`module`,`section`,`key`,`value`) VALUES ('13','admin','project','','homepage','browse');
DROP TABLE IF EXISTS `zt_cron`;
CREATE TABLE `zt_cron` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `m` varchar(20) NOT NULL,
  `h` varchar(20) NOT NULL,
  `dom` varchar(20) NOT NULL,
  `mon` varchar(20) NOT NULL,
  `dow` varchar(20) NOT NULL,
  `command` text NOT NULL,
  `remark` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `buildin` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(20) NOT NULL,
  `lastTime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lastTime` (`lastTime`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
INSERT INTO `zt_cron`(`id`,`m`,`h`,`dom`,`mon`,`dow`,`command`,`remark`,`type`,`buildin`,`status`,`lastTime`) VALUES ('1','*','*','*','*','*','','监控定时任务','zentao','1','normal','2018-08-04 10:59:48');
INSERT INTO `zt_cron`(`id`,`m`,`h`,`dom`,`mon`,`dow`,`command`,`remark`,`type`,`buildin`,`status`,`lastTime`) VALUES ('2','30','23','*','*','*','moduleName=project&methodName=computeburn','更新燃尽图','zentao','1','normal','2018-08-04 10:59:48');
INSERT INTO `zt_cron`(`id`,`m`,`h`,`dom`,`mon`,`dow`,`command`,`remark`,`type`,`buildin`,`status`,`lastTime`) VALUES ('3','0','8','*','*','*','moduleName=report&methodName=remind','每日任务提醒','zentao','1','normal','2018-08-04 10:59:48');
INSERT INTO `zt_cron`(`id`,`m`,`h`,`dom`,`mon`,`dow`,`command`,`remark`,`type`,`buildin`,`status`,`lastTime`) VALUES ('4','*/5','*','*','*','*','moduleName=svn&methodName=run','同步SVN','zentao','1','stop','0000-00-00 00:00:00');
INSERT INTO `zt_cron`(`id`,`m`,`h`,`dom`,`mon`,`dow`,`command`,`remark`,`type`,`buildin`,`status`,`lastTime`) VALUES ('5','*/5','*','*','*','*','moduleName=git&methodName=run','同步GIT','zentao','1','stop','0000-00-00 00:00:00');
INSERT INTO `zt_cron`(`id`,`m`,`h`,`dom`,`mon`,`dow`,`command`,`remark`,`type`,`buildin`,`status`,`lastTime`) VALUES ('6','30','0','*','*','*','moduleName=backup&methodName=backup','备份数据和附件','zentao','1','running','2018-08-04 10:59:48');
INSERT INTO `zt_cron`(`id`,`m`,`h`,`dom`,`mon`,`dow`,`command`,`remark`,`type`,`buildin`,`status`,`lastTime`) VALUES ('7','*/5','*','*','*','*','moduleName=mail&methodName=asyncSend','异步发信','zentao','1','normal','2018-08-03 10:25:38');
INSERT INTO `zt_cron`(`id`,`m`,`h`,`dom`,`mon`,`dow`,`command`,`remark`,`type`,`buildin`,`status`,`lastTime`) VALUES ('8','*/5','*','*','*','*','moduleName=webhook&methodName=asyncSend','异步发送Webhook','zentao','1','normal','2018-08-03 10:25:39');
INSERT INTO `zt_cron`(`id`,`m`,`h`,`dom`,`mon`,`dow`,`command`,`remark`,`type`,`buildin`,`status`,`lastTime`) VALUES ('9','*/5','*','*','*','*','moduleName=admin&methodName=deleteLog','删除过期日志','zentao','1','normal','2018-08-03 10:25:39');
INSERT INTO `zt_cron`(`id`,`m`,`h`,`dom`,`mon`,`dow`,`command`,`remark`,`type`,`buildin`,`status`,`lastTime`) VALUES ('10','1','1','*','*','*','moduleName=todo&methodName=createCycle','生成周期性待办','zentao','1','normal','2018-08-03 08:01:36');
DROP TABLE IF EXISTS `zt_dept`;
CREATE TABLE `zt_dept` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(60) NOT NULL,
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `path` char(255) NOT NULL DEFAULT '',
  `grade` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `position` char(30) NOT NULL DEFAULT '',
  `function` char(255) NOT NULL DEFAULT '',
  `manager` char(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `dept` (`parent`,`path`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
INSERT INTO `zt_dept`(`id`,`name`,`parent`,`path`,`grade`,`order`,`position`,`function`,`manager`) VALUES ('1','经理','0',',1,','1','0','','','');
INSERT INTO `zt_dept`(`id`,`name`,`parent`,`path`,`grade`,`order`,`position`,`function`,`manager`) VALUES ('2','开发','0',',2,','1','0','','','');
INSERT INTO `zt_dept`(`id`,`name`,`parent`,`path`,`grade`,`order`,`position`,`function`,`manager`) VALUES ('3','测试','0',',3,','1','0','','','');
INSERT INTO `zt_dept`(`id`,`name`,`parent`,`path`,`grade`,`order`,`position`,`function`,`manager`) VALUES ('4','市场','0',',4,','1','0','','','');
INSERT INTO `zt_dept`(`id`,`name`,`parent`,`path`,`grade`,`order`,`position`,`function`,`manager`) VALUES ('5','产品','1',',1,5,','2','0','','','');
INSERT INTO `zt_dept`(`id`,`name`,`parent`,`path`,`grade`,`order`,`position`,`function`,`manager`) VALUES ('6','项目','1',',1,6,','2','0','','','');
INSERT INTO `zt_dept`(`id`,`name`,`parent`,`path`,`grade`,`order`,`position`,`function`,`manager`) VALUES ('7','编程','2',',2,7,','2','0','','','');
INSERT INTO `zt_dept`(`id`,`name`,`parent`,`path`,`grade`,`order`,`position`,`function`,`manager`) VALUES ('8','美工','2',',2,8,','2','0','','','');
DROP TABLE IF EXISTS `zt_doc`;
CREATE TABLE `zt_doc` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL,
  `project` mediumint(8) unsigned NOT NULL,
  `lib` varchar(30) NOT NULL,
  `module` varchar(30) NOT NULL,
  `title` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `type` varchar(30) NOT NULL,
  `views` smallint(5) unsigned NOT NULL,
  `addedBy` varchar(30) NOT NULL,
  `addedDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `acl` varchar(10) NOT NULL DEFAULT 'open',
  `groups` varchar(255) NOT NULL,
  `users` text NOT NULL,
  `version` smallint(5) unsigned NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `doc` (`product`,`project`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_doccontent`;
CREATE TABLE `zt_doccontent` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `doc` mediumint(8) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `digest` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `files` text NOT NULL,
  `type` varchar(10) NOT NULL,
  `version` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `doc_version` (`doc`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_doclib`;
CREATE TABLE `zt_doclib` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL,
  `project` mediumint(8) unsigned NOT NULL,
  `name` varchar(60) NOT NULL,
  `acl` varchar(10) NOT NULL DEFAULT 'open',
  `groups` varchar(255) NOT NULL,
  `users` text NOT NULL,
  `main` enum('0','1') NOT NULL DEFAULT '0',
  `order` tinyint(5) unsigned NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
INSERT INTO `zt_doclib`(`id`,`product`,`project`,`name`,`acl`,`groups`,`users`,`main`,`order`,`deleted`) VALUES ('1','1','0','产品库','open','','','1','0','0');
INSERT INTO `zt_doclib`(`id`,`product`,`project`,`name`,`acl`,`groups`,`users`,`main`,`order`,`deleted`) VALUES ('2','2','0','产品库','open','','','1','0','0');
INSERT INTO `zt_doclib`(`id`,`product`,`project`,`name`,`acl`,`groups`,`users`,`main`,`order`,`deleted`) VALUES ('3','0','1','项目库','open','','','1','0','0');
INSERT INTO `zt_doclib`(`id`,`product`,`project`,`name`,`acl`,`groups`,`users`,`main`,`order`,`deleted`) VALUES ('4','0','2','项目库','open','','','1','0','0');
DROP TABLE IF EXISTS `zt_effort`;
CREATE TABLE `zt_effort` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user` char(30) NOT NULL DEFAULT '',
  `todo` enum('1','0') NOT NULL DEFAULT '1',
  `date` date NOT NULL,
  `begin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` enum('1','2','3') NOT NULL DEFAULT '1',
  `idvalue` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` char(30) NOT NULL DEFAULT '',
  `desc` char(255) NOT NULL DEFAULT '',
  `status` enum('1','2','3') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_entry`;
CREATE TABLE `zt_entry` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(20) NOT NULL,
  `key` varchar(32) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_extension`;
CREATE TABLE `zt_extension` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `code` varchar(30) NOT NULL,
  `version` varchar(50) NOT NULL,
  `author` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `license` text NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'extension',
  `site` varchar(150) NOT NULL,
  `zentaoCompatible` varchar(100) NOT NULL,
  `installedTime` datetime NOT NULL,
  `depends` varchar(100) NOT NULL,
  `dirs` mediumtext NOT NULL,
  `files` mediumtext NOT NULL,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `extension` (`name`,`installedTime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_file`;
CREATE TABLE `zt_file` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pathname` char(50) NOT NULL,
  `title` char(90) NOT NULL,
  `extension` char(30) NOT NULL,
  `size` int(10) unsigned NOT NULL DEFAULT '0',
  `objectType` char(30) NOT NULL,
  `objectID` mediumint(9) NOT NULL,
  `addedBy` char(30) NOT NULL DEFAULT '',
  `addedDate` datetime NOT NULL,
  `downloads` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `extra` varchar(255) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `file` (`objectType`,`objectID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_group`;
CREATE TABLE `zt_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL,
  `role` char(30) NOT NULL DEFAULT '',
  `desc` char(255) NOT NULL DEFAULT '',
  `acl` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
INSERT INTO `zt_group`(`id`,`name`,`role`,`desc`,`acl`) VALUES ('1','管理员','admin','系统管理员','');
INSERT INTO `zt_group`(`id`,`name`,`role`,`desc`,`acl`) VALUES ('2','研发','dev','研发人员','');
INSERT INTO `zt_group`(`id`,`name`,`role`,`desc`,`acl`) VALUES ('3','测试','qa','测试人员','');
INSERT INTO `zt_group`(`id`,`name`,`role`,`desc`,`acl`) VALUES ('4','项目经理','pm','项目经理','');
INSERT INTO `zt_group`(`id`,`name`,`role`,`desc`,`acl`) VALUES ('5','产品经理','po','产品经理','');
INSERT INTO `zt_group`(`id`,`name`,`role`,`desc`,`acl`) VALUES ('6','研发主管','td','研发主管','');
INSERT INTO `zt_group`(`id`,`name`,`role`,`desc`,`acl`) VALUES ('7','产品主管','pd','产品主管','');
INSERT INTO `zt_group`(`id`,`name`,`role`,`desc`,`acl`) VALUES ('8','测试主管','qd','测试主管','');
INSERT INTO `zt_group`(`id`,`name`,`role`,`desc`,`acl`) VALUES ('9','高层管理','top','高层管理','');
INSERT INTO `zt_group`(`id`,`name`,`role`,`desc`,`acl`) VALUES ('10','其他','others','其他','');
INSERT INTO `zt_group`(`id`,`name`,`role`,`desc`,`acl`) VALUES ('11','guest','guest','For guest','');
INSERT INTO `zt_group`(`id`,`name`,`role`,`desc`,`acl`) VALUES ('12','受限用户','limited','受限用户分组(只能编辑与自己相关的内容)','');
DROP TABLE IF EXISTS `zt_grouppriv`;
CREATE TABLE `zt_grouppriv` (
  `group` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `module` char(30) NOT NULL DEFAULT '',
  `method` char(30) NOT NULL DEFAULT '',
  UNIQUE KEY `group` (`group`,`module`,`method`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','action','editComment');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','action','hideAll');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','action','hideOne');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','action','trash');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','action','undelete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','admin','checkDB');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','admin','checkWeak');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','admin','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','admin','safe');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','api','debug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','api','getModel');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','api','sql');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','backup','backup');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','backup','change');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','backup','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','backup','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','backup','restore');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','branch','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','branch','manage');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','branch','sort');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','batchActivate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','batchAssignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','batchClose');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','batchConfirm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','batchResolve');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','confirmBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','deleteTemplate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','linkBugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','resolve');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','saveTemplate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','unlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','bug','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','build','batchUnlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','build','batchUnlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','build','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','build','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','build','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','build','linkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','build','linkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','build','unlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','build','unlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','build','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','company','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','company','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','company','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','company','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','company','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','convert','checkBugFree');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','convert','checkConfig');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','convert','checkRedmine');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','convert','convertBugFree');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','convert','convertRedmine');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','convert','execute');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','convert','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','convert','selectSource');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','convert','setBugfree');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','convert','setConfig');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','convert','setRedmine');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','cron','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','cron','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','cron','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','cron','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','cron','toggle');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','cron','turnon');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','custom','flow');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','custom','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','custom','restore');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','custom','set');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','custom','working');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','dept','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','dept','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','dept','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','dept','manageChild');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','dept','updateOrder');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','dev','api');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','dev','db');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','doc','allLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','doc','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','doc','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','doc','createLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','doc','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','doc','deleteLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','doc','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','doc','editLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','doc','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','doc','objectLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','doc','showFiles');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','doc','sort');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','doc','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','editor','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','editor','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','editor','extend');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','editor','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','editor','newPage');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','editor','save');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','extension','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','extension','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','extension','deactivate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','extension','erase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','extension','install');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','extension','obtain');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','extension','structure');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','extension','uninstall');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','extension','upgrade');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','extension','upload');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','file','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','file','download');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','file','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','file','uploadImages');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','git','apiSync');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','git','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','git','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','group','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','group','copy');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','group','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','group','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','group','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','group','manageMember');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','group','managePriv');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','group','manageView');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','index','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','mail','batchDelete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','mail','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','mail','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','mail','detect');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','mail','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','mail','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','mail','resend');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','mail','reset');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','mail','save');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','mail','test');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','message','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','message','setting');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','misc','ping');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','my','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','my','changePassword');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','my','deleteContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','my','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','my','editProfile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','my','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','my','manageContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','my','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','my','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','my','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','my','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','my','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','my','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','my','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','my','unbind');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','product','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','product','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','product','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','product','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','product','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','product','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','product','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','product','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','product','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','product','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','product','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','product','order');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','product','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','product','roadmap');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','product','updateOrder');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','product','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','productplan','batchUnlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','productplan','batchUnlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','productplan','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','productplan','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','productplan','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','productplan','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','productplan','linkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','productplan','linkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','productplan','unlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','productplan','unlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','productplan','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','batchedit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','batchUnlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','burn');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','burnData');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','computeBurn');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','grouptask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','importBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','importPlanStories');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','importtask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','kanban');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','linkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','manageMembers');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','manageProducts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','order');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','putoff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','start');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','suspend');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','team');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','testtask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','tree');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','unlinkMember');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','unlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','updateOrder');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','project','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','qa','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','release','batchUnlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','release','batchUnlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','release','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','release','changeStatus');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','release','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','release','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','release','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','release','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','release','linkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','release','linkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','release','unlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','release','unlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','release','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','report','bugAssign');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','report','bugCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','report','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','report','productSummary');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','report','projectDeviation');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','report','workload');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','search','buildForm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','search','buildQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','search','deleteQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','search','saveQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','search','select');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','batchAssignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','batchChangeBranch');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','batchChangePlan');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','batchChangeStage');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','batchClose');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','batchReview');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','change');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','linkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','review');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','tasks');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','unlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','story','zeroCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','svn','apiSync');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','svn','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','svn','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','batchAssignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','batchClose');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','cancel');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','deleteEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','editEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','finish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','pause');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','recordEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','restart');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','start');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','task','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','batchCaseTypeChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','batchConfirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','batchDelete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','batchReview');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','confirmChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','createBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','exportTemplet');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','import');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','importFromLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','linkCases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','review');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','showImport');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','unlinkCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testcase','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testreport','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testreport','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testreport','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testreport','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testreport','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testsuite','batchCreateCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testsuite','batchUnlinkCases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testsuite','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testsuite','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testsuite','createCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testsuite','createLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testsuite','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testsuite','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testsuite','exportTemplet');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testsuite','import');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testsuite','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testsuite','library');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testsuite','libView');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testsuite','linkCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testsuite','showImport');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testsuite','unlinkCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testsuite','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','batchAssign');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','batchRun');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','block');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','linkcase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','results');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','runcase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','start');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','unlinkcase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','testtask','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','todo','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','todo','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','todo','batchFinish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','todo','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','todo','createCycle');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','todo','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','todo','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','todo','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','todo','finish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','todo','import2Today');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','todo','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','tree','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','tree','browseTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','tree','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','tree','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','tree','fix');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','tree','manageChild');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','tree','updateOrder');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','deleteContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','manageContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','unbind');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','unlock');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('1','user','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','action','editComment');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','api','getModel');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','batchActivate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','batchAssignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','batchClose');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','batchConfirm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','batchResolve');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','confirmBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','deleteTemplate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','linkBugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','resolve');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','saveTemplate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','unlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','bug','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','build','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','build','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','build','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','build','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','company','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','company','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','company','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','company','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','doc','allLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','doc','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','doc','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','doc','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','doc','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','doc','objectLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','doc','showFiles');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','doc','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','file','download');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','file','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','git','apiSync');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','git','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','git','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','group','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','index','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','misc','ping');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','my','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','my','changePassword');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','my','deleteContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','my','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','my','editProfile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','my','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','my','manageContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','my','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','my','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','my','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','my','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','my','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','my','unbind');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','product','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','product','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','product','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','product','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','product','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','product','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','product','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','product','roadmap');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','product','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','productplan','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','productplan','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','burn');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','burnData');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','computeBurn');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','grouptask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','importBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','importtask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','kanban');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','team');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','testtask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','tree');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','project','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','qa','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','release','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','release','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','release','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','report','bugAssign');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','report','bugCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','report','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','report','productSummary');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','report','projectDeviation');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','report','workload');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','search','buildForm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','search','buildQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','search','deleteQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','search','saveQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','search','select');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','story','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','story','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','story','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','story','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','story','tasks');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','story','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','svn','apiSync');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','svn','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','svn','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','batchAssignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','batchClose');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','cancel');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','deleteEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','editEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','finish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','pause');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','recordEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','restart');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','start');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','task','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','testcase','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','testcase','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','testcase','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','testcase','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','testcase','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','testcase','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','testsuite','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','testsuite','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','testsuite','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','testtask','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','testtask','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','testtask','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','testtask','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','testtask','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','testtask','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','testtask','results');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','testtask','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','todo','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','todo','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','todo','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','todo','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','todo','batchFinish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','todo','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','todo','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','todo','createCycle');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','todo','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','todo','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','todo','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','todo','finish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','todo','import2Today');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','todo','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','user','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','user','deleteContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','user','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','user','manageContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','user','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','user','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','user','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','user','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','user','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','user','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','user','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('2','user','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','action','editComment');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','api','getModel');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','batchActivate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','batchClose');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','batchConfirm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','batchResolve');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','confirmBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','deleteTemplate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','linkBugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','resolve');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','saveTemplate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','unlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','bug','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','build','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','build','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','build','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','company','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','company','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','company','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','company','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','doc','allLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','doc','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','doc','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','doc','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','doc','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','doc','objectLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','doc','showFiles');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','doc','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','file','download');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','file','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','git','apiSync');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','git','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','git','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','group','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','index','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','misc','ping');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','my','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','my','changePassword');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','my','deleteContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','my','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','my','editProfile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','my','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','my','manageContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','my','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','my','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','my','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','my','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','my','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','my','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','my','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','my','unbind');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','product','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','product','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','product','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','product','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','product','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','product','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','product','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','product','roadmap');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','product','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','productplan','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','productplan','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','burn');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','burnData');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','computeBurn');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','grouptask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','importBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','importtask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','kanban');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','team');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','testtask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','tree');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','project','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','qa','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','release','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','release','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','release','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','report','bugAssign');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','report','bugCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','report','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','report','productSummary');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','report','projectDeviation');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','report','workload');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','search','buildForm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','search','buildQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','search','deleteQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','search','saveQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','search','select');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','story','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','story','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','story','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','story','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','story','tasks');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','story','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','story','zeroCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','svn','apiSync');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','svn','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','svn','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','batchAssignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','batchClose');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','cancel');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','deleteEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','editEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','finish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','pause');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','recordEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','restart');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','start');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','task','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','batchCaseTypeChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','batchConfirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','batchReview');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','confirmChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','createBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','exportTemplet');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','import');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','linkCases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','review');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','showImport');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','unlinkCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testcase','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testreport','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testreport','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testsuite','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testsuite','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testsuite','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testsuite','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testsuite','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testsuite','linkCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testsuite','unlinkCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testsuite','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testtask','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testtask','batchAssign');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testtask','batchRun');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testtask','block');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testtask','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testtask','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testtask','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testtask','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testtask','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testtask','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testtask','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testtask','linkcase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testtask','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testtask','results');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testtask','runcase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testtask','start');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testtask','unlinkcase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','testtask','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','todo','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','todo','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','todo','batchFinish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','todo','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','todo','createCycle');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','todo','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','todo','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','todo','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','todo','finish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','todo','import2Today');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','todo','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','user','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','user','deleteContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','user','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','user','manageContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','user','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','user','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','user','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','user','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','user','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','user','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','user','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('3','user','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','action','editComment');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','action','hideAll');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','action','hideOne');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','action','trash');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','action','undelete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','admin','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','api','getModel');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','batchActivate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','batchAssignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','batchClose');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','batchConfirm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','batchResolve');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','confirmBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','deleteTemplate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','linkBugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','resolve');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','saveTemplate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','unlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','bug','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','build','batchUnlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','build','batchUnlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','build','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','build','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','build','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','build','linkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','build','linkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','build','unlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','build','unlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','build','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','company','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','company','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','company','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','company','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','doc','allLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','doc','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','doc','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','doc','createLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','doc','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','doc','deleteLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','doc','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','doc','editLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','doc','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','doc','objectLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','doc','showFiles');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','doc','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','extension','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','extension','obtain');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','extension','structure');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','file','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','file','download');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','file','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','file','uploadImages');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','git','apiSync');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','git','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','git','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','group','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','index','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','misc','ping');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','my','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','my','changePassword');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','my','deleteContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','my','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','my','editProfile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','my','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','my','manageContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','my','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','my','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','my','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','my','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','my','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','my','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','my','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','my','unbind');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','product','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','product','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','product','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','product','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','product','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','product','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','product','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','product','roadmap');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','product','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','productplan','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','productplan','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','batchedit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','batchUnlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','burn');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','burnData');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','computeBurn');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','grouptask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','importBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','importPlanStories');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','importtask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','kanban');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','linkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','manageMembers');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','manageProducts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','order');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','putoff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','start');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','suspend');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','team');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','testtask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','tree');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','unlinkMember');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','unlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','updateOrder');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','project','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','qa','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','release','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','release','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','release','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','report','bugAssign');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','report','bugCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','report','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','report','productSummary');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','report','projectDeviation');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','report','workload');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','search','buildForm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','search','buildQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','search','deleteQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','search','saveQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','search','select');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','story','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','story','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','story','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','story','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','story','tasks');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','story','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','story','zeroCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','svn','apiSync');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','svn','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','svn','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','batchAssignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','batchClose');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','cancel');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','deleteEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','editEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','finish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','pause');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','recordEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','restart');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','start');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','task','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testcase','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testcase','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testcase','createBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testcase','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testcase','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testcase','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testcase','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testsuite','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testsuite','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testsuite','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testtask','batchAssign');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testtask','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testtask','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testtask','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testtask','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testtask','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testtask','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testtask','results');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','testtask','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','todo','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','todo','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','todo','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','todo','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','todo','batchFinish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','todo','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','todo','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','todo','createCycle');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','todo','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','todo','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','todo','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','todo','finish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','todo','import2Today');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','todo','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','tree','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','tree','browseTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','tree','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','tree','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','tree','fix');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','tree','manageChild');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','tree','updateOrder');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','user','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','user','deleteContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','user','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','user','manageContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','user','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','user','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','user','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','user','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','user','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','user','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','user','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('4','user','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','action','editComment');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','action','hideAll');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','action','hideOne');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','action','trash');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','action','undelete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','admin','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','api','getModel');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','branch','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','branch','manage');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','branch','sort');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','batchActivate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','batchAssignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','batchClose');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','batchConfirm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','batchResolve');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','confirmBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','deleteTemplate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','linkBugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','resolve');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','saveTemplate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','unlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','bug','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','build','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','company','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','company','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','company','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','company','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','doc','allLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','doc','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','doc','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','doc','createLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','doc','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','doc','deleteLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','doc','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','doc','editLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','doc','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','doc','objectLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','doc','showFiles');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','doc','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','extension','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','extension','obtain');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','extension','structure');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','file','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','file','download');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','file','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','file','uploadImages');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','git','apiSync');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','git','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','git','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','group','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','index','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','misc','ping');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','my','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','my','changePassword');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','my','deleteContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','my','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','my','editProfile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','my','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','my','manageContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','my','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','my','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','my','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','my','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','my','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','my','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','my','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','my','unbind');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','product','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','product','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','product','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','product','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','product','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','product','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','product','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','product','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','product','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','product','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','product','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','product','order');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','product','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','product','roadmap');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','product','updateOrder');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','product','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','productplan','batchUnlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','productplan','batchUnlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','productplan','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','productplan','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','productplan','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','productplan','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','productplan','linkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','productplan','linkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','productplan','unlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','productplan','unlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','productplan','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','batchedit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','batchUnlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','burn');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','burnData');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','computeBurn');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','grouptask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','importBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','importPlanStories');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','importtask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','kanban');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','linkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','manageMembers');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','manageProducts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','order');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','putoff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','start');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','suspend');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','team');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','testtask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','tree');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','unlinkMember');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','unlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','updateOrder');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','project','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','qa','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','release','batchUnlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','release','batchUnlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','release','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','release','changeStatus');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','release','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','release','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','release','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','release','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','release','linkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','release','linkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','release','unlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','release','unlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','release','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','report','bugAssign');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','report','bugCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','report','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','report','productSummary');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','report','projectDeviation');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','report','workload');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','search','buildForm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','search','buildQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','search','deleteQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','search','saveQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','search','select');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','batchChangeBranch');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','batchChangePlan');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','batchChangeStage');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','batchClose');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','batchReview');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','change');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','linkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','review');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','tasks');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','unlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','story','zeroCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','svn','apiSync');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','svn','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','svn','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','task','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','task','deleteEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','task','editEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','task','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','task','recordEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','task','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','task','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testcase','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testcase','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testcase','createBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testcase','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testcase','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testcase','importFromLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testcase','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testcase','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testsuite','batchCreateCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testsuite','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testsuite','createCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testsuite','createLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testsuite','exportTemplet');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testsuite','import');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testsuite','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testsuite','library');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testsuite','libView');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testsuite','showImport');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testsuite','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testtask','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testtask','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testtask','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testtask','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testtask','results');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','testtask','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','todo','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','todo','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','todo','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','todo','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','todo','batchFinish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','todo','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','todo','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','todo','createCycle');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','todo','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','todo','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','todo','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','todo','finish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','todo','import2Today');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','todo','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','tree','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','tree','browseTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','tree','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','tree','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','tree','fix');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','tree','manageChild');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','tree','updateOrder');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','user','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','user','deleteContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','user','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','user','manageContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','user','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','user','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','user','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','user','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','user','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','user','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','user','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('5','user','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','action','editComment');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','action','hideAll');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','action','hideOne');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','action','trash');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','action','undelete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','admin','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','api','getModel');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','batchActivate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','batchAssignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','batchClose');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','batchConfirm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','batchResolve');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','confirmBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','deleteTemplate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','linkBugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','resolve');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','saveTemplate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','unlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','bug','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','build','batchUnlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','build','batchUnlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','build','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','build','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','build','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','build','linkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','build','linkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','build','unlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','build','unlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','build','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','company','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','company','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','company','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','company','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','doc','allLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','doc','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','doc','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','doc','createLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','doc','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','doc','deleteLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','doc','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','doc','editLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','doc','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','doc','objectLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','doc','showFiles');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','doc','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','extension','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','extension','obtain');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','extension','structure');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','file','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','file','download');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','file','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','file','uploadImages');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','git','apiSync');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','git','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','git','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','group','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','index','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','misc','ping');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','my','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','my','changePassword');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','my','deleteContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','my','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','my','editProfile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','my','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','my','manageContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','my','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','my','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','my','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','my','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','my','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','my','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','my','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','my','unbind');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','product','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','product','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','product','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','product','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','product','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','product','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','product','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','product','roadmap');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','product','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','productplan','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','productplan','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','batchedit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','batchUnlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','burn');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','burnData');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','computeBurn');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','grouptask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','importBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','importPlanStories');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','importtask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','kanban');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','linkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','manageMembers');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','manageProducts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','order');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','putoff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','start');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','suspend');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','team');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','testtask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','tree');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','unlinkMember');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','unlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','updateOrder');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','project','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','qa','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','release','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','release','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','release','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','report','bugAssign');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','report','bugCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','report','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','report','productSummary');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','report','projectDeviation');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','report','workload');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','search','buildForm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','search','buildQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','search','deleteQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','search','saveQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','search','select');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','story','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','story','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','story','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','story','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','story','tasks');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','story','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','story','zeroCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','svn','apiSync');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','svn','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','svn','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','batchAssignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','batchClose');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','cancel');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','deleteEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','editEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','finish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','pause');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','recordEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','restart');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','start');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','task','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','testcase','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','testcase','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','testcase','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','testcase','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','testcase','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','testcase','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','testsuite','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','testsuite','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','testsuite','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','testtask','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','testtask','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','testtask','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','testtask','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','testtask','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','testtask','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','testtask','results');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','testtask','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','todo','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','todo','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','todo','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','todo','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','todo','batchFinish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','todo','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','todo','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','todo','createCycle');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','todo','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','todo','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','todo','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','todo','finish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','todo','import2Today');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','todo','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','tree','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','tree','browseTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','tree','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','tree','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','tree','fix');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','tree','manageChild');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','tree','updateOrder');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','user','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','user','deleteContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','user','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','user','manageContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','user','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','user','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','user','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','user','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','user','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','user','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','user','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('6','user','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','action','editComment');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','action','hideAll');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','action','hideOne');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','action','trash');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','action','undelete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','admin','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','api','getModel');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','branch','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','branch','manage');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','branch','sort');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','batchActivate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','batchClose');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','batchConfirm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','batchResolve');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','confirmBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','deleteTemplate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','linkBugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','resolve');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','saveTemplate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','unlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','bug','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','build','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','company','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','company','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','company','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','company','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','doc','allLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','doc','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','doc','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','doc','createLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','doc','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','doc','deleteLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','doc','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','doc','editLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','doc','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','doc','objectLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','doc','showFiles');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','doc','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','extension','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','extension','obtain');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','extension','structure');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','file','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','file','download');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','file','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','file','uploadImages');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','git','apiSync');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','git','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','git','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','group','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','index','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','misc','ping');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','my','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','my','changePassword');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','my','deleteContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','my','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','my','editProfile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','my','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','my','manageContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','my','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','my','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','my','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','my','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','my','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','my','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','my','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','my','unbind');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','product','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','product','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','product','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','product','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','product','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','product','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','product','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','product','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','product','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','product','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','product','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','product','order');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','product','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','product','roadmap');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','product','updateOrder');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','product','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','productplan','batchUnlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','productplan','batchUnlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','productplan','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','productplan','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','productplan','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','productplan','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','productplan','linkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','productplan','linkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','productplan','unlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','productplan','unlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','productplan','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','burn');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','burnData');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','grouptask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','importPlanStories');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','kanban');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','linkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','manageProducts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','team');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','testtask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','tree');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','unlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','project','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','qa','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','release','batchUnlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','release','batchUnlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','release','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','release','changeStatus');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','release','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','release','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','release','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','release','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','release','linkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','release','linkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','release','unlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','release','unlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','release','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','report','bugAssign');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','report','bugCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','report','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','report','productSummary');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','report','projectDeviation');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','report','workload');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','search','buildForm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','search','buildQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','search','deleteQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','search','saveQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','search','select');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','batchAssignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','batchChangeBranch');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','batchChangePlan');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','batchChangeStage');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','batchClose');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','batchReview');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','change');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','linkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','review');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','tasks');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','unlinkStory');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','story','zeroCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','svn','apiSync');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','svn','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','svn','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','task','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','task','deleteEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','task','editEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','task','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','task','recordEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','task','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','task','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testcase','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testcase','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testcase','createBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testcase','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testcase','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testcase','importFromLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testcase','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testcase','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testsuite','batchCreateCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testsuite','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testsuite','createCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testsuite','createLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testsuite','exportTemplet');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testsuite','import');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testsuite','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testsuite','library');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testsuite','libView');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testsuite','showImport');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testsuite','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testtask','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testtask','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testtask','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testtask','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testtask','results');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','testtask','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','todo','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','todo','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','todo','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','todo','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','todo','batchFinish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','todo','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','todo','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','todo','createCycle');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','todo','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','todo','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','todo','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','todo','finish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','todo','import2Today');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','todo','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','tree','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','tree','browseTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','tree','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','tree','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','tree','fix');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','tree','manageChild');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','tree','updateOrder');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','user','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','user','deleteContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','user','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','user','manageContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','user','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','user','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','user','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','user','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','user','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','user','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','user','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('7','user','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','action','editComment');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','action','hideAll');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','action','hideOne');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','action','trash');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','action','undelete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','admin','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','api','getModel');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','batchActivate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','batchAssignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','batchClose');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','batchConfirm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','batchResolve');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','confirmBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','deleteTemplate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','linkBugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','resolve');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','saveTemplate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','unlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','bug','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','build','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','build','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','build','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','build','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','company','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','company','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','company','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','company','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','doc','allLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','doc','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','doc','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','doc','createLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','doc','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','doc','deleteLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','doc','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','doc','editLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','doc','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','doc','objectLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','doc','showFiles');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','doc','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','extension','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','extension','obtain');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','extension','structure');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','file','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','file','download');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','file','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','file','uploadImages');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','git','apiSync');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','git','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','git','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','group','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','index','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','misc','ping');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','my','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','my','changePassword');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','my','deleteContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','my','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','my','editProfile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','my','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','my','manageContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','my','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','my','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','my','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','my','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','my','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','my','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','my','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','my','unbind');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','product','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','product','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','product','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','product','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','product','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','product','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','product','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','product','roadmap');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','product','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','productplan','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','productplan','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','project','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','project','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','project','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','project','burn');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','project','burnData');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','project','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','project','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','project','grouptask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','project','importBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','project','importtask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','project','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','project','kanban');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','project','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','project','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','project','team');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','project','testtask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','project','tree');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','project','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','qa','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','release','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','release','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','release','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','report','bugAssign');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','report','bugCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','report','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','report','productSummary');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','report','projectDeviation');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','report','workload');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','search','buildForm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','search','buildQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','search','deleteQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','search','saveQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','search','select');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','story','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','story','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','story','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','story','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','story','tasks');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','story','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','story','zeroCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','svn','apiSync');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','svn','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','svn','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','batchAssignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','batchClose');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','cancel');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','deleteEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','editEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','finish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','pause');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','recordEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','restart');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','start');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','task','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','batchCaseTypeChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','batchConfirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','batchDelete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','batchReview');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','confirmChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','confirmStoryChange');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','createBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','exportTemplet');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','import');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','importFromLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','linkCases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','review');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','showImport');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','unlinkCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testcase','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testreport','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testreport','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testreport','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testreport','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testreport','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testsuite','batchCreateCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testsuite','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testsuite','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testsuite','createCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testsuite','createLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testsuite','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testsuite','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testsuite','exportTemplet');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testsuite','import');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testsuite','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testsuite','library');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testsuite','libView');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testsuite','linkCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testsuite','showImport');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testsuite','unlinkCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testsuite','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','batchAssign');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','batchRun');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','block');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','linkcase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','results');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','runcase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','start');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','unlinkcase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','testtask','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','todo','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','todo','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','todo','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','todo','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','todo','batchFinish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','todo','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','todo','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','todo','createCycle');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','todo','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','todo','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','todo','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','todo','finish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','todo','import2Today');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','todo','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','tree','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','tree','browseTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','tree','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','tree','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','tree','fix');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','tree','manageChild');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','tree','updateOrder');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','user','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','user','deleteContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','user','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','user','manageContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','user','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','user','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','user','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','user','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','user','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','user','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','user','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('8','user','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','action','editComment');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','action','hideAll');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','action','hideOne');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','action','trash');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','action','undelete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','admin','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','api','getModel');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','bug','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','bug','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','bug','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','bug','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','bug','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','build','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','company','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','company','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','company','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','company','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','company','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','dept','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','dept','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','dept','manageChild');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','dept','updateOrder');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','doc','allLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','doc','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','doc','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','doc','createLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','doc','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','doc','deleteLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','doc','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','doc','editLib');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','doc','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','doc','objectLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','doc','showFiles');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','doc','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','extension','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','extension','obtain');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','extension','structure');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','file','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','file','download');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','file','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','file','uploadImages');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','git','apiSync');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','git','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','git','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','group','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','index','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','misc','ping');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','my','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','my','changePassword');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','my','deleteContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','my','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','my','editProfile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','my','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','my','manageContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','my','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','my','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','my','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','my','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','my','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','my','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','my','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','my','unbind');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','product','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','product','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','product','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','product','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','product','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','product','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','product','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','product','roadmap');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','product','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','productplan','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','productplan','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','project','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','project','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','project','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','project','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','project','burn');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','project','burnData');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','project','computeBurn');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','project','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','project','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','project','grouptask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','project','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','project','kanban');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','project','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','project','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','project','team');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','project','tree');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','project','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','qa','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','release','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','release','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','release','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','report','bugAssign');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','report','bugCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','report','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','report','productSummary');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','report','projectDeviation');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','report','workload');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','search','buildForm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','search','buildQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','search','deleteQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','search','saveQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','search','select');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','story','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','story','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','story','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','story','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','story','review');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','story','tasks');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','story','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','story','zeroCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','svn','apiSync');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','svn','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','svn','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','task','deleteEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','task','editEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','task','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','task','recordEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','task','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','task','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','testcase','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','testcase','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','testcase','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','testcase','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','testcase','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','testcase','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','testsuite','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','testsuite','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','testsuite','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','testtask','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','testtask','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','testtask','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','testtask','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','testtask','results');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','testtask','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','todo','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','todo','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','todo','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','todo','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','todo','batchFinish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','todo','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','todo','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','todo','createCycle');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','todo','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','todo','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','todo','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','todo','finish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','todo','import2Today');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','todo','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','deleteContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','manageContacts');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','unbind');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','unlock');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('9','user','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','action','editComment');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','api','getModel');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','bug','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','bug','batchChangeModule');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','bug','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','bug','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','bug','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','bug','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','bug','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','bug','linkBugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','bug','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','bug','unlinkBug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','bug','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','build','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','company','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','company','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','company','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','company','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','doc','allLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','doc','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','doc','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','doc','objectLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','doc','showFiles');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','doc','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','file','download');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','index','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','misc','ping');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','my','changePassword');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','my','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','my','editProfile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','my','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','my','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','my','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','my','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','my','unbind');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','product','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','product','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','product','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','product','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','product','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','product','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','product','roadmap');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','product','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','productplan','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','productplan','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','project','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','project','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','project','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','project','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','project','burn');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','project','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','project','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','project','grouptask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','project','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','project','kanban');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','project','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','project','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','project','team');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','project','testtask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','project','tree');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','project','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','qa','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','release','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','release','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','report','bugAssign');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','report','bugCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','report','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','report','productSummary');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','report','projectDeviation');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','report','workload');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','search','buildForm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','search','buildQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','search','deleteQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','search','saveQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','story','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','story','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','story','tasks');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','story','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','task','deleteEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','task','editEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','task','recordEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','task','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','testsuite','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','testsuite','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','testsuite','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','todo','activate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','todo','assignTo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','todo','batchCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','todo','batchEdit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','todo','batchFinish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','todo','close');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','todo','create');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','todo','createCycle');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','todo','delete');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','todo','edit');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','todo','export');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','todo','finish');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','todo','import2Today');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','todo','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','user','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','user','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','user','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','user','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','user','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','user','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','user','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','user','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','user','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('10','user','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','bug','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','bug','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','bug','report');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','bug','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','build','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','company','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','company','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','company','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','company','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','doc','allLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','doc','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','doc','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','doc','objectLibs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','doc','showFiles');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','doc','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','file','download');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','git','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','git','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','group','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','index','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','misc','ping');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','my','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','product','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','product','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','product','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','product','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','product','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','product','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','product','roadmap');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','product','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','productplan','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','productplan','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','project','all');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','project','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','project','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','project','build');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','project','burn');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','project','doc');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','project','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','project','grouptask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','project','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','project','kanban');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','project','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','project','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','project','team');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','project','testtask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','project','tree');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','project','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','qa','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','release','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','release','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','report','bugAssign');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','report','bugCreate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','report','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','report','productSummary');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','report','projectDeviation');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','report','workload');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','search','buildForm');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','search','buildQuery');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','story','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','story','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','story','tasks');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','story','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','svn','cat');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','svn','diff');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','task','recordEstimate');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','task','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','testcase','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','testcase','bugs');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','testcase','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','testcase','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','testcase','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','testsuite','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','testsuite','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','testsuite','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','testtask','browse');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','testtask','cases');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','testtask','groupCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','testtask','index');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','testtask','results');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','testtask','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','user','bug');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','user','dynamic');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','user','profile');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','user','project');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','user','story');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','user','task');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','user','testCase');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','user','testTask');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','user','todo');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('11','user','view');
INSERT INTO `zt_grouppriv`(`group`,`module`,`method`) VALUES ('12','my','limited');
DROP TABLE IF EXISTS `zt_history`;
CREATE TABLE `zt_history` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `action` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `field` varchar(30) NOT NULL DEFAULT '',
  `old` text NOT NULL,
  `new` text NOT NULL,
  `diff` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `action` (`action`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('1','14','version','1','2','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('2','14','spec','&nbsp;作为一名本公司的用户，我希望可以在成果展示看到该公司网站的企业新闻，这样可以方便我进行了解该公司的产品并进行购买。&nbsp;<br />','&nbsp;作为一名本公司的用户，我希望可以在成果展示看到该公司网站的吹产品，这样可以方便我进行了解该公司的产品并进行购买。&nbsp;<br />','001- <del>作为一名本公司的用户，我希望可以在成果展示看到该公司网站的企业新闻，这样可以方便我进行了解该公司的产品并进行购买。<br /></del>
001+ <ins>作为一名本公司的用户，我希望可以在成果展示看到该公司网站的吹产品，这样可以方便我进行了解该公司的产品并进行购买。<br /></ins>');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('3','41','consumed','0','1','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('4','41','status','wait','doing','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('6','42','consumed','1','7','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('7','42','left','10','0','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('8','42','assignedTo','dev1','projectManager','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('9','42','status','doing','done','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('10','42','finishedBy','','dev1','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('11','42','finishedDate','','2012-06-05 10:38:00','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('13','43','consumed','0','6','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('14','43','left','8','0','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('15','43','assignedTo','dev1','projectManager','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('16','43','status','wait','done','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('17','43','finishedBy','','dev1','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('18','43','finishedDate','','2012-06-05 10:39:14','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('20','44','canceledDate','2012-06-05 10:41:12','2012-06-05 10:41:20','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('21','45','canceledDate','2012-06-05 10:41:12','2012-06-05 10:41:20','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('22','46','canceledDate','2012-06-05 10:41:12','2012-06-05 10:41:20','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('23','47','closedDate','2012-06-05 10:41:12','2012-06-05 10:41:20','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('24','48','closedDate','2012-06-05 10:41:12','2012-06-05 10:41:20','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('25','49','closedDate','2012-06-05 10:41:12','2012-06-05 10:41:20','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('26','50','closedDate','2012-06-05 10:41:12','2012-06-05 10:41:20','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('27','51','status','wait','done','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('28','51','consumed','0','8','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('29','51','left','10','0','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('30','51','finishedBy','','dev1','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('31','51','finishedDate','','2012-06-05 10:41:20','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('32','52','finishedDate','2012-06-05 10:38:00','2012-06-05 10:41:20','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('33','55','status','closed','done','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('34','55','consumed','0','5','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('35','55','left','8','0','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('36','55','finishedBy','','dev2','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('37','55','closedBy','dev1','','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('38','55','closedReason','done','','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('39','55','finishedDate','','2012-06-05 10:42:56','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('40','55','closedDate','2012-06-05 10:41:20','','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('41','56','status','closed','done','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('42','56','consumed','0','8','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('43','56','left','8','0','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('44','56','finishedBy','','dev2','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('45','56','closedBy','dev1','','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('46','56','closedReason','done','','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('47','56','finishedDate','','2012-06-05 10:42:56','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('48','56','closedDate','2012-06-05 10:41:20','','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('49','59','status','closed','done','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('50','59','consumed','0','5','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('51','59','left','8','0','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('52','59','finishedBy','dev1','dev3','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('53','59','closedBy','dev1','','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('54','59','closedReason','done','','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('55','59','finishedDate','','2012-06-05 10:43:32','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('56','59','closedDate','2012-06-05 10:41:20','','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('57','60','status','closed','done','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('58','60','consumed','0','5','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('59','60','left','8','0','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('60','60','finishedBy','dev1','dev3','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('61','60','closedBy','dev1','','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('62','60','closedReason','done','','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('63','60','finishedDate','','2012-06-05 10:43:32','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('64','60','closedDate','2012-06-05 10:41:20','','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('65','82','title','首页的测试用例','售后服务的测试用例','001- <del>首页的测试用例</del>
001+ <ins>售后服务的测试用例</ins>');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('66','82','story','1','4','');
INSERT INTO `zt_history`(`id`,`action`,`field`,`old`,`new`,`diff`) VALUES ('67','93','build','','trunk','');
DROP TABLE IF EXISTS `zt_lang`;
CREATE TABLE `zt_lang` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `lang` varchar(30) NOT NULL,
  `module` varchar(30) NOT NULL,
  `section` varchar(30) NOT NULL,
  `key` varchar(60) NOT NULL,
  `value` text NOT NULL,
  `system` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `lang` (`lang`,`module`,`section`,`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_log`;
CREATE TABLE `zt_log` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `objectType` varchar(30) NOT NULL,
  `objectID` mediumint(8) unsigned NOT NULL,
  `action` mediumint(8) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `url` varchar(255) NOT NULL,
  `contentType` varchar(30) NOT NULL,
  `data` text NOT NULL,
  `result` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `objectType` (`objectType`),
  KEY `obejctID` (`objectID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_mailqueue`;
CREATE TABLE `zt_mailqueue` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `toList` varchar(255) NOT NULL,
  `ccList` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `addedBy` char(30) NOT NULL,
  `addedDate` datetime NOT NULL,
  `sendTime` datetime NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'wait',
  `failReason` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sendTime` (`sendTime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_module`;
CREATE TABLE `zt_module` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `root` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `branch` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` char(60) NOT NULL DEFAULT '',
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `path` char(255) NOT NULL DEFAULT '',
  `grade` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `type` char(30) NOT NULL,
  `owner` varchar(30) NOT NULL,
  `short` varchar(30) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `module` (`root`,`type`,`path`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
INSERT INTO `zt_module`(`id`,`root`,`branch`,`name`,`parent`,`path`,`grade`,`order`,`type`,`owner`,`short`,`deleted`) VALUES ('1','1','0','首页','0',',1,','1','10','story','','','0');
INSERT INTO `zt_module`(`id`,`root`,`branch`,`name`,`parent`,`path`,`grade`,`order`,`type`,`owner`,`short`,`deleted`) VALUES ('2','1','0','新闻中心','0',',2,','1','20','story','','','0');
INSERT INTO `zt_module`(`id`,`root`,`branch`,`name`,`parent`,`path`,`grade`,`order`,`type`,`owner`,`short`,`deleted`) VALUES ('3','1','0','成果展示','0',',3,','1','30','story','','','0');
INSERT INTO `zt_module`(`id`,`root`,`branch`,`name`,`parent`,`path`,`grade`,`order`,`type`,`owner`,`short`,`deleted`) VALUES ('4','1','0','售后服务','0',',4,','1','40','story','','','0');
INSERT INTO `zt_module`(`id`,`root`,`branch`,`name`,`parent`,`path`,`grade`,`order`,`type`,`owner`,`short`,`deleted`) VALUES ('5','1','0','诚聘英才','0',',5,','1','50','story','','','0');
INSERT INTO `zt_module`(`id`,`root`,`branch`,`name`,`parent`,`path`,`grade`,`order`,`type`,`owner`,`short`,`deleted`) VALUES ('6','1','0','合作洽谈','0',',6,','1','60','story','','','0');
INSERT INTO `zt_module`(`id`,`root`,`branch`,`name`,`parent`,`path`,`grade`,`order`,`type`,`owner`,`short`,`deleted`) VALUES ('7','1','0','关于我们','0',',7,','1','70','story','','','0');
INSERT INTO `zt_module`(`id`,`root`,`branch`,`name`,`parent`,`path`,`grade`,`order`,`type`,`owner`,`short`,`deleted`) VALUES ('8','1','0','首页','0',',8,','1','10','bug','','','0');
INSERT INTO `zt_module`(`id`,`root`,`branch`,`name`,`parent`,`path`,`grade`,`order`,`type`,`owner`,`short`,`deleted`) VALUES ('9','1','0','新闻中心','0',',9,','1','20','bug','','','0');
INSERT INTO `zt_module`(`id`,`root`,`branch`,`name`,`parent`,`path`,`grade`,`order`,`type`,`owner`,`short`,`deleted`) VALUES ('10','1','0','成果展示','0',',10,','1','30','bug','','','0');
INSERT INTO `zt_module`(`id`,`root`,`branch`,`name`,`parent`,`path`,`grade`,`order`,`type`,`owner`,`short`,`deleted`) VALUES ('11','1','0','售后服务','0',',11,','1','40','bug','','','0');
INSERT INTO `zt_module`(`id`,`root`,`branch`,`name`,`parent`,`path`,`grade`,`order`,`type`,`owner`,`short`,`deleted`) VALUES ('12','1','0','诚聘英才','0',',12,','1','50','bug','','','0');
INSERT INTO `zt_module`(`id`,`root`,`branch`,`name`,`parent`,`path`,`grade`,`order`,`type`,`owner`,`short`,`deleted`) VALUES ('13','1','0','合作洽谈','0',',13,','1','60','bug','','','0');
INSERT INTO `zt_module`(`id`,`root`,`branch`,`name`,`parent`,`path`,`grade`,`order`,`type`,`owner`,`short`,`deleted`) VALUES ('14','1','0','关于我们','0',',14,','1','70','bug','','','0');
DROP TABLE IF EXISTS `zt_notify`;
CREATE TABLE `zt_notify` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `objectType` varchar(50) NOT NULL,
  `objectID` mediumint(8) unsigned NOT NULL,
  `action` mediumint(9) NOT NULL,
  `toList` varchar(255) NOT NULL,
  `ccList` text NOT NULL,
  `subject` varchar(255) NOT NULL,
  `data` text NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `sendTime` datetime NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'wait',
  `failReason` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_product`;
CREATE TABLE `zt_product` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(90) NOT NULL,
  `code` varchar(45) NOT NULL,
  `line` mediumint(8) NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT 'normal',
  `status` varchar(30) NOT NULL DEFAULT '',
  `desc` text NOT NULL,
  `PO` varchar(30) NOT NULL,
  `QD` varchar(30) NOT NULL,
  `RD` varchar(30) NOT NULL,
  `acl` enum('open','private','custom') NOT NULL DEFAULT 'open',
  `whitelist` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `createdVersion` varchar(20) NOT NULL,
  `order` mediumint(8) unsigned NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `order` (`order`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
INSERT INTO `zt_product`(`id`,`name`,`code`,`line`,`type`,`status`,`desc`,`PO`,`QD`,`RD`,`acl`,`whitelist`,`createdBy`,`createdDate`,`createdVersion`,`order`,`deleted`) VALUES ('1','公司企业网站建设','companyWebsite','0','normal','normal','建立公司企业网站，可以更好对外展示。<br />','productManager','testManager','productManager','open','','productManager','2012-06-05 09:57:07','','5','0');
INSERT INTO `zt_product`(`id`,`name`,`code`,`line`,`type`,`status`,`desc`,`PO`,`QD`,`RD`,`acl`,`whitelist`,`createdBy`,`createdDate`,`createdVersion`,`order`,`deleted`) VALUES ('2','企业内部工时管理系统','workhourManage','0','normal','normal','','productManager','testManager','productManager','open','','productManager','2012-06-05 11:15:20','5.2.1','10','0');
DROP TABLE IF EXISTS `zt_productplan`;
CREATE TABLE `zt_productplan` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL,
  `branch` mediumint(8) unsigned NOT NULL,
  `title` varchar(90) NOT NULL,
  `desc` text NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `order` text NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `plan` (`product`,`end`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
INSERT INTO `zt_productplan`(`id`,`product`,`branch`,`title`,`desc`,`begin`,`end`,`order`,`deleted`) VALUES ('1','1','0','1.0版本','开发出企业网站1.0版本。<br />','2000-01-01','2015-01-01','','0');
DROP TABLE IF EXISTS `zt_project`;
CREATE TABLE `zt_project` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `isCat` enum('1','0') NOT NULL DEFAULT '0',
  `catID` mediumint(8) unsigned NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'sprint',
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(90) NOT NULL,
  `code` varchar(45) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `days` smallint(5) unsigned NOT NULL,
  `status` varchar(10) NOT NULL,
  `statge` enum('1','2','3','4','5') NOT NULL DEFAULT '1',
  `pri` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `desc` text NOT NULL,
  `openedBy` varchar(30) NOT NULL DEFAULT '',
  `openedDate` datetime NOT NULL,
  `openedVersion` varchar(20) NOT NULL,
  `closedBy` varchar(30) NOT NULL DEFAULT '',
  `closedDate` datetime NOT NULL,
  `canceledBy` varchar(30) NOT NULL DEFAULT '',
  `canceledDate` datetime NOT NULL,
  `PO` varchar(30) NOT NULL DEFAULT '',
  `PM` varchar(30) NOT NULL DEFAULT '',
  `QD` varchar(30) NOT NULL DEFAULT '',
  `RD` varchar(30) NOT NULL DEFAULT '',
  `team` varchar(90) NOT NULL,
  `acl` enum('open','private','custom') NOT NULL DEFAULT 'open',
  `whitelist` text NOT NULL,
  `order` mediumint(8) unsigned NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `project` (`parent`,`begin`,`end`,`status`,`order`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
INSERT INTO `zt_project`(`id`,`isCat`,`catID`,`type`,`parent`,`name`,`code`,`begin`,`end`,`days`,`status`,`statge`,`pri`,`desc`,`openedBy`,`openedDate`,`openedVersion`,`closedBy`,`closedDate`,`canceledBy`,`canceledDate`,`PO`,`PM`,`QD`,`RD`,`team`,`acl`,`whitelist`,`order`,`deleted`) VALUES ('1','0','0','sprint','0','企业网站第一期','coWeb1','2012-06-05','2012-12-04','184','done','1','1','开发企业网站的基本雏形。<br />','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','productManager','projectManager','testManager','productManager','公司开发团队','open','','5','0');
INSERT INTO `zt_project`(`id`,`isCat`,`catID`,`type`,`parent`,`name`,`code`,`begin`,`end`,`days`,`status`,`statge`,`pri`,`desc`,`openedBy`,`openedDate`,`openedVersion`,`closedBy`,`closedDate`,`canceledBy`,`canceledDate`,`PO`,`PM`,`QD`,`RD`,`team`,`acl`,`whitelist`,`order`,`deleted`) VALUES ('2','0','0','sprint','0','企业网站第二期','coWebsite2','2013-06-05','2014-06-04','365','wait','1','1','','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','productManager','projectManager','testManager','productManager','公司开发团队','open','','10','0');
DROP TABLE IF EXISTS `zt_projectproduct`;
CREATE TABLE `zt_projectproduct` (
  `project` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL,
  `branch` mediumint(8) unsigned NOT NULL,
  `plan` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`project`,`product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `zt_projectproduct`(`project`,`product`,`branch`,`plan`) VALUES ('1','1','0','0');
INSERT INTO `zt_projectproduct`(`project`,`product`,`branch`,`plan`) VALUES ('2','1','0','0');
DROP TABLE IF EXISTS `zt_projectstory`;
CREATE TABLE `zt_projectstory` (
  `project` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `product` mediumint(8) unsigned NOT NULL,
  `story` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `version` smallint(6) NOT NULL DEFAULT '1',
  `order` smallint(6) unsigned NOT NULL,
  UNIQUE KEY `project` (`project`,`story`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `zt_projectstory`(`project`,`product`,`story`,`version`,`order`) VALUES ('1','1','4','1','0');
INSERT INTO `zt_projectstory`(`project`,`product`,`story`,`version`,`order`) VALUES ('1','1','3','2','0');
INSERT INTO `zt_projectstory`(`project`,`product`,`story`,`version`,`order`) VALUES ('1','1','2','1','0');
INSERT INTO `zt_projectstory`(`project`,`product`,`story`,`version`,`order`) VALUES ('1','1','1','1','0');
DROP TABLE IF EXISTS `zt_release`;
CREATE TABLE `zt_release` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `branch` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `build` mediumint(8) unsigned NOT NULL,
  `name` char(30) NOT NULL DEFAULT '',
  `date` date NOT NULL,
  `stories` text NOT NULL,
  `bugs` text NOT NULL,
  `leftBugs` text NOT NULL,
  `desc` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'normal',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `build` (`build`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_score`;
CREATE TABLE `zt_score` (
  `id` bigint(12) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(30) NOT NULL,
  `module` varchar(30) NOT NULL DEFAULT '',
  `method` varchar(30) NOT NULL,
  `desc` varchar(250) NOT NULL DEFAULT '',
  `before` int(11) NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  `after` int(11) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account` (`account`),
  KEY `model` (`module`),
  KEY `method` (`method`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_story`;
CREATE TABLE `zt_story` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `branch` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `module` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plan` text,
  `source` varchar(20) NOT NULL,
  `sourceNote` varchar(255) NOT NULL,
  `fromBug` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT '',
  `pri` tinyint(3) unsigned NOT NULL DEFAULT '3',
  `estimate` float unsigned NOT NULL,
  `status` enum('','changed','active','draft','closed') NOT NULL DEFAULT '',
  `color` char(7) NOT NULL,
  `stage` enum('','wait','planned','projected','developing','developed','testing','tested','verified','released','closed') NOT NULL DEFAULT 'wait',
  `mailto` text,
  `openedBy` varchar(30) NOT NULL DEFAULT '',
  `openedDate` datetime NOT NULL,
  `assignedTo` varchar(30) NOT NULL DEFAULT '',
  `assignedDate` datetime NOT NULL,
  `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  `lastEditedDate` datetime NOT NULL,
  `reviewedBy` varchar(255) NOT NULL,
  `reviewedDate` date NOT NULL,
  `closedBy` varchar(30) NOT NULL DEFAULT '',
  `closedDate` datetime NOT NULL,
  `closedReason` varchar(30) NOT NULL,
  `toBug` mediumint(9) NOT NULL,
  `childStories` varchar(255) NOT NULL,
  `linkStories` varchar(255) NOT NULL,
  `duplicateStory` mediumint(8) unsigned NOT NULL,
  `version` smallint(6) NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `story` (`product`,`module`,`status`,`assignedTo`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
INSERT INTO `zt_story`(`id`,`product`,`branch`,`module`,`plan`,`source`,`sourceNote`,`fromBug`,`title`,`keywords`,`type`,`pri`,`estimate`,`status`,`color`,`stage`,`mailto`,`openedBy`,`openedDate`,`assignedTo`,`assignedDate`,`lastEditedBy`,`lastEditedDate`,`reviewedBy`,`reviewedDate`,`closedBy`,`closedDate`,`closedReason`,`toBug`,`childStories`,`linkStories`,`duplicateStory`,`version`,`deleted`) VALUES ('1','1','0','1','1','po','','0','首页设计和开发','','','1','1','active','','developed','','productManager','2012-06-05 10:09:49','productManager','0000-00-00 00:00:00','productManager','2012-06-05 10:25:19','productManager, ','2012-06-05','','0000-00-00 00:00:00','','0','','','0','1','0');
INSERT INTO `zt_story`(`id`,`product`,`branch`,`module`,`plan`,`source`,`sourceNote`,`fromBug`,`title`,`keywords`,`type`,`pri`,`estimate`,`status`,`color`,`stage`,`mailto`,`openedBy`,`openedDate`,`assignedTo`,`assignedDate`,`lastEditedBy`,`lastEditedDate`,`reviewedBy`,`reviewedDate`,`closedBy`,`closedDate`,`closedReason`,`toBug`,`childStories`,`linkStories`,`duplicateStory`,`version`,`deleted`) VALUES ('2','1','0','2','1','po','','0','新闻中心的设计和开发。','','','1','1','active','','developed','','productManager','2012-06-05 10:16:37','productManager','2012-06-05 10:16:37','productManager','2012-06-05 10:25:33','productManager, ','2012-06-05','','0000-00-00 00:00:00','','0','','','0','1','0');
INSERT INTO `zt_story`(`id`,`product`,`branch`,`module`,`plan`,`source`,`sourceNote`,`fromBug`,`title`,`keywords`,`type`,`pri`,`estimate`,`status`,`color`,`stage`,`mailto`,`openedBy`,`openedDate`,`assignedTo`,`assignedDate`,`lastEditedBy`,`lastEditedDate`,`reviewedBy`,`reviewedDate`,`closedBy`,`closedDate`,`closedReason`,`toBug`,`childStories`,`linkStories`,`duplicateStory`,`version`,`deleted`) VALUES ('3','1','0','3','1','po','','0','成果展示的设计和开发','','','1','0','active','','developed','','productManager','2012-06-05 10:18:10','productManager','2012-06-05 10:18:10','productManager','2012-06-05 10:25:38','productManager, ','2012-06-05','','0000-00-00 00:00:00','','0','','','0','2','0');
INSERT INTO `zt_story`(`id`,`product`,`branch`,`module`,`plan`,`source`,`sourceNote`,`fromBug`,`title`,`keywords`,`type`,`pri`,`estimate`,`status`,`color`,`stage`,`mailto`,`openedBy`,`openedDate`,`assignedTo`,`assignedDate`,`lastEditedBy`,`lastEditedDate`,`reviewedBy`,`reviewedDate`,`closedBy`,`closedDate`,`closedReason`,`toBug`,`childStories`,`linkStories`,`duplicateStory`,`version`,`deleted`) VALUES ('4','1','0','4','1','po','','0','售后服务的设计和开发','','','1','1','active','','projected','','productManager','2012-06-05 10:20:16','productManager','2012-06-05 10:20:16','productManager','2012-06-05 10:25:42','productManager, ','2012-06-05','','0000-00-00 00:00:00','','0','','','0','1','0');
INSERT INTO `zt_story`(`id`,`product`,`branch`,`module`,`plan`,`source`,`sourceNote`,`fromBug`,`title`,`keywords`,`type`,`pri`,`estimate`,`status`,`color`,`stage`,`mailto`,`openedBy`,`openedDate`,`assignedTo`,`assignedDate`,`lastEditedBy`,`lastEditedDate`,`reviewedBy`,`reviewedDate`,`closedBy`,`closedDate`,`closedReason`,`toBug`,`childStories`,`linkStories`,`duplicateStory`,`version`,`deleted`) VALUES ('5','1','0','5','1','po','','0','诚聘英才的设计和开发','','','1','1','draft','','planned','','productManager','2012-06-05 10:21:39','productManager','2012-06-05 10:21:39','','0000-00-00 00:00:00','','0000-00-00','','0000-00-00 00:00:00','','0','','','0','1','0');
INSERT INTO `zt_story`(`id`,`product`,`branch`,`module`,`plan`,`source`,`sourceNote`,`fromBug`,`title`,`keywords`,`type`,`pri`,`estimate`,`status`,`color`,`stage`,`mailto`,`openedBy`,`openedDate`,`assignedTo`,`assignedDate`,`lastEditedBy`,`lastEditedDate`,`reviewedBy`,`reviewedDate`,`closedBy`,`closedDate`,`closedReason`,`toBug`,`childStories`,`linkStories`,`duplicateStory`,`version`,`deleted`) VALUES ('6','1','0','6','1','po','','0','合作洽谈的设计和开发','','','1','1','draft','','planned','','productManager','2012-06-05 10:23:11','productManager','2012-06-05 10:23:11','','0000-00-00 00:00:00','','0000-00-00','','0000-00-00 00:00:00','','0','','','0','1','0');
INSERT INTO `zt_story`(`id`,`product`,`branch`,`module`,`plan`,`source`,`sourceNote`,`fromBug`,`title`,`keywords`,`type`,`pri`,`estimate`,`status`,`color`,`stage`,`mailto`,`openedBy`,`openedDate`,`assignedTo`,`assignedDate`,`lastEditedBy`,`lastEditedDate`,`reviewedBy`,`reviewedDate`,`closedBy`,`closedDate`,`closedReason`,`toBug`,`childStories`,`linkStories`,`duplicateStory`,`version`,`deleted`) VALUES ('7','1','0','7','1','po','','0','关于我们的设计和开发','','','1','1','draft','','planned','','productManager','2012-06-05 10:24:19','productManager','2012-06-05 10:24:19','','0000-00-00 00:00:00','','0000-00-00','','0000-00-00 00:00:00','','0','','','0','1','0');
DROP TABLE IF EXISTS `zt_storyspec`;
CREATE TABLE `zt_storyspec` (
  `story` mediumint(9) NOT NULL,
  `version` smallint(6) NOT NULL,
  `title` varchar(90) NOT NULL,
  `spec` text NOT NULL,
  `verify` text NOT NULL,
  UNIQUE KEY `story` (`story`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `zt_storyspec`(`story`,`version`,`title`,`spec`,`verify`) VALUES ('1','1','首页设计和开发','作为一名本公司的用户，我希望可以在首页看到该公司网站的基本内容，例如最新动态、部分成果展示、联系信息、工商信息等。<br />','开发并通过验收<br />');
INSERT INTO `zt_storyspec`(`story`,`version`,`title`,`spec`,`verify`) VALUES ('2','1','新闻中心的设计和开发。','作为一名本公司的用户，我希望可以在新闻中心看到该公司网站的企业新闻，这样可以通过新闻了解企业的最新动态。<br />','');
INSERT INTO `zt_storyspec`(`story`,`version`,`title`,`spec`,`verify`) VALUES ('3','1','成果展示的设计和开发','&nbsp;作为一名本公司的用户，我希望可以在成果展示看到该公司网站的企业新闻，这样可以方便我进行了解该公司的产品并进行购买。&nbsp;<br />','');
INSERT INTO `zt_storyspec`(`story`,`version`,`title`,`spec`,`verify`) VALUES ('3','2','成果展示的设计和开发','&nbsp;作为一名本公司的用户，我希望可以在成果展示看到该公司网站的吹产品，这样可以方便我进行了解该公司的产品并进行购买。&nbsp;<br />','');
INSERT INTO `zt_storyspec`(`story`,`version`,`title`,`spec`,`verify`) VALUES ('4','1','售后服务的设计和开发','作为一名本公司的用户，我希望可以在售后服务看到该公司网站的售后服务，这样可以方便我联系该公司来解决我的问题。&nbsp;<br />','');
INSERT INTO `zt_storyspec`(`story`,`version`,`title`,`spec`,`verify`) VALUES ('5','1','诚聘英才的设计和开发','作为一名求职者，我希望可以在诚聘英才里看到该公司的招聘信息，这样可以方便我应聘该公司。&nbsp;<br />','');
INSERT INTO `zt_storyspec`(`story`,`version`,`title`,`spec`,`verify`) VALUES ('6','1','合作洽谈的设计和开发','作为一名合作商，我希望可以在合作洽谈里看到该公司对外的合作内容，这样可以方便我和该公司进行合作洽谈。&nbsp;<br />','');
INSERT INTO `zt_storyspec`(`story`,`version`,`title`,`spec`,`verify`) VALUES ('7','1','关于我们的设计和开发','我希望可以在关于我们里看到该公司的基本信息，这样可以方便我了解该公司。<br />','');
DROP TABLE IF EXISTS `zt_storystage`;
CREATE TABLE `zt_storystage` (
  `story` mediumint(8) unsigned NOT NULL,
  `branch` mediumint(8) unsigned NOT NULL,
  `stage` varchar(50) NOT NULL,
  KEY `story` (`story`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_suitecase`;
CREATE TABLE `zt_suitecase` (
  `suite` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL,
  `case` mediumint(8) unsigned NOT NULL,
  `version` smallint(5) unsigned NOT NULL,
  UNIQUE KEY `suitecase` (`suite`,`case`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_task`;
CREATE TABLE `zt_task` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parent` mediumint(8) NOT NULL DEFAULT '0',
  `project` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `module` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `story` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `storyVersion` smallint(6) NOT NULL DEFAULT '1',
  `fromBug` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `pri` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `estimate` float unsigned NOT NULL,
  `consumed` float unsigned NOT NULL,
  `left` float unsigned NOT NULL,
  `deadline` date NOT NULL,
  `status` enum('wait','doing','done','pause','cancel','closed') NOT NULL DEFAULT 'wait',
  `color` char(7) NOT NULL,
  `mailto` text,
  `desc` text NOT NULL,
  `openedBy` varchar(30) NOT NULL,
  `openedDate` datetime NOT NULL,
  `assignedTo` varchar(30) NOT NULL,
  `assignedDate` datetime NOT NULL,
  `estStarted` date NOT NULL,
  `realStarted` date NOT NULL,
  `finishedBy` varchar(30) NOT NULL,
  `finishedDate` datetime NOT NULL,
  `canceledBy` varchar(30) NOT NULL,
  `canceledDate` datetime NOT NULL,
  `closedBy` varchar(30) NOT NULL,
  `closedDate` datetime NOT NULL,
  `closedReason` varchar(30) NOT NULL,
  `lastEditedBy` varchar(30) NOT NULL,
  `lastEditedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `task` (`project`,`module`,`story`,`assignedTo`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
INSERT INTO `zt_task`(`id`,`parent`,`project`,`module`,`story`,`storyVersion`,`fromBug`,`name`,`type`,`pri`,`estimate`,`consumed`,`left`,`deadline`,`status`,`color`,`mailto`,`desc`,`openedBy`,`openedDate`,`assignedTo`,`assignedDate`,`estStarted`,`realStarted`,`finishedBy`,`finishedDate`,`canceledBy`,`canceledDate`,`closedBy`,`closedDate`,`closedReason`,`lastEditedBy`,`lastEditedDate`,`deleted`) VALUES ('1','0','1','0','1','1','0','首页页面的设计','design','1','10','7','0','0000-00-00','done','','','首页页面的设计<br />','projectManager','2012-06-05 10:32:03','projectManager','2012-06-05 10:41:20','0000-00-00','0000-00-00','dev1','2012-06-05 10:41:20','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','dev1','2012-06-05 10:41:20','0');
INSERT INTO `zt_task`(`id`,`parent`,`project`,`module`,`story`,`storyVersion`,`fromBug`,`name`,`type`,`pri`,`estimate`,`consumed`,`left`,`deadline`,`status`,`color`,`mailto`,`desc`,`openedBy`,`openedDate`,`assignedTo`,`assignedDate`,`estStarted`,`realStarted`,`finishedBy`,`finishedDate`,`canceledBy`,`canceledDate`,`closedBy`,`closedDate`,`closedReason`,`lastEditedBy`,`lastEditedDate`,`deleted`) VALUES ('2','0','1','0','1','1','0','首页的开发','devel','1','10','8','0','0000-00-00','done','','','首页的开发<br />','projectManager','2012-06-05 10:32:23','dev1','2012-06-05 10:41:20','0000-00-00','0000-00-00','dev1','2012-06-05 10:41:20','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','dev1','2012-06-05 10:41:20','0');
INSERT INTO `zt_task`(`id`,`parent`,`project`,`module`,`story`,`storyVersion`,`fromBug`,`name`,`type`,`pri`,`estimate`,`consumed`,`left`,`deadline`,`status`,`color`,`mailto`,`desc`,`openedBy`,`openedDate`,`assignedTo`,`assignedDate`,`estStarted`,`realStarted`,`finishedBy`,`finishedDate`,`canceledBy`,`canceledDate`,`closedBy`,`closedDate`,`closedReason`,`lastEditedBy`,`lastEditedDate`,`deleted`) VALUES ('3','0','1','0','2','1','0','新闻中心的设计','design','1','8','8','0','0000-00-00','done','','','新闻中心的设计<br />','projectManager','2012-06-05 10:33:01','dev2','2012-06-05 10:42:56','0000-00-00','0000-00-00','dev2','2012-06-05 10:42:56','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','dev2','2012-06-05 10:42:56','0');
INSERT INTO `zt_task`(`id`,`parent`,`project`,`module`,`story`,`storyVersion`,`fromBug`,`name`,`type`,`pri`,`estimate`,`consumed`,`left`,`deadline`,`status`,`color`,`mailto`,`desc`,`openedBy`,`openedDate`,`assignedTo`,`assignedDate`,`estStarted`,`realStarted`,`finishedBy`,`finishedDate`,`canceledBy`,`canceledDate`,`closedBy`,`closedDate`,`closedReason`,`lastEditedBy`,`lastEditedDate`,`deleted`) VALUES ('4','0','1','0','2','1','0','新闻中心的开发','devel','1','8','5','0','0000-00-00','done','','','新闻中心的开发<br />','projectManager','2012-06-05 10:33:21','dev2','2012-06-05 10:42:56','0000-00-00','0000-00-00','dev2','2012-06-05 10:42:56','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','dev2','2012-06-05 10:42:56','0');
INSERT INTO `zt_task`(`id`,`parent`,`project`,`module`,`story`,`storyVersion`,`fromBug`,`name`,`type`,`pri`,`estimate`,`consumed`,`left`,`deadline`,`status`,`color`,`mailto`,`desc`,`openedBy`,`openedDate`,`assignedTo`,`assignedDate`,`estStarted`,`realStarted`,`finishedBy`,`finishedDate`,`canceledBy`,`canceledDate`,`closedBy`,`closedDate`,`closedReason`,`lastEditedBy`,`lastEditedDate`,`deleted`) VALUES ('5','0','1','0','3','2','0','成果展示的设计','design','1','8','5','0','0000-00-00','done','','','成果展示的设计<br />','projectManager','2012-06-05 10:33:44','dev3','2012-06-05 10:43:32','0000-00-00','0000-00-00','dev3','2012-06-05 10:43:32','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','dev3','2012-06-05 10:43:32','0');
INSERT INTO `zt_task`(`id`,`parent`,`project`,`module`,`story`,`storyVersion`,`fromBug`,`name`,`type`,`pri`,`estimate`,`consumed`,`left`,`deadline`,`status`,`color`,`mailto`,`desc`,`openedBy`,`openedDate`,`assignedTo`,`assignedDate`,`estStarted`,`realStarted`,`finishedBy`,`finishedDate`,`canceledBy`,`canceledDate`,`closedBy`,`closedDate`,`closedReason`,`lastEditedBy`,`lastEditedDate`,`deleted`) VALUES ('6','0','1','0','3','2','0','成果展示的开发','devel','1','8','5','0','0000-00-00','done','','','成果展示的开发<br />','projectManager','2012-06-05 10:33:59','dev3','2012-06-05 10:43:32','0000-00-00','0000-00-00','dev3','2012-06-05 10:43:32','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','dev3','2012-06-05 10:43:32','0');
INSERT INTO `zt_task`(`id`,`parent`,`project`,`module`,`story`,`storyVersion`,`fromBug`,`name`,`type`,`pri`,`estimate`,`consumed`,`left`,`deadline`,`status`,`color`,`mailto`,`desc`,`openedBy`,`openedDate`,`assignedTo`,`assignedDate`,`estStarted`,`realStarted`,`finishedBy`,`finishedDate`,`canceledBy`,`canceledDate`,`closedBy`,`closedDate`,`closedReason`,`lastEditedBy`,`lastEditedDate`,`deleted`) VALUES ('7','0','1','0','4','1','0','售后服务的设计','design','1','8','0','8','0000-00-00','cancel','','','售后服务的设计<br />','projectManager','2012-06-05 10:34:25','projectManager','2012-06-05 10:41:20','0000-00-00','0000-00-00','','0000-00-00 00:00:00','dev1','2012-06-05 10:41:20','','0000-00-00 00:00:00','','dev1','2012-06-05 10:41:20','0');
INSERT INTO `zt_task`(`id`,`parent`,`project`,`module`,`story`,`storyVersion`,`fromBug`,`name`,`type`,`pri`,`estimate`,`consumed`,`left`,`deadline`,`status`,`color`,`mailto`,`desc`,`openedBy`,`openedDate`,`assignedTo`,`assignedDate`,`estStarted`,`realStarted`,`finishedBy`,`finishedDate`,`canceledBy`,`canceledDate`,`closedBy`,`closedDate`,`closedReason`,`lastEditedBy`,`lastEditedDate`,`deleted`) VALUES ('8','0','1','0','4','1','0','售后服务的开发','devel','1','8','6','0','0000-00-00','cancel','','','售后服务的开发<br />','projectManager','2012-06-05 10:34:45','projectManager','2012-06-05 10:41:20','0000-00-00','0000-00-00','dev1','0000-00-00 00:00:00','dev1','2012-06-05 10:41:20','','0000-00-00 00:00:00','','dev1','2012-06-05 10:41:20','0');
INSERT INTO `zt_task`(`id`,`parent`,`project`,`module`,`story`,`storyVersion`,`fromBug`,`name`,`type`,`pri`,`estimate`,`consumed`,`left`,`deadline`,`status`,`color`,`mailto`,`desc`,`openedBy`,`openedDate`,`assignedTo`,`assignedDate`,`estStarted`,`realStarted`,`finishedBy`,`finishedDate`,`canceledBy`,`canceledDate`,`closedBy`,`closedDate`,`closedReason`,`lastEditedBy`,`lastEditedDate`,`deleted`) VALUES ('9','0','1','0','4','1','0','售后服务的开发','devel','1','8','0','8','0000-00-00','cancel','','','售后服务的开发<br />','projectManager','2012-06-05 10:35:01','projectManager','2012-06-05 10:41:20','0000-00-00','0000-00-00','','0000-00-00 00:00:00','dev1','2012-06-05 10:41:20','','0000-00-00 00:00:00','','dev1','2012-06-05 10:41:20','0');
DROP TABLE IF EXISTS `zt_taskestimate`;
CREATE TABLE `zt_taskestimate` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `task` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `left` float unsigned NOT NULL DEFAULT '0',
  `consumed` float unsigned NOT NULL,
  `account` char(30) NOT NULL DEFAULT '',
  `work` text,
  PRIMARY KEY (`id`),
  KEY `task` (`task`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_team`;
CREATE TABLE `zt_team` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `root` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` enum('project','task') NOT NULL DEFAULT 'project',
  `account` char(30) NOT NULL DEFAULT '',
  `role` char(30) NOT NULL DEFAULT '',
  `limited` char(8) NOT NULL DEFAULT 'no',
  `join` date NOT NULL DEFAULT '0000-00-00',
  `days` smallint(5) unsigned NOT NULL,
  `hours` float(2,1) unsigned NOT NULL DEFAULT '0.0',
  `estimate` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `consumed` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `left` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `order` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `team` (`root`,`type`,`account`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
INSERT INTO `zt_team`(`id`,`root`,`type`,`account`,`role`,`limited`,`join`,`days`,`hours`,`estimate`,`consumed`,`left`,`order`) VALUES ('1','1','project','dev3','研发','no','2013-02-20','184','7.0','0.00','0.00','0.00','0');
INSERT INTO `zt_team`(`id`,`root`,`type`,`account`,`role`,`limited`,`join`,`days`,`hours`,`estimate`,`consumed`,`left`,`order`) VALUES ('2','1','project','productManager','产品经理','no','2013-02-20','184','7.0','0.00','0.00','0.00','0');
INSERT INTO `zt_team`(`id`,`root`,`type`,`account`,`role`,`limited`,`join`,`days`,`hours`,`estimate`,`consumed`,`left`,`order`) VALUES ('3','1','project','tester2','测试','no','2013-02-20','184','7.0','0.00','0.00','0.00','0');
INSERT INTO `zt_team`(`id`,`root`,`type`,`account`,`role`,`limited`,`join`,`days`,`hours`,`estimate`,`consumed`,`left`,`order`) VALUES ('4','1','project','tester1','测试','no','2013-02-20','184','7.0','0.00','0.00','0.00','0');
INSERT INTO `zt_team`(`id`,`root`,`type`,`account`,`role`,`limited`,`join`,`days`,`hours`,`estimate`,`consumed`,`left`,`order`) VALUES ('5','2','project','projectManager','项目经理','no','2013-02-20','365','7.0','0.00','0.00','0.00','0');
INSERT INTO `zt_team`(`id`,`root`,`type`,`account`,`role`,`limited`,`join`,`days`,`hours`,`estimate`,`consumed`,`left`,`order`) VALUES ('6','2','project','tester2','测试','no','2013-02-20','365','7.0','0.00','0.00','0.00','0');
INSERT INTO `zt_team`(`id`,`root`,`type`,`account`,`role`,`limited`,`join`,`days`,`hours`,`estimate`,`consumed`,`left`,`order`) VALUES ('7','2','project','tester1','测试','no','2013-02-20','365','7.0','0.00','0.00','0.00','0');
INSERT INTO `zt_team`(`id`,`root`,`type`,`account`,`role`,`limited`,`join`,`days`,`hours`,`estimate`,`consumed`,`left`,`order`) VALUES ('8','2','project','dev3','研发','no','2013-02-20','365','7.0','0.00','0.00','0.00','0');
INSERT INTO `zt_team`(`id`,`root`,`type`,`account`,`role`,`limited`,`join`,`days`,`hours`,`estimate`,`consumed`,`left`,`order`) VALUES ('9','2','project','dev2','研发','no','2013-02-20','365','7.0','0.00','0.00','0.00','0');
INSERT INTO `zt_team`(`id`,`root`,`type`,`account`,`role`,`limited`,`join`,`days`,`hours`,`estimate`,`consumed`,`left`,`order`) VALUES ('10','2','project','dev1','研发','no','2013-02-20','365','7.0','0.00','0.00','0.00','0');
INSERT INTO `zt_team`(`id`,`root`,`type`,`account`,`role`,`limited`,`join`,`days`,`hours`,`estimate`,`consumed`,`left`,`order`) VALUES ('11','1','project','dev1','研发','no','2013-02-20','184','7.0','0.00','0.00','0.00','0');
INSERT INTO `zt_team`(`id`,`root`,`type`,`account`,`role`,`limited`,`join`,`days`,`hours`,`estimate`,`consumed`,`left`,`order`) VALUES ('12','1','project','dev2','研发','no','2013-02-20','184','7.0','0.00','0.00','0.00','0');
INSERT INTO `zt_team`(`id`,`root`,`type`,`account`,`role`,`limited`,`join`,`days`,`hours`,`estimate`,`consumed`,`left`,`order`) VALUES ('13','1','project','projectManager','项目经理','no','2013-02-20','184','7.0','0.00','0.00','0.00','0');
INSERT INTO `zt_team`(`id`,`root`,`type`,`account`,`role`,`limited`,`join`,`days`,`hours`,`estimate`,`consumed`,`left`,`order`) VALUES ('14','1','project','testManager','测试主管','no','2013-02-20','184','7.0','0.00','0.00','0.00','0');
INSERT INTO `zt_team`(`id`,`root`,`type`,`account`,`role`,`limited`,`join`,`days`,`hours`,`estimate`,`consumed`,`left`,`order`) VALUES ('15','2','project','productManager','产品经理','no','2013-02-20','365','7.0','0.00','0.00','0.00','0');
DROP TABLE IF EXISTS `zt_testreport`;
CREATE TABLE `zt_testreport` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL,
  `project` mediumint(8) unsigned NOT NULL,
  `tasks` varchar(255) NOT NULL,
  `builds` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `owner` char(30) NOT NULL,
  `members` text NOT NULL,
  `stories` text NOT NULL,
  `bugs` text NOT NULL,
  `cases` text NOT NULL,
  `report` text NOT NULL,
  `objectType` varchar(20) NOT NULL,
  `objectID` mediumint(8) unsigned NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_testresult`;
CREATE TABLE `zt_testresult` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `run` mediumint(8) unsigned NOT NULL,
  `case` mediumint(8) unsigned NOT NULL,
  `version` smallint(5) unsigned NOT NULL,
  `caseResult` char(30) NOT NULL,
  `stepResults` text NOT NULL,
  `lastRunner` varchar(30) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `testresult` (`case`,`version`,`run`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
INSERT INTO `zt_testresult`(`id`,`run`,`case`,`version`,`caseResult`,`stepResults`,`lastRunner`,`date`) VALUES ('1','4','1','1','pass','a:1:{i:1;a:2:{s:6:\"result\";s:4:\"pass\";s:4:\"real\";s:0:\"\";}}','testManager','2012-06-05 11:11:00');
INSERT INTO `zt_testresult`(`id`,`run`,`case`,`version`,`caseResult`,`stepResults`,`lastRunner`,`date`) VALUES ('2','3','2','1','pass','a:1:{i:2;a:2:{s:6:\"result\";s:4:\"pass\";s:4:\"real\";s:0:\"\";}}','testManager','2012-06-05 11:11:05');
INSERT INTO `zt_testresult`(`id`,`run`,`case`,`version`,`caseResult`,`stepResults`,`lastRunner`,`date`) VALUES ('3','2','3','1','pass','a:1:{i:3;a:2:{s:6:\"result\";s:4:\"pass\";s:4:\"real\";s:0:\"\";}}','testManager','2012-06-05 11:11:07');
INSERT INTO `zt_testresult`(`id`,`run`,`case`,`version`,`caseResult`,`stepResults`,`lastRunner`,`date`) VALUES ('4','1','4','1','pass','a:1:{i:4;a:2:{s:6:\"result\";s:4:\"pass\";s:4:\"real\";s:0:\"\";}}','testManager','2012-06-05 11:11:08');
DROP TABLE IF EXISTS `zt_testrun`;
CREATE TABLE `zt_testrun` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `task` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `case` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `version` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `assignedTo` char(30) NOT NULL DEFAULT '',
  `lastRunner` varchar(30) NOT NULL,
  `lastRunDate` datetime NOT NULL,
  `lastRunResult` char(30) NOT NULL,
  `status` char(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task` (`task`,`case`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
INSERT INTO `zt_testrun`(`id`,`task`,`case`,`version`,`assignedTo`,`lastRunner`,`lastRunDate`,`lastRunResult`,`status`) VALUES ('1','1','4','1','','testManager','2012-06-05 11:11:08','pass','done');
INSERT INTO `zt_testrun`(`id`,`task`,`case`,`version`,`assignedTo`,`lastRunner`,`lastRunDate`,`lastRunResult`,`status`) VALUES ('2','1','3','1','','testManager','2012-06-05 11:11:07','pass','done');
INSERT INTO `zt_testrun`(`id`,`task`,`case`,`version`,`assignedTo`,`lastRunner`,`lastRunDate`,`lastRunResult`,`status`) VALUES ('3','1','2','1','','testManager','2012-06-05 11:11:05','pass','done');
INSERT INTO `zt_testrun`(`id`,`task`,`case`,`version`,`assignedTo`,`lastRunner`,`lastRunDate`,`lastRunResult`,`status`) VALUES ('4','1','1','1','','testManager','2012-06-05 11:11:00','pass','done');
DROP TABLE IF EXISTS `zt_testsuite`;
CREATE TABLE `zt_testsuite` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `addedBy` char(30) NOT NULL,
  `addedDate` datetime NOT NULL,
  `lastEditedBy` char(30) NOT NULL,
  `lastEditedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_testtask`;
CREATE TABLE `zt_testtask` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(90) NOT NULL,
  `product` mediumint(8) unsigned NOT NULL,
  `project` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `build` char(30) NOT NULL,
  `owner` varchar(30) NOT NULL,
  `pri` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `mailto` text,
  `desc` text NOT NULL,
  `report` text NOT NULL,
  `status` enum('blocked','doing','wait','done') NOT NULL DEFAULT 'wait',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `build` (`build`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
INSERT INTO `zt_testtask`(`id`,`name`,`product`,`project`,`build`,`owner`,`pri`,`begin`,`end`,`mailto`,`desc`,`report`,`status`,`deleted`) VALUES ('1','企业网站第一期测试任务','1','1','trunk','testManager','0','2012-06-05','2013-06-21','','','','wait','0');
DROP TABLE IF EXISTS `zt_todo`;
CREATE TABLE `zt_todo` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account` char(30) NOT NULL,
  `date` date NOT NULL,
  `begin` smallint(4) unsigned zerofill NOT NULL,
  `end` smallint(4) unsigned zerofill NOT NULL,
  `type` char(10) NOT NULL,
  `cycle` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `idvalue` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pri` tinyint(3) unsigned NOT NULL,
  `name` char(150) NOT NULL,
  `desc` text NOT NULL,
  `status` enum('wait','doing','done','closed') NOT NULL DEFAULT 'wait',
  `private` tinyint(1) NOT NULL,
  `config` varchar(255) NOT NULL,
  `assignedTo` varchar(30) NOT NULL DEFAULT '',
  `assignedBy` varchar(30) NOT NULL DEFAULT '',
  `assignedDate` datetime NOT NULL,
  `finishedBy` varchar(30) NOT NULL DEFAULT '',
  `finishedDate` datetime NOT NULL,
  `closedBy` varchar(30) NOT NULL DEFAULT '',
  `closedDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `todo` (`account`,`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_user`;
CREATE TABLE `zt_user` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `dept` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `account` char(30) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `role` char(10) NOT NULL DEFAULT '',
  `realname` varchar(100) NOT NULL DEFAULT '',
  `nickname` char(60) NOT NULL DEFAULT '',
  `commiter` varchar(100) NOT NULL,
  `avatar` char(30) NOT NULL DEFAULT '',
  `birthday` date NOT NULL DEFAULT '0000-00-00',
  `gender` enum('f','m') NOT NULL DEFAULT 'f',
  `email` char(90) NOT NULL DEFAULT '',
  `skype` char(90) NOT NULL DEFAULT '',
  `qq` char(20) NOT NULL DEFAULT '',
  `yahoo` char(90) NOT NULL DEFAULT '',
  `gtalk` char(90) NOT NULL DEFAULT '',
  `wangwang` char(90) NOT NULL DEFAULT '',
  `mobile` char(11) NOT NULL DEFAULT '',
  `phone` char(20) NOT NULL DEFAULT '',
  `address` char(120) NOT NULL DEFAULT '',
  `zipcode` char(10) NOT NULL DEFAULT '',
  `join` date NOT NULL DEFAULT '0000-00-00',
  `visits` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `last` int(10) unsigned NOT NULL DEFAULT '0',
  `fails` tinyint(5) NOT NULL DEFAULT '0',
  `locked` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ranzhi` char(30) NOT NULL DEFAULT '',
  `score` int(11) NOT NULL DEFAULT '0',
  `scoreLevel` int(11) NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`),
  KEY `user` (`dept`,`email`,`commiter`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
INSERT INTO `zt_user`(`id`,`dept`,`account`,`password`,`role`,`realname`,`nickname`,`commiter`,`avatar`,`birthday`,`gender`,`email`,`skype`,`qq`,`yahoo`,`gtalk`,`wangwang`,`mobile`,`phone`,`address`,`zipcode`,`join`,`visits`,`ip`,`last`,`fails`,`locked`,`ranzhi`,`score`,`scoreLevel`,`deleted`) VALUES ('1','0','admin','a2709dc61b7c7088169c01966093f69b','','admin','','','','0000-00-00','f','','','','','','','','','','','0000-00-00','3','172.21.0.1','1533257795','0','0000-00-00 00:00:00','','0','0','0');
INSERT INTO `zt_user`(`id`,`dept`,`account`,`password`,`role`,`realname`,`nickname`,`commiter`,`avatar`,`birthday`,`gender`,`email`,`skype`,`qq`,`yahoo`,`gtalk`,`wangwang`,`mobile`,`phone`,`address`,`zipcode`,`join`,`visits`,`ip`,`last`,`fails`,`locked`,`ranzhi`,`score`,`scoreLevel`,`deleted`) VALUES ('2','5','productManager','e10adc3949ba59abbe56e057f20f883e','po','产品经理','','','','0000-00-00','m','','','','','','','','','','','0000-00-00','3','192.168.0.8','1338866083','0','0000-00-00 00:00:00','','0','0','0');
INSERT INTO `zt_user`(`id`,`dept`,`account`,`password`,`role`,`realname`,`nickname`,`commiter`,`avatar`,`birthday`,`gender`,`email`,`skype`,`qq`,`yahoo`,`gtalk`,`wangwang`,`mobile`,`phone`,`address`,`zipcode`,`join`,`visits`,`ip`,`last`,`fails`,`locked`,`ranzhi`,`score`,`scoreLevel`,`deleted`) VALUES ('3','6','projectManager','e10adc3949ba59abbe56e057f20f883e','pm','项目经理','','','','0000-00-00','m','','','','','','','','','','','0000-00-00','3','192.168.0.8','1338865876','0','0000-00-00 00:00:00','','0','0','0');
INSERT INTO `zt_user`(`id`,`dept`,`account`,`password`,`role`,`realname`,`nickname`,`commiter`,`avatar`,`birthday`,`gender`,`email`,`skype`,`qq`,`yahoo`,`gtalk`,`wangwang`,`mobile`,`phone`,`address`,`zipcode`,`join`,`visits`,`ip`,`last`,`fails`,`locked`,`ranzhi`,`score`,`scoreLevel`,`deleted`) VALUES ('4','2','dev1','e10adc3949ba59abbe56e057f20f883e','dev','开发甲','','','','0000-00-00','m','','','','','','','','','','','0000-00-00','1','192.168.0.8','1338863860','0','0000-00-00 00:00:00','','0','0','1');
INSERT INTO `zt_user`(`id`,`dept`,`account`,`password`,`role`,`realname`,`nickname`,`commiter`,`avatar`,`birthday`,`gender`,`email`,`skype`,`qq`,`yahoo`,`gtalk`,`wangwang`,`mobile`,`phone`,`address`,`zipcode`,`join`,`visits`,`ip`,`last`,`fails`,`locked`,`ranzhi`,`score`,`scoreLevel`,`deleted`) VALUES ('5','2','dev2','e10adc3949ba59abbe56e057f20f883e','dev','开发乙','','','','0000-00-00','m','','','','','','','','','','','0000-00-00','1','192.168.0.8','1338864116','0','0000-00-00 00:00:00','','0','0','1');
INSERT INTO `zt_user`(`id`,`dept`,`account`,`password`,`role`,`realname`,`nickname`,`commiter`,`avatar`,`birthday`,`gender`,`email`,`skype`,`qq`,`yahoo`,`gtalk`,`wangwang`,`mobile`,`phone`,`address`,`zipcode`,`join`,`visits`,`ip`,`last`,`fails`,`locked`,`ranzhi`,`score`,`scoreLevel`,`deleted`) VALUES ('6','2','dev3','e10adc3949ba59abbe56e057f20f883e','dev','开发丙','','','','0000-00-00','m','','','','','','','','','','','0000-00-00','1','192.168.0.8','1338864187','0','0000-00-00 00:00:00','','0','0','1');
INSERT INTO `zt_user`(`id`,`dept`,`account`,`password`,`role`,`realname`,`nickname`,`commiter`,`avatar`,`birthday`,`gender`,`email`,`skype`,`qq`,`yahoo`,`gtalk`,`wangwang`,`mobile`,`phone`,`address`,`zipcode`,`join`,`visits`,`ip`,`last`,`fails`,`locked`,`ranzhi`,`score`,`scoreLevel`,`deleted`) VALUES ('7','3','tester1','e10adc3949ba59abbe56e057f20f883e','qa','测试甲','','','','0000-00-00','m','','','','','','','','','','','0000-00-00','3','192.168.0.8','1338865739','0','0000-00-00 00:00:00','','0','0','1');
INSERT INTO `zt_user`(`id`,`dept`,`account`,`password`,`role`,`realname`,`nickname`,`commiter`,`avatar`,`birthday`,`gender`,`email`,`skype`,`qq`,`yahoo`,`gtalk`,`wangwang`,`mobile`,`phone`,`address`,`zipcode`,`join`,`visits`,`ip`,`last`,`fails`,`locked`,`ranzhi`,`score`,`scoreLevel`,`deleted`) VALUES ('8','3','tester2','e10adc3949ba59abbe56e057f20f883e','qa','测试乙','','','','0000-00-00','m','','','','','','','','','','','0000-00-00','2','192.168.0.8','1338865450','0','0000-00-00 00:00:00','','0','0','1');
INSERT INTO `zt_user`(`id`,`dept`,`account`,`password`,`role`,`realname`,`nickname`,`commiter`,`avatar`,`birthday`,`gender`,`email`,`skype`,`qq`,`yahoo`,`gtalk`,`wangwang`,`mobile`,`phone`,`address`,`zipcode`,`join`,`visits`,`ip`,`last`,`fails`,`locked`,`ranzhi`,`score`,`scoreLevel`,`deleted`) VALUES ('9','3','tester3','e10adc3949ba59abbe56e057f20f883e','qa','测试丙','','','','0000-00-00','m','','','','','','','','','','','0000-00-00','1','192.168.0.8','1338865125','0','0000-00-00 00:00:00','','0','0','1');
INSERT INTO `zt_user`(`id`,`dept`,`account`,`password`,`role`,`realname`,`nickname`,`commiter`,`avatar`,`birthday`,`gender`,`email`,`skype`,`qq`,`yahoo`,`gtalk`,`wangwang`,`mobile`,`phone`,`address`,`zipcode`,`join`,`visits`,`ip`,`last`,`fails`,`locked`,`ranzhi`,`score`,`scoreLevel`,`deleted`) VALUES ('10','1','testManager','e10adc3949ba59abbe56e057f20f883e','qd','测试经理','','','','0000-00-00','m','','','','','','','','','','','0000-00-00','6','192.168.0.8','1338865842','0','0000-00-00 00:00:00','','0','0','1');
DROP TABLE IF EXISTS `zt_usercontact`;
CREATE TABLE `zt_usercontact` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account` char(30) NOT NULL,
  `listName` varchar(60) NOT NULL,
  `userList` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account` (`account`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_usergroup`;
CREATE TABLE `zt_usergroup` (
  `account` char(30) NOT NULL DEFAULT '',
  `group` mediumint(8) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `account` (`account`,`group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `zt_usergroup`(`account`,`group`) VALUES ('dev1','2');
INSERT INTO `zt_usergroup`(`account`,`group`) VALUES ('dev2','2');
INSERT INTO `zt_usergroup`(`account`,`group`) VALUES ('dev3','2');
INSERT INTO `zt_usergroup`(`account`,`group`) VALUES ('productManager','5');
INSERT INTO `zt_usergroup`(`account`,`group`) VALUES ('projectManager','4');
INSERT INTO `zt_usergroup`(`account`,`group`) VALUES ('tester1','3');
INSERT INTO `zt_usergroup`(`account`,`group`) VALUES ('tester2','3');
INSERT INTO `zt_usergroup`(`account`,`group`) VALUES ('tester3','3');
INSERT INTO `zt_usergroup`(`account`,`group`) VALUES ('testManager','8');
DROP TABLE IF EXISTS `zt_userquery`;
CREATE TABLE `zt_userquery` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account` char(30) NOT NULL,
  `module` varchar(30) NOT NULL,
  `title` varchar(90) NOT NULL,
  `form` text NOT NULL,
  `sql` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `query` (`account`,`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_usertpl`;
CREATE TABLE `zt_usertpl` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account` char(30) NOT NULL,
  `type` char(30) NOT NULL,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `public` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `account` (`account`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_webhook`;
CREATE TABLE `zt_webhook` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL DEFAULT 'default',
  `name` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `contentType` varchar(30) NOT NULL DEFAULT 'application/json',
  `sendType` enum('sync','async') NOT NULL DEFAULT 'sync',
  `products` text NOT NULL,
  `projects` text NOT NULL,
  `params` varchar(100) NOT NULL,
  `actions` text NOT NULL,
  `desc` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `zt_webhookdatas`;
CREATE TABLE `zt_webhookdatas` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `webhook` mediumint(8) unsigned NOT NULL,
  `action` mediumint(8) unsigned NOT NULL,
  `data` text NOT NULL,
  `status` enum('wait','sended') NOT NULL DEFAULT 'wait',
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq` (`webhook`,`action`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
