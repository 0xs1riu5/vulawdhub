<?php
/**
 * @copyright   2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate   2012-0501 koyshe <koyshe@gmail.com>
 */
$menumark = 'cache';
switch ($act) {
	//#####################@ 缓存更新 @#####################//
	case 'update':
		pe_lead('hook/cache.hook.php');
		cache_write($_g_cache);
		pe_success('缓存更新成功!');
	break;
	//#####################@ 缓存列表 @#####################//
	default:
		//数据库缓存
		$info_list['category']['cache_name'] = '分类信息';
		$info_list['category']['cache_text'] = '网站【商品分类】【文章分类】里信息显示错乱或不显示时，可尝试更新此项。';
		$category_size = filesize("{$pe['path_root']}data/cache/category.cache.php");
		$category_size += filesize("{$pe['path_root']}data/cache/category_arr.cache.php");
		$category_size += filesize("{$pe['path_root']}data/cache/class.cache.php");
		$info_list['category']['cache_size'] = round($category_size/1024, 1);

		$info_list['page']['cache_name'] = '单页信息';
		$info_list['page']['cache_text'] = '网站【单页列表】里信息显示错乱或不显示时，可尝试更新此项。';
		$info_list['page']['cache_size'] = round(filesize("{$pe['path_root']}data/cache/page.cache.php")/1024, 1);

		$info_list['setting']['cache_name'] = '网站信息';
		$info_list['setting']['cache_text'] = '网站【基本信息】里信息显示错乱或不显示时，可尝试更新此项。';
		$info_list['setting']['cache_size'] = round(filesize("{$pe['path_root']}data/cache/setting.cache.php")/1024, 1);

		$info_list['payway']['cache_name'] = '支付信息';
		$info_list['payway']['cache_text'] = '网站【支付方式】里信息显示错乱或不显示时，可尝试更新此项。';
		$info_list['payway']['cache_size'] = round(filesize("{$pe['path_root']}data/cache/payway.cache.php")/1024, 1);

		$info_list['link']['cache_name'] = '友链信息';
		$info_list['link']['cache_text'] = '网站【友情链接】里信息显示错乱或不显示时，可尝试更新此项。';
		$info_list['link']['cache_size'] = round(filesize("{$pe['path_root']}data/cache/link.cache.php")/1024, 1);
		
		$info_list['ad']['cache_name'] = '广告信息';
		$info_list['ad']['cache_text'] = '网站【广告列表】里信息显示错乱或不显示时，可尝试更新此项。';
		$info_list['ad']['cache_size'] = round(filesize("{$pe['path_root']}data/cache/ad.cache.php")/1024, 1);

		//数据缓存
		$info_list['template']['cache_name'] = '模板信息';
		$info_list['template']['cache_text'] = '网站页面显示错乱或不显示时，可尝试更新此项。';
		$info_list['template']['cache_size'] = round(pe_dirsize("{$pe['path_root']}data/cache/template")/1024, 1);

		$info_list['attachment']['cache_name'] = '附件信息';
		$info_list['attachment']['cache_text'] = '附件缓存过大时，可更新此项。';
		$info_list['attachment']['cache_size'] = round(pe_dirsize("{$pe['path_root']}data/cache/attachment")/1024, 1);

		$info_list['thumb']['cache_name'] = '缩略图信息';
		$info_list['thumb']['cache_text'] = '缩略图缓存过大时，可更新此项。';
		$info_list['thumb']['cache_size'] = round(pe_dirsize("{$pe['path_root']}data/cache/thumb")/1024, 1);

		$seo = pe_seo($menutitle='缓存管理', '', '', 'admin');
		include(pe_tpl('cache_list.html'));
	break;
}
?>