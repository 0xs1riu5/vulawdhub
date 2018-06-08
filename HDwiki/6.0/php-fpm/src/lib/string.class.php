<?php

class string {

	function string() {
		die("Class string can not instantiated!");
	}

	function substring($str, $start=0, $limit=12) {
		if('gbk'==strtolower(WIKI_CHARSET)){
			$strlen=strlen($str);
			if ($start>=$strlen){
				return $str;
			}
			$clen=0;
			for($i=0;$i<$strlen;$i++,$clen++){
				if(ord(substr($str,$i,1))>0xa0){
					if ($clen>=$start){
						$tmpstr.=substr($str,$i,2);
					}
					$i++;
				}else{
					if ($clen>=$start){
						$tmpstr.=substr($str,$i,1);
					}
				}
				if ($clen>=$start+$limit){
					break;
				}
			}
			$str=$tmpstr;
		}else{
			$patten = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
			preg_match_all($patten, $str, $regs);
			$s = '';
			for($i=$start; $i<count($regs[0]); $i++){
				$s .= $regs[0][$i];
				if($i >= $limit){
					break;
				}
			}
			$str=$s;
		}
		return $str;
	}

	function hiconv($str,$to='',$from='',$force=false) {
		if (empty($str)) return $str;
		if(!preg_match( '/[\x80-\xff]/', $str)) return $str; // is contain chinese char
		if(empty($to)){
			if ('utf-8' == strtolower(WIKI_CHARSET)){
				return $str;
			}
			$to=WIKI_CHARSET;
		}
		if(empty($from)){
			$from = ('gbk'==strtolower($to)) ? 'utf-8':'gbk';
		}
		$to=strtolower($to);
		$from=strtolower($from);
		//$isutf8=preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $str );
		$re = strlen($str) > 6 ? '/([\xe0-\xef][\x80-\xbf]{2}){2}/' : '/[\xe0-\xef][\x80-\xbf]{2}/';
		$isutf8 = preg_match($re, $str);

		//$force = (substr($to, 0, 3) == 'utf') ? true : $force;

		if(!$force && $isutf8 && $to=='utf-8' ) return $str;
		if(!$force && !$isutf8 && $to=='gbk' ) return $str;

		if (function_exists('iconv')){
			$str = iconv($from, $to, $str);
		}else{
			require_once(HDWIKI_ROOT.'/lib/Chinese.class.php');
			$ch = new chinese($from,$to);
			if('utf-8'==$from){
				$str = addslashes($ch->convert(stripslashes($str)));
			}else{
				$str = $ch->convert($str);
			}
		}
		return $str;
	}

	function hstrlen($str) {
		if('gbk'==strtolower(WIKI_CHARSET)){
			$length=strlen($str);
		}else{
			$length=floor(2/3*strlen($str));
		}
		return $length;
	}

	function hstrtoupper($str){
		if (is_array($str)){
			foreach ($str as $key => $val){
				$str[$key] = string::hstrtoupper($val);
			}
		}else{
			$i=0;
			$total = strlen($str);
			$restr = '';
			for ($i=0; $i<$total; $i++){
				$str_acsii_num = ord($str[$i]);
				if($str_acsii_num>=97 and $str_acsii_num<=122){
					$restr.=chr($str_acsii_num-32);
				}else{
					$restr.=chr($str_acsii_num);
				}
			}
		}
		return $restr;
	}

	function hstrtolower($string){
		if (is_array($string)){
			foreach ($string as $key => $val){
				$string[$key] = string::hstrtolower($val);
			}
		}else{
			$string = strtolower($string);
		}
		return $string;
	}

	function haddslashes($string, $force = 0) {
		if(!MAGIC_QUOTES_GPC || $force) {
			if(is_array($string)) {
				foreach($string as $key => $val) {
					$string[$key] = string::haddslashes($val, $force);
				}
			}else {
				$string = addslashes($string);
			}
		}
		return $string;
	}

	function hstripslashes($string) {
		if(is_array($string)){
			while(@list($key,$var) = @each($string)) {
				if ($key != 'argc' && $key != 'argv' && (strtoupper($key) != $key || ''.intval($key) == "$key")) {
					if (is_string($var)) {
						$string[$key] = stripslashes($var);
					}
					if (is_array($var))  {
						$string[$key] = string::hstripslashes($var);
					}
				}
			}
		}else{
			$string=stripslashes($string);
		}
		return $string;
	}
	function convercharacter($str){
		$str=str_replace('\\\r',"",$str);
		$str=str_replace('\\\n',"",$str);
		$str=str_replace('\n',"",$str);
		$str=str_replace('\r',"",$str);
		return $str;
	}

