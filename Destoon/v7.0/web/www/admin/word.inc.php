<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('编辑助手', '?file='.$file),
);
switch($action) {
	case 'upload':
		$_FILES['uploadfile']['size'] or dalert('请选择zip文件');
		require DT_ROOT.'/include/upload.class.php';
		$name = date('YmdHis').mt_rand(10, 99).$_userid;
		$upload = new upload($_FILES, 'file/temp/', $name.'.zip', 'zip');
		$upload->adduserid = false;
		if($upload->save()) {
			dir_create(DT_ROOT.'/file/temp/'.$name);
			require DT_ROOT.'/admin/unzip.class.php';
			$zip = new unzip;
			$zip->extract_zip(DT_ROOT.'/file/temp/'.$name.'.zip', DT_ROOT.'/file/temp/'.$name.'/');
			file_del(DT_ROOT.'/file/temp/'.$name.'.zip');
			$F = get_file(DT_ROOT.'/file/temp/'.$name);
			if($F) {
				$htm = '';
				$max = 0;
				foreach($F as $f) {
					$ext = file_ext($f);
					if(in_array($ext, array('htm', 'html'))) {
						$tmp = filesize($f);
						if($tmp > $max) $htm = str_replace(DT_ROOT.'/file/temp/', '', $f);
						$max = $tmp;
					} else {
						in_array($ext, array('jpg', 'jpeg', 'gif', 'png', 'bmp')) or file_del($f);
					}
				}
				if($htm) {
					dalert('', '', 'parent.Upsuccess("'.$htm.'");');
				} else {
					dalert('系统未在压缩包内找到HTM文件');
				}
			} else {
				dalert('解压缩失败，请检查目录权限');
			}
		} else {
			dalert($upload->errmsg);
		}
	break;
	case 'read':
		if($word && in_array(file_ext($word), array('htm', 'html'))) {
			$data = file_get(DT_ROOT.'/file/temp/'.$word);
			if($data) {
				if($charset) $data = convert($data, $charset, DT_CHARSET);
				if(preg_match("/<body[^>]*>([\s\S]+)<\/body>/i", $data, $m)) $data = trim($m[1]);
				$data = str_replace('<![if !vml]>', '', $data);
				$data = str_replace('<![endif]>', '', $data);
				$data = str_replace('<o:p>', '', $data);
				$data = str_replace('</o:p>', '', $data);
				$data = preg_replace("/ v:shapes=\"[^\"]*\">/i", ">", $data);
				if($wd_class) {
					$data = preg_replace("/[\s]?class=[\'|\"]?[^>]*[\'|\"]?/i", '', $data);
				}
				if($wd_style) {
					$data = preg_replace("/[\s]?style=[\'|\"]?[^>]*[\'|\"]?/i", '', $data);
				}
				if($wd_span) {
					$data = preg_replace("/<span[^>]*>/i", "", $data);
					$data = preg_replace("/<\/span>/i", "", $data);
				}
				if($wd_note) {
					$data = preg_replace("/<!--[\s\S]*-->/isU", '', $data);
				}
				if($wd_nr) {
					$data = str_replace('<p></p>', '', $data);
					$data = str_replace('<p> </p>', '', $data);
					$data = str_replace('<p>&nbsp;</p>', '', $data);
					$data = str_replace('<p>&nbsp;</p>', '', $data);
					$data = preg_replace('/($\s*$)|(^\s*^)/m', '', $data);
				}
				exit($data);
			}
		}
		exit('');
	break;
	case 'run':
		$code = stripslashes($code);
		$base = DT_PATH.'file/temp/'.dirname($temp).'/';
		$code = str_replace('src="', 'src="'.$base, $code);
		echo '<html>';
		echo '<meta http-equiv="Content-Type" content="text/html;charset='.DT_CHARSET.'"/>';
		echo '<body>';
		echo $code;
		echo '</body>';
		echo '</html>';
	break;
	default:
		if($submit) {
			if(!isset($water)) $DT['water_type'] = 0;
			$content = stripslashes($content);
			$dir = dirname($word);
			$base = DT_PATH.'file/temp/'.$dir.'/';
			$content = str_replace('src="', 'src="'.$base, $content);
			$content = save_remote($content, 'jpg|jpeg|gif|png', 1);
			$tmp = explode('/', $dir);
			dir_delete(DT_ROOT.'/file/temp/'.$tmp[0]);
		}
		include tpl('word');
	break;
}
?>