<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
if(!isset($CFG['edittpl']) || !$CFG['edittpl']) msg('系统禁止了在线修改模板，请修改根目录config.inc.php<br/>$CFG[\'edittpl\'] = \'0\'; 修改为 $CFG[\'edittpl\'] = \'1\';');
isset($dir) or $dir = '';
$menus = array (
	array('新建模板', '?file='.$file.'&action=add&dir='.$dir),
    array('模板管理', '?file='.$file),
    array('风格管理', '?file=skin'),
    array('标签向导', '?file=tag'),
);
isset($bakid) or $bakid = '';
isset($fileid) or $fileid = '';
$this_forward = '?file='.$file.'&dir='.$dir;
$tpl = get_cookie('tpl');
$tpl = check_name($tpl) ? $tpl : $CFG['template'];
$template_root = DT_ROOT.'/template/'.$tpl.'/'.$dir;
$template_path = 'template/'.$tpl.'/'.$dir;
@include $template_root.'/these.name.php';

function template_name($fileid = '', $name = '') {
	global $template_root, $names;
	isset($names) or $names = array();
	if($fileid && $name) $names[$fileid] = $name;
	foreach($names as $k => $v) {
		if(!is_file($template_root.'/'.$k.'.htm') && !is_dir($template_root.'/'.$k)) unset($names[$k]);
	}
	if($names) ksort($names);
	file_put($template_root.'/these.name.php', "<?php\n\$names = ".var_export($names, true).";\n?>");
}

function template_safe($content) {
	$content = stripslashes($content);
	$content = strip_sql($content, 0);
	if(preg_match_all("/script([^>]+)>/i", $content, $matches)) {
		foreach($matches[1] as $m) {
			$m = strtolower($m);
			if(strpos($m, 'language') !== false || strpos($m, 'php') !== false) msg('模板内容包含不安全写法，禁止在线修改或预览');
		}
	}
	if(preg_match("/(\<\?|\{php|file\(|eval|copy|file_put|file_get|fopen|fwrite|fread)/i", $content)) msg('模板内容包含不安全写法，禁止在线修改或预览');
	return $content;
}

