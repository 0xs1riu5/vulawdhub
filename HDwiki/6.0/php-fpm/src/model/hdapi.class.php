<?php
!defined('IN_HDWIKI') && exit('Access Denied');
define('WIKI_LOCK_URL','http://api.hudong.com/lockdocapi.do');
define('WIKI_POST_URL','http://api.hudong.com/createdocouter.do');
define('WIKI_GET_BASE_URL','http://api.hudong.com/docpopbasic.do');
define('WIKI_GET_URL','http://api.hudong.com/docPopContent.do');
define('WIKI_UNION_URL','http://api.hudong.com/docPopUnion.do');
define('WIKI_USER_URL','http://api.hudong.com/checkSiteOuter.do');
define('NEW_WIKI_POST_URL','http://api.hudong.com/saveouterdoc.do');

define('SNS_LOGIN_URL','http://api.hudong.com/sharelogin.do');
define('SNS_CHECK_URL','http://api.hudong.com/checkshare.do');
define('SNS_SHARE_URL','http://api.hudong.com/autoshare.do');



class hdapimodel {
	var $db;
	var $base;
	var $exploder = "；";
	var $api_error = 'ok';
	var $doclink = array();
	var $content_mark = array();
	var $cachetime ;
	var $charlen;
	
	function hdapimodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;

