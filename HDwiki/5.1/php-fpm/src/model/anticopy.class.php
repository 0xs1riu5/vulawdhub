<?php !defined('IN_HDWIKI') && exit('Access Denied');

class anticopymodel {
	var $base;
	var $db;

	function anticopymodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function add_randomstr(&$text){
		if(empty($this->base->setting['random_text'])) {
			return false;
		}
		$random_text = explode('<br />', nl2br($this->base->setting['random_text']));
		$totalitem = count($random_text);
		if(0 == $totalitem) {
			return false;
		}
		
		$maxpos = 256;
		$fontColor = "#FFFFFF";
		$st=util::random(6,2);
		$rndstyleValue = ".{$st} { display:none; }";
		$rndstyleName = $st;
		$reString = "<style> $rndstyleValue </style>\r\n";

		$rndem[1] = 'font';
		$rndem[2] = 'div';
		$rndem[3] = 'span';
		$rndem[4] = 'p';

		$bodylen = strlen($text) - 1;
		$prepos = 0;
		for($i=0;$i<=$bodylen;$i++)
		{
			if($i+2 >= $bodylen || $i<50){
				$reString .= $text[$i];
			}else{
				$ntag = @strtolower($text[$i].$text[$i+1].$text[$i+2]);
				if($ntag=='</p' || ($ntag=='<br' && $i-$prepos>$maxpos) ){
					$dd = mt_rand(1,4);
					$emname = $rndem[$dd];
					$dd = mt_rand(0,$totalitem-1);
					$rnstr = $random_text[$dd];
					if($emname!='font'){
						$rnstr = " <$emname class='$rndstyleName'>$rnstr</$emname> ";
					}else{
						$rnstr = " <font color='$fontColor'>$rnstr</font> ";
					}
					$reString .= $rnstr.$text[$i];
					$prepos = $i;
				}else{
					$reString .= $text[$i];
				}
			}
		}
		$text = $reString;
		return true;
	}
	
	function check_useragent() {
		$ua = isset($_SERVER['HTTP_USER_AGENT']) ?  strtolower($_SERVER['HTTP_USER_AGENT']) : '';
		$in_whitelist = $in_blacklist = false;
		
		$whitelist = empty($this->base->setting['ua_whitelist']) ? array() : explode('<br />', nl2br($this->base->setting['ua_whitelist']));
		foreach($whitelist as $keyword) {
			$keyword = trim(strtolower($keyword));
			if($ua == $keyword || false !== @strpos($ua, $keyword)) {
				$in_whitelist = true;
				break;
			}
		}
		
		$blacklist = empty($this->base->setting['ua_blacklist']) ? array() : explode('<br />', nl2br($this->base->setting['ua_blacklist']));
		foreach($blacklist as $keyword) {
			$keyword = trim(strtolower($keyword));
			if($ua == $keyword || false !== @strpos($ua, $keyword)) {
				$in_blacklist = true;
				break;
			}
		}
		
		if($in_whitelist && $in_blacklist) { //同时存在于黑白名单
			return '1' == $this->base->setting['allow_ua_both'];
		} elseif($in_whitelist) { //只存在于白名单
			return true;
		} elseif($in_blacklist) { //只存在于黑名单
			return false;
		} else { //黑白名单中均不存在
			return '1' == $this->base->setting['ua_allow_first'];
		}
	}
	
	function check_visitrate() {
		if($this->_check_ip_exception()) {// 如果IP存在于IP例外数组，则不判断，直接返回
			return true;
		} else { // 否则判断访问频率
			if(isset($this->base->setting['visitrate'])) {
				$vr_setting = unserialize($this->base->setting['visitrate']);
			} else {
				return true;
			}
			$ua = isset($_SERVER['HTTP_USER_AGENT']) ? string::haddslashes(substr($_SERVER['HTTP_USER_AGENT'], 0, 255)) : '';		
			$time_start = $this->base->time - $vr_setting['duration'];
			$this->db->query("DELETE FROM ".DB_TABLEPRE."visitlist WHERE `time` <= {$time_start}");
			$count = $this->db->fetch_total("visitlist", "`time` > {$time_start} AND `ip` = '{$this->base->ip}' AND `useragent` = '{$ua}'");
			if($count > $vr_setting['pages']) {
				$this->base->load('banned');
				$userip=explode(".",$this->base->ip);
				$ban_expiration = $vr_setting['ban_time'] > 0 ? 3600*$vr_setting['ban_time'] : 3600;
				$this->db->query("INSERT INTO `".DB_TABLEPRE."banned` (`ip1`,`ip2`,`ip3`,`ip4`,`admin`,`time`,`expiration`) VALUES 
					('{$userip[0]}', '{$userip[1]}', '{$userip[2]}', '{$userip[3]}', 'SYSTEM', '{$this->base->time}', '{$ban_expiration}')");
				$_ENV['banned']->updatebannedip();
				return false;
			} else {
				$this->db->query("INSERT INTO ".DB_TABLEPRE."visitlist (`ip`,`useragent`,`time`) values ('{$this->base->ip}', '{$ua}', '{$this->base->time}')");
				return true;
			}
		}
	}
	
	function _check_ip_exception() {
		$ip_exceptions = empty($this->base->setting['visitrate_ip_exception']) ? array() : explode('<br />', nl2br($this->base->setting['visitrate_ip_exception']));
		!is_array($ip_exceptions) && $ip_exceptions = array();
		$userip=explode(".",$this->base->ip);
		foreach($ip_exceptions as $ip){
			$ip = explode('.', trim($ip));
			if( ($ip[0]=='*'||$ip[0]==$userip[0])
				&&($ip[1]=='*'||$ip[1]==$userip[1])
				&&($ip[2]=='*'||$ip[2]==$userip[2])
				&&($ip[3]=='*'||$ip[3]==$userip[3])
			){
				return true;
			}
		}
		return false;
	}
}

































