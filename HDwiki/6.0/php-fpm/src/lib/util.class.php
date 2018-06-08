<?php

class util{
	function util(){
		die("Class util can not instantiated!");
	}
	/**
     * random
     * @param int $length
     * @return string $hash
   */
	function random($length=6,$type=0) {
		$hash = '';
		$chararr =array(
			'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz',
			'0123456789',
			'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
		);
		$chars=$chararr[$type];
		$max = strlen($chars) - 1;
		PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
		return $hash;
	}


	/**
     * image_compress
     * @param string $url,$prefix;int  $width,$height
     * @return array $result
   */
	function image_compress($url,$prefix='s_',$width=80,$height=60,$suffix=''){
		global $lang;
		$result=array ('result'=>false,'tempurl'=>'','msg'=>'something Wrong');
		if(!file_exists($url)){
			$result['msg'] =$url. 'img is not exist';
			return $result;
		}
		$urlinfo=pathinfo($url);
		$ext=strtolower($urlinfo['extension']);
		$tempurl=$urlinfo['dirname'].'/'.$prefix.substr($urlinfo['basename'],0,-1-strlen($ext)).$suffix.'.'.$ext;
		if ( ! util::isimage($ext)) {
			$result['msg'] ='img must be gif|jpg|jpeg|png';
			return $result;
		}
		$ext=($ext=='jpg')?'jpeg':$ext;
		$createfunc='imagecreatefrom'.$ext;
		$imagefunc='image'.$ext;
		if(function_exists($createfunc)){
			list($actualWidth,$actualHeight) = getimagesize($url);
			if($actualWidth<$width&&$actualHeight<$height){
				    copy($url,$tempurl);
					$result['tempurl']=$tempurl;
					$result['result']=true;
					return $result;
			}
			if ($actualWidth < $actualHeight){
				$width=round(($height / $actualHeight) *$actualWidth);
			} else {
				$height=round(($width/ $actualWidth) *$actualHeight);
			}
			$tempimg=imagecreatetruecolor($width, $height);
			$img = $createfunc($url);
			imagecopyresampled($tempimg, $img, 0, 0, 0, 0, $width, $height, $actualWidth, $actualHeight);
			$result['result']=($ext=='png')?$imagefunc($tempimg, $tempurl):$imagefunc($tempimg, $tempurl, 80);
			
			imagedestroy($tempimg);
			imagedestroy($img);
			if(file_exists($tempurl)){
				$result['tempurl']=$tempurl;
			}else {
				$result['tempurl']=$url;
			}
		}else{
				copy($url, $tempurl);
				if(file_exists($tempurl)){
					$result['result']= true;
					$result['tempurl']=$tempurl;
				}else {
					$result['tempurl']=$url;
				}
		  }
		return $result;
	}
	
	/**
     * isimage
     * @param string $extname
     * @return true or false
   */
	function isimage($extname){
		return in_array( $extname,array('jpg','jpeg','png','gif') );
	}
	
	/**
     * getfirstimg
     * @param string $content
     * @return string $tempurl
   */
	function getfirstimg($string){
		preg_match("/<img.+?src=[\\\\]?\"(.+?)[\\\\]?\"/i",$string,$imgs);
		if(isset($imgs[1])){
			return $imgs[1];
		}else{
			return "";
		}
		
	}
	
	function getimagesnum($string){
		preg_match_all("/<img.+?src=[\\\\]?\"(.+?)[\\\\]?\"/i",$string,$imgs);
		return count($imgs[0]);
	}
	
    /**
     * formatfilesize
     *
     * @param int $size
     * @return string $_format
     */
	function formatfilesize($filename){
		$size=filesize($filename);
		if ($size < 1024){
			$_format = $size . "B";
			return $_format;
		}elseif ($size < 1024*1024){
			$_format = round($size/1024, 2) . "KB";
			return $_format;
		}elseif ($size < 1024*1024*1024){
			$_format = round($size/(1024*1024), 2) . "MB";
			return $_format;
		}
	}
 
  
 
