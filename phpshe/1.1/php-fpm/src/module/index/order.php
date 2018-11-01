<?php
switch ($act) {
	//#####################@ 购物车商品加入 @#####################//
	case 'cartadd':
		$info['cart_atime'] = time();
		$info['product_id'] = intval($_g_product_id);
		$info['product_num'] = intval($_g_product_num);
		$product = $db->pe_select('product', array('product_id'=>$info['product_id']), '`product_num`');
		if ($product['product_num'] >= $info['product_num']) {
			if (pe_login('user')) {
				$info['user_id'] = $_s_user_id;	
				$cart = $db->pe_select('cart', array('product_id'=>$info['product_id'], 'user_id'=>$_s_user_id));
				if ($cart['product_num']) {
					$result = $db->pe_update('cart', array('cart_id'=>$cart['cart_id']), array('product_num'=>$cart['product_num']+$info['product_num'])) ? true : false;
				}
				else {
					$result = $db->pe_insert('cart', $info) ? true : false;		
				}
			}
			else {
				$cart_list = unserialize($_c_cart_list);
				if (is_array($cart_list[$info['product_id']])) {
					$cart_list[$info['product_id']]['product_num'] = $cart_list[$info['product_id']]['product_num'] + $info['product_num'];
				}
				else {
					$cart_list[$info['product_id']] = $info;
				}
				$result = is_array($cart_list[$info['product_id']]) ? true : false;
				setcookie('cart_list', serialize($cart_list), 0, '/');
			}
		}
		echo json_encode(array('result'=>$result));
	break;
	//#####################@ 购物车商品更改数量 @#####################//
	case 'cartnum':
		$money['order_productmoney'] = $money['order_wlmoney'] = $money['order_money'] = 0;
		if (pe_login('user')) {
			$result = $db->pe_update('cart', array('user_id'=>$_s_user_id, 'product_id'=>$_g_product_id), array('product_num'=>$_g_product_num));
		}
		else {
			$cart_list = unserialize($_c_cart_list);
			$cart_list[$_g_product_id]['product_num'] = $_g_product_num;
			$result = is_array($cart_list[$_g_product_id]) ? true : false;
			setcookie('cart_list', serialize($cart_list), 0, '/');
		}
		$cart_info = cart_info($cart_list);
		echo json_encode(array('result'=>$result, 'money'=>$cart_info['money']));
	break;
	//#####################@ 购物车商品删除 @#####################//
	case 'cartdel':
		$money['order_productmoney'] = $money['order_wlmoney'] = $money['order_money'] = 0;
		if (pe_login('user')) {
			$result = $db->pe_delete('cart', array('user_id'=>$_s_user_id, 'product_id'=>$_g_product_id));
		}
		else {
			$cart_list = unserialize($_c_cart_list);
			unset($cart_list[$_g_product_id]);
			$result = is_array($cart_list[$_g_product_id]) ? false : true;
			setcookie('cart_list', serialize($cart_list), 0, '/');
		}
		$cart_info = cart_info($cart_list);
		echo json_encode(array('result'=>$result, 'money'=>$cart_info['money']));
	break;
	//#####################@ 订单增加 @#####################//
	case 'add':
		$cart_info = cart_info(unserialize($_c_cart_list));
		$info_list = $cart_info['list'];
		$money = $cart_info['money'];
		if (isset($_p_pesubmit)) {
 			!count($info_list) && pe_error('购物车商品为空');
			$order = $db->pe_select('order', array('order by'=>'order_id desc'));
			substr($order['order_id'], 0 , 6) != date('ymd') && $_p_info['order_id'] = $order_id = date('ymd').'0001';
			$_p_info['order_productmoney'] = $money['order_productmoney'];
			$_p_info['order_wlmoney'] = $money['order_wlmoney'];
			$_p_info['order_money'] = $money['order_money'];
			$_p_info['order_atime'] = time();
			$_p_info['user_id'] = $_s_user_id;
			$_p_info['user_name'] = $_s_user_name;
			$_p_info['user_address'] = "{$_p_province}{$_p_city}{$_p_info['user_address']}";
			if ($order_id = $db->pe_insert('order', $_p_info)) {
				foreach ($info_list as $v) {
					$orderdata['product_id'] = $v['product_id'];
					$orderdata['product_name'] = $v['product_name'];
					$orderdata['product_smoney'] = $v['product_smoney'];
					$orderdata['product_num'] = $v['product_num'];
					$orderdata['order_id'] = $order_id;
					$db->pe_insert('orderdata', $orderdata);
					//更新商品库存数量
					$db->pe_update('product', array('product_id'=>$v['product_id']), "`product_num`=`product_num`-{$v['product_num']}");
				}
				//清空购物车
				if (pe_login('user')) {
					$db->pe_delete('cart', array('user_id'=>$_s_user_id));
				}
				else {
					setcookie('cart_list', '', 0, '/');
				}
				pe_success('订单提交成功，请选择支付方式！', "{$pe['host_root']}index.php?mod=order&act=pay&id={$order_id}");
			}
			else {
				pe_error('订单提交失败...');
			}	
		}
		//调用用户个人信息里的收货地址
		$info = $db->pe_select('user', array('user_id'=>$_s_user_id));

		$seo = pe_seo('填写收货信息');
		include(pe_tpl('order_add.html'));
	break;
	//#####################@ 选择支付方式 @#####################//
	case 'pay':
		$order_id = pe_dbhold($_g_id);
		$cache_payway = cache::get('payway');
		foreach($cache_payway as $k => $v) {
			$cache_payway[$k]['payway_config'] = unserialize($cache_payway[$k]['payway_config']);
			if ($k == 'bank') {
				$cache_payway[$k]['payway_config']['bank_text'] = str_replace(array("\r", "\n", "\t"), '\n', $cache_payway[$k]['payway_config']['bank_text']);
			}
		}
		$order = $db->pe_select('order', array('order_id'=>$order_id, 'order_state'=>'notpay'));
		!$order['order_id'] && pe_error('订单号错误...');
		if (isset($_p_pesubmit)) {
			$info_list = $db->pe_selectall('orderdata', array('order_id'=>$order_id));
			foreach ($info_list as $v) {
				$order['order_name'] .= "{$v['product_name']};";			
			}
			echo '正在为您连接支付网站，请稍后...';
			include("{$pe['path_root']}include/plugin/payway/{$_p_info['order_payway']}/order_pay.php");
		}
		$seo = pe_seo('选择支付方式');
		include(pe_tpl('order_pay.html'));
	break;
}
//购物车商品列表和价格
function cart_info($_c_cart_list=array()) {
	global $db;
	if (pe_login('user')) {
		$sql = "select a.`product_num`, b.`product_id`, b.`product_name`, b.`product_logo`, b.`product_smoney`, b.`product_num` as `product_maxnum` from `".dbpre."cart` a, `".dbpre."product` b where a.`product_id` = b.`product_id` and a.`user_id` = '{$_SESSION['user_id']}'";
		$info_list = $db->sql_selectall($sql);
	}
	else {
		if (is_array($_c_cart_list)) {
			foreach ($_c_cart_list as $k => $v) {
				$product_rows = $db->pe_select('product', array('product_id'=>$k), '`product_name`, `product_logo`, `product_smoney`, `product_num` as `product_maxnum`');
				$info_list[] = array_merge($v, $product_rows);
			}
		}
	}
	foreach ((array)$info_list as $v) {
		$money['order_productmoney'] += $v['product_num'] * $v['product_smoney'];
		$money['order_wlmoney'] += $v['product_wlmoney'];
	}
	$money['order_money'] = number_format($money['order_wlmoney'] + $money['order_productmoney'], 1);
	$money['order_productmoney'] = number_format($money['order_productmoney'], 1);
	$money['order_wlmoney'] = number_format($money['order_wlmoney'], 1);
	return array('list'=>(array)$info_list, 'money'=>$money);
}
?>