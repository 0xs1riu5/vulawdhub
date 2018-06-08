<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{
	
	var $theme;
	var $tempfile;
	var $filename;
	
	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->view->setlang($this->setting['lang_name'],'back');
		$this->tempfile=HDWIKI_ROOT."/data/tmp/".$GLOBALS['theme'].'_temp.php';
		$this->load('theme');
	}
	
	function dodefault(){
		$addlist=$_ENV['theme']->get_wait_add();
		$defaultstyle=$_ENV['theme']->choose_theme_name($this->setting['theme_name']);
		
		$stylearray=$_ENV['theme']->get_all_list_num();
		$count=count($stylearray);
		$num = 10;
		$page = max(1, intval(end($this->get)));
		$start_limit = ($page - 1) * $num;
		$stylelist=$_ENV['theme']->get_all_list($start_limit,$num);
		$departstr=$this->multi($count, $num, $page,'admin_theme-default');
		$this->view->assign('departstr',$departstr);
		
		$this->view->assign('stylelist',$stylelist);
		$this->view->assign('toaddlist',$addlist);
		$this->view->assign('appurl',$this->setting['app_url']);
		$this->view->assign('defaultstyle',$defaultstyle);
		$this->view->display('admin_theme');
	}
	
	function doeditxml(){
		if(!isset($this->post['stylesave']) && !isset($this->post['styleshare'])){
			$xmlcon=$_ENV['theme']->read_xml($this->get[2]);
			$this->view->assign('stylename',$this->get[2]);
			$this->view->assign('style',$xmlcon);
			$this->view->assign('share',$this->get[3]);
			$this->view->display('admin_themexml_edit');
		}else{
			if($_FILES['styleimg']['name']!=''){
				$image = $_FILES['styleimg'];
				$extname=file::extname($image['name']);
				if($extname=='jpg'){
					$destfile='style/'.$this->get[2].'/screenshot.'.$extname;
					$result = file::uploadfile($image,$destfile);
					if($result['result']){
						util::image_compress($destfile,NULL,158,118);
					} else {
						$this->message($result['msg'],'BACK');
					}
				}else{
					$this->message($this->view->lang['uploadFormatWrong'],'BACK');
				}
			}
			//insert into db
			$style = $this->post['style'];
			$style['hdversion']=HDWIKI_VERSION;
			$style['path']=trim($this->get[2]);
			$style['charset']=$_ENV['theme']->style_charset($style[path]);
			$stylecon=$_ENV['theme']->add_check_style($style['path']);
			if($stylecon==null){
				$_ENV['theme']->add_style($style);
			}else{
				$_ENV['theme']->update_style($style);
			}
			//得到需要插入的blocks的sql。
			$style['sql']='';
			$blocks = $_ENV['theme']->get_blocks_by_theme($this->get[2]);
			if($blocks){
			    $insertsql="INSERT INTO wiki_block (theme,file,area,areaorder,block,fun,tpl,params) VALUES ";
			    foreach ($blocks as $val){
				$insertsql.="( '$val[theme]','$val[file]','$val[area]','$val[areaorder]','$val[block]','$val[fun]','$val[tpl]','$val[params]' ),";
			    }
			    $style['sql'] = substr($insertsql,0,-1);
			}
			//write to xml
			$_ENV['theme']->write_xml($style);
			if(isset($this->post['stylesubmit'])){
				$this->message($this->view->lang['docEditSuccess'],'index.php?admin_theme');
			}else if(isset($this->post['stylesave'])){
				$this->message($this->view->lang['docEditSuccess'],'index.php?admin_theme-editxml-'.$style[path].'-share');
			}else if(isset($this->post['styleshare'])){
				//check
				$filename='style/'.$style['path'].'/share.lock';
				if(is_file($filename)){
					$this->message($this->view->lang['style_share_lock'].$filename,'BACK');
				}else{
					file::writetofile( $filename , $a='' );
				}
				//zip
				require HDWIKI_ROOT."/lib/zip.class.php";
				$zip = new Zip;
				$filedir=array('style/'.$style['path'], 'view/'.$style['path'], 'block/'.$style['path']);
				$zipdir=array('hdwiki/style/'.$style['path'], 'hdwiki/view/'.$style['path'], 'hdwiki/block/'.$style['path']);
				file::forcemkdir(HDWIKI_ROOT.'/data/tmp/');
				$tmpname=HDWIKI_ROOT.'/data/tmp/'.util::random(6).'.zip';
				@$zip->zip_dir($filedir,$tmpname,$zipdir);
				//share
				if(is_file($tmpname)){
					$zip_content=file::readfromfile($tmpname);
					$upload_url=$this->setting['app_url']."/hdapp.php?action=upload&type=template";
					$data='data='.base64_encode($zip_content);
					unlink($tmpname);
					if('1'==@util::hfopen($upload_url,0,$data)){
						$this->message($this->view->lang['styleShareSuccess'],'index.php?admin_theme');
					}else{
						$this->message($this->view->lang['styleShareFaile'],'index.php?admin_theme');
					}
				}else{
					$this->message($this->view->lang['styleFileZipFail'],'index.php?admin_theme');
				}
			}
		}
	}

	function doadd(){
		$style['path']=trim($this->get[2]);
		$style=$_ENV['theme']->read_xml($style['path']);
		if($style['path']=='' || $style['name']=='' || $style['copyright']==''){
			$this->message($this->view->lang['stylexmlwrong'],'index.php?admin_theme');
		}
		$stylecon=$_ENV['theme']->add_check_style($path);
		if($stylecon==null){
			$_ENV['theme']->add_style($style);
			$this->cache->removecache('style');
			$this->message($this->view->lang['styleaddsuccess'],'index.php?admin_theme');
		}else{
			$this->message($this->view->lang['stylepathexist'],'index.php?admin_theme');
		}
	}
	
	function docreate(){
		if($this->setting['theme_name']){
			$defaultstyle=$_ENV['theme']->choose_theme_name($this->setting['theme_name']);
		}
		$this->view->assign('appurl',$this->setting['app_url']);
		$this->view->assign('defaultstyle',$defaultstyle);
		$this->view->display('admin_theme_create');
	}
	
	function docreatestyle(){
		$style = $this->post['style'];
		if($style['desc']!=""){
			$style['desc'] = string::stripscript($style['desc']);
		}
		$stylelist=$_ENV['theme']->get_style_list(0);
		if(in_array($style[path],$stylelist)){
			$this->message($this->view->lang['styledocpathexist'],BACK);
			exit;
		}else{
			@file::copydir(HDWIKI_ROOT.'/style/'.$this->setting['theme_name'],HDWIKI_ROOT.'/style/'.$style[path]);
			if($this->setting['theme_name']!='default'){
				@file::copydir(HDWIKI_ROOT.'/view/'.$this->setting['theme_name'],HDWIKI_ROOT.'/view/'.$style[path]);
			}
			//insert into db
			$style['hdversion'] = HDWIKI_VERSION;
			$style['charset']=$_ENV['theme']->style_charset($style[path]);
			$stylecon=$_ENV['theme']->add_check_style($style['path']);
			if($stylecon==null){
				$_ENV['theme']->add_style($style);
			}else{
				$_ENV['theme']->update_style($style);
			}
			//write to xml
			$_ENV['theme']->write_xml($style);
			$this->cache->removecache('style');
			$this->header("admin_theme-edit-$style[path]");
		}
	}

	function doedit(){
		$stylelang=array();
		$themelist = $_ENV['theme']->get_theme_list();
		//模板编辑列表
		$currtheme = $this->get[2] ? $this->get[2] : $this->setting['theme_name'] ;
		$filedir=HDWIKI_ROOT.'/view/'.$currtheme.'/';
		file::forcemkdir($filedir);
		$url=$_ENV['theme']->visual_url($filedir, $currtheme);
		//读取所有模板
		$handle=opendir($filedir);
		while($filename=readdir($handle)){
			if (!is_dir($filedir.$filename) && '.'!=$filename && '..'!=$filename && substr($filename,0,6)!="admin_" && substr($filename,-4)==".htm"){
				$tpl=substr($filename,0,-4);
				$viewlist[$tpl]=isset($url[$tpl])?1:0;
				$stylelang[$tpl]=isset($this->view->lang['style'.$tpl]) ? $this->view->lang['style'.$tpl] : null;
			}
		}
		closedir($handle);
		is_array($viewlist) && arsort($viewlist);
		//读取样式
		$filedir=HDWIKI_ROOT.'/style/'.$currtheme.'/';
		file::forcemkdir($filedir);
		$handle=opendir($filedir);
		while($filename=readdir($handle)){
			if (!is_dir($filedir.$filename) && '.'!=$filename && '..'!=$filename && substr($filename,0,6)!='admin_' && substr($filename,-4)=='.css' || substr($filename,-4)=='.xml'){
				$style_key=str_replace('.','',substr($filename,-4));
				$stylelist[substr($filename,0,-4)]=$style_key;
				$stylelang[substr($filename,0,-4)]=isset($this->view->lang['style'.substr($filename,0,-4)]) ? $this->view->lang['style'.substr($filename,0,-4)] : null;
			}
			if(is_file($filedir.'screenshot.jpg')){
				$styleimg=1;
			}
		}
		closedir($handle);
		$this->view->assign('stylelang',$stylelang);
		$this->view->assign('viewlist',$viewlist);
		$this->view->assign('stylelist',$stylelist);
		$this->view->assign('currtheme',$currtheme);
		$this->view->assign('themelist',$themelist);
		$this->view->display('admin_theme_edit');
	}
	
	function docodeedit(){
		$baklist = array();
		$themelist = $_ENV['theme']->get_path_list();
		//模板编辑列表
		$currtheme = $this->get[2] ? $this->get[2] : 'default' ;
		$currpage = $this->get[3] && $this->get[4] ? $this->get[3].'.'.$this->get[4] : 'index.htm';

		$filedir = HDWIKI_ROOT.'/data/bak/'.$currtheme.'/';
		file::forcemkdir($filedir);
		$handle=opendir($filedir);
		while($filename=readdir($handle)){
			if (!is_dir($filedir.$filename) && '.'!=$filename && '..'!=$filename && strstr($filename, $currpage.'.bak')){
				$temp['name']=$filename;
				$temp['theme']=$currtheme;
				$temp['time']=date('Y-m-d H:i:s', strtotime(substr($filename, -14)));
				$baklist[] = $temp;
			}
		}
		closedir($handle);
		$this->view->assign('baklist',$baklist);		
		$this->view->assign('currtheme',$currtheme);
		$this->view->assign('currpage',$currpage);		
		$this->view->assign('themelist',$themelist);
		$this->view->display('admin_theme_codeedit');		
	}
	
	/*
	 * 删除模板备份文件
	 */
	function dodelbak(){
		$filename = HDWIKI_ROOT.'/data/bak/'.$this->post['style_path'].'/'.$this->post['id'];
		if(file_exists($filename) && @unlink($filename)){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	function dobaseedit(){
		if(!isset($this->post['imgupload'])){
			$filedir=HDWIKI_ROOT.'/style/'.$this->get[2].'/';
			file::forcemkdir($filedir);
			$handle=opendir($filedir);
			while($filename=readdir($handle)){
				if (!is_dir($filedir.$filename) && '.'!=$filename && '..'!=$filename && substr($filename,0,6)!='admin_' && substr($filename,-4)=='.css' || substr($filename,-4)=='.xml'){
					$style_key=str_replace('.','',substr($filename,-4));
					$stylelist[substr($filename,0,-4)]=$style_key;
					$stylelang[substr($filename,0,-4)]=$this->view->lang['style'.substr($filename,0,-4)];
				}
				if(is_file($filedir.'screenshot.jpg')){
					$styleimg=1;
				}
			}
			closedir($handle);
			$stylecss=$_ENV['theme']->add_check_style($this->get[2]);
			$style=unserialize($stylecss['css']);
			$this->view->assign('style_path',$this->get[2]);
			$this->view->assign('style',$style);
			$this->view->assign('styleimg',$styleimg);
			$this->view->display('admin_theme_baseedit');
		}else{
			$imgform=array('jpg','jpeg','png','gif');
			if($_FILES['bg_file']['name']!=''){
				$background_img = explode('.',$this->post['background_img']);
				$_FILES['bg_file']['rename']=$background_img[0];
				$bg_extname=file::extname($_FILES['bg_file']['name']);
				if(!in_array($bg_extname,$imgform)){
					$this->message($this->view->lang['stylebgimgwrong'],BACK);
				}
				$uploadimg[]=$_FILES['bg_file'];
			}
			if($_FILES['title_file']['name']!=''){
				$titlebg_img = explode('.',$this->post['titlebg_img']);
				$_FILES['title_file']['rename']=$titlebg_img[0];
				$title_extname=file::extname($_FILES['title_file']['name']);
				if(!in_array($title_extname,$imgform)){
					$this->message($this->view->lang['styledetitimgwrong'],BACK);
				}
				$uploadimg[]=$_FILES['title_file'];
			}
			if($_FILES['screenshot_file']['name']!=''){
				$_FILES['screenshot_file']['rename']='screenshot';
				$screenshot_extname=file::extname($_FILES['screenshot_file']['name']);
				$imgform=array('jpg');
				if(!in_array($screenshot_extname,$imgform)){
					$this->message($this->view->lang['styledescreenwrong'],BACK);
				}
				$uploadimg[]=$_FILES['screenshot_file'];
			}
			$_ENV['theme']->upload_img($uploadimg,$this->get[2]);
			
			$stylelist = array('bg_color' => '#fff',
								'titlebg_color' => '',
							//	'title_framcolor' => '#e1e1e1',
								'nav_framcolor' => '#e1e1e1',
								'input_bgcolor' => '#7f9db9',
								'input_color' => '#666',
								'link_color' => '#0268cd',
								'link_hovercolor' => '#f30',
								'link_difcolor' => '#ff3300'
								);
			foreach($stylelist as $key => $value){
				$style[$key] = $this->post['style'][$key]?$this->post['style'][$key]:$value;
			}
			$style['bg_imgname'] = $background_img[0]?$background_img[0].'.'.$bg_extname:'';
			$style['titbg_imgname'] = $titlebg_img[0]?$titlebg_img[0].'.'.$title_extname:'col_h2_bg.gif';	
			$style['path']=$this->get[2];
			$_ENV['theme']->write_css($style);
			$stylecss=serialize($style);
			$_ENV['theme']->update_stylecss($stylecss,$style['path']);
			$this->cache->removecache('style');
			$this->message($this->view->lang['styleEditSuccess'],'index.php?admin_theme-baseedit-'.$this->get[2]);
		}
	}
	
	function doadvancededit(){
		$stylelang=array();
		$filedir=HDWIKI_ROOT.'/view/'.$this->get[2].'/';
		file::forcemkdir($filedir);
		
		include HDWIKI_ROOT.'/view/default/url.php';
		$this->view->assign('url',$url[$this->get[3]]);
		
		$handle=opendir($filedir);
		while($filename=readdir($handle)){
			if (!is_dir($filedir.$filename) && '.'!=$filename && '..'!=$filename && substr($filename,0,6)!="admin_" && substr($filename,-4)==".htm"){
				/*
				$view_key=str_replace(".","",substr($filename,-4));
				$viewlist[substr($filename,0,-4)]=$view_key;
				*/
				$tpl=substr($filename,0,-4);
				$viewlist[$tpl]=isset($url[$tpl])?1:0;
				$stylelang[substr($filename,0,-4)]=$this->view->lang['style'.substr($filename,0,-4)];
			}
		}
		closedir($handle);
		$filedir=HDWIKI_ROOT.'/style/'.$this->get[2].'/';
		file::forcemkdir($filedir);
		$handle=opendir($filedir);
		while($filename=readdir($handle)){
			if (!is_dir($filedir.$filename) && '.'!=$filename && '..'!=$filename && substr($filename,0,6)!='admin_' && substr($filename,-4)=='.css' || substr($filename,-4)=='.xml'){
				$style_key=str_replace('.','',substr($filename,-4));
				$stylelist[substr($filename,0,-4)]=$style_key;
				$stylelang[substr($filename,0,-4)]=$this->view->lang['style'.substr($filename,0,-4)];
			}
			if(is_file($filedir.'screenshot.jpg')){
				$styleimg=1;
			}
		}
		closedir($handle);
		if($this->get[3]!=''){
			$select_style=$this->get[3];
			$select_con=$_ENV['theme']->choose_theme_name($this->get[3]);
		}else{
			$select_style=$this->get[2];
			$select_con=$_ENV['theme']->choose_theme_name($this->get[2]);
		}
		$defaultstyle=$_ENV['theme']->choose_theme_name($this->setting['theme_name']);
		
		$this->cache->removecache('style');
		$this->view->assign('viewlist',$viewlist);
		$this->view->assign('stylelist',$stylelist);
		$this->view->assign('select_con',$select_con);
		$this->view->assign('style_path',$this->get[2]);
		$this->view->assign('defaultstyle',$defaultstyle);
		$this->view->assign('stylelang',$stylelang);
		$this->view->assign('styleimg',$styleimg);
		$this->view->assign('editfilename','hdwiki.css');
		$this->view->display('admin_theme_advancededit');
	}
	
	function doreadfile(){
		$filename=str_replace('*','.',$this->post['id']);
		if($this->post['bak']){
			$filedir=HDWIKI_ROOT.'/data/bak/'.$this->post['style_path'].'/'.$filename;
		}else{
			$type=explode('.',$filename);
			if($type[1]=='htm'){
				$filedir=HDWIKI_ROOT.'/view/'.$this->post['style_path'].'/'.$filename;
			}else{
				$filedir=HDWIKI_ROOT.'/style/'.$this->post['style_path'].'/'.$filename;
			}
		}
		if( file_exists($filedir) ){
			$data=file::readfromfile($filedir);
		}
		$title=string::hiconv($data,'utf-8','gbk');
		echo $title;
	}
	
	function dosavefile(){
		$type=explode('.',$this->post['filename']);
		if($type[1]=="htm"){
			$filedir=HDWIKI_ROOT.'/view/'.$this->get[2].'/';
		}else{
			$filedir=HDWIKI_ROOT.'/style/'.$this->get[2].'/';
		}
		file::forcemkdir($filedir);
		//备份文件夹
		$filebakdir = HDWIKI_ROOT.'/data/bak/'.$this->get[2].'/';
		file::forcemkdir($filebakdir);
		//得到当前时间戳
		//不用$this->date()原因: $this->date()返回后台设置的格式,不能返回我所需要的格式
		$timeoffset=$this->setting['time_offset']*3600+$this->setting['time_diff']*60;
		$filename=$this->post['filename'].'.bak'.date('YmdHis', $this->time+$timeoffset);
		if ($this->post['isbackup'] && !copy($filedir.$this->post['filename'],$filebakdir.$filename)) {
		    echo $this->view->lang['styleFileBackFail'];
		}else{
			$this->post['html_con']=string::hiconv($this->post['html_con']);
			file::writetofile($filedir.$this->post['filename'],stripcslashes($this->post['html_con']));
			$this->message($this->view->lang['styleEditSuccess'],'index.php?admin_theme-codeedit-'.$this->get[2].'-'.$type[0].'-'.$type[1]);
		}
	}
	
	function doremovestyle(){
		$style_path=$this->setting['theme_name'];
		if($style_path!=$this->get[2]){
			$_ENV['theme']->remove_style($this->get[2]);
		}
		$this->hsetcookie('theme','default',24*3600*365);
		$this->cache->removecache('style');
		$this->message($this->view->lang['styleDelSucess'],'index.php?admin_theme');
	}

	function dosetdefaultstyle(){
		$defaultpath=trim($this->get[2]);
		$stylefilepath = HDWIKI_ROOT.'/style/'.$defaultpath;
		if(is_dir($stylefilepath)){
			$_ENV['theme']->default_style($defaultpath);
			$this->cache->removecache('setting');
		}else{
			$this->message($this->view->lang['styleNotExist'],'BACK');
		}
		$this->header("admin_theme");
	}
	
	function dolist(){
		$num = 10;
		$page = isset($this->get[3]) ? max(1, intval($this->get[3])) : 1;
		$orderby = $this->get[2];
		if(!$orderby)$orderby='time';
		$wiki_list_url=$this->setting['app_url']."/hdapp.php?action=download&type=template&charset=".WIKI_CHARSET."&page=".$page."&orderby=".$orderby."&version=".urlencode(HDWIKI_VERSION);
		$style_content=@util::hfopen($wiki_list_url);
		$style_content=unserialize(base64_decode($style_content));
		if(empty($style_content)){
			$this->message($this->view->lang['styleEmpty'],'index.php?admin_theme');
		}
		$count = $style_content['count'];
		$departstr=$this->multi($count, $num, $page,'admin_theme-list-'.$orderby);
		$stylelist=$style_content['data'];
		if(is_array($stylelist)){
			foreach($stylelist as $key=>$style){
				$stylelist[$key]['image']=$this->setting['app_url'].$style['image'];
				$stylelist[$key]['install_list']=$this->setting['app_url']."/template.php?action=stat&id=".$style['appid'];
				$stylelist[$key]['tag']=explode(' ',$style['tag']);
			}
		}
		$addlist=$_ENV['theme']->get_wait_add();
		$this->view->assign('toaddlist',$addlist);
		$this->view->assign('tag_url',$this->setting['app_url']."/template.php?action=search&tag=");
		$this->view->assign("departstr",$departstr);
		$this->view->assign('stylelist',$stylelist);
		$this->view->assign('orderby',$orderby);
		$this->view->display('admin_themelist');
	}
	
	function doinstall(){
		if(isset($this->get[2])&&is_numeric($this->get[2])){
			$style_download_url=$this->setting['app_url']."/hdapp.php?action=download&type=template&install=1&id=".$this->get[2]."&url=".WIKI_URL;
			$zipcontent=@util::hfopen($style_download_url);
			$tmpdir=HDWIKI_ROOT.'/data/tmp/';
			file::forcemkdir($tmpdir);
			$tmpname=$tmpdir.util::random(6).'.zip';
			file::writetofile($tmpname,$zipcontent);
			require HDWIKI_ROOT."/lib/zip.class.php";
			require HDWIKI_ROOT."/lib/xmlparser.class.php";
			$zip=new zip();
			if(!$zip->chk_zip){
				$this->message($this->view->lang['styleInstallNoZlib'],'');
			}
			$ziplist=@$zip->get_List($tmpname);
			if(!(bool)$ziplist){
			  @unlink($tmpname);
			  $this->message($this->view->lang['styleZipFail'],'BACK');
			}
			$theme_name=$_ENV['theme']->get_theme_name($ziplist);
			@$zip->Extract($tmpname,$tmpdir);
			@unlink($tmpname);
			//move file
			$syle_path=$tmpdir.'hdwiki';
			if(is_dir(HDWIKI_ROOT.'/style/'.$theme_name)){
				@file::removedir($syle_path);
				$this->message($this->view->lang['stylePathRepeat'],'BACK');
			}
			@file::copydir($syle_path,HDWIKI_ROOT);
			@file::removedir($syle_path);
			//save db
			$style_xml=HDWIKI_ROOT.'/style/'.$theme_name.'/desc.xml';
			if(!is_file($style_xml)){
				$this->message($this->view->lang['styleXmlNotExist'],'BACK');
			}
			$xmlnav=$_ENV['theme']->read_xml($theme_name);
			$style['name']=$xmlnav['name'];
			$style['copyright']=$xmlnav['copyright'];
			$style['path']=$theme_name;
			$stylecon=$_ENV['theme']->add_check_style($style['path']);
			if($stylecon==null){
				$_ENV['theme']->add_style($style);
				$this->cache->removecache('style');
				$this->message($this->view->lang['styleInstallSuccess'],'BACK');
			}else{
				$this->message($this->view->lang['styleDbPathRepeat'],'index.php?admin_theme');
			}
		}else{
			$this->message($this->view->lang['commonParametersInvalidTip'],'index.php?admin_theme');
		}
	}
	
//下面的内容是模板修改新加入内容。------------------------------------------start--------------------------------------------------------------

	function dosaveblock(){
		//$this->post的信息包括theme_file和区域id中包含的按顺序用短线"-"隔开的区块id字符串。
		$theme_file = str_replace('-', '_', $this->post['theme_file']);
		if(file_exists($this->tempfile)){
			include $this->tempfile;
			unlink($this->tempfile);
		}
		$temp=isset($temp)?$temp:'';
		$data=$_ENV['theme']->block_query($this->post,$temp);
		//删除缓存。
		$this->cache->removecache("block_$theme_file");
		$this->cache->removecache("data_$theme_file");
		$viewfile=HDWIKI_ROOT.'/data/view/'.($GLOBALS['theme']=='default'?substr($theme_file,strpos($theme_file,'_')+1):$theme_file).'.tpl.php';
		if(file_exists($viewfile)){
			unlink($viewfile);
		}
		//返回提示信息。
		$this->message($data,"",2);
	}
	
	function dodelblock(){
		if(file_exists($this->tempfile)){
			include $this->tempfile;
		}
		if(!is_numeric($this->post['bid'])){
		    unset ($temp[$this->post['bid']]);
		}else{
		    $temp['del'][]=$this->post['bid'];
		}
		$contents='<?php $temp=';
		$contents.=var_export($temp,true).' ?>';
		file::writetofile( $this->tempfile,$contents);
		$this->message('ok',"",2);
	}
	
	function dopreview(){
		if($GLOBALS['theme']!==$this->get[2]){
			$this->hsetcookie('theme',$this->get[2],24*3600*365);
		}
		//删掉前面可能留下的临时文件。
		if(file_exists($this->tempfile)){
			unlink($this->tempfile);
		}
		$filedir=HDWIKI_ROOT.'/data/tmp/';
		$handle=opendir($filedir);
		$prefix = $GLOBALS['theme'].'.';
		while ($file = readdir($handle)) {
		    if(substr($file,0,strlen($prefix)) == $prefix){
			unlink($filedir.$file);
		    }
		}

		$url=$_ENV['theme']->get_url($this->get[3]);
		
		$this->view->assign('url',$url);
		$this->view->assign('theme',$this->get[2]);
		$this->view->assign('viewfile',$this->get[3]);
		$this->view->display('admin_theme_preview');
	}
	
	function doaddblock(){
		$this->hsetcookie('querystring','admin_theme', 3600);//重新设置querystring，为了避免hdwiki.class.php中run方法设置cookie导致后台重新进入会跑到这个页面。
		$defaultblocks=$_ENV['theme']->get_blocks(HDWIKI_ROOT.'/block/default');
		$themeblocks=$_ENV['theme']->get_blocks(HDWIKI_ROOT.'/block/'.$this->get[2]);
		$blocks = array_merge($defaultblocks, $themeblocks);
		$this->view->assign('blocks',$blocks);
		$this->view->display('admin_theme_add');
	}
    
	function dogetconfig(){
		$returndata=array();
		if(isset($this->post['bid'])){//编辑时
			$data=$_ENV['theme']->get_setting($this->post['bid'],$GLOBALS['theme']);
			$params='';
			if(is_array($data['params'])){
				$params='{';
				foreach($data['params'] as $key=>$value){
					$params.=$key.":'".$value."',";
				}
				$params=substr($params,0,-1).'}';
			}
			$block=$data['block'];
			$fun=$data['fun'];
			$this->view->assign('params',$params);
		}else{//添加时
			$block=$this->post['block'];
			$fun=$this->post['fun'];
		}
		
		$configPHPfile=$_ENV['global']->block_file($GLOBALS['theme'],'/'.$block.'/'.$block.'_inc.php');
		if(is_file($configPHPfile)){//是否有提供参数的inc.php文件，如果有则执行取到数据。
			include_once $configPHPfile;
			$inc=$block.'_inc';
			$obj=new $inc($this);
			$inc_data=method_exists($obj,$fun)?$obj->$fun():array();
		}
		if($inc_data){
			ob_start();
			$this->view->assign('inc_data',$inc_data);
			if(!file_exists(HDWIKI_ROOT.'/block/'.$GLOBALS['theme']."/$block/{$fun}_inc.htm")){
				$inctplfile="file://block/default/$block/{$fun}_inc";
			}else{
				$inctplfile='file://block/' .$GLOBALS['theme']."/$block/{$fun}_inc";
			}
			$this->view->display($inctplfile);
			$contents=ob_get_contents();
			ob_clean();
		}else{
			$configfile=$_ENV['global']->block_file($GLOBALS['theme'],'/'.$block.'/'.$fun.'_inc.htm');
			$contents='';
			if(is_file($configfile)){
				$contents = file::readfromfile($configfile);
			}
		}
		
		$tplfile=HDWIKI_ROOT."/data/tmp/".$GLOBALS['theme'].".{$block}.{$fun}.htm";
		$tplfile = file_exists($tplfile)? $tplfile:$_ENV['global']->block_file($GLOBALS['theme'],'/'.$block.'/'.$fun.'.htm');
		$tplcontent = file::readfromfile($tplfile);
		$contents.='<ul class="col-ul ul_l_s"><li><span>模板代码：</span><p style=" color:red;cursor:pointer" id ="tplbtn" onclick="block.viewtpl()">显示编辑模板</p><div id="tpl" style="display:none"><textarea id="tplcontent" class="textarea" style="width:390px; height:160px;">'.$tplcontent.'</textarea><p style="color:red;cursor:pointer" onclick="block.returntpl(\''.$block.'-'.$fun.'\')">恢复上次保存</p></div></li></ul>';
		
		$this->view->assign('contents',$contents);
		$this->view->display('admin_theme_ajax');
		//这个地方用了xml的格式返回数据。在js端完成了赋值工作，好处是模板的*.inc.htm配置文件，不用关心数据赋值,PHP程序端也不用关心。
		//坏处是ajax变复杂了，并且在显示的时候需依靠jquery完成赋值操作。
	}
    
	function dosavetemp(){//将参数写入临时文件，等待模板“保存修改”时调用参数，存入到数据库。
		$iseidt=isset($this->post['bid']);//编辑时
		$bid = $iseidt?$this->post['bid']:uniqid('hd');
		
		file_exists($this->tempfile)&&include($this->tempfile);
		
		if(strtoupper(WIKI_CHARSET) == 'GBK' && isset($this->post['params'])){//ajax在gbk下传过来的值是utf8的，所以gbk下需要转码。
			//array_walk($this->post['params'],string::hiconv());
			foreach($this->post['params'] as $key=>$val){
				$this->post['params'][$key]=string::hiconv($val);
			}
		}
		
		$this->post = string::hstripslashes($this->post);

		if($this->post['tplcontent']){
		    $tplc = $this->post['tplcontent'];
		    unset($this->post['tplcontent']);
		}
		
		if($iseidt){
			if(is_numeric($bid)){//如果是数字，则有可能是第一次编辑，临时表中，并没有他的数据。那么交给get_setting去处理。
				$data=$_ENV['theme']->get_setting($bid);
				$temp[$bid]['block']=$data['block'];
				$temp[$bid]['fun']=$data['fun'];
			}
			$temp[$bid]['params']=$this->post['params'];
			$cls=$temp[$bid]['block'];
			$fun=$temp[$bid]['fun'];
		}else{
			$cls=$this->post['block'];
			$fun=$this->post['fun'];
			$temp[$bid]=$this->post;
		}
		$contents='<?php $temp=';
		$contents.=var_export($temp,true).' ?>';
		file::writetofile( $this->tempfile,$contents);
		$this->view->setlang($this->setting['lang_name'],'front');
		//得到数据
		 $blockfile=$_ENV['global']->block_file($GLOBALS['theme'],"/$cls/$cls.php");
		if(is_file($blockfile)){
			include_once $blockfile;
			$obj=new $cls($this);
			if(method_exists($obj,$fun))
				$blockdata=$obj->$fun($this->post['params']);
			else
				$blockdata=array();
		}
		
		//将数据赋值给模板。显示替换上数据的html代码。
		$this->view->assign('bid',$bid);
		$this->view->assign('data',$blockdata);

		//2010-11-8模板代码编辑将文件内容写入临时文件，目的是给下面的预览时模板调用使用。
		$tplfile=HDWIKI_ROOT."/data/tmp/".$GLOBALS['theme'].".{$cls}.{$fun}.htm";
		isset ($tplc) && file::writetofile( $tplfile,$tplc);
		if(file_exists($tplfile)){
		    $tplfile = "file://data/tmp/".$GLOBALS['theme'].".{$cls}.{$fun}";
		}else{
		    if(!file_exists(HDWIKI_ROOT.'/block/'.$GLOBALS['theme']."/$cls/$fun.htm")){
			$tplfile="file://block/default/$cls/$fun";
		    }else{
			$tplfile='file://block/' .$GLOBALS['theme']."/$cls/$fun";
		    }
		}
		
		$this->view->display($tplfile);
	}

	function dogettpl(){
	    list($block,$fun) = explode('-', $this->post['tplfile']);
	    $tplfile = $_ENV['global']->block_file($GLOBALS['theme'],'/'.$block.'/'.$fun.'.htm');
	    echo file::readfromfile($tplfile);
	}

}
?>