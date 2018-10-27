<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
function fields_update($post_fields, $table, $itemid, $keyname = 'itemid', $fd = array()) {
	global $FD;
	if(!$table || !$itemid) return '';
	if($fd) $FD = $fd;
	$sql = '';
	foreach($FD as $k=>$v) {
		if(isset($post_fields[$v['name']]) || $v['html'] == 'checkbox') {
			$mk = $v['name'];
			$mv = $post_fields[$v['name']];
			if($v['html'] == 'checkbox') $mv = implode(',', $post_fields[$v['name']]);
			$mv = $v['html'] == 'editor' ? dsafe($mv) : dhtmlspecialchars(trim($mv));
			$sql .= ",$mk='$mv'";
		}
	}
	$sql = substr($sql, 1);
	if($sql) DB::query("UPDATE {$table} SET $sql WHERE `$keyname`=$itemid");
}

function fields_check($post_fields, $fd = array()) {
	global $FD, $session;
	include load('include.lang');
	if($fd) $FD = $fd;
	if(!is_object($session)) $session = new dsession();
	$uploads = isset($_SESSION['uploads']) ? $_SESSION['uploads'] : array();
	foreach($FD as $k=>$v) {
		$value = isset($post_fields[$v['name']]) ? $post_fields[$v['name']] : '';
		if(in_array($v['html'], array('thumb', 'file', 'editor')) && $uploads) {
			foreach($uploads as $sk=>$sv) {
				if($v['html'] == 'editor') {
					if(strpos($value, $sv) !== false) unset($_SESSION['uploads'][$sk]);
				} else {
					if($sv == $value) unset($_SESSION['uploads'][$sk]);
				}
			}
		}
		if(!$v['input_limit']) continue;
		if(!defined('DT_ADMIN') && !$v['front']) continue;
		if($v['input_limit'] == 'is_date') {
			if(!is_date($value)) fields_message(lang($L['fields_input'], array($v['title'])));
		} else if($v['input_limit'] == 'is_time') {
			if(!is_time($value)) fields_message(lang($L['fields_input'], array($v['title'])));
		} else if($v['input_limit'] == 'is_email') {
			if(!is_email($value)) fields_message(lang($L['fields_valid'], array($v['title'])));
		} else if(is_numeric($v['input_limit'])) {
			$length = $value ? ($v['html'] == 'checkbox' ? count($value) : word_count($value)) : 0;
			if($length < $v['input_limit']) fields_message(lang($L['fields_less'], array($v['title'], $v['input_limit'])));
		} else if(preg_match("/^([0-9]{1,})\-([0-9]{1,})$/", $v['input_limit'], $m)) {			
			$length = $value ? ($v['html'] == 'checkbox' ? count($value) : word_count($value)) : 0;
			if($m[1] && $length < $m[1]) fields_message(lang($L['fields_less'], array($v['title'], $m[1])));
			if($m[2] && $length > $m[2]) fields_message(lang($L['fields_more'], array($v['title'], $m[2])));
		} else {
			if(!preg_match("/^".$v['input_limit']."$/", $value)) fields_message(lang($L['fields_match'], array($v['title'])));
		}
	}
}

function fields_js($fd = array()) {
	global $FD;
	if($fd) $FD = $fd;
	$js = '';
	include load('include.lang');
	foreach($FD as $k=>$v) {
		if(!$v['input_limit']) continue;
		if(!defined('DT_ADMIN') && !$v['front']) continue;
		if($v['input_limit'] == 'is_date') {
			$js .= 'f = "post_fields'.$v['name'].'";l = Dd(f).value.length;';
			$js .= 'if(l != 10) {Dmsg("'.lang($L['fields_input'], array($v['title'])).'", f, 1);return false;}';
		} else if($v['input_limit'] == 'is_time') {
			$js .= 'f = "post_fields'.$v['name'].'";l = Dd(f).value.length;';
			$js .= 'if(l > 19 || l > 16) {Dmsg("'.lang($L['fields_input'], array($v['title'])).'", f, 1);return false;}';
		} else if($v['input_limit'] == 'is_email') {
			$js .= 'f = "'.$v['name'].'";l = Dd(f).value.length;';
			$js .= 'if(l < 8) {Dmsg("'.lang($L['fields_input'], array($v['title'])).'", f);return false;}';
		} else if(is_numeric($v['input_limit'])) {
			if($v['html'] == 'area') {
				$js .= 'f = "'.$v['name'].'";l = Dd("areaid_1").value;';
				$js .= 'if(l == 0) {Dmsg("'.lang($L['fields_area']).'", f, 1);return false;}';
			} else if($v['html'] == 'checkboxs') {
				$js .= 'f = "'.$v['name'].'";l = checked_count(f);';
				$js .= 'if(l < '.$v['input_limit'].') {Dmsg("'.lang($L['fields_less'], array($v['title'], $v['input_limit'])).'", f, 1);return false;}';
			} else {
				$js .= 'f = "'.$v['name'].'";l = Dd(f).value.length;';
				$js .= 'if(l < '.$v['input_limit'].') {Dmsg("'.lang($L['fields_less'], array($v['title'], $v['input_limit'])).'", f);return false;}';
			}
		} else if(preg_match("/^([0-9]{1,})\-([0-9]{1,})$/", $v['input_limit'], $m)) {
			if($v['html'] == 'checkbox') {
				$js .= 'f = "'.$v['name'].'";l = checked_count(f);';
				if($m[1]) $js .= 'if(l < '.$m[1].') {Dmsg("'.lang($L['fields_less'], array($v['title'], $m[1])).'", f, 1);return false;}';
				if($m[2]) $js .= 'if(l > '.$m[2].') {Dmsg("'.lang($L['fields_more'], array($v['title'], $m[2])).'", f, 1);return false;}';
			} else {
				$js .= 'f = "'.$v['name'].'";l = Dd(f).value.length;';
				if($m[1]) $js .= 'if(l < '.$m[1].') {Dmsg("'.lang($L['fields_less'], array($v['title'], $m[1])).'", f);return false;}';
				if($m[2]) $js .= 'if(l > '.$m[2].') {Dmsg("'.lang($L['fields_more'], array($v['title'], $m[2])).'", f);return false;}';
			}
		} else {
			$js .= 'f = "'.$v['name'].'";l = Dd(f).value;';
			$js .= 'if(l.match(/^'.$v['input_limit'].'$/) == null) {Dmsg("'.lang($L['fields_match'], array($v['title'])).'", f);return false;}';
		}
	}
	return $js;
}

