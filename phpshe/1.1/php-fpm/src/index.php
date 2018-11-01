<?php
include('common.php');

$cache_category = cache::get('category');
$cache_category_arr = cache::get('category_arr');
$cache_class = cache::get('class');

$cache_ad = cache::get('ad');
$cache_link = cache::get('link');
$cache_page = cache::get('page');
$web_qq = $cache_setting['web_qq']['setting_value'] ? explode(',', $cache_setting['web_qq']['setting_value']) : array();

$cart_num = pe_login('user') ? $db->pe_num('cart', array('user_id'=>$_s_user_id)) : (unserialize($_c_cart_list) ? count(unserialize($_c_cart_list)) : 0);

include("{$pe['path_root']}module/{$module}/{$mod}.php");
pe_result();
?>