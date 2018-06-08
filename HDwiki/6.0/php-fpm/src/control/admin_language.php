<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->view->setlang($this->setting['lang_name'],'back');
		$this->load("language");
	}
	
	function dodefault(){
		//$langtag 页面隐藏的语言文件标记
		$langtype = isset($this->get[2])?$this->get[2]:isset($this->post['langtype'])?$this->post['langtype']:'';
		$langtag = $langtype;
		if(!$langtype){
			$langtag = '0';
			$this->view->setlang($this->setting['lang_name'],'front');
		}
		//timeoffset内容为数组，所以释放
		unset($this->view->lang['timeoffset']);
		
		$keyword = !empty($this->post['keyword']) ? trim($this->post['keyword']) : '';
		if($keyword){
			$matches = array();
			$pattern='/(.*?)'.$keyword.'(.*?)/i';
			foreach($this->view->lang as $key=>$lang){
				if(is_string($lang)){
					if(preg_match($pattern,$lang)){
						$matches[$key]=$lang;
					}
				}
			}
		}else{
			foreach($this->view->lang as $key=>$lang){
				if(is_string($lang)){
					$matches[$key]=$lang;
				}
			}
		}
		$this->view->assign("langtype",$langtag);
		$this->view->assign("langtag",$langtag);
		$this->view->assign("lang",$matches);
		$this->view->display('admin_language');
	}
		
	function doeditlang(){
		switch($this->get[3]){
			case 0:
				$langname = 'front.php';
				break;
			case 1:
				$langname = 'back.php';
				break;
		}
		
		if(!$this->get[3]){
			template::setlang('zh','front');
			$this->view->lang = $this->lang;
		}
		
		$lang = array_merge($this->view->lang,$this->post['lang']);
		
		if(is_file(HDWIKI_ROOT.'/lang/zh/'.$langname)) {
			if(copy(HDWIKI_ROOT.'/lang/zh/'.$langname, HDWIKI_ROOT.'/lang/zh/bak_'.$langname)){
				$data ="<?php\r\n";
				foreach($lang as $key=>$value){
					$data.='$lang[\''.$key."']='".str_replace("'", "\'", str_replace("\\", "\\\\", stripslashes($value)))."';\r\n"; //只需要把\换成 \\, 把' 换成\' 即可
					$lang[$key]=$value;
				}
				if($this->get[3]==1){
					$data.='$lang[\'timeoffset\']'." = array(
							'-12'=>'(标准时-12:00) 日界线西',
							'-11'=>'(标准时-11:00) 中途岛、萨摩亚群岛',
							'-10'=>'(标准时-10:00) 夏威夷',
							'-9'=>'(标准时-9:00) 阿拉斯加',
							'-8'=>'(标准时-8:00) 太平洋时间(美国和加拿大)',
							'-7'=>'(标准时-7:00) 山地时间(美国和加拿大)',
							'-6'=>'(标准时-6:00) 中部时间(美国和加拿大)、墨西哥城',
							'-5'=>'(标准时-5:00) 东部时间(美国和加拿大)、波哥大',
							'-4'=>'(标准时-4:00) 大西洋时间(加拿大)、加拉加斯',
							'-3.5'=>'(标准时-3:30) 纽芬兰',
							'-3'=>'(标准时-3:00) 巴西、布宜诺斯艾利斯、乔治敦',
							'-2'=>'(标准时-2:00) 中大西洋',
							'-1'=>'(标准时-1:00) 亚速尔群岛、佛得角群岛',
							'0'=>'(格林尼治标准时) 西欧时间、伦敦、卡萨布兰卡',
							'1'=>'(标准时+1:00) 中欧时间、安哥拉、利比亚',
							'2'=>'(标准时+2:00) 东欧时间、开罗，雅典',
							'3'=>'(标准时+3:00) 巴格达、科威特、莫斯科',
							'3.5'=>'(标准时+3:30) 德黑兰',
							'4'=>'(标准时+4:00) 阿布扎比、马斯喀特、巴库',
							'4.5'=>'(标准时+4:30) 喀布尔',
							'5'=>'(标准时+5:00) 叶卡捷琳堡、伊斯兰堡、卡拉奇',
							'5.5'=>'(标准时+5:30) 孟买、加尔各答、新德里',
							'6'=>'(标准时+6:00) 阿拉木图、 达卡、新亚伯利亚',
							'7'=>'(标准时+7:00) 曼谷、河内、雅加达',
							'8'=>'(标准时+8:00)北京、重庆、香港、新加坡',
							'9'=>'(标准时+9:00) 东京、汉城、大阪、雅库茨克',
							'9.5'=>'(标准时+9:30) 阿德莱德、达尔文',
							'10'=>'(标准时+10:00) 悉尼、关岛',
							'11'=>'(标准时+11:00) 马加丹、索罗门群岛',
							'12'=>'(标准时+12:00) 奥克兰、惠灵顿、堪察加半岛');\r\n";
				}
				$data.="?>";
				file::writetofile(HDWIKI_ROOT.'/lang/zh/'.$langname,$data);
			}
		}
		file::cleardir(HDWIKI_ROOT.'/data/cache');
		file::cleardir(HDWIKI_ROOT.'/data/view');		
		$this->view->assign("langtype",$this->get[3]);
		$this->view->assign("langtag",$this->get[3]);
		$this->view->assign("lang",$lang);
		$this->message('语言文件修改成功!','index.php?admin_language-default-'.$this->get[3]);
	}
	
	function doaddlang(){
		switch ($this->post['addlangtype']){
			case 0:
				$langname = 'front.php';
				break;
			case 1:
				$langname = 'back.php';
				break;
		}
		$langcon = trim($this->post['langcon']);
		$langvar = trim($this->post['langname']);
		if(!$langcon || !$langvar){
			$this->message('语言变量内容不能为空!','index.php?admin_language');
		}

		if(is_file(HDWIKI_ROOT.'/lang/zh/'.$langname)) {
			$filelang = substr($langname,0,-4);
			$this->view->setlang($this->setting['lang_name'],$filelang);
			if(array_key_exists($langvar,$this->view->lang)){
				$this->message('模版变量名已存在,请重新填写!','index.php?admin_language');
			}
			if(copy(HDWIKI_ROOT.'/lang/zh/'.$langname, HDWIKI_ROOT.'/lang/zh/bak_'.$langname)){
				$data = file::readfromfile(HDWIKI_ROOT.'/lang/zh/'.$langname);
				$con = '$lang[\''.$langvar."']='".str_replace("'", "\'", str_replace("\\", "\\\\", stripslashes($langcon)))."';\r\n?>";
				$content = str_replace('?>',$con,$data);
				file::writetofile(HDWIKI_ROOT.'/lang/zh/'.$langname,$content);
			}
		}
		$langtype=$this->post['addlangtype'];
		$this->message('语言文件添加成功!','index.php?admin_language-default-'.$langtype);
	}
	
	/*
	function dodefault(){
		$languagelist=$_ENV['language']->get_all_list();
		$this->view->assign("languagelist",$languagelist);
		$this->view->assign("lang_name",$this->setting['lang_name']);
		$this->view->display('admin_lang');
	}
	
	function doaddlanguage(){
		$addlanguage['addlangname']=trim($this->post['addlangname']);
		$addlanguage['addlangpath']=trim($this->post['addlangpath']);
		$addlanguage['addlangcopyright']=trim($this->post['addlangcopyright']);
		if($addlanguage['addlangname']==''||$addlanguage['addlangpath']==''||$addlanguage['addlangcopyright']==''){
			$this->message($this->view->lang['langConNull'],'index.php?admin_language');
		}
		$langname=$_ENV['language']->add_check_language($addlanguage);
		if($langname){
			$this->message($this->view->lang['langFileExist'],'index.php?admin_language');			
		}else{
			$_ENV['language']->add_language($addlanguage);
		}
		$this->cache->removecache('language');
		header("Location:index.php?admin_language");
	}
	
	function doremovelanguage(){
		$removelanguageid = isset($this->post['lang_id'])?$this->post['lang_id']:array();
		$this->load('setting');
		$lang_name=$this->setting['lang_name'];
		if(is_array($removelanguageid)){
			foreach($removelanguageid as $languageid){
				$lang=$this->db->fetch_by_field('language','id',$languageid);
				if($lang_name!=$lang['path']){
					$_ENV['language']->remove_language($languageid);
				}
			}
			$this->cache->removecache('language');
		}
		header("Location:index.php?admin_language");
	}
	
	function doupdatelanguage(){
		$languageids = isset($this->post["all_lang_id"])?$this->post["all_lang_id"]:array();
		if(is_array($languageids)){
			foreach($languageids as $id){
				$name = $this->post["lang_name_".$id];
				$path = $this->post["lang_path_".$id];
				$state = isset($this->post["lang_state_".$id])?1:0;
				$_ENV['language']->update_language($name,$path,$state,$id);
			}
			$this->cache->removecache('language');
		}
		header("Location:index.php?admin_language");
	}
	
	function dosetdefaultlanguage(){
		$langpath = "lang_path_".$this->post['lang_id'][0];
		$langfilepath = HDWIKI_ROOT.'/lang/'.$this->post[$langpath];
		if(is_dir($langfilepath)){
			$_ENV['language']->default_language($this->post[$langpath]);
			$this->cache->removecache('setting');
		}else{
			$this->message($this->view->lang['langFileNone'],'index.php?admin_language');
		}
		header("Location:index.php?admin_language");
	}
	*/
}
?>
