<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
function daddslashes($string) {
	return is_array($string) ? array_map('daddslashes', $string) : addslashes($string);
}

function dstripslashes($string) {
	return is_array($string) ? array_map('dstripslashes', $string) : stripslashes($string);
}

function dtrim($string) {
	return str_replace(array(chr(10), chr(13), "\t", ' '), array('', '', '', ''), $string);
}

function dwrite($string) {
	return str_replace(array(chr(10), chr(13), "'"), array('', '', "\'"), $string);
}

function dheader($url) {
	global $DT;	
	if(!defined('DT_ADMIN') && $DT['defend_reload']) sleep($DT['defend_reload']);
	exit(header('location:'.$url));
}

function dmsg($dmsg = '', $dforward = '') {
	if(!$dmsg && !$dforward) {
		$dmsg = get_cookie('dmsg');
		if($dmsg) {
			echo '<script type="text/javascript">showmsg(\''.$dmsg.'\');</script>';
			set_cookie('dmsg', '');
		}
	} else {
		set_cookie('dmsg', $dmsg);
		$dforward = preg_replace("/(.*)([&?]rand=[0-9]*)(.*)/i", "\\1\\3", $dforward);
		$dforward = str_replace('.php&', '.php?', $dforward);
		$dforward = strpos($dforward, '?') === false ? $dforward.'?rand='.mt_rand(10, 99) : str_replace('?', '?rand='.mt_rand(10, 99).'&', $dforward);
		dheader($dforward);
	}
}

function dalert($dmessage = errmsg, $dforward = '', $extend = '') {
	global $DT;
	exit(include template('alert', 'message'));
}

function dsubstr($string, $length, $suffix = '', $start = 0) {
	if($start) {
		$tmp = dsubstr($string, $start);
		$string = substr($string, strlen($tmp));
	}
	$strlen = strlen($string);
	if($strlen <= $length) return $string;
	$string = str_replace(array('&quot;', '&lt;', '&gt;'), array('"', '<', '>'), $string);
	$length = $length - strlen($suffix);
	$str = '';
	if(DT_CHARSET == 'UTF-8') {
		$n = $tn = $noc = 0;
		while($n < $strlen)	{
			$t = ord($string{$n});
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t <= 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}
			if($noc >= $length) break;
		}
		if($noc > $length) $n -= $tn;
		$str = substr($string, 0, $n);
	} else {
		for($i = 0; $i < $length; $i++) {
			$str .= ord($string{$i}) > 127 ? $string{$i}.$string{++$i} : $string{$i};
		}
	}
	$str = str_replace(array('"', '<', '>'), array('&quot;', '&lt;', '&gt;'), $str);
	return $str == $string ? $str : $str.$suffix;
}

function cutstr($str, $mark1, $mark2 = '') {
	$p1 = strpos($str, $mark1);
	if($p1 === false) return '';
	$str = substr($str, $p1 + strlen($mark1));
	if(!$mark2) return $str;
	$p2 = strpos($str, $mark2);
	if($p2 === false) return $str;
	return substr($str, 0, $p2);
}

function encrypt($txt, $key = '', $expiry = 0) {
	strlen($key) > 5 or $key = DT_KEY;
	$str = $txt.substr($key, 0, 3);
	return str_replace(array('=', '+', '/', '0x', '0X'), array('-E-', '-P-', '-S-', '-Z-', '-X-'), mycrypt($str, $key, 'ENCODE', $expiry));
}

function decrypt($txt, $key = '') {
	strlen($key) > 5 or $key = DT_KEY;
	$str = mycrypt(str_replace(array('-E-', '-P-', '-S-', '-Z-', '-X-'), array('=', '+', '/', '0x', '0X'), $txt), $key, 'DECODE');
	return substr($str, -3) == substr($key, 0, 3) ? substr($str, 0, -3) : '';
}

function mycrypt($string, $key, $operation = 'DECODE', $expiry = 0) {
	$ckey_length = 4;
	$key = md5($key);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);
	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + DT_TIME : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);
	$result = '';
	$box = range(0, 255);
	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}
	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}
	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - DT_TIME > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.base64_encode($result);
	}
}

function dround($var, $precision = 2, $sprinft = false) {
	$var = round(floatval($var), $precision);
	if($sprinft) $var = sprintf('%.'.$precision.'f', $var);
	return $var;
}

function dalloc($i, $n = 5000) {
	return ceil($i/$n);
}

function strip_nr($string, $js = false) {
	$string =  str_replace(array(chr(13), chr(10), "\n", "\r", "\t", '  '),array('', '', '', '', '', ''), $string);
	if($js) $string = str_replace("'", "\'", $string);
	return $string;
}

function template($template = 'index', $dir = '') {
	global $CFG, $DT_PC;
	check_name($template) or exit('BAD TPL NAME');
	if($dir) check_name($dir) or exit('BAD TPL DIR');
	$tpl = $DT_PC ? $CFG['template'] : $CFG['template_mobile'];
	$to = DT_CACHE.'/tpl/'.$tpl.'/'.($dir ? $dir.'/' : '').$template.'.php';
	$isfileto = is_file($to);
	if($CFG['template_refresh'] || !$isfileto) {
		if($dir) $dir = $dir.'/';
        $from = DT_ROOT.'/template/'.$tpl.'/'.$dir.$template.'.htm';
		if(!is_file($from)) $from = DT_ROOT.'/template/'.($DT_PC ? 'default' : 'mobile').'/'.$dir.$template.'.htm';
        if(!$isfileto || filemtime($from) > filemtime($to) || (filesize($to) == 0 && filesize($from) > 0)) {
			require_once DT_ROOT.'/include/template.func.php';
			template_compile($from, $to);
		}
	}
	return $to;
}

function ob_template($template, $dir = '') {
	extract($GLOBALS, EXTR_SKIP);
	ob_start();
	include template($template, $dir);
	$contents = ob_get_contents();
	ob_clean();
	return $contents;
}

function message($dmessage = errmsg, $dforward = 'goback', $dtime = 1) {
	if(!$dmessage && $dforward && $dforward != 'goback') dheader($dforward);
	global $DT, $DT_PC;
	exit(include template('message', 'message'));
}

