<?php
error_reporting(0);

define('UC_CLIENT_VERSION', '1.5.1');
define('UC_CLIENT_RELEASE', '20100501');

define('API_DELETEUSER', 1);			//用户删除 API
define('API_RENAMEUSER', 1);            //用户改名 API
define('API_GETTAG', 1);                //获取标签 API
define('API_SYNLOGIN', 1);              //同步登录 API
define('API_SYNLOGOUT', 1);             //同步登出 API
define('API_UPDATEPW', 1);              //更改用户密码
define('API_UPDATEBADWORDS', 1);        //更新关键字列表
define('API_UPDATEHOSTS', 1);           //更新域名解析缓存
define('API_UPDATEAPPS', 1);            //更新应用列表
define('API_UPDATECLIENT', 1);          //更新客户端缓存
define('API_UPDATECREDIT', 1);          //更新用户积分
define('API_GETCREDITSETTINGS', 1);     //向 UCenter 提供积分设置
define('API_GETCREDIT', 1);             //获取用户的某项积分
define('API_UPDATECREDITSETTINGS', 1);  //积分设置

define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '-2');

define('HDWIKI_ROOT', substr(dirname(__FILE__),0,-4));
define('UC_CLIENT_ROOT',HDWIKI_ROOT.'/api/uc_client/');
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

require_once HDWIKI_ROOT.'/api/ucconfig.inc.php';

$get = $post = array();
$code = @$_GET['code'];
parse_str(authcode($code, 'DECODE', UC_KEY), $get);
if(MAGIC_QUOTES_GPC) {
	$get = _stripslashes($get);
}
$timestamp = time();
if(empty($get)){
	exit('Invalid Request');
}elseif($timestamp - $get['time'] > 3600){
	exit('Authracation has expiried');
}

require HDWIKI_ROOT."/config.php";
require HDWIKI_ROOT."/lib/hddb.class.php";
$db =new hddb(DB_HOST,DB_USER,DB_PW,DB_NAME,UC_DBCHARSET);

$action = $get['action'];
//file_put_contents('test.txt',var_export($get,true));
include_once UC_CLIENT_ROOT.'lib/xml.class.php';
$post = xml_unserialize(file_get_contents('php://input'));

if(in_array($action, array('test', 'deleteuser', 'renameuser', 'synlogin', 'synlogout', 'updatepw', 'updateapps', 'updateclient', 'updatecredit', 'getcreditsettings', 'updatecreditsettings'))) {
	$uc_note = new uc_note($db);
	echo $uc_note->$get['action']($get, $post);
	exit;
} else {
	exit(API_RETURN_FAILED);
}

class uc_note {

	var $db = '';
	var $cookie_pre = '';
	var $setting = '';
	
	function uc_note($db) {
		$this->db = $db;
		$this->setting = $this->db->fetch_first("select * from ".DB_TABLEPRE."setting where variable='cookie_pre'");
		$this->cookie_pre=$this->setting['value']?$this->setting['value']:'hd_';
	}

	function test($get, $post) {
		return API_RETURN_SUCCEED;
	}
	
	function deleteuser($get, $post) {
		$uids = $get['ids'];
		!API_DELETEUSER && exit(API_RETURN_FORBIDDEN);
		return API_RETURN_SUCCEED;
	}
	
