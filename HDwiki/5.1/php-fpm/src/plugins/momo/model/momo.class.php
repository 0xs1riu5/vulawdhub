<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class momomodel {

	var $db;
	var $base;
	
	function momomodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	/*插件必须具有的安装方法*/
	function install(){
		$plugin=array(
			'name'=>'互动摸摸',
			'identifier'=>'momo',
			'description'=>'通过互动摸摸，可以使用户在您的任何站点上方便地查询百科词条！',
			'datatables'=>'momo',
			'type'=>'0',
			'copyright'=>'hudong.com',
			'homepage'=>'http://kaiyuan.hudong.com',
			'version'=>'V1.4',
			'suit'=>'5.0',
			'modules'=>''
		);
		$var=array(
			array('displayorder'=>"0",
			'title'=>'摸摸类型',
			'description'=>'选择摸摸适用类型',
			'variable'=>'momotype',
			'type'=>'select',
			'value'=>'2',
			'extra'=>'0=适用于内链和鼠标划词
			1=仅适用于内链
			2=仅适用于鼠标划词'),
			array('displayorder'=>"1",
			'title'=>'摸摸URL',
			'description'=>'摸摸内容的来源网址,仅限hdwiki。以“http://”开头，以“/”结尾。若留空，则默认为本站URL。',
			'variable'=>'momourl',
			'type'=>'text',
			'value'=>'',
			'extra'=>''),
			array('displayorder'=>"2",
			'title'=>'摸摸有效区域ID号',
			'description'=>'摸摸有效区域的ID，即页面div的id。若为空，则表示整个页面摸摸都有效。目前不支持多个ID。',
			'variable'=>'momoid',
			'type'=>'text',
			'value'=>'',
			'extra'=>''),
			array('displayorder'=>"3",
			'title'=>'摸摸划词长度(字符)',
			'description'=>'摸摸划词长度，每个汉字及字母都算一个字符。若留空，则默认为16个字符。',
			'variable'=>'momolength',
			'type'=>'text',
			'value'=>'16',
			'extra'=>'')
		);
		$plugin['hooks']=array();
		$plugin['vars']=$var;
		return $plugin;
	}
	function update($vars){
		$momolength=trim($vars['momolength']);
		$momoid=trim($vars['momoid']);
		$momourl=trim($vars['momourl']);
		$momourl = preg_replace("/\/$/", '', $momourl);
		if(!empty($momourl) && !preg_match("/^(http:\/\/)/i", $momourl)) {
			return ('您输入的URL地址不正确！');
		}
		if($momolength!='' && !is_numeric($momolength)){
			return('划词长度不为数字！');
		}
		
		return true;
	}

	/*插件必须具有的卸载方法*/
	function uninstall(){
		
	}
	function get_momo($pluginid){
		$query=$this->db->query("select variable,value from  ".DB_TABLEPRE."pluginvar where pluginid=$pluginid ");
		while($hdmomo=$this->db->fetch_array($query)){
			$momolist[]=$hdmomo;
		}
		return @$momolist;
	}
	
	function get_doc_by_title($title){
		return $this->db->fetch_by_field('doc','title',$title);
	}
}
?>