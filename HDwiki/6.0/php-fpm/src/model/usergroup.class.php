<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class usergroupmodel {

	var $db;
	var $base;

	function usergroupmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function add_usergroup($grouptitle,$type=0) {
		$this->db->query("INSERT INTO  ".DB_TABLEPRE."usergroup(grouptitle,`regulars`,`default`,type) VALUES ('$grouptitle', '', '',$type) ");
	}
	
	function change_usergroup($groupdata, $type=0, $action='update'){
		if (!is_array($groupdata)){
			return false;
		}
		if ('update' == $action){
			foreach($groupdata as $key => $value){
				$sql = "UPDATE ".DB_TABLEPRE."usergroup SET ";
				$i=0;
				foreach($value as $k => $v){
					if ($k != 'groupid') {$sql .= " `$k`='$v',";}
					$i++;
				}
				if(!$i) return false;
				$sql = substr($sql, 0, -1)." WHERE groupid=".$value['groupid'];
				$this->db->query($sql);
			}
		}elseif('insert' == $action){
			$sql = "INSERT INTO  ".DB_TABLEPRE."usergroup(`grouptitle`,`creditslower`,`stars`,`color`,`type`,`regulars`) VALUES ";
			$i=0;
			foreach($groupdata as $v){
				if (empty($v['grouptitle'])||($type == 2 && !is_numeric($v['creditslower']))) {continue;}
				if ( $type == 0) {$v['creditslower'] = 0;}
				if (!is_numeric($v['stars'])){$v['stars']=0;}
				if ($v['projectid'] == '0'){
					$v['projectid'] = '2';
				}
				$usergroup = $this->get_usergroup($v['projectid']);
				$regulars = $usergroup['regulars'];
				$i++;
				$sql .= "('{$v['grouptitle']}',{$v['creditslower']},{$v['stars']},'{$v['color']}',$type,'$regulars'),";
			}
			if(!$i) return false;
			$sql = substr($sql, 0, -1);
			if (count($groupdata)>0){
				$this->db->query($sql);
			}
		}elseif('delete' == $action) {
			$id=implode(',',$groupdata);
			if ($id){
				$this->db->query("DELETE FROM  ".DB_TABLEPRE."usergroup  WHERE groupid IN ($id)");
			}
		}
	}
	
	function remove_usergroup($groupid,$destgroupid) {
		$this->db->query("UPDATE ".DB_TABLEPRE."user SET groupid=$destgroupid WHERE groupid=$groupid ");
		$this->db->query("DELETE FROM  ".DB_TABLEPRE."usergroup WHERE groupid=$groupid ");
	}
	
	function edit_usergroup($groupid,$grouptitle,$groupregulars) {
		if (!$grouptitle && !$groupregulars){
			return false;
		}
		$str = '';
		if ($grouptitle){
			$str = "grouptitle='$grouptitle'";
		}
		if ($groupregulars){
			if ($str) {$str .= ',';}
			$str .= "regulars='$groupregulars'";
		}
		
		$this->db->query("UPDATE ".DB_TABLEPRE."usergroup SET $str WHERE groupid=$groupid ");
	}
	
	function resume_usergroup($groupid) {
		$this->db->query("UPDATE ".DB_TABLEPRE."usergroup SET  regulars=`default` WHERE groupid=$groupid ");
	}
	
	function get_usergroup($groupid){
		return $this->db->fetch_first('SELECT * FROM '.DB_TABLEPRE.'usergroup WHERE groupid='.$groupid);
	}
	
	function get_star($star){
		$starlist = array();
		$starlist[3] = floor($star/16);
		$starlist[2] = floor(($star - $starlist[3] * 16)/4);
		$starlist[1] = $star - $starlist[3] * 16 - $starlist[2] * 4;
		return $starlist;
	}
	
	function get_groupinfo($value, $field='u.uid'){
		$sql = 'SELECT g.groupid,g.grouptitle,g.`type`,g.creditslower,g.creditshigher,g.stars,g.color,g.groupavatar,u.username,u.image,u.credit2,u.uid,u.views,u.creates,u.edits,u.regtime,u.signature,u.credit1
		 FROM '.DB_TABLEPRE.'user u, '.DB_TABLEPRE.'usergroup g
		 WHERE u.groupid=g.groupid AND '.$field.'='.$value;
		$usergroup= $this->db->fetch_first($sql);
		$usergroup['editorstar'] = $this->get_star($usergroup['stars']);
		return $usergroup;
	}
	
	function get_userstar($uids){
		$userlist = array();
		if (is_array($uids)){
			$uids = implode(',',$uids);
		}
		if(empty($uids)){
			return $userlist;
		}
		$query= $this->db->query('SELECT g.grouptitle,g.`type`,g.stars,g.color,u.uid,u.username,u.image,u.credit1 FROM '.DB_TABLEPRE.'user u, '.DB_TABLEPRE.'usergroup g WHERE u.groupid=g.groupid AND u.uid in('.$uids.')');
		while($user=$this->db->fetch_array($query)){
			$user['userstars'] = $this->get_star($user['stars']);
			$userlist[$user['username']]=$user;
		}
		return $userlist;
	}
	
	function get_all_list($type = -1, $orderby='g.groupid ASC', $fields='*'){
		$usergrouplist = array();
		if ($type == -1){
			$query=$this->db->query('SELECT '.$fields.' FROM '.DB_TABLEPRE.'usergroup g ORDER BY '.$orderby);
		} else {
			$query=$this->db->query('SELECT '.$fields.' FROM '.DB_TABLEPRE.'usergroup g WHERE g.`type`='.$type.' ORDER BY '.$orderby);
		}
		while($usergroup=$this->db->fetch_array($query)){
			$usergrouplist[]=$usergroup;
		}
		return $usergrouplist;
	}
	
	function cache_memberlevel(){
		$creditslower = $this->get_all_list(2, 'g.creditslower DESC', 'grouptitle, creditslower');
		$this->base->cache->writecache('usergroupMemberLevel', $creditslower);
		return $creditslower;
	}
	
	function get_memberlevel(){
		$creditslower = $this->base->cache->getcache('usergroupMemberLevel');
		if (!is_array($creditslower)){
			$creditslower = $this->cache_memberlevel();
		}
		return $creditslower;
	}
	
	function is_empty($groupid){
		$user = $this->base->cache->getcache('usergroup_empty_'.$groupid, 3600*24*30);
		if (!$user){
			$user = $this->db->fetch_first('SELECT uid FROM '.DB_TABLEPRE.'user WHERE groupid='.$groupid.' limit 1');
			$user = empty($user)? '1' : '0';
			$user = array($user);
			$this->base->cache->writecache('usergroup_empty_'.$groupid, $user);
		}
		return $user[0];
	}
	
}


?>