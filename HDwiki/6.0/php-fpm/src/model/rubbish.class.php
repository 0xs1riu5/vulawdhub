<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class rubbishmodel {

	var $db;
	var $base;

	function rubbishmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function get_dir_list($dir){
		$filelist = array();
		$filedir=HDWIKI_ROOT.'/'.$dir;
		file::forcemkdir($filedir);
		$handle=opendir($filedir);
		while($filename=readdir($handle)){
			if (is_dir($filedir.$filename) && '.'!=$filename && '..'!=$filename && 'gift'!=$filename && 'userface'!=$filename && '.svn'!=$filename && 'hdpic'!=$filename){
					
					$filestr = mb_substr($filename,0,6);
				
					if(intval($filestr)>200507){
						$filelist['hdwiki'][] = $dir.$filestr;
					}else{	
						if($dir=='data/attachment/'){
							$filelist['userd'][] = $dir.$filename;
						}else{
							$filelist['user'][] = $dir.$filename;
						}
					}

			}
		}
		closedir($handle);
		return $filelist;
	}
	
	function get_file_list($dir,$attimg){
		$imgname['size'] = 0;
		foreach($dir as $filedirs){
			$filedir=HDWIKI_ROOT.'/'.$filedirs;
			file::forcemkdir($filedir);
			$handle=opendir($filedir);
			$i = 0;
			while($filename=readdir($handle)){
				if (!is_dir($filedir.'/'.$filename) && '.'!=$filename && '..'!=$filename && '.svn'!=$filename && 'index.htm'!=$filename){
					$fstr = explode(".",$filename); 
					$flast = substr(strrchr($fstr[0],'_'), 1);
					if(!in_array($filedirs.'/'.$filename,$attimg)){	//if !in_array($filedirs.'/'.$filename,$attimg) && $flast!='140' && $flast!="s"
						$i++;
						$imgname[] = $filedirs.'/'.$filename;
						$imgname['size']+=filesize($filedir.'/'.$filename);
					}
				}
			}
			$imgname['num'] = $i;
			closedir($handle);
		}
		return $imgname;
	}
	

}
?>
