<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
function msg($msg = errmsg, $forward = 'goback', $time = '1') {
	global $CFG;
	if(!$msg && $forward && $forward != 'goback') dheader($forward);
	include DT_ROOT.'/admin/template/msg.tpl.php';
    exit;
}

function dialog($dcontent) {
	global $CFG;
	include DT_ROOT.'/admin/template/dialog.tpl.php';
    exit;
}

function tpl($file = 'index', $mod = 'destoon') {
	global $CFG, $DT;
	return $mod == 'destoon' ? DT_ROOT.'/admin/template/'.$file.'.tpl.php' : DT_ROOT.'/module/'.$mod.'/admin/template/'.$file.'.tpl.php';
}

function progress($sid, $fid, $tid) {
	if($tid > $sid && $fid < $tid) {
		$p = dround(($fid-$sid)*100/($tid-$sid), 0, true);
		if($p > 100) $p = 100;
		if($p < 1) $p = 1;
		$p = $p.'%';
	} else {
		$p = '100%';
	}
	return '<table cellpadding="0" cellspacing="0" width="100%" style="margin:0"><tr><td><div class="progress"><div style="width:'.$p.';">&nbsp;</div></div></td><td style="color:#666666;font-size:10px;width:40px;text-align:center;">'.$p.'</td></tr></table>';
}

function show_menu($menus = array()) {
	global $moduleid, $mid, $module, $file, $action;
    $menu = '';
    foreach($menus as $id=>$m) {
		if(isset($m[1])) {
			$extend = isset($m[2]) ? $m[2] : '';
			$menu .= '<td id="Tab'.$id.'" class="tab"><a href="'.$m[1].'" '.$extend.'>'.$m[0].'</a></td>';
		} else {
			$class = $id == 0 ? 'tab_on' : 'tab';
			$menu .= '<td id="Tab'.$id.'" class="'.$class.'"><a href="javascript:Tab('.$id.');">'.$m[0].'</a></td>';
		}
	}
	include DT_ROOT.'/admin/template/menu.tpl.php';
}

function update_setting($item, $setting) {
	DB::query("DELETE FROM ".DT_PRE."setting WHERE item='$item'");
	foreach($setting as $k=>$v) {
		if(is_array($v)) $v = implode(',', $v);		
		$v = addslashes(stripslashes($v));
		DB::query("INSERT INTO ".DT_PRE."setting (item,item_key,item_value) VALUES ('$item','$k','$v')");
	}
	return true;
}

function get_setting($item) {
	$setting = array();
	$query = DB::query("SELECT * FROM ".DT_PRE."setting WHERE item='$item'");
	while($r = DB::fetch_array($query)) {
		$setting[$r['item_key']] = $r['item_value'];
	}
	return $setting;
}

function update_category($CAT) {
	global $DT;
	$linkurl = listurl($CAT);
	if($DT['index']) $linkurl = str_replace($DT['index'].'.'.$DT['file_ext'], '', $linkurl);
	DB::query("UPDATE ".DT_PRE."category SET linkurl='$linkurl' WHERE catid=".$CAT['catid']);
}

function tips($tips) {
	echo ' <img src="admin/image/tips.png" width="16" height="16" title="'.$tips.'" alt="tips" class="c_p" onclick="Dconfirm(this.title, \'\', 450);" />';
}

function array_save($array, $arrayname, $file) {
	$data = var_export($array,true);
	$data = "<?php\n".$arrayname." = ".$data.";\n?>";
	return file_put($file,$data);
}

function fetch_url($url) {
	$fetch = array();
	$tmp = parse_url($url);
	$domain = $tmp['host'];
	$r = DB::get_one("SELECT * FROM ".DT_PRE."fetch WHERE domain='$domain' ORDER BY edittime DESC");
	if($r) {
		$content = file_get($url);
		if($content) {
			$content = convert($content, $r['encode'], DT_CHARSET);
			preg_match("/<title>(.*)<\/title>/isU", $content, $m);
			if(isset($m[1])) $fetch['title'] = trim($r['title'] ? str_replace($r['title'], '', $m[1]) : $m[1]);
			preg_match("/<meta[\s]+name=['\"]description['\"] content=['\"](.*)['\"]/isU", $content, $m);
			if(isset($m[1])) $fetch['introduce'] = $m[1];
			list($f, $t) = explode('[content]', $r['content']);
			if($f && $t) {
				$s = strpos($content, $f);
				if($s !== false) {
					$e = strpos($content, $t, $s);
					if($e !== false && $e > $s) {
						$fetch['content'] = substr($content, $s + strlen($f), $e - $s - strlen($f));
					}
				}
			}
		}
	}
	return $fetch;
}

