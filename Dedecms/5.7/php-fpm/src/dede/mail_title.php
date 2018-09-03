<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/datalistcp.class.php");
CheckPurview('plus_Mail');
if(!isset($dopost)) $dopost = '';
if($dopost=="add"){
	$dsql->SetQuery("SELECT id,typename FROM `#@__mail_type` ORDER BY id");
  $dsql->Execute();
	$btypes = Array();
	while($row = $dsql->GetArray())
	{
	  $btypes[$row['id']] = $row['typename'];
	}
	require_once(DEDEADMIN."/templets/mail_title_add.htm");	
}elseif($dopost=="edit"){
	$dsql->SetQuery("SELECT id,typename FROM `#@__mail_type` ORDER BY id");
  $dsql->Execute();
	$btypes = Array();
	while($row = $dsql->GetArray())
	{
	  $btypes[$row['id']] = $row['typename'];
	}
  $row=$dsql->GetOne("SELECT * FROM `#@__mail_title` WHERE id=$id");
  require_once(DEDEADMIN."/templets/mail_title_edit.htm");	
}elseif($dopost=="addsave"){
	if(!preg_match('/[0-9]/',$period)){
		ShowMsg("期刊号只能为数字!","-1");
		exit();
	}
	if($typeid=="0"){
		ShowMsg("请选择类别!","-1");
		exit();
	}
	if($title==''){
		ShowMsg("请填写信息标题!","-1");
		exit();
	}
	if($message==''){
		ShowMsg("请填写内容!","-1");
		exit();
	}
	$message = stripslashes($message);
  $pattern="/\\".$cfg_medias_dir."/";
	$message =preg_replace($pattern,$cfg_basehost.$cfg_medias_dir,$message);
	$title = cn_substrR(HtmlReplace($title,1),60);
	$addtime=$sendtime = time();
	$writer= $cuserLogin->getUserName();
	$mid=$cuserLogin->getUserID();
	$query = "INSERT INTO #@__mail_title (period,typeid,title,content,addtime,sendtime,writer,mid,state,count) VALUES ('$period','$typeid','$title','$message','$addtime',0,'$writer','$mid',0,0)";
	if(!$dsql->ExecuteNoneQuery($query)){
		ShowMsg("更新数据库#@__mail_title表时出错，请检查！","javascript:;");
	  exit();
	}else{
    ShowMsg("发表期刊成功！","mail_title.php");
		exit();
	}
}elseif($dopost=="editsave"){
	if(!preg_match('/[0-9]/',$period)){
		ShowMsg("期刊号只能为数字!","-1");
		exit();
	}
	if($title==''){
		ShowMsg("请填写信息标题!","-1");
		exit();
	}
	if($message==''){
		ShowMsg("请填写内容!","-1");
		exit();
	}
	$message = stripslashes($message);
  $pattern="/\\".$cfg_medias_dir."/";
	$message =preg_replace($pattern,$cfg_basehost.$cfg_medias_dir,$message);
	$title = cn_substrR(HtmlReplace($title,1),60);
	$writer= $cuserLogin->getUserName();
	$mid=$cuserLogin->getUserID();

	$query = "UPDATE #@__mail_title SET period='$period',typeid='$typeid',title='$title',content='$message',writer='$writer',mid='$mid' WHERE id=$id";
	if(!$dsql->ExecuteNoneQuery($query)){
		ShowMsg("更新数据库#@__mail_title表时出错，请检查！","javascript:;");
	  exit();
	}else{
    ShowMsg("编辑期刊成功！","mail_title.php");
		exit();
	}
}elseif($dopost=="delete"){
  $dsql->ExecuteNoneQuery("Delete From `#@__mail_title` where id='$id'");
  ShowMsg("删除期刊成功！","mail_title.php");
	exit();
}else{
	
	function GetSendTimeMk($mktime){
		if($mktime=="0") return "未发送";
		else return MyDate('Y-m-d H:i:s',$mktime);
	}
	
	$sql  = "SELECT t.*,p.typename FROM `#@__mail_title` AS t LEFT JOIN `#@__mail_type` AS p ON t.typeid=p.id ORDER BY t.id desc";
	$dlist = new DataListCP();
	$dlist->SetTemplet(DEDEADMIN."/templets/mail_title_main.htm");
	$dlist->SetSource($sql);
	$dlist->display();
}

?>