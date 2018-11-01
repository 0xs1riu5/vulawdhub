<?php
/**
 * @copyright   2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate   2012-0501 koyshe <koyshe@gmail.com>
 */
$menumark = 'category';
pe_lead('hook/cache.hook.php');
pe_lead('hook/category.hook.php');
$category_treelist = category_treelist();
switch ($act) {
	//#####################@ 增加分类 @#####################//
	case 'add':
		if (isset($_p_pesubmit)) {
			if ($db->pe_insert('category', $_p_info)) {
				cache_write('category');
				pe_success('分类增加成功!', 'admin.php?mod=category');
			}
			else {
				pe_error('分类增加失败!');
			}
		}
		$seo = pe_seo($menutitle='增加分类', '', '', 'admin');
		include(pe_tpl('category_add.html'));
	break;
	//#####################@ 修改分类 @#####################//
	case 'edit':
		$category_id = intval($_g_id);
		if (isset($_p_pesubmit)) {
			if ($db->pe_update('category', array('category_id'=>$category_id), $_p_info)) {
				cache_write('category');
				pe_success('分类修改成功!', 'admin.php?mod=category');
			}
			else {
				pe_error('分类修改失败...');
			}
		}
		$info = $db->pe_select('category', array('category_id'=>$category_id));

		//不允许移动到的分类id数组
		$category = new category();
		$category_noid = $category->getcid_arr($category_treelist, $info['category_id']);
		$category_noid[] = $info['category_id'];

		$seo = pe_seo($menutitle='修改分类', '', '', 'admin');
		include(pe_tpl('category_add.html'));
	break;
	//#####################@ 分类排序 @#####################//
	case 'order':
		foreach ($_p_category_order as $k=>$v) {
			$result = $db->pe_update('category', array('category_id'=>$k), array('category_order'=>$v));
		}
		if ($result) {
			cache_write('category');
			pe_success('分类排序成功!');
		}
		else {
			pe_error('分类排序失败...');
		}
	break;
	//#####################@ 分类删除 @#####################//
	case 'del':
		if ($db->pe_delete('category', array('category_id'=>is_array($_p_category_id) ? $_p_category_id : $_g_id))) {
			cache_write('category');
			pe_success('分类删除成功!');
		}
		else {
			pe_error('分类删除失败...');
		}
	break;
	//#####################@ 分类列表 @#####################//
	default :
		$info_list = $category_treelist;

		$seo = pe_seo($menutitle='商品分类', '', '', 'admin');
		include(pe_tpl('category_list.html'));
	break;
}
?>