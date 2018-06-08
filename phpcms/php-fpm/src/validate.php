<?php
session_start();
header("Content-type: image/png");
$words="2,3,4,5,6,7,8,9";
$words_arr=explode(',',$words);
$words_count=count($words_arr);
$key1='';
for($i=0;$i<4;$i++){
	$key1=$key1.$words_arr[rand(0, $words_count-1)];
}
$_SESSION['cfmcode'] = $key1;
$string = $_SESSION['cfmcode'];
$im     = imagecreatefromgif("images/key.gif");
$orange = imagecolorallocate($im, 200, 200, 200);
$px     = (imagesx($im) - 1.5 * strlen($string)) /3;
imagestring($im, 5, $px, 2, $string, $orange);
imagepng($im);
imagedestroy($im);
?>