		$this->cache = $base->cache;
		if (!empty($this->cache)){
			$error = $this->cache->getcache('hdapi_timeout', 30);
			$this->api_error = $error[0];
		}
		$this->cachetime = 3600*24*30;
		if (WIKI_CHARSET == 'GBK') {$this->charlen = 2;}
		else {$this->charlen = 3;}
	}

	function setLog($key, $value){
		if(!defined('LOG_HDAPI') || !LOG_HDAPI){
			return false;
		}
		$handle = fopen("data/cache/log_".date("Ymd").".txt", "ab");
		if (!$handle){
			return false;
		}
		
		$value = print_r($value, true)."\n\n";
		
		$text = date("Y-m-d H:i:s")."\n".$key." = ".$value;
		
		fwrite($handle, $text);
		fclose($handle);
		return true;
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
	
	function islocal(){
		return preg_match('/^localhost|127.0.0|192.168|10.0.0|test.cn/', $_SERVER['SERVER_NAME']);
	}
	
	function hfopen($url, $limit = 0, $post = '', $timeout = 27){
		if ($this->api_error == 'timeout') {return '';}
		
		$t = time();
		$data = @util::hfopen($url, $limit, $post, $cookie, FALSE, '', $timeout);
		$t = time() - $t;
		$data = trim($data);
		$this->setLog('time:'.$url, 'time:'.$t);
		if ($t >= $timeout){
			$this->api_error = 'timeout';
			$this->cache->writecache('hdapi_timeout', array('timeout'));
			$data = '';
			$this->setLog('error:'.$url, 'timeout:'.$t);
		} elseif(!empty($data) && substr($data,0,5)!= '<?xml' && substr($data,0,1)!= '{'){
			$this->setLog('error:'.$url, $data.$post);
			$data = '';
		} else {
			$this->setLog($url, $data.$post);
			$this->cache->writecache('hdapi_timeout', array('ok'));
		}

		return $data;
	}
	
	function islock($title){
		if($this->islocal()){return false;}
		if(strtolower(WIKI_CHARSET) == 'gbk') {$title = string::hiconv($title,'utf-8','gbk');}
		$xmldata = $this->hfopen($this->auth_url(WIKI_LOCK_URL,$this->base->user['username']).'title='.urlencode($title).'&lock_type=1');
		$xmlarray = $this->xml2array($xmldata);
		if('true'== $xmlarray['locked']) {return true;}
		else {return false;}
  	}

	function lock($title){
		if($this->islocal()){return false;}
		if(strtolower(WIKI_CHARSET) == 'gbk') {$title = string::hiconv($title,'utf-8','gbk');}
		$xmldata = $this->hfopen($this->auth_url(WIKI_LOCK_URL,$this->base->user['username']).'title='.urlencode($title).'&lock_type=2');
		$xmlarray = $this->xml2array($xmldata);
		if('true'== $xmlarray['success']) {return true;}
		else {return false;}
  	}

  	function un_lock($title){
		if($this->islocal()){return false;}
  		if(strtolower(WIKI_CHARSET) == 'gbk') {$title = string::hiconv($title,'utf-8','gbk');}
		$xmldata = $this->hfopen($this->auth_url(WIKI_LOCK_URL,$this->base->user['username']).'title='.urlencode($title).'&lock_type=3');
		$xmlarray = $this->xml2array($xmldata);
		if('true'== $xmlarray['success']){return true;}
		else {return false;}
 	}

	function refresh_lock($title){
		if($this->islocal()){return false;}
	  	if(strtolower(WIKI_CHARSET) == 'gbk') {$title = string::hiconv($title,'utf-8','gbk');}
		$xmldata = $this->hfopen($this->auth_url(WIKI_LOCK_URL,$this->base->user['username']).'title='.urlencode($title).'&lock_type=4');
		$xmlarray = $this->xml2array($xmldata);
		if('true'==$xmlarray['success']) {return true;}
		else {return false;}
  	}

  	function auth_url($url,$name=''){
		if (''==$name) {$usernick = $this->base->setting['site_nick'];}
		else {$usernick = $name;}
		$sitenick = $this->base->setting['site_nick'];
		if('gbk' == strtolower(WIKI_CHARSET)){
			$usernick = string::hiconv($usernick,'utf-8','gbk',true);
			$sitenick = string::hiconv($sitenick,'utf-8','gbk',true);
		}
	    return $url."?key=".$this->base->setting['site_key']."&site_nick=".urlencode($sitenick)."&user_nick=".urlencode($usernick)."&";
	}

	function checkUser($sitekey,$sitenick){
		$l = WIKI_USER_URL."?key=$sitekey&site_nick=$sitenick";
		$xmldata = $this->hfopen($l);
		if(!$xmldata) {return false;}
		$xmlarray = $this->xml2array($xmldata);
		if(isset($xmlarray['allow']) && intval($xmlarray['allow'])) {return true;}
		else {return false;}
	}

	function get_content($title,$type=1,$return_info=false){
		$key = md5($title);
		if($this->is_private_title($title)){return '';}
		
		$doc = $_ENV['doc']->get_doc_by_title(addslashes($title), 'summary,lastedit');
		
		if('gbk' == strtolower(WIKI_CHARSET)) {$title = string::hiconv($title,'utf-8','gbk');}
		
		if($doc && $doc['summary']){
			$lastedit = $doc['lastedit'];
			$l = $this->auth_url(WIKI_GET_BASE_URL).'title='.urlencode($title).'&type='.$type.'&encode='.strtolower(WIKI_CHARSET);
			$xmldata = $this->hfopen($l);
			
			$lastest_eidt_time=0;
			$xmlarray = $this->xml2array($xmldata);
			if(isset($xmlarray['lastest_eidt_time'])){
				$lastest_eidt_time=strtotime($xmlarray['lastest_eidt_time']);
			}
			
			if($lastest_eidt_time < $lastedit){
				return '';
			}
		}
		
		$l = $this->auth_url(WIKI_GET_URL).'title='.urlencode($title).'&type='.$type.'&encode='.strtolower(WIKI_CHARSET);
		$xmldata = $this->hfopen($l);
		
		$xmlarray = $this->xml2array($xmldata);
		$content='';
		if(isset($xmlarray['content'])){
			//$content = str_replace("http://www.baike.com/wiki/","index.php?doc-innerlink-",$xmlarray['content']);
			$content = preg_replace("/href=[\"\'](http:\/\/www.baike.com)?\/wiki\/([^\"\' >]+)[\"\']/i", "href=\"index.php?doc-innerlink-\$2\"", $xmlarray['content']);
			$content=stripslashes($content);
			$this->setLog('content', $content);
			//cache
			$this->content_mark = $this->cache->getcache('hdapi_content_mark_'.$key[0], $this->cachetime);
			if (!is_array($this->content_mark)) {$this->content_mark = array();}
			$this->content_mark[$key] = strlen($content) / $this->charlen;
			$this->content_mark[$key.'_isedit'] = $xmlarray['is_edit']; //is allow client edit
			$this->cache->writecache('hdapi_content_mark_'.$key[0], $this->content_mark);
		}
		return $content;
	}
	
	function get_content_import($title,$type=1,$return_info=false){
		$key = md5($title);
		if($this->is_private_title($title)){return '';}
	  	if('gbk' == strtolower(WIKI_CHARSET)) {$title = string::hiconv($title,'utf-8','gbk');}
		$l = $this->auth_url(WIKI_GET_URL).'title='.urlencode($title).'&type='.$type.'&encode='.strtolower(WIKI_CHARSET);
		$xmldata = $this->hfopen($l);
		
		$xmlarray = $this->xml2array($xmldata);
		$content='';
		if(isset($xmlarray['content'])){
			//$content = str_replace("http://www.baike.com/wiki/","index.php?doc-innerlink-",$xmlarray['content']);
			$content = preg_replace("/href=[\"\'](http:\/\/www.baike.com)?\/wiki\/([^\"\' >]+)[\"\']/i", "href=\"index.php?doc-innerlink-\$2\"", $xmlarray['content']);
			$content=stripslashes($content);
			$this->setLog('content', $content);
			//cache
			$this->content_mark = $this->cache->getcache('hdapi_content_mark_'.$key[0], $this->cachetime);
			if (!is_array($this->content_mark)) {$this->content_mark = array();}
			$this->content_mark[$key] = strlen($content) / $this->charlen;
			$this->content_mark[$key.'_isedit'] = $xmlarray['is_edit']; //is allow client edit
			$this->cache->writecache('hdapi_content_mark_'.$key[0], $this->content_mark);
		}else if (isset($xmlarray['return_info'])){
			if('gbk' == strtolower(WIKI_CHARSET)) {$xmlarray['return_info'] = string::hiconv($xmlarray['return_info'],'gbk','utf-8');}
			$content = array(
				'return_info'=>$xmlarray['return_info'],
				'return_type'=>$xmlarray['type']
			);
			
		}
		return $content;
	}
	
	function get_tit_url($title){
		if (!is_array($this->doclink)) $this->doclink = array();
		if(strtolower(WIKI_CHARSET) == 'gbk')$title = string::hiconv($title,'utf-8','gbk');
		$l = WIKI_UNION_URL.'?title='.urlencode($title).'&encode='.strtolower(WIKI_CHARSET);
		$xmldata = $this->hfopen($l);
		$xmlarray = $this->xml2array($xmldata);
		
		if(isset($xmlarray['docdeclaration'])){
			return $xmlarray;
		}else if(!isset($xmlarray['isunion']) || !$xmlarray['isunion']){
			$xmlarray = false;
		}
		
		return $xmlarray;
	}

	function post_content($title, $content=''){
		if($this->islocal() || $this->is_private_title($title)){return false;}
		$key = md5($title);
		if ($this->base->user['username'] == $content) {
			$content = $_ENV['doc']->get_doc_by_title($title);
			$content = $content['content'];
		}
		$this->content_mark = $this->cache->getcache('hdapi_content_mark_'.$key[0], $this->cachetime);
		if (is_array($this->content_mark) && isset($this->content_mark[$key])){
			if ($this->content_mark[$key.'_isedit'] == 0){
				return false;
			}
		}

		include HDWIKI_ROOT.'/lib/json.class.php';
		$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		$sitenick = $this->base->setting['site_nick'];
		if('gbk' == strtolower(WIKI_CHARSET)){
		  	$title=  string::hiconv($title,'utf-8','gbk');
			$sitenick = string::hiconv($sitenick,'utf-8','gbk');
		}
		$postdata = array('type'=>'notbatch','site_nick'=>urlencode($sitenick),'key'=>$this->base->setting['site_key'],'docList'=>array($title));
		$l = NEW_WIKI_POST_URL;
		$jsondata='json='.$json->encode($postdata);
	 	$xmldata=$this->hfopen($l,0,$jsondata);
 	}

	function xml2array($xmldata){
	  	$result=array();
	  	$splits=array('<![CDATA[',']]>','</','>');
	  	$pos0=0;
	  	while($pos0!==FALSE){
			$pos1=strpos($xmldata,$splits[0],$pos0);
			if($pos1==false) {break;}
		  	$pos2=strpos($xmldata,$splits[1],$pos1);
		  	$pos3=strpos($xmldata,$splits[2],$pos2);
		  	$pos4=strpos($xmldata,$splits[3],$pos3);
		  	$key=substr($xmldata,$pos3+2,$pos4-$pos3-2);
		  	$value=substr($xmldata,$pos1+9,$pos2-$pos1-9);
		  	$result[$key]=$value;
		  	$pos0=$pos4;
	  	}
	  	return $result;
  	}

  	function get_recent_editon_info($did){
		$sql = "SELECT `eid`, `reason` FROM ".DB_TABLEPRE."edition WHERE did='{$did}' ORDER BY time DESC LIMIT 1";
		return $this->db->fetch_first($sql);
	}
	
	function is_private_title($title){
		$title=addslashes(stripslashes($title));//防止个别词条当中包含 ' 导致SQL错误
		$sql = "SELECT * FROM `".DB_TABLEPRE."privatetitle` WHERE title = '{$title}'";
		return $this->db->fetch_first($sql);
	}
	
	function get_all_private_titles(){
		$sql = "SELECT `title` FROM `".DB_TABLEPRE."privatetitle` order by id asc limit 0, 200";
		$query = $this->db->query($sql);
		$list = array();
		while($row=$this->db->fetch_array($query)){
			$row['title'] = trim($row['title']);
			if($row['title']){
				$list[] = $row['title'];
			}
		}
		return $list;
	}
	
	function update_private_titles($list){
		$sql = "DELETE FROM `".DB_TABLEPRE."privatetitle`";
		$this->db->query($sql);
		
		$sql = "REPLACE INTO `".DB_TABLEPRE."privatetitle` (`title`) values";
		$values=array();
		$num = count($list);
		$num = $num < 200 ? $num:200;
		for($i=0;$i<$num;$i++){
			$value = trim($list[$i]);
			if($value){
				$values[]= "('$value')";
			}
		}
		if(!empty($values)){
			return $this->db->query($sql.join(',', $values));
		}else{
			return false;
		}
	}
	
	function get_doc_by_title($title, $fields='*'){
		if ($this->is_private_title($title)) return false;
		$title=addslashes(stripslashes($title));
		$sql = "SELECT $fields FROM `".DB_TABLEPRE."doc` WHERE title = '{$title}'";
		return $this->db->fetch_first($sql);
	}

	function get_doc_title_by_id($did, $fields='*'){
		$sql = "SELECT $fields FROM `".DB_TABLEPRE."doc` WHERE did = '{$did}'";
		return $this->db->fetch_first($sql);
	}
	
	function get_uniontitle_by_did($did, $fields='*'){
		$sql = "SELECT $fields FROM `".DB_TABLEPRE."uniontitle` WHERE did = '{$did}'";
		$row = $this->db->fetch_first($sql);
		
		$title = '';
		if ($row){
			$title = $row['time'] > time() - 3600*24*7 ? $row:'';
		}
		
		return $title;
	}
	
	function save_uniontitle_by_did($did, $uniontitle){
		$t = time();
		if (empty($uniontitle)) $uniontitle = '';
		$sql = "replace into `".DB_TABLEPRE."uniontitle` (`did`, `docdeclaration`,`time`) values($did, '$uniontitle', $t)";
		$this->del_uniontitle();
		return $this->db->query($sql);
	}
	
	function del_uniontitle(){
		$isDel = $this->cache->getcache('del_uniontitle', 3600*24*6);
		if(date('w') != 1 || $isDel[0] == 'OK') return false;
		$t = time() - 3600;
		$sql = "DELETE FROM `".DB_TABLEPRE."uniontitle` WHERE `time` < $t";
		$this->cache->writecache('del_uniontitle', array('OK'));
		return $this->db->query($sql);
	}
	
	function get_version($did){
		$sql = "SELECT COUNT(*) AS num FROM `".DB_TABLEPRE."edition` WHERE did = '{$did}'";
		$r = $this->db->fetch_first($sql);
		return $r['num'];
	}

	function update($var){
		if('gbk' == strtolower(WIKI_CHARSET)){
			if (function_exists('iconv')){
				$var['sitenick'] = iconv('GBK', 'UTF-8', $var['sitenick']);
			}else{
				$var['sitenick'] = string::hiconv($var['sitenick'],'utf-8','gbk');
			}
		}
		if($this->checkUser($var['sitekey'],$var['sitenick'])){	return true;}
		else {return '用户信息验证错误，请检查sitenick和sitekey的设置';}
	}

	function get_doc_title_by_catname($catname){
		$l = "http://api.hudong.com/categorydocs.do";
		if(strtolower(WIKI_CHARSET) == 'gbk') {$catname = string::hiconv($catname,'utf-8','gbk');}
		include HDWIKI_ROOT.'/lib/json.class.php';
		$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		$postdata=array(
	     	'm'=>'category',
	     	'a'=>'getdocs',
	     	'start'=>0,
	     	'limit'=>2000,
	     	'name'=>$catname
	     );
	    $jsondata='json='.$json->encode($postdata);
	 	$xmldata=$this->hfopen($l,0,$jsondata);
		if($xmldata){
			$t = '';
			$xmlarray = $json->decode($xmldata);
			if(is_array($xmlarray['data']['list'])){
				foreach($xmlarray['data']['list'] as $id => $p){
					$t .= $p.'|||';
				}
				if('gbk' == strtolower(WIKI_CHARSET)) {
					$t = string::hiconv($t,'gbk','utf-8');
				}
				return str_replace('|||', $this->exploder, $t);
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	function input_db_docs($str,$cid){
		$docs = explode($this->exploder,$str);
		$num = array('all'=>0,'in'=>0);
		$num['all'] = count($docs)-1;
		$time = time();
		if(is_array($docs)){
			foreach($docs as $doc){
				if($doc){
					$sql = "SELECT * FROM `".DB_TABLEPRE."doc` WHERE title='{$doc}'";
					if(!is_array($this->db->fetch_first($sql))){
						$letter = string::getfirstletter($doc);
						$author = $this->base->user['username'];
						$authorid = $this->base->user['uid'];
						$sql = "INSERT INTO `".DB_TABLEPRE."doc` (`cid`,`letter`,`title`,`author`,`authorid`,`time`,`lastedit`,`lasteditor`) VALUES ('{$cid}','{$letter}','{$doc}','{$author}','{$authorid}','{$time}','{$time}','{$author}')";
						$this->db->query($sql);
						$num['in']++;
					}
				}
			}
		}
		return $num;
	}

	function update_doc($did,$content){
		$summary = addslashes(string::substring(strip_tags($content),0,100));
		$content = addslashes($content);
		$sql = "UPDATE `".DB_TABLEPRE."doc` SET summary = '{$summary}', content = '{$content}' WHERE did = '{$did}'";
		$this->db->query($sql);
	}

	function update_doc_by_time($did,$content){
		$summary = addslashes(string::substring(strip_tags($content),0,100));
		$content = addslashes($content);
		$time = time();
		$tmpdoc = $this->get_doc_title_by_id($did);
		$author = $this->base->user['username'];
		$authorid = $this->base->user['uid'];
		$ip = $this->base->ip;
		$sql = "INSERT INTO `".DB_TABLEPRE."edition` (`cid`,`did`,`author`,`authorid`,`time`,`ip`,`title`,`tag`,`summary`,`content`) VALUES ('{$tmpdoc['cid']}','{$did}','{$author}','{$authorid}','{$time}','{$ip}','{$tmpdoc['title']}','{$tmpdoc['tag']}','{$tmpdoc['summary']}','{$tmpdoc['content']}')";
		$this->db->query($sql);
		$sql = "UPDATE `".DB_TABLEPRE."doc` SET summary = '{$summary}', content = '{$content}',lastedit = '{$time}' WHERE did = '{$did}'";
		$this->db->query($sql);
	}

	function roll_docs($page,$limit = 30){
		$title = NULL;

		$start = ($page-1)*$limit;
		$sql = "SELECT `did`,`title` FROM `".DB_TABLEPRE."doc` WHERE 1 LIMIT {$start},{$limit}";
		$query = $this->db->query($sql);
		while($tmp = $this->db->fetch_array($query)){
			if('gbk' == strtolower(WIKI_CHARSET)) {$title[] = string::hiconv($tmp['title'],'utf-8','gbk');}
			else {$title[] = $tmp['title'];}
		}
		if(!$title){return false;}
		else{
			include HDWIKI_ROOT.'/lib/json.class.php';
			$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);			
			$l = NEW_WIKI_POST_URL;
			$sitenick = $this->base->setting['site_nick'];
			if('gbk' == strtolower(WIKI_CHARSET)){
				$sitenick = string::hiconv($sitenick,'utf-8','gbk');
			}
			$postdata = array('type'=>'batch','site_nick'=>urlencode($sitenick),'key'=>$this->base->setting['site_key'],'docList'=>$title);
			if($postdata['site_nick'] && $postdata['key']){
				$jsondata = 'json='.$json->encode($postdata);
				$xmldata = $this->hfopen($l,0,$jsondata);
				return true;
			}else {
				return false;
			}
		}
	}
	
	function filter_external($content){
		preg_match_all("/<a[^>]*>(.*?)<\/a>/is", $content, $links);
		if ($links){
			for($i=count($links[0])-1; $i>=0; $i--){
				if (!preg_match("/href=\"?(http:\/\/(\w+?\.){1,2}hudong\.com|index\.php\?doc-innerlink)/i", $links[0][$i])){
					$content = str_replace($links[0][$i], $links[1][$i], $content);
				}
			}
		}
		return $content;
	}
	
	
	function sharetosns($did, $content, $op){
		$doc=$_ENV['hdapi']->get_doc_title_by_id($did, 'title, summary');
		$title=$doc['title'];
		$summary=trim($doc['summary']);
		
		if(empty($summary)){
			$doc=$_ENV['hdapi']->get_doc_title_by_id($did, 'content');
			$summary=trim(strip_tags($doc['content']));
			$summary=substr($summary, 0, 250);
		}
		
		$appkey =$this->base->setting['site_appkey'];
		
	  	if('gbk' == strtolower(WIKI_CHARSET)) {
			$title = string::hiconv($title,'utf-8','gbk');
			$summary = string::hiconv($summary,'utf-8','gbk');
			$content = string::hiconv($content,'utf-8','gbk');
		}
		
		$summary=string::convercharacter($summary);
		
		$link=urlencode(WIKI_URL.'/?doc-innerlink-'.urlencode($title));
		
		$rows=$this->db->fetch_first("SELECT attachment FROM ".DB_TABLEPRE."attachment WHERE did='$did' and isimage=1 order by id desc limit 1");
		if(!empty($rows)){
			if(strpos($rows['attachment'], '_') === false){
				$rows['attachment']=str_replace('.', '_140.', $rows['attachment']);
				
				if(is_file(HDWIKI_ROOT.'/'.$rows['attachment'])){
					$pic=urlencode(WIKI_URL.'/'.$rows['attachment']);
				}else{
					$pic='';
				}
			}
		}else{
			$pic='';
		}
		
		$post = 'op='.$op.'&appkey='.$appkey.'&url='.$link .'&title='.urlencode($title).'&content='.urlencode($content).'&pic='.$pic.'&summary='.urlencode($summary);
		$data = $this->hfopen(SNS_SHARE_URL, 0, $post);
		$obj = $_ENV['hdapi']->unjson($data);
		
		return $obj;
	}
}