<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('api_ucenter');
if(!function_exists('file_put_contents')){ function file_put_contents($filename, $s)
{
	$fp = @fopen($filename, 'w');
	@fwrite($fp, $s);
	@fclose($fp);
	return TRUE;
}}
require_once(DEDEINC.'/dedetemplate.class.php');
if(file_exists(DEDEROOT.'/uc_client/client.php'))
{
	if(!defined('UC_API')) define('UC_API', '');
	include_once DEDEROOT.'/uc_client/client.php';
}
else
{
	ShowMsg('请安装UCenter模块!',-1);
	exit();
}

$dopost = api_gpc('dopost','R');

$uc = new api_ucenter($dopost);

class api_ucenter
{
	var $action;
	var $dtp;
	var $config;
	
	//php5构造函数PHP>=5.0
	function __construct($ac = '')
	{
		$action = 'uc_'.(empty($ac)||(!in_array($ac,array('install','edit'))) ? 'show' : trim($ac));
		$this->dtp = new DedeTemplate();
		$this->config = DEDEINC.'/common.inc.php';
		$this->$action();
	}
	
	//构造类成员PHP<5.0
	function api_ucenter($ac = '')
	{
		$this->__construct($ac);
	}
	
	function uc_install()
	{
		$uc_setings = api_gpc('uc_setings','P');
		
		if(!isset($uc_setings['authkey']) || empty($uc_setings['authkey']))
		{
			ShowMsg('请填写uc创始人密码!',-1);
			exit();
		}
		
		$uc_setings['ucapi'] = preg_replace("/\/$/", '', trim($uc_setings['ucapi']));
		
		if(empty($uc_setings['ucapi']) || !preg_match("/^(http:\/\/)/i", $uc_setings['ucapi']))
		{
			ShowMsg('请填正确的服务端地址以http://开头!',-1);
			exit();
		}
		else
		{
			if(!$uc_setings['ucip'])
			{
				$temp = @parse_url($uc_setings['ucapi']);
				$uc_setings['ucapi'] = gethostbyname($temp['host']);
				if(ip2long($uc_setings['ucapi']) == -1 || ip2long($uc_setings['ucapi']) === FALSE)
				{
					$uc_setings['ucip'] = '127.0.0.1';
				}
			}
		}
		
		$ucinfo = api_fopen($uc_setings['ucapi'].'/index.php?m=app&a=ucinfo', 500, '', '', 1, $uc_setings['ucip']);
		
		list($status, $ucversion, $ucrelease, $uccharset, $ucdbcharset, $apptypes) = explode('|', $ucinfo);
		
		if($status != 'UC_STATUS_OK')
		{
			ShowMsg('uc服务端地址无效,请仔细检查您安装的uc服务端地址!',-1);
			exit();
		}
		else
		{
			$ucdbcharset = strtolower($ucdbcharset ? str_replace('-', '', $ucdbcharset) : $ucdbcharset);
			if(UC_CLIENT_VERSION > $ucversion)
			{
				ShowMsg('uc服务端版本不一致,您当前的uc客服端版本为:'.UC_CLIENT_VERSION.',而服务端版本为:'.$ucversion.'!',-1);
				exit();	
			}
			elseif($ucdbcharset != 'gbk')
			{
				ShowMsg('uc服务端编码与DedeCMS编码不一致!要求您的uc服务端编码为:gbk编码.',-1);
				exit();	
			}
			//标签应用模板
			$app_tagtemplates = 'apptagtemplates[template]='.urlencode('<a href="{url}" target="_blank">{title}</a>').'&'.
			'apptagtemplates[fields][title]='.urlencode('标题').'&'.
			'apptagtemplates[fields][writer]='.urlencode('作者').'&'.
			'apptagtemplates[fields][pubdate]='.urlencode('时间').'&'.
			'apptagtemplates[fields][url]='.urlencode('地址');
			
			$postdata = 'm=app&a=add&ucfounder=&ucfounderpw='.urlencode($uc_setings['authkey']).'&apptype=OTHER&appname='.urlencode($GLOBALS['cfg_webname']).'&appurl='.urlencode($GLOBALS['cfg_basehost']).'&appip=&appcharset=gbk&appdbcharset=gbk&'.$app_tagtemplates.'&release='.UC_CLIENT_RELEASE;
		
			$ucconfig = api_fopen($uc_setings['ucapi'].'/index.php', 500, $postdata, '', 1, $uc_setings['ucip']);
			
			if(strstr($ucconfig,'<?xml'))
			{
				$temp = explode('<?xml', $ucconfig);
				$ucconfig = $temp[0]; unset($temp);
			}
			
			if(empty($ucconfig))
			{
				ShowMsg('请填写有效的配置信息!',-1);
				exit();
			}
			elseif($ucconfig == '-1')
			{
				ShowMsg('创始人密码错误!',-1);
				exit();
			}
			else
			{
				list($appauthkey, $appid) = explode('|', $ucconfig);
				if(empty($appauthkey) || empty($appid))
				{
					ShowMsg('数据获取失败!',-1);
					exit();
				}
				elseif($succeed = api_write_config($ucconfig."|".$uc_setings['ucapi']."|".$uc_setings['ucip'], $this->config))
				{
					ShowMsg('安装成功!',-1);
					exit();
				}
				else
				{
					ShowMsg('写入配置数据失败!'.$this->config.' 请设置可写权限!',-1);
					exit();
				}
			}
		}
	}
	
