<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load('setting');
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	/*base setting*/
	function dobase(){
		if(!isset($this->post['settingsubmit'])){
			$this->view->assign('timeoffset',$this->view->lang['timeoffset']);
			
			if(!is_numeric($this->setting['img_width_big'])) $this->setting['img_width_big']=300;
			if(!is_numeric($this->setting['img_height_big'])) $this->setting['img_height_big']=300;
			if(!is_numeric($this->setting['img_width_small'])) $this->setting['img_width_small']=140;
			if(!is_numeric($this->setting['img_height_small'])) $this->setting['img_height_small']=140;
			
			$this->view->assign('basecfginfo',$this->setting);
			$this->view->display("admin_base");
		}else{
			$settings = $this->post['setting'];
			if(empty($settings['site_notice'])){
				$this->load('user');
				$this->load('doc');
				$usernum=$_ENV['user']->get_total_num('',0);
				$docnum=$this->db->fetch_total('doc','1');
				$settings['site_notice']=$this->view->lang['resetnotice'].' '.number_format($usernum).' '.$this->view->lang['resetnotice0'].' '.number_format($docnum).' '.$this->view->lang['resetnotice2'];
			}
			foreach($settings as $key =>$value){
				$settings[$key] = trim($value);
			}
			if('/'==substr($settings['site_url'],-1)){
				$settings['site_url']=substr($settings['site_url'],0,-1);
			}
			$settings['site_url'] = string::stripspecialcharacter($settings['site_url']);
			$setting=$_ENV['setting']->update_setting($settings);
			$this->cache->removecache('setting');
			$this->message($this->view->lang['baseConfigSuccess'],'index.php?admin_setting-base');
		}
	}
	
	function dotime(){
		if(!isset($this->post['timesubmit'])){
			$this->view->assign('timeoffset',$this->view->lang['timeoffset']);
			$time['time_offset'] = $this->setting['time_offset'];
			$time['time_diff'] = $this->setting['time_diff'];
			$time['date_format'] = $this->setting['date_format'];
			$time['time_format'] = $this->setting['time_format'];
			$this->view->assign('basecfginfo',$time);
			$this->view->display("admin_time");
		}else{
			$settings = $this->post['setting'];
			if(!is_numeric($settings['time_diff'])){
				$this->message($this->view->lang['baseConfigMustNum'], 'BACK');
			}
			foreach($settings as $key =>$value){
				$settings[$key] = trim($value);
			}
			$setting=$_ENV['setting']->update_setting($settings);
			$this->cache->removecache('setting');
			$this->message($this->view->lang['baseConfigSuccess'],'index.php?admin_setting-time');
		}	
	}
	
	function docode(){
		if(!isset($this->post['codesubmit'])){
			$this->view->assign('checkcode',$this->setting['checkcode']);
			$this->view->display("admin_code");
		}else{
			$settings['checkcode'] = $this->post['checkcode'];
			$setting=$_ENV['setting']->update_setting($settings);
			file::cleardir(HDWIKI_ROOT.'/data/cache');
			file::cleardir(HDWIKI_ROOT.'/data/view');
			$this->message($this->view->lang['baseConfigSuccess'],'index.php?admin_setting-code');
		}
	}

	function docookie(){
		if(!isset($this->post['cookiesubmit'])){
			$cookie['cookie_domain'] = $this->setting['cookie_domain'];
			$cookie['cookie_pre'] = $this->setting['cookie_pre'];
			$this->view->assign('basecfginfo',$cookie);
			$this->view->display("admin_cookie");
		}else{
			$settings = $this->post['setting'];
			if(!$settings['cookie_pre']){
				$settings['cookie_pre']='hd_';
			}
			foreach($settings as $key =>$value){
				$settings[$key] = trim($value);
			}
			$setting=$_ENV['setting']->update_setting($settings);
			$this->cache->removecache('setting');
			$this->message($this->view->lang['baseConfigSuccess'],'index.php?admin_setting-cookie');
		}
	}
	
	function dobaseregister(){
		if(!isset($this->post['settingsubmit'])){
			$this->view->assign('basecfginfo',$this->setting);
			$this->view->display("admin_baseregister");
		}else{
			$settings = $this->post['setting'];
			foreach($settings as $key =>$value){
				$settings[$key] = trim($value);
			}
			$error_names = $settings['error_names'];
			$error_names = str_replace(",", "\n", $error_names);
			$error_names = str_replace('"', '', $error_names);
			$error_names = explode("\n", $error_names);
			foreach($error_names as $key =>$value){
				$error_names[$key] = trim($value);
				if ('' === $error_names[$key]){unset($error_names[$key]);}
			}
			$settings['error_names'] = implode("\n", $error_names);
			$settings=$_ENV['setting']->update_setting($settings);
			$this->cache->removecache('setting');
			$this->message($this->view->lang['baseConfigSuccess'],'index.php?admin_setting-baseregister');
		}
	}
	/*security*/
	function dosec(){
		if(!isset($this->post['secsubmit'])){
			$this->view->assign('basecfginfo',$this->setting);
			$this->view->display("admin_sec");
		}else{
			$settings['doc_verification_edit_code']=isset($this->post['doc_verification_edit_code'])?$this->post['doc_verification_edit_code']:0;
			$settings['doc_verification_create_code']=isset($this->post['doc_verification_create_code'])?$this->post['doc_verification_create_code']:0;
			$settings['doc_verification_reference_code']=isset($this->post['doc_verification_reference_code'])?$this->post['doc_verification_reference_code']:0;
			$settings['forbidden_edit_time']=trim($this->post['forbidden_edit_time']);
			$settings['eng_max_pcnt']=trim($this->post['eng_max_pcnt']);
			$settings['extlink_max_pcnt']=trim($this->post['extlink_max_pcnt']);
			$settings['submit_min_interval']=trim($this->post['submit_min_interval']);
			$settings['save_spam']=isset($this->post['save_spam'])?$this->post['save_spam']:1;
			if(!is_numeric($settings['forbidden_edit_time'])||($settings['forbidden_edit_time']>999 || $settings['forbidden_edit_time'] < 0)){
				$this->message($this->view->lang['secForbidEditTimeWarning'],'BACK');
			}
			if(!is_numeric($settings['eng_max_pcnt'])||($settings['eng_max_pcnt']>100 || $settings['eng_max_pcnt'] < 0)){
				$this->message($this->view->lang['eng_max_pcnt_err'],'BACK');
			}
			if(!is_numeric($settings['extlink_max_pcnt'])||($settings['extlink_max_pcnt']>100 || $settings['extlink_max_pcnt'] < 0)){
				$this->message($this->view->lang['extlink_max_pcnt_err'],'BACK');
			}
			if(!is_numeric($settings['submit_min_interval'])||($settings['submit_min_interval']>99999 || $settings['submit_min_interval'] < 0)){
				$this->message($this->view->lang['submit_min_interval_err'],'BACK');
			}
			
			$settings=$_ENV['setting']->update_setting($settings);
			$this->cache->removecache('setting');
			$this->message($this->view->lang['baseConfigSuccess'],'index.php?admin_setting-sec');
		}
	}
	
	/*upload logo*/
	function dologo(){
		if(!isset($this->post['logsubmit'])){
			$this->view->assign('logowidth',$this->setting['logowidth']);
			$this->view->display("admin_uploadlogo");
		}else{
			$settings['logowidth'] = $this->post['logowidth'] ? $this->post['logowidth']:'220px';
			$setting=$_ENV['setting']->update_setting($settings);
			$this->cache->removecache('setting');
			if($_FILES['logo']['name']){
				$imgname=$_FILES['logo']['name'];
				$filetype = array('image/jpeg','image/gif','image/x-png','image/png','image/pjpeg');
				if(in_array($_FILES['logo']['type'],$filetype)){
					$destfile='style/default/logo.gif';
					$arrupload=file::uploadfile($_FILES['logo'],$destfile,1024);
					if(isset($arrupload) && $arrupload['result'] == true){
						$this->message($arrupload['msg'],'index.php?admin_setting-logo');
					}else{
						$this->message($this->view->lang['uploadFail'],'BACK');
					}
				}else{
					$this->message($this->view->lang['uploadFormatWrong'],'BACK');
				}
			}
			$this->message('宽度修改成功！','index.php?admin_setting-logo');
		}
	}

	/*set credit*/
	function docredit(){
		if(!isset($this->post['creditsubmit'])){
			$this->view->assign('creditconfig',$this->setting);
			$this->view->display("admin_credit");
		}else{
			$creditlist=array(
				'credit_register'=>20,
				'credit_login'=>1,
				'credit_create'=>5,
				'credit_edit'=>3,
				'credit_upload'=>2,
				'credit_pms'=>1,
				'credit_comment'=>2,
				'credit_download'=>0,
				
				'coin_register'=>20,
				'coin_login'=>1,
				'coin_create'=>5,
				'coin_edit'=>3,
				'coin_upload'=>2,
				'coin_pms'=>1,
				'coin_comment'=>2,
				'coin_download'=>10,
				
				'credit_exchangeRate'=>10,
				'coin_exchangeRate'=>1,
				'credit_exchange'=>0,
				'coin_exchange'=>0
			);
			foreach($creditlist as $creditkey => $creditvalue){
				$setting[$creditkey]=(is_numeric($this->post[$creditkey]))?intval($this->post[$creditkey]):$creditvalue;
			}
			$setting['credit_unit']=trim($this->post['credit_unit']);
			$setting['coin_unit']=trim($this->post['coin_unit']);
			$setting['coin_name']=trim($this->post['coin_name']);
			
			$_ENV['setting']->update_setting($setting);
			$this->cache->removecache('setting');
			$this->message($this->view->lang['bonusSuccess'],'index.php?admin_setting-credit');
		}
	}
	
	function dolistdisplay(){
		if(!isset($this->post['submit'])){
			$this->view->assign('listdisplay',$this->setting);
			$this->view->display("admin_listdisplay");
		}else{
			$listdisplay = $this->post[listplay];
			foreach($listdisplay as $key=>$display){
				$setting[$key]=(intval($display)>0)?intval($display):10;
			}
			$_ENV['setting']->update_setting($setting);
			$this->cache->removecache('setting');
			$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
			$this->message($this->view->lang['listDisplaySucess'],'index.php?admin_setting-listdisplay');
		}
	}
	
	function doattachment(){
		if(!isset($this->post['attachmentsubmit'])){
		
			$post_max_size =intval(ini_get('post_max_size'));
			$upload_max_filesize=intval(ini_get('upload_max_filesize'));
			
			$sys_upload_limit=min($post_max_size, $upload_max_filesize);
			$this->view->assign('sys_upload_limit', $sys_upload_limit * 1024);
 			$this->view->display("admin_attachment");
 		}else{
 			if(!is_numeric($this->post['attachment_size'])){$this->message($this->view->lang['attachmentSizeTrip'],'index.php?admin_setting-attachment');}
 			if($this->post['attachment_type']==""){$this->post['attachment_type']="jpg|jpeg|bmp|gif|zip|rar|doc|ppt|mp3|xls|txt|swf|flv";}
 			unset($this->post['attachmentsubmit']);
			$this->post['attachment_type']=preg_replace("/[^|\w*]/i", '', $this->post['attachment_type']);
 			$_ENV['setting']->update_setting($this->post);
 			$this->cache->removecache('setting');
 			$this->message($this->view->lang['attachmentConfigSucess'],'index.php?admin_setting-attachment');
 		}
	}

	function domail(){
		if(!isset($this->post['mailsubmit'])){
			if(isset($this->setting['mail_config'])){
				$mail_config=unserialize($this->setting['mail_config']);
				$this->view->assign('mailconfig',$mail_config);
			}
			$this->load('user');
			$this->view->assign('adminemail', $_ENV['user']->get_admin_email());
 			$this->view->display("admin_mail");
 		}else{
 			$config['maildefault'] = $this->post['maildefault'];
 			$config['mailsend'] = $this->post['mailsend'];
 			$config['mailserver'] = $this->post['mailserver'];
 			$config['mailport'] = $this->post['mailport'];
 			$config['mailauth'] = $this->post['mailauth'];
 			$config['mailfrom'] = $this->post['mailfrom'];
 			$config['mailauth_username'] = $this->post['mailauth_username'];
 			$config['mailauth_password'] = $this->post['mailauth_password'];
 			$config['maildelimiter'] = $this->post['maildelimiter'];
 			$config['mailusername'] = $this->post['mailusername'];
 			$mail['mail_config']=serialize($config);
 			unset($config);
 			$setting=$_ENV['setting']->update_setting($mail);
 			$this->cache->removecache('setting');
 			$this->load('mail');
 			$_ENV['mail']->reset_failures();
 			$this->message($this->view->lang['emaliSuc'],'BACK');
 		}
	}
	
	function donoticemail() {
		if(!isset($this->post['submit'])) {
			if(isset($this->setting['noticemail']) && isset($this->setting['noticemailtpl'])) {
				$config = unserialize($this->setting['noticemail']);
				$this->view->assign('doc_create', explode(',', $config['doc-create']));
				$this->view->assign('doc_edit', explode(',', $config['doc-edit']));
				$this->view->assign('comment_add', explode(',', $config['comment_add']));
				
				$configtpl = unserialize($this->setting['noticemailtpl']);
				$this->view->assign('noticemailtpl', $configtpl);
			} else {
				$this->view->assign('doc_create', array());
				$this->view->assign('doc_edit', array());
			}
			$this->load('usergroup');
			$groups = $_ENV['usergroup']->get_all_list(-1, 'type asc');
			$this->view->assign('groups', $groups);
			$this->view->display("admin_noticemail");
		} else {
			$config['noticemail'] = serialize(array(
				'doc-create' => empty($this->post['doc-create']) ? '' : implode(',', $this->post['doc-create']),
				'doc-edit' => empty($this->post['doc-edit']) ? '' : implode(',', $this->post['doc-edit']),
				'comment_add' => empty($this->post['comment_add']) ? '' : implode(',', $this->post['comment_add']),
			));

			$this->post['noticemailtpl'] = string::hstripslashes($this->post['noticemailtpl']);
//			foreach($this->post['noticemailtpl'] as $key => $val) { //由于已经在base里执行了 addslashes，此处必须先去掉slashes
//				$this->post['noticemailtpl'][$key] = stripslashes($val);
//			}
			$config['noticemailtpl'] = addslashes(serialize($this->post['noticemailtpl']));
			
			$_ENV['setting']->update_setting($config);
			$this->cache->removecache('setting');
			$this->message($this->view->lang['commonSuccess'], 'BACK');
		}
	}

	/*set seo*/
	function doseo(){
		$servertype=strtoupper(substr($_SERVER['SERVER_SOFTWARE'],0,6));
		if(!isset($this->post['seosubmit'])){
			$this->view->assign('servertype',$servertype);
			$this->view->assign('seoconfig',$this->setting);
			$this->view->display("admin_seo");
		}else{
			if(empty($this->post['seo_prefix'])){$this->post['seo_prefix']='index.php?';}
			$seotype['seo_type']=$this->post['seo_type'];
			if($seotype['seo_type']==1){
			/*	if($servertype!='APACHE'){
					$seotype['seo_type']=0;
					$seotype['seo_prefix']=$this->post['seo_prefix'];
					$_ENV['setting']->update_setting($seotype);
					$this->cache->removecache('setting');
					$this->message('Sorry!The server is not apache!','index.php?admin_setting-seo');
				}*/
				$base_root=substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],"index.php"));
				
				if('1'==$this->post['seo_type_doc']){
				$data="
<IfModule mod_rewrite.c>
	RewriteEngine on
	RewriteBase $base_root
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule ^wiki/(.*)$ index.php?doc-innerlink-$1
</IfModule>";
				}else{
				 $data="
<IfModule mod_rewrite.c>
	RewriteEngine on
	RewriteBase $base_root
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule ^.*$ index.php?\$0
</IfModule>";
				}
				
				$bytes=file::writetofile(HDWIKI_ROOT."/.htaccess",$data);
				if($bytes==0){
					$seotype['seo_type']=0;
					$seotype['seo_prefix']=$this->post['seo_prefix'];
					$_ENV['setting']->update_setting($seotype);
					$this->cache->removecache('setting');
					$this->message($this->view->lang['seoConfigFileWriteError'],'BACK');
				}
				$this->post['seo_prefix']=('1'==$this->post['seo_type_doc'])?"index.php?":"";
			}else if(file_exists(HDWIKI_ROOT."/.htaccess")){
				unlink (HDWIKI_ROOT."/.htaccess");
			}
			$this->post['seo_headers'] = string::stripscript($this->post['seo_headers']);
			$setting=$_ENV['setting']->update_setting($this->post);
			$this->cache->removecache('setting');
			file::cleardir(HDWIKI_ROOT.'/data/view');
 			$this->message($this->view->lang['seoConfigSuccess'],'index.php?admin_setting-seo');
		}
	}

	function docache(){
		$arr_cachelist=array(array("variable"=>"index_cache_time","value"=>$this->setting['index_cache_time'],"describ"=>$this->view->lang['delIndeCache'],"cache_name"=>$this->view->lang['indeCache']),
		array("variable"=>"list_cache_time","value"=>$this->setting['list_cache_time'],"describ"=>$this->view->lang['delListCache'],"cache_name"=>$this->view->lang['listCache'])
		);
		$this->view->assign("cachelist",$arr_cachelist);
		$this->view->display("admin_cache");
	}
	
	function dorenewcache(){
		$cachelist = array('index_cache_time','list_cache_time');
		foreach($cachelist as $cache){
			if(!is_numeric($this->post[$cache."_value"])){
				$this->message($this->view->lang['cacheTimeIsNum'],'BACK');
			}
		}
		$_ENV['setting']->update_cache($cachelist,$this->post);
		$this->cache->removecache('setting');
		$this->message($this->view->lang['cacheConfigSuccess'],'index.php?admin_setting-cache');
	}

	function doremovecache(){
		file::cleardir(HDWIKI_ROOT.'/data/cache');
		file::cleardir(HDWIKI_ROOT.'/data/view');
		$this->message($this->view->lang['cacheDelSuccess'],'index.php?admin_setting-cache');
	}
	
	function donotice(){
		if(isset($this->post['notice'])){
			$notice=$this->post['notice'];
			if(empty($notice)){
				$this->load('user');
				$this->load('doc');
				$usernum=$_ENV['user']->get_total_num('',0);
				$docnum=$this->db->fetch_total('doc','1');
				$notice=$this->view->lang['resetnotice'].' '.number_format($usernum).' '.$this->view->lang['resetnotice0'].' '.number_format($docnum).' '.$this->view->lang['resetnotice2'];
			}
			$setting['site_notice']=$notice;
			$_ENV['setting']->update_setting($setting);
			$this->cache->removecache('setting');
			$this->message($this->view->lang['usermanageOptSuccess'],'index.php?admin_setting-notice');
		}
		$this->view->display("admin_notice");
	}
	
	function dopassport(){
		$ppfile=HDWIKI_ROOT.'/data/passport.inc.php';
		if(isset($this->post['passport'])){
			$passportdata="<?php
define('PP_OPEN', '".$this->post['ppopen']."');
define('PP_TYPE', '".$this->post['pptype']."');
define('PP_API', '".$this->post['ppapi']."');
define('PP_NAME', '".$this->post['ppname']."');
define('PP_LOGIN', '".$this->post['pplogin']."');
define('PP_LOGOUT', '".$this->post['pplogout']."');
define('PP_REG', '".$this->post['ppreg']."');
define('PP_KEY', '".$this->post['ppkey']."');
?>";
			$byte=file::writetofile($ppfile,$passportdata);
			if($byte==0){
				$this->message($this->view->lang['passportnotwrite'],'BACK');
			}else{
				$this->message($this->view->lang['passportsucess'],'index.php?admin_setting-passport');
			}
		}else{
			if(file_exists($ppfile)){
				include($ppfile);
				if(defined('PP_API')){
					$this->view->assign('pp_open',PP_OPEN);
					$this->view->assign('pp_type',PP_TYPE);
					$this->view->assign('pp_api',PP_API);
					$this->view->assign('pp_name',PP_NAME);
					$this->view->assign('pp_login',PP_LOGIN);
					$this->view->assign('pp_logout',PP_LOGOUT);
					$this->view->assign('pp_reg',PP_REG);
					$this->view->assign('pp_key',PP_KEY);
				}
			}
			$this->view->display("admin_passport");
		}
	}
	
	function dowatermark(){
		if(!isset($this->post['settingsubmit'])){
			if(isset($this->setting['watermark'])){
				$settingsnew=unserialize($this->setting['watermark']);
				$settingsnew['imageimpath']=urldecode($settingsnew['imageimpath']);
				$this->view->assign('settingsnew',$settingsnew);
			}
			$list = array('auto_picture','img_width_big','img_height_big','img_width_small','img_height_small');
			foreach($list as $value){
				$basecfginfo[$value] = $this->setting[$value];
			}
			$this->view->assign('basecfginfo',$basecfginfo);
			$ttf=file::get_file_by_ext("./style/default",array('ttf','ttc','fon'));
			$this->view->assign('ttf',$ttf);
 			$this->view->display("admin_watermark");
 		}else{
 			$settingsnew=$this->post['settingsnew'];
 			$settingsnew['imageimpath']=urlencode(stripslashes($settingsnew['imageimpath']));
			if($settingsnew['watermarktype'] == 2) {
				$settingsnew['watermarktext']['size'] = intval($this->post['settingsnew']['watermarktext']['size']);
				$settingsnew['watermarktext']['angle'] = intval($this->post['settingsnew']['watermarktext']['angle']);
				$settingsnew['watermarktext']['shadowx'] = intval($this->post['settingsnew']['watermarktext']['shadowx']);
				$settingsnew['watermarktext']['shadowy'] = intval($this->post['settingsnew']['watermarktext']['shadowy']);
				$settingsnew['watermarktext']['fontpath'] = str_replace(array('\\', '/'), '', $this->post['settingsnew']['watermarktext']['fontpath']);
				if($settingsnew['watermarktype'] == 2 && $settingsnew['watermarktext']['fontpath']) {
					$fontpath = $settingsnew['watermarktext']['fontpath'];
					$settingsnew['watermarktext']['fontpath'] = file_exists('./style/default/'.$fontpath) ? $fontpath : (file_exists('./style/default/'.$fontpath) ? $fontpath : '');
					if(!$settingsnew['watermarktext']['fontpath']){
						$this->message($this->view->lang['watermark_fontpath_error'],'index.php?admin_setting-watermark');
					}
				}
			}else{
				unset($settingsnew['watermarktext']);
			}
			$set=$this->post['setting'];
			$set['watermark']=serialize($settingsnew);
			$setting=$_ENV['setting']->update_setting($set);
			$this->cache->removecache('setting');
			$this->message($this->view->lang['watermarkSuc'],'index.php?admin_setting-watermark');
		}
	}
	
	function dopreview(){
		$settingsnew=$this->post['settingsnew'];
		if($settingsnew['watermarktype'] == 2) {//文字水印
			$settingsnew['watermarktext']['size'] = intval($this->post['settingsnew']['watermarktext']['size']);
			$settingsnew['watermarktext']['angle'] = intval($this->post['settingsnew']['watermarktext']['angle']);
			$settingsnew['watermarktext']['shadowx'] = intval($this->post['settingsnew']['watermarktext']['shadowx']);
			$settingsnew['watermarktext']['shadowy'] = intval($this->post['settingsnew']['watermarktext']['shadowy']);
			$settingsnew['watermarktext']['fontpath'] = str_replace(array('\\', '/'), '', $this->post['settingsnew']['watermarktext']['fontpath']);
			if($settingsnew['watermarktype'] == 2 && $settingsnew['watermarktext']['fontpath']) {
				$fontpath = $settingsnew['watermarktext']['fontpath'];
				$settingsnew['watermarktext']['fontpath'] = file_exists('./style/default/'.$fontpath) ? $fontpath : (file_exists('./style/default/'.$fontpath) ? $fontpath : '');
				if(!$settingsnew['watermarktext']['fontpath']){
					$this->message($this->view->lang['watermark_fontpath_error'],'index.php?admin_setting-watermark');
				}
			}
		}else{
			unset($settingsnew['watermarktext']);
		}
		if($settingsnew[watermarkstatus]=='0'){
			$this->message($this->view->lang['noWaterMark'],'');
		}
		$this->load("watermark");
		$pic="./style/default/watermark/preview.jpg";
		$previewpic="./style/default/watermark/preview_tem.jpg";
		@unlink($previewpic);
		if(!is_file($pic)){
			$this->message('预览需要的图片不存在。','');
		}
		if($_ENV['watermark']->image($pic,$previewpic,$settingsnew)){
			echo '<img src="'.$previewpic.'?'.util::random().'" />';
		}else{
			$this->message('图片加水印失败，请检查你设置的参数。','');
		}
	}
	
	function doanticopy(){
		if(!isset($this->post['settingsubmit'])){
			$config['random_open'] = $this->setting['random_open'];
			$config['random_text'] = $this->setting['random_text'];
			$config['check_useragent'] = $this->setting['check_useragent'];
			$config['ua_allow_first'] = $this->setting['ua_allow_first'];
			$config['allow_ua_both'] = $this->setting['allow_ua_both'];
			$config['ua_blacklist'] = $this->setting['ua_blacklist'];
			$config['ua_whitelist'] = $this->setting['ua_whitelist'];
			$config['check_visitrate'] = $this->setting['check_visitrate'];
			$config['visitrate'] = unserialize($this->setting['visitrate']);
			$config['visitrate_ip_exception'] = $this->setting['visitrate_ip_exception'];
			$this->view->assign('config',$config);
 			$this->view->display("admin_anticopy");
 		}else{
 			$config['random_open'] = intval($this->post['random_open']);
			$config['random_text'] = trim($this->post['random_text']);
			$config['check_useragent'] = intval($this->post['check_useragent']);
			$config['ua_allow_first'] = intval($this->post['ua_allow_first']);
			$config['allow_ua_both'] = intval($this->post['allow_ua_both']);
			$config['ua_blacklist'] = $this->post['ua_blacklist'];
			$config['ua_whitelist'] = $this->post['ua_whitelist'];
			$config['check_visitrate'] = intval($this->post['check_visitrate']);
			$this->post['visitrate']['duration'] = intval($this->post['visitrate']['duration']);
			$this->post['visitrate']['pages'] = intval($this->post['visitrate']['pages']);
			$this->post['visitrate']['ban_time'] = intval($this->post['visitrate']['ban_time']);
			!$this->post['visitrate']['duration'] && $this->post['visitrate']['duration'] = 10; 
			!$this->post['visitrate']['pages'] && $this->post['visitrate']['pages'] = 5; 
			!$this->post['visitrate']['ban_time'] && $this->post['visitrate']['ban_time'] = 1; 
			$config['visitrate'] = serialize($this->post['visitrate']);
			$config['visitrate_ip_exception'] = trim($this->post['visitrate_ip_exception']);
			
 			$_ENV['setting']->update_setting($config);
 			$this->cache->removecache('setting');
 			$this->message($this->view->lang['randomstr_notice'],'BACK');
 		}
	}
	
	function doshortcut(){
		$shortcut = trim($this->post['link']);
		if(string::hstrtoupper(WIKI_CHARSET)=='GBK'){
			$shortcut=string::hiconv($shortcut,'gbk','utf-8');
		}
		$setting['shortcut']=$shortcut;
		$setting['shortcutstate']=$this->post['shortcutstate'];
		$_ENV['setting']->update_setting($setting);
		$this->cache->removecache('setting');
		$this->message("1","",2);
	}
	
	function doindex(){
		if(!isset($this->post['indexsubmit'])){
			$this->view->assign('base_toplist',$this->setting['base_toplist']);
			$this->view->display("admin_index");
		}else{
			$settings['base_toplist'] = $this->post['base_toplist'];
			$setting=$_ENV['setting']->update_setting($settings);
			$this->cache->removecache('setting');
			$this->message('首页设置保存成功！','index.php?admin_setting-index');
		}
	}
	
	function dodocset(){
		if(!isset($this->post['docsubmit'])){
			$list = array('sandbox_id','verify_doc','max_newdocs', 'comments','filter_external','base_createdoc', 'base_isreferences');
			foreach($list as $value){
				$basecfginfo[$value] = $this->setting[$value];
			}
			$this->view->assign('basecfginfo',$basecfginfo);
			$this->view->display("admin_docset");
		}else{
			$settings = $this->post['setting'];
			$setting=$_ENV['setting']->update_setting($settings);
			$this->cache->removecache('setting');
			$this->message('词条设置保存成功！','index.php?admin_setting-docset');
		}	
	}
	
	function dosearch(){
		if(!isset($this->post['searchsubmit'])){
			$list = array('search_time','search_tip_switch', 'cloud_search');
			foreach($list as $value){
				$basecfginfo[$value] = $this->setting[$value];
			}
			$this->view->assign('basecfginfo',$basecfginfo);
			$this->view->display("admin_search");
		}else{
			$settings = $this->post['setting'];
			$setting=$_ENV['setting']->update_setting($settings);
			$this->cache->removecache('setting');
			$this->message('搜索设置保存成功！','index.php?admin_setting-search');
		}	
	}
	
	function doucenter(){
		$d_url='index.php?admin_setting-ucenter';
		if(isset($this->post['submit'])){
			$settings['ucopen'] = $this->post['ucopen'];
			$settings['feed'] = serialize($this->post['feed']);
			$setting=$_ENV['setting']->update_setting($settings);
			$this->cache->removecache('setting');
			$this->message('UCenter设置成功！',$d_url);
		}elseif(isset($this->post['ucsubmit'])){
			$ucopen=1;
			$ucapi=$this->post['ucapi'];
			$ucpassword=$this->post['ucpassword'];
			$ucip=$this->post['ucip'];

			$ucapi = preg_replace("/\/$/", '', trim($ucapi));
			$ucip = trim($ucip);

			if(empty($ucapi) || !preg_match("/^(http:\/\/)/i", $ucapi)) {
				$this->message('您输入的URL地址不正确！',$d_url);
			}
			if(!$ucip){
				$temp = @parse_url($ucapi);
				$ucip = gethostbyname($temp['host']);
				if(ip2long($ucip) == -1 || ip2long($ucip) === FALSE) {
					$ucip = '';
				}
			}
			
			define('UC_API',true);
			if(!@include_once HDWIKI_ROOT.'/api/uc_client/client.php') {
				$this->message('uc_client目录不存在。',$d_url);
			}
			
			$ucinfo = uc_fopen($ucapi.'/index.php?m=app&a=ucinfo&release='.UC_CLIENT_RELEASE, 500, '', '', 1, $ucip);
			list($status, $ucversion, $ucrelease, $uccharset, $ucdbcharset, $apptypes) = explode('|', $ucinfo);
			if($status != 'UC_STATUS_OK') {
				$this->message('uc_url_unreachable',$d_url);
			} else {
				$dbcharset = strtolower(str_replace('-', '', trim(WIKI_CHARSET)));
				$ucdbcharset = strtolower(str_replace('-', '', $ucdbcharset));
				
				if(UC_CLIENT_VERSION > $ucversion) {
					$this->message('uc_version_incorrect',$d_url);
				} elseif($dbcharset && $ucdbcharset != $dbcharset) {
					$this->message('uc_dbcharset_incorrect',$d_url);
				}
				
				$tagtemplates = '';
				$app_url=WIKI_URL;
				$postdata = "m=app&a=add&ucfounder=&ucfounderpw=".urlencode($ucpassword)."&apptype=".urlencode('OTHER')."&appname=".urlencode('HDWIKI')."&appurl=".urlencode($app_url)."&appip=&appcharset=".$dbcharset.'&appdbcharset='.$dbcharset.'&'.$tagtemplates.'&release='.UC_CLIENT_RELEASE;
				$s = uc_fopen($ucapi.'/index.php', 500, $postdata, '', 1, $ucip);
				if(empty($s)) {
					$this->message('不能连接到 UCenter 服务端。',$d_url);
				} elseif($s == '-1') {
					$this->message('UCenter 密码错误。',$d_url);
				} else {
					$ucs = explode('|', $s);
					if(empty($ucs[0]) || empty($ucs[1])) {
						$this->message('UCenter 不能返回数据。',$d_url);
					}
				}
				$ucdata="<?php
					define('UC_OPEN','$ucopen');
					define('UC_CONNECT', 'uc_api_post');

					define('UC_DBHOST', '{$ucs[2]}');
					define('UC_DBUSER', '{$ucs[4]}');
					define('UC_DBPW', '{$ucs[5]}');
					define('UC_DBNAME', '{$ucs[3]}');
					define('UC_DBCHARSET', '{$ucs[6]}');
					define('UC_DBTABLEPRE', '{$ucs[7]}');

					define('UC_KEY', '{$ucs[0]}');
					define('UC_API', '$ucapi');
					define('UC_CHARSET', '{$ucs[8]}');
					define('UC_IP', '$ucip');
					define('UC_APPID', '{$ucs[1]}');
					?>";
				$byte=file::writetofile(HDWIKI_ROOT.'/api/ucconfig.inc.php',$ucdata);
				if($byte==0){
					$this->message('不能写ucconfig.inc.php文件。',$d_url);
				}
				$this->message('UCenter 设置成功！',$d_url);
			}
		}elseif(isset($this->post['import'])){
			set_time_limit(0);
			$url=HDWIKI_ROOT.'/api/ucconfig.inc.php';
			if(!file_exists($url)){
				$this->message('您还没有设置过UCenter!','BACK');
			}else{
				include($url);
				if(!(UC_OPEN && isset($this->setting['ucopen']) && $this->setting['ucopen'])){
					$this->message('您还没有开通ucenter！','BACK');
				}
				$url=HDWIKI_ROOT."/data/import_uc.lock";
				if(file_exists($url)){
					$this->message('您已经导入过用户，想重新导入请删除'.$url.'文件。','BACK');
				}
			}
			#包含uc相应文件。
			require_once(HDWIKI_ROOT.'/api/uc_client/client.php');
			require_once(HDWIKI_ROOT.'/api/uc_client/lib/db.class.php');
			#
			#实例uc数据类
			$ucdb = new db();
			$ucdb->connect(UC_DBHOST, UC_DBUSER, UC_DBPW, UC_DBNAME, UC_DBCHARSET, UC_DBCONNECT, UC_DBTABLEPRE);
			#
			$this->load('user');
			$ucuserlist='';
			$usercount=$_ENV['user']->get_total_num('',0);
			for($i=0;$i<$usercount;$i=$i+100){
				$userlist=$_ENV['user']->get_list('',0,$i,100);
				foreach($userlist as $temUser){
					if(uc_get_user($temUser['username'])) {
						$ucuserlist .="username:".$temUser['username']."	user_email:".$temUser['email']."\r\n";
					}else{
						$salt = substr(uniqid(rand()), -6);
						$password = md5($temUser['password'].$salt);
						$ucdb->query("INSERT INTO ".UC_DBTABLEPRE."members SET username='".$temUser['username']."', password='$password', email='".$temUser['email']."', regip='".$temUser['regip']."', regdate='".time()."', salt='".$salt."'");
						$uid = $ucdb->insert_id();
						$ucdb->query("INSERT INTO ".UC_DBTABLEPRE."memberfields SET uid='$uid'");
					}
				}
			}

			$mes="import complate!";
			if(empty($ucuserlist)){
				$ucuserlist=$mes;
			}
			file :: writetofile(HDWIKI_ROOT."/data/hdwiki_user_uc.txt",$ucuserlist);
			file :: writetofile(HDWIKI_ROOT."/data/import_uc.lock",$mes);
			$this->message('导入用户数据成功!',$d_url);
		}else{
			$this->setting['ucopen'] = !empty($this->setting['ucopen']) ? $this->setting['ucopen'] : false;
			$this->view->assign('ucopen',$this->setting['ucopen']);
			$url="./api/ucconfig.inc.php";
			if(!file_exists($url)){
				$iscon=1;
			}else{
				include($url);
				if(UC_OPEN && $this->setting['uc'])
					$isopen=1;
				$url=HDWIKI_ROOT."/data/import_uc.lock";
				if(file_exists($url))
					$islock=1;
				$this->view->assign('uc_api',UC_API);
			}

			$this->setting['feed'] = !empty($this->setting['feed']) ? $this->setting['feed'] : false;
			$isopen = isset($isopen) ? $isopen : 0;
			$islock = isset($islock) ? $islock : 0;
			$feed=unserialize($this->setting['feed']);
			$this->view->assign('create',@in_array('create',$feed));
			$this->view->assign('edit',@in_array('edit',$feed));
			
			$this->view->assign('iscon',$iscon);
			$this->view->assign('isopen',$isopen);
			$this->view->assign('islock',$islock);
			
			$this->view->display("admin_ucenter");
		}
	}

	function doldap(){
		$ldapfile=HDWIKI_ROOT.'/data/ldap.inc.php';
		if (function_exists('ldap_connect')) {
		    $ldap_available = 1;
		} else {
		    $ldap_available = 0;
		}

		if(1 == $ldap_available) {
		if(isset($this->post['ldapsubmit'])){
			$ldapdata="<?php
define('LDAP_OPEN', '".$this->post['ldap_open']."');
define('LDAP_SERVER', '".$this->post['ldap_server']."');
define('LDAP_USER', '".$this->post['ldap_user']."');
define('LDAP_EMAIL', '".$this->post['ldap_email']."');
?>";
			$byte=file::writetofile($ldapfile,$ldapdata);
			if($byte==0){
				$this->message($this->view->lang['passportnotwrite'],'BACK');
			}else{
				$this->message($this->view->lang['passportsucess'],'index.php?admin_setting-ldap');
			}
		}else{
		    $ldap_error = 'LDAP服务未开启！';
			if(file_exists($ldapfile)){
				include($ldapfile);
				if(defined('LDAP_OPEN')){
				   if(1 == LDAP_OPEN) $ldap_error = '';
				    if(isset($this->post['ldaptestsubmit']) && 1 == LDAP_OPEN){
					// 测试LDAP服务
					$test_user = $this->post['test_user'];
					$test_password = $this->post['test_password'];
					if(!empty($test_user) && !empty($test_password)) {
					    $test_user = str_replace('LDAP_USER_NAME', $test_user, LDAP_USER);
					    $connect_id=ldap_connect(LDAP_SERVER);
					    if($connect_id){
						    $bind_id = ldap_bind($connect_id,$test_user,$test_password);
						    if ($bind_id) {
							    $ldap_error = 'LDAP 验证成功,可以正常使用！';
						    } else {
							    $ldap_error = 'LDAP 验证失败！<br /> 服务器地址：'.LDAP_SERVER.'<br /> RDN为：'.$test_user;
						    }
					     }
					}

				    }
					$this->view->assign('ldap_open',LDAP_OPEN);
					$this->view->assign('ldap_server',LDAP_SERVER);
					$this->view->assign('ldap_user',LDAP_USER);
					$this->view->assign('ldap_email',LDAP_EMAIL);
				}
			}
			
		}
		}
		$this->view->assign('ldap_available',$ldap_available);
		$this->view->assign('ldap_error',$ldap_error);
		$this->view->display("admin_ldap");
	}

}
?>