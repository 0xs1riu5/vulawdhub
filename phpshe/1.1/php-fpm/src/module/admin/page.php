<?php
/**
 * @copyright   2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate   2012-0501 koyshe <koyshe@gmail.com>
 */
$menumark = 'page';
pe_lead('hook/cache.hook.php');
switch ($act) {
	//#####################@ 单页增加 @#####################//
	case 'add':
		if (isset($_p_pesubmit)) {
			if ($db->pe_insert('page', pe_dbhold($_p_info, array('page_text')))) {
				cache_write('page');
				pe_success('单页增加成功!', 'admin.php?mod=page');
			}
			else {
				pe_error('单页增加失败...');
			}
		}
		$seo = pe_seo($menutitle='增加单页', '', '', 'admin');
		include(pe_tpl('page_add.html'));
	break;
	//#####################@ 单页修改 @#####################//
	case 'edit':
		$page_id = intval($_g_id);
		if (isset($_p_pesubmit)) {
			if ($db->pe_update('page', array('page_id'=>$page_id), pe_dbhold($_p_info, array('page_text')))) {
				cache_write('page');
				pe_success('单页修改成功!', 'admin.php?mod=page');
			}
			else {
				pe_error('单页修改失败...');
			}
		}
		$info = $db->pe_select('page', array('page_id'=>$page_id));
		$seo = pe_seo($menutitle='修改单页', '', '', 'admin');
		include(pe_tpl('page_add.html'));
	break;
	//#####################@ 单页删除sql @#####################//
	case 'del':
		if ($db->pe_delete('page', array('page_id'=>intval($_g_id)))) {
			cache_write('page');
			pe_success('单页删除成功!');
		}
		else {
			pe_error('单页删除失败...');
		}
	break;
	//#####################@ 单页列表 @#####################//
	default :
		$info_list = $db->pe_selectall('page', array('order by'=>'`page_id` desc'), '*', array(20, $_g_page));
		$seo = pe_seo($menutitle='单页列表', '', '', 'admin');
		include(pe_tpl('page_list.html'));
	break;
}
?>