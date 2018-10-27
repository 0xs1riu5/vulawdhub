<?php
defined('DT_ADMIN') or exit('Access Denied');
$menu = array(
	array('添加会员', '?moduleid=2&action=add'),
	array('会员列表', '?moduleid=2'),
	array('审核会员', '?moduleid=2&action=check'),
	array(VIP.'管理', '?moduleid=4&file=vip'),
	array('会员升级', '?moduleid=2&file=grade&action=check'),
	array('资料审核', '?moduleid=2&file=validate'),
	array('微信管理', '?moduleid=2&file=weixin'),
	array('一键登录', '?moduleid=2&file=oauth'),
	array('会员组管理', '?moduleid=2&file=group'),
	array('模块设置', '?moduleid=2&file=setting'),
);
$menu_finance = array(
	array($DT['money_name'].'管理', '?moduleid=2&file=record'),
	array($DT['credit_name'].'管理', '?moduleid=2&file=credit'),
	array('短信管理', '?moduleid=2&file=sms&action=record'),
	array('支付记录', '?moduleid=2&file=charge'),
	array('提现记录', '?moduleid=2&file=cash'),
	array('信息支付', '?moduleid=2&file=pay'),
	array('信息打赏', '?moduleid=2&file=award'),
	array('优惠促销', '?moduleid=2&file=promo'),
	array('保证金管理', '?moduleid=2&file=deposit'),
	array('充值卡管理', '?moduleid=2&file=card'),
);
$menu_relate = array(
	array('在线交谈', '?moduleid=2&file=chat'),
	array('站内信件', '?moduleid=2&file=message'),
	array('电子邮件', '?moduleid=2&file=sendmail&action=record'),
	array('手机短信', '?moduleid=2&file=sendsms&action=record'),
	array('客服中心', '?moduleid=2&file=ask'),
	array('贸易提醒', '?moduleid=2&file=alert'),
	array('邮件订阅', '?moduleid=2&file=mail'),
	array('商机收藏', '?moduleid=2&file=favorite'),
	array('会员商友', '?moduleid=2&file=friend'),
	array('收货地址', '?moduleid=2&file=address'),
	array('在线会员', '?moduleid=2&file=online'),
	array('登录日志', '?moduleid=2&file=loginlog'),
);
if(!$_founder) unset($menu_relate[7]);
?>