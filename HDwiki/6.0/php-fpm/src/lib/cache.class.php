<?php

class cache{
	
	var $db;
	var $cachefile;


	function cache(& $db){
		$this->db=$db;
	}

	function getfile($cachename){
		$this->cachefile=HDWIKI_ROOT.'/data/cache/'.$cachename.'.php';
	}

	function isvalid($cachename,$cachetime){
		$this->getfile($cachename);
		if(!is_readable($this->cachefile)||$cachetime<0){
			return false;
		}
		if(0==$cachetime){
			return true;
		}
		clearstatcache();
		return (time()-filemtime($this->cachefile))<$cachetime;
	}

	function getcache($cachename,$cachetime=0){
		if($this->isvalid($cachename,$cachetime)){
			return include $this->cachefile;
		}
		return false;
	}

	function writecache($cachename, $arraydata){
		$this->getfile($cachename);
		$data = is_array($arraydata)?var_export($arraydata, true):"'".$arraydata."'";
		$strdata = "<?php\nreturn ".$data.";\n?>";
		$bytes = file::writetofile($this->cachefile, $strdata);
		return $bytes;
	}

	function removecache($cachename){
		$this->getfile($cachename);
		if(file_exists($this->cachefile)){
			unlink($this->cachefile);
		}
	}

	/*读取缓存，如果缓存不存在则自动创建并返回*/
	function load($cachename,$available=0){
		$arraydata=$this->getcache($cachename);
		if(!$arraydata){
			$arraydata=array();
			$sql="SELECT * FROM ".DB_TABLEPRE.$cachename;
			if($available){
				$sql=$sql."  WHERE  available=1";
			}
			if('channel'==$cachename){
				$sql=$sql."  ORDER BY displayorder ASC";
			}
			$query=$this->db->query($sql);
			if (!$query){
				return false;
			}
			while($data=$this->db->fetch_array($query)){
				if($available){
					$arraydata[]=$data;
				}else{
					if( isset($data['variable']) ){
						$arraydata[$data['variable']]=$data['value'];
					}else{
						$arraydata[$data['find']]=$data['replacement'];
					}
				}
			}
			if('setting' == $cachename) {  //兼容以前版本的插件
				$arraydata['site_url'] = WIKI_URL;
			}
			$this->writecache($cachename,$arraydata);
		}
		return $arraydata;
	}
	
}

?>