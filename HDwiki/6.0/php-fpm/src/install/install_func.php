<?php
function gd_version() {
	if (function_exists('gd_info')) {
		$GDArray = gd_info();
		if ($GDArray['GD Version']) {
			$gd_version_number = $GDArray['GD Version'];
		} else {
			$gd_version_number = "Off";
		}
		unset ($GDArray);
	} else {
		$gd_version_number = "Off";
	}
	return $gd_version_number;
}

function result($result = 1, $output = 1) {
	if ($result) {
		$text = '... <font color="#0000EE">Yes</font><br />';
		if (!$output) {
			return $text;
		}
		echo $text;
	} else {
		$text = '... <font color="#FF0000">No</font><br />';
		if (!$output) {
			return $text;
		}
		echo $text;
	}
}

function runquery($sql) {
	global $db, $tablenum, $lang, $strCreateTable;
	$sql = str_replace("\r", "\n", str_replace('wiki_', ' ' .DB_TABLEPRE, $sql));
	$ret = array ();
	$num = 0;
	foreach (explode(";\n", trim($sql)) as $query) {
		$queries = explode("\n", trim($query));
		foreach ($queries as $query) {
			$ret[$num] .= $query[0] == '#' || $query[0] . $query[1] == '--' ? '' : $query;
		}
		$num++;
	}
	unset ($sql);
	$strtip = "";
	foreach ($ret as $query) {
		$query = trim($query);
		if ($query) {
			if (substr($query, 0, 12) == 'CREATE TABLE') {
				$name = preg_replace("/CREATE TABLE\s*([a-z0-9_]+)\s*.*/is", "\\1", $query);
				$res = $db->query(createtable($query, DB_CHARSET));
				$strtip .= $strCreateTable . $name . " ... <font color=\"#0000EE\">{$lang['commonSuccess']}</font><br />";
				$tablenum++;
			} else {
				$res = $db->query($query);
			}
		}
	}
	return $strtip;
}

