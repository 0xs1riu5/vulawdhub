<?php
if (pe_login('user') && in_array($act, array('login', 'register'))) {
	pe_goto("{$pe['host_root']}index.php?mod=user&act=order");
}
if (!pe_login('user') && !in_array($act, array('login', 'register'))) {
	pe_goto(pe_url('user-login'));
}
switch($act) {
	//#####################@ 用户登录 @#####################//
	case 'login':
		if (isset($_p_pesubmit)) {
			$_p_info['user_pw'] = md5($_p_info['user_pw']);
			if ($info = $db->pe_select('user', $_p_info)) {
				$db->pe_update('user', array('user_id'=>$info['user_id']), array('user_ltime'=>time()));
				$_SESSION['user_idtoken'] = md5($info['user_id'].$pe['host_root']);
				$_SESSION['user_id'] = $info['user_id'];
				$_SESSION['user_name'] = $info['user_name'];
				//未登录时的购物车列表入库
				if (is_array($cart_list = unserialize($_c_cart_list))) {
					$cart_rows = $db->index('product_id')->pe_selectall('cart', array('user_id'=>$info['user_id']));
					foreach ($cart_list as $k => $v) {
						if (array_key_exists($k, $cart_rows)) {
							$db->pe_update('cart', array('cart_id'=>$cart_rows[$k]['cart_id']), array('product_num'=>$cart_rows[$k]['product_num']+$cart_list[$k]['product_num']));
						}
						else {
							$cart_info['cart_atime'] = time();
							$cart_info['product_id'] = $k;
							$cart_info['product_num'] = $v['product_num'];
							$cart_info['user_id'] = $info['user_id'];
							$db->pe_insert('cart', $cart_info);
						}
					}
					setcookie('cart_list', '', time()-3600, '/');
				}
				pe_success('用户登录成功！', $_g_fromto);
			}
			else {
				pe_error('用户名或密码错误...');
			}
		}
		$seo = pe_seo($menutitle='用户登录');
 		include(pe_tpl('user_login.html'));
	break;
	//#####################@ 用户退出 @#####################//
	case 'logout':
		unset($_SESSION['user_idtoken'], $_SESSION['user_id'], $_SESSION['user_name']);
		pe_success('用户退出成功！', $pe['host_root']);
	break;
	//#####################@ 用户注册 @#####################//
	case 'register':
		if ($_g_type == 'checkname') {
			$result = $db->pe_num('user', array('user_name'=>pe_dbhold($_g_user_name))) > 0 ? false : true;
			echo json_encode(array('result'=>$result));
			die();
		}
		if ($_g_type == 'checkemail') {
			$result = $db->pe_num('user', array('user_email'=>pe_dbhold($_g_user_email))) > 0 ? false : true;
			echo json_encode(array('result'=>$result));
			die();
		}
		if (isset($_p_pesubmit)) {
			$_p_info['user_pw'] = md5($_p_info['user_pw']);
			$_p_info['user_atime'] = $info['user_ltime'] = time();
			if ($user_id = $db->pe_insert('user', $_p_info)) {
				$info = $db->pe_select('user', array('user_id'=>$user_id));
				$_SESSION['user_idtoken'] = md5($info['user_id'].$pe['host_root']);
				$_SESSION['user_id'] = $info['user_id'];
				$_SESSION['user_name'] = $info['user_name'];
				//未登录时的购物车列表入库
				if (is_array($cart_list = unserialize($_c_cart_list))) {
					foreach ($cart_list as $k => $v) {
						$cart_info['cart_atime'] = time();
						$cart_info['product_id'] = $k;
						$cart_info['product_num'] = $v['product_num'];
						$cart_info['user_id'] = $info['user_id'];
						$db->pe_insert('cart', $cart_info);
					}
					setcookie('cart_list', '', time()-3600, '/');
				}
				pe_success('用户注册成功！', $_g_fromto);
			}
			else {
				pe_error('用户注册失败...');
			}
		}
		$seo = pe_seo($menutitle='用户注册');
 		include(pe_tpl('user_register.html'));
	break;
	//#####################@ 订单列表 @#####################//
	case 'order':
		$info_list = $db->pe_selectall('order', array('user_id'=>$_s_user_id, 'order by'=>'order_id desc'), '*', array(10, $_g_page));
		foreach ($info_list as $k => $v) {
			$info_list[$k]['product_list'] = $db->pe_selectall('orderdata', array('order_id'=>$v['order_id']));
		}
		$seo = pe_seo($menutitle='我的订单');
		include(pe_tpl('user_orderlist.html'));
	break;
	//#####################@ 订单详情 @#####################//
	case 'orderview':
		$order_id = intval($_g_id);
		pe_lead('hook/payway.hook.php');
		$ini_payway = payway_ini();
		$info = $db->pe_select('order', array('order_id'=>$order_id, 'user_id'=>$_s_user_id));
		$product_list = $db->pe_selectall('orderdata', array('order_id'=>$order_id));

		$seo = pe_seo($menutitle='订单详情');
		include(pe_tpl('user_orderview.html'));
	break;
	//#####################@ 订单删除 @#####################//
	case 'orderdel':
		$info = $db->pe_select('order', array('user_id'=>$_s_user_id, 'order_id'=>pe_dbhold($_g_id), 'order_state'=>'notpay'));
		if ($info['order_id']) {
			if ($db->pe_delete('order', array('order_id'=>$info['order_id']))) {
				//删除订单子表数据
				$db->pe_delete('orderdata', array('order_id'=>$info['order_id']));
				//更新商品库存数
				pe_lead('hook/product.hook.php');
				product_num('addnum', $info['order_id']);
				pe_success('订单删除成功！');
			}
			else {
				pe_error('订单删除失败...');
			}
		}
		else {
			pe_error('抱歉，已付款订单不能删除...');
		}
	break;
	//#####################@ 收藏列表 @#####################//
	case 'collect':
		$sql = "select * from `".dbpre."collect` a, `".dbpre."product` b where a.`product_id` = b.`product_id` and a.`user_id` = '{$_s_user_id}' order by a.`collect_id` desc";
		$info_list = $db->sql_selectall($sql, array(10, $_g_page));
		
		$seo = pe_seo($menutitle='我的收藏');
		include(pe_tpl('user_collectlist.html'));
	break;
	//#####################@ 收藏删除 @#####################//
	case 'collectdel':
		$product_id = intval($_g_product_id);
		if ($db->pe_delete('collect', array('product_id'=>intval($product_id), 'user_id'=>$_s_user_id))) {
			pe_lead('hook/product.hook.php');
			product_num('collectnum', $product_id);
			pe_success('商品收藏删除成功！');
		}
		else {
			pe_error('商品收藏删除失败...');
		}
	break;
	//#####################@ 咨询列表 @#####################//
	case 'ask':
		$sql = "select * from `".dbpre."ask` a, `".dbpre."product` b where a.`product_id` = b.`product_id` and a.`user_id` = '{$_s_user_id}' order by a.`ask_id` desc";
		$info_list = $db->sql_selectall($sql, array(10, $_g_page));
		
		$seo = pe_seo($menutitle='我的咨询');
		include(pe_tpl('user_asklist.html'));
	break;
	//#####################@ 评价列表 @#####################//
	case 'comment':
		$sql = "select * from `".dbpre."comment` a, `".dbpre."product` b where a.`product_id` = b.`product_id` and a.`user_id` = '{$_s_user_id}' order by a.`comment_id` desc";
		$info_list = $db->sql_selectall($sql, array(10, $_g_page));
		
		$seo = pe_seo($menutitle='我的评价');
		include(pe_tpl('user_commentlist.html'));
	break;
	//#####################@ 基本信息 @#####################//
	case 'base':
		if (isset($_p_pesubmit)) {
			if ($db->pe_update('user', array('user_id'=>$_s_user_id), pe_dbhold($_p_info))) {
				pe_success('基本信息修改成功！');
			}
			else {
				pe_error('基本信息修改失败...');
			}
		}
		$info = $db->pe_select('user', array('user_id'=>$_s_user_id));

		$seo = pe_seo($menutitle='基本信息');
		include(pe_tpl('user_base.html'));
	break;
	//#####################@ 密码修改  @#####################//
	case 'pw':
		if (isset($_p_pesubmit)) {
			if ($db->pe_update('user', array('user_id'=>$_s_user_id), array('user_pw'=>md5($_p_info['user_pw'])))) {
				pe_success('密码修改成功！');
			}
			else {
				pe_error('密码修改失败...');
			}
		}
		$info = $db->pe_select('user', array('user_id'=>$_s_user_id));
	
		$seo = pe_seo($menutitle='修改密码');
		include(pe_tpl('user_pw.html'));
	break;
}
?>