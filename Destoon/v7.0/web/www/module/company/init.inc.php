<?php 
defined('IN_DESTOON') or exit('Access Denied');
if($EXT['mobile_enable']) {
	if(DT_TOUCH) include DT_ROOT.'/include/mobile.inc.php';
	$head_mobile = $DT_URL;
	$foot = '';
}
isset($file) or $file = 'homepage';
if(isset($update) || isset($preview)) {
	$db->cids = 1;
	userclean($username);
}
$COM = userinfo($username);
if(!$COM || ($COM['groupid'] < 5 && $COM['groupid'] > 1)) {
	userclean($username);
	$head_title = $L['not_company'];
	if($DT_BOT) dhttp(404, $DT_BOT);
	include template('com-notfound', 'message');
	exit;
}
if(!$COM['edittime'] && !$MOD['openall']) {
	if($DT_BOT) dhttp(404, $DT_BOT);
	$head_title = $COM['company'];
	include template('com-opening', 'message');
	exit;
}
$domain = $COM['domain'];
if($domain) {
	if(!isset($preview) && !isset($update) && !isset($key)) {
		if($CFG['com_domain']) {
			if(strpos($DT_URL, $domain) === false) {
				$subdomain = userurl($username);
				if(strpos($DT_URL, $subdomain) === false) {
					dheader('http://'.$domain.'/');
				} else {
					if($DT_URL == $subdomain.'index.php' || $DT_URL == $subdomain) dheader('http://'.$domain.'/');
					dheader(str_replace($subdomain, 'http://'.$domain.'/', $DT_URL));
				}
			}
		} else {
			if(strpos($DT_URL, $domain) === false) dheader(userurl($username, ($file && $file != 'homepage') ? 'file='.$file : '', $domain));
		}
	}
	$DT['rewrite'] = intval($CFG['com_rewrite']);
}
$userid = $COM['userid'];
$linkurl = userurl($username, '', $domain);
$clean = 0;
if($COM['linkurl'] != $linkurl) {
	$COM['linkurl'] = $linkurl;
	$db->query("UPDATE LOW_PRIORITY {$table} SET linkurl='$linkurl' WHERE userid=$userid", 'UNBUFFERED');
	$clean = 1;
}
if($MOD['delvip'] && $COM['vip'] && $COM['totime'] && $COM['totime'] < $DT_TIME) {//VIP Expired
	$COM['vip'] = 0;
	$COM['groupid'] = $gid = $COM['regid'] == 5 ? 5 : 6;
	$COM['skin'] = $COM['template'] = '';
	$db->query("UPDATE {$table} SET groupid=$gid,vip=0,styletime=0,skin='',template='' WHERE userid=$userid");
	$db->query("UPDATE {$DT_PRE}member SET groupid=$gid WHERE userid=$userid");
	$clean = 1;
}
if($COM['styletime'] && $COM['styletime'] < $DT_TIME) {//SKIN Expired
	$COM['skin'] = $COM['template'] = '';
	$db->query("UPDATE {$table} SET styletime=0,skin='',template='' WHERE userid=$userid");
	$clean = 1;
}
if($clean) userclean($username);
$COM['year'] = vip_year($COM['fromtime']);
$COMGROUP = cache_read('group-'.$COM['groupid'].'.php');
if(!isset($COMGROUP['homepage']) || !$COMGROUP['homepage']) {
	$head_title = $COM['company'];
	$head_keywords = $COM['keyword'];
	$head_description = $COM['introduce'];
	$member = $COM;
	$content_table = content_table(4, $userid, is_file(DT_CACHE.'/4.part'), $DT_PRE.'company_data');
	$r = $db->get_one("SELECT content FROM {$content_table} WHERE userid=$userid", 'CACHE');
	$content = $r['content'];
	$member['thumb'] = $member['thumb'] ? $member['thumb'] : DT_SKIN.'image/company.jpg';
	include template('show', $module);
	exit;
}
$api_map = ($MOD['map'] && $COMGROUP['map']) ? $MOD['map'] : '';
$api_stats = ($MOD['stats'] && $COMGROUP['stats']) ? $MOD['stats'] : '';
$api_kf = ($MOD['kf'] && $COMGROUP['kf']) ? $MOD['kf'] : '';
isset($rewrite) or $rewrite = '';
if($rewrite) {
	$r = explode('-', $rewrite);
	$rc = count($r);
	if($rc%2 == 0) {
		for($i = 0; $i < $rc; $i++) {
			if(in_array($r[$i], array('itemid', 'typeid', 'page', 'view', 'kw', 'preview', 'update'))) {
				${$r[$i]} = $r[++$i];
			} else {
				++$i;
			}
		}
	}
	$page = isset($page) ? max(intval($page), 1) : 1;
	$catid = isset($catid) ? intval($catid) : 0;
	$itemid = isset($itemid) ? (is_array($itemid) ? $itemid : intval($itemid)) : 0;
	$kw = isset($kw) ? strip_kw($kw, $DT['max_kw']) : '';
	if(strlen($kw) < $DT['min_kw'] || strlen($kw) > $DT['max_kw']) $kw = '';
	$keyword = $kw ? str_replace(array(' ', '*'), array('%', '%'), $kw) : '';
}
include load('homepage.lang');
in_array($file, $MFILE) or dheader($MOD['linkurl']);
if($COMGROUP['menu_d']) {
	$_menu_show = array();
	foreach($HMENU as $k=>$v) {
		$_menu_show[$k] = strpos(','.$COMGROUP['menu_d'].',', ','.$k.',') !== false ? 1 : 0;
	}
	$_menu_show = implode(',', $_menu_show);
} else {
	$_menu_show = '1,1,1,1,1,1,1,1,0,0,0,0,0,0';
}
$_menu_order = '0,10,20,30,40,50,60,70,80,90,100,110,120,130';
$_menu_num = '1,16,30,30,10,30,1,12,12,12,12,30,12,1';
$_menu_file = implode(',' , $MFILE);
$_menu_name = implode(',' , $HMENU);

