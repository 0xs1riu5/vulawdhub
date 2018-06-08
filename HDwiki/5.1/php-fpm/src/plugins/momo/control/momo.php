<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->loadplugin('momo');
	}

	function dodefault(){
		$len=strlen('plugin-momo-momo-default-');
		$title=substr($_SERVER['QUERY_STRING'],$len);
		$title = urldecode($title);

		$title=trim($title);
		$title2 = $title;
		
		$title=urldecode($title);
		if(string::hstrtoupper(WIKI_CHARSET)=='GBK'){
			$title=string::hiconv($title,$to='gbk',$from='utf-8');
		}
		$doc=$_ENV['momo']->get_doc_by_title($title);
		if($doc){
			$doc['image']=util::getfirstimg($doc['content']);
			$momourl=trim($this->plugin[momo][vars][momourl]);
			if($momourl){
				$doc['url']=$momourl."index.php?doc-view-".$doc['did'].$this->setting['seo_suffix'];
			}else{
				$doc['url']=$this->setting['site_url']."/".$this->setting['seo_prefix']."doc-view-".$doc['did'].$this->setting['seo_suffix'];
			}
			$doc_exists=1;
		}else{
			$url = 'http://www.hudong.com/validateDocSummary.do?doc_title='.$title2;
			$data = util::hfopen($url);
			$doc_exists=1;
			if($data && stripos($data,'<flag>true</flag>') && preg_match_all("/<\!\[CDATA\[(.*)\]\]>/", $data, $matches)){
				$summary = $matches[1][1];
				$image = $matches[1][2];
				if ($summary == 'null') $summary = '';
				if ($image == 'null') $image = '';
				if(string::hstrtoupper(WIKI_CHARSET)=='GBK'){
					$summary=string::hiconv($summary,$to='gbk',$from='utf-8');
				}
				$doc = array(
					'image'=>$image,
					'url'=>'http://www.hudong.com/wiki/'.$title,
					'summary'=>$summary
				);
			}else{
				$doc_exists=0;
			}
			
		}
		$this->view->assign("doc_exists",$doc_exists);
		$this->view->assign("doc",$doc);
		$this->view->assign("encode",WIKI_CHARSET);
		$this->view->assign("title",$title);
		$this->view->display('file://plugins/momo/view/momo');
	}
}

?>
