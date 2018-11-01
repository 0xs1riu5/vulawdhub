<?php
/**
 * @copyright   2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate   2012-0501 koyshe <koyshe@gmail.com>
 */
$menumark = 'ad';
pe_lead('hook/cache.hook.php');
$ad_position = array('index_jdt'=>'首页焦点图广告(730*300)', 'index_header'=>'首页顶部广告(980*80)','index_footer'=>'首页底部广告(980*80)', 'header'=>'所有页面顶部广告(980*80)','footer'=>'所有页面底部广告(980*80)');
switch ($act) {
	//#####################@ 增加广告 @#####################//
	case 'add':
		if (isset($_p_pesubmit)) {
			if ($_FILES['ad_logo']['size']) {
				pe_lead('include/class/upload.class.php');
				$upload = new upload($_FILES['ad_logo']);
				$_p_info['ad_logo'] = $upload->filehost;
			}
			if ($db->pe_insert('ad', pe_dbhold($_p_info))) {
				cache_write('ad');
				pe_success('广告增加成功!', 'admin.php?mod=ad');
			}
			else {
				pe_error('广告增加失败...');
			}
		}
		$seo = pe_seo($menutitle='增加广告', '', '', 'admin');
		include(pe_tpl('ad_add.html'));
	break;
	//#####################@ 修改广告 @#####################//
	case 'edit':
		$ad_id = intval($_g_id);
		if (isset($_p_pesubmit)) {
			if ($_FILES['ad_logo']['size']) {
				pe_lead('include/class/upload.class.php');
				$upload = new upload($_FILES['ad_logo']);
				$_p_info['ad_logo'] = $upload->filehost;
			}
			if ($db->pe_update('ad', array('ad_id'=>$ad_id), pe_dbhold($_p_info))) {
				cache_write('ad');
				pe_success('广告修改成功!', 'admin.php?mod=ad');
			}
			else {
				pe_error('广告修改失败...');
			}
		}
		$info = $db->pe_select('ad', array('ad_id'=>$ad_id));
		$seo = pe_seo($menutitle='修改广告', '', '', 'admin');
		include(pe_tpl('ad_add.html'));
	break;
	//#####################@ 广告排序 @#####################//
	case 'order':
		foreach ($_p_ad_order as $k=>$v) {
			$result = $db->pe_update('ad', array('ad_id'=>$k), array('ad_order'=>$v));
		}
		if ($result) {
			cache_write('ad');
			pe_success('广告排序成功!');
		}
		else {
			pe_error('广告排序失败...');
		}
	break;
	//#####################@ 广告删除 @#####################//
	case 'del':
		if ($db->pe_delete('ad', array('ad_id'=>is_array($_p_ad_id) ? $_p_ad_id : $_g_id))) {
			cache_write('ad');
			pe_success('广告删除成功!');
		}
		else {
			pe_error('广告删除失败...');
		}
	break;
	//#####################@ 广告列表 @#####################//
	default :
		$info_list = $db->pe_selectall('ad', array('order by'=>'`ad_order` asc, `ad_id` asc'), '*', array(10, $_g_page));
		$seo = pe_seo($menutitle='广告列表', '', '', 'admin');
		include(pe_tpl('ad_list.html'));
	break;
}
?>