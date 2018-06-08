<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load('user');
		$this->load('usergroup');
	}

 	function doregister(){
		if (!empty($this->user['islogin'])){
			$this->header('');
		}
		$inviter = !empty($this->get[2]) ? $this->authcode($this->get[2], 'DECODE') : false;
		if('0' === $this->setting['reg_status']){
			$this->message($this->setting['close_register_reason'],'',0);
		}
		if('1' === $this->setting['reg_status'] && false != $inviter){
			$this->message($this->view->lang['noInvite'],'index.php?user-register',0);
		}
		if('2' === $this->setting['reg_status'] && false == $inviter){
			$this->message($this->view->lang['onlyInvite'],'',0);
		}

		if(!isset($this->post['submit'])){
			if(isset($this->setting['register_least_minute']) && !$_ENV['user']->check_ip($this->ip)){
				$msg = $this->view->lang['registerTip9'];
				$msg = str_replace('30', $this->setting['register_least_minute'], $msg);
				$this->message($msg,'',0);
			}

			$_ENV['user']->passport_server('register','1');
			$_ENV['user']->passport_client('register');

			if(isset($this->setting['checkcode'])){
				$this->view->assign('checkcode',$this->setting['checkcode']);
			}else{
				$this->view->assign('checkcode',"0");
			}
			if (!isset($this->setting['name_min_length'])) {$this->setting['name_min_length'] = 3;}
			if (!isset($this->setting['name_max_length'])) {$this->setting['name_max_length'] = 15;}
			$this->view->assign('minlength',$this->setting['name_min_length']);
			$this->view->assign('maxlength',$this->setting['name_max_length']);
			$loginTip2 = $this->view->lang['loginTip2'];
			$loginTip2 = str_replace('3',$this->setting['name_min_length'],$loginTip2);
			$loginTip2 = str_replace('15',$this->setting['name_max_length'],$loginTip2);
			$this->view->assign('loginTip2',$loginTip2);
		}else{
			$username=trim($this->post['username']);
			$password=$this->post['password'];
			$repassword=$this->post['repassword'];
			$email=trim($this->post['email']);
			$code=$this->setting['checkcode']!=3?trim($this->post['code']):'';
			$error=$this->docheck($username,$password,$repassword,$email,$code);
			if($error=='OK'){
				//eval($this->plugin["ucenter"]["hooks"]["register"]);
				UC_OPEN&&$_ENV['ucenter']->register($username, $password, $email);
				$uid=$_ENV['user']->add_user($username, md5($password), $email,$this->time,$this->ip);
				if($uid){
					$_ENV['user']->refresh_user($uid);
					$user_credit = $this->setting['credit_register'];
					if($inviter && count($inviter = explode('|', $inviter)) == 2) {
						$user_credit += $this->setting['invitee_credit'];
						$_ENV['user']->add_credit($inviter[1], 'user-register', $this->setting['inviter_credit']);
					}
					$_ENV['user']->add_credit($this->user['uid'],'user-register',$user_credit,$this->setting['coin_register']);
					if('0' !== $this->setting['send_welcome']) {
						$mail_subject = string::haddslashes(str_replace('_USERNAME_', $username, $this->setting['welcome_subject']));
						$mail_content = nl2br($this->setting['welcome_content']);
						$mail_content = str_replace('_USERNAME_', $username, $mail_content);
						$mail_content = str_replace('_SITENAME_', $this->setting['site_name'], $mail_content);
						$mail_content = str_replace('_LINK_', "<a href='".WIKI_URL."'>".WIKI_URL."</a>", $mail_content);
						$mail_content = string::haddslashes(str_replace('_TIME_', $this->date($this->time), $mail_content));
						if('1' === $this->setting['send_welcome']) {
							$this->load('pms');
							$_ENV['pms']->send_sys_message($this->user, $mail_subject, $mail_content);
						} elseif ('2' === $this->setting['send_welcome']) {
							$this->load('mail');
							$_ENV['mail']->add(array($uid), array(), $mail_subject, $mail_content);
						}
					}
					$this->view->assign('user',$this->user);
					$this->view->assign('newpms',$_ENV['global']->newpms($this->user['uid']));
					$_ENV['user']->passport_server('register','2');
					$this->message($this->view->lang['registerSuccess']."<b>$username</b>",'index.php',0);
				}else{
					$this->message($this->view->lang['registerFaild'],'',0);
				}
			}else{
				$this->view->assign('error',$error);
				$this->view->assign('username',$username);
				$this->view->assign('email',$email);
				$this->view->assign('code',$code);
			}
		}

		if($inviter) {
			$this->view->assign('formAction', $this->view->url('user-register-'.$this->get[2]));
		} else {
			$this->view->assign('formAction', $this->view->url('user-register'));
		}
		//$this->view->display('register');
		$_ENV['block']->view('register');
	}

	function dologin(){
		
		$_ENV['user']->passport_server('login','1');
		if(!isset($this->post['submit'])){
			$this->view->assign('checkcode',isset($this->setting['checkcode'])?$this->setting['checkcode']:0);

			$_ENV['user']->add_referer();
			$_ENV['user']->passport_server('login','2');
			$_ENV['user']->passport_client('login');

			if (!isset($this->setting['name_min_length'])) {$this->setting['name_min_length'] = 3;}
			if (!isset($this->setting['name_max_length'])) {$this->setting['name_max_length'] = 15;}
			$loginTip2 = str_replace(array('3','15'),array($this->setting['name_min_length'],$this->setting['name_max_length']),$this->view->lang['loginTip2']);
			$this->view->assign('name_min_length',$this->setting['name_min_length']);
			$this->view->assign('name_max_length',$this->setting['name_max_length']);
			$this->view->assign('loginTip2',$loginTip2);
			//$this->view->display('login');
			$_ENV['block']->view('login');
		}else{
            if (!$this->check_csrf_token()){
                $this->message('缺少TOKEN参数', 'BACK',$this->post['indexlogin']?2:0);
            }
			$username=string::hiconv(trim($this->post['username']));
			$password=md5($this->post['password']);
			$error=$this->setting['checkcode']!=3?$this->docheckcode($this->post['code'],1):'OK';
			if($error=='OK'){
				// LDAP 登录检测
				$ldap_login = $_ENV['user']->ldap_login($username,$this->post['password']);
				if(!empty($ldap_login) && is_array($ldap_login)) {
				    if(1 !== $ldap_login['status']) {
					$this->message($ldap_login['message'], 'BACK',$this->post['indexlogin']?2:0);
				    }
				}
				// LDAP 登录检测 结束

				$user=$this->db->fetch_by_field('user','username',$username);
				if ($this->setting['close_website'] === '1' && $user['groupid'] != 4){
					@header('Content-type: text/html; charset='.WIKI_CHARSET);
					exit($this->setting['close_website_reason']);
				}
				//eval($this->plugin["ucenter"]["hooks"]["login"]);//UC返回登录js代码，退出了。
				UC_OPEN && $_ENV['ucenter']->login($username);
				if(is_array($user)&&($password==$user['password'])){
					if($this->time>($user['lasttime']+24*3600)){
						$user['credit1'] += $this->setting['coin_login'];
						$user['credit2'] += $this->setting['credit_login'];
						$_ENV['user']->add_credit($user['uid'],'user-login',$this->setting['credit_login'], $this->setting['coin_login']);
					}
					$_ENV['user']->update_user($user['uid'],$this->time,$this->ip);
					$_ENV['user']->refresh_user($user['uid']);
					$_ENV['user']->passport_server('login','3',$user);
					$newpms=$_ENV['global']->newpms($this->user['uid']);
					$adminlogin=$this->checkable('admin_main-login');
					if($this->post['indexlogin']==1){
						$usergroup=$this->db->fetch_by_field('usergroup','groupid',$user['groupid'],'grouptitle');
						$user['grouptitle'] = $usergroup['grouptitle'];
						$user['image'] = ($user['image'])?$user['image']:'style/default/user_l.jpg';
						$user['news'] = $newpms[0];
						//公共短消息个数
						$user['pubpms'] = $newpms[3];
						$user['adminlogin'] = $adminlogin;
						unset($user['signature']);
						//channel
						$user['channel'] = '';
						foreach($this->channel as $channel){
							$user['channel'].='<li class="l bor_no"><a href="'.$channel['url'].'" target="_blank">'.$channel['name'].'</a></li>';
						}
						$data='{';
						foreach($user as $key=>$value){
							$data.=$key.":'".$value."',";
						}
						$data=substr($data,0,-1).'}';
						$this->message($data,"",2);
					}else{
						$this->view->assign('user',$this->user);
						$this->view->assign('newpms',$newpms);
						$this->view->assign('adminlogin',$adminlogin);
						$this->message($this->view->lang['loginSuccess'],$_ENV['user']->get_referer() ,0);
					}
				}else{
					$this->message($this->view->lang['userInfoError'],'BACK',$this->post['indexlogin']?2:0);
				}
			}else{
				$this->message($error, 'BACK',$this->post['indexlogin']?2:0);
			}
		}
	}

	function docheck($username,$password,$repassword,$email,$code=''){
		if(($error=$this->docheckusername($username,1))=="OK"){
			if(($error=$_ENV['user']->checkpassword($password,$repassword))=="OK"){
				if(($error=$this->docheckemail($email,1))=="OK"){
					if($code!=''){
						$error=$this->docheckcode($code,1);
					}
				}
			}
		}
		return $error;
	}

	function docheckusername($username='',$type=0){
		$msg='OK';
		$type=isset($this->post['type'])?$this->post['type']:$type;
		if(empty($username)&&($username=string::hiconv(trim($this->post['username'])))==''){
			$msg=$this->view->lang['usernameIsNull'];
		}else{
			if(!preg_match("/^[\w\s\d_\x80-\xff]+$/i", $username) || (!$_ENV['user']->check_name($username) && $type>0)){
				$msg=$this->view->lang['registerTip8'];
			}else{
				//eval($this->plugin["ucenter"]["hooks"]["checkname"]);
				UC_OPEN && $_ENV['ucenter']->checkname($msg,$username,$type);//UC返回验证结果。
				if(!isset($uid)){
					$user=$this->db->fetch_by_field('user','username',$username);
					if(!empty($user)){//有此用户
						if($type>0){//只有当type>0时返回错误，此时表明是注册验证。
							$msg=$this->view->lang['registerTip6'];
						}
					}else{
						if($type==0){//只有当type==0时返回错误，此时表明是登录验证。
							$msg=$this->view->lang['userNotExist'];
						}
					}
				}
			}
		}
		if($type==1){
			return $msg;
		}else{
			$this->message($msg,'',2);
		}
	}

 	function docheckcode($code='',$type=0){
 		return $_ENV['user']->checkcode($code,$type);
	}

	function docheckoldpass(){
		$oldpass=md5($this->post['oldpass']);
		if($oldpass==$this->user['password']){
			$this->message('OK','',2);
		}else{
			$this->message($this->view->lang['oldPassError'],'',2);
		}
	}

 	function docheckemail($email='',$type=0){
 		$msg="OK";
		if($email==''){
			$email=$this->post['email'];
		}
		$lenmax=strlen($email);
		if($lenmax<6){
			$msg=$this->view->lang['getPassTip2'];
		}elseif((bool)$this->db->fetch_by_field('user','email',$email)){
			$msg=$this->view->lang['registerTip7'];
		}else{
			UC_OPEN && $_ENV['ucenter']->checkemail($msg,$email);
		}
		if($type==1){
			return $msg;
		}
		$this->message($msg,'',2);
	}

 	function dologout(){
 		$_ENV['user']->passport_client('logout');
		$synlogout="";
		$_ENV['user']->user_logout();
		$_ENV['user']->passport_server('logout');
		//eval($this->plugin["ucenter"]["hooks"]["logout"]);
		UC_OPEN && $synlogout=$_ENV['ucenter']->logout();
		$this->hsetcookie('style','',0);
		$this->message($this->view->lang['logoutSuccess'].$synlogout, WIKI_URL, 0);
	}

	function doprofile(){
		if($this->user['birthday']){
			$this->user['birthday'] = date('Y-m-d',$this->user['birthday']);
		}else{
			$this->user['birthday']='';
		}
		//eval($this->plugin["ucenter"]["hooks"]["iscredit"]);
		UC_OPEN && $_ENV['ucenter']->iscredit();
		$this->user['editorstar']=$_ENV['usergroup']->get_star($this->user['stars']);
		$this->user['regtime'] = $this->date($this->user['regtime']);
		$creditDetail = $_ENV['user']->get_credit($this->user['uid']);
		$this->user['credits']= $creditDetail['creditTotal'];

		$this->view->assign('creditDetail',$creditDetail);
		$this->view->assign('user',$this->user);
		$this->view->assign('randnum',rand(0,100000));
		//$this->view->display('profile');
		$_ENV['block']->view('profile');
	}

	function doeditprofile(){
		if(isset($this->post['submit'])){
			$gender = intval($this->post['gender']);
			$birthday = strtotime($this->post['birthday']);
			$location = $this->post['location'];
			$signature = $this->post['signature'];
			if (WIKI_CHARSET == 'GBK'){
				$location = string::hiconv($location);
				$signature = string::hiconv($signature);
			}
			$location = htmlspecial_chars($location);
			$signature = htmlspecial_chars(str_replace(array('\n','\r'),'',$signature));

			$_ENV['user']->set_profile($gender,$birthday,$location,$signature,$this->user['uid']);
		}else{
			if(0 == $this->user['birthday']){
				$birthday = '';
			}else{
				$birthday=$this->setting['time_offset']*3600+$this->setting['time_diff']*60+$this->user['birthday'];
				$birthday = date('Y-m-d',$birthday);
			}

			$this->view->assign('birthday',$birthday);
			//$this->view->display('editprofile');
			$_ENV['block']->view('editprofile');
		}
	}

	function doeditpass(){
		if(isset($this->post['submit'])){
			$oldpass = trim($this->post['oldpass']);
			$newpass = trim($this->post['newpass']);
			$renewpass = trim($this->post['renewpass']);
			$oldpass=md5($oldpass);
			if(strlen($newpass)<1){
				$this->message($this->view->lang['editPassTip1'],"BACK",0);
			}
			if($oldpass==$this->user['password']){
				if($newpass==$renewpass){
					//eval($this->plugin["ucenter"]["hooks"]["editpass"]);
					UC_OPEN && $msg=$_ENV['ucenter']->editpass($newpass);
					$_ENV['user']->update_field('password',md5($newpass),$this->user['uid']);
					$_ENV['user']->refresh_user($this->user['uid']);
					$this->view->assign('message',$this->view->lang['viewDocTip1']);
					//$this->view->display('editpass');
					$_ENV['block']->view('editpass');
					exit;
				}else{
					$this->message($this->view->lang['editPassTip3'],'BACK',0);
				}
			}else{
				$this->message($this->view->lang['oldPassError'],'BACK',0);
			}
		}
		if(defined('PP_OPEN')&&PP_OPEN){
			if(PP_TYPE=='client'){
				$this->message('go to modify the password on server','BACK',0);
			}
		}
		//$this->view->display('editpass');
		$_ENV['block']->view('editpass');
	}

	function doeditimageifeam(){
		$imagetype = array(
				'png'=> IMG_PNG,
				'jpg'=> IMG_JPG,
				'jpeg'=> IMG_JPG,
				'gif'=> IMG_GIF
		);
		if(isset($this->post['imageupload'])){
			$image = $_FILES['image'];

			if($image['size'] > 1048576){
				echo "<script language=\"javascript\">alert($this->view->lang['imgToBig'])</script>";
				return false;
			}
			$extname=file::extname($image['name']);
			if (!(imagetypes() & $imagetype[$extname])){
				echo "<script language=\"javascript\">alert('".$extname.$this->view->lang['formateNotExport']."')</script>";
				return false;
			}
			if( util::isimage($extname) ){
				$destfile = 'uploads/userface/'.($this->user['uid']%10).'/'.$this->user['uid'].'_src.'.$extname;	//大图
				$result = file::uploadfile($image,$destfile);
				if($result['result']){
					$destfilerand = $destfile.'?'.rand(0000,9999);
					$imagedrag="<img src='$destfilerand' id='ImageDrag'>";
					$imageicon="<img src='$destfilerand' id='ImageIcon'>";
					$size = getimagesize(HDWIKI_ROOT.'/'.$destfile);
					echo '<script language="javascript">parent.editimage("'.$imagedrag.'","'.$imageicon.'","'.$destfile.'","'.$size[0].'","'.$size[1].'")</script>';
				}
			} else{
				echo "<script language=\"javascript\">alert('".$extname.$this->view->lang['formateNotPermision']."')</script>";
			}
		}
	}

	function doeditimage(){
		//头像显示
		if(empty($this->user['image'])){
			$imageview= 'style/default/user.jpg';
		} else{
			$imagepath = pathinfo($this->user['image']);
			$fileinfo = array();
			$imageinfo = file::getfileinfo($imagepath['dirname'], false, $fileinfo);
			$image = array();
			if(!empty($imageinfo)){
				foreach($imageinfo as $k => $v){
					if(strpos($v['filename'], '_src')){
						if(empty($image)){
							$image = $v;
						} else{
							if($image['filemtime'] < $v['filemtime']){
								$image = $v;
							}
						}
					}
				}
			}
			if(!empty($image)){
				$imageview = $image['dirname']."/".$image['basename'];
			} else{
				$imageview = $this->user['image'];
			}

			if(!file_exists($imageview)){
				$imageview = 'style/default/user.jpg';
			}
		}
		//eval($this->plugin['ucenter']['hooks']['edit_user_image']);
		UC_OPEN && $_ENV['ucenter']->edit_user_image();

		$this->view->assign("imageview", $imageview);
		$this->view->assign("imagesize", getimagesize($imageview));
		//$this->view->display('editimage');
		$_ENV['block']->view('editimage');
	}

	function docutimage(){
		if(empty($this->user['image']) && empty($this->post['uploadflag'])){
			$this->header('user-profile');
			exit();
		}else{
			$imagesrc = $this->post['bigimage'];		//传送过来图片名称
			if(!empty($imagesrc)){
				$imagepath = pathinfo($imagesrc);
				$tagimage = $imagepath['dirname'].'/'.trim($imagepath['filename'], '_src').'.'.strtolower($imagepath['extension']);
				if($_ENV['user']->cutimage($imagesrc, $tagimage, $this->post)){
					$_ENV['user']->update_field("image",$tagimage,$this->user['uid']);
				}
			}
			$this->header('user-profile');

		}
	}

	function dogetpass(){
		if(isset($this->get[2])){
			$uid = is_numeric($this->get[2]) ? $this->get[2] : 0;
			$encryptstring=$this->get[3];
			$idstring=$_ENV['user']->get_idstring_by_uid($uid,$this->time);
			if(!empty($encryptstring) && !empty($idstring) && ($idstring==$encryptstring)){
				$this->view->assign('uid',$uid);
				$this->view->assign('encryptstring',$encryptstring);
				//$this->view->display('resetpass');
				$_ENV['block']->view('resetpass');
			}else{
				$this->message($this->view->lang['resetPassMessage'], WIKI_URL ,0);
			}
		}elseif(isset($this->post['verifystring'])){
			$uid = is_numeric($this->post['uid']) ? $this->post['uid'] : 0;
			$encryptstring=$this->post['verifystring'];
			$idstring=$_ENV['user']->get_idstring_by_uid($uid,$this->time);
			if(!empty($idstring) && !empty($encryptstring) && ($idstring==$encryptstring)){
				$newpass = $this->post['password'];
				$renewpass = $this->post['repassword'];
				$error=$_ENV['user']->checkpassword($newpass,$renewpass);
				if($error=='OK'){
					//eval($this->plugin["ucenter"]["hooks"]["getpass"]);
					UC_OPEN && $msg=$_ENV['ucenter']->getpass($uid,$newpass);
					$_ENV['user']->update_field('password',md5($newpass),$uid);
					$_ENV['user']->update_getpass($uid);
					$this->message($this->view->lang['resetPassSucess'],'index.php?user-login',0);
				}else{
				  $this->message($error,'BACK',0);
				}
			}else{
				$this->message($this->view->lang['resetPassMessage'], WIKI_URL ,0);
			}
		}
		if(!isset($this->post['submit'])){
			if(isset($this->setting['checkcode'])){
				$this->view->assign('checkcode',$this->setting['checkcode']);
			}else{
				$this->view->assign('checkcode',"0");
			}
			//$this->view->display('getpass');
			$_ENV['block']->view('getpass');
			exit;
		}
		$email=$this->post['email'];
		if(isset($this->setting['checkcode'])){
			if($this->setting['checkcode']!=3){
				$code=$this->post['code'];
				$error=$this->docheckcode($code,1);
			}else{
				$error = "OK";
			}
		}else{
			$code=$this->post['code'];
			$error=$this->docheckcode($code,1);
		}
		if("OK" != $error){
			$this->message($error,'BACK',0);
		}
		$user=$this->db->fetch_by_field('user','email',$email);
		if(!(bool)$user){
			$this->message($this->view->lang['noMail'],'BACK',0);
		}else{
			$timetemp=date("Y-m-d H:i:s",$this->time);
			$verification= rand(1000,9999);
			$encryptstring=md5($this->time.$verification);
			$reseturl=WIKI_URL."/index.php?user-getpass-".$user['uid'].'-'.$encryptstring;
			$_ENV['user']->update_getpass($user['uid'],$encryptstring);
			$mail_subject = $this->setting['site_name'].$this->view->lang['getPass'];
			$mail_message = $this->view->lang['resetPassMs1'].$user['username'].$this->view->lang['resetPassMs2'].$timetemp.$this->view->lang['resetPassMs3']."<a href='".$reseturl."' target='_blank'>".$reseturl."</a>".$this->view->lang['resetPassMs4'].$this->setting['site_name'].$this->view->lang['resetPassMs5'].$this->setting['site_name'].$this->view->lang['resetPassMs6'];
			$this->load('mail');
			$_ENV['mail']->add(array(), array($email), $mail_subject, $mail_message, '', 1, 0);
			$this->message($this->view->lang['emailSucess'],'index.php?user-login',0);
		}
	}

	function docode(){
		if(isset($this->setting['checkcode'])){
			$codecase = $this->setting['checkcode'];
		}else{
			$codecase = 0;
		}
 		$code=util::random(4,$codecase);
		if(function_exists("imagecreate")){
			$_ENV['user']->save_code(strtolower($code),$this->user['sid']);
			util::makecode($code);
		}else{
			$_ENV['user']->save_code('abcd',$this->user['sid']);
			header('location:style/default/vdcode.jpg');
		}
	}

	function dospace(){

		$uid = empty($this->get[2]) ? 0 : intval($this->get[2]);
		$type=(isset($this->get[3]))?intval($this->get[3]):0;
		$this->get[4] = empty($this->get[4]) ? NULL : $this->get[4];
		$page = max(1, intval($this->get[4]));

		$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
		$start_limit = ($page - 1) * $num;

		$_ENV['user']->update_field('views',1,$uid,0);
		if($uid == $this->user['uid']){
			$spaceuser = $this->user;
			$spaceuser['views']++;
			$spaceuser['editorstar'] = $_ENV['usergroup']->get_star($spaceuser['stars']);
		}elseif(is_numeric($uid)){
			$spaceuser = $_ENV['usergroup']->get_groupinfo($uid);
		}else{
			$spaceuser = $_ENV['usergroup']->get_groupinfo($uid,'u.username');
			$uid=$spaceuser['uid'];
		}
		if ($page > 1 && $this->isMobile()) {
		    $this->dowapspace(array('uid'=>$uid, 'type'=>$type, 'start_limit'=>$start_limit, 'num'=>$num));
		    exit;
		}
		if(!(bool)$spaceuser){
			$this->message($this->view->lang['loginTip3'],'BACK',0);
		}

		$spaceuser['image'] = $_ENV['global']->uc_api_avatar($spaceuser['image'], $spaceuser['uid'], 'middle');
		$spaceuser['regtime'] = $this->date($spaceuser['regtime']);
		switch ($type) {
			case 0:
			case 1:
				$doccount=($type)? $spaceuser['edits'] : $spaceuser['creates'];
				break;
			case 2:
				$doccount = $_ENV['user']->get_totalfavotire($uid);
		}
		$doclist = $_ENV['user']->space($uid,$type,$start_limit,$num);
		$departstr=$this->multi($doccount, $num, $page,"user-space-$uid-$type");
		$this->view->assign('uid',$this->user['uid']);
		$this->view->assign('departstr',$departstr);
		$this->view->assign('type',$type);
		$this->view->assign('doclist',$doclist);
		$this->view->assign('spaceuser',$spaceuser);

		$this->isMobile() ? $_ENV['block']->view('wap-space') :$_ENV['block']->view('space');
	}
	
	// wap 输出json数据的接口
	function dowapspace($param) {
        exit(json_encode($_ENV['user']->space($param['uid'], $param['type'], $param['start_limit'], $param['num'])));
	}

	function doclearcookies(){
		$cookiepre=$this->setting['cookie_pre']?$this->setting['cookie_pre']:'hd_';
		$lenth=strlen($cookiepre);
		if(is_array($_COOKIE)) {
			foreach ($_COOKIE as $key => $val) {
				if(substr($key,0,$lenth)==$cookiepre){
					$this->hsetcookie(substr($key,$lenth), '',0);
				}
			}
		}
	 	$this->header();
	}

	function doaddfavorite(){
		$result = '';
		$did = isset($this->post['did'])? $this->post['did']:$this->get[2];
		if(is_numeric($did) && !empty($this->user['uid'])) {
			$this->load('doc');
			$doc = $_ENV['doc']->get_doc($did);
			if(!empty($doc['did'])) {
				$param['did'] = $doc['did'];
				$param['title'] = $doc['title'];
				$param['uid'] = $this->user['uid'];
				$flag = $_ENV['user']->add_favorite($param);
				if(0 == $flag) {
					$result = 2;
				} else {
					$result = 1;
				}
			} else {
				$result = 3;
			}
		} else {
			$result = 0;
		}
		echo $result;
	}

	function doremovefavorite(){
		$message = $this->view->lang['checkOneOptions'];
		$uid = $this->user['uid'];
		if(!empty($this->get[2])) {
			$favorite = $this->get[2];
			$_ENV['user']->remove_favorite($favorite, $uid);
			$message = $this->view->lang['removeSuc'];
		}

		$this->message($message,"index.php?user-space-$uid-2",0);
	}

        function doexchange() {
		if (!isset($this->post['exchangesubmit'])) {
			$this->view->assign('user',$this->user);
			$outextcredits=unserialize($this->setting["outextcredits"]);
			$option=array();
			if(!empty($outextcredits)) {
				foreach($outextcredits as $key=>$change){
					$option[$change[creditsrc]][$key]=$change;
				}
			}
			$this->view->assign('outextcredits',$outextcredits);
			$this->view->assign('option',$option);
			//$this->view->display('exchange');
			$_ENV['block']->view('exchange');
		} else {
			$netamount = $tocredits = 0;
			$outextcredits=unserialize($this->setting["outextcredits"]);
			$fromcredit = $this->post['fromcredit'];
			$tocredit = $this->post['tocredit_'.$fromcredit];
			$outexange = $outextcredits[$tocredit];
			if(!$outexange || !$outexange['ratio']) {
				$this->message('credits_exchange_invalid!','BACK',0);
			}
			$amount = intval($this->post['amount']);
			$credit1=$credit2=0;
			if($outexange[creditsrc]==0){
				$credits='credit2';
				$credit2=-$amount;
			}else{
				$credits='credit1';
				$credit1=-$amount;
			}


			if($amount <= 0 || $amount >$this->user[$credits]) {
				$this->message('积分提交无效！','BACK',0);
			}

			require_once(HDWIKI_ROOT."/api/ucconfig.inc.php");
			require_once(HDWIKI_ROOT."/api/uc_client/client.php");

			$ucresult = uc_user_login($this->user['username'], $this->post['password']);
			list($tmp['uid']) = $ucresult;
			if($tmp['uid'] <= 0) {
				$this->message('输入的密码错误！','BACK',0);
			}
			$netamount = floor($amount * 1/$outexange['ratio']);

			$ucresult = uc_credit_exchange_request($this->user['uid'], $outexange['creditsrc'], $outexange['creditdesc'], $outexange['appiddesc'], $netamount);
			if(!$ucresult) {
				$this->message('积分转换失败！','BACK',0);
			}

			$_ENV['user']->add_credit($this->user['uid'],'syn_credit',$credit2,$credit1);
			$this->message('积分兑换成功!',$this->setting['seo_prefix']."user-profile".$this->setting['seo_suffix'],0);
		}
	}

	function doinvite() {
		if('1' === $this->setting['reg_status']) {
			$this->message($this->view->lang['inviteClosed'], 'BACK', 0);
		}
		$invite_code = $this->authcode($this->user['username'].'|'.$this->user['uid'], 'ENCODE');
		$invite_url = WIKI_URL.'/'.$this->view->url('user-register-'.$invite_code);
		$mailtpl = nl2br($this->setting['invite_content']);
		$mailtpl = str_replace('_USERNAME_', $this->user['username'], $mailtpl);
		$mailtpl = str_replace('_SITENAME_', $this->setting['site_name'], $mailtpl);
		$mailtpl = str_replace('_LINK_', "<a href='{$invite_url}'>{$invite_url}</a>", $mailtpl);
		if(isset($this->post['submit'])) {
			$error = false;
			$this->post['toemails'] = explode(",", $this->post['toemails']);
			$mail_count = 0;
			$mails = array();
			foreach($this->post['toemails'] as $key=>$mail) {
				if($mail_count > 50) {
					break;
				}
				$mail = trim($mail);
				if(strlen($mail) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $mail)) {
					$mails[$key] = $mail;
					$mail_count++;
				}
			}
			if(empty($mails)) {
				$error = true;
				$this->view->assign('mail_error', $this->view->lang['emptyEmail']);
			}
			if(!empty($this->post['ps']) && strlen($this->post['ps']) > 1000) {
				$error = true;
				$this->view->assign('ps_error', sprintf($this->view->lang['psTooLong'], strlen($this->post['ps'])));
			}
			if(!$error) {
				$subject = str_replace('_USERNAME_', $this->user['username'], $this->setting['invite_subject']);
				$mails = array_unique($mails);
				$this->load('mail');
				$content = str_replace('_PS_', nl2br($this->post['ps']), addslashes($mailtpl));
				$_ENV['mail']->add(array(), $mails, $subject, $content);
				$this->message($this->view->lang['emailSent'],'BACK', 0);
			} else {
				$this->view->assign('toemails', implode(",", $mails));
				$this->view->assign('ps', stripslashes($this->post['ps']));
			}
		}
		$preview = str_replace('_PS_', '<span id="PreContainer"></span>', $mailtpl);
		$this->view->assign('preview', $preview);
		$this->view->assign('invete_credit',$this->setting['inviter_credit']);
		$this->view->assign('invite_url',$invite_url);
		//$this->view->display('invite');
		$_ENV['block']->view('invite');
	}
}
?>