<?php

!defined('IN_HDWIKI') && exit('Access Denied');
 
class control extends base{

	function control(& $get,& $post){
		$this->base( $get, $post);
		$this->load('search');
		$this->load('doc');
		$this->load("synonym");
		$this->load('category');
	}
	
	function dodefault() {
		$title=trim($this->post['searchtext']);
		/*if($title==''){
			$this->header();
			exit;
		}*/
		// 同义词查找
		$synonym=$_ENV['synonym']->get_synonym_by_src($title);
		if($synonym){
			header('Location:index.php?doc-innerlink-'.rawurlencode($synonym['srctitle']));
			exit;
		}
		// 词条查找
		$doc=$this->db->fetch_by_field('doc','title',$title);
//		/$this->view->assign("searchtext",$title);
//		//$this->view->assign("searchword",urlencode(string::hiconv($title,'utf-8')));

		if(!(bool)$doc){
			$title = str_replace(array('-', '.'),array('&#45;', '&#46;'), $title);
			$title = rawurlencode($title);
			header("Location:index.php?search-fulltext-title-$title--all-0-within-time-desc-1");
		}else{
			$this->header("doc-view-".$doc['did']);
		}
	}
	
	function dofulltext(){
		 if(!$this->get[3] && !$this->get[10]){
			if(1 == $this->setting['cloud_search']){
				// 云搜索开启后，关闭本地搜索
				$this->header();
			}
			$all_category=$_ENV['category']->get_category_cache();
			$categorytree=$_ENV['category']->get_categrory_tree($all_category);

			$this->view->assign("categorytree",$categorytree);
			$_ENV['block']->view('search');
		}else{
			$page=isset($this->get[11])?intval($this->get[11]):'';
			if(empty($page) || !is_numeric($page)){
				$page=1;
				// 指定时间内只能进行一次搜索
				$search_time=isset($this->setting['search_time'])?$this->setting['search_time']:30;
				if(''!=$this->hgetcookie('searchtime') && $search_time > $this->time-$this->hgetcookie('searchtime'))
					$this->message($this->view->lang['search_time_error1'].$search_time.$this->view->lang['search_time_error2'],"BACK",0);
				else
					$this->hsetcookie('searchtime',$this->time,24*3600*365);
			}
			// 获得搜索类型和搜索关键字
			$element['searchtype']=$this->get[2];	// tag or title
			$element['keyword']=isset($this->get[3])?string::haddslashes(str_replace(array('&#45;', '&#46;'), array('-', '.'), rawurldecode($this->get[3]))):'';
			// 自动转码，将编码变为当前设置编码
			//$element['keyword']= string::hiconv(trim($element['keyword']));
			//$element['keyword']=string::haddslashes($element['keyword'],1);
			
			$author=isset($this->get[4])?string::haddslashes(urldecode($this->get[4])):'';
			$element['author']=$author?str_replace('*','%',$author):'';
			$element['categoryid']=isset($this->get[5])?explode(",",$this->get[5]):'all';
			$element['timelimited']=isset($this->get[6])?$this->get[6]:0;
			$element['withinbefore']=isset($this->get[7])?$this->get[7]:'within';
			$element['ordertype']=isset($this->get[8])?$this->get[8]:'time';
			$element['ascdesc']=isset($this->get[9])?$this->get[9]:'desc';
			if(!(bool)$element['keyword']){
				$this->message($this->view->lang['searchKeywordNull'],"BACK",0);
			}elseif(strtoupper(substr($element['keyword'],0,4))=='TAG:'&&strlen($element['keyword'])>4){
				$element['keyword']=substr($element['keyword'],4);
				$element['searchtype']='tag';
			}
			if($element['searchtype']!="title"&&$element['searchtype']!="tag"&&$element['searchtype']!="content"){
				$element['searchtype']="title";
			}
			if($element['categoryid']!="all"&&!preg_match("/^\d[\d\,]*?$/i",implode(",",$element['categoryid']))){
				$element['categoryid'][0]="all";
			}
			if(!is_numeric($element['timelimited'])){
				$element['timelimited']=0;
			}
			if($element['withinbefore']!="within" && $element['timelimited']!="before"){
				$element['timelimited']="within";
			}
			if($element['ordertype']!="time"&& $element['ordertype']!="comments"&&$element['ordertype']!="views"){
				$element['ordertype']="time";
			}
			if($element['ascdesc']!="asc"&&$element['ascdesc']!="desc"){
				$element['ascdesc']="desc";
			}

			// 初始化云搜索
			$cloudsearch = 0;
			if(1 == $this->setting['cloud_search']){
				//最后一次云搜索异常时间
				$cloud_search_last_time = $this->hgetcookie('lasttime');
				// 异常时，关闭云搜索的时间，默认关闭30秒
				$cloud_search_close_time = isset($this->setting['cloud_search_close_time'])?$this->setting['cloud_search_close_time']:30;
				if(empty($cloud_search_last_time) || ($this->time - $cloud_search_last_time > $cloud_search_close_time  )) {
					$cloudsearch = 1;
					// 云搜索
					$iframesrc = $_ENV['search']->cloud_search($element['keyword']);
					$this->view->assign('iframesrc',$iframesrc);
					if(!$iframesrc || 2 > strlen($iframesrc)) {
						// 显示超时信息
						// 云搜索超时
						// 设置暂时关闭云搜索
						$this->hsetcookie('lasttime', $this->time, $cloud_search_last_time);
						$cloudsearch = 0;
					}
				}
			}
			if(0 == $cloudsearch){
				$result=$_ENV['search']->join_sql($element);
				$count=$_ENV['search']->get_total_num($result['dsql']);
				$count=$count<=500?$count:500;	// 最多500条记录
				$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
				$start_limit = ($page - 1) * $num;
				$list=$_ENV['search']->fulltext_search($result['sql'],$start_limit,$num);
				$keyword_for_view=str_replace("|","\|",$element['keyword']);
				foreach($list as $key => $value){
					$list[$key]['title'] = preg_replace("|({$keyword_for_view})|i", "<span style='color:red'>\$1</span>", $value['title']);
				}

				$url="search-fulltext-$element[searchtype]-".str_replace(array('-', '.'),array('&#45;', '&#46;'),rawurlencode($element[keyword]))."-".urlencode($element[author])."-".implode(',',$element[categoryid])."-$element[timelimited]-$element[withinbefore]-$element[ordertype]-$element[ascdesc]-1";
				$url=isset($this->setting['seo_prefix'])?$url:"index.php?".$url;
				$departstr=$this->multi($count, $num, $page,$url);

				$allcategory=$_ENV['category']->get_category_cache();
				$categorylist=$_ENV['category']->get_site_category(0,$allcategory);
			}
			$searchtext=stripslashes($element['searchtype']=="tag"?"TAG:".stripslashes($element['keyword']):stripslashes($element['keyword']));

			// 标题搜索，查找同义词，并给出提示
			if($element['searchtype']=="title") {
				// 查找同义词
			$synonym=$_ENV['synonym']->get_synonym_by_src($element['keyword']);
				if($synonym){
					//header('Location:index.php?doc-innerlink-'.urlencode($synonym['srctitle']));
					$synonym['linktitle'] =rawurlencode($synonym['srctitle']);
					$this->view->assign("synonym",$synonym);
					//exit;
				} else {
					//创建词条提示
					$docexit=$this->db->fetch_by_field('doc','title',$element['keyword']);
					if(!$docexit) {
						$this->view->assign("docnoexit",1);
					}
				}
			}
			
			$title=htmlspecial_chars(stripslashes($element['keyword']));

			$this->view->assign("title",$title);
			$this->view->assign("keyword",rawurlencode($element['keyword']));
			$this->view->assign("searchword",urlencode(string::hiconv($title,'utf-8')));
			$this->view->assign("search_tip_switch", $this->setting['search_tip_switch']);
				
 			$this->view->assign('cloudsearch',$cloudsearch);
			$this->view->assign('categorylist',$categorylist);
			$this->view->assign("searchtext",$searchtext);
			$this->view->assign("list",$list);
			$this->view->assign("count",$count);
			$this->view->assign('navtitle',$this->view->lang['search'].'-'.stripslashes(stripslashes($element['keyword'])));
			$this->view->assign("departstr",$departstr);
			//$this->view->display("searchresult");
			 if ($this->isMobile()){
				 $_ENV['block']->view('wap-searchresult');
			 } else {
				 $_ENV['block']->view('searchresult');
			 }
		}
	}

