<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
class captcha {
	var $chars = 'abcdeghkmnpqstwxyz234789ABCEFGHJKLMNPRSTWXYZ';
	var $length = 4;
	var $soundtag;
	var $soundstr;
	var $cn;
	var $font;

	function question($id) {
		$r = DB::get_one("SELECT * FROM ".DT_PRE."question ORDER BY rand()");
		$_SESSION['answerstr'] = encrypt($r['answer'], DT_KEY.'ANS');
		exit('document.getElementById("'.$id.'").innerHTML = "'.$r['question'].'";');
	}

	function image() {
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		} else {
			header('Pragma: no-cache');
		}
		header('Expires: '.gmdate('D, d M Y H:i:s').' GMT');
		header("Content-type: image/png");	
		$string = $this->mk_str();
		$_SESSION['captchastr'] = encrypt(strtoupper($string), DT_KEY.'CPC');
		$imageX = $this->length*26;
		$imageY = 32;
		$im = imagecreatetruecolor($imageX, $imageY);  
		imagefill($im, 0, 0, imagecolorallocate($im, 250, 250, 250));
		$color = imagecolorallocate($im, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));
		if($this->cn) {
			$string = $string;
			$angle = mt_rand(-15, 15);
			$size = mt_rand(12, 22);
			$font = $this->font;
			$X = $size + mt_rand(5, 10);
			$Y = $size + mt_rand(5, 10);
			imagettftext($im, $size, $angle, $X, $Y, $color, $font, $string);
			$this->mk_sin($im, $color);
			imagepng($im);
			imagedestroy($im);
		} else {
			$fonts = glob(DT_ROOT.'/file/captcha/*.ttf');
			$num = count($fonts) - 1;
			$font = $fonts[mt_rand(0, $num)];
			$C0 = mt_rand(200, 255);
			$C1 = mt_rand(200, 255);
			$C2 = mt_rand(200, 255);
			$BG = imagecolorallocate($im, $C0, $C1, $C2);
			imagefill($im, 0, 0, $BG);
			$X = 0;
			for($i = 0; $i < $this->length; $i++) {
				$size = mt_rand(20, 25);
				$angle = mt_rand(-3, 3);
				if($i > 0) $X += $size - mt_rand(3, 5);
				$Y = $size + mt_rand(-2, 2);
				imagettftext($im, $size, $angle, $X, $Y, $color, $font, $string{$i});
			}
			$IM = imagecreatetruecolor($imageX, $imageY);
			imagefill($IM, 0, 0, $BG);				
			for($i = 0; $i < $imageX; $i++) {
				for($j = 0; $j < $imageY; $j++) {
					$C = imagecolorat($im, $i, $j);
					if(($i+20+sin($j/$imageY*2*M_PI)*6) <= $imageX && ($i+20+sin($j/$imageY*2*M_PI)*6) >=0 ) {
						imagesetpixel($IM, $i+10+sin($j/$imageY*2*M_PI-M_PI*0.1)*0.8, $j, $C);
					}
				}
			}
			$this->mk_sin($IM, $color);
			imagepng($IM);
			imagedestroy($IM);
			imagedestroy($im);
		}
		exit;
	}

	function mk_sin($im, $color) {
		$R = mt_rand(5, 20);
		$X = mt_rand(15, 25);
		$Y = mt_rand(5, 10);
		$L = mt_rand(50, 80);
		for($yy = $R; $yy <= $R + 1; $yy++) {
			for($px = -$L; $px <= $L; $px = $px + 0.1) {
				$x = $px/$X;
				if($x != 0) $y = sin($x);
				$py = $y*$Y;
				imagesetpixel($im, $px + $L, $py + $yy, $color);
			}
		}
	}

	function mk_str() {
		$str = '';
		if($this->cn) {
			$step = DT_CHARSET == 'UTF-8' ? 3 : 2;
			$text = substr(file_get(DT_ROOT.'/file/config/cncaptcha.inc.php'), 13);
			$max = strlen($text) - 1 - $step;
			while(1) {
				$i = mt_rand(0, $max);
				if($i%$step == 0) {
					$str .= substr($text, $i, $step);
					break;
				}
			}
			while(1) {
				$i = mt_rand(0, $max);
				if($i%$step == 0) {
					$str .= substr($text, $i, $step);
					break;
				}
			}
		} else {
			$max = strlen($this->chars) - 1;
			while(1) {
				if(strlen($str) == $this->length) break;
				$r = mt_rand(0, $max);
				if(strpos(strtolower($str), strtolower($this->chars{$r})) === false) $str .= $this->chars{$r};
			}
		}
		return $str;
	}
}
?>