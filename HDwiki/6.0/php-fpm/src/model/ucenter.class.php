<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class ucentermodel {

	var $db;
	var $base;

	function ucentermodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function avatar(){
		$this->base->user["image"]=UC_API."/avatar.php?uid=".$this->base->user["uid"]."&size=middle";
	}
	
	function register($username, $password, $email){
		$uid = uc_user_register($username,$password,$email);
		if ($uid <= 0) {
			if($uid == -1) {
				$msg= "用户名不合法";
			} elseif($uid == -2) {
				$msg= "包含不允许注册的词语";
			} elseif($uid == -3) {
				$msg= "用户名已经存在";
			} elseif($uid == -4) {
				$msg= "Email 格式有误";
			} elseif($uid == -5) {
				$msg= "Email 不允许注册";
			} elseif($uid == -6) {
				$msg= "该 Email 已经被注册";
			} else {
				$msg= "异常错误，未定义";
			}
			$this->base->message($msg,"BACK",0);
		} else {
			$result=$_ENV["user"]->add_user($username, md5($password), $email,$this->base->time,$this->base->ip,2,$uid);
			$_ENV["user"]->refresh_user($result);
			$_ENV["user"]->add_credit($this->base->user["uid"],"user-register",$this->base->setting["credit_register"]);
			$synlogin=uc_user_synlogin($uid);
			$this->base->message("恭喜 <b>$username</b> 注册成功！".$synlogin,"index.php",0);
		}
	}
	
	function login($username){
		list($uid, $username, $password, $email) = uc_user_login($username, $this->base->post["password"]);
		if ($uid > 0) {
			$msg = "OK";
			$synlogin=uc_user_synlogin($uid);
		} elseif ($uid == -1) {
			$msg= "用户不存在,或者已经被删除";
		} elseif ($uid == -2) {
			$msg= "密码错误";
		} elseif ($uid == -3) {
			$msg= "安全提问错误";
		} else {
			$msg= "不明错误，可能是连接UC服务端失败了。";
		}
		if ($msg != "OK") {
			if ($this->base->post["submit"] == "ajax") {
				exit($msg);
			} else {
				$this->base->message($msg,"BACK",0);
			}
		} else {
			$user = $_ENV["user"]->get_user("username",$username);
			if(is_array($user) && $user["uid"]!=$uid){//有这个用户名，并且uid不相符。
				if($usernum=$this->db->result_first("select count(*) from ".DB_TABLEPRE."user where uid= $uid")){
					$maxuid=$this->db->result_first("select max(uid) from ".DB_TABLEPRE."user");
					$maxuid+=1;
					$this->db->query("update ".DB_TABLEPRE."user set uid=$maxuid where uid=$uid");
					$this->update_field($uid,$maxuid);
				}
				$_ENV["user"]->update_field("uid",$uid,$user["uid"],1);
				$this->update_field($user["uid"],$uid);
				$user["uid"]=$uid;
			}elseif(!is_array($user)){//没有这个用户名。
				if($usernum=$this->db->result_first("select count(*) from ".DB_TABLEPRE."user where uid= $uid")){
					$maxuid=$this->db->result_first("select max(uid) from ".DB_TABLEPRE."user");
					$maxuid+=1;
					$this->db->query("update ".DB_TABLEPRE."user set uid=$maxuid where uid=$uid");
					$this->update_field($uid,$maxuid);
				}
				$_ENV["user"]->add_user($username, md5($password), $email,$this->base->time,$this->base->ip,2,$uid);
				$_ENV["user"]->add_credit($uid,"user-register",$this->base->setting["credit_register"]);
				$user = $_ENV["user"]->get_user("username",$username);
			}else{//有这个用户名，uid也正确。
				$lasttime=$user["lasttime"];
				if($this->base->time>($lasttime+24*3600)){
					$_ENV["user"]->add_credit($user["uid"],"user-login",$this->base->setting["credit_login"]);
				}
				//修改用户的资料。
				$_ENV["user"]->edit_user($user["uid"],$password,$email,$user["groupid"]);
				$_ENV["user"]->update_user($user["uid"],$this->base->time,$this->base->ip);
			}
			
			
			$_ENV["user"]->refresh_user($user["uid"]);
			$this->base->view->assign("adminlogin",$this->base->checkable("admin_main-login"));
			if ($this->base->post["submit"] == "ajax") {
				echo $synlogin;
				exit;
			} else {
				$this->base->message("登录成功".$synlogin,"index.php",0);
			}
		}
	}
	
	function checkname(&$msg,$username,$type){
		$uid = uc_user_checkname($username);
		if($uid <= 0) {
			if ($uid == -1) {
				$msg= "用户名不合法";
			} elseif ($uid == -2 || $uid == -3) {
				if ($type>0) {
					$msg = $uid == -3?"用户名已经存在":"包含不允许注册的词语";
				} elseif ($type==0) {
					$msg=  "OK";
				}
			} 
		} else {
			if ($type>0) {
				$msg= "OK";
			} elseif ($type==0) {
				$msg= "用户名不存在";
			}
		}	
		
	}
	
	function checkemail(&$msg,$email){
		$ucresult = uc_user_checkemail($email);
		if($ucresult == -4) {
			$msg= "Email 格式有误";
		} elseif($ucresult == -5) {
			$msg="Email 不允许注册";
		} elseif($ucresult == -6) {
			$msg="该 Email 已经被注册";
		} 
	}
	
	function logout(){
		return uc_user_synlogout();
	}
	
	function iscredit(){
		$outextcredits=unserialize($this->base->setting["outextcredits"]);
		if((bool)$outextcredits){
			$this->base->view->assign("iscredit",true);
		}
	}
	
	function editpass($newpass){
		$userarr = $_ENV["user"]->get_user("uid",$this->base->user["uid"]);
		$username=$userarr["username"];
		$ucresult = uc_user_edit($username,$this->base->post["oldpass"],$newpass);
		if ($ucresult == -1) {
			$this->base->message("旧密码不正确","BACK",0);
		} elseif ($ucresult == 1) {
			$msg = "OK";
		} else {
			$this->base->message("某些原因出错了，没做任何修改","BACK",0);
		}
		return $msg;
	}
	
	function edit_user_image(){
		$image_html=uc_avatar($this->base->user["uid"]);
		if(uc_check_avatar($this->base->user["uid"])){
			$uid_image=UC_API."/avatar.php?uid=".$this->base->user["uid"]."&size=middle";
			$this->base->view->assign("uid_image",$uid_image);
		}
		$this->base->view->assign("image_html",$image_html);
	}
	
	function getpass($uid,$newpass){
		$userarr = $_ENV["user"]->get_user("uid",$uid);
		$username=$userarr["username"];
		$ucresult = uc_user_edit($username,"",$newpass,"",1);
		if($ucresult == -1) {
			$this->base->message("旧密码不正确","BACK",0);
		} elseif($ucresult == 1) {
			$msg = "OK";
		}else{
			$this->base->message("某些原因出错了，没做任何修改","BACK",0);
		}
		return $msg;
	}						
	
	function doc_user_image(&$editors,$doc){
		if(uc_check_avatar($editors[$doc["author"]][uid])){
			$editors[$doc["author"]]["image"]=UC_API."/avatar.php?uid=".$editors[$doc["author"]][uid]."&size=small";
		}
		if(uc_check_avatar($editors[$doc["lasteditor"]][uid])){
			$editors[$doc["lasteditor"]]["image"]=UC_API."/avatar.php?uid=".$editors[$doc["lasteditor"]][uid]."&size=small";
		}
	}
	
	function create_feed($doc,$did){
		$isimg=util::getfirstimg($doc[content]);
		if(false!==strpos($isimg,"http://")){
			$img=empty($isimg)?"":$isimg;
		}else{
			$img=empty($isimg)?"":WIKI_URL."/".$isimg;
		}
		$doc[did]=$did;
		$feed=unserialize($this->base->setting[feed]);
		$uid=$this->base->user[uid];
		if(@in_array("create",$feed)){
			$feed = array();
			$feed["icon"] = "post";
			$feed["type"] = "create";
			$feed["title_data"] = array(
				"title" => "<a href=\\\"".WIKI_URL."/{$this->base->setting[seo_prefix]}doc-view-$doc[did]{$this->base->setting[seo_suffix]}\\\">$doc[title]</a>",
				"author" => "<a href=\\\"space.php?uid={$uid}\\\">$this->user[username]</a>",
				"app"=>$this->base->setting["site_name"]
			);
			$feed["body_data"] = array(
				"subject"=> "<a href=\\\"".WIKI_URL."/{$this->base->setting[seo_prefix]}doc-view-$doc[did]{$this->base->setting[seo_suffix]}\\\">$doc[title]</a>",
				"message"=> $doc["summary"]
			);
			$feed["images"][]= array("url" => "{$img}", "link" => WIKI_URL."/{$this->base->setting[seo_prefix]}doc-view-$doc[did]{$this->base->setting[seo_suffix]}");
			$this->postfeed($feed);
		}
	
	}
	
	function edit_feed($doc){
		$isimg=util::getfirstimg($doc[content]);
		if(false!==strpos($isimg,"http://")){
			$img=empty($isimg)?"":$isimg;
		}else{
			$img=empty($isimg)?"":WIKI_URL."/".$isimg;
		}
		$feed=unserialize($this->base->setting[feed]);
		$uid=$this->base->user[uid];
		if(@in_array("edit",$feed)){
			$feed = array();
			$feed["icon"] = "post";
			$feed["type"] = "edit";
			$feed["title_data"] = array(
				"title" => "<a href=\\\"".WIKI_URL."/{$this->base->setting[seo_prefix]}doc-view-$doc[did]{$this->base->setting[seo_suffix]}\\\">$doc[title]</a>",
				"author" => "<a href=\\\"space.php?uid={$uid}\\\">{$this->base->user['username']}</a>",
				"app"=>$this->base->setting["site_name"]
			);
			$feed["body_data"] = array(
				"subject"=> "<a href=\\\"".WIKI_URL."/{$this->base->setting[seo_prefix]}doc-view-$doc[did]{$this->base->setting[seo_suffix]}\\\">$doc[title]</a>",
				"message"=> $doc["summary"]
			);
			$feed["images"][]= array("url" => "{$img}", "link" => WIKI_URL."/{$this->base->setting[seo_prefix]}doc-view-$doc[did]{$this->base->setting[seo_suffix]}");
			$this->postfeed($feed);
		}
	
	}
	
	function admin_register(){
		$username=$this->base->post["username"];
		$password=$this->base->post["password"];
		$email=$this->base->post["email"];
		$uid = uc_user_register($username,$password,$email);
		if($uid <= 0) {
			if($uid == -1) {
				$msg= "用户名不合法";
			} elseif($uid == -2) {
				$msg= "包含要允许注册的词语";
			} elseif($uid == -3) {
				$msg= "用户名已经存在";
			} elseif($uid == -4) {
				$msg= "Email 格式有误";
			} elseif($uid == -5) {
				$msg= "Email 不允许注册";
			} elseif($uid == -6) {
				$msg= "该 Email 已经被注册";
			} else {
				$msg= "未定义";
			}
			$this->base->message($msg,"BACK",0);
		}	
	}
	
	function edituser($uid){
		$userarr = $_ENV["user"]->get_user("uid",$uid);
		$username=$userarr["username"];
		$newpass=$this->base->post["password"];
		$email=$this->base->post["email"];
		$ismail=$userarr["email"]!=$email?1:0;
		if((bool)$newpass && $ismail){
			$ucresult = uc_user_edit($username,"",$newpass,$email,1);
		}elseif((bool)$newpass && !$ismail){
			$ucresult = uc_user_edit($username,"",$newpass,"",1);
		}elseif($ismail){
			$ucresult = uc_user_edit($username,"","",$email,1);
		}else{
			$ucresult = 1;
		}
		if($ucresult == 1) {
			$msg= "OK";
		} elseif($ucresult == 0) {
			$msg= "新旧资料相同，操作完成。";
		} elseif($ucresult == -4) {
			$msg= "Email 格式有误";
		} elseif($ucresult == -5) {
			$msg= "Email 不允许注册";
		} elseif($ucresult == -6) {
			$msg= "该 Email 已经被注册";
		}else {
			$msg= "UCenter更新失败";
		}
		if($msg != "OK"){
			$this->base->message($msg,"BACK");
		}
	}
	
	function delete(){
		foreach($this->base->post["uid"] as $uid){
		$userarr = $_ENV["user"]->get_user("uid",$uid);
		$username=$userarr["username"];
			uc_user_delete($username);
		}
	}
	
	
	//积分的控制，输入用户名和积分，发送到ucenter。
	function send_credit($uid,$credit,$outextcredits){
		if((bool)$outextcredits){
			foreach($outextcredits as $outextcredit){
				$credit=intval($credit/$outextcredit["ratio"]);
				uc_credit_exchange_request($uid, $outextcredit["creditsrc"] , $outextcredit["creditdesc"] , $outextcredit["appiddesc"] , $credit);
				return $uid;
			}
		}
	}

	function postfeed($feed) {
		$feed['title_template'] = $feed['type']=='create' ? '<b>{actor} 在 {app} 创建了新词条</b>':'<b>{actor} 在 {app} 编辑了词条</b>';
		$feed['body_template'] = '<b>{subject}</b><br />{message}';
		uc_feed_add($feed['icon'], $this->base->user['uid'], $this->base->user['username'], $feed['title_template'], $feed['title_data'], $feed['body_template'], $feed['body_data'], '', '', $feed['images']);
	}

	//这个方法是在uid同步的时候使用
	function update_field($uid,$newuid) {
		$this->db->query("UPDATE ".DB_TABLEPRE."activation SET uid='$newuid' WHERE uid='$uid'");
		$this->db->query("UPDATE ".DB_TABLEPRE."attachment SET uid='$newuid' WHERE uid='$uid'");
		$this->db->query("UPDATE ".DB_TABLEPRE."creditdetail SET uid='$newuid' WHERE uid='$uid'");
		$this->db->query("UPDATE ".DB_TABLEPRE."doc SET authorid='$newuid' WHERE authorid='$uid'");
		$this->db->query("UPDATE ".DB_TABLEPRE."edition SET authorid='$newuid' WHERE authorid='$uid'");
		$this->db->query("UPDATE ".DB_TABLEPRE."comment SET authorid='$newuid' WHERE authorid='$uid'");
	}
	
}
?>