switch($action) {
	case 'add':
		if($submit) {
			if(!preg_match("/^[a-z0-9_\-]+$/", $fileid)) msg('文件名只能为小写字母、数字、中划线、下划线');
			if(substr($fileid, -1) == '-') msg('文件名不能以 - 符号结尾');
			if(substr_count($fileid, '-') > 1) msg('文件名只能包含一个 - 符号');
			if(!$name) $name = $fileid;
			$template = $template_root.'/'.$fileid.'.htm';
			if(isset($nowrite) && is_file($template)) msg('文件已经存在');
			file_put($template, template_safe($content));
			if($name != $fileid) template_name($fileid, $name);
			dmsg('创建成功', $this_forward);
		} else {
			$content = '';
			if(isset($type)) $content = dhtmlspecialchars(file_get($template_root.'/'.$type.'.htm'));
			include tpl('template_add');
		}
	break;
	case 'edit':
		if($submit) {
			$dfileid or msg();
			if(!preg_match("/^[a-z0-9_\-]+$/", $fileid)) msg('文件名只能为小写字母、数字、中划线、下划线');
			if(substr($fileid, -1) == '-') msg('文件名不能以 - 符号结尾');
			if(substr_count($fileid, '-') > 1) msg('文件名只能包含一个 - 符号');
			if(!$name) $name = $fileid;
			$dfile = $template_root.'/'.$dfileid.'.htm';
			$nfile = $template_root.'/'.$fileid.'.htm';
			if(isset($backup)) {
				$i = 0;
				while(++$i) {
					$bakfile = $template_root.'/'.$dfileid.'.'.$i.'.bak';
					if(!is_file($bakfile)) {
						file_copy($dfile, $bakfile);
						break;
					}
				}
			}
			file_put($nfile, template_safe($content));
			if($dfileid != $fileid) file_del($dfile);
			if($name != $fileid) template_name($fileid, $name);
			dmsg('修改成功', '?file='.$file.'&action='.$action.'&fileid='.$fileid.'&dir='.$dir);
		} else {
			$fileid or msg();
			if(!is_write($template_root.'/'.$fileid.'.htm')) msg($fileid.'.htm不可写，请将其属性设置为可写');
			if($dir) $template_path = $template_path.'/';
			$name = (isset($names[$fileid]) && $names[$fileid]) ? $names[$fileid] : $fileid;
			$content = dhtmlspecialchars(file_get($template_root.'/'.$fileid.'.htm'));
			include tpl('template_edit');
		}
	break;
	case 'preview':
		$db->halt = 0;
		require_once DT_ROOT.'/include/template.func.php';
		$tpl_content = template_safe($content);
		unset($content);
		$tpl_content = template_parse($tpl_content);
		cache_write('_preview.tpl.php', $tpl_content, 'tpl');
		$module = $dir ? $dir : 'destoon';
		$head_title = '模板预览';
		include DT_CACHE.'/tpl/_preview.tpl.php';
		exit();
	break;
	case 'import':
		$fileid or msg();
		$bakid or msg();
		if(file_copy($template_root.'/'.$fileid.'.'.$bakid.'.bak', $template_root.'/'.$fileid.'.htm')) dmsg('恢复成功', $this_forward);
		msg('备份文件恢复失败');
	break;
	case 'template_name':
		$fileid or exit('0');
		$name or exit('0');
		template_name($fileid, $name);
		exit('1');
	break;
	case 'download':
		$fileid or msg();
		$file_ext = $bakid ? '.'.$bakid.'.bak' : '.htm';
		file_down($template_root.'/'.$fileid.$file_ext);
	break;
	case 'delete':
		$fileid or msg();
		$file_ext = $bakid ? '.'.$bakid.'.bak' : '.htm';
		file_del($template_root.'/'.$fileid.$file_ext);
		if(!$bakid) template_name();
		dmsg('删除成功', $this_forward);
	break;
	case 'cache':
		cache_clear('php', 'dir', 'tpl');
		dmsg('更新成功', $this_forward);	
	break;
	case 'change':
		$to = check_name($to) ? $to : '';
		if($to && is_dir(DT_ROOT.'/template/'.$to.'/')) {
			if($to == $CFG['template']) $to = '';
			set_cookie('tpl', $to);
		}
		dmsg('切换成功', $this_forward);	
	break;
	default:
		$dirs = $files = $templates = $baks = array();
		if(substr($template_root, -1) != '/') $template_root .= '/';
		$files = glob($template_root.'*');
		if(!$files) msg('模板文件不存在，请先创建', "?file=$file&action=add&dir=$dir");
		foreach($files as $k=>$v) {
			if(is_dir($v)) {
				$dirid = basename($v);
				$dirs[$dirid]['dirname'] = $dirid;
				$dirs[$dirid]['name'] = (isset($names[$dirid]) && $names[$dirid]) ? $names[$dirid] : $dirid;
				$dirs[$dirid]['mtime'] = timetodate(filemtime($v), 5);
				$dirs[$dirid]['mod'] = substr(base_convert(fileperms($v), 10, 8), -4);
			} else {
				$filename = str_replace($template_root, '', $v);
				if(preg_match("/^[0-9a-z_-]+\.htm$/", $filename)) {
					$fileid = str_replace('.htm', '', $filename);
					$templates[$fileid]['fileid'] = $fileid;
					$templates[$fileid]['filename'] = $filename;
					$templates[$fileid]['filesize'] = round(filesize($v)/1024, 2);
					$templates[$fileid]['name'] = (isset($names[$fileid]) && $names[$fileid]) ? $names[$fileid] : $fileid;
					$tmp = strpos($filename, '-');
					$templates[$fileid]['type'] = $tmp ? substr($filename, 0, $tmp) : $fileid;
					$templates[$fileid]['mtime'] = timetodate(filemtime($v), 5);
					$templates[$fileid]['mod'] = substr(base_convert(fileperms($v), 10, 8), -4);
				} else if(preg_match("/^([0-9a-z_-]+)\.([0-9]+)\.bak$/", $filename, $m)) {
					$fileid = str_replace('.bak', '', $filename);
					$baks[$fileid]['fileid'] = $fileid;
					$baks[$fileid]['filename'] = $filename;
					$baks[$fileid]['filesize'] = round(filesize($v)/1024, 2);
					$baks[$fileid]['number'] = $m[2];
					$baks[$fileid]['type'] = $m[1];
					$baks[$fileid]['mtime'] = timetodate(filemtime($v), 5);
					$baks[$fileid]['mod'] = substr(base_convert(fileperms($v), 10, 8), -4);
				}
			}
		}
		if($dirs) ksort($dirs);
		if($templates) ksort($templates);
		if($baks) ksort($baks);
		include tpl('template');
	break;
}
?>