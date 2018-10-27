<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
$L['send_status'] = array(
	'<span style="color:#888888;">未知</span>',
	'<span style="color:#008080;">在途</span>',
	'<span style="color:#0000FF;">派送中</span>',
	'<span style="color:#008000;">已签收</span>',
	'<span style="color:#FF0000;">退回</span>',
	'<span style="color:#FF6600;">其他</span>',
	'<span style="color:#FF0000;">无记录</span>',
);
$L['send_dstatus'] = array(
	'未知',
	'在途',
	'派送中',
	'已签收',
	'退回',
	'其他',
	'无记录',
);

$L['group_status'] = array(
	'<span style="color:#0000FF;">已付款</span>',
	'<span style="color:#FF0000;">已发货</span>',
	'<span style="color:#FF6600;">已消费</span>',
	'<span style="color:#008000;">交易成功</span>',
	'<span style="color:#FF0000;text-decoration:underline;">申请退款</span>',
	'<span style="color:#888888;text-decoration:line-through;">已退款</span>',
	'<span style="color:#FF6600;text-decoration:underline;">待付款</span>',
);
$L['group_dstatus'] = array(
	'已付款',
	'已发货',
	'已消费',
	'交易成功',
	'申请退款',
	'已退款',
	'待付款',
);
$L['group_order_credit'] = '团购订单';
$L['group_msg_deny'] = '您无权进行此操作';
$L['group_msg_null'] = '订单不存在';
$L['group_success'] = '恭喜！此订单交易成功';
$L['group_detail_title'] = '订单详情';
$L['group_express_title'] = '物流追踪';
$L['group_title'] = '订单管理';
$L['group_sfields'] = array('按条件', '商品', '金额', '密码', '买家', '买家姓名', '买家地址', '买家邮编', '买家手机', '买家电话', '发货方式', '物流号码', '备注');
$L['group_order_title'] = '团购订单';
$L['group_order_sfields'] = array('按条件', '商品', '金额', '密码', '卖家', '发货方式', '物流号码', '备注');
$L['group_order_id'] = '团购单号';
$L['group_send_title'] = '商家发货';
$L['group_addtime_null'] = '请填写延长的时间';
$L['group_addtime_success'] = '买家确认时间延长成功';
$L['group_addtime_title'] = '延长买家确认时间';
$L['group_record_pay'] = '交易成功';
$L['group_order_id'] = '团购单号:';
$L['group_buyer_timeout'] = '团购单号:{V0}[买家超时]';
$L['group_pay_order_success'] = '订单支付成功';
$L['group_pay_order_title'] = '订单支付';

$L['trade_status'] = array(
	'<span style="color:#008080;">待确认</span>',
	'<span style="color:#FF6600;">待付款</span>',
	'<span style="color:#0000FF;">待发货</span>',
	'<span style="color:#FF0000;">已发货</span>',
	'<span style="color:#008000;">交易成功</span>',
	'<span style="color:#FF0000;text-decoration:underline;">申请退款</span>',
	'<span style="color:#0000FF;text-decoration:underline;">已退款</span>',
	'<span style="color:#008080;">货到付款</span>',
	'<span style="color:#888888;text-decoration:line-through;">买家关闭</span>',
	'<span style="color:#888888;text-decoration:line-through;">卖家关闭</span>',
);
$L['trade_dstatus'] = array(
	'待确认',
	'待付款',
	'待发货',
	'已发货',
	'交易成功',
	'申请退款',
	'已退款',
	'货到付款',
	'买家关闭',
	'卖家关闭',
);
$L['trade_msg_deny'] = '您无权进行此操作';
$L['trade_msg_null'] = '订单不存在';
$L['trade_msg_pay_bind'] = '系统采用了{V0}担保交易，请先绑定您的{V0}帐号';
$L['trade_msg_less_fee'] = '附加金额不能小于{V0}';
$L['trade_msg_confirm'] = '此订单需要卖家确认';
$L['trade_msg_deny_comment'] = '此订单不支持评价';
$L['trade_msg_comment_again'] = '您已经评价过此交易';
$L['trade_msg_comment_success'] = '评价提交成功';
$L['trade_msg_empty_explain'] = '解释内容不能为空';
$L['trade_msg_explain_again'] = '您已经解释过此评价';
$L['trade_msg_explain_success'] = '解释成功';
$L['trade_msg_secured_close'] = '系统未开启担保交易接口';
$L['trade_msg_bind_edit'] = '您的帐号已经绑定，不可再修改<br/>如果需要修改，请与网站联系';
$L['trade_msg_bind_exists'] = '帐号绑定已经存在，请检查您的帐号';
$L['trade_msg_bind_success'] = '更新成功';
$L['trade_msg_muti_choose'] = '请选择需要支付的订单';
$L['trade_msg_muti_empty'] = '暂无符合条件的订单';
$L['trade_bind_title'] = '绑定{V0}帐号';
$L['trade_bind_error'] = '{V0}帐号格式不正确';
$L['trade_muti_title'] = '批量付款';
$L['trade_muti_send_title'] = '批量发货';