	function synlogin($get, $post) {
		!API_SYNLOGIN && exit(API_RETURN_FORBIDDEN);
		$cookietime = 2592000;
		$timestamp=$get['time'];
		$discuz_auth_key = md5($_DCACHE['settings']['authkey'].$_SERVER['HTTP_USER_AGENT']);
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		$username=$get['username'];
		$password=$get['password'];
		$uid=$get['uid'];
		$user = $this->db->fetch_first("select * from ".DB_TABLEPRE."user  where username='$username'");
		
		if(!is_array($user)){
			if($usernum=$this->db->result_first("select count(*) from ".DB_TABLEPRE."user where uid= $uid")){
				$maxuid=$this->db->result_first("select max(uid) from ".DB_TABLEPRE."user");
				$maxuid+=1;
				$this->db->query("update ".DB_TABLEPRE."user set uid=$maxuid where uid=$uid");
				update_field($uid,$maxuid);
			}
			$password=$email="";
			$ip = $_SERVER['REMOTE_ADDR'];
			$groupid = 2;
			$this->db->query("replace into  ".DB_TABLEPRE."user (uid,username,`password`,email,regip,regtime,groupid) values ($uid,'$username','$password','$email','$ip',$timestamp,$groupid)");
			$user = $this->db->fetch_first("select * from ".DB_TABLEPRE."user u  where u.uid=$uid and u.username='$username'");
		}elseif(is_array($user) && $user['uid']!=$uid){
			if($usernum=$this->db->result_first("select count(*) from ".DB_TABLEPRE."user where uid= $uid")){
				$maxuid=$this->db->result_first("select max(uid) from ".DB_TABLEPRE."user");
				$maxuid+=1;
				$this->db->query("update ".DB_TABLEPRE."user set uid=$maxuid where uid=$uid");
				update_field($uid,$maxuid);
			}
			$this->db->query("update ".DB_TABLEPRE."user SET uid = '$uid' WHERE uid= '$user[uid]'");
			update_field($user["uid"],$uid);
			$user['uid']=$uid;
		}
		if(is_array($user)){
			$sid=$_COOKIE[$this->cookie_pre.'sid'];
			$uid=$user['uid'];
			$password=$user['password'];
			if($sid!=''){
				$userarr=$this->db->fetch_first("select u.*, g.*, s.* from ".DB_TABLEPRE."user u,".DB_TABLEPRE."usergroup g,".DB_TABLEPRE."session s where s.uid=$uid and u.uid=$uid  and s.sid='$sid'  and u.groupid=g.groupid");
			}else{
				$sid=random(6);
				setcookie($this->cookie_pre.'sid',$sid,$timestamp+24*3600*365,'/','',false);
				setcookie($this->cookie_pre.'hid',0,$timestamp+24*3600*365,'/','',false);
			}
			if(!(bool)$userarr){
				$this->db->query("replace into ".DB_TABLEPRE."session (sid,uid,username,islogin,`time`) values ('$sid',$uid,'$username',1,{$timestamp})");
				$auth_key=$this->db->fetch_first("select value from ".DB_TABLEPRE."setting  where variable='auth_key'");
				setcookie($this->cookie_pre.'auth',authcode("$uid\t$password",'ENCODE',$auth_key['value'],0,1),$timestamp+24*3600*365,'/','',false);
			}
		}
	}
	
	function renameuser($get, $post) {
		!API_RENAMEUSER && exit(API_RETURN_FORBIDDEN);
		$oldusername = $get['oldusername'];
		$newusername = $get['newusername'];
		$this->db->query("UPDATE ".DB_TABLEPRE."user SET username='$newusername' WHERE username ='".$oldusername."'");
		return API_RETURN_SUCCEED;
	}
	
	function synlogout($get, $post) {
		!API_SYNLOGOUT && exit(API_RETURN_FORBIDDEN);
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		$sid=$_COOKIE[$this->cookie_pre.'sid'];
		setcookie($this->cookie_pre.'sid',$sid,0,'/','',false);
		setcookie($this->cookie_pre.'auth',0,0,'/','',false);
		$this->db->query("delete from ".DB_TABLEPRE."session where sid='$sid' ");
	}
	
	function updatepw($get, $post) {
		!API_UPDATEPW && exit(API_RETURN_FORBIDDEN);
		$username = $get['username'];
		$password = md5($get['password']);
		$this->db->query("UPDATE `".DB_TABLEPRE."user` SET password='$password' WHERE username= '$username' ");
		return API_RETURN_SUCCEED;
	}
	
	function updatecredit($get, $post) {
		!UPDATECREDIT && exit(API_RETURN_FORBIDDEN);
		$timestamp=$get['time'];
		$credit = intval($get['credit']);
		$amount = intval($get['amount']);
		$uid = intval($get['uid']);
		$operation="syn_credit";
		$this->db->query("insert into ".DB_TABLEPRE."creditdetail(uid,operation,credit,time) values ($uid,'$operation',$amount,{$timestamp}) ");
		$this->db->query("update ".DB_TABLEPRE."user set credits=credits+$amount where uid=$uid ");
		return API_RETURN_SUCCEED;
	}
	
