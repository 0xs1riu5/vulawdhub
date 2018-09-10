<?php
/**
 *插入相片到日志
 *design by 奇遇
*/
require_once('../../../init.php');
$DB = MySql::getInstance();
ISLOGIN == FALSE && exit('error!');
$album = isset($_GET['album']) ? intval($_GET['album']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr" lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>EM相册</title>
<style type="text/css">
@charset "utf-8"; 
body{font-size:12px; color:#000; line-height:1.6; font-family:arial,helvetica,sans-serif; padding:0; margin:0;}
a{color:#999; text-decoration:none;outline:none; }
a:hover{color:#333; text-decoration:none}
img{border:0}
#media-upload-header{background-color:#E4F2FD; border-top:solid #C6D9E9 1px; margin-top:8px; height:20px;}
#media-upload-header span{margin:0px 5px 0px;padding:1px 5px; list-style-type:none; display:block; float:left; zoom:1; height:17px;}
#curtab{background-color:#FFF; border-left:1px solid #C6D9E9; border-right:1px solid #C6D9E9; border-top:1px solid #C6D9E9}
#attlist{float:left; border:1px solid #CCC; list-style:none; text-align:center; margin:5px; padding:3px}
#attmsg{margin:40px 0px 0px 250px}
#media-upload-body{margin:0px 200px 0px 0px}
</style>
</head>
<script type="text/javascript">
function addPhoto(url,width,height)
{
	if (parent.KE.g['content'].wyswygMode == false){
		alert('请先切换到所见所得模式');
	}else {
		parent.KE.insertHtml('content','<a target=\"_blank\" href=\"'+url+'\"><img src=\"'+url+'\" width=\"'+width+'\" height=\"'+height+'\" alt=\"点击查看原图\" border=\"0\"></a>');
	}
}
</script>
<body>
<?php
	$DB = MySql::getInstance();
	$blogurl = Option::get('blogurl');
	$log_photo_content = "";
	$log_photo_info = Option::get('kl_album_info');
	if(!is_null($log_photo_info))
	{
		$log_photo_info = unserialize($log_photo_info);
		krsort($log_photo_info);
?>
<div id="media-upload-header">
	<span><?php echo $album ? '<a href="./kl_album_log_photo_lib.php">返回相册列表</a>' : '相册列表';?></span>
</div>
<?php
//显示相册列表
if(!$album)
{
	foreach ($log_photo_info as $value)
	{
		if(ROLE != 'admin' && $value['restrict'] == 'private')
		{
			continue;
		}
		if(isset($value['head']))
		{
			$log_photo_query = $DB->query("SELECT * FROM ".DB_PREFIX."kl_album WHERE id={$value['head']}");
			if($log_photo_row = $DB->fetch_row($log_photo_query))
			{
				$log_photo_cover = substr($log_photo_row[2], strpos($log_photo_row[2], 'upload/'), strlen($log_photo_row[2])-strpos($log_photo_row[2], 'upload/'));
			}else{
				$log_photo_cover = 'images/no_cover_s.jpg';
			}
		}else{
			$log_photo_query = $DB->query("SELECT * FROM ".DB_PREFIX."kl_album WHERE album={$value['addtime']}");
			if($log_photo_row = $DB->fetch_array($log_photo_query))
			{
				$log_photo_cover = substr($log_photo_row['filename'], strpos($log_photo_row['filename'], 'upload/'), strlen($log_photo_row['filename'])-strpos($log_photo_row['filename'], 'upload/'));
			}else{
				$log_photo_cover = 'images/no_cover_s.jpg';
			}
		}
		$log_photo_content .= '
		<li id="attlist">
		<a href="./kl_album_log_photo_lib.php?album='.$value['addtime'].'" title="'.$value['name'].'">
		<img src="../kl_album/'.$log_photo_cover.'" width="60" height="60"></a>
		</li>';
	}
}
//显示单个相册里的照片
if($album)
{
	foreach ($log_photo_info as $value)
	{
		$albumpwd = isset($value['pwd']) ? $value['pwd'] : '';
		if($value['addtime'] == $album)
		{
			if($value['restrict'] == 'private' && ROLE != 'admin')
			{
				$log_photo_content .= '该相册仅主人可见';
			}else{
				if($value['restrict'] == 'protect' && ROLE != 'admin'){
					$postpwd = isset($_POST['albumpwd']) ? addslashes(trim($_POST['albumpwd'])) : '';
					$cookiepwd = isset($_COOKIE['kl_album_log_photopwd_'.$album]) ? addslashes(trim($_COOKIE['kl_album_log_photopwd_'.$album])) : '';
					kl_album_authPassword($postpwd, $cookiepwd, $albumpwd, $album, './kl_album_log_photo_lib.php', 'kl_album_log_photopwd_');
				}
				$kl_album = Option::get('kl_album_'.$album);
				if(is_null($kl_album)){
					$condition = " and album={$album} order by id desc";
				}else{
					$idStr = empty($kl_album) ? 0 : $kl_album;
					$condition = " and id in({$idStr}) order by substring_index('{$idStr}', id, 1)";
				}
				$log_photo_query = $DB->query("SELECT * FROM ".DB_PREFIX."kl_album WHERE 1 {$condition}");
				$kl_album_config = unserialize(Option::get('kl_album_config'));
				$kl_album_log_photo_length = isset($kl_album_config['log_photo_length']) ? intval($kl_album_config['log_photo_length']) : 480;
				$kl_album_log_photo_width = isset($kl_album_config['log_photo_width']) ? intval($kl_album_config['log_photo_width']) : 360;
				if($kl_album_log_photo_length == 0 || $kl_album_log_photo_width == 0) $kl_album_log_photo_length = $kl_album_log_photo_width = 10000;
				while($log_photo = $DB->fetch_array($log_photo_query))
				{
					$log_photo_url = $blogurl.substr($log_photo['filename'],3);
					$log_photo_size = chImageSize(EMLOG_ROOT.str_replace('thum-', '',substr($log_photo['filename'],2)),$kl_album_log_photo_length,$kl_album_log_photo_width);
					$log_photo_content .= '
					<li id="attlist">
					<a href="'.str_replace('thum-', '',$log_photo_url).'" title="'.$log_photo['truename'].'" target="_blank">
					<img src="'.$log_photo_url.'" width="60" height="60"></a>
					<br /><a href="javascript: addPhoto(\''.str_replace('thum-', '',$log_photo_url).'\',\''.$log_photo_size['w'].'\',\''.$log_photo_size['h'].'\');">嵌入</a>
					</li>';
				}
			}
			break;
		}
	}
}
?>
<div id="media-upload-body">
<?php echo $log_photo_content; ?>
</div>
<?php
	}else{
?>
<br /><br /><br />
<font color="red"><center>还没有创建相册！</center></font>
<?php
	}
?>
</body>
</html>