function admin_log($force = 0) {
	global $DT, $DT_QST, $moduleid, $file, $action, $_username;
	if($force) $DT['admin_log'] = 2;
	if(!$DT['admin_log'] || !$DT_QST || ($moduleid == 1 && $file == 'index')) return false;
	if($DT['admin_log'] == 2 || ($DT['admin_log'] == 1 && ($file == 'setting' || in_array($action, array('delete', 'edit', 'move', 'clear', 'add'))))) {
		if(strpos($DT_QST, 'file=log') !== false) return false;
		$fpos = strpos($DT_QST, '&forward');
		if($fpos) $DT_QST = substr($DT_QST, 0, $fpos);
		$logstring = get_cookie('logstring');
		$md5string = md5($DT_QST);
		if($md5string == $logstring)  return false;
		DB::query("INSERT INTO ".DT_PRE."admin_log(qstring,username,ip,logtime) VALUES('$DT_QST','$_username','".DT_IP."','".DT_TIME."')");
		set_cookie('logstring', $md5string);
	}
}

function admin_online() {
	global $DT, $DT_QST, $moduleid, $_username;
	if(!$DT['admin_online'] || !$_username) return false;
	$qstring = $DT_QST;
	$fpos = strpos($qstring, '&forward');
	if($fpos) $qstring = substr($qstring, 0, $fpos);
	$qstring = preg_replace("/rand=([0-9]{1,})\&/", "", $qstring);
	DB::query("REPLACE INTO ".DT_PRE."admin_online (sid,username,ip,moduleid,qstring,lasttime) VALUES ('".session_id()."','$_username','".DT_IP."','$moduleid','$qstring','".DT_TIME."')");	
	$lastime = DT_TIME - $DT['online'];
	DB::query("DELETE FROM ".DT_PRE."admin_online WHERE lasttime<$lastime");
}

function admin_check() {
	global $CFG, $_admin, $_userid, $moduleid, $file, $action, $catid, $_catids, $_childs;
	if(!check_name($file)) return false;
	if(in_array($file, array('logout', 'cloud', 'mymenu', 'search', 'ip', 'mobile'))) return true;//All user
	if($moduleid == 1 && $file == 'index') return true;
	if(is_founder($_userid)) return true;//Founder
	if($_admin == 2) {
		$R = cache_read('right-'.$_userid.'.php');
		if(!$R) return false;
		if(!isset($R[$moduleid])) return false;
		if(!$R[$moduleid]) return true;//Module admin
		if(!isset($R[$moduleid][$file])) return false;
		if(!$R[$moduleid][$file]) return true;
		if($action && $R[$moduleid][$file]['action'] && !in_array($action, $R[$moduleid][$file]['action'])) return false;
		if(!$R[$moduleid][$file]['catid']) return true;
		$_catids = implode(',', $R[$moduleid][$file]['catid']);
		if($catid) {
			if(in_array($catid, $R[$moduleid][$file]['catid'])) return true;
			//Childs
			$result = DB::query("SELECT catid,child,arrchildid FROM ".DT_PRE."category WHERE moduleid=$moduleid AND catid IN ($_catids)");
			while($r = DB::fetch_array($result)) {
				$_childs .= ','.($r['child'] ? $r['arrchildid'] : $r['catid']);
			}
			if(strpos($_childs.',', ','.$catid.',') !== false) return true;
			return false;
		}
	} else if($_admin == 1) {
		if(in_array($file, array('admin', 'setting', 'module', 'area', 'database', 'template', 'skin', 'log', 'update', 'group', 'fields', 'loginlog'))) return false;//Founder || Common Admin Only
	}
	return true;
}

