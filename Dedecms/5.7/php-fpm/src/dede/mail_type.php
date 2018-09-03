<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/datalistcp.class.php");
CheckPurview('plus_Mail');
if(!isset($dopost)) $dopost = '';
$id = (empty($id) ? 0 : intval($id));
if($dopost=="add"){
	require_once(DEDEADMIN."/templets/mail_type_add.htm");	
}elseif($dopost=="edit"){
  $row=$dsql->GetOne("SELECT * FROM `#@__mail_title` WHERE id=$id");
  require_once(DEDEADMIN."/templets/mail_title_edit.htm");	
}elseif($dopost=="addsave"){
	if($typename==""){
		ShowMsg("分类名不能为空","-1");
		exit();
  }
	$typename=Html2Text($typename,1);
	$description = Html2Text($description,1);
	
	$query = "INSERT INTO #@__mail_type (typename,description) VALUES ('$typename','$description')";
	if(!$dsql->ExecuteNoneQuery($query)){
		ShowMsg("更新数据库#@__mail_type表时出错，请检查！","javascript:;");
	  exit();
	}else{
    ShowMsg("添加分类成功！","mail_type.php");
		exit();
	}
}elseif($dopost=="editsave"){
	if($typename==""){
		ShowMsg("分类名不能为空","-1");
		exit();
  }
	$typename=Html2Text($typename,1);
	$description = Html2Text($description,1);
	
	$query = "UPDATE #@__mail_type SET typename='$typename',description='$description' WHERE id=$id";
	if(!$dsql->ExecuteNoneQuery($query)){
		ShowMsg("更新数据库#@__mail_type表时出错，请检查！","javascript:;");
	  exit();
	}else{
    ShowMsg("更改分类成功！","mail_type.php");
		exit();
	}
}elseif($dopost=="delete"){
  $dsql->ExecuteNoneQuery("Delete From `#@__mail_type` where id='$id'");
  ShowMsg("删除分类成功！","mail_type.php");
	exit();
}else{
	$sql  = "SELECT * FROM `#@__mail_type` ORDER BY id ";
	$dlist = new DataListCP();
	$dlist->SetTemplet(DEDEADMIN."/templets/mail_type_main.htm");
	$dlist->SetSource($sql);
	$dlist->display();
}
?>