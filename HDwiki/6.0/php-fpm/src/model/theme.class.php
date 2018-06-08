<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class thememodel {

	var $db;
	var $base;

	function thememodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function get_all_list($start=0,$limit=10){
		$stylelist=array();
		$query=$this->db->query('SELECT path FROM '.DB_TABLEPRE.'theme where path!="'.$this->base->setting['theme_name'].'" ORDER BY  id ASC limit '.$start.','.$limit);
		while($allstyle=$this->db->fetch_array($query)){
			if($allstyle['path']!=$this->base->setting['theme_name']){
				$style=$this->read_xml($allstyle['path']);
				$style['tag']=explode(' ',$style['tag']);
				$style['charset']=explode(' ',$style['charset']);
				$stylelist[]=$style;
			}
		}
		return $stylelist;
	}
	
	function get_all_list_num(){
		$stylelist=array();
		$query=$this->db->query('SELECT path FROM '.DB_TABLEPRE.'theme where path!="'.$this->base->setting['theme_name'].'" ORDER BY  id ASC ');
		while($allstyle=$this->db->fetch_array($query)){
			if($allstyle['path']!=$this->base->setting['theme_name']){
				$stylelist[]=$style;
			}
		}
		return $stylelist;
	}	
	function get_path_list(){
		$pathlist=array();
		$query=$this->db->query('SELECT path FROM '.DB_TABLEPRE.'theme  ORDER BY  id ASC ');
		while($style=$this->db->fetch_array($query)){
			$pathlist[]=$style['path'];
		}
		return $pathlist;
	}
	/**
	 * 得到已安装主题列表,get_path_list方法里只得到path,此方法可以得到name和path
	 */
	function get_theme_list(){
		$themelist=array();
		$query=$this->db->query('SELECT name,path FROM '.DB_TABLEPRE.'theme  ORDER BY  id ASC ');
		while($style=$this->db->fetch_array($query)){
			$themelist[]=$style;
		}
		return $themelist;
	}
	/*
	 * 得到待添加的模板
	 */
	function get_wait_add(){
		$waitadds = array();
		//所有本地模板
		$toaddlist=$this->get_style_list(1);
		//已添加的模板
		$pathlist=$this->get_path_list();
		$addlist=array_diff($toaddlist,$pathlist);
		if($addlist){
			foreach($addlist as $add){
				$detail = $this->read_xml($add);
				$waitadd['ename'] = $add;
				$waitadd['zname'] = $detail['name'];
				$waitadds[] = $waitadd;
			}
		}
		return $waitadds;
	}
	
	function add_style($style){
		$this->db->query("INSERT INTO ".DB_TABLEPRE."theme (name,path,available,css,copyright) VALUES ('$style[name]','$style[path]','1','','$style[copyright]')");
		if(!empty($style['sql'])){
		    $this->db->query(str_replace('wiki_block', DB_TABLEPRE.'block', $style['sql']));
		}
	}
	
	function read_xml($filenames){
		$xmlarray=array();
		$values=array();
		$tags=array();
		$filedir=HDWIKI_ROOT.'/style/'.$filenames.'/';
		if(file_exists($filedir."desc.xml")){
			$data = implode("",file($filedir."desc.xml"));
			$parser = xml_parser_create();
			xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
			xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
			xml_parse_into_struct($parser, $data, $values, $tags);
			xml_parser_free($parser);
			$xmlcounts=count($values);
			$xmlarray['path']=$filenames;
			for($x=0;$x<$xmlcounts;$x++){
				if($values[$x]['level']==2){
					$values[$x]['value']=isset($values[$x]['value']) ? string::hiconv($values[$x]['value']) : '';
					$xmlarray[$values[$x]['tag']]=$values[$x]['value'];
				}
			}
			$filedir=HDWIKI_ROOT."/style/".$filenames.'/';
			file::forcemkdir($filedir);
			if(is_file($filedir."screenshot.jpg")){
				$xmlarray['img']=1;
			}
		}
		return $xmlarray;
	}
	
	function write_xml($style){
		$xml="<?xml version=\"1.0\" encoding=\"".WIKI_CHARSET."\"?>\n".
			"<theme name=\"default\" version=\"1.0.1\" active=\"true\">\n".
			"<author><![CDATA[".$style['author']."]]></author>\n".
			"<authorurl><![CDATA[".$style['authorurl']."]]></authorurl>\n".
			"<name><![CDATA[".$style['name']."]]></name>\n".
			"<tag><![CDATA[".$style['tag']."]]></tag>\n".
			"<desc><![CDATA[".$style['desc']."]]></desc>\n".
			"<sitename><![CDATA[".$style['sitename']."]]></sitename>\n".
			"<weburl><![CDATA[".$style['weburl']."]]></weburl>\n".
			"<version><![CDATA[".$style['version']."]]></version>\n".
			"<hdversion><![CDATA[".$style['hdversion']."]]></hdversion>\n".
			"<copyright><![CDATA[".$style['copyright']."]]></copyright>\n".
			"<sql><![CDATA[".$style['sql']."]]></sql>\n".
			"<charset><![CDATA[".$style['charset']."]]></charset>\n".
			"</theme>";
		$filedir=HDWIKI_ROOT.'/style/'.$style['path'].'/';
		file::forcemkdir($filedir);
		$bytes=file::writetofile($filedir.'desc.xml',$xml);
		return ($bytes>0);
	}
	
	function add_check_style($path){
		$style=$this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."theme WHERE path = '$path'");
		return $style;
	}
	
	function update_style($style){
		$this->db->query("UPDATE ".DB_TABLEPRE."theme set name='$style[name]',copyright='$style[copyright]',available='1' where path='$style[path]'");
	}
	
	function update_stylecss($style,$path){
		$this->db->query("UPDATE ".DB_TABLEPRE."theme set css='$style' where path='$path'");
	}
	
	function default_style($path){
		$this->db->query("UPDATE ".DB_TABLEPRE."setting SET value = '$path' WHERE variable = 'theme_name' or variable = 'tpl_name'");
	}
	
	function remove_style($path){
		$this->db->query("DELETE FROM ".DB_TABLEPRE."theme WHERE path='$path'");
		$this->db->query("DELETE FROM ".DB_TABLEPRE."block WHERE theme='$path'");
	}
	
	function choose_theme_name($path){
		$style=$this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."theme WHERE path='$path'");
		$xmlstyle=$this->read_xml($style['path']);
		$xmlstyle['tag']=isset($xmlstyle['tag']) ? explode(" ",$xmlstyle['tag']) : null;
		$xmlstyle['charset']=isset($xmlstyle['charset']) ? explode(" ",$xmlstyle['charset']) : null;
		return $xmlstyle;
	} 
	
	function get_style_list($type){
		$toaddlist=array();
		$filedir=HDWIKI_ROOT.'/style/';
		file::forcemkdir($filedir);
		$handle=opendir($filedir);
		while($filename=readdir($handle)){
			if (is_dir($filedir.$filename) && '.'!=$filename && '..'!=$filename){
				if($type==1){
					if(file_exists($filedir.$filename."/desc.xml")){
						$toaddlist[]=$filename;
					}
				}else{
					$toaddlist[]=$filename;
				}
			}
		}
		closedir($handle);
		return $toaddlist;
	}
	
	function upload_img($uploadimg,$filename){
		$counts=count($uploadimg);
		if($counts!=0){
			for($i=0;$i<$counts;$i++){
				$imgname=$uploadimg[$i]['name'];
				$extname=file::extname($imgname);
				$destfile=HDWIKI_ROOT.'/style/'.$filename.'/'.$uploadimg[$i]['rename'].".".$extname;
				$result = file::uploadfile($uploadimg[$i],$destfile);
				if($result['result'] && $uploadimg[$i]['rename']=='screenshot'){
					util::image_compress($destfile,NULL,158,118);
				}
				$success++;
			}
		}
		return $success;
	}
	
	function get_theme_name($ziplist){
		if(!is_array($ziplist)){
			return false;
		}
		foreach($ziplist as $list){
			if(false!==strpos($list['filename'],'desc.xml')){
				$theme_name=$list['filename'];
				break;
			}
		}
		$theme_name=substr($theme_name,strpos($theme_name,'style/')+6,-9);
		return $theme_name;
	}
	
	function style_charset($path){
		$filedir=HDWIKI_ROOT.'/view/'.$path.'/';
		if(is_dir($filedir)){
			$list=array();
			file::forcemkdir($filedir);
			$handle=opendir($filedir);
			while($filename=readdir($handle)){
				if (!is_dir($filedir.$filename) && '.'!=$filename && '..'!=$filename){
					$list[]=$filename;
				}
			}
			if($list==null)$styletag=1;
		}else{
			$styletag=1;
		}
		if($styletag==1){
			if(WIKI_CHARSET=='UTF-8'){
				$charset=WIKI_CHARSET.' GBK';
			}else{
				$charset=WIKI_CHARSET.' UTF-8';
			}	
		}else{
			$charset=WIKI_CHARSET;
		}
		return $charset;
	}
	
	function write_css($style){
			$data="#html{background:".$style['bg_color']." url(".$style['bg_imgname'].") repeat-x left top;}\n".
				"#html body{width:950px;}\n".
				"#html .bor_b-ccc,#html .col-h2{}\n".
				"#html .bor-ccc,#html .columns,#html .bor-c_dl dl{border:1px ".$style['nav_framcolor']." solid;}\n".
				"#html .inp_txt{border:1px ".$style['input_bgcolor']." solid;color:".$style['input_color'].";}\n".
				"html a{color:".$style['link_color'].";}\n".
				"html a:hover{color:".$style['link_hovercolor'].";}\n".
				"#html .link_orange a{color:".$style['link_difcolor']."; text-decoration:none;}\n".
				"#html .link_orange a:hover{color:".$style['link_difcolor'].";text-decoration:underline;}\n".
				"#html .col-h2{height:21px;line-height:21px;background:".$style['titlebg_color']." url(".$style['titbg_imgname'].") repeat-x left top;}";
		$filedir=HDWIKI_ROOT.'/style/'.$style['path'].'/';
		file::forcemkdir($filedir);
		$bytes=file::writetofile($filedir.'mix_color.css',$data);
		return ($bytes>0);
	}
	
	
	//根据一个文件夹路径，得到此下面的所有block，以数组形式返回。二维数组，状如：{hottag{……},doc{……},……}
	function get_blocks($dir){
		$blockdata=array();
		if(is_dir($dir)){
			$theme_dir = str_replace(array('\\','/'), '', substr($dir, strpos($dir, '/block/')+7));//其实加这个东西，基本上没意义，权且加上吧。
			$d=dir($dir);
			while (false !== ($child = $d->read())){
				if ($child != '.' && $child != '..') {
					$blockfile=$dir.'/'.$child.'/'.'block.php';
					if(is_file($blockfile)){
						include $blockfile;
						$block['theme_dir']=$theme_dir;//其实加这个东西，基本上没意义，权且加上吧。
						$blockdata[$child]=$block;
					}
				}
			}
		}
		return $blockdata;
	}

	//根据一个$theme主题得到下面的所有blocks
	function get_blocks_by_theme($theme){
		$blocks=array();
		$sql = "SELECT * FROM ".DB_TABLEPRE."block WHERE theme = '$theme'";
		$query=$this->db->query($sql);
		while($block=$this->db->fetch_array($query)){
			$blocks[]=$block;
		}
		return $blocks;
	}

	//根据bid得到已经保存的block,fun,params。先从临时文件中寻找，如果没有找到，就从数据库中寻找。
	function get_setting($bid,$theme=''){
		$data=array();
		$theme=!empty($theme)?$theme:$this->base->hgetcookie('theme');
		$filename=HDWIKI_ROOT."/data/tmp/".$theme.'_temp.php';
		if(file_exists($filename)){
			include_once $filename;
			$data=isset($temp[$bid])?$temp[$bid]:$data;
		}
		if( !$data && is_numeric($bid)){
			$sql="SELECT block,fun,params FROM ".DB_TABLEPRE."block WHERE  id='$bid'";
			$data=$this->db->fetch_first($sql);
			if ($data['params']){
				$data['params']=unserialize($data['params']);
			}
		}
		return $data;
	}

	function relation_url(){
	    	return array('categorylist' => 'index.php?category-view',
			    'category' => 'index.php?category-view',
			    'viewcomment' => 'index.php?comment-view',
			    'viewdoc' => 'index.php?doc-view',
			    'createdoc' => 'index.php?doc-create',
			    'editor' => 'index.php?doc-edit',
			    'hdmomo' => 'index.php?doc-summary',
			    'managesave' => 'index.php?doc-managesave',
			    'cooperate' => 'index.php?doc-cooperate',
			    'editionlist' => 'index.php?edition-list',
			    'viewedition' => 'index.php?edition-view',
			    'compare' => 'index.php?edition-list',
			    'giftlist' => 'index.php?gift-default',
			    'index' => 'index.php?index-default',
			    'list' => 'index.php?list-focus',
			    'piclist' => 'index.php?pic-piclist',
			    'viewpic' => 'index.php?pic-view',
			    'searchpic' => 'index.php?pic-search',
			    'sendmessage' => 'index.php?pms-sendmessage',
			    'blacklist' => 'index.php?pms-blacklist',
			    'box' => 'index.php?pms-box',
			    'search' => 'index.php?search-fulltext',
			    'register' => 'index.php?user-register',
			    'login' => 'index.php?user-login',
			    'profile' => 'index.php?user-profile',
			    'editprofile' => 'index.php?user-editprofile',
			    'editpass' => 'index.php?user-editpass',
			    'editimage' => 'index.php?user-editimage',
			    'resetpass' => 'index.php?user-getpass',
			    'space' => 'index.php?user-space');
	}

	function visual_url($dir,$theme) {
		$writecache = false;
	    $url=array();
	    if(is_dir($dir)){
		    $d = dir($dir);
		    $cachename='visualurl_'.$theme;
		    $cache_file=HDWIKI_ROOT.'/data/cache/'.$cachename.'.php';
		    if(file_exists($cache_file)){
			$url=$this->base->cache->getcache($cachename);
			$c_time=filemtime($cache_file);
		    }
		    while (false !== ($entry = $d->read())) {
			    $scan=FALSE;
			    if(strtolower(substr($entry,0,6))!=='admin_' && strtolower(substr($entry,strrpos($entry,'.')+1))=='htm'){
			    $file=$dir.$entry;
			    if(isset($c_time)){
				 if($c_time < filemtime($file)){
					    $scan=TRUE;
				 }
			    }else{
				    $scan=TRUE;
			    }
			    if($scan){
				    $content = file::readfromfile($file);
				    $entry=substr($entry,0,strrpos($entry,'.'));
				    if(1 == preg_match("/\{block:([^\}]+?)\/\}/i", $content)){
					    $url[$entry] = TRUE;
				    }else{
					    if(isset($url[$entry])){
						    unset($url[$entry]);
					    }
				    }
				    $writecache=true;
			    }
			    }
		    }
		    $d->close();
	    }
	    if($writecache){
	    	$cache_file=$this->base->cache->writecache($cachename,$url);
	    }
	    return $url;
	}

	function get_url($file){
		$url=$this->relation_url();
		$return_url=$url[$file];
		switch($file){
			case 'category':
				$query=$this->db->fetch_first("SELECT cid FROM ".DB_TABLEPRE."category limit 1");
				$return_url.='-'.$query['cid'];
				break;
			case 'compare':
				$query=$this->db->fetch_first("SELECT did FROM ".DB_TABLEPRE."edition limit 1");
				$return_url.='-'.$query['did'];
				break;
			case 'editor':
				$query=$this->db->fetch_first("SELECT did FROM ".DB_TABLEPRE."doc limit 1");
				$return_url.='-'.$query['did'];
				break;
			case 'viewpic':
				$query=$this->db->fetch_first("SELECT id,did FROM ".DB_TABLEPRE."attachment where isimage=1  limit 1");
				$return_url.='-'.$query['id'].'-'.$query['did'];
				break;
			case 'viewedition':
				$query=$this->db->fetch_first("SELECT eid FROM ".DB_TABLEPRE."edition limit 1");
				$return_url.='-'.$query['eid'].'-1';
				break;
			case 'viewdoc':
				$query=$this->db->fetch_first("SELECT did FROM ".DB_TABLEPRE."doc limit 1");
				$return_url.='-'.$query['did'];
				break;
			case 'viewcomment':
				$query=$this->db->fetch_first("SELECT did FROM ".DB_TABLEPRE."comment limit 1");
				$return_url.='-'.$query['did'];
				break;
			case 'space':
				$query=$this->db->fetch_first("SELECT uid FROM ".DB_TABLEPRE."user limit 1");
				$return_url.='-'.$query['uid'];
				break;
		}
		return $return_url;
	}
	
	function block_query($post,$temp=''){
		$temlist = array();
		list($theme, $file) = explode('-', array_shift($post));//得到 theme 和 file 值。
		//删除操作
		if(is_array($temp['del'])){
			$delid=implode(',',$temp['del']);
			$this->db->query("DELETE FROM ".DB_TABLEPRE."block WHERE id IN ($delid)");
		}
		//添加和更新 block数据的操作。
		$insertsql="INSERT INTO ".DB_TABLEPRE."block (theme,file,area,areaorder,block,fun,tpl,params) VALUES ";
		$areas='';//用来保存页面区域的数组。供下面的删除页面没有区域使用。
		foreach($post as $key=>$value){
			$areas.="'".$key."',";
			$value=explode('-',$value);
			$num=count($value);
			for($i=0;$i<$num;$i++){
				$id=$value[$i];
				if(is_numeric($id)){//id是数字，表明是需要更新的。
					$updatesql="UPDATE ".DB_TABLEPRE."block SET area='$key',areaorder=$i";
					if(isset($temp[$id]['params'])){
						$updatesql .= ",params='".serialize($temp[$id]['params'])."'";
						$temlist[] = $id;
					}
					$updatesql .= " WHERE id = $id";
					$this->db->query($updatesql);
				}else{//id非数字，表明是新加入的模块。
					$temlist[] = $id;
					if(is_array($temp[$id])){
						$block=$temp[$id];
						$params=$block['params']?serialize($block['params']):'';
						$insertsql.="('".$block['theme']."','".$block['file']."','$key',$i,'".$block['block']."','".$block['fun']."','".$block['fun'].".htm','$params'),";
					}
				}
			}
		}
		if(isset($block)){
			$insertsql=substr($insertsql,0,-1);
			$this->db->query($insertsql);
		}
		foreach ($temlist as $id){
		    $block=$temp[$id];
		    $fromfile = HDWIKI_ROOT."/data/tmp/".$theme.".{$block['block']}.{$block['fun']}.htm";
		    if(file_exists($fromfile)){
			$tofile = HDWIKI_ROOT.'/block/'.$theme.'/'.$block['block'].'/'.$block['fun'].'.htm';
			$tplcontent = file::readfromfile($fromfile);
			file::writetofile($tofile,$tplcontent);
			unlink($fromfile);
		    }
		}

		//删除页面中没有区域的元素。(比如页面只有一个right区域，但是数据库中却多了一个left区域，显然是需要删掉的。
		//如果模板被修改的话，可能出现这种情况。)
		$areas = substr($areas, 0, -1);
		$delsql='DELETE FROM '.DB_TABLEPRE."block WHERE theme = '$theme' and file = '$file' and area NOT IN ($areas)";
		$this->db->query($delsql);
		return 'ok';
	}
}
?>
