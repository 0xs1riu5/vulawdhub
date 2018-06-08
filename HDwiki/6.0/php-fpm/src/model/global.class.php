<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class globalmodel {

	var $db;
	var $base;

	function globalmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	 
	/**
	* 构造 UC 头像地址
	*/
	function uc_api_avatar($image, $uid, $size='middle'){
		return defined('UC_API') ? UC_API.'/avatar.php?uid='.$uid.'&size='.$size : $image;
	}

	function checkbanned() {
		$ips=$this->base->cache->getcache('bannedip');
		$ips=(bool)$ips?$ips:array();
		$userip=explode(".",$this->base->ip);
		foreach($ips as $ip){
			$bannedtime=$ip['expiration']+$ip['time']-$this->base->time;
			if( $bannedtime>0
				&&($ip['expiration']&&$ip['ip1']=='*'||$ip['ip1']==$userip[0])
				&&($ip['ip2']=='*'||$ip['ip2']==$userip[1])
				&&($ip['ip3']=='*'||$ip['ip3']==$userip[2])
				&&($ip['ip4']=='*'||$ip['ip4']==$userip[3])
			){
				$this->base->message($this->base->view->lang['bannedIP'],'',0);
			}
		}
	}

	/**
	 * 短消息
	 *
	 * @param int $uid 用户ID
	 * @return array (0-新消息数,1-总消息数,2-私有消息数,3-公有消息数)
	 */
	function newpms($uid){
		$newpms=$totalpms=$pripms=$pubpms=0;
		$blacklist=$this->db->fetch_first('select blacklist from '.DB_TABLEPRE.'blacklist where uid='.$uid);
		if('[ALL]'!=$blacklist['blacklist']){
			$blackuser=str_replace(",","','",$blacklist['blacklist']);
			$query=$this->db->query('select id,new,delstatus,og from '.DB_TABLEPRE.'pms where toid='.$uid." and delstatus!=2 and  drafts!=1 and `from` not in ('$blackuser')");
			while($pms=$this->db->fetch_array($query)){
				if('1'==$pms['new']){
					$newpms++;
					if('0'==$pms['og']){
						$pripms++;
					}else{
						$pubpms++;
					}
				}
				$totalpms++;
			}
		}
		$this->base->view->assign('newpms',array($newpms,$totalpms,$pripms,$pubpms));
		return array($newpms,$totalpms,$pripms,$pubpms);
	}
	
	function adv_filter($advertisement){
		$advlist=array();
		if (!is_array($advertisement)){return $advlist;}
		$inarray=array('index-default','doc-view','category-default','category-view');
		$url=$this->base->get[0].'-'.$this->base->get[1];
		if(in_array($url,$inarray)||'admin_'==substr($this->base->get[0],0,6)){
			if($url==$inarray[0])
				$advlist=$this->adv_index_filter($advertisement);
			elseif($url==$inarray[2]||$url==$inarray[3])
				$advlist=$this->adv_category_filter($advertisement);
		}else{
			foreach($advertisement as $adv){
				$adv['targets']=explode(';',$adv['targets']);
				if('0'==$adv['type']||'1'==$adv['type']){
					if(in_array('all',$adv['targets'])||('user'==$this->base->get[0]&&'register'==$this->base->get[1]&&in_array('register',$adv['targets'])))
						if(($adv['starttime']=='0'||$adv['starttime']<=$this->base->time)&&($adv['endtime']=='0'||$adv['endtime']>=$this->base->time))
							$advlist[$adv['type']][]=$adv;
				}
			}
		}
		if(!empty($advlist)){
			$advlist=$this->rand_array($advlist);
			$this->base->view->assign('advlist',$advlist);
		}
	}
	
function adv_index_filter($advertisement){
		$advlist=array();
		foreach($advertisement as $adv){
			if(($adv['starttime']=='0'||$adv['starttime']<=$this->base->time)&&($adv['endtime']=='0'||$adv['endtime']>=$this->base->time)){
				$adv['targets']=explode(';',$adv['targets']);
				if(in_array($adv['type'],array('0','1','2'))){
					if($adv['type']=='2'||in_array('all',$adv['targets'])||in_array('index',$adv['targets'])){
						$advlist[$adv['type']][]=$adv;
					}
					continue;
				}
				if('5'==$adv['type']||'6'==$adv['type']){
					$advlist[$adv['type']][]=$adv;
				}
			}
		}
		return $advlist;
	}
	
	function adv_category_filter($advertisement){
		$advlist=array();
		$this->base->get[2] = isset($this->base->get[2]) ? $this->base->get[2] : NULL ;
		$categorys=array('all',$this->base->get[2]);
		if(!empty($advertisement)) {
			foreach($advertisement as $adv){
				if(in_array($adv['type'],array('0','1','7'))&&($adv['starttime']=='0'||$adv['starttime']<=$this->base->time)&&($adv['endtime']=='0'||$adv['endtime']>=$this->base->time)){
					$adv['targets']=explode(';',$adv['targets']);
					if($adv['type']=='7'||array_intersect($adv['targets'], $categorys)){
						$advlist[$adv['type']][]=$adv;
					}
				}
			}
		}
		return $advlist;
	}
	
	function rand_array($advlist){
		foreach($advlist as $key=>$adv){
			if(in_array($key,array('3','4'))){
				foreach($adv as $position=>$value){
					$advlist[$key][$position]=$value[array_rand($value)];
				}
			}else{
				$advlist[$key]=$adv[array_rand($adv)];
			}
		}
		return $advlist;
	}
	
	function writelog($regular,$pluginid){
		$menu=null;
		if('admin'==substr($this->base->get[0],0,5)){
			if(''==$pluginid){
				$menu=$this->db->fetch_first("select name from ".DB_TABLEPRE."regular where regular like'%".$regular."%'");
			}else{
				if(is_numeric($pluginid)){
					$menu=$this->db->fetch_first("select name from ".DB_TABLEPRE."plugin where pluginid='".$pluginid."'");
				}
			}
			$str="<?php exit;?>"."	".$this->base->user['username']."	".$this->base->user['grouptitle']."	".$this->base->ip."	".$this->base->date($this->base->time)."	".$menu['name']."	".$regular."\r\n";
			file::forcemkdir(HDWIKI_ROOT."/data/logs");
			$handle = fopen(HDWIKI_ROOT."/data/logs/".date('Ym')."_adminaccess.php","a");
			fwrite($handle,$str);
			fclose($handle);
		}
	}
	
	function block_file($theme,$filetxt){ 
		$dir=HDWIKI_ROOT.'/block/';
		$filename=$dir.$theme.$filetxt;
		if(!file_exists($filename)){
			$filename=$dir.'default'.$filetxt;
		}
		return $filename;
	}

}
?>