	function uc_edit()
	{
		$uc_setings = api_gpc('uc_setings','P');		
		$uc_dbpass = $uc_setings['dbpass'] == '********' ? UC_DBPW : $uc_setings['dbpass'];	
		$fp = fopen($this->config, 'r');
		$content = fread($fp, filesize($this->config));
		$content = trim($content);
		$content = substr($content, -2) == '?>' ? substr($content, 0, -2) : $content;
		$content = strstr($content, '_|cfg_|GLOBALS') ? str_replace('_|cfg_|GLOBALS','cfg_|GLOBALS',$content) : $content;
		fclose($fp);
		
		$connect = '';		
		if($uc_setings['connect'])
		{
			$uc_dblink = @mysql_connect($uc_setings['dbhost'], $uc_setings['dbuser'], $uc_dbpass, 1);
			if(!$uc_dblink)
			{
				ShowMsg('数据库连接失败!',-1);
				exit();
			}else{
				mysql_close($uc_dblink);
			}
			
			$connect = 'mysql';
			$content = api_insert_config($content, "/define\('UC_DBHOST',\s*'.*?'\);/i", "define('UC_DBHOST', '".$uc_setings['dbhost']."');");
			$content = api_insert_config($content, "/define\('UC_DBUSER',\s*'.*?'\);/i", "define('UC_DBUSER', '".$uc_setings['dbuser']."');");
			$content = api_insert_config($content, "/define\('UC_DBPW',\s*'.*?'\);/i", "define('UC_DBPW', '".$uc_dbpass."');");
			$content = api_insert_config($content, "/define\('UC_DBNAME',\s*'.*?'\);/i", "define('UC_DBNAME', '".$uc_setings['dbname']."');");
			$content = api_insert_config($content, "/define\('UC_DBTABLEPRE',\s*'.*?'\);/i", "define('UC_DBTABLEPRE', '`".$uc_setings['dbname'].'`.'.$uc_setings['dbtablepre']."');");
		}
		
		$content = api_insert_config($content, "/define\('UC_CONNECT',\s*'.*?'\);/i", "define('UC_CONNECT', '$connect');");
		$content = api_insert_config($content, "/define\('UC_KEY',\s*'.*?'\);/i", "define('UC_KEY', '".$uc_setings['authkey']."');");
		$content = api_insert_config($content, "/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '".$uc_setings['ucapi']."');");
		$content = api_insert_config($content, "/define\('UC_IP',\s*'.*?'\);/i", "define('UC_IP', '".$uc_setings['ucip']."');");
		$content = api_insert_config($content, "/define\('UC_APPID',\s*'?.*?'?\);/i", "define('UC_APPID', '".UC_APPID."');");
		$content .= '?>';
		
		if($fp = @fopen($this->config, 'w'))
		{
			@fwrite($fp, trim($content));
			@fclose($fp);
			ShowMsg('配置已经更改!',-1);
			exit();
		}else{
			ShowMsg('写入配置数据失败!'.$this->config.' 请设置可写权限!',-1);
			exit();
		}
	}	
	
	function uc_show()
	{
		$this->dtp->Assign('uc_config_file',$this->config);
		
		if(!defined('UC_APPID'))
		{
			$this->dtp->LoadTemplate(DEDEADMIN.'/templets/api_ucenter_install.htm');
		}
		else
		{
			$uc_api_open = false;			
			$ucapparray = uc_app_ls();
			foreach($ucapparray as $apparray)
			{
				if($apparray['appid'] == UC_APPID)
				{
					$uc_api_open = true;
					break;
				}
			}
	
			if(!$uc_api_open)
			{
				ShowMsg("DedeCMS没找到正确的uc配置！",-1);
				exit();
			}
			
	
			list($dbname,$dbtablepre) = explode('.',str_replace('`','',UC_DBTABLEPRE));	
			$uc_setings = array('appid' => UC_APPID, 'ucapi' => UC_API, 'connect' => UC_CONNECT, 'dbhost' => UC_DBHOST, 'dbuser' => UC_DBUSER,'dbpass' => UC_DBPW, 'dbname' => $dbname, 'dbtablepre' => $dbtablepre,'ucip' => UC_IP,'authkey' => UC_KEY);
		
			$this->dtp->Assign('uc_setings',$uc_setings);
			$this->dtp->LoadTemplate(DEDEADMIN.'/templets/api_ucenter_edit.htm');
		}
		$this->dtp->Display();
		exit();
	}
}
/*
class uc_function{...}
*/
function api_fopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE)
{
	$return = '';
	$matches = parse_url($url);
	$host = $matches['host'];
	$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
	$port = !empty($matches['port']) ? $matches['port'] : 80;
	if($post)
	{
		$out = "POST $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= 'Content-Length: '.strlen($post)."\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
		$out .= $post;
	}else{
		$out = "GET $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
	}

	$fp = @fsockopen(($host ? $host : $ip), $port, $errno, $errstr, $timeout);
	if(!$fp)
	{
		return '';
	}else{
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);
		if(!$status['timed_out'])
		{
			while (!feof($fp))
			{
				if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n"))
				{
					break;
				}
			}

			$stop = false;
			while(!feof($fp) && !$stop)
			{
				$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
				$return .= $data;
				if($limit)
				{
					$limit -= strlen($data);
					$stop = $limit <= 0;
				}
			}
		}
		@fclose($fp);
		return $return;
	}
}

