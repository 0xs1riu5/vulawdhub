<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('文件备份', '?file=patch'),
    array('木马扫描', '?file=scan'),
    array('文件校验', '?file=md5'),
);
$bd_code = base64_decode('VkJTY3JpcHQuRW5jb2RlfEdldFByb2Nlc3Nlc3xnenVuY29tcHJlc3N8Z3ppbmZsYXRlfHBhc3N0aHJ1fGV2YWx8YmFzZTY0X2RlY29kZXxzaGVsbHx6ZW5kfGV4ZWN8Y21kfHNvbmFtZXx3aW5kb3dzfDAwMDAwMHxmc28ufC5leGV8LmRsbHzlrbF85o+Q5p2DfOaMgumprHzmnKjpqax8XHg=');
$bd_code = convert($bd_code, 'UTF-8', DT_CHARSET);
$bd_ext = 'php|asp|aspx|asa|asax|dll|jsp|cgi|fcgi|pl';
if($submit) {	
	$W = array(
		'baidunews.xml' => 1,
		'config.inc.php' => 1,
		'index.html' => 1,
		'admin/area.inc.php' => 1,
		'admin/config.inc.php' => 1,
		'admin/data.inc.php' => 2,
		'admin/database.inc.php' => 1,
		'admin/license.func.php' => 1,
		'admin/log.inc.php' => 1,
		'admin/md5.inc.php' => 1,
		'admin/menu.inc.php' => 1,
		'admin/patch.inc.php' => 1,
		'admin/scan.inc.php' => 3,
		'admin/tag.inc.php' => 1,
		'admin/unzip.class.php' => 2,
		'admin/update.inc.php' => 1,
		'admin/template.inc.php' => 1,
		'admin/template/count.tpl.php' => 1,
		'admin/template/msg.tpl.php' => 1,
		'admin/template/scan.tpl.php' => 1,
		'admin/template/setting.tpl.php' => 1,
		'admin/template/tag_preview.tpl.php' => 1,
		'api/memcache.php' => 3,
		'api/avatar/upload.php' => 1,
		'api/oauth/baidu/callback.php' => 1,
		'api/oauth/netease/callback.php' => 1,
		'api/oauth/qq/callback.php' => 1,
		'api/oauth/qq/index.php' => 1,
		'api/oauth/qq/post.php' => 1,
		'api/oauth/qq/qzone.php' => 1,
		'api/qrcode.png.php' => 3,
		'api/pay/kq99bill/notify.php' => 1,
		'api/pay/paypal/notify.php' => 1,
		'api/pay/paypal/send.inc.php' => 1,
		'api/pay/yeepay/send.inc.php' => 1,
		'include/cache_shmop.class.php' => 1,
		'include/captcha.class.php' => 1,
		'include/fields.func.php' => 1,
		'include/file.func.php' => 1,
		'include/global.func.php' => 3,
		'include/ip.class.php' => 1,
		'include/post.func.php' => 2,
		'include/safe.func.php' => 2,
		'include/seo.inc.php' => 1,
		'include/session_apc.class.php' => 1,
		'include/session_eaccelerator.class.php' => 1,
		'include/session_file.class.php' => 1,
		'include/session_memcache.class.php' => 1,
		'include/session_mysql.class.php' => 1,
		'include/session_redis.class.php' => 1,
		'include/session_shmop.class.php' => 1,
		'include/session_wincache.class.php' => 1,
		'include/session_xcache.class.php' => 1,
		'include/sql.func.php' => 1,
		'include/template.func.php' => 1,
		'install/index.php' => 1,
		'mobile/common.inc.php' => 1,
		'module/brand/admin/install.inc.php' => 1,
		'module/buy/admin/install.inc.php' => 1,
		'module/club/admin/install.inc.php' => 1,
		'module/down/admin/install.inc.php' => 1,
		'module/exhibit/admin/install.inc.php' => 1,
		'module/group/admin/install.inc.php' => 1,
		'module/group/admin/template/order_stats.tpl.php' => 1,
		'module/job/admin/install.inc.php' => 1,
		'module/know/admin/install.inc.php' => 1,
		'module/mall/admin/install.inc.php' => 1,
		'module/mall/admin/template/order_stats.tpl.php' => 1,
		'module/member/admin/promo.inc.php' => 1,
		'module/member/admin/sendmail.inc.php' => 1,
		'module/member/admin/sendsms.inc.php' => 1,
		'module/member/admin/template/cash_stats.tpl.php' => 1,
		'module/member/admin/template/charge_stats.tpl.php' => 1,
		'module/member/admin/template/pay_stats.tpl.php' => 1,
		'module/member/message.inc.php' => 1,
		'module/photo/admin/install.inc.php' => 1,
		'module/quote/admin/install.inc.php' => 1,
		'module/quote/price.inc.php' => 1,
		'module/sell/admin/install.inc.php' => 1,
		'module/special/admin/install.inc.php' => 1,
		'module/special/type.inc.php' => 1,
		'module/video/admin/install.inc.php' => 1,
		'upgrade/config.inc.php' => 1,
		'upgrade/index.php' => 1,
		'mobile/index.php' => 1,
	);
	isset($filedir) or $filedir = array();
	$fileext or $fileext = $bd_ext;
	$code or $code = $bd_code;
	$codenum or $codenum = 1;
	$code = str_replace('\|', '|', preg_quote(stripslashes($code)));
	$code = convert($code, DT_CHARSET, $charset);
	$files = array();
	foreach(glob(DT_ROOT.'/*.*') as $f) {
		$files[] = $f;
	}
	foreach($filedir as $d) {
		$files = array_merge($files, get_file(DT_ROOT.'/'.$d, $fileext));
	}
	$lists = $mirror = array();
	if(is_file(DT_ROOT.'/file/md5/'.DT_VERSION.'.php')) {
		$content = substr(trim(file_get(DT_ROOT.'/file/md5/'.DT_VERSION.'.php')), 13);
		foreach(explode("\n", $content) as $v) {
			list($m, $f) = explode(' ', trim($v));
			$mirror[$m] = $f;
		}
	}
	foreach($files as $f) {
		$content = file_get($f);
		if(preg_match_all('/('.$code.')/i', $content, $m)) {
			$r = $c = array();
			foreach($m[1] as $v) {
				in_array($v, $c) or $c[] = $v;
			}
			$r['num'] = count($c);
			if($r['num'] < $codenum && strpos($content, 'Zend') === false) continue;
			$r['file'] = str_replace(DT_ROOT.'/', '', $f);
			if($mirror && in_array($r['file'], $mirror)) {
				if(md5_file($f) == array_search($r['file'], $mirror)) continue;
			}
			if(isset($W[$r['file']]) && $W[$r['file']] == $r['num']) continue;
			$r['code'] = convert(implode(',', $c), $charset, DT_CHARSET);
			$lists[] = $r;
		}
	}
	$find = count($lists);
} else {
	$files = glob(DT_ROOT.'/*');
	$dirs = $rfiles = array();
	foreach($files as $f) {
		$bn = basename($f);		
		if(is_file($f)) {
			$rfiles[] = $bn;
		} else {
			if($bn == 'file') continue;
			$dirs[] = $bn;
		}
	}
	$code = $bd_code;
	$fileext = $bd_ext;
}
include tpl('scan');
?>