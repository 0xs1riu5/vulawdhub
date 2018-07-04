<?php
$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."admin`;","");
$data[]=array("CREATE TABLE `".DB_PRE."admin` (
  		`id` mediumint(8) NOT NULL auto_increment,
 		`admin_name` varchar(60) NOT NULL,
  		`admin_password` varchar(60) NOT NULL,
  		`admin_nich` varchar(60) NOT NULL,
 		`admin_purview` mediumint(8) NOT NULL,
 		`admin_admin` varchar(60) default NULL,
 		`admin_mail` varchar(60) default NULL,
 		`admin_tel` varchar(60) default NULL,
 		`is_disable` mediumint(8) NOT NULL default '0',
  		`admin_ip` varchar(60) default NULL,
  		`admin_time` varchar(60) default NULL,
 		PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;",
		"admin");
$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."admin_group`;","");
$data[]=array("CREATE TABLE `".DB_PRE."admin_group` (
  `id` mediumint(8) NOT NULL auto_increment,
  `admin_group_name` varchar(60) NOT NULL,
  `admin_group_info` varchar(255) default NULL,
  `admin_group_purview` text COMMENT '分组权限-字符串以,分割',
  `is_disable` mediumint(8) NOT NULL default '0' COMMENT '是否禁用',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;","admin_group");
$data[]=array("INSERT INTO `".DB_PRE."admin_group` (`id`, `admin_group_name`, `admin_group_info`, `admin_group_purview`, `is_disable`) VALUES
(1, '超级管理员', '可以管理后台所有功能，没有任何限制', 'all_purview', 0),
(2, '信息发布员 ', '发布信息内容的管理员', 'content_create,content_edit', 0);
","");
$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."alone`;","");
$data[]=array("CREATE TABLE `".DB_PRE."alone` (
  `id` mediumint(8) NOT NULL,
  `content` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;","alone");
$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."article`;","");
$data[]=array("CREATE TABLE `".DB_PRE."article` (
  `id` mediumint(8) NOT NULL,
  `content` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;","article");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."auto_fields`;","");
$data[]=array("CREATE TABLE `".DB_PRE."auto_fields` (
  `id` mediumint(8) NOT NULL auto_increment,
  `field_name` varchar(60) NOT NULL,
  `use_name` varchar(60) NOT NULL COMMENT '表单提示文字',
  `field_type` varchar(60) NOT NULL,
  `field_value` varchar(255) default NULL COMMENT '字段默认值',
  `field_length` mediumint(8) default NULL,
  `channel_id` mediumint(8) NOT NULL COMMENT '所属频道id',
  `field_info` varchar(255) default NULL COMMENT '字段提示信息',
  `is_disable` mediumint(8) NOT NULL,
  `check_value` varchar(60) default NULL,
  `field_order` mediumint(8) NOT NULL default '10',
  `is_del` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;","auto_fields");
$data[]=array("INSERT INTO `".DB_PRE."auto_fields` (`id`, `field_name`, `use_name`, `field_type`, `field_value`, `field_length`, `channel_id`, `field_info`, `is_disable`, `check_value`, `field_order`, `is_del`) VALUES
(1, 'content', '内容', 'html', '', 255, 2, '', 0, '', 1, 1),
(2, 'model', '型号', 'text', '', 255, 3, '', 1, '', 1, 1),
(28, 'content', '详细内容', 'html', '', 255, 5, '', 0, '', 16, 1),
(6, 'down', '下载地址', 'upload_file', '', 255, 4, '', 0, '', 1, 1),
(27, 'content', '详细内容', 'html', '', 255, 4, '', 0, '', 4, 1),
(10, 'jobnum', '招聘人数', 'text', '', 255, 5, '', 0, '', 2, 0),
(12, 'jopaddress', '工作地点', 'text', '', 255, 5, '', 0, '', 5, 0),
(16, 'joblasttime', '截止日期', 'text', '2011-1-2', 255, 5, '', 0, '', 9, 0),
(26, 'content', '详细介绍', 'html', '', 255, 3, '', 0, '', 4, 1),
(24, 'content', '详细内容', 'html', '', 255, 1, '', 0, '', 1, 1),
(25, 'pics', '产品图片', 'upload_pic_more', '', 255, 3, '', 0, '', 10, 0),
(29, 'filesize', '文件大小', 'text', '', 255, 4, '单位为K', 1, '', 3, 1),
(30, 'filetype', '文件类型', 'select', '.exe,.rar,其他', 255, 4, '', 1, '', 2, 1),
(36, 'pics', '图集', 'upload_pic_more', '', 255, 1, '', 0, NULL, 10, 0);","");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."block`;","");
$data[]=array("CREATE TABLE `".DB_PRE."block` (
   `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `tag` varchar(60) NOT NULL,
  `content` text,
  `tag_name` varchar(255) DEFAULT NULL,
  `lang` varchar(255) DEFAULT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;","block");
$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."category`;","");
$data[]=array("CREATE TABLE `".DB_PRE."category` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `custom_url` varchar(255) DEFAULT NULL,
  `cate_name` varchar(60) NOT NULL,
  `cate_mb_is` mediumint(8) NOT NULL,
  `cate_hide` mediumint(8) NOT NULL,
  `cate_channel` mediumint(8) NOT NULL,
  `cate_fold_name` varchar(60) NOT NULL,
  `cate_order` mediumint(8) NOT NULL,
  `cate_rank` mediumint(8) DEFAULT '0',
  `cate_tpl` mediumint(8) NOT NULL,
  `cate_tpl_index` varchar(60) DEFAULT NULL,
  `cate_tpl_list` varchar(60) DEFAULT NULL,
  `cate_tpl_content` varchar(60) DEFAULT NULL,
  `cate_title_seo` varchar(60) DEFAULT NULL,
  `cate_key_seo` varchar(60) DEFAULT NULL,
  `cate_info_seo` varchar(350) DEFAULT NULL,
  `lang` varchar(60) NOT NULL,
  `cate_parent` mediumint(8) NOT NULL,
  `cate_html` mediumint(8) NOT NULL DEFAULT '0',
  `cate_nav` varchar(60) NOT NULL DEFAULT '0',
  `is_content` mediumint(8) DEFAULT '0',
  `cate_url` varchar(60) DEFAULT NULL,
  `cate_is_open` mediumint(8) NOT NULL DEFAULT '0',
  `form_id` mediumint(8) DEFAULT NULL,
  `cate_pic1` varchar(255) DEFAULT NULL,
  `cate_pic2` varchar(255) DEFAULT NULL,
  `cate_pic3` varchar(255) DEFAULT NULL,
  `cate_content` text,
  `temp_id` mediumint(8) DEFAULT NULL,
  `list_num` MEDIUMINT( 8 ) NOT NULL DEFAULT '20',
  `nav_show` varchar(60) DEFAULT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;","category");
$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."channel`;","");
$data[]=array("CREATE TABLE `".DB_PRE."channel` (
  `id` mediumint(8) NOT NULL auto_increment,
  `channel_name` varchar(60) NOT NULL,
  `channel_mark` varchar(60) NOT NULL,
  `channel_table` varchar(60) NOT NULL,
  `is_disable` mediumint(8) NOT NULL,
  `is_member` mediumint(8) DEFAULT NULL,
  `channel_mb_grade` mediumint(8) default '0',
  `is_verify` mediumint(8) NOT NULL COMMENT '发布内容是否审核',
  `is_del` mediumint(8) NOT NULL default '0',
  `channel_order` mediumint(8) NOT NULL default '10',
  `is_alone` mediumint(8) NOT NULL default '0',
  `list_php` VARCHAR( 150 ) NULL,
  `content_php` VARCHAR( 150 ) NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;","channel");

$data[]=array("INSERT INTO `".DB_PRE."channel` (`id`, `channel_name`, `channel_mark`, `channel_table`, `is_disable`, `is_member`, `channel_mb_grade`, `is_verify`, `is_del`, `channel_order`, `is_alone`, `list_php`, `content_php`) VALUES
(1, '单页模型', 'alone', 'alone', 0, 0, 2, 0, 1, 5, 1, 'alone/alone.php', 'alone/show_alone.php'),
(2, '文章模块', 'article', 'article', 0, 0, 2, 0, 0, 1, 0, 'article/article.php', 'article/show_article.php'),
(3, '产品模块', 'product', 'product', 0, 0, 2, 0, 0, 2, 0, 'product/product.php', 'product/show_product.php'),
(4, '下载模块', 'down', 'down', 0, 0, 2, 0, 0, 3, 0, 'down/down.php', 'down/show_down.php'),
(5, '招聘模块', 'job', 'job', 0, 0, 2, 0, 0, 4, 0, 'job/job.php', 'job/show_job.php'),
(-9, '表单模块', 'mx_form', 'mx_form', 0, NULL, 0, 0, 0, 10, 0, 'mx_form/mx_form.php', 'mx_form/show_mx_form.php');
","");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."cmsinfo`;","");
$data[]=array("CREATE TABLE `".DB_PRE."cmsinfo` (
  `id` mediumint(8) NOT NULL auto_increment,
  `info_tag` varchar(60) default NULL COMMENT '配置信息标识',
  `info_array` text COMMENT '配置信息内容',
  `info_name` varchar(60) default NULL COMMENT '配置信息名',
  `lang_tag` varchar(60) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;","cmsinfo");

$data[]=array("INSERT INTO `".DB_PRE."cmsinfo` (`id`, `info_tag`, `info_array`, `info_name`, `lang_tag`) VALUES
(1, 'sys', 'array (\n  ''web_upload_file'' => ''zip|gz|rar|iso|doc|xsl|ppt|wps|swf|mpg|mp3|rm|rmvb|wmv|wma|wav|mid|mov'',\n  ''thump_width'' => ''300'',\n  ''thump_height'' => ''200'',\n  ''upload_size'' => ''2024000'',\n  ''web_member'' => \n  array (\n    0 => ''1'',\n  ),\n  ''is_member'' => \n  array (\n    0 => ''1'',\n  ),\n  ''member_mail'' => \n  array (\n    0 => ''1'',\n  ),\n  ''member_no_name'' => ''admin|administrator|user|users'',\n  ''image_is'' => \n  array (\n    0 => ''1'',\n  ),\n  ''image_url_is'' => \n  array (\n    0 => ''1'',\n  ),\n  ''image_type'' => \n  array (\n    0 => ''1'',\n  ),\n  ''image_text'' => ''www.beescms.com'',\n  ''image_text_color'' => ''0,0,0'',\n  ''image_text_size'' => ''12'',\n  ''pic'' => ''mark_logo.gif'',\n  ''image_position'' => \n  array (\n    0 => ''9'',\n  ),\n  ''mail_type'' => \n  array (\n    0 => ''1'',\n  ),\n  ''mail_host'' => ''smtp.163.com'',\n  ''mail_pot'' => ''25'',\n  ''mail_mail'' => '''',\n  ''mail_user'' => ''admin'',\n  ''mail_pw'' => ''admin'',\n  ''mail_js'' => '''',\n  ''mail_jw'' => ''BEESCMS企业网站管理系统 http://www.beescms.com'',\n  ''safe_open'' => \n  array (\n    0 => ''1'',\n    1 => ''2'',\n    2 => ''3'',\n  ),\n  ''web_content_title'' => ''180'',\n  ''web_content_info'' => ''200'',\n  ''is_hits'' => ''1'',\n  ''fialt_words'' => ''她妈|它妈|他妈|你妈|去死|贱人|非典|艾滋病|阳痿'',\n  ''arc_html'' => \n  array (\n    0 => ''1'',\n  ),\n)', '系统配置', ''),
(2, 'index_info', 'array (\n  ''flash_is'' => ''0'',\n  ''index_lang'' => ''9'',\n)', '首页配置', '');
","");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."down`;","");
$data[]=array("CREATE TABLE `".DB_PRE."down` (
  `id` mediumint(8) NOT NULL,
  `down` varchar(255) default NULL,
  `body` text,
  `content` text,
  `filesize` varchar(255) default NULL,
  `filetype` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;","down");
$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."form`;","");
$data[]=array("CREATE TABLE `".DB_PRE."form` (
  `id` mediumint(8) NOT NULL auto_increment,
  `form_name` varchar(60) NOT NULL,
  `form_mark` varchar(60) NOT NULL,
  `is_disable` mediumint(8) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;","form");

$data[]=array("INSERT INTO `".DB_PRE."form` (`id`, `form_name`, `form_mark`, `is_disable`) VALUES
(5, '产品购买', 'prinfo', 0),
(8, '在线应聘', 'webjob', 0);;","form");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."formfield`;","");
$data[]=array("CREATE TABLE `".DB_PRE."formfield` (
  `id` mediumint(8) NOT NULL auto_increment,
  `field_name` varchar(60) NOT NULL,
  `use_name` varchar(60) NOT NULL,
  `field_type` varchar(60) NOT NULL,
  `field_value` varchar(255) NOT NULL,
  `field_length` mediumint(8) NOT NULL,
  `form_id` mediumint(8) NOT NULL,
  `field_info` varchar(60) character set utf8 collate utf8_estonian_ci NOT NULL,
  `is_disable` mediumint(8) NOT NULL,
  `form_order` mediumint(8) NOT NULL default '0',
  `is_empty` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;","formfield");

$data[]=array("INSERT INTO `".DB_PRE."formfield` (`id`, `field_name`, `use_name`, `field_type`, `field_value`, `field_length`, `form_id`, `field_info`, `is_disable`, `form_order`, `is_empty`) VALUES
(9, 'username', '姓名', 'text', '', 255, 5, '', 0, 1, 0),
(32, 'web_contact', 'QQ/MSN/旺旺', 'text', '', 255, 5, '', 0, 4, 0),
(17, 'address', '公司地址', 'text', '', 255, 5, '', 0, 5, 0),
(12, 'mail', '邮箱', 'text', '', 255, 5, '', 0, 2, 1),
(13, 'tel', '电话/传真', 'text', '', 255, 5, '', 0, 3, 0),
(14, 'content', '详细内容', 'textarea', '', 255, 5, '', 0, 6, 0),
(19, 'jobname', '姓名', 'text', '', 255, 8, '', 0, 1, 0),
(20, 'jobsex', '性别', 'select', '男\r\n女', 255, 8, '', 0, 2, 0),
(21, 'jobmoth', '出生年月', 'text', '', 255, 8, '', 0, 3, 0),
(22, 'jobjg', '籍贯', 'text', '', 255, 8, '', 0, 4, 0),
(23, 'jobxl', '学历', 'text', '', 255, 8, '', 0, 5, 0),
(24, 'jobzy', '专业', 'text', '', 255, 8, '', 0, 6, 0),
(25, 'jobbyyx', '毕业院校', 'text', '', 255, 8, '', 0, 7, 0),
(26, 'jobphone', '联系电话', 'text', '', 255, 8, '', 0, 8, 0),
(27, 'jobmail', 'E–mail', 'text', '', 255, 8, '', 0, 9, 0),
(28, 'jobhj', '所获奖项', 'textarea', '', 255, 8, '', 0, 10, 0),
(29, 'jobgzjl', '工作经历', 'textarea', '', 255, 8, '', 0, 11, 0),
(30, 'jobzyjn', '专业技能', 'textarea', '', 255, 8, '', 0, 12, 0),
(31, 'jobyyah', '业余爱好', 'textarea', '', 255, 8, '', 0, 13, 0);","formfield");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."formlist`;","");
$data[]=array("CREATE TABLE `".DB_PRE."formlist` (
  `id` mediumint(8) NOT NULL auto_increment,
  `form_id` mediumint(8) default NULL,
  `form_time` varchar(60) default NULL,
  `form_ip` varchar(60) default NULL,
  `is_read` mediumint(8) NOT NULL default '0',
  `member_id` mediumint(8) default '0',
  `arc_id` mediumint(8) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;","formlist");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."job`;","");
$data[]=array("CREATE TABLE `".DB_PRE."job` (
  `id` mediumint(8) NOT NULL,
  `jobnum` varchar(255) default NULL,
  `jopaddress` varchar(255) default NULL,
  `joblasttime` varchar(255) default NULL,
  `content` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;","job");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."lang`;","");
$data[]=array("CREATE TABLE `".DB_PRE."lang` (
  `id` mediumint(8) NOT NULL auto_increment,
  `lang_name` varchar(60) NOT NULL,
  `lang_order` mediumint(8) NOT NULL,
  `lang_tag` varchar(60) NOT NULL,
  `lang_is_use` mediumint(8) NOT NULL default '1',
  `lang_is_open` mediumint(8) NOT NULL,
  `lang_is_url` mediumint(8) NOT NULL,
  `lang_url` varchar(60) default NULL,
  `lang_is_fix` mediumint(8) NOT NULL default '0',
  `lang_main` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;","lang");

$data[]=array("INSERT INTO `".DB_PRE."lang` (`id`, `lang_name`, `lang_order`, `lang_tag`, `lang_is_use`, `lang_is_open`, `lang_is_url`, `lang_url`, `lang_is_fix`, `lang_main`) VALUES
(10, 'English', 9, 'en', 1, 0, 0, 'http://', 0, 0),
(9, '简体中文', 10, 'cn', 1, 0, 0, 'http://', 1, 1);","");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."maintb`;","");
$data[]=array("CREATE TABLE `".DB_PRE."maintb` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `filter` varchar(60) DEFAULT NULL,
  `tbpic` varchar(60) DEFAULT NULL,
  `keywords` varchar(60) DEFAULT NULL,
  `info` text,
  `author` varchar(60) DEFAULT NULL,
  `source` varchar(60) DEFAULT NULL,
  `hits` mediumint(8) NOT NULL DEFAULT '0',
  `category` mediumint(8) NOT NULL,
  `channel` mediumint(9) NOT NULL,
  `addtime` varchar(60) NOT NULL,
  `updatetime` varchar(60) DEFAULT NULL,
  `top` mediumint(8) NOT NULL,
  `purview` mediumint(8) NOT NULL COMMENT '浏览权限',
  `is_html` mediumint(8) NOT NULL COMMENT '1为动态浏览,0为静态',
  `verify` mediumint(8) NOT NULL DEFAULT '0',
  `url` varchar(255) DEFAULT NULL,
  `lang` varchar(60) DEFAULT NULL,
  `is_url` mediumint(8) NOT NULL DEFAULT '0',
  `url_add` varchar(60) DEFAULT NULL,
  `title_color` varchar(60) DEFAULT NULL,
  `title_style` mediumint(8) NOT NULL DEFAULT '0',
  `is_open` mediumint(8) NOT NULL DEFAULT '0',
  `cache_time` varchar(60) DEFAULT NULL,
  `custom_url` varchar(255) DEFAULT NULL,
  `c_order` mediumint(8) DEFAULT NULL,
  `content_key` varchar(200) DEFAULT NULL,
  `small_title` varchar(200) DEFAULT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;","maintb");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."member`;","");
$data[]=array("CREATE TABLE `".DB_PRE."member` (
  `id` mediumint(8) NOT NULL auto_increment,
  `member_name` varchar(60) default NULL,
  `member_password` varchar(60) NOT NULL,
  `member_nich` varchar(60) NOT NULL,
  `member_purview` mediumint(8) NOT NULL default '0',
  `member_user` varchar(60) NOT NULL,
  `member_mail` varchar(60) NOT NULL,
  `member_tel` varchar(60) default NULL,
  `is_disable` mediumint(8) NOT NULL default '0',
  `member_ip` varchar(60) default NULL,
  `member_time` varchar(60) default NULL,
  `member_count` mediumint(8) NOT NULL default '0',
  `member_qq` varchar(60) default NULL,
  `member_phone` varchar(60) default NULL,
  `member_sex` mediumint(8) default '0',
  `member_addtime` varchar(60) default NULL,
  `member_birth` varchar(60) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;","member");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."member_group`;","");
$data[]=array("CREATE TABLE `".DB_PRE."member_group` (
  `id` mediumint(8) NOT NULL auto_increment,
  `member_group_name` varchar(60) NOT NULL,
  `member_group_info` varchar(255) NOT NULL,
  `is_disable` mediumint(8) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;","member_group");

$data[]=array("INSERT INTO `".DB_PRE."member_group` (`id`, `member_group_name`, `member_group_info`, `is_disable`) VALUES
(1, '注册会员', '注册完成的所有会员都是这个级别', 0),
(2, 'VIP会员', '注册会员通过管理后台升级的级别', 0);","");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."product`;","");
$data[]=array("CREATE TABLE `".DB_PRE."product` (
  `id` mediumint(8) NOT NULL,
  `model` varchar(255) default NULL,
  `marketprice` varchar(255) default NULL,
  `pics` varchar(255) default NULL,
  `content` text,
  `wholesale` text,
  `shipping` text,
  `trading` text,
  `contact` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;","product");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."ask`;","");
$data[]=array("CREATE TABLE `".DB_PRE."ask` (
  `id` mediumint(8) NOT NULL auto_increment,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `addtime` varchar(60) NOT NULL,
  `reply` text,
  `member` mediumint(8) NOT NULL,
  `replytime` varchar(60) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;","ask");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."link`;","");
$data[]=array("CREATE TABLE `".DB_PRE."link` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `link_url` varchar(60) NOT NULL,
  `link_name` varchar(60) NOT NULL,
  `link_logo` varchar(60) DEFAULT NULL,
  `link_order` mediumint(8) NOT NULL DEFAULT '1',
  `link_info` varchar(255) DEFAULT NULL,
  `link_mail` varchar(60) DEFAULT NULL,
  `lang` varchar(60) NOT NULL,
  `addtime` varchar(60) DEFAULT NULL,
  `link_type` mediumint(8) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;","link");

$data[]=array("INSERT INTO `".DB_PRE."link` (`id`, `link_url`, `link_name`, `link_logo`, `link_order`, `link_info`, `link_mail`, `lang`, `addtime`, `link_type`) VALUES
(1, 'http://www.beescms.com', '企业网站管理系统', 'http://', 1, '', '', 'cn', '1309053704', 0),
(2, 'http://www.beescms.com/bbs', '交流论坛', 'http://', 2, '', '', 'cn', '1309053729', 0),
(3, 'http://www.beescms.com/help', '在线帮助', 'http://', 3, '', '', 'cn', '1309053749', 0),
(4, 'http://www.beescms.com', 'BEES企业网站管理系统', 'img/20110812/201108121414162883.gif', 1, '', '', 'cn', '1313129685', 1),
(5, 'http://www.169host.com', '高速空间', '', 1, '', '', 'cn', '1323935030', 0),
(6, 'http://www.lp0874.com', '罗平生活网', '', 10, '', '', 'cn', '1355143203', 0);","link");


$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."collect`;","");
$data[]=array("CREATE TABLE `".DB_PRE."collect` (
  `id` mediumint(8) NOT NULL auto_increment,
  `member_id` mediumint(8) NOT NULL,
  `arc_id` mediumint(8) NOT NULL,
  `addtime` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;","collect");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."prinfo`;","");
$data[]=array("CREATE TABLE `".DB_PRE."prinfo` (
  `id` mediumint(8) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `sex` varchar(255) DEFAULT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `addcode` varchar(255) DEFAULT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;","prinfo");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."market`;","");
$data[]=array("CREATE TABLE `".DB_PRE."market` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `market_name` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `market_type` mediumint(8) NOT NULL DEFAULT '0',
  `market_num` varchar(60) CHARACTER SET utf8 NOT NULL,
  `market_order` varchar(60) CHARACTER SET utf8 NOT NULL DEFAULT '10',
  `market_is` mediumint(8) NOT NULL DEFAULT '1',
  `lang` varchar(60) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;","market");


$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."lang_lang`;","");
$data[]=array("CREATE TABLE `".DB_PRE."lang_lang` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `lang_tag` varchar(60) NOT NULL,
  `lang_value` varchar(240) DEFAULT NULL,
  `lang` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=266 ;","lang_lang");

$data[]=array("INSERT INTO `".DB_PRE."lang_lang` (`id`, `lang_tag`, `lang_value`, `lang`) VALUES
(5, 'pages', '共', 'cn'),
(6, 'pagesize', '条记录', 'cn'),
(7, 'page', '当前第', 'cn'),
(8, 'pagehome', '首页', 'cn'),
(9, 'pageend', '尾页', 'cn'),
(10, 'pagapre', '上一页', 'cn'),
(11, 'pagenext', '下一页', 'cn'),
(12, 'pagego', '转到', 'cn'),
(13, 'previous', '上一条', 'cn'),
(14, 'next', '下一条', 'cn'),
(15, 'nonestr', '没有了', 'cn'),
(17, 'sitemap', '网站地图', 'cn'),
(384, 'order_msg4', '表单已经处理，我们会及时和你联系！', 'cn'),
(382, 'order_msg3', '发生错误,该表单已经停止使用,不能添加表单信息', 'cn'),
(380, 'order_msg2', '表单不能为空', 'cn'),
(376, 'index', '首页', 'cn'),
(377, 'book', '留言本', 'cn'),
(378, 'order_msg1', '发生错误，无法处理该表单，清重新操作！', 'cn'),
(84, 'member_msg', '请先登录', 'cn'),
(85, 'member_msg2', '验证码不正确', 'cn'),
(86, 'member_smg3', '用户名或密码不能为空', 'cn'),
(87, 'member_msg3', '用户名名或密码不正确', 'cn'),
(88, 'member_msg4', '登录失败,该账号已被锁定', 'cn'),
(89, 'member_msg5', '会员注册已经暂停', 'cn'),
(90, 'member_msg6', '用户名只能是字母数字，4以上长度', 'cn'),
(91, 'member_msg7', '昵称只能是字母数字，4以上长度', 'cn'),
(92, 'member_msg8', '密码不能为空', 'cn'),
(93, 'member_msg9', '两次密码不一样', 'cn'),
(94, 'member_msg10', '邮箱不正确', 'cn'),
(95, 'member_msg11', '该用户名不能注册', 'cn'),
(96, 'member_msg12', '已经存在相同的用户名，请更换用户名', 'cn'),
(97, 'member_msg13', '该邮箱已经被使用,请更换', 'cn'),
(98, 'member_msg14', '用户注册成功', 'cn'),
(99, 'msg_info', '不存在flash引导页模板', 'cn'),
(100, 'msg_info2', '不存在【@】语言首页模板', 'cn'),
(101, 'msg_info3', '找不到【@】语言模板文件', 'cn'),
(102, 'msg_info4', '请先生成【@】语言首页', 'cn'),
(103, 'msg_info5', '请先更新栏目缓存', 'cn'),
(104, 'msg_info6', '请先更新频道缓存', 'cn'),
(105, 'msg_info7', '你当前的会员权限不能浏览', 'cn'),
(106, 'msg_info8', '该内容未审核,还不能浏览', 'cn'),
(107, 'msg_info9', '还没有添加内容', 'cn'),
(422, 'msg_info4', 'Please generate【@】Language Home', 'en'),
(421, 'msg_info3', 'Unable to find【@】language template file', 'en'),
(420, 'msg_info2', 'Does not exist【@】Language Home template', 'en'),
(419, 'msg_info', 'Not flash boot Pages template', 'en'),
(418, 'member_msg14', 'Register success', 'en'),
(417, 'member_msg13', 'he mailbox is already in use, replace', 'en'),
(439, 'member_msg25', 'Deleted successfully', 'en'),
(438, 'member_msg24', 'Advisory modified successfully', 'en'),
(437, 'member_msg23', 'The content can not be empty', 'en'),
(436, 'member_msg22', 'The consultation has been processed, please re-add', 'en'),
(435, 'member_msg21', 'The consultation does not exist', 'en'),
(434, 'member_msg20', 'Consulting successfully added', 'en'),
(433, 'member_msg19', 'Title or content can not be empty', 'en'),
(432, 'msg_info10', 'Parameter passing errors', 'en'),
(431, 'member_msg18', 'Modified successfully', 'en'),
(430, 'member_msg17', 'From form submission', 'en'),
(429, 'member_msg16', 'Phone must be numeric', 'en'),
(428, 'member_msg15', 'QQ number is incorrect', 'en'),
(427, 'msg_info9', 'Has not yet added content', 'en'),
(426, 'msg_info8', 'The content is not audited, but also can not browse', 'en'),
(425, 'msg_info7', 'Your current membership privileges can not browse', 'en'),
(424, 'msg_info6', 'Please update the channel cache', 'en'),
(423, 'msg_info5', 'Please update section cache', 'en'),
(416, 'member_msg12', 'The same user name already exists, replace the user name', 'en'),
(415, 'member_msg11', 'The user name can not be registered', 'en'),
(414, 'member_msg10', 'E-mail is incorrect', 'en'),
(413, 'member_msg9', 'Not the same password twice', 'en'),
(412, 'member_msg8', 'Password can not be empty', 'en'),
(411, 'member_msg7', 'The nickname can only contain alphanumeric, length of 4 or more', 'en'),
(410, 'member_msg6', 'The user name can only be alphanumeric longer than 4', 'en'),
(409, 'member_msg5', 'Member registration has been suspended', 'en'),
(408, 'member_msg4', 'Login failed, the account has been locked', 'en'),
(266, 'member_msg15', 'QQ号码不正确', 'cn'),
(267, 'member_msg16', '手机必须为数字', 'cn'),
(268, 'member_msg17', '请从表单提交', 'cn'),
(269, 'member_msg18', '修改成功', 'cn'),
(407, 'member_msg3', 'Username name or password is incorrect', 'en'),
(406, 'member_smg3', 'User name or password can not be empty', 'en'),
(274, 'msg_info10', '参数传递错误,请重新操作', 'cn'),
(276, 'member_msg19', '标题或内容不能为空', 'cn'),
(277, 'member_msg20', '咨询添加成功', 'cn'),
(278, 'member_msg21', '不存在该咨询', 'cn'),
(279, 'member_msg22', '咨询已经处理,请重新添加', 'cn'),
(280, 'member_msg23', '内容不能为空', 'cn'),
(281, 'member_msg24', '咨询修改成功', 'cn'),
(282, 'member_msg25', '删除成功', 'cn'),
(283, 'member_msg26', '原始密码不正确', 'cn'),
(284, 'member_msg27', '已经退出', 'cn'),
(449, 'member_msg28', 'User Center', 'en'),
(450, 'member_out', '退出登陆', 'cn'),
(451, 'member_out', 'Logout', 'en'),
(447, 'member_wel', 'Welcome back!', 'en'),
(448, 'member_msg28', '用户中心', 'cn'),
(445, 'book_msg4', 'Successfully added', 'en'),
(444, 'book_msg3', 'The message can not be empty', 'en'),
(443, 'book_msg2', 'Message title can not be empty', 'en'),
(442, 'book_msg1', 'The Guestbook can not use', 'en'),
(441, 'member_msg27', 'Has withdrawn from the', 'en'),
(440, 'member_msg26', 'Original password is incorrect', 'en'),
(386, 'pages', 'Common', 'en'),
(387, 'pagesize', 'Records', 'en'),
(388, 'page', 'Current', 'en'),
(389, 'pagehome', 'Home', 'en'),
(390, 'pageend', 'Last', 'en'),
(391, 'pagapre', 'Previous', 'en'),
(392, 'pagenext', 'Next', 'en'),
(393, 'pagego', 'Go to', 'en'),
(405, 'member_msg2', 'Incorrect verification code', 'en'),
(404, 'member_msg', 'Please login', 'en'),
(350, 'book_msg1', '留言本不能使用', 'cn'),
(351, 'book_msg2', '留言标题不能为空', 'cn'),
(352, 'book_msg3', '留言内容不能为空', 'cn'),
(353, 'book_msg4', '添加成功', 'cn'),
(403, 'order_msg1', 'An error occurs, can not process the form, clear!', 'en'),
(402, 'book', 'Guestbook', 'en'),
(401, 'index', 'Home', 'en'),
(400, 'order_msg2', 'The form can not be empty', 'en'),
(399, 'order_msg3', 'An error occurs, the form has to stop using, you can not add form', 'en'),
(398, 'order_msg4', 'The form has been processed, we will promptly contact you!', 'en'),
(397, 'sitemap', 'Site Map', 'en'),
(396, 'nonestr', 'No', 'en'),
(395, 'next', 'Next', 'en'),
(446, 'member_wel', '欢迎你回来!', 'cn'),
(394, 'previous', 'Previous', 'en'),
(452, 'code', '验证码：', 'cn'),
(453, 'code', 'Code:', 'en'),
(454, 'code_info', '看不清？更换一张', 'cn'),
(455, 'code_info', 'See? Replacing a', 'en'),
(456, 'form_submit', '提交', 'cn'),
(457, 'form_submit', 'submit', 'en'),
(458, 'form_reset', '重置', 'cn'),
(459, 'form_reset', 'reset', 'en');","lang_lang_data");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."keywords`;","");
$data[]=array("CREATE TABLE `".DB_PRE."keywords` (
  `id` mediumint(8) NOT NULL auto_increment,
  `keywords` varchar(60) character set utf8 NOT NULL,
  `wordsurl` varchar(60) character set utf8 NOT NULL,
  `lang` varchar(60) character set ucs2 NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;","keywords");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."flash_ad`;","");
$data[]=array("CREATE TABLE `".DB_PRE."flash_ad` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `pic` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `pic_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `pic_text` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `pic_order` mediumint(8) NOT NULL DEFAULT '10',
  `lang` varchar(60) NOT NULL,
  `cate_id` mediumint(8) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;","flash_ad");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."flash_info`;","");
$data[]=array("CREATE TABLE `".DB_PRE."flash_info` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `flash_width` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `flash_height` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `flash_style` mediumint(8) NOT NULL,
  `lang` varchar(60) CHARACTER SET utf8 NOT NULL,
  `cate_id` mediumint(8) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;","flash_info");


$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."book`;","");
$data[]=array("CREATE TABLE `".DB_PRE."book` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `book_name` varchar(60) DEFAULT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `book_type` mediumint(8) NOT NULL DEFAULT '0' COMMENT '0-留言,1-投诉,2-询问,3-售后',
  `pr_id` mediumint(8) DEFAULT NULL COMMENT '产品',
  `book_title` varchar(60) NOT NULL,
  `book_content` text NOT NULL,
  `addtime` varchar(60) NOT NULL,
  `reply` text,
  `verify` mediumint(8) NOT NULL DEFAULT '0',
  `lang` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;","book");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."book_info`;","");
$data[]=array("CREATE TABLE `".DB_PRE."book_info` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `is_book` varchar(60) DEFAULT NULL,
  `book_pos` varchar(60) DEFAULT NULL,
  `book_verify` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;","book_info");
$data[]=array("INSERT INTO `".DB_PRE."book_info` (`id`, `is_book`, `book_pos`, `book_verify`) VALUES
(1, '1', '2', '0');","book_info_data");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."uppics`;","");
$data[]=array("CREATE TABLE `".DB_PRE."uppics` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `pic_name` varchar(60) NOT NULL COMMENT '图片名称',
  `pic_url` varchar(255) DEFAULT NULL COMMENT '外部链接',
  `pic_ext` varchar(60) NOT NULL COMMENT '图片后缀',
  `pic_alt` varchar(255) DEFAULT NULL COMMENT '图片alt',
  `pic_size` varchar(60) DEFAULT NULL,
  `pic_path` varchar(60) DEFAULT NULL COMMENT '图片路径',
  `pic_time` varchar(60) DEFAULT NULL COMMENT '图片上传修改时间',
  `pic_thumb` varchar(60) DEFAULT NULL COMMENT '缩略图',
  `pic_cate` mediumint(8) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;","uppics");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."webjob`;","");
$data[]=array("CREATE TABLE `".DB_PRE."webjob` (
  `id` mediumint(8) NOT NULL,
  `jobname` varchar(255) DEFAULT NULL,
  `jobsex` varchar(255) DEFAULT NULL,
  `jobmoth` varchar(255) DEFAULT NULL,
  `jobjg` varchar(255) DEFAULT NULL,
  `jobxl` varchar(255) DEFAULT NULL,
  `jobzy` varchar(255) DEFAULT NULL,
  `jobbyyx` varchar(255) DEFAULT NULL,
  `jobphone` varchar(255) DEFAULT NULL,
  `jobmail` varchar(255) DEFAULT NULL,
  `jobhj` varchar(255) DEFAULT NULL,
  `jobgzjl` varchar(255) DEFAULT NULL,
  `jobzyjn` varchar(255) DEFAULT NULL,
  `jobyyah` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;","webjob");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."upfiles`;","");
$data[]=array("CREATE TABLE `".DB_PRE."upfiles` (
`id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`file_info` VARCHAR( 255 ) NULL ,
`file_ext` VARCHAR( 255 ) NULL ,
`file_size` MEDIUMINT( 8 ) NULL DEFAULT '0',
`file_path` VARCHAR( 255 ) NULL ,
`file_time` VARCHAR( 255 ) NULL ,
`hits` MEDIUMINT( 8 ) NOT NULL DEFAULT '0'
) ENGINE = MYISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;","upfiles");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."uppic_cate`;","");
$data[]=array("CREATE TABLE `".DB_PRE."uppic_cate` (
`id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`cate_name` VARCHAR( 255 ) NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;","uppic_cate");
$data[]=array("INSERT INTO `".DB_PRE."uppic_cate` (`id`, `cate_name`) VALUES
(1, '产品图片'),
(2, '下载图片'),
(3, '其它图片');
","");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."flash_ad_cate`;","");
$data[]=array("CREATE TABLE `".DB_PRE."flash_ad_cate` (
`id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`cate_name` VARCHAR( 60 ) NOT NULL ,
`cate_tpl_id` MEDIUMINT( 8 ) NULL DEFAULT '0',
`is_disable` MEDIUMINT( 8 ) NULL DEFAULT '0'
) ENGINE = MYISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;","flash_ad_cate");
$data[]=array("INSERT INTO `".DB_PRE."flash_ad_cate` (`id` ,`cate_name` ,`cate_tpl_id` ,`is_disable`)VALUES (NULL , '默认', '2', '1');","");

$data[]=array("DROP TABLE IF EXISTS `".DB_PRE."mx_form`;","");
$data[]=array("CREATE TABLE `".DB_PRE."mx_form` (
  `id` mediumint(8) NOT NULL,
  `form_id` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = MYISAM DEFAULT CHARSET=utf8;","mx_form");
?>
