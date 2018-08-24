<?php
include ("admin.php");
checkadminisdo("zh");

$page = isset($_POST['page'])?$_POST['page']:1;//只从修改页传来的值
checkid($page);
$id = isset($_POST['id'])?$_POST['id']:0;
checkid($id,1);
checkid($bigclassid,1);
$passed = isset($_POST['passed'])?$_POST['passed']:0;
checkid($passed,1);

if (isset($_POST["elite"])){
$elite=$_POST["elite"];
	if ($elite>255){
	$elite=255;
	}elseif ($elite<0){
	$elite=0;
	}
}else{
$elite=0;
}
checkid($elite,1);

if ($_REQUEST["action"]=="add" && $_SESSION["admin"]<>''){
query("INSERT INTO zzcms_zh (bigclassid,title,address,timestart,timeend,content,passed,elite,sendtime)VALUES('$bigclassid','$title','$address','$timestart','$timeend','$content','$passed','$elite','".date('Y-m-d H:i:s')."')");
}elseif ($_REQUEST["action"]=="modify") {
query("update zzcms_zh set bigclassid='$bigclassid',title='$title',address='$address',timestart='$timestart',timeend='$timeend',content='$content',passed='$passed',elite='$elite',sendtime='".date('Y-m-d H:i:s')."' where id='$id'");	
}
echo  "<script>location.href='zh_manage.php?page=".$page."'</script>";
?>