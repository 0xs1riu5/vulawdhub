<?php
require '../common.inc.php';
check_referer() or exit;
if($DT_BOT) dhttp(403);
isset($auth) or $auth = '';
if($auth) {
	$str = decrypt($auth, DT_KEY.'SPAM');
	if(preg_match("/^[a-z0-9_@\-\s\/\.\,\(\)\+]{5,}$/i", $str)) {
		header("content-type:image/png");
		$imageX = strlen($str)*9;
		$imageY = 20;
		$im = @imagecreate($imageX, $imageY) or exit();
		imagecolorallocate($im, 255, 255, 255);
		$color = imagecolorallocate($im, 0, 0, 0);
		imagestring($im, 5, 0, 5, $str, $color);
		imagepng($im);
		imagedestroy($im);
	}
}
?>