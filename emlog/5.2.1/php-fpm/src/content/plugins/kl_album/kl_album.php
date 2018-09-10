<?php
/*
Plugin Name: EM相册
Version: 3.0.1
Plugin URL: http://kller.cn/?post=33
Description: 一款优秀的本地相册插件，并且支持将照片嵌入到日志内容。
Author: KLLER
Author Email: kller@foxmail.com
Author URL: http://kller.cn
*/
!defined('EMLOG_ROOT') && exit('access deined!');
function kl_album(){
	echo '<div class="sidebarsubmenu" id="kl_album"><a href="./plugin.php?plugin=kl_album&kl_album_action=display">EM相册</a></div>';
}
addAction('adm_sidebar_ext', 'kl_album');

function kl_album_to_backup(){
	global $tables;
	$DB = MySql::getInstance();
	$is_exist_album_query = $DB->query('show tables like "'.DB_PREFIX.'kl_album"');
	if($DB->num_rows($is_exist_album_query) != 0) array_push($tables, 'kl_album');
}
addAction('data_prebakup', 'kl_album_to_backup');

function klUploadFile($filename, $errorNum, $tmpfile, $filesize, $filetype, $type, $isIcon = 0){
	$kl_album_config = unserialize(Option::get('kl_album_config'));
	$extension  = strtolower(substr(strrchr($filename, "."),1));
	$uppath = KL_UPLOADFILE_PATH . date("Ym") . "/";
	$fname = md5($filename) . date("YmdHis") . rand() .'.'. $extension;
	$attachpath = $uppath . $fname;
	if(!is_dir(KL_UPLOADFILE_PATH)){
		umask(0);
		$ret = @mkdir(KL_UPLOADFILE_PATH, 0777);
		if($ret === false) return '创建文件上传目录失败';
	}
	if(!is_dir($uppath)){
		umask(0);
		$ret = @mkdir($uppath, 0777);
		if($ret === false) return "上传失败。文件上传目录(content/plugins/kl_album/upload)不可写";
	}
	doAction('kl_album_upload', $tmpfile);
	//缩略
	$imtype = array('jpg','png','jpeg','gif');
	$thum = $uppath."thum-". $fname;
	$attach = in_array($extension, $imtype) && function_exists("ImageCreate") && klResizeImage($tmpfile,$filetype,$thum,$isIcon,KL_IMG_ATT_MAX_W,KL_IMG_ATT_MAX_H) ? $thum : $attachpath;
	$kl_album_compression_length = isset($kl_album_config['compression_length']) ? intval($kl_album_config['compression_length']) : 1024;
	$kl_album_compression_width = isset($kl_album_config['compression_width']) ? intval($kl_album_config['compression_width']) : 768;
	if($kl_album_compression_length == 0 || $kl_album_compression_width == 0){
		if(@is_uploaded_file($tmpfile)){
			if(@!move_uploaded_file($tmpfile ,$attachpath)){
				@unlink($tmpfile);
				return "上传失败。文件上传目录(content/plugins/kl_album/upload)不可写";
			}else{
				echo 'kl_album_successed';
			}
			chmod($attachpath, 0777);
		}
	}else{
		if(in_array($extension, $imtype) && function_exists("ImageCreate") && klResizeImage($tmpfile,$filetype,$attachpath,$isIcon,$kl_album_compression_length,$kl_album_compression_width)){
			echo 'kl_album_successed';
		}else{
			if(@is_uploaded_file($tmpfile)){
				if(@!move_uploaded_file($tmpfile ,$attachpath)){
					@unlink($tmpfile);
					return "上传失败。文件上传目录(content/plugins/kl_album/upload)不可写";
				}else{
					echo 'kl_album_successed';
				}
				chmod($attachpath, 0777);
			}
		}
	}
	$attach = substr($attach, 6, strlen($attach));
	return 	$attach;
}

