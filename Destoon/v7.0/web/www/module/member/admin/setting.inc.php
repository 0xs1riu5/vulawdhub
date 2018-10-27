<?php
defined('DT_ADMIN') or exit('Access Denied');
$tab = isset($tab) ? intval($tab) : 0;
$all = isset($all) ? intval($all) : 0;
if($submit) {
	foreach($pay as $k=>$v) {
		$pay[$k] = array_map('trim', $v);
	}
	foreach($oauth as $k=>$v) {
		$oauth[$k] = array_map('trim', $v);
	}
	if($setting['deposit'] < 100) $setting['deposit'] = 100;
	$P = cache_read('pay.php');
	$pay['tenpay']['keycode'] = pass_decode($pay['tenpay']['keycode'], $P['tenpay']['keycode']);
	$pay['weixin']['keycode'] = pass_decode($pay['weixin']['keycode'], $P['weixin']['keycode']);
	$pay['alipay']['keycode'] = pass_decode($pay['alipay']['keycode'], $P['alipay']['keycode']);
	$pay['aliwap']['keycode'] = pass_decode($pay['aliwap']['keycode'], $P['aliwap']['keycode']);
	$pay['chinabank']['keycode'] = pass_decode($pay['chinabank']['keycode'], $P['chinabank']['keycode']);
	$pay['yeepay']['keycode'] = pass_decode($pay['yeepay']['keycode'], $P['yeepay']['keycode']);
	$pay['paypal']['keycode'] = pass_decode($pay['paypal']['keycode'], $P['paypal']['keycode']);
	$setting['uc_dbpwd'] = pass_decode($setting['uc_dbpwd'], $MOD['uc_dbpwd']);
	$setting['ex_pass'] = pass_decode($setting['ex_pass'], $MOD['ex_pass']);
	$setting['edit_check'] = implode(',', $setting['edit_check']);
	$setting['login_time'] = $setting['login_time'] >= 86400 ? $setting['login_time'] : 0;
	foreach($pay as $k=>$v) {
		update_setting('pay-'.$k, $v);
	}
	$setting['oauth'] = 0;
	foreach($oauth as $k=>$v) {
		if($v['enable']) $setting['oauth'] = 1;
		update_setting('oauth-'.$k, $v);
	}
	update_setting($moduleid, $setting);
	cache_module($moduleid);
	$ext_oauth = $setting['oauth'];
	if($oauth['sina']['enable'] && $oauth['sina']['sync']) $ext_oauth .= ',sina';
	if($oauth['qq']['enable'] && $oauth['qq']['sync']) $ext_oauth .= ',qq';
	$db->query("UPDATE {$DT_PRE}setting SET item_value='$ext_oauth' WHERE item_key='oauth' AND item='3'");
	cache_module(3);
	dmsg('设置保存成功', '?moduleid='.$moduleid.'&file='.$file.'&tab='.$tab);
} else {
	$GROUP = cache_read('group.php');
	extract(dhtmlspecialchars($MOD));
	cache_pay();
	$P = cache_read('pay.php');
	extract($P);
	cache_oauth();	
	$O = cache_read('oauth.php');
	extract($O);
	$tenpay['keycode'] = pass_encode($tenpay['keycode']);
	$weixin['keycode'] = pass_encode($weixin['keycode']);
	$alipay['keycode'] = pass_encode($alipay['keycode']);
	$aliwap['keycode'] = pass_encode($aliwap['keycode']);
	$chinabank['keycode'] = pass_encode($chinabank['keycode']);
	$yeepay['keycode'] = pass_encode($yeepay['keycode']);
	$paypal['keycode'] = pass_encode($paypal['keycode']);
	$uc_dbpwd = pass_encode($uc_dbpwd);
	$ex_pass = pass_encode($ex_pass);
	if($kw) {
		$all = 1;
		ob_start();
	}
	include tpl('setting', $module);
	if($kw) {
		$data = $content = ob_get_contents();
		ob_clean();
		$data = preg_replace('\'(?!((<.*?)|(<a.*?)|(<strong.*?)))('.$kw.')(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</strong>))\'si', '<span class=highlight>'.$kw.'</span>', $data);
		$data = preg_replace('/<span class=highlight>/', '<a name=high></a><span class=highlight>', $data, 1);
		echo $data ? $data : $content;
	}
}
?>