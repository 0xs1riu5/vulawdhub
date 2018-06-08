DROP TABLE IF EXISTS met_admin_array;
CREATE TABLE IF NOT EXISTS `met_admin_array` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `array_name` varchar(255) NOT NULL,
  `admin_type` text NOT NULL,
  `admin_ok` int(11) NOT NULL default '0',
  `admin_op` varchar(20) default 'metinfo',
  `admin_issueok` int(11) NOT NULL default '0',
  `admin_group` int(11) NOT NULL,
  `user_webpower` int(11) NOT NULL,
  `array_type` int(11) default NULL,
  `lang` varchar(50) default NULL,
  `langok` varchar(255) default 'metinfo',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS met_admin_column;
CREATE TABLE `met_admin_column` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) default NULL,
  `url` varchar(255) default NULL,
  `bigclass` int(11) NOT NULL,
  `field` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `list_order` int(11) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_admin_table`;
CREATE TABLE `met_admin_table` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `admin_type` text NOT NULL,
  `admin_id` char(15) NOT NULL,
  `admin_pass` char(64) default NULL,
  `admin_name` varchar(30) NOT NULL,
  `admin_sex` tinyint(1) NOT NULL default '1',
  `admin_tel` varchar(20) default NULL,
  `admin_mobile` varchar(20) default NULL,
  `admin_email` varchar(150) default NULL,
  `admin_qq` varchar(12) NOT NULL,
  `admin_msn` varchar(40) NOT NULL,
  `admin_taobao` varchar(40) NOT NULL,
  `admin_introduction` text,
  `admin_login` int(11) unsigned NOT NULL default '0',
  `admin_modify_ip` varchar(20) default NULL,
  `admin_modify_date` datetime default NULL,
  `admin_register_date` datetime default NULL,
  `admin_approval_date` datetime default NULL,
  `admin_ok` int(11) NOT NULL default '0',
  `admin_op` varchar(20) default 'metinfo',
  `admin_issueok` int(11) NOT NULL default '0',
  `admin_group` int(11) NOT NULL,
  `companyname` varchar(255) default NULL,
  `companyaddress` varchar(255) default NULL,
  `companyfax` varchar(255) default NULL,
  `usertype` int(11) default '0',
  `checkid` int(1) default '0',
  `companycode` varchar(50) default NULL,
  `companywebsite` varchar(50) default NULL,
  `lang` varchar(50) default NULL,
  `langok` varchar(255) default 'metinfo',
  PRIMARY KEY  (`id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_app`;
CREATE TABLE `met_app` (
  `id` int(11) NOT NULL auto_increment,
  `no` varchar(10) NOT NULL,
  `ver` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `file` varchar(255) NOT NULL,
  `download` tinyint(1) NOT NULL,
  `power` int(11) NOT NULL,
  `sys` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `site` varchar(255) NOT NULL,
  `url` tinytext NOT NULL,
  `info` text NOT NULL,
  `addtime` int(11) NOT NULL,
  `updatetime` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_column`;
CREATE TABLE `met_column` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) default NULL,
  `foldername` varchar(50) default NULL,
  `filename` varchar(50) default NULL,
  `bigclass` int(11) default '0',
  `samefile` int(11) NOT NULL default '0',
  `module` int(11) default NULL,
  `no_order` int(11) default NULL,
  `wap_ok` int(1) default '0',
  `if_in` int(1) default '0',
  `nav` int(1) default '0',
  `ctitle` varchar(200) default NULL,
  `keywords` varchar(200) default NULL,
  `content` longtext,
  `description` text,
  `list_order` int(11) default '0',
  `new_windows` varchar(50) default NULL,
  `classtype` int(11) default '1',
  `out_url` varchar(200) default NULL,
  `index_num` int(11) default '0',
  `access` int(11) default '0',
  `indeximg` varchar(255) default NULL,
  `columnimg` varchar(255) default NULL,
  `isshow` int(11) default '1',
  `lang` varchar(50) default NULL,
  `namemark` varchar(255) default NULL,
  `releclass` int(11) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_config`;
CREATE TABLE `met_config` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `columnid` int(11) NOT NULL,
  `flashid` int(11) NOT NULL,
  `lang` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_cv`;
