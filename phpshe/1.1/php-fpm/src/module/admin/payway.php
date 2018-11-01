<?php
/**
 * @copyright   2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate   2012-1116 koyshe <koyshe@gmail.com>
 */
$menumark = 'payway';
pe_lead('hook/cache.hook.php');
//支付宝模版
$alipay['alipay_class']['name']='支付宝接口';
$alipay['alipay_class']['form_type']='select';
$alipay['alipay_class']['form_value']['alipay_js']='即时到帐收款';
$alipay['alipay_class']['form_value']['alipay_db']='担保交易收款';
$alipay['alipay_class']['form_value']['alipay_sgn']='双功能收款';

$alipay['alipay_name']['name']='支付宝账户';
$alipay['alipay_name']['form_type']='text';

$alipay['alipay_pid']['name']='合作者身份Pid';
$alipay['alipay_pid']['form_type']='text';

$alipay['alipay_key']['name']='安全校验码Key';
$alipay['alipay_key']['form_type']='text';

//银行转账付款模版
$bank['bank_text']['name'] = '收款信息';
$bank['bank_text']['form_type']='textarea';

pe_lead('hook/cache.hook.php');
switch ($act) {
	//#####################@ 支付修改 @#####################//
	case 'edit':
		$payway_id = intval($_g_id);
		if (isset($_p_pesubmit)) {
			$_p_info['payway_config'] = serialize($_p_config);
			if ($db->pe_update('payway', array('payway_id'=>$payway_id), $_p_info)) {
				cache_write('payway');
				pe_success('支付修改成功!', 'admin.php?mod=payway');
			}
			else {
				pe_error('支付修改失败...' );
			}
		}
		$info = $db->pe_select('payway', array('payway_id'=>$payway_id));
		$info['payway_model'] = unserialize($info['payway_model']);
		$info['payway_config'] = unserialize($info['payway_config']);
		$seo = pe_seo($menutitle='修改支付方式', '', '', 'admin');
		include(pe_tpl('payway_add.html'));
	break;
	//#####################@ 支付删除 @#####################//
	case 'del':
		if ($db->pe_delete('payway', array('payway_id'=>is_array($_p_payway_id) ? $_p_payway_id : $_g_id))) {
			cache_write('payway');
			pe_success('支付删除成功!');
		}
		else {
			pe_error('支付删除失败...');
		}
	break;
	//#####################@ 支付排序 @#####################//
	case 'order':
		foreach ($_p_payway_order as $k => $v) {
			$result = $db->pe_update('payway', array('payway_id'=>$k), array('payway_order'=>$v));
		}
		if ($result) {
			cache_write('payway');
			pe_success('支付排序成功!');
		}
		else {
			pe_error('支付排序失败...');
		}
	break;
	//#####################@ 支付列表 @#####################//
	default:
		$info_list = $db->pe_selectall('payway', array('order by'=>'`payway_order` asc, `payway_id` asc'), '*', array(20, $_g_page));
		$seo = pe_seo($menutitle='支付方式', '', '', 'admin');
		include(pe_tpl('payway_list.html'));
	break;
}
?>