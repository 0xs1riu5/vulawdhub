INSERT INTO `blue_ann_cat` (`cid`, `cat_name`, `show_order`) VALUES
(1, '网站公告', 0),
(2, '付费推广', 0),
(3, '帮助中心', 0),
(4, '关于本站', 0);

INSERT INTO `blue_area` (`area_id`, `area_name`, `parentid`, `area_indent`, `ishavechild`, `show_order`) VALUES ('1', '地区一', '0', '0', '0', '0');

INSERT INTO `blue_card_type` (`id`, `name`, `value`, `price`, `is_close`) VALUES
(1, '便民卡', 100, 30, 0);

INSERT INTO `blue_config` (`name`, `value`) VALUES
('site_name', '演示网站'),
('site_url', 'http://www.bluecms.net'),
('description', ''),
('keywords', ''),
('tel', '1234567|1234567'),
('icp', ''),
('count', ''),
('isclose', '0'),
('reason', ''),
('cookie_hash', ''),
('url_rewrite', '0'),
('qq', '1234567|1234567'),
('qq_group', '1234567|1234567'),
('right', 'BlueCMS — 第一款免费开源的专业地方门户系统，专注于地方门户的CMS！'),
('info_is_check', '0'),
('comment_is_check', '0'),
('news_is_check', '0'),
('is_gzip', '0');

INSERT INTO `blue_pay` (`id`, `code`, `name`, `userid`, `key`, `email`, `description`, `fee`, `logo`, `is_open`, `show_order`) VALUES
(1, 'alipay', '支付宝', '', '', '', '支付宝网站(www.alipay.com)是国内先进的网上支付平台，由全球最佳B2B公司阿里巴巴公司创办，致力于为网络交易用户提供优质的安全支付服务。', 0.00, 'images/alipay.jpg', 1, 0),
(2, 'bank', '银行转账', '', '', '', '账号:\r\n户名:dd\r\n开户行:', 0.00, '', 1, 0);

INSERT INTO `blue_service` (`id`, `name`, `type`, `service`, `price`) VALUES
(1, '大类置顶', 'info', 'top2', '10.00'),
(2, '小类置顶', 'info', 'top1', '5.00'),
(3, '分类信息推荐', 'info', 'rec', '10.00'),
(4, '分类信息头条', 'info', 'head_line', '10.00'),
(5, '大类置顶', 'company', 'top2', '10.00'),
(6, '小类置顶', 'company', 'top1', '5.00'),
(7, '商家黄页推荐', 'company', 'rec', '10.00'),
(8, '商家黄页头条', 'company', 'head_line', '10.00');

INSERT INTO `blue_task` (`id`, `name`, `last_time`, `exp`) VALUES
(1, 'update_info', 0, 1);