function klResizeImage($img,$imgtype,$name,$isIcon,$kl_img_att_max_w,$kl_img_att_max_h){
	$max_w = $isIcon ? ICON_MAX_W : $kl_img_att_max_w;
	$max_h = $isIcon ? ICON_MAX_H : $kl_img_att_max_h;
	$size = chImageSize($img,$max_w,$max_h);
	$size_bak = @getimagesize($img);
	$imgtype = $size_bak['mime'];
	$newwidth = $size['w'];
	$newheight = $size['h'];
	$w =$size['rc_w'];
	$h = $size['rc_h'];
	if($w <= $max_w && $h <= $max_h) return false;
	if(($imgtype == "image/pjpeg" || $imgtype == "image/jpeg") && function_exists("imagecreatefromjpeg")) $img = imagecreatefromjpeg($img);
	if(($imgtype == "image/x-png" || $imgtype == "image/png") && function_exists("imagecreatefrompng")) $img = imagecreatefrompng($img);
	if($imgtype == "image/gif" && function_exists("imagecreatefromgif")) $img = imagecreatefromgif($img);
	if(!isset($img)) return false;
	if(function_exists("imagecopyresampled")){
		$newim = imagecreatetruecolor($newwidth, $newheight);
		imagecopyresampled($newim, $img, 0, 0, 0, 0, $newwidth, $newheight, $w, $h);
	}else{
		$newim = imagecreate($newwidth, $newheight);
		imagecopyresized($newim, $img, 0, 0, 0, 0, $newwidth, $newheight, $w, $h);
	}
	if(($imgtype == "image/pjpeg" || $imgtype == "image/jpeg") && !imagejpeg($newim,$name)) return false;
	if(($imgtype == "image/x-png" || $imgtype == "image/png") && !imagepng($newim,$name)) return false;
	if(($imgtype == "image/gif") && !imagegif($newim,$name)) return false;
	ImageDestroy ($newim);
	return true;
}

function kl_album_authPassword($postPwd, $cookiePwd, $albumPwd, $albumid, $url, $cookie_prefix)
{
	$pwd = $cookiePwd ? $cookiePwd : $postPwd;
	if($pwd !== addslashes($albumPwd))
	{
		echo <<<EOT
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>emlog message</title>
<style type="text/css">
<!--
body{background-color:#F7F7F7;font-family: Arial;font-size: 12px;line-height:150%;}
.main{background-color:#FFFFFF;margin-top:20px;font-size: 12px;color: #666666;width:580px;margin:10px 200px;padding:10px;list-style:none;border:#DFDFDF 1px solid;}
-->
</style>
</head>
<body>
<div class="main">
<form action="" method="post">
请输入该相册的访问密码<br>
<input type="password" name="albumpwd" /><input type="submit" value="进入.." />
<br /><br /><a href="$url">&laquo;返回相册列表页面</a>
</form>
</div>
</body>
</html>
EOT;
		if($cookiePwd)
		{
			setcookie($cookie_prefix.$albumid, ' ', time() - 31536000);
		}
		exit;
}else {
	setcookie($cookie_prefix.$albumid, $albumPwd);
}
}

function kl_album_get_upload_max_filesize(){
	$upload_max_filesize = 2097152;//附件大小上限 单位：字节（默认2M）
	if(function_exists('ini_get')){
		$upload_max_filesize = ini_get('upload_max_filesize');
		$upload_max_filesize = intval(substr($upload_max_filesize, 0, strlen($upload_max_filesize)-1));
		$post_max_size = ini_get('post_max_size');
		$post_max_size = intval(substr($post_max_size, 0, strlen($post_max_size)-1));
		$upload_max_filesize = $upload_max_filesize < $post_max_size ? $upload_max_filesize * 1048576 : $post_max_size * 1048576;
	}
	return $upload_max_filesize;
}

function kl_album_log_photo()
{
	$DB = MySql::getInstance();
	$is_exist_album_query = $DB->query('show tables like "'.DB_PREFIX.'kl_album"');
	if($DB->num_rows($is_exist_album_query) == 0) return;
	echo '　<a href="javascript: displayToggle(\'kl_album_log_photo\', 0);" class="thickbox">插入相片</a>';
	echo '<div id="kl_album_log_photo" style="display: none;"><iframe width="720" height="160" frameborder="0" src="../content/plugins/kl_album/kl_album_log_photo_lib.php"></iframe></div>';
}
addAction('adm_writelog_head', 'kl_album_log_photo');
?>