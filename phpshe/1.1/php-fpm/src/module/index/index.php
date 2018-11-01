<?php
//网站公告
$notice_list = $db->pe_selectall('article', array('class_id'=>1,'order by'=>'`article_atime` desc'), '*', array(10));
//商品推荐
$product_tuijian = $db->pe_selectall('product', array('product_istuijian'=>1, 'product_state'=>1, 'order by'=>'product_id desc'), '*', array(5));

pe_lead('hook/category.hook.php');
foreach((array)$cache_category_arr[0] as $k=>$v) {
	$v['product_newlist'] = $db->pe_selectall('product', array('category_id'=>category_cidarr($v['category_id']), 'product_state'=>1, 'order by'=>'product_id desc'), '*', array(8));
	$v['product_selllist'] = $db->pe_selectall('product', array('category_id'=>category_cidarr($v['category_id']), 'product_state'=>1, 'order by'=>'product_sellnum desc'), '*', array(5));
	$category_indexlist[] = $v;
}

$seo = pe_seo();
include(pe_tpl('index.html'));
?>