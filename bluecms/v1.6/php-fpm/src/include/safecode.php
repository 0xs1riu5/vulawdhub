<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：safecode.php
 * $author：lucks
 */
session_start();
function get_rand_str($length)
{
	$strings = Array('3','4','5','6','7','a','b','c','d','e','f','h','i','j','k','m','n','p','r','s','t','u','v','w','x','y');
	$rand_str = "";
	$count = count($strings);
	for ($i = 1; $i <= $length; $i++) {				
		$rand_str .= $strings[rand(0,$count-1)];
	}
	return $rand_str;
}
$fontSize = 13;													
$length = 4;										
$rand_str = get_rand_str($length);			

$_SESSION['safecode'] = $rand_str;
$width = 60;
$height = 24;
$im = imagecreate($width,$height);								
$backgroundcolor = imagecolorallocate ($im, 255, 255, 255);			
$frameColor = imageColorAllocate($im, 150, 150, 150);				
$font = realpath("arial.ttf");				
for($i = 0; $i < $length; $i++) {
	$charY = ($height+10)/2 + rand(-1,1);	
	$charX = $i*13+8;									
													
	$text_color = imagecolorallocate($im, mt_rand(50, 200), mt_rand(50, 128), mt_rand(50, 200));
	$angle = rand(-20,20);							
									
	imagettftext($im, $fontSize, $angle, $charX,  $charY, $text_color, $font, $rand_str[$i]);
}
for($i=0; $i <= 5; $i++) {							
	$linecolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
	$linex = mt_rand(1, $width-1);
	$liney = mt_rand(1, $height-1);
	imageline($im, $linex, $liney, $linex + mt_rand(0, 4) - 2, $liney + mt_rand(0, 4) - 2, $linecolor);
}
for($i=0; $i <= 32; $i++) {							
	$pointcolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
	imagesetpixel($im, mt_rand(1, $width-1), mt_rand(1, $height-1), $pointcolor);
}
imagerectangle($im, 0, 0, $width-1 , $height-1 , $frameColor);		
ob_clean();
@header('Content-type: image/png');
imagepng($im);
imagedestroy($im);
exit;
?>