	function getfirstletter($string) {
		if(WIKI_CHARSET=='UTF-8'){
			$string=string::hiconv($string,'gbk','utf-8');
		}
		$dict=array(
			'a'=>0xB0C4,'b'=>0xB2C0,'c'=>0xB4ED,'d'=>0xB6E9,
			'e'=>0xB7A1,'f'=>0xB8C0,'g'=>0xB9FD,'h'=>0xBBF6,
			'j'=>0xBFA5,'k'=>0xC0AB,'l'=>0xC2E7,'m'=>0xC4C2,
			'n'=>0xC5B5,'o'=>0xC5BD,'p'=>0xC6D9,'q'=>0xC8BA,
			'r'=>0xC8F5,'s'=>0xCBF9,'t'=>0xCDD9,'w'=>0xCEF3,
			'x'=>0xD1B8,'y'=>0xD4D0,'z'=>0xD7F9,
			);
		$letter = substr($string, 0, 1);
		$letter_ord = ord($letter);
		if($letter_ord >= 176 && $letter_ord <= 215){
			$num = '0x'.bin2hex(substr($string, 0, 2));
			foreach ($dict as $k=>$v){
				if($v>=$num) break;
			}
			return $k;
		}elseif(($letter_ord>64 && $letter_ord<91) || ($letter_ord>96 && $letter_ord<123)){
			return $letter;
		}elseif($letter>='0' && $letter<='9'){
			return $letter;
		}else{
			return '*';
		}
	}

	function stripspecialcharacter($string) {
		$string=trim($string);
		$string=str_replace("&","",$string);
		$string=str_replace("\'","",$string);
		$string=str_replace("'","",$string);
		$string=str_replace("&amp;amp;","",$string);
		$string=str_replace("&amp;quot;","",$string);
		$string=str_replace("\"","",$string);
		$string=str_replace("&amp;lt;","",$string);
		$string=str_replace("<","",$string);
		$string=str_replace("&amp;gt;","",$string);
		$string=str_replace(">","",$string);
		$string=str_replace("&amp;nbsp;","",$string);
		$string=str_replace("\\\r","",$string);
		$string=str_replace("\\\n","",$string);
		$string=str_replace("\n","",$string);
		$string=str_replace("\r","",$string);
		$string=str_replace("\r","",$string);
		$string=str_replace("\n","",$string);
		$string=str_replace("'","&#39;",$string);
		$string=nl2br($string);
		return $string;
	}

	function convert_to_unicode($string){
		if(WIKI_CHARSET=='GBK'){
			$string=string::hiconv($string,'utf-8','gbk');
		}
		$string=preg_replace("/([\\xc0-\\xff][\\x80-\\xbf]*)/e","' U8'.bin2hex( \"$1\" )",string::hstrtolower( $string ));
		if(strlen($string)<4){
			$string=' HDWIKI'.$string;
		}
		return $string;
	}

	function stripscript($string){
		$pregfind=array("/<script.*>.*<\/script>/siU",'/on(error|mousewheel|mouseover|click|load|onload|submit|focus|blur|start)="[^"]*"/i');
		$pregreplace=array('','',);
		$string=preg_replace($pregfind,$pregreplace,$string);
		return $string;
	}

	function filter_expression($html) {
		if (empty($html)) {
			return $html;
		}
		
		function mreplace($matches) {
			$str = preg_replace(array(
				'|style=\\\?[\'"].+?expression.+?[\'"]|i',
				'|style=\\\?[\'"].+?e\/\*.*?\*\/xpression.+?[\'"]|i',
				'|style=\\\?[\'"].+?ex\/\*.*?\*\/pression.+?[\'"]|i',
				'|style=\\\?[\'"].+?exp\/\*.*?\*\/ression.+?[\'"]|i',
				'|style=\\\?[\'"].+?expr\/\*.*?\*\/ession.+?[\'"]|i',
				'|style=\\\?[\'"].+?expre\/\*.*?\*\/ssion.+?[\'"]|i',
				'|style=\\\?[\'"].+?expres\/\*.*?\*\/sion.+?[\'"]|i',
				'|style=\\\?[\'"].+?express\/\*.*?\*\/ion.+?[\'"]|i',
				'|style=\\\?[\'"].+?expressi\/\*.*?\*\/on.+?[\'"]|i',
				'|style=\\\?[\'"].+?expressio\/\*.*?\*\/n.+?[\'"]|i',
			), '', $matches[0]);
			
			return $str;
		}
		
		return preg_replace_callback(
			'|<\w{1,10}[^>]+?style=[^>]+>|i',
			'mreplace',
			$html
		);
		
		return $html;
	}
}
?>