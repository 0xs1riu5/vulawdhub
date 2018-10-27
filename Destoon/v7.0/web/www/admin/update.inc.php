<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$release = isset($release) ? intval($release) : 0;
$release or msg();
$release_dir = DT_ROOT.'/file/update/'.$release;
switch($action) {
	case 'download':
		$PHP_URL = @get_cfg_var("allow_url_fopen");
		if(!$PHP_URL) msg('当前服务器不支持URL打开文件，请修改php.ini中allow_url_fopen = on');
		$url = 'http://www.destoon.com/update.php?product=b2b&release='.$release.'&version='.DT_VERSION.'&charset='.DT_CHARSET.'&lang='.DT_LANG.'&domain='.(DT_DOMAIN ? DT_DOMAIN : DT_PATH);
		$code = dcurl($url);
		if($code) {
			if(substr($code, 0, 8) == 'StatusOk') {
				$code = substr($code, 8);
			} else {
				msg($code);
			}
		} else {
			msg('无法连接官方服务器，请重试或稍后更新');
		}
		dir_create($release_dir);
		if(@copy($code, $release_dir.'/'.$release.'.zip')) {
			file_copy(DT_ROOT.'/file/index.html', $release_dir.'/index.html');
			dir_create($release_dir.'/source/');
			dir_create($release_dir.'/backup/');
			msg('更新下载成功，开始解压缩..', '?file='.$file.'&action=unzip&release='.$release);
		} else {
			msg('更新下载失败，请重试..');
		}
	break;
	case 'unzip':
		require DT_ROOT.'/admin/unzip.class.php';
		$zip = new unzip;
		$zip->extract_zip($release_dir.'/'.$release.'.zip', $release_dir.'/source/');
		if(is_file($release_dir.'/source/destoon/version.inc.php')) {			
			msg('更新解压缩成功，开始更新文件..', '?file='.$file.'&action=copy&release='.$release);
		} else {
			msg('更新解压缩失败，请重试..');
		}
	break;
	case 'copy':
		if($CFG['template'] != 'default' && is_dir($release_dir.'/source/destoon/template/default')) @rename($release_dir.'/source/destoon/template/default', $release_dir.'/source/destoon/template/'.$CFG['template']);
		if($CFG['skin'] != 'default' && is_dir($release_dir.'/source/destoon/skin/default')) @rename($release_dir.'/source/destoon/skin/default', $release_dir.'/source/destoon/skin/'.$CFG['skin']);
		$files = file_list($release_dir.'/source/destoon');
		foreach($files as $v) {
			$file_a = str_replace('file/update/'.$release.'/source/destoon/', '', $v);
			$file_b = str_replace('source/destoon/', 'backup/', $v);
			if(is_file($file_a)) file_copy($file_a, $file_b);
		}
		foreach($files as $v) {
			$file_a = str_replace('file/update/'.$release.'/source/destoon/', '', $v);
			file_copy($v, $file_a) or msg('因文件权限不可写，系统无法覆盖'.str_replace(DT_ROOT.'/', '', $file_a).'<br/>请通过FTP工具移动file/update/'.$release.'/source/destoon/目录内所有文件覆盖到站点根目录(Windows独立服务器可以直接复制->粘贴)<br/>Linux独立服务器执行\cp -rf '.DT_ROOT.'/file/update/'.$release.'/source/destoon/* '.DT_ROOT.'/');
		}
		msg('文件更新成功，开始运行更新..', '?file='.$file.'&action=cmd&release='.$release);
	break;
	case 'cmd':
		@include $release_dir.'/source/cmd.inc.php';
		msg('更新运行成功', '?file='.$file.'&action=finish&release='.$release);
	break;
	case 'finish':
		msg('系统更新成功 当前版本V'.DT_VERSION.' R'.DT_RELEASE, '?file=cloud&action=update', 2);
	break;
	case 'undo':
		is_file($release_dir.'/backup/version.inc.php') or msg('此版本备份文件不存在，无法还原', '?file=cloud&action=update', 2);
		@include $release_dir.'/source/cmd.inc.php';
		$files = file_list($release_dir.'/backup');
		foreach($files as $v) {
			file_copy($v, str_replace('file/update/'.$release.'/backup/', '', $v));
		}
		msg('系统还原成功', '?file=cloud&action=update', 2);
	break;
	default:
		$release > DT_RELEASE or msg('当前版本不需要运行此更新', '?file=cloud&action=update', 2);
		msg('在线更新已经启动，开始下载更新..', '?file='.$file.'&action=download&release='.$release, 2);
	break;
}
?>