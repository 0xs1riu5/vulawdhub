<?php
!defined('IN_HDWIKI') && exit('Access Denied');

require HDWIKI_ROOT.'/version.php';
require HDWIKI_ROOT.'/lib/file.class.php';
require HDWIKI_ROOT.'/lib/util.class.php';
require HDWIKI_ROOT.'/lib/hddb.class.php';
require HDWIKI_ROOT.'/lib/template.class.php';
require HDWIKI_ROOT.'/lib/cache.class.php';
require HDWIKI_ROOT.'/lib/functions.php';
require HDWIKI_ROOT.'/lib/rate.class.php';

class base {

	var $ip;
	var $time;
	var $db;
	var $view;
	var $cache;
	var $forward;
	var $user = array();
	var $setting = array();
	var $advertisement = array();
	var $channel = array();
	var $unpubdoc = array();
	var $theme=array();
	var $plugin = array();
	var $get = array();
	var $post = array();

	function base(& $get,& $post) {
		session_start();

		$this->time = time();
		$this->ip =util::getip();
		$this->get=& $get;
		$this->post=& $post;
		$this->init_db();
		$this->init_cache();
		$this->init_user();
		$this->init_unpubdoc();
		$this->init_template();
		$this->init_global();
		$this->init_mail();
		$this->init_admin();
		//$this->init_count();
		if($this->setting['auto_baiduxml'] == '1') {
			$this->check_baiduxml();
		}

		// 检测redis是否配置，如果REDIS_HOST, REDIS_PORT都已经配置, 就开启redis频率限制 
		defined('REDIS_HOST') && defined('REDIS_HOST') && $this->init_rate_limit();
	}
	
