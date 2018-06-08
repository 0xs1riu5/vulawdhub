<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class logmodel {

	var $db;
	var $base;

	function logmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function readlog($line,$num){
		$logpath=HDWIKI_ROOT.'/data/logs/';
		$lastmonth =date('Ym',mktime(0, 0, 0, date('m'),0,date('Y')));
		$currentlogs=file($logpath.date('Ym').'_adminaccess.php');
		@$lastlogs=file($logpath.$lastmonth.'_adminaccess.php');
		if(false!=$lastlogs){
			$logs = array_merge($lastlogs, $currentlogs);
			$formatdate=$lastmonth.'--'.date('Ym');
		}else{
			$logs=$currentlogs;
			$formatdate=date('Ym');
		}
		$logs=array_reverse($logs);
		for($i=0;$i<$num;$i++){
			if($logs[$line]!=''){
				$loglist[$i]=explode("\t",$logs[$line]);
				$line++;
			}else{
				$i=$num;
			}
		}
		$content=array($loglist,count($logs),$formatdate);
		return $content;
	}

}
?>