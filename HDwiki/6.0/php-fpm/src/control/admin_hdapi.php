<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	var $json;
	
	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load('user');
		$this->load('doc');
		$this->load('hdapi');
		$this->load('category');
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	
	function doset(){
		if(isset($this->post['cloud_search'])){
			$this->load('setting');
			
			$settings = array(
				'cloud_search'=>$this->post['cloud_search'],
				'cloud_search_cache'=>$this->post['cloud_search_cache'],
				'cloud_search_timeout'=>$this->post['cloud_search_timeout'],
				'cloud_search_close_time'=>$this->post['cloud_search_close_time']
				
			);
			$_ENV['setting']->update_setting($settings);
			// 开启云搜素，需要重新向主站注册
			if(isset($settings['cloud_search']) && 1 == $settings['cloud_search'] && empty($this->setting['cloud_search'])) {
				$this->load('search');
				$flag = $_ENV['search']->clode_register();
			}
			$this->cache->removecache('setting');
			echo 'OK';
		}else{
			$this->view->assign('settings',$this->setting);
			$this->view->display('admin_bklmset');
		}
	}

	function donosynset(){
		if(isset($this->post['titles'])){
			$this->load('setting');
			$titles=$this->post['titles'];
			
			if (WIKI_CHARSET == 'GBK'){
				$titles=string::hiconv($titles);
			}
			$titles = explode("\n", $titles);
			$_ENV['hdapi']->update_private_titles($titles);
			echo 'OK';
		}else{
			$titles = $_ENV['hdapi']->get_all_private_titles();
			$privtetitlescount = 0;
			
			if (is_array($titles)){
				$privtetitlescount = count($titles);
				$this->view->assign('privtetitles', join("\n", $titles));
			}
			
			$this->view->assign('privtetitlescount', $privtetitlescount);
			
			$this->view->display('admin_bklmnosynset');
		}
	}

	function dodown(){
		$sitenick =$this->setting['site_nick'];
		
		if($sitenick){
			$this->view->assign('is_login', 'login');
			
			if($this->setting['hdapi_bklm']){
				$this->view->assign('is_open', 1);
				$cats = $_ENV['category']->get_all_category();
				$cats = $_ENV['category']->get_categrory_tree($cats);
				$this->view->assign('cats',$cats);
			}else{
				$this->view->assign('is_open', 0);
			}
		}else{
			$this->view->assign('is_login', 0);
		}
		
		$this->view->display('admin_bklmdown');
	}
	
	function doinfo(){
		$sitenick =$this->setting['site_nick'];
		
		if($sitenick){
			$this->view->assign('is_login', 'login');
			$this->view->assign('site_name', $this->setting['site_name']);
		}else{
			$this->view->assign('is_login', 0);
		}
		$this->view->display('admin_bklminfo');
	}
	
	function form($key){
		return string::hiconv($this->post[$key]);
	}
	
	function dodefault() {
		$sitenick =$this->setting['site_nick'];
		$sitekey =$this->setting['site_key'];
		$this->view->assign('is_login', $sitenick && $sitekey);
		$this->view->assign('site_key', $sitekey);
		$this->view->display('admin_bklm');
	}
	
	
	function dogetindexinfo(){
		@header('Content-type: application/json; charset='.WIKI_CHARSET);
		$sitenick =$this->setting['site_nick'];
		$sitekey =$this->setting['site_key'];
		$this->hsetcookie('querystring','admin_hdapi', 3600);
		if (!$sitenick){
			exit('');
		}
		
		//获取站长的信用信息
		$sitenick2 = $sitenick;
		if(strtolower(WIKI_CHARSET) == 'gbk') {$sitenick2 = string::hiconv($sitenick,'utf-8','gbk',true);}
		$json = array('site_nick' => array($sitenick2));
		
		$json = $_ENV['hdapi']->tojson($json);
		$url='http://api.hudong.com/sitelistquery.do?json='.$json;
		
		$data = $_ENV['hdapi']->hfopen($url);
		
		if($data){
			if(strtolower(WIKI_CHARSET) == 'gbk') {$data = string::hiconv($data,'gbk','utf-8',true);}
			$data = str_replace(':[{', ':{', $data);
			$data = str_replace('}}]}', '}}}', $data);				
			$data = str_replace('[', '{', $data);
			$data = str_replace(']', '}', $data);
			$data = str_replace(':{{', ':{', $data);
			$data = str_replace('}},{', '},', $data);
			$data = str_replace('}}},', '}},', $data);
			
			$_ENV['hdapi']->setLog('hdapi_creditinfo', $data);
			echo $data;
		}
	}
	
	/*
	获取或修改站长资料
	*/
	function dositeuserinfo(){
		$sitenick =$this->setting['site_nick'];
		$sitekey =$this->setting['site_key'];
			
		if(isset($this->post['action']) && $this->post['action']=='updateinfo'){
			//提交站长资料
			$arr['type'] = 'update';
			$arr['key'] = $sitekey;
			$arr['user_nick'] = $sitenick;
			$arr['user_name'] = $this->form('name');
			$arr['siteremark'] = $this->form('siteremark');
			$arr['sitedomain'] = $this->post['sitedomain'];
			$arr['wikiurl'] = $this->post['wikiurl'];
			$arr['user_qq'] = $this->post['qq'];
			$arr['user_msn'] = $this->post['msn'];
			$arr['user_phone'] = $this->post['tel'];
			$arr['email'] = $this->post['email'];
			
			$arr['wikiurl'] = str_replace('admin_hdapi.html','',$arr['wikiurl']);
				
			$json = $_ENV['hdapi']->tojson($arr);
			$url='http://api.hudong.com/siteoutermodify.do?json='.$json;
			$_ENV['hdapi']->setLog('post dositeuserinfo', $url);
			
			$data = $_ENV['hdapi']->hfopen($url);
			
			$obj = $_ENV['hdapi']->unjson($data);
			
			if ($obj->success == true){
				echo 'OK';
			} else {
				echo $obj->error_code;
			}
			
		} else if(isset($this->post['action']) && $this->post['action']=='readinfo'){
			@header('Content-type: application/json; charset='.WIKI_CHARSET);
			//获取站长的资料信息
			$arr= array('type'=>'get', 'user_nick'=>$sitenick, 'key'=>$sitekey);
			$json = $_ENV['hdapi']->tojson($arr);
			$url='http://api.hudong.com/siteoutermodify.do?json='.$json;
			$data = $_ENV['hdapi']->hfopen($url);
			$data = string::hiconv($data, WIKI_CHARSET);
			echo $data;
		} else {
			echo '';
		}
	}
	
	function dotitles(){
		$tag=string::hiconv($this->post['tag']);
	 	$titles=$_ENV['hdapi']->get_doc_title_by_catname($tag);
	    $this->message($titles,'',2);
	}
	
	function doimport(){
		$title=string::hiconv(trim($this->post['title']));
		$data=$_ENV['doc']->get_doc_by_title($title);
		$page = $this->cache->getcache('systemdata');
		if(empty($page))$page = array(1);
		$_ENV['hdapi']->roll_docs($page[0],10);
		$this->cache->writecache('systemdata', array(++$page[0]));
		if($data){
			$this->message(0,'',2);
		}
 		$doc['content']=$_ENV['hdapi']->get_content_import($title, 2, true);
		$doc['tags'] = array($title);
		$doc['search_tags'] = $title;
 		if($doc['content'] && is_string($doc['content'])){
			$this->load('innerlink');
			$doc['category']=$this->post['cid'];
			$doc['title']=$title;
			$doc['letter']=string::getfirstletter($title);
			$doc['content']=mysql_real_escape_string($doc['content']);
			$doc['content']=str_replace('\r\n', "\r\n", $doc['content']);
			$doc['tags']=$_ENV['doc']->jointags($doc['tags']);
			$doc['summary']=trim(string::convercharacter(strip_tags($doc['content'])));
			$doc['images']=util::getimagesnum($doc['content']);
			$doc['time']=$this->time;
			$doc['words']=string::hstrlen($doc['content']);
			$doc['visible']='1';//$this->setting['verify_doc']?'0':'1';
			$did=$_ENV['doc']->add_doc($doc);
			$_ENV['doc']->add_searchindex($did,$doc['title'],$doc['search_tags'],$doc['content']);
			$_ENV['user']->add_credit($this->user['uid'],'doc-create',$this->setting['credit_create'], $this->setting['coin_create']);
			$_ENV['user']->update_field('creates',$this->user['creates']+1,$this->user['uid']);
			$_ENV['category']->update_category_docs($this->post['category']);
			$_ENV['innerlink']->update($title, $did);
			//$data = array('did'=>$did, 'name'=>'互动百科 '.$title, 'url'=>'http://www.baike.com/wiki/'.urlencode($this->post['title']));
			//$_ENV['reference']->add($data);
			
			$this->message(1,'',2);
 		}else if($doc['content'] && is_array($doc['content'])){
			$this->message($doc['content']['return_type'].'_'.$doc['content']['return_info'],'',2);
		}else{
			$this->message(2,'',2);
		}
	}

	function dorolldocs(){
		$page = intval($this->get[2]);
		$limit = intval($this->get[3]);
		
		if($page<1){$page = 1;}
		if($limit<1){$limit = 300;}
		
		if($_ENV['hdapi']->roll_docs($page, $limit)){
			$this->message('第'. $page .'页提交成功，请等待本页自动转入','index.php?admin_hdapi-rolldocs-'.intval($page+1).'-'.$limit);
		}else{
			$this->message('提交完成','index.php?admin_hdapi');
		}
	}
	
	/*
	注册时验证email和昵称是否存在
	*/
	function doregistercheck(){
		$arr['type'] = '1';
		$arr['email'] = '';
		$arr['usernick'] = '';
		if (isset($this->post['email'])){
			$arr['email'] = $this->post['email'];
		} else if (isset($this->post['usernick'])){
			$arr['usernick'] = $this->form('usernick');
		}
		
		$json = $_ENV['hdapi']->tojson($arr);
		$url = 'http://api.hudong.com/registercheck.do?json='.$json;
		
		$data = $_ENV['hdapi']->hfopen($url);
		@header('Content-type: text/html; charset='.WIKI_CHARSET);
		$obj = $_ENV['hdapi']->unjson($data);
		
		if (true == $obj->success){
			echo 'OK';
		} else {
			echo $obj->error_code;// . $data;
		}
	}
	
	/*
	登录接口，
	登录或注册成功后，将获取到昵称和信息码，自动写入setting变量
	*/
	function dologin(){
		set_time_limit(60);
		header('Content-type: text/html; charset='.WIKI_CHARSET);
		$json = '';
		$arr = array();
		if (!isset($this->post['action'])){
			exit('error');
		}
		
		$arr['type'] = $this->post['action'];
		$arr['email'] = $this->post['email'];
		$arr['password'] = strtoupper(md5($this->post['pwd']));
		$arr['sitedomain'] = $this->post['sitedomain'];
		$arr['wikiurl'] = str_replace('admin_hdapi.html','',$this->post['wikiurl']);
		
		if('register'==$this->post['action']){
			$arr['user_nick'] = $this->form('usernick');
			$arr['username'] = $this->form('name');
			$arr['siteremark'] = $this->form('sitename');
			$arr['userqq'] = $this->post['qq'];
			$arr['usermsn'] = $this->post['msn'];
			$arr['userphone'] = $this->post['tel'];
		}
		
		$this->load('setting');
		$settings=array();
		
		$json = $_ENV['hdapi']->tojson($arr);
		$url='http://api.hudong.com/siteouteractive.do?json='.$json;
		$data = $_ENV['hdapi']->hfopen($url);
		$obj = $_ENV['hdapi']->unjson($data);
		
		if ($obj->success){
			//登录或注册成功，更新插件变量信息
			//并将usernick提交给安装统计
			$settings['site_nick']=$obj->site_nick;
			$settings['user_nick']=$obj->site_nick;
			$settings['site_key']=$obj->key;
			$settings['site_appkey']=$obj->appkey;
			echo 'OK';
			
			//将信息传给统计列表程序
			$url2 = 'http://kaiyuan.hudong.com/count2/in.php?action=update&sitedomain='.$arr['sitedomain'].'&json='.base64_encode($data);
			$data = $_ENV['hdapi']->hfopen($url2);
		}
		
		$_ENV['setting']->update_setting($settings);
		$this->cache->removecache('setting');
		
		if($obj->error_code || $obj->errormessage){
			if($obj->error_code){
				echo $obj->error_code;
			}else {
				echo $obj2->errormessage;
			}
		}
	}
	
	function doprivatedoc(){
		$titles=$this->post['titles'];
		if (WIKI_CHARSET == 'GBK'){
			$titles=string::hiconv($titles);
		}
		$titles = explode("\n", $titles);
		$_ENV['hdapi']->update_private_titles($titles);
	}
	
	function docheckapi(){
		$url=SNS_CHECK_URL.'?appkey='.$this->setting['site_appkey'];
		$this->hsetcookie('querystring','admin_hdapi', 3600);
		$data = $_ENV['hdapi']->hfopen($url);
		$obj = $_ENV['hdapi']->unjson($data);
		if(!$obj){
			exit('err');
		}
		if ($obj && $obj->powerlist){
			echo $obj->powerlist;
		}else{
			echo $obj->errormessage;
		}
	}
	
	//手动分享
	function dotosns(){
		$did=trim($this->post['did']);
		$content=trim($this->post['content']);
		$op=trim($this->post['op']);
		$this->hsetcookie('querystring','admin_hdapi', 3600);
		$obj=$_ENV['hdapi']->sharetosns($did, $content, $op);
		
		if(!$obj){
			exit('err');
		}
		
		if($obj->return && $obj->return == 'true'){
			echo 'OK';
		}else{
			echo $obj->errormessage;
		}
	}
}
?>