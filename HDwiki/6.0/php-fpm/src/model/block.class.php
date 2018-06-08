<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class blockmodel {

	var $db;
	var $base;
	var $cachetime;

	function blockmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		$this->cachetime = $this->base->setting['index_cache_time'];
	}

	function view($file){
		$theme=$GLOBALS['theme'];//得到模版的主题
		if($theme!='default' && !file_exists(HDWIKI_ROOT."/view/$theme/$file.htm")){
			$theme = 'default';
		}
		$blocklist=$this->load_block($theme,$file);//将本文件所需的block从数据库中取出来，分为两种组合形式，一种是按block名，一种是按区域名。
		$GLOBALS['blocklist']=$blocklist[1];//将按区域名排列的数组取出，赋给全局变量备用。
		$cachename='data_'.$theme.'_'.$file;
		$blockdata=$this->base->cache->getcache($cachename,$this->cachetime);
		if(!is_array($blockdata)){
			$blockdata=array();
			foreach($blocklist[0] as $key=>$blocks){
				$filename=$_ENV['global']->block_file($theme,"/$key/$key.php");
				if(is_file($filename)){
					include_once $filename;
					$obj=new $key($this->base);
					foreach($blocks as $block){
						if($block['fun'] && method_exists ($obj, $block['fun'])){
							$block['params']=$block['params']?unserialize($block['params']):'';
							$blockdata[$block['id']]=$obj->$block['fun']($block['params']);
						}
					}
				}
			}
			$this->base->cache->writecache($cachename,$blockdata);
		}
		$GLOBALS['blockdata']=$blockdata;//加载进block程序得到的数据到全局变量备用。
		$this->base->view->display($file);
	}
	
	function load_block($theme,$file){
		$cachename='block_'.$theme.'_'.$file;
		$cachedata=$this->base->cache->getcache($cachename,$this->cachetime);
		if(!is_array($cachedata)){
			$cachedata=array(array(),array());
			$sql="SELECT id,theme,fun,params,area,block,tpl FROM ".DB_TABLEPRE."block WHERE  theme='$theme' and file='$file' ORDER BY areaorder ASC";
			$query=$this->db->query($sql);
			if($query){
				while($data=$this->db->fetch_array($query)){
					//$cachedata[0][$data['block']][]=array_splice($data,0,3,$data['id']);
					$cachedata[0][$data['block']][]=$data;
					$cachedata[1][$data['area']][]=$data;
				}
				$this->base->cache->writecache($cachename,$cachedata);
			}
		}
		return $cachedata;
	}
	
}
?>
