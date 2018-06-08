<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class taskmodel {

	var $db;
	var $base;

	function taskmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function get_task_list(){
		$tasklist = array();
		$query = $this->db->query("SELECT * FROM ".DB_TABLEPRE."task");
		while($task = $this->db->fetch_array($query)){
			$task['lastrun'] = $this->base->date($task['lastrun']);
			$task['nextrun'] = $this->base->date($task['nextrun']);
			if($task['weekday'] == 8 || !$task['weekday'])$task['weekday'] = '*';
			if(!$task['day'])$task['day'] = '*';
			$task['hour'] = intval($task['hour']);
			$task['minute'] = intval($task['minute']);
			$tasklist[] = $task;
		}
		return $tasklist;
	}
	
	function edit_task_status($id){
		$task = $this->get_task($id);
		if($task['id']){
			if($task['status']){
				$status = 0;
			}else{
				$status = 1;
				$nextrun = $this->get_task_runtime($id);
				$this->db->query("UPDATE ".DB_TABLEPRE."task SET nextrun = '$nextrun' WHERE id = $id");
			}
			$this->db->query("UPDATE ".DB_TABLEPRE."task SET status = '$status' WHERE id = $id");
			return true;
		}else{
			return false;
		}
	}
	
	function get_task($id){
		return $this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."task WHERE id = '$id'");
	}
	
	function del_task($id){	
		if(is_array($id)){
			$ids=implode(',',$id);
		}else{
			$ids =$id;
		}
		$this->db->query("DELETE FROM `".DB_TABLEPRE."task` WHERE id IN ($ids)");
	}
	
	function add_task($taskname){
		$task = $this->db->fetch_first("SELECT id FROM ".DB_TABLEPRE."task WHERE name = '$taskname'");
		if($task['id']){
			return false;
		}else{
			$this->db->query("INSERT INTO ".DB_TABLEPRE."task (`name`,`weekday`) VALUES ('$taskname','8')");
			$id = $this->db->insert_id();
			$nextrun = $this->get_task_runtime($id);
			$this->db->query("UPDATE `".DB_TABLEPRE."task` SET nextrun = '$nextrun' WHERE id = $id");
			return $id;
		}
	}
	
	function edit_task($id,$name,$w,$d,$h,$i,$url='#'){
		$this->db->query("UPDATE ".DB_TABLEPRE."task SET name = '$name',weekday='$w', day ='$d', hour = '$h', minute = '$i' WHERE id = $id");
		$nextrun = $this->get_task_runtime($id);
		$this->db->query("UPDATE ".DB_TABLEPRE."task SET nextrun = '$nextrun' WHERE id = $id");
	}
	
	function get_task_runtime($id){
		$task = $this->get_task($id);
		$w = intval($task['weekday']);
		$d = intval($task['day']);
		$h = intval($task['hour']);
		$i = intval($task['minute']);
		$time = $this->base->time;
		$tmp = date("Y-m-d-h-i-s-w",$time);
		list($ty,$tm,$td,$th,$ti,$ts,$tw) = explode("-",$tmp);		
		if($w == 8){
			if($th > $h)$td++;
			if($th == $h && $ti>$i)$td++;
			$y = $ty;
			if(!$d){
				$time = $time + 3600*24;
				$tstr = date('Y-m-d',$time);
				return strtotime("$tstr $h:$i:0");
			}else{
				if($d < $td){				
					$m = $tm + 1;
					if($m>12){
						$y++;
						$m = $m %12;
					}				
				}else $m = $tm;
				while(!checkdate($m,$d,$y)){
					if($m>12){
						$y++;
						$m = $m %12;
					}
					$m++;
				}
				return strtotime("$y-$m-$d $h:$i:0");
			}
		}else{
			$w = $w % 7;
			if($w < $tw)$add = $w + 7 - $tw;
			elseif($w == $tw){
				if($th > $h)$add = 7;
				if($th == $h && $ti>$i)$add = 7;
			}
			else $add = $w - $tw;
			$time = $time + $add * 3600 * 24;
			$tmpstr = date("Y-m-d",$time);
			return strtotime("$tmpstr $h:$i:0");
		}
	}
}


?>