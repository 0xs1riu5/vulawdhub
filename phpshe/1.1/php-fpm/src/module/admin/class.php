<?php
/**
 * @copyright   2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate   2012-0501 koyshe <koyshe@gmail.com>
 */
$menumark = 'class';
pe_lead('hook/cache.hook.php');
switch ($act) {
	//#####################@ 分类增加 @#####################//
	case 'add':
		$class_id = intval($_g_id);
		if (isset($_p_pesubmit)) {
			if ($db->pe_insert('class', pe_dbhold($_p_info))) {
				cache_write('class');
				pe_success('分类增加成功!', 'admin.php?mod=class');
			}
			else {
				pe_error('分类增加失败...');
			}
		}
		$seo = pe_seo($menutitle='分类增加', '', '', 'admin');
		include(pe_tpl('class_add.html'));
	break;
	//#####################@ 分类修改 @#####################//
	case 'edit':
		$class_id = intval($_g_id);
		if (isset($_p_pesubmit)) {
			if ($db->pe_update('class', array('class_id'=>$class_id), pe_dbhold($_p_info))) {
				cache_write('class');
				pe_success('分类修改成功!', 'admin.php?mod=class');
			}
			else {
				pe_error('分类修改失败...');
			}
		}
		$info = $db->pe_select('class', array('class_id'=>$class_id));

		$seo = pe_seo($menutitle='分类修改', '', '', 'admin');
		include(pe_tpl('class_add.html'));
	break;
	//#####################@ 分类删除 @#####################//
	case 'del':
		$_g_id == 1 && pe_error('系统内置分类不能删除...');
		if ($db->pe_delete('class', array('class_id'=>$_g_id))) {
			cache_write('class');
			pe_success('分类删除成功!');
		}
		else {
			pe_error('分类删除失败...');
		}
	break;
	//#####################@ 分类排序 @#####################//
	case 'order':
		foreach ($_p_class_order as $k=>$v) {
			$result = $db->pe_update('class', array('class_id'=>$k), array('class_order'=>$v));
		}
		if ($result) {
			cache_write('class');
			pe_success('分类排序成功!');
		}
		else {
			pe_error('分类排序失败...');
		}
	break;
	//#####################@ 分类列表 @#####################//
	default :
		$info_list = $db->pe_selectall('class', array('order by'=>'`class_order` asc, `class_id` asc'));
		$seo = pe_seo($menutitle='文章分类', '', '', 'admin');
		include(pe_tpl('class_list.html'));
	break;
}
?>