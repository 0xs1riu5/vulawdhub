<?php
function cache_write($cache_type = 'all') {
	global $db;
	switch ($cache_type) {
		case 'category':
			cache::write('category', $db->index('category_id')->pe_selectall('category', array('order by'=>'`category_order` asc, `category_id` asc')));
			cache::write('category_arr', $db->index('category_pid|category_id')->pe_selectall('category', array('order by'=>'`category_order` asc, `category_id` asc')));
		break;
		case 'class':
			cache::write('class', $db->index('class_id')->pe_selectall('class', array('order by'=>'`class_order` asc, `class_id` asc')));
		break;
		case 'setting':
			cache::write('setting', 'setting_key');
		break;
		case 'payway':
			cache::write('payway', $db->index('payway_mark')->pe_selectall('payway', array('order by'=>'`payway_order` asc, `payway_id` asc')));
		break;
		case 'link':
			cache::write('link', $db->pe_selectall('link', array('order by'=>'`link_order` asc, `link_id` asc')));
		break;
		case 'page':
			cache::write('page', $db->index('page_id')->pe_selectall('page', '', '`page_id`, `page_name`'));
		break;
		case 'ad':
			cache::write('ad', $db->index('ad_position|ad_id')->pe_selectall('ad', array('order by'=>'`ad_order` asc, `ad_id` asc')));
		break;
		case 'template':
			pe_dirdel("{$pe['path_root']}data/cache/template");
		break;
		case 'attachment':
			pe_dirdel("{$pe['path_root']}data/cache/attachment");
		break;
		case 'thumb':
			pe_dirdel("{$pe['path_root']}data/cache/thumb");
		break;
		default:
			cache::write('category', $db->index('category_id')->pe_selectall('category', array('order by'=>'`category_order` asc, `category_id` asc')));
			cache::write('category_arr', $db->index('category_pid|category_id')->pe_selectall('category', array('order by'=>'`category_order` asc, `category_id` asc')));
			cache::write('class', $db->index('class_id')->pe_selectall('class', array('order by'=>'`class_order` asc, `class_id` asc')));
			cache::write('setting', 'setting_key');
			cache::write('payway', $db->index('payway_mark')->pe_selectall('payway', array('order by'=>'`payway_order` asc, `payway_id` asc')));
			cache::write('link', $db->pe_selectall('link', array('order by'=>'link_order asc')));
			cache::write('page', $db->index('page_id')->pe_selectall('page', '', '`page_id`, `page_name`'));
			cache::write('ad', $db->index('ad_position|ad_id')->pe_selectall('ad', array('order by'=>'`ad_order` asc, `ad_id` asc')));
			pe_dirdel("{$pe['path_root']}data/cache/template");
			pe_dirdel("{$pe['path_root']}data/cache/attachment");
			pe_dirdel("{$pe['path_root']}data/cache/thumb");
		break;
	}
}
?>