function api_write_config($config, $file)
{
	$success = false;
	list($appauthkey, $appid, $ucdbhost, $ucdbname, $ucdbuser, $ucdbpw, $ucdbcharset, $uctablepre, $uccharset, $ucapi, $ucip) = explode('|', $config);
		
	if($content = file_get_contents($file))
	{
		$content = trim($content);
		$content = substr($content, -2) == '?>' ? substr($content, 0, -2) : $content;
		$content = strstr($content, '_|cfg_|GLOBALS') ? str_replace('_|cfg_|GLOBALS','cfg_|GLOBALS',$content) : $content;
		$link = mysql_connect($ucdbhost, $ucdbuser, $ucdbpw, 1);
		$uc_connnect = $link && mysql_select_db($ucdbname, $link) ? 'mysql' : '';
		$content = api_insert_config($content, "/define\('UC_CONNECT',\s*'.*?'\);/i", "define('UC_CONNECT', '$uc_connnect');");
		$content = api_insert_config($content, "/define\('UC_DBHOST',\s*'.*?'\);/i", "define('UC_DBHOST', '$ucdbhost');");
		$content = api_insert_config($content, "/define\('UC_DBUSER',\s*'.*?'\);/i", "define('UC_DBUSER', '$ucdbuser');");
		$content = api_insert_config($content, "/define\('UC_DBPW',\s*'.*?'\);/i", "define('UC_DBPW', '$ucdbpw');");
		$content = api_insert_config($content, "/define\('UC_DBNAME',\s*'.*?'\);/i", "define('UC_DBNAME', '$ucdbname');");
		$content = api_insert_config($content, "/define\('UC_DBCHARSET',\s*'.*?'\);/i", "define('UC_DBCHARSET', '$ucdbcharset');");
		$content = api_insert_config($content, "/define\('UC_DBTABLEPRE',\s*'.*?'\);/i", "define('UC_DBTABLEPRE', '`$ucdbname`.$uctablepre');");
		$content = api_insert_config($content, "/define\('UC_DBCONNECT',\s*'.*?'\);/i", "define('UC_DBCONNECT', '0');");
		$content = api_insert_config($content, "/define\('UC_KEY',\s*'.*?'\);/i", "define('UC_KEY', '$appauthkey');");
		$content = api_insert_config($content, "/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '$ucapi');");
		$content = api_insert_config($content, "/define\('UC_CHARSET',\s*'.*?'\);/i", "define('UC_CHARSET', '$uccharset');");
		$content = api_insert_config($content, "/define\('UC_IP',\s*'.*?'\);/i", "define('UC_IP', '$ucip');");
		$content = api_insert_config($content, "/define\('UC_APPID',\s*'?.*?'?\);/i", "define('UC_APPID', '$appid');");
		$content = api_insert_config($content, "/define\('UC_PPP',\s*'?.*?'?\);/i", "define('UC_PPP', '20');");
		$content .= "\r\n".'?>';
		
		if(@file_put_contents($file, $content))
		{
			$success = true;
		}
	}
	return $success;
}

function api_insert_config($s, $find, $replace)
{
	if(preg_match($find, $s))
	{
		$s = preg_replace($find, $replace, $s);
	}else{
		// 插入到最后一行
		$s .= "\r\n".$replace;
	}
	return $s;
}

function api_gpc($k, $var='R')
{
	switch($var)
	{
		case 'G': $var = &$_GET; break;
		case 'P': $var = &$_POST; break;
		case 'C': $var = &$_COOKIE; break;
		case 'R': $var = &$_REQUEST; break;
	}
	return isset($var[$k]) ? $var[$k] : NULL;
}

if(!function_exists('file_put_contents')){ function file_put_contents($filename, $s)
{
	$fp = @fopen($filename, 'w');
	@fwrite($fp, $s);
	@fclose($fp);
	return TRUE;
}}
?>