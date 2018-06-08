<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class pmsmodel {

	var $db;
	var $base;

	function pmsmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function check_recipient($sendto, $type){
		$userinfos = array();
		$send = array_unique(explode(',', $sendto));
		sort($send);
		$num = count($send);
		$sendto = str_replace(",", "','", $sendto);
		$query = $this->db->query("SELECT username,uid FROM ".DB_TABLEPRE."user WHERE username IN ('$sendto')");
		if($this->db->num_rows($query) == $num && $type != 1){
			return true;
		}
		while($userlist = $this->db->fetch_array($query)){
			$userinfo['uid'] = $userlist['uid'];
			$userinfo['username'] = $userlist['username'];
			$userinfos[] = $userinfo;
			$userindex = array_keys($send, $userlist['username']);
			unset($send[$userindex[0]]);
		}
		return (($type == 1) ? $userinfos : implode(',',$send));
	}

	function send_ownmessage($sendarray){
		$pmsresult = true;
		$isdraft = ($sendarray['isdraft'] === 'on')? 1 : 0;
		$userinfo = $this->check_recipient($sendarray['sendto'],1);
		$num = count($userinfo);
		if($num > 0){
			$pmsquery = "INSERT INTO ".DB_TABLEPRE."pms (`from`,`fromid`,`drafts`,`toid`,`to`,`subject`,`message`,`time`,`new`) VALUES ";
			for($i=0; $i<$num; $i++){
				$pmsquery .= "('".$sendarray['user']['username']."','".$sendarray['user']['uid']."','".$isdraft."','".$userinfo[$i]['uid']."','".$userinfo[$i]['username']."','".$sendarray['subject']."','".$sendarray['content']."','".$this->base->time."',1),";	
			}
			$pmsquery = substr($pmsquery,0,-1) . ';';
			$pmsresult = $this->db->query($pmsquery);
		}
		return $pmsresult;
	}
	
	function send_pubmessage($sendarray){	
		$begin = intval($sendarray['begin']);
		$step = intval($sendarray['step']);
		$isdraft = ($sendarray['isdraft'] == 'on')? 1 : 0;
		$continue = FALSE;
		$conditions = $sendarray['togroupid'] == '99999' ? '1=1' : "groupid=$sendarray[togroupid]";
		$query = $this->db->query("SELECT uid, username FROM ".DB_TABLEPRE."user WHERE $conditions LIMIT $begin, $step");
	    $groupquery = "INSERT INTO ".DB_TABLEPRE."pms (`from`,`fromid`,`drafts`,`toid`,`to`,`subject`,`message`,`time`,`new`,`og`) VALUES ";
        while($member = $this->db->fetch_array($query)) {
			$groupquery .= "('".$sendarray['user']['username']."','".$sendarray['user']['uid']."','".$isdraft."','".$member['uid']."','".$member['username']."','".$sendarray['subject']."','".$sendarray['content']."','".$this->base->time."',1,1),";	
        	$continue = TRUE;
         }
        $groupquery = substr($groupquery,0,-1) . ';';
        if($continue){
        	$this->db->query($groupquery);
        }
        $begin = $continue ==TRUE ? ($begin += $step) : FALSE;
        return $begin;
	}
	
	function send_sys_message(&$to_user, $subject, $message, $is_draft = 0) {
		$pmsquery = "INSERT INTO ".DB_TABLEPRE."pms (`from`,`fromid`,`drafts`,`toid`,`to`,`subject`,`message`,`time`,`new`, `og`) VALUES 
			('SYSTEM','0','{$is_draft}','{$to_user['uid']}','{$to_user['username']}','{$subject}','{$message}','{$this->base->time}',1,1)";
		return $this->db->query($pmsquery);	
	}

	function get_pmsmessage(){
		$pms = $this->db->fetch_first("SELECT value FROM ".DB_TABLEPRE."setting WHERE variable='pms_cache'");
		return unserialize($pms['value']);
	}
	
	function add_pmsmessage($sendarray){
		$sendarray = addslashes(serialize($sendarray));
		return($this->db->query("REPLACE INTO ".DB_TABLEPRE."setting (variable,value) VALUES('pms_cache','$sendarray')"));
	}
	
	function add_blacklist($blacklist,$uid){
		return($this->db->query("REPLACE INTO ".DB_TABLEPRE."blacklist (uid,blacklist) VALUES('$uid','$blacklist')"));
	}
	
	function get_blacklist($uid){
		$user = $this->db->fetch_first("SELECT blacklist FROM ".DB_TABLEPRE."blacklist WHERE uid='".$uid."'");
		return $user['blacklist'];
	}
	
	function get_box($boxarray){
		$start = $boxarray['start_limit'];
		$limit = $boxarray['num'];
		$pmslist = '';
		if($boxarray['type'] == 'inbox'){
			$blacklist = $this->get_blacklist($boxarray['user']['uid']);
			
			if($blacklist == "[ALL]"){
				return '';
			}else{
				$blacklist = mysql_real_escape_string($blacklist);
				$blackuser = str_replace(",","','",$blacklist);
				$sqladd = $boxarray['group']=='owner' ? 'AND og=0' : 'AND og=1';
				$sql = "SELECT * FROM ".DB_TABLEPRE."pms WHERE toid='".$boxarray['user']['uid']."' AND delstatus!=2 AND  drafts !=1 ";
				if($blackuser){$sql.="AND `from` NOT IN ('$blackuser')";}
				$sql.=$sqladd." ORDER BY id DESC LIMIT $start,$limit";
				$query = $this->db->query($sql);
			}
		}else{
			$isdraft=($boxarray['type']=='outbox')?'drafts!=1':'drafts=1';
			$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."pms WHERE fromid='".$boxarray['user']['uid']."' AND delstatus!=1 AND $isdraft ORDER BY id DESC LIMIT $start,$limit");	
		}
		while($pms = $this->db->fetch_array($query)){
			$pms['time']=$this->base->date($pms['time']);
			$pmslist[]=$pms;
		}
		return $pmslist;
	}
	
	function get_pms($id){
		$id = is_numeric($id) ? $id : 0;
		return $this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."pms WHERE id=$id");
	}
		
	function update_pms($messageids,$type){
		$tmp_messageids = array();
		$messageids = empty($messageids)? NULL : explode(',', $messageids);
		if(!empty($messageids) && is_array($messageids)) {
			foreach($messageids as $val) {
				if(is_numeric($val)) {
					$tmp_messageids[] = intval($val);
				}else{
					continue;
				}
			}
			if(!empty($tmp_messageids) && is_array($tmp_messageids)) {
				$messageids = implode(',', $tmp_messageids);
			}else {
				return false;
			}
		}
		$id = strpos($messageids , ',') ? substr($messageids, 0, strpos($messageids, ',')) : $messageids;
		if(is_numeric($id)) {
			$id = intval($id);
		}else {
			return false;
		}		
		$pms = $this->get_pms($id);
		if($pms['delstatus'] == $type || $type == 3){
			$result=$this->remove($messageids);
		}else{
			$type = ($type == 2) ? 1 : 2;
			$result=$this->db->query("UPDATE ".DB_TABLEPRE."pms SET delstatus='$type' WHERE id in ($messageids)");
		}
		return $result;
	}
	
	function remove($messageids){
		return($this->db->query("DELETE FROM ".DB_TABLEPRE."pms WHERE id in ($messageids)"));
	}
	
	function get_totalpms($uid, $type, $group=''){
		$sqladd = '';
		if($type == 'inbox'){
			$blacklist = $this->get_blacklist($uid);
			if($blacklist == '[ALL]'){
				return '0';
			}else{
				$blacklist = mysql_real_escape_string($blacklist);
				$blackuser = str_replace(",","','",$blacklist);
				if($group){
					$sqladd = ($group == 'owner') ? 'AND og=0' : 'AND og=1';
				}
				$query = "SELECT COUNT(*) num FROM ".DB_TABLEPRE."pms WHERE toid='$uid' AND delstatus!=2 AND drafts!=1 $sqladd AND `from` NOT IN ('$blackuser')";	
			}		
		}else{
			$sqladd = ($type == 'outbox') ? 'drafts!=1' : 'drafts=1';
			$query = "SELECT COUNT(*) as num FROM ".DB_TABLEPRE."pms WHERE fromid='$uid' AND delstatus!=1 AND $sqladd";			
		}
		$total = $this->db->fetch_first($query);
		return $total['num'];		
	}
	
	function remove_blacklist($uid){
		return($this->db->query("DELETE FROM ".DB_TABLEPRE."blacklist WHERE uid='$uid'"));
	}
	
	function set_read($id){
		return($this->db->query("UPDATE ".DB_TABLEPRE."pms SET `new`=0 WHERE id='$id'"));
	}
}
?>