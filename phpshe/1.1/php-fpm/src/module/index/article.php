<?php

switch ($act) {
	//#####################@ 文章列表 @#####################//
	case 'list':
		$class_id = intval($id);
		$info_list = $db->pe_selectall('article', array('class_id'=>$class_id, 'order by'=>'`article_atime` desc'), '*', array(20, $_g_page));

		$nowpath = " > 资讯中心 > <a href='".pe_url("article-list-{$class_id}")."'>{$cache_class[$class_id]['class_name']}</a>";
		$seo = pe_seo($cache_class[$class_id]['class_name']);
		include(pe_tpl('page.html'));
	break;
	//#####################@ 文章内容 @#####################//
	default:
		$article_id = intval($act);
		$db->pe_update('article', array('article_id'=>$article_id), '`article_clicknum`=`article_clicknum`+1');
		$info = $db->pe_select('article', array('article_id'=>$article_id));

		$nowpath = " > 资讯中心 > <a href='".pe_url("article-list-{$info['class_id']}")."'>{$cache_class[$info['class_id']]['class_name']}</a>  > <a href='".pe_url("article-{$article_id}")."'>{$info['article_name']}</a>";
		$seo = pe_seo($info['article_name']);
		include(pe_tpl('page.html'));
	break;
}
?>