if($COMGROUP['side_d']) {
	$_side_show = array();
	foreach($HSIDE as $k=>$v) {
		$_side_show[$k] = strpos(','.$COMGROUP['side_d'].',', ','.$k.',') !== false ? 1 : 0;
	}
	$_side_show = implode(',', $_side_show);
} else {
	$_side_show = '1,1,1,0,1,0,1';
}
$_side_order = '0,10,20,30,40,50,60';
$_side_num = '1,5,10,1,1,5,5';
$_side_file = implode(',' , $SFILE);
$_side_name = implode(',' , $HSIDE);

$HOME = get_company_setting($COM['userid'], '', 'CACHE');

//if(isset($HOME['menu_file'])) $HOME['menu_file'] = str_replace('credit', 'honor', $HOME['menu_file']);//For 3.x
//if(isset($HOME['side_file'])) $HOME['side_file'] = str_replace('credit', 'honor', $HOME['side_file']);//For 3.x

$menu_show = explode(',', isset($HOME['menu_show']) ? $HOME['menu_show'] : $_menu_show);
$menu_order = explode(',', isset($HOME['menu_order']) ? $HOME['menu_order'] : $_menu_order);
$menu_num = explode(',', isset($HOME['menu_num']) ? $HOME['menu_num'] : $_menu_num);
$menu_file = explode(',', isset($HOME['menu_file']) ? $HOME['menu_file'] : $_menu_file);
$menu_name = explode(',', isset($HOME['menu_name']) ? $HOME['menu_name'] : $_menu_name);
$_HMENU = array();
asort($menu_order);
foreach($menu_order as $k=>$v) {
	$_HMENU[$k] = $HMENU[$k];
}
$HMENU = $_HMENU;

$MENU = array();
$menuid = 0;
foreach($HMENU as $k=>$v) {
	if($menu_show[$k] && in_array($menu_file[$k], $MFILE)) {
		$MENU[$k]['name'] = $menu_name[$k];
		$MENU[$k]['file'] = $menu_file[$k];
		$MENU[$k]['linkurl'] = userurl($username, 'file='.$menu_file[$k], $domain);
	}
	if($file == $menu_file[$k]) $menuid = $k;
	if($menu_num[$k] < 1 || $menu_num[$k] > 50) $menu_num[$k] = 10;
}

isset($_MENU['introduce']) or $_MENU['introduce'] = $L['com_introduce'];
isset($_MENU['news']) or $_MENU['news'] = $L['com_news'];
isset($_MENU['credit']) or $_MENU['credit'] = $L['com_credit'];
isset($_MENU['contact']) or $_MENU['contact'] = $L['com_contact'];

