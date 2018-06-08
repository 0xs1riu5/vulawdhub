<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class archivermodel {
	var $db;
	var $base;

	function archivermodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function get_doc_list($condition){
		$condition['start'] = ($condition['page'] - 1 ) * $condition['num'];
		if(0 > $condition['start']) {
			$condition['start'] = 0;
		}
		$sql = "SELECT  title, did ,lastedit FROM ".DB_TABLEPRE."doc WHERE visible=1 ORDER BY lastedit DESC LIMIT ".$condition['start']."  , ".$condition['num']." ";
		$query=$this->db->query($sql);
		while($doc=$this->db->fetch_array($query)){
			$doclist[] = $doc;
		}
		return $doclist;
	}

	function get_doc($did){
		$doc='';
		if(is_numeric($did) && !empty($did)) {
			$query = $this->db->query("SELECT * FROM ".DB_TABLEPRE."doc WHERE did='$did' ");
			$doc = $this->db->fetch_array($query);
		}
		return $doc;
	}
	function get_total_num() {
		return $this->db->result_first("SELECT COUNT(*) num FROM ".DB_TABLEPRE."doc WHERE 1");
	}

	function get_max_did(){
		return $this->db->result_first("SELECT MAX(did) as did FROM ".DB_TABLEPRE."doc WHERE 1 ");
	}

	function get_html_header(){
		return '<html xmlns="http://www.w3.org/1999/xhtml" id="html"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head><body>';
	}

	function get_html_footer(){
		return '</body></html>';
	}

	function get_xml_header(){
		return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"."<doc>\n";
	}

	function get_xml_footer(){
		return "</doc>";
	}

	function get_html_list($doclist, $totalpage, $num, $count, $maxdid){
		$outhtml = '';
		if(!is_array($doclist) || empty($doclist)) {
			$outhtml .= 'No Body! No Body!';
		} else {
			$outhtml .= '<ul>';
			if(is_array($doclist) && !empty($doclist)) {
				foreach($doclist as $v) {
						$outhtml .= '<li><a href="index.php?archiver-view-'.$v['did'].'">'.$v['title'].'</a><span>'.date("Y-m-d H:i:s", $v['lastedit']).'|'.$v['lastedit'].'</span></li>';
				}
			}
			$outhtml .= '</ul>';
			if(1 <= $totalpage) {
				$outhtml .= '<div id="page">';
				for($i=1;$i<=$totalpage;$i++) {
					$outhtml .= '<span><a href="index.php?archiver-list-'.$i.'-'.$num.'">'.$i.'</a>&nbsp';
				}
				$outhtml .= "<p id='count'>$count,$maxdid</p>";
				$outhtml .= '</div>';
			}
		}
		return $this->get_html_header().$outhtml.$this->get_html_footer();
	}

	function get_html_view($doc){
		if(!empty($doc)) {
			$outxml = '';
			if ('gbk' == strtolower(WIKI_CHARSET)){
					$transition = 1;
			}
			else $transition = 0;
			foreach($doc as $k=>$v) {
				if($transition ) {
					$v = string::hiconv($v, 'utf-8', 'gbk', 1);
				}
				$outxml .= "<$k><![CDATA[".$v."]]></$k>\n";
			}
		} else {
			$outxml = 'No Body! No Body!';
		}
		return $this->get_xml_header().$outxml.$this->get_xml_footer();
	}

	function close_mysql(){
		$this->db->close();
	}
}
?>