function createtable($sql, $dbcharset) {
	$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
	$type = in_array($type, array (
		'MYISAM',
		'HEAP'
	)) ? $type : 'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql) .
	 (mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET='".DB_CHARSET."'" : " TYPE=$type");
}

function replace_string($str_content) {
	if (strpos($str_content, "var") > 0) {
		$string = str_replace("[var]", "", $str_content);
		$string = str_replace("[/var]", "", $string);
		global $$string;
		$return = $$string;
	} else {
		$return = $str_content;
	}
	return $return;
}

function encode($string) {
	$string = trim($string);
	$string = str_replace("&", "&amp;", $string);
	$string = str_replace("'", "&#39;", $string);
	$string = str_replace("&amp;amp;", "&amp;", $string);
	$string = str_replace("&amp;quot;", "&quot;", $string);
	$string = str_replace("\"", "&quot;", $string);
	$string = str_replace("&amp;lt;", "&lt;", $string);
	$string = str_replace("<", "&lt;", $string);
	$string = str_replace("&amp;gt;", "&gt;", $string);
	$string = str_replace(">", "&gt;", $string);
	$string = str_replace("&amp;nbsp;", "&nbsp;", $string);

	$string = nl2br($string);
	return $string;
}

function check_email($email) {
	if ($email != "") {
		if (ereg("^.+@.+\\..+$", $email)) {
			return 1;
		} else {
			return 0;
		}
	} else {
		return 1;
	}
}

function check_user($username) {
	if ($username == "") {
		return 0;
	} else {
		if (preg_match("/[\s\'\"\\\]+/is", $username)) {
			return 0;
		}
		elseif (strlen(str_replace("/[^\x00-\xff]/g", "**", $username)) < 3) {
			return 0;
		} else {
			return 1;
		}
	}
}

function check_nickname($username) {
	if ($username == "") {
		return 0;
	} else {
		if (preg_match("/[\'\"\\\]+/is", $username)) {
			return 0;
		}
		elseif (strlen(str_replace("/[^\x00-\xff]/g", "**", $username)) < 3) {
			return 0;
		} else {
			return 1;
		}
	}
}

function check_password($password) {
	if ($password == "") {
		return 0;
	} else {
		if (preg_match("/[\'\"\\\]+/", $password) || strlen($password) < 5) {
			return 0;
		} else {
			return 1;
		}
	}
}

function hstrtoupper($str) {
	$i = 0;
	$total = strlen($str);
	$restr = '';
	for ($i = 0; $i < $total; $i++) {
		$str_acsii_num = ord($str[$i]);
		if ($str_acsii_num >= 97 AND $str_acsii_num <= 122) {
			$restr .= chr($str_acsii_num -32);
		} else {
			$restr .= chr($str_acsii_num);
		}
	}
	return $restr;
}

function subString($text, $start = 0, $limit = 12) {
	global $g_db_charset;
		if (strtolower($g_db_charset) == 'gbk') {
			$strlen = strlen($text);
			if ($start >= $strlen)return $text;
			$clen = 0;
			for ($i = 0; $i < $strlen; $i++, $clen++) {
				if (ord(substr($text, $i, 1)) > 0xa0) {
					if ($clen >= $start)
						$tmpstr .= substr($text, $i, 2);
					$i++;
				} else {
					if ($clen >= $start)
						$tmpstr .= substr($text, $i, 1);
				}
				if ($clen >= $start + $limit)
					break;
			}
			$text = $tmpstr;
		}else{
			$patten = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
			preg_match_all($patten, $text, $regs);
			$v = 0;
			$s = '';
			for ($i = 0; $i < count($regs[0]); $i++) {
				(ord($regs[0][$i]) > 129) ? $v += 2 : $v++;
				$s .= $regs[0][$i];
				if ($v >= $limit * 2) {
					break;
				}
			}
			$text = $s;
	}
	return $text;
}

function cleardir($dir) {
	if(!is_dir($dir))return;
	$directory = dir($dir);
	while ($entry = $directory->read()) {
		$filename = $dir . '/' . $entry;
		if (is_file($filename)) {
			@ unlink($filename);
		}
	}
	$directory->close();
}

function  file_writeable($file){
  if(is_dir($file)){
        $dir=$file;
		if($fp = @fopen("$dir/test.txt", 'w')) {
			@fclose($fp);
			@unlink("$dir/test.txt");
			$writeable = 1;
		} else {
			$writeable = 0;
		}
  }else{
  	  if(file_exists($file)){
	  	  if($fp = @fopen($file, 'a+')) {
				@fclose($fp);
				$writeable = 1;
			}else {
				$writeable = 0;
			}
  	  }
  }
  return $writeable;
}


function forceMkdir($path) {
  if (!file_exists($path))
	{
		forceMkdir(dirname($path));
	   mkdir($path, 0777);
	}
}

function random($length=32) {
	$hash = '';
	$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	$max = strlen($chars) - 1;
	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}
	
function generate_key(){
	$random = random(32);
	$info = md5($_SERVER['SERVER_SOFTWARE'].$_SERVER['SERVER_NAME'].$_SERVER['SERVER_ADDR'].$_SERVER['SERVER_PORT'].$_SERVER['HTTP_USER_AGENT'].time());
	$return = '';
	for($i=0; $i<64; $i++) {
		$p = intval($i/2);
		$return[$i] = $i % 2 ? $random[$p] : $info[$p];
	}
	return implode('', $return);
}

function copydir($srcdir, $dstdir) {
	if(!is_dir($dstdir)) mkdir($dstdir);
	if($curdir = opendir($srcdir)) {
		while($file = readdir($curdir)) {
			if($file != '.' && $file != '..') {
				$srcfile = $srcdir . '/' . $file;
				$dstfile = $dstdir . '/' . $file;
				if(is_file($srcfile)) {
					copy($srcfile, $dstfile);
				}
				else if(is_dir($srcfile)) {
					copydir($srcfile, $dstfile);
				}
			}
		}
		closedir($curdir);
	}
}

require HDWIKI_ROOT.'/model/plugin.class.php';
class pluginbase {
	var $db;
	var $model;
	function pluginbase(&$db){
		$this->db = $db;
		$this->model = new pluginmodel($this);
	}
	
	function install($identifier){
		require HDWIKI_ROOT."/plugins/$identifier/model/$identifier.class.php";
		$identifiermodel=$identifier.'model';
		$themodel = new $identifiermodel($this);
		$plugin=$themodel->install();
		$this->model->add_plugin($plugin);
	}
}

function clode_register_install(){
	include_once HDWIKI_ROOT.'/config.php';
	include_once HDWIKI_ROOT.'/lib/json.class.php';
	include_once HDWIKI_ROOT.'/lib/util.class.php';
	include_once HDWIKI_ROOT.'/lib/string.class.php';
	$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
	$site_url="http://".$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,-20);
	$site_name='我的HDWiki';
	$privateip = util::is_private_ip();
	if($privateip) {
		 // 内网IP  不开启
		$flag = 0;
	}else{
		if ('gbk' == strtolower(WIKI_CHARSET)){
			$sitename = string::hiconv($site_name, 'utf-8', 'gbk');
		} else {
			$sitename = $site_name;
		}
		$sitename = urlencode($sitename);
		$jsondata = array('siteName'=>$sitename,'siteUrl'=>$site_url);
		$jsondata='json='.$json->encode($jsondata);
		
		$flag = util::hfopen('http://union.hudong.com/sitelist/registerSite', '',$jsondata,'','','',2);;
	}
	if(empty($flag)) {
		require_once HDWIKI_ROOT.'/config.php';
		require_once HDWIKI_ROOT.'/lib/hddb.class.php';
		$db = new hddb(DB_HOST, DB_USER, DB_PW, DB_NAME, DB_CHARSET);
		$db->query("REPLACE INTO `".DB_TABLEPRE."setting` (`variable`, `value`) values ('cloud_search', '0')");
	}
	return $flag;
}

