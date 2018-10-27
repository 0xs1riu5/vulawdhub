<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
$L['message_limit'] = '今日可发送{V0}次 当前已发送{V1}次';
$L['com_not_member'] = '无法完成操作，该企业未注册本站会员';
$L['send_self'] = '请不要给自己留言';
$L['price_self'] = '请不要给自己报价';
$L['inquiry_self'] = '请不要给自己询价';
$L['buy_self'] = '您不能购买自己的商品';
$L['sign_self'] = '您不能报名自己发布的展会';
$L['has_expired'] = '此信息已过期';
$L['not_exists'] = '信息不存在';

$L['msg_type_title'] = '请填写主题';
$L['msg_type_content'] = '请填写内容';
$L['msg_type_truename'] = '请填写联系人';
$L['msg_type_email'] = '请填写正确的电子邮件';
$L['msg_type_telephone'] = '请填写联系电话';
$L['msg_type_address'] = '请填写联系地址';
$L['msg_type_postcode'] = '请填写邮政编码';
$L['msg_type_mobile'] = '请填写手机号码';
$L['msg_type_express'] = '请填写期望物流';

$L['msg_message_success'] = '留言发送成功';
$L['msg_member_failed'] = '留言发送失败，对方可能拒绝您的留言';
$L['msg_guest_failed'] = '留言发送失败，对方可能拒绝非会员的留言';
$L['msg_price_success'] = '报价单发送成功';
$L['msg_price_member_failed'] = '报价发送失败，对方可能拒绝您的报价';
$L['msg_price_guest_failed'] = '报价发送失败，对方可能拒绝非会员的报价';
$L['msg_home_success'] = '提交成功';
$L['msg_home_member_failed'] = '提交失败，对方可能拒绝您的信息';
$L['msg_home_guest_failed'] = '提交失败，对方可能拒绝非会员的信息';
$L['content_truename'] = '联系人：';
$L['content_email'] = '电子邮件：';
$L['content_company'] = '公司名：';
$L['content_telephone'] = '联系电话：';
$L['content_qq'] = 'QQ：';
$L['content_wx'] = '微信：';
$L['content_ali'] = '阿里旺旺：';
$L['content_skype'] = 'Skype：';
$L['content_type'] = '我想了解的产品信息有：';
$L['content_from'] = '(信息来自公司主页)';
$L['content_date'] = '我希望在 {V0} 之前回复';
/* buy/price.inc.php */
$L['content_product'] = '产品信息：';
$L['price_message_title'] = '我对您发布的“{V0}”很感兴趣';
$L['price_head_title'] = '报价单';
/* info/message.inc.php */
$L['content_info'] = '信息地址：';
$L['info_message_title'] = '我对您发布的“{V0}”很感兴趣';
$L['info_head_title'] = '留言信息';
/* brand/message.inc.php */
$L['content_brand'] = '产品品牌：';
$L['brand_message_title'] = '愿加盟“{V0}”品牌';
$L['brand_head_title'] = '留言加盟';
/* sell/inquiry.inc.php */
$L['inquiry_result'] = '共发送{V0}条，成功{V1}条';
$L['inquiry_no_info'] = '信息不存在或者发布人未注册';
$L['inquiry_message_title'] = '我对您发布的“{V0}”很感兴趣';
$L['inquiry_head_title'] = '询价单';
$L['inquiry_message_title_multi'] = '我对您在“{V0}”发布的信息很感兴趣';
$L['inquiry_head_title_multi'] = '批量询价';
$L['inquiry_itemid'] = '请指定需要询价的信息';
$L['inquiry_limit'] = '最多可选择 {V0} 条信息';
/* sell/order.inc.php */
$L['order_condition'] = '此信息未设置价格或计量单位或起订量，无法在线订购';
$L['order_self'] = '请不要给自己的信息下单';
$L['order_guest'] = '该企业未注册本站会员，无法收到订单';
$L['order_type_amount'] = '请填写订货总量';
$L['order_min_amount'] = '订货总量不能小于最小起订量';
$L['order_max_amount'] = '订货总量不能大于供货总量';
$L['order_confirm'] = '确认订单';
$L['order_goods'] = '订购产品';
/* job/apply.inc.php */
$L['apply_self'] = '您不能向自己公司投递简历';
$L['apply_again'] = '您已经向此职位投递过简历';
$L['apply_title'] = '投递简历';
$L['apply_msg_title'] = '您的招聘[{V0}]收到新的简历';
$L['apply_msg_content'] = '详见:<a href="{V0}" target="_blank">{V0}</a>';
$L['apply_success'] = '简历投递成功';
$L['make_resume'] = '请先创建简历';
$L['not_resume'] = '简历不存在';
/* konw/answer.inc.php */
$L['vote_end'] = '投票已经结束';
$L['vote_answer'] = '问题投票';
$L['vote_reject'] = '您已经投过票或无权投票';
$L['min_answer'] = '至少需要保留两个答案';
$L['record_reward'] = '[{V0}]最佳答案悬赏';
$L['record_best'] = '[{V0}]最佳答案奖励';
$L['record_thank'] = '[{V0}]最佳答案感谢';
$L['record_addto'] = '[{V0}]追加悬赏';
$L['record_expired'] = '[{V0}]问题过期';
$L['select_credit'] = '请选择'.$DT['credit_name'];
$L['lack_credit'] = $DT['credit_name'].'不足';
$L['type_answer'] = '请填写答案';
$L['answer_title'] = '我来回答';
$L['answer_question'] = '回答问题';
$L['answer_msg_title'] = '您的提问[{V0}]收到新的回答';
$L['answer_msg_content'] = '<strong>问</strong>:{V0}<br/><strong>答</strong>:{V1}<br/>详见:<a href="{V2}" target="_blank">{V2}</a><br/>如果回答没有显示出来，可能需要系统审核后显示';
$L['answer_success'] = '回答成功，感谢参与';
$L['answer_check'] = '回答成功，请等待审核';
$L['expired_msg_title'] = '您的提问[{V0}]即将到期，请及时处理';
$L['expired_msg_content'] = '详见:<a href="{V0}" target="_blank">{V0}</a>';
$L['sms_inquiry'] = '询盘';
$L['sms_price'] = '报价';
$L['sms_message'] = '留言';
/* mall/cart.inc.php */
$L['cart_title'] = '购物车';
/* mall|group/buy.inc.php */
$L['buy_title'] = '提交订单';
$L['post_free'] = '包邮';
$L['msg_buy_success'] = '订单提交成功';
/* group/buy.inc.php*/
$L['group_expired'] = '团购已结束';
/* exhibit/sign.inc.php*/
$L['sign_title'] = '在线报名';
$L['sign_again'] = '您已经报过名了，不能重复提交';
$L['has_started'] = '展会已经开始';
$L['msg_sign_success'] = '报名提交成功，请等待工作人员与您联系';
?>