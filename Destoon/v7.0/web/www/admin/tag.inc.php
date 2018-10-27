<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('重建缓存', '?file='.$file.'&action=cache'),
    array('模板管理', '?file=template'),
    array('风格管理', '?file=skin'),
    array('标签向导', '?file='.$file),
);
switch($action) {
	case 'cache':
		cache_clear('htm', 'dir', 'tag');
		dmsg('更新成功', '?file='.$file);
	break;
	case 'find':
		$mid or $mid = '';
		$tb = isset($tb) ? trim($tb) : '';
		if(isset($MODULE[$mid]) && $mid > 3) {
			$table = get_table($mid);
			$note = urlencode($MODULE[$mid]['name']);
		} else {
			$table = $DT_PRE.$tb;
			$note = '';
		}
		dheader('?file='.$file.'&action=dict&table='.$table.'&note='.$note);
	break;
	case 'dict':
		(isset($table) && $table) or exit;
		$table = strip_sql($table, 0);
		if(strpos($table, $DT_PRE) === false) {
			$rtable = $table;
		} else {
			$rtable = substr($table, strlen($DT_PRE));
			$rtable = preg_replace("/_[0-9]{1,}/", '', $rtable);
		}
		if($submit) {
			$csv = '';
			foreach($name as $k=>$v) {
				$v = str_replace(',', '，', $v);
				$n = str_replace(',', '，', $note[$k]);
				$csv .= $k.','.$v.','.$n."\n";
			}
			file_put(DT_ROOT.'/file/setting/'.$rtable.'.csv', trim($csv));
			dmsg('更新成功', '?file='.$file.'&action='.$action.'&table='.$table.'&note='.urlencode($nt));
		} else {
			$fields = $csv = array();
			if(is_file(DT_ROOT.'/file/setting/'.$rtable.'.csv')) {
				$tmp = file_get(DT_ROOT.'/file/setting/'.$rtable.'.csv');
				$arr = explode("\n", $tmp);
				foreach($arr as $v) {
					$t = explode(',', $v);
					$csv[$t[0]]['name'] = $t[1];
					$csv[$t[0]]['note'] = $t[2];
				}
			}
			$result = $db->query("SHOW COLUMNS FROM `$table`");
			while($r = $db->fetch_array($result)) {
				$r['Type'] = str_replace(' unsigned', '', $r['Type']);
				if(isset($csv[$r['Field']])) {
					$r['cn_name'] = $csv[$r['Field']]['name'];
					$r['cn_note'] = $csv[$r['Field']]['note'];
				} else {
					$r['cn_name'] = $r['cn_note'] = '';
					//if(isset($names[$r['Field']])) $r['cn_name'] = $names[$r['Field']];
				}
				$fields[] = $r;
			}
			include tpl('tag_dict');
		}
	break;
	case 'preview':
		$db->halt = 0;
		$destoon_task = '';
		if($tag_css) $tag_css = stripslashes($tag_css); 
		if($tag_html_s) $tag_html_s = stripslashes($tag_html_s); 
		if($tag_html_e) $tag_html_e = stripslashes($tag_html_e); 
		if($tag_code) $tag_code = stripslashes($tag_code); 
		$code_eval = $code_call = $code_html = '';
		if($tag_css) $code_eval .= '<style type="text/css">'."\n".''.$tag_css.''."\n".'</style>'."\n";
		if($tag_html_s) $code_eval .= $tag_html_s."\n";
		$code_call = $code_eval;
		$code_call .= $tag_code."\n";
		$tag_code = str_replace('<!--{', '', $tag_code);
		$tag_code = str_replace('}-->', '', $tag_code);
		if(strpos($tag_code, '",') !== false) {
			$tag_code = str_replace(', '.$tag_expires.')', ', -1)', $tag_code);
		} else {
			$tag_code = str_replace('")', '", -1)', $tag_code);
		}
		$tag_code .= ';';
		$tag_pass = 1;
		if(substr($tag_code, 0, 5) != 'tag("') $tag_pass = 0;
		if(substr($tag_code, -7) != '", -1);') $tag_pass = 0;
		$tag_safe = substr($tag_code, 5, -7);
		foreach(array('(', '`', ',', ';') as $v) {
			if(strpos($tag_safe, $v) !== false) {
				$tag_pass = 0;
				break;
			}
		}
		$tag_pass or msg('标签内容包含不安全写法，禁止在线预览');
		$tag_md5 = md5($tag_safe);
		$tag_js = '<script type="text/javascript" charset="'.DT_CHARSET.'" src="'.DT_PATH.'api/js.php?auth='.encrypt($tag_safe).'&key=0'.$tag_md5.'&'.$tag_safe.'"></script>';
		ob_start();
		eval($tag_code);
		$contents = ob_get_contents();
		ob_clean();
		$code_eval .= $contents."\n";
		if($tag_html_e) {
			$code_eval .= $tag_html_e;
			$code_call .= $tag_html_e;
		}
		$t = str_replace('",', '&debug=1",', $tag_code);
		ob_start();
		eval($t);
		$td = ob_get_contents();
		ob_clean();
		$t = explode('<br/>', $td);
		$tag_debug = "参数:".$t[0]."\n语句:".$t[1];
		$head_title = '标签预览';
		include tpl('tag_preview');
	break;
	default:
		$table_select = $all_select = '';
		$out = array('ad', 'ad_place', 'admin', 'alert', 'area', 'ask' ,'category', 'favorite', 'finance_cash', 'finance_charge', 'finance_record', 'finance_trade', 'friend', 'group', 'guestbook', 'keylink', 'log', 'mail', 'mail_list', 'message', 'module', 'session', 'style', 'type', 'vip');
		$query = $db->query("SHOW TABLE STATUS FROM `".$CFG['db_name']."`");
		while($r = $db->fetch_row($query)) {
			$table = $r[0];
			$alltable = preg_match("/^".$DT_PRE."/i", $table) ? substr($table, strlen($DT_PRE)) : $table.'&prefix=';
			$all_select .= '<option value="'.$alltable.'">'.$table.'</option>';
			if(substr($table, -5) == '_data' || strpos($table, '_data_') !== false) continue;
			if(preg_match("/^".$DT_PRE."/i", $table)) {
				$table = substr($table, strlen($DT_PRE));
				if(in_array($table, $out)) continue;
				$s = $db->get_one("SHOW TABLE STATUS FROM `".$CFG['db_name']."` LIKE '".$r[0]."'");
				$table_select .= '<option value="'.$table.'">'.($s['Comment'] ? $s['Comment'] : $table).'</option>';         
			}
		}
		$mid or $mid = '';
		include tpl('tag');
	break;
}
?>