$L['trade_price_fee_null'] = '请填写附加金额';
$L['trade_price_fee_name'] = '请填写附加金额名称';
$L['trade_price_edit_success'] = '订单修改成功';
$L['trade_price_title'] = '修改订单';
$L['trade_detail_title'] = '订单详情';
$L['trade_exprss_title'] = '物流追踪';
$L['trade_confirm_success'] = '订单已确认，请等待买家付款';
$L['trade_pay_order_success'] = '订单支付成功，请等待卖家发货';
$L['trade_pay_order_title'] = '订单支付';
$L['trade_refund_reason'] = '请填写退款原因';
$L['trade_refund_success'] = '您的退款申请已经提交，请等待网站处理';
$L['trade_refund_title'] = '申请退款';
$L['trade_send_success'] = '已经确认发货，请等待买家确认收货';
$L['trade_send_title'] = '确认发货';
$L['trade_receive_title'] = '确认到货';
$L['trade_addtime_null'] = '请填写延长的时间';
$L['trade_addtime_success'] = '买家确认时间延长成功';
$L['trade_addtime_title'] = '延长买家确认时间';
$L['trade_success'] = '恭喜！此订单交易成功';
$L['trade_close_success'] = '交易已关闭';
$L['trade_delete_success'] = '订单删除成功';
$L['trade_pay_seller'] = '请填写收款会员名';
$L['trade_pay_self'] = '收款人不能是自己';
$L['trade_pay_seller_bad'] = '收款会员名不存在，请确认';
$L['trade_pay_amount'] = '请填写付款金额';
$L['trade_pay_note'] = '请填写付款说明';
$L['trade_pay_goods'] = '请填写商品或服务名称';
$L['trade_pay_title'] = '我要付款';
$L['trade_pay1_success'] = '直接付款成功，会员[{V0}]将直接收到您的付款';
$L['trade_pay0_success'] = '订单已经发出，请等待卖家确认';
$L['trade_order_sfields'] = array('按条件', '商品', '金额', '附加金额', '附加名称', '卖家', '发货方式', '物流号码', '备注');
$L['trade_order_title'] = '我的订单';
$L['trade_sfields'] = array('按条件', '商品', '金额', '附加金额', '附加名称', '买家', '买家姓名', '买家地址', '买家邮编', '买家手机', '买家电话', '发货方式', '物流号码', '备注');
$L['trade_title'] = '订单管理';
$L['trade_record_pay'] = '交易成功';
$L['trade_record_payfor'] = '站内付款';
$L['trade_record_receive'] = '站内收款';
$L['trade_record_new'] = '通知卖家确认订单';
$L['trade_refund'] = '订单退款';
$L['trade_refund_reason'] = '请填写退款原因';
$L['trade_refund_success'] = '您的退款申请已经提交，请等待网站处理';
$L['trade_refund_title'] = '申请退款';
$L['trade_refund_agree_title'] = '同意退款';
$L['trade_refund_agree_success'] = '订单退款成功';
$L['trade_refund_by_seller'] = '[卖家]';
$L['trade_order_id'] = '订单号:';
$L['trade_fee'] = '网站服务费';
$L['trade_buyer_timeout'] = '单号:{V0}[买家超时]';
$L['trade_sms_confirm'] = '通知买家付款';
$L['trade_sms_pay'] = '通知卖家发货';
$L['trade_sms_send'] = '通知买家已发货';
$L['trade_sms_income'] = '站内付款通知';
$L['trade_sms_receive'] = '通知卖家已收货';
$L['trade_message_t1'] = '站内交易提醒，您有一笔交易需要付款(T{V0})';
$L['trade_message_c1'] = '卖家 <a href="{V0}" class="t">{V1}</a> 于 <span class="f_gray">{V2}</span> 更新了您的订单<br/><a href="{V3}" class="t" target="_blank">&raquo; 请点这里立即处理或查看详情</a>';
$L['trade_message_t2'] = '站内交易提醒，您有一笔交易需要发货(T{V0})';
$L['trade_message_c2'] = '买家 <a href="{V0}" class="t">{V1}</a> 于 <span class="f_gray">{V2}</span> 支付了您的订单<br/><a href="{V3}" class="t" target="_blank">&raquo; 请点这里立即处理或查看详情</a>';
$L['trade_message_t3'] = '站内交易提醒，您有一笔交易需要收货(T{V0})';
$L['trade_message_c3'] = '卖家 <a href="{V0}" class="t">{V1}</a> 于 <span class="f_gray">{V2}</span> 已经发货<br/><a href="{V3}" class="t" target="_blank">&raquo; 请点这里立即处理或查看详情</a>';
$L['trade_message_t4'] = '站内交易提醒，您有一笔交易已经成功(T{V0})';
$L['trade_message_c4'] = '买家 <a href="{V0}" class="t">{V1}</a> 于 <span class="f_gray">{V2}</span> 确认收货，交易完成<br/><a href="{V3}" class="t" target="_blank">&raquo; 请点这里立即处理或查看详情</a>';
$L['trade_message_t5'] = '站内收入提醒，您收到一笔付款';
$L['trade_message_c5'] = '<a href="{V0}" class="t">{V1}</a> 于 <span class="f_gray">{V2}</span> 向您支付了 <span class="f_blue">{V3}'.$DT['money_unit'].'</span> 的站内付款<br/>备注：<span class="f_gray">{V4}</span>';
$L['trade_message_t6'] = '站内交易提醒，您有一笔交易需要确认(T{V0})';
$L['trade_message_c6'] = '<a href="{V0}" class="t">{V1}</a> 于 <span class="f_gray">{V2}</span> 向您订购了：<br/>{V3}<br/>订单编号：<span class="f_red">T{V4}</span> &nbsp;订单金额为：<span class="f_blue f_b">{V5}'.$DT['money_unit'].'</span><br/><a href="{V6}" class="t" target="_blank">&raquo; 请点这里立即处理或查看详情</a>';

$L['purchase_title'] = '确认订单';
$L['purchase_msg_address'] = '请先创建收货地址';
$L['purchase_msg_goods'] = '商品不存在';
$L['purchase_msg_self'] = '不能购买自己的商品';
$L['purchase_msg_group_finish'] = '团购已结束';
$L['purchase_msg_online_buy'] = '此商品不支持在线购买';
$L['post_free'] = '包邮';
$L['msg_express_no'] = '请填写快递单号';
$L['msg_express_type'] = '请填写快递类型';
$L['msg_express_no_error'] = '快递单号格式错误';
$L['msg_express_date_error'] = '发货时间格式错误';

$L['express_sfields'] = array('按条件', '商品名称', '快递公司', '快递单号');
$L['express_title'] = '我的快递';
?>