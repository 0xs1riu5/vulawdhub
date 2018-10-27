<?php
$_SERVER['REQUEST_URI'] = '';
$_DPOST = $_POST;
include '../../common.inc.php';
$auth = isset($auth) ? trim($auth) : '';
list($_userid, $_username) = explode('|', decrypt($auth, DT_KEY.'AVATAR'));
$_userid = intval($_userid);
$_userid or exit('{"status":0}');
$user = $db->get_one("SELECT username FROM {$DT_PRE}member WHERE userid=$_userid");
$user or exit('{"status":0}');
$user['username'] == $_username or exit('{"status":0}');
$pic1 = $_DPOST['pic1'];
$pic2 = $_DPOST['pic2'];
$pic3 = $_DPOST['pic3'];
$pic1 or exit('{"status":0}');
$pic2 or exit('{"status":0}');
$pic3 or exit('{"status":0}');
if(md5($pic3) == '68777643ca2d4cecce869c2d4cc9f4fd') exit('{"status":2}');
$md5 = md5($_userid);
$dir = DT_ROOT.'/file/avatar/'.substr($md5, 0, 2).'/'.substr($md5, 2, 2).'/'.$_userid;
$img = array();
$img[1] = $dir.'.jpg';
$img[2] = $dir.'x48.jpg';
$img[3] = $dir.'x20.jpg';
file_put($img[1], base64_decode($pic1));
file_put($img[2], base64_decode($pic2));
file_put($img[3], base64_decode($pic3));
$s1 = getimagesize($img[1]);
$w1 = $s1[0];
$h1 = $s1[1];
$s2 = getimagesize($img[2]);
$w2 = $s2[0];
$h2 = $s2[1];
$s3 = getimagesize($img[3]);
$w3 = $s3[0];
$h3 = $s3[1];
if($s1 && $s2 && $s3 && $w1 == 128 && $h1 == 128 && $w2 == 48 && $h2 == 48 && $w3 == 20 && $h3 == 20) {
	$md5 = md5($user['username']);
	$dir = DT_ROOT.'/file/avatar/'.substr($md5, 0, 2).'/'.substr($md5, 2, 2).'/_'.$user['username'];
	$img[4] = $dir.'.jpg';
	$img[5] = $dir.'x48.jpg';
	$img[6] = $dir.'x20.jpg';
	file_put($img[4], base64_decode($pic1));
	file_put($img[5], base64_decode($pic2));
	file_put($img[6], base64_decode($pic3));
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
	echo '{"status":1}';
} else {
	file_del($img[1]);
	file_del($img[2]);
	file_del($img[3]);
	exit('{"status":3}');
}
?>