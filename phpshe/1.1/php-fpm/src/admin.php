<?php
/**
 * @copyright   2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate   2012-0501 koyshe <koyshe@gmail.com>
 */
include('common.php');
$adminmenu[] = array(
	'headnav' => '商品管理',
	'subnav' => array(
		array('name' => '商品分类', 'menumark' => 'category', 'url' => 'admin.php?mod=category'),
		array('name' => '商品列表', 'menumark' => 'product', 'url' => 'admin.php?mod=product&state=1'),
		array('name' => '商品咨询', 'menumark' => 'ask', 'url' => 'admin.php?mod=ask'),
		array('name' => '商品评价', 'menumark' => 'comment', 'url' => 'admin.php?mod=comment'),
		array('name' => '订单列表', 'menumark' => 'order', 'url' => 'admin.php?mod=order')
	)
);
$adminmenu[] = array(
	'headnav' => '信息管理',
	'subnav' => array(
		array('name' => '文章分类', 'menumark' => 'class', 'url' => 'admin.php?mod=class'),
		array('name' => '文章列表', 'menumark' => 'article', 'url' => 'admin.php?mod=article'),
		array('name' => '单页列表', 'menumark' => 'page', 'url' => 'admin.php?mod=page')
	)
);
$adminmenu[] = array(
	'headnav' => '用户管理',
	'subnav' => array(
		array('name' => '会员列表', 'menumark' => 'user', 'url' => 'admin.php?mod=user'),
		array('name' => '管理列表', 'menumark' => 'admin', 'url' => 'admin.php?mod=admin')
	)
);
$adminmenu[] = array(
	'headnav' => '控制面板',
	'subnav' => array(
		array('name' => '基本信息', 'menumark' => 'setting_base', 'url' => 'admin.php?mod=setting&act=base'),
		array('name' => '支付方式', 'menumark' => 'payway', 'url' => 'admin.php?mod=payway'),
		array('name' => '缓存管理', 'menumark' => 'cache', 'url' => 'admin.php?mod=cache'),
		array('name' => '数据安全', 'menumark' => 'db', 'url' => 'admin.php?mod=db'),
		array('name' => '友情链接', 'menumark' => 'link', 'url' => 'admin.php?mod=link'),
		array('name' => '广告列表', 'menumark' => 'ad', 'url' => 'admin.php?mod=ad')
	)
);

if (!pe_login('admin') && $act != 'login') {
	pe_goto('admin.php?mod=do&act=login');
}
if (pe_login('admin') && ($act == 'login' or $mod == 'index')) {
	pe_goto('admin.php?mod=order&act=list');
}
include("{$pe['path_root']}module/{$module}/{$mod}.php");
pe_result();
?>