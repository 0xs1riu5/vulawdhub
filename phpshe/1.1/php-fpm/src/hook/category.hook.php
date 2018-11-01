<?php
function category_treelist()
{
	global $db;
	pe_lead('include/class/categorytree.class.php');
	$category = new category();
	return $category->gettree($db->pe_selectall('category', array('order by'=> 'category_order asc, category_id asc')));
}
function category_path($id, $other = null)
{
	global $pe;
	$category_list = cache::get('category');
	pe_lead('include/class/categorytree.class.php');
	$category = new category();
	$pid_arr = $category->getpid_arr($category_list, $id);
	$path = "您现在的位置：<a href='{$pe['host_root']}'>首页</a>";
	
	foreach ($pid_arr as $v) {
		$path .= " > <a href='".pe_url("product-list-{$category_list[$v]['category_id']}")."' title='{$category_list[$v]['category_name']}'>{$category_list[$v]['category_name']}</a>";
	}
	$path .= " > <a href='".pe_url("product-list-{$category_list[$id]['category_id']}")."' title='{$category_list[$id]['category_name']}'>{$category_list[$id]['category_name']}</a>";
	$other && $path .= " > {$other}";
	return $path;
}
function category_cidarr($id) {
	$category_list = cache::get('category');
	pe_lead('include/class/categorytree.class.php');
	$category = new category();
	$cid_arr = $category->getcid_arr($category_list, $id);
	if ($cid_arr) {
		$cid_arr[] = $id;
		return $cid_arr;
	}
	else {
		return $id;
	}
}
?>