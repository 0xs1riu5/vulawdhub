<?php
/**
 * @copyright   2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate   2012-0501 koyshe <koyshe@gmail.com>
 */
$menumark = 'user';
switch ($act) {
	//#####################@ 会员修改 @#####################//
	case 'edit':
		$user_id = intval($_g_id);
		if (isset($_p_pesubmit)) {
			$_p_user_pw && $_p_info['user_pw'] = md5($_p_user_pw);
			if ($db->pe_update('user', array('user_id'=>$user_id), $_p_info)) {
				pe_success('会员信息修改成功!', $_g_fromto);
			}
			else {
				pe_error('会员信息修改失败...');
			}
		}
		$info = $db->pe_select('user', array('user_id'=>$user_id));
		$seo = pe_seo($menutitle='修改会员', '', '', 'admin');
		include(pe_tpl('user_add.html'));
	break;
	//#####################@ 会员删除 @#####################//
	case 'del':
		if ($db->pe_delete('user', array('user_id'=>is_array($_p_user_id) ? $_p_user_id : intval($_g_id)))) {
			pe_success('会员删除成功!');
		}
		else {
			pe_error('会员删除失败...');
		}
	break;
	//#####################@ 会员列表 @#####################//
	default:
		$_g_name && $sqlwhere = " and `user_name` like '%{$_g_name}%'";
		$_g_phone && $sqlwhere = " and `user_phone` like '%{$_g_phone}%'";
		$_g_email && $sqlwhere = " and `user_email` like '%{$_g_email}%'";
		$sqlwhere .= " order by `user_id` desc";
		$info_list = $db->pe_selectall('user', $sqlwhere, '*', array(20, $_g_page));

		$seo = pe_seo($menutitle='会员列表', '', '', 'admin');
		include(pe_tpl('user_list.html'));
	break;
}
?>