	function init_count() {
		$current_domain = '';
		$server_name = $_SERVER['SERVER_NAME'];
		$current_domain = $server_name;
		if($current_domain) {
			if($this->setting['wk_count']) {//note update
				$wk_count = unserialize($this->setting['wk_count']);
				$domain = $wk_count['domain'];
				
				$current_z_domain = substr($current_domain, strpos($current_domain, '.') + 1);
				$old_z_domain = substr($domain, strpos($domain, '.') + 1);
				
				if($current_z_domain != $old_z_domain) {//note 更换域名
					$key = $this->_get_key($current_domain);
					if($key) {//存在key
						$sql = "UPDATE ".DB_TABLEPRE."setting set `value`= '".serialize(array ('domain' => $current_domain, 'key' => $key))."' WHERE `variable`='wk_count';";
					}else {//不存在key
						$sql = "DELETE FROM ".DB_TABLEPRE."setting WHERE `variable`='wk_count';";
					}
					$this->db->query($sql);
					$this->cache->removecache('setting');
				}
			}else {//note insert 升级过来的
					$key = $this->_get_key($current_domain);
					if($key) {
						$value = serialize(array ('domain' => $current_domain, 'key' => $key));
						if($this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."setting WHERE variable = 'wk_count'")) {
							$this->cache->removecache('setting');
						} else {
							$sql = "INSERT INTO  ".DB_TABLEPRE."setting (`variable`, `value`) VALUES ('wk_count', '$value');";
							$this->db->query($sql);	
						}
					}
			}
		}
	}
	
	function _get_key($domain) {
		$key = '';
		if($domain) {
			$key = @util::hfopen('http://kaiyuan.hudong.com/count2/count.php?m=count&a=getkey&domain='.$domain, 0);
		}
		return $key;
	}
	
	function init_db(){
		$this->db=new hddb(DB_HOST, DB_USER, DB_PW, DB_NAME , DB_CHARSET , DB_CONNECT);
	}

	function init_cache(){
		$this->cache=new cache($this->db);
		$this->setting=$this->cache->load('setting');
		$this->advertisement=$this->cache->load('advertisement',1);
		$this->channel=$this->cache->load('channel',1);
		if($this->channel){
			foreach($this->channel as $channel){
				if($channel['available'] == 1){
					$fchannel[$channel['position']][] = $channel;
				}
			}

            if (isset($fchannel[3]) && isset($fchannel[1])) {
                $tempArr = array();
                foreach ($fchannel[1] as $item) {
                    $tempArr[] = $item['name'];
                }

                foreach ($fchannel[3] as $key => $item) {
                    if (in_array($item['name'], $tempArr)) {
                        unset($fchannel[3][$key]);
                    }
                }
            }
		}

		$this->channel = $fchannel;
		
		$this->theme= $this->cache->load('theme',1);
		$this->plugin= $this->load_plugincache();
		//ucenter
		if(isset($this->setting['ucopen']) && $this->setting['ucopen']){
			$ucurl=HDWIKI_ROOT.'/api/ucconfig.inc.php';
			file_exists($ucurl) && include($ucurl);
		}
		defined('UC_OPEN') || define('UC_OPEN',false);
		if(UC_OPEN){
			include(HDWIKI_ROOT.'/api/uc_client/client.php');
			$this->load('ucenter');
		}
	}


	function load_plugincache(){
		$plugindata=$this->cache->getcache('plugin');
		if(!$plugindata){
			$this->load('plugin');
			$plugindata=$_ENV['plugin']->read_all();
			$this->cache->writecache('plugin',$plugindata);
		}
		return $plugindata;
	}

	function init_template(){
		$GLOBALS['theme']=$this->hgetcookie('theme');
		if(!isset($GLOBALS['theme'])){
			$GLOBALS['theme']=$this->setting['theme_name'];
		}
		$this->view=new template($GLOBALS['theme']);
		$this->view->setlang($this->setting['lang_name'],'front');

		//passport include
		$ppfile=HDWIKI_ROOT.'/data/passport.inc.php';
		if(file_exists($ppfile)){
			include($ppfile);
			if(defined('PP_OPEN')&&PP_OPEN){
				$this->forward=$_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER']:WIKI_URL;
				if(PP_TYPE=='client')
				$this->view->assign('pp_api',PP_API.PP_LOGIN);
			}
		}
		
		$this->view->assign('pluginlist', array_values($this->plugin) );
		$this->view->assign('channellist',$this->channel);
		$this->view->assign('themelist',$this->theme);
		$this->view->assign('user',$this->user);
		$this->view->assign('unpubdoc',$this->unpubdoc);

		$referer=empty($_SERVER["QUERY_STRING"]) ? '' : "-".$this->setting['seo_prefix'].$_SERVER["QUERY_STRING"];
		$this->view->assign('referer',urlencode($referer) );
		$this->view->assign('timenow',$this->date($this->time,3));
		$this->view->assign('setting',$this->setting);
		$this->view->assign('navtitle','');
		
		$header_regulars = '';
		if ($this->checkable('doc-edit')) $header_regulars .= ',doc-edit';
		if ($this->checkable('doc-sandbox')) $header_regulars .= ',doc-sandbox';
		if ($this->checkable('doc-create')) $header_regulars .= ',doc-create';
		
		$this->view->assign('header_regulars', $header_regulars);

		$this->view->assign('theme',$GLOBALS['theme']);
		$this->view->assign('style',$GLOBALS['theme']);
		@$hotsearch=unserialize($this->setting['hotsearch']);
		$hotsearch=is_array($hotsearch)?$hotsearch:array();
		$this->view->assign('hotsearch',$hotsearch);
		// 云搜索
		$this->view->assign('cloudsearchhead',$this->setting['cloud_search']);
		$this->view->assign('adminlogin',$this->checkable('admin_main-login') );
		//统计
		@$wk_count=unserialize($this->setting['wk_count']);
		$this->view->assign('wk_count',$wk_count);
	}

	function load($model, $base = NULL) {
		$base = $base ? $base : $this;
		if(empty($_ENV[$model])) {
			require HDWIKI_ROOT."/model/$model.class.php";
			eval('$_ENV[$model] = new '.$model.'model($base);');
		}
		return $_ENV[$model];
	}

	function loadplugin($model, $identifier=NULL) {
		$identifier = $identifier ? $identifier : $model ;
		if(empty($_ENV[$model])) {
			require HDWIKI_ROOT."/plugins/$identifier/model/$model.class.php";
			eval('$_ENV[$model] = new '.$model.'model($this);');
		}
		return $_ENV[$model];
	}

	function init_user() {
		$sid=$this->hgetcookie('sid');//notice error 
		$auth=$this->hgetcookie('auth');
		list($uid,$password) = empty($auth) ? array(0,0) : string::haddslashes(explode("\t", $this->authcode($auth, 'DECODE')), 1);
		if(!$sid){
			$sid=util::random(6);
			$this->hsetcookie('sid',$sid,24*3600*365, true);
		}
		if($uid){
			if($password==''){
				$sql='select u.*, g.grouptitle,g.regulars,g.default,g.type,g.creditslower,g.creditshigher,g.stars,g.color,g.groupavatar from '.DB_TABLEPRE.'user u,'.DB_TABLEPRE.'usergroup g where  u.uid='.$uid.' and g.groupid=1';
			}else{
				$sql='select u.*, g.* from '.DB_TABLEPRE.'user u,'.DB_TABLEPRE.'usergroup g where  u.uid='.$uid.' and u.groupid=g.groupid';
			}
			$user=$this->db->fetch_first($sql);
			if($password==$user['password']){
				$this->user=$user;
				UC_OPEN&&$_ENV['ucenter']->avatar();
			}
		}
		if(!(bool)$this->user){
			$this->user=$this->db->fetch_first('select * from '.DB_TABLEPRE.'usergroup where groupid=1');
			$this->user['uid']=0;
		}
		$this->user['sid']=$sid;
	}
	
	function init_global(){
		$this->load('global');
		$_ENV['global']->checkbanned();
		if($this->user['uid']){
			$_ENV['global']->newpms($this->user['uid']);
		}
		$_ENV['global']->adv_filter($this->advertisement);
		$this->get[2] = empty($this->get[2]) ? NULL : $this->get[2];
		$_ENV['global']->writelog($this->get[0].'-'.$this->get[1],$this->get[2]);
		$this->load('tag');
		$this->load('datacall');
		$this->load('block');
	}
	
	function init_unpubdoc(){
		if(isset($this->user['uid'])){
			$this->load('doc');
			$this->unpubdoc = $_ENV['doc']->get_unpubdoc($this->user['uid']);
		}
	}
	
	function message($message, $redirect = '', $type = 1) {
		$this->view->assign('message', $message);
		$this->view->assign('redirect', $redirect);
		if($type == 0) {
			$this->view->display('message');
		} else if($type == 1){
			$this->view->display('admin_message');
		}else {
			$this->view->assign('ajax', 1);
			$this->view->assign('charset', WIKI_CHARSET);
			$this->view->display('message');
		}
		exit;
	}

	function header($url=''){
		if(empty($url)){
			header("Location: ".WIKI_URL);
		}else{
			header("Location:{$this->setting['seo_prefix']}$url{$this->setting['seo_suffix']}");
		}
	}

	function date($time, $type = 3, $friendly=0) {
		$format[] = $type & 2 ? (!empty($this->setting['date_format']) ? $this->setting['date_format'] : 'Y-n-j') : '';
		$format[] = $type & 1 ? (!empty($this->setting['time_format']) ? $this->setting['time_format'] : 'H:i') : '';
		$timeoffset=$this->setting['time_offset']*3600+$this->setting['time_diff']*60;
		$timestring=gmdate(implode(' ', $format), $time + $timeoffset);
		if($friendly){
			$dtime=$this->time -$time;
			$dday=intval(date('Ymd',$this->time))-intval(date('Ymd',$time));
			$dyear=intval(date('Y',$this->time))-intval(date('Y',$time));
			if($dtime<60){
				$timestring= $dtime.$this->view->lang['beforeSeconds'];
			}elseif($dtime<3600){
				$timestring= intval($dtime/60).$this->view->lang['beforeMinutes'];
			}elseif($dtime>=3600 && 0==$dday){
				$timestring= intval($dtime/3600).$this->view->lang['beforeHours'];
			}
		}
		return $timestring;
	}

	function checkable($url){
		if(4==$this->user['groupid']){
			return true;
		}
		$anonymous_regular='user-boxlogin|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-getpass|user-code|hdapi-default|hdapi-uniontitle|momo-default';
		$regulars = explode('|', $anonymous_regular.'|'.$this->user['regulars']);
		return in_array($url,$regulars);
	}

	function hsetcookie($var, $value, $life = 0, $httponly=false) {
		$domain=$this->setting['cookie_domain']?$this->setting['cookie_domain']:'';
		$cookiepre=$this->setting['cookie_pre']?$this->setting['cookie_pre']:'hd_';
		setcookie($cookiepre.$var, $value,$life ? $this->time + $life : 0, '/',$domain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0, $httponly);
	}
	
	function hgetcookie($var) {
		$cookiepre=!empty($this->setting['cookie_pre'])?$this->setting['cookie_pre']:'hd_';
		return isset($_COOKIE[$cookiepre.$var]) ? $_COOKIE[$cookiepre.$var] : NULL ;
 	}
	
	function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
		$ckey_length = 4;
		$key = md5($key ? $key : $this->setting['auth_key']);
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
	
	function multi($num, $perpage, $curpage, $mpurl, $maxpages = 0, $page = 10, $autogoto = TRUE, $simple = FALSE) {
		global $maxpage;
		$multipage = '';
		$ajaxtarget = NULL;
		$seo_prefix=$this->setting['seo_prefix'];
		$seo_suffix=$this->setting['seo_suffix'];
		$mpurl = $seo_prefix.$mpurl.'-';
		$realpages = 1;
		if(is_numeric($num) && $num > $perpage) {
			$offset = 2;
			$realpages = ceil($num / $perpage);
			$pages = $maxpages && $maxpages < $realpages ? $maxpages : $realpages;
			if($page > $pages) {
				$from = 1;
				$to = $pages;
			} else {
				$from = $curpage - $offset;
				$to = $from + $page - 1;
				if($from < 1) {
					$to = $curpage + 1 - $from;
					$from = 1;
					if($to - $from < $page) {
						$to = $page;
					}
				} elseif($to > $pages) {
					$from = $pages - $page + 1;
					$to = $pages;
				}
			}
			$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="'.$mpurl.'1"'.$seo_suffix.' >1 ...</a>' : '').
				($curpage > 1 && !$simple ? '<a href="'.$mpurl.($curpage - 1).$seo_suffix.'" >&lsaquo;&lsaquo;</a>' : '');
			for($i = $from; $i <= $to; $i++) {
				$multipage .= $i == $curpage ? '<span class="gray">'.$i.'</span>' :'<a href="'.$mpurl.$i.$seo_suffix.'" >'.$i.'</a>';
			}
			$multipage .= ($curpage < $pages && !$simple ? '<a href="'.$mpurl.($curpage + 1).$seo_suffix.'" >&rsaquo;&rsaquo;</a>' : '').
				($to < $pages ? '<a href="'.$mpurl.$pages.$seo_suffix.'" >... '.$realpages.'</a>' : '').
				(!$simple && $pages > $page && !$ajaxtarget ? '<kbd><input type="text" name="custompage" size="3" onkeydown="if(event.keyCode==13) {window.location=\''.$mpurl.'\'+this.value+\''.$seo_suffix.'\'; return false;}" /></kbd>' : '');

			$multipage = $multipage ? (!$simple ? '<span class="gray">&nbsp;'.$this->view->lang['commonTotal'].$num.$this->view->lang['commonTotalNum'].'&nbsp;</span>' : '').$multipage : '';
		}
		$maxpage = $realpages;
		return $multipage;
	}
	
	function init_mail() {
		if(file_exists(HDWIKI_ROOT.'/data/mail.exists')) {
			$this->load('mail');
			$_ENV['mail']->send();
		}
	}
	
	function init_admin(){
		if(substr($this->get[0],0,6)=='admin_' && !( $this->get[0]=='admin_main' && ($this->get[1]=='default' || $this->get[1]=='login'))){
			$sid=$this->user['sid'];
			$isadmin=$this->db->result_first("SELECT islogin FROM ".DB_TABLEPRE."session  WHERE sid='$sid'");
			if($isadmin != 2){
				header("Location:index.php?admin_main-login");
				exit();
			}
		}
	}
	
	function check_baiduxml() {
		$this->load('sitemap');
		$_ENV['sitemap']->autoupdate_baiduxml();
	}
	
	/**
	 * @breif 判断是移动端还是pc端
	 * @return boolean
	 */
	function isMobile() {
	    // 判断手机发送的客户端标志
	    $agent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
	    if ($agent) {
	        $clientKeyWords = array(
	                'nokie', 'sony', 'ericsson', 'mot', 'samsung', 'htc','wap',
	                'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic',
	                'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry',
	                'meizu', 'android', 'netfront', 'symbian', 'ucweb',
	                'windowsce', 'palm', 'operamini', 'operamobi', 'midp',
	                'opera mobi', 'openwave', 'nexusone', 'cldc', 'mobile'
	        );

	        if (preg_match("/(" . implode("|", $clientKeyWords) . ")/i", $agent) && strpos($agent, 'ipad') == false ) {
	            return true;
	        }
	    }
	    return false;
	}

	/**
	 * @breif 验证 CSRF_TOKEN
	 * 该函数需要在控制器方法中显式调用，才起作用
	 * @return boolean
	 */
	function check_csrf_token() {
		$method = strtoupper($_SERVER['REQUEST_METHOD']);
		$bool = true;
        
		if (in_array($method, array('POST', 'PUT'))) {
			$token = '';

			if (isset($this->post['_token'])) {
				$token = $this->post['_token'];
			} else if (isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
				$token = $_SERVER['HTTP_X_CSRF_TOKEN'];
			}

			$bool = ($token && csrf_token() === $token);
		}
		return $bool;
	}
	
	/**
	 * 频率限制
	 * @param array $param
	 *  'key'      (用户名/IP等)
	 *  'requests' (请求次数，默认10次)
	 *  'seconds'  (秒数, 默认60秒)
	 *
	 * @return boolean true(未超出限制)/false(已超出限制)
	 *
	 */
	function rate_limit($param) {
	    $key = isset($param['key']) ? $param['key'] : $this->ip;
	    $requests = isset($param['requests']) ? intval($param['requests']) : 30;
	    $seconds = isset($param['seconds']) ? intval($param['seconds']) : 60;
	
	    $adapter = new RateLimitAdapterRedis(REDIS_HOST, REDIS_PORT);
	    $rateLimit = new RateLimit($key, $requests, $seconds, $adapter);
	
	    return $rateLimit->check($key) > 0 ? true : false;
	}
	
	// 默认频率限制
	function init_rate_limit() {
	    $method = strtoupper($_SERVER['REQUEST_METHOD']);
	
	    if ('GET' === $method) {
	        $param = array(
	                'key' => "GET:{$this->ip}",
	                'requests' => 600,
	                'seconds' => 60,
	        );
	    } else {
	        $param = array(
	                'key' => "POST:{$this->ip}",
	                'requests' => 200,
	                'seconds' => 60,
	        );
	    }
	
	    if (!$this->rate_limit($param)) {
	        exit('请求过快，请稍后再试！');
	    }
	}
	
}
?>