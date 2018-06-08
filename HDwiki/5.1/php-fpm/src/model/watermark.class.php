<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class watermarkmodel {
	
	var $base;
	
	var $srcfile = '';
	var $targetfile = '';
	var $imagecreatefromfunc = '';
	var $imagefunc = '';
	var $attachinfo = '';
	var $attach = array();
	var $animatedgif = 0;
	
	var $imagelib;//选用哪个图片处理库
	var $imageimpath;//如果采用im，记录im的位置
	var $watermarkstatus;//水印位置或是否开启
	var $watermarkminwidth;//水印图片最小宽度
	var $watermarkminheight;//水印图片最小高度
	var $watermarktype;//水印类型
	var $watermarktrans;//水印融合度,透明度
	var $watermarkquality;//水印质量，针对jpeg类型。
	var $watermarktext;//文本类型水印参数数组
	
	function watermarkmodel(&$base) {
		$this->base = $base;
	}
	
	function image($srcfile, $targetfile,$settingnew='') {
		$watermarks=is_array($settingnew)?$settingnew:unserialize($this->base->setting['watermark']);
		
		foreach($watermarks as $key=>$value){
			$this->$key=$value;
		}
		if($this->watermarktype==2 && WIKI_CHARSET=='GBK'){
			$this->watermarktext['text']=string::hiconv($this->watermarktext['text'],'utf-8','gbk');
		}
		if($this->watermarkstatus =='0'){
			return false;
		}
		$this->imageimpath=$this->formaturl(urldecode($this->imageimpath));
		$this->watermarktext['fontpath']='./style/default/'.$this->watermarktext['fontpath'];
		$this->srcfile = strpos($srcfile,HDWIKI_ROOT)!==false?realpath(HDWIKI_ROOT.$srcfile):$srcfile;
		$this->targetfile = strpos($targetfile,HDWIKI_ROOT)!==false?realpath(HDWIKI_ROOT.$targetfile):$targetfile;
		$this->attachinfo = @getimagesize($this->srcfile);
		$this->attach['size'] = @filesize($this->srcfile);
		if(!$this->imagelib || !$this->imageimpath) {//gd库
			switch($this->attachinfo['mime']) {
				case 'image/jpeg':
					$this->imagecreatefromfunc = function_exists('imagecreatefromjpeg') ? 'imagecreatefromjpeg' : '';
					$this->imagefunc = function_exists('imagejpeg') ? 'imagejpeg' : '';
					break;
				case 'image/gif':
					$this->imagecreatefromfunc = function_exists('imagecreatefromgif') ? 'imagecreatefromgif' : '';
					$this->imagefunc = function_exists('imagegif') ? 'imagegif' : '';
					break;
				case 'image/png':
					$this->imagecreatefromfunc = function_exists('imagecreatefrompng') ? 'imagecreatefrompng' : '';
					$this->imagefunc = function_exists('imagepng') ? 'imagepng' : '';
					break;
			}
		} else {//imagemagick
			$this->imagecreatefromfunc = $this->imagefunc = TRUE;
		}
		
		if($this->attachinfo['mime'] == 'image/gif') {
			if($this->imagecreatefromfunc && !@imagecreatefromgif($srcfile)){//imagemagick并且返回gif图片失败
				return FALSE;
			}
			$fp = fopen($srcfile, 'rb');
			$targetfilecontent = fread($fp, $this->attach['size']);
			fclose($fp);
			$this->animatedgif = strpos($targetfilecontent, 'NETSCAPE2.0') === FALSE ? 0 : 1;//判断是静态还是动态
		}
		if(($this->watermarkminwidth && $this->attachinfo[0] <= $this->watermarkminwidth && $this->watermarkminheight && $this->attachinfo[1] <= $this->watermarkminheight) 
		|| ($this->watermarktype == 2 && (!file_exists($this->watermarktext['fontpath']) || !is_file($this->watermarktext['fontpath'])))) {
			return false;
		}
		return $this->imagelib && $this->imageimpath ? $this->Watermark_IM() : $this->Watermark_GD();
	}
	
	function Watermark_IM() {
		$this->watermarktext['fontpath']=realpath(HDWIKI_ROOT.$this->watermarktext['fontpath']);
		switch($this->watermarkstatus) {
			case 1:
				$gravity = 'NorthWest';
				break;
			case 2:
				$gravity = 'North';
				break;
			case 3:
				$gravity = 'NorthEast';
				break;
			case 4:
				$gravity = 'West';
				break;
			case 5:
				$gravity = 'Center';
				break;
			case 6:
				$gravity = 'East';
				break;
			case 7:
				$gravity = 'SouthWest';
				break;
			case 8:
				$gravity = 'South';
				break;
			case 9:
				$gravity = 'SouthEast';
				break;
		}
		
		if($this->watermarktype < 2) {//水印类型非文本
			$watermark_file = HDWIKI_ROOT.'./style/default/watermark/watermark.' . ($this->watermarktype == 1 ? 'png' : 'gif');
			$exec_str = $this->imageimpath.'/composite'.
				($this->watermarktype != 1 && $this->watermarktrans != '100' ? ' -watermark '.$this->watermarktrans.'%' : '').
				' -quality '.$this->watermarkquality.
				' -gravity '.$gravity.
				' '.$watermark_file.' '.$this->srcfile.' '.$this->targetfile;
		} else {//文本类型
			//文本水印内容
			$watermarktextcvt = str_replace(array("\n", "\r", "'"), array('', '', '\''), $this->watermarktext['text']);
			//文本水印显示角度
			$this->watermarktext['angle'] = -$this->watermarktext['angle'];
			//文本水印偏移量
			$this->watermarktext['translatex']=$this->watermarktext['translatex']?$this->watermarktext['translatex']:0;
			$this->watermarktext['translatey']=$this->watermarktext['translatey']?$this->watermarktext['translatey']:0;
			$translate = $this->watermarktext['translatex'] || $this->watermarktext['translatey'] ? ' translate '.$this->watermarktext['translatex'].','.$this->watermarktext['translatey'] : '';
			//文本水印横向倾斜角度
			$skewX = $this->watermarktext['skewx'] ? ' skewX '.$this->watermarktext['skewx'] : '';
			//文本水印纵向倾斜角度
			$skewY = $this->watermarktext['skewy'] ? ' skewY '.$this->watermarktext['skewy'] : '';
			//执行代码
			$exec_str = $this->imageimpath.'/convert'.
				' -quality '.$this->watermarkquality.
				' -font "'.$this->watermarktext['fontpath'].'"'.
				' -pointsize '.$this->watermarktext['size'].
				(($this->watermarktext['shadowx'] || $this->watermarktext['shadowy']) && $this->watermarktext['shadowcolor'] ?
					' -fill "rgb('.$this->excolor($this->watermarktext['shadowcolor'],2).')"'.
					' -draw "'.
						' gravity '.$gravity.$translate.$skewX.$skewY.
						' rotate '.$this->watermarktext['angle'].
						' text '.$this->watermarktext['shadowx'].','.$this->watermarktext['shadowy'].' \''.$watermarktextcvt.'\'"' : '').
				' -fill "rgb('.$this->excolor($this->watermarktext['color'],2).')"'.
				' -draw "'.
					' gravity '.$gravity.$translate.$skewX.$skewY.
					' rotate '.$this->watermarktext['angle'].
					' text 0,0 \''.$watermarktextcvt.'\'"'.
				' '.$this->srcfile.' '.$this->targetfile;
		}
		@exec($exec_str, $output, $return);
		if(empty($return) && empty($output)) {
			return true;
		}else{
			return false;
		}
	}

	function Watermark_GD() {
		if(function_exists('imagecopy') && function_exists('imagealphablending') && function_exists('imagecopymerge')) {
			$imagecreatefromfunc = $this->imagecreatefromfunc;
			$imagefunc = $this->imagefunc;
			list($img_w, $img_h) = $this->attachinfo;
			if($this->watermarktype < 2) {//非文本
				$watermark_file = HDWIKI_ROOT.'./style/default/watermark/logo.' . ($this->watermarktype == 1 ? 'png' : 'gif');
				$watermarkinfo	= @getimagesize($watermark_file);
				$watermark_logo	= $this->watermarktype == 1 ? @imageCreateFromPNG($watermark_file) : @imageCreateFromGIF($watermark_file);
				if(!$watermark_logo) {
					return;
				}
				list($logo_w, $logo_h) = $watermarkinfo;
			} else {//水印是文本类型
				$watermarktextcvt = $this->watermarktext['text'];
				$box = imagettfbbox($this->watermarktext['size'], $this->watermarktext['angle'], $this->watermarktext['fontpath'], $watermarktextcvt);
				$logo_h = max($box[1], $box[3]) - min($box[5], $box[7]);
				$logo_w = max($box[2], $box[4]) - min($box[0], $box[6]);
				$ax = min($box[0], $box[6]) * -1;
   				$ay = min($box[5], $box[7]) * -1;
			}
			$wmwidth = $img_w - $logo_w;
			$wmheight = $img_h - $logo_h;

			if(($this->watermarktype < 2 && is_readable($watermark_file) || $this->watermarktype == 2) && $wmwidth > 10 && $wmheight > 10 && !$this->animatedgif) {
				switch($this->watermarkstatus) {
					case 1:
						$x = +5;
						$y = +5;
						break;
					case 2:
						$x = ($img_w - $logo_w) / 2;
						$y = +5;
						break;
					case 3:
						$x = $img_w - $logo_w - 5;
						$y = +5;
						break;
					case 4:
						$x = +5;
						$y = ($img_h - $logo_h) / 2;
						break;
					case 5:
						$x = ($img_w - $logo_w) / 2;
						$y = ($img_h - $logo_h) / 2;
						break;
					case 6:
						$x = $img_w - $logo_w -5;
						$y = ($img_h - $logo_h) / 2;
						break;
					case 7:
						$x = +5;
						$y = $img_h - $logo_h - 5;
						break;
					case 8:
						$x = ($img_w - $logo_w) / 2;
						$y = $img_h - $logo_h - 5;
						break;
					case 9:
						$x = $img_w - $logo_w - 5;
						$y = $img_h - $logo_h - 5;
						break;
				}

				$dst_photo = imagecreatetruecolor($img_w, $img_h);
				$target_photo = @$imagecreatefromfunc($this->srcfile);
				imageCopy($dst_photo, $target_photo, 0, 0, 0, 0, $img_w, $img_h);

				if($this->watermarktype == 1) {
					imageCopy($dst_photo, $watermark_logo, $x, $y, 0, 0, $logo_w, $logo_h);
				} elseif($this->watermarktype == 2) {
					if(($this->watermarktext['shadowx'] || $this->watermarktext['shadowy']) && $this->watermarktext['shadowcolor']) {
						$shadowcolorrgb = $this->excolor($this->watermarktext['shadowcolor']);
						$shadowcolor = imagecolorallocate($dst_photo, $shadowcolorrgb[0], $shadowcolorrgb[1], $shadowcolorrgb[2]);
						imagettftext($dst_photo, $this->watermarktext['size'], $this->watermarktext['angle'], $x + $ax + $this->watermarktext['shadowx'], $y + $ay + $this->watermarktext['shadowy'], $shadowcolor, $this->watermarktext['fontpath'], $watermarktextcvt);
					}
					$colorrgb = $this->excolor($this->watermarktext['color']);
					$color = imagecolorallocate($dst_photo, $colorrgb[0], $colorrgb[1], $colorrgb[2]);
					imagettftext($dst_photo, $this->watermarktext['size'], $this->watermarktext['angle'], $x + $ax, $y + $ay, $color, $this->watermarktext['fontpath'], $watermarktextcvt);
				} else {
					imageAlphaBlending($watermark_logo, true);
					imageCopyMerge($dst_photo, $watermark_logo, $x, $y, 0, 0, $logo_w, $logo_h, $this->watermarktrans);
				}
				if($this->attachinfo['mime'] == 'image/jpeg') {
					$imagefunc($dst_photo, $this->targetfile, $this->watermarkquality);
				} else {
					$imagefunc($dst_photo, $this->targetfile);
				}
			}else{
				return false;
			}
		}
		return true;
	}

	function excolor($color,$type=1){
		$color=substr($color,1);
		$returncolor=array();
		$returncolor[]=hexdec($color{0}.$color{1});
		$returncolor[]=hexdec($color{2}.$color{3});
		$returncolor[]=hexdec($color{4}.$color{5});
		if($type==2){
			return $returncolor[0].','.$returncolor[1].','.$returncolor[2];
		}
		return $returncolor;
	}
	
	function formaturl($url){
		$returnurl='';
		$url=realpath($url);
		$num=strlen($url);
		$k=0;
		$tarray=array('\\','/','\\\\','//');
		while($k<$num){
			if(($pos=strpos($url,' ',$k))!==false){
				for($i=$pos;$i>0;$i--){
					if(in_array($url[$i],$tarray)){
						$url=substr($url,0,$i+1).'"'.substr($url,$i+1);
						break;
					}
				}
				for($i=$pos;$i<$num;$i++){
					if(in_array($url[$i],$tarray)){
						$url=substr($url,0,$i).'"'.substr($url,$i);
						$k=$i;
						break;
					}
				}
			}else{
				break;
			}
		}
		return $url;
	}
}