function fields_html($left = '<td class="tl">', $right = '<td>', $values = array(), $fd = array()) {
	extract($GLOBALS, EXTR_SKIP);
	if($fd) $FD = $fd;
	$html = '';
	foreach($FD as $k=>$v) {
		if(!$v['display']) continue;
		if(!defined('DT_ADMIN') && !$v['front']) continue;
		$html .= fields_show($k, $left, $right, $values, $fd);
	}
	return $html;
}

function fields_show($itemid, $left = '<td class="tl">', $right = '<td>', $values = array(), $fd = array()) {
	extract($GLOBALS, EXTR_SKIP);
	if($fd) $FD = $fd;
	if(!$values) {
		if(isset($item)) $values = $item;
		if(isset($user)) $values = $user;
	}
	$html = '';
	$v = $FD[$itemid];
	$value = $v['default_value'];
	$did = 'd'.$v['name'];
	if(isset($values[$v['name']])) {
		$value = $values[$v['name']];
	} else if($v['default_value']) {
		eval('$value = "'.$v['default_value'].'";');
	}
	if($v['html'] == 'hidden') {
		$html .= '<input type="hidden" name="post_fields['.$v['name'].']" id="'.$v['name'].'" value="'.$value.'" '.$v['addition'].'/>';
	} else {
		if($DT_PC) {
			$html .= '<tr>'.$left;
			if($v['input_limit']) {
				$html .= '<span class="f_red">*</span> ';
			} else {
				$html .= defined('DT_ADMIN') ? '<span class="f_hid">*</span> ' : '';
			}
			$html .= $v['title'];
			$html .= '</td>';
			$html .= $right;
		} else {
			$html .= '<p>'.$v['title'];
			if($v['input_limit']) $html .= '<em>*</em>';
			$html .= '<b id="'.$did.'"></b></p><div>';
		}
		switch($v['html']) {
			case 'text':
				$html .= '<input type="text" name="post_fields['.$v['name'].']" id="'.$v['name'].'" value="'.$value.'" '.$v['addition'].'/>';
			break;
			case 'textarea':
				$html .= '<textarea name="post_fields['.$v['name'].']" id="'.$v['name'].'" '.$v['addition'].'>'.$value.'</textarea>';
			break;
			case 'select':
				if($v['option_value']) {
					$html .= '<select name="post_fields['.$v['name'].']" id="'.$v['name'].'" '.$v['addition'].'><option value="">'.$L['choose'].'</option>';
					$rows = explode("*", $v['option_value']);
					foreach($rows as $row) {
						if($row) {
							$cols = explode("|", trim($row));
							$html .= '<option value="'.$cols[0].'"'.($cols[0] == $value ? ' selected' : '').'>'.$cols[1].'</option>';
						}
					}
					$html .= '</select>';
				}
			break;
			case 'radio':
				if($v['option_value']) {
					$html .= '<span id="'.$v['name'].'">';
					$rows = explode("*", $v['option_value']);
					foreach($rows as $rw => $row) {
						if($row) {
							$cols = explode("|", trim($row));
							$html .= '<input type="radio" name="post_fields['.$v['name'].']" value="'.$cols[0].'" id="'.$v['name'].'_'.$rw.'"'.($cols[0] == $value ? ' checked' : '').'> '.$cols[1].'&nbsp;&nbsp;&nbsp;';
						}
					}
					$html .= '</span>';
				}
			break;
			case 'checkbox':
				if($v['option_value']) {
					$html .= '<span id="'.$v['name'].'">';
					$value = explode(',', $value);
					$rows = explode("*", $v['option_value']);
					foreach($rows as $rw => $row) {
						if($row) {
							$cols = explode("|", trim($row));
							$html .= '<input type="checkbox" name="post_fields['.$v['name'].'][]" value="'.$cols[0].'" id="'.$v['name'].'_'.$rw.'"'.(in_array($cols[0], $value) ? ' checked' : '').'> '.$cols[1].'&nbsp;&nbsp;&nbsp;';
						}
					}
					$html .= '</span>';
				}
			break;
			case 'date':
				if($DT_PC) {
					$html .= dcalendar('post_fields['.$v['name'].']', $value);
					$did = 'post_dfields'.$v['name'];
				} else {
					$html .= '<input type="date" name="post_fields['.$v['name'].']" id="'.$v['name'].'" value="'.$value.'" '.$v['addition'].'/>';
				}
			break;
			case 'time':
				if($DT_PC) {
					$html .= dcalendar('post_fields['.$v['name'].']', $value, '-', 1);
					$did = 'post_dfields'.$v['name'];
				} else {
					$html .= '<input type="datetime-local" name="post_fields['.$v['name'].']" id="'.$v['name'].'" value="'.$value.'" '.$v['addition'].'/>';
				}
			break;
			case 'thumb':
				if($DT_PC) {
					$html .= '<input name="post_fields['.$v['name'].']" type="text" size="60" id="'.$v['name'].'" value="'.$value.'" '.$v['addition'].'/>&nbsp;&nbsp;<span onclick="Dthumb('.$moduleid.','.$v['width'].','.$v['height'].', Dd(\''.$v['name'].'\').value,\''.(defined('DT_ADMIN') ? '' : '1').'\',\''.$v['name'].'\');" class="jt">['.$L['upload'].']</span>&nbsp;&nbsp;<span onclick="_preview(Dd(\''.$v['name'].'\').value);" class="jt">['.$L['preview'].']</span>&nbsp;&nbsp;<span onclick="Dd(\''.$v['name'].'\').value=\'\';" class="jt">['.$L['delete'].']</span>';
				} else {
					$html .= '<input type="url" name="post_fields['.$v['name'].']" id="'.$v['name'].'" value="'.$value.'" '.$v['addition'].'/>';
				}
			break;
			case 'file':
				if($DT_PC) {
					$html .= '<input name="post_fields['.$v['name'].']" type="text" size="60" id="'.$v['name'].'" value="'.$value.'" '.$v['addition'].'/>&nbsp;&nbsp;<span onclick="Dfile('.$moduleid.', Dd(\''.$v['name'].'\').value, \''.$v['name'].'\');" class="jt">['.$L['upload'].']</span>&nbsp;&nbsp;<span onclick="if(Dd(\''.$v['name'].'\').value) window.open(Dd(\''.$v['name'].'\').value);" class="jt">['.$L['preview'].']</span>&nbsp;&nbsp;<span onclick="Dd(\''.$v['name'].'\').value=\'\';" class="jt">['.$L['delete'].']</span>';
				} else {
					$html .= '<input type="url" name="post_fields['.$v['name'].']" id="'.$v['name'].'" value="'.$value.'" '.$v['addition'].'/>';
				}
			break;
			case 'editor':
				if($DT_PC) {
					$toolbar = isset($group_editor) ? $group_editor : 'Destoon';
					if(DT_EDITOR == 'fckeditor') {
						$html .= '<textarea name="post_fields['.$v['name'].']" id="'.$v['name'].'" style="display:none">'.$value.'</textarea><iframe id="'.$v['name'].'___Frame" src="'.$MODULE[2]['linkurl'].'editor/fckeditor/editor/fckeditor.html?InstanceName='.$v['name'].'&Toolbar='.$toolbar.'" width="'.$v['width'].'" height="'.$v['height'].'" frameborder="no" scrolling="no"></iframe><br/>';
					} else {
						$html .= '<textarea name="post_fields['.$v['name'].']" id="'.$v['name'].'" style="display:none">'.$value.'</textarea>'. deditor($moduleid, $v['name'], $toolbar, $v['width'], $v['height']);
					}
				} else {
					$html .= '<textarea name="post_fields['.$v['name'].']" id="'.$v['name'].'" '.$v['addition'].'>'.$value.'</textarea>';
				}
				
			break;
			case 'area':
				$html .= ajax_area_select('post_fields['.$v['name'].']', $GLOBALS['L']['choose'], $value);
			break;
		}
		if($DT_PC) {
			$html .= ' <span class="f_red" id="'.$did.'"></span>';
			$html .= $v['note'];
			$html .= '</td></tr>';
		} else {
			$html .= '</div>';
		}
	}
	return $html;
}

function fields_message($msg) {
	defined('DT_ADMIN') ? msg($msg) : dalert($msg);
}
?>