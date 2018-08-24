<?php
include ("admin.php");
checkadminisdo("wangkan");
$page = isset($_POST['page'])?$_POST['page']:1;//只从修改页传来的值
checkid($page);
$id = isset($_POST['id'])?$_POST['id']:0;
checkid($id,1);
checkid($bigclassid,1);
$passed = isset($_POST['passed'])?$_POST['passed']:0;
checkid($passed,1);

$img=getimgincontent(stripfxg($content,true));
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

if ($_REQUEST["action"]=="add"){
query("INSERT INTO zzcms_wangkan (bigclassid,title,content,img,passed,elite,sendtime)VALUES('$bigclassid','$title','$content','$img','$passed','$elite','".date('Y-m-d H:i:s')."')");
}elseif ($_REQUEST["action"]=="modify") {
query("update zzcms_wangkan set bigclassid='$bigclassid',title='$title',content='$content',img='$img',passed='$passed',elite='$elite',sendtime='".date('Y-m-d H:i:s')."' where id='$id'");	
}
echo  "<script>location.href='wangkan_manage.php?page=".$page."'</script>";
?>