function get_db_config(){
	$return_arr = array();
	$hdwiki_root = dirname(dirname(__FILE__));
	$root_path = dirname($hdwiki_root);

	$path = array(
		$hdwiki_root.'/config.php',	// hdwiki
		$root_path.'/data/common.inc.php',	// dedecms
		$root_path.'/include/config.inc.php', // phpcms
		$root_path.'/data/config.inc.php',		// ucenter
		$root_path.'/ucenter/data/config.inc.php',		// ucenter
		$root_path.'/config.inc.php',		// DZ
		$root_path.'/config/config_global.php',		// DZX
		$root_path.'/data/sql_config.php',		// phpwind
	);
	
		foreach($path as $k=> $v) {
			if(file_exists($v)) {
				include_once($v);
				$db_config = array(
				array(
						// hdwiki
						'dbhost'=>defined('DB_HOST')?DB_HOST:'',
						'dbname'=>defined('DB_NAME')?DB_NAME:'',
						'dbuser'=>defined('DB_USER')?DB_USER:'',
						'dbpassword'=>defined('DB_PW')?DB_PW:'',
						'table_prefix'=>defined('DB_TABLEPRE')?DB_TABLEPRE:''
					),
					array(
						// dedecms
						'dbhost'=>$cfg_dbhost,
						'dbname'=>$cfg_dbname,
						'dbuser'=>$cfg_dbuser,
						'dbpassword'=>$cfg_dbpwd,
					),
					array(
						// phpcms
						'dbhost'=>defined('DB_HOST')?DB_HOST:'',
						'dbname'=>DB_NAME,
						'dbuser'=>DB_USER,
						'dbpassword'=>DB_PW,
					),
					array(
						// ucenter
						'dbhost'=>defined('UC_DBHOST')?UC_DBHOST:'',
						'dbhost_de'=>'UC_DBHOST',
						'dbname'=>UC_DBNAME,
						'dbuser'=>UC_DBUSER,
						'dbpassword'=>UC_DBPW,
					),
					array(
						// ucenter
						'dbhost'=>defined('UC_DBHOST')?UC_DBHOST:'',
						'dbname'=>UC_DBNAME,
						'dbuser'=>UC_DBUSER,
						'dbpassword'=>UC_DBPW,
					),
					array(
						// DZ
						'dbhost'=>$dbhost,
						'dbname'=>$dbname,
						'dbuser'=>$dbuser,
						'dbpassword'=>$dbpw,
					),
					array(
						// DZX
						'dbhost'=>$_config['db']['1']['dbhost'],
						'dbname'=>$_config['db']['1']['dbname'],
						'dbuser'=>$_config['db']['1']['dbuser'],
						'dbpassword'=>$_config['db']['1']['dbpw'],
					),
					array(
						    // phpwind
						    'dbhost'=>$dbhost,
						    'dbname'=>$dbname,
						    'dbuser'=>$dbuser,
						    'dbpassword'=>$dbpw,
					    )
				);
				$return_arr["dbhost"] = $db_config[$k]["dbhost"];
				$return_arr['dbname'] = $db_config[$k]['dbname'];
				$return_arr['dbuser'] = $db_config[$k]['dbuser'];
				$return_arr['dbpassword'] = $db_config[$k]['dbpassword'];
			}
			if(!empty($return_arr['dbhost'])) {
					break;
			}
			
			unset($db_config);
		}

		if(empty($return_arr['dbhost'])) $return_arr['dbhost'] = 'localhost';//为兼容微软 Webmatrix，此处去掉默认端口号
		if(empty($return_arr['dbuser'])) $return_arr['dbuser'] = '';
		if(empty($return_arr['dbpassword'])) $return_arr['dbpassword'] = '';
		if(empty($return_arr['dbname'])) $return_arr['dbname'] = 'wiki';
		if(empty($return_arr['table_prefix'])) $return_arr['table_prefix'] = 'wiki_';
		return $return_arr;
	}
?>