$side_show = explode(',', isset($HOME['side_show']) ? $HOME['side_show'] : $_side_show);
$side_order = explode(',', isset($HOME['side_order']) ? $HOME['side_order'] : $_side_order);
$side_num = explode(',', isset($HOME['side_num']) ? $HOME['side_num'] : $_side_num);
$side_file = explode(',', isset($HOME['side_file']) ? $HOME['side_file'] : $_side_file);
$side_name = explode(',', isset($HOME['side_name']) ? $HOME['side_name'] : $_side_name);
$_HSIDE = array();
asort($side_order);
foreach($side_order as $k=>$v) {
	if($side_show[$k] && in_array($side_file[$k], $SFILE)) {
		$_HSIDE[$k] = $HSIDE[$k];
	}
	if($side_num[$k] < 1 || $side_num[$k] > 50) $side_num[$k] = 10;
}
$HSIDE = $_HSIDE;
$side_pos = isset($HOME['side_pos']) && $HOME['side_pos'] ? 1 : 0;
$side_width = isset($HOME['side_width']) && $HOME['side_width'] ? $HOME['side_width'] : 200;
$show_stats = isset($HOME['show_stats']) && $HOME['show_stats'] == 0 ? 0 : 1;
$skin = 'default';
$template = 'homepage';
if($COM['skin'] && $COM['template']) {
	$skin = $COM['skin'];
	$template = $COM['template'];
} else if($COMGROUP['styleid']) {
	$r = $db->get_one("SELECT skin,template FROM {$DT_PRE}style WHERE itemid=$COMGROUP[styleid]", 'CACHE');
	if($r) {
		$skin = $r['skin'];
		$template = $r['template'];
	}
}
$preview = isset($preview) ? intval($preview) : 0;
if($file == 'homepage') {
	if($preview) {
		$preview = $db->get_one("SELECT * FROM {$DT_PRE}style WHERE itemid={$preview}");
		if($preview) {
			$skin = $preview['skin'];
			$template = $preview['template'];
		}
	}
}
$bannert = isset($HOME['bannert']) ? $HOME['bannert'] : 0;
$banner = isset($HOME['banner']) ? $HOME['banner'] : '';
$bannerf = isset($HOME['bannerf']) ? $HOME['bannerf'] : '';
$banner1 = isset($HOME['banner1']) ? $HOME['banner1'] : '';
$banner2 = isset($HOME['banner2']) ? $HOME['banner2'] : '';
$banner3 = isset($HOME['banner3']) ? $HOME['banner3'] : '';
$banner4 = isset($HOME['banner4']) ? $HOME['banner4'] : '';
$banner5 = isset($HOME['banner5']) ? $HOME['banner5'] : '';
$bannerlink1 = isset($HOME['bannerlink1']) ? $HOME['bannerlink1'] : '';
$bannerlink2 = isset($HOME['bannerlink2']) ? $HOME['bannerlink2'] : '';
$bannerlink3 = isset($HOME['bannerlink3']) ? $HOME['bannerlink3'] : '';
$bannerlink4 = isset($HOME['bannerlink4']) ? $HOME['bannerlink4'] : '';
$bannerlink5 = isset($HOME['bannerlink5']) ? $HOME['bannerlink5'] : '';
if($bannert == 2) {
	if($banner1) {
		if(!$banner2) {
			$bannert = 0;
			$banner = $banner1;
		}
	} else {
		$bannert = 0;
	}
} else if($bannert == 1) {
	if($bannerf) {
		if(preg_match("/^(jpg|jpeg|gif|png|bmp)$/i", file_ext($bannerf))) {
			$bannert = 0;
			$banner = $bannert;
		}
	} else {
		$bannert = 0;
	}
}
$bannerw = (isset($HOME['bannerw']) && $HOME['bannerw']) ? intval($HOME['bannerw']) : 960;
$bannerh = (isset($HOME['bannerh']) && $HOME['bannerh']) ? intval($HOME['bannerh']) : 200;
$could_comment = $MOD['comment'];
$homeurl = $MOD['homeurl'];
if($domain) $could_comment = false;
$could_contact = check_group($_groupid, $MOD['group_contact']);
if($username == $_username || $domain) $could_contact = true;
$HSPATH = DT_STATIC.$MODULE[4]['moduledir'].'/skin/'.$skin.'/';
if(!$banner) $banner = is_file(DT_ROOT.'/'.$MODULE[4]['moduledir'].'/skin/'.$skin.'/banner.jpg') ? $HSPATH.'banner.jpg' : '';
$background = isset($HOME['background']) ? $HOME['background'] : '';
$bgcolor = isset($HOME['bgcolor']) ? $HOME['bgcolor'] : '';
$logo = isset($HOME['logo']) ? $HOME['logo'] : '';
$video = isset($HOME['video']) ? $HOME['video'] : '';
$css = isset($HOME['css']) ? $HOME['css'] : '';
$announce = isset($HOME['announce']) ? $HOME['announce'] : '';
$map = isset($HOME['map']) ? $HOME['map'] : '';
$stats = isset($HOME['stats']) ? $HOME['stats'] : '';
$kf = isset($HOME['kf']) ? $HOME['kf'] : '';
$comment_proxy = '';
if($domain) {
	$comment_proxy = 'http://'.$domain.'/';
} else {
	if($CFG['com_domain']) {
		$comment_proxy = $linkurl;
		$comment_proxy = substr($CFG['com_domain'], 0, 1) == '.' ? $linkurl : 'http://'.$CFG['com_domain'].'/';
	} else {
		$comment_proxy = DT_PATH;
	}
}
$comment_proxy = encrypt($comment_proxy, DT_KEY.'PROXY');
$album_js = 0;
$head_title = $head_name = $MENU[$menuid]['name'];
$seo_keywords = isset($HOME['seo_keywords']) ? $HOME['seo_keywords'] : '';
$seo_description = isset($HOME['seo_description']) ? $HOME['seo_description'] : '';
$head_keywords = strip_tags($seo_keywords ? $seo_keywords : $COM['company'].','.str_replace('|', ',', $COM['business']));
$head_description = strip_tags($seo_description ? $seo_description : $COM['introduce']);
if(!$DT_BOT && $MOD['hits']) {
	if($DT['cache_hits']) {
		 cache_hits($moduleid, $userid);
	} else {
		$db->query("UPDATE LOW_PRIORITY {$table} SET hits=hits+1 WHERE userid=$userid", 'UNBUFFERED');
	}
}
if($DT_PC) {
	//
} else {
	$back_link = $linkurl;
}
include DT_ROOT.'/module/company/'.$file.'.inc.php';
?>