function admin_notice() {
	global $DT, $MODULE, $moduleid, $file, $itemid, $action, $reason, $msg, $eml, $sms, $wec;
	if(!is_array($itemid)) return;
	if(count($itemid) == 0) return;
	$S = array(
		'delete' => '已经被删除', 
		'check' => '已经通过审核', 
		'reject' => '没有通过审核',
		'onsale' => '已经上架',
		'unsale' => '已经下架',
	);
	$N = array(
		'honor' => '荣誉资质', 
		'news' => '公司新闻', 
		'page' => '公司单页', 
		'link' => '友情链接',
	);
	if(!isset($S[$action])) return;
	if($moduleid > 4) {
		$table = get_table($moduleid);
		$name = $MODULE[$moduleid]['name'];
		if($MODULE[$moduleid]['module'] == 'job') {
			if($file == 'resume') {
				$table = DT_PRE.'job_'.$file.'_'.$moduleid;
				$name = '简历';
			} else {
				$name = '招聘';
			}
		} else if($MODULE[$moduleid]['module'] == 'mall') {
			$name = '商品';
		}
	} else if(isset($N[$file])) {
		$table = DT_PRE.$file;
		$name = $N[$file];
	} else {
		return;
	}
	if($reason == '操作原因') $reason = '';
	$msg = isset($msg) ? 1 : 0;
	if(strlen($reason) > 2) $msg = 1;
	$eml = isset($eml) ? 1 : 0;
	if($msg == 0 && $eml == 0) return;
	$sms = isset($sms) ? 1 : 0;
	$wec = isset($wec) ? 1 : 0;
	if($msg == 0) $sms = $wec = 0;
	$result = DB::query("SELECT itemid,title,username,linkurl FROM {$table} WHERE itemid IN (".implode(',', $itemid).")");
	while($r = DB::fetch_array($result)) {
		$username = $r['username'];
		if(!check_name($username)) continue;
		$title = $r['title'];
		$linkurl = strpos($r['linkurl'], '://') === false ? $MODULE[$moduleid]['linkurl'].$r['linkurl'] : $r['linkurl'];
		$subject = '您发布的['.$name.']'.$title.'(ID:'.$r['itemid'].')'.$S[$action];
		$body = '尊敬的会员：<br/>您发布的['.$name.']<a href="'.$linkurl.'" target="_blank">'.$title.'</a>(ID:'.$r['itemid'].')'.$S[$action].'！<br/>';
		if($reason) $body .= '操作原因：<br/>'.$reason.'<br/>';
		$body .= '如果您对此操作有异议，请及时与网站联系。';
		if($msg) send_message($username, $subject, $body);
		if($wec) send_weixin($username, $subject);
		if($eml || $sms) {
			$user = userinfo($username);
			if($eml) send_mail($user['email'], $subject, $body);
			if($sms) send_sms($user['mobile'], $subject.$DT['sms_sign']);
		}
	}
}

function item_check($itemid) {
	global $table, $_child, $moduleid;
	if($moduleid == 3) return true;
	$fd = 'itemid';
	if($moduleid == 2 || $moduleid == 4) $fd = 'userid';
	$r = DB::get_one("SELECT catid FROM {$table} WHERE `$fd`=$itemid");
	if($r && $_child && in_array($r['catid'], $_child)) return true;
	return false;
}

function city_check($itemid) {
	global $table, $_areaid, $moduleid;
	if($moduleid == 3) return true;
	$fd = 'itemid';
	if($moduleid == 2 || $moduleid == 4) $fd = 'userid';
	$r = DB::get_one("SELECT areaid FROM {$table} WHERE `$fd`=$itemid");
	if($r && $_areaid && in_array($r['areaid'], $_areaid)) return true;
	return false;
}

function split_content($moduleid, $part) {
	global $CFG, $MODULE;
	$table = DT_PRE.$moduleid.'_'.$part;
	$fd = $moduleid == 4 ? 'userid' : 'itemid';
	if(DB::version() > '4.1' && $CFG['db_charset']) {
		$type = " ENGINE=MyISAM DEFAULT CHARSET=".$CFG['db_charset'];
	} else {
		$type = " TYPE=MyISAM";
	}	
	DB::query("CREATE TABLE IF NOT EXISTS `{$table}` (`{$fd}` bigint(20) unsigned NOT NULL default '0',`content` longtext NOT NULL,PRIMARY KEY  (`{$fd}`))".$type." COMMENT='".$MODULE[$moduleid]['name']."内容_".$part."'");
}

function split_sell($part) {
	global $CFG, $MODULE;
	$sql = file_get(DT_ROOT.'/file/setting/split_sell.sql');
	$sql or dalert('请检查文件file/setting/split_sell.sql是否存在');
	$sql = str_replace('destoon_sell', DT_PRE.'sell_5_'.$part, $sql);
	if(DB::version() > '4.1' && $CFG['db_charset']) {
		$sql .= " ENGINE=MyISAM DEFAULT CHARSET=".$CFG['db_charset'];
	} else {
		$sql .= " TYPE=MyISAM";
	}
	$sql .= " COMMENT='".$MODULE[5]['name']."分表_".$part."';";
	DB::query($sql);
}