	function doagent(){
		//$this->view->display("cloudsearchagent");
		$_ENV['block']->view('cloudsearchagent');
	}
	
	function dotag() {
		$keyword=trim($this->get[2]);
		header("Location:index.php?search-fulltext-tag-$keyword--all-0-within-time-desc-1");
	}

	function dokw(){
		$searchtype = isset($this->post['searchtype'])?$this->post['searchtype']:'title';
		$keyword=isset($this->post['searchtext'])?$this->post['searchtext']:rawurldecode($this->get[2]);
		if(!empty($keyword)) {
			$keyword = str_replace(array('-', '.'),array('&#45;', '&#46;'), $keyword);
			$keyword = rawurlencode(trim($keyword));
			
		}
		
		$author = isset($this->post['author'])?$this->post['author']:'';
		if(!empty($author)) {
			$author = rawurlencode($author);
		}
		$category = isset($this->post['categoryid'])?$this->post['categoryid']:'all';
		if(is_array($category)) {
			$category = implode(',',$category);
		}
		$timelimited = isset($this->post['timelimited'])?$this->post['timelimited']:'0';
		$withinbefore = isset($this->post['withinbefore'])?$this->post['withinbefore']:'within';
		$ordertype = isset($this->post['ordertype'])?$this->post['ordertype']:'time';
		$ascdesc = isset($this->post['ascdesc'])?$this->post['ascdesc']:'desc';

		header("Location:index.php?search-fulltext-$searchtype-$keyword-$author-$category-$timelimited-$withinbefore-$ordertype-$ascdesc-1");
	}
}
?>