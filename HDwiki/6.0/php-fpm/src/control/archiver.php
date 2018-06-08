<?php
/**
 * 用于云搜索抓取页面
 */
!defined('IN_HDWIKI') && exit('Access Denied');
class control extends base{
	function control(& $get,& $post){
		$this->base( $get, $post);
		$this->load("doc");
		$this->load("archiver");
	}

	function dodefault(){
		$this->dolist();
	}

	function dolist(){
		$page = !empty($this->get[2])&&is_numeric($this->get[2])?$this->get[2]:1;
		$num = !empty($this->get[3])&&is_numeric($this->get[3])?$this->get[3]:50;

		if($page < 1) {
			$page = 1;
		}
		$doclist = array();
		$count  = $_ENV['archiver']->get_total_num();
		if(0 < $count ){
			// 获得词条最大ID
			$maxdid = $_ENV['archiver']->get_max_did();
			$doclist = $_ENV['archiver']->get_doc_list(array('page'=>$page, 'num'=>$num));
			// 分页数据
			$totalpage = ceil($count / $num);
			$outhtml = $_ENV['archiver']->get_html_list($doclist, $totalpage, $num, $count, $maxdid);
		} else {
			$outhtml = $_ENV['archiver']->get_html_header().'No Body! No Body!'.$_ENV['archiver']->get_html_footer();
		}
		if ('gbk' == strtolower(WIKI_CHARSET)){
			$outhtml = string::hiconv($outhtml, 'utf-8', 'gbk');
		}
		$_ENV['archiver']->close_mysql();
		echo $outhtml;
	}

	function doview(){
		$did = !empty($this->get[2])&&is_numeric($this->get[2])?$this->get[2]:0;
		if(empty($did)) {
			$outxml = $_ENV['archiver']->get_xml_header().'No Body! No Body!'.$_ENV['archiver']->get_xml_footer();
		} else {
			$doc = $_ENV['archiver']->get_doc($did);
			$outxml = $_ENV['archiver']->get_html_view($doc);
		}
		$_ENV['archiver']->close_mysql();
		header("Content-Type: application/xml");
		echo $outxml;
	}
}

?>
