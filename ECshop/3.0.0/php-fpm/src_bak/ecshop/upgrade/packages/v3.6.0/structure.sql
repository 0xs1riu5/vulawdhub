-- --------------------------------------------------------

--
-- 表的结构 `ecs_config`
--

DROP TABLE IF EXISTS `ecs_config`;
CREATE TABLE `ecs_config` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `config` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- 表的结构 `ecs_cert`
--

DROP TABLE IF EXISTS `ecs_cert`;
CREATE TABLE `ecs_cert` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `config_id` tinyint(4) NOT NULL COMMENT '配置id',
  `file` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cert_config_id_index` (`config_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- 表的结构 `ecs_device`
--

DROP TABLE IF EXISTS `ecs_device`;
CREATE TABLE `ecs_device` (
  `user_id` int(11) NOT NULL,
  `device_id` varchar(200) NOT NULL COMMENT '设备id',
  `device_type` varchar(200) NOT NULL COMMENT '设备类型',
  `platform_type` varchar(200) NOT NULL COMMENT '平台类型',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '推送开关 0:关闭 1:开启 默认开启',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `device_user_id_unique` (`user_id`),
  KEY `device_device_id_index` (`device_id`),
  KEY `device_device_type_index` (`device_type`),
  KEY `device_platform_type_index` (`platform_type`),
  KEY `device_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ecs_order_review`
--

DROP TABLE IF EXISTS `ecs_order_review`;
CREATE TABLE `ecs_order_review` (
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  UNIQUE KEY `order_review_user_id_order_id_goods_id_unique` (`user_id`,`order_id`,`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ecs_push`
--

DROP TABLE IF EXISTS `ecs_push`;
CREATE TABLE `ecs_push` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL COMMENT '标题',
  `content` varchar(200) NOT NULL COMMENT '内容',
  `photo` varchar(200) DEFAULT NULL COMMENT '图片',
  `objectId` varchar(200) DEFAULT NULL COMMENT 'leancloud返回的objectId',
  `link` varchar(200) DEFAULT NULL COMMENT '链接',
  `platform` tinyint(4) NOT NULL DEFAULT '3' COMMENT '平台类型',
  `push_type` tinyint(4) DEFAULT '0' COMMENT '任务类型 1 定时任务 0 即时推送',
  `message_type` tinyint(4) DEFAULT '1' COMMENT '消息类型 1　系统消息 ２ 物流消息',
  `isPush` tinyint(4) DEFAULT '0' COMMENT '定时任务是否已经推送',
  `push_at` timestamp NULL DEFAULT NULL COMMENT '定时推送时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 0:关闭 1:开启 默认开启',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `push_user_id_index` (`user_id`),
  KEY `push_title_index` (`title`),
  KEY `push_content_index` (`content`),
  KEY `push_photo_index` (`photo`),
  KEY `push_objectid_index` (`objectId`),
  KEY `push_link_index` (`link`),
  KEY `push_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ecs_search_history`
--

DROP TABLE IF EXISTS `ecs_search_history`;
CREATE TABLE `ecs_search_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` char(50) NOT NULL,
  `count` int(11) NOT NULL,
  `type` enum('goods','store') NOT NULL,
  `store_id` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ecs_sns`
--

DROP TABLE IF EXISTS `ecs_sns`;
CREATE TABLE `ecs_sns` (
  `user_id` int(11) NOT NULL,
  `open_id` varchar(255) NOT NULL,
  `vendor` tinyint(4) NOT NULL COMMENT '第三方平台类型',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `sns_user_id_unique` (`user_id`),
  KEY `sns_open_id_index` (`open_id`),
  KEY `sns_vendor_index` (`vendor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ecs_user_reg_status`
--

DROP TABLE IF EXISTS `ecs_user_reg_status`;
CREATE TABLE `ecs_user_reg_status` (
  `user_id` int(11) NOT NULL,
  `is_completed` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ecs_version`
--

DROP TABLE IF EXISTS `ecs_version`;
CREATE TABLE `ecs_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `platform` tinyint(4) NOT NULL DEFAULT '3',
  `version` char(50) DEFAULT NULL,
  `url` char(200) DEFAULT NULL,
  `content` char(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


