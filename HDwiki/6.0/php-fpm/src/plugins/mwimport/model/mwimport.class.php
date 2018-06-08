<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class mwimportmodel {

	var $db;
	var $base;
	
	function mwimportmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
 
	function install(){
		$plugin=array(
		'name'=>'mediawiki数据导入',
		'identifier'=>'mwimport',
		'description'=>'将已经安装的MediaWiki程序的用户、词条以及分类导入到HDwiki中。',
		'type'=>'0',
		'copyright'=>'www.baike.com',
		'version'=>'1.0',
		'suit'=>'5.0',
		'modules'=>'',
		'vars'=>array(),
		'hooks'=>array()
		);
		return $plugin;
	}
	
	 
	function uninstall(){}

	function writefile($file,$data){
	    $fp = fopen($file, 'wb');
	    $byte = fwrite($fp, $data);
	    fclose($fp);
	    return  $byte;
	}

	function get_nav (&$wmdb,$cat_title){
	    static $nav=array();
	    $sql = "SELECT cl.cl_to name,c.cat_id cid  FROM ".WDB_TABLEPRE."page p JOIN ".WDB_TABLEPRE."categorylinks cl JOIN ".WDB_TABLEPRE."category c on cl.cl_from = p.page_id AND c.cat_title = cl.cl_to WHERE p.page_title = '".$cat_title."'";
	    $clink = $wmdb->fetch_first($sql);
	    if($clink){
		$nav[] = array('cid'=> $clink['cid'] , 'name'=>$clink['name']);
		$this->get_nav($wmdb, $clink['name']);
	    }
	    return $nav;
	}

	function get_groupid (&$wmdb,$uid){
	    $sql = "SELECT ug_group  FROM ".WDB_TABLEPRE."user_groups WHERE ug_user = '".$uid."'";
	    $query = $wmdb->query($sql);
	    $data = array();
	    while($group = $wmdb->fetch_array($query)){
		$data[] = $group['ug_group'];
	    }
	    if(in_array('sysop', $data)){
		return 4;
	    }elseif(in_array('bureaucrat', $data)){
		return 3;
	    }
	    return 2;
	}

	function get_cid (&$wmdb,$cname='',$did=''){
	    if($cname){
		$sql = "SELECT cat_id  FROM ".WDB_TABLEPRE."category WHERE cat_title = '".$cname."'";
		$cid = $wmdb->result_first($sql);
	    }elseif($did){
		$sql = "SELECT cl_to  FROM ".WDB_TABLEPRE."categorylinks WHERE cl_from = ".$did;
		$cname = $wmdb->result_first($sql);
		return $this->get_cid($wmdb,$cname);
	    }
	    return $cid>0?$cid:0;
	}

	function get_time($str){
	    $hour = $str[8].$str[9];
	    $minute = $str[10].$str[11];
	    $second = $str[12].$str[13];
	    $month = $str[4].$str[5];
	    $day = $str[6].$str[7];
	    $year = $str[0].$str[1].$str[2].$str[3];
	    return mktime($hour, $minute, $second, $month, $day, $year);
	}

	function get_sql(&$wmdb,$type,$start,$end){
	    switch ($type){
		case 1://导入分类
		    $nav = array();
		    $pid = 0;
		    $hdsql = "REPLACE INTO ".DB_TABLEPRE."category (`cid`, `pid`, `name`, `docs`, `navigation`) VALUES ";
		    $sql = "SELECT *  FROM ".WDB_TABLEPRE."category  limit ".$start.",$end";
		    $query = $wmdb->query($sql);
		    while($cat = $wmdb->fetch_array($query)){
			$nav  = $this->get_nav($wmdb, $cat['cat_title']);
			if($nav){
			    $pid = $nav[0]['cid'];
			    krsort($nav);
			}
			$nav[] = array('cid'=> $cat['cat_id'] , 'name'=>$cat['cat_title']);
			$hdsql .= "(".$cat['cat_id'].", ".$pid.", '".$cat['cat_title']."',".($cat['cat_pages']-$cat['cat_subcats']).", '".serialize($nav)."'),";
		    }
		    break;
		case 2://导入用户
		    $hdsql = "REPLACE INTO ".DB_TABLEPRE."user (uid, username, email, credit2, regtime, lasttime, groupid, truename, edits) VALUES ";
		    $sql = "SELECT *  FROM ".WDB_TABLEPRE."user limit ".$start.",$end";
		    $query = $wmdb->query($sql);
		    while($user = $wmdb->fetch_array($query)){
			if(!strcasecmp($user['user_name'],$this->base->user['username'])) continue;
			$groupid  = $this->get_groupid($wmdb, $user['user_id']);
			$hdsql .= "(".$user['user_id'].", '".$user['user_name']."', '".$user['user_email']."', '".($user['user_editcount']*$this->base->setting['credit_edit'])."', '".$this->get_time($user['user_registration'])."', '".$this->get_time($user['user_touched'])."', '".$groupid."', '".$user['user_real_name']."', '".$user['user_editcount']."'),";
		    }
		    break;
		case 4:
		    $hdsql = "REPLACE INTO ".DB_TABLEPRE."categorylink (did, cid) VALUES ";
		    $sql = "SELECT cl_from,cl_to  FROM ".WDB_TABLEPRE."categorylinks limit ".$start.",$end";
		    $query = $wmdb->query($sql);
		    while($clink = $wmdb->fetch_array($query)){
			$cid  = $this->get_cid($wmdb, $clink['cl_to']);
			if($cid){
			    $sql = "SELECT count(*) sum FROM ".DB_TABLEPRE."categorylink where did = ".$clink['cl_from']." AND cid = ".$cid ;
			    $sum = $this->db->result_first($sql);
			    if($sum == 0){
				$hdsql .= "(".$clink['cl_from'].",".$cid."),";
			    }
			}
		    }
		    break;
	    }
	    return substr($hdsql, -1)==','? substr($hdsql,0, -1) : '';
	}
   	
}


?>