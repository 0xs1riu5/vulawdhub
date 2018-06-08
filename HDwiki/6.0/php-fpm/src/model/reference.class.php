<?php
/**
词条编辑页面的参考资料
*/
!defined('IN_HDWIKI') && exit('Access Denied');

class referencemodel {

	var $db;
	var $base;

	function referencemodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	/**
	获取参考资料列表
	*/
	function getall($did){
		$list = array();
		$did = intval($did);
		$query=$this->db->query("SELECT `id`,`name`,`url` FROM ".DB_TABLEPRE."docreference where did=$did ORDER BY id ASC");
		while($row=$this->db->fetch_array($query)){
			$list[]=$row;
		}
		return $list;
	}
	
	/**
	添加参考资料
	*/
	function add($data){
		$data['did'] = intval($data['did']);
		$data['name'] = htmlspecial_chars(string::stripscript($data['name']));
		if (isset($data['id'])) return $this->edit($data);
		$sql = "INSERT INTO  ".DB_TABLEPRE."docreference(did,name,url) VALUES('{$data['did']}','{$data['name']}','{$data['url']}')";
		if ($this->db->query($sql)) return $this->db->insert_id();
	}
	
	/**
	编辑参考资料
	*/
	function edit($data){
		$data['id'] = is_int($data['id']) ? $data['id'] : 0;
		$data['name'] = htmlspecial_chars(string::stripscript($data['name']));
		$sql = "UPDATE ".DB_TABLEPRE."docreference SET name='{$data['name']}',url='{$data['url']}' WHERE id={$data['id']}";
		return $this->db->query($sql);
	}
	
	/**
	删除参考资料
	*/
	function remove($id){
		$id = is_int($id) ? $id : 0;
		$sql = "DELETE FROM ".DB_TABLEPRE."docreference WHERE id=$id";
		return $this->db->query($sql);
	}
}
?>