	function getcreditsettings($get, $post) {
		!API_GETCREDITSETTINGS && exit(API_RETURN_FORBIDDEN);
		$credits=array('0' => array('经验', ''),'1' => array('金币', ''));
		return uc_serialize($credits);
	}
	
	function updatecreditsettings($get, $post) {
		!API_UPDATECREDITSETTINGS && exit(API_RETURN_FORBIDDEN);
		$credit = $get['credit'];
		$outextcredits = array();
		
		if($credit) {
			foreach($credit as $appid => $credititems) {
				if($appid == UC_APPID) {
					foreach($credititems as $value) {
						$value['titlesrc']=$value['creditsrc']==0?'经验':'金币';
						$outextcredits[] = array(
							'appiddesc' => $value['appiddesc'],
							'creditdesc' => $value['creditdesc'],
							'creditsrc' => $value['creditsrc'],
							'title' => $value['title'],
							'titlesrc' => $value['titlesrc'],
							'unit' => $value['unit'],
							'ratiosrc' => $value['ratiosrc'],
							'ratiodesc' => $value['ratiodesc'],
							'ratio' => $value['ratio']
						);
					}
				}
			}
		}
		$this->db->query("REPLACE INTO ".DB_TABLEPRE."setting (variable, value) VALUES ('outextcredits','".serialize($outextcredits)."');", 'UNBUFFERED');
		$settingCache='../data/cache/setting.php';
		if(is_file($settingCache)){
	    	unlink($settingCache);
	    }
		return API_RETURN_SUCCEED;
	}
	
	function updateclient($get, $post) {
		if(!API_UPDATECLIENT) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = UC_CLIENT_ROOT.'./data/cache/settings.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'settings\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}
	
	function updateapps($get, $post) {
		return API_RETURN_SUCCEED;
	}

}

function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0 ,$source=0) {
	$ckey_length = 4;
	if($source){
		$setting=getcache('setting');
		$key = md5($key ? $key : $setting['auth_key']);
	}else{
		$key = md5($key ? $key : UC_KEY);
	}
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}
function uc_serialize($arr, $htmlon = 0) {
	include_once UC_CLIENT_ROOT.'/lib/xml.class.php';
	return xml_serialize($arr, $htmlon);
}

function uc_unserialize($s) {
	include_once UC_CLIENT_ROOT.'/lib/xml.class.php';
	return xml_unserialize($s);
}
function random($length) {
	$hash = '';
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	$max = strlen($chars) - 1;
	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}
function _stripslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = _stripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}
function update_field($uid,$newuid) {
	global $db;
	$db->query("UPDATE ".DB_TABLEPRE."activation SET uid='$newuid' WHERE uid='$uid'");
	$db->query("UPDATE ".DB_TABLEPRE."attachment SET uid='$newuid' WHERE uid='$uid'");
	$db->query("UPDATE ".DB_TABLEPRE."creditdetail SET uid='$newuid' WHERE uid='$uid'");
	$db->query("UPDATE ".DB_TABLEPRE."doc SET authorid='$newuid' WHERE authorid='$uid'");
	$db->query("UPDATE ".DB_TABLEPRE."edition SET authorid='$newuid' WHERE authorid='$uid'");
	$db->query("UPDATE ".DB_TABLEPRE."comment SET authorid='$newuid' WHERE authorid='$uid'");
}	
function getcache($cachename){
	$data='';
	$cachefile=HDWIKI_ROOT.'/data/cache/'.$cachename.'.php';
	if($fp = @fopen($cachefile,'rb')) {
		flock($fp,LOCK_EX);
		$data=fread($fp, filesize($cachefile));
		flock($fp, LOCK_UN);
		fclose($fp);
	}
	$data=substr($data,13);
	$data=unserialize(base64_decode($data));
	return $data;
}