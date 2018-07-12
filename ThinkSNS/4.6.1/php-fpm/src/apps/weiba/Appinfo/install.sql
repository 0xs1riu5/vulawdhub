/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50516
Source Host           : 127.0.0.1:3306
Source Database       : sociax_team

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2012-06-06 16:10:35
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `ts_weiba`
-- ----------------------------
DROP TABLE IF EXISTS `ts_weiba`;
CREATE TABLE `ts_weiba` (
  `weiba_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '微吧ID',
  `cid` int(11) DEFAULT NULL,
  `weiba_name` varchar(255) NOT NULL DEFAULT '微吧名称',
  `uid` int(11) NOT NULL COMMENT '创建者ID',
  `ctime` int(11) NOT NULL COMMENT '创建时间',
  `logo` varchar(255) DEFAULT NULL COMMENT '微吧logo',
  `intro` text COMMENT '微吧简介',
  `who_can_post` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发帖权限 0-所有人 1-仅成员',
  `who_can_reply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '回帖权限 0-所有人 1-仅成员',
  `follower_count` int(10) DEFAULT '0' COMMENT '成员数',
  `thread_count` int(10) DEFAULT '0' COMMENT '帖子数',
  `admin_uid` int(11) NOT NULL COMMENT '超级圈主uid',
  `recommend` tinyint(1) DEFAULT '0' COMMENT '是否设为推荐（热门）0-否，1-是',
  `status` tinyint(1) DEFAULT '0' COMMENT '是否通过审核：0-未通过，1-已通过',
  `is_del` int(2) DEFAULT '0' COMMENT '是否删除 默认为0',
  `notify` varchar(255) DEFAULT NULL COMMENT '微吧公告',
  PRIMARY KEY (`weiba_id`),
  KEY `recommend` (`recommend`,`is_del`) USING BTREE,
  KEY `count` (`is_del`,`follower_count`,`thread_count`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ts_weiba_follow`
-- ----------------------------
DROP TABLE IF EXISTS `ts_weiba_follow`;
CREATE TABLE `ts_weiba_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `weiba_id` int(11) NOT NULL COMMENT '微吧ID',
  `follower_uid` int(11) NOT NULL COMMENT '成员ID',
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '等级 1-粉丝 2-小主 3-圈主',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- ----------------------------
-- Table structure for `ts_weiba_post`
-- ----------------------------
DROP TABLE IF EXISTS `ts_weiba_post`;
CREATE TABLE `ts_weiba_post` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '帖子ID',
  `weiba_id` int(11) NOT NULL COMMENT '所属微吧ID',
  `post_uid` int(11) NOT NULL COMMENT '发表者uid',
  `title` varchar(255) NOT NULL COMMENT '帖子标题',
  `content` text NOT NULL COMMENT '帖子内容',
  `post_time` int(11) NOT NULL COMMENT '发表时间',
  `reply_count` int(10) DEFAULT '0' COMMENT '回复数',
  `read_count` int(10) DEFAULT '0' COMMENT '浏览数',
  `last_reply_uid` int(11) DEFAULT '0' COMMENT '最后回复人',
  `last_reply_time` int(11) DEFAULT '0' COMMENT '最后回复时间',
  `digest` tinyint(1) DEFAULT '0' COMMENT '全局精华 0-否 1-是',
  `top` tinyint(1) DEFAULT '0' COMMENT '置顶帖 0-否 1-吧内 2-全局',
  `lock` tinyint(1) DEFAULT '0' COMMENT '锁帖（不允许回复）0-否 1-是',
  `recommend` tinyint(1) DEFAULT '0' COMMENT '是否设为推荐',
  `recommend_time` int(11) DEFAULT '0' COMMENT '设为推荐的时间',
  `is_del` tinyint(2) DEFAULT '0' COMMENT '是否已删除 0-否 1-是',
  `feed_id` int(11) NOT NULL COMMENT '对应的分享ID',
  `reply_all_count` int(11) NOT NULL DEFAULT '0' COMMENT '全部评论数目',
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ts_weiba_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ts_weiba_reply`;
CREATE TABLE `ts_weiba_reply` (
  `reply_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '回复ID',
  `weiba_id` int(11) NOT NULL COMMENT '所属微吧',
  `post_id` int(11) NOT NULL COMMENT '所属帖子ID',
  `post_uid` int(11) NOT NULL COMMENT '帖子作者UID',
  `uid` int(11) NOT NULL COMMENT '回复者ID',
  `to_reply_id` int(11) NOT NULL DEFAULT '0' COMMENT '回复的评论id',
  `to_uid` int(11) NOT NULL DEFAULT '0' COMMENT '被回复的评论的作者的uid',
  `ctime` int(11) NOT NULL COMMENT '回复时间',
  `content` text NOT NULL COMMENT '回复内容',
  `is_del` tinyint(2) DEFAULT '0' COMMENT '是否已删除 0-否 1-是',
  `comment_id` int(11) NOT NULL COMMENT '对应的分享评论ID',
  `storey` int(11) NOT NULL DEFAULT '0' COMMENT '绝对楼层',
  PRIMARY KEY (`reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sociax_contact_user
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_weiba_category`
-- ----------------------------
DROP TABLE IF EXISTS `ts_weiba_category`;
CREATE TABLE `ts_weiba_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of ts_weiba_category
-- ----------------------------
INSERT INTO `ts_weiba_category` VALUES ('1', '分类1');
INSERT INTO `ts_weiba_category` VALUES ('2', '分类2');
INSERT INTO `ts_weiba_category` VALUES ('3', '分类3');


-- /* 插入system_data数据表数据 */
-- INSERT INTO ts_system_data (`list`, `key`, `value`, `mtime`) VALUES ('pageKey', 'contact_Admin_index', 'a:6:{s:3:\"key\";a:1:{s:13:\"contact_field\";s:13:\"contact_field\";}s:8:\"key_name\";a:1:{s:13:\"contact_field\";s:12:\"显示字段\";}s:8:\"key_type\";a:1:{s:13:\"contact_field\";s:8:\"checkbox\";}s:11:\"key_default\";a:1:{s:13:\"contact_field\";s:0:\"\";}s:9:\"key_tishi\";a:1:{s:13:\"contact_field\";s:0:\"\";}s:14:\"key_javascript\";a:1:{s:13:\"contact_field\";s:0:\"\";}}', '2012-06-07 00:16:18');
-- INSERT INTO ts_system_data (`list`, `key`, `value`, `mtime`) VALUES ('pageKey', 'contact_Admin_getContactList', 'a:4:{s:3:\"key\";a:4:{s:3:\"uid\";s:3:\"uid\";s:5:\"uname\";s:5:\"uname\";s:5:\"email\";s:5:\"email\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:4:{s:3:\"uid\";s:9:\"用户UID\";s:5:\"uname\";s:6:\"姓名\";s:5:\"email\";s:6:\"邮箱\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:4:{s:3:\"uid\";s:1:\"0\";s:5:\"uname\";s:1:\"0\";s:5:\"email\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:4:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-06-07 00:17:33');
-- INSERT INTO ts_system_data (`list`, `key`, `value`, `mtime`) VALUES ('searchPageKey', 'S_contact_Admin_getContactList', 'a:5:{s:3:\"key\";a:3:{s:3:\"uid\";s:3:\"uid\";s:5:\"uname\";s:5:\"uname\";s:5:\"email\";s:5:\"email\";}s:8:\"key_name\";a:3:{s:3:\"uid\";s:9:\"用户UID\";s:5:\"uname\";s:6:\"姓名\";s:5:\"email\";s:6:\"邮件\";}s:8:\"key_type\";a:3:{s:3:\"uid\";s:4:\"text\";s:5:\"uname\";s:4:\"text\";s:5:\"email\";s:4:\"text\";}s:9:\"key_tishi\";a:3:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";}s:14:\"key_javascript\";a:3:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";}}', '2012-06-07 00:17:58');
-- INSERT INTO ts_system_data (`list`, `key`, `value`, `mtime`) VALUES ('pageKey', 'contact_Admin_editPersonProfile', 'a:6:{s:3:\"key\";a:11:{s:3:\"uid\";s:3:\"uid\";s:5:\"uname\";s:5:\"uname\";s:5:\"email\";s:5:\"email\";s:5:\"intro\";s:5:\"intro\";s:6:\"mobile\";s:6:\"mobile\";s:3:\"tel\";s:3:\"tel\";s:5:\"weibo\";s:5:\"weibo\";s:13:\"work_director\";s:13:\"work_director\";s:15:\"work_experience\";s:15:\"work_experience\";s:13:\"work_position\";s:13:\"work_position\";s:12:\"work_project\";s:12:\"work_project\";}s:8:\"key_name\";a:11:{s:3:\"uid\";s:9:\"用户UID\";s:5:\"uname\";s:6:\"姓名\";s:5:\"email\";s:6:\"邮箱\";s:5:\"intro\";s:12:\"个人简介\";s:6:\"mobile\";s:6:\"手机\";s:3:\"tel\";s:6:\"座机\";s:5:\"weibo\";s:12:\"新浪分享\";s:13:\"work_director\";s:12:\"直接主管\";s:15:\"work_experience\";s:12:\"工作经历\";s:13:\"work_position\";s:6:\"职位\";s:12:\"work_project\";s:12:\"项目经验\";}s:8:\"key_type\";a:11:{s:3:\"uid\";s:6:\"hidden\";s:5:\"uname\";s:4:\"word\";s:5:\"email\";s:4:\"word\";s:5:\"intro\";s:8:\"textarea\";s:6:\"mobile\";s:4:\"text\";s:3:\"tel\";s:4:\"text\";s:5:\"weibo\";s:4:\"text\";s:13:\"work_director\";s:4:\"text\";s:15:\"work_experience\";s:8:\"textarea\";s:13:\"work_position\";s:4:\"text\";s:12:\"work_project\";s:8:\"textarea\";}s:11:\"key_default\";a:11:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";s:5:\"intro\";s:0:\"\";s:6:\"mobile\";s:0:\"\";s:3:\"tel\";s:0:\"\";s:5:\"weibo\";s:0:\"\";s:13:\"work_director\";s:0:\"\";s:15:\"work_experience\";s:0:\"\";s:13:\"work_position\";s:0:\"\";s:12:\"work_project\";s:0:\"\";}s:9:\"key_tishi\";a:11:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";s:5:\"intro\";s:0:\"\";s:6:\"mobile\";s:0:\"\";s:3:\"tel\";s:0:\"\";s:5:\"weibo\";s:0:\"\";s:13:\"work_director\";s:0:\"\";s:15:\"work_experience\";s:0:\"\";s:13:\"work_position\";s:0:\"\";s:12:\"work_project\";s:0:\"\";}s:14:\"key_javascript\";a:11:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";s:5:\"intro\";s:0:\"\";s:6:\"mobile\";s:0:\"\";s:3:\"tel\";s:0:\"\";s:5:\"weibo\";s:0:\"\";s:13:\"work_director\";s:0:\"\";s:15:\"work_experience\";s:0:\"\";s:13:\"work_position\";s:0:\"\";s:12:\"work_project\";s:0:\"\";}}', '2012-06-28 18:56:39');

-- ----------------------------
-- 任务
-- ----------------------------
INSERT INTO `ts_task` (`id`, `task_level`, `task_name`, `task_type`, `step_name`, `step_desc`, `condition`, `action`, `ctime`, `reward`, `is_del`, `headface`, `show`) VALUES (23, 2, '进阶任务', 2, '关注1个微吧', '关注1个微吧', '{"weibafollow":1}', '', NULL, '{"exp":5,"score":5,"medal":null}', 0, NULL, '0,1,2');
INSERT INTO `ts_task` (`id`, `task_level`, `task_name`, `task_type`, `step_name`, `step_desc`, `condition`, `action`, `ctime`, `reward`, `is_del`, `headface`, `show`) VALUES (24, 2, '进阶任务', 2, '在微吧发表1篇帖子', '在微吧发表1篇帖子', '{"weibapost":1}', '', NULL, '{"exp":6,"score":6,"medal":{"id":80,"name":"\\u5fae\\u5427\\u5148\\u950b","src":"2015\\/0615\\/15\\/557e84148c636.png"}}', 0, NULL, '0,1,2');
INSERT INTO `ts_task` (`id`, `task_level`, `task_name`, `task_type`, `step_name`, `step_desc`, `condition`, `action`, `ctime`, `reward`, `is_del`, `headface`, `show`) VALUES (35, 3, '达人任务', 2, '至少有1篇精华帖子', '在微吧中至少有1篇帖子被管理员设置为精华帖', '{"weibamarrow":1}', '', NULL, '{"exp":6,"score":6,"medal":{"id":86,"name":"\\u5fae\\u5427\\u8fbe\\u4eba","src":"2015\\/0615\\/15\\/557e8315cc33c.png"}}', 0, NULL, '0,1,2');
INSERT INTO `ts_task` (`id`, `task_level`, `task_name`, `task_type`, `step_name`, `step_desc`, `condition`, `action`, `ctime`, `reward`, `is_del`, `headface`, `show`) VALUES (43, 4, '高手任务', 2, '至少有10篇精华帖子', '在微吧发表10篇以上的精华帖子', '{"weibamarrow":10}', '', NULL, '{"exp":6,"score":6,"medal":{"id":93,"name":"\\u5fae\\u5427\\u725b\\u4eba","src":"2015\\/0615\\/15\\/557e82738b973.png"}}', 0, NULL, '0,1,2');
INSERT INTO `ts_task` (`id`, `task_level`, `task_name`, `task_type`, `step_name`, `step_desc`, `condition`, `action`, `ctime`, `reward`, `is_del`, `headface`, `show`) VALUES (53, 5, '终极任务', 2, '发表100篇以上的精华帖子', '在微吧发表100篇以上的精华帖子', '{"weibamarrow":100}', '', NULL, '{"exp":6,"score":6,"medal":{"id":100,"name":"\\u5fae\\u5427\\u795e\\u4eba","src":"2015\\/0615\\/15\\/557e80d4c572c.png"}}', 0, NULL, '0,1,2');
