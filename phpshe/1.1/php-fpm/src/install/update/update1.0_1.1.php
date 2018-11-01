<?php
include('../../common.php');
pe_lead('hook/cache.hook.php');
//更新ad表
$db->query("ALTER TABLE `".dbpre."ad` ADD `ad_position` VARCHAR( 15 ) NOT NULL AFTER `ad_url`");
$db->query("UPDATE `".dbpre."ad` set `ad_position` = 'index_jdt'");
$db->query("UPDATE `".dbpre."ad` SET `ad_logo` = REPLACE(`ad_logo`, '{$pe['host_root']}', '')");
//更新article表
$db->query("ALTER TABLE `".dbpre."article` CHANGE `category_id` `class_id` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0'");
$db->query("ALTER TABLE `".dbpre."article` DROP INDEX `category_id`");
$db->query("ALTER TABLE `".dbpre."article` ADD INDEX ( `class_id` )");
$db->query("UPDATE `".dbpre."article` set `class_id` = 1");
//更新ask表
$db->query("ALTER TABLE `".dbpre."ask` CHANGE `user_name` `user_name` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
//更新category表
$db->query("ALTER TABLE `".dbpre."category` DROP `category_type`");
//更新class表
$db->query("CREATE TABLE IF NOT EXISTS `".dbpre."class` (`class_id` smallint(5) unsigned NOT NULL auto_increment, `class_name` varchar(30) NOT NULL, `class_order` smallint(5) unsigned NOT NULL default '0', PRIMARY KEY  (`class_id`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;");
$db->query("INSERT INTO `".dbpre."class` (`class_id`, `class_name`, `class_order`) VALUES(1, '网站公告', 0)");
//更新comment表
$db->query("ALTER TABLE `".dbpre."comment` CHANGE `user_name` `user_name` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
//更新order表
$db->query("ALTER TABLE `".dbpre."order` CHANGE `order_paytype` `order_payway` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'alipay_js'");
$db->query("ALTER TABLE `order` CHANGE `user_tname` `user_tname` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
//更新payway表
$db->query("CREATE TABLE IF NOT EXISTS `".dbpre."payway` (`payway_id` tinyint(3) unsigned NOT NULL auto_increment, `payway_name` varchar(10) NOT NULL, `payway_mark` varchar(15) NOT NULL, `payway_logo` varchar(100) NOT NULL, `payway_model` text NOT NULL, `payway_config` text NOT NULL, `payway_text` varchar(255) NOT NULL, `payway_order` tinyint(3) unsigned NOT NULL default '0', `payway_state` tinyint(1) unsigned NOT NULL default '1', PRIMARY KEY  (`payway_id`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3");
$db->query("INSERT INTO `".dbpre."payway` (`payway_id`, `payway_name`, `payway_mark`, `payway_logo`, `payway_model`, `payway_config`, `payway_text`, `payway_order`, `payway_state`) VALUES (1, '支付宝', 'alipay', 'include/plugin/payway/alipay/logo.gif', 'a:4:{s:12:\"alipay_class\";a:3:{s:4:\"name\";s:15:\"支付宝接口\";s:9:\"form_type\";s:6:\"select\";s:10:\"form_value\";a:3:{s:9:\"alipay_js\";s:18:\"即时到账收款\";s:9:\"alipay_db\";s:18:\"担保交易收款\";s:10:\"alipay_sgn\";s:15:\"双功能收款\";}}s:11:\"alipay_name\";a:2:{s:4:\"name\";s:15:\"支付宝账户\";s:9:\"form_type\";s:4:\"text\";}s:10:\"alipay_pid\";a:2:{s:4:\"name\";s:18:\"合作者身份Pid\";s:9:\"form_type\";s:4:\"text\";}s:10:\"alipay_key\";a:2:{s:4:\"name\";s:18:\"安全校验码Key\";s:9:\"form_type\";s:4:\"text\";}}', 'a:4:{s:12:\"alipay_class\";s:10:\"alipay_sgn\";s:11:\"alipay_name\";s:16:\"koyshe@gmail.com\";s:10:\"alipay_pid\";s:16:\"2088102457797916\";s:10:\"alipay_key\";s:32:\"esfsclzgahxncgzi3bbe7giwa2ywxyv3\";}', '国内领先的第三方支付平台，为电子商务提供“简单、安全、快速”的在线支付解决方案。', 0, 1), (2, '银行转账/汇款', 'bank', 'include/plugin/payway/bank/logo.gif', 'a:1:{s:9:\"bank_text\";a:2:{s:4:\"name\";s:12:\"收款信息\";s:9:\"form_type\";s:8:\"textarea\";}}', 'a:1:{s:9:\"bank_text\";s:130:\"建设银行 621700254000005xxxx 刘某某\r\n工商银行 621700254000005xxxx 刘某某\r\n农业银行 621700254000005xxxx 刘某某\";}', '当您提交订单后，请到银行汇款所购商品款项，待款项到达后我们安排发货。', 0, 1)");
//更新product表
$db->query("ALTER TABLE `".dbpre."product` ADD `product_istuijian` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商品是否推荐' AFTER `product_commentnum`");
$db->query("UPDATE `".dbpre."product` SET `product_logo` = REPLACE(`product_logo`, '{$pe['host_root']}', '')");
//更新setting表
$db->query("ALTER TABLE `".dbpre."setting` ADD INDEX ( `setting_key` )");
$db->query("DELETE from `".dbpre."setting` where `setting_key` in('alipay_name','alipay_pid','alipay_key')");
$db->query("INSERT INTO `".dbpre."setting` (`setting_key`, `setting_value`) VALUES ('web_phone', '15839823500'), ('web_qq', '1318321,1517735'), ('web_weibo', ''), ('web_logo', '')");
$db->query("UPDATE `".dbpre."setting` set `setting_value` = 'default' where `setting_key` = 'web_tpl'");
//更新tag表
$db->query("DROP TABLE `".dbpre."tag`");
//更新user表
$db->query("ALTER TABLE `".dbpre."user` ADD `user_tel` VARCHAR( 20 ) NOT NULL COMMENT '固定电话' AFTER `user_phone`");
//更新数据库
$db->query("ALTER DATABASE `".dbpre."{$pe['db_name']}` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
//更新cart表
$db->query("ALTER TABLE `".dbpre."cart` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
//更新collect表
$db->query("ALTER TABLE `".dbpre."collect` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
cache_write();
pe_success('数据库更新完成！');
?>