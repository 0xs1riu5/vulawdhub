<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class recyclemodel {

	var $db;
	var $base;

	function recyclemodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function get_recycle($id){
		return $this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."recycle WHERE id=$id");
	}
	
	function get_count(){
		return $this->db->result_first("SELECT COUNT(*) num FROM ".DB_TABLEPRE."recycle WHERE 1");
	}
	
	function get_list ($start,$limit){
		$recyclelist=array();
		$query=$this->db->query("SELECT id,type,keyword,adminid,admin,dateline FROM ".DB_TABLEPRE."recycle WHERE 1 ORDER BY dateline DESC LIMIT $start,$limit");
		while($recycle=$this->db->fetch_array($query)){
			$recycle['dateline']=$this->base->date($recycle['dateline']);
			$recyclelist[]=$recycle;
		}
		return $recyclelist;
	}
	
	function search_recycle($start,$limit,$keyword,$author,$starttime,$endtime,$type){
		$recyclelist=array();
		$sql="SELECT id,type,keyword,adminid,admin,dateline FROM ".DB_TABLEPRE."recycle where 1=1 ";
		
		if($keyword){
			$sql=$sql." AND keyword like '%$keyword%' ";
		}
		if($author){
			$sql=$sql." AND admin = '$author' ";
		}
		if($starttime){
			$sql=$sql." AND dateline>=$starttime ";
		}
		if($endtime){
			$sql=$sql." AND dateline<=$endtime ";
		}
		if($type){
			$sql=$sql." AND type='$type' ";
		}
		$sql=$sql." ORDER BY dateline DESC LIMIT $start,$limit ";
		$query=$this->db->query($sql);
		while($recycle=$this->db->fetch_array($query)){
			$recycle['dateline'] = $this->base->date($recycle['dateline']);
			$recyclelist[]=$recycle;
		}
		return $recyclelist;
	}
	
	function search_recycle_num($keyword,$author,$starttime,$endtime,$type){
		$sql="SELECT count(*) num FROM ".DB_TABLEPRE."recycle where 1=1 ";
		if($keyword){
			$sql=$sql." AND keyword like '%$keyword%' ";
		}
		if($author){
			$sql=$sql." AND admin = '$author' ";
		}
		if($starttime){
			$sql=$sql." AND dateline>=$starttime ";
		}
		if($endtime){
			$sql=$sql." AND dateline<=$endtime ";
		}
		if($type){
			$sql=$sql." AND type='$type' ";
		}
		return $this->db->result_first($sql);
	}
	
	function  remove($ids){
		$query=$this->db->query("SELECT file FROM ".DB_TABLEPRE."recycle WHERE id IN ($ids) ");
		while($recycle=$this->db->fetch_array($query)){
			if($recycle['file']!='N;'){
				$files=unserialize($recycle['file']);
				foreach($files as $file){
					@unlink($file);
				}
			}
		}
		$this->db->query("DELETE FROM ".DB_TABLEPRE."recycle where id IN ($ids)");
	}
	
	function clear($pernum=100){
		$query=$this->db->query("SELECT file FROM ".DB_TABLEPRE."recycle WHERE 1 LIMIT $pernum ");
		while($recycle=$this->db->fetch_array($query)){
			if($recycle['file']!='N;'){
				$files=unserialize($recycle['file']);
				foreach($files as $file){
					@unlink($file);
				} 
			}
		}
		$this->db->query("DELETE FROM ".DB_TABLEPRE."recycle where 1 LIMIT $pernum");
	}
	
	function recover($eids){
		set_time_limit(0);
		$return = array();
		$models=array('doc'=>array('model'=>'doc','function'=>'recover'),
				  'edition'=>array('model'=>'doc','function'=>'recover_edition'),
					 'user'=>array('model'=>'user','function'=>'recover'),
			   'category'=>array('model'=>'category','function'=>'recover'),
			   'attachment'=>array('model'=>'attachment','function'=>'recover'),
				'comment'=>array('model'=>'comment','function'=>'recover'),
					 'gift'=>array('model'=>'gift','function'=>'recover'));
		$query=$this->db->query("SELECT id,type,keyword,content FROM ".DB_TABLEPRE."recycle WHERE id IN ($eids) ");
		$deleids = '';	// 最终要从回收站删除的数据ID
		$dids = '';
		$statu="0";
		$titles=array();//词条
		while($recycle=$this->db->fetch_array($query)){
			$content = unserialize($recycle['content']);
			if($models[$recycle['type']]['model'] == 'doc') {
				// 判断词条是否已经存在
				$content['doc'][0]['title']=string::haddslashes($content['doc'][0]['title']);
				$data=$this->db->fetch_by_field('doc','title',$content['doc'][0]['title']);
				if((bool)$data){
						$return['doc'][]['title'] = $content['doc'][0]['title'];
						continue;
				} else {
					$dids .= $content['doc'][0]['did'].',';
				}
			}

			$this->base->load($models[$recycle['type']]['model']);
			if($models[$recycle['type']]['model'] == 'user') {
				// 判断用户是否存在
				$user = $_ENV[$models[$recycle['type']]['model']]->get_user('username',$content['username'] );
				if(!empty($user)) {
					$return['user'][]['username'] = $content['username'];
					continue;
				}else{
					$user = $_ENV[$models[$recycle['type']]['model']]->get_user('email',$content['email'] );
					if(!empty($user)) {
						$return['user'][]['email'] = $content['email'];
						continue;
					}
				}
			}
			
			$deleids .= $recycle['id'].',';		//	去掉了不要被删除的ID
			// 在相应表中插入相应数据
			$_ENV[$models[$recycle['type']]['model']]->$models[$recycle['type']]['function']($content);
			
			if($recycle['type'] == 'doc'){
				$titles[addslashes($recycle['keyword'])]=$content['doc'][0]['did'];
			}
		}
		if(!empty($dids) && 1 == $this->base->setting['cloud_search']) {
			// 恢复词条 通知云搜索
			$dids = trim($dids, ',');
			$_ENV['search']->cloud_change(array('dids'=>$dids,'mode'=>'3'));
		}
		$deleids = trim($deleids, ',');
		if(!empty($deleids)) {
			$this->db->query("DELETE FROM ".DB_TABLEPRE."recycle where id IN ($deleids)");
		}
		
		if(!empty($titles)){
			foreach($titles as $title=>$titleid){
				$this->db->query("update ".DB_TABLEPRE."innerlinkcache set titleid='$titleid' where title='$title'");
			}
		}
		return $return;
	}
}
?>