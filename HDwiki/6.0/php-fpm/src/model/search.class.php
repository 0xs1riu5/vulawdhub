<?php
!defined('IN_HDWIKI') && exit('Access Denied');
define('CLOUD_SEARCH','http://union.hudong.com/search/search');
define('CLOUD_SEARCH_URL','http://union.hudong.com/search/searchUrl');
define('CLOUD_CHANGE_URL','http://union.hudong.com/spider/spider');
define('CLOUD_REGISTER_URL','http://union.hudong.com/sitelist/registerSite');

class searchmodel {

	var $db;
	var $base;

	function searchmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function get_total_num($sql){
		$query=$this->db->query($sql);
		$data=$this->db->fetch_array($query);
		return $data['num'];
	}
	
	function fulltext_search($sql,$start=0,$limit=10){
		$doclist=array();
		$query=$this->db->query($sql." limit $start,$limit");
		while($doc=$this->db->fetch_array($query)){
			$doc['time']=$this->base->date($doc['time']);
			$doc['tag']=$_ENV['doc']->spilttags($doc['tag']);
			$doc['rawtitle']=$doc['title'];
			$doc['title']=htmlspecial_chars($doc['title']);
			$doclist[]=$doc;
		}
		return $doclist;
	}
	
	function join_sql($element){
		$keywords = $element['keyword'];
		$searchtitle=($_ENV['doc']->get_total_num() < $this->base->setting['search_num'])?0:1;
		if($searchtitle){
			$sqlkeywords .="d.title LIKE '$keywords%'";
			$element['author']="";
			$element['categoryid'][0]='all';
		}else{
			$sqlkeywords .="d.".$element['searchtype']." LIKE '%$keywords%'";
		}	
		
		$sqladd=(trim($sqlkeywords)!='')?' AND ('.$sqlkeywords.")":"";
		$sqladd .=(bool)$element['author']?" AND d.author='".$element['author']."' ":"";
		$sqladd .=('all'!=$element['categoryid'][0])?' AND c.cid in ('.implode(',',$element['categoryid']).') ':'';
		if(0!=$element['timelimited']){
			$sqladd .=('within'==$element['withinbefore'])?" AND d.`time` > '".($this->base->time-$element['timelimited'])."'" :" AND d.`time` < '".($this->base->time-$element['timelimited'])."'";
		}
		$order=" ORDER BY d.".$element['ordertype']." ".$element['ascdesc']."";
		$sqladdcat = ('all'!=$element['categoryid'][0])?"INNER JOIN ".DB_TABLEPRE."categorylink c ON d.did=c.did":'';
		$result['sql']='SELECT d.did,d.tag,d.title,d.author,d.authorid,d.time,d.summary,d.edits, d.views,d.comments FROM '.DB_TABLEPRE.'doc d '.$sqladdcat.' WHERE 1 '.$sqladd.$order;
		$result['dsql']='SELECT COUNT(*) as num FROM '.DB_TABLEPRE.'doc d '.$sqladdcat.' WHERE 1 '.$sqladd;
		return $result;
	}

	/**
	 *云搜索接口
	 * @param <type> $keyword
	 * @return <type>
	 */
	function cloud_search($keyword){
		if(strtoupper(substr($keyword,0,4))=='TAG:'&& strlen($keyword)>4){
			$keyword=substr($keyword,4);
		}
		// 去掉添加的引号转义
		$keyword = stripslashes($keyword);
		// 编码转为UTF8
		if ('gbk' == strtolower(WIKI_CHARSET)){
			$keyword = string::hiconv($keyword, 'utf-8', 'gbk');
		}
		
		$keyword = urlencode($keyword);
		// 判断缓存
		if(!isset($this->base->setting['cloud_search_cache'])) $this->base->setting['cloud_search_cache'] = 300;
		$clodecache = $this->base->cache->getcache('cloud_search', $this->base->setting['cloud_search_cache']);

		if($clodecache && $this->base->time -  $clodecache[0] < $this->base->setting['cloud_search_cache'] ) {
			// 缓存存在，拼链接
			$jsondata = $this->get_jason_data(array('query'=>$keyword,'type'=>'doc'));
			$content = CLOUD_SEARCH.'?'.$jsondata;
			
		} else {
			$timeout = isset($this->setting['cloud_search_timeout'])?$this->setting['cloud_search_timeout']:5;		//超时时间
			// 准备JASON数据
			$jsondata = $this->get_jason_data(array('query'=>$keyword,'type'=>'doc'));
			$content = util::hfopen(CLOUD_SEARCH_URL,'',$jsondata,'','','',$timeout);
		}
		
		if(empty($content)) {
			$content = false;
		} else {
			// 握手状态缓存
			$this->base->cache->writecache('cloud_search',array(time()));
		}

		return $content;
	}

	/**
	 * 云搜索--创建、更新接口
	 * @param <type> $content
	 */
	function cloud_change($content){
		 // $content['mode'] 操作方式（0，删除，1：创建；2修改;3恢复）
		$dids = $content['dids'];
		if(!empty($dids)) {
			$dids = trim($dids, ',');
		}
		if(!empty($dids)) {
			$jsondata = $this->get_jason_data(array('dids'=>$dids,'type'=>'doc', 'mode'=>$content['mode']));
			// 发送数据
			$timeout = isset($this->setting['cloud_search_timeout'])?$this->setting['cloud_search_timeout']:5;
			util::hfopen(CLOUD_CHANGE_URL, '',$jsondata,'','','',$timeout);
		}
	}

	function get_jason_data($content){
		include_once HDWIKI_ROOT.'/lib/json.class.php';
		$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);

		// 规范URL
		$siteUrl = strtolower(WIKI_URL);
		$pos = strpos($siteUrl, 'http://');
		if(false === $pos) {
			$pos = strpos($siteUrl, 'https://');
			if(false === $pos) {
				$siteUrl = 'http://'.$siteUrl;
			}
		}

		if ('gbk' == strtolower(WIKI_CHARSET)){
			$sitename = string::hiconv($this->base->setting['site_name'], 'utf-8', 'gbk');
		} else {
			$sitename = $this->base->setting['site_name'];
		}
		$sitename = urlencode($sitename);
		$jsondata = array_merge(array('siteName'=>$sitename,'siteUrl'=>$siteUrl), $content);
		$jsondata='json='.$json->encode($jsondata);
		return $jsondata;
	}

	function clode_register() {
		$privateip = util::is_private_ip();
		if(!$privateip) {
			$jsondata = $this->get_jason_data(array());
			// 发送数据
			$timeout = isset($this->setting['cloud_search_timeout'])?$this->setting['cloud_search_timeout']:5;
			$flag = util::hfopen(CLOUD_REGISTER_URL, '',$jsondata,'','','',$timeout);
		}
		
		if(empty($flag) || $privateip) {
			// 关闭云搜索
			$this->db->query("REPLACE INTO `".DB_TABLEPRE."setting` (`variable`, `value`) values ('cloud_search', '0')");
		}
		return $flag;
	}
}


?>