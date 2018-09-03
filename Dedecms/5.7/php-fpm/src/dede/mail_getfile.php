<?php 
require_once(dirname(__FILE__)."/config.php");
CheckPurview('plus_Mail');
if(!isset($dopost)){
	$dopost = '';
}
if($dopost=="save"){
	$start = empty($start)? 1 : intval(preg_replace("/[\d]/",'', $start));
	$end = empty($end)? 0 : intval(preg_replace("/[\d]/",'', $end));
	if(!preg_match("/^[0-9a-z_]+$/i",$filename)){
		 ShowMsg("请填写正确的文件名!","-1");
	   exit();
	}
	if($end!="0") $wheresql="where mid between $start and $end";
	else $wheresql="";
	
	$sql="SELECT email FROM  #@__member $wheresql";
	$db->Execute('me',$sql);
	while($row = $db->GetArray()){
		$mails[]=$row;
	}
	$email="";
	foreach($mails as $mail){
		$email.=$mail['email'].",";
	}
	
	$m_file = DEDEDATA."/mail/".$filename.".txt";
	
	if (file_exists($m_file)) {
    ShowMsg("该文件已经存在，重新换个文件名!","-1");
	  exit();
	} else {
    $fp = fopen($m_file,'w');
		flock($fp,3);
		fwrite($fp,$email);
		fclose($fp);
		ShowMsg("获取邮件列表成功!","-1");
		exit();
	}
}
require_once(DEDEADMIN."/templets/mail_getfile.htm");
?>