    /**
     * getip
     *
     * @return string
     */
    function getip(){
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')){
            $ip = getenv('HTTP_CLIENT_IP');
        }else if (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')){
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        }else if (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')){
            $ip = getenv('REMOTE_ADDR');
        }else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')){
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        preg_match("/[\d\.]{7,15}/", $ip, $temp);
        $ip = $temp[0] ? $temp[0] : 'unknown';
        unset($temp);
        return $ip;
    }
 

   function makecode($code){
		$codelen = strlen($code);
		$im = imagecreate(50,20);
		$font_type = HDWIKI_ROOT."/style/default/ant2.ttf";
		$bgcolor = ImageColorAllocate($im, 235,245,255);//近白色
		$iborder = ImageColorAllocate($im, 70,80,90); //近黑色

		$fontColor = ImageColorAllocate($im, 164,164,164);
		$fontColor1 = ImageColorAllocate($im, 20,80,255); //近蓝色
		$fontColor2 = ImageColorAllocate($im, 50,50,50);//近黑色
		$fontColor3 = ImageColorAllocate($im, 255,80,20);//近红色
		$fontColor4 = ImageColorAllocate($im, 20,200,20); //近绿色

		$lineColor = ImageColorAllocate($im, 110,220,220);//淡蓝色

		for($j=3;$j<=16;$j=$j+4) imageline($im,2,$j,48,$j,$lineColor);
		for($j=2;$j<52;$j=$j+(mt_rand(3,6))) imageline($im,$j,2,$j-6,18,$lineColor);
		imagerectangle($im, 0, 0, 49, 19, $iborder);
		$strposs = array();
		for($i=0;$i<$codelen;$i++){
		if(function_exists("imagettftext")){
		$strposs[$i][0] = $i*10+6;
		$strposs[$i][1] = mt_rand(15,18);
		imagettftext($im, 11, 5, $strposs[$i][0]+1, $strposs[$i][1]+1, $fontColor, $font_type, $code[$i]);
		}
		else{
		imagestring($im, 5, $i*10+6, mt_rand(2,4), $code[$i], $fontColor2);
		}
		}
		for($i=0;$i<$codelen;$i++){
		if(function_exists("imagettftext")){
		$fontC=${'fontColor'.mt_rand(1,4)};
		imagettftext($im, 11,5, $strposs[$i][0], $strposs[$i][1], $fontC, $font_type, $code[$i]);
		}
		}
		header("Pragma:no-cache\r\n");
		header("Cache-Control:no-cache\r\n");
		header("Expires:0\r\n");
		if(function_exists("imagejpeg")){
			header("content-type:image/jpeg\r\n");
			imagejpeg($im);
		}else{
			header("content-type:image/png\r\n");
			imagepng($im);
		}
		ImageDestroy($im);
   }

	 function hfopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
		$return = '';
		$matches = parse_url($url);
		$host = $matches['host'];
		@$path = $matches['path'] ? $matches['path'].'?'.$matches['query'].'#'.$matches['fragment'] : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;

		if($post) {
			$out = "POST $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			//$out .= "Referer: $site_url\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= 'Content-Length: '.strlen($post)."\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cache-Control: no-cache\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
			$out .= $post;
		} else {
			$out = "GET $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			//$out .= "Referer: $site_url\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		}
		$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		if(!$fp) {
			return '';
		} else {
			stream_set_blocking($fp, $block);
			stream_set_timeout($fp, $timeout);
			@fwrite($fp, $out);
			$status = stream_get_meta_data($fp);
			if(!$status['timed_out']) {
				$firstline=true;
				while (!feof($fp)) {
					$header = @fgets($fp);
					if($firstline && (false===strstr($header,'200')) ){
						return '';
					}
					$firstline=false;
					if( $header  && ($header == "\r\n" ||  $header == "\n") ) {
						break;
					}
				}
				$stop = false;
				while(!feof($fp) && !$stop) {
					$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
					$return .= $data;
					if($limit) {
						$limit -= strlen($data);
						$stop = $limit <= 0;
					}
				}
			}
			@fclose($fp);
			return $return;
		}
	}
	
	function is_mem_available($mem){
		$limit = trim(ini_get('memory_limit'));
		if(empty($limit)) return true; 
		$unit = strtolower(substr($limit,-1));
		switch($unit){
		case 'g':
			$limit = substr($limit,0,-1);
			$limit *= 1024*1024*1024;
			break;
		case 'm':
			$limit = substr($limit,0,-1);
			$limit *= 1024*1024;
			break;
		case 'k':
			$limit = substr($limit,0,-1);
			$limit *= 1024;
			break;
		}
		if(function_exists('memory_get_usage')){
			$used = memory_get_usage();
		}
		if($used+$mem > $limit){
			return false;
		}
		return true;
	}
	
	function strcode($string,$action='ENCODE'){
		$key	= substr(md5($_SERVER["HTTP_USER_AGENT"].PP_KEY),8,18);
		$string	= $action == 'ENCODE' ? $string : base64_decode($string);
		$len	= strlen($key);
		$code	= '';
		for($i=0; $i < strlen($string); $i++){
			$k		= $i % $len;
			$code  .= $string[$i] ^ $key[$k];
		}
		$code = $action == 'DECODE' ? $code : base64_encode($code);
		return $code;
	}

	/**
	 * 检测给出IP是否为内网Ip
	 * @param <type> $ip
	 * @return <type> 是内网IP 返回TRUE
	 */
	function is_private_ip($ip='') {
		if(empty($ip)) {
			$ip = $_SERVER['SERVER_ADDR'];
		}
		else {
			$ip = gethostbyname($ip);
		}
		
		$i = explode('.', $ip);
	//	if ($i[0] == 127) return true;
	//	if ($i[0] == 10) return true;
		if ($i[0] == 172 && $i[1] > 15 && $i[1] < 32) return true;
		if ($i[0] == 192 && $i[1] == 168) return true;
		return false;
	}
}
?>