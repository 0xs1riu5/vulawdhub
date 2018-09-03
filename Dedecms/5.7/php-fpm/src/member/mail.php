<?php
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
$menutype = 'mydede';
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
$dopost = isset($dopost) ? trim($dopost) : '';
$folder = isset($folder) ? trim($folder) : '';
$mid = $cfg_ml->M_ID;
if($dopost == '')
{
	if($cfg_mb_spacesta!="-10"){
		if($cfg_checkemail=='Y'){
			$row=$dsql->GetOne("SELECT email,checkmail FROM `#@__member` WHERE mid=$mid");
			if($row['checkmail']=="-1"){
				 $msg="邮件订阅需要您先进行邮箱验证！</br><a href='mail.php?dopost=sendmail'>点击进行验证</a>";
			   ShowMsg($msg,'-1');
		     exit();
			}
		}
	}
	$db->SetQuery("SELECT typeid FROM `#@__mail_order` WHERE mid=$mid");	
	$db->Execute();
	$typeid="";
	while($row = $db->GetArray())
	{
	   $typeid.=$row['typeid'].",";
	}
	if($folder=="drop"){
		$dsql->SetQuery("SELECT t.*,o.mid FROM `#@__mail_type` AS t LEFT JOIN `#@__mail_order` AS o ON t.id=o.typeid WHERE mid=$mid ORDER BY t.id asc");
	  $dsql->Execute();
	  while($arr = $dsql->GetArray())
	  {
	  	$rows[]=$arr;
	  }
	  $rows=empty($rows)? "" : $rows;
	  $tpl = new DedeTemplate();
	  $tpl->LoadTemplate(DEDEMEMBER.'/templets/mail_drop.htm');
	  $tpl->Display();
	}else{
		$typeid=explode(",",$typeid);
	  $dsql->SetQuery("SELECT * FROM `#@__mail_type` ORDER BY id asc");
	  $dsql->Execute();
	  $inputbox="";
	  while($row = $dsql->GetObject())
	  {
	  	if(in_array($row->id,$typeid)){
        $inputbox.="<li><input type='checkbox' name='mailtype[]' id='{$row->id}' value='{$row->id}' class='np' checked/> <label>{$row->typename}</label></li>\r\n";
    	}else{
    		$inputbox.="<li><input type='checkbox' name='mailtype[]' id='{$row->id}' value='{$row->id}' class='np' /> <label>{$row->typename}</label></li>\r\n";
	  	}
	  }
	  $tpl = new DedeTemplate();
	  $tpl->LoadTemplate(DEDEMEMBER.'/templets/mail.htm');
	  $tpl->Display();
	} 
}elseif($dopost == 'save' || $dopost == 'drop'){
	$mailtype=empty($mailtype)? "" : $mailtype;
	$dsql->ExecuteNoneQuery("DELETE FROM #@__mail_order WHERE mid=$mid");
	if($dopost == 'save' && $mailtype==""){
		ShowMsg("请选择订阅类型！",'mail.php');
	  exit();
	}	
	if($dopost=="save") $msg="订阅成功！";
	elseif($dopost=="drop") $msg="退订成功！";
	if(is_array($mailtype)){
		foreach($mailtype as $type){
				$dsql->ExecuteNoneQuery("INSERT INTO #@__mail_order(`typeid` , `mid`)VALUES ('$type', '$mid')");
		}
	}	
	ShowMsg($msg,'mail.php');
	exit();
}elseif($dopost=='sendmail'){
	$userhash = md5($cfg_cookie_encode.'--'.$cfg_ml->fields['mid'].'--'.$cfg_ml->fields['email']);
  $url = $cfg_basehost.(empty($cfg_cmspath) ? '/' : $cfg_cmspath)."/member/mail.php?dopost=checkmail&mid={$cfg_ml->fields['mid']}&userhash={$userhash}&do=1";
  $url = eregi_replace('http://', '', $url);
  $url = 'http://'.eregi_replace('//', '/', $url);
  $mailtitle = "{$cfg_webname}--会员邮件验证通知";
  $mailbody = '';
  $mailbody .= "尊敬的用户[{$cfg_ml->fields['uname']}]，您好：\r\n";
  $mailbody .= "欢迎使用邮件订阅功能。\r\n";
  $mailbody .= "要通过验证，请点击或复制下面链接到地址栏访问这地址：\r\n\r\n";
  $mailbody .= "{$url}\r\n\r\n";
 
	if($cfg_sendmail_bysmtp == 'Y' && !empty($cfg_smtp_server))
	{		
		$mailtype = 'TXT';
		require_once(DEDEINC.'/mail.class.php');
		$smtp = new smtp($cfg_smtp_server,$cfg_smtp_port,true,$cfg_smtp_usermail,$cfg_smtp_password);
		$smtp->debug = false;
		if(!$smtp->smtp_sockopen($cfg_smtp_server)){
		  ShowMsg('邮件发送失败,请联系管理员','index.php');
	    exit();
		}
		$smtp->sendmail($cfg_ml->fields['email'], $cfg_webname,$cfg_smtp_usermail, $mailtitle, $mailbody, $mailtype);
	}else{
		@mail($cfg_ml->fields['email'], $mailtitle, $mailbody);
	}
	if(empty($cfg_smtp_server)){
		ShowMsg('邮件发送失败,请联系管理员','index.php');
	  exit();
	}else{
		ShowMsg('成功发送邮件，请登录你的邮箱进行接收！', 'index.php');
		exit();	
	}
}else if($dopost=='checkmail'){
	$mid = intval($mid);
	if(empty($mid))
	{
		ShowMsg('你的效验串不合法！', '-1');
		exit();
	}
	$row = $dsql->GetOne("Select * From `#@__member` where mid='{$mid}' ");
	$needUserhash = md5($cfg_cookie_encode.'--'.$mid.'--'.$row['email']);
	if($needUserhash != $userhash)
	{
		ShowMsg('你的效验串不合法！', '-1');
		exit();
	}
	$dsql->ExecuteNoneQuery("Update `#@__member` set checkmail=0 where mid='{$mid}' ");
	ShowMsg('操作成功,欢迎使用邮件订阅！', 'mail.php');
	exit();
}
?>
