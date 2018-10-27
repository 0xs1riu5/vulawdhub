<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
if(!isset($CFG['edittpl']) || !$CFG['edittpl']) msg('系统禁止了在线修改模板，请FTP修改根目录config.inc.php<br/>$CFG[\'edittpl\'] = \'0\'; 修改为 $CFG[\'edittpl\'] = \'1\';');
$menus = array (
    array('新建样式', '?file=skin&action=add'),
    array('模板管理', '?file=template'),
    array('风格管理', '?file=skin'),
    array('标签向导', '?file=tag'),
);
$this_forward = '?file='.$file;
$skin = get_cookie('skin');
$skin = check_name($skin) ? $skin : $CFG['skin'];
$skin_root = DT_ROOT.'/skin/'.$skin.'/';
is_dir($skin_root) or dir_create($skin_root);
$skin_path = 'skin/'.$skin.'/';
isset($fileid) or $fileid = '';
isset($bakid) or $bakid = '';
if($fileid && !preg_match("/^[0-9a-z_\-]+$/", $fileid))  msg('文件格式错误');

switch($action) {
	case 'add':
		if($submit) {
			if(!$fileid)  msg('文件名不能为空');
			if(!$content) msg('风格内容不能为空');
			$dfile = $skin_root.$fileid.'.css';
			if(isset($nowrite) && is_file($dfile)) msg('文件已经存在');
			file_put($dfile,  strip_sql(stripslashes($content), 0));
			dmsg('风格添加成功', $this_forward);
		} else {
			include tpl('skin_add');
		}
	break;
	case 'edit':
		if(!$fileid)  msg('文件名不能为空');
		if($submit) {
			if(!$dfileid) msg('Invalid Request');
			if(!$content) msg('风格内容不能为空');
			$dfile = $skin_root.$dfileid.'.css';
			$nfile = $skin_root.$fileid.'.css';
			if(isset($backup)) {
				$i = 0;
				while(++$i) {
					$bakfile = $skin_root.$dfileid.'.'.$i.'.bak';
					if(!is_file($bakfile)) {
						file_copy($dfile, $bakfile);
						break;
					}
				}
			}
			file_put($nfile,  strip_sql(stripslashes($content), 0));
			if($dfileid != $fileid) file_del($dfile);
			dmsg('风格修改成功', $forward);
		} else {
			if(!is_write($skin_root.$fileid.'.css')) msg($fileid.'.css不可写，请将其属性设置为可写');
			$content = file_get($skin_root.$fileid.'.css');
			include tpl('skin_edit');
		}
	break;
	case 'import':
		if(!$fileid) msg('文件名不能为空');
		if(!$bakid) msg('Invalid Request');
		if(file_copy($skin_root.$fileid.'.'.$bakid.'.bak', $skin_root.$fileid.'.css')) dmsg('备份文件恢复成功', $this_forward);
		dmsg('备份文件恢复失败');
	break;
	case 'download':
		if(!$fileid) msg('文件名不能为空');
		$file_ext = $bakid ? '.'.$bakid.'.bak' : '.css';
		file_down($skin_root.$fileid.$file_ext);
	break;
	case 'delete':
		if(!$fileid) msg('文件名不能为空');
		$file_ext = $bakid ? '.'.$bakid.'.bak' : '.css';
		file_del($skin_root.$fileid.$file_ext);
		dmsg('文件删除成功', $this_forward);
	break;
	case 'change':
		$to = check_name($to) ? $to : '';
		if($to && is_dir(DT_ROOT.'/skin/'.$to.'/')) {
			if($to == $CFG['skin']) $to = '';
			set_cookie('skin', $to);
		}
		dmsg('切换成功', $this_forward);	
	break;
	default:
		$files = $skins = $baks = array();
		$files = glob($skin_root.'*.*');
		if(!$files) msg('风格文件不存在，请先创建', "?file=$file&action=add");
		foreach($files as $k=>$v) {
			$filename = str_replace($skin_root, '', $v);
			if(preg_match("/^[0-9a-z_-]+\.css$/", $filename)) {
				$fileid = str_replace('.css', '', $filename);
				$skins[$fileid]['fileid'] = $fileid;
				$skins[$fileid]['filename'] = $filename;
				$skins[$fileid]['filesize'] = round(filesize($v)/1024, 2);
				$skins[$fileid]['mtime'] = date('Y-m-d H:i', filemtime($v));
			} else if(preg_match("/^([0-9a-z_-]+)\.([0-9]+)\.bak$/", $filename, $m)) {
				$fileid = str_replace('.bak', '', $filename);
				$baks[$fileid]['fileid'] = $fileid;
				$baks[$fileid]['filename'] = $filename;
				$baks[$fileid]['filesize'] = round(filesize($v)/1024, 2);
				$baks[$fileid]['number'] = $m[2];
				$baks[$fileid]['type'] = $m[1];
				$baks[$fileid]['mtime'] = date('Y-m-d H:i', filemtime($v));
			}
		}
		if($skins) ksort($skins);
		if($baks) ksort($baks);
		include tpl('skin');
	break;
}
?>