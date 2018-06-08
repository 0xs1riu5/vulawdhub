<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load('user');
		$this->load('db');
		$this->load('setting');
		$this->view->setlang($this->setting['lang_name'],'back');
	}

	function dodefault(){
		$this->dologin();
	}

	function doupdate() { 
		include_once HDWIKI_ROOT.'/lib/xmlparser.class.php';
		$sendversion = base64_encode( serialize( array('v'=>HDWIKI_VERSION,'r'=>HDWIKI_RELEASE,'c'=>WIKI_CHARSET,'u'=>WIKI_URL) ) );
		$xmlfile="http://kaiyuan.hudong.com/update.php?v={$sendversion}";
		$xmlparser = new XMLParser();
		$xmlnav=$xmlparser->parse($xmlfile);
		$isupdate = $xmlnav[0]['child'][0]['content'];
		$version = $xmlnav[0]['child'][1]['content'];
		$release = $xmlnav[0]['child'][2]['content'];
		$url = $xmlnav[0]['child'][3]['content'];
		$description = $xmlnav[0]['child'][4]['content'];
		$json = '{"isupdate":"'.$isupdate.'","version":"'.$version.'","release":"'.$release.'","url":"'.$url.'","description":"'.$description.'"}';
		echo $json;
	}

 	function dologin(){
		$admin_mainframe = $this->hgetcookie('querystring') ? $this->hgetcookie('querystring'):'admin_main-mainframe';
		$this->view->assign('admin_mainframe', $admin_mainframe);
		$shortcut = $shortlist = null;
		if(isset($this->setting['shortcut'])){
			$shortcut=explode(';',$this->setting['shortcut']);
			foreach($shortcut as $link){
				if($link){
					$short=explode(',',$link);
					$shortlist[]=$short;
				}
			}
		}
		$this->view->assign('shortlist',$shortlist);
		$islogin=$_ENV['user']->is_login();
 		if(2==$islogin){
 			$this->view->display("admin_main");
 			exit;
 		}
 		if($islogin){
			if(!isset($this->post['password'])){
				$this->view->display("admin_login");
			}else{
				if( $this->user['password'] != md5($this->post['password']) ){
					$this->view->assign('loginmsg',$this->view->lang['commonPasswdIsWrong']);
					$this->view->display("admin_login");
					exit;
				}else{
					$session['islogin']=2;
					$_ENV['user']->update_session($session,$this->user['sid']);
					$this->view->assign('env',$this->env());
					if(!$this->cache->isvalid('admindiary',3600*24)){
					    $this->view->assign('diary','index.php?admin_main-diary');
					    $this->cache->writecache('admindiary',' ');
					}
					$this->view->assign('env',$this->env());
					$this->view->display("admin_main");
					exit;
				}
			}
		}else{
			$this->header('user-login');
		}
	}

 	function dologout(){
 		$session['islogin']=1;
		$_ENV['user']->update_session($session,$this->user['sid']);
		$this->header();
	}

	function domainframe(){
		$attachsize = $uploadssize = 0;
		$sizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
		$sys['server'] = PHP_OS.' / '.$_SERVER['SERVER_SOFTWARE'];
		if (strpos($sys['server'],'PHP') === false){
			$sys['server'] .= ' / PHP v'.PHP_VERSION;
		}
		$mysql=$this->db->fetch_first('SELECT VERSION() AS version');
		$sys['mysql']=$mysql['version'];
		$dbsize = $_ENV['db']->databasesize();
		if($dbsize == 0) {
			$dbsize = "0 Bytes";
		}else{
	 		$dbsize =round($dbsize/pow(1024, ($i = floor(log($dbsize, 1024)))), 2) . $sizename[$i];
		}

		$adminlist=$_ENV['user']->get_users('groupid',4);

		$this->view->assign('show_upgrade', $this->checkable('admin_upgrade'));
		$this->view->assign('adminlist', $adminlist);
		$this->view->assign('newunewd_on', $this->setting['verify_doc'] == -1);
		$this->view->assign('sys', $sys);
		$this->view->assign('attsize', $attachsize);
		$this->view->assign('uploadsize', $uploadssize);
		$this->view->assign('dbsize', $dbsize);
		$this->view->display("admin_mainframe");
	}

	function env(){
		$adminmainenv = $this->cache->getcache('adminmainenv');
		if ($adminmainenv == date('W')) return '';
		$this->load('doc');

		$url = $this->setting['app_url'].'/count2/'.'en'.'v.'.'php'.'?'.'q';
		$mysql=$this->db->fetch_first('SELECT VERSION() AS version');
		$maxdid=$_ENV['doc']->get_maxid();
		$info = array();
		$info[0] = PHP_OS;
		$info[1] = $_SERVER['SERVER_SOFTWARE'];
		$info[2] = PHP_VERSION;
		$info[3] = $mysql['version'];
		$info[4] = function_exists('phpinfo')? '1':'0';
		if (function_exists('extension_loaded')){
			$info[5] = extension_loaded('gd')? '1':'0';
			$info[6] = extension_loaded('iconv')? '1':'0';
			$info[7] = extension_loaded('xml')? '1':'0';
			$info[8] = extension_loaded('json')? '1':'0';
			$info[9] = extension_loaded('zlib')? '1':'0';
		}else{
			$info[5] = function_exists('imagecreatetruecolor')? '1':'0';
			$info[6] = function_exists('iconv')? '1':'0';
			$info[7] = function_exists('xml_parse')? '1':'0';
			$info[8] = function_exists('json_encode')? '1':'0';
			$info[9] = function_exists('gzopen')? '1':'0';
		}

		$info[10] = $maxdid;

		if (function_exists('ini_get')){
			$info[11] = ini_get('safe_mode')?'1':'0';
			$info[12] = ini_get('memory_limit');
			$info[13] = ini_get('post_max_size');
			$info[14] = ini_get('upload_max_filesize');
			$info[15] = ini_get('allow_url_fopen')?'1':'0';
		}else{
			$info[11] = '1';
			$info[12] = '0';
			$info[13] = '0';
			$info[14] = '0';
			$info[15] = '0';
		}

		$info = implode(';',$info);
		$this->cache->writecache('adminmainenv',date('W'));
		return $url.'='.chr(rand(65,90)).rawurlencode(base64_encode($info));
	}

	function dodiary(){
	    $adminmaindiary = $this->cache->getcache('adminmaindiary',3600*25);
	    //if (!empty($adminmaindiary)) return '';
	    $this->load('statistics');
	    $this->setting['app_url'] = 'http://localhost';
	    $url = $this->setting['app_url'].base64_decode('L2NvdW50Mi9kaWFyeS5waHA=');
	    $post = array();
	    $post['domain']=WIKI_URL;
	    $post['lasttime'] = $this->time;
	    $post['sitename'] = $this->setting['site_name'];
	    $postquery = $_ENV['statistics']->http_array($post);
	    $this->cache->writecache('adminmaindiary',date('d'));
	    die(util::hfopen($url,0,$postquery));
	}

	function dodatasize(){
		$sizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
		$typelist = array('attsize'=>'/data/attachment','uploadsize'=>'/uploads');
		$type = $typelist[$this->get[2]];
		if(file_exists(HDWIKI_ROOT.$type)){
			$datasize = file::getdirsize(HDWIKI_ROOT.$type);
			$datasize = sprintf("%u", $datasize);
			if($datasize == 0) {
				$datasize = "0 Bytes";
			}else{
		 		$datasize =round($datasize/pow(1024, ($i = floor(log($datasize, 1024)))), 2) . $sizename[$i];
			}
			echo $datasize;
		}else{
			exit("Error!");
		}

	}

	function dologinshow(){
		$set_show=$this->post['isshow'];
		if($set_show > 0){
			$settings['login_show']=$set_show;
			$setting = $_ENV['setting']->update_setting($settings);
			$this->cache->removecache('setting');
			if($set_show==1){
				echo '1';exit;
			}
			if($set_show==2){
				echo '2';exit;
			}
		}else{
			echo '3';exit;
		}
	}

}
?>