
--
-- 表的结构 `ecs_account_other_log`
--

DROP TABLE IF EXISTS `ecs_account_other_log`;
CREATE TABLE `ecs_account_other_log` (
  `user_id` mediumint(8) NOT NULL,
  `order_id` mediumint(8) NOT NULL,
  `order_sn` varchar(20) NOT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pay_type` varchar(20) NOT NULL,
  `pay_time` varchar(10) NOT NULL,
  `change_desc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ecs_callback_status`
--

DROP TABLE IF EXISTS `ecs_callback_status`;
CREATE TABLE `ecs_callback_status` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `msg_id` varchar(50) DEFAULT '',
  `type` varchar(100) DEFAULT NULL,
  `status` enum('true','false','running') DEFAULT 'false',
  `type_id` varchar(50) DEFAULT NULL,
  `date_time` int(11) DEFAULT NULL,
  `data` text,
  `disabled` enum('true','false') DEFAULT 'false',
  `times` tinyint(4) DEFAULT '0',
  `method` varchar(100) NOT NULL,
  `http_type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ind_type_type_id` (`type`,`type_id`) USING BTREE,
  KEY `date_time` (`date_time`),
  KEY `ind_status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ecs_coincidence`
--

DROP TABLE IF EXISTS `ecs_coincidence`;
CREATE TABLE `ecs_coincidence` (
  `type_id` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL,
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`type_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ecs_shop_bind`
--

DROP TABLE IF EXISTS `ecs_shop_bind`;
CREATE TABLE `ecs_shop_bind` (
  `shop_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `node_id` varchar(32) DEFAULT NULL COMMENT '节点',
  `node_type` varchar(128) DEFAULT NULL COMMENT '节点类型',
  `status` enum('bind','unbind') DEFAULT NULL COMMENT '状态',
  `app_url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `ecs_admin_user` ADD `passport_uid` VARCHAR(20);
ALTER TABLE `ecs_admin_user` ADD `yq_create_time` SMALLINT( 10 ) UNSIGNED;
ALTER TABLE `ecs_goods` ADD `virtual_sales` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';

