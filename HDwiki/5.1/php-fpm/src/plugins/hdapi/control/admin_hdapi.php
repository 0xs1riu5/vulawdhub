<?php
/*
 * Created on 2008-7-25
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{
	var $json;
	var $pluginid;
	
	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('category');
		$this->load('doc');
		$this->load('user');
		$this->load('plugin');
		//$this->load('reference');
		$this->loadplugin('hdapi');
		$this->view->setlang('zh','back');
	}
	
	
	function toutf( $a){
		if (empty($a) || WIKI_CHARSET == 'UTF-8'){
			return $a;
		}
		if (is_string($a)){
			$a = string::hiconv($a, 'utf-8');
		} else if (is_array($a)){
			foreach($a as $k => $v){
				$k2 = string::hiconv($k, 'utf-8');
				if ($k2 != $k) {
					unset($a[$k]);
					$k = $k2;
				}
				$a[$k] = $this->toutf($v);
			}
		} else if (is_object($a)){
			foreach($a as $k => $v){
				$k2 = string::hiconv($k, 'utf-8');
				if ($k2 != $k) {
					unset($a->$k);
					$k = $k2;
				}
				$a->$k = $this->toutf($v);
			}
		}
		
		return $a;
	}
	
	function togbk( $a){
		if (empty($a) || WIKI_CHARSET == 'UTF-8'){
			return $a;
		}
		
		if (is_string($a)){
			$a = string::hiconv($a, 'gbk');
		} else if (is_array($a)){
			foreach($a as $k => $v){
				$k2 = string::hiconv($k, 'gbk');
				if ($k2 != $k) {
					unset($a[$k]);
					$k = $k2;
				}
				$a[$k] = $this->togbk($v);
			}
		} else if (is_object($a)){
			foreach($a as $k => $v){
				$k2 = string::hiconv($k, 'gbk');
				if ($k2 != $k) {
					unset($a->$k);
					$k = $k2;
				}
				$a->$k = $this->togbk($v);
			}
		}
		
		return $a;
	}
	
	function tojson($a){
		$a = $this->toutf($a);
		
		if(function_exists('json_encode')){
			$data=json_encode($a);
		}else{
			include_once HDWIKI_ROOT.'/lib/json.class.php';
			$json = new Services_JSON();
			$data=$json->encode($a);
		}
		
		return $data;
	}
	
	function unjson($a){
		if (empty($data)){
			$data = '{"success":false}';
		}
		
		if(function_exists('json_decode')){
			$data=json_decode($a);
		}else{
			include_once HDWIKI_ROOT.'/lib/json.class.php';
			$json = new Services_JSON();
			$data=$json->decode($a);
		}
		
		return $this->togbk($data);
	}
	
	function form($key){
		return string::hiconv($this->post[$key]);
	}
	
	function get_pluginid(){
		if (is_null($this->pluginid)){
			$plugin=$_ENV['plugin']->get_plugin_by_identifier('hdapi');
			$this->pluginid = $plugin['pluginid'];
		}
		
		return $this->pluginid;
	}

	function dodefault() {
		$pluginid = $this->get_pluginid();
		 $this->view->assign('pluginid',$pluginid);
		 if(isset($this->post['insertdocform'])){
		 	$docs = $this->post['docs'];
		 	$catid = intval($this->post['catid']);
		 	$num = $_ENV['hdapi']->input_db_docs($docs,$catid);
		 	$this->message('共导入'.$num['all'].'个词条。其中'.intval($num['all']-$num['in']).'个已存在','BACK');
		 }
		 elseif(isset($this->post['getdocform'])){
		 	$catname = $this->post['catname'];
		 	$cats = $_ENV['category']->get_all_category();
			$cats = $_ENV['category']->get_categrory_tree($cats);
			$this->view->assign('cats',$cats);
		 	$docstr = $_ENV['hdapi']->get_doc_title_by_catname($catname);
		 	$this->view->assign('docstr',$docstr);
			$this->view->assign("pluginid",$pluginid);
			$this->view->assign('catname',$catname);
			$this->view->display('file://plugins/hdapi/view/admin_hdapi');
		 }
		 else {
			$sitenick =$this->plugin['hdapi']['vars']['sitenick'];
			$sitekey =$this->plugin['hdapi']['vars']['sitekey'];
			
			if ($sitenick) {
				$this->view->assign('is_login', 'login');
			} else if(!isset($this->plugin['hdapi'])) {
				$this->view->assign('is_login', 'notstart');
			} else {
				$this->view->assign('is_login', '');
			}
			
			if (!empty($sitenick)){
				$cats = $_ENV['category']->get_all_category();
				$cats = $_ENV['category']->get_categrory_tree($cats);
				$this->view->assign('cats',$cats);
				
				$obj = $this->cache->getcache('hdapi_creditinfo_'.md5($sitenick), 300);
				
				if (!is_object($obj)){
					//获取站长的信用信息
					$sitenick2 = $sitenick;
					if(strtolower(WIKI_CHARSET) == 'gbk') {$sitenick2 = string::hiconv($sitenick,'utf-8','gbk',true);}
					$json = array('site_nick' => array($sitenick2));
					
					$json = $this->tojson($json);
					$url='http://api.hudong.com/sitelistquery.do?json='.$json;
					
					$data = util::hfopen($url);
					
					if (!empty($data)){
						$data = str_replace(':[{', ':{', $data);
						$data = str_replace('}}]}', '}}}', $data);				
						$data = str_replace('[', '{', $data);
						$data = str_replace(']', '}', $data);
						$data = str_replace(':{{', ':{', $data);
						$data = str_replace('}},{', '},', $data);
						$data = str_replace('}}},', '}},', $data);
					}
					$_ENV['hdapi']->setLog('hdapi_creditinfo', $data);
					$obj = $this->unjson($data);
				}
				
				if ($obj->success == true){
					$this->cache->writecache('hdapi_creditinfo_'.md5($sitenick), $obj);

					$this->view->assign('is_readcredit', 'ok');
					$d = $obj->data->$sitenick;
					if (0 == $d->user_credit){
						$d->user_credit = '0';
					}
					if (0 == $d->user_credit_exchange){
						$d->user_credit_exchange = '0';
					}
					if (0 == $d->user_credit_left){
						$d->user_credit_left = '0';
					}
					if (0 == $d->doc_create){
						$d->doc_create = '0';
					}
					if (0 == $d->doc_cooper){
						$d->doc_cooper = '0';
					}
					if (0 == $d->warning_count){
						$d->warning_count = '0';
					}
					if (0 == $d->msgcount){
						$d->msgcount = '0';
					}
					//$d->msgcount = 1;
					$this->view->assign('sitekey', $sitekey);
					$this->view->assign('sitename', $d->siteremark);
					$this->view->assign('msgcount', $d->msgcount);
					$this->view->assign('name', $sitenick);
					$this->view->assign('user_credit', $d->user_credit);
					$this->view->assign('user_credit_exchange', $d->user_credit_exchange);
					$this->view->assign('user_credit_left', $d->user_credit_left);
					$this->view->assign('doc_create', $d->doc_create);
					$this->view->assign('doc_cooper', $d->doc_cooper);
					$this->view->assign('site_class', $d->site_class);
					$this->view->assign('warning_count', (string)$d->warning_count);
					$this->view->assign('version', $this->plugin['hdapi']['version']);
				} else if ($obj->success == false){
					$this->view->assign('is_readcredit', '');
					$this->view->assign('error_info', $data);
				}
			}
			
			$titles = $_ENV['hdapi']->get_all_private_titles();
			$privtetitlescount = 0;
			
			if (is_array($titles)){
				$privtetitles = '';
				$privtetitlescount = count($titles);
				foreach($titles as $title){
					$privtetitles .= $title."\n";
				}
				$this->view->assign('privtetitles', $privtetitles);
			}
			
			$this->view->assign('privtetitlescount', $privtetitlescount);
			$this->view->display('file://plugins/hdapi/view/admin_hdapi');
		 }
	}
	
	/*
	获取或修改站长资料
	*/
	function dositeuserinfo(){
		$sitekey = $this->plugin['hdapi']['vars']['sitekey'];
		$sitenick =$this->plugin['hdapi']['vars']['sitenick'];
		if(isset($this->post['action']) && $this->post['action']=='updateinfo'){
			//提交站长资料
			$arr['type'] = 'update';
			$arr['key'] = $sitekey;
			$arr['user_nick'] = $sitenick;
			$arr['user_name'] = $this->form('name');
			$arr['siteremark'] = $this->form('sitename');
			$arr['sitedomain'] = $this->post['sitedomain'];
			$arr['wikiurl'] = $this->post['wikiurl'];
			$arr['user_qq'] = $this->post['qq'];
			$arr['user_msn'] = $this->post['msn'];
			$arr['user_phone'] = $this->post['tel'];
			$arr['email'] = $this->post['email'];
			
			$arr['wikiurl'] = str_replace('plugin-hdapi-admin_hdapi.html','',$arr['wikiurl']);
				
			$json = $this->tojson($arr);
			$url='http://api.hudong.com/siteoutermodify.do?json='.$json;
			$_ENV['hdapi']->setLog('post dositeuserinfo', $url);
			
			$data = util::hfopen($url);
			
			$obj = $this->unjson($data);
			
			if ($obj->success == true){
				echo 'OK';
				$this->cache->removecache('hdapi_siteuserinfo'.md5($sitenick));
			} else {
				echo $obj->error_code;
			}
			
		} else if(isset($this->post['action']) && $this->post['action']=='readinfo'){
			@header('Content-type: application/json; charset='.WIKI_CHARSET);
			//获取站长的资料信息
			$data = $this->cache->getcache('hdapi_siteuserinfo'.md5($sitenick), 300);
			if (empty($data)){
				$arr= array('type'=>'get', 'user_nick'=>$sitenick, 'key'=>$sitekey);
				$json = $this->tojson($arr);
				$url='http://api.hudong.com/siteoutermodify.do?json='.$json;
				$data = util::hfopen($url);
				$data = string::hiconv($data, WIKI_CHARSET);
				
				$this->cache->writecache('hdapi_siteuserinfo'.md5($sitenick), $data);
			}
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
		$title=string::hiconv($this->post['title']);
		$data=$_ENV['doc']->get_doc_by_title($title);
		$page = $this->cache->getcache('systemdata');
		if(empty($page))$page = 1;
		$_ENV['hdapi']->roll_docs($page,10);
		$this->cache->writecache('systemdata',++$page);
		if($data){
			$this->message(0,'',2);
		}
 		$doc['content']=$_ENV['hdapi']->get_content_import($title, 2, true);
		$doc['tags'] = array($title);
		$doc['search_tags'] = $title;
 		if($doc['content'] && is_string($doc['content'])){
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
			$_ENV['user']->add_credit($this->user['uid'],'doc-create',$this->setting['credit_create']);
			$_ENV['user']->update_field('creates',$this->user['creates']+1,$this->user['uid']);
			$_ENV['category']->update_category_docs($this->post['category']);
			//$data = array('did'=>$did, 'name'=>'互动百科 '.$title, 'url'=>'http://www.hudong.com/wiki/'.urlencode($this->post['title']));
			//$_ENV['reference']->add($data);
			
			$this->message(1,'',2);
 		}else if($doc['content'] && is_array($doc['content'])){
			$this->message($doc['content']['return_type'].'_'.$doc['content']['return_info'],'',2);
		}else{
			$this->message(2,'',2);
		}
	}

	function dorolldocs(){
		$pluginid = $this->get_pluginid();
		$page = intval($this->get[2]);
		if($page<1){$page = 1;}
		if($_ENV['hdapi']->roll_docs($page)){
			$this->message('本页提交成功,现转到下一页,请等待本页自动转入','index.php?plugin-hdapi-admin_hdapi-rolldocs-'.intval($page+1));
		}else{
			$this->message('提交完成','index.php?plugin-hdapi-admin_hdapi');
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
		
		$json = $this->tojson($arr);
		$url = 'http://api.hudong.com/registercheck.do?json='.$json;
		
		$data = util::hfopen($url);
		@header('Content-type: text/html; charset='.WIKI_CHARSET);
		$obj = $this->unjson($data);
		
		if (true == $obj->success){
			echo 'OK';
		} else {
			echo $obj->error_code;// . $data;
		}
	}
	
	/*
	登录接口，
	登录或注册成功后，将获取到昵称和信息码，自动写入插件变量，以代替之前人工输入的过程
	*/
	function dologin(){
		@header('Content-type: text/html; charset='.WIKI_CHARSET);
		$json = '';
		$arr = array();
		if (!isset($this->post['action'])){
			exit('error');
		}
		
		$arr['type'] = $this->post['action'];
		$arr['email'] = $this->post['email'];
		$arr['password'] = strtoupper(md5($this->post['pwd']));
		$arr['sitedomain'] = $this->post['sitedomain'];
		$arr['wikiurl'] = str_replace('plugin-hdapi-admin_hdapi.html','',$this->post['wikiurl']);
		
		if('register'==$this->post['action']){
			$arr['user_nick'] = $this->form('usernick');
			$arr['username'] = $this->form('name');
			$arr['siteremark'] = $this->form('sitename');
			$arr['userqq'] = $this->post['qq'];
			$arr['usermsn'] = $this->post['msn'];
			$arr['userphone'] = $this->post['tel'];
		}
		
		$json = $this->tojson($arr);
		$url='http://api.hudong.com/siteouteractive.do?json='.$json;
		
		$data = util::hfopen($url);
		$json = base64_encode($data);
		
		$obj = $this->unjson($data);

		if ($obj->success == true){
			//登录或注册成功，更新插件变量信息
			//并将usernick提交给安装统计
			
			//将信息传给统计列表程序
			$url2 = 'http://kaiyuan.hudong.com/count2/in.php?action=update&sitedomain='.$arr['sitedomain'].'&json='.$json;
			$data = util::hfopen($url2);
						
			$pluginid = $this->get_pluginid();
			$a = array('sitenick'=>$obj->site_nick, 'usernick'=>$obj->site_nick, 'sitekey'=>$obj->key);
			$_ENV['plugin']->update_pluginvar($a, $pluginid);
			
			//清理缓存
			$this->cache->removecache('plugin');
			
			echo 'OK';
			
		} else {
			//登录失败
			echo $obj->error_code;
		}
	}
	
	function dostarthdapi(){
		$pluginid = $this->get_pluginid();
		$_ENV['plugin']->update_plugin($pluginid, 1);
		
		//清理缓存
		$this->cache->removecache('plugin');
		echo 'OK';
	}
	
	function doprivatedoc(){
		$titles=$this->post['titles'];
		if (WIKI_CHARSET == 'GBK'){
			$titles=string::hiconv($titles);
		}
		$titles = explode("\n", $titles);
		$_ENV['hdapi']->update_private_titles($titles);
	}
}
?>