function login() {
	global $_userid, $MODULE, $DT_URL, $DT_PC, $DT;
	$_userid or dheader(($DT_PC ? $MODULE[2]['linkurl'] : $MODULE[2]['mobile']).$DT['file_login'].'?forward='.rawurlencode($DT_URL));
}

function random($length, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz') {
	$hash = '';
	$max = strlen($chars) - 1;
	for($i = 0; $i < $length; $i++)	{
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}

function set_cookie($var, $value = '', $time = 0) {
	global $CFG;
	$time = $time > 0 ? $time : (empty($value) ? DT_TIME - 3600 : 0);
	$port = $_SERVER['SERVER_PORT'] == '443' ? 1 : 0;
	$var = $CFG['cookie_pre'].$var;
	return setcookie($var, $value, $time, $CFG['cookie_path'], $CFG['cookie_domain'], $port);
}

function get_cookie($var) {
	global $CFG;
	$var = $CFG['cookie_pre'].$var;
	return isset($_COOKIE[$var]) ? $_COOKIE[$var] : '';
}

function get_table($moduleid, $data = 0) {
	global $MODULE;
	$module = $MODULE[$moduleid]['module'];
	$M = array('company', 'member');
	if($data) {
		return in_array($module, $M) ? DT_PRE.$module.'_data' : DT_PRE.$module.'_data_'.$moduleid;
	} else {
		return in_array($module, $M) ? DT_PRE.$module : DT_PRE.$module.'_'.$moduleid;
	}
}

function get_process($fromtime, $totime) {
	if($fromtime && DT_TIME < $fromtime) return 1;
	if($totime && DT_TIME > $totime) return 3;
	return 2;
}

function send_message($touser, $title, $content, $typeid = 4, $fromuser = '') {
	if($touser == $fromuser) return false;
	if(check_name($touser) && $title && $content) {
		$title = addslashes($title);
		$content = addslashes($content);
		$r = DB::get_one("SELECT black FROM ".DT_PRE."member_misc WHERE username='$touser'");
		if($r) {
			if($r['black'] && $typeid != 4) {
				$blacks = explode(' ', $r['black']);
				$_from = $fromuser ? $fromuser : 'Guest';
				if(in_array($_from, $blacks)) return false;
			}
			DB::query("INSERT INTO ".DT_PRE."message (title,typeid,touser,fromuser,content,addtime,ip,status) VALUES ('$title', $typeid, '$touser','$fromuser','$content','".DT_TIME."','".DT_IP."',3)");			
			DB::query("UPDATE ".DT_PRE."member SET message=message+1 WHERE username='$touser'");
			if($fromuser) {
				DB::query("INSERT INTO ".DT_PRE."message (title,typeid,content,fromuser,touser,addtime,ip,status) VALUES ('$title','$typeid','$content','$fromuser','$touser','".DT_TIME."','".DT_IP."','2')");
			}
			return true;
		}
	}
	return false;
}

function send_mail($mail_to, $mail_subject, $mail_body, $mail_from = '', $mail_sign = true) {
	global $DT;
	require_once DT_ROOT.'/include/mail.func.php';
	$result = dmail(trim($mail_to), $mail_subject, $mail_body, $mail_from, $mail_sign);
	$success = $result == 'SUCCESS' ? 1 : 0;
	if($DT['mail_log']) {
		$status = $success ? 3 : 2;
		$note = $success ? '' : addslashes($result);
		$mail_subject = stripslashes($mail_subject);
		$mail_body = stripslashes($mail_body);
		$mail_subject = addslashes($mail_subject);
		$mail_body = addslashes($mail_body);
		DB::query("INSERT INTO ".DT_PRE."mail_log (email,title,content,addtime,status,note) VALUES ('$mail_to','$mail_subject','$mail_body','".DT_TIME."','$status','$note')");
	}
	return $success;
}

function strip_sms($message) {
	global $DT;
	$message = strip_tags($message);
	$message = trim($message);
	$message = preg_replace("/&([a-z]{1,});/", '', $message);
	if($DT['sms_sign']) $message .= $DT['sms_sign'];
	return $message;
}

function send_sms($mobile, $message, $word = 0, $time = 0) {
	global $DT, $_username;
	if(!$DT['sms'] || !DT_CLOUD_UID || !DT_CLOUD_KEY || !is_mobile($mobile) || strlen($message) < 5) return false;
	$word or $word = word_count($message);
	$sms_message = $message;
	$data = 'sms_uid='.DT_CLOUD_UID.'&sms_key='.md5(DT_CLOUD_KEY.'|'.$mobile.'|'.md5($sms_message)).'&sms_charset='.DT_CHARSET.'&sms_mobile='.$mobile.'&sms_message='.rawurlencode($sms_message).'&sms_time='.$time.'&sms_url='.rawurlencode(DT_PATH);
	$code = dcurl('http://sms.destoon.com/send.php', $data);
	if($code && strpos($code, 'destoon_sms_code=') !== false) {
		$code = explode('destoon_sms_code=', $code);
		$code = $code[1];
	} else {
		$code = 'Can Not Connect SMS Server';
	}
	DB::query("INSERT INTO ".DT_PRE."sms (mobile,message,word,editor,sendtime,ip,code) VALUES ('$mobile','$message','$word','$_username','".DT_TIME."','".DT_IP."','$code')");
	return $code;
}

function send_weixin($touser, $word) {
	if(check_name($touser) && strlen($word) > 1) {
		$user = DB::get_one("SELECT openid,push,visittime FROM ".DT_PRE."weixin_user WHERE username='$touser'");
		if($user && $user['openid'] && $user['push'] && DT_TIME - $user['visittime'] < 172800) {
			$openid = $user['openid'];
			$type = 'text';
			require_once DT_ROOT.'/api/weixin/init.inc.php';
			if(!is_object($wx)) {
				$wx = new weixin;
				$wx->access_token = $wx->get_token();
			}
			$arr = $wx->send($openid, $type, $word);
			if($arr['errcode'] != 0) return false;
			$post = array();
			$post['content'] = $word;
			$post['type'] = 'push';
			$post['openid'] = $openid;
			$post['editor'] = 'system';
			$post['addtime'] = DT_TIME;
			$post['misc'] = '';
			$post = daddslashes($post);
			$sql = '';
			foreach($post as $k=>$v) {
				$sql .= ",$k='$v'";
			}
			DB::query("INSERT INTO ".DT_PRE."weixin_chat SET ".substr($sql, 1));
			return true;
		}
	}
	return false;
}

function word_count($string) {
	if(function_exists('mb_strlen')) return mb_strlen($string, DT_CHARSET);
	$string = convert($string, DT_CHARSET, 'gbk');
	$length = strlen($string);
	$count = 0;
	for($i = 0; $i < $length; $i++) {
		$t = ord($string[$i]);
		if($t > 127) $i++;
		$count++;
	}
	return $count;
}

function cache_read($file, $dir = '', $mode = '') {
	$file = $dir ? DT_CACHE.'/'.$dir.'/'.$file : DT_CACHE.'/'.$file;
	if(!is_file($file)) return $mode ? '' : array();
	return $mode ? file_get($file) : include $file;
}

function cache_write($file, $string, $dir = '') {
	if(is_array($string)) $string = "<?php defined('IN_DESTOON') or exit('Access Denied'); return ".strip_nr(var_export($string, true))."; ?>";
	$file = $dir ? DT_CACHE.'/'.$dir.'/'.$file : DT_CACHE.'/'.$file;
	$strlen = file_put($file, $string);
	return $strlen;
}

function cache_delete($file, $dir = '') {
	$file = $dir ? DT_CACHE.'/'.$dir.'/'.$file : DT_CACHE.'/'.$file;
	return file_del($file);
}

function cache_clear($str, $type = '', $dir = '') {
	$dir = $dir ? DT_CACHE.'/'.$dir.'/' : DT_CACHE.'/';
	$files = glob($dir.'*');
	if(is_array($files)) {
		if($type == 'dir') {
			foreach($files as $file) {
				if(is_dir($file)) {dir_delete($file);} else {if(file_ext($file) == $str) file_del($file);}
			}
		} else {
			foreach($files as $file) {
				if(!is_dir($file) && strpos(basename($file), $str) !== false) file_del($file);
			}
		}
	}
}

function content_table($moduleid, $itemid, $split, $table_data = '') {
	if($split) {
		return split_table($moduleid, $itemid);
	} else {
		$table_data or $table_data = get_table($moduleid, 1);
		return $table_data;
	}
}

function split_table($moduleid, $itemid) {
	$part = split_id($itemid);
	return DT_PRE.$moduleid.'_'.$part;
}

function split_id($id) {
	return $id > 0 ? ceil($id/100000) : 1;
}

function ip2area($ip) {
	$area = '';
	if(is_ip($ip)) {
		$tmp = explode('.', $ip);
		if($tmp[0] == 10 || $tmp[0] == 127 || ($tmp[0] == 192 && $tmp[1] == 168) || ($tmp[0] == 172 && ($tmp[1] >= 16 && $tmp[1] <= 31))) {
			$area = 'LAN';
		} elseif($tmp[0] > 255 || $tmp[1] > 255 || $tmp[2] > 255 || $tmp[3] > 255) {
			$area = 'Unknown';
		} else {
			require_once DT_ROOT.'/include/ip.class.php';
			$do = new ip($ip);
			$area = $do->area();
		}
	}
	return $area ? $area : 'Unknown';
}

function banip($IP) {
	$ban = false;
	foreach($IP as $v) {
		if($v['totime'] && $v['totime'] < DT_TIME) continue;
		if($v['ip'] == DT_IP) { $ban = true; break; }
		if(preg_match("/^".str_replace('*', '[0-9]{1,3}', $v['ip'])."$/", DT_IP)) { $ban = true; break; }
	}
	if($ban) message(lang('include->msg_ip_ban', array(DT_IP)));
}

function banword($WORD, $string, $extend = true) {
	$string = stripslashes($string);
	foreach($WORD as $v) {
		$v[0] = preg_quote($v[0]);
		$v[0] = str_replace('/', '\/', $v[0]);
		$v[0] = str_replace("\*", ".*", $v[0]);
		if($v[2] && $extend) {
			if(preg_match("/".$v[0]."/i", $string)) dalert(lang('include->msg_word_ban'));
		} else {
			if($string == '') break;
			if(preg_match("/".$v[0]."/i", $string)) $string = preg_replace("/".$v[0]."/i", $v[1], $string);
		}
	}
	return addslashes($string);
}

function get_env($type) {
	switch($type) {
		case 'ip':
			if(DT_CDN) {
				if(isset($_SERVER['X-REAL-IP']) && is_ip($_SERVER['X-REAL-IP'])) return $_SERVER['X-REAL-IP'];
				if(isset($_SERVER['HTTP_CF_CONNECTING_IP']) && is_ip($_SERVER['HTTP_CF_CONNECTING_IP'])) return $_SERVER['HTTP_CF_CONNECTING_IP'];
			}
			if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				if(is_ip($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
				$ip = trim(end(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])));
				if(is_ip($ip)) return $ip;
			}
			if(isset($_SERVER['REMOTE_ADDR']) && is_ip($_SERVER['REMOTE_ADDR'])) return $_SERVER['REMOTE_ADDR'];
			if(isset($_SERVER['HTTP_CLIENT_IP']) && is_ip($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
			return '0.0.0.0';
		break;
		case 'self':
			return isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : (isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : $_SERVER['ORIG_PATH_INFO']);
		break;
		case 'referer':
			return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		break;
		case 'domain':
			return $_SERVER['SERVER_NAME'];
		break;
		case 'scheme':
			return $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
		break;
		case 'port':
			return ($_SERVER['SERVER_PORT'] == '80' || $_SERVER['SERVER_PORT'] == '443') ? '' : ':'.$_SERVER['SERVER_PORT'];
		break;
		case 'host':
			return preg_match("/^[a-z0-9_\-\.]{4,}$/i", $_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
		break;
		case 'url':
			if(isset($_SERVER['HTTP_X_REWRITE_URL']) && $_SERVER['HTTP_X_REWRITE_URL']) {
				$uri = $_SERVER['HTTP_X_REWRITE_URL'];
			} else if(isset($_SERVER['HTTP_X_ORIGINAL_URL']) && $_SERVER['HTTP_X_ORIGINAL_URL']) {
				$uri = $_SERVER['HTTP_X_ORIGINAL_URL'];
			} else if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI']) {
				$uri = $_SERVER['REQUEST_URI'];
			} else {
				$uri = $_SERVER['PHP_SELF'];
				if(isset($_SERVER['argv'])) {
					if(isset($_SERVER['argv'][0])) $uri .= '?'.$_SERVER['argv'][0];
				} else {
					$uri .= '?'.$_SERVER['QUERY_STRING'];
				}
			}
			$uri = dhtmlspecialchars($uri);
			if(strpos($uri, '.php?') !== false && strpos($uri, '.html') !== false) $uri = str_replace('.php?', '-htm-', $uri);
			return get_env('scheme').$_SERVER['HTTP_HOST'].(strpos($_SERVER['HTTP_HOST'], ':') === false ? get_env('port') : '').$uri;
		break;
		case 'mobile':
			$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
			$ck = get_cookie('mobile');
			$os = $browser = '';
			if(strpos($ua, 'android') !== false) {
				$os = 'android';
				if($ck == 'app') {
					$browser = 'app';
				} else if($ck == 'b2b') {
					$browser = 'b2b';
				} else {
					if(strpos($ua, 'micromessenger/') !== false) {
						$browser = 'weixin';
					} else if(strpos($ua, 'qq/') !== false) {
						$browser = 'qq';
					}
				}
			} else if(strpos($ua, 'iphone') !== false || strpos($ua, 'ipod') !== false) {
				$os = 'ios';
				if($ck == 'app') {
					$browser = 'app';
				} else if($ck == 'b2b') {
					$browser = 'b2b';
				} else if($ck == 'screen') {
					$browser = 'screen';
				} else {
					if(strpos($ua, 'micromessenger/') !== false) {
						$browser = 'weixin';
					} else if(strpos($ua, 'qq/') !== false) {
						$browser = 'qq';
					} else if(strpos($ua, 'safari') !== false) {
						$browser = 'safari';
					}
				}
			} else if(strpos($ua, 'adr') !== false && strpos($ua, 'ucbrowser') !== false) {
				$os = 'android';
				$browser = 'uc';
			}
			return array('os' => $os, 'browser' => $browser);
		break;
	}
}

function convert($str, $from = 'utf-8', $to = 'gb2312') {
	if(!$str) return '';
	$from = strtolower($from);
	$to = strtolower($to);
	if($from == $to) return $str;
	$from = str_replace('gbk', 'gb2312', $from);
	$to = str_replace('gbk', 'gb2312', $to);
	$from = str_replace('utf8', 'utf-8', $from);
	$to = str_replace('utf8', 'utf-8', $to);
	if($from == $to) return $str;
	$tmp = array();
	if(function_exists('mb_convert_encoding')) {
		if(is_array($str)) {
			foreach($str as $key => $val) {
				$tmp[$key] = mb_convert_encoding($val, $to, $from);
			}
			return $tmp;
		} else {
			return mb_convert_encoding($str, $to, $from);
		}
	} else if(function_exists('iconv')) {
		if(is_array($str)) {
			foreach($str as $key => $val) {
				$tmp[$key] = iconv($from, $to."//IGNORE", $val);
			}
			return $tmp;
		} else {
			return iconv($from, $to."//IGNORE", $str);
		}
	} else {
		require_once DT_ROOT.'/include/convert.func.php';
		return dconvert($str, $from, $to);
	}
}

function get_type($item, $cache = 0) {
	$types = array();
	if($cache) {
		$types = cache_read('type-'.$item.'.php');
	} else {
		$result = DB::query("SELECT * FROM ".DT_PRE."type WHERE item='$item' ORDER BY listorder ASC,typeid DESC ");
		while($r = DB::fetch_array($result)) {
			$types[$r['typeid']] = $r;
		}
	}
	return $types;
}

function get_cat($catid) {
	if(!is_numeric($catid)) return array();
	$catid = intval($catid);
	return $catid ? DB::get_one("SELECT * FROM ".DT_PRE."category WHERE catid=$catid") : array();
}

function cat_pos($CAT, $str = ' &raquo; ', $target = '', $deep = 0, $start = 0) {
	global $MODULE;
	if(!$CAT) return '';
	$arrparentids = $CAT['arrparentid'].','.$CAT['catid'];
	$arrparentid = explode(',', $arrparentids);
	$pos = '';
	$target = $target ? ' target="_blank"' : '';	
	$CATEGORY = array();
	$result = DB::query("SELECT catid,moduleid,catname,linkurl FROM ".DT_PRE."category WHERE catid IN ($arrparentids)", 'CACHE');
	while($r = DB::fetch_array($result)) {
		$CATEGORY[$r['catid']] = $r;
	}
	if($deep) $i = 1;
	$j = 0;
	foreach($arrparentid as $catid) {
		if(!$catid || !isset($CATEGORY[$catid])) continue;
		if($j++ < $start) continue;
		if($deep) {
			if($i > $deep) continue;
			$i++;
		}
		$pos .= '<a href="'.$MODULE[$CATEGORY[$catid]['moduleid']]['linkurl'].$CATEGORY[$catid]['linkurl'].'"'.$target.'>'.$CATEGORY[$catid]['catname'].'</a>'.$str;
	}
	$_len = strlen($str);
	if($str && substr($pos, -$_len, $_len) === $str) $pos = substr($pos, 0, strlen($pos) - $_len);
	return $pos;
}

function cat_url($catid) {
	global $MODULE;
	$catid = intval($catid);
	$r = DB::get_one("SELECT moduleid,linkurl FROM ".DT_PRE."category WHERE catid=$catid");
	return $r ? $MODULE[$r['moduleid']]['linkurl'].$r['linkurl'] : '';
}

function get_area($areaid) {
	if(!is_numeric($areaid)) return array();
	$areaid = intval($areaid);
	return $areaid ? DB::get_one("SELECT * FROM ".DT_PRE."area WHERE areaid=$areaid") : array();
}

function area_pos($areaid, $str = ' &raquo; ', $deep = 0, $start = 0) {
	$areaid = intval($areaid);
	if($areaid) {
		global $AREA;
	} else {
		global $L;
		return $L['allcity'];
	}
	$AREA or $AREA = cache_read('area.php');
	$arrparentid = $AREA[$areaid]['arrparentid'] ? explode(',', $AREA[$areaid]['arrparentid']) : array();
	$arrparentid[] = $areaid;
	$pos = '';
	if($deep) $i = 1;
	$j = 0;
	foreach($arrparentid as $areaid) {
		if(!$areaid || !isset($AREA[$areaid])) continue;
		if($j++ < $start) continue;
		if($deep) {
			if($i > $deep) continue;
			$i++;
		}
		$pos .= $AREA[$areaid]['areaname'].$str;
	}
	$_len = strlen($str);
	if($str && substr($pos, -$_len, $_len) === $str) $pos = substr($pos, 0, strlen($pos)-$_len);
	return $pos;
}

function get_maincat($catid, $moduleid, $level = -1) {
	$catid = intval($catid);
	$condition = $catid ? "parentid=$catid" : "moduleid=$moduleid AND parentid=0";
	if($level >= 0) $condition .= " AND level=$level";
	$cat = array();
	$result = DB::query("SELECT catid,catname,child,style,linkurl,item FROM ".DT_PRE."category WHERE $condition ORDER BY listorder,catid ASC", 'CACHE');
	while($r = DB::fetch_array($result)) {
		$cat[] = $r;
	}
	return $cat;
}

function get_mainarea($areaid) {
	$areaid = intval($areaid);
	$are = array();
	$result = DB::query("SELECT areaid,areaname FROM ".DT_PRE."area WHERE parentid=$areaid ORDER BY listorder,areaid ASC", 'CACHE');
	while($r = DB::fetch_array($result)) {
		$are[] = $r;
	}
	return $are;
}

function get_user($value, $key = 'username', $from = 'userid') {
	$r = DB::get_one("SELECT `$from` FROM ".DT_PRE."member WHERE `$key`='$value'");
	return $r[$from];
}

function check_group($groupid, $groupids) {
	if(!$groupids || $groupid == 1) return true;
	if($groupid == 4) $groupid = 3;
	return in_array($groupid, explode(',', $groupids));
}

function tohtml($htmlfile, $module = '', $parameter = '') {
	defined('TOHTML') or define('TOHTML', true);
    extract($GLOBALS, EXTR_SKIP);
	if($parameter) parse_str($parameter);
    include $module ? DT_ROOT.'/module/'.$module.'/'.$htmlfile.'.htm.php' : DT_ROOT.'/include/'.$htmlfile.'.htm.php';
}

function set_style($string, $style = '', $tag = 'span') {
	if(preg_match("/^#[0-9a-zA-Z]{6}$/", $style)) $style = 'color:'.$style;
	return $style ? '<'.$tag.' style="'.$style.'">'.$string.'</'.$tag.'>' : $string;
}

function crypt_action($action) {
	return md5(md5($action.DT_KEY.DT_IP));
}

function captcha($captcha, $enable = 1, $return = false) {
	global $DT, $session;
	if($enable) {
		if($DT['captcha_cn']) {
			if(strlen($captcha) < 4) {
				$msg = lang('include->captcha_missed');
				return $return ? $msg : message($msg);
			}
		} else {
			if(!preg_match("/^[0-9a-z]{4,}$/i", $captcha)) {
				$msg = lang('include->captcha_missed');
				return $return ? $msg : message($msg);
			}
		}
		if(!is_object($session)) $session = new dsession();
		if(!isset($_SESSION['captchastr'])) {
			$msg = lang('include->captcha_expired');
			return $return ? $msg : message($msg);
		}
		if(decrypt($_SESSION['captchastr'], DT_KEY.'CPC') != strtoupper($captcha)) {
			$msg = lang('include->captcha_error');
			return $return ? $msg : message($msg);
		}
		unset($_SESSION['captchastr']);
	} else {
		return '';
	}
}

function question($answer, $enable = 1, $return = false) {
	global $session;
	if($enable) {
		if(!$answer) {
			$msg = lang('include->answer_missed');
			return $return ? $msg : message($msg);
		}
		$answer = stripslashes($answer);
		if(!is_object($session)) $session = new dsession();
		if(!isset($_SESSION['answerstr'])) {
			$msg = lang('include->question_expired');
			return $return ? $msg : message($msg);
		}
		if(decrypt($_SESSION['answerstr'], DT_KEY.'ANS') != $answer) {
			$msg = lang('include->answer_error');
			return $return ? $msg : message($msg);
		}
		unset($_SESSION['answerstr']);
	} else {
		return '';
	}
}

function pages($total, $page = 1, $perpage = 20, $demo = '', $step = 3) {
	global $DT_URL, $DT, $L;
	if($total <= $perpage) return '';
	$items = $total;
	$total = ceil($total/$perpage);
	if($page < 1 || $page > $total) $page = 1;
	if($demo) {
		$demo_url = str_replace('%7Bdestoon_page%7D', '{destoon_page}', $demo);
		$home_url = str_replace('{destoon_page}', '1', $demo_url);
	} else {
		if(defined('DT_REWRITE') && $DT['rewrite'] && $_SERVER["SCRIPT_NAME"] && strpos($DT_URL, '?') === false) {
			$demo_url = $_SERVER["SCRIPT_NAME"];
			$demo_url = str_replace('//', '/', $demo_url);//Fix Nginx
			$mark = false;
			if(substr($demo_url, -4) == '.php') {
				if(strpos($_SERVER['QUERY_STRING'], '.html') === false) {
					$qstr = '';
					if($_SERVER['QUERY_STRING']) {					
						if(substr($_SERVER['QUERY_STRING'], -5) == '.html') {
							$qstr = '-'.substr($_SERVER['QUERY_STRING'], 0, -5);
						} else {
							parse_str($_SERVER['QUERY_STRING'], $qs);
							foreach($qs as $k=>$v) {
								$qstr .= '-'.$k.'-'.rawurlencode($v);
							}
						}
					}
					$demo_url = substr($demo_url, 0, -4).'-htm-page-{destoon_page}'.$qstr.'.html';
				} else {
					$demo_url = substr($demo_url, 0, -4).'-htm-'.$_SERVER['QUERY_STRING'];
					$mark = true;
				}
			} else {
				$mark = true;
			}
			if($mark) {
				if(strpos($demo_url, '%') === false) $demo_url =  rawurlencode($demo_url);
				$demo_url = str_replace(array('%2F', '%3A'), array('/', ':'), $demo_url);
				if(strpos($demo_url, '-page-') !== false) {
					$demo_url = preg_replace("/page-([0-9]+)/", 'page-{destoon_page}', $demo_url);
				} else {
					$demo_url = str_replace('.html', '-page-{destoon_page}.html', $demo_url);
				}
			}
			$home_url = str_replace('-page-{destoon_page}', '-page-1', $demo_url);
		} else {
			$DT_URL = str_replace('&amp;', '&', $DT_URL);
			$demo_url = $home_url = preg_replace("/(.*)([&?]page=[0-9]*)(.*)/i", "\\1\\3", $DT_URL);
			$s = strpos($demo_url, '?') === false ? '?' : '&';
			$demo_url = $demo_url.$s.'page={des'.'toon_page}';
			if(defined('DT_ADMIN') && strpos($demo_url, 'sum=') === false) $demo_url = str_replace('page=', 'sum='.$items.'&page=', $demo_url);
		}
	}
	$pages = '';
	include DT_ROOT.'/api/pages.'.((!$DT['pages_mode'] && $page < 100) ? 'default' : 'sample').'.php';
	return $pages;
}

function listpages($CAT, $total, $page = 1, $perpage = 20, $step = 2) {
	global $DT, $MOD, $L;
	if($total <= $perpage) return '';
	$items = $total;
	$total = ceil($total/$perpage);
	if($page < 1 || $page > $total) $page = 1;
	$home_url = $MOD['linkurl'].$CAT['linkurl'];
	$demo_url = $MOD['linkurl'].listurl($CAT, '{destoon_page}');
	$pages = '';
	include DT_ROOT.'/api/pages.'.((!$DT['pages_mode'] && $page < 100) ? 'default' : 'sample').'.php';
	return $pages;
}

function linkurl($linkurl) {
	return strpos($linkurl, '://') === false ? DT_PATH.$linkurl : $linkurl;
}

function imgurl($url = '', $width = '') {
	if($url) {
		return strpos($url, '://') === false ? DT_PATH.'file/upload/'.$url : $url;
	} else {
		return DT_SKIN.'image/nopic'.$width.'.gif';
	}
}

function userurl($username, $qstring = '', $domain = '') {
	global $CFG, $DT, $MODULE;
	$URL = '';
	$subdomain = 0;
	if($CFG['com_domain']) $subdomain = substr($CFG['com_domain'], 0, 1) == '.' ? 1 : 2;
	if($username) {
		if($subdomain || $domain) {
			$scheme = $DT['com_https'] ? 'https://' : 'http://';
			$URL = $domain ? $scheme.$domain.'/' : ($subdomain == 1 ? $scheme.($DT['com_www'] ? 'www.' : '').$username.$CFG['com_domain'].'/' : $scheme.$CFG['com_domain'].'/'.$username.'/');
			if($qstring) {
				parse_str($qstring, $q);
				if(isset($q['file'])) {
					$URL .= $CFG['com_dir'] ? $q['file'].'/' : 'company/'.$q['file'].'/';
					unset($q['file']);
				}
				if($q) {
					if($DT['rewrite']) {
						foreach($q as $k=>$v) {
							$v = rawurlencode($v);
							$URL .= $k.'-'.$v.'-';
						}
						$URL = substr($URL, 0, -1).'.shtml';
					} else {
						$URL .= 'index.php?';
						$i = 0;
						foreach($q as $k=>$v) {
							$v = rawurlencode($v);
							$URL .= ($i++ == 0 ? '' : '&').$k.'='.$v;
						}
					}
				}
			}
		} else if($DT['rewrite']) {
			$URL = DT_PATH.'com/'.$username.'/';
			if($qstring) {
				parse_str($qstring, $q);
				if(isset($q['file'])) {
					$URL .= $CFG['com_dir'] ? $q['file'].'/' : 'company/'.$q['file'].'/';
					unset($q['file']);
				}
				if($q) {
					foreach($q as $k=>$v) {
						$v = rawurlencode($v);
						$URL .= $k.'-'.$v.'-';
					}
					$URL = substr($URL, 0, -1).'.html';
				}
			}
		} else {
			$URL = DT_PATH.'index.php?homepage='.$username;
			if($qstring) $URL = $URL.'&'.$qstring;
		}
	} else {
		$URL = $MODULE[4]['linkurl'].'guest.php';
	}
	return $URL;
}

function useravatar($var, $size = '', $isusername = 1, $real = 0) {
	in_array($size, array('large', 'small')) or $size = 'middle';
	if($real) {
		$ext = 'x48.jpg';
		if($size == 'large') $ext = '.jpg';
		if($size == 'small') $ext = 'x20.jpg';
		$file = DT_ROOT.'/api/avatar/default'.$ext;
		$md5 = md5($var);
		if($isusername) {
			$img = DT_ROOT.'/file/avatar/'.substr($md5, 0, 2).'/'.substr($md5, 2, 2).'/_'.$var.$ext;
			if(is_file($img) && check_name($var)) $file = $img;
		} else {
			$img = DT_ROOT.'/file/avatar/'.substr($md5, 0, 2).'/'.substr($md5, 2, 2).'/'.$var.$ext;
			if(is_file($img)) $file = $img;
		}
		if($real == 1) {
			$url = str_replace(DT_ROOT.'/', DT_PATH, $file);
			if(strpos($url, '/default') === false) {
				$remote = file_get(DT_ROOT.'/file/avatar/remote.html');
				if(strlen($remote) > 10) $url = str_replace(DT_ROOT.'/file/', $remote, $file);
			}
			return $url;
		}
		return strpos($file, '/api/') === false ? $file : '';
	} else {
		$name = $isusername ? 'username' : 'userid';
		return DT_PATH.'api/avatar/show.php?'.$name.'='.$var.'&size='.$size;
	}
}

function userinfo($username, $cache = 1) {
	global $dc, $CFG;
	if(!check_name($username)) return array();
	$user = array();
	if($cache && $CFG['db_expires']) {
		$user = $dc->get('user-'.$username);
		if($user) return $user;
	}
	$r1 = DB::get_one("SELECT * FROM ".DT_PRE."member WHERE username='$username'");
	if($r1) {
		$r2 = DB::get_one("SELECT * FROM ".DT_PRE."member_misc WHERE username='$username'");
		$r3 = DB::get_one("SELECT * FROM ".DT_PRE."company WHERE username='$username'");
		$user = array_merge($r1, $r2, $r3);
	}
	if($cache && $CFG['db_expires'] && $user) $dc->set('user-'.$username, $user, $CFG['db_expires']);
	return $user;
}

function userclean($username) {
	global $dc, $CFG;
	$user = array();
	if($CFG['db_expires']) $dc->rm('user-'.$username);
}

function listurl($CAT, $page = 0) {
	global $DT, $MOD, $L;
	include DT_ROOT.'/api/url.inc.php';
	$catid = $CAT['catid'];
	$file_ext = $DT['file_ext'];
	$index = $DT['index'];
	$catdir = $CAT['catdir'];
	$catname = file_vname($CAT['catname']);
	$prefix = $MOD['htm_list_prefix'];
	$urlid = $MOD['list_html'] ? $MOD['htm_list_urlid'] : $MOD['php_list_urlid'];
	$ext = $MOD['list_html'] ? 'htm' : 'php';
	isset($urls[$ext]['list'][$urlid]) or $urlid = 0;
	$url = $urls[$ext]['list'][$urlid];
	$url = $page ? $url['page'] : $url['index'];
    eval("\$listurl = \"$url\";");
	if(substr($listurl, 0, 1) == '/') $listurl = substr($listurl, 1);
	return $listurl;
}

function itemurl($item, $page = 0) {
	global $DT, $MOD, $L;
	if(isset($item['islink']) && $item['islink']) return $item['linkurl'];
	if($MOD['show_html'] && $item['filepath']) {
		if($page === 0) return $item['filepath'];
		$ext = file_ext($item['filepath']);
		return str_replace('.'.$ext, '_'.$page.'.'.$ext, $item['filepath']);
	}
	include DT_ROOT.'/api/url.inc.php';
	$file_ext = $DT['file_ext'];
	$index = $DT['index'];
	$itemid = $item['itemid'];
	$title = file_vname($item['title']);
	$addtime = $item['addtime'];
	$catid = $item['catid'];
	$year = date('Y', $addtime);
	$month = date('m', $addtime);
	$day = date('d', $addtime);
	$prefix = $MOD['htm_item_prefix'];
	$urlid = $MOD['show_html'] ? $MOD['htm_item_urlid'] : $MOD['php_item_urlid'];
	$ext = $MOD['show_html'] ? 'htm' : 'php';
	$alloc = dalloc($itemid);
	$url = $urls[$ext]['item'][$urlid];
	$url = $page ? $url['page'] : $url['index'];
	if(strpos($url, 'cat') !== false && $catid) {
		if(isset($item['gid'])) {
			$catid = $item['gid'];
			$cate = get_group($catid);
			$catdir = $cate['filepath'];
			$catname = $cate['title'];
		} else {
			$cate = get_cat($catid);
			$catdir = $cate['catdir'];
			$catname = $cate['catname'];
		}
	}
    eval("\$itemurl = \"$url\";");
	if(substr($itemurl, 0, 1) == '/') $itemurl = substr($itemurl, 1);
	return $itemurl;
}

function rewrite($url, $encode = 0) {
	if(!RE_WRITE) return $url;
	if(RE_WRITE == 1 && strpos($url, 'search.php') !== false) return $url;
	if(strpos($url, '.php?') === false || strpos($url, '=') === false) return $url;
	$url = str_replace(array('+', '-'), array('%20', '%20'), $url);
	$url = str_replace(array('.php?', '&', '='), array('-htm-', '-', '-'), $url).'.html';
	return $url;
}

function timetodate($time = 0, $type = 6) {
	if(!$time) $time = DT_TIME;
	$types = array('Y-m-d', 'Y', 'm-d', 'Y-m-d', 'm-d H:i', 'Y-m-d H:i', 'Y-m-d H:i:s');
	if(isset($types[$type])) $type = $types[$type];
	$date = '';
	if($time > 2147212800) {		
		if(class_exists('DateTime')) {
			$D = new DateTime('@'.($time - 3600 * intval(str_replace('Etc/GMT', '', $GLOBALS['CFG']['timezone']))));
			$date = $D->format($type);
		}
	}
	return $date ? $date : date($type, $time);
}

function datetotime($date) {
	$time = strtotime($date);
	if($time === false) {
		if(class_exists('DateTime')) {
			$D = new DateTime($date);
			$time = $D->format('U');
		}
	}
	return $time;
}

function log_write($message, $type = 'php', $force = 0) {
	global $_username, $log_id;
	if(!DT_DEBUG && !$force) return;
	if($log_id) {
		$log_id++;
	} else {
		$log_id = 1;
	}
	$user = $_username ? $_username : 'guest';
	check_name($type) or $type = 'php';
	$log = "<?php exit;?>\n<$type>\n";
	$log .= "\t<time>".timetodate()."</time>\n";
	$log .= "\t<ip>".DT_IP."</ip>\n";
	$log .= "\t<user>".$user."</user>\n";
	$log .= "\t<php>".$_SERVER['SCRIPT_NAME']."</php>\n";
	$log .= "\t<querystring>".str_replace('&', '&amp;', $_SERVER['QUERY_STRING'])."</querystring>\n";
	$log .= "\t<message>".(is_array($message) ? var_export($message, true) : $message)."</message>\n";
	$log .= "</$type>";
	file_put(DT_ROOT.'/file/log/'.timetodate(0, 'Ym/d').'/'.$type.'-'.timetodate(0, 'Y.m.d H.i.s').'-'.$log_id.'.php', $log);
}

function load($file) {
	$ext = file_ext($file);
	if($ext == 'css') {
		echo '<link rel="stylesheet" type="text/css" href="'.DT_SKIN.$file.'" />';
	} else if($ext == 'js') {
		echo '<script type="text/javascript" src="'.DT_STATIC.'file/script/'.$file.'"></script>';
	} else if($ext == 'htm') {
		$file = str_replace('ad_m', 'ad_t6_m', $file);
		if(is_file(DT_CACHE.'/htm/'.$file)) {
			$content = file_get(DT_CACHE.'/htm/'.$file);
			if(substr($content, 0, 4) == '<!--') $content = substr($content, 17);
			echo $content;
		} else {
			echo '';
		}
	} else if($ext == 'lang') {
		$file = str_replace('.lang', '.inc.php', $file);
		return DT_ROOT.'/lang/'.DT_LANG.'/'.$file;
	} else if($ext == 'inc' || $ext == 'func' || $ext == 'class') {
		return DT_ROOT.'/include/'.$file.'.php';
	}
}

function ad($id, $cid = 0, $kw = '', $tid = 0) {
	global $cityid;
	if($tid) {
		if($kw) {
			$file = 'ad_t'.$tid.'_m'.$id.'_k'.urlencode($kw);
		} else if($cid) {
			$file = 'ad_t'.$tid.'_m'.$id.'_c'.$cid;
		} else {
			$file = 'ad_t'.$tid.'_m'.$id;
		}
		$a3 = 'ad_'.$id.'_d'.$tid.'.htm';
	} else {
		$file = 'ad_'.$id;
		$a3 = 'ad_'.$id.'_d0.htm';
	}
	$a1 = $file.'_'.$cityid.'.htm';
	if(is_file(DT_CACHE.'/htm/'.$a1)) return load($a1);
	$a2 = $file.'_0.htm';
	if(is_file(DT_CACHE.'/htm/'.$a2)) return load($a2);
	if(is_file(DT_CACHE.'/htm/'.$a3)) return load($a3);
}

function lang($str, $arr = array()) {
	if(strpos($str, '->') !== false) {
		global $DT;
		$t = explode('->', $str);
		include load($t[0].'.lang');
		$str = $L[$t[1]];
	}
	if($arr) {
		foreach($arr as $k=>$v) {
			$str = str_replace('{V'.$k.'}', $v, $str);
		}
	}
	return $str;
}

function check_name($username) {
	if(strpos($username, '__') !== false || strpos($username, '--') !== false) return false; 
	return preg_match("/^[a-z0-9]{1}[a-z0-9_\-]{0,}[a-z0-9]{1}$/", $username);
}

function check_post() {
	if(strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') return false;
	return check_referer();
}

function check_referer() {
	global $DT_REF, $CFG, $DT;
	if($DT['check_referer']) {
		if(!$DT_REF) return false;
		$R = parse_url($DT_REF);
		if($CFG['cookie_domain'] && strpos($R['host'], $CFG['cookie_domain']) !== false) return true;
		if($CFG['com_domain'] && strpos($R['host'], $CFG['com_domain']) !== false) return true;
		if($DT['safe_domain']) {
			$tmp = explode('|', $DT['safe_domain']);
			foreach($tmp as $v) {
				if(strpos($R['host'], $v) !== false) return true;
			}
		}		
		$U = parse_url(DT_PATH);
		if(strpos($R['host'], str_replace('www.', '.', $U['host'])) !== false) return true;
		return false;
	}
	return true;
}

function is_robot() {
	return preg_match("/(spider|bot|crawl|slurp|lycos|robozilla)/i", $_SERVER['HTTP_USER_AGENT']);
}

function is_ip($ip) {
	return preg_match("/^([0-9]{1,3}\.){3}[0-9]{1,3}$/", $ip);
}

function is_mobile($mobile) {
	return preg_match("/^1[3|4|5|6|7|8|9]{1}[0-9]{9}$/", $mobile);
}

function is_md5($password) {
	return preg_match("/^[a-f0-9]{32}$/", $password);
}

function is_openid($openid) {
	return preg_match("/^[0-9a-zA-Z\-_]{10,}$/", $openid);
}

function is_touch() {
	$ck = get_cookie('mobile');
	if($ck == 'pc') return 0;
	if($ck == 'touch' || $ck == 'screen') return 1;
	return preg_match("/(iPhone|iPad|iPod|Android)/i", $_SERVER['HTTP_USER_AGENT']) ? 1 : 0;
}

function is_founder($userid) {
	global $CFG;
	$userid = intval($userid);
	if($userid < 1) return false;
	if(strpos($CFG['founderid'], ',') === false) {
		return $userid == $CFG['founderid'] ? true : false;
	} else {
		return strpos(','.$CFG['founderid'].',', ','.$userid.',') === false ? false : true;
	}
}

function debug() {
	global $db, $debug_starttime;
	$mtime = explode(' ', microtime());
	$s = number_format(($mtime[1] + $mtime[0] - $debug_starttime), 3);
	echo 'Processed in '.$s.' second(s), '.$db->querynum.' queries';
    if(function_exists('memory_get_usage')) echo ', Memory '.round(memory_get_usage()/1024/1024, 2).' M';
}

function dhttp($status, $exit = 1) {
	switch($status) {
		case '301': @header("HTTP/1.1 301 Moved Permanently"); break;
		case '403': @header("HTTP/1.1 403 Forbidden"); break;
		case '404': @header("HTTP/1.1 404 Not Found"); break;
		case '503': @header("HTTP/1.1 503 Service Unavailable"); break;
	}
	if($exit) exit;
}

function dcurl($url, $par = '') {
	if(function_exists('curl_init')) {
		$cur = curl_init($url);
		if($par) {
			curl_setopt($cur, CURLOPT_POST, 1);
			curl_setopt($cur, CURLOPT_POSTFIELDS, $par);
		}
		curl_setopt($cur, CURLOPT_REFERER, DT_PATH);
		curl_setopt($cur, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($cur, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($cur, CURLOPT_HEADER, 0);
		curl_setopt($cur, CURLOPT_TIMEOUT, 30);
		curl_setopt($cur, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($cur, CURLOPT_RETURNTRANSFER, 1);
		$rec = curl_exec($cur);
		curl_close($cur);
		return $rec;
	}
	return file_get($par ? $url.'?'.$par : $url);
}

function d301($url) {
	dhttp(301, 0);
	dheader($url);
}
?>