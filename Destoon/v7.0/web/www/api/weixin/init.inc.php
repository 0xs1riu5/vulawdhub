<?php
defined('IN_DESTOON') or exit('Access Denied');
include_once DT_ROOT.'/api/weixin/config.inc.php';
$session = new dsession();
class weixin {
	var $access_token;

	function __construct() {
		$this->access_token = $this->get_token();
	}

	function weixin() {
		$this->__construct();
	}

	function signature() {
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];        		
		$token = WX_APPTOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);
		return $tmpStr == $signature;
	}

	function http_get($url) {
		$rec = dcurl($url);
		$arr = json_decode($rec, true);
		return $arr ? $arr : array();
	}

	function http_post($url, $par) {
		$rec = dcurl($url, $par);
		$arr = json_decode($rec, true);
		return $arr ? $arr : array();
	}

	function http_upload($file) {
		$ext = file_ext($file);
		$size = dround(filesize($file)/1024);
		if($size < 1) return array('', 'invalid media file');
		if($ext == 'mp3' || $ext == 'amr') {
			if($size > 256) return array('', 'media file too large');
			$type = 'voice';
		} else if($ext == 'mp4') {
			if($size > 1024) return array('', 'media file too large');
			$type = 'video';
		} else if($ext == 'jpg') {
			if($size > 128) return array('', 'media file too large');
			$type = 'image';
		} else {
			return array('', 'invalid media type');
		}
		$par = array();
		$par['access_token'] = $this->access_token;
		$par['type'] = $type;
		$par['media'] = '@'.$file;
		#$par = 'access_token='.$this->access_token.'&type='.$type.'&media=@'.$file;
		$cur = curl_init('http://file.api.weixin.qq.com/cgi-bin/media/upload');
		curl_setopt($cur, CURLOPT_POST, 1);
		curl_setopt($cur, CURLOPT_POSTFIELDS, $par);
		curl_setopt($cur, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($cur, CURLOPT_HEADER, 0);
		curl_setopt($cur, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($cur, CURLOPT_RETURNTRANSFER, 1);
		#curl_setopt($cur, CURLOPT_HTTPHEADER, array('Expect: '));
		$rec = curl_exec($cur);
		curl_close($cur);
		$arr = json_decode($rec, true);
		return isset($arr['media_id']) ? array($arr['media_id'], $type) : array('', $arr['errmsg']);
	}
	
	function get_token() {
		$wt = cache_read('weixin-token.php');
		if($wt && $wt['token'] && DT_TIME - $wt['time'] < 7000) return $wt['token'];
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.WX_APPID.'&secret='.WX_APPSECRET;
		$arr = $this->http_get($url);
		$access_token = isset($arr['access_token']) ? $arr['access_token'] : '';
		cache_write('weixin-token.php', array('time' => DT_TIME, 'token' => $access_token));
		return $access_token;
	}

	function get_user($openid) {
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->access_token.'&openid='.$openid;
		$arr = $this->http_get($url);
		if(is_array($arr)) {
			foreach($arr as $k=>$v) {
				$arr[$k] = $v;
			}
		}
		return $arr;
	}

	function send($openid, $type, $content, $misc = array()) {
		$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->access_token;
		$par = array();
		$par['touser'] = $openid;
		$par['msgtype'] = $type;
		switch($type) {
			case 'text':
				$par[$type]['content'] = urlencode($content);
			break;
			case 'image':
				$par[$type]['media_id'] = $content;
			break;
			case 'voice':
				$par[$type]['media_id'] = $content;
			break;
			case 'video':
				$par[$type]['media_id'] = $content;
				$par[$type]['title'] = isset($misc['title']) ? urlencode($misc['title']) : '';
				$par[$type]['description'] = isset($misc['description']) ? urlencode($misc['description']) : '';
			break;
			case 'music':
				$par[$type]['title'] = isset($misc['title']) ? urlencode($misc['title']) : '';
				$par[$type]['description'] = isset($misc['description']) ? urlencode($misc['description']) : '';
				$par[$type]['musicurl'] = isset($misc['musicurl']) ? $misc['musicurl'] : '';
				$par[$type]['hqmusicurl'] = isset($misc['hqmusicurl']) ? $misc['hqmusicurl'] : '';
				$par[$type]['thumb_media_id'] = isset($misc['thumb_media_id']) ? $misc['thumb_media_id'] : '';
			break;
			case 'news':
				if($misc && count($misc) < 11) {
					foreach($misc as $k=>$v) {
						$misc[$k]['title'] = urlencode($v['title']);
						$misc[$k]['description'] = urlencode($v['description']);
					}
					$par[$type]['articles'] = $misc;
				} else {
					return false;
				}
			break;
			default:
				return false;
			break;
		}
		return $this->http_post($url, urldecode(json_encode($par)));
	}

	function response($openid, $from, $type, $content, $misc = array()) {
		$xml = '<xml>';
		$xml .= '<ToUserName><![CDATA['.$openid.']]></ToUserName>';
		$xml .= '<FromUserName><![CDATA['.$from.']]></FromUserName>';
		$xml .= '<CreateTime>'.DT_TIME.'</CreateTime>';
		$xml .= '<MsgType><![CDATA['.$type.']]></MsgType>';
		switch($type) {
			case 'text':
				$xml .= '<Content><![CDATA['.$content.']]></Content>';
			break;
			case 'image':
				$xml .= '<Image>';
				$xml .= '<MediaId><![CDATA['.$content.']]></MediaId>';
				$xml .= '</Image>';
			break;
			case 'voice':
				$xml .= '<Voice>';
				$xml .= '<MediaId><![CDATA['.$content.']]></MediaId>';
				$xml .= '</Voice>';
			break;
			case 'video':
				$xml .= '<Video>';
				$xml .= '<MediaId><![CDATA['.$content.']]></MediaId>';
				$xml .= '<Title><![CDATA['.(isset($misc['title']) ? $misc['title'] : '').']]></Title>';
				$xml .= '<Description><![CDATA['.(isset($misc['description']) ? $misc['description'] : '').']]></Description>';
				$xml .= '</Video>';
			break;
			case 'music':
				$xml .= '<Music>';
				$xml .= '<MediaId><![CDATA['.$content.']]></MediaId>';
				$xml .= '<Title><![CDATA['.(isset($misc['title']) ? $misc['title'] : '').']]></Title>';
				$xml .= '<Description><![CDATA['.(isset($misc['description']) ? $misc['description'] : '').']]></Description>';
				$xml .= '<MusicUrl><![CDATA['.(isset($misc['musicurl']) ? $misc['musicurl'] : '').']]></MusicUrl>';
				$xml .= '<HQMusicUrl><![CDATA['.(isset($misc['hqmusicurl']) ? $misc['hqmusicurl'] : '').']]></HQMusicUrl>';
				$xml .= '<ThumbMediaId><![CDATA['.(isset($misc['thumb_media_id']) ? $misc['thumb_media_id'] : '').']]></ThumbMediaId>';
				$xml .= '</Music>';
			break;
			case 'news':
				if($misc && count($misc) < 11) {
					$xml .= '<ArticleCount>'.count($misc).'</ArticleCount>';
					$xml .= '<Articles>';
					foreach($misc as $k=>$v) {
						$v['title'] = dsubstr($v['title'], 48);
						$xml .= '<item>';
						$xml .= '<Title><![CDATA['.$v['title'].']]></Title>';
						$xml .= '<Description><![CDATA['.$v['description'].']]></Description>';
						$xml .= '<PicUrl><![CDATA['.$v['picurl'].']]></PicUrl>';
						$xml .= '<Url><![CDATA['.$v['url'].']]></Url>';
						$xml .= '</item>';
					}
					$xml .= '</Articles>';
				} else {
					return false;
				}
			break;
			default:
				return false;
			break;
		}
		$xml .= '</xml>';
		echo $xml;
	}
}
$wx = new weixin;
$access_token = $wx->access_token;
function weixin_user($openid) {
	return DB::get_one("SELECT * FROM ".DT_PRE."weixin_user WHERE openid='$openid'");
}

function weixin_bind($openid, $username) {
	if(check_name($username)) {
		DB::query("UPDATE ".DT_PRE."weixin_user SET username='' WHERE username='$username'");
		DB::query("UPDATE ".DT_PRE."weixin_user SET username='$username' WHERE openid='$openid'");
	}
}

function weixin_log() {
	log_write(DT_IP."\nPOST:\n".var_export($_POST, true)."\nGET:".var_export($_GET, true)."\nGLB:".var_export($GLOBALS["HTTP_RAW_POST_DATA"], true), 'wx', 1);
}
#if($GLOBALS["HTTP_RAW_POST_DATA"]) weixin_log();
?>