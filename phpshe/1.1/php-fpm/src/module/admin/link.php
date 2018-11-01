<?php
/**
 * @copyright   2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate   2012-0501 koyshe <koyshe@gmail.com>
 */
$menumark = 'link';
pe_lead('hook/cache.hook.php');
switch ($act) {
	//#####################@ 链接增加 @#####################//
	case 'add':
		if (isset($_p_pesubmit)) {
			stripos($_p_info['link_url'], 'http://') === false && $_p_info['link_url'] = "http://{$_p_info['link_url']}";
			if ($db->pe_insert('link', pe_dbhold($_p_info))) {
				cache_write('link');
				pe_success('链接增加成功!', 'admin.php?mod=link');
			}
			else {
				pe_error('链接增加失败...');
			}
		}
		$seo = pe_seo($menutitle='增加链接', '', '', 'admin');
		include(pe_tpl('link_add.html'));
	break;
	//#####################@ 链接修改 @#####################//
	case 'edit':
		$link_id = intval($_g_id);
		if (isset($_p_pesubmit)) {
			stripos($_p_info['link_url'], 'http://') === false && $_p_info['link_url'] = "http://{$_p_info['link_url']}";
			if ($db->pe_update('link', array('link_id'=>$link_id), pe_dbhold($_p_info))) {
				cache_write('link');
				pe_success('链接修改成功!', 'admin.php?mod=link');
			}
			else {
				pe_error('链接修改失败...' );
			}
		}
		$info = $db->pe_select('link', array('link_id'=>$link_id));
		$seo = pe_seo($menutitle='修改链接', '', '', 'admin');
		include(pe_tpl('link_add.html'));
	break;
	//#####################@ 链接删除 @#####################//
	case 'del':
		if ($db->pe_delete('link', array('link_id'=>is_array($_p_link_id) ? $_p_link_id : $_g_id))) {
			cache_write('link');
			pe_success('链接删除成功!');
		}
		else {
			pe_error('链接删除失败...');
		}
	break;
	//#####################@ 链接排序 @#####################//
	case 'order':
		foreach ($_p_link_order as $k => $v) {
			$result = $db->pe_update('link', array('link_id'=>$k), array('link_order'=>$v));
		}
		if ($result) {
			cache_write('link');
			pe_success('链接排序成功!');
		}
		else {
			pe_error('链接排序失败...');
		}
	break;
	//#####################@ 链接列表 @#####################//
	default:
		$info_list = $db->pe_selectall('link', array('order by'=>'`link_order` asc, `link_id` asc'), '*', array(10, $_g_page));
		$seo = pe_seo($menutitle='友情链接', '', '', 'admin');
		include(pe_tpl('link_list.html'));
	break;
}
?>