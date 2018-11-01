<?php
/**
 * @copyright   2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate   2012-0501 koyshe <koyshe@gmail.com>
 */
$menumark = 'admin';
switch ($act) {
	//#####################@ 管理增加 @#####################//
	case 'add':
		if (isset($_p_pesubmit)) {
			$_p_admin_pw && $_p_info['admin_pw'] = md5($_p_admin_pw);
			if ($db->pe_insert('admin', $_p_info)) {
				pe_success('管理增加成功!', 'admin.php?mod=admin');
			}
			else {
				pe_error('管理增加失败...');
			}
		}
		$seo = pe_seo($menutitle='增加管理', '', '', 'admin');
		include(pe_tpl('admin_add.html'));
	break;
	//#####################@ 管理修改 @#####################//
	case 'edit':
		$admin_id = intval($_g_id);
		if (isset($_p_pesubmit)) {
			$_p_admin_pw && $_p_info['admin_pw'] = md5($_p_admin_pw);
			if ($db->pe_update('admin', array('admin_id'=>$admin_id), $_p_info)) {
				pe_success('管理信息修改成功!', 'admin.php?mod=admin');
			}
			else {
				pe_error('管理信息修改失败...');
			}
		}
		$info = $db->pe_select('admin', array('admin_id'=>$admin_id));
		$seo = pe_seo($menutitle='修改管理信息', '', '', 'admin');
		include(pe_tpl('admin_add.html'));
	break;
	//#####################@ 管理删除 @#####################//
	case 'del':
		$_g_id == 1 && pe_error('抱歉，默认管理员不可删除...');
		if ($db->pe_delete('admin', array('admin_id'=>$_g_id))) {
			pe_success('管理删除成功!');
		}
		else {
			pe_error('管理删除失败...');
		}
	break;
	//#####################@ 管理列表 @#####################//
	default:
		$info_list = $db->pe_selectall('admin', '', '*', array(20, $_g_page));
		$seo = pe_seo($menutitle='管理列表', '', '', 'admin');
		include(pe_tpl('admin_list.html'));
	break;
}
?>