function seo_title($title, $show = '') {
	$SEO = array(
		'modulename'		=>	'模块名称',
		'page'				=>	'页码',
		'sitename'			=>	'网站名称',
		'sitetitle'			=>	'网站SEO标题',
		'sitekeywords'		=>	'网站SEO关键词',
		'sitedescription'	=>	'网站SEO描述',
		'catname'			=>	'分类名称',
		'cattitle'			=>	'分类SEO标题',
		'catkeywords'		=>	'分类SEO关键词',
		'catdescription'	=>	'分类SEO描述',
		'showtitle'			=>	'内容标题',
		'showintroduce'		=>	'内容简介',
		'kw'				=>	'关键词',
		'areaname'			=>	'地区',
		'delimiter'			=>	'分隔符',
	);
	if(is_array($show)) {
		foreach($show as $v) {
			if(isset($SEO[$v])) echo '<a href="javascript:_into(\''.$title.'\', \'{'.$SEO[$v].'}\');" title="{'.$SEO[$v].'}">{'.$SEO[$v].'}</a>&nbsp;&nbsp;';
		}
	} else {
		foreach($SEO as $k=>$v) {
			$title = str_replace('{'.$v.'}', '{$seo_'.$k.'}', $title);
		}
		return $title;
	}
}

function seo_check($str) {
	foreach(array('<', '>', ';', '?', '"', '()') as $v) {
		if(strpos($str, $v) !== false) return false;
	}
	if(preg_match_all("/\(([^\)]+)\)/i", $str, $matches)) {
		foreach($matches[1] as $m) {
			$m = trim($m);
			if(strlen($m) < 2) return false;			
			foreach(array('$', ',', "'") as $v) {
				if(strpos($m, $v) !== false) return false;
			}
		}
	}
	return true;
}

function install_file($moduleid, $file, $dir, $extend = 0) {
	$content = "<?php\n";
	if($extend == 1) $content .= "define('DT_REWRITE', true);\n";
	$content .= "\$moduleid = ".$moduleid.";\n";
	$content .= "require '../common.inc.php';\n";
	$content .= "require DT_ROOT.'/module/'.\$module.'/".$file.".inc.php';\n";
	$content .= '?>';
	file_put(DT_ROOT.'/'.$dir.'/'.$file.'.php', $content);
	$content = "<?php\n";
	if($extend == 1) $content .= "define('DT_REWRITE', true);\n";
	$content .= "\$moduleid = ".$moduleid.";\n";
	$content .= "require '../../common.inc.php';\n";
	$content .= "require DT_ROOT.'/include/mobile.inc.php';\n";
	$content .= "require DT_ROOT.'/module/'.\$module.'/".$file.".inc.php';\n";
	$content .= '?>';
	file_put(DT_ROOT.'/mobile/'.$dir.'/'.$file.'.php', $content);
}

function list_dir($dir) {
	include DT_ROOT.'/'.$dir.'/these.name.php';
	$list = $dirs = array();
	$files = glob(DT_ROOT.'/'.$dir.'/*');
	foreach($files as $v) {
		if(is_file($v)) continue;
		$v = basename($v);
		$dirs[$v] = $v;
	}
	foreach($names as $k=>$v) {
		if(isset($dirs[$k])) {
			$list[] = array('dir'=>$k, 'name'=>$v);
			unset($dirs[$k]);
		}
	}
	foreach($dirs as $v) {
		$list[] = array('dir'=>$v, 'name'=>$v);
	}
	return $list;
}

function pass_encode($str) {
	$len = strlen($str);
	if($len < 1) return '';
	$new = '';
	for($i = 0; $i < $len; $i++) {
		$new .= ($i == 0 || $i == $len - 1) ? $str{$i} : '*';
	}
	return $new;
}

function pass_decode($new, $old) {
	return $new == pass_encode($old) ? $old : $new;
}

function fix_domain($domain) {
	if(strpos($domain, '.') === false) return '';
	if(substr($domain, 0, 4) != 'http') $domain = 'http://'.$domain;
	if(substr($domain, -1) != '/') $domain = $domain.'/';
	return $domain;
}
?>