CREATE TABLE `met_cv` (
  `id` int(11) NOT NULL auto_increment,
  `addtime` datetime default NULL,
  `readok` int(11) default '0',
  `customerid` varchar(50) default '0',
  `jobid` int(11) NOT NULL default '0',
  `lang` varchar(50) default NULL,
  `ip` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_download`;
CREATE TABLE `met_download` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(200) default NULL,
  `ctitle` varchar(200) default NULL,
  `keywords` varchar(200) default NULL,
  `description` text,
  `content` longtext,
  `class1` int(11) default '0',
  `class2` int(11) default '0',
  `class3` int(11) default '0',
  `no_order` int(11) default '0',
  `new_ok` int(1) default '0',
  `wap_ok` int(1) default '0',
  `downloadurl` varchar(255) default NULL,
  `filesize` varchar(100) default NULL,
  `com_ok` int(1) default '0',
  `hits` int(11) default '0',
  `updatetime` datetime default NULL,
  `addtime` datetime default NULL,
  `issue` varchar(100) default '',
  `access` int(11) default '0',
  `top_ok` int(1) default '0',
  `downloadaccess` int(11) default '0',
  `filename` varchar(255) default NULL,
  `lang` varchar(50) default NULL,
  `recycle` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_feedback`;
CREATE TABLE `met_feedback` (
  `id` int(11) NOT NULL auto_increment,
  `class1` int(11) default '0',
  `fdtitle` varchar(255) default NULL,
  `fromurl` varchar(255) default NULL,
  `ip` varchar(255) default NULL,
  `addtime` datetime default NULL,
  `readok` int(11) default '0',
  `useinfo` text,
  `customerid` varchar(30) default '0',
  `lang` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_flash`;
CREATE TABLE `met_flash` (
  `id` int(11) NOT NULL auto_increment,
  `module` text,
  `img_title` varchar(255) default NULL,
  `img_path` varchar(255) default NULL,
  `img_link` varchar(255) default NULL,
  `flash_path` varchar(255) default NULL,
  `flash_back` varchar(255) default NULL,
  `no_order` int(11) default NULL,
  `width` int(11) default NULL,
  `height` int(11) default NULL,
  `lang` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_flist`;
CREATE TABLE `met_flist` (
  `id` int(11) NOT NULL auto_increment,
  `listid` int(11) default NULL,
  `paraid` int(11) default NULL,
  `info` text,
  `lang` varchar(50) default NULL,
  `module` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_img`;
CREATE TABLE `met_img` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(200) default NULL,
  `ctitle` varchar(200) default NULL,
  `keywords` varchar(200) default NULL,
  `description` text,
  `content` longtext,
  `class1` int(11) default '0',
  `class2` int(11) default '0',
  `class3` int(11) default '0',
  `no_order` int(11) default '0',
  `wap_ok` int(1) default '0',
  `new_ok` int(1) default '0',
  `imgurl` varchar(255) default NULL,
  `imgurls` varchar(255) default NULL,
  `displayimg` text default NULL,
  `com_ok` int(1) default '0',
  `hits` int(11) default '0',
  `updatetime` datetime default NULL,
  `addtime` datetime default NULL,
  `issue` varchar(100) default '',
  `access` int(11) default '0',
  `top_ok` int(1) default '0',
  `filename` varchar(255) default NULL,
  `lang` varchar(50) default NULL,
  `content1` text,
  `content2` text,
  `content3` text,
  `content4` text,
  `contentinfo` varchar(255) default NULL,
  `contentinfo1` varchar(255) default NULL,
  `contentinfo2` varchar(255) default NULL,
  `contentinfo3` varchar(255) default NULL,
  `contentinfo4` varchar(255) default NULL,
  `recycle` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_job`;
CREATE TABLE `met_job` (
  `id` int(11) NOT NULL auto_increment,
  `position` varchar(200) default NULL,
  `count` int(11) default '0',
  `place` varchar(200) default NULL,
  `deal` varchar(200) default NULL,
  `addtime` date default NULL,
  `useful_life` int(11) default NULL,
  `content` longtext,
  `access` int(11) default '0',
  `no_order` int(11) default '0',
  `wap_ok` int(1) default '0',
  `top_ok` int(1) default '0',
  `email` varchar(255) default NULL,
  `filename` varchar(255) default NULL,
  `lang` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_label`;
CREATE TABLE `met_label` (
  `id` int(11) NOT NULL auto_increment,
  `oldwords` varchar(255) default NULL,
  `newwords` varchar(255) default NULL,
  `newtitle` varchar(255) default NULL,
  `url` varchar(255) default NULL,
  `num` int(11) NOT NULL default '99',
  `lang` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_lang`;
CREATE TABLE `met_lang` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `useok` int(1) NOT NULL,
  `no_order` int(11) NOT NULL,
  `mark` varchar(50) NOT NULL,
  `synchronous` varchar(50) NOT NULL,
  `flag` varchar(100) NOT NULL,
  `link` varchar(255) NOT NULL,
  `newwindows` int(1) NOT NULL,
  `metconfig_webhtm` int(1) NOT NULL,
  `metconfig_htmtype` varchar(50) NOT NULL,
  `metconfig_weburl` varchar(255) NOT NULL,
  `lang` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_language`;
CREATE TABLE IF NOT EXISTS `met_language` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `site` tinyint(1) NOT NULL,
  `no_order` int(11) NOT NULL default '0',
  `array` int(11) NOT NULL,
  `app` int(11) NOT NULL,
  `lang` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_link`;
CREATE TABLE `met_link` (
  `id` int(11) NOT NULL auto_increment,
  `webname` varchar(255) default NULL,
  `weburl` varchar(255) default NULL,
  `weblogo` varchar(255) default NULL,
  `link_type` int(11) default '0',
  `info` varchar(255) default NULL,
  `contact` varchar(255) default NULL,
  `orderno` int(11) default '0',
  `com_ok` int(11) default '0',
  `show_ok` int(11) default '0',
  `addtime` datetime default NULL,
  `lang` varchar(50) default NULL,
  `ip` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_list`;
CREATE TABLE `met_list` (
  `id` int(11) NOT NULL auto_increment,
  `bigid` int(11) default NULL,
  `info` varchar(255) default NULL,
  `no_order` int(11) default NULL,
  `lang` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_message`;
CREATE TABLE `met_message` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `tel` varchar(255) default NULL,
  `email` varchar(255) default NULL,
  `contact` varchar(255) default NULL,
  `info` text,
  `ip` varchar(255) default NULL,
  `addtime` datetime default NULL,
  `readok` int(11) default '0',
  `useinfo` text,
  `lang` varchar(50) default NULL,
  `access` int(11) default '0',
  `customerid` varchar(30) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_news`;
CREATE TABLE `met_news` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(200) default NULL,
  `ctitle` varchar(200) default NULL,
  `keywords` varchar(200) default NULL,
  `description` text,
  `content` longtext,
  `class1` int(11) default '0',
  `class2` int(11) default '0',
  `class3` int(11) default '0',
  `no_order` int(11) default '0',
  `wap_ok` int(1) default '0',
  `img_ok` int(1) default '0',
  `imgurl` varchar(255) default NULL,
  `imgurls` varchar(255) default NULL,
  `com_ok` int(1) default '0',
  `issue` varchar(100) default NULL,
  `hits` int(11) default '0',
  `updatetime` datetime default NULL,
  `addtime` datetime default NULL,
  `access` int(11) default '0',
  `top_ok` int(1) default '0',
  `filename` varchar(255) default NULL,
  `lang` varchar(50) default NULL,
  `recycle` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_online`;
CREATE TABLE `met_online` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) default NULL,
  `no_order` int(11) default NULL,
  `qq` text,
  `msn` varchar(100) default NULL,
  `taobao` varchar(100) default NULL,
  `alibaba` varchar(100) default NULL,
  `skype` varchar(100) default NULL,
  `lang` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_otherinfo`;
CREATE TABLE `met_otherinfo` (
  `id` int(11) NOT NULL auto_increment,
  `info1` varchar(255) default NULL,
  `info2` varchar(255) default NULL,
  `info3` varchar(255) default NULL,
  `info4` varchar(255) default NULL,
  `info5` varchar(255) default NULL,
  `info6` varchar(255) default NULL,
  `info7` varchar(255) default NULL,
  `info8` text,
  `info9` text,
  `info10` text,
  `imgurl1` varchar(255) default NULL,
  `imgurl2` varchar(255) default NULL,
  `rightmd5` varchar(255) default NULL,
  `righttext` varchar(255) default NULL,
  `authcode` text,
  `authpass` varchar(255) default NULL,
  `authtext` varchar(255) default NULL,
  `data` longtext,
  `lang` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_parameter`;
CREATE TABLE `met_parameter` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) default NULL,
  `no_order` int(2) default NULL,
  `type` int(2) default NULL,
  `access` int(11) default '0',
  `wr_ok` int(2) default '0',
  `class1` int(11) default NULL,
  `module` int(2) default NULL,
  `lang` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_plist`;
CREATE TABLE `met_plist` (
  `id` int(11) NOT NULL auto_increment,
  `listid` int(11) default NULL,
  `paraid` int(11) default NULL,
  `info` text,
  `lang` varchar(50) default NULL,
  `imgname` varchar(255) default NULL,
  `module` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_product`;
CREATE TABLE `met_product` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(200) default NULL,
  `ctitle` varchar(200) default NULL,
  `keywords` varchar(200) default NULL,
  `description` text,
  `content` longtext,
  `class1` int(11) default '0',
  `class2` int(11) default '0',
  `class3` int(11) default '0',
  `no_order` int(11) default '0',
  `wap_ok` int(1) default '0',
  `new_ok` int(1) default '0',
  `imgurl` varchar(255) default NULL,
  `imgurls` varchar(255) default NULL,
  `displayimg` text default NULL,
  `com_ok` int(1) default '0',
  `hits` int(11) default '0',
  `updatetime` datetime default NULL,
  `addtime` datetime default NULL,
  `issue` varchar(100) default '',
  `access` int(11) default '0',
  `top_ok` int(1) default '0',
  `filename` varchar(255) default NULL,
  `lang` varchar(50) default NULL,
  `content1` text,
  `content2` text,
  `content3` text,
  `content4` text,
  `contentinfo` varchar(255) default NULL,
  `contentinfo1` varchar(255) default NULL,
  `contentinfo2` varchar(255) default NULL,
  `contentinfo3` varchar(255) default NULL,
  `contentinfo4` varchar(255) default NULL,
  `recycle` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_skin_table`;
CREATE TABLE `met_skin_table` (
  `id` int(11) NOT NULL auto_increment,
  `skin_name` varchar(200) default NULL,
  `skin_file` varchar(20) default NULL,
  `skin_info` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_sms`;
CREATE TABLE `met_sms` (
  `id` int(11) NOT NULL auto_increment,
  `time` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `content` text NOT NULL,
  `tel` text NOT NULL,
  `remark` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_visit_day`;
CREATE TABLE `met_visit_day` (
  `id` int(11) NOT NULL auto_increment,
  `ip` varchar(30) NOT NULL,
  `acctime` int(10) NOT NULL,
  `visitpage` varchar(255) NOT NULL,
  `antepage` varchar(255) NOT NULL,
  `columnid` int(11) NOT NULL,
  `listid` int(11) NOT NULL,
  `browser` varchar(255) NOT NULL,
  `dizhi` varchar(255) NOT NULL,
  `network` varchar(100) NOT NULL,
  `lang` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_visit_detail`;
CREATE TABLE `met_visit_detail` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) character set utf8 collate utf8_bin NOT NULL,
  `pv` int(11) NOT NULL default '0',
  `ip` int(11) NOT NULL default '0',
  `alone` int(11) NOT NULL default '0',
  `remark` varchar(255) character set utf8 collate utf8_bin NOT NULL,
  `type` int(1) NOT NULL default '0',
  `columnid` int(11) NOT NULL,
  `listid` int(11) NOT NULL,
  `stattime` int(11) NOT NULL,
  `lang` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `met_visit_summary`;
CREATE TABLE `met_visit_summary` (
  `id` int(11) NOT NULL auto_increment,
  `pv` int(11) NOT NULL default '0',
  `ip` int(11) NOT NULL default '0',
  `alone` int(11) NOT NULL,
  `parttime` text NOT NULL,
  `stattime` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO met_config VALUES('1','metconfig_nurse_link_tel','','0','0','metinfo');
INSERT INTO met_config VALUES('2','metconfig_nurse_link','0','0','0','metinfo');
INSERT INTO met_config VALUES('3','metcms_v','5.1.7','0','0','metinfo');
INSERT INTO met_config VALUES('4','metconfig_nurse_job_tel','','0','0','metinfo');
INSERT INTO met_config VALUES('5','metconfig_nurse_job','0','0','0','metinfo');
INSERT INTO met_config VALUES('6','metconfig_nurse_massge_tel','','0','0','metinfo');
INSERT INTO met_config VALUES('7','metconfig_nurse_massge','0','0','0','metinfo');
INSERT INTO met_config VALUES('8','metconfig_nurse_feed_tel','','0','0','metinfo');
INSERT INTO met_config VALUES('9','metconfig_nurse_feed','0','0','0','metinfo');
INSERT INTO met_config VALUES('10','metconfig_nurse_member_tel','','0','0','metinfo');
INSERT INTO met_config VALUES('11','metconfig_nurse_member','0','0','0','metinfo');
INSERT INTO met_config VALUES('12','metconfig_nurse_monitor_tel','','0','0','metinfo');
INSERT INTO met_config VALUES('13','metconfig_nurse_monitor_timeb','23','0','0','metinfo');
INSERT INTO met_config VALUES('14','metconfig_nurse_monitor_timea','0','0','0','metinfo');
INSERT INTO met_config VALUES('15','metconfig_apptime','0','0','0','metinfo');
INSERT INTO met_config VALUES('16','metconfig_nurse_monitor_weeka','1','0','0','metinfo');
INSERT INTO met_config VALUES('17','metconfig_nurse_monitor_fre','1','0','0','metinfo');
INSERT INTO met_config VALUES('18','metconfig_nurse_monitor','0','0','0','metinfo');
INSERT INTO met_config VALUES('19','metconfig_host','api.metinfo.cn','0','0','metinfo');
INSERT INTO met_config VALUES('20','metconfig_nurse_stat','0','0','0','metinfo');
INSERT INTO met_config VALUES('21','metconfig_nurse_stat_tel','','0','0','metinfo');
INSERT INTO met_config VALUES('22','metconfig_nurse_max','10','0','0','metinfo');
INSERT INTO met_config VALUES('23','metconfig_sitemap_html','0','0','0','metinfo');
INSERT INTO met_config VALUES('24','metconfig_adminfile','admin','0','0','metinfo');
INSERT INTO met_config VALUES('25','metconfig_ch_lang','1','0','0','metinfo');
INSERT INTO met_config VALUES('26','metconfig_stat_max','10000','0','0','metinfo');
INSERT INTO met_config VALUES('27','metconfig_stat_cr5','2','0','0','metinfo');
INSERT INTO met_config VALUES('28','metconfig_stat_cr4','3','0','0','metinfo');
INSERT INTO met_config VALUES('29','metconfig_stat_cr3','3','0','0','metinfo');
INSERT INTO met_config VALUES('30','metconfig_stat_cr1','0','0','0','metinfo');
INSERT INTO met_config VALUES('31','metconfig_stat_cr2','3','0','0','metinfo');
INSERT INTO met_config VALUES('32','metconfig_stat','1','0','0','metinfo');
INSERT INTO met_config VALUES('33','metconfig_ch_mark','cn','0','0','metinfo');
INSERT INTO met_config VALUES('34','metconfig_lang_editor','','0','0','metinfo');
INSERT INTO met_config VALUES('35','metconfig_lang_mark','1','0','0','metinfo');
INSERT INTO met_config VALUES('36','metconfig_url_type','0','0','0','metinfo');
INSERT INTO met_config VALUES('37','metconfig_admin_type_ok','1','0','0','metinfo');
INSERT INTO met_config VALUES('38','metconfig_admin_type','cn','0','0','metinfo');
INSERT INTO met_config VALUES('39','metconfig_sitemap_lang','1','0','0','metinfo');
INSERT INTO met_config VALUES('40','metconfig_sitemap_not2','1','0','0','metinfo');
INSERT INTO met_config VALUES('41','metconfig_sitemap_not1','0','0','0','metinfo');
INSERT INTO met_config VALUES('42','metconfig_sitemap_txt','0','0','0','metinfo');
INSERT INTO met_config VALUES('43','metconfig_sitemap_xml','0','0','0','metinfo');
INSERT INTO met_config VALUES('44','metconfig_index_type','cn','0','0','metinfo');
INSERT INTO met_config VALUES('45','metconfig_nurse_monitor_weekb','1','0','0','metinfo');
INSERT INTO met_config VALUES('46','physical_time','','0','0','metinfo');
INSERT INTO met_config VALUES('47','physical_admin','1','0','0','metinfo');
INSERT INTO met_config VALUES('48','physical_backup','1','0','0','metinfo');
INSERT INTO met_config VALUES('49','physical_update','1','0','0','metinfo');
INSERT INTO met_config VALUES('50','physical_seo','1|1|1|','0','0','metinfo');
INSERT INTO met_config VALUES('51','physical_static','1','0','0','metinfo');
INSERT INTO met_config VALUES('52','physical_unread','0|1|0','0','0','metinfo');
INSERT INTO met_config VALUES('53','physical_spam','1','0','0','metinfo');
INSERT INTO met_config VALUES('54','physical_member','1','0','0','metinfo');
INSERT INTO met_config VALUES('55','physical_web','1','0','0','metinfo');
INSERT INTO met_config VALUES('56','physical_file','1','0','0','metinfo');
INSERT INTO met_config VALUES('57','physical_fingerprint','-1','0','0','metinfo');
INSERT INTO met_config VALUES('58','physical_function','1','0','0','metinfo');
INSERT INTO met_config VALUES('59','metconfig_member_force','','0','0','metinfo');
INSERT INTO met_config VALUES('60','metconfig_smspass','1','0','0','metinfo');
INSERT INTO met_config VALUES('61','metconfig_nurse_sendtime','10','0','0','metinfo');
INSERT INTO met_config VALUES('62','metconfig_recycle','1','0','0','metinfo');
INSERT INTO met_config VALUES('534','metconfig_tablename','admin_array|admin_table|admin_column|app|column|config|cv|download|feedback|flash|flist|img|job|label|lang|language|link|list|message|news|online|otherinfo|parameter|plist|product|skin_table|sms|visit_day|visit_detail|visit_summary','0','0','metinfo');
INSERT INTO met_config VALUES('539','metconfig_smsprice','0.1','0','0','metinfo');

INSERT INTO met_config VALUES('540','metconfig_agents_logo_login','templates/met/images/login-logo.png','0','0','metinfo');
INSERT INTO met_config VALUES('541','metconfig_agents_logo_index','templates/met/images/logoen.gif','0','0','metinfo');
INSERT INTO met_config VALUES('542','metconfig_agents_copyright_foot','Powered by <b><a href=http://www.metinfo.cn target=_blank>MetInfo $metcms_v</a></b> &copy;2008-$m_now_year &nbsp;<a href=http://www.metinfo.cn target=_blank>MetInfo Inc.</a>','0','0','metinfo');
INSERT INTO met_config VALUES('543','metconfig_agents_type','0','0','0','metinfo');

INSERT INTO met_config VALUES('544','metconfig_agents_thanks','感谢使用 Metinfo','0','0','cn-metinfo');
INSERT INTO met_config VALUES('545','metconfig_agents_depict_login','打造具有营销价值的企业网站','0','0','cn-metinfo');
INSERT INTO met_config VALUES('546','metconfig_agents_name','Metinfo企业网站管理系统','0','0','cn-metinfo');
INSERT INTO met_config VALUES('547','metconfig_agents_copyright','长沙米拓信息技术有限公司（MetInfo Inc.）','0','0','cn-metinfo');
INSERT INTO met_config VALUES('548','metconfig_agents_about','关于我们','0','0','cn-metinfo');
INSERT INTO met_config VALUES('549','metconfig_agents_thanks','thanks use Metinfo','0','0','en-metinfo');
INSERT INTO met_config VALUES('550','metconfig_agents_depict_login','Metinfo Build marketing value corporate website','0','0','en-metinfo');
INSERT INTO met_config VALUES('551','metconfig_agents_name','Metinfo CMS','0','0','en-metinfo');
INSERT INTO met_config VALUES('552','metconfig_agents_copyright','China Changsha MetInfo Information Co., Ltd.','0','0','en-metinfo');
INSERT INTO met_config VALUES('553','metconfig_agents_about','About Us','0','0','en-metinfo');
INSERT INTO met_config VALUES('554','metconfig_agents_code','','0','0','metinfo');
INSERT INTO met_config VALUES('555','metconfig_agents_backup','metinfo','0','0','metinfo');
INSERT INTO met_config VALUES('556','metconfig_agents_sms','1','0','0','metinfo');
INSERT INTO met_config VALUES('557','metconfig_agents_app','1','0','0','metinfo');
INSERT INTO met_config VALUES('558','metconfig_agents_img','public/images/metinfo.gif','0','0','metinfo');
INSERT INTO met_config VALUES('561','metconfig_newcmsv','','0','0','metinfo');
INSERT INTO met_config VALUES('562','metconfig_patch','23','0','0','metinfo');
INSERT INTO met_config VALUES('563','metconfig_content_type','2','0','0','metinfo');
INSERT INTO met_config VALUES('564','metconfig_app_sysver','','0','0','metinfo');
INSERT INTO met_config VALUES('565','metconfig_app_info','0','0','0','metinfo');

INSERT INTO met_admin_column VALUES('1','lang_indexbasic','','0','0','1','1');
INSERT INTO met_admin_column VALUES('2','lang_indexskin','','0','0','1','2');
INSERT INTO met_admin_column VALUES('3','lang_indexcolumn','','0','0','1','3');
INSERT INTO met_admin_column VALUES('4','lang_indexcontent','','0','0','1','4');
INSERT INTO met_admin_column VALUES('5','lang_indexseo','','0','0','1','5');
INSERT INTO met_admin_column VALUES('6','lang_indexapp','','0','0','1','6');
INSERT INTO met_admin_column VALUES('7','lang_indexuser','','0','0','1','7');
INSERT INTO met_admin_column VALUES('8','lang_indexsysteminfo','system/sysadmin.php','1','0','2','1');
INSERT INTO met_admin_column VALUES('9','lang_indexbasicinfo','system/basic.php','1','1001','2','2');
INSERT INTO met_admin_column VALUES('10','lang_indexlang','system/lang/lang.php','1','1002','2','3');
INSERT INTO met_admin_column VALUES('11','lang_indexpic','system/img.php','1','1003','2','4');
INSERT INTO met_admin_column VALUES('12','lang_indexsafe','system/safe.php','1','1004','2','5');
INSERT INTO met_admin_column VALUES('13','lang_indexdataback','system/database/index.php','1','1005','2','6');
INSERT INTO met_admin_column VALUES('14','lang_indexupload','system/uploadfile.php','1','1006','2','7');
INSERT INTO met_admin_column VALUES('15','lang_indexcode','system/authcode.php','1','0','2','8');
INSERT INTO met_admin_column VALUES('16','lang_indexebook','http://www.metinfo.cn/course/','1','0','2','9');
INSERT INTO met_admin_column VALUES('17','lang_indexbbs','http://bbs.metinfo.cn/','1','0','2','10');
INSERT INTO met_admin_column VALUES('18','lang_temstyle','interface/skin_manager.php','2','1101','2','1');
INSERT INTO met_admin_column VALUES('19','lang_indexhomeset','interface/skin.php?cs=2','2','1102','2','2');
INSERT INTO met_admin_column VALUES('20','lang_pagesting','interface/skin.php?cs=3','2','1103','2','3');
INSERT INTO met_admin_column VALUES('21','lang_contsting','interface/skin.php?cs=4','2','1104','2','4');
INSERT INTO met_admin_column VALUES('22','lang_indexflashset','interface/flash/setflash.php','2','1105','2','5');
INSERT INTO met_admin_column VALUES('23','lang_indexonlineset','interface/online/index.php','2','1106','2','6');
INSERT INTO met_admin_column VALUES('24','lang_indexskinset','/interface/info.php','2','0','2','7');
INSERT INTO met_admin_column VALUES('25','lang_indexcolumn','column/index.php','3','1201','2','1');
INSERT INTO met_admin_column VALUES('26','lang_mod3|lang_field','column/parameter/parameter.php?module=3','3','1202','2','2');
INSERT INTO met_admin_column VALUES('27','lang_mod4|lang_field','column/parameter/parameter.php?module=4','3','1203','2','3');
INSERT INTO met_admin_column VALUES('28','lang_mod5|lang_field','column/parameter/parameter.php?module=5','3','1204','2','4');
INSERT INTO met_admin_column VALUES('29','lang_indexcontent','content/content.php','4','1301','2','1');
INSERT INTO met_admin_column VALUES('30','lang_indexfoot','content/foot.php','4','1302','2','2');
INSERT INTO met_admin_column VALUES('31','lang_indexotherinfo','content/other_info.php','4','1303','2','3');
INSERT INTO met_admin_column VALUES('32','lang_bulkopr','app/batch/contentup.php','4','1304','2','4');
INSERT INTO met_admin_column VALUES('33','lang_recycle','content/recycle/index.php','4','1305','2','5');
INSERT INTO met_admin_column VALUES('34','lang_indexwebcount','app/stat/index.php','5','1401','2','1');
INSERT INTO met_admin_column VALUES('35','lang_indexhtmset','seo/sethtm.php','5','1402','2','2');
INSERT INTO met_admin_column VALUES('36','lang_htmsitemap','seo/sitemap.php','5','1403','2','3');
INSERT INTO met_admin_column VALUES('37','lang_indexseoset','seo/seo.php','5','1404','2','4');
INSERT INTO met_admin_column VALUES('38','lang_indexhot','seo/strcontent.php','5','1405','2','5');
INSERT INTO met_admin_column VALUES('39','lang_indexlink','seo/link/index.php','5','1406','2','6');
INSERT INTO met_admin_column VALUES('40','lang_smsfuc','app/sms/sms.php','6','1503','2','3');
INSERT INTO met_admin_column VALUES('41','lang_indexwap','app/wap/wap.php','6','1502','2','2');
INSERT INTO met_admin_column VALUES('42','lang_webnanny','app/nurse/index.php','6','1504','2','4');
INSERT INTO met_admin_column VALUES('43','lang_indexPhysical','app/physical/index.php','6','1501','2','5');
INSERT INTO met_admin_column VALUES('44','lang_myapp','app/dlapp/index.php','6','1505','2','1');
INSERT INTO met_admin_column VALUES('45','lang_memberManage','member/index.php','7','1601','2','1');
INSERT INTO met_admin_column VALUES('46','lang_memberset','member/member.php','7','1602','2','3');
INSERT INTO met_admin_column VALUES('47','lang_indexadminname','admin/index.php','7','1603','2','4');
INSERT INTO met_admin_column VALUES('48','lang_indexperson','admin/editor_pass.php','7','0','2','5');
INSERT INTO met_admin_column VALUES('49','lang_memberarrayManage','member/array.php','7','1604','2','2');

INSERT INTO `met_otherinfo` VALUES (1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '您的域名没有经过MetInfo企业网站管理系统官方认证', '', 'metinfo');

INSERT INTO `met_skin_table` VALUES (1,'metv5','default','MetInfo v5.0默认模板，正式版新增二种颜色！');
INSERT INTO `met_skin_table` VALUES (2,'metv5s','metv5s','MetInfo v5.0正式版新推出一套全新精致免费模板！');
INSERT INTO `met_skin_table` VALUES (3,'metv4','metv4','MetInfo企业网站管理系统V4.0默认模板');
INSERT INTO `met_skin_table` VALUES (4,'met007','met007','Met007免费模板');
INSERT INTO `met_skin_table` VALUES (5,'metv3','metv3','MetInfo企业网站管理系统V3.0默认模板');
INSERT INTO `met_skin_table` VALUES (6,'metv2','metv2','MetInfo企业网站管理系统V2.0默认模板');

INSERT INTO met_lang VALUES('2','English','1','2','en','en','','','0','0','','','metinfo');
INSERT INTO met_lang VALUES('1','简体中文','1','1','cn','cn','','','0','0','','','metinfo');

INSERT INTO `met_admin_array` VALUES (3, '管理员', 'metinfo', 1, 'metinfo', 0, 10000, 256, 2, 'metinfo', 'metinfo');