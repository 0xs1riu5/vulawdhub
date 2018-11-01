<?php
/**
 * @copyright   2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate   2012-0501 koyshe <koyshe@gmail.com>
 */
$menumark = 'article';
$cache_class = cache::get('class');
switch ($act) {
	//#####################@ 文章增加 @#####################//
	case 'add':
		if (isset($_p_pesubmit)) {
			$_p_info['article_atime'] = $_p_info['article_atime'] ? strtotime($_p_info['article_atime']) : time();		
			if ($db->pe_insert('article', pe_dbhold($_p_info, array('article_text')))) {
				pe_success('文章发布成功!', 'admin.php?mod=article');
			}
			else {
				pe_error('文章发布失败...');
			}
		}
		$seo = pe_seo($menutitle='发布文章', '', '', 'admin');
		include(pe_tpl('article_add.html'));
	break;
	//#####################@ 文章修改 @#####################//
	case 'edit':
		$article_id = intval($_g_id);
		if (isset($_p_pesubmit)) {
			$_p_info['article_atime'] = $_p_info['article_atime'] ? strtotime($_p_info['article_atime']) : time();
			if ($db->pe_update('article', array('article_id'=>$article_id), pe_dbhold($_p_info, array('article_text')))) {
				pe_success('文章修改成功!', $_g_fromto);
			}
			else {
				pe_error('文章修改失败...');
			}
		}
		$info = $db->pe_select('article', array('article_id'=>$article_id));
		$seo = pe_seo($menutitle='修改文章', '', '', 'admin');
		include(pe_tpl('article_add.html'));
	break;
	//#####################@ 文章删除sql @#####################//
	case 'del':
		if ($db->pe_delete('article', array('article_id'=>is_array($_p_article_id) ? $_p_article_id : $_g_id))) {
			pe_success('文章删除成功!');
		}
		else {
			pe_error('文章删除失败...');
		}
	break;
	//#####################@ 文章列表sql @#####################//
	default :
		$_g_name && $sqlwhere .= " and `article_name` like '%{$_g_name}%'";
		$_g_class_id && $sqlwhere .= " and `class_id` = '{$_g_class_id}'"; 
		$sqlwhere .= " order by `article_id` desc";
		$info_list = $db->pe_selectall('article', $sqlwhere, '*', array(20, $_g_page));

		$seo = pe_seo($menutitle='文章列表', '', '', 'admin');
		include(pe_tpl('article_list.html'));
	break;
}
?>