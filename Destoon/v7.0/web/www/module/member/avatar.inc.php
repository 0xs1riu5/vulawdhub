<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
$avatar = useravatar($_userid, 'large', 0, 2);
switch($action) {
	case 'update':
		$t = $avatar ? 1 : 0;
		$db->query("UPDATE {$DT_PRE}member SET avatar=$t WHERE userid=$_userid");
		dheader('?reload='.$DT_TIME);
	break;
	case 'upload':
		if(!$_FILES['file']['size']) {
			if($DT_PC) dheader('?action=html&reload='.$DT_TIME);
			exit('{"error":1,"message":"Error FILE"}');
		}
		require DT_ROOT.'/include/upload.class.php';
		$ext = file_ext($_FILES['file']['name']);
		$name = 'avatar'.$_userid.'.'.$ext;
		$file = DT_ROOT.'/file/temp/'.$name;
		if(is_file($file)) file_del($file);
		$upload = new upload($_FILES, 'file/temp/', $name, 'jpg|jpeg|gif|png');
		$upload->adduserid = false;
		if($upload->save()) {
			$file = DT_ROOT.'/file/temp/'.$name;
			$img_info = @getimagesize($file);
			$upload_mime = array('jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'gif' => 'image/gif', 'png' => 'image/png');
			if(!$img_info || $img_info['mime'] != $upload_mime[$ext] || $img_info[0] < 128 || $img_info[1] < 128) file_del($file);
			if($DT_PC) {
				$img_info or message($L['avatar_img_t'], '?action=html&reload='.$DT_TIME);
				$img_info['mime'] == $upload_mime[$ext] or message($L['avatar_img_t'], '?action=html&reload='.$DT_TIME);
				$img_info[0] >= 128 or message($L['avatar_img_w'], '?action=html&reload='.$DT_TIME);
				$img_info[1] >= 128 or message($L['avatar_img_h'], '?action=html&reload='.$DT_TIME);
			} else {				
				$img_info or exit('{"error":1,"message":"'.$L['avatar_img_t'].'"}');
				$img_info['mime'] == $upload_mime[$ext] or exit('{"error":1,"message":"'.$L['avatar_img_t'].'"}');
				$img_info[0] >= 128 or exit('{"error":1,"message":"'.$L['avatar_img_w'].'"}');
				$img_info[1] >= 128 or exit('{"error":1,"message":"'.$L['avatar_img_h'].'"}');
			}
			$ani = ($ext == 'gif' && strpos(file_get($file), chr(0x21).chr(0xff).chr(0x0b).'NETSCAPE2.0') !== false && $_FILES['file']['size'] < 200*1024) ? 1 : 0;
			$md5 = md5($_userid);
			$dir = DT_ROOT.'/file/avatar/'.substr($md5, 0, 2).'/'.substr($md5, 2, 2).'/'.$_userid;
			$img = array();
			$img[1] = $dir.'.jpg';
			$img[2] = $dir.'x48.jpg';
			$img[3] = $dir.'x20.jpg';
			$md5 = md5($_username);
			$dir = DT_ROOT.'/file/avatar/'.substr($md5, 0, 2).'/'.substr($md5, 2, 2).'/_'.$_username;
			$img[4] = $dir.'.jpg';
			$img[5] = $dir.'x48.jpg';
			$img[6] = $dir.'x20.jpg';
			require DT_ROOT.'/include/image.class.php';
			if(!$ani) {
				$image = new image($file);
				$image->thumb(128, 128);
			}
			file_copy($file, $img[1]);
			file_copy($file, $img[4]);
			if(!$ani) {
				$image = new image($file);
				$image->thumb(48, 48);
			}
			file_copy($file, $img[2]);
			file_copy($file, $img[5]);
			if(!$ani) {
				$image = new image($file);
				$image->thumb(20, 20);
			}
			file_copy($file, $img[3]);
			file_copy($file, $img[6]);
			file_del($file);
			if($DT['ftp_remote'] && $DT['remote_url']) {
				require DT_ROOT.'/include/ftp.class.php';
				$ftp = new dftp($DT['ftp_host'], $DT['ftp_user'], $DT['ftp_pass'], $DT['ftp_port'], $DT['ftp_path'], $DT['ftp_pasv'], $DT['ftp_ssl']);
				if($ftp->connected) {
					foreach($img as $i) {
						$t = explode("/file/", $i);
						$ftp->dftp_put('file/'.$t[1], $t[1]);
					}
				}
			}
			if($DT_PC) dheader('?action=html&reload='.$DT_TIME);
			exit('{"error":0}');
		} else {
			if($DT_PC) message($upload->errmsg);
			exit('{"error":1,"message":"'.$upload->errmsg.'"}');
		}
	break;
	case 'delete':
		if($avatar) {
			$img = array();
			$img[1] = useravatar($_userid, 'large', 0, 2);
			$img[2] = useravatar($_userid, '', 0, 2);
			$img[3] = useravatar($_userid, 'small', 0, 2);
			$img[4] = useravatar($_username, 'large', 1, 2);
			$img[5] = useravatar($_username, '', 1, 2);
			$img[6] = useravatar($_username, 'small', 1, 2);
			foreach($img as $i) {
				file_del($i);
			}
			if($DT['ftp_remote'] && $DT['remote_url']) {
				require DT_ROOT.'/include/ftp.class.php';
				$ftp = new dftp($DT['ftp_host'], $DT['ftp_user'], $DT['ftp_pass'], $DT['ftp_port'], $DT['ftp_path'], $DT['ftp_pasv'], $DT['ftp_ssl']);
				if($ftp->connected) {
					foreach($img as $i) {
						$t = explode("/file/", $i);
						$ftp->dftp_delete($t[1]);
					}
				}
			}
		}
		$db->query("UPDATE {$DT_PRE}member SET avatar=0 WHERE userid=$_userid");
		dmsg($L['avatar_delete'], '?'.(isset($html) ? 'action=html&' : '').'reload='.$DT_TIME);
	break;
	default:
		$auth = encrypt($_userid.'|'.$_username, DT_KEY.'AVATAR');
		$head_title = $L['avatar_title'];	
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
	$back_link = DT_MOB.'my.php';
}
include template('avatar', $module);
?>