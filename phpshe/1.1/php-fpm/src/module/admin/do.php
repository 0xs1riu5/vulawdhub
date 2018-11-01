<?php
/**
 * @copyright   2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate   2012-0501 koyshe <koyshe@gmail.com>
 */
switch ($act) {
	//#####################@ 管理员退出 @#####################//
	case 'logout':
		unset($_SESSION['admin_idtoken'], $_SESSION['admin_id'], $_SESSION['admin_name']);
		pe_success('管理员退出成功！', 'admin.php');
	break;
	//#####################@ 管理员登录 @#####################//
	default:
		if (isset($_p_pesubmit)) {
			$_p_info['admin_pw'] = md5($_p_info['admin_pw']);
			if ($info = $db->pe_select('admin', pe_dbhold($_p_info))) {
				$db->pe_update('admin', array('admin_id'=>$info['admin_id']), array('admin_ltime'=>time()));
				$_SESSION['admin_idtoken'] = md5($info['admin_id'].$pe['host_root']);
				$_SESSION['admin_id'] = $info['admin_id'];
				$_SESSION['admin_name'] = $info['admin_name'];
				pe_success('管理员登录成功！', 'admin.php');
			}
			else {
				pe_error('用户名或密码错误...');
			}
		}
		$seo = pe_seo('管理员登录', '', '', 'admin');
		include(pe_tpl('